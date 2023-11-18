<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;

/**
 * CreateUsers Controller
 *
 * @method \App\Model\Entity\CreateUser[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CreateUsersController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        // 使用するモデル
        $this->Users = TableRegistry::getTableLocator()->get('Users');

        // トランザクション用の変数
        $this->connection = $this->Users->getConnection();
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        // ユーザー作成済であればアクセスさせない
        if ($this->AuthUser->autherized_flg == 1) {
            return $this->redirect('/admin');
        }
    }

    public function create()
    {
        $this->viewBuilder()->disableAutoLayout();

        $user = $this->Users->newEmptyEntity();

        if ($this->request->is(['patch', 'post', 'put'])) {

            $data = $this->request->getData();

            // バリデーション処理
            $user = $this->Users->patchEntity($user, $data);

            if ($data['password'] != $data['re_password']) {
                $user->setError('password', ['入力されたパスワードと一致しません。']);
                $user->setError('re_password', ['入力されたパスワードと一致しません。']);
            }

            if ($user->getErrors()) {
                $this->set('user', $user);

                return;
            }

            $this->session->write('create_user_data', $data);
            return $this->redirect(['action' => 'confirm']);
        }

        $this->set('user', $user);
    }

    public function confirm()
    {
        if (!$this->session->check('create_user_data')) {
            return $this->redirect(['action' => 'create']);
        }
    }
}
