<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * LandingPages Controller
 *
 * @method \App\Model\Entity\LandingPage[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LandingPagesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->viewBuilder()->disableAutoLayout();
    }
}
