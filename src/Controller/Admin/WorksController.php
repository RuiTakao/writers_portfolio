<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Model\Table\WorksTable;
use Cake\Database\Exception\DatabaseException;
use Cake\ORM\TableRegistry;

/**
 * Works Controller
 *
 * @property WorksTable $Works
 */
class WorksController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();

        // 利用するモデル
        $this->Works = TableRegistry::getTableLocator()->get('Works');

        // トランザクション変数
        $this->connection = $this->Works->getConnection();
    }

    public function index()
    {
        $works = $this->Works->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->order(['works_order' => 'asc']);

        $this->set('works', $works);
    }

    public function add()
    {
        $work = $this->Works->newEmptyEntity();

        if ($this->request->is('post')) {

            // リクエストデータ取得
            $data = $this->request->getData();

            // ログインユーザーのIDを追加
            $data['user_id'] = $this->AuthUser->id;

            // 並び順の最後尾を検索し、最後尾の最後の順番を追加
            $works = $this->Works->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->order(['works_order' => 'asc']);
            $works_order_array = [];
            foreach ($works as $value) {
                array_push($works_order_array, intval($value->works_order));
            }
            if (empty($works_order_array)) {
                $data['works_order'] = 1;
            } else {
                $data['works_order'] = max($works_order_array) + 1;
            }

            try {

                // トランザクション開始
                $this->connection->begin();

                // 登録処理
                $work = $this->Works->patchEntity($work, $data);
                $ret = $this->Works->save($work);
                if (!$ret) {
                    throw new DatabaseException;
                }

                // コミット
                $this->connection->commit();
            } catch (DatabaseException $e) {
                // ロールバック
                $this->connection->rollback();
                return $this->redirect(['action' => 'index']);
            }

            return $this->redirect(['action' => 'detail', $work->id]);
        }

        $this->set('work', $work);
    }

    public function detail($id)
    {
        // idとログインユーザーidから実績のレコードを取得
        $work = $this->Works->find('all', ['conditions' => ['id' => $id, 'user_id' => $this->AuthUser->id]])->first();

        // 不正なアクセスの場合は一覧画面へリダイレクト
        if (!$work) {
            return $this->redirect(['action' => 'index']);
        }

        $this->set('work', $work);
    }

    public function editImage($id)
    {
        // idとログインユーザーidから実績のレコードを取得
        $work = $this->Works->find('all', ['conditions' => ['id' => $id, 'user_id' => $this->AuthUser->id]])->first();

        // 不正なアクセスの場合は一覧画面へリダイレクト
        if (!$work) {
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['post', 'patch', 'put'])) {

            // リクエストデータ取得
            $data = $this->request->getData();

            $image = $data['image_path'];
            $data['image_path'] = $data['image_path']->getClientFilename();

            try {

                // トランザクション開始
                $this->connection->begin();

                // 登録処理
                $work = $this->Works->patchEntity($work, $data);
                $ret = $this->Works->save($work);
                if (!$ret) {
                    throw new DatabaseException;
                }

                // ディレクトリに画像保存
                $path = WorksTable::ROOT_WORKS_IMAGE_PATH . $this->AuthUser->username;
                if (file_exists($path)) {
                    mkdir($path . '/' . $work->id);
                    $image->moveTo($path . '/' . $work->id . '/' . $data['image_path']);
                } else {
                    throw new DatabaseException;
                }

                // コミット
                $this->connection->commit();
            } catch (DatabaseException $e) {
                // ロールバック
                $this->connection->rollback();
                return $this->redirect(['action' => 'detail', $work->id]);
            }

            return $this->redirect(['action' => 'detail', $work->id]);
        }

        $this->set('work', $work);
    }

    public function edit($id)
    {
        // idとログインユーザーidから実績のレコードを取得
        $work = $this->Works->find('all', ['conditions' => ['id' => $id, 'user_id' => $this->AuthUser->id]])->first();

        // 不正なアクセスの場合は一覧画面へリダイレクト
        if (!$work) {
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['post', 'patch', 'put'])) {

            // リクエストデータ取得
            $data = $this->request->getData();

            try {

                // トランザクション開始
                $this->connection->begin();

                // 登録処理
                $work = $this->Works->patchEntity($work, $data);
                $ret = $this->Works->save($work);
                if (!$ret) {
                    throw new DatabaseException;
                }

                // コミット
                $this->connection->commit();
            } catch (DatabaseException $e) {
                // ロールバック
                $this->connection->rollback();
                return $this->redirect(['action' => 'detail', $work->id]);
            }

            return $this->redirect(['action' => 'detail', $work->id]);
        }

        $this->set('work', $work);
    }

    public function delete($id)
    {
        // idとログインユーザーidから実績のレコードを取得
        $work = $this->Works->find('all', ['conditions' => ['id' => $id, 'user_id' => $this->AuthUser->id]])->first();

        // 不正なアクセスの場合は一覧画面へリダイレクト
        if (!$work) {
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['post', 'patch', 'put'])) {
            try {

                // トランザクション開始
                $this->connection->begin();

                // 排他制御
                $this->Works
                    ->find('all', ['conditions' => ['id' => $work->id]])
                    ->modifier('SQL_NO_CACHE')
                    ->epilog('FOR UPDATE')
                    ->first();

                // 削除処理
                $ret = $this->Works->delete($work);
                if (!$ret) {
                    throw new DatabaseException;
                }

                // コミット
                $this->connection->commit();
            } catch (DatabaseException $e) {

                // ロールバック
                $this->connection->rollback();
                return $this->redirect(['action' => 'index']);
            }
        }
        return $this->redirect(['action' => 'index']);
    }

    public function order()
    {
        $works = $this->Works->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->order(['works_order' => 'asc']);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            // 更新データ作成
            $save_data = [];
            foreach ($data['id'] as $index => $item) {
                $save_data[] =  [
                    'id' => $item,
                    'works_order' => $data['order'][$index]
                ];
            }

            try {
                $this->connection->begin();

                // 排他制御
                $works->modifier('SQL_NO_CACHE')->epilog('FOR UPDATE')->toArray();

                // 一括更新
                $works = $this->Works->patchEntities($works, $save_data);
                $works = $this->Works->saveMany($works);
                if (!$works) {
                    throw new DatabaseException();
                }

                $this->connection->commit();

                $this->session->write('message', '設定を反映しました。');
                return $this->redirect(['action' => 'order']);
            } catch (DatabaseException $e) {
                $this->connection->rollback();
                $this->session->write('message', '設定の更新が失敗しました。');
                return $this->redirect(['action' => 'index']);
            }
        }

        $this->set('works', $works);
    }
}
