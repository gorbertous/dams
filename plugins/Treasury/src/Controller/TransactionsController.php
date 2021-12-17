<?php

declare(strict_types=1);

namespace Treasury\Controller;

use Cake\Event\EventInterface;

/**
 * Transactions Controller
 *
 * @property \Treasury\Model\Table\TransactionsTable $Transactions
 * @method \Treasury\Model\Entity\Transaction[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TransactionsController extends AppController
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
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Originals', 'ParentTransactions', 'Cpties'],
        ];
        $transactions = $this->paginate($this->Transactions);

        $this->set(compact('transactions'));
    }

    /**
     * View method
     *
     * @param string|null $id Transaction id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $transaction = $this->Transactions->get($id, [
            'contain' => ['Originals', 'ParentTransactions', 'Cpties', 'Bonds', 'LimitBreaches', 'HistoPs', 'ChildTransactions'],
        ]);

        $this->set(compact('transaction'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $transaction = $this->Transactions->newEmptyEntity();
        if ($this->request->is('post')) {
            $transaction = $this->Transactions->patchEntity($transaction, $this->request->getData());
            if ($this->Transactions->save($transaction)) {
                $this->Flash->success(__('The transaction has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The transaction could not be saved. Please, try again.'));
        }
        $originals = $this->Transactions->Originals->find('list', ['limit' => 200]);
        $parentTransactions = $this->Transactions->ParentTransactions->find('list', ['limit' => 200]);
        $cpties = $this->Transactions->Cpties->find('list', ['limit' => 200]);
        $bonds = $this->Transactions->Bonds->find('list', ['limit' => 200]);
        $limitBreaches = $this->Transactions->LimitBreaches->find('list', ['limit' => 200]);
        $this->set(compact('transaction', 'originals', 'parentTransactions', 'cpties', 'bonds', 'limitBreaches'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Transaction id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $transaction = $this->Transactions->get($id, [
            'contain' => ['Bonds', 'LimitBreaches'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $transaction = $this->Transactions->patchEntity($transaction, $this->request->getData());
            if ($this->Transactions->save($transaction)) {
                $this->Flash->success(__('The transaction has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The transaction could not be saved. Please, try again.'));
        }
        $originals = $this->Transactions->Originals->find('list', ['limit' => 200]);
        $parentTransactions = $this->Transactions->ParentTransactions->find('list', ['limit' => 200]);
        $cpties = $this->Transactions->Cpties->find('list', ['limit' => 200]);
        $bonds = $this->Transactions->Bonds->find('list', ['limit' => 200]);
        $limitBreaches = $this->Transactions->LimitBreaches->find('list', ['limit' => 200]);
        $this->set(compact('transaction', 'originals', 'parentTransactions', 'cpties', 'bonds', 'limitBreaches'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Transaction id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $transaction = $this->Transactions->get($id);
        if ($this->Transactions->delete($transaction)) {
            $this->Flash->success(__('The transaction has been deleted.'));
        } else {
            $this->Flash->error(__('The transaction could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }


    public function trconfirmation($tr_number)
    {

        @$this->validate_param('int', $tr_number);
        $tr_numbers = array($tr_number);
        $displayFields = array();
        if (strpos($tr_number, ',') !== false) {
            $tr_numbers = explode(',', $tr_number);
            $tr_number = $tr_numbers[0];
            foreach ($tr_numbers as $fields) {
                @$displayFields = array_merge($displayFields, $fields);
            }
        } else {
            $tr_numbers = array($tr_number);
            $displayFields = array($tr_number);
        }

        $this->Transaction->virtualFields  =  array(
            'amountccy' => "CONCAT('Transaction.amount',' ','AccountA.ccy')",
            'interest'    => "CONCAT(Transaction.total_interest,' ',AccountA.ccy)",
            'tax'    => "CONCAT(Transaction.tax_amount,' ',AccountA.ccy)",
        );
        $this->Transaction->setFieldsToDisplay($displayFields);

        if (!empty($tr_numbers)) {
            $transactions = array();
            foreach ($tr_numbers as $tr_number) {
                $transaction = $this->Transaction->getRawsById($tr_number);
                $interest_rate = $this->Transaction->find("first", array('fields' => array('interest_rate', 'total_interest', 'tax'), 'conditions' => array('tr_number' => $tr_number)));
                if (!empty($interest_rate['Transaction'])) {
                    $transaction[0]['Transaction']['interest_rate'] = $interest_rate['Transaction']['interest_rate'];
                    $transaction[0]['Transaction']['total_interest'] = $interest_rate['Transaction']['total_interest'];
                    $transaction[0]['Transaction']['tax'] = $interest_rate['Transaction']['tax'];
                }
                $transactions[] = $transaction;

                //update the amount_eur and CCY, as the rest seems to be a custom dataset
                $trn = $this->Transaction->find('first', array('fields' => array('Transaction.amount', 'AccountA.ccy'), 'conditions' => array('tr_number' => $tr_number)));
                foreach ($transaction as &$trna) {
                    unset($trna['Transaction']['amount_eur']);
                    if (!empty($trn['Transaction']['amount_eur'])) {
                        if (!empty($trn['AccountA']['ccy']) && strtolower($trn['AccountA']['ccy']) !== 'eur') {
                            $trna['Transaction']['Amount EUR'] = $trn['Transaction']['amount_eur'] . ' EUR';
                        }
                    }
                }
            }
            $this->set(compact('transactions'));
        }
    }

    public function trcorrection_router($tr_number)
    {

        @$this->validate_param('int', $tr_number);
        $model = $this->Transactionbondid->getModel($tr_number);
        switch ($model) {
            case 'transactions':
                $tr_type = $this->Transaction->getAttribByTrn('tr_type', $tr_number);
                switch ($tr_type) {
                    case 'Rollover':
                        $this->redirect('/treasury/treasurytransactions/newdeposits/edit/' . $tr_number);
                        break;
                    case 'Deposit':
                        $this->redirect('/treasury/treasurytransactions/newdeposits/edit/' . $tr_number);
                    case 'Repayment':
                        $this->redirect('/treasury/treasurytransactions/correctrepayment/' . $tr_number);
                    default:
                        break;
                }
                break;

            case 'bond':
            case 'bonds':
            case 'bondstransaction': //bondstransaction
                $this->redirect('/treasury/treasurybonds/newbonds/add/' . $tr_number);
                break;

            default:
                $this->Session->setFlash('Unknown transaction. You cannot edit transactions by typing url directly, please use the edit/delete menu.', 'flash/error');
                $this->redirect('/treasury/treasurytransactions/edit');
                break;
        }
    }

    /*public function delete_transaction($tr_number)
	{
		@$this->validate_param('int', $tr_number);
		$model = $this->Transactionbondid->getModel($tr_number);
		switch($model)
		{
			case 'transactions':
				$this->redirect('/treasury/treasurytransactions/deletetr/'.$tr_number);
				//$this->deletetr($tr_number);
			break;
			
			case 'bonds':
			case 'bondstransaction':
				$this->redirect('/treasury/treasurytransactions/deleteBondtr/'.$tr_number);
				//$this->deleteBondtr($tr_number);
			break;
			
			default:
				$this->Session->setFlash('Unknown transaction. You cannot delete transactions by typing url directly, please use the edit/delete menu.', 'flash/error');
				$this->redirect('/treasury/treasurytransactions/edit');
			break;
		}
	}*/


    public function deleteBondtr()
    {
        $tr_number = $this->request->data['Transaction']['tr_number'];
        //exit if transaction state is not Created
        if (!$this->Bondtransaction->isEditable($tr_number)) {
            $this->Session->setFlash('This transaction cannot be deleted. You cannot delete transactions by typing url directly, please use the edit/delete menu.', 'flash/error');
            $this->redirect('/treasury');
        } else {
            $data = $this->Bondtransaction->find("first", array('conditions' => array('Bondtransaction.tr_number' => $tr_number)));
            if (!empty($data)) {
                if (strpos($data['Bond']['issue_date'], '-') !== false) {
                    $data['Bond']['issue_date'] = date("d/m/Y", strtotime($data['Bond']['issue_date']));
                }
                if (strpos($data['Bondtransaction']['settlement_date'], '-') !== false) {
                    $data['Bondtransaction']['settlement_date'] = date("d/m/Y", strtotime($data['Bondtransaction']['settlement_date']));
                }
                if (strpos($data['Bond']['maturity_date'], '-') !== false) {
                    $data['Bond']['maturity_date'] = date("d/m/Y", strtotime($data['Bond']['maturity_date']));
                }
            }
            $this->set('data', $data);

            if ($this->request->is('post')) {
                $tr = $this->Bondtransaction->read(null, $tr_number);
                $this->Bondtransaction->set('tr_state', 'Deleted');
                $this->Bondtransaction->set('comment', $this->request->data['Transaction']['comment']);
                $bond_trn = $this->Bondtransaction->save();

                $bond_id = $bond_trn['Bondtransaction']['bond_id'];
                $bond = $this->Bond->find('first', array('conditions' => array('Bond.bond_id' => $bond_id, 'Bond.state' => 'Created'))); //only change if state is 'Created'
                if (!empty($bond)) {
                    $bond["Bond"]["state"] = "Deleted";
                    $this->Bond->save($bond);
                    $this->log_entry('Bond ' . $bond_id . ' has been deleted', 'treasury');
                }
                /*$event = new CakeEvent('Model.Treasury.Transaction.change', $this, array("transaction" => $tr));// NOT YET
				$this->getEventManager()->dispatch($event);*/
                $this->log_entry('Bond TRN ' . $tr_number . ' has been deleted', 'treasury');
                //$this->log_entry('Bond '.$bond_id.' deleted : '.print_r($bond['Bond'], true), 'treasury');
                $this->Session->setFlash('The bond transaction ' . $tr_number . ' has been deleted', 'flash/success');
                $this->redirect('/treasury/treasurytransactions/edit');
            }
        }
    }

    public function deletetr()
    {
        $tr_number = $this->request->data['Transaction']['tr_number'];
        //exit if transaction state is not Created
        if (!$this->Transaction->isEditable($tr_number)) {
            $this->Session->setFlash('This transaction cannot be deleted. You cannot delete transactions by typing url directly, please use the edit/delete menu.', 'flash/error');
            $this->redirect('/treasury');
        } else {
            $this->Transaction->setFieldsToDisplay($tr_number);
            $this->set('transaction', $this->Transaction->getRawsById($tr_number));

            if ($this->request->is('post')) {
                $tr = $this->Transaction->read(null, $tr_number);
                $this->Transaction->set('tr_state', 'Deleted');
                $this->Transaction->set('comment', $this->request->data['Transaction']['comment']);
                $this->Transaction->save();
                if ($this->Transaction->getAttribByTrn('tr_type', $tr_number) == 'Repayment' or $this->Transaction->getAttribByTrn('tr_type', $tr_number) == 'Rollover') {
                    $source_group = $this->Transaction->getAttribByTrn('source_group', $tr_number);
                    $source_fund = $this->Transaction->getAttribByTrn('source_fund', $tr_number);
                    $new_amount_left = $this->Reinvestment->getAttribByReinv('amount_left' . $source_fund, $source_group) + $this->Transaction->getAttribByTrn('amount', $tr_number);
                    $this->Reinvestment->read(null, $source_group);
                    $this->Reinvestment->set('amount_left' . $source_fund, $new_amount_left);
                    $this->Reinvestment->save();
                }
                $event = new CakeEvent('Model.Treasury.Transaction.change', $this, array("transaction" => $tr));
                $this->getEventManager()->dispatch($event);
                $this->log_entry('TRN:' . $tr_number . ' has been deleted', 'treasury', $tr_number);
                $this->Session->setFlash('The transaction ' . $tr_number . ' has been deleted', 'flash/success');
                $this->redirect('/treasury/treasurytransactions/edit');
            }
        }
    }

    function newdeposits($action = null, $tr_number = null, $checkonly = null)
    {

        @$this->validate_param('string', $action);
        @$this->validate_param('string', $tr_number); //TODO check why int fails (value=  "9071907290739074907590769077")
        @$this->validate_param('bool', $checkonly);
        $this->set('UserIsInCheckerGroup', $this->UserPermissions->UserIsInCheckerGroup());
        /*
    	 * If isset transaction number, load data for edition
    	 */
        if (isset($tr_number)) {

            $tr = $this->Transaction->getTransactionById($tr_number);

            //exit if transaction is not editable or if it's nor a deposit, neither a rollover
            if (empty($tr) or !$this->Transaction->isEditable($tr_number) or ($tr['Transaction']['tr_type'] != 'Deposit' and $tr['Transaction']['tr_type'] != 'Rollover')) {
                $this->Session->setFlash('This transaction cannot be edited. You cannot edit transactions directly, please use the edit/delete menu.', 'flash/error');
                if (empty($multiple) && !$this->request->is('ajax')) $this->redirect('/treasury');
            }

            // Define default options depending on wether $tr is null or not
            $defaultOpts = $tr['Transaction'];
            $interest = $this->Interest->find('first', array(
                'conditions' => array('tr_number' => $tr_number, 'interest_rate_to' => null)
            ));
            if (!empty($interest)) {
                $defaultOpts['interest_rate'] = $interest['Interest']['interest_rate'];
            }
            $interest = $this->Interest->find('first', array(
                'conditions' => array('tr_number' => $tr_number, 'interest_rate_to' => null)
            ));
            if (!empty($interest)) {
                $defaultOpts['interest_rate'] = $interest['Interest']['interest_rate'];
            }
            // Mandate is always disabled when $tr_number is defined
            $disabledOpts = array(
                'mandate_ID' => 'readonly',
                'cmp_ID' => 0,
                'cpty_id' => 0,
                'commencement_date' => 0,
                'interest_rate'    =>    0,
                'depo_type' => 0,
            );

            // If the selected transaction is a Rollover than commencement_date, counterparty and compartment are disabled
            // and FromReinv is displayed
            if (sizeof($tr['Transaction']) > 0 && $tr['Transaction']['tr_type'] == 'Rollover') {
                $disabledOpts = array(
                    'mandate_ID' => 'disabled',
                    'cmp_ID' => 'disabled',
                    'cpty_id' => 'disabled',
                    'commencement_date' => 'disabled',
                    'depo_type' => 'disabled',
                    'interest_rate' => 0
                );
                // get source_group
                $this->set('fromReinv', $defaultOpts['source_group']);
                $this->set('pool', $this->Transaction->getAttribOfAssoc('outFromReinv', 'amount_left' . $tr['Transaction']['source_fund'], $tr_number) + $tr['Transaction']['amount']);
            }

            // variable to use in the view for testing if $tr_number is set  or not
            $trnIsSet = true;

            // Get compartments list based on default mandate_ID
            $cmps =  $this->Compartment->getcmpbymandate($defaultOpts['mandate_ID']);

            // Get counterparties list based on default mandate_ID
            $cptys =  $this->Mandate->getcptybymandate($defaultOpts['mandate_ID']);

            //Accounts
            $accounts = $this->Compartment->getAccountsByCmp($tr['Transaction']['cmp_ID']);
            $accountA_IBAN = array(
                $accounts['accountA_IBAN'] => 'Account A : ' . $accounts['accountA_IBAN'],
                $accounts['accountB_IBAN'] => 'Account B : ' . $accounts['accountB_IBAN']
            );
            $submitButtonLabel = 'Correct Transaction';

            // Set transaction type
            $this->Transaction->setTransactionType($defaultOpts['tr_type']);
            $this->set('tr_number', $tr_number);

            $int_rate_msg = false;
            $interests = $this->Interest->find('first', array('conditions' => array('trn_number' => $tr_number)));
            if (count($interests) > 0) {
                $int_rate_msg = true;
            }
            $this->set('int_rate_msg');
        } else {

            /*
    	 	 * Load default data for addition
    	     */
            $trnIsSet = false;
            $cmps = array();
            $cptys = array();
            $accountA_IBAN = array();
            $accountB_IBAN = array();
            $defaultOpts = array(
                'mandate_ID' => 0,
                'cmp_ID' => 0,
                'cpty_id' => 0,
                'accountA_IBAN' => 0,
                'accountB_IBAN' => 0,
                'amount' => '0.00',
                'ccy' => 'CCY',
                'depo_term' => 0,
                'depo_type' => 0,
                'depo_term' => '1W',
                'depo_renew' => 0,
                'total_interest' => false,
                'tax_amount' => false,
                'date_basis' => false,
                'interest_rate' => false,
                'depo_renew' => 0,
                'commencement_date' => false,
                'maturity_date' => false,
                'linked_trn' => '',
            );
            $disabledOpts = array('mandate_ID' => 0, 'cmp_ID' => 0, 'cpty_id' => 0, 'commencement_date' => 0, 'interest_rate' => 0, 'depo_type' => 0);
            $submitButtonLabel = 'Create New Deposit';
            $this->Transaction->setTransactionType('Deposit');
        }
        $this->set(compact('defaultOpts'));
        $this->set(compact('disabledOpts'));
        $this->set(compact('trnIsSet'));
        $this->set(compact('cmps'));
        $this->set(compact('cptys'));
        $this->set(compact('accountA_IBAN'));
        $this->set(compact('submitButtonLabel'));
        $this->set('action', $action);
        $trnum = null;

        /*
		 * If form was submited (post or ajax)
		 */
        if ($this->request->is('post') or $this->request->is('ajax')) {

            $this->request->data['Transaction']['tr_number'] = $tr_number;
            $this->request->data['Transaction']['amount'] = $this->Transaction->formatAmounts($this->request->data['Transaction']['amount']);
            if (!isset($this->request->data['Transaction']['cpty_id'])) $this->request->data['Transaction']['cpty_id'] = $defaultOpts['cpty_id'];
            if (empty($this->request->data['Transaction']['depo_renew'])) $this->request->data['Transaction']['depo_renew'] = 'No';

            if (!empty($this->request->data['Transaction']['cmp_ID'])) {
                if (!isset($errors)) $errors = array();
                if (empty($this->request->data['Transaction']['scheme'])) {
                    $errors['scheme'] = array('Scheme can not be empty');
                }
                if (empty($this->request->data['Transaction']['amount'])) {
                    $errors['amount'] = array('Amount can not be empty');
                }
                if (empty($this->request->data['Transaction']['commencement_date'])) {
                    $errors['commencement_date'] = array('Commencement date can not be empty');
                }
                if (!empty($this->request->data['Transaction']['scheme'])) {
                    if ($accounts = $this->Compartment->getAccountsByCmp($this->request->data['Transaction']['cmp_ID'])) {
                        if ($this->request->data['Transaction']['scheme'] == 'AA') {
                            $this->request->data['Transaction']['accountA_IBAN'] = $accounts['accountA_IBAN'];
                            $this->request->data['Transaction']['accountB_IBAN'] = $accounts['accountA_IBAN'];
                        } elseif ($this->request->data['Transaction']['scheme'] == 'AB') {
                            $this->request->data['Transaction']['accountA_IBAN'] = $accounts['accountA_IBAN'];
                            $this->request->data['Transaction']['accountB_IBAN'] = $accounts['accountB_IBAN'];
                        } elseif ($this->request->data['Transaction']['scheme'] == 'BA') {
                            $this->request->data['Transaction']['accountA_IBAN'] = $accounts['accountB_IBAN'];
                            $this->request->data['Transaction']['accountB_IBAN'] = $accounts['accountA_IBAN'];
                        } elseif ($this->request->data['Transaction']['scheme'] == 'BB') {
                            $this->request->data['Transaction']['accountA_IBAN'] = $accounts['accountB_IBAN'];
                            $this->request->data['Transaction']['accountB_IBAN'] = $accounts['accountB_IBAN'];
                        }
                    }
                } else {
                    if (!isset($errors)) $errors = array();
                    $errors['scheme'] = array('Scheme can not be empty');
                }
            }
            if (empty($errors)) {

                $this->Transaction->set($this->request->data);
                if ($this->Transaction->validateMultipleDeposits()) {

                    if (!isset($this->request->data['checkSave']['checkOnly'])) $this->request->data['checkSave']['checkOnly'] = null;

                    /*
					 * Click only on check button and check also limit breach
					 */
                    if ($this->request->data['checkSave']['checkOnly']) {
                        $errors = $this->Transaction->validateLimitsBreaches($this->request->data);
                        /* new : also checking amounts balance */
                        if (isset($tr_number)) {
                            $tr_type = $this->Transaction->getAttribByTrn('tr_type', $tr_number);
                            if ($tr_type != 'Deposit') {
                                $source_fund = $this->Transaction->getAttribByTrn('source_fund', $tr_number);
                                $correct_amount_left = $this->Transaction->getAttribOfAssoc('outFromReinv', 'amount_left' . $source_fund, $tr_number) + $defaultOpts['amount'] - $this->Transaction->formatAmounts($this->request->data['Transaction']['amount']);
                                if (0 > $correct_amount_left) {
                                    $save = false;
                                    if (!isset($errors['amount'])) {
                                        $errors = array('amount');
                                    }
                                    $errors['amount']['balance' . count($errors['amount'])] = array('message' => 'Cannot Rollover more than available in the pool.');
                                }
                            }
                        }
                        /* balance check */
                        $this->set('errors', array('Transaction' => $errors));
                        $this->set('_serialize', array('errors'));
                    } elseif (empty($this->request->data['checkSave']['checkOnly'])) {
                        if (!isset($tr_number)) {

                            /*
							 * Add new transaction
							 */
                            $save = true;
                            $new_tr = $this->Transaction->save();
                            $trn = $this->Transaction->id;
                            $this->Transaction->read(null, $trn);
                            $interest_rate = $this->Transaction->interest_rate;
                            //$this->Transaction->set('interest_rate', null);
                            $this->Transaction->set('original_id', $trn);
                            //$this->Transaction->set('parent_id',$trn);
                            $this->Transaction->save();

                            if ($new_tr['Transaction']['depo_type'] == "Callable") {
                                $this->Interest->updateInterest($this->Transaction->id, $this->request->data['Transaction']['interest_rate'], $this->request->data['Transaction']['commencement_date']);
                            }
                            if (!isset($this->request->data['msg'])) $this->request->data['msg'] = null;

                            $lolg = $this->log_entry('Deposit created TRN: ' . $this->Transaction->id . ' ' . $this->request->data['msg'] . ' ' . print_r($this->request->data['Transaction'], true) . ' ' . $this->request->data['msg'], 'treasury', $this->Transaction->id);
                            $this->set('success', Router::url('/treasury/treasurytransactions/trconfirmation/' . $this->Transaction->id, false));
                            $this->set('_serialize', array('success'));
                        } else {

                            /*
							 * Edit transaction
							 */
                            $tr_type = $this->Transaction->getAttribByTrn('tr_type', $tr_number);
                            $depo_type = $this->Transaction->getAttribByTrn('depo_type', $tr_number);
                            if (($this->request->data['Transaction']['depo_type'] === "Callable") && ($depo_type == "Term")) {
                                // from Term to Callable
                                $this->Interest->updateInterest($tr_number, $this->request->data['Transaction']['interest_rate'], $this->request->data['Transaction']['commencement_date']);
                                $this->Interest->updateCommencementDate($tr_number, $this->request->data['Transaction']['commencement_date']);
                                $this->Interest->updateOriginalInterestRate($tr_number, $this->request->data['Transaction']['interest_rate']);
                            } elseif (($depo_type === "Callable") && ($this->request->data['Transaction']['depo_type'] == "Term")) {
                                //old Callable now Term => remove interest entries
                                $this->Interest->deleteAll(array('Interest.trn_number' => $tr_number), false);
                            } elseif (($depo_type === "Callable") && ($this->request->data['Transaction']['depo_type'] == "Callable")) {
                                // remain Callable, update original line
                                $this->Interest->updateCommencementDate($tr_number, $this->request->data['Transaction']['commencement_date']);
                                $this->Interest->updateOriginalInterestRate($tr_number, $this->request->data['Transaction']['interest_rate']);
                            } // Term to Term : no interest rate history (yet)
                            if ($tr_type == 'Deposit') {
                                $save = true;
                                $this->Transaction->save();
                                $this->log_entry('Deposit corrected TRN: ' . $tr_number . ' ' . $this->request->data['msg'] . ' ' . print_r($this->request->data['Transaction'], true), 'treasury', $tr_number);
                                $this->set('success', Router::url('/treasury/treasurytransactions/trconfirmation/' . $tr_number, false));
                                $this->set('_serialize', array('success'));
                            } else {
                                $source_fund = $this->Transaction->getAttribByTrn('source_fund', $tr_number);
                                $correct_amount_left = $this->Transaction->getAttribOfAssoc('outFromReinv', 'amount_left' . $source_fund, $tr_number) + $defaultOpts['amount'] - $this->Transaction->formatAmounts($this->request->data['Transaction']['amount']);
                                if (0 > $correct_amount_left) {
                                    $save = false;
                                    $errors = array('amount' => array('Cannot Rollover more than available in the pool.'));

                                    $this->Transaction->validateLimits($this->request->data);
                                    $errors_validation = $this->Transaction->validationErrors;
                                    foreach ($errors_validation as $k => $err_val) {
                                        $err = "";
                                        if (is_array($err_val[0])) {
                                            $err = $err_val[0][0];
                                        } else {
                                            $err = $err_val[0];
                                        }
                                        $errors[$k][] = $err;
                                    }
                                    if (empty($multiple) && !$this->request->is('ajax')) {
                                        $this->Session->setFlash('Cannot Rollover more than available in the pool.', 'flash/error');
                                        $this->redirect($this->referer());
                                    }
                                } else {
                                    $save = true;
                                    $this->Transaction->save();
                                    $this->Reinvestment->read(null, $this->Transaction->getAttribByTrn('source_group', $tr_number));
                                    $this->Reinvestment->set('amount_left' . $source_fund, $correct_amount_left);
                                    $this->Reinvestment->save();
                                    /**/
                                    $this->Transaction->validateLimits($this->request->data);
                                    $errors = $this->Transaction->validationErrors;
                                    if ($errors) {
                                        $this->set('errors', array('Transaction' => $errors));
                                        $this->set('_serialize', array('errors'));
                                    } else {
                                        $this->set('success', Router::url('/treasury/treasurytransactions/trconfirmation/' . $tr_number, false));
                                        $this->set('_serialize', array('success'));
                                    }
                                    /**/
                                    $this->log_entry('Rollover corrected TRN: ' . $tr_number . ' ' . $this->request->data['msg'] . ' ' . print_r($this->request->data['Transaction'], true), 'treasury', $tr_number);
                                    //$this->set('success', Router::url('/treasury/treasurytransactions/trconfirmation/'.$tr_number,false));
                                    //$this->set('_serialize', array('success'));
                                }
                            }
                        }
                        if ($save) {
                            $trnum = $this->Transaction->id;
                            $this->Transaction->read(null, $this->Transaction->id);
                            $tr = $this->Transaction->read(null, $this->Transaction->id);
                            if ($defaultOpts['mandate_ID'] == 0) $defaultOpts['mandate_ID'] = $this->request->data['Transaction']['mandate_ID'];
                            $this->Transaction->set('tax_ID', $this->Tax->getTaxID($defaultOpts['mandate_ID'], $this->request->data['Transaction']['cpty_id']));
                            $this->Transaction->save();
                            $event = new CakeEvent('Model.Treasury.Transaction.change', $this, array("transaction" => $tr));
                            $this->getEventManager()->dispatch($event);
                        } elseif (!empty($errors)) {
                            $this->set('errors', array('Transaction' => $errors));
                            $this->set('_serialize', array('errors'));
                        }
                    }
                } else {
                    $errors = $this->Transaction->validationErrors;
                    $this->set('errors', array('Transaction' => $errors));
                    $this->set('_serialize', array('errors'));
                }
            }
        }

        $form_process = array();
        $linenum = 1;
        if (!empty($this->request->data['checkSave']['linenum'])) $linenum = $this->request->data['checkSave']['linenum'];

        $force = false;
        if (isset($this->request->data['force'])) {
            if ($this->request->data['force'] == 1) {
                $force = true;
            }
        }
        if ((empty($errors) || $force) && !empty($trnum)) {
            $form_process['trnum'] = $linenum;
            $form_process['result'] = 'success';

            $form_process['result_text'] = 'Line #' . $linenum . ' has been saved as Transaction #<span class="trnum">' . $trnum . '</span>. <a class="trreport" href="' . Router::url('/treasury/treasurytransactions/trconfirmation/' . $trnum) . '?aftr=1" target="_blank">(report)</a>';
        } elseif (!empty($errors)) {
            $this->set('errors', array($linenum => $errors));
            $errorstxt = $errortxtlist = '';
            foreach ($errors as $field => $error) {
                foreach ($error as $desc) {
                    if (is_array($desc)) {
                        $desc = $desc['message'];
                    }
                    $errortxtlist .= '<li data-field="' . $field . '">' . $desc . '</li>';
                }
            }
            if (!empty($errortxtlist)) $errorstxt .= '<ul class="errorlist" data-trnum="' . $linenum . '">' . $errortxtlist . '</ul>';

            $form_process['trnum'] = $linenum;
            $form_process['result'] = 'error';
            $form_process['result_text'] = '<strong>Line #' . $linenum . ' contains errors</strong> ' . $errorstxt;
        } elseif (empty($errors) && !empty($this->request->data['checkSave']['checkOnly'])) {
            $form_process['trnum'] = $linenum;
            $form_process['result'] = 'success-check';
            $form_process['result_text'] = 'Line #' . $linenum . ' passed the verification successfully';
        }
        $this->set('form_process', $form_process);
        $this->set('linenum', $linenum);
        $this->set('multiple', true);
        $this->render('newdeposits');
    }

    function calldeposit()
    {
        if (Cache::read("automatic_fixing", 'treasury')) {
            $this->Session->setFlash("Process of the automatic interest fixing is currently running. Your modification on the transactions could create a conflict in the database. Please try again in 5 minutes.", 'flash/warning');
        }
        $transactions = $this->Transaction->find(
            'all',
            array(
                'conditions' => array(
                    'depo_type' => 'Callable',
                    'tr_state'    => 'Confirmed',
                    'tr_type'    => array('Deposit', 'Rollover')
                )
            )
        );

        $account_list = $this->Account->find('list', array('recursive' => -1, 'fields' => array('IBAN', 'IBAN')));

        $this->set(compact('transactions', 'account_list'));

        if ($this->request->is('post') or $this->request->is('ajax')) {
            $this->request->data['Transaction']['reqamount'] = $this->Transaction->formatAmounts($this->request->data['Transaction']['reqamount']);

            if (isset($this->request->data['Transaction']['tr_number'])) {

                $tr = $this->Transaction->getTransactionById($this->request->data['Transaction']['tr_number']);
                $this->request->data['Transaction']['amount'] = $tr['Transaction']['amount'];
            }
            $this->Transaction->set($this->request->data);
            $validateCallDeposit = $this->Transaction->validateCallDeposit();
            if ($validateCallDeposit) {
                $tr_number = $this->request->data['Transaction']['tr_number'];
                $tr = $this->Transaction->findByTrNumber($tr_number);

                $reqamount = 0.00;
                if (isset($this->request->data['Transaction']['reqamount'])) {
                    $reqamount = implode('', explode(',', $this->request->data['Transaction']['reqamount']));
                }

                $new_amount_left = $tr['Transaction']['amount'] - $reqamount;
                $full = (($this->request->data['Transaction']['full'] == 'full') or  ($new_amount_left  == 0));

                if ($full) {
                    $new_amount_left = 0.00;
                    $reqamount = $tr['Transaction']['amount'];
                }

                switch ($tr['Transaction']['scheme']) {
                    case 'AA':
                        $amount_leftA             = (float) ($new_amount_left);
                        $amount_leftB             = 0.00;
                        $rollover_source_fund     = 'A';
                        break;
                    case 'BB':
                        $amount_leftA             = 0.00;
                        $amount_leftB             = (float) ($new_amount_left);
                        $rollover_source_fund     = 'B';
                        break;
                    case 'AB':
                        $amount_leftA             = (float) ($new_amount_left);
                        $amount_leftB             = 0.00;
                        $rollover_source_fund    = 'A';
                        break;
                    default:
                        //$this->Session->setFlash("Error in the Transaction scheme ID: ".$tr_number.", please contact the administrator", "flash/error");
                        break;
                }

                // Compartment's accounts
                $accounts = $this->Compartment->find('first', array(
                    'conditions' => array(
                        'Compartment.cmp_ID' => $this->Transaction->getAttribByTrn('cmp_ID', $tr_number),
                    ),
                    'fields' => array(
                        'Compartment.accountA_IBAN as accountA', 'Compartment.accountB_IBAN as accountB'
                    ),
                ));

                $accounts = array_shift($accounts);

                $this->Reinvestment->create();
                $this->Reinvestment->set('reinv_status', 'Open');
                $this->Reinvestment->set('mandate_ID', $tr['Transaction']['mandate_ID']);
                $this->Reinvestment->set('cmp_ID', $tr['Transaction']['cmp_ID']);
                $this->Reinvestment->set('cpty_ID', $tr['Transaction']['cpty_id']);
                $this->Reinvestment->set('availability_date', $this->request->data['Transaction']['value_date']);
                $this->Reinvestment->set('accountA_IBAN', $accounts['accountA']);
                $this->Reinvestment->set('accountB_IBAN', $accounts['accountB']);
                $this->Reinvestment->set('amount_leftA', $amount_leftA);
                $this->Reinvestment->set('amount_leftB', $amount_leftB);
                $this->Reinvestment->set('reinv_type', 'Call');
                $this->Reinvestment->save();
                $reinv_id = $this->Reinvestment->id;

                $interest_rate = 0.00;
                if ($tr['Transaction']['depo_type'] == "Callable") {
                    $interest_rate = $this->Interest->getInterestAt($tr['Transaction']['tr_number'], $this->request->data['Transaction']['value_date']);
                } else {
                    $interest_rate = $tr['Transaction']['interest_rate'];
                }
                if (!$full) {
                    $this->Transaction->create();
                    $data = array('Transaction' => array(
                        'tr_type'            => 'Rollover',
                        'tr_state'            => 'Created',
                        'depo_type'            => 'Callable',
                        'depo_renew'        => $tr['Transaction']['depo_renew'],
                        'rate_type'            => $tr['Transaction']['rate_type'], //will be overwritten
                        'booking_status'    => 'Not booked',
                        'source_group'        => $reinv_id,
                        'original_id'        => (is_null($tr['Transaction']['parent_id']) ? $tr['Transaction']['tr_number'] : $tr['Transaction']['original_id']),
                        'parent_id'            => $tr['Transaction']['tr_number'],
                        'amount'            => $new_amount_left,
                        'commencement_date'    => $this->request->data['Transaction']['value_date'],
                        'accountA_IBAN'        => $tr['Transaction']['accountA_IBAN'],
                        'accountB_IBAN'        => $tr['Transaction']['accountB_IBAN'],
                        'scheme'            => $tr['Transaction']['scheme'],
                        'mandate_ID'        => $tr['Transaction']['mandate_ID'],
                        'cmp_ID'            => $tr['Transaction']['cmp_ID'],
                        'cpty_id'            => $tr['Transaction']['cpty_id'],
                        'source_fund'        => $rollover_source_fund,
                        'instr_num'            => $tr['Transaction']['instr_num'],
                        'interest_rate'        => $interest_rate,
                        //'fixed_rate_type'	=> $tr['Transaction']['rate_type'],//will overwrite
                        'date_basis'        => $tr['Transaction']['date_basis'],
                        'benchmark'            => $tr['Transaction']['benchmark'],
                        'reference_rate'    => $tr['Transaction']['reference_rate'],
                    ));
                    $rollover = $this->Transaction->save($data);
                    $rollover_id = $rollover['Transaction']['tr_number'];
                    $this->log_entry('Rollover of the remainder after call. TRN: ' . $rollover_id . ' ' . print_r($data, true), 'treasury', $rollover_id);
                    $this->log_entry('Rollover of the remainder after call. TRN: ' . $rollover_id . ' ' . print_r($data, true), 'treasury', $tr['Transaction']['tr_number']);
                    // interest record for this new rollover
                    $this->Interest->updateInterest($rollover_id, $tr['Transaction']['interest_rate'], $this->request->data['Transaction']['value_date']);
                }

                $this->Transaction->create();
                $data = array('Transaction' => array(
                    'tr_type'            => 'Call',
                    'tr_state'            => 'Created',
                    'rate_type'            => $tr['Transaction']['rate_type'],
                    //'fixed_rate_type'	=> $tr['Transaction']['rate_type'],
                    'booking_status'    => 'Not booked',
                    'source_group'        => $reinv_id,
                    'original_id'        => (is_null($tr['Transaction']['parent_id']) ? $tr['Transaction']['tr_number'] : $tr['Transaction']['original_id']),
                    'parent_id'            => $tr['Transaction']['tr_number'],
                    'amount'            => $reqamount,
                    'commencement_date'    => $this->request->data['Transaction']['value_date'],
                    'maturity_date'        => $this->request->data['Transaction']['value_date'],
                    'accountA_IBAN'        => $this->request->data['Transaction']['accountA_IBAN'],
                    'accountB_IBAN'        => $this->request->data['Transaction']['accountB_IBAN'],
                    'mandate_ID'        => $tr['Transaction']['mandate_ID'],
                    'cmp_ID'            => $tr['Transaction']['cmp_ID'],
                    'cpty_id'            => $tr['Transaction']['cpty_id'],
                    'scheme'            => $tr['Transaction']['scheme'],
                ));
                $this->Transaction->save($data);
                $call_id = $this->Transaction->id;
                $this->log_entry('Call ' . $call_id . ' created :  ' . print_r($data, true), 'treasury', $tr['Transaction']['tr_number']);
                $this->log_entry('Call ' . $call_id . ' created :  ' . print_r($data, true), 'treasury', $call_id);

                $this->Reinvestment->read(null, $reinv_id); //this->reinvestment has changed, so we re-set it
                $this->Reinvestment->set('amount_leftA', 0.00);
                $this->Reinvestment->set('amount_leftB', 0.00);
                $this->Reinvestment->set('reinv_status', 'Closed');
                $this->Reinvestment->save();

                $tr = $this->Transaction->read(null, $tr_number);
                $this->Transaction->set('tr_state', 'Called');
                $this->Transaction->set('reinv_group', $reinv_id);
                $this->Transaction->set('maturity_date', $this->request->data['Transaction']['value_date']);
                $this->Transaction->save();
                $event = new CakeEvent('Model.Treasury.Transaction.change', $this, array("transaction" => $tr));
                $this->getEventManager()->dispatch($event);
                $this->log_entry('Deposit ' . $tr_number . ' Called.', 'treasury', $tr_number);

                if (!$full) {
                    $this->set('success', Router::url('/treasury/treasurytransactions/calldeposit_result/' . $tr_number . '/' . $call_id . '/' . $rollover_id, false));
                } else $this->set('success', Router::url('/treasury/treasurytransactions/calldeposit_result/' . $tr_number . '/' . $call_id, false));
                $this->set('_serialize', array('success'));
            } else {
                $Transaction = $this->Transaction->validationErrors;
                $data = compact('Transaction');
                $this->set('errors', $data);
                $this->set('_serialize', array('errors'));
            }
        }
    }

    /**
     * Call deposit confirmation
     */
    function calldeposit_result($tr_number, $call_id, $rollover_id = null)
    {


        @$this->validate_param('int', $tr_number);
        @$this->validate_param('int', $call_id);
        @$this->validate_param('int', $rollover_id);

        $this->Transaction->virtualFields  =  array(
            'amountccy' => "CONCAT('Transaction.amount',' ','AccountA.ccy')",
            'interest'    => "CONCAT(Transaction.total_interest,' ',AccountA.ccy)",
            'tax'    => "CONCAT(Transaction.tax_amount,' ',AccountA.ccy)",
        );

        // Parent transaction
        $this->Transaction->setFieldsToDisplay($tr_number);
        $confTables = array('parent_transaction' => $this->Transaction->getRawsById($tr_number));

        // Call repayment
        $this->Transaction->setFieldsToDisplay($call_id);
        $confTables['repayment_of_requested_amount'] = $this->Transaction->getRawsById($call_id);

        if (isset($rollover_id)) {
            $this->Transaction->setFieldsToDisplay($rollover_id);
            $confTables['rollover_of_remaining_amount'] = $this->Transaction->getRawsById($rollover_id);
        }

        //remove fields amount_eur & reinv_availability_date from results (why were they here?!)
        foreach ($confTables as &$confTable) {
            foreach ($confTable as &$trn) {
                if (isset($trn['Transaction']['amount_eur'])) unset($trn['Transaction']['amount_eur']);
                if (isset($trn['Transaction']['reinv_availability_date'])) unset($trn['Transaction']['reinv_availability_date']);
            }
        }

        $this->set(compact('confTables'));
    }


    function interest_fixing_empty()
    {
    }

    function automatic_interest_fixing()
    {
        /*if (Cache::read("automatic_fixing", 'treasury'))
		{
			$this->Session->setFlash("Process of the automatic interest fixing is currently running. Your modification on the transactions could create a conflict in the database. Please try again in 5 minutes.", 'flash/warning');
		}*/
        $months = array(
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        );
        $this->set('months', $months);

        $years = array();
        for ($i = date('Y'); $i >= 2012; $i--) $years[$i] = $i;
        $this->set('years', $years);
        if ($this->request->is('post') or $this->request->is('ajax')) {
            //filtered transactions to fix automatically
            if (isset($this->request->data['filterTransactions']['action']) && ($this->request->data['filterTransactions']['action'] == 'automate')) {
                $running = Cache::read("automatic_fixing controler", 'treasury');
                $result = $this->Transaction->automatic_fixing($this->request->data['filterTransactions']['Filter']['month'], $this->request->data['filterTransactions']['Filter']['year']);
                $msg_result = "";
                if (isset($result['success']) && count($result['success']) == 0  && isset($result['fail']) && count($result['fail']) == 0) {
                    $msg_result = "No transaction met your requirements.";
                }
                if (isset($result['success']) && count($result['success']) > 0) {
                    $msg_result = "Interest have been automatically set for " . count($result['success']) . " deposit(s) for the period of the " . $this->request->data['filterTransactions']['Filter']['month'] . "/" . $this->request->data['filterTransactions']['Filter']['year'];
                    $this->Session->setFlash($msg_result, "flash/success", array('key' => "ok"));
                    $msg_result .= "\n<br />";
                }
                if (isset($result['fail']) && count($result['fail']) > 0) {
                    $msg_result .= "\nInterests have not been set for the following deposit(s) : " . implode(',', $result['fail']);
                    $msg_result .= "\n<br />";
                    $msg_result = wordwrap($msg_result, 280, "<br />", true);
                }
                if (in_array('wrong_period', $result)) {
                    $msg_result = "The automatic fixing can only be run for the current month or the previous month.";
                }
                //$event = new CakeEvent('Model.Treasury.Transaction.changeAll', array("transaction" => $this->Transaction->data));
                $event = new CakeEvent('Model.Treasury.Transaction.changeAll');
                $this->getEventManager()->dispatch($event);
                $this->Session->setFlash($msg_result, "flash/default");
            }
        }
    }

    /**
     * Fixing interest of a callable deposit
     */
    function interest_fixing()
    {
        clearstatcache(true);
        if (Cache::read("automatic_fixing", 'treasury')) {
            $this->Session->setFlash("Process of the automatic interest fixing is currently running. Your modification on the transactions could create a conflict in the database. Please try again in 5 minutes.", 'flash/warning');
        }
        $months = array(
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        );
        $this->set('months', $months);

        $years = array();
        for ($i = date('Y'); $i >= 2012; $i--) $years[$i] = $i;
        $this->set('years', $years);

        $transactions_all_fixing = $this->Transaction->getTransactions_fixing();
        $mandate_id_list = array();
        $cpty_id_list = array();
        foreach ($transactions_all_fixing as $trn) {
            $mandate_id_list[] = $trn['Transaction']['mandate_ID'];
            $cpty_id_list[] = $trn['Transaction']['cpty_id'];
        }

        $mandates = $this->Mandate->find('list', array(
            'order' => 'Mandate.mandate_name',
            'conditions' => array("Mandate.mandate_id" => $mandate_id_list),
            'fields' => array('mandate_id', 'mandate_name'),
        ));
        $this->set('mandates', $mandates);
        $counterparties = $this->Counterparty->find('list', array(
            'order' => 'Counterparty.cpty_name',
            'conditions' => array("Counterparty.cpty_ID" => $cpty_id_list),
            'fields' => array('cpty_ID', 'cpty_name'),
        ));
        $this->set('counterparties', $counterparties);

        $tr_number_filter = null;
        $mandate_id_filter = null;
        $cpty_id_filter = null;
        if (!empty($this->request->data['filterform']['tr_number'])) {
            $tr_number_filter = $this->request->data['filterform']['tr_number'];
        }
        if (!empty($this->request->data['filterform']['mandate_id'])) {
            $mandate_id_filter = $this->request->data['filterform']['mandate_id'];
        }
        if (!empty($this->request->data['filterform']['cpty_id'])) {
            $cpty_id_filter = $this->request->data['filterform']['cpty_id'];
        }
        $this->set('tr_number_filter', $tr_number_filter);
        $this->set('mandate_id_filter', $mandate_id_filter);
        $this->set('cpty_id_filter', $cpty_id_filter);

        $transactions = $this->Transaction->getTransactions_fixing($tr_number_filter, $mandate_id_filter, $cpty_id_filter);
        $trn_fixing = array();
        foreach ($transactions as $tt) {
            //$end_date = $tt["Transaction"]["end_date"];
            $end_date = $this->Interest->fixDate('30/06/2021');
            $is_rollover = ($tt['Transaction']['tr_type'] == 'Rollover');

            $is_from_partial_call_tr = $tt['Transaction']['parent_id'];
            $is_from_partial_call = !empty($this->Transaction->find('first', array('conditions' => array('parent_id' => $is_from_partial_call_tr, 'tr_type' => 'Call'))));


            $commencement_date = $tt["Transaction"]["commencement_date"];
            $tc1 = array(array('OR' => array('interest_rate_to >= ' => $commencement_date, 'interest_rate_to' => null)));
            $tc2 = array('interest_rate_from <= ' => $end_date);
            $time_conditions = array(array('AND' => array($tc1, $tc2)));
            $conditions =  array('recursive' => -1, "conditions" => array('trn_number' => $tt["Transaction"]["tr_number"], $time_conditions));
            $interest_list =  $this->Interest->find("all", $conditions);
            if (count($interest_list) > 1) {
                foreach ($interest_list as $int) {
                    $interest_rate = $int['Interest']["interest_rate"];
                    $start_date_interest = $int['Interest']["interest_rate_from"];
                    $end_date_interest = $int['Interest']["interest_rate_to"];
                    if (empty($end_date_interest) || (strtotime($end_date_interest) > strtotime($end_date))) {
                        $end_date_interest = $end_date;
                    }
                    if (strtotime($start_date_interest) < strtotime($commencement_date)) {
                        $start_date_interest = $commencement_date;
                    }
                    if ($interest_rate != null) {
                        $n_tr = array(
                            'tr_number' => $tt["Transaction"]["tr_number"],
                            'amount' => $tt["Transaction"]["amount"],
                            'interest_rate' => $interest_rate,
                            'commencement_date' => $this->Interest->fixDate($commencement_date),
                            'end_date' => $end_date_interest,
                            'date_basis' => $tt["Transaction"]["date_basis"],
                        );
                        $trn_fixing[] = $n_tr;
                    }
                }
            } else {
                $n_tr = array(
                    'tr_number' => $tt["Transaction"]["tr_number"],
                    'amount' => $tt["Transaction"]["amount"],
                    'interest_rate' => $tt["Transaction"]["interest_rate"],
                    'commencement_date' => $this->Interest->fixDate($tt["Transaction"]["commencement_date"]),
                    'end_date' => $end_date,
                    'date_basis' => $tt["Transaction"]["date_basis"],
                );
                $trn_fixing[] = $n_tr;
            }

            //extra day
            $extra_interest = '0.00';
            if ($is_rollover && !$is_from_partial_call) // add one day if capitalisation or rollover: TREASURY-303
            {
                $interest_rate_extra = $tt["Transaction"]["interest_rate"];
                if (count($interest_list) > 1) {
                    $interest_rate_extra = $interest_list[0]['Interest']["interest_rate"];
                }
                $date_end = date('Y-m-d', strtotime($commencement_date . ' +1 day'));
                //$extra_interest = $ajaxController->getSASInterest($amount, $date_end, $date_basis, $commencement_date, $trn['Transaction']['tr_number']);

                $n_tr = array(
                    'tr_number' => $tt["Transaction"]["tr_number"],
                    'amount' => $tt["Transaction"]["amount"],
                    'interest_rate' => $interest_rate_extra,
                    'commencement_date' => $this->Interest->fixDate($commencement_date),
                    'end_date' => $date_end = date('Y-m-d', strtotime($commencement_date . ' +1 day')),
                    'date_basis' => $tt["Transaction"]["date_basis"],
                );
                $trn_fixing[] = $n_tr;
            }
        }
        error_log("transactions fixing (callables) : " . json_encode($trn_fixing, true));
        $table_name = 'treasury_interest.interest_calculation_' . time();
        error_log("creating table temporary " . json_encode($table_name, true));
        $this->Transaction->query('CREATE TABLE ' . $table_name . ' (
   `id` int(11)  PRIMARY KEY AUTO_INCREMENT,
   `tr_number` int(11) NOT NULL,
   `amount` decimal(15,2) DEFAULT NULL,
   `interest_rate` decimal(11,3) DEFAULT NULL,
   `commencement_date` date DEFAULT NULL,
   `end_date` date DEFAULT NULL,
   `date_basis` varchar(10) DEFAULT NULL,
   `result` decimal(11,3) DEFAULT NULL
);');
        foreach ($trn_fixing as $tt_insert) {
            $sql_insert = 'INSERT INTO ' . $table_name . ' (`tr_number`, `amount`, `interest_rate`, `commencement_date`, `end_date`, `date_basis`) values (';
            $sql_insert .= $tt_insert['tr_number'] . ', ';
            $sql_insert .= $tt_insert['amount'] . ', ';
            $sql_insert .= $tt_insert['interest_rate'] . ', ';
            $sql_insert .= '"' . $tt_insert['commencement_date'] . '", ';
            $sql_insert .= '"' . $tt_insert['end_date'] . '", ';
            $sql_insert .= '"' . $tt_insert['date_basis'] . '" )';
            //error_log("create from object : ".json_encode($tt_insert, true));
            error_log("creating table create line : " . $sql_insert);
            $this->Transaction->query($sql_insert);
        }
        $params = array(
            "table"    =>    $table_name,
        );
        $interest = $this->SAS->curl("register_confirmation_new.sas", $params, false);
        $sql_get_result = "SELECT * FROM " . $table_name . " ; ";
        $results = $this->Transaction->query($sql_get_result);
        error_log("creating table fixing calculations : " . json_encode($results, true));
        $sql_delete_table = "DROP TABLE " . $table_name . ";";
        $this->Transaction->query($sql_delete_table);

        $this->set(compact('transactions'));

        $this->set('session', $_SESSION);

        if ($this->request->is('post') or $this->request->is('ajax')) {
            if (empty($this->request->data['action']) && empty($this->request->data['filterform']['action'])) {
                if (!is_array($_SESSION['fixing'])) {
                    $_SESSION['fixing'] = array();
                }
                if (!empty($_SESSION['fixing']["fixing_trn_" . $this->request->data['Transaction']['tr_number']])) {
                    error_log("double fixing");
                    exit();
                } else {
                    $_SESSION['fixing']["fixing_trn_" . $this->request->data['Transaction']['tr_number']] = true;
                }
                if (Cache::read("fixing_trn_" . $this->request->data['Transaction']['tr_number'], 'treasury')) {
                    error_log("double fixing");
                    exit();
                } else {
                    Cache::write("fixing_trn_" . $this->request->data['Transaction']['tr_number'], "running", 'treasury');
                }

                if (!empty($this->request->data['intfixing']['capitalisation_date'])) {
                    try {
                        $fixing_date = strtotime($this->request->data['Transaction']['fixingdate']);
                        $capi_date = strtotime($this->request->data['intfixing']['capitalisation_date']);
                        if ($fixing_date > $capi_date) {
                            //the js stoppropagation does not work every time, so we add this security
                            return false;
                        }
                    } catch (Exception $e) {
                        error_log("dates failed : " . json_encode($e, true));
                    }
                }

                // setting default values for interest and tax (to uncomment when Anna finish booking tests)
                if (empty($this->request->data['tax_accrued_interest'])) {
                    $this->request->data['tax_accrued_interest'] = 0.00;
                }
                if (empty($this->request->data['Transaction']['eom'])) {
                    $this->request->data['Transaction']['eom'] = 0.00;
                }
                if (!empty($this->request->data['intfixing'])) {
                    if (empty($this->request->data['intfixing']['tax_interest_capitalisation'])) {
                        $this->request->data['intfixing']['tax_interest_capitalisation'] = 0.00;
                    }
                    if (empty($this->request->data['intfixing']['interest_capitalisation'])) {
                        $this->request->data['intfixing']['interest_capitalisation'] = 0.00;
                    }
                }
                //data[intfixing][eom_tax] remain empty
                $this->Transaction->set($this->request->data);
                $eom_tax = '';
                if (isset($this->request->data['intfixing']['tax_accrued_interest'])) {
                    $eom_tax = $this->request->data['intfixing']['tax_accrued_interest'];
                }

                $error_count = 0;

                $tr_number = $this->request->data['Transaction']['tr_number'];
                $tr = $this->Transaction->findByTrNumber($tr_number);
                $matu_date = str_replace('/', '-', $tr["Transaction"]["maturity_date"]);
                $commencement_date = str_replace('/', '-', $tr["Transaction"]["commencement_date"]);
                $state = $tr["Transaction"]["tr_state"];

                $fixing_date = null;
                if (!empty($this->request->data['Transaction']['fixingdate'])) {
                    $fixing_date = strtotime($this->request->data['Transaction']['fixingdate']);

                    if ($fixing_date <= $commencement_date) {
                        $error_count += 1;
                        $this->Session->setFlash("Fixing date cannot be lower than the commencement date", "flash/error", array(), 'error');
                        $this->redirect($this->referer());
                    }
                }

                if (!empty($this->request->data['intfixing']['capitalisation_date'])) {
                    $capitalisation_date = strtotime($this->request->data['intfixing']['capitalisation_date']);

                    if ($capitalisation_date < $commencement_date) {
                        $error_count += 1;
                        $this->Session->setFlash("Capitalisation date cannot be lower than the commencement date", "flash/error", array(), 'error');
                        $this->redirect($this->referer());
                    }

                    if ($capitalisation_date < $fixing_date) {
                        $error_count += 1; // => JS
                        $this->Session->setFlash("Capitalisation date cannot be before the Fixing date", "flash/error", array(), 'error');
                        $this->redirect($this->referer());
                    }
                }

                if ($state == 'Called') {
                    $timestamp_matu_date = strtotime($matu_date);
                    $timestamp_fixing_date = strtotime($this->request->data['Transaction']['fixingdate']);
                    if ($timestamp_fixing_date > $timestamp_matu_date) {
                        //$this->Session->setFlash("Fixing date cannot be after the maturity date of the called deposit", "flash/error");
                        //temporarely uncommented : TODO
                        $this->Session->setFlash("Fixing date cannot be after the maturity date of the called deposit", "flash/error");
                        // end temp uncomment
                        error_log("interest fixing : Fixing date cannot be after the maturity date of the called deposit");
                        $error_count += 1;
                        $Transaction =  array("fixingdate" => "Fixing date cannot be after the maturity date of the called deposit");
                        $data = compact('Transaction');
                        $this->set('errors', $data);
                        $this->set('_serialize', array('errors'));
                    }
                }

                if ($error_count < 1) {
                    $tax = 0.00;
                    if ($this->request->data['intfixing']['no_capitalisation'] == '1') {
                        $no_capitalisation         = 1;
                    } else {
                        $no_capitalisation         = 0;
                        $capitalisation_date     = $this->request->data['intfixing']['capitalisation_date'];
                        $tax = str_replace(',', '', $this->request->data['intfixing']['tax_interest_capitalisation']);
                    }

                    // INIT: amount left calculation based on account scheme
                    $int_cap = 0.00;
                    if (isset($this->request->data['intfixing']['interest_capitalisation'])) {
                        $int_cap = implode('', explode(',', $this->request->data['intfixing']['interest_capitalisation']));
                    }

                    switch ($tr['Transaction']['scheme']) {
                        case 'AA':
                            $amount_leftA             = (float) ($tr['Transaction']['amount'] + $int_cap - $tax);
                            $amount_leftB             = 0.00;
                            $new_repayment             = false;
                            $amount                 = $amount_leftA;
                            $rollover_source_fund    = 'A';
                            break;
                        case 'BB':
                            $amount_leftA             = 0.00;
                            $amount_leftB             = (float) ($tr['Transaction']['amount'] + $int_cap - $tax);
                            $new_repayment             = false;
                            $amount                 = $amount_leftB;
                            $rollover_source_fund    = 'B';
                            break;
                        case 'AB':
                            $amount_leftA             = (float) ($tr['Transaction']['amount']);
                            $amount_leftB             = (float) ($int_cap - $tax);
                            $new_repayment             = true;
                            $amount                 = $amount_leftA;
                            $amount_repay            = $amount_leftB;
                            $rollover_source_fund    = 'A';
                            $repay_source_fund        = 'B';
                            break;
                        default:
                            $this->Session->setFlash("Error in the Transaction scheme ID: $tr_number, please contact the administrator", "flash/error");
                            $error_count += 1;
                            return 0;
                            break;
                    }
                    if ($error_count < 1) {
                        if (!(bool)$no_capitalisation) {
                            // Get compartment's accounts
                            $accounts = $this->Compartment->find(
                                'first',
                                array(
                                    'conditions' => array(
                                        'Compartment.cmp_ID' => $this->Transaction->getAttribByTrn('cmp_ID', $tr_number)
                                    ),
                                    'fields' => array(
                                        'Compartment.accountA_IBAN as accountA', 'Compartment.accountB_IBAN as accountB'
                                    ),
                                )
                            );
                            $accounts = array_shift($accounts);

                            // STEP1: Creation of the reivestment group
                            $this->Reinvestment->create();
                            $this->Reinvestment->set('reinv_status', 'Open');
                            $this->Reinvestment->set('mandate_ID', $tr['Transaction']['mandate_ID']);
                            $this->Reinvestment->set('cmp_ID', $tr['Transaction']['cmp_ID']);
                            $this->Reinvestment->set('cpty_ID', $tr['Transaction']['cpty_id']);
                            $this->Reinvestment->set('availability_date', $capitalisation_date);
                            $this->Reinvestment->set('accountA_IBAN', $accounts['accountA']);
                            $this->Reinvestment->set('accountB_IBAN', $accounts['accountB']);
                            $this->Reinvestment->set('amount_leftA', $amount_leftA);
                            $this->Reinvestment->set('amount_leftB', $amount_leftB);
                            $this->Reinvestment->set('reinv_type', 'Fixing');
                            $reinv_fixing = $this->Reinvestment->save();
                            $reinv_id = $this->Reinvestment->id;
                            $this->log_entry('Reinvestment created for fixing ' . $reinv_id . ': ' . print_r($reinv_fixing, true), 'treasury', $tr_number);

                            // STEP2: Creation of a rollover
                            $interest_rate = null;
                            if ($tr['Transaction']['depo_type'] == "Callable") {
                                $interest_rate = $this->Interest->getInterestAt($tr['Transaction']['tr_number'], $capitalisation_date);
                            } else {
                                $interest_rate = $tr['Transaction']['interest_rate'];
                            }
                            $this->Transaction->create();
                            $data = array('Transaction' => array(
                                'tr_type'            => 'Rollover',
                                'tr_state'            => 'Confirmed',
                                'depo_type'            => 'Callable',
                                'depo_renew'        => $tr['Transaction']['depo_renew'],
                                'rate_type'            => $tr['Transaction']['rate_type'],
                                'booking_status'    => 'Not booked',
                                'source_group'        => $reinv_id,
                                'interest_rate'        => $interest_rate, //$tr['Transaction']['interest_rate'],
                                'tax_ID'            => $tr['Transaction']['tax_ID'],
                                'date_basis'        => $tr['Transaction']['date_basis'],
                                'reference_rate'    => $tr['Transaction']['reference_rate'],
                                'original_id'        => $tr['Transaction']['original_id'],
                                'parent_id'            => $tr_number,
                                'amount'            => $amount,
                                'commencement_date'    => $capitalisation_date,
                                'accountA_IBAN'        => $tr['Transaction']['accountA_IBAN'],
                                'accountB_IBAN'        => $tr['Transaction']['accountB_IBAN'],
                                'scheme'            => $tr['Transaction']['scheme'],
                                'mandate_ID'        => $tr['Transaction']['mandate_ID'],
                                'cmp_ID'            => $tr['Transaction']['cmp_ID'],
                                'cpty_id'            => $tr['Transaction']['cpty_id'],
                                'source_fund'        => $rollover_source_fund,
                                'instr_num'            => $tr['Transaction']['instr_num'],
                            ));
                            $this->Transaction->save($data);
                            $rollover_id = $this->Transaction->id;
                            // create Interest line for this Rollover
                            $this->Interest->updateInterest($rollover_id, $tr['Transaction']['interest_rate'], $capitalisation_date);

                            $rollover_id = $this->Transaction->id;
                            if ($new_repayment) {
                                $this->Transaction->create();
                                $data = array(
                                    'Transaction' => array(
                                        'tr_type'            => 'Repayment',
                                        'tr_state'            => 'Confirmed',
                                        'source_group'        => $reinv_id,
                                        'original_id'        => $tr['Transaction']['original_id'],
                                        'parent_id'            => $tr_number,
                                        'amount'            => $amount_repay,
                                        'commencement_date'    => $capitalisation_date,
                                        'maturity_date'        => $capitalisation_date,
                                        'booking_status'    => 'Not booked',
                                        'accountA_IBAN'        => $tr['Transaction']['accountB_IBAN'],
                                        'accountB_IBAN'        => $tr['Transaction']['accountB_IBAN'],
                                        'mandate_ID'        => $tr['Transaction']['mandate_ID'],
                                        'cmp_ID'            => $tr['Transaction']['cmp_ID'],
                                        'cpty_id'            => $tr['Transaction']['cpty_id'],
                                        'source_fund'        => $repay_source_fund,
                                    )
                                );
                                //$tab = array_merge($tr['Transaction'], $data['Transaction']);
                                $this->Transaction->save($data);
                                $repayment_id = $this->Transaction->id;
                                $this->log_entry('Interest capitalised to rollover ' . $rollover_id . ', TRN ' . $tr_number . ' ' . print_r($this->request->data['Transaction'], true), 'treasury', $tr_number);
                                $this->log_entry('Interest capitalised to rollover ' . $rollover_id . ', TRN ' . $tr_number . ' ' . print_r($this->request->data['Transaction'], true), 'treasury', $rollover_id);
                            } else {
                                $this->log_entry('Transaction fixed to rollover ' . $rollover_id . ', TRN ' . $tr_number . ' ' . print_r($this->request->data['Transaction'], true), 'treasury', $tr_number);
                                $this->log_entry('Transaction fixed to rollover ' . $rollover_id . ', TRN ' . $tr_number . ' ' . print_r($this->request->data['Transaction'], true), 'treasury', $rollover_id);
                            }

                            $reinv = $this->Reinvestment->getRawsById($reinv_id);
                            $reinv = $reinv[0];
                            $reinv['Reinvestment']['amount_leftA'] = 0.00;
                            $reinv['Reinvestment']['amount_leftB'] = 0.00;
                            $reinv['Reinvestment']['reinv_status'] = 'Closed';
                            $reinv_fixing = $this->Reinvestment->save($reinv);

                            $this->log_entry('Reinvestment updated for fixing ' . $reinv_id . ': ' . print_r($reinv_fixing, true), 'treasury', $tr_number);
                        }

                        $this->Transaction->read(null, $tr_number);
                        if (!$no_capitalisation) {
                            $this->Transaction->set('tr_state', 'Reinvested');
                            $this->Transaction->set('total_interest', $int_cap);
                            $this->Transaction->set('reinv_group', $reinv_id);
                            $this->Transaction->set('maturity_date', $capitalisation_date);
                            $this->Transaction->set('fixing_date', $this->request->data['Transaction']['fixingdate']);
                            $this->Transaction->set('eom_interest', $this->Transaction->formatAmounts($this->request->data['Transaction']["eom"]));
                            $this->Transaction->set('eom_tax', $this->Transaction->formatAmounts($this->request->data['intfixing']['tax_accrued_interest']));
                            $this->Transaction->set('tax_amount', $this->Transaction->formatAmounts($this->request->data['intfixing']['tax_interest_capitalisation']));
                            //$this->log_entry('Interest capitalized to rollover '.$rollover_id.', TRN '.$tr_number.' '.print_r($this->request->data['Transaction'],true), 'treasury');
                            /* log with cap:*/
                            $log_msg = "Interest capitalised to rollover,  Array
(
	[tr_number] => " . $tr_number . "
	[fixingdate] => " . $this->data['Transaction']['fixingdate'] . "
	[eom_interest] => " . $this->data['Transaction']['eom'] . "
	[eom_tax] => " . $this->data['intfixing']['tax_accrued_interest'] . "
	[tr_number_rollover] => " . $rollover_id . "
	[capdate] => " . $this->data['intfixing']['capitalisation_date'] . "
	[total_interest] => " . $this->data['intfixing']['interest_capitalisation'] . "
	[tax_amount] => " . $this->data['intfixing']['tax_interest_capitalisation'] . "
)";
                            $this->log_entry($log_msg, 'treasury', $tr_number);
                        } else { //clicked
                            $this->Transaction->set('fixing_date', $this->request->data['Transaction']['fixingdate']);
                            $this->Transaction->set('eom_interest', $this->Transaction->formatAmounts($this->request->data['Transaction']["eom"]));
                            $this->Transaction->set('eom_tax', $this->Transaction->formatAmounts($this->request->data['intfixing']['tax_accrued_interest']));
                            /* log fix interest no cap: content of the screen*/
                            $log_message = "Interest capitalised TRN " . $tr_number . ", Array
(
	[tr_number] => " . $tr_number . "
	[fixingdate] => " . $this->data['Transaction']['fixingdate'] . "
	[eom_interest] => " . $this->data['Transaction']['eom'] . "
	[eom_tax] => " . $this->data['intfixing']['tax_accrued_interest'] . "
)";
                            $this->log_entry($log_message, 'treasury', $tr_number);
                        }
                        $ok_saved = $this->Transaction->save();

                        $event = new CakeEvent('Model.Treasury.Transaction.change', $this, array("transaction" => $ok_saved));
                        $this->getEventManager()->dispatch($event);

                        if (!(bool)$no_capitalisation) {
                            if ($new_repayment) {
                                $this->set('success', Router::url('/treasury/treasurytransactions/interest_fixing_result/' . $no_capitalisation . '/' . $tr_number . '/' . $rollover_id . '/' . $repayment_id, false));
                            } else {
                                $this->set('success', Router::url('/treasury/treasurytransactions/interest_fixing_result/' . $no_capitalisation . '/' . $tr_number . '/' . $rollover_id, false));
                            }
                        } else {
                            $this->set('success', Router::url('/treasury/treasurytransactions/interest_fixing_result/' . $no_capitalisation . '/' . $tr_number, false));
                        }
                        $this->set('_serialize', array('success'));
                    }
                } else {
                    $Transaction = $this->Transaction->validationErrors;
                    $data = compact('Transaction');
                    $this->set('errors', $data);
                    $this->set('_serialize', array('errors'));
                }
                unset($_SESSION['fixing']["fixing_trn_" . $this->request->data['Transaction']['tr_number']]);
                Cache::delete("fixing_trn_" . $this->request->data['Transaction']['tr_number'], 'treasury');
            }
        }
    }

    /**
     * Fixing interest of a callable deposit
     */

    public function manual_fixing()
    {
        clearstatcache(true);
        if (Cache::read("automatic_fixing", 'treasury')) {
            $this->Session->setFlash("Process of the automatic interest fixing is currently running. Your modification on the transactions could create a conflict in the database. Please try again in 5 minutes.", 'flash/warning');
        }

        $transactions_all_fixing = $this->Transaction->getTransactions_fixing();
        $mandate_id_list = array();
        $cpty_id_list = array();
        foreach ($transactions_all_fixing as $trn) {
            $mandate_id_list[] = $trn['Transaction']['mandate_ID'];
            $cpty_id_list[] = $trn['Transaction']['cpty_id'];
        }

        $mandates = $this->Mandate->find('list', array(
            'order' => 'Mandate.mandate_name',
            'conditions' => array("Mandate.mandate_id" => $mandate_id_list),
            'fields' => array('mandate_id', 'mandate_name'),
        ));
        $this->set('mandates', $mandates);
        $counterparties = $this->Counterparty->find('list', array(
            'order' => 'Counterparty.cpty_name',
            'conditions' => array("Counterparty.cpty_ID" => $cpty_id_list),
            'fields' => array('cpty_ID', 'cpty_name'),
        ));
        $this->set('counterparties', $counterparties);

        $mandate_id_filter = null;
        $cpty_id_filter = null;
        if (!empty($this->request->data['filterform']['mandate_id'])) {
            $mandate_id_filter = $this->request->data['filterform']['mandate_id'];
        }
        if (!empty($this->request->data['filterform']['cpty_id'])) {
            $cpty_id_filter = $this->request->data['filterform']['cpty_id'];
        }
        if (!empty($this->request->data['selection_date']['mandate_id'])) {
            $mandate_id_filter = $this->request->data['selection_date']['mandate_id'];
        }
        if (!empty($this->request->data['selection_date']['cpty_id'])) {
            $cpty_id_filter = $this->request->data['selection_date']['cpty_id'];
        }
        $this->set('mandate_id_filter', $mandate_id_filter);
        $this->set('cpty_id_filter', $cpty_id_filter);

        $transactions = array();
        $fixing_interests = array();
        $capitalisation_interests = array();
        if (!empty($cpty_id_filter)) {
            $transactions = $this->Transaction->getTransactions_fixing(null, $mandate_id_filter, $cpty_id_filter);

            if (!empty($this->request->data['selection_date'])) {
                if (!empty($this->request->data['selection_date']['fixing_date'])) {
                    $trn_list_fixing = array();
                    $fixing_date = $this->fix_date($this->request->data['selection_date']['fixing_date']);
                    foreach ($transactions as $transaction_fixing) {
                        $tr_number = $transaction_fixing['Transaction']['tr_number'];
                        $date_basis = $transaction_fixing['Transaction']['date_basis'];
                        $amount = $transaction_fixing['Transaction']['amount'];
                        $rate = $transaction_fixing['Transaction']['rate'];
                        $commencement_date = $this->fix_date($transaction_fixing['Transaction']['commencement_date']);
                        // several interest rates ?
                        if (strtotime($commencement_date) < strtotime($fixing_date)) {
                            $interests = $this->getInterestList($tr_number, $commencement_date, $fixing_date, $date_basis, $amount, $rate);

                            foreach ($interests as $intrst) {
                                $trn_list_fixing[] = $intrst;
                            }
                            // 1 day add
                            $is_rollover = ($transaction_fixing['Transaction']['tr_type'] == 'Rollover');
                            $is_from_partial_call_tr = $transaction_fixing['Transaction']['parent_id'];
                            $is_from_partial_call = !empty($this->Transaction->find('first', array('conditions' => array('parent_id' => $is_from_partial_call_tr, 'tr_type' => 'Call'))));
                            if ($is_rollover && !$is_from_partial_call) {
                                $date_end = date('Y-m-d', strtotime($commencement_date . ' +1 day'));
                                $int = $this->Interest->getInterestAt($tr_number, $date_end);
                                $trn_list_fixing[] = array(
                                    'tr_number' => $tr_number,
                                    'amount' => $transaction_fixing['Transaction']['amount'],
                                    'interest_rate' => $this->Interest->getInterestAt($tr_number, $date_end),
                                    'commencement_date' => $commencement_date,
                                    'end_date' => $date_end,
                                    'date_basis' => $transaction_fixing['Transaction']['date_basis'],
                                );
                                error_log("single interest: ");
                            }
                        }
                    }
                    $this->set('fixing_date', $fixing_date);
                    $fixing_interests = $this->interest_calculation_table($trn_list_fixing);
                }
                if (!empty($this->request->data['selection_date']['capitalisation_date'])) {
                    $trn_list_capitalisation = array();
                    $capitalisation_date = $this->fix_date($this->request->data['selection_date']['capitalisation_date']);
                    foreach ($transactions as $transaction_fixing) {
                        $tr_number = $transaction_fixing['Transaction']['tr_number'];
                        $date_basis = $transaction_fixing['Transaction']['date_basis'];
                        $amount = $transaction_fixing['Transaction']['amount'];
                        $rate = $transaction_fixing['Transaction']['rate'];
                        $commencement_date = $this->fix_date($transaction_fixing['Transaction']['commencement_date']);
                        // several interest rates ?
                        if (strtotime($commencement_date) < strtotime($capitalisation_date)) {
                            $interests = $this->getInterestList($tr_number, $commencement_date, $capitalisation_date, $date_basis, $amount, $rate);

                            foreach ($interests as $intrst) {
                                $trn_list_capitalisation[] = $intrst;
                            }
                        }
                    }

                    $this->set('capitalisation_date', $capitalisation_date);
                    $capitalisation_interests = $this->interest_calculation_table($trn_list_capitalisation);
                }
            }
        }
        $this->set(compact('transactions'));
        $this->set(compact('fixing_interests'));
        $this->set(compact('capitalisation_interests'));

        //saving in DB:
        if (!empty($this->request->data['fixing']['action'] == 'saving')) {
            $number_of_processed_trn = 0;
            $fixing_date = $this->request->data['fixing']['fixing_date'];
            $capitalisation_date = $this->request->data['fixing']['capitalisation_date'];
            unset($this->request->data['fixing']['fixing_date']);
            unset($this->request->data['fixing']['capitalisation_date']);
            unset($this->request->data['fixing']['action']);
            $fixing_interest_set = true;
            $fixing_interest_set_message = '';
            foreach ($this->request->data as $key => $POST) {
                if ($POST['selected'] == 1) {
                    if (empty($POST["fixing_interest"])) {
                        $fixing_interest_set = false;
                        $fixing_interest_set_message .= intval(str_replace('tr_', '', $key)) . ', ';
                    }
                }
            }
            if ($fixing_interest_set) {
                foreach ($this->request->data as $key => $POST) {
                    if ($POST['selected'] == 1) {
                        $tr_number = intval(str_replace('tr_', '', $key));
                        if (!empty($tr_number)) {
                            $fixing_interest = $POST["fixing_interest"];
                            $capitalisation_interest = $POST["capitalisation_interest"];
                            if (!empty($capitalisation_interest)) {
                                //capitalisation
                                $this->Transaction->save_fixing_capitalisation(false, $tr_number, $capitalisation_date,  $capitalisation_interest, $fixing_date, $fixing_interest);
                                $number_of_processed_trn++;
                            } else {
                                //fixing only
                                $this->Transaction->save_fixing_capitalisation(true, $tr_number, null,  null, $fixing_date, $fixing_interest);
                                $number_of_processed_trn++;
                            }
                        }
                    }
                }
                $this->Session->setFlash('Manual Interest Fixing has been successfully performed. Number of processed transactions: ' . $number_of_processed_trn, 'flash/success');
            } else {
                $fixing_interest_set_message = trim($fixing_interest_set_message, ' ,');
                $this->Session->setFlash('There are missing interest values for the following transactions: ' . $fixing_interest_set_message, 'flash/error');
            }
        }
    }
    /**
     * Fixing interest of a callable deposit
     */

    public function automatic_fixing()
    {
        clearstatcache(true);
        if (Cache::read("automatic_fixing", 'treasury')) {
            $this->Session->setFlash("Process of the automatic interest fixing is currently running. Your modification on the transactions could create a conflict in the database. Please try again in 5 minutes.", 'flash/warning');
        }
        $month_list = array(
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        );
        $this->set('month_list', $month_list);

        $year_list = array();
        for ($i = date('Y'); $i >= 2012; $i--) $year_list[$i] = $i;
        $this->set('year_list', $year_list);

        $year_filter = null;
        $month_filter = null;
        $action = null;
        if (!empty($this->request->data['filterform']['year'])) {
            $year_filter = $this->request->data['filterform']['year'];
        }
        if (!empty($this->request->data['filterform']['month'])) {
            $month_filter = $this->request->data['filterform']['month'];
        }
        if (!empty($this->request->data['filterform']['action'])) {
            $action = $this->request->data['filterform']['action'];
        }
        if (!empty($this->request->data['fixing']['year'])) {
            $year_filter = $this->request->data['fixing']['year'];
        }
        if (!empty($this->request->data['fixing']['month'])) {
            $month_filter = $this->request->data['fixing']['month'];
        }
        if (!empty($this->request->data['fixing']['action'])) {
            $action = $this->request->data['fixing']['action'];
        }

        $this->set('year_filter', $year_filter);
        $this->set('month_filter', $month_filter);

        $transactions_confirmed = array();

        if (!empty($month_filter) && !empty($year_filter) && ($action == 'filter')) {
            $selected_date = strtotime('last day of ' . $year_filter . "-" . $month_filter);
            $current_month = strtotime('last day of ' . date("15-m-Y")); //current month (end)
            $last_month = strtotime('first day of last month'); //last month (beginning)
            if (($selected_date < $last_month) || ($selected_date > $current_month)) {
                error_log("WARNING The automatic fixing can only be run for the current month or the previous month: selected_date=" . $selected_date);
                $msg_result = "The automatic fixing can only be run for the current month or the previous month.";
                $this->Session->setFlash($msg_result, "flash/default");
            }
            $month_matching_period = array(
                'Monthly' => array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12), //every month
                'Quarterly' => array(3, 6, 9, 12),
                'Semi-annually' => array(6, 12),
                'Annually' => array(12)
            );
            $ctpy_auto = $this->Transaction->query("SELECT cpty_ID, capitalisation_frequency, cpty_name FROM counterparties WHERE automatic_fixing=1");
            $ctpy_automatic = array();
            $cpty_to_be_capitalised = array();
            foreach ($ctpy_auto as $ctpy) {
                $ctpy_automatic[] = $ctpy['counterparties']['cpty_ID'];
                $capitalisation_frequency = $ctpy['counterparties']['capitalisation_frequency'];
                if (in_array($month_filter,  $month_matching_period[$capitalisation_frequency])) {
                    //capitalisation process
                    $cpty_to_be_capitalised[] = $ctpy['counterparties']['cpty_name'];
                }
            }
            //fixing_date = last day of selected month
            $fixing_date = date("Y-m-d", $selected_date);
            $transactions_confirmed = $this->Transaction->find('all', array(
                'conditions' => array(
                    'depo_type' => 'Callable',
                    'tr_state'    => 'Confirmed',
                    'rate_type' => 'Fixed',
                    'tr_type'    => array('Deposit', 'Rollover'),
                    'commencement_date <= ' => $fixing_date,
                    'Transaction.cpty_id' => $ctpy_automatic
                ), 'recursive' => 0, 'order' => array('Counterparty.cpty_name ASC', 'Transaction.tr_number DESC')
            ));
            $this->set('transactions_confirmed', $transactions_confirmed);
            //$this->set('cpty_to_be_capitalised', $cpty_to_be_capitalised);
            if (!empty($cpty_to_be_capitalised)) {
                $cpty_to_be_capitalised = preg_filter('/^/', '<li>', $cpty_to_be_capitalised);
                $cpty_to_be_capitalised = preg_filter('/$/', '</li>', $cpty_to_be_capitalised);
                $msg = "The selected period will trigger the capitalisation of the transactions under <ul>";
                $msg .= implode($cpty_to_be_capitalised);
                $msg .= "</ul>";
                $this->Session->setFlash($msg, 'flash/warning');
            }
        }

        if (!empty($month_filter) && !empty($year_filter) && ($action == 'saving')) {
            $fixing_interests = array();
            $capitalisation_interests = array();
            $selected_date = strtotime('last day of ' . $year_filter . "-" . $month_filter);
            $current_month = strtotime('last day of ' . date("15-m-Y")); //current month (end)
            $last_month = strtotime('first day of last month'); //last month (beginning)
            if (($selected_date < $last_month) || ($selected_date > $current_month)) {
                error_log("WARNING The automatic fixing can only be run for the current month or the previous month: selected_date=" . $selected_date);
                $msg_result = "The automatic fixing can only be run for the current month or the previous month.";
                $this->Session->setFlash($msg_result, "flash/default");
                $this->redirect($this->referer());
            }
            $month_matching_period = array(
                'Monthly' => array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12), //every month
                'Quarterly' => array(3, 6, 9, 12),
                'Semi-annually' => array(6, 12),
                'Annually' => array(12)
            );

            $period = null;
            $ctpy_auto = $this->Transaction->query("SELECT cpty_ID FROM counterparties WHERE automatic_fixing=1");
            $ctpy_automatic = array();
            foreach ($ctpy_auto as $ctpy) {
                $ctpy_automatic[] = $ctpy['counterparties']['cpty_ID'];
            }
            //fixing_date = last day of selected month
            $fixing_date = date("Y-m-d", $selected_date);
            $capitalisation_date = date("Y-m-d", strtotime('+1 day', $selected_date));
            $transactions_confirmed = $this->Transaction->find('all', array(
                'conditions' => array(
                    'depo_type' => 'Callable',
                    'tr_state'    => 'Confirmed',
                    'rate_type' => 'Fixed',
                    'tr_type'    => array('Deposit', 'Rollover'),
                    'commencement_date <= ' => $fixing_date,
                    'Transaction.cpty_id' => $ctpy_automatic
                ), 'recursive' => 0, 'order' => array('Counterparty.cpty_name ASC', 'Transaction.tr_number DESC')
            ));

            $this->set('transactions_confirmed', $transactions_confirmed);
            $fail = array();
            $success = array();
            $trn_list_capitalisation = array();
            $trn_list_fixing = array();

            error_log("automatic fixing monitor line " . __LINE__);
            error_log("automatic fixing monitor confirmed: " . count($transactions_confirmed));
            foreach ($transactions_confirmed as $trn) {
                $rate = $trn['Transaction']['rate'];
                $tr_number = $trn['Transaction']['tr_number'];
                $interest_rate = $trn['Transaction']['interest_rate'];
                $date_basis = $trn['Transaction']['date_basis'];
                if (empty($interest_rate)) {
                    error_log("automatic fixing monitor fail no interest rate " . $tr_number);
                    $fail[] = $tr_number;
                }
                if (empty($date_basis)) {
                    error_log("automatic fixing monitor fail no date basis " . $tr_number);
                    $fail[] = $tr_number;
                }
                $capitalisation_frequency = $trn['Counterparty']['capitalisation_frequency'];

                error_log("automatic fixing monitor line " . __LINE__ . " : cap freg: " . $capitalisation_frequency);
                if (!empty($capitalisation_frequency)) {
                    $amount = $trn['Transaction']['amount'];
                    $commencement_date = $trn['Transaction']['commencement_date'];
                    $commencement_date_exp = explode('/', $commencement_date);
                    $commencement_date = $commencement_date_exp[2] . "-" . $commencement_date_exp[1] . "-" . $commencement_date_exp[0];

                    $interests = $this->getInterestList($tr_number, $commencement_date, $fixing_date, $date_basis, $amount, $rate);

                    error_log("automatic fixing monitor getInterestList line " . __LINE__);
                    error_log("automatic fixing monitor getInterestList " . json_encode($interests, 2));
                    foreach ($interests as $intrst) {
                        $trn_list_fixing[] = $intrst;
                    }

                    // 1 day add
                    $is_rollover = ($trn['Transaction']['tr_type'] == 'Rollover');
                    $is_from_partial_call_tr = $trn['Transaction']['parent_id'];
                    $is_from_partial_call = !empty($this->Transaction->find('first', array('conditions' => array('parent_id' => $is_from_partial_call_tr, 'tr_type' => 'Call'))));
                    if ($is_rollover && !$is_from_partial_call) {
                        $date_end = date('Y-m-d', strtotime($commencement_date . ' +1 day'));
                        $int = $this->Interest->getInterestAt($tr_number, $date_end);
                        $trn_list_fixing[] = array(
                            'tr_number' => $tr_number,
                            'amount' => $trn['Transaction']['amount'],
                            'interest_rate' => $this->Interest->getInterestAt($tr_number, $date_end),
                            'commencement_date' => $commencement_date,
                            'end_date' => $date_end,
                            'date_basis' => $trn['Transaction']['date_basis'],
                        );
                    }

                    if (in_array($month_filter,  $month_matching_period[$capitalisation_frequency])) {
                        //capitalisation process
                        $interests = $this->getInterestList($tr_number, $commencement_date, $capitalisation_date, $date_basis, $amount, $rate);

                        error_log("automatic fixing monitor getInterestList line " . __LINE__);
                        error_log("automatic fixing monitor getInterestList " . json_encode($interests, 2));
                        foreach ($interests as $intrst) {
                            $trn_list_capitalisation[] = $intrst;
                        }
                    }
                } else {
                    error_log("automatic fixing monitor fail no cap freq " . $tr_number);
                    $fail[] = $tr_number;
                }
            }
            error_log("automatic fixing monitor before sas calc line " . __LINE__);
            error_log("automatic fixing monitor before sas calc trn_list_fixing " . json_encode($trn_list_fixing, 2));
            error_log("automatic fixing monitor before sas calc trn_list_capitalisation " . json_encode($trn_list_capitalisation, 2));
            $fixing_interests = $this->interest_calculation_table($trn_list_fixing);
            $capitalisation_interests = $this->interest_calculation_table($trn_list_capitalisation);

            error_log("automatic fixing monitor after sas calc line " . __LINE__);
            error_log("automatic fixing monitor fixing_interests line " . json_encode($fixing_interests, 2));
            error_log("automatic fixing monitor capitalisation_interests line " . json_encode($capitalisation_interests, 2));
            $number_of_processed_trn = 0;
            foreach ($transactions_confirmed as $trn_fixing) {
                $tr_number = $trn_fixing['Transaction']['tr_number'];
                $fixing_interest = $fixing_interests[$tr_number];
                if (!empty($capitalisation_interests[$tr_number])) {
                    //capitalisation
                    $capitalisation_interest = $capitalisation_interests[$tr_number];
                    $this->Transaction->save_fixing_capitalisation(false, $tr_number, $capitalisation_date,  $capitalisation_interest, $fixing_date, $fixing_interest);
                    $number_of_processed_trn++;
                } else {
                    //fixing only
                    $this->Transaction->save_fixing_capitalisation(true, $tr_number, null,  null, $fixing_date, $fixing_interest);
                    $number_of_processed_trn++;
                }
            }
            error_log("automatic fixing monitor end line " . __LINE__);
            $this->Session->setFlash('Automatic Interest Fixing has been successfully performed. Number of processed transactions: ' . $number_of_processed_trn, 'flash/success');
            Cache::delete('automatic_fixing', 'treasury');
        }
    }


    function export_interest_fixing_list($tr_number = null, $mandate_id = null, $ctpy_id = null)
    {
        if (!empty($this->passedArgs["tr_number"])) {
            $tr_number = $this->passedArgs["tr_number"];
        }
        if (!empty($this->passedArgs["mandate_id"])) {
            $mandate_id = $this->passedArgs["mandate_id"];
        }
        if (!empty($this->passedArgs["ctpy_id"])) {
            $ctpy_id = $this->passedArgs["ctpy_id"];
        }
        @$this->validate_param('int', $tr_number);
        @$this->validate_param('int', $mandate_id);
        @$this->validate_param('int', $ctpy_id);
        $transactions = $this->Transaction->getTransactions_fixing($tr_number, $mandate_id, $ctpy_id);
        if (empty($transactions)) {
            $this->Session->setFlash('There are no transaction for fixing with this Mandate and Counterparty',  'flash/error');
            $this->redirect($this->referer());
        }
        $this->autoLayout = false;

        $transactions_excel = array();
        foreach ($transactions as $trn) {
            $transactions_excel[] = array(
                "Transaction" => array(
                    "TRN" => $trn["Transaction"]["tr_number"],
                    "Transaction Type" => $trn["Transaction"]["tr_type"],
                    "State" => $trn["Transaction"]["tr_state"],
                    "DI" => $trn["Instruction"]["instr_num"],
                    "commencement date" => $trn["Transaction"]["commencement_date"],
                    "Amount" => $trn["Transaction"]["amount"],
                    "Currency" => $trn["AccountA"]["ccy"],
                    "Scheme" => $trn["Transaction"]["scheme"],
                    "Origin TRN" => $trn["Transaction"]["original_id"],
                    "Parent TRN" => $trn["Transaction"]["parent_id"],
                    "Mandate" => $trn["Mandate"]["mandate_name"],
                    "Compartment" => $trn["Compartment"]["cmp_name"],
                    "Counterparty" => $trn["Counterparty"]["cpty_name"],
                    "Fixing date" => $trn["Transaction"]["fixing_date"]
                )
            );
        }
        $filename = "export_callable_" . date('Y_m_d_h_i_s') . ".xlsx";
        $filepath = WWW . DS . 'data' . DS . 'treasury' . DS . 'export' . DS . $filename;
        $this->Spreadsheet->generateExcel($transactions_excel, array('Transaction'), $filepath);
        DownloadLib::Download($filepath);
        exit();
    }


    function interest_fixing_result($no_capitalisation, $tr_number, $rollover_id = null, $repayment_id = null)
    {

        @$this->validate_param('bool', $no_capitalisation);
        @$this->validate_param('int', $tr_number);
        @$this->validate_param('int', $rollover_id);
        @$this->validate_param('int', $repayment_id);
        $this->Transaction->virtualFields  =  array(
            'amountccy' => "CONCAT('Transaction.amount',' ','AccountA.ccy')",
            'interest'    => "CONCAT(Transaction.total_interest,' ',AccountA.ccy)",
            'tax'    => "CONCAT(Transaction.tax_amount,' ',AccountA.ccy)",
        );

        // Parent transaction
        $this->Transaction->setFieldsToDisplay($tr_number);

        $conditions = array('conditions' => array('tr_number' => $tr_number), 'fields' => '*', 'recursive' => 0);
        $parent_transaction = $this->Transaction->find("first", $conditions);
        $confTables = array('parent_transaction' => $parent_transaction);

        if (isset($repayment_id) && !$no_capitalisation) {
            // The Rollover resulting from interest fixing
            $conditions['conditions']['tr_number'] = $rollover_id;
            $confTables['rollover_of_principal'] = $this->Transaction->find("first", $conditions);

            // The repayment
            $this->Transaction->fieldsToDisplay = array(
                'Transaction.tr_number as TRN',
                'Transaction.tr_state as State',
                'Transaction.tr_type as Type',
                'Mandate.mandate_name',
                'Compartment.cmp_name',
                'amount',
                'AccountA.ccy',
                'Transaction.accountA_IBAN as repayment_account',
                'Transaction.source_fund as Source',
                'commencement_date as repayment_date',
            );
            $conditions['conditions']['tr_number'] = $repayment_id;
            $confTables['repayment_of_interest'] = $this->Transaction->find("first", $conditions);
        } elseif (!isset($repayment_id) && !$no_capitalisation) {

            // The Rollover resulting from interest fixing
            $this->Transaction->fieldsToDisplay = array(
                'tr_number',
                'Transaction.tr_state as State',
                'Transaction.tr_type as Type',
                'Mandate.mandate_name',
                'Compartment.cmp_name',
                'Transaction.accountA_IBAN as principal_account',
                'Transaction.accountB_IBAN as interest_account',
                'Transaction.depo_type as Term_or_Call',
                'commencement_date',
                'amount as Amount',
                'AccountA.ccy',
            );
            $conditions['conditions']['tr_number'] = $rollover_id;
            $confTables['rollover_of_principal_and_interest'] = $this->Transaction->find("first", $conditions);
        }
        $this->set(compact('confTables', 'parent_transaction', 'no_capitalisation'));
    }

    function  validatecallconf($reject = false)
    {
        @$this->validate_param('bool', $reject);

        $transactions  =  $this->Transaction->find(
            'all',
            array(
                'conditions'   =>  array(
                    'tr_type'  =>  array('Call'),
                    'tr_state' =>  array('Confirmation Received')
                ),
            )
        );


        foreach ($transactions as &$trn) // http://vmu-sas-01:8080/browse/TREASURY-510
        {
            //getting interest and tax from the called trn
            $called_trn_number = $trn['Transaction']['parent_id'];
            $called_trn = $this->Transaction->read(null,  $called_trn_number);
            if (!empty($called_trn)) {
                $trn['Transaction']['total_interest'] = $called_trn['Transaction']['total_interest'];
                $trn['Transaction']['tax_amount'] = $called_trn['Transaction']['tax_amount'];
            }
        }

        $this->set(compact('transactions'));

        if ($this->request->is('post')) {
            if (!empty($this->request->data['Transaction']['tr_number'])) {
                $sister_trn = $this->Transaction->findSisterTRN($this->request->data['Transaction']['tr_number']);
                $tr = $this->Transaction->read(null,  $this->request->data['Transaction']['tr_number']);

                if ($this->request->data['Transaction']['reject']) {

                    $this->Transaction->set('tr_state',  'Instruction Sent');
                    $this->Transaction->save();
                    if (isset($sister_trn['Transaction'])) {
                        $this->Transaction->read(null, $sister_trn['Transaction']['tr_number']);
                        $this->Transaction->set('tr_state', 'Instruction Sent');
                        $this->Transaction->save();
                        //$this->log_entry('The transaction '.$sister_trn['Transaction']['tr_number'].' changed  to  "Instruction Sent"', 'treasury');
                        $this->log_entry('Call Confirmation rejected TRN ' . $sister_trn['Transaction']['tr_number'], 'treasury', $sister_trn['Transaction']['tr_number']);
                    }
                    $this->Session->setFlash('The transaction ' . $this->request->data['Transaction']['tr_number'] . ' changed  to  "Instruction sent"',  'flash/default');
                    //$this->log_entry('The transaction '.$this->request->data['Transaction']['tr_number'].' changed  to  "Instruction sent"', 'treasury');
                    $this->log_entry('Call Confirmation rejected TRN ' . $this->request->data['Transaction']['tr_number'], 'treasury', $this->request->data['Transaction']['tr_number']);
                } else {
                    $tr_call = $this->Transaction->read(null, $this->request->data['Transaction']['tr_number']);
                    $tr_called = $this->Transaction->read(null, $tr_call['Transaction']['parent_id']);
                    $tr_call = $this->Transaction->read(null, $this->request->data['Transaction']['tr_number']); //yes i know, repetition
                    $principal = $tr_call["Transaction"]["amount"];
                    $interest = $tr_called['Transaction']['total_interest'];
                    $tax = $tr_called['Transaction']['tax_amount'];
                    $principal_call = floatval($principal) + floatval($interest) - floatval($tax);

                    $this->Transaction->set('amount',  $principal_call);
                    $this->Transaction->set('tr_state',  'Confirmed');

                    $this->Transaction->save();
                    if (isset($sister_trn['Transaction'])) {
                        $this->Transaction->read(null, $sister_trn['Transaction']['tr_number']);
                        $this->Transaction->set('tr_state', 'Confirmed');
                        $this->Transaction->save();
                        //$this->log_entry('The transaction '.$sister_trn['Transaction']['tr_number'].' changed  to  "Confirmed"', 'treasury');
                        $this->log_entry('Call Confirmed TRN ' . $sister_trn['Transaction']['tr_number'], 'treasury', $sister_trn['Transaction']['tr_number']);
                    }
                    $this->Session->setFlash('The transaction ' . $this->request->data['Transaction']['tr_number'] . ' changed to "Confirmed"', 'flash/success');
                    //$this->log_entry('The transaction '.$this->request->data['Transaction']['tr_number'].' changed  to  "Confirmed"', 'treasury');
                    $this->log_entry('Call ' . $this->request->data['Transaction']['tr_number'] . ' Confirmed.', 'treasury', $this->request->data['Transaction']['tr_number']);
                }
                /*$event = new CakeEvent('Model.Treasury.Transaction.change', $this, array("transaction" => $tr));
				$this->getEventManager()->dispatch($event);*/
                $this->redirect($this->referer());
            } else {
                $this->Session->setFlash('Please select a Transaction',  'flash/error');
            }
        }
    }

    function breakdeposit()
    {
        if (Cache::read("automatic_fixing", 'treasury')) {
            $this->Session->setFlash("Process of the automatic interest fixing is currently running. Your modification on the transactions could create a conflict in the database. Please try again in 5 minutes.", 'flash/warning');
        }
        $transactions = $this->Transaction->find(
            'all',
            array(
                'conditions' => array(
                    'tr_state'            => array('Confirmed', 'First Notification', 'Second Notification'),
                    'tr_type'            => array('Deposit', 'Rollover'),
                    'depo_type'            => array('Term'),
                )
            )
        );

        $this->set(compact('transactions'));

        if ($this->request->is('post') or $this->request->is('ajax')) {

            error_log("Transaction break deposit start");
            $this->request->data['Transaction']['reqamount'] = $this->Transaction->formatAmounts($this->request->data['Transaction']['reqamount']);
            if (isset($this->request->data['Transaction']['tr_number'])) {
                $tr = $this->Transaction->getTransactionById($this->request->data['Transaction']['tr_number']);
                $this->request->data['Transaction']['amount'] = $tr['Transaction']['amount'];
            }
            $this->Transaction->set($this->request->data);
            if ($this->Transaction->validateCallDeposit()) {
                $tr_number = $this->request->data['Transaction']['tr_number'];
                $tr = $this->Transaction->findByTrNumber($tr_number);

                $reqamount = (isset($this->request->data['Transaction']['reqamount'])) ? implode('', explode(',', $this->request->data['Transaction']['reqamount'])) :  0.00;

                $new_amount_left = $tr['Transaction']['amount'] - $reqamount;
                if ($new_amount_left < 0) {
                    $this->Session->setFlash("Cannot withdraw more than available !", "flash/default");
                    $this->redirect($this->referer());
                }

                if (isset($this->request->data['Transaction']['full'])) {
                    $new_amount_left = 0.00;
                    $reqamount = $tr['Transaction']['amount'];
                }

                switch ($tr['Transaction']['scheme']) {
                    case 'AA':
                        $amount_leftA     = (float) ($new_amount_left);
                        $amount_leftB     = 0.00;
                        break;
                    case 'BB':
                        $amount_leftA     = 0.00;
                        $amount_leftB     = (float) ($new_amount_left);
                        break;
                    case 'AB':
                        $amount_leftA     = (float) ($new_amount_left);
                        $amount_leftB     = 0.00;
                        break;
                    default:
                        $this->Session->setFlash("Error in the Transaction scheme ID: $tr_number, please contact the administrator", "flash/error");
                        $this->redirect($this->referer());
                        break;
                }

                // Get compartment's accounts
                $accounts = $this->Compartment->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Compartment.cmp_ID' => $this->Transaction->getAttribByTrn('cmp_ID', $tr_number)
                        ),
                        'fields' => array(
                            'Compartment.accountA_IBAN as accountA', 'Compartment.accountB_IBAN as accountB'
                        ),
                    )
                );

                $accounts = array_shift($accounts);

                $this->Reinvestment->create();
                $this->Reinvestment->set('reinv_status', 'Open');
                $this->Reinvestment->set('mandate_ID', $tr['Transaction']['mandate_ID']);
                $this->Reinvestment->set('cmp_ID', $tr['Transaction']['cmp_ID']);
                $this->Reinvestment->set('cpty_ID', $tr['Transaction']['cpty_id']);
                $this->Reinvestment->set('availability_date', $this->request->data['Transaction']['value_date']);
                $this->Reinvestment->set('accountA_IBAN', $accounts['accountA']);
                $this->Reinvestment->set('accountB_IBAN', $accounts['accountB']);
                $this->Reinvestment->set('amount_leftA', $amount_leftA);
                $this->Reinvestment->set('amount_leftB', $amount_leftB);
                $this->Reinvestment->set('reinv_type', 'Break');
                $reinv_created = $this->Reinvestment->save();
                //$reinv_id = $this->Reinvestment->id;
                $reinv_id = $reinv_created['Reinvestment']['reinv_group'];

                // interest calculation
                $date_basis = $tr['Transaction']['date_basis'];
                $date_end = explode('/', $this->request->data['Transaction']['value_date']);
                $date_end = $date_end[2] . '-' . $date_end[1] . '-' . $date_end[0];
                $commencement_date = explode('/', $tr['Transaction']['commencement_date']);
                $commencement_date = $commencement_date[2] . '-' . $commencement_date[1] . '-' . $commencement_date[0];
                $amount = $tr['Transaction']['amount'];
                $error = false;

                $interest_rate = $tr['Transaction']['interest_rate'];
                if (!empty($interest_rate)) {
                    $interest_rate = str_replace(',', '', $interest_rate);
                    $params = array(
                        "amount"            => $amount,
                        "new_date"            => $date_end,
                        "interest_rate"        => $interest_rate,
                        "date_basis"        => $date_basis,
                        "commencement_date"    => $commencement_date,
                    );
                    $interest_sasResult = $this->SAS->curl("register_confirmation_new.sas", $params, false);

                    if (strpos($interest_sasResult, 'This request completed with errors') !== false) {
                        $error = true;
                    } else {
                        $interest_sasResult = mb_convert_encoding($interest_sasResult, "UTF-8"); // to remove \ufeff
                        $interest_sasResult = trim($interest_sasResult); // to remove \r
                        $interest_sasResult = preg_replace("/[^-0-9\,\.]/", '', $interest_sasResult);
                        $total_interest = $interest_sasResult;
                    }
                }

                if ($error) {
                    error_log("error on interest calculation");
                }
                $this->Transaction->create();
                $data = array('Transaction' => array(
                    'tr_type'            => 'Withdrawal',
                    'tr_state'            => 'Created',
                    'rate_type'            => $tr['Transaction']['rate_type'],
                    'booking_status'    => 'Not booked',
                    'source_group'        => $reinv_id,
                    'original_id'        => $tr['Transaction']['original_id'],
                    'parent_id'            => $tr_number,
                    'amount'            => $reqamount,
                    'total_interest'    => $total_interest,
                    'commencement_date'    => $tr['Transaction']['commencement_date'],
                    'maturity_date'        => $this->request->data['Transaction']['value_date'],
                    'accountA_IBAN'        => $this->request->data['Transaction']['accountA_IBAN'],
                    'accountB_IBAN'        => $this->request->data['Transaction']['accountB_IBAN'],
                    'mandate_ID'        => $tr['Transaction']['mandate_ID'],
                    'cmp_ID'            => $tr['Transaction']['cmp_ID'],
                    'cpty_id'            => $tr['Transaction']['cpty_id'],
                    'scheme'            => $tr['Transaction']['scheme'],
                ));
                $this->Transaction->save($data);
                $drawal_id = $this->Transaction->id;
                $this->log_entry('Withdrawal created. TRN: ' . $drawal_id . ' ' . print_r($data, true), 'treasury', $drawal_id);

                $this->Reinvestment->set('amount_leftA', 0.00);
                $this->Reinvestment->set('amount_leftB', 0.00);
                $this->Reinvestment->set('reinv_status', 'Closed');
                $this->Reinvestment->save();

                $this->Transaction->read(null, $tr_number);
                $this->Transaction->set('tr_state', 'Broken');
                $this->Transaction->set('reinv_group', $reinv_id);
                $this->Transaction->save();

                $copy_id = null;
                if ($new_amount_left != 0.00) {
                    $new_tr = $tr;
                    $total_interest_rollover = null;
                    if (!empty($interest_rate)) {
                        $value_date = explode('/', $this->request->data['Transaction']['value_date']);
                        $value_date = $value_date[2] . '-' . $value_date[1] . '-' . $value_date[0];
                        $maturity_date = $tr['Transaction']['maturity_date'];
                        $maturity_date = explode('/', $maturity_date);
                        $maturity_date = $maturity_date[2] . '-' . $maturity_date[1] . '-' . $maturity_date[0];
                        $interest_rate = str_replace(',', '', $interest_rate);
                        $params = array(
                            "amount"            => $new_amount_left,
                            "new_date"            => $maturity_date,
                            "interest_rate"        => $interest_rate,
                            "date_basis"        => $date_basis,
                            "commencement_date"    => $value_date,
                        );
                        $interest_sasResult = $this->SAS->curl("register_confirmation_new.sas", $params, false);

                        if (strpos($interest_sasResult, 'This request completed with errors') !== false) {
                            $error = true;
                        } else {
                            $interest_sasResult = mb_convert_encoding($interest_sasResult, "UTF-8"); // to remove \ufeff
                            $interest_sasResult = trim($interest_sasResult); // to remove \r
                            $interest_sasResult = preg_replace("/[^-0-9\,\.]/", '', $interest_sasResult);
                            $total_interest_rollover = $interest_sasResult;
                        }
                    }

                    if ($error) {
                        error_log("error on interest calculation");
                    }

                    unset($new_tr['Transaction']['tr_number']);
                    //unset($new_tr['Transaction']['source_group']);
                    unset($new_tr['Transaction']['accrued_interst']);
                    if ($tr['Transaction']['booking_status'] != 'Booked out') {
                        unset($new_tr['Transaction']['booking_status']);
                    }
                    unset($new_tr['Transaction']['eom_booking']);
                    $new_tr['Transaction']['tr_state'] = 'Created';
                    $new_tr['Transaction']['tr_type'] = 'Rollover';
                    $new_tr['Transaction']['commencement_date'] = $this->request->data['Transaction']['value_date'];
                    //$new_tr['Transaction']['maturity_date'] = $tr['Transaction']['maturity_date'];
                    $new_tr['Transaction']['total_interest'] = $total_interest_rollover;
                    $new_tr['Transaction']['booking_status'] = null;
                    $new_tr['Transaction']['accrued_interest'] = null;
                    $new_tr['Transaction']['accrued_tax'] = null;
                    $new_tr['Transaction']['amount'] = $new_amount_left;
                    $new_tr['Transaction']['source_group'] = $reinv_id;
                    $new_tr['Transaction']['parent_id'] = $tr_number;
                    $this->Transaction->create();
                    $this->Transaction->save($new_tr);
                    $copy_id = $this->Transaction->id;
                    $this->log_entry('Remainder after breackage created. TRN: ' . $copy_id . ' ' . print_r($data, true), 'treasury', $copy_id);
                    // source_group is not being saved correctly, so we push it
                    $tr = $this->Transaction->read(null, $copy_id);
                    $this->Transaction->set('source_group', $reinv_id);
                    $this->Transaction->save();
                    $event = new CakeEvent('Model.Treasury.Transaction.change', $this, array("transaction" => $tr));
                    $this->getEventManager()->dispatch($event);
                }


                $this->set('success', Router::url('/treasury/treasurytransactions/breakdeposit_result/' . $tr_number . '/' . $drawal_id . '/' . $copy_id, false));
                //$this->redirect('/treasury/treasurytransactions/breakdeposit_result/'.$tr_number.'/'.$drawal_id.'/'.$copy_id);
                $this->set('_serialize', array('success'));
            } else {
                $Transaction = $this->Transaction->validationErrors;
                $data = compact('Transaction');
                $this->set('errors', $data);
                $this->set('_serialize', array('errors'));
            }
        }
    }

    /**
     * Break deposit confirmation
     */
    function breakdeposit_result($tr_number = null, $drawal_id, $copy_id = null)
    {
        @$this->validate_param('int', $tr_number);
        @$this->validate_param('int', $drawal_id);
        @$this->validate_param('int', $copy_id);
        $this->Transaction->virtualFields  =  array(
            'amountccy' => "CONCAT(Transaction.amount,' ',AccountA.ccy)",
            'interest'    => "CONCAT(Transaction.total_interest,' ',AccountA.ccy)",
            'tax'    => "CONCAT(Transaction.tax_amount,' ',AccountA.ccy)",
        );
        // Broker transaction
        if ($tr_number != 0) {
            $this->Transaction->setFieldsToDisplay($tr_number);
            $confTables = array('broken_transaction' => $this->Transaction->getRawsById($tr_number));
        }

        // Withdrawal
        $this->Transaction->setFieldsToDisplay($drawal_id);

        $confTables['withdrawal'] = $this->Transaction->getRawsById($drawal_id);

        // Copy
        $this->Transaction->setFieldsToDisplay($copy_id);
        if (!empty($copy_id)) {
            $confTables['remainder'] = $this->Transaction->getRawsById($copy_id);
        }
        //remove fields amount_eur & reinv_availability_date from results (why were they here?!)
        foreach ($confTables as &$confTable) {
            foreach ($confTable as &$trn) {
                if (isset($trn['Transaction']['amount_eur'])) unset($trn['Transaction']['amount_eur']);
                if (isset($trn['Transaction']['reinv_availability_date'])) unset($trn['Transaction']['reinv_availability_date']);
            }
        }

        $this->set(compact('confTables'));
    }

    function breakconf()
    {
        $transactions = $this->Transaction->find("all", array("conditions" => array(
            "tr_type"  => "Withdrawal",
            "tr_state" => "Instruction Sent"
        )));

        $this->set(compact("transactions"));

        if ($this->request->is('post')) {
            if (!empty($this->request->data['Transaction']['tr_number'])) {
                if (empty($this->request->data['Transaction']['with_interest']) or empty($this->request->data['Transaction']['ret_interest'])) {
                    $this->Session->setFlash('Please enter at least interest amounts corresponding to the part withdrawan and the one retained.', 'flash/error');
                    $this->redirect($this->referer());
                } else {
                    $drawal_id = $this->request->data['Transaction']['tr_number'];
                    $with_interest = $this->request->data['Transaction']['with_interest'];
                    $ret_interest = $this->request->data['Transaction']['ret_interest'];
                    $with_tax = $ret_tax = null;
                    if (!empty($this->request->data['Transaction']['with_tax'])) {
                        $with_tax = $this->request->data['Transaction']['with_tax'];
                    }
                    if (!empty($this->request->data['Transaction']['ret_tax'])) {
                        $ret_tax = $this->request->data['Transaction']['ret_tax'];
                    }
                    $withdrawal = $this->Transaction->getTransactionById($drawal_id);
                    $ret = $this->Transaction->findSisterTRN($drawal_id);
                    $copy_id = $ret['Transaction']['tr_number'];

                    $with_interest     = $this->Transaction->formatAmounts($with_interest);
                    $with_tax         = $this->Transaction->formatAmounts($with_tax);
                    $ret_interest     = $this->Transaction->formatAmounts($ret_interest);
                    $ret_tax         = $this->Transaction->formatAmounts($ret_tax);
                    $interest = array(
                        'withdrawal' => array(
                            'total_interest' => $with_interest,
                            'tax_amount' => $with_tax
                        ),
                        'retained' => array(
                            'total_interest' => $ret_interest,
                            'tax_amount' => $ret_tax
                        )
                    );

                    $this->Transaction->save(array('Transaction' => array(
                        'tr_number'         => $withdrawal['Transaction']['tr_number'],
                        'total_interest'    => $with_interest,
                        'tax_amount'        => $with_tax,
                        'tr_state'          => 'Confirmation Received'
                    )));
                    $this->Transaction->save(array('Transaction' => array(
                        'tr_number'            => $ret['Transaction']['tr_number'],
                        'total_interest'    => $ret_interest,
                        'tax_amount'        => $ret_tax,
                        'tr_state'            => 'Confirmation Received'
                    )));

                    $this->log_entry('Break deposit ' . $this->request->data["Transaction"]["tr_number"] . ' is Confirmed: (' . json_encode($interest, true) . ')', 'treasury', $this->request->data["Transaction"]["tr_number"]);
                    $event = new CakeEvent('Model.Treasury.Transaction.change', $this, array("transaction" => $ret));
                    $this->getEventManager()->dispatch($event);
                    $this->Session->setFlash('The transaction ' . $this->request->data["Transaction"]["tr_number"] . ' changed to "Confirmation Received"', 'flash/success');
                }
            } else {
                $this->Session->setFlash('Please select a transaction', 'flash/error');
            }
        }
    }

    function validatebreakconf()
    {
        $transactions  =  $this->Transaction->find('all', array(
            'conditions'  =>  array(
                'tr_type'  =>  array('Withdrawal'),
                'tr_state'  =>  array('Confirmation Received')
            ),
        ));

        $this->set(compact('transactions'));

        if ($this->request->is('post')) {
            if (empty($this->request->data['Withdrawal']['tr_number']) || empty($this->request->data['Withdrawal']['tr_number'])) {
                $this->Session->setFlash("Something went wrong while updating Transactions TRN: <strong>" . $this->request->data['Transaction']['tr_number'] . "</strong>: no Withrdawal or Retained found.", "flash/error");
                $this->redirect($this->referer());
            }
            $log_msg = null;
            if (isset($this->request->data['reject'])) {
                $tr_state = 'Instruction Sent';
                $log_msg = " Confirmation is rejected";
            } else {
                $tr_state = 'Confirmed';
                $log_msg = " Confirmation is validated";
            }
            //$this->Transaction->create();
            $this->Transaction->save(array('Transaction' => array(
                'tr_number' => $this->request->data['Withdrawal']['tr_number'],
                'tr_state' => $tr_state,
            )));
            //$this->Transaction->create();
            $trn_saved = $this->Transaction->save(array('Transaction' => array(
                'tr_number' => $this->request->data['Retained']['tr_number'],
                'tr_state' => $tr_state,
            )));

            $this->log_entry('Break deposit ' . $this->request->data["Withdrawal"]["tr_number"] . $log_msg, 'treasury', $this->request->data["Withdrawal"]["tr_number"]);

            $event = new CakeEvent('Model.Treasury.Transaction.change', $this, array("transaction" => $trn_saved));
            $this->getEventManager()->dispatch($event);
            $this->Session->setFlash("Transactions TRN: <strong>" . $this->request->data['Withdrawal']['tr_number'] . "</strong> & <strong>" . $this->request->data['Retained']['tr_number'] . "</strong> are now <strong>" . $tr_state . "</strong>", "flash/success");
            $this->redirect($this->referer());
        }
    }

    function newrepayment($datas = null)
    {

        if (Cache::read("automatic_fixing", 'treasury')) {
            $this->Session->setFlash("Process of the automatic interest fixing is currently running. Your modification on the transactions could create a conflict in the database. Please try again in 5 minutes.", 'flash/warning');
        }
        if (!empty($datas)) @$this->request->data = $datas;

        $output = array('success' => false, 'msg' => 'Something went wrong...');
        $fromajax = false;
        if (!empty($this->request->data['reinv_op']['controller_action'])) $fromajax = true;

        $reinvGroupOpts = $this->Reinvestment->getreinvs('Open');
        $this->set('reinvGroupOpts', $reinvGroupOpts);
        $this->set('openReinvNo', count($reinvGroupOpts));

        if (!empty($this->request->data['newrepayform'])) {
            $error = false;
            if (!empty($this->request->data['newrepayform']['amount'])) {
                $amount = implode('', explode(',', $this->request->data['newrepayform']['amount']));
            } else {
                $error = true;
                $this->set('amountError', true);
            }

            if (empty($this->request->data['newrepayform']['RepaymentAcc'])) {
                $error = true;
                $this->set('accountError', true);
            }

            if (empty($this->request->data['newrepayform']['Source'])) {
                $error = true;
                $this->set('accountError', true);
            } else {
                $src = $this->request->data['newrepayform']['Source'];
            }
            if ($error == false) {

                $reinvestment = $this->Reinvestment->getRawsById($this->request->data['newrepayform']['reinv_group']);
                $reinvestment = $reinvestment[0];

                $amount_left = floatVal(str_replace(',', '', $reinvestment['Reinvestment']['amount_left' . $src]));
                $output['amounts'] = array('A' => $reinvestment['Reinvestment']['amount_leftA'], 'B' => $reinvestment['Reinvestment']['amount_leftB']);

                if (!empty($this->request->data['Transaction'])) {
                    if (empty($this->request->data['Transaction']['reinv_group']) || $this->request->data['Transaction']['reinv_group'] == 1) {
                        if ($amountsAvailable = $this->Transaction->computeReinvGroup(array($this->request->data['Transaction']['tr_number']))) {
                            $amount_left = floatVal(str_replace(',', '', $amountsAvailable['amountIn' . strtoupper($src)]));
                            $output['amounts'] = array('A' => $amountsAvailable['amountInA'], 'B' => $amountsAvailable['amountInB']);
                        }
                    }
                }

                $new_amount_left = $amount_left - $amount;
                if ($new_amount_left < 0) {
                    $msg = "Cannot repay more than available in the pool!";
                    $msg .= ' (' . UniformLib::uniform($amount, 'amount') . ' / ' . UniformLib::uniform($amount_left, 'amount_left') . ')';
                    if (empty($fromajax)) {
                        $this->Session->setFlash($msg, "flash/default");
                        $this->redirect($this->referer());
                    } else {
                        $output['success'] = false;
                        $output['msg'] = $msg;
                        return $output;
                    }
                }

                $data = array('Transaction' => array(
                    'tr_type'            => 'Repayment',
                    'tr_state'            => 'Created',
                    'source_group'        => $reinvestment['Reinvestment']['reinv_group'],
                    'amount'            => $amount,
                    'commencement_date' => $reinvestment['Reinvestment']['availability_date'],
                    'maturity_date'        => $reinvestment['Reinvestment']['availability_date'],
                    'mandate_ID'        => $reinvestment['Reinvestment']['mandate_ID'],
                    'cmp_ID'            => $reinvestment['Reinvestment']['cmp_ID'],
                    'cpty_id'            => $reinvestment['Reinvestment']['cpty_ID'],
                    'accountA_IBAN'        => $this->request->data['newrepayform']['RepaymentAcc'],
                    'accountB_IBAN'        => $this->request->data['newrepayform']['RepaymentAcc'],
                    'booking_status'    => 'Not booked',
                    'source_fund'        => $this->request->data['newrepayform']['Source'],
                    'instr_num'            => 0,
                ));
                if (isset($this->request->data['Transaction']['tr_number'])) {
                    $tr_ori = $this->request->data['Transaction']['tr_number'];

                    $data['original_id'] = $tr_ori;
                    $data['parent_id'] = $tr_ori;

                    $this->Transaction->set('original_id', $tr_ori);
                    $this->Transaction->set('parent_id', $tr_ori);
                }
                if (isset($this->request->data['newrepayform'])) {
                    if (isset($this->request->data['newrepayform']['reinv_group']) && ($this->request->data['newrepayform']['reinv_group'] != null)) {
                        $reinv_group = $this->Reinvestment->getRawsById($this->request->data['newrepayform']['reinv_group']);
                        $previous_trn = $this->Transaction->find('all', array(
                            'conditions'    => array('Transaction.reinv_group' => $reinv_group[0]['Reinvestment']['reinv_group']),
                            'fields'        => array('Transaction.original_id', 'Transaction.tr_number'),
                            'recursive'        => 1,
                        ));
                        if (count($previous_trn) == 1) {
                            $data['Transaction']['original_id'] = $previous_trn[0]['Transaction']['original_id'];
                            $data['Transaction']['parent_id'] = $previous_trn[0]['Transaction']['tr_number'];
                        }
                    }
                }

                if (empty($this->request->data['checkSave']['checkonly'])) {
                    $trn = $this->Transaction->save($data);
                    $tr_number = $this->Transaction->id;
                    $this->log_entry('Repayment created TRN ' . $tr_number . ' ' . print_r($this->request->data['newrepayform'], true), 'treasury', $tr_number);

                    $this->Reinvestment->read(null, $reinvestment['Reinvestment']['reinv_group']);
                    $this->Reinvestment->set('amount_left' . $src, $new_amount_left);
                    $this->Reinvestment->save();
                    $event = new CakeEvent('Model.Treasury.Transaction.change', $this, array("transaction" => $trn));
                    $this->getEventManager()->dispatch($event);
                    /* TODO:add result view */
                    $this->Transaction->setFieldsToDisplay($tr_number);

                    $repayment = $this->Transaction->getRawsById($tr_number);
                    $this->set(compact('repayment'));

                    if (!empty($fromajax)) {
                        $msg = 'Repayment ' . $repayment[0]['Transaction']['tr_number'] . ' created ';
                        $msg .= ' (' . UniformLib::uniform($amount, 'amount') . ' from ' . UniformLib::uniform($this->request->data['newrepayform']['Source'], 'source_account') . ' to ' . UniformLib::uniform($this->request->data['newrepayform']['RepaymentAcc'], 'repayment_account') . ')';

                        $output['success'] = true;
                        $output['msg'] = $msg;
                        $output['repayment'] = $repayment;

                        return $output;
                    }
                } else {
                    $msg = 'The new repayment can be created ';
                    $msg .= ' (' . UniformLib::uniform($amount, 'amount') . ' from ' . UniformLib::uniform($this->request->data['newrepayform']['Source'], 'source_account') . ' to ' . UniformLib::uniform($this->request->data['newrepayform']['RepaymentAcc'], 'repayment_account') . ')';

                    $output['success'] = true;
                    $output['msg'] = $msg;
                    return $output;
                }
            } else {
                $msg = 'The new repayment cannot be created.  Some fields are missing.';
                $output['success'] = false;
                $output['msg'] = $msg;
                if (isset($repayment)) {
                    $this->set('msg', '');
                    $this->set('tab2state', 'active');
                    $this->set('tab1state', '');
                } else {
                    $this->set('msg', $msg);
                    $this->set('tab2state', '');
                    $this->set('tab1state', 'active');
                }
                return $output;
            }
        }
        if (isset($repayment)) {
            $this->set('msg', '');
            $this->set('tab2state', 'active');
            $this->set('tab1state', '');
        } else {
            $this->set('msg', '');
            $this->set('tab2state', '');
            $this->set('tab1state', 'active');
        }

        $this->set('title_for_layout', 'New Repayment');
        return $output;
    }

    function correctrepayment($tr_number)
    {

        @$this->validate_param('int', $tr_number);
        //exit if transaction state is not Created
        if (isset($tr_number) && (!$this->Transaction->isEditable($tr_number) or $this->Transaction->getAttribByTrn('tr_type', $tr_number) != 'Repayment')) {
            $this->Session->setFlash('This transaction cannot be deleted. You cannot delete transactions by typing url directly, please use the edit/delete menu.', 'flash/error');
            $this->redirect('/treasury');
        }

        //exit if $tr_number is null
        if (!isset($tr_number)) {
            $this->Session->setFlash('The. You cannot delete transactions by typing the url directly, please use the edit/delete menu.', 'flash/error');
            $this->redirect('/treasury');
        }

        $tr = $this->Transaction->find('first', array(
            'conditions'    => array('tr_number' => $tr_number),
            'fields'        => array('amount', 'accountA_IBAN', 'source_group', 'source_fund', 'Compartment.accountA_IBAN', 'Compartment.accountB_IBAN', 'outFromReinv.amount_leftA', 'outFromReinv.amount_leftB', 'AccountA.ccy'),
            'recursive'        => 1,
        ));

        $defaultAmount = $tr['Transaction']['amount'];
        $defaultAccount = $tr['Transaction']['accountA_IBAN'];
        $accounts = $tr['Compartment'];
        $accountA_IBAN = array(
            $accounts['accountA_IBAN'] => 'Account A : ' . $accounts['accountA_IBAN'],
            $accounts['accountB_IBAN'] => 'Account B : ' . $accounts['accountB_IBAN']
        );
        $source_fund = $tr['Transaction']['source_fund'];
        $source_group = $tr['Transaction']['source_group'];
        $amount_left = $tr['outFromReinv']['amount_left' . $source_fund];

        $this->set(compact('defaultAmount'));
        $this->set(compact('defaultAccount'));
        $this->set(compact('accountA_IBAN'));
        // Send current repayment summary to the view
        $this->Transaction->setFieldsToDisplay($tr_number);
        $this->set('repayment', $this->Transaction->getRawsById($tr_number));

        if ($this->request->is('post')) {
            if (!empty($this->request->data['Transaction']['amount'])) {
                $new_repay_amount = $this->Transaction->formatAmounts($this->request->data['Transaction']['amount']);
                $new_amount_left = floatval($amount_left) + floatval($defaultAmount) - floatval($new_repay_amount);
                if ($new_amount_left < 0) {
                    $this->Session->setFlash('You can not repay more than available in the pool. Maximum repayment is : ' . ($amount_left + $defaultAmount) . ' ' . $tr['AccountA']['ccy'], 'flash/error');
                } else {
                    $this->Transaction->read(null, $tr_number);
                    $this->Transaction->set('tr_state', 'Created');
                    $this->Transaction->set('amount', $new_repay_amount);
                    $this->Transaction->set('accountA_IBAN', $this->request->data['Transaction']['accountA_IBAN']);
                    $this->Transaction->set('accountB_IBAN', $this->request->data['Transaction']['accountA_IBAN']);
                    $trns_saved = $this->Transaction->save();
                    $this->Reinvestment->read(null, $source_group);
                    $this->Reinvestment->set('amount_left' . $source_fund, $new_amount_left);
                    $this->Reinvestment->save();

                    $event = new CakeEvent('Model.Treasury.Transaction.change', $this, array("transaction" => $trns_saved));
                    $this->getEventManager()->dispatch($event);
                    $this->log_entry('Repayment corrected TRN ' . $tr_number . ' ' . print_r($this->request->data['Transaction'], true), 'treasury', $tr_number);
                    $this->redirect('/treasury/treasurytransactions/trconfirmation/' . $tr_number);
                }
            } else $this->Session->setFlash('Repayment amount cannot be empty or zero', 'flash/error');
        }
    }

    function newrollover($datas = null)
    {

        if (Cache::read("automatic_fixing", 'treasury')) {
            $this->Session->setFlash("Process of the automatic interest fixing is currently running. Your modification on the transactions could create a conflict in the database. Please try again in 5 minutes.", 'flash/warning');
        }
        if (!empty($datas)) @$this->request->data = $datas;
        $msgLimit = null;
        if (isset($datas['msg'])) $msgLimit = $datas['msg'];

        $output = array('success' => false, 'msg' => 'Something went wrong...');
        $fromajax = false;
        if (!empty($this->request->data['controller_action'])) $fromajax = true;

        // Get Reinv Group
        $reinvGroupOpts = $this->Reinvestment->getreinvs('Open');
        $this->set(compact('reinvGroupOpts'));
        $this->set('openReinvNo', count($reinvGroupOpts));

        //(Hardcoded because Tomasz want them ordered in a certain way)
        $depoTerm = array(
            'ON'    =>    'Overnight',
            '1W'    =>    '1W',
            '1M'    =>    '1M',
            '2M'    =>    '2M',
            '3M'    =>    '3M',
            '6M'    =>    '6M',
            '9M'    =>    '9M',
            '1Y'    =>    '1Y',
            'NS'    =>    'Non Standard',
        );

        $this->set(compact('depoTerm'));
        $output['depoTerm'] = $depoTerm;

        //reload all datas of the transaction (usefull in case of ajax & partial request datas)
        if (!empty($this->request->data['Transaction']['tr_number'])) {
            $trn = $this->Transaction->getTransactionById($this->request->data['Transaction']['tr_number']);
            if (!empty($trn['Transaction'])) foreach ($trn['Transaction'] as $key => $val) {
                if (!isset($this->request->data['Transaction'][$key])) $this->request->data['Transaction'][$key] = $val;
            }
        }

        if (!empty($this->request->data['Transaction'])) {
            //retrieve availability date to fix bug with new rollover
            $reinvestment = $this->Reinvestment->getRawsById($this->request->data['Transaction']['reinv_group']);
            $reinvestment = $reinvestment[0];

            if (!isset($this->request->data['Transaction']['commencement_date'])) {
                $this->request->data['Transaction']['commencement_date'] = $reinvestment['Reinvestment']['availability_date'];
            }
            $tr = $this->Transaction->set($this->request->data);

            if ($this->Transaction->validateNewRollover()) {
                $src = $this->request->data['Transaction']['source_fund'];

                $amount_left = floatVal(str_replace(',', '', $reinvestment['Reinvestment']['amount_left' . $src]));
                $output['amounts'] = array('A' => $reinvestment['Reinvestment']['amount_leftA'], 'B' => $reinvestment['Reinvestment']['amount_leftB']);
                if (!empty($this->request->data['Transaction'])) {
                    if (empty($this->request->data['Transaction']['reinv_group']) || ($this->request->data['Transaction']['reinv_group'] == 1 && !empty($this->request->data['Transaction']['tr_number']))) {
                        $amountsAvailable = $this->Transaction->computeReinvGroup(array($this->request->data['Transaction']['tr_number']));
                        $amount_left = floatVal(str_replace(',', '', $amountsAvailable['amountIn' . strtoupper($src)]));
                        $output['amounts'] = array('A' => $amountsAvailable['amountInA'], 'B' => $amountsAvailable['amountInB']);
                    }
                }

                $amount = floatVal(str_replace(',', '', $this->request->data['Transaction']['amount']));
                $new_amount_left = round($amount_left, 2) - round($amount, 2);
                if ($new_amount_left < 0) {
                    $msg = "Cannot rollover more than available in the pool!";
                    $msg .= ' (' . UniformLib::uniform($amount, 'amount') . ' / ' . UniformLib::uniform($amount_left, 'amount_left') . ')';
                    if (empty($fromajax)) {
                        $this->Session->setFlash($msg, "flash/default");
                        $this->redirect($this->referer());
                    } else {
                        $output['success'] = false;
                        $output['msg'] = $msg;
                    }
                    return $output;
                }

                $maturity_date = (!empty($this->request->data['Transaction']['maturity_date'])) ? $this->request->data['Transaction']['maturity_date'] : '';
                //$rate_type in beforesave in Transaction model
                $rate_type = null;
                if ($this->data['Transaction']['depo_type'] == 'Callable') {
                    if (isset($this->data['Transaction']['interest_rate']) && $this->data['Transaction']['interest_rate'] != null) {
                        $rate_type = 'Fixed'; //http://vmu-sas-01:8080/browse/TREASURY-433
                    } else {
                        $rate_type = 'Floating';
                    }
                } else {
                    $rate_type = 'Fixed';
                }
                $amount = floatVal(str_replace(',', '', $this->request->data['Transaction']['amount']));

                $interest_rate = null;
                if ($tr['Transaction']['depo_type'] == "Callable") {
                    $interest_rate = $this->Interest->getInterestAt($tr['Transaction']['tr_number'], $this->request->data['Transaction']['commencement_date']);
                } else {
                    //here we could fill the interest_rate for Term deposit
                }
                $data = array(
                    'Transaction' => array(
                        'tr_type'            => 'Rollover',
                        'tr_state'            => 'Created',
                        'source_group'        => $reinvestment['Reinvestment']['reinv_group'],
                        'reinv_group'        => 1,
                        'amount'            => $amount,
                        'commencement_date'    => $reinvestment['Reinvestment']['availability_date'],
                        'maturity_date'        => $maturity_date,
                        'depo_term'            => $this->request->data['Transaction']['depo_term'],
                        'depo_type'            => $this->request->data['Transaction']['depo_type'],
                        'depo_renew'        => $this->request->data['Transaction']['depo_renew'],
                        'rate_type'            => $rate_type,
                        'mandate_ID'        => $reinvestment['Reinvestment']['mandate_ID'],
                        'cmp_ID'            => $reinvestment['Reinvestment']['cmp_ID'],
                        'cpty_id'            => $reinvestment['Reinvestment']['cpty_ID'],
                        'accountA_IBAN'        => $this->request->data['Transaction']['accountA_IBAN'],
                        'accountB_IBAN'        => $this->request->data['Transaction']['accountB_IBAN'],
                        'booking_status'    => 'Not booked',
                        'instr_num'            => 0,
                        'tax_ID'            =>  $this->Tax->getTaxID($reinvestment['Reinvestment']['mandate_ID'], $reinvestment['Reinvestment']['cpty_ID']),
                        'source_fund'        => $this->request->data['Transaction']['source_fund'],
                        'interest_rate'        => $interest_rate,
                    )
                );

                if (isset($this->request->data['Transaction']['interest_rate'])) $data['Transaction']['interest_rate'] = $this->request->data['Transaction']['interest_rate'];
                if (isset($this->request->data['Transaction']['total_interest'])) $data['Transaction']['total_interest'] = $this->request->data['Transaction']['total_interest'];
                if (isset($this->request->data['Transaction']['tax_amount'])) $data['Transaction']['tax_amount'] = $this->request->data['Transaction']['tax_amount'];
                if (isset($this->request->data['Transaction']['reference_rate'])) $data['Transaction']['reference_rate'] = $this->request->data['Transaction']['reference_rate'];
                if (isset($this->request->data['Transaction']['date_basis'])) $data['Transaction']['date_basis'] = $this->request->data['Transaction']['date_basis'];
                if (isset($this->request->data['Transaction']['TransactionScheme'])) $data['Transaction']['scheme'] = $this->request->data['Transaction']['TransactionScheme'];

                // original ID & parent of this original ID
                if (!empty($this->request->data['Transaction']['tr_number'])) {
                    $tr_number = $this->request->data['Transaction']['tr_number'];
                    $tr = $this->Transaction->findByTrNumber($tr_number);
                    $data['Transaction']['original_id'] = $tr['Transaction']['original_id'];
                    $data['Transaction']['parent_id'] = $tr_number;
                    $data['Transaction']['scheme'] = $tr['Transaction']['scheme'];
                    //if(!empty($trn['Transaction']['parent_id'])) $data['Transaction']['parent_id'] = $trn['Transaction']['parent_id'];
                }

                if (isset($this->request->data['Transaction'])) {
                    if (isset($this->request->data['Transaction']['reinv_group']) && ($this->request->data['Transaction']['reinv_group'] != null)) {
                        $reinv_group = $this->Reinvestment->getRawsById($this->request->data['Transaction']['reinv_group']);
                        $previous_trn = $this->Transaction->find('all', array(
                            'conditions'    => array('Transaction.reinv_group' => $reinv_group[0]['Reinvestment']['reinv_group']),
                            'fields'        => array('Transaction.original_id', 'Transaction.tr_number', 'Transaction.scheme'),
                            'recursive'        => 1,
                        ));
                        if (count($previous_trn) == 1) {
                            $data['Transaction']['original_id'] = $previous_trn[0]['Transaction']['original_id'];
                            $data['Transaction']['parent_id'] = $previous_trn[0]['Transaction']['tr_number'];
                            $data['Transaction']['scheme'] = $previous_trn[0]['Transaction']['scheme'];
                        }
                    }
                }

                if (empty($this->request->data['checkSave']['checkonly'])) {
                    if (empty($data['Transaction']['instr_num'])) $this->Transaction->create();
                    $trn = $this->Transaction->save($data);
                    if ($data['Transaction']['depo_type'] == "Callable") {
                        $this->Interest->updateInterest($this->Transaction->id, $data['Transaction']['interest_rate'], $data['Transaction']['commencement_date']);
                    }
                    $tr_number = $trn['Transaction']['tr_number']; //$this->Transaction->id;

                    $event = new CakeEvent('Model.Treasury.Transaction.change', $this, array("transaction" => $trn));
                    $this->getEventManager()->dispatch($event);
                    $this->log_entry('Rollover created TRN ' . $tr_number . ' ' . $msgLimit . ' ' . print_r($this->request->data['Transaction'], true), 'treasury', $tr_number);

                    $this->Reinvestment->read(null, $reinvestment['Reinvestment']['reinv_group']);
                    $reinv = $this->Reinvestment->set('amount_left' . $src, $new_amount_left);
                    $this->Reinvestment->save();

                    /* TODO:add result view */
                    $this->Transaction->setFieldsToDisplay($tr_number);
                    $rollover = $this->Transaction->getRawsById($tr_number);
                    $this->set(compact('rollover'));

                    $msg = 'Rollover ' . $tr_number . ' created ';
                    $msg .= ' (' . UniformLib::uniform($amount, 'amount') . ' from ' . UniformLib::uniform($this->request->data['Transaction']['source_fund'], 'source_fund') . ' to ' . UniformLib::uniform($this->request->data['Transaction']['accountA_IBAN'], 'accountA_IBAN') . ' / ' . UniformLib::uniform($this->request->data['Transaction']['accountA_IBAN'], 'accountA_IBAN') . ')';

                    $output['success'] = true;
                    $output['msg'] = $msg;
                    $output['rollover'] = $rollover;
                } else {
                    $msg = 'The new rollover can be created ';
                    $msg .= ' (' . UniformLib::uniform($amount, 'amount') . ' from ' . UniformLib::uniform($this->request->data['Transaction']['source_fund'], 'source_fund') . ' to ' . UniformLib::uniform($this->request->data['Transaction']['accountA_IBAN'], 'accountA_IBAN') . ' / ' . UniformLib::uniform($this->request->data['Transaction']['accountA_IBAN'], 'accountA_IBAN') . ')';
                    $output['success'] = true;
                    $output['msg'] = $msg;
                    //display limit breach as warning and not as errors
                    $validateLimits = $this->Transaction->validateLimitBreach();
                    if (!empty($validateLimits)) {
                        foreach ($validateLimits as $validateLimit) {
                            if (isset($validateLimit['exposure'])) {
                                $output['limitbreach'] = true;
                                $output['breachmsg'] = $validateLimit['exposure']['message'];
                            }
                            if (isset($validateLimits['maxmaturity'])) {
                                $output['limitbreach'] = true;
                                $output['breachmsg'] = $validateLimit['maxmaturity']['message'];
                            }
                        }
                    }

                    return $output;
                }
            } else {
                $msg = '';
                if ($errors = $this->Transaction->validationErrors) {
                    foreach ($errors as $error) {
                        foreach ($error as $err) {
                            $msg .= '<br>- ' . $err;
                        }
                    }
                }
                if ($msg) $msg = ': ' . $msg;
                $msg = 'The new rollover cannot be created' . $msg;
                $output['success'] = false;
                $output['msg'] = $msg;

                if (isset($repayment)) {
                    $this->set('msg', '');
                    $this->set('tab2state', 'active');
                    $this->set('tab1state', '');
                } else {
                    $this->set('msg', $msg);
                    $this->set('tab2state', '');
                    $this->set('tab1state', 'active');
                }

                return $output;
            }
        }

        if (isset($rollover)) {
            $this->set('msg', '');
            $this->set('tab2state', 'active');
            $this->set('tab1state', '');
        } else {
            $this->set('msg', '');
            $this->set('tab2state', '');
            $this->set('tab1state', 'active');
        }

        $this->set('title_for_layout', 'New Rollover');
        return $output;
    }

    function validateconf()
    {
        $instrList = $this->Transaction->find(
            'all',
            array(
                'conditions'    => array(
                    'tr_type'     => array('Rollover', 'Deposit'),
                    'tr_state'    => array('Confirmation Received'),
                    'NOT'        => array(
                        'tr_number' => array_merge(
                            $this->Transaction->remaindersAfterBreakage(),
                            $this->Transaction->remaindersAfterCall()
                        )
                    ),
                ),
                'fields'         => array('instr_num', 'tr_number', 'Mandate.mandate_name', 'Compartment.cmp_name', 'amount', 'AccountA.ccy', 'tax_amount'),
                'recursive'     => 1,
            )
        );

        $instr = array();
        foreach ($instrList as $key => $value) {
            $instr[$value['Transaction']['tr_number']] = array(
                "instr_num"        => $value['Transaction']['instr_num'],
                "mandate_name"    => $value['Mandate']['mandate_name'],
                "cmp_name"        => $value['Compartment']['cmp_name'],
                "amount"        => $value['Transaction']['amount'],
                "ccy"            => $value['AccountA']['ccy'],
                "tax_amount"    => $value['Transaction']['tax_amount'],
            );
        }

        $this->set(compact('instr'));
    }

    function registerconf()
    {

        $tr = $this->Transaction->find(
            'all',
            array(
                'conditions'     => array(
                    'tr_type'     => array('Rollover', 'Deposit'),
                    'tr_state'    => array('Instruction Sent'),
                    'NOT'        => array(
                        'Transaction.tr_number' => array_merge(
                            $this->Transaction->remaindersAfterBreakage(),
                            $this->Transaction->remaindersAfterCall()
                        )
                    ),
                ),
                'recursive' => 1,
            )
        );

        $trList = array();
        foreach ($tr as $key => $value) {
            $trList[$value['Transaction']['tr_number']] = array(
                "instr_num"             => $value['Transaction']['instr_num'],
                "tr_type"             => $value['Transaction']['tr_type'],
                "mandate_name"        => $value['Mandate']['mandate_name'],
                "cmp_name"            => $value['Compartment']['cmp_name'],
                "maturity_date"     => $value['Transaction']['maturity_date'],
                "commencement_date" => $value['Transaction']['commencement_date'],
                "depo_term"         => $value['Transaction']['depo_term'],
                "depo_type"         => $value['Transaction']['depo_type'],
                "currency"             => $value['AccountA']['ccy'],
                "amount"            => $value['Transaction']['amount'],
            );
        }

        $this->set(compact('trList'));

        if ($this->request->is('post') or $this->request->is('ajax')) {
            $this->Transaction->set($this->request->data);

            if ($this->Transaction->validateRegisterConfirmation()) {
                $this->Transaction->save();
                $event = new CakeEvent('Model.Treasury.Transaction.changeAll', $this, array("transaction" => $this->Transaction->data));
                $this->getEventManager()->dispatch($event);

                $tr_number = $this->Transaction->id;

                $this->log_entry('Confirmation Received TRN ' . $this->request->data["Transaction"]["tr_number"] . ' ' . print_r($this->request->data['Transaction'], true), 'treasury', $this->request->data["Transaction"]["tr_number"]);
                $this->set('success', Router::url('/treasury/treasurytransactions/registerconf_result/' . $tr_number, false));
                $this->set('_serialize', array('success'));
            } else {
                $Transaction = $this->Transaction->validationErrors;
                $data = compact('Transaction');
                $this->set('errors', $data);
                $this->set('_serialize', array('errors'));
            }
        }
    }
    function registerInstrConf($instr_num = null)
    {
        @$this->validate_param('int', $instr_num);

        // if post, validate & save
        if (!empty($this->request->data['Transaction'])) {
            try {
                $instr_num = $this->request->data['Transaction']['instr_num'];
                $data = $this->request->data['Transaction'];
                $trn = $this->Transaction->findByTrNumber($this->data['Transaction']['tr_number']);
                if ($trn['Transaction']['depo_type'] == 'Callable') {
                    //$data['fixed_rate_type'] = $trn['Transaction']['rate_type'];//to inherit the rate type. other fields could be added
                    $data['rate_type'] = $trn['Transaction']['rate_type'];
                }
                $this->Transaction->set($data);
                $form_process = array();
                if ($trn['Transaction']['depo_type'] == 'Callable') {
                    $validation = $this->Transaction->validateRegisterConfirmation();
                } else {
                    $validation = $this->Transaction->validateRegisterConfirmationStrict();
                }
                error_log("registerInstrConf validationerrors : " . json_encode($this->Transaction->validationErrors, true));
                if (empty($this->Transaction->validationErrors)) {
                    $this->Transaction->save();
                    if ($trn['Transaction']['depo_type'] == 'Callable') {
                        $this->Interest->updateOriginalInterestRate($trn['Transaction']['tr_number'], $data['interest_rate']);
                        //$this->Interest->updateInterest($trn['Transaction']['tr_number'], $data['interest_rate'], $trn['Transaction']['commencement_date']);// + original line // no change in interest_rate_history
                    }

                    $tr_number = $this->Transaction->id;

                    $this->log_entry('Confirmation Received TRN ' . $data["tr_number"] . ' ' . print_r($data, true), 'treasury', $data["tr_number"]);
                    $this->set('success', Router::url('/treasury/treasurytransactions/registerconf_result/' . $tr_number, false));
                    $this->set('_serialize', array('success'));

                    $form_process['trnum'] = $data["tr_number"];
                    $form_process['result'] = 'success';
                    $form_process['result_text'] = 'Confirmation for transaction #<span class="trnum">' . $tr_number . '</span> has been registered. <a class="trreport" href="' . Router::url('/treasury/treasurytransactions/registerconf_result/' . $tr_number) . '?aftr=1" target="_blank">Read the report</a>';
                } else {
                    $errors = $this->Transaction->validationErrors;
                    $this->set('errors', $errors);
                    $this->set('_serialize', array('errors'));
                    $errorstxt = $errortxtlist = '';
                    foreach ($errors as $field => $error) {
                        foreach ($error as $desc) {
                            $errortxtlist .= '<li data-field="' . $field . '">' . $desc . '</li>';
                        }
                    }
                    if (!empty($errortxtlist)) $errorstxt .= '<ul class="errorlist">' . $errortxtlist . '</ul>';

                    $form_process['trnum'] = $data["tr_number"];
                    $form_process['result'] = 'error';
                    $form_process['result_text'] = '<strong>Confirmation for transaction #' . $data["tr_number"] . ' has NOT been registered, due to:</strong>' . $errorstxt;
                }
                $this->set('form_process', $form_process);
            } catch (Exception $e) {
                error_log("Exception : " . $e->getMessage());
            }
        }

        //attachment update
        try {
            if (!empty($this->request->data['Instruction']['attachment'])) {

                $file = $this->request->data['Instruction']['attachment'];
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $file['name'] = 'deposit_instruction_' . $this->request->data['Instruction']['instr_num'] . '_confirmation_' . time() . '.' . $ext;
                $fileMovingPath = WWW . DS . 'data' . DS . 'treasury' . DS . 'pdf' . DS . $file['name'];
                if ($this->File->checkFileInForm($file, $fileMovingPath, array())) {
                    $instr = $this->Instruction->save(array(
                        'instr_num'    => $this->request->data['Instruction']['instr_num'],
                        'confirmation_file' => $file['name'],
                        'confirmation_date' => date('Y-m-d H:i:s'),
                        'confirmation_by' => $this->UserAuth->getUserName()
                    ));

                    $this->Session->setFlash("The confirmation file has been successfully attached to the Deposit Instruction #" . $this->request->data['Instruction']['instr_num'], 'flash/success');
                }
            }
        } catch (Exception $e) {
            error_log("Exception : " . $e->getMessage());
        }

        try {
            //preparing datas: instruction & transactions
            $this->set('instr', $this->Instruction->getInstructionById($instr_num));
            $tr = $this->Transaction->find(
                'all',
                array(
                    'conditions'     => array(
                        'tr_type'     => array('Rollover', 'Deposit'),
                        'LOWER(tr_state)'    => array('instruction sent'),
                        'Transaction.instr_num' => $instr_num,
                        'NOT'        => array(
                            'tr_number' => array_merge(
                                $this->Transaction->remaindersAfterBreakage(),
                                $this->Transaction->remaindersAfterCall()
                            )
                        ),
                    ),
                    'recursive' => 1,
                )
            );
        } catch (Exception $e) {
            error_log("Exception : " . $e->getMessage());
        }

        $trList = array();
        foreach ($tr as $key => $value) {
            $trList[$value['Transaction']['tr_number']] = array(
                "instr_num"             => $value['Transaction']['instr_num'],
                "tr_type"             => $value['Transaction']['tr_type'],
                "mandate_ID"        => $value['Transaction']['mandate_ID'],
                "mandate_name"        => $value['Mandate']['mandate_name'],
                "cmp_name"            => $value['Compartment']['cmp_name'],
                "cpty_id"            => $value['Transaction']['cpty_id'],
                "maturity_date"     => $value['Transaction']['maturity_date'],
                "commencement_date" => $value['Transaction']['commencement_date'],
                "depo_term"         => $value['Transaction']['depo_term'],
                "depo_type"         => $value['Transaction']['depo_type'],
                "currency"             => $value['AccountA']['ccy'],
                "amount"            => $value['Transaction']['amount'],
                "scheme"            => $value['Transaction']['scheme'],
                "external_ref"        => $value['Transaction']['external_ref'],
                "interest_rate"        => $value['Transaction']['interest_rate'],
                "date_basis"        => $value['Transaction']['date_basis'],
                "total_interest"    => $value['Transaction']['total_interest'],
                "tax_amount" => $value['Transaction']['tax_amount'],
                "reference_rate"    => isset($value['Transaction']['reference_rate']) ? $value['Transaction']['reference_rate'] : '',
                "AccountA"    => $value['AccountA']['IBAN'],
                "AccountB"    => $value['AccountB']['IBAN'],
                "automatic_fixing"    => $value['Counterparty']['automatic_fixing'],
                "benchmark"            => $value['Transaction']['benchmark'],
            );
        }
        $this->set('trns', $trList);
    }
    function validateInstrConf($instr_num = null)
    {

        @$this->validate_param('int', $instr_num);
        if (empty($instr_num)) return;

        //preparing datas: instruction & transactions
        $this->set('instr', $this->Instruction->getInstructionById($instr_num));

        $trtypes = array('Rollover', 'Deposit');

        $tr = $this->Transaction->find(
            'all',
            array(
                'conditions'     => array(
                    'tr_type'     => $trtypes,
                    'LOWER(tr_state)'    => array('confirmation received'),
                    'Transaction.instr_num' => $instr_num,
                    'NOT' => array(
                        'tr_number' => array_merge(
                            $this->Transaction->remaindersAfterBreakage(),
                            $this->Transaction->remaindersAfterCall()
                        )
                    ),
                ),
                'recursive' => 1,
            )
        );

        $trList = array();
        foreach ($tr as $key => $value) {
            $trList[$value['Transaction']['tr_number']] = array(
                "instr_num"             => $value['Transaction']['instr_num'],
                "tr_type"             => $value['Transaction']['tr_type'],
                "mandate_name"        => $value['Mandate']['mandate_name'],
                "cmp_name"            => $value['Compartment']['cmp_name'],
                "maturity_date"     => $value['Transaction']['maturity_date'],
                "commencement_date" => $value['Transaction']['commencement_date'],
                "depo_term"         => $value['Transaction']['depo_term'],
                "depo_type"         => $value['Transaction']['depo_type'],
                "currency"             => $value['AccountA']['ccy'],
                "amount"            => $value['Transaction']['amount'],
                "scheme"            => $value['Transaction']['scheme'],
                "external_ref"        => $value['Transaction']['external_ref'],
                "interest_rate"        => $value['Transaction']['interest_rate'],
                "date_basis"        => $value['Transaction']['date_basis'],
                "total_interest"    => $value['Transaction']['total_interest'],
                "tax_amount"    => $value['Transaction']['tax_amount'],
                "reference_rate"    => $value['Transaction']['reference_rate'],
            );
        }
        $this->set('trns', $trList);
    }

    function registerconf_result($tr_number = null, $instr_num = null)
    {

        @$this->validate_param('int', $tr_number);
        @$this->validate_param('int', $instr_num);
        if (isset($tr_number)) {

            if (strpos($tr_number, ',') !== false) {
                $tr_numbers = explode(',', $tr_number);
                $tr_number = $tr_numbers[0];
            } else {
                $tr_numbers = array($tr_number);
            }

            $this->Transaction->virtualFields  =  array(
                'amountccy' => "CONCAT(Transaction.amount,' ',AccountA.ccy)",
                'interest'    => "CONCAT(Transaction.total_interest,' ',AccountA.ccy)",
                'tax'    => "CONCAT(Transaction.tax_amount,' ',AccountA.ccy)",
            );
            $this->Transaction->setFieldsToDisplay($tr_number);

            if (!empty($tr_numbers)) {
                $transactions = array();
                foreach ($tr_numbers as $trn) {
                    $hh = $this->Transaction->getRawsById($trn);
                    $tmp = reset($hh);
                    $transactions[] = $tmp;
                }
                $this->set('confirmedTransactions', $transactions);
            }

            $this->set(compact('instr_num'));
        }
    }

    function callconf()
    {
        $transactions = $this->Transaction->find(
            'all',
            array(
                'conditions'     => array(
                    'tr_type'     => array('Call'),
                    'tr_state'    => array('Instruction Sent')
                ),
            )
        );

        $this->set(compact('transactions'));

        if ($this->request->is('post') || $this->request->is('put')) {
            $interest = $this->request->data['Transaction']['total_interest'];
            $tax = $this->request->data['Transaction']['tax_amount'];

            $interest = str_replace(',', '', $interest);
            $tax = str_replace(',', '', $tax);

            if (!empty($this->request->data['Transaction']['tr_number'])) {
                $tr_call = $this->Transaction->read(null, $this->request->data['Transaction']['tr_number']);
                $principal = $tr_call["Transaction"]["amount"];
                $principal = floatval($principal);
                $principal_call = floatval($principal);

                $this->Transaction->set('tr_state', 'Confirmation Received');
                $this->Transaction->set('amount', $principal_call);
                //$this->Transaction->set('total_interest', $interest);
                //$this->Transaction->set('tax_amount', $tax);
                $this->Transaction->save();

                $tr_called = $this->Transaction->read(null, $tr_call['Transaction']['parent_id']);
                $tr_called['Transaction']['total_interest'] = $interest;
                $tr_called['Transaction']['tax_amount'] = $tax;
                $this->Transaction->save($tr_called);

                $this->log_entry('Confirmation received TRN; ' . $this->Transaction->id . ' ' . print_r($this->request->data['Transaction'], true), 'treasury', $this->Transaction->id);
                $sister_trn = $this->Transaction->findSisterTRN($this->request->data['Transaction']['tr_number']);
                if (isset($sister_trn['Transaction'])) {
                    $this->Transaction->read(null, $sister_trn['Transaction']['tr_number']);
                    $this->Transaction->set('tr_state', 'Confirmation Received');
                    $this->Transaction->save();
                }
                $event = new CakeEvent('Model.Treasury.Transaction.change', $this, array("transaction" => $tr_call));
                $this->getEventManager()->dispatch($event);
                $this->Session->setFlash('The transaction ' . $this->request->data['Transaction']['tr_number'] . ' changed to "Confirmation Received"', 'flash/success');
                $this->redirect($this->referer());
            } else {
                $this->Session->setFlash('All fields are required', 'flash/error');
            }
        }
    }

    function alertbatch($launch = false)
    {

        @$this->validate_param('bool', $launch);
        $this->set('launch', $launch);
        if (!empty($launch)) {
            $command = 'treasury.maturity -d ' . EIFENV;
            $args = explode(' ', $command);

            $dispatcher = new ShellDispatcher($args, false);

            if ($resp = $dispatcher->dispatch()) {
                $this->set($resp);
                return true;
            }
        }
        return false;

        /****** OLD VERSION CLONING THE SHELL CLASS *******/
        $tr_email = array();

        /* First, repayments */
        $repayments = $this->Transaction->find('all', array(
            'conditions' => array(
                'tr_type'           => array('Repayment'),
                'tr_state'          => array('Instruction Sent'),
            )
        ));

        /** Turn previous repayments TO 'Confirmed' **/
        foreach ($repayments as $key => $value) {
            $this->Transaction->read(null, $value['Transaction']['tr_number']);
            $this->Transaction->set('tr_state', 'Confirmed');
            $this->Transaction->save();
        }

        /* Maturity Date 14 */
        $transactions = $this->Transaction->find('all', array(
            'conditions' => array(
                'maturity_date <='  => date("Y-m-d", strtotime("+14 days")),
                'tr_type'           => array('Deposit', 'Rollover'),
                'depo_type'         => 'Term',
                'tr_state'          => array('Confirmed', 'First Notification', 'Second Notification'),
            ),
            'fields'                => array('Transaction.days', 'tr_number'),
        ));


        $processed = array();
        foreach ($transactions as $key => $tr) {
            $status =  $this->Transaction->statusAtMaturity($tr['Transaction']['tr_number']);
            $tr_state = $this->Transaction->getAttribByTrn('tr_state', $tr['Transaction']['tr_number']);
            if ($status != false and $status != $tr_state) {
                $processed[$key] = $tr['Transaction']['tr_number'];
                $this->Transaction->read(null, $tr['Transaction']['tr_number']);
                $this->Transaction->set('tr_state', $status);
                $this->Transaction->save();
            }
        }

        $this->set(compact('processed'));

        if (sizeof($processed) > 0) {
            /*  -------------------  */
            /* | Automatic Renewal | */
            /*  -------------------  */
            $trToRenew = $this->Transaction->find('all', array(
                'conditions' => array(
                    'tr_number'           => $processed,
                    'tr_state'            => 'Renewed',
                ),
                'fields'    => array('tr_number', 'amount', 'original_id', 'mandate_ID', 'cmp_ID', 'cpty_id', 'accountA_IBAN', 'accountB_IBAN', 'total_interest', 'scheme', 'maturity_date', 'tax_amount', 'depo_renew', 'depo_term', 'rate_type', 'instr_num'),
            ));

            $tr = array();
            foreach ($trToRenew as $key =>  $value) {
                $tr[$value['Transaction']['tr_number']] = array_slice($value['Transaction'], 1);
            }

            foreach ($tr as $trn => $value) {

                switch ($value['scheme']) {
                    case 'AA':
                        $amount_leftA = (float) ($value['amount'] + $value['total_interest'] - $value['tax_amount']);
                        $amount_leftB = 0.00;
                        $new_repayment = false;
                        $amount = $amount_leftA;
                        $rollover_source_fund = 'A';
                        break;
                    case 'BB':
                        $amount_leftA   = 0.00;
                        $amount_leftB   = (float) ($value['amount'] + $value['total_interest'] - $value['tax_amount']);
                        $new_repayment  = false;
                        $amount         = $amount_leftB;
                        $rollover_source_fund = 'B';
                        break;
                    case 'AB':
                        $amount_leftA   = (float) ($value['amount']);
                        $amount_leftB   = (float) ($value['total_interest'] - $value['tax_amount']);
                        $new_repayment  = true;
                        $amount         = $amount_leftA;
                        $amount_repay   = $amount_leftB;
                        $rollover_source_fund = 'A';
                        break;
                }

                $accounts = $this->Compartment->getAccountsByCmp($value['cmp_ID']);

                $this->Reinvestment->create();
                $this->Reinvestment->set('reinv_status',      'Open');
                $this->Reinvestment->set('mandate_ID',        $value['mandate_ID']);
                $this->Reinvestment->set('cmp_ID',            $value['cmp_ID']);
                $this->Reinvestment->set('cpty_ID',           $value['cpty_id']);
                $this->Reinvestment->set('availability_date', $value['maturity_date']);
                $this->Reinvestment->set('accountA_IBAN',     $accounts['accountA_IBAN']);
                $this->Reinvestment->set('accountB_IBAN',     $accounts['accountB_IBAN']);
                $this->Reinvestment->set('amount_leftA',      $amount_leftA);
                $this->Reinvestment->set('amount_leftB',      $amount_leftB);
                $this->Reinvestment->set('reinv_type',        'Renewal');

                $this->Reinvestment->save();
                $reinv_id = $this->Reinvestment->id;

                $this->Transaction->read(null, $trn);
                $this->Transaction->set('reinv_group', $reinv_id);
                $this->Transaction->save();

                // Create rollover
                $this->Transaction->create();
                $data = array(
                    'Transaction' => array(
                        'tr_type'           => 'Rollover',
                        'tr_state'          => 'Instruction Sent',
                        'depo_type'         => 'Term',
                        'depo_renew'        => $value['depo_renew'],
                        'rate_type'         => $value['rate_type'],
                        'booking_status'    => 'Not booked',
                        'source_group'      => $reinv_id,
                        'original_id'          => $value['original_id'],
                        'parent_id'            => $trn,
                        'amount'            => $amount,
                        'commencement_date' => $value['maturity_date'],
                        'depo_term'         => $value['depo_term'],
                        'date_basis'        => $value['date_basis'],
                        'accountA_IBAN'     => $value['accountA_IBAN'],
                        'accountB_IBAN'     => $value['accountB_IBAN'],
                        'scheme'            => $value['scheme'],
                        'mandate_ID'        => $value['mandate_ID'],
                        'cmp_ID'            => $value['cmp_ID'],
                        'cpty_id'           => $value['cpty_id'],
                        'source_fund'       => $rollover_source_fund,
                        'instr_num'            => $value['instr_num'],
                    )
                );
                $this->Transaction->save($data);
                $rollover_id = $this->Transaction->id;
                $interest_rate = $this->Interest->getInterestAt($trn, $value['maturity_date']);
                $this->Interest->updateInterest($rollover_id, $interest_rate, $value['maturity_date']);

                if ($new_repayment) {
                    $this->Transaction->create();
                    $data = array('Transaction' => array(
                        'tr_type'           => 'Repayment',
                        'tr_state'          => 'Confirmed',
                        'source_group'      => $reinv_id,
                        'original_id'          => $value['original_id'],
                        'parent_id'            => $trn,
                        'amount'            => $amount_repay,
                        'commencement_date' => $value['maturity_date'],
                        'maturity_date'     => $value['maturity_date'],
                        'rate_type'         => $value['rate_type'],
                        'booking_status'    => 'Not booked',
                        'accountA_IBAN'     => $value['accountB_IBAN'],
                        'accountB_IBAN'     => $value['accountB_IBAN'],
                        'scheme'            => $value['scheme'],
                        'mandate_ID'        => $value['mandate_ID'],
                        'cmp_ID'            => $value['cmp_ID'],
                        'cpty_id'           => $value['cpty_id'],
                        'source_fund'       => 'B',
                    ));
                    $this->Transaction->save($data);
                }

                $this->Reinvestment->read(null, $reinv_id);
                $this->Reinvestment->set('reinv_status', 'Closed');
                $this->Reinvestment->save();
                $event = new CakeEvent('Model.Treasury.Transaction.changeAll', $this, array("transaction" => $this->Transaction->data));
                $this->getEventManager()->dispatch($event);
            }

            $transactions = $this->Transaction->find('all', array(
                'conditions'    => array(
                    'tr_number' => $processed
                )

            ));
            if (sizeof($transactions) > 0) {

                $tr_email = $transactions;
                $this->set(compact('transactions'));
            }
        }
        // Transaction which are First and Second Notification (sent to view) but not necessarily
        //processed
        $notifications = $this->Transaction->find('all', array(
            'conditions'    => array(
                'tr_state' => array('First Notification', 'Second Notification')
            ),

        ));
        $this->set(compact('notifications'));
    }


    function edit()
    { /// bond version

        if (Cache::read("automatic_fixing", 'treasury')) {
            $this->Session->setFlash("Process of the automatic interest fixing is currently running. Your modification on the transactions could create a conflict in the database. Please try again in 5 minutes.", 'flash/warning');
        }
        $fields = array(
            'Transaction.tr_number',
            "'Deposit' AS Type",
            'Mandate.mandate_name',
            'Compartment.cmp_name',
            'commencement_date',
            'maturity_date',
            'CONCAT(amount," ",AccountA.ccy) as Amount',
            'Transaction.depo_type AS Term_or_Call',
            'Transaction.depo_renew AS Renewal',
            'depo_term',
            "DATEDIFF(maturity_date,  CURDATE()) as days"
        );

        //filter
        $mandates = $this->Mandate->find('list', array(
            'order' => 'Mandate.mandate_name',
            'conditions' => array("Mandate.mandate_id !=" => 0),
            'fields' => array('mandate_id', 'mandate_name'),
        ));
        $this->set('mandates', $mandates);
        $counterparties = $this->Counterparty->find('list', array(
            'order' => 'Counterparty.cpty_name',
            //'conditions' => array("Counterparty.cpty_ID" => $cpty_id_list),
            'fields' => array('cpty_ID', 'cpty_name'),
        ));
        $this->set('counterparties', $counterparties);

        $tr_number_filter = null;
        $mandate_id_filter = null;
        $cpty_id_filter = null;
        if (!$this->Session->read('Treasury.edit.filter')) {
            $this->Session->write('Treasury.edit.filter', array(
                'Transaction'   => array(
                    'tr_number' => ''
                ),
                'Mandate' => array(
                    'mandate_id' => '',
                ),
                'Counterparty' => array(
                    'cpty_id' => '',
                )
            ));
        }
        if (isset($this->request->data['filterform'])) {
            $this->request->params['named']['page'] = 1;
        }
        if (isset($this->request->data['filterform']['tr_number'])) {
            $this->request->data['filterform']['tr_number'] = filter_var($this->request->data['filterform']['tr_number'], FILTER_SANITIZE_NUMBER_INT);
            $tr_number_filter = $this->request->data['filterform']['tr_number'];
            $this->Session->write('Treasury.edit.filter.Transaction.tr_number', $tr_number_filter);
        }
        if (isset($this->request->data['filterform']['mandate_id'])) {
            $mandate_id_filter = $this->request->data['filterform']['mandate_id'];
            $this->Session->write('Treasury.edit.filter.Mandate.mandate_id', $mandate_id_filter);
        }
        if (isset($this->request->data['filterform']['cpty_id'])) {
            $cpty_id_filter = $this->request->data['filterform']['cpty_id'];
            $this->Session->write('Treasury.edit.filter.Counterparty.cpty_id', $cpty_id_filter);
        }
        if (!empty($this->Session->read('Treasury.edit.filter.Transaction.tr_number'))) {
            $tr_number_filter = $this->Session->read('Treasury.edit.filter.Transaction.tr_number');
        }
        if (!empty($this->Session->read('Treasury.edit.filter.Mandate.mandate_id'))) {
            $mandate_id_filter = $this->Session->read('Treasury.edit.filter.Mandate.mandate_id');
        }
        if (!empty($this->Session->read('Treasury.edit.filter.Counterparty.cpty_id'))) {
            $cpty_id_filter = $this->Session->read('Treasury.edit.filter.Counterparty.cpty_id');
        }
        $tr_number_filter = filter_var($tr_number_filter, FILTER_SANITIZE_NUMBER_INT);

        $this->set('tr_number_filter', $tr_number_filter);
        $this->set('mandate_id_filter', $mandate_id_filter);
        $this->set('cpty_id_filter', $cpty_id_filter);

        // Need to be discussed with Tomasz. Rollovers from interest fixing are not captured and should not appear in this screen,
        // same for Rollovers from Call deposit and deposit break
        $filter_conditions = array('deposit' => array(), 'rollover' => array(), 'bond' => array("Bondtransaction.tr_state = 'Created'"));

        $filter_conditions['deposit'][] = "Transaction.tr_type = 'Deposit'";
        $filter_conditions['deposit'][] = "Transaction.tr_state = 'Created'";
        $filter_conditions['deposit'][] = "outFromReinv.reinv_type <> 'Break'";

        $filter_conditions['rollover'][] = "Transaction.tr_type IN ('Rollover','Repayment') ";
        $filter_conditions['rollover'][] = "Transaction.tr_state = 'Created'";
        $filter_conditions['rollover'][] = "outFromReinv.reinv_status = 'Open'";

        if (!empty($tr_number_filter)) {
            $filter_conditions['deposit'][] = "Transaction.tr_number = " . $tr_number_filter;
            $filter_conditions['rollover'][] = "Transaction.tr_number = " . $tr_number_filter;
            $filter_conditions['bond'][] = "Bondtransaction.tr_number = " . $tr_number_filter;
        }
        if (!empty($mandate_id_filter)) {
            $filter_conditions['deposit'][] = "Transaction.mandate_ID = " . $mandate_id_filter;
            $filter_conditions['rollover'][] = "Transaction.mandate_ID = " . $mandate_id_filter;
            $filter_conditions['bond'][] = "Bondtransaction.mandate_ID = " . $mandate_id_filter;
        }
        if (!empty($cpty_id_filter)) {
            $filter_conditions['deposit'][] = "Transaction.cpty_id = " . $cpty_id_filter;
            $filter_conditions['rollover'][] = "Transaction.cpty_id = " . $cpty_id_filter;
            $filter_conditions['bond'][] = "bondtransaction.cpty_id = " . $cpty_id_filter;
        }
        $fields_bond = array(
            "Bondtransaction.tr_number",
            "'Bond' as type",
            "Mandate.mandate_name as mandate_name",
            "cmp_name",
            "Bondtransaction.settlement_date",
            "Bond.maturity_date",
            "nominal_amount as amount",
            "'' as Term_or_Call",
            "'No' as renewal",
            "'' as depo_term",
            "DATEDIFF(maturity_date,  CURDATE()) as days"
        );
        $fields_paginator = array('deposit' => $fields, 'bond' => $fields_bond);
        //RESULTS
        $this->Paginator->settings = $this->paginate;
        $this->Paginator->settings = array(
            'limit' => 20,
            'conditions' => $filter_conditions,
            'fields'    => $fields_paginator,
            'recursive'    => 1,
        );
        $transactions = $this->Paginator->paginate('Transactionbondid');
        $this->set(compact('transactions'));
    } // end EDIt bond version


    public function updateIndicativeMaturity()
    {
        $transactions = $this->Transaction->find('all', array(
            'fields' => array('Transaction.tr_number'),
            'limit' => 100,
            'conditions' => array(
                'OR' => array(
                    'indicative_maturity_date' => NULL,
                )
            )
        ));
        if (!empty($transactions)) foreach ($transactions as $key => $transaction) {
            $trn = $this->Transaction->read(null, $transaction['Transaction']['tr_number']);
            $trn = $this->Transaction->save($trn);
            $event = new CakeEvent('Model.Treasury.Transaction.change', $this, array("transaction" => $trn));
            $this->getEventManager()->dispatch($event);
            if (!empty($trn['Transaction']['indicative_maturity_date'])) print '<br>- TRN #' . $trn['Transaction']['tr_number'] . ' > ' . $trn['Transaction']['indicative_maturity_date'];
        }
        die();
    }

    public function benchmark()
    {
        //UPDATE
        if (!empty($this->request->data['updatebench']['updateRefRate']) && !empty($this->request->data['Transaction']['tr_number'])) {
            $up = $this->request->data;


            $rate = $up['Transaction']['reference_rate'];
            $rate = str_replace(',', '', $rate);

            $this->request->data['Transaction']['spread_bp'] =  str_replace(',', '', $this->request->data['Transaction']['spread_bp']);
            $this->request->data['Transaction']['spread_bp'] =  str_replace('+', '', $this->request->data['Transaction']['spread_bp']); //remove the '+'

            $up['Transaction']['reference_rate'] = $rate;
            $spread_str = explode('.', $up['Transaction']['spread_bp']);
            if ((strlen($spread_str[0]) >= 8)) {
                error_log("spread bp value too big for database : TreasurytransactionController.php " . __LINE__);
                $this->set('updateSuccess', array(
                    'status' => 'error',
                    'message' => __('Transaction #' . $this->request->data['Transaction']['tr_number'] . ' has NOT been saved')
                ));
            } else {
                $model = $this->Transactionbondid->getModel($this->request->data['Transaction']['tr_number']);
                switch ($model) {
                    case 'transactions':
                        $old_values = $this->Transaction->find('first', array('conditions' => array('Transaction.tr_number' => $this->request->data['Transaction']['tr_number']), 'fields' => array('reference_rate', 'spread_bp')));

                        if ($this->Transaction->save($up)) {
                            //$event = new CakeEvent('Model.Treasury.Transaction.change', $this, array("transaction" => $this->Transaction->data));
                            //$this->getEventManager()->dispatch($event);
                            $this->set('updateSuccess', array(
                                'status' => 'success',
                                'message' => __('Transaction #' . $this->request->data['Transaction']['tr_number'] . ' has been saved')
                            ));
                            $this->log_entry("Benchmark update TRN " . $this->request->data['Transaction']['tr_number'] . ": ref.rate from " . $old_values['Transaction']['reference_rate'] . " to " . $this->request->data['Transaction']['reference_rate'] . "; spread BP from " . $old_values['Transaction']['spread_bp'] . " to " . $this->request->data['Transaction']['spread_bp'], 'treasury', $this->request->data['Transaction']['tr_number']);
                        } else {
                            $this->set('updateSuccess', array(
                                'status' => 'error',
                                'message' => __('Transaction #' . $this->request->data['Transaction']['tr_number'] . ' has NOT been saved')
                            ));
                        }
                        break;

                    case 'bonds':
                    case 'bondstransaction':
                        $old_values = $this->Bondtransaction->find('first', array('conditions' => array('Bondtransaction.tr_number' => $this->request->data['Transaction']['tr_number']), 'fields' => array('reference_rate', 'spread_bp')));
                        $this->Bondtransaction->read(null, $this->request->data['Transaction']['tr_number']);
                        $bond_trn_data = $this->Bondtransaction->find("first", array('conditions' => array('Bondtransaction.tr_number' => $this->request->data['Transaction']['tr_number']), 'recursive' => -1));
                        $bond_trn_data['Bondtransaction']['reference_rate'] = $this->request->data['Transaction']['reference_rate'];
                        //$bond_trn_data['Bondtransaction']['spread_bp'] = $this->request->data['Transaction']['spread_bp'];
                        $bond_trn_data['Bondtransaction']['benchmark'] = $this->request->data['Transaction']['benchmark'];
                        $saved = $this->Bondtransaction->save($bond_trn_data);
                        //error_log("benchmark saved : ".json_encode($saved, true));
                        if ($saved) {
                            /*$event = new CakeEvent('Model.Treasury.Transaction.change', $this, array("transaction" => $this->Transaction->data));
							$this->getEventManager()->dispatch($event);*/
                            $this->set('updateSuccess', array(
                                'status' => 'success',
                                'message' => __('Transaction #' . $this->request->data['Transaction']['tr_number'] . ' has been saved')
                            ));
                            $this->log_entry("Update of benchmark for bond TRN " . $this->request->data['Transaction']['tr_number'] . ": benchmark:" . $bond_trn_data['Bondtransaction']['benchmark'] . "; ref.rate from " . $old_values['Bondtransaction']['reference_rate'] . " to " . $this->request->data['Transaction']['reference_rate'] . "; spread BP from " . $old_values['Bondtransaction']['spread_bp'] . " to " . $this->request->data['Transaction']['spread_bp'], 'treasury');
                        } else {
                            $this->set('updateSuccess', array(
                                'status' => 'error',
                                'message' => __('Transaction #' . $this->request->data['Transaction']['tr_number'] . ' has NOT been saved')
                            ));
                        }
                        break;

                    default:
                        $this->Session->setFlash('Unknown transaction. You cannot edit transactions by typing url directly, please use the edit/delete menu.', 'flash/error');
                        $this->redirect('/treasury/treasurytransactions/edit');
                        break;
                }
            }
        }
        //FILTERS UPDATE
        if ($this->request->is('post')) {
            $this->request->params['named']['page'] = 1;

            if ($this->Session->read('Form.data.Transaction.mandate_ID')) {
                if (!empty($this->request->data['Transaction']['mandate_ID']) && ($this->request->data['Transaction']['mandate_ID'] != $this->Session->read('Form.data.Transaction.mandate_ID'))) {
                    unset($this->request->data['Transaction']['cpty_id']);
                    unset($this->request->data['Transaction']['cmp_ID']);
                }
            }

            $this->Session->write('Form.data', $this->request->data);
        }
        $conditions = array('deposit' => array(), 'bond' => array('Bondtransaction.tr_type="Bond"'));
        if ($this->Session->read('Form.data.Transaction.mandate_ID')) {
            $conditions['deposit'][] = 'Transaction.mandate_ID = ' . $this->Session->read('Form.data.Transaction.mandate_ID');
            $conditions['bond'][] = 'Bondtransaction.mandate_ID = ' . $this->Session->read('Form.data.Transaction.mandate_ID');
        }
        if ($this->Session->read('Form.data.Transaction.cpty_id')) {
            $conditions['deposit'][] = 'Transaction.cpty_id = ' . $this->Session->read('Form.data.Transaction.cpty_id');
            $conditions['bond'][] = 'Bondtransaction.cpty_id = ' . $this->Session->read('Form.data.Transaction.cpty_id');
        }
        if ($this->Session->read('Form.data.Transaction.cmp_ID')) {
            $conditions['deposit'][] = 'Transaction.cmp_ID = ' . $this->Session->read('Form.data.Transaction.cmp_ID');
            $conditions['bond'][] = 'Bondtransaction.cmp_id = ' . $this->Session->read('Form.data.Transaction.cmp_ID');
        }

        $this->set('instr_mandates', $this->Mandate->getMandateList());

        $instr_counterparties = $instr_cmp = array();
        if (!empty($this->Session->read('Form.data.Transaction.mandate_ID'))) {
            $instr_counterparties = $this->Mandate->getcptybymandate($this->Session->read('Form.data.Transaction.mandate_ID'));
            $instr_cmp = $this->Compartment->find('list', array(
                'fields' => array('cmp_ID', 'cmp_name'),
                'recursive'    => -1,
                'conditions' => array('cmp_name <>' => '', 'mandate_ID' => $this->Session->read('Form.data.Transaction.mandate_ID')),
                'order' => array('cmp_name')
            ));
        }
        $this->set(compact('instr_counterparties'));
        $this->set(compact('instr_cmp'));

        $fields = array(
            'deposit' => array('Transactionbondid.table_link', 'Transaction.tr_number', 'instr_num', "Compartment.cmp_name", "commencement_date", 'AccountA.ccy as ccy', "amount", 'interest_rate', 'benchmark', 'reference_rate', 'spread_bp', '1 as yield'),
            'bond' => array('Transactionbondid.table_link', 'Bondtransaction.tr_number', 'instr_num', "Compartment.cmp_name", "settlement_date as commencement_date", 'Bond.currency as ccy', 'nominal_amount as amount', 'coupon_rate as interest_rate', 'benchmark', 'reference_rate', 'spread_bp', 'yield_to_maturity as yield')
        );

        //RESULTS
        $this->Paginator->settings = $this->paginate;
        $this->Paginator->settings = array(
            'limit' => 20,
            'conditions' => $conditions,
            'order' => array('tr_number' => 'desc'),
            'fields' => $fields,
        );
        $transactions = $this->Paginator->paginate('Transactionbondid');
        $this->set(compact('transactions'));
    }

    public function spreadFix()
    {
        $limit = 10;
        $trns = $this->Transaction->find('all', array('limit' => 10, 'recursive' => -1, 'conditions' => array(
            'interest_rate <>' => NULL,
            'reference_rate <>' => NULL,
            'spread_bp' => NULL,
        )));

        $count = count($trns);
        $message = '<h1>' . $count . ' spreads to fix</h1>';

        if (!empty($trns)) foreach ($trns as $trn) {
            $up = array();
            $up['Transaction']['tr_number'] = $trn['Transaction']['tr_number'];
            $up = $this->Transaction->save($up);
            $message .= '- Transaction #' . $up['Transaction']['tr_number'] . ' spread updated: ' . $up['Transaction']['spread_bp'] . '<br>';
        }

        if ($count < $limit) {
            $message .= '<h3>=> There shouldnt be anymore spread to fix.</h3>';
        } else {
            $message .= '<h3>=> There might have some other spreads to fix. Please refresh this page.</h3>';
        }
        die($message);
    }

    public function interest_rate_change()
    {
        $have_interest_rate_history = $this->Interest->find('list', array('fields' => array('trn_number')));
        $value_date = date("Y-m-d");
        $selected_trn = array();
        $conditions = array(
            'depo_type' => 'Callable',
            'tr_number' => $have_interest_rate_history,
            'tr_type != ' => 'Call',
            'tr_state' => array('Called', 'Reinvested', 'Confirmed')
        );
        if (!empty($this->request->data['updateInterestRate']['value_date'])) {
            if (strpos($this->request->data['updateInterestRate']['value_date'], '/') !== false) {
                $val = explode('/', $this->request->data['updateInterestRate']['value_date']);
                $value_date = $val[2] . "-" . $val[1] . "-" . $val[0];
            } else {
                $value_date = $this->request->data['updateInterestRate']['value_date'];
            }
            $this->set('value_date', $value_date);
        }
        if ($this->request->is('post') or $this->request->is('ajax')) {
            if (!empty($this->request->data['updateInterestRate']['new_rate']) && !empty($this->request->data['Transaction'])) {
                $trn_list = array_keys($this->request->data['Transaction']);
                foreach ($trn_list as $tr) {
                    if ($this->request->data['Transaction'][$tr] == '0') {
                        unset($this->request->data['Transaction'][$tr]);
                    }
                }
                if (count($this->request->data['Transaction']) < 1) {
                    $this->Session->setFlash('Please select at least one transaction.', 'flash/error');
                } else {
                    $selected_trn = array_keys($this->request->data['Transaction']);
                    $new_rate = $this->request->data['updateInterestRate']['new_rate'];
                    $this->interest_rate_change_update($selected_trn, $value_date, $new_rate);
                }
            }
        }
        if (!empty($this->request->data['updateInterestRate']['value_date'])) {
            $conditions[] = array('OR'    => array('maturity_date' =>    null, 'maturity_date > ' =>    $value_date));
            $conditions[] = array('Interest.interest_rate_from < ' =>    $value_date, 'Interest.interest_rate_to' =>    null);
        } else {
            $conditions[] = array('maturity_date' =>    null);
        }
        if (!empty($this->request->data['updateInterestRate']['cpty_ID'])) {
            $conditions['Transaction.cpty_id'] = $this->request->data['updateInterestRate']['cpty_ID'];
            $this->set('cpty_ID', $this->request->data['updateInterestRate']['cpty_ID']);
        }
        if (!empty($this->request->data['updateInterestRate']['cpty_ID']) && !empty($this->request->data['updateInterestRate']['value_date'])) {
            $callables = $this->Transaction->find('all', array(
                'conditions' => $conditions,
                'recursive' => 0,
                'order' => 'Counterparty.cpty_name ASC, Mandate.mandate_name ASC, Transaction.tr_number ASC'
            ));
        } else {
            $callables = array();
        }
        $this->set(compact('callables'));

        $conditions = array(
            'depo_type' => 'Callable',
            'tr_state' => array('Called', 'Reinvested', 'Confirmed')
        );
        $conditions[] = array('OR'    => array('maturity_date' =>    null, 'maturity_date >= ' => $value_date));
        $callables = $this->Transaction->find('all', array(
            'conditions' => $conditions,
            'recursive' => 0,
        ));
        $cpty_ids = array();
        $conditions_no_value_date = array(
            'depo_type' => 'Callable',
            //'tr_number' => $have_interest_rate_history
            'tr_state' => array('Called', 'Reinvested', 'Confirmed'),
            'tr_type != ' => 'Call',
        );
        $callables_no_value_date = $this->Transaction->find('all', array(
            'conditions' => $conditions_no_value_date,
            'recursive' => 0,
        ));
        foreach ($callables_no_value_date as &$trn) {
            $cpty_ids[$trn['Transaction']['cpty_id']] = $trn['Transaction']['cpty_id'];
        }

        $counterparties = $this->Counterparty->find('list', array(
            'conditions' => array(
                'cpty_ID' => $cpty_ids
            ),
            'fields' => array('cpty_ID', 'cpty_name'),
            'order' => 'Counterparty.cpty_name',
            'recursive' => -1
        ));
        $this->set(compact('counterparties'));
    }

    private function interest_rate_change_update($transactions_ids, $value_date, $new_interest_rate)
    {
        @$this->validate_param('string', $transactions_ids); //list
        @$this->validate_param('date', $value_date);
        @$this->validate_param('decimal', $new_interest_rate);
        if (strpos($value_date, '/') !== false) {
            $date_from = explode('/', $value_date);
            $value_date = $date_from[2] . '-' . $date_from[1] . '-' . $date_from[0];
        }

        $trns = $this->Transaction->find("all", array('conditions'    =>    array('Transaction.tr_number'    =>    $transactions_ids, 'Interest.interest_rate_to'    =>    null), 'recursive' => 1));
        $ok_to_proceed = true;
        foreach ($trns as $tr) {
            //check new rate_from after previous rate_from
            $latest_interest = $this->Interest->find('first', array('conditions' => array('trn_number' => $tr['Transaction']['tr_number'], 'interest_rate_to' => null)));
            if ($latest_interest) {
                if (strtotime($latest_interest['Interest']['interest_rate_from']) > strtotime($value_date)) {
                    $this->Session->setFlash('The date of the change must be after the starting date of the previous rate period.', 'flash/error');
                    $ok_to_proceed = false;
                }
            }
        }

        if ($ok_to_proceed) {
            foreach ($trns as $tr) {
                $latest_interest = $tr['Interest'];
                if ($latest_interest['id'] !== null) {
                    $int = $this->Interest->read(null, $latest_interest['id']);
                    $int['interest_rate_to'] = $value_date;
                    $this->Interest->save(array('Interest' => $int));
                }
                $data = array('Interest' => array(
                    'trn_number' => $tr['Transaction']['tr_number'],
                    'interest_rate_from' => $value_date,
                    'interest_rate_to' => null,
                    'interest_rate' => $new_interest_rate
                ));
                $sav = $this->Interest->create($data);
                $this->Interest->save();
                $tr['Transaction']['interest_rate'] = $new_interest_rate;
                $newest_interest = $this->Transaction->save($tr);
                $this->log_entry('Interest rate update TRN ' . $tr['Transaction']['tr_number'] . ' from ' . $latest_interest['interest_rate'] . ' to ' . $new_interest_rate . ' on ' . $value_date, 'treasury', $tr['Transaction']['tr_number']);
                $this->log_entry('Benchmark update TRN ' . $tr['Transaction']['tr_number'] . ': int. rate from ' . $latest_interest['interest_rate'] . ' to ' . $new_interest_rate . '; spread BP from ' . $tr['Transaction']['spread_bp'] . ' to ' . $newest_interest['Transaction']['spread_bp'], 'treasury');
            }
            $this->Session->setFlash('The interest_rate for the transactions ' . implode(',', $transactions_ids) . ' has been saved.', 'flash/success');
        }
    }

    public function getInterest($amount, $date_end, $date_basis, $commencement_date, $tr_number)
    {
        @$this->validate_param('decimal', $amount);
        @$this->validate_param('date', $date_end);
        @$this->validate_param('date', $commencement_date);
        @$this->validate_param('string', $date_basis);
        @$this->validate_param('int', $tr_number);
        $tc1 = array('OR' => array('interest_rate_to >= ' => $commencement_date, 'interest_rate_to' => null));
        $tc2 = array('interest_rate_from <= ' => $date_end);
        $time_conditions = array('AND' => array($tc1, $tc2));
        $conditions =  array('recursive' => -1, "conditions" => array('trn_number' => $tr_number, $time_conditions));
        $interest_list =  $this->Interest->find("all", $conditions);
        $imax = count($interest_list);
        $total_interest = 0;
        for ($i = 0; $i < $imax; $i++) {
            $interest_rate = $interest_list[$i]['Interest']["interest_rate"];
            $start_date_interest = $interest_list[$i]['Interest']["interest_rate_from"];
            $end_date_interest = $interest_list[$i]['Interest']["interest_rate_to"];
            if (empty($end_date_interest) || (strtotime($end_date_interest) > strtotime($date_end))) {
                $end_date_interest = $date_end;
            }
            if (strtotime($start_date_interest) < strtotime($commencement_date)) {
                $start_date_interest = $commencement_date;
            }

            $params = array(
                "amount"            => $amount,
                "new_date"            => $end_date_interest,
                "interest_rate"        => $interest_rate,
                "date_basis"        => $date_basis,
                "commencement_date"    => $start_date_interest,
            );
            $interest = $this->SAS->curl("register_confirmation_new.sas", $params, false);
            if (strpos($interest, 'This request completed with errors') !== false) {
                error_log("SAS error on calculation of the interest, Params : " . json_encode($params, true) . " for Interest : " . json_encode($interest_list[$i], true));
                throw new Exception("SAS error on calculation of the interest");
            } else {
                $interest = mb_convert_encoding($interest, "UTF-8"); // to remove \ufeff
                $interest = trim($interest); // to remove \r
                $interest = preg_replace("/[^-0-9\,\.]/", '', $interest);
                if (strpos($interest, '.') === false) {
                    $interest = $interest . ".00";
                }
                $total_interest +=  (float)filter_var($interest, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            }
        }

        return $total_interest;
    }

    function recalculate_limits()
    {
        $event = new CakeEvent('Model.Treasury.Transaction.changeAll', $this, array("transaction" => $this->data));
        $this->getEventManager()->dispatch($event);
    }

    function interest_calculation_table($trn_list)
    {
        if (empty($trn_list)) {
            return false;
        }
        $table_name = uniqid('interest_', true);
        $table_name = str_replace('.', '', $table_name);
        $this->Transaction->query('CREATE TABLE treasury.' . $table_name . ' (
		   `id` int(11)  PRIMARY KEY AUTO_INCREMENT,
		   `tr_number` int(11) NOT NULL,
		   `amount` decimal(15,2) DEFAULT NULL,
		   `interest_rate` decimal(11,3) DEFAULT NULL,
		   `commencement_date` date DEFAULT NULL,
		   `end_date` date DEFAULT NULL,
		   `date_basis` varchar(10) DEFAULT NULL,
		   `result` decimal(15,2) DEFAULT NULL
		);');
        $query_multi = "INSERT INTO treasury." . $table_name . " (tr_number,amount,interest_rate,commencement_date,end_date,date_basis) values ";
        foreach ($trn_list as $interest_trn) {

            $query_multi .= " (" . $interest_trn['tr_number'];
            $query_multi .= "," . $interest_trn['amount'];
            $query_multi .= "," . $interest_trn['interest_rate'];
            $query_multi .= ",'" . $this->fix_date($interest_trn['commencement_date']) . "'";
            $query_multi .= ",'" . $interest_trn['end_date'] . "'";
            $query_multi .= ",'" . $interest_trn['date_basis'] . "'";
            $query_multi .= "),";
        }
        $query_multi = substr_replace($query_multi, ';', -1, 1);
        $this->Transaction->query($query_multi);

        $params = array(
            "table"    =>    $table_name,
        );
        $interest = $this->SAS->curl("interest_calculation.sas", $params, false);
        $interests_calculated = $this->Transaction->query("SELECT tr_number, result FROM " . $table_name);
        $this->Transaction->query("DROP TABLE treasury." . $table_name);

        $result = array();
        foreach ($interests_calculated as $interest_line) {
            $tr_number = $interest_line[$table_name]['tr_number'];
            $result_interest  = $interest_line[$table_name]['result'];

            if (empty($result[$tr_number])) {
                $result[$tr_number] = '0.00';
            }
            $result[$tr_number] = bcadd($result[$tr_number], $result_interest, 2);
        }
        return $result;
    }

    public function fix_date($date)
    {
        if (strpos($date, '/') !== false) {
            $date = explode('/', $date);
            $date = $date[2] . '-' . $date[1] . '-' . $date[0];
        }
        return $date;
    }

    /*
	function getInterestList
	return an array containing the interest and periods for a trn ready for the interest_calculation_table() function
	$from and $to are dates of format YYYY-MM-DD
	*/
    public function getInterestList($tr_number, $from, $to, $date_basis, $amount, $rate)
    {
        $return = array();
        $interest_list = $this->Interest->getInterestInterval($tr_number, $from, $to);

        if (!empty($interest_list)) {
            foreach ($interest_list as $interest) {
                $start = $from;
                if (strtotime($interest['Interest']['interest_rate_from']) > strtotime($from)) {
                    $start = $interest['Interest']['interest_rate_from'];
                }
                $end = $to;
                if (!empty($interest['Interest']['interest_rate_to']) && strtotime($interest['Interest']['interest_rate_to']) < strtotime($to)) {
                    $end = $interest['Interest']['interest_rate_to'];
                }
                $return[] = array(
                    'tr_number' => $tr_number,
                    'amount' => $amount,
                    'interest_rate' => $interest['Interest']['interest_rate'],
                    'commencement_date' => $start,
                    'end_date' => $end,
                    'date_basis' => $date_basis,
                );
            }
        } else {
            $return[] = array(
                'tr_number' => $tr_number,
                'amount' => $amount,
                'interest_rate' => $rate,
                'commencement_date' => $commencement_date,
                'end_date' => $to,
                'date_basis' => $date_basis,
            );
        }
        return $return;
    }
}
