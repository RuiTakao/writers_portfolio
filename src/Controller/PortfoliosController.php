<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Table\ContactsTable;
use App\Model\Table\HistoriesTable;
use App\Model\Table\MailFormsTable;
use App\Model\Table\OthersTable;
use App\Model\Table\ProfilesTable;
use App\Model\Table\SitesTable;
use App\Model\Table\UsersTable;
use App\Model\Table\WorksTable;
use Cake\Mailer\Mailer;
use Cake\ORM\TableRegistry;

/**
 * Portfolios Controller
 *
 * @param UsersTable $Users
 * @param ProfilesTable $Profiles
 * @param SitesTable $Sites
 * @param HistoriesTable $Histories
 * @param WorksTable $Works
 * @param OthersTable $Others
 * @param ContactsTable $Contacts
 * @param MailFormsTable $MailForms
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
        $this->Histories = TableRegistry::getTableLocator()->get('Histories');
        $this->Works = TableRegistry::getTableLocator()->get('Works');
        $this->Others = TableRegistry::getTableLocator()->get('Others');
        $this->Contacts = TableRegistry::getTableLocator()->get('Contacts');
        $this->MailForms = TableRegistry::getTableLocator()->get('MailForms');
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

        if (is_null($user) || $user->autherized_flg == 0) {
            return $this->redirect('/');
        }

        // if ($this->request->is('post')) {
        //     $mailer = new Mailer();
        //     $mailer->setEmailFormat('text')
        //         ->setTo('ruia1082halfnc@gmail.com')
        //         ->setFrom(['ruia1082halfnc@gmail.com' => 'fromの名前をここに入れる'])
        //         ->setSubject('件名をここに入れる');

        //     $mailer->deliver();

        //     return $this->redirect(['action' => 'index', $username]);
        // }

        $profile = $this->Profiles->find('all', ['conditions' => ['user_id' => $user->id]])->first();

        $site = $this->Sites->find('all', ['conditions' => ['user_id' => $user->id]])->first();

        $mailForms = $this->MailForms->find('all', ['conditions' => ['user_id' => $user->id]])->first();

        $histories = $this->Histories->find('all', ['conditions' => ['user_id' => $user->id]])->order(['history_order' => 'asc']);

        $works = $this->Works->find('all', ['conditions' => ['user_id' => $user->id]])->order(['works_order' => 'asc']);

        $others = $this->Others->find('all', ['conditions' => ['user_id' => $user->id]])->order(['others_order' => 'asc']);

        $contacts = $this->Contacts->find('all', ['conditions' => ['user_id' => $user->id]])->order(['contacts_order' => 'asc']);

        // viewに渡すデータセット
        $this->set('profile', $profile);
        $this->set('site', $site);
        $this->set('histories', $histories);
        $this->set('works', $works);
        $this->set('others', $others);
        $this->set('contacts', $contacts);
        $this->set('mailForms', $mailForms);
        $this->set('username', $username);
        // 画像のパス
        $this->set('profile_image', $this->profile_image($profile, $username));
        $this->set('favicon', $this->favicon($site, $username));
        $this->set('header_image', $this->header_image($site, $username));
        $this->set('header_image_sp', $this->header_image_sp($site, $username));
        $this->set('works_image_path', WorksTable::WORKS_IMAGE_PATH);
        $this->set('root_works_image_path', WorksTable::ROOT_WORKS_IMAGE_PATH);
        $this->set('contacts_image_path', ContactsTable::WORKS_IMAGE_PATH);
        $this->set('root_contacts_image_path', ContactsTable::ROOT_WORKS_IMAGE_PATH);
    }

    /**
     * プロフィール画像
     *
     * @param object $entity
     * @param string $username
     *
     * @return string|null
     */
    private function profile_image($entity, $username)
    {
        if (is_null($entity->image_path) || !file_exists(ProfilesTable::ROOT_PROFILE_IMAGE_PATH)) {
            return null;
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
     * @return string|null
     */
    private function header_image($entity, $username)
    {
        if (is_null($entity->header_image_path) || !file_exists(SitesTable::ROOT_HEADER_IMAGE_PATH)) {
            return null;
        } else {
            return SitesTable::HEADER_IMAGE_PATH . $username . '/' . $entity->header_image_path;
        }
    }

    /**
     * ヘッダー画像（モバイルサイズ）
     *
     * @param object $entity
     * @param string $username
     *
     * @return string|null
     */
    private function header_image_sp($entity, $username)
    {
        if (is_null($entity->header_image_sp_path) || !file_exists(SitesTable::ROOT_HEADER_IMAGE_SP_PATH)) {
            return null;
        } else {
            return SitesTable::HEADER_IMAGE_SP_PATH . $username . '/' . $entity->header_image_sp_path;
        }
    }
}
