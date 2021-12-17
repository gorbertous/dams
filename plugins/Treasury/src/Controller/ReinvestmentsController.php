<?php

declare(strict_types=1);

namespace Treasury\Controller;

use Cake\Event\EventInterface;

/**
 * Reinvestments Controller
 *
 * @property \Treasury\Model\Table\ReinvestmentsTable $Reinvestments
 * @method \Treasury\Model\Entity\Reinvestment[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReinvestmentsController extends AppController
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
        $reinvestments = $this->paginate($this->Reinvestments);

        $this->set(compact('reinvestments'));
    }

    /**
     * View method
     *
     * @param string|null $id Reinvestment id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $reinvestment = $this->Reinvestments->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('reinvestment'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $reinvestment = $this->Reinvestments->newEmptyEntity();
        if ($this->request->is('post')) {
            $reinvestment = $this->Reinvestments->patchEntity($reinvestment, $this->request->getData());
            if ($this->Reinvestments->save($reinvestment)) {
                $this->Flash->success(__('The reinvestment has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The reinvestment could not be saved. Please, try again.'));
        }
        $this->set(compact('reinvestment'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Reinvestment id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $reinvestment = $this->Reinvestments->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $reinvestment = $this->Reinvestments->patchEntity($reinvestment, $this->request->getData());
            if ($this->Reinvestments->save($reinvestment)) {
                $this->Flash->success(__('The reinvestment has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The reinvestment could not be saved. Please, try again.'));
        }
        $this->set(compact('reinvestment'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Reinvestment id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $reinvestment = $this->Reinvestments->get($id);
        if ($this->Reinvestments->delete($reinvestment)) {
            $this->Flash->success(__('The reinvestment has been deleted.'));
        } else {
            $this->Flash->error(__('The reinvestment could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    function calculate()
    {
        $this->Transaction->virtualFields  =  array(
            'amountccy' => "CONCAT(Transaction.amount,' ',AccountA.ccy)",
            'interest'    => "CONCAT(Transaction.total_interest,' ',AccountA.ccy)",
            'tax'    => "CONCAT(Transaction.tax_amount,' ',AccountA.ccy)",
        );

        /*
		 * Get mandates list
		 */
        $this->set('mandates_list', $this->Mandate->getMandateList());
        if ($this->request->is('post')) {
            if (isset($this->request->data['openreinvestform']['reinvestables']) and !empty($this->request->data['openreinvestform']['reinvestables'])) {
                $incoming = $this->request->data['openreinvestform']['reinvestables'];

                if (empty($this->request->data['openreinvestform']['mandate_ID'])) {
                    $this->Session->setFlash('Please Select a Mandate', 'flash/error');
                    $error = true;
                }

                if (empty($this->request->data['openreinvestform']['cpty_ID'])) {
                    $this->Session->setFlash('Please Select a Counterparty', 'flash/error');
                    $error = true;
                }

                if (empty($this->request->data['openreinvestform']['cmp_ID'])) {
                    $this->Session->setFlash('Please Select a Compartment', 'flash/error');
                    $error = true;
                }

                if (!isset($error)) {

                    $amountsAvailable = $this->Transaction->computeReinvGroup($incoming);
                    $accounts = $this->Compartment->getCompartementById($this->request->data['openreinvestform']['cmp_ID']);

                    $this->set(compact('amountsAvailable'));
                    $this->set(compact('accounts'));

                    $transactions = $this->Transaction->getRawsById($this->request->data['openreinvestform']['reinvestables']);
                    $calculated = true;
                    $this->set(compact('calculated'));
                    $this->set(compact('transactions'));
                    $this->set('headers', $this->Transaction->fieldsToDisplay);
                    $this->Session->write('incoming', $incoming);
                    $this->Session->write('amountsAvailable', $amountsAvailable);
                    $this->Session->write('accounts', $accounts);
                    $this->Session->write('mandate_ID', $this->request->data['openreinvestform']['mandate_ID']);
                    $this->Session->write('cmp_ID', $this->request->data['openreinvestform']['cmp_ID']);
                    $this->Session->write('cpty_id', $this->request->data['openreinvestform']['cpty_ID']);
                    $this->Session->write('availability_date', $this->request->data['openreinvestform']['availability_date']);
                }
            } else $this->Session->setFlash('You should select at least one incoming transaction', 'flash/error');
        }
        if (isset($calculated) && $calculated) {
            $this->set('msg', '');
            $this->set('tab2state', 'active');
            $this->set('tab1state', '');
        } else {
            $this->set('msg', 'Please first create a reinvestment group by filling out the form above.');
            $this->set('tab2state', '');
            $this->set('tab1state', 'active');
        }

        $this->set('title_for_layout', 'Calculate Reinvestment Group');
    }

    function open($reinv = null)
    {

        $output = array('success' => false);

        $this->Transaction->virtualFields  =  array(
            'amountccy' => "CONCAT(Transaction.amount,' ',AccountA.ccy)",
            'interest'    => "CONCAT(Transaction.total_interest,' ',AccountA.ccy)",
            'tax'    => "CONCAT(Transaction.tax_amount,' ',AccountA.ccy)",
        );

        if (!empty($reinv)) {
            $incoming = $reinv['incoming'];
            $amountsAvailable = $reinv['amountsAvailable'];
            $accounts = $reinv['accounts'];
            $mandate_id = $reinv['mandate_ID'];
            $cmp_ID = $reinv['cmp_ID'];
            $cpty_id = $reinv['cpty_id'];
            $availability_date = $reinv['availability_date'];
        } else {
            $incoming = $this->Session->read('incoming');
            $amountsAvailable = $this->Session->read('amountsAvailable');
            $accounts = $this->Session->read('accounts');
            $mandate_id = $this->Session->read('mandate_ID');
            $cmp_ID = $this->Session->read('cmp_ID');
            $cpty_id = $this->Session->read('cpty_id');
            $availability_date = $this->Session->read('availability_date');
        }

        if (!empty($incoming)) {
            $reinvestment = array(
                'Reinvestment' => array(
                    'reinv_status'         => 'Open',
                    'mandate_ID'        => $mandate_id,
                    'cmp_ID'            => $cmp_ID,
                    'cpty_ID'            => $cpty_id,
                    'availability_date'    => $availability_date,
                    /*'accountA_IBAN'		=> $accounts['Compartment']['accountA_IBAN'],
					'accountB_IBAN'		=> $accounts['Compartment']['accountB_IBAN'],*/
                    'amount_leftA'        => $amountsAvailable['amountInA'],
                    'amount_leftB'        => $amountsAvailable['amountInB'],
                    'reinv_type'        => 'Standard',
                )
            );
            if (isset($accounts['Compartment']['accountA_IBAN'])) {
                $reinvestment['Reinvestment']['accountA_IBAN'] = $accounts['Compartment']['accountA_IBAN'];
            }
            if (isset($accounts['Compartment']['accountB_IBAN'])) {
                $reinvestment['Reinvestment']['accountB_IBAN'] = $accounts['Compartment']['accountB_IBAN'];
            }
            if (isset($accounts['Compartment']['accountA'])) {
                $reinvestment['Reinvestment']['accountA_IBAN'] = $accounts['Compartment']['accountA'];
            }
            if (isset($accounts['Compartment']['accountB'])) {
                $reinvestment['Reinvestment']['accountB_IBAN'] = $accounts['Compartment']['accountB'];
            }

            $this->Reinvestment->create();
            $reinv = $this->Reinvestment->save($reinvestment);
            if (empty($reinv)) {
                error_log("could not create the reinvestment : " . json_encode($reinv, true));
                error_log("could not create the reinvestment : " . json_encode($this->Reinvestment->validationErrors, true));
            }
            $reinv_group_id = $reinv["Reinvestment"]["reinv_group"];
            $reinv_group = $this->Reinvestment->getRawsById($reinv_group_id);
            if (isset($reinv_group['Reinvestment'])) {
                $reinv_group = array($reinv_group);
            }
            $reinv_group[0]['Reinvestment']['availability_date'] = $this->type_date($reinv_group[0]['Reinvestment']['availability_date']);
            $reinv_account = $this->Reinvestment->find("first", array(
                'conditions' => array('reinv_group' => $reinv_group_id),
                'fields'    => array('AccountA.ccy'),
            ));
            $reinv_group[0]['Reinvestment']['currency'] = $reinv_account['AccountA']['ccy'];
            error_log("reinvestments l" . __LINE__ . " : " . json_encode($reinv_group, true));
            $this->set('reinvestment', $reinv_group);


            if (!empty($incoming)) foreach ($incoming as $value) {
                $save_trns[] = array('Transaction' => array(
                    'tr_number'     => $value,
                    'tr_state'         => "Reinvested",
                    'reinv_group'     => $reinv_group_id,
                ));
            }

            if (!empty($save_trns)) {
                $this->Transaction->saveMany($save_trns);
                foreach ($save_trns as $trn) {
                    $this->log_entry('Reinvestment created. reinvGroup ' . $reinv_group_id . ' ' . print_r($reinvestment['Reinvestment'], true), 'treasury', $trn['Transaction']['tr_number']);
                }
            } else {
                $this->log_entry('Reinvestment created. reinvGroup ' . $reinv_group_id . ' ' . print_r($reinvestment['Reinvestment'], true), 'treasury');
            }

            $transactions = $this->Transaction->getRawsById($incoming);
            $this->set(compact('transactions'));
            $this->set('headers', $this->Transaction->fieldsToDisplay);

            $this->set('title_for_layout', 'Open Reinvestment');

            $output['success'] = true;
            $output['transactions'] = $transactions;
            $output['headers'] = $this->Transaction->fieldsToDisplay;
            $output['reinv_group'] = $reinv_group;
            $output['reinv_group_id'] = $reinv_group_id;
        }

        return $output;
    }

    function close($reinv_group_id_forced = null)
    {

        @$this->validate_param('int', $reinv_group_id_forced);
        $output = array('success' => false);

        $reinvGroupOpts = $this->Reinvestment->getreinvs('Open');
        $this->set(compact('reinvGroupOpts'));
        $this->set('openReinvNo', count($reinvGroupOpts));
        $output['reinvGroupOpts'] = $reinvGroupOpts;
        $output['openReinvNo'] = count($reinvGroupOpts);

        if (!empty($this->request->data['closereinvform']['reinv_group'])) {
            $reinv_group_id = $this->request->data['closereinvform']['reinv_group'];
        }
        if (!empty($reinv_group_id_forced)) {
            $reinv_group_id = $reinv_group_id_forced;
        }

        if (!empty($reinv_group_id)) {

            $reinvestment = $this->Reinvestment->getRawsById($reinv_group_id);
            $reinvestment = $reinvestment[0];

            $output['reinvestment'] = $reinvestment;

            if (isset($reinvestment['Reinvestment'])) {
                if ($reinvestment['Reinvestment']['amount_leftA'] == 0 && $reinvestment['Reinvestment']['amount_leftB'] == 0) {
                    $reinvestment['Reinvestment']['reinv_status'] = 'Closed';
                    $test = $this->Reinvestment->save($reinvestment);
                    if (!$test) {
                        $msg = "could not save : " . json_encode($this->Reinvestment->validationErrors, true);
                        $this->Session->setFlash($msg, "flash/error");
                        $this->redirect($this->referer());
                    }
                    $closed_reinv = $this->Reinvestment->getRawsById($this->Reinvestment->id);
                    $closed_reinv[0]['Reinvestment']['Amount left A'] = $closed_reinv[0]['Reinvestment']['amount_leftA'];
                    unset($closed_reinv[0]['Reinvestment']['amount_leftA']);
                    $closed_reinv[0]['Reinvestment']['Amount left B'] = $closed_reinv[0]['Reinvestment']['amount_leftB'];
                    unset($closed_reinv[0]['Reinvestment']['amount_leftB']);
                    $this->set(compact('closed_reinv'));
                    $trn = $this->Transaction->find('first', array('conditions' => array('Transaction.reinv_group' => $this->Reinvestment->id), 'recursive' => -1, 'fields' => array('tr_number')));
                    if (!empty($trn)) {
                        foreach ($trn as $tr) {
                            $this->log_entry('Reinvestment closed. reinvGroup ' . $this->Reinvestment->id, 'treasury', $tr['Transaction']['tr_number']);
                        }
                    } else {
                        $this->log_entry('Reinvestment closed. reinvGroup ' . $this->Reinvestment->id, 'treasury');
                    }
                    $output['closed_reinv'] = $closed_reinv;
                    $output['success'] = true;

                    $msg = "The reinvestment <strong>" . $reinvestment['Reinvestment']['reinv_group'] . "</strong> is now <strong>closed</strong>";
                    $output['msg'] = $msg;

                    if (empty($reinv_group_id_forced)) {
                        $this->Session->setFlash($msg, "flash/success");
                    }
                } else {
                    $msg = 'Cannot close reinvestment #' . $reinvestment['Reinvestment']['reinv_group'] . ' until amounts left are balanced';
                    $msg .= '. Amounts left: ' . UniformLib::uniform($reinvestment['Reinvestment']['amount_leftA'], 'amount_leftA') . ' / ' . UniformLib::uniform($reinvestment['Reinvestment']['amount_leftB'], 'amount_leftB');
                    $output['msg'] = $msg;

                    if (empty($reinv_group_id_forced)) {
                        $this->Session->setFlash($msg, "flash/error");
                        $this->redirect($this->referer());
                    }
                }
            } else {
                $msg = "Cannot find this reinvestment in the DB, please contact the administrator";
                $output['msg'] = $msg;

                if (empty($reinv_group_id_forced)) {
                    $this->Session->setFlash($msg, "flash/error");
                    $this->redirect($this->referer());
                }
            }
        }

        if (isset($closed_reinv)) {
            $this->set('msg', '');
            $this->set('tab2state', 'active');
            $this->set('tab1state', '');
        } else {
            $this->set('msg', 'Please first fill in the form in case there is an open reinvestment.');
            $this->set('tab2state', '');
            $this->set('tab1state', 'active');
        }

        $this->set('title_for_layout', 'Close reinvestment');
        return $output;
    }

    function reinvreportform()
    {
        $this->set('openedreinvs', $this->Reinvestment->getreinvs('Open'));
        $this->set('closedreinvs', $this->Reinvestment->getreinvs('Closed'));
    }

    // reopen reinvestment
    function edit_to_reopen()
    {

        // Get the list of 'reopenable' reinvestments
        //$reinv_groups = $this->Reinvestment->getreinvs('Closed');
        $reinv_groups = $this->Reinvestment->getreinvs_reopen();
        $openables = array();
        //$start_time = microtime(true);
        //error_log("edit_to_reopen function start");
        if (!is_string($reinv_groups)) {
            foreach ($reinv_groups as $key => $value) {
                if ($this->Reinvestment->isOpenable($key)) {
                    $openables[$key] = $key;
                }
            }
        }
        //error_log("openable optim: ".json_encode($openables, true));
        //$start_time2 = microtime(true);
        //error_log("edit_to_reopen function openables ".($start_time2 - $start_time)." on ".count($reinv_groups)." reinv");
        $this->Reinvestment->unBindModel(array('hasMany' => array('inTransactions', 'outTransactions')));
        $this->Reinvestment->fieldsToDisplay = array(
            'reinv_group',
            'Reinvestment.reinv_status as Status',
            'Mandate.mandate_name',
            'Compartment.cmp_name',
            'availability_date',
            'CONCAT(amount_leftA," ",AccountA.ccy) as amount_leftA',
            'CONCAT(amount_leftB," ",AccountA.ccy) as amount_leftB'
        );
        $this->set('reopenableReinvs', $this->Reinvestment->getRawsById($openables));
    }

    // reopen reinvestment
    function edit_to_delete()
    {
        // Get the list of 'deletable' reinvestments
        $reinv_groups = $this->Reinvestment->getreinvs('Open');
        if (is_array($reinv_groups)) {
            $deletables = array();
            foreach ($reinv_groups as $key => $value) {
                if ($this->Reinvestment->isDeletable($key)) {
                    $deletables[$key] = $key;
                }
            }
            $this->Reinvestment->unBindModel(array('hasMany' => array('inTransactions', 'outTransactions')));
            $this->Reinvestment->fieldsToDisplay = array(
                'reinv_group',
                'Reinvestment.reinv_status as Status',
                'Mandate.mandate_name',
                'Compartment.cmp_name',
                'availability_date',
                'CONCAT(amount_leftA," ",AccountA.ccy) as amount_leftA',
                'CONCAT(amount_leftB," ",AccountA.ccy) as amount_leftB'
            );

            $this->set('deletableReinvs', $this->Reinvestment->getRawsById($deletables));
        } else {
            $this->set('deletableReinvs', array());
        }
    }

    // Reopen reinvestment given its reinv_group
    function reopen($reinv_group)
    {

        @$this->validate_param('int', $reinv_group);
        if (!$this->Reinvestment->isOpenable($reinv_group)) {
            $this->Session->setFlash('This reinvestment can not be reopend.', 'flash/error');
            $this->redirect('/treasury/treasuryreinvestments/edit_to_reopen/');
        }

        // Summary of reinvestment to open
        $this->Reinvestment->unBindModel(array('hasMany' => array('inTransactions', 'outTransactions')));
        $this->Reinvestment->fieldsToDisplay = array(
            'reinv_group',
            'Reinvestment.reinv_status as Status',
            'Mandate.mandate_name',
            'Compartment.cmp_name',
            'availability_date',
            'amount_leftA',
            'amount_leftB',
        );
        $this->set('reopenableReinv', $this->Reinvestment->getRawsById($reinv_group));

        // Summary of its outgoing transactions
        $incoming = $this->Reinvestment->getInOutTrn('in', $reinv_group);
        $outgoing = $this->Reinvestment->getInOutTrn('out', $reinv_group);
        $this->Transaction->fieldsToDisplay = array(
            'tr_number',
            'Transaction.tr_state as State',
            'Transaction.tr_type as Type',
            'Mandate.mandate_name',
            'Compartment.cmp_name',
            'commencement_date',
            'CONCAT(amount," ",AccountA.ccy) as Amount',
        );
        $this->set('inTransactions', $this->Transaction->getRawsById($incoming));
        $this->set('outTransactions', $this->Transaction->getRawsById($outgoing));
        if ($this->request->is('post')) {
            $this->Reinvestment->read(null, $reinv_group);
            $this->Reinvestment->set('reinv_status', 'Open');
            $this->Reinvestment->save();
            $this->Session->setFlash('The reinvestment ' . $reinv_group . ' has been reopened', 'flash/success');
            $this->log_entry("Reinvestment  group " . $reinv_group . " reopened", 'treasury');
            $this->redirect('/treasury/treasuryreinvestments/edit_to_reopen');
        }
    }

    // Delete reinvestment given its reinv_group
    function delete($reinv_group)
    {

        @$this->validate_param('int', $reinv_group);
        if (!$this->Reinvestment->isDeletable($reinv_group)) {
            $this->Session->setFlash('This reinvestment can not be deleted as it has outgoing transactions which you should first delete, is not open or technical.', 'flash/error');
            $this->redirect('/treasury/treasuryreinvestments/edit_to_delete/');
        }

        // Summary of reinvestment to delete
        $this->Reinvestment->unBindModel(array('hasMany' => array('inTransactions', 'outTransactions')));
        $this->Reinvestment->fieldsToDisplay = array(
            'reinv_group',
            'Reinvestment.reinv_status as Status',
            'Mandate.mandate_name',
            'Compartment.cmp_name',
            'availability_date',
            'amount_leftA',
            'amount_leftB',
        );
        $this->set('deletableReinv', $this->Reinvestment->getRawsById($reinv_group));

        // Summary of its incoming transactions
        $incoming = $this->Reinvestment->getInOutTrn('in', $reinv_group);

        $this->Transaction->fieldsToDisplay = array(
            'tr_number',
            'Transaction.tr_state as State',
            'Transaction.tr_type as Type',
            'Mandate.mandate_name',
            'Compartment.cmp_name',
            'commencement_date',
            'CONCAT(amount," ",AccountA.ccy) as Amount',
        );
        $this->set('inTransactions', $this->Transaction->getRawsById($incoming));

        if ($this->request->is('post')) {
            foreach ($incoming as $key => $tr_number) {
                $status = $this->Transaction->statusAtMaturity($tr_number);
                $this->Transaction->read(null, $tr_number);
                $this->Transaction->set('tr_state', 'Confirmed');
                $this->Transaction->set('reinv_group', '1');
                if ($status != false and $status != 'Renewed') $this->Transaction->set('tr_state', $status);
                $this->Transaction->save();
            }
            $this->Reinvestment->read(null, $reinv_group);
            $this->Reinvestment->set('reinv_status', 'Deleted');
            $this->Reinvestment->save();
            $this->log_entry("Reinvestment  group " . $reinv_group . " deleted", 'treasury', $incoming);
            $this->Session->setFlash('The reinvestment ' . $reinv_group . ' has been deleted', 'flash/success');
            $this->redirect('/treasury/treasuryreinvestments/edit_to_delete');
        }
    }

    /*
	 * Reinvestment report Result (Queries)
	 * @param (string) - $status IN {'Open', 'Closed'}
	 */

    function reinvreportresult($status)
    {

        @$this->validate_param('string', $status);
        $reinv_group = $this->request->data['reinvreportform'][$status];

        // Get Source Transactions
        $source_transactions = $this->Transaction->find(
            'all',
            array(
                'conditions'     => array('Transaction.reinv_group' => $reinv_group),
                'order'            => array('Transaction.tr_type' => 'desc'),
                'recursive'        => 1,
                'fields'        => array(
                    'tr_number', 'tr_type', 'tr_state',
                    'CONCAT(amount," ",AccountA.ccy) as Amount', 'CONCAT(total_interest," ",AccountA.ccy) as total_interest', 'commencement_date',
                    'maturity_date', 'depo_term', 'depo_type',
                    'depo_renew', 'rate_type', 'mandate_id', 'cmp_id', 'Mandate.mandate_name',
                    'Compartment.cmp_name', 'accountA_IBAN', 'accountB_IBAN',
                )
            )
        );
        $this->set(compact('source_transactions'));

        // Get Deposits and Rollovers
        $depo_rollovers = $this->Transaction->find(
            'all',
            array(
                'conditions'     => array(
                    'Transaction.source_group' => $reinv_group,
                    'Transaction.tr_type'    => array('Deposit', 'Rollover'),
                ),
                'order'            => array('Transaction.tr_type' => 'desc'),
                'recursive'        => 1,
                'fields'        => array(
                    'tr_number', 'tr_type', 'tr_state',
                    'CONCAT(amount," ",AccountA.ccy) as Amount',
                    'total_interest', 'commencement_date',
                    'maturity_date', 'depo_term', 'depo_type',
                    'depo_renew', 'rate_type', 'Mandate.mandate_name',
                    'Compartment.cmp_name', 'accountA_IBAN', 'accountB_IBAN',
                )
            )
        );
        $this->set(compact('depo_rollovers'));

        // Get Repayments
        $repayments = $this->Transaction->find(
            'all',
            array(
                'conditions'     => array(
                    'Transaction.source_group' => $reinv_group,
                    'Transaction.tr_type'    => array('Repayment', 'Call', 'Withdrawal'),
                ),
                'order'            => array('Transaction.tr_type' => 'desc'),
                'recursive'        => 1,
                'fields'        => array(
                    'tr_number', 'tr_type', 'tr_state',
                    'CONCAT(amount," ",AccountA.ccy) as Amount', 'total_interest',
                    'commencement_date', 'Mandate.mandate_name', 'Compartment.cmp_name',
                    'accountA_IBAN',
                )
            )
        );
        $this->set(compact('repayments'));
        $this->layout = 'ajax';
    }

    public function type_date($date)
    {
        if (strpos($date, '/') !== false) {
            $dat = explode('/', $date);
            $date = $dat[2] . '-' . $dat[1] . '-' . $dat[0];
        }
        return $date;
    }
}
