<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Model\Table\ProfilesTable;
use App\Model\Table\SitesTable;
use App\Model\Table\UsersTable;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Event\EventInterface;
use Cake\Http\Response;
use Cake\ORM\TableRegistry;

/**
 * CreateUsers Controller
 *
 * @property UsersTable $Users
 * @property ProfilesTable $Profiles
 * @property SitesTable $Sites
 */
class CreateUsersController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();

        // ユーザー作成用のテンプレート読み込み
        $this->viewBuilder()->setLayout('CreateUsers');

        // 使用するモデル
        $this->Users = TableRegistry::getTableLocator()->get('Users');
        $this->Profiles = TableRegistry::getTableLocator()->get('Profiles');
        $this->Sites = TableRegistry::getTableLocator()->get('Sites');

        // トランザクション用の変数
        $this->connection = $this->Users->getConnection();
    }

    /**
     * @param EventInterface $event
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        // ユーザー作成済であればアクセスさせない
        if ($this->AuthUser->autherized_flg == 1) {
            return $this->redirect('/admin');
        }
    }

    /**
     * ユーザー名作成画面
     * 
     * @return Response|void
     */
    public function create()
    {
        // 空のエンティティ作成
        $user = $this->Users->newEmptyEntity();

        if ($this->request->is(['patch', 'post', 'put'])) {
            // postの場合

            // requestデータ取得
            $data = $this->request->getData();

            // エンティティにデータセット
            $user = $this->Users->patchEntity($this->AuthUser, $data);

            // パスワード再入力チェック
            if ($data['password'] != $data['re_password']) {
                $user->setError('password', ['入力されたパスワードと一致しません。']);
                $user->setError('re_password', ['入力されたパスワードと一致しません。']);
            }

            // バリデーション処理
            if ($user->getErrors()) {
                $this->set('user', $user);
                return;
            }

            // セッションにデータ保持し確認画面へ遷移
            $this->session->write('create_user_data', $data);
            return $this->redirect(['action' => 'confirm']);
        }

        // viewに渡すデータセット
        $this->set('user', $user);
    }

    /**
     * 確認画面
     * 
     * @return Response|void
     */
    public function confirm()
    {

        // セッションデータが無ければリダイレクト
        if (!$this->session->check('create_user_data')) {
            return $this->redirect('/');
        }

        // セッションからデータ取得
        $session_data = $this->session->read('create_user_data');
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            // postの場合

            // セッションデータ削除
            $this->session->delete('create_user_data');

            // 保存に必要なデータセット
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

                // idからデータ取得
                $user = $this->Users->find('all', ['conditions' => ['id' => $this->AuthUser->id]])->first();

                // 登録処理
                $user = $this->Users->patchEntity($user, $data);
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
                'view_name' => $this->AuthUser->username,
                'user_id' => $this->AuthUser->id
            ];

            try {

                // トランザクション開始
                $connection->begin();

                $profiles = $this->Profiles->newEmptyEntity();
                $profiles = $this->Profiles->patchEntity($profiles, $data);
                if ($profiles->getErrors()) {
                    return $this->redirect('/');
                }

                // 登録処理
                $ret = $this->Profiles->save($profiles);
                if (!$ret) {
                    throw new DatabaseException('ユーザーの作成に失敗しました。');
                }

                // ユーザープロフィール画像保存用ディレクトリ作成
                $mkdir = mkdir(WWW_ROOT . 'img/users/profiles/' . $this->AuthUser->username);
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

            // トランザクション用の変数用意
            $connection = $this->Sites->getConnection();

            $data = [
                'site_title' => $this->AuthUser->username,
                'user_id' => $this->AuthUser->id
            ];

            try {

                // トランザクション開始
                $connection->begin();

                $sites = $this->Sites->newEmptyEntity();
                $sites = $this->Sites->patchEntity($sites, $data);
                if ($sites->getErrors()) {
                    return $this->redirect('/');
                }

                // 登録処理
                $ret = $this->Sites->save($sites);
                if (!$ret) {
                    throw new DatabaseException('ユーザーの作成に失敗しました。');
                }

                // ファビコン画像保存用ディレクトリ作成
                $mkdir = mkdir(WWW_ROOT . 'img/users/sites/favicons/' . $this->AuthUser->username);
                if (!$mkdir) {
                    throw new DatabaseException('ディレクトリ作成失敗');
                }

                // ヘッダー画像保存用ディレクトリ作成
                $mkdir = mkdir(WWW_ROOT . 'img/users/sites/headers/' . $this->AuthUser->username);
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

    /**
     * ユーザー作成完了
     */
    public function complete()
    {
        $this->set('user', $this->AuthUser);
    }

    /**
     * パスワード暗号化
     */
    protected function _setPassword(string $password): ?string
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher())->hash($password);
        }
    }
}
