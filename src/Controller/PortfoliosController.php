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

        if (is_null($user) || $user->autherized_flg    == 0) {
            return $this->redirect('/');
        }

        $profile = $this->Profiles->find('all', ['conditions' => ['user_id' => $user->id]])->first();

        $site = $this->Sites->find('all', ['conditions' => ['user_id' => $user->id]])->first();

        // viewに渡すデータセット
        $this->set('profile', $profile);
        $this->set('site', $site);
        $this->set('username', $username);
        // 画像のパス
        $this->set('profile_image', $this->profile_image($profile, $username));
        $this->set('favicon', $this->favicon($site, $username));
        $this->set('header_image', $this->header_image($site, $username));
    }

    /**
     * プロフィール画像
     * 
     * @param object $entity
     * @param string $username
     * 
     * @return string
     */
    private function profile_image($entity, $username)
    {
        if (is_null($entity->image_path) || !file_exists(ProfilesTable::ROOT_PROFILE_IMAGE_PATH)) {
            return ProfilesTable::BLANK_PROFILE_IMAGE_PATH;
        } else {
            return ProfilesTable::PROFILE_IMAGE_PATH .  $username . '/' . $entity->image_path;
        }
    }

    /**
     * ファビコン
     * 
     * @param object $entity
     * @param string $username
     * 
     * @return string|null
     */
    private function favicon($entity, $username)
    {
        if (is_null($entity->favicon_path) || !file_exists(SitesTable::ROOT_FAVICON_PATH)) {
            return null;
        } else {
            return SitesTable::FAVICON_PATH . $username . '/' . $entity->favicon_path;
        }
    }

    /**
     * ヘッダー画像
     * 
     * @param object $entity
     * @param string $username
     * 
     * @return string
     */
    private function header_image($entity, $username)
    {
        if (is_null($entity->header_image_path) || !file_exists(SitesTable::ROOT_HEADER_IMAGE_PATH)) {
            return SitesTable::BLANK_HEADER_IMAGE_PATH;
        } else {
            return SitesTable::HEADER_IMAGE_PATH . $username . '/' . $entity->header_image_path;
        }
    }
}
