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
class AlertbatchController extends AppController
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

    function alertbatch(){
		$sasResult1 = $this->SAS->curl("F_MaturityBatch.sas",array(),false);

		$this->set('sas1',$sasResult1);

		$this->set('tables', $this->SAS->get_all_tables_from_webout (utf8_encode($sasResult1)));
	}

}
