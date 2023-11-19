<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller\Admin;

use Authentication\Controller\Component\AuthenticationComponent;
use Cake\Controller\Controller;
use Cake\Event\EventInterface;

/**
 * 管理画面用コントローラー
 *
 * @property AuthenticationComponent $Authentication
 */
class AppController extends Controller
{
    
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');

        // 認証プラグイン
        $this->loadComponent('Authentication.Authentication');

        // sessionをグローバルで使用できる設定
        $session = $this->session = $this->getRequest()->getSession();
        $this->set('session', $session);

        // ログインユーザー情報をグローバルで使用できる設定
        $auth = $this->AuthUser = $this->request->getSession()->read('Auth');
        $this->set('auth', $auth);

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }

    /**
     * @param EventInterface $event
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        /** ユーザー作成済かどうか判定する */
        // ログインしてなくてもアクセスできるので、ここでログイン済か確認する
        if ($this->session->check('Auth')) {

            // アクセスされたメソッドがCreateUsersかUsers::logoutか確認
            if (
                $this->request->getParam('controller') == 'CreateUsers' ||
                $this->request->getParam('controller') == 'Users' && $this->request->getParam('action') == 'logout'
            ) {
                $flg = false;
            } else {
                $flg = true;
            }

            // ユーザー未作成またはログアウト以外
            if ($flg) {

                // ユーザー未作成であれば強制的にユーザー作成画面へ遷移
                if ($this->AuthUser->autherized_flg == 0) {
                    return $this->redirect(['controller' => 'CreateUsers', 'action' => 'create']);
                }
            }
        }
    }
}
