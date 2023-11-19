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
     * Index method
     *
     * @return Response|void 
     */
    public function index()
    {
        // ログインidからデータ取得
        $profile = $this->Profiles->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->first();

        $this->set('profile', $profile);
    }

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
                    throw new DatabaseException('プロフィールの変更に失敗しました。');
                }

                // コミット
                $this->connection->commit();
            } catch (DatabaseException $e) {

                // ロールバック
                $this->connection->rollback();
                $this->session->write('message', $e);
                return $this->redirect('/');
            }

            $this->session->write('message', 'プロフィールを変更しました。');
            return $this->redirect(['action' => 'index']);
        }

        $this->set('profile', $profile);
    }
}
