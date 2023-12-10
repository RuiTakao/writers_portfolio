<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Model\Table\ProfilesTable;
use App\Model\Table\SitesTable;
use Cake\Database\Exception\DatabaseException;
use Cake\Http\Response;
use Cake\ORM\TableRegistry;

/**
 * Sites Controller
 *
 * @property SitesTable $Sites
 * @property ProfilesTable $Profiles
 */
class SitesController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();

        // 利用するモデル
        $this->Sites = TableRegistry::getTableLocator()->get('Sites');
        $this->Profiles = TableRegistry::getTableLocator()->get('Profiles');

        // トランザクション変数
        $this->connection = $this->Sites->getConnection();
    }

    /**
     * サイト管理画面
     * 
     * @return Response|void|null
     */
    public function index()
    {
        // ログインidからデータ取得
        $site = $this->Sites->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->first();

        // viewに渡すデータセット
        $this->set('site', $site);
    }

    /**
     * サイト編集画面
     * 
     * @return Response|void|null
     * @throws DatabaseException
     */
    public function edit()
    {
        // ログインidからデータ取得
        $site = $this->Sites->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->first();

        if ($this->request->is(['patch', 'post', 'put'])) {
            // postの場合

            // requestデータ取得
            $data = $this->request->getData();

            // エンティティにデータセット
            $site = $this->Sites->patchEntity($site, $data);

            // バリデーション処理
            if ($site->getErrors()) {
                $this->session->write('message', SitesTable::INVALID_INPUT_MESSEGE);
                $this->set('site', $site);
                return;
            }

            try {

                // トランザクション開始
                $this->connection->begin();

                // 排他制御
                $this->Sites
                    ->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])
                    ->modifier('SQL_NO_CACHE')
                    ->epilog('FOR UPDATE')
                    ->first();

                // 登録処理
                $ret = $this->Sites->save($site);
                if (!$ret) {
                    throw new DatabaseException();
                }

                // コミット
                $this->connection->commit();
            } catch (DatabaseException $e) {

                // ロールバック
                $this->connection->rollback();
                $this->session->write('message', SitesTable::INVALID_MESSAGE);
                return $this->redirect(['action' => 'index']);
            }

            // 完了画面へリダイレクト
            $this->session->write('message', SitesTable::SUCCESS_MESSAGE);
            return $this->redirect(['action' => 'index']);
        }

        // viewに渡すデータセット
        $this->set('site', $site);
    }

    /**
     * ファビコン編集画面
     * 
     * @return Response|void|null
     * @throws DatabaseException
     */
    public function editFaviconImage()
    {
        // ログインidからデータ取得
        $site = $this->Sites->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->first();

        if ($this->request->is(['patch', 'post', 'put'])) {
            // postの場合

            // requestデータ取得
            $data = $this->request->getData();

            if ($data['favicon_path']->getClientFilename() == '' || $data['favicon_path']->getClientMediaType() == '') {

                // アップロードされていなければ処理せず変更完了
                $this->session->write('message', SitesTable::SUCCESS_FAVICON_MESSAGE);
                return $this->redirect(['action' => 'index']);
            }

            // 画像データを変数に格納
            $image = $data['favicon_path'];

            // 画像名をリクエストデータに代入
            $data['favicon_path'] = $data['favicon_path']->getClientFilename();

            // バリデーション
            if (!in_array(pathinfo($data['favicon_path'])['extension'], SitesTable::FAVICON_EXTENTIONS)) {
                $site->setError('favicon_path', [SitesTable::INVALID_EXTENSION_MESSAGE]);
                $this->session->write('message', SitesTable::INVALID_INPUT_MESSEGE);
                $this->set('site', $site);
                return;
            }

            // エンティティにデータセット
            $site = $this->Sites->patchEntity($site, $data);
            if ($site->getErrors()) {
                $this->session->write('message', SitesTable::INVALID_MESSAGE);
                return $this->redirect(['action' => 'index']);
            }

            try {

                // トランザクション開始
                $this->connection->begin();

                // 排他制御
                $this->Sites
                    ->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])
                    ->modifier('SQL_NO_CACHE')
                    ->epilog('FOR UPDATE')
                    ->first();

                // 登録処理
                $ret = $this->Sites->save($site);
                if (!$ret) {
                    throw new DatabaseException;
                }

                // ディレクトリに画像保存
                $path = SitesTable::ROOT_FAVICON_PATH . $this->AuthUser->username;
                if (file_exists($path)) {
                    // 既に画像がある場合は削除
                    foreach (glob($path . '/*') as $old_file) {
                        unlink($old_file);
                    }
                    $image->moveTo($path . '/' . $data['favicon_path']);
                } else {
                    throw new DatabaseException;
                }

                // コミット
                $this->connection->commit();
            } catch (DatabaseException $e) {

                // ロールバック
                $this->connection->rollback();
                $this->session->write('message', SitesTable::INVALID_FAVICON_MESSAGE);
                return $this->redirect(['action' => 'index']);
            }

            // 完了画面へリダイレクト
            $this->session->write('message', SitesTable::SUCCESS_FAVICON_MESSAGE);
            return $this->redirect(['action' => 'index']);
        }

        // viewに渡すデータセット
        $this->set('site', $site);
    }

    public function editHeaderImage()
    {
        // ログインidからデータ取得
        $site = $this->Sites->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->first();

        if ($this->request->is(['patch', 'post', 'put'])) {
            // postの場合

            // requestデータ取得
            $data = $this->request->getData();

            if ($data['header_image_path']->getClientFilename() == '' || $data['header_image_path']->getClientMediaType() == '') {

                // アップロードされていなければ処理せず変更完了
                $this->session->write('message', SitesTable::SUCCESS_HEADER_IMAGE_MESSAGE);
                return $this->redirect(['action' => 'index']);
            }

            // 画像データを変数に格納
            $image = $data['header_image_path'];

            // 画像名をリクエストデータに代入
            $data['header_image_path'] = $data['header_image_path']->getClientFilename();

            // バリデーション
            if (!in_array(pathinfo($data['header_image_path'])['extension'], SitesTable::EXTENTIONS)) {
                $site->setError('header_image_path', [SitesTable::INVALID_EXTENSION_MESSAGE]);
                $this->session->write('message', SitesTable::INVALID_INPUT_MESSEGE);
                $this->set('site', $site);
                return;
            }

            // エンティティにデータセット
            $site = $this->Sites->patchEntity($site, $data);
            if ($site->getErrors()) {
                $this->session->write('message', SitesTable::INVALID_MESSAGE);
                return $this->redirect(['action' => 'index']);
            }

            try {

                // トランザクション開始
                $this->connection->begin();

                // 排他制御
                $this->Sites
                    ->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])
                    ->modifier('SQL_NO_CACHE')
                    ->epilog('FOR UPDATE')
                    ->first();

                // 登録処理
                $ret = $this->Sites->save($site);
                if (!$ret) {
                    throw new DatabaseException;
                }

                // ディレクトリに画像保存
                $path = SitesTable::ROOT_HEADER_IMAGE_PATH . $this->AuthUser->username;
                if (file_exists($path)) {
                    // 既に画像がある場合は削除
                    foreach (glob($path . '/*') as $old_file) {
                        unlink($old_file);
                    }
                    $image->moveTo($path . '/' . $data['header_image_path']);
                } else {
                    throw new DatabaseException;
                }

                // コミット
                $this->connection->commit();
            } catch (DatabaseException $e) {

                // ロールバック
                $this->connection->rollback();
                $this->session->write('message', SitesTable::INVALID_HEADER_IMAGE_MESSAGE);
                return $this->redirect(['action' => 'index']);
            }

            // 完了画面へリダイレクト
            $this->session->write('message', SitesTable::SUCCESS_HEADER_IMAGE_MESSAGE);
            return $this->redirect(['action' => 'index']);
        }

        // viewに渡すデータセット
        $this->set('site', $site);
    }

    public function settingHeaderImage()
    {
        $this->viewBuilder()->disableAutoLayout();

        // ログインidからデータ取得
        $site = $this->Sites->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->first();
        $profile = $this->Profiles->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->first();

        if ($this->request->is(['patch', 'post', 'put'])) {
            // postの場合

            // requestデータ取得
            $data = $this->request->getData();

            // エンティティにデータセット
            $site = $this->Sites->patchEntity($site, $data);
            if ($site->getErrors()) {
                $this->session->write('message', SitesTable::INVALID_MESSAGE);
                return $this->redirect(['action' => 'index']);
            }

            try {

                // トランザクション開始
                $this->connection->begin();

                // 排他制御
                $this->Sites
                    ->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])
                    ->modifier('SQL_NO_CACHE')
                    ->epilog('FOR UPDATE')
                    ->first();

                // 登録処理
                $ret = $this->Sites->save($site);
                if (!$ret) {
                    throw new DatabaseException;
                }

                // コミット
                $this->connection->commit();
            } catch (DatabaseException $e) {

                // ロールバック
                $this->connection->rollback();
                $this->session->write('message', SitesTable::INVALID_HEADER_IMAGE_MESSAGE);
                return $this->redirect(['action' => 'index']);
            }
            $this->session->write('message', '変更を保存しました。');
        }

        // viewに渡すデータセット
        $this->set('site', $site);
        $this->set('profile', $profile);
    }
}
