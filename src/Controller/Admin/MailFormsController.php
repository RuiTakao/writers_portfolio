<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

/**
 * MailForms Controller
 *
 * @property \App\Model\Table\MailFormsTable $MailForms
 * @method \App\Model\Entity\MailForm[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MailFormsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users'],
        ];
        $mailForms = $this->paginate($this->MailForms);

        $this->set(compact('mailForms'));
    }
}
