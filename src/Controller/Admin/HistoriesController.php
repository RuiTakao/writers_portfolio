<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Model\Table\HistoriesTable;
use Cake\Core\Configure;
use Cake\Database\Exception\DatabaseException;
use Cake\Http\Response;
use Cake\ORM\TableRegistry;

/**
 * Histories Controller
 *
 * @property HistoriesTable $Histories
 */
class HistoriesController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();

        // 利用するモデル
        $this->Histories = TableRegistry::getTableLocator()->get('Histories');

        // トランザクション変数
        $this->connection = $this->Histories->getConnection();
    }

    /**
     * 一覧画面
     *
     * @return Response|null|void
     */
    public function index()
    {

        // 登録用のエンティティ
        $historie = $this->Histories->newEmptyEntity();

        // 表示用に履歴一覧取得
        $histories = $this->Histories
            ->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])
            ->order(['history_order' => 'asc']);

        // 挿入箇所指定用
        $add_place["end"] = "末尾に追加";
        foreach ($histories as $key => $value) {
            $add_place[$key + 1] = $key + 1 . "行目に追加";
        }

        if ($this->request->is('post')) {

            // リクエストデータ取得
            $data = $this->request->getData();

            $data['user_id'] = $this->AuthUser->id;

            // 更新用の履歴データを取得
            $histories = $this->Histories
                ->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])
                ->order(['history_order' => 'asc'])
                ->toArray();

            // 履歴データのオーダー順を整列させる
            for ($i = 0; $i < count($histories); $i++) {
                $histories[$i]['history_order'] = $i + 1;
            }

            // 追加位置が指定の場合は追加位置を飛ばして整列し直す
            if (is_numeric($data['add_place'])) {
                for ($i = 0; $i < count($histories); $i++) {
                    if (intval($data['add_place']) <= intval($histories[$i]['history_order'])) {
                        $histories[$i]['history_order']++;
                    }
                }

                $data['history_order'] = $data['add_place'];
            } else if ($data['add_place'] == "end") {
                $data['history_order'] = count($histories) + 1;
            }

            unset($data['add_place']);

            if ($data['to_now'] == "1") {
                $data['end'] = date('Y-m');
            }

            // エンティティにデータをセット
            $historie = $this->Histories->patchEntity($historie, $data);

            /**
             * 日付のバリデーション
             */
            if ($data['start'] == "" ||  $data['end'] == "") {
                $historie->setError('start', ['日付が未入力です。']);
            } elseif (
                strtotime($data['start']) > strtotime($data['end']) ||
                strtotime($data['end']) > strtotime(date('Y-m'))
            ) {
                $historie->setError('start', ['日付が無効です。']);
            }

            // バリデーション処理
            if ($historie->getErrors()) {
                $this->set('historie', $historie);
                $this->set('histories', $histories);
                $this->set('add_place', $add_place);
                $this->session->write('message', Configure::read('alert_message.input_faild'));
                return;
            }

            array_push($histories, $historie);

            try {

                // トランザクション開始
                $this->connection->begin();

                // 排他制御
                $this->Histories
                    ->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])
                    ->modifier('SQL_NO_CACHE')
                    ->epilog('FOR UPDATE')
                    ->toArray();

                $ret = $this->Histories->saveMany($histories);
                if (!$ret) {
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

            // 一覧画面へリダイレクト
            $this->session->write('message', '経歴' . Configure::read('alert_message.add'));
            return $this->redirect(['action' => 'index']);
        }

        $this->set('historie', $historie);
        $this->set('histories', $histories);
        $this->set('add_place', $add_place);
    }

    /**
     * 編集
     * 
     * @return Response|void|null
     */
    public function edit($id)
    {

        // idとログインユーザーidから実績のレコードを取得
        $historie = $this->Histories->find('all', ['conditions' => ['id' => $id, 'user_id' => $this->AuthUser->id]])->first();

        // 不正なアクセスの場合は一覧画面へリダイレクト
        if (!$historie) {
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['post', 'patch', 'put'])) {

            // リクエストデータ取得
            $data = $this->request->getData();

            if ($data['to_now'] == "1") {
                $data['end'] = date('Y-m');
            }

            // エンティティにデータをセット
            $historie = $this->Histories->patchEntity($historie, $data);

            /**
             * 日付のバリデーション
             */
            if ($data['start'] == "" ||  $data['end'] == "") {
                $historie->setError('start', ['日付が未入力です。']);
            } elseif (
                strtotime($data['start']) > strtotime($data['end']) ||
                strtotime($data['end']) > strtotime(date('Y-m'))
            ) {
                $historie->setError('start', ['日付が無効です。']);
            }

            // バリデーション処理
            if ($historie->getErrors()) {
                $this->set('historie', $historie);
                $this->session->write('message', Configure::read('alert_message.input_faild'));
                return;
            }

            try {

                // トランザクション開始
                $this->connection->begin();

                // 排他制御
                $this->Histories
                    ->find('all', ['conditions' => ['id' => $historie->id]])
                    ->modifier('SQL_NO_CACHE')
                    ->epilog('FOR UPDATE')
                    ->first();

                $ret = $this->Histories->save($historie);
                if (!$ret) {
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

            // 一覧画面へリダイレクト
            $this->session->write('message', Configure::read('alert_message.complete'));
            return $this->redirect(['action' => 'index']);
        }

        $this->set('historie', $historie);
    }

    /**
     * 削除
     * 
     * @property int $id
     * 
     * @return Response|void|null
     */
    public function delete($id = null)
    {
        // idとログインユーザーidから実績のレコードを取得
        $historie = $this->Histories->find('all', ['conditions' => ['id' => $id, 'user_id' => $this->AuthUser->id]])->first();

        try {

            // トランザクション開始
            $this->connection->begin();

            // 更新用の履歴データを取得
            $histories = $this->Histories
                ->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])
                ->toArray();

            // 排他制御
            $this->Histories
                ->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])
                ->modifier('SQL_NO_CACHE')
                ->epilog('FOR UPDATE')
                ->toArray();

            for ($i = 0; $i < count($histories); $i++) {
                if (intval($historie->history_order) <= intval($histories[$i]['history_order'])) {
                    $histories[$i]['history_order'] = intval($histories[$i]['history_order']) - 1;
                }
            }

            // 順番の更新
            $ret = $this->Histories->saveMany($histories);
            if (!$ret) {
                throw new DatabaseException;
            }

            // 削除処理
            $ret = $this->Histories->delete($historie);
            if (!$ret) {
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

        $this->session->write('message', Configure::read('alert_message.delete'));
        return $this->redirect(['action' => 'index']);
    }
}
