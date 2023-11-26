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
        $historie = $this->Histories->newEmptyEntity();
        $histories = $this->Histories
            ->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])
            ->order(['history_order' => 'asc']);

        if ($this->request->is('post')) {

            // リクエストデータ取得
            $data = $this->request->getData();

            // ログインユーザーのIDを追加
            // $data['user_id'] = $this->AuthUser->id;

            if ($data['add_place'] == "end") {
                // 末尾に追加の場合

                $history_order_array = [];
                foreach ($histories as $value) {
                    array_push($history_order_array, intval($value->history_order));
                }
            } else {
                // 行数指定の場合

                $history_order_array = [];
                foreach ($histories as $key => $value) {
                    $history_order_array[] = [
                        'id' => $value->id,
                        'history_order' => $key + 1
                    ];
                }
                foreach ($history_order_array as $key => $value) {
                    if (intval($value['history_order']) > intval($data['add_place'])) {
                        $value['history_order']++;
                        $history_order_array[$key]['history_order'] = $value['history_order'];
                    }
                }
                $data['history_order'] = $data['add_place'];
                $data['user_id'] = $this->AuthUser->id;
                $historie = $this->Histories->patchEntity($historie, $data);
                $this->Histories->save($historie);
                $histories = $this->Histories->patchEntities($histories , $history_order_array);
                $this->Histories->saveMany($histories);
            }

            

            // try {
            //     $this->connection->begin();

            //     $historie = $this->Histories->patchEntity($historie, $data);
            //     $ret = $this->Histories->save($historie);
            //     if (!$ret) {
            //         throw new DatabaseException;
            //     }

            //     $this->connection->commit();
            // } catch (DatabaseException $e) {

            //     // ロールバック
            //     $this->connection->rollback();
            //     $this->session->write('message', '');
            //     return $this->redirect(['action' => 'index']);
            // }

            return $this->redirect(['action' => 'index']);
        }

        $add_order["end"] = "末尾に追加";
        foreach ($histories as $key => $value) {
            $add_order[$key + 1] = $key + 1 . "行目に追加";
        }
        array_pop($add_order);
        $this->set('add_order', $add_order);
        $this->set('historie', $historie);
        $this->set('histories', $histories);
    }

    public function add()
    {
    }

    public function edit()
    {
    }
}
