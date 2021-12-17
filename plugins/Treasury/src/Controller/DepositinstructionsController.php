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
class DepositinstructionsController extends AppController
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
    function createdi($default = null)
    {

        @$this->validate_param('bool', $default);
        /*
         * Set the Mandate field options
         */
        $this->set('mandates_list', $this->Mandate->getMandateList());
        $this->set('displayFunds', isset($default) ? false : true);
        $this->set('defaultInstrType', isset($default) ? array($default => ucfirst($default)) : array('di' => 'Standard'));

        if ($this->request->is('post')) {
            $type        = $this->request->data['Instruction']['type'];
            $preview    = $this->request->data['Instruction']['preview'];
            $funds = '';
            $instr_date = null;
            $commencement_date = null;

            $error = false;

            switch ($type) {
                case 'di':
                    $transactions = $this->Transaction->find('all', array(
                        'conditions' => array(
                            'Transaction.mandate_ID'         => $this->request->data['Transaction']['mandate_ID'],
                            'Transaction.cpty_ID'             => $this->request->data['Transaction']['cpty_id'],
                            'Transaction.tr_state'             => "Created",
                            'Transaction.tr_type'             => array("Deposit", "Rollover", "Repayment"),
                            //'Transaction.parent_id' 		=> NULL,//useless rule, removed for http://vmu-sas-01:8080/browse/TREASURY-427
                            'OR' => array(
                                array('outFromReinv.reinv_status' => NULL),
                                array('outFromReinv.reinv_status' => 'Closed'),
                            ),
                        ),
                        'order' => array('Transaction.commencement_date' => 'ASC'),
                    ));

                    $transactions = $this->clean_transactions_for_di($transactions);

                    if (!empty($transactions)) {
                        $result         = false;
                        foreach ($transactions as $key => &$transaction) {
                            $parent = $this->Transaction->getTransactionById($transaction['Transaction']['original_id']);
                            $commencement_date = $transaction['Transaction']['commencement_date'];
                            if (isset($parent['Transaction'])) {
                                if (in_array($parent['Transaction']['tr_state'], array('Broken', 'Called'))) {
                                    unset($transactions[$key]);
                                }
                            }
                        }
                    }



                    if (!empty($transactions)) {

                        $di_interest       = $transactions;
                        $di_repayments     = array();

                        foreach ($di_interest as &$transaction) {
                            if ($transaction['Transaction']['tr_type'] == "Repayment") {
                                if ($transaction['Transaction']['accountB_IBAN'] != "") {
                                    $transaction['AccountA']['IBAN'] = $transaction['AccountB']['IBAN'];
                                    $transaction['AccountA']['BIC'] = $transaction['AccountB']['BIC'];
                                }
                                $di_repayments[] = $transaction;
                            }
                        }

                        $this->set(compact('di_interest'));
                        $this->set(compact('di_repayments'));
                    } else {
                        $this->Session->setFlash('There are no transactions matching your options', 'flash/error');
                        $error = true;
                    }
                    break;
                case 'call':

                    $transactions = $this->Transaction->find('all', array(
                        'conditions' => array(
                            'Transaction.mandate_ID'     => $this->request->data['Transaction']['mandate_ID'],
                            'Transaction.cpty_ID'         => $this->request->data['Transaction']['cpty_id'],
                            'Transaction.tr_state'         => "Created",
                            'Transaction.tr_type'         => array("Call")
                        ),
                        'order' => array('Transaction.commencement_date' => 'ASC'),
                    ));
                    $transactions = $this->clean_transactions_for_di($transactions);

                    if (!empty($transactions)) {
                        foreach ($transactions as &$transaction) {
                            $origin = $this->Transaction->getTransactionById($transaction['Transaction']['original_id']);

                            if (isset($origin['Transaction']['instr_num'])) {
                                $instr_num = $origin['Transaction']['instr_num'];
                            } else {
                                $instr_num = $transactions[0]['Transaction']['instr_num'];
                            }
                            $transaction['Transaction']['di_tr_number'] = $transaction['Transaction']['tr_number'] . " (" . $transaction['Transaction']['original_id'] . " DI:" . $instr_num . ")";
                        }
                    } else {
                        $this->Session->setFlash('There are no transactions matching your options', 'flash/error');
                        $error = true;
                    }
                    break;
                case 'break':
                    $transactions = $this->Transaction->find('all', array(
                        'conditions' => array(
                            'Transaction.mandate_ID'     => $this->request->data['Transaction']['mandate_ID'],
                            'Transaction.cpty_ID'         => $this->request->data['Transaction']['cpty_id'],
                            'Transaction.tr_state'         => "Created",
                            'Transaction.tr_type'         => array("Withdrawal")
                        ),
                        'order' => array('Transaction.commencement_date' => 'ASC'),
                        'limit' => 1
                    ));
                    $transactions = $this->clean_transactions_for_di($transactions);

                    if (!empty($transactions[0]['Transaction']['maturity_date'])) {
                        $instr_date = $transactions[0]['Transaction']['maturity_date'];
                    }

                    $sisters = array();
                    if (!empty($transactions)) {

                        $origin = $this->Transaction->getTransactionById($transactions[0]['Transaction']['tr_number']);
                        $instr_num = isset($origin['Transaction']['instr_num']) ? $origin['Transaction']['instr_num'] : $transactions[0]['Transaction']['instr_num'];
                        $transactions[0]['Transaction']['di_tr_number'] = $transactions[0]['Transaction']['tr_number'] . " (" . $transactions[0]['Transaction']['original_id'] . " DI:" . $instr_num . ")";

                        $sister = $this->Transaction->findSisterTRN($transactions[0]['Transaction']['tr_number']);
                        if (isset($sister['Transaction'])) {
                            $sister['Transaction']['di_tr_number'] = $sister['Transaction']['tr_number'] . " (" . $sister['Transaction']['original_id'] . " DI:" . $instr_num . ")";
                            $sisters[] = $sister;
                        }
                        $this->set(compact("sisters"));
                    } else {
                        $this->Session->setFlash('There are no transactions matching your options', 'flash/error');
                        $error = true;
                    }
                    break;
            }

            if (empty($error) && !empty($transactions)) {

                if (!$preview) {
                    $trns = array();
                    if (!empty($transactions)) foreach ($transactions as $key => $value) {
                        $trns[] = $value['Transaction']['tr_number'];
                    }

                    $data = array(
                        'instr_status'    => 'Created',
                        'instr_type'    => strtoupper($type),
                        'mandate_ID'     => $this->request->data['Transaction']['mandate_ID'],
                        'cpty_ID'         => $this->request->data['Transaction']['cpty_id'],
                        'notify'         => $this->request->data['Instruction']['notify'],
                        'notify_date'     => $this->request->data['Instruction']['intr_date'],
                        'created_by'    => $this->UserAuth->getUserName(),
                    );

                    //in case of BREAK, the instr_date has to be forced to maturity_date, not default commencement_date
                    if (!empty($instr_date)) {
                        $data['instr_date'] = $instr_date;
                    }

                    $this->Instruction->save($data);
                    $instr_num = $this->Instruction->id;
                    foreach ($trns as $trnumber) {
                        $this->log_entry('Instruction number ' . $instr_num . ' created with TRN ' . $trnumber, 'treasury', $trnumber);
                    }

                    /*if($type == 'di'){
						$this->limitMonitorSnapshot($this->request->data, $transactions, $commencement_date, $instr_num);
					}*/

                    if (!$this->Instruction->id) {
                        $this->Session->setFlash('Error during the creation of the DI, please contact the administrator', 'flash/error');
                        $this->redirect($this->referer());
                    }
                } else {
                    $instr_num = 'Draft';
                }

                $external_refs = array();
                $x5 = '';

                if (!empty($transactions)) foreach ($transactions as &$transaction) {
                    if ($transaction['Transaction']['source_group'] != 1 && $transaction['Counterparty']['cpty_code'] == "BCEE") {
                        $inTrns = $this->Reinvestment->getInOutTrn('in', $transaction['Transaction']['source_group']);
                        // The ideal would be find('list',) but it leads to MySQL error as it selects also tr_number
                        // Select t.tr_number, distinct t.external_ref from treasury.transactions as t where t.tr_number = ..
                        // gives a MySQL error (strange)
                        $extRefs = $this->Transaction->find(
                            'all',
                            array(
                                'fields'    => array('Transaction.external_ref'),
                                'conditions' => array('Transaction.tr_number' => $inTrns),
                                'recursive'    => -1,
                            )
                        );

                        // Format $extRefs otherwise
                        if (sizeof($extRefs) > 0) {
                            foreach ($extRefs as $value) {
                                $external_refs[] = $value['Transaction']['external_ref'];
                            }
                        }
                    }

                    if ($transaction['Transaction']['depo_renew'] == 'Yes') {
                        switch ($transaction['Transaction']['scheme']) {
                            case 'AA':
                            case 'BB':
                                $transaction['Transaction']['depo_renew'] = "Yes<br>(P+I)";
                                break;
                            case 'AB':
                                $transaction['Transaction']['depo_renew'] = "Yes<br>(P)";
                                break;
                        }
                    }

                    $transaction['Transaction']['depo_term'] = ((empty($transaction['Transaction']['depo_term'])) ? '.' : $transaction['Transaction']['depo_term']);

                    if (!$preview) {
                        $this->Transaction->save(array('Transaction' => array(
                            'tr_number' => $transaction['Transaction']['tr_number'],
                            'tr_state' => "Instruction Created",
                            'instr_num' => $instr_num
                        )));
                    }
                }

                if (!$preview && isset($sister['Transaction'])) {
                    $this->Transaction->save(array('Transaction' => array(
                        'tr_number' => $sister['Transaction']['tr_number'],
                        'tr_state' => "Instruction Created",
                        'instr_num' => $instr_num
                    )));
                }
                $external_refs = array_unique($external_refs);
                $x5 = implode(' ', $external_refs);

                if (!empty($x5)) {
                    $x5 = "related to the existing deposit(s) with deal number: " . $x5;
                }
                $this->set(compact('x5'));
                $this->set(compact('transactions'));
                $this->set(compact('instr_num'));

                // PDF generation
                $template = $type;
                $preamble = $attn = $deposits_footer = '';

                // Header date: custom or now
                $headerdate = date('Y-m-d');
                if (!empty($this->request->data['Instruction']['intr_date'])) {
                    $headerdate = $this->request->data['Instruction']['intr_date'];
                }

                //footer text: custom or TM / D
                $funds = '';
                $funds_options = array(
                    "D" => "For New deposits, at Commencement Date, the above funds should be automatically debited from Depositor's account",
                    "TM" => "For New deposits, at Commencement Date, the above funds will be available at Treasury Manager's account",
                );
                $custom_texts = $this->CustomText->find('all', array('conditions' => array('CustomText.cpty_id' => $this->request->data['Transaction']['cpty_id'])));
                foreach ($custom_texts as $custom_t) {
                    $funds_options[$custom_t['CustomText']['custom_id']] = $custom_t['CustomText']['custom_txt'];
                }

                if (!empty($this->request->data['Instruction']['Funds']) && substr($this->request->data['Instruction']['Funds'], 0, 6) != 'CUSTOM') {
                    $funds = $funds_options[$this->request->data['Instruction']['Funds']];
                    if ($this->request->data['Instruction']['Funds'] == 'TM') {
                        $cpty_acc = $this->Counterparty->find('first', array('conditions' => array('cpty_ID' => $this->request->data['Transaction']['cpty_id'])));
                        //debug($cpty_acc);
                        $cpty_account = $this->CounterpartyAccount->find("first", array('conditions' => array(
                            'currency' =>    $transactions[0]['Transaction']['ccy'], //currency of first trn, users normally don't do several currencies
                            'cpty_id' =>    $this->request->data['Transaction']['cpty_id'],
                        )));
                        $iban = $cpty_account['CounterpartyAccount']['account_IBAN'];
                        //$funds = $funds.' '.$cpty_acc['Counterparty']['cpty_acc_bic'].' '.$cpty_acc['Counterparty']['cpty_acc_iban'];
                        $funds = $funds . ' ' . $cpty_acc['Counterparty']['cpty_bic'] . ' ' . $iban;
                    }
                    $deposits_footer = $funds;
                }

                //custom templates & values
                $ditpl = $this->DItemplate->getTemplate($this->request->data['Transaction']['mandate_ID'], $this->request->data['Transaction']['cpty_id']);
                if (!empty($ditpl)) {
                    if (!empty($ditpl['template'])) $template .= '_' . trim($ditpl['template']);
                    if (!empty($ditpl['attn'])) $attn = $ditpl['attn'];

                    //force footer
                    if (!empty($ditpl['deposits_footer']) && !empty($ditpl['footer_force'])) {
                        $this->request->data['Instruction']['Funds'] = 'CUSTOM_0';
                    }

                    if (substr($this->request->data['Instruction']['Funds'], 0, 6) == 'CUSTOM' && !empty($ditpl['deposits_footer'])) {
                        //multiple custom text or simple
                        if ($ditpl['deposits_footer'][0] == '{') {
                            $json = array_values(json_decode($ditpl['deposits_footer'], true));
                            $index = intval(str_replace('CUSTOM_', '', $this->request->data['Instruction']['Funds']));
                            if (!empty($json[$index])) $newfooter = $json[$index];
                        } else {
                            $newfooter = $ditpl['deposits_footer'];
                        }

                        if (!empty($newfooter)) $deposits_footer = $newfooter;
                        else $deposits_footer = '';
                    }
                    if (!empty($ditpl['preamble'])) $preamble = $ditpl['preamble'];
                }

                //setters
                $this->set(array('preamble' => $preamble));
                $this->set(array('attn' => $attn));
                $this->set(array('deposits_footer' => $deposits_footer));
                $this->set(array('headerdate' => $headerdate));
                $this->set('transaction_model', $this->Transaction);

                $view = new View($this);
                $raw = $view->render('Depositinstructions/' . $template . '_pdf');
                $raw = strstr($raw, '<!-- di -->'); // remove cake styling
                $raw = strstr($raw, '<!-- end di -->', true);
                file_put_contents("/var/www/html/data/treasury/pdf/deposit_instruction_" . $instr_num . ".html", $raw);
                $this->autoRender = false;

                // get an instance of wkhtmltopdf
                $pdf = new WkHtmlToPdf();

                $pdf->addPage($raw);
                $pdf->setOptions(array(
                    'footer-right' => '"Page [page]/[topage]"',
                    //'zoom' => 0.75,
                    'disable-smart-shrinking',
                    'page-size' => 'A4',
                    'margin-left' => '1cm',
                    'margin-right' => '1cm',
                    'margin-bottom' => '1.5cm',
                    'margin-top' => '1cm',
                    'dpi' => 300,
                    'user-style-sheet' => WWW . '/php/app/View/Themed/Cakestrap/webroot/css/bootstrap.css',
                ));

                if (!$preview) {
                    if ($type == 'di') {
                        $this->limitMonitorSnapshot($this->request->data, $transactions, $commencement_date, $instr_num);


                        $to_swift = $this->Transaction->find("all", array('conditions' => array('Transaction.instr_num' => $instr_num, 'Transaction.tr_type' => 'Deposit'), 'fields' => array('tr_number', 'tr_type')));

                        if (!empty($to_swift)) {
                            $this->generate_swift($instr_num);
                        }
                    }
                    $pdfpath = WWW . DS . 'data' . DS . 'treasury' . DS . 'pdf' . DS . "deposit_instruction_" . $instr_num . ".pdf";
                    if (!$pdf->saveAs($pdfpath)) {
                        $commmand = 'wkhtmltopdf --footer-right "Page [page]/[topage]" --disable-smart-shrinking --page-size A4 --margin-left 1cm --margin-right 1cm --margin-bottom 1.5cm --margin-top 1cm --dpi 300 --user-style-sheet /var/www/html/php/app/View/Themed/Cakestrap/webroot/css/bootstrap.css /var/www/html/data/treasury/pdf/deposit_instruction_' . $instr_num . '.html  /var/www/html/data/treasury/pdf/deposit_instruction_' . $instr_num . '.pdf';
                        exec($commmand);
                        error_log("pdf generated using " . $commmand);
                        if (file_exists('/var/www/html/data/treasury/pdf/deposit_instruction_' . $instr_num . '.pdf')) {
                            $this->Session->setFlash('The Deposit Instruction number <strong>' . $instr_num . '</strong> has been created', 'flash/success');
                            $this->redirect($this->referer());
                        } else {
                            $this->Session->setFlash('Could not create PDF: ' . $pdf->getError() . ' Please contact the administrator', 'flash/error');
                            $this->redirect($this->referer());
                        }
                    } else {
                        $this->Session->setFlash('The Deposit Instruction number <strong>' . $instr_num . '</strong> has been created', 'flash/success');
                        $this->redirect($this->referer());
                    }
                } else {
                    if (!$pdf->send("Deposit_Instruction_Draft_" . time() . ".pdf")) {
                        $this->Session->setFlash('Could not create PDF: ' . $pdf->getError(), 'flash/error');

                        /*
						$this->Instruction->saveField('instr_status', 'Rejected');
						$transactions = $this->Transaction->find('all', array("conditions"=> array(
							'Transaction.instr_num' => $instr_num,
						)));

						foreach ($transactions as &$transaction) {
							$transaction['Transaction']['instr_num'] = '0';
							$transaction['Transaction']['tr_state'] = 'Created';
						}

						$this->Transaction->saveMany($transactions);
*/
                        $this->redirect($this->referer());
                    }
                }
            }
        }
    }


    public function limitMonitorSnapshot($data, $transactions, $commencement_date, $instr_num)
    {

        @$this->validate_param('array', $data);
        @$this->validate_param('array', $transactions);
        @$this->validate_param('date', $commencement_date);
        @$this->validate_param('int', $instr_num);

        $mandate_ID = $data['Transaction']['mandate_ID'];
        $cpty_id = $data['Transaction']['cpty_id'];

        $mandateGroups = array();
        $limits = array();
        $mandateGroups = $this->Mandate->getGroupsIDsByMandate($mandate_ID);

        $date = $commencement_date;
        $commencement_date = UniformLib::format_date_for_request($commencement_date);
        $cpty = $this->Counterparty->find(
            'first',
            array(
                'fields' => array('cpty_name'),
                'recursive'    => -1,
                'conditions' => array('cpty_ID ' => $cpty_id)
            )
        );
        $mandate = $this->Mandate->find(
            'first',
            array(
                'fields' => array('mandate_name'),
                'recursive'    => -1,
                'conditions' => array('mandate_ID ' => $mandate_ID)
            )
        );
        $MandateGroup = ClassRegistry::init('Treasury.MandateGroup');
        if (empty($mandateGroups)) {
            $this->Session->setFlash("No Portfolio for mandate " . $mandate["Mandate"]['mandate_name'], 'flash/error');
            $this->redirect($this->referer());
            die();
        }
        //limits based on date & portfolio
        foreach ($mandateGroups as $mandateGroup) {
            $limits_tmp = $this->Limit->getByCounterparties($commencement_date, $mandateGroup['id']);
            $mandateGroupName = $mandateGroup['mandategroup_name'];
            $mandate_group_id = $mandateGroup['id'];

            if (!empty($limits_tmp)) {
                $limits = $limits_tmp;
                break;
            }
        }

        if (strpos($date, '/') !== false) {
            $date_expl = explode('/', $date);
            $date = $date_expl[2] . '-' . $date_expl[1] . '-' . $date_expl[0];
        }
        if (!empty($limits['portfolioSize'])) {
            $portfolioSize = $limits['portfolioSize'];
        } else {
            $MandateGroup = ClassRegistry::init('Treasury.MandateGroup');
            $portfolioSize =  $portfolioSize = $MandateGroup->getSize($mandate_group_id, $date);
        }
        $portfolioConcentrationUnit = $MandateGroup->getConcentrationUnit($mandate_group_id, $portfolioSize);
        $portfolioMaxConcentration = $MandateGroup->getMaxConcentration($mandate_group_id, $portfolioConcentrationUnit, $portfolioSize);

        $portfolio_concentration_key = 'portfolio_concentration';
        $ctpy_concentration_key = 'portfolio_concentration';
        $portfolio_concentration_suffix = ' EUR';
        if ($portfolioConcentrationUnit == 'PCT') {
            $portfolioMaxConcentration *= 100;
        } elseif ($portfolioConcentrationUnit == 'NA') {
            $portfolioMaxConcentration = "N/A";
            $portfolio_concentration_suffix = "";
            //$portfolioMaxConcentration *= 100;
        }

        if (!empty($portfolioConcentrationUnit) && ($portfolioConcentrationUnit == 'PCT' || $portfolioConcentrationUnit == 'NA')) {
            $portfolio_concentration_key .= '_pct';
            $portfolio_concentration_suffix = '%';
        }

        //get trn for the mandate/cpty and commencement_date
        $transactions = $this->Transaction->getOSByMandateGroupAndCpty($mandateGroup['id'], $cpty_id, $commencement_date);

        //get Limit from cpty
        //$limitByCpty = $this->Limit->get_bymandatetroup_cpty_date($mandateGroup['id'], $cpty_id, $commencement_date);//unused

        $limit = $this->Limit->find(
            'first',
            array(
                'conditions' => array(
                    'mandategroup_ID' => $mandate_group_id,
                    'cpty_ID' => $cpty_id,
                    'limit_date_to' => null
                ),
                'recursive'    => -1,
            )
        );

        $cptyConcentrationLimit = null;
        if (!empty($limit)) {
            if ($limit['Limit']['concentration_limit_unit'] == 'PCT') {
                $ctpy_concentration_key = '_pct';
                $cptyConcentrationLimit = sprintf("%.2f", $limit['Limit']['max_concentration'] * 100) . '%';
            } elseif ($limit['Limit']['concentration_limit_unit'] == 'ABS') {
                $cptyConcentrationLimit = $limit['Limit']['max_concentration'] . ' EUR';
            } else {
                //no limit
                $cptyConcentrationLimit = "N/A";
                if ($portfolioConcentrationUnit == 'PCT') {
                    $ctpy_concentration_key .= '_pct';
                }
            }
        } else {
            $cptyConcentrationLimit = "N/A";
        }

        $this->set(compact(
            'commencement_date',
            'mandateGroupName',
            'limits',
            'portfolioSize',
            'portfolioMaxConcentration',
            'portfolioConcentrationUnit',
            'portfolio_concentration_key',
            'portfolio_concentration_suffix',
            'cpty',
            'instr_num',
            'mandate',
            'transactions',
            'cptyConcentrationLimit',
            'ctpy_concentration_key'
        ));

        $view = new View($this);
        $raw = $view->render('/Depositinstructions/limit_breach_pdf');
        $raw = strstr($raw, '<!-- limit_breach -->'); // remove cake styling
        $raw = strstr($raw, '<!-- end limit_breach -->', true);
        $this->autoRender = false;


        // get an instance of wkhtmltopdf
        $pdf = new WkHtmlToPdf();

        $pdf->addPage($raw);
        $pdf->setOptions(array(
            'footer-right' => '"Page [page]/[topage]"',
            //'zoom' => 0.75,
            'disable-smart-shrinking',
            'page-size' => 'A4',
            'margin-left' => '1cm',
            'margin-right' => '1cm',
            'margin-bottom' => '1cm',
            'margin-top' => '1cm',
            'dpi' => 300,
            'user-style-sheet' => WWW . '/php/app/View/Themed/Cakestrap/webroot/css/bootstrap.css',
            'orientation' => 'Landscape'
        ));

        $pdfpath = WWW . DS . 'data' . DS . 'treasury' . DS . 'pdf' . DS . "limit_breach_" . $instr_num . ".pdf";
        $htmlpath = WWW . DS . 'data' . DS . 'treasury' . DS . 'pdf' . DS . "limit_breach_" . $instr_num . ".html";
        $f = fopen($htmlpath, 'w');
        fputs($f, $raw);
        fclose($f);
        $pdf->saveAs($pdfpath);

        if (!file_exists($pdfpath)) {
            $commmand = 'wkhtmltopdf --footer-right "Page [page]/[topage]" --disable-smart-shrinking --page-size A4 --margin-left 1cm --margin-right 1cm --margin-bottom 1cm --margin-top 1cm --dpi 300 --orientation landscape --user-style-sheet /var/www/html/php/app/View/Themed/Cakestrap/webroot/css/bootstrap.css ' . $htmlpath . '  ' . $pdfpath . ' > /dev/null ';
            exec($commmand);
            error_log("pdf generated using " . $commmand);
            if (file_exists('/var/www/html/data/treasury/pdf/limit_breach_' . $instr_num . '.pdf')) {
                //$this->Session->setFlash('The Deposit Instruction number <strong>'.$instr_num.'</strong> has been created', 'flash/success');
                //$this->redirect($this->referer());
                error_log("limit monitor snapshot created at /var/www/html/data/treasury/pdf/limit_breach_" . $instr_num . ".pdf");
            } else {
                $this->Session->setFlash('Could not create Limit monitor snapchot PDF: ' . $pdf->getError() . ', Please contact the administrator', 'flash/error');
                error_log('Could not create Limit monitor snapchot PDF: ' . $pdf->getError() . ', Please contact the administrator');
                //$this->redirect($this->referer());
            }
        }
    }

    private function clean_transactions_for_di($transactions)
    {

        @$this->validate_param('array', $transactions);
        //eximbanka hack: keep only ON, 3M, 6M
        /*if(!empty($this->request->data['Transaction']['cpty_id']) && $this->request->data['Transaction']['cpty_id']==EXIMBANKA_ID){
			foreach($transactions as $key=>&$transaction){
				if(strtolower($transaction['Transaction']['tr_type'])!='repayment' && !in_array($transaction['Transaction']['depo_term'], array('ON', '3M', '6M'))){
					unset($transactions[$key]);
				}
			}
		}*/

        // Keep only transactions in first date. Queue the others
        $firstdate = null;
        foreach ($transactions as $key => &$transaction) {
            if (empty($firstdate)) {
                if (!empty($transaction['Transaction']['commencement_date'])) {
                    $tmstp = strtotime($this->clean_date($transaction['Transaction']['commencement_date']));
                    $firstdate = date('Ymd', $tmstp);
                    break;
                }
            }
        }

        if (!empty($firstdate)) foreach ($transactions as $key => &$transaction) {
            $date = '';
            if (!empty($transaction['Transaction']['commencement_date'])) {
                $tmstp = strtotime($this->clean_date($transaction['Transaction']['commencement_date']));
                $date = date('Ymd', $tmstp);
            }

            if ($date != $firstdate) {
                unset($transactions[$key]);
            }
        }

        //hack for eximbanka and call and break instruction
        if ($this->request->data['Transaction']['cpty_id'] == EXIMBANKA_ID && $this->request->data['Instruction']['type'] != 'di') {
            $transactions = array();
        }
        return $transactions;
    }

    function validatedi()
    {

        $instructions = $this->Instruction->find(
            'all',
            array(
                'conditions' => array('Instruction.instr_status'    => 'Created'),
                'recursive'    => 1,
                'order' => array('Instruction.instr_num' => 'DESC')
            )
        );

        $instr = array();
        foreach ($instructions as $key => $value) {
            $instr[$value['Instruction']['instr_num']] = array(
                "type"                 => $value['Instruction']['instr_type'],
                "mandate_name"         => $value['Mandate']['mandate_name'],
                "cpty_name"            => $value['Counterparty']['cpty_name'],
                "created_by"        => $value['Instruction']['created_by'],
                "created"            => $value['Instruction']['created'],
            );
            if ($instr[$value['Instruction']['instr_num']]["type"] == 'DI') {
                $instr[$value['Instruction']['instr_num']]["type"] = "Deposit";
            }
            $instr[$value['Instruction']['instr_num']]['trn'] = array();
            if (!empty($value['Transactions'])) {
                foreach ($value['Transactions'] as $trn) {
                    $instr[$value['Instruction']['instr_num']]['trn'][] = $trn['tr_number'];
                }
            }
        }

        $this->set(compact('instr'));
    }


    function action_di($action, $id, $redirect = false)
    {
        /* TODO: check the user rights */

        @$this->validate_param('string', $action);
        @$this->validate_param('int', $id);
        @$this->validate_param('string', $redirect);
        $instruction = $this->Instruction->find("first", array('conditions' => array("Instruction.instr_num" => $id), 'fields' => array('*')));

        switch ($action) {
            case 'validate':
                $this->Instruction->id = $id;
                $this->Instruction->read(null, $id);
                /*$instruction['Instruction']['instr_num'] = $id;
				$instruction['Instruction']['instr_status'] = 'Sent';
				$instruction['Instruction']['timestamp_validated'] = date('Y-m-d H:i:s');
				$instruction['Instruction']['validated_by'] = $this->UserAuth->getUserName();*/
                $this->Instruction->set('instr_status', 'Sent');
                $this->Instruction->set('timestamp_validated', date('Y-m-d H:i:s'));
                $this->Instruction->set('validated_by', $this->UserAuth->getUserName());
                //$oo = $this->Instruction->save($instruction);
                $this->Instruction->save();
                if ($instruction['Instruction']['instr_type'] == "Bond") {
                    $BondTransac = $this->Bondtransaction->find("first", array('conditions' => array("Bondtransaction.instr_num" => $id), 'recursive' => 1));
                    //error_log("bond transaction = ".json_encode($BondTransac, true));
                    $bond_already_confirmed = false;
                    $bond = $this->Bond->find("first", array('conditions' => array("Bond.bond_id" => $BondTransac['Bondtransaction']['bond_id']), 'recursive' => 1));
                    if ($bond["Bond"]["state"] == "Confirmed") {
                        $bond_already_confirmed = true;
                        $bond_data = $bond;
                    } else {
                        $bond["Bond"]["state"] = "Confirmed";
                        $bond_data = $this->Bond->save($bond);
                    }
                    if (empty($bond_data)) {
                        error_log("validate di bond : not saved : " . json_encode($bond_data, true));
                    }
                    /*creation of coupon_payment*/
                    $params = array(
                        "bond_id" => $bond_data['Bond']['bond_id'],
                        "save" => true
                    );

                    $sasResult = $this->SAS->curl("bonds_accrued_calculation.sas", $params, false);

                    // TODO : deal with sas response (error/success)

                    $this->Bondtransaction->updateAll(
                        array('Bondtransaction.tr_state' => "'Confirmed'"),
                        array('Bondtransaction.instr_num' => $id)
                    );
                    if (!$bond_already_confirmed) {
                        $this->log_entry('Bond ' . $bond_data['Bond']['bond_id'] . ' confirmed', 'treasury');
                    }
                    $this->log_entry('Bond TRN ' . $BondTransac['Bondtransaction']['tr_number'] . ' confirmed : ' . print_r($BondTransac['Bondtransaction'], true), 'treasury');

                    /*$this->Bond->updateAll(
						array('Bond.tr_state' => "'Confirmed'"),
						array('Bondtransaction.instr_num' => $id)
					);*/
                } else {
                    /*
					//in create DI now
					$to_swift = $this->Transaction->find("all", array('conditions' => array('Transaction.instr_num' => $id, 'Transaction.tr_type' => 'Deposit'), 'fields' => array('tr_number', 'tr_type')));

					if (!empty($to_swift))
					{
						$this->generate_swift($id);
					}*/
                    $update = $this->Transaction->updateAll(
                        array('Transaction.tr_state' => "'Instruction Sent'"),
                        array('Transaction.instr_num' => $id)
                    );
                }
                $this->log_entry('Instruction number ' . $id . ' validated', 'treasury', $id);
                break;
            case 'reject':
                $instruction['Instruction']['instr_status'] = 'Rejected';
                $instruction['Instruction']['timestamp_validated'] = date('Y-m-d H:i:s');
                $instruction['Instruction']['validated_by'] = $this->UserAuth->getUserName();
                $this->Instruction->save($instruction);

                if ($instruction['Instruction']['instr_type'] == "Bond") {
                    /*$bond = $this->Bond->find("first", array('conditions'=>array("Bond.instr_num"=>$id)));
					$bond["Bond"]["Status"] = "Created";
					$this->Bond->save($bond);*/
                    $this->Bondtransaction->updateAll(
                        array('Bondtransaction.tr_state' => "'Created'"),
                        array('Bondtransaction.instr_num' => $id)
                    );
                    $bondtrn = $this->Bondtransaction->find("first", array('conditions' => array("Bondtransaction.instr_num" => $id), 'recursive' => 1));
                    //Bond ID_BOND rejected : {BOND DATA}
                    //Bond Transaction rejected : {BOND_TRANSACTION_DATA}
                    //$this->log_entry('Bond '.$bondtrn['Bond']['bond_id'].' status updated '': '.print_r($bondtrn['Bond'], true), 'treasury');
                    $this->log_entry('Instruction number ' . $id . ' rejected', 'treasury');
                    $this->log_entry('Bond TRN ' . $bondtrn['Bondtransaction']['tr_number'] . ' changed to \'Created\'', 'treasury');
                    /*if (!empty($bondtrn['Bond']))
					{
						if ($bondtrn['Bond']['state'] !== "Confirmed")
						{
							
						}
					}*/
                    /*$this->Bond->updateAll(
						array('Bond.tr_state' => "'Created'"),
						array('Bondtransaction.instr_num' => $id)
					);*/
                } else {
                    $transactions = $this->Transaction->find('all', array("conditions" => array(
                        'Transaction.instr_num' => $id,
                    )));

                    if (!empty($transactions)) {
                        foreach ($transactions as &$transaction) {
                            $transaction['Transaction']['instr_num'] = '0';
                            $transaction['Transaction']['tr_state'] = 'Created';
                        }
                        $trn_saved = $this->Transaction->saveMany($transactions);
                        if (!empty($trn_saved)) {
                            foreach ($trn_saved as $trn) {
                                $this->log_entry('Instruction number ' . $id . ' rejected', 'treasury', $trn['Transaction']['tr_number']);
                            }
                        }
                    }
                }

                break;
            default:
        }
        if (empty($redirect)) {
            $this->redirect("/treasury/treasurydepositinstructions/validatedi");
        } else {
            switch ($redirect) {
                case 'disp':
                    $this->redirect("/treasury/treasurydepositinstructions/displaydi");
                    break;
                default:
                    $this->redirect("/treasury/treasurydepositinstructions/validatedi");
            }
        }
    }

    function callvalidatedi($instr_num)
    {
        /*
		@$this->validate_param('int', $instr_num);
		$sasResult = $this->SAS->curl("F_ValidateDepositInstruction.sas", array("instr_num=".$instr_num,"DIValidate=yes"),false);

		$this->set('sas', utf8_encode($sasResult));
		$this->set('tables', $this->SAS->get_all_tables_from_webout (utf8_encode($sasResult)));*/
    }

    function callrejectdi($instr_num)
    {

        @$this->validate_param('int', $instr_num);

        $sasResult = $this->SAS->curl("F_ValidateDepositInstruction.sas", array("instr_num" => $instr_num, "DIValidate=no"), false);

        $this->set('sas', utf8_encode($sasResult));
        $this->set('tables', $this->SAS->get_all_tables_from_webout(utf8_encode($sasResult)));
    }

    function displaydi()
    {

        //user auth

        $user_groups = array();
        if ($groups = $this->Session->read('UserAuth.UserGroups')) {
            foreach ($groups as $group) {
                $user_groups[$group['alias_name']] = $group['name'];
            }
        }
        $this->set('user_groups', $user_groups);

        //--- CONFIRMATION UPLOAD ---


        //--- signed DI UPLOAD ---
        if ($this->request->is('post') && !empty($this->request->data['confirmation']['instr_num'])) {
            $file_uploaded = $this->request->data['confirmation']['file_uploaded'];
            $file_field = '';
            $date_field = '';
            $user_field = '';
            $file_description = '';
            switch ($file_uploaded) {
                case 'confirmation': {
                        $file_field = 'confirmation_file';
                        $date_field = 'confirmation_date';
                        $user_field = 'confirmation_by';
                        $file_description = 'confirmation';
                        break;
                    }
                case 'signedDI': {
                        $file_field = 'signedDI_file';
                        $date_field = 'signedDI_date';
                        $user_field = 'signedDI_by';
                        $file_description = 'signedDI';
                        break;
                    }
                case 'trade_request': {
                        $file_field = 'traderequest_file';
                        $date_field = 'traderequest_date';
                        $user_field = 'traderequest_by';
                        $file_description = 'trade_request';
                        break;
                    }
                default: {
                        $this->Session->setFlash("The confirmation file has a wrong type #" . $this->request->data['confirmation']['instr_num'], 'flash/error');
                        $this->redirect('displaydi');
                    }
            }
            $file = $this->request->data['confirmation']['attachment'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $file['name'] = 'deposit_instruction_' . $this->request->data['confirmation']['instr_num'] . '_' . $file_description . '_' . time() . '.' . $ext;
            $fileMovingPath = WWW . DS . 'data' . DS . 'treasury' . DS . 'pdf' . DS . $file['name'];

            if ($this->File->checkFileInForm($file, $fileMovingPath, array())) {
                $instr = $this->Instruction->save(array('Instruction' => array(
                    'instr_num'    => $this->request->data['confirmation']['instr_num'],
                    $file_field => $file['name'],
                    $date_field => date('Y-m-d H:i:s'),
                    $user_field => $this->UserAuth->getUserName()
                )));

                $this->Session->setFlash("The confirmation file has been successfully attached to the Deposit Instruction #" . $this->request->data['confirmation']['instr_num'], 'flash/success');
                $this->redirect('displaydi');
            }
        }

        //--- CONFIRMATION REMOVE ---
        if ($this->request->is('post') && !empty($this->request->data['remove_confirmation']['instr_num'])) {
            $instr = $this->Instruction->read(null, $this->request->data['remove_confirmation']['instr_num']);
            if (!empty($instr['Instruction']['confirmation_file'])) {

                $file_uploaded = $this->request->data['remove_confirmation']['file_uploaded'];
                $file_field = '';
                $date_field = '';
                $user_field = '';
                $file_description = '';
                switch ($file_uploaded) {
                    case 'confirmation': {
                            $file_field = 'confirmation_file';
                            $date_field = 'confirmation_date';
                            $user_field = 'confirmation_by';
                            $file_description = 'confirmation';
                            break;
                        }
                    case 'signedDI': {
                            $file_field = 'signedDI_file';
                            $date_field = 'signedDI_date';
                            $user_field = 'signedDI_by';
                            $file_description = 'signed DI';
                            break;
                        }
                    case 'trade_request': {
                            $file_field = 'traderequest_file';
                            $date_field = 'traderequest_date';
                            $user_field = 'traderequest_by';
                            $file_description = 'trade request';
                            break;
                        }
                    default: {
                            $this->Session->setFlash("The confirmation file has a wrong type #" . $this->request->data['confirmation']['instr_num'], 'flash/error');
                            $this->redirect('displaydi');
                        }
                }



                $file = $instr['Instruction']['confirmation_file'];
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                $filepath = WWW . DS . 'data' . DS . 'treasury' . DS . 'pdf' . DS . $file;

                $instr = $this->Instruction->save(array(
                    $file_field => '',
                    $date_field => date('Y-m-d H:i:s'),
                    $user_field => $this->UserAuth->getUserName(),
                ));

                @unlink($filepath);

                $this->Session->setFlash("The " . $file_description . " file has been successfully removed for the Deposit Instruction #" . $instr['Instruction']['instr_num'], 'flash/success');
                $this->redirect('displaydi');
            }
        }

        //--- DISPLAY LIST
        //FILTERS POPULATE
        $this->set('instr_types', array('DI' => 'DI', 'CALL' => 'CALL', 'BREAK' => 'BREAK', 'BOND' => 'BI'));
        $this->set('instr_mandates', $this->Mandate->getMandateList());
        $this->set('instr_counterparties', $this->Counterparty->getCounterpartyList());

        $months = array();
        for ($i = 1; $i <= 12; $i++) $months[$i] = $i;
        $this->set(compact('months'));

        $years = array();
        for ($i = date('Y'); $i >= 2012; $i--) $years[$i] = $i;
        $this->set(compact('years'));

        //FILTERS UPDATE
        if ($this->request->is('post')) {
            $this->request->params['named']['page'] = 1;
            $this->Session->write('Form.data', $this->request->data);
        }
        $conditions = array();
        if ($this->Session->read('Form.data.Instruction.instr_num'))
            $conditions['Instruction.instr_num'] = $this->Session->read('Form.data.Instruction.instr_num');
        if ($this->Session->read('Form.data.Instruction.instr_type'))
            $conditions['Instruction.instr_type'] = $this->Session->read('Form.data.Instruction.instr_type');
        if ($this->Session->read('Form.data.Instruction.mandate_ID'))
            $conditions['Instruction.mandate_ID'] = $this->Session->read('Form.data.Instruction.mandate_ID');
        if ($this->Session->read('Form.data.Instruction.cpty_ID'))
            $conditions['Instruction.cpty_ID'] = $this->Session->read('Form.data.Instruction.cpty_ID');
        if ($this->Session->read('Form.data.Instruction.instr_date_month'))
            $conditions['MONTH(Instruction.instr_date)'] = $this->Session->read('Form.data.Instruction.instr_date_month');
        if ($this->Session->read('Form.data.Instruction.instr_date_year'))
            $conditions['YEAR(Instruction.instr_date)'] = $this->Session->read('Form.data.Instruction.instr_date_year');

        //RESULTS
        $this->Paginator->settings = $this->paginate;
        $this->Paginator->settings = array(
            'limit' => 20,
            'conditions' => array_merge($conditions, array()),
            'order' => array('Instruction.instr_num' => 'DESC')
        );

        $this->User->bindModel(array('hasOne' => array('History')));
        $instructions = $this->Paginator->paginate('Instruction');

        //check if all the transactions in the instr are confirmed (in order to validate or not)
        foreach ($instructions as &$instruction) {
            $needvalidate = false;
            $needconfirm = false;

            //tr_type filter, depending on the instruction instr_type (deposit/rollover, break, call)
            $typefilter = array('rollover', 'deposit');
            if (!empty($instruction['Instruction']['instr_type'])) {
                if (strtolower($instruction['Instruction']['instr_type']) == 'break') {
                    $typefilter = array('withdrawal');
                } elseif (strtolower($instruction['Instruction']['instr_type']) == 'call') {
                    $typefilter = array('call');
                }
            }

            //does the instruction have transaction awaiting for confirmation/validation?
            if (!empty($instruction['Transactions'])) foreach ($instruction['Transactions'] as $tr) {
                //print '<br>'.$instruction['Instruction']['instr_num'].'-'.$tr['tr_number'].':'.$tr['tr_state'];
                if (strtolower($tr['tr_state']) == 'confirmation received' && in_array(strtolower($tr['tr_type']), $typefilter)) {
                    $needvalidate = true;
                }
                if (strtolower($tr['tr_state']) == 'instruction sent' && in_array(strtolower($tr['tr_type']), $typefilter)) {
                    $needconfirm = true;
                }
            }

            $instruction['Instruction']['trn_need_validation'] = $needvalidate;
            $instruction['Instruction']['trn_need_confirmation'] = $needconfirm;
        }

        $this->set(compact('instructions'));
    }

    //calendar of deposits
    public function calendar($year = null, $month = null, $mandate = null)
    {

        @$this->validate_param('int', $year);
        @$this->validate_param('int', $month);
        @$this->validate_param('int', $mandate);
        if (empty($year)) $year = date('Y');
        if (empty($month)) $month = date('m');

        if (!empty($this->request->data['Instruction']['month'])) $month = $this->request->data['Instruction']['month'];
        if (!empty($this->request->data['Instruction']['year'])) $year = $this->request->data['Instruction']['year'];
        if (!empty($this->request->data['Instruction']['mandate_ID'])) $mandate = $this->request->data['Instruction']['mandate_ID'];

        $this->set(compact('year'));
        $this->set(compact('month'));
        $this->set(compact('mandate'));

        // Mandates List
        $this->set('mandates_list', $this->Mandate->getMandateList());

        // Year List
        $year_list = array();
        for ($i = date('Y') + 2; $i >= 2000; $i--) {
            $year_list[$i] = $i;
        }
        $this->set(compact('year_list'));


        // Month List
        $month_list = array();
        for ($i = 1; $i <= 12; $i++) {
            $month_list[$i] = date('F', strtotime('2013-' . $i . '-01'));
        }
        $this->set(compact('month_list'));

        // Transactions
        $conditions = array(
            'Transaction.mandate_ID <>' => 0,
            'OR' => array(
                //array('MONTH(Transaction.maturity_date)'=>$month, 'YEAR(Transaction.maturity_date)'=>$year),
                array('MONTH(Transaction.indicative_maturity_date)' => $month, 'YEAR(Transaction.indicative_maturity_date)' => $year)
            ),
            'tr_type' => array('Deposit', 'Rollover'),
            'NOT' => array('tr_state' => array('Created', 'Instruction Created', 'Instruction Sent', 'Matured', 'Reinvested', 'Deleted'))
        );
        if (!empty($mandate)) $conditions['Transaction.mandate_ID'] = $mandate;

        $transactions = $this->Transaction->find('all', array(
            'conditions' => $conditions,
            'order' => array('Transaction.indicative_maturity_date' => 'ASC', 'Transaction.maturity_date' => 'ASC'),
        ));

        //remove transactions having a child
        if (!empty($transactions)) foreach ($transactions as $key => &$trn) {
            $childcount = $this->Transaction->find('count', array(
                'conditions' => array(
                    'OR' => array(
                        'Transaction.parent_id' => $trn['Transaction']['tr_number'],
                        'Transaction.linked_trn' => $trn['Transaction']['tr_number'],
                    ),
                    'Transaction.tr_state <>' => 'Deleted',
                )
            ));
            if ($childcount) {
                unset($transactions[$key]);
            } else {
                if ((!empty($trn['Transaction']['indicative_maturity_date'])) && (strpos($trn['Transaction']['indicative_maturity_date'], '-') !== false)) {
                    $date = explode('-', $trn["Transaction"]['indicative_maturity_date']);
                    $new_tr["Transaction"]['indicative_maturity_date'] = $date[2] . '/' . $date[1] . '/' . $date[0];
                }
            }
        }

        // BONDS : 
        $conditions_bond = array(
            'NOT' => array('Bondtransaction.tr_state' => array('Created', 'Deleted')),
            'Bondtransaction.mandate_ID <>' => 0, 'tr_type' => 'Bond',
            array('MONTH(Bond.maturity_date)' => $month, 'YEAR(Bond.maturity_date)' => $year)
        );
        if (!empty($mandate)) $conditions_bond['Bondtransaction.mandate_ID'] = $mandate;
        $bondTransactions = $this->Bondtransaction->find('all', array(
            'conditions' => $conditions_bond,
            'order' => array('Bond.maturity_date' => 'ASC'),
        ));
        if (!empty($bondTransactions)) foreach ($bondTransactions as $key => &$trn) {
            $tr_number = $trn['Bondtransaction']['tr_number'];
            $children = $this->Bondtransaction->isParentOf($tr_number);

            if (!empty($children)) {
                unset($bondTransactions[$key]);
            } else {
                //compatibility with transactions
                $new_tr = array("Transaction" => $trn['Bondtransaction']);
                $new_tr["Transaction"]['maturity_date'] = $trn['Bond']['maturity_date'];
                if (strpos($new_tr["Transaction"]['maturity_date'], '-') !== false) {
                    $date = explode('-', $new_tr["Transaction"]['maturity_date']);
                    $new_tr["Transaction"]['maturity_date'] = $date[2] . '/' . $date[1] . '/' . $date[0];
                }
                $new_tr["Transaction"]['amount'] = $trn['Bondtransaction']['nominal_amount'];
                $new_tr['AccountA'] = array('ccy' => $trn['Bondtransaction']['currency']);
                $new_tr["Transaction"]['tax_amount'] = $trn['Bondtransaction']['total_tax'];
                $new_tr["Transaction"]['total_interest'] = $trn['Bondtransaction']['coupon_payment_amount'];
                $new_tr["Mandate"] = $trn['Mandate'];
                $new_tr["Compartment"] = $trn['Compartment'];
                $transactions[] = $new_tr;
            }
        }
        $this->set(compact('transactions'));
    }

    // parse all instructions to update 'instr_date'
    public function updateAllDates($emptyonly = false)
    {

        @$this->validate_param('bool', $emptyonly);
        $conditions = array();
        if (!empty($emptyonly)) $conditions[] = array('instr_date' => '0000-00-00');
        $instr = $this->Instruction->find('all', array(
            'conditions' => $conditions,
            'limit' => 0,
            'order' => array('Instruction.instr_num' => 'DESC')
        ));
        $this->set('msg', 'updateAllDate: OK (' . count($instr) . ' items)');
        $this->render('/message');
    }

    //test Shell DINotification
    public function shell()
    {
        $command = 'treasury.instruction_notification -d';
        $args = explode(' ', $command);

        $dispatcher = new ShellDispatcher($args, false);

        $dispatcher->dispatch();
        die();
    }


    // TREASURY 164
    private function generate_swift($instr_num)
    {

        @$this->validate_param('int', $instr_num);
        $result = false;
        $instruction = $this->Instruction->find(
            'all',
            array(
                'conditions' => array('instr_num' => $instr_num)
            )
        );
        $zip = null;
        $success_zip = null;
        $trns = $this->Transaction->find("all", array('conditions' => array('Transaction.instr_num' => $instr_num, 'Transaction.tr_type' => 'Deposit')));
        $path_tmp = "/var/www/html/data/treasury/swift/temp/";
        $path_arch = "/var/www/html/data/treasury/swift/archives/";
        if (count($trns) > 1) {
            //create zip only if several transactions
            try {
                $zip = new ZipArchive;
                $success_zip = $zip->open($path_arch . $instr_num . '.zip', ZipArchive::CREATE);
            } catch (\Exception $e) {
                error_log("exception : " . $e->getMessage());
            }
        }
        foreach ($trns as $tr) {
            $ctpy = $this->Counterparty->find(
                "first",
                array('conditions' => array(
                    'cpty_ID' => $tr['Transaction']['cpty_id'],
                ))
            );
            error_log("generating MT202 FILE : cpty : " . json_encode($ctpy, true));
            $currency = $tr['Transaction']['ccy'];
            $cpty_account = $this->CounterpartyAccount->find("first", array('conditions' => array(
                'currency' =>    $currency,
                'cpty_id' =>    $tr['Transaction']['cpty_id'],
            )));
            error_log("generating MT202 FILE TRN " . $tr['Transaction']['tr_number'] . " : cpty account : " . json_encode($cpty_account, true));
            if (empty($cpty_account)) {
                continue;
            }
            $end_of_line = "\r\n";

            $tag = array();
            $tag[':20'] = 'DI' . $instr_num; // TO change for instr_num ?
            $tag[':21'] = 'DI' . $instr_num;
            $timestmp = strtotime($this->clean_date($tr['Transaction']['commencement_date']));
            $amount = $tr['Transaction']['amount'];
            /*if (strpos($amount, '.') === false)
			{
				$amount = $amount . ',00';
			}
			else
			{
				$amount = str_replace('.', ',', $amount);
			}*/
            $amount = bcmul($amount, '1', 2); // to get a comma and 2 decimals
            $amount = str_replace('.', ',', $amount); //decimals separated by ,

            $tr['Transaction']['accountA_IBAN'] = str_replace(' ', '', $tr['Transaction']['accountA_IBAN']);
            $cpty_account['CounterpartyAccount']['account_IBAN'] = str_replace(' ', '', $cpty_account['CounterpartyAccount']['account_IBAN']);
            $cpty_account['CounterpartyAccount']['correspondent_BIC'] = str_replace(' ', '', $cpty_account['CounterpartyAccount']['correspondent_BIC']);
            $ctpy['Counterparty']['cpty_bic'] = str_replace(' ', '', $ctpy['Counterparty']['cpty_bic']);
            try {
                $tag[':32A'] = date("ymd", $timestmp) . $currency . $amount;
                $tag[':53B'] = '/' . $tr['Transaction']['accountA_IBAN'];

                if (empty($cpty_account['CounterpartyAccount']['account_IBAN'])) //if no iban, do not show line 57a
                {
                    //do not show 57a
                } else {
                    if ($cpty_account['CounterpartyAccount']['target'] && !empty($cpty_account['CounterpartyAccount']['account_IBAN']) && ($cpty_account['CounterpartyAccount']['account_IBAN'] != "")) {
                        $tag[':57A'] = '//RT' . $end_of_line . $cpty_account['CounterpartyAccount']['correspondent_BIC'];
                    } else {
                        $tag[':57A'] = $cpty_account['CounterpartyAccount']['correspondent_BIC'];
                    }
                }
                if ($cpty_account['CounterpartyAccount']['target'] && empty($cpty_account['CounterpartyAccount']['account_IBAN']) && ($cpty_account['CounterpartyAccount']['account_IBAN'] == "")) {
                    $tag[':58A'] = '//RT' . $end_of_line . $ctpy['Counterparty']['cpty_bic'];
                } elseif (!empty($cpty_account['CounterpartyAccount']['account_IBAN']) && ($cpty_account['CounterpartyAccount']['account_IBAN'] != "")) {
                    $tag[':58A'] = '/' . $cpty_account['CounterpartyAccount']['account_IBAN'] . $end_of_line . $ctpy['Counterparty']['cpty_bic'];
                } else {
                    $tag[':58A'] = $ctpy['Counterparty']['cpty_bic'];
                }
                error_log("generating MT202 FILE TRN " . $tr['Transaction']['tr_number'] . " : cpty message: ALL " . json_encode($ctpy, true));
                //error_log("generating MT202 FILE : cpty message: ".json_encode(['Counterparty']['cpty_mt202_message'], true));
                $tag[':72'] = '/BNF/DI' . $instr_num;
                $swift_file_content = "";
                foreach ($tag as $key => $content) {
                    $swift_file_content = $swift_file_content . $key . ':' . $content . $end_of_line;
                }
                $written = file_put_contents($path_tmp . '' . $tr['Transaction']['tr_number'] . '.txt', $swift_file_content);
                if ($written === false) {
                    error_log("could not write file " . $path_tmp . '' . $tr['Transaction']['tr_number'] . '.txt');
                }
                if (count($trns) > 1) {
                    try {
                        if ($success_zip === TRUE) {
                            $zip->addFile($path_tmp . '' . $tr['Transaction']['tr_number'] . '.txt', $tr['Transaction']['tr_number'] . '.txt');
                        } else {
                            error_log("fail zip file swift MT 202 : " . json_encode($success));
                        }
                    } catch (\Exception $e) {
                        error_log("exception : " . $e->getMessage());
                    }
                }
            } catch (Exception $e) {
                error_log("swift error : " . $e->getMessage());
            }
        }
        if (!empty($zip)) {
            $zip->close();
        }
    }

    /*mandatory yyyy-mm-dd format for dates to sas*/
    public function clean_date($date)
    {

        @$this->validate_param('date', $date);
        if (strpos($date, '/') !== false) {
            $dat = explode('/', $date);
            $date = $dat[2] . '-' . $dat[1] . '-' . $dat[0];
        }
        return $date;
    }
}
