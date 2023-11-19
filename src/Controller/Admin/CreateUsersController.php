<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;

/**
 * CreateUsers Controller
 *
 * @method \App\Model\Entity\CreateUser[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CreateUsersController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        // 使用するモデル
        $this->Users = TableRegistry::getTableLocator()->get('Users');
        $this->Profiles = TableRegistry::getTableLocator()->get('Profiles');

        // トランザクション用の変数
        $this->connection = $this->Users->getConnection();
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        // ユーザー作成済であればアクセスさせない
        if ($this->AuthUser->autherized_flg == 1) {
            return $this->redirect('/admin');
        }
    }

    public function create()
    {
        $this->viewBuilder()->disableAutoLayout();

        $user = $this->Users->newEmptyEntity();

        if ($this->request->is(['patch', 'post', 'put'])) {

            $data = $this->request->getData();

            // バリデーション処理
            $user = $this->Users->patchEntity($user, $data);

            if ($data['password'] != $data['re_password']) {
                $user->setError('password', ['入力されたパスワードと一致しません。']);
                $user->setError('re_password', ['入力されたパスワードと一致しません。']);
            }

            if ($user->getErrors()) {
                $this->set('user', $user);

                return;
            }

            $this->session->write('create_user_data', $data);
            return $this->redirect(['action' => 'confirm']);
        }

        $this->set('user', $user);
    }

    public function confirm()
    {

        $this->viewBuilder()->disableAutoLayout();

        if (!$this->session->check('create_user_data')) {
            return $this->redirect('/');
        }

        $session_data = $this->session->read('create_user_data');

        if ($this->request->is(['patch', 'post', 'put'])) {

            $data = [
                'username' => $session_data['username'],
                'password' => $this->_setPassword($session_data['password']),
                'autherized_flg' => 1
            ];

            try {

                // トランザクション開始
                $this->connection->begin();

                // 排他制御
                $this->Users
                    ->find('all', ['conditions' => ['id' => $this->AuthUser->id]])
                    ->modifier('SQL_NO_CACHE')
                    ->epilog('FOR UPDATE')
                    ->first();

                $user = $this->Users->find('all', ['conditions' => ['id' => $this->AuthUser->id]])->first();
                $user = $this->Users->patchEntity($user, $data);

                // 登録処理
                $ret = $this->Users->save($user);
                if (!$ret) {
                    throw new DatabaseException('ユーザーの作成に失敗しました。');
                }

                // コミット
                $this->connection->commit();
            } catch (DatabaseException $e) {

                // ロールバック
                $this->connection->rollback();
                $this->session->write('message', $e);
                return $this->redirect('/');
            }

            // トランザクション用の変数用意
            $connection = $this->Profiles->getConnection();

            $data = [
                'view_name' => $user->username,
                'user_id' => $this->AuthUser->id
            ];

            try {

                // トランザクション開始
                $connection->begin();

                $profiles = $this->Profiles->newEmptyEntity();
                $profiles = $this->Profiles->patchEntity($profiles, $data);
                if ($user->getErrors()) {
                    return $this->redirect('/');
                }

                // 登録処理
                $ret = $this->Profiles->save($profiles);
                if (!$ret) {
                    throw new DatabaseException('ユーザーの作成に失敗しました。');
                }

                // ユーザープロフィール画像保存用ディレクトリ作成
                $mkdir = mkdir(WWW_ROOT . 'img/users/profiles/' . $user->username);
                if (!$mkdir) {
                    throw new DatabaseException('ディレクトリ作成失敗');
                }

                // コミット
                $connection->commit();
            } catch (DatabaseException $e) {

                // ロールバック
                $connection->rollback();
                $this->session->write('message', $e);
                return $this->redirect('/');
            }

            $this->session->delete('create_user_data');
            return $this->redirect(['action' => 'complete']);
        }

        $this->set('user', $session_data);
    }

    public function complete()
    {
        $this->viewBuilder()->disableAutoLayout();

        $user = $this->Users->find('all', ['conditions' => ['id' => $this->AuthUser->id]])->first();

        $this->set('user', $user);
    }

    protected function _setPassword(string $password): ?string
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher())->hash($password);
        }
    }
}
