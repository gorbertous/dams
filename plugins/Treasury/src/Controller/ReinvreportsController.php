<?php

declare(strict_types=1);

namespace Treasury\Controller;

use Cake\Event\EventInterface;

/**
 * Taxes Controller
 *
 * @property \Treasury\Model\Table\TaxesTable $Taxes
 * @method \Treasury\Model\Entity\Tax[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReinvreportsController extends AppController
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

    function openedreport()
    {

        $reinv_group = $this->request->data['reinvestreportformo']['opened'];

        $sasResult = $this->SAS->curl("F_ReinvestmentReport.sas", array("reinv_group" => $reinv_group), false);

        $this->set('sas', utf8_encode($sasResult));
        $this->set('table1', $this->SAS->get_ith_table_from_webout(utf8_encode($sasResult), 1));
        $this->set('table2', $this->SAS->get_ith_table_from_webout(utf8_encode($sasResult), 4));
        $this->set('table3', $this->SAS->get_ith_table_from_webout(utf8_encode($sasResult), 7));

        $this->layout = 'ajax';
    }

    function closedreport()
    {

        $reinv_group = $this->request->data['reinvestreportformc']['closed'];

        $sasResult = $this->SAS->curl("F_ReinvestmentReport.sas", array("reinv_group" => $reinv_group), false);

        $this->set('sas', utf8_encode($sasResult));
        $this->set('table1', $this->SAS->get_ith_table_from_webout(utf8_encode($sasResult), 1));
        $this->set('table2', $this->SAS->get_ith_table_from_webout(utf8_encode($sasResult), 4));
        $this->set('table3', $this->SAS->get_ith_table_from_webout(utf8_encode($sasResult), 7));

        $this->layout = 'ajax';
    }
}
