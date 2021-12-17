<?php

declare(strict_types=1);

namespace Damsv2\Controller;

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

    public function deny()
    {
        $this->redirect(['plugin' => null, 'controller' => 'Home', 'action' => 'home']);
    }

    /**
     * Home method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function home()
    {
        //load custom home layout
        $this->viewBuilder()->setLayout('damsHome');

        //chart data
        $CountSmes = $this->getTableLocator()->get('Damsv2.CountSmesAltfinal');
        $result_graph = $CountSmes
            ->find()
            ->where(['total_nbr_of_SMEs > ' => 100000])
            ->order(['total_nbr_of_SMEs' => 'ASC'])->toArray();

        $this->set('result_graph', $result_graph);

        //table data
        $analytics = $this->getTableLocator()->get('Damsv2.SummaryTable');
        $result_summary_table = $analytics->find()->all()->toArray();
        $this->set('result_summary_table', $result_summary_table);
    }
}
