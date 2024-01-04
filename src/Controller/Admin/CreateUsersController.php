<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Model\Table\ContactsTable;
use App\Model\Table\MailFormsTable;
use App\Model\Table\ProfilesTable;
use App\Model\Table\SitesTable;
use App\Model\Table\UsersTable;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Event\EventInterface;
use Cake\Http\Response;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

/**
 * CreateUsers Controller
 *
 * @property UsersTable $Users
 * @property ProfilesTable $Profiles
 * @property SitesTable $Sites
 * @property MailFormsTable $MailForms
 * @property ContactsTable $Contacts
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
        $this->MailForms = TableRegistry::getTableLocator()->get('MailForms');
        $this->Contacts = TableRegistry::getTableLocator()->get('Contacts');

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

            // バリデーション処理
            if ($this->create_validate($user, $data)) {
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
     * ユーザー作成バリデーション
     * 
     * @param UsersTable $entity
     * @param array $data
     * 
     * @return bool
     */
    private function create_validate($entity, $data): bool
    {
        $error_count = 0;
        if ($data['username'] == '') {
            $entity->setError('username', ['ユーザー名が入力されていません。']);
            $error_count++;
        } elseif (!empty($this->Users->find('all', ['conditions' => ['username ' => $data['username']]])->toArray())) {
            $entity->setError('username', ['このユーザー名は使用できません。']);
            $error_count++;
        } elseif ($data['username'] == "admin") {
            $entity->setError('username', ['このユーザー名は使用できません。']);
            $error_count++;
        } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $data['username'])) {
            $entity->setError('username', ['ユーザー名は半角英数字のみで入力してください。']);
            $error_count++;
        }

        if ($data['password'] == '') {
            $entity->setError('password', ['パスワードが入力されていません。']);
            $error_count++;
        } elseif (mb_strlen($data['password']) < 8) {
            $entity->setError('password', ['パスワードは8文字以上で入力してください。']);
            $error_count++;
        } elseif ($data['password'] != $data['re_password']) {
            $entity->setError('password', ['パスワードが一致しません。']);
            $error_count++;
        } elseif (!preg_match("/^[ -~]+$/", $data['password'])) {
            $entity->setError('password', ['パスワードは半角記号英数字のみで入力してください。']);
            $error_count++;
        }

        return $error_count !== 0;
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

            // メールフォームテーブル作成処理
            if (!$this->createMailForms()) {
                return $this->redirect('/');
            }

            // お問い合わせフォームテーブル作成処理
            if (!$this->createContact()) {
                return $this->redirect('/');
            }

            // 実績画像保存ディレクトリ作成処理
            $this->createWork();

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
     * 
     * @return bool
     * 
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
     * 
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
            $this->_createDir(WWW_ROOT . 'img/users/profiles/' . $this->AuthUser->username);

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
     * 
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
            $this->_createDir(WWW_ROOT . 'img/users/sites/favicons/' . $this->AuthUser->username);

            // ヘッダー画像保存用ディレクトリ作成
            $this->_createDir(WWW_ROOT . 'img/users/sites/headers/' . $this->AuthUser->username);

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
     * 実績画像保存ディレクトリ作成
     * 
     * @return void
     */
    private function createWork()
    {
        // 実績画像保存用ディレクトリ作成
        $path = WWW_ROOT . 'img/users/works/' . $this->AuthUser->username;
        if (file_exists($path)) {
            foreach (glob($path . '/*') as $dir) {
                array_map('unlink', glob($dir . '/*.*'));
                rmdir($dir);
            }
            rmdir($path);
        }
        mkdir($path);
    }

    /**
     * メールフォームテーブル作成
     * 
     * @return bool
     * 
     * @throws DatabaseException
     */
    private function createMailForms()
    {
        // トランザクション用の変数用意
        $connection = $this->MailForms->getConnection();

        $data = ['user_id' => $this->AuthUser->id];

        try {

            // トランザクション開始
            $connection->begin();

            $mailForms = $this->MailForms->newEmptyEntity();
            $mailForms = $this->MailForms->patchEntity($mailForms, $data);
            if ($mailForms->getErrors()) {
                return $this->redirect('/');
            }

            // 登録処理
            $ret = $this->MailForms->save($mailForms);
            if (!$ret) {
                throw new DatabaseException(UsersTable::INVALID_CREATE_USER);
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
     * お問い合わせテーブル作成
     * 
     * @return bool
     * 
     * @throws DatabaseException
     */
    private function createContact()
    {
        // 実績画像保存用ディレクトリ作成
        $path = WWW_ROOT . 'img/users/contacts/' . $this->AuthUser->username;
        if (file_exists($path)) {
            foreach (glob($path . '/*') as $dir) {
                array_map('unlink', glob($dir . '/*.*'));
                rmdir($dir);
            }
            rmdir($path);
        }
        mkdir($path);

        // お問い合わせテーブルLINE作成
        if (!$this->CreateContactLine($path)) {
            return false;
        }

        // お問い合わせテーブルその他作成
        if (!$this->CreateContactOther($path)) {
            return false;
        }

        return true;
    }

    /**
     * お問い合わせテーブルLINE作成
     * 
     * @param string $path 画像保存パス
     * 
     * @return bool
     * 
     * @throws DatabaseException
     */
    private function CreateContactLine($path)
    {
        // トランザクション用の変数用意
        $connection = $this->Contacts->getConnection();

        $data = [
            'title' => 'LINE',
            'overview' => 'LINEお問い合わせについての説明',
            'url_name' => 'LINEリンク',
            'url_path' => Router::url('/'),
            'image_path' => 'line_qr.jpg',
            'contacts_order' => 1,
            'user_id' => $this->AuthUser->id
        ];

        try {

            // トランザクション開始
            $connection->begin();

            $contacts = $this->Contacts->newEmptyEntity();
            $contacts = $this->Contacts->patchEntity($contacts, $data);
            if ($contacts->getErrors()) {
                return false;
            }

            // 登録処理
            $ret = $this->Contacts->save($contacts);
            if (!$ret) {
                throw new DatabaseException(UsersTable::INVALID_CREATE_USER);
            }

            // コミット
            $connection->commit();
        } catch (DatabaseException $e) {

            // ロールバック
            $connection->rollback();
            $this->session->write('message', $e);
            return false;
        }

        $this->_createDir($path . '/' . $ret->id);
        copy(WWW_ROOT . 'img/contact/line_qr.jpg', $path . '/' . $ret->id . '/line_qr.jpg');

        return true;
    }

    /**
     * お問い合わせテーブルその他作成
     * 
     * @param string $path 画像保存パス
     * 
     * @return bool
     * 
     * @throws DatabaseException
     */
    private function CreateContactOther($path)
    {

        // トランザクション用の変数用意
        $connection = $this->Contacts->getConnection();

        $data = [
            'title' => 'その他お問い合わせ',
            'overview' => 'メルマガ等、お問い合わせを設定してください',
            'url_name' => 'お問い合わせリンク',
            'url_path' => Router::url('/'),
            'image_path' => 'contact_img.jpg',
            'contacts_order' => 2,
            'user_id' => $this->AuthUser->id
        ];

        try {

            // トランザクション開始
            $connection->begin();

            $contacts = $this->Contacts->newEmptyEntity();
            $contacts = $this->Contacts->patchEntity($contacts, $data);
            if ($contacts->getErrors()) {
                return false;
            }

            // 登録処理
            $ret = $this->Contacts->save($contacts);
            if (!$ret) {
                throw new DatabaseException(UsersTable::INVALID_CREATE_USER);
            }

            // コミット
            $connection->commit();
        } catch (DatabaseException $e) {

            // ロールバック
            $connection->rollback();
            $this->session->write('message', $e);
            return false;
        }

        $this->_createDir($path . '/' . $ret->id);
        copy(WWW_ROOT . 'img/contact/contact_img.jpg', $path . '/' . $ret->id . '/contact_img.jpg');

        return true;
    }

    /**
     * ディレクトリ作成
     * 
     * @param string $path
     * @return void 
     */
    private function _createDir($path)
    {
        if (file_exists($path)) {
            array_map('unlink', glob($path . '/*.*'));
            rmdir($path);
        }
        mkdir($path);
    }

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
