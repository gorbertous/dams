<?php

declare(strict_types=1);

namespace Dsr\Controller;

use Cake\Event\EventInterface;


/**
 * Home Controller
 *
 */
class HomeController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        //$this->loadComponent('Security');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        //$this->Security->setConfig('blackHoleCallback', 'blackhole');
    }

    /**
     * Home method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function home()
    {
//        $this->viewBuilder()->setLayout('dams');
    }

}
