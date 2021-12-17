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
class RolloverRepaymentsController extends AppController
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

    function dash(){
		if (Cache::read("automatic_fixing", 'treasury'))
		{
			$this->Session->setFlash("Process of the automatic interest fixing is currently running. Your modification on the transactions could create a conflict in the database. Please try again in 5 minutes.", 'flash/warning');
		}
		$this->set('UserIsInCheckerGroup', $this->UserPermissions->UserIsInCheckerGroup());
		$this->view = '/RolloverRepayment/rollover_repayment';

		//FILTERS UPDATE
		if($this->request->is('post')){
			$this->request->params['named']['page'] = 1;

			if($this->Session->read('Form.data.Transaction.mandate_ID')){
				if($this->request->data['Transaction']['mandate_ID']!=$this->Session->read('Form.data.Transaction.mandate_ID')){
					unset($this->request->data['Transaction']['cpty_id']);
					unset($this->request->data['Transaction']['cmp_ID']);
				}
			}
			$this->Session->write('Form.data', $this->request->data);
		}
		$conditions = array(
			'Transaction.tr_type'	=>	array('Deposit','Rollover'),
			'Transaction.tr_state'	=>  array('Confirmed','First Notification','Second Notification'),
			'Transaction.depo_type !=' 	=>  'Callable',
		);
		
		if($this->Session->read('Form.data.Transaction.mandate_ID'))
			$conditions['Transaction.mandate_ID'] = $this->Session->read('Form.data.Transaction.mandate_ID');
		if($this->Session->read('Form.data.Transaction.cpty_id'))
			$conditions['Transaction.cpty_id'] = $this->Session->read('Form.data.Transaction.cpty_id');
		if($this->Session->read('Form.data.Transaction.cmp_ID'))
			$conditions['Transaction.cmp_ID'] = $this->Session->read('Form.data.Transaction.cmp_ID');
		if($this->Session->read('Form.data.Transaction.maturity_date'))
			$conditions['Transaction.maturity_date'] = date('Y-m-d', strtotime(str_replace('/','-',$this->Session->read('Form.data.Transaction.maturity_date'))));
		

		//get mandates
		$this->set('instr_mandates', $this->Mandate->getMandateList());

		//get counterparties and cmp based on mandate
		$this->set('instr_counterparties', !empty($conditions['Transaction.mandate_ID']) ? $this->Mandate->getcptybymandate($conditions['Transaction.mandate_ID']) : array());
		$this->set('instr_cmp', !empty($conditions['Transaction.mandate_ID']) ? $this->Compartment->getcmpbymandate($conditions['Transaction.mandate_ID']) : array());

		//CALCULATED FIELDS
		$this->Transaction->virtualFields  =  array(
			'amountccy' => "CONCAT('Transaction.amount',' ','AccountA.ccy')",
			'interest'	=> "CONCAT(Transaction.total_interest,' ',AccountA.ccy)",
			'tax'	=> "CONCAT(Transaction.tax_amount,' ',AccountA.ccy)",
		);

		//RESULTS
		$this->Paginator->settings = array(
			'limit' => 10,
	        'conditions' => $conditions,
			'recursive'	=> 1,
		);
		
		ini_set('precision', 20);

		$res = array();
	    $trns = $this->Paginator->paginate('Transaction');
	    if(!empty($trns)) foreach($trns as $key=>&$trn){
	    	$amountsAvailable = $this->Transaction->computeReinvGroup(array($trn['Transaction']['tr_number']));
	    	$line = array('raw'=>$trn);
	    	$line['trn'] = !empty($trn['Transaction']['tr_number'])?$trn['Transaction']['tr_number']:'';
	    	$line['reinv_group'] = !empty($trn['Transaction']['reinv_group'])?$trn['Transaction']['reinv_group']:'';
	    	$line['di'] = !empty($trn['Instruction']['instr_num'])?$trn['Instruction']['instr_num']:'';
	    	$line['cmp'] = !empty($trn['Compartment']['cmp_name'])?$trn['Compartment']['cmp_name']:'';
	    	$line['cmp_ID'] = !empty($trn['Compartment']['cmp_name'])?$trn['Compartment']['cmp_ID']:'';
	    	$line['cpty_id'] = !empty($trn['Transaction']['cpty_id'])?$trn['Transaction']['cpty_id']:'';
	    	$line['mandate'] = !empty($trn['Mandate']['mandate_ID'])?$trn['Mandate']['mandate_ID']:'';
	    	$line['availability_date'] = !empty($trn['Transaction']['maturity_date'])?$trn['Transaction']['maturity_date']:'';
	    	$line['ccy'] = !empty($trn['AccountA']['ccy'])?$trn['AccountA']['ccy']:'';
	    	$line['principal'] = !empty($trn['Transaction']['amount'])?$trn['Transaction']['amount']:0;
	    	$line['interest'] = !empty($trn['Transaction']['total_interest'])?$trn['Transaction']['total_interest']:0;
	    	$line['tax'] = !empty($trn['Transaction']['tax_amount'])?$trn['Transaction']['tax_amount']:0;
	    	$line['scheme'] = !empty($trn['Transaction']['scheme'])?$trn['Transaction']['scheme']:'';
	    	$line['depo_term'] = !empty($trn['Transaction']['depo_term'])?$trn['Transaction']['depo_term']:'';
	    	$line['depo_type'] = !empty($trn['Transaction']['depo_type'])?$trn['Transaction']['depo_type']:'';
	    	$line['accountA_amount'] = !empty($amountsAvailable['amountInA'])?$amountsAvailable['amountInA']:0;
	    	$line['accountB_amount'] = !empty($amountsAvailable['amountInB'])?$amountsAvailable['amountInB']:0;
	    	$line['accountA_IBAN'] = !empty($trn['AccountA']['IBAN'])?$trn['AccountA']['IBAN']:'';
	    	$line['accountB_IBAN'] = !empty($trn['AccountB']['IBAN'])?$trn['AccountB']['IBAN']:'';
	    	$line['actions'] = array();
			
			$line['principal'] = floatval($line['principal']);
			$line['interest'] = floatval($line['interest']);
			$line['tax'] = floatval($line['tax']);

	    	//rollover P+I
	    	if(in_array($line['scheme'], array('AA', 'BB'))){
	    		$line['actions']['rollover_pi'] = array(
		    		'rollover'=>number_format($line['principal']+$line['interest']-$line['tax'], 2, '.', ',')
		    	);
	    	}
	    	//rollover P
	    	if(in_array($line['scheme'], array('AA', 'BB', 'AB'))){
	    		$line['actions']['rollover_p'] = array(
		    		'rollover'=>number_format($line['principal'], 2, '.', ','),
		    		'repayment'=>number_format($line['interest']-$line['tax'], 2, '.', ','),
		    	);
	    	}
	    	//partial rollover
	    	if(in_array($line['scheme'], array('AA', 'BB', 'AB'))){
	    		$line['actions']['partial_rollover'] = array(
		    		'rollover'=>0,
		    		'repayment'=>number_format($line['principal']+$line['interest']-$line['tax'], 2, '.', ','),
		    	);
		    	if($line['scheme']=='AB'){
		    		$line['actions']['partial_rollover']['repayment'] = number_format($line['principal'], 2, '.', ',');
		    		$line['actions']['partial_rollover']['repayment_b'] = number_format($line['interest']-$line['tax'], 2, '.', ',');
		    	}
	    	}

	    	//partial repayment
	    	if(in_array($line['scheme'], array('AA', 'BB', 'AB'))){
	    		$line['actions']['partial_repayment'] = array(
	    			'repayment'=>0,
		    		'rollover'=>number_format($line['principal']+$line['interest']-$line['tax'], 2, '.', ','),
		    	);
		    	if($line['scheme']=='AB'){
		    		$line['actions']['partial_repayment']['rollover'] = number_format($line['principal'], 2, '.', ',');
		    		$line['actions']['partial_repayment']['repayment_b'] = number_format($line['interest']-$line['tax'], 2, '.', ',');
		    	}
	    	}
	    	
	    	$res[] = $line;
	    }
		

	    $this->set('reinv', $res);
	}

	function reinv_open(){
		header('Content-type: application/json');

		if(!empty($this->request->data)){
			$reinv = array();
			$reinv['incoming'] = array($this->request->data['Transaction']['tr_number']);
			$reinv['amountsAvailable'] = $this->Transaction->computeReinvGroup($reinv['incoming']);
			$reinv['accounts'] = $this->Compartment->find('first', array(
				'conditions' => array('Compartment.cmp_ID' => $this->request->data['Transaction']['cmp_ID']),
				'fields' => array(
					'Compartment.accountA_IBAN as accountA',
					'Compartment.accountB_IBAN as accountB',
					'AccountA.ccy as ccy'
				),
			));
			$reinv['mandate_ID'] = $this->request->data['Transaction']['mandate_ID'];
			$reinv['cmp_ID'] = $this->request->data['Transaction']['cmp_ID'];
			$reinv['cpty_id'] = $this->request->data['Transaction']['cpty_id'];
			$reinv['availability_date'] = $this->request->data['Transaction']['availability_date'];

			App::import('Treasury.Controller', 'TreasuryreinvestmentsController');
			$ReinvestmentsController = new TreasuryreinvestmentsController;
	    	$result = $ReinvestmentsController->open($reinv);
	    	die(json_encode($result));
		}
		
		die(json_encode(array('success'=>false)));
	}

	function reinv_close(){
		header('Content-type: application/json');

		if(!empty($this->request->data['Transaction']['reinv_group'])){
			App::import('Treasury.Controller', 'TreasuryreinvestmentsController');
			$ReinvestmentsController = new TreasuryreinvestmentsController;
	    	$result = $ReinvestmentsController->close($this->request->data['Transaction']['reinv_group']);
	    	die(json_encode($result));
		}
		
		die(json_encode(array('success'=>false)));
	}

	function reinv_op(){
		header('Content-type: application/json');
		if(!empty($this->request->data['reinv_op']['controller_action'])){

			App::import('Treasury.Controller', 'TreasurytransactionsController');
			$TransactionsController = new TreasurytransactionsController;

			// Rollover
			if($this->request->data['reinv_op']['controller_action']=='newrollover'){
				$result = $TransactionsController->newrollover($this->request->data);
		    	die(json_encode($result));
			}elseif($this->request->data['reinv_op']['controller_action']=='newrepayment'){
				$result = $TransactionsController->newrepayment($this->request->data);
		    	die(json_encode($result));
			}
		}else{
			die(json_encode(array('success'=>false, 'msg'=>'Unhandled action')));
		}
		
		die(json_encode(array('success'=>false)));
	}

	function callLimitBreach(){
		header('Content-type: application/json');

		$this->request->data['linenum'] = 1;
		App::import('Treasury.Controller', 'TreasuryajaxController');
		$TransactionsController = new TreasuryajaxController;
		
		$result = $TransactionsController->checkLimitBreachAmount($this->request->data);
    	die(json_encode($result));
		
		
		die(json_encode(array('success'=>false)));
	}

}
