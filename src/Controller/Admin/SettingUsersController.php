<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Model\Table\UsersTable;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Core\Configure;
use Cake\Http\Response;
use Cake\ORM\TableRegistry;

/**
 * SettingUsers Controller
 * 
 * @property UsersTable $Users
 *
 * @method \App\Model\Entity\SettingUser[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SettingUsersController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();

        // 使用するモデル
        $this->Users = TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * ユーザー設定画面
     *
     * @return void
     */
    public function index()
    {
    }

    /**
     * パスワード変更
     * 
     * @return Response|null|void
     */
    public function changePassword()
    {
        // GETは通さない
        if (!$this->request->is(['patch', 'post', 'put'])) {
            return $this->redirect(['action' => 'index']);
        }

        $user = null;
        $error_message = null;

        // リクエストあるか確認
        $data = $this->request->getData();
        if (!empty($data)) {

            // ログイン情報からid取得
            $user = $this->Users->find('all', ['conditions' => ['id' => $this->AuthUser->id]])->first();

            /**
             * パスワードチェック
             */
            $hasher = new DefaultPasswordHasher();
            if (!$hasher->check($data['password'], $user->password)) {
                $this->session->write('message', 'パスワードが違います');
                $user = $this->Users->patchEntity($user, $data);
                $user->setError('password', ['パスワードが違います']);
                $this->set('user', $user);
                $this->set('error_message', $error_message);
                return;
            }

            /**
             * パスワードのバリデーション
             */
            if ($data['new_password'] == '') {
                $error_message = 'パスワードが入力されていません。';
            } elseif (mb_strlen($data['new_password']) < 8) {
                $error_message = 'パスワードは8文字以上で入力してください。';
            } elseif ($data['new_password'] != $data['re_password']) {
                $error_message = 'パスワードが一致しません。';
            } elseif (!preg_match("/^[ -~]+$/", $data['new_password'])) {
                $error_message = 'パスワードは半角記号英数字のみで入力してください。';
            } elseif (mb_strlen($data['new_password']) > 100) {
                $error_message = 'パスワードは100字以内で入力してください。';
            }
            // バリデーションがあれば通る
            if (!is_null($error_message)) {
                $this->session->write('message', Configure::read('alert_message.input_faild'));
                $this->set('user', $user);
                $this->set('error_message', $error_message);
                return;
            }

            // 更新データセット
            $user = $this->Users->patchEntity($user, ['password' => $this->_setPassword($data['new_password'])]);

            // トランザクション用の変数
            $connection = $this->Users->getConnection();

            try {

                // トランザクション開始
                $connection->begin();

                // 排他制御
                $this->Users
                    ->find('all', ['conditions' => ['id' => $this->AuthUser->id]])
                    ->modifier('SQL_NO_CACHE')
                    ->epilog('FOR UPDATE')
                    ->first();

                $ret = $this->Users->save($user);
                if (!$ret) {
                    throw new DatabaseException;
                }

                // コミット
                $connection->commit();
            } catch (DatabaseException $e) {

                // ロールバック
                $connection->rollback();
                $this->session->write('message', $e);
                return false;
            }

            // パスワード変更後TOPへリダイレクト
            $this->session->write('message', 'パスワードが変更されました。');
            return $this->redirect('/admin');
        }

        // viewに渡すデータセット
        $this->set('user', $user);
        $this->set('error_message', $error_message);
    }

    /**********************************
     * Private Method
     **********************************/

    /**
     * パスワード暗号化
     * 
     * @param string $password
     * @return ?string 
     */
    private function _setPassword(string $password): ?string
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher())->hash($password);
        }
    }
}
