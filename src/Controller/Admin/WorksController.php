<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Model\Table\WorksTable;
use Cake\Core\Configure;
use Cake\Database\Exception\DatabaseException;
use Cake\Http\Response;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

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
        $this->set(
            'works',
            $this->Works->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->order(['works_order' => 'asc'])
        );
    }

    /**
     * 追加
     * 
     * @return Response|void|null
     */
    public function edit($id = null)
    {
        // エンティティ取得
        $work = $this->set_entity($id);

        // 不正なアクセスの場合は一覧画面へリダイレクト
        if (is_null($work)) {
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['post', 'patch', 'put'])) {

            // バリデーション
            if ($this->validate()) {
                return;
            }

            // リクエストデータ取得
            $data = $this->request->getData();

            // 新規登録時
            if (is_null($id)) {

                // ログインユーザーのIDを追加
                $data['user_id'] = $this->AuthUser->id;

                // オーダーの初期値を追加
                $data['works_order'] = $this->set_order();
            }

            // 画像がアップロードされているか確認
            if (!is_null(Hash::get($data, 'image_path')) && $data['image_path']->getClientFilename() != '') {

                // 画像データを変数に格納
                $image = $data['image_path'];

                // 画像名をリクエストデータに代入
                $data['image_path'] = $data['image_path']->getClientFilename();
            } else {
                $image = null;
                $data['image_path'] = null;
            }

            // エンティティにデータセット
            $work = $this->Works->patchEntity($work, $data);

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

                // 画像保存
                if (!$this->save_image($image, $work->id)) {
                    throw new DatabaseException;
                }

                // コミット
                $this->connection->commit();
            } catch (DatabaseException $e) {

                // ロールバック
                $this->connection->rollback();

                // 一覧画面へリダイレクト
                $this->session->write('message', Configure::read('alert_message.system_faild'));
                return $this->redirect(['action' => 'index']);
            }

            // 詳細へリダイレクト
            if (is_null($id)) {
                $this->session->write('message', '実績' . Configure::read('alert_message.add'));
            } else {
                $this->session->write('message', Configure::read('alert_message.complete'));
            }
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
    public function editImage($id = null)
    {
        // idとログインユーザーidから実績のレコードを取得
        $work = $this->Works->find('all', ['conditions' => ['id' => $id, 'user_id' => $this->AuthUser->id]])->first();

        // 不正なアクセスの場合は一覧画面へリダイレクト
        if (!$work) {
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['post', 'patch', 'put'])) {
            // postの場合

            // バリデーション
            if ($this->validate()) {
                return;
            }

            // リクエストデータ取得
            $data = $this->request->getData();

            // 画像がアップロードされていなければ処理を終了
            if ($data['image_path']->getClientFilename() == '') {
                $this->set('work', $work);
                return;
            }

            // 画像データを変数に格納
            $image = $data['image_path'];

            // 画像名をリクエストデータに代入
            $data['image_path'] = $data['image_path']->getClientFilename();

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

                // 画像保存
                if (!$this->save_image($image, $work->id)) {
                    throw new DatabaseException;
                }

                // コミット
                $this->connection->commit();
            } catch (DatabaseException $e) {

                // ロールバック
                $this->connection->rollback();

                // 一覧画面へリダイレクト
                $this->session->write('message', Configure::read('alert_message.system_faild'));
                return $this->redirect(['action' => 'index']);
            }

            // 詳細へリダイレクト
            $this->session->write('message', Configure::read('alert_message.delete'));
            return $this->redirect(['action' => 'editImage', $work->id]);
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
    public function editLink($id = null)
    {
        // idとログインユーザーidから実績のレコードを取得
        $work = $this->Works->find('all', ['conditions' => ['id' => $id, 'user_id' => $this->AuthUser->id]])->first();

        // 不正なアクセスの場合は一覧画面へリダイレクト
        if (!$work) {
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['post', 'patch', 'put'])) {
            // postの場合
        }

        $this->set('work', $work);
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
                $this->session->write('message', Configure::read('alert_message.system_faild'));
                return $this->redirect(['action' => 'index']);
            }
        }

        // 一覧画面へリダイレクト
        $this->session->write('message', Configure::read('alert_message.delete'));
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
                $this->session->write('message', Configure::read('alert_message.system_faild'));
                return $this->redirect(['action' => 'index']);
            }

            $this->session->write('message', Configure::read('alert_message.complete'));
            return $this->redirect(['action' => 'order']);
        }

        $this->set('works', $works);
    }

    /** private method **/

    /**
     * バリデーション
     * 
     * @return bool
     */
    private function validate(): bool
    {
        // リクエストデータ取得
        $data = $this->request->getData();

        /**
         * 画像のバリデーション
         */
        $image_error = '';
        // 画像がアップロードされているか確認
        if (!is_null(Hash::get($data, 'image_path'))) {
            if ($data['image_path']->getClientFilename() != '' || $data['image_path']->getClientMediaType() != '') {
    
                // 拡張子の確認
                if (!in_array($data['image_path']->getClientMediaType(), ['image/jpeg', 'image/png', 'image/x-icon', 'image/webp'])) {
                    $image_error = '無効な拡張子です。';
                }
            }
        }

        // オブジェクト型で渡ってくるためnullに変換
        $data['image_path'] = null;

        // エンティティにデータセット
        $work = $this->Works->patchEntity($this->Works->newEmptyEntity(), $data);

        if ($image_error != '') {
            $work->setError('image_path', [$image_error]);
        }

        // バリデーション処理
        if ($work->getErrors() || $work->hasErrors()) {
            $this->session->write('message', Configure::read('alert_message.input_faild'));
            $this->set('work', $work);
            return true;
        }

        return false;
    }

    /**
     * 取得するエンティティ
     * 
     * @param int $id
     * 
     * @return entity|null
     */
    private function set_entity($id)
    {
        // $idによって処理判定
        if (is_null($id)) {

            // 新規登録
            return $this->Works->newEmptyEntity();
        } else {

            // 編集
            // idとログインユーザーidから実績のレコードを取得
            return $this->Works->find('all', ['conditions' => ['id' => $id, 'user_id' => $this->AuthUser->id]])->first();
        }
    }

    /**
     * オーダーの初期値設定
     * 
     * @return int
     */
    private function set_order(): int
    {
        // 並び順の最後尾を検索し、最後尾の最後の順番を追加
        $works = $this->Works->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->order(['works_order' => 'asc']);
        $order_array = [];
        foreach ($works as $value) {
            // 比較用にothers_orderの数値を全て配列に格納
            array_push($order_array, intval($value->works_order));
        }
        if (empty($order_array)) {
            // データが無い場合は1を追加する
            return 1;
        } else {
            // データの最大値+1を追加する
            return max($order_array) + 1;
        }
    }

    /**
     * 画像保存処理
     * 
     * @param int $id エンティティのパス
     * @param object $image 画像データ
     * 
     * @return bool
     */
    private function save_image($image, $id): bool
    {
        if (is_null($image)) {
            return true;
        }

        // ディレクトリに画像保存
        $path = WorksTable::ROOT_WORKS_IMAGE_PATH . $this->AuthUser->username;
        if (file_exists($path)) {

            // 保存ディレクトリを取得
            $path = $path . '/' . $id;
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
            $image->moveTo($path . '/' . $image->getClientFilename());
        } else {
            return false;
        }

        return true;
    }
}
