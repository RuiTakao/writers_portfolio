<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Model\Table\ProfilesTable;
use Cake\Http\Response;
use Cake\ORM\TableRegistry;

/**
 * Profiles Controller
 *
 * @property ProfilesTable $Profiles
 */
class ProfilesController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();

        // 利用するモデル
        $this->Profiles = TableRegistry::getTableLocator()->get('Profiles');

        // トランザクション変数
        $this->connection = $this->Profiles->getConnection();
    }

    /**
     * プロフィール管理画面
     *
     * @return Response|void|null
     */
    public function index()
    {
        // ログインidからデータ取得
        $profile = $this->Profiles->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->first();

        // viewに渡すデータセット
        $this->set('profile', $profile);
    }

    /**
     * プロフィール編集画面
     * 
     * @return Response|void|null
     * @throws DatabaseException
     */
    public function edit()
    {
        // ログインidからデータ取得
        $profile = $this->Profiles->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->first();

        if ($this->request->is(['patch', 'post', 'put'])) {
            // postの場合

            // requestデータ取得
            $data = $this->request->getData();

            try {

                // トランザクション開始
                $this->connection->begin();

                // 排他制御
                $this->Profiles
                    ->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])
                    ->modifier('SQL_NO_CACHE')
                    ->epilog('FOR UPDATE')
                    ->first();

                // エンティティにデータセット
                $profile = $this->Profiles->patchEntity($profile, $data);

                // バリデーション処理
                if ($profile->getErrors()) {
                    $this->set('profile', $profile);
                    return;
                }

                // 登録処理
                $ret = $this->Profiles->save($profile);
                if (!$ret) {
                    throw new DatabaseException(ProfilesTable::INVALID_MESSAGE);
                }

                // コミット
                $this->connection->commit();
            } catch (DatabaseException $e) {

                // ロールバック
                $this->connection->rollback();
                $this->session->write('message', $e);
                return $this->redirect('/');
            }

            // 完了画面へリダイレクト
            $this->session->write('message', ProfilesTable::SUCCESS_MESSAGE);
            return $this->redirect(['action' => 'index']);
        }

        // viewに渡すデータセット
        $this->set('profile', $profile);
    }

    /**
     * プロフィール画像編集画面
     * 
     * @return Response|void|null
     * @throws DatabaseException
     */
    public function editImage()
    {

        // ログインidからデータ取得
        $profile = $this->Profiles->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->first();

        if ($this->request->is(['patch', 'post', 'put'])) {
            // postの場合

            // requestデータ取得
            $data = $this->request->getData();

            // 画像がアップロードされているか確認
            if ($data['image_path']->getClientFilename() == '' || $data['image_path']->getClientMediaType() == '') {

                // アップロードされていなければ処理せず変更完了
                $this->session->write('message', ProfilesTable::SUCCESS_MESSAGE);
                return $this->redirect(['action' => 'index']);
            }

            // 画像データを変数に格納
            $image = $data['image_path'];

            // 画像名をリクエストデータに代入
            $data['image_path'] = $data['image_path']->getClientFilename();

            try {

                // トランザクション開始
                $this->connection->begin();

                // 排他制御
                $this->Profiles
                    ->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])
                    ->modifier('SQL_NO_CACHE')
                    ->epilog('FOR UPDATE')
                    ->first();

                // エンティティにデータセット
                $profile = $this->Profiles->patchEntity($profile, $data);

                // バリデーション処理
                if ($profile->getErrors()) {
                    $this->set('profile', $profile);
                    return;
                }

                // 登録処理
                $ret = $this->Profiles->save($profile);
                if (!$ret) {
                    throw new DatabaseException(ProfilesTable::INVALID_MESSAGE);
                }

                // ディレクトリに画像保存
                $ret = $image->moveTo(WWW_ROOT . 'img/users/profiles/' . $this->AuthUser->username . '/' . $data['image_path']);
                if (!$ret) {
                    throw new DatabaseException(ProfilesTable::INVALID_MESSAGE);
                }

                // コミット
                $this->connection->commit();
            } catch (DatabaseException $e) {

                // ロールバック
                $this->connection->rollback();
                $this->session->write('message', $e);
                return $this->redirect('/');
            }

            // 完了画面へリダイレクト
            $this->session->write('message', ProfilesTable::SUCCESS_MESSAGE);
            return $this->redirect(['action' => 'index']);
        }

        // viewに渡すデータセット
        $this->set('profile', $profile);
    }
}
