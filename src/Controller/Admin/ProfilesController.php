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
        // エンティティの作成
        $profiles = $this->Profiles->find('all', ['conditions' => ['user_id' => $this->AuthUser->id]])->first();

        $this->set('profiles', $profiles);
    }
}
