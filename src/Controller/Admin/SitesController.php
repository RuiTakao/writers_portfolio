<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Model\Table\SitesTable;
use Cake\ORM\TableRegistry;

/**
 * Sites Controller
 *
 * @property SitesTable $Sites
 */
class SitesController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        // 利用するモデル
        $this->Sites = TableRegistry::getTableLocator()->get('Sites');

        // トランザクション変数
        $this->connection = $this->Sites->getConnection();
    }

    public function index()
    {
        // ログインidからデータ取得
        $site = $this->Sites->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->first();

        $this->set('site', $site);
    }

    public function edit()
    {
        // ログインidからデータ取得
        $site = $this->Sites->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->first();

        if ($this->request->is(['patch', 'post', 'put'])) {
            // postの場合

            // requestデータ取得
            $data = $this->request->getData();

            try {

                // トランザクション開始
                $this->connection->begin();

                // 排他制御
                $this->Sites
                    ->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])
                    ->modifier('SQL_NO_CACHE')
                    ->epilog('FOR UPDATE')
                    ->first();

                // エンティティにデータセット
                $site = $this->Sites->patchEntity($site, $data);

                // バリデーション処理
                if ($site->getErrors()) {
                    $this->set('site', $site);
                    return;
                }

                // 登録処理
                $ret = $this->Sites->save($site);
                if (!$ret) {
                    throw new DatabaseException('サイトの変更に失敗しました。');
                }

                // コミット
                $this->connection->commit();
            } catch (DatabaseException $e) {

                // ロールバック
                $this->connection->rollback();
                $this->session->write('message', $e);
                return $this->redirect('/');
            }

            $this->session->write('message', 'サイトを変更しました。');
            return $this->redirect(['action' => 'index']);
        }

        $this->set('site', $site);
    }

    public function editFaviconImage()
    {
        // ログインidからデータ取得
        $site = $this->Sites->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->first();

        if ($this->request->is(['patch', 'post', 'put'])) {
            // postの場合

            // requestデータ取得
            $data = $this->request->getData();

            if ($data['favicon_path']->getClientFilename() == '' || $data['favicon_path']->getClientMediaType() == '') {
                $this->session->write('message', 'ファビコンを変更しました。');
                return $this->redirect(['action' => 'index']);
            }

            $image = $data['favicon_path'];
            $data['favicon_path'] = $data['favicon_path']->getClientFilename();

            try {

                // トランザクション開始
                $this->connection->begin();

                // 排他制御
                $this->Sites
                    ->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])
                    ->modifier('SQL_NO_CACHE')
                    ->epilog('FOR UPDATE')
                    ->first();

                // エンティティにデータセット
                $site = $this->Sites->patchEntity($site, $data);

                // バリデーション処理
                if ($site->getErrors()) {
                    $this->set('site', $site);
                    return;
                }

                // 登録処理
                $ret = $this->Sites->save($site);
                if (!$ret) {
                    throw new DatabaseException('ファビコンの変更に失敗しました。');
                }

                $image->moveTo(WWW_ROOT . 'img/users/sites/favicons/' . $this->AuthUser->username . '/' . $data['favicon_path']);

                // コミット
                $this->connection->commit();
            } catch (DatabaseException $e) {

                // ロールバック
                $this->connection->rollback();
                $this->session->write('message', $e);
                return $this->redirect('/');
            }

            $this->session->write('message', 'ファビコンを変更しました。');
            return $this->redirect(['action' => 'index']);
        }


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
                $this->session->write('message', 'ヘッダー画像を変更しました。');
                return $this->redirect(['action' => 'index']);
            }

            $image = $data['header_image_path'];
            $data['header_image_path'] = $data['header_image_path']->getClientFilename();

            try {

                // トランザクション開始
                $this->connection->begin();

                // 排他制御
                $this->Sites
                    ->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])
                    ->modifier('SQL_NO_CACHE')
                    ->epilog('FOR UPDATE')
                    ->first();

                // エンティティにデータセット
                $site = $this->Sites->patchEntity($site, $data);

                // バリデーション処理
                if ($site->getErrors()) {
                    $this->set('site', $site);
                    return;
                }

                // 登録処理
                $ret = $this->Sites->save($site);
                if (!$ret) {
                    throw new DatabaseException('ヘッダー画像の変更に失敗しました。');
                }

                $image->moveTo(WWW_ROOT . 'img/users/sites/headers/' . $this->AuthUser->username . '/' . $data['header_image_path']);

                // コミット
                $this->connection->commit();
            } catch (DatabaseException $e) {

                // ロールバック
                $this->connection->rollback();
                $this->session->write('message', $e);
                return $this->redirect('/');
            }

            $this->session->write('message', 'ヘッダー画像を変更しました。');
            return $this->redirect(['action' => 'index']);
        }


        $this->set('site', $site);
    }
}
