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
    /**
     * session key
     */
    // ユーザー一時保存用
    const DATA_CREATE_USER = 'create_user_data';

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
     * @return Response|void|null
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
                $user->setError('password', [UsersTable::PASSWORD_MISMATCH]);
                $user->setError('re_password', [UsersTable::PASSWORD_MISMATCH]);
            }

            // バリデーション処理
            if ($user->getErrors()) {
                $this->set('user', $user);
                return;
            }

            // セッションにデータ保持し確認画面へ遷移
            $this->session->write(self::DATA_CREATE_USER, $data);
            return $this->redirect(['action' => 'confirm']);
        }

        // viewに渡すデータセット
        $this->set('user', $user);
    }

    /**
     * 確認画面
     * 
     * @return Response|void|null
     */
    public function confirm()
    {

        // セッションデータが無ければリダイレクト
        if (!$this->session->check(self::DATA_CREATE_USER)) {
            return $this->redirect('/');
        }

        // セッションからデータ取得
        $session_data = $this->session->read(self::DATA_CREATE_USER);

        if ($this->request->is(['patch', 'post', 'put'])) {
            // postの場合

            // セッションデータ削除
            $this->session->delete(self::DATA_CREATE_USER);

            // ユーザーテーブル更新、認証処理
            if (!$this->createUser($session_data)) {
                return $this->redirect('/');
            }

            // プロフィールテーブル作成処理
            if (!$this->createProfile()) {
                return $this->redirect('/');
            }

            // サイトテーブル作成処理
            if (!$this->createSite()) {
                return $this->redirect('/');
            }

            // ユーザー作成に使用したセッションデータ削除
            $this->session->delete(self::DATA_CREATE_USER);

            // 完了画面へリダイレクト
            return $this->redirect(['action' => 'complete']);
        }

        // viewに渡すデータセット
        $this->set('user', $session_data);
    }

    /**
     * ユーザー作成完了
     * 
     * @return Response|void|null
     */
    public function complete()
    {
        // viewに渡すデータセット
        $this->set('user', $this->AuthUser);
    }

    /**
     * ユーザーテーブル更新、認証済にする
     * 
     * @param array $data
     * @return bool
     * @throws DatabaseException
     */
    private function createUser($data)
    {

        // ログイン情報からid取得
        $user = $this->Users->find('all', ['conditions' => ['id' => $this->AuthUser->id]])->first();

        try {

            // トランザクション開始
            $this->connection->begin();

            // 排他制御
            $this->Users
                ->find('all', ['conditions' => ['id' => $this->AuthUser->id]])
                ->modifier('SQL_NO_CACHE')
                ->epilog('FOR UPDATE')
                ->first();

            // 登録処理
            $user = $this->Users->patchEntity($user, [
                'username' => $data['username'],
                'password' => $this->_setPassword($data['password']),
                'autherized_flg' => 1
            ]);
            $ret = $this->Users->save($user);
            if (!$ret) {
                throw new DatabaseException(UsersTable::INVALID_CREATE_USER);
            }

            // コミット
            $this->connection->commit();
        } catch (DatabaseException $e) {

            // ロールバック
            $this->connection->rollback();
            $this->session->write('message', $e);
            return false;
        }

        return true;
    }

    /**
     * プロフィールテーブル作成
     * 
     * @return bool
     * @throws DatabaseException
     */
    private function createProfile()
    {
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
                throw new DatabaseException(UsersTable::INVALID_CREATE_USER);
            }

            // 登録処理
            $ret = $this->Profiles->save($profiles);
            if (!$ret) {
                throw new DatabaseException(UsersTable::INVALID_CREATE_USER);
            }

            // ユーザープロフィール画像保存用ディレクトリ作成
            $mkdir = mkdir(WWW_ROOT . 'img/users/profiles/' . $this->AuthUser->username);
            if (!$mkdir) {
                throw new DatabaseException(UsersTable::INVALID_CREATE_DIR);
            }

            // コミット
            $connection->commit();
        } catch (DatabaseException $e) {

            // ロールバック
            $connection->rollback();
            $this->session->write('message', $e);
            return false;
        }

        return true;
    }

    /**
     * サイトテーブル作成
     * 
     * @return bool
     * @throws DatabaseException
     */
    private function createSite()
    {
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
                throw new DatabaseException(UsersTable::INVALID_CREATE_USER);
            }

            // ファビコン画像保存用ディレクトリ作成
            $mkdir = mkdir(WWW_ROOT . 'img/users/sites/favicons/' . $this->AuthUser->username);
            if (!$mkdir) {
                throw new DatabaseException(UsersTable::INVALID_CREATE_DIR);
            }

            // ヘッダー画像保存用ディレクトリ作成
            $mkdir = mkdir(WWW_ROOT . 'img/users/sites/headers/' . $this->AuthUser->username);
            if (!$mkdir) {
                throw new DatabaseException(UsersTable::INVALID_CREATE_DIR);
            }

            // コミット
            $connection->commit();
        } catch (DatabaseException $e) {

            // ロールバック
            $connection->rollback();
            $this->session->write('message', $e);
            return false;
        }

        return true;
    }

    /**
     * パスワード暗号化
     * 
     * @param string
     * @return ?string 
     */
    private function _setPassword(string $password): ?string
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher())->hash($password);
        }
    }
}
