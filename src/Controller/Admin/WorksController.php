<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Model\Table\WorksTable;
use Cake\Database\Exception\DatabaseException;
use Cake\Http\Response;
use Cake\ORM\TableRegistry;

/**
 * Works Controller
 *
 * @property WorksTable $Works
 */
class WorksController extends AppController
{

    /**
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        // 利用するモデル
        $this->Works = TableRegistry::getTableLocator()->get('Works');

        // トランザクション変数
        $this->connection = $this->Works->getConnection();
    }

    /**
     * 一覧表示
     * 
     * @return Response|void|null
     */
    public function index()
    {
        $works = $this->Works->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->order(['works_order' => 'asc']);

        $this->set('works', $works);
    }

    /**
     * 追加
     * 
     * @return Response|void|null
     */
    public function edit($id = null)
    {

        // $idによって処理判定
        if (is_null($id)) {

            // 新規登録
            $work = $this->Works->newEmptyEntity();
        } else {

            // 編集
            // idとログインユーザーidから実績のレコードを取得
            $work = $this->Works->find('all', ['conditions' => ['id' => $id, 'user_id' => $this->AuthUser->id]])->first();

            // 不正なアクセスの場合は一覧画面へリダイレクト
            if (!$work) {
                return $this->redirect(['action' => 'index']);
            }
        }

        if ($this->request->is(['post', 'patch', 'put'])) {

            // リクエストデータ取得
            $data = $this->request->getData();

            // ログインユーザーのIDを追加
            $data['user_id'] = $this->AuthUser->id;

            // 並び順の最後尾を検索し、最後尾の最後の順番を追加
            $works = $this->Works->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->order(['works_order' => 'asc']);
            $order_array = [];
            foreach ($works as $value) {
                // 比較用にothers_orderの数値を全て配列に格納
                array_push($order_array, intval($value->works_order));
            }
            if (empty($order_array)) {
                // データが無い場合は1を追加する
                $data['works_order'] = 1;
            } else {
                // データの最大値+1を追加する
                $data['works_order'] = max($order_array) + 1;
            }

            // 画像がアップロードされているか確認
            if ($data['image_path']->getClientFilename() != '' || $data['image_path']->getClientMediaType() != '') {

                // 画像データを変数に格納
                $image = $data['image_path'];

                // 画像名をリクエストデータに代入
                $data['image_path'] = $data['image_path']->getClientFilename();

                // 拡張子の確認
                if (!in_array(pathinfo($data['image_path'])['extension'],  ['jpg', 'png', 'jpeg', 'webp'])) {
                    $work->setError('image_path', ['無効な拡張子です。']);
                }
            } else {
                $data['image_path'] = null;
            }

            // エンティティにデータセット
            $work = $this->Works->patchEntity($work, $data);

            // バリデーション処理
            if ($work->getErrors()) {
                $this->session->write('message', '入力に不備があります。');
                $this->set('work', $work);
                return;
            }

            try {

                // トランザクション開始
                $this->connection->begin();

                if (!is_null($id)) {

                    // 更新の場合、排他制御
                    $this->Works
                        ->find('all', ['conditions' => ['id' => $work->id]])
                        ->modifier('SQL_NO_CACHE')
                        ->epilog('FOR UPDATE')
                        ->first();
                }

                // 登録処理
                $ret = $this->Works->save($work);
                if (!$ret) {
                    throw new DatabaseException;
                }

                if (!is_null($data['image_path'])) {

                    // ディレクトリに画像保存
                    $path = WorksTable::ROOT_WORKS_IMAGE_PATH . $this->AuthUser->username;
                    if (file_exists($path)) {

                        // 保存ディレクトリを取得
                        $path = $path . '/' . $work->id;
                        if (!file_exists($path)) {

                            // ディレクトリが無ければ作成
                            mkdir($path);
                        } else {

                            // ディレクトリがあれば画像があるか確認し、あれば削除
                            foreach (glob($path . '/*') as $old_file) {
                                unlink($old_file);
                            }
                        }

                        // 画像保存
                        $image->moveTo($path . '/' . $data['image_path']);
                    } else {
                        throw new DatabaseException;
                    }
                }

                // コミット
                $this->connection->commit();
            } catch (DatabaseException $e) {

                // ロールバック
                $this->connection->rollback();

                // 一覧画面へリダイレクト
                $this->session->write('message', '登録に失敗しました。');
                return $this->redirect(['action' => 'index']);
            }

            // 詳細へリダイレクト
            $this->session->write('message', '実績を一件、追加しました。');
            return $this->redirect(['action' => 'index']);
        }

        $this->set('work', $work);
    }

