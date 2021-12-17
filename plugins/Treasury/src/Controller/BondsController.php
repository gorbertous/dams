<?php

declare(strict_types=1);

namespace Treasury\Controller;

use Cake\Event\EventInterface;

/**
 * Bonds Controller
 *
 * @property \Treasury\Model\Table\BondsTable $Bonds
 * @method \Treasury\Model\Entity\Bond[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BondsController extends AppController
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
        $bonds = $this->paginate($this->Bonds);

        $this->set(compact('bonds'));
    }

    /**
     * View method
     *
     * @param string|null $id Bond id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $bond = $this->Bonds->get($id, [
            'contain' => ['Transactions', 'Bonds', 'CouponSchedule'],
        ]);

        $this->set(compact('bond'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $bond = $this->Bonds->newEmptyEntity();
        if ($this->request->is('post')) {
            $bond = $this->Bonds->patchEntity($bond, $this->request->getData());
            if ($this->Bonds->save($bond)) {
                $this->Flash->success(__('The bond has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The bond could not be saved. Please, try again.'));
        }
        $transactions = $this->Bonds->Transactions->find('list', ['limit' => 200]);
        $this->set(compact('bond', 'transactions'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Bond id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $bond = $this->Bonds->get($id, [
            'contain' => ['Transactions'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $bond = $this->Bonds->patchEntity($bond, $this->request->getData());
            if ($this->Bonds->save($bond)) {
                $this->Flash->success(__('The bond has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The bond could not be saved. Please, try again.'));
        }
        $transactions = $this->Bonds->Transactions->find('list', ['limit' => 200]);
        $this->set(compact('bond', 'transactions'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Bond id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $bond = $this->Bonds->get($id);
        if ($this->Bonds->delete($bond)) {
            $this->Flash->success(__('The bond has been deleted.'));
        } else {
            $this->Flash->error(__('The bond could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function trconfirmation($tr_number)
    {

        @$this->validate_param('string', $tr_number);
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


        if (!empty($tr_numbers)) {
            $transactions = array();
            foreach ($tr_numbers as $tr_number) {
                $transaction = $this->Bondtransaction->getTransactionById($tr_number);
                $transactions[] = $transaction;
            }
            $this->set(compact('transactions'));
        }
    }

    public function trcorrection_router($tr_number)
    {
        @$this->validate_param('int', $tr_number);
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
    }

    public function deletetr($tr_number)
    {
        @$this->validate_param('int', $tr_number);
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

    function newbonds($action = null, $tr_number = null, $checkonly = null)
    {
        @$this->validate_param('string', $action);
        @$this->validate_param('int', $tr_number);
        @$this->validate_param('bool', $checkonly);
        $this->set('UserIsInCheckerGroup', $this->UserPermissions->UserIsInCheckerGroup());
        /*
    	 * If isset bondtransaction number, load data for edition
    	 */
        if (isset($tr_number)) {
            // EDIT SAVING
            if ($this->request->is('post') && !empty($this->request->data)) {
                // bond id cannot change but ISIN can
                $old_bond_id = $this->request->data['Bond']['current_bond_id'];
                $bond_db = $this->Bond->find('first', array('conditions' => array('Bond.bond_id' => $old_bond_id)));
                $bondtransaction_db = $this->Bondtransaction->find('first', array('conditions' => array('Bondtransaction.tr_number' => $this->request->data['TransactionBond']['tr_number'])));
                //creating new instruction
                $data_instruction = array('Instruction' => array(
                    'instr_type' => 'Bond',
                    'instr_status' => 'Created',
                    'cpty_ID' => $bondtransaction_db['Bondtransaction']['cpty_id'],
                    'mandate_ID' => $bondtransaction_db['Bondtransaction']['mandate_id'],
                    'instr_date' => $this->data['TransactionBond']['settlement_date'],
                    'created_by' => $this->UserAuth->getUserName(),
                ));
                $this->Instruction->create();
                $instr = $this->Instruction->save($data_instruction);
                if (empty($instr)) {
                    // stop, show error message
                    $this->Session->setFlash('Could not create the new instruction. Please contact the administrator', 'flash/error');
                    $this->redirect($this->referer());
                }
                /*else
				{
					$this->log_entry('Instruction number '.$instr['Instruction']['instr_num'].' created with TRN(S): '.print_r($instr['Instruction'], true), 'treasury');
				}*/
                $bondtransactionData = $this->request->data['TransactionBond'];
                $bondtransactionData['instr_num'] = $instr['Instruction']['instr_num'];
                $bondtransactionData['nominal_amount'] = str_replace(',', '', $bondtransactionData['nominal']);
                unset($bondtransactionData['nominal']);
                $bondtransactionData['purchase_price'] = str_replace(',', '', $bondtransactionData['purchase_price']);
                $bondtransactionData['accrued_coupon_at_purchase'] = str_replace(',', '', $bondtransactionData['accrued_coupon']);
                $bondtransactionData['total_purchase_amount'] = str_replace(',', '', $bondtransactionData['total_purchase_amount']);
                $bondtransactionData['total_coupon'] = str_replace(',', '', $bondtransactionData['coupon']);
                $bondtransactionData['total_tax'] = str_replace(',', '', $bondtransactionData['tax_amount']);
                $bondtransactionData['yield_to_maturity'] = str_replace(',', '', $bondtransactionData['yield']);
                $bondtransactionData['reference_rate'] = str_replace(',', '', $bondtransactionData['reference_rate']);
                $bondtransactionData['tr_state'] = "Instruction Created"; // update state for new instruction
                $bondtransactionData['currency'] = $bondtransactionData['ccy']; // update state for new instruction

                $save_edit = $this->Bondtransaction->save(array('Bondtransaction' => $bondtransactionData));
                if (empty($save_edit)) {
                    $this->Session->setFlash('Could not save your changes.', 'flash/error');
                } else {
                    $this->log_entry('Instruction number ' . $instr['Instruction']['instr_num'] . ' created with TRN(S): ' . print_r($bondtransactionData, true), 'treasury');
                    // save bond
                    // saving bond changes
                    $bond = $this->request->data['Bond'];
                    if (!empty($this->request->data['Bond']['current_bond_id'])) {
                        $bond['bond_id'] = $this->request->data['Bond']['current_bond_id'];
                    }
                    if (!empty($this->request->data['Bond']['new_isin'])) {
                        $bond['ISIN'] = $this->request->data['Bond']['new_isin'];
                        unset($bond['new_isin']);
                    }

                    if ($bond_db['Bond']['state'] == "Created") {
                        $bond['coupon_rate'] = str_replace(',', '', $bond['coupon_rate']);
                        $bond['tax_rate'] = str_replace(',', '', $bond['tax_rate']);
                        /*if ($bond['date_convention'] == 'Modified Following')
						{
							$bond['date_convention'] = 'Modified_Following';
						}
						if ($bond['date_convention'] == 'Modified Preceding')
						{
							$bond['date_convention'] = 'Modified_Preceding';
						}*/
                        $save_edit = $this->Bond->save(array('Bond' => $bond));
                        $this->log_entry('Bond ' . $bond['bond_id'] . ' corrected : ' . print_r($bond, true), 'treasury');
                    }
                    if (!empty($save_edit)) {
                        $this->log_entry('Bond TRN ' . $this->request->data['TransactionBond']['tr_number'] . ' corrected : ' . print_r($bondtransactionData, true), 'treasury');
                        //$this->log_entry('Bond '.$bond['bond_id'].' corrected : '.print_r($bond, true), 'treasury');
                        // new instruction
                        $this->generate_pdf_instruction($bondtransactionData['tr_number']);
                        $this->Session->setFlash('Bond transaction ' . $bondtransactionData['tr_number'] . ' has been successfully registered.', 'flash/success');
                        $this->redirect("/treasury/treasurytransactions/edit");
                    } else {
                        $this->Session->setFlash('Could not save your changes.', 'flash/error');
                    }
                }
            }

            $tr = $this->Bondtransaction->getTransactionById($tr_number);

            //exit if transaction is not editable or if it's nor a deposit, neither a rollover
            if (empty($tr) or !$this->Bondtransaction->isEditable($tr_number)) {
                $this->Session->setFlash('This transaction cannot be edited. You cannot edit transactions directly, please use the edit/delete menu.', 'flash/error');
                if (empty($multiple) && !$this->request->is('ajax')) $this->redirect('/treasury');
            }

            //default values format
            $tr['Bond']['issue_date'] = $this->sl_date($tr['Bond']['issue_date']);
            $tr['Bond']['first_coupon_accrual_date'] = $this->sl_date($tr['Bond']['first_coupon_accrual_date']);
            $tr['Bond']['first_coupon_payment_date'] = $this->sl_date($tr['Bond']['first_coupon_payment_date']);
            $tr['Bond']['maturity_date'] = $this->sl_date($tr['Bond']['maturity_date']);

            // Define default options depending on wether $tr is null or not
            $defaultOpts = array_merge($tr['Bondtransaction'], $tr['Bond']);


            // Mandate is always disabled when $tr_number is defined
            $disabledOpts = array(
                'mandate_ID' => 'readonly',
                'bond_id' => 'readonly',
                'tr_number' => 'readonly',
                'exist_isin' => true,
                'cpty_id ' => false,
                'date_basis' => false,
            );

            $bondTrn_keys = array_keys($tr["Bondtransaction"]);
            $bondTrn_disabled = array_fill_keys($bondTrn_keys, false);
            $disabledOpts = array_merge($disabledOpts, $bondTrn_disabled);

            if ($tr['Bond']['state'] == 'Confirmed') {
                // no editing the bond part
                $this->set("NoEditBond");
                $bond_keys = array_keys($tr["Bond"]);
                $bond_disabled = array_fill_keys($bond_keys, true);
                $disabledOpts = array_merge($disabledOpts, $bond_disabled);
            } else {
                $bond_keys = array_keys($tr["Bond"]);
                $bond_disabled = array_fill_keys($bond_keys, false);
                $disabledOpts = array_merge($disabledOpts, $bond_disabled);
            }

            // variable to use in the view for testing if $tr_number is set  or not
            $trnIsSet = true;

            // Get compartments list based on default mandate_ID
            $cmps =  $this->Compartment->getcmpbymandate($defaultOpts['mandate_id']);

            // Get counterparties list based on default mandate_ID
            $cptys =  $this->Mandate->getcptybymandate($defaultOpts['mandate_id']);

            $submitButtonLabel = 'Correct Transaction';

            // Set transaction type
            $this->set('tr_number', $tr_number);
        } else {

            $mandate_ID = 0;
            $cmp_ID = 0;
            $cpty_id = 0;
            if (!empty($this->data['Transaction']['mandate_ID'])) {
                $mandate_ID = $this->data['Transaction']['mandate_ID'];
            }
            if (!empty($this->data['Transaction']['cpty_id'])) {
                $cpty_id = $this->data['Transaction']['cpty_id'];
            }
            if (!empty($this->data['Bond']['mandate_ID'])) {
                $mandate_ID = $this->data['Bond']['mandate_ID'];
            }
            if (!empty($this->data['Bond']['cpty_id'])) {
                $cpty_id = $this->data['Bond']['cpty_id'];
            }
            if (!empty($this->data['TransactionBond']['cmp_ID'])) {
                $cmp_ID = $this->data['TransactionBond']['cmp_ID'];
            }
            /*
    	 	 * Load default data for addition
    	     */
            $trnIsSet = false;
            $cmps = array();
            $cptys = array();
            $accountA_IBAN = array();
            $accountB_IBAN = array();
            $defaultOpts = array(
                'mandate_id' => $mandate_ID,
                'cmp_id' => $cmp_ID,
                'cpty_id' => $cpty_id,
                'issuer' => '',
                'ISIN' => '',
                'bond_id' => '',
                'exist_isin' => '',
                'currency' => '',
                'issue_date' => '',
                'first_coupon_accrual_date' => '',
                'first_coupon_payment_date' => '',
                'maturity_date' => '',
                'coupon_rate' => '',
                'coupon_frequency' => '',
                'date_convention' => '',
                'tax_rate' => '',
                'trade_date' => '',
                'settlement_date' => '',
                'nominal_amount' => '',
                'purchase_price' => '',
                'accrued_coupon_at_purchase' => '',
                'total_purchase_amount' => '',
                'total_coupon' => '',
                'yield_to_maturity' => '',
                'reference_rate' => '',
                'total_interest' => false,
                'total_tax' => false,
                'date_basis' => false,
                'interest_rate' => false,
                'maturity_date' => false,
            );
            $disabledOpts = array(
                'mandate_id' => 0, 'cmp_id' => 0, 'cpty_id' => 0, 'issuer' => 0, 'currency' => 0, 'issue_date' => 0,
                'first_coupon_accrual_date' => 0,
                'first_coupon_payment_date' => 0,
                'maturity_date' => 0,
                'ISIN' => 0,
                'bond_id' => 0,
                'exist_isin' => 0,
                'coupon_rate' => 0,
                'coupon_frequency' => 0,
                'date_convention' => 0,
                'tax_rate' => 0,
                'trade_date' => 0,
                'settlement_date' => 0,
                'nominal_amount' => 0,
                'purchase_price' => 0,
                'accrued_coupon_at_purchase' => 0,
                'total_purchase_amount' => 0,
                'total_coupon' => 0,
                'yield_to_maturity' => 0,
                'reference_rate' => 0,
                'total_tax' => 0,
                'date_basis' => 0,
            );
            $submitButtonLabel = 'Create New Deposit';
        }

        $isin_list = $this->Bond->find('list', array('fields' => array('bond_id', 'ISIN'), 'conditions' => array("state" => "Confirmed")));
        if ($trnIsSet) {
            $defaultOpts['current_isin'] = $defaultOpts['ISIN'];
            $defaultOpts['current_bond_id'] = $defaultOpts['bond_id'];
            if ($defaultOpts['state'] == 'Confirmed') {
                //bond is not editable, is in existing ISIN field
                $isin_list[$defaultOpts['bond_id']] = $defaultOpts['ISIN'];
                $defaultOpts['ISIN'] = '';
            } else {
                // bond is editable, in new ISIN field
                //$defaultOpts['bond_id'] = '';
            }
        }
        $this->set('isin_list', $isin_list);
        $this->set(compact('defaultOpts'));
        $this->set(compact('disabledOpts'));
        $this->set(compact('trnIsSet'));
        $this->set(compact('cmps'));
        $this->set(compact('cptys'));
        $this->set(compact('accountA_IBAN'));
        $this->set(compact('submitButtonLabel'));
        $this->set('action', $action);
        $trnum = null;

        $this->set('isin_list', $isin_list);

        $ccy_list_all = $this->Account->find('all', array('conditions' => array('Account.ccy is not null'), 'fields' => array('distinct (Account.ccy) as ccy'), 'recursive' => -1));
        $ccy_list = array();
        foreach ($ccy_list_all as $ccy) {
            if (!empty($ccy['Account']['ccy'])) {
                $ccy_list[$ccy['Account']['ccy']] = $ccy['Account']['ccy'];
            }
        }
        $this->set('ccy_list', $ccy_list);
        /*
		 * If form was submited (post or ajax)
		 */
        if ($this->request->is('post') or $this->request->is('ajax')) {

            $this->request->data['Transaction']['tr_number'] = $tr_number;
            $this->request->data['Bond']['coupon_rate'] = str_replace(',', '', $this->request->data['Bond']['coupon_rate']);
            $this->request->data['Bond']['tax_rate'] = str_replace(',', '', $this->request->data['Bond']['tax_rate']);
            $this->request->data['TransactionBond']['nominal'] = str_replace(',', '', $this->request->data['TransactionBond']['nominal']);
            $this->request->data['TransactionBond']['purchase_price'] = str_replace(',', '', $this->request->data['TransactionBond']['purchase_price']);
            $this->request->data['TransactionBond']['accrued_coupon'] = str_replace(',', '', $this->request->data['TransactionBond']['accrued_coupon']);
            $this->request->data['TransactionBond']['total_purchase_amount'] = str_replace(',', '', $this->request->data['TransactionBond']['total_purchase_amount']);
            $this->request->data['TransactionBond']['coupon'] = str_replace(',', '', $this->request->data['TransactionBond']['coupon']);
            $this->request->data['TransactionBond']['tax_amount'] = str_replace(',', '', $this->request->data['TransactionBond']['tax_amount']);
            $this->request->data['TransactionBond']['yield'] = str_replace(',', '', $this->request->data['TransactionBond']['yield']);
            $this->request->data['TransactionBond']['reference_rate'] = str_replace(',', '', $this->request->data['TransactionBond']['reference_rate']);

            if (!empty($this->request->data['Transaction']['cmp_ID'])) {
                if (!isset($errors)) $errors = array();
            }
            if (empty($errors)) {

                $this->Transaction->set($this->request->data);
                $validateMultipleDeposits = $this->Transaction->validateMultipleDeposits();
                if (($validateMultipleDeposits == true) | ($validateMultipleDeposits == null)) {

                    if (!isset($this->request->data['checkOnly'])) $this->request->data['checkOnly'] = null;

                    /*
					 * Click only on check button and check also limit breach
					 */
                    if ($this->request->data['checkOnly']) {
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
                    } elseif (empty($this->request->data['checkOnly'])) {
                        if (!isset($tr_number)) {

                            /*
							 * Add new bond transaction (and maybe bond and instruction)
							 */
                            $save = true;

                            $ISIN = null;
                            $bond_id = null;
                            if (!empty($this->request->data['Bond']['new_isin'])) {
                                $ISIN = $this->request->data['Bond']['new_isin'];
                                // new BOND to create
                                $this->Bond->create();
                                $bond_data = array('Bond' => array(
                                    'ISIN' => $ISIN,
                                    'state' => 'Created',
                                    'issuer' => $this->data['Bond']['issuer'],
                                    'currency' => $this->data['Bond']['ccy'],
                                    'issue_date' => $this->data['Bond']['issuedate'],
                                    'first_coupon_accrual_date' => $this->data['Bond']['first_coupon_accrual_date'],
                                    'first_coupon_payment_date' => $this->data['Bond']['first_coupon_payment_date'],
                                    'maturity_date' => $this->data['Bond']['maturity_date'],
                                    'coupon_rate' => $this->data['Bond']['coupon_rate'],
                                    'coupon_frequency' => $this->data['Bond']['coupon_frequency'],
                                    'date_basis' => $this->data['Bond']['date_basis'],
                                    'date_convention' => $this->data['Bond']['date_convention'],
                                    'tax_rate' => $this->data['Bond']['tax_rate'],

                                ));
                                $bond = $this->Bond->save($bond_data);
                                if (empty($bond)) {
                                    //bond not created : error TODO
                                    echo $this->Bond->validationErrors;
                                    error_log($this->Bond->validationErrors);
                                } else {
                                    $bond_id = $bond['Bond']['bond_id'];

                                    $this->log_entry('Bond ' . $bond_id . '  created :' . print_r($bond['Bond'], true), 'treasury');
                                }
                            } elseif (!empty($this->request->data['Bond']['exist_isin'])) {
                                // reuse existing Bond
                                $bond_id = $this->request->data['Bond']['exist_isin'];
                                $bond = $this->Bond->find("first", array('conditions' => array('Bond.bond_id' => $bond_id)));
                                if (!empty($bond)) {
                                    $ISIN = $bond['Bond']['ISIN'];
                                    $bond_id = $bond['Bond']['bond_id'];
                                } else {

                                    //request bond that does not exist => error TODO
                                }
                            }

                            //create instruction
                            $data_instruction = array('Instruction' => array(
                                'instr_type' => 'Bond',
                                'instr_status' => 'Created',
                                'cpty_ID' => $this->data['Bond']['cpty_id'],
                                'mandate_ID' => $this->data['Bond']['mandate_ID'],
                                'instr_date' => $this->data['TransactionBond']['settlement_date'],
                                'created_by' => $this->UserAuth->getUserName(),
                            ));
                            //$this->Instruction->create();
                            $instr = $this->Instruction->save($data_instruction);
                            if (empty($instr)) {
                                error_log("could not create instruction for Bond : " . json_encode($instr, true));
                            }
                            $intruction_id = $instr['Instruction']['instr_num'];

                            $iban_account = "";
                            if (!empty($this->data['TransactionBond']['cmp_ID'])) {
                                $compartement = $this->Compartment->find("first", array('conditions' => array('cmp_ID' => $this->data['TransactionBond']['cmp_ID'])));
                                if (!empty($compartement)) {
                                    $iban_account = $compartement['Compartment']['accountA_IBAN'];
                                }
                            }
                            //$this->Bondtransaction->create();
                            $data_Bondtransaction = array('Bondtransaction' => array(
                                'bond_id'    => $bond_id,
                                //'ISIN'		=> $ISIN,//ISIN removed in latest BRD
                                'instr_num'    => $intruction_id,
                                'cpty_id'    => $this->data['Bond']['cpty_id'],
                                'mandate_id' => $this->data['Bond']['mandate_ID'],
                                'cmp_id' => $this->data['TransactionBond']['cmp_ID'],
                                'currency' => $this->data['TransactionBond']['ccy'],
                                'trade_date' => $this->data['TransactionBond']['trade_date'],
                                'settlement_date' => $this->data['TransactionBond']['settlement_date'],
                                'nominal_amount' => $this->data['TransactionBond']['nominal'],
                                'purchase_price' => $this->data['TransactionBond']['purchase_price'],
                                'accrued_coupon_at_purchase' => $this->data['TransactionBond']['accrued_coupon'],
                                'total_purchase_amount' => $this->data['TransactionBond']['total_purchase_amount'],
                                'total_coupon' => $this->data['TransactionBond']['coupon'],
                                'total_tax' => $this->data['TransactionBond']['tax_amount'],
                                'reference_rate' => $this->data['TransactionBond']['reference_rate'],
                                'tr_state' => 'Instruction Created',
                                'tr_type' => 'Bond',
                                'account_iban' => $iban_account,
                                'yield_to_maturity' => $this->data['TransactionBond']['yield'],
                            ));

                            $bond_trn = $this->Bondtransaction->save($data_Bondtransaction);
                            if (empty($bond_trn)) {
                                $errors = $this->Bondtransaction->validationErrors;
                                error_log("Bondtransaction not saved : " . json_encode($this->Bondtransaction->validationErrors, true));

                                $this->set('errors', array('Bondtransaction' => $errors));
                                $this->set('_serialize', array('errors'));
                            }

                            $this->log_entry('Instruction number ' . $intruction_id . '  created with TRN(S): ' . print_r($bond_trn, true), 'treasury');
                            // generate pdf
                            $pdf_instruction_path = $this->generate_pdf_instruction($bond_trn['Bondtransaction']['tr_number']);

                            // generate limit monitor snapshot
                            try {
                                $data_snapshot = array('Transaction' => array('mandate_ID' => $this->data['Bond']['mandate_ID'], 'cpty_id' => $this->data['Bond']['cpty_id']));
                                $instructionController = new TreasurydepositinstructionsController();
                                $instructionController->constructClasses();
                                @$instructionController->beforeFilter();
                                $total_interest = $instructionController->limitMonitorSnapshot($data_snapshot, null, $this->data['TransactionBond']['settlement_date'], $intruction_id);
                            } catch (Exception $e) {
                                error_log("limitMonitorSnapshot not generated : " . $e->getMessage());
                            }
                            $this->log_entry('Bond transaction ' . $bond_trn['Bondtransaction']['tr_number'] . ' created :' . print_r($bond_trn, true), 'treasury');
                            //$this->set('success', Router::url('/treasury/treasurybonds/trconfirmation/'.$bond_trn['Bondtransaction']['tr_number'],false));
                            $this->redirect("/treasury/treasurybonds/trconfirmation/" . $bond_trn['Bondtransaction']['tr_number']);

                            $this->set('_serialize', array('success'));
                        } else {

                            /*
							 * Edit and save bondtransaction (bond not editable if status 'Confirmed') + update instruction 
							 UNUSED, see before
							 */

                            $save = true;

                            // bond status
                            $bond = $this->Bond->find('first', array('conditions' => array('bond_id' => $this->data['Bond']['bond_id'])));
                            $saveBond = true;
                            if (!empty($bond)) {
                                if ($bond['Bond']['state'] === 'Confirmed') {
                                    $saveBond = false;
                                }
                            }

                            if ($saveBond) {
                                //update vals

                                //
                                $bond = $this->Bond->save($bond);
                            }

                            $bondtransaction = $this->Bondtransaction->find('first', array('conditions' => array('Bondtransaction.tr_number' => $this->data['TransactionBond']['tr_number'])));
                            $bondtransaction['Bondtransaction']['tr_state'] = "Instruction Created"; //
                            $this->Bondtransaction->save($bondtransaction);
                            /*instruction back to Created*/
                            $instr_num = $bondtransaction['Bondtransaction']['instr_num'];
                            $instr = $this->Instruction->find('first', array('conditions' => array('Instruction.instr_num' => $instr_num)));
                            if (!empty($instr)) {
                                $instr['Instruction']['instr_status'] = "Created";
                                $this->Instruction->save($instr);
                            }
                            $bondtransaction = $this->Bondtransaction->save($this->data['TransactionBond']);

                            $this->log_entry('Bond TRN ' . $tr_number . ' corrected : ' . print_r($this->request->data['TransactionBond'], true), 'treasury');
                            $this->log_entry('Instruction ' . $instr_num . ' corrected : ' . print_r($instr['Instruction'], true), 'treasury');
                        }
                        if ($save) {
                        } elseif (!empty($errors)) {
                            $this->set('errors', array('errors' => $errors));
                            $this->set('_serialize', array('errors'));
                        }
                    }
                } else {
                    $errors = $this->Bondtransaction->validationErrors;
                    $this->set('errors', array('Transaction' => $errors));
                    $this->set('_serialize', array('errors'));
                }
            }
        }

        $form_process = array();
        $linenum = 0;
        if (!empty($this->request->data['linenum'])) $linenum = $this->request->data['linenum'];


        if ((empty($errors) || $this->request->data['force']) && !empty($trnum)) {
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
        } elseif (empty($errors) && !empty($this->request->data['checkOnly'])) {
            $form_process['trnum'] = $linenum;
            $form_process['result'] = 'success-check';
            $form_process['result_text'] = 'Line #' . $linenum . ' passed the verification successfully';
        }
        $this->set('form_process', $form_process);
        $this->set('linenum', $linenum);
        $this->set('multiple', true);
        $this->render('newbonds');
    }


    function generate_pdf_instruction($bond_transaction_tr_number)
    {
        @$this->validate_param('int', $bond_transaction_tr_number);
        $pdf_path = false;
        if (isset($bond_transaction_tr_number)) {
            $bond_trn = $this->Bondtransaction->find("first", array('conditions' => array('Bondtransaction.tr_number' => $bond_transaction_tr_number)));
            $instuction_number = $bond_trn['Bondtransaction']['instr_num'];
            $mandate = $this->Mandate->find('first', array('conditions' => array('Mandate.mandate_ID' => $bond_trn['Bondtransaction']['mandate_id'])));
            $bond_trn['Bond']['mandate'] = $mandate['Mandate']['mandate_name'];
            $counterparty = $this->Counterparty->find('first', array('conditions' => array('Counterparty.cpty_ID' => $bond_trn['Bondtransaction']['cpty_id'])));
            $bond_trn['Bond']['counterparty'] = $counterparty['Counterparty']['cpty_name'];
            $this->set(compact('bond_trn'));
            $username = $this->Session->read('UserAuth.User.first_name') . ' ' . $this->Session->read('UserAuth.User.last_name');
            $this->set(compact('username'));

            $view = new View($this);

            /* PDF generation */
            $raw = $view->render('Bonds/instruction_pdf');
            $raw = strstr($raw, '<!-- di -->'); // remove cake styling
            $raw = strstr($raw, '<!-- end di -->', true);

            // write to the database
            $pdf_file = array('Pdf' => array(
                'name' => $instuction_number, // change this to something in a form
                'raw' => base64_encode($raw) // encode the data to save space
            ));

            $this->autoRender = false;
            // get an instance of wkhtmltopdf
            $pdf = new WkHtmlToPdf();

            // decode the database and add the html to the pdf
            $html = base64_decode($pdf_file['Pdf']['raw']);

            $pdf->addPage($html);
            $pdf->setOptions(array('footer-right' => '"Page [page]/[topage]"'));
            $pdf_path = "/var/www/html/data/treasury/pdf/bond_instruction_" . $instuction_number . ".pdf";
            if (!$pdf->saveAs($pdf_path)) {
                $this->Session->setFlash('Could not create PDF: ' . $pdf->getError() . ' Please contact the administrator', 'flash/error');
                return false;
            }
        }
        return $pdf_path;
    }

    public function detail()
    {
        /*filter*/
        $mandate_id_filter = '';
        $issuer_filter = '';
        if (!empty($this->data['filterform']['mandate_id'])) {
            $mandate_id_filter = $this->data['filterform']['mandate_id'];
        }
        if (!empty($this->data['filterform']['issuer'])) {
            $issuer_filter = $this->data['filterform']['issuer'];
        }
        $this->set('mandate_id_filter', $mandate_id_filter);
        $this->set('issuer_filter', $issuer_filter);
        $mandates = $this->Mandate->find('list', array(
            'order' => 'Mandate.mandate_name',
            'conditions' => array("Mandate.mandate_id !=" => 0),
            'fields' => array('mandate_id', 'mandate_name'),
        ));
        $issuer_list = $this->Bond->find('list', array(
            'order' => 'Bond.issuer',
            'conditions' => array('Bond.state' => 'Confirmed'),
            'fields' => array('Bond.issuer', 'Bond.issuer'),
            'group'        => array('Bond.issuer', 'Bond.issuer'),
        ));

        /*saving*/
        if (!empty($this->data)) {
            foreach ($this->data as $post_name => $data) {
                if (strpos($post_name, 'form_update_bond_') !== false) {
                    //saving $this->data[$post]
                    $bond_data = $this->data[$post_name];
                    $bond_data['issue_rating_STP'] = urldecode($bond_data['issue_rating_STP']);
                    $bond_data['issue_rating_MDY'] = urldecode($bond_data['issue_rating_MDY']);
                    $bond_data['issue_rating_FIT'] = urldecode($bond_data['issue_rating_FIT']);
                    $saved = $this->Bond->save($bond_data);
                    if (empty($saved)) {
                        $this->Session->setFlash("Could not save the Bond " . $this->data[$post_name]['bond_id'], "flash/error");
                        error_log("could not save bond detail : " . json_encode($this->data[$post_name], true));
                    } else {
                        $this->Session->setFlash("Bond with ISIN " . $this->data[$post_name]['ISIN'] . " saved", "flash/success");
                        $this->log_entry("update of rating for Bond " . $this->data[$post_name]['bond_id'] . " : " . print_r($this->data[$post_name], true), "treasury");
                    }
                }
            }
        }

        /*ratings : Standar and poor*/
        $values_rating = $this->Rating->getLongTermRatings();
        $issue_rating_STP_values = array();
        $issue_rating_MDY_values = array();
        $issue_rating_FIT_values = array();
        foreach ($values_rating as $rating_val) {
            if (!empty($rating_val['LT-STP'])) {
                $issue_rating_STP_values[urlencode($rating_val['LT-STP'])] = $rating_val['LT-STP'];
            }
            if (!empty($rating_val['LT-MDY'])) {
                $issue_rating_MDY_values[urlencode($rating_val['LT-MDY'])] = $rating_val['LT-MDY'];
            }
            if (!empty($rating_val['LT-FIT'])) {
                $issue_rating_FIT_values[urlencode($rating_val['LT-FIT'])] = $rating_val['LT-FIT'];
            }
        }

        /*possible values*/
        $country_values_db = $this->Bondtransaction->query("SELECT code FROM damsv2.dictionary_values WHERE dictionary_id=17 ORDER BY code ASC");
        $country_values = array();
        foreach ($country_values_db as $cv) {
            $country_values[$cv['dictionary_values']['code']] = $cv['dictionary_values']['code'];
        }
        $covered_values = array("1" => "YES", "0" => "NO");
        $secured_values = array("1" => "YES", "0" => "NO");
        $seniority_values = array("Senior" => "Senior", "TLAC" => "TLAC", "Junior" => "Junior");
        $structured_values = array("1" => "YES", "0" => "NO");
        $issuer_type_values = array("EU & Euratom" => "EU & Euratom", "EU Member State" => "EU Member State", "Government (non EU)" => "Government (non EU)", "Corporate" => "Corporate", "Supranational Institution (EU)" => "Supranational Institution (EU)", "Supranational Institution (non EU)" => "Supranational Institution (non EU)", "Financials" => "Financials", "Agency" => "Agency", "Public Company" => "Public Company");
        $issue_rating_STP_values = $issue_rating_STP_values;
        $issue_rating_MDY_values = $issue_rating_MDY_values;
        $issue_rating_FIT_values = $issue_rating_FIT_values;
        $this->set(compact('mandates', 'issuer_list', 'country_values', 'covered_values', 'secured_values', 'seniority_values', 'structured_values', 'issuer_type_values', 'issue_rating_STP_values', 'issue_rating_MDY_values', 'issue_rating_FIT_values'));

        /*results*/
        $conditions = array("Bond.state" => "Confirmed");
        if (!empty($mandate_id_filter)) {
            $bond_ids = $this->Bondtransaction->find("list", array('conditions' => array('mandate_id' => $mandate_id_filter), 'fields' => 'bond_id'));
            $conditions["Bond.bond_id"] = $bond_ids;
        }
        if (!empty($issuer_filter)) {
            $conditions["Bond.issuer"] = $issuer_filter;
        }
        $this->Paginator->settings = $this->paginate;
        $this->Paginator->settings = array(
            'limit' => 20,
            'conditions' => $conditions,
            'fields'    => '*',
            'recursive'    => 1,
        );
        /*try
		{*/
        $bonds = $this->Paginator->paginate('Bond');
        /*}
		catch(NotFoundException $e)
		{
			$url = $this->request['here'];
			$url = preg_replace('/\/page:[0-9]+/', '', $url);
			$this->Session->setFlash("Please go back to page 1 and then filter the results", "flash/error");
			$this->redirect($url);
		}*/
        $this->set("bonds", $bonds);
    }

    public function get_retained_rating($stp = 'NR', $mdy = 'NR', $fit = 'NR', $plouf)
    {
        @$this->validate_param('string', $stp);
        @$this->validate_param('string', $mdy);
        @$this->validate_param('string', $fit);
        @$this->validate_param('string', $plouf);
        $this->set('result', $this->Bond->compute_retained_rating($stp, $mdy, $fit));
    }

    public function sl_date($date)
    {
        @$this->validate_param('date', $date);
        if (strpos($date, '-') !== false) {
            $dat = explode('-', $date);
            $date = $dat[2] . '/' . $dat[1] . '/' . $dat[0];
        }
        return $date;
    }
}
