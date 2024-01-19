<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Model\Table\ContactsTable;
use App\Model\Table\DesignsTable;
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
 * @property DesignsTable $Designs
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
        $this->Designs = TableRegistry::getTableLocator()->get('Designs');
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

        if ($this->session->check(self::DATA_CREATE_USER)) {
            $username = $this->session->read(self::DATA_CREATE_USER)['username'];
            $this->session->delete(self::DATA_CREATE_USER);
        } else {
            $username = null;
        }

        // viewに渡すデータセット
        $this->set('user', $user);
        $this->set('username', $username);
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
            if (!$this->updateUser($session_data)) {
                return $this->redirect('/');
            }

            // デフォルトデータセット
            $this->setDefaultData();

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

    /**********************************
     * Private Method
     **********************************/

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
     * ユーザーテーブル更新、認証済にする
     * 
     * @param array $data
     * 
     * @return bool
     * 
     * @throws DatabaseException
     */
    private function updateUser($data)
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
     * デフォルトデータ設定
     * 
     * @return void
     */
    private function setDefaultData()
    {
        $username = $this->AuthUser->username;

        /**
         * Profiles
         */
        // ユーザープロフィール画像保存用ディレクトリ作成
        $this->_createSingleDir(WWW_ROOT . 'img/users/profiles/' . $username);

        // プロフィールテーブル作成処理
        if (!$this->_defaultInsert(['view_name' => $username], $this->Profiles)) {
            return $this->redirect('/');
        }

        /**
         * Sites
         */
        // ファビコン画像保存用ディレクトリ作成
        $this->_createSingleDir(WWW_ROOT . 'img/users/favicon/' . $username);

        // サイトテーブル作成処理
        if (!$this->_defaultInsert(['site_title' => $username], $this->Sites)) {
            return $this->redirect('/');
        }

        /**
         * Designs
         */
        // TOP画像保存用ディレクトリ作成
        $fv_pc_image_path = WWW_ROOT . 'img/users/fv_pc/' . $username;
        $this->_createSingleDir($fv_pc_image_path);
        copy(WWW_ROOT . 'img/fv_default/default1.jpg', $fv_pc_image_path . '/default1.jpg');

        // TOP画像（モバイルサイズ）保存用ディレクトリ作成
        $this->_createSingleDir(WWW_ROOT . 'img/users/fv_sp/' . $username);

        //デザインテーブル
        if (!$this->_defaultInsert(['fv_image_path' => 'default1.jpg'], $this->Designs)) {
            return $this->redirect('/');
        }

        /**
         * Works
         */
        // 実績画像保存ディレクトリ作成処理
        $this->_createMultiDir(WWW_ROOT . 'img/users/works/' . $username);

        /**
         * MailForms
         */
        // メールフォームテーブル作成処理
        if (!$this->_defaultInsert([], $this->MailForms)) {
            return $this->redirect('/');
        }

        /**
         * Contacts
         */
        // お問い合わせ画像保存ディレクトリ作成処理
        $contact_dir_path = WWW_ROOT . 'img/users/contacts/' . $username;
        $this->_createMultiDir($contact_dir_path);

        // お問い合わせテーブルLINE作成
        $data = [
            'title' => 'LINE',
            'overview' => 'LINEお問い合わせについての説明',
            'url_name' => 'LINEリンク',
            'url_path' => Router::url('/'),
            'image_path' => 'line_qr.jpg',
            'contacts_order' => 1
        ];
        $ret = $this->_defaultInsert($data, $this->Contacts);
        if (!$ret) {
            return $this->redirect('/');
        }
        $this->_createSingleDir($contact_dir_path . '/' . $ret->id);
        copy(WWW_ROOT . 'img/contact/line_qr.jpg', $contact_dir_path . '/' . $ret->id . '/line_qr.jpg');

        // お問い合わせテーブルその他作成
        $data = [
            'title' => 'その他お問い合わせ',
            'overview' => 'メルマガ等、お問い合わせを設定してください',
            'url_name' => 'お問い合わせリンク',
            'url_path' => Router::url('/'),
            'image_path' => 'contact_img.jpg',
            'contacts_order' => 2
        ];
        $ret = $this->_defaultInsert($data, $this->Contacts);
        if (!$ret) {
            return $this->redirect('/');
        }
        $this->_createSingleDir($contact_dir_path . '/' . $ret->id);
        copy(WWW_ROOT . 'img/contact/contact_img.jpg', $contact_dir_path . '/' . $ret->id . '/contact_img.jpg');
    }

    /**
     * テーブル作成
     * 
     * @param array $data
     * @param object $entity
     * 
     * @return object|bool
     * 
     * @throws DatabaseException
     */
    private function _defaultInsert($data, $entity)
    {
        // トランザクション用の変数用意
        $connection = $entity->getConnection();

        try {

            // トランザクション開始
            $connection->begin();

            $set_entity = $entity->patchEntity($entity->newEmptyEntity(), array_merge($data, ['user_id' => $this->AuthUser->id]));
            if ($set_entity->getErrors()) {
                return $this->redirect('/');
            }

            // 登録処理
            $ret = $entity->save($set_entity);
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

        return $ret;
    }

    /**
     * 単一ディレクトリ作成
     * 
     * @param string $path
     * 
     * @return void 
     */
    private function _createSingleDir($path)
    {
        if (file_exists($path)) {
            array_map('unlink', glob($path . '/*.*'));
            rmdir($path);
        }
        mkdir($path);
    }

    /**
     * 複数ディレクトリ作成
     * 
     * @param string $path
     * 
     * @return void 
     */
    private function _createMultiDir($path)
    {
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
