<?php

declare(strict_types=1);

namespace Treasury\Console;

App::uses('Shell', 'Console');
App::uses('UniformLib', 'Lib');
App::uses('CurrencyLib', 'Lib');
class MaturityShell extends Shell
{

    public $uses = array('Treasury.Transaction', 'Treasury.Reinvestment', 'Treasury.MandateManager');
    public $tasks = array('ResetOwner');
    public function main()
    {


        $tr_email = array();
        $errors = array();

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
            )
        ));

        $processed = array();
        $processed_muted = array();
        if (sizeof($transactions) > 0) {
            foreach ($transactions as $key => $tr) {
                if ($status =  $this->Transaction->statusAtMaturity($tr['Transaction']['tr_number'])) {
                    $actual_state = $this->Transaction->getAttribByTrn('tr_state', $tr['Transaction']['tr_number']);

                    if ($actual_state != $status) {
                        //treats only transactions without children
                        $childcount = $this->Transaction->find('count', array(
                            'conditions' => array(
                                'OR' => array(
                                    'Transaction.parent_id' => $tr['Transaction']['tr_number'],
                                    'Transaction.linked_trn' => $tr['Transaction']['tr_number'],
                                ),
                                'Transaction.tr_state <>' => 'Deleted',
                            )
                        ));
                        if ($childcount) {
                            $processed_muted[$tr['Transaction']['tr_number']] = $tr['Transaction']['tr_number'];
                        }

                        $processed[$key] = $tr['Transaction']['tr_number'];
                        $this->Transaction->read(null, $tr['Transaction']['tr_number']);
                        $this->Transaction->set('tr_state', $status);
                        $this->Transaction->save();
                    }
                }
            }
        }

        /*  -------------------  */
        /* | Automatic Renewal | */
        /*  -------------------  */
        $trToRenew = $this->Transaction->find('all', array(
            'conditions' => array(
                'tr_number'           => $processed,
                'tr_state'            => 'Renewed',
            ),
            'fields'    => array('tr_number', 'amount', 'original_id', 'parent_id', 'mandate_ID', 'cmp_ID', 'cpty_id', 'accountA_IBAN', 'accountB_IBAN', 'total_interest', 'scheme', 'maturity_date', 'tax_amount', 'depo_renew', 'depo_term', 'rate_type'),
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
            $data = array(
                'Reinvestment' => array(
                    'reinv_status'      => 'Open',
                    'mandate_ID'        => $value['mandate_ID'],
                    'cmp_ID'            => $value['cmp_ID'],
                    'cpty_ID'           => $value['cpty_id'],
                    'availability_date' => $value['maturity_date'],
                    'accountA_IBAN'     => $accounts['accountA_IBAN'],
                    'accountB_IBAN'     => $accounts['accountB_IBAN'],
                    'amount_leftA'      => $amount_leftA,
                    'amount_leftB'      => $amount_leftB,
                    'reinv_type'        => 'Renewal',
                )
            );

            $this->Reinvestment->save($data);
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
                    'original_id'      => $value['original_id'],
                    'parent_id'        => $trn,
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
                    'instr_num'         => $value['instr_num'],
                )
            );
            $this->Transaction->save($data);
            $rollover_id = $this->Transaction->id;

            if ($new_repayment) {
                $this->Transaction->create();
                $data = array('Transaction' => array(
                    'tr_type'           => 'Repayment',
                    'tr_state'          => 'Confirmed',
                    'source_group'      => $reinv_id,
                    'original_id'      => $value['original_id'],
                    'parent_id'        => $trn,
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
        }

        $transactions = $this->Transaction->find('all', array(
            'conditions'    => array('tr_number' => $processed)
        ));
        if (!empty($transactions)) {
            $tr_email = $transactions;
        }

        $tr_per_mandate = array();
        foreach ($transactions as $key => $transaction) {

            //add transactions to $tr_per_mandate only if its not muted (doesnt have children)
            if (!isset($processed_muted[$transaction['Transaction']['tr_number']])) {

                $tr_per_mandate[$transaction['Transaction']['mandate_ID']] =  $this->Transaction->find('all', array(
                    'conditions'    => array(
                        'tr_number' => $processed,
                        'Transaction.mandate_id ' => array($transaction['Transaction']['mandate_ID']),
                    ),
                ));
            }
        }

        /*  -------  */
        /* | Email | */
        /*  -------  */
        if (!empty($tr_per_mandate)) {
            App::uses('CakeEmail', 'Network/Email');
            foreach ($tr_per_mandate as $key => $transaction) {
                if (!empty($transaction)) {
                    $firsttransaction = reset(array_values($transaction));
                    $managerlist = array();
                    if (!empty($firsttransaction['Transaction']['mandate_ID'])) {
                        $managers = $this->MandateManager->find('all', array(
                            'conditions' => array('MandateManager.mandate_ID' => $firsttransaction['Transaction']['mandate_ID']),
                            'recursive' => -1
                        ));
                        if (!empty($managers)) foreach ($managers as $manager) {
                            $managerlist[] = $manager['MandateManager']['email'];
                        }
                    }

                    $subject = 'Treasury: Maturity Report';
                    $prefix = $sufix = $server = '';

                    //detecting the current server
                    $cclist = array('eifsas-support@eif.org');
                    $testserver = true;
                    $prefix = '[TEST] ';
                    if ($this->args[0] == 'dev') {
                        $prefix = '[VMD - TEST] ';
                    } elseif ($this->args[0] == 'uat') {
                        $prefix = '[VMU - TEST] ';
                    } elseif ($this->args[0] == 'prod') {
                        $prefix = '';
                        $testserver = false;
                        $cclist = array('eifsas-support@eif.org', 'eif-treasury@eif.org');
                    }
                    $cclist = array('i.ribassin@eif.org');
                    //subject: append the current mandate name
                    if (isset($firsttransaction['Mandate']['mandate_name'])) {
                        $sufix = " - " . $firsttransaction['Mandate']['mandate_name'];
                    }
                    $subject = $prefix . $subject . $sufix;

                    /* If the Manager Email is stored in Mandate Table then do the following, otherwise change the Model name */
                    $Email = new CakeEmail();
                    $Email->template('Treasury.maturity')
                        ->emailFormat('html')
                        ->from(array('eifsas-support@eif.org' => 'EIFSAS Platform'))
                        ->subject($subject)
                        ->viewVars(
                            array(
                                'transactions' => $transaction
                            )
                        );
                    if (!empty($managerlist) && empty($testserver)) {
                        $Email->to($managerlist);
                        $Email->cc($cclist);
                    } else {
                        //if test server, only send notification to support email
                        if (!empty($testserver)) {
                            $cclist = array('igor.ribassin@eif.org');
                        }

                        $Email->to($cclist);
                        $errors[] = 'No manager found for the mandate #' . $firsttransaction['Transaction']['mandate_ID'] . ' -> sending to CC only';
                    }
                    try {
                        @$Email->send();
                    } catch (Exception $e) {
                        if (!empty($this->params['display'])) print($e->getMessage());
                    }


                    if (!empty($this->params['display'])) $this->out('Mandate #' . $firsttransaction['Transaction']['mandate_ID'] . ': ' . count($managerlist) . 'emails sent to managers + CC');
                }
            }
            //$this->log("Manually maturity: Email sent, ".sizeof($tr_email)." transaction(s)", 'treasury');
        } else {
            $errors[] = 'There is no transaction per mandate';
        }

        $notifications = array();
        if (!empty($this->params['display'])) {
            // Transaction which are First and Second Notification (sent to view) but not necessarily processed
            $notifications = $this->Transaction->find('all', array(
                'conditions'    => array(
                    'tr_state' => array('First Notification', 'Second Notification')
                ),

            ));

            foreach ($notifications as $key => $notif) {
                //remove transactions to display list if it has child
                if (isset($processed_muted[$notif['Transaction']['tr_number']])) {
                    unset($notifications[$key]);
                }
            }
        }

        if (!empty($errors)) foreach ($errors as $error) {
            $this->out($error);
        }

        $event = new CakeEvent('Model.Treasury.Maturity.updated', $this, array());
        $this->Transaction->getEventManager()->dispatch($event);


        $this->ResetOwner->execute();
        return array('transactions' => $transactions, 'processed' => $processed_muted, 'notifications' => $notifications, 'errors' => $errors);
    }

    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addOption('display', array('short' => 'd', 'help' => 'Display in browser mode', 'boolean' => TRUE));
        return $parser;
    }
}