    /**
     * 画像削除
     * 
     * @param int $id
     * 
     * @return Response|void|null
     */
    public function deleteImage($id = null)
    {
        // idとログインユーザーidから実績のレコードを取得
        $work = $this->Works->find('all', ['conditions' => ['id' => $id, 'user_id' => $this->AuthUser->id]])->first();

        // 不正なアクセスの場合は一覧画面へリダイレクト
        if (!$work) {
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['post', 'patch', 'put'])) {
            // postの場合

            $image_path = WorksTable::ROOT_WORKS_IMAGE_PATH . $this->AuthUser->username  . '/' . $work->id . '/' . $work->image_path;

            // 削除するデータ作成
            $data = [
                'image_path' => null,
                'url' => null,
                'url_path' => null
            ];

            // エンティティにデータセット
            $work = $this->Works->patchEntity($work, $data);

            try {

                // トランザクション開始
                $this->connection->begin();

                // 排他制御
                $this->Works
                    ->find('all', ['conditions' => ['id' => $work->id]])
                    ->modifier('SQL_NO_CACHE')
                    ->epilog('FOR UPDATE')
                    ->first();

                // 登録処理
                $ret = $this->Works->save($work);
                if (!$ret) {
                    throw new DatabaseException;
                }

                // 画像削除
                if (file_exists($image_path)) {
                    $ret = unlink($image_path);
                    if (!$ret) {
                        throw new DatabaseException;
                    }
                }

                // コミット
                $this->connection->commit();
            } catch (DatabaseException $e) {

                // ロールバック
                $this->connection->rollback();

                // 一覧画面へリダイレクト
                $this->session->write('message', '変更に失敗しました。');
                return $this->redirect(['action' => 'index']);
            }

            // 詳細へリダイレクト
            $this->session->write('message', '実績を変更しました。');
            return $this->redirect(['action' => 'add', $work->id]);
        }
    }

    /**
     * 削除
     * 
     * @param int $id
     * 
     * @return Response|void|null
     */
    public function delete($id = null)
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

                // 画像削除処理
                $path = WorksTable::ROOT_WORKS_IMAGE_PATH . $this->AuthUser->username . '/' . $work->id;
                if (file_exists($path)) {
                    foreach (glob($path . '/*') as $file) {
                        unlink($file);
                    }
                    rmdir($path);
                }

                // コミット
                $this->connection->commit();
            } catch (DatabaseException $e) {

                // ロールバック
                $this->connection->rollback();

                // 一覧画面へリダイレクト
                $this->session->write('message', '削除に失敗しました。');
                return $this->redirect(['action' => 'index']);
            }
        }

        // 一覧画面へリダイレクト
        $this->session->write('message', '実績を一件、削除しました。');
        return $this->redirect(['action' => 'index']);
    }

    /**
     * 順序入れ替え
     * 
     * @return Response|void|null
     */
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

                // トランザクション開始
                $this->connection->begin();

                // 排他制御
                $works->modifier('SQL_NO_CACHE')->epilog('FOR UPDATE')->toArray();

                // 一括更新
                $works = $this->Works->patchEntities($works, $save_data);
                $works = $this->Works->saveMany($works);
                if (!$works) {
                    throw new DatabaseException();
                }

                // コミット
                $this->connection->commit();
            } catch (DatabaseException $e) {

                // ロールバック
                $this->connection->rollback();
                $this->session->write('message', '設定の更新が失敗しました。');
                return $this->redirect(['action' => 'index']);
            }

            $this->session->write('message', '設定を反映しました。');
            return $this->redirect(['action' => 'order']);
        }

        $this->set('works', $works);
    }
}
