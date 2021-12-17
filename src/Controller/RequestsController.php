<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Home Controller
 *
 */
class RequestsController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        //$this->loadComponent('Security');
        $this->loadComponent('RequestHandler');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        //$this->Security->setConfig('blackHoleCallback', 'blackhole');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        //load custom home layout
        //$this->viewBuilder()->setLayout('damsHome');
        $requests = $_SESSION['PROFILER_DATA'] ?? [];
        $this->set(compact('requests'));
    }
    public function reset() {
        $_SESSION['PROFILER_DATA'] = [];
        $this->redirect(['action' => 'index']);
    }
}
