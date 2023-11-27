<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Model\Table\OthersTable;
use Cake\Database\Exception\DatabaseException;
use Cake\Http\Response;
use Cake\ORM\TableRegistry;

/**
 * Others Controller
 *
 * @property OthersTable $Others
 */
class OthersController extends AppController
{

    /**
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        // 利用するモデル
        $this->Others = TableRegistry::getTableLocator()->get('Others');

        // トランザクション変数
        $this->connection = $this->Others->getConnection();
    }

    /**
     * その他一覧
     * 
     * @return Response|void|null
     * 
     * @throws DatabaseException
     */
    public function index()
    {
        // ログインユーザーidからその他のレコードを取得
        $others = $this->Others->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->order(['others_order' => 'asc']);

        // viewに渡すデータセット
        $this->set('others', $others);
    }

    /**
     * その他追加
     * 
     * @return Response|void|null
     */
    public function add()
    {
        // エンティティ作成
        $other = $this->Others->newEmptyEntity();

        if ($this->request->is('post')) {
            // postの場合

            // リクエストデータ取得
            $data = $this->request->getData();

            // ログインユーザーのIDを追加
            $data['user_id'] = $this->AuthUser->id;

            // 並び順の最後尾を検索し、最後尾の最後の順番を追加
            $others = $this->Others->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->order(['others_order' => 'asc']);
            $order_array = [];
            foreach ($others as $value) {
                // 比較用にothers_orderの数値を全て配列に格納
                array_push($order_array, intval($value->others_order));
            }
            if (empty($order_array)) {
                // データが無い場合は1を追加する
                $data['others_order'] = 1;
            } else {
                // データの最大値+1を追加する
                $data['others_order'] = max($order_array) + 1;
            }

            // エンティティにデータをセット
            $other = $this->Others->patchEntity($other, $data);

            // バリデーション処理
            if ($other->getErrors()) {
                $this->session->write('message', '入力に不備があります。');
                $this->set('other', $other);
                return;
            }

            try {

                // トランザクション開始
                $this->connection->begin();

                // 登録処理
                $ret = $this->Others->save($other);
                if (!$ret) {
                    throw new DatabaseException;
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

            // 一覧画面へリダイレクト
            $this->session->write('message', 'その他を一件、追加しました。');
            return $this->redirect(['action' => 'index']);
        }

        // viewに渡すデータセット
        $this->set('other', $other);
    }

    /**
     * その他編集
     * 
     * @param int $id
     * 
     * @return Response|void|null
     * 
     * @throws DatabaseException
     */
    public function edit($id = null)
    {
        // idとログインユーザーidからその他のレコードを取得
        $other = $this->Others->find('all', ['conditions' => ['id' => $id, 'user_id' => $this->AuthUser->id]])->first();

        // 不正なアクセスの場合は一覧画面へリダイレクト
        if (!$other) {
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['post', 'patch', 'put'])) {
            // postの場合

            // リクエストデータ取得
            $data = $this->request->getData();

            // エンティティにデータをセット
            $other = $this->Others->patchEntity($other, $data);

            // バリデーション処理
            if ($other->getErrors()) {
                $this->session->write('message', '入力に不備があります。');
                $this->set('other', $other);
                return;
            }

            try {

                // トランザクション開始
                $this->connection->begin();

                // 排他制御
                $this->Others
                    ->find('all', ['conditions' => ['id' => $id]])
                    ->modifier('SQL_NO_CACHE')
                    ->epilog('FOR UPDATE')
                    ->first();

                // 登録処理
                $ret = $this->Others->save($other);
                if (!$ret) {
                    throw new DatabaseException;
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

            // 一覧画面へリダイレクト
            $this->session->write('message', 'その他を変更しました。');
            return $this->redirect(['action' => 'edit', $id]);
        }

        // viewに渡すデータセット
        $this->set('other', $other);
    }

    /**
     * 順番の入れ替え
     * 
     * @return Response|void|null
     * 
     * @throws DatabaseException
     */
    public function order()
    {
        // ログインユーザーidからその他のレコードを取得
        $others = $this->Others->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->order(['others_order' => 'asc']);

        if ($this->request->is(['patch', 'post', 'put'])) {
            // postの場合

            // リクエストデータ取得
            $data = $this->request->getData();

            // 更新データ作成
            $save_data = [];
            foreach ($data['id'] as $index => $item) {
                $save_data[] =  [
                    'id' => $item,
                    'others_order' => $data['order'][$index]
                ];
            }

            try {

                // トランザクション開始
                $this->connection->begin();

                // 排他制御
                $others->modifier('SQL_NO_CACHE')->epilog('FOR UPDATE')->toArray();

                // 一括更新
                $others = $this->Others->patchEntities($others, $save_data);
                $others = $this->Others->saveMany($others);
                if (!$others) {
                    throw new DatabaseException;
                }

                // コミット
                $this->connection->commit();
            } catch (DatabaseException $e) {

                // ロールバック
                $this->connection->rollback();

                // 一覧画面へ遷移
                $this->session->write('message', '設定の更新が失敗しました。');
                return $this->redirect(['action' => 'index']);
            }

            // 並び順変更ページに遷移
            $this->session->write('message', '設定を反映しました。');
            return $this->redirect(['action' => 'order']);
        }

        // viewに渡すデータセット
        $this->set('others', $others);
    }

    /**
     * 削除
     * 
     * @param int $id
     * 
     * @return Response|void|null
     * 
     * @throws DatabaseException
     */
    public function delete($id = null)
    {
        // idとログインユーザーidから実績のレコードを取得
        $other = $this->Others->find('all', ['conditions' => ['id' => $id, 'user_id' => $this->AuthUser->id]])->first();

        // 不正なアクセスの場合は一覧画面へリダイレクト
        if (!$other) {
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['post', 'patch', 'put'])) {
            try {

                // トランザクション開始
                $this->connection->begin();

                // 排他制御
                $this->Others
                    ->find('all', ['conditions' => ['id' => $other->id]])
                    ->modifier('SQL_NO_CACHE')
                    ->epilog('FOR UPDATE')
                    ->first();

                // 削除処理
                $ret = $this->Others->delete($other);
                if (!$ret) {
                    throw new DatabaseException;
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
        $this->session->write('message', 'その他を一件、削除しました。');
        return $this->redirect(['action' => 'index']);
    }
}
