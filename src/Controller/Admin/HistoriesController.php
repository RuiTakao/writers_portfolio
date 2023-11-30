<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Model\Table\HistoriesTable;
use Cake\Database\Exception\DatabaseException;
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
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {

        // 登録用のエンティティ
        $historie = $this->Histories->newEmptyEntity();

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

            // エンティティにデータをセット
            $historie = $this->Histories->patchEntity($historie, $data);

            // バリデーション処理
            if ($historie->getErrors()) {
                $this->session->write('message', '入力に不備があります。');
                return $this->redirect(['action' => 'index']);
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
                $this->session->write('message', '登録に失敗しました。');
                return $this->redirect(['action' => 'index']);
            }

            return $this->redirect(['action' => 'index']);
        }

        // 表示用に履歴一覧取得
        $histories = $this->Histories
            ->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])
            ->order(['history_order' => 'asc']);

        // 挿入箇所指定用
        $add_place["end"] = "末尾に追加";
        foreach ($histories as $key => $value) {
            $add_place[$key + 1] = $key + 1 . "行目に追加";
        }

        $this->set('historie', $historie);
        $this->set('histories', $histories);
        $this->set('add_place', $add_place);
    }

    public function add()
    {
    }

    public function edit()
    {
    }
}
