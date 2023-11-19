<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Table\ProfilesTable;
use App\Model\Table\SitesTable;
use App\Model\Table\UsersTable;
use Cake\ORM\TableRegistry;

/**
 * Portfolios Controller
 *
 * @param UsersTable $Users
 * @param ProfilesTable $Profiles
 * @param SitesTable $Sites
 */
class PortfoliosController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();

        // 使用するモデル
        $this->Users = TableRegistry::getTableLocator()->get('Users');
        $this->Profiles = TableRegistry::getTableLocator()->get('Profiles');
        $this->Sites = TableRegistry::getTableLocator()->get('Sites');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index($username)
    {
        $this->viewBuilder()->disableAutoLayout();

        $user = $this->Users->find('all', ['conditions' => ['username' => $username]])->first();

        if (is_null($user) || $user->autherized_flg	== 0) {
            return $this->redirect('/');
        }

        $profile = $this->Profiles->find('all', ['conditions' => ['user_id' => $user->id]])->first();

        $site = $this->Sites->find('all', ['conditions' => ['user_id' => $user->id]])->first();

        $this->set('profile', $profile);
        $this->set('site', $site);
        $this->set('username', $username);
    }
}
