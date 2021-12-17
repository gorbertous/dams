<?php

declare(strict_types=1);

namespace Treasury\Controller;

use Cake\Event\EventInterface;
use App\Lib\DownloadLib;


class AjaxController extends AppController
{

    public $name = 'Ajax';

    public function initialize(): void
    {
        parent::initialize();
        //$this->loadComponent('Security');
        //$this->loadComponent('Spreadsheet');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        //$this->Security->setConfig('blackHoleCallback', 'blackhole');
    }

    public function downloadFile()
    {
        $download_file = $this->request->getAttribute('params');
        $download_file = DownloadLib::filter_parameters($download_file['pass']);
        if (empty($download_file[1])) {
            $this->Flash->error('Wrong download path!');
            $this->redirect('/');
            return;
        }
        $path = array(
            'archive'            => "/upload/",
            'error'              => "/data/DSR/errors/",
            'upload'              => "/data/DSR/upload/",
            'docs'               => "/data/docs/",

        );
        if (!(empty($download_file[2]))) {
            $download_file_path = $path[$download_file[1]] . '/' . $download_file[2] . '/' . $download_file[0];
        } else {
            $download_file_path = $path[$download_file[1]] . $download_file[0];
        }

        DownloadLib::download($download_file_path);
        exit();
    }

    function getoriginaltrncall()
    {
        $selected_trn = $this->request->data['Transaction']['tr_number'];
        $origin_trn = $this->request->data['Transaction']['original_id'];

        $selected     = $this->Transaction->find('first', array('conditions' => array(
            'Transaction.tr_number' => $selected_trn
        )));
        $origin     = $this->Transaction->find('first', array('conditions' => array(
            'Transaction.tr_number' => $selected['Transaction']['parent_id']
        )));


        $this->set(compact('origin'));
        $this->set(compact('selected'));
    }

    function getoriginaltrnbreak()
    {
        $selected_trn = $this->request->data['Transaction']['tr_number'];
        $origin_trn = $this->request->data['Transaction']['original_id'];

        $selected     = $this->Transaction->find('first', array('conditions' => array(
            'Transaction.tr_number' => $selected_trn
        )));
        $origin     = $this->Transaction->find('first', array('conditions' => array(
            'Transaction.tr_number' => $origin_trn
        )));


        $this->set(compact('origin'));
        $this->set(compact('selected'));
    }

    function getoriginaltrnbreakvalid()
    {
        $selected_trn = $this->request->data['Transaction']['tr_number'];
        $sister = $this->Transaction->findSisterTRN($this->request->data['Transaction']['tr_number']);

        $selected     = $this->Transaction->find('first', array('conditions' => array(
            'Transaction.tr_number' => $selected_trn
        )));

        if (!empty($selected['Transaction']['parent_id'])) {
            $parent_trn = $selected['Transaction']['parent_id'];
            $parent     = $this->Transaction->find('first', array('conditions' => array(
                'Transaction.tr_number' => $parent_trn
            )));
        }


        $this->set(compact('parent'));
        $this->set(compact('selected'));
        $this->set(compact('sister'));
    }

    function getaccountsbytrn()
    {
        $trn = $this->request->data['Transaction']['tr_number'];

        if (isset($trn) and !empty($trn)) {
            $this->set('accountslist', $this->Compartment->getAccountsByCmp($this->Transaction->getAttribByTrn('cmp_ID', $trn)));
        } else $this->set('accountslist', array('accountA_IBAN' => ''));
        $this->layout = 'ajax';
    }

    function getcmpbymandate2()
    {
        $this->set('cmps', $this->Compartment->getcmpbymandate($this->request->data['openreinvestform']['mandate_ID']));
        $this->layout = 'ajax';
    }

    function getcptybymandate2($formName = 'openreinvestform', $all = null)
    {

        @$this->validate_param('string', $formName);
        @$this->validate_param('string', $all);
        $this->set('cptys', $this->Mandate->getcptybymandate($this->request->data[$formName]['mandate_ID'], $all));
        $this->layout = 'ajax';
    }

    function getreinvestables()
    {
        $this->Transaction->virtualFields  =  array(
            'amountccy' => "CONCAT('Transaction.amount',' ','AccountA.ccy')",
            'interest'    => "CONCAT(Transaction.total_interest,' ',AccountA.ccy)",
            'tax'    => "CONCAT(Transaction.tax_amount,' ',AccountA.ccy)",
        );

        $tab = $this->request->data['openreinvestform'];
        $this->set('mandate_ID', $tab['mandate_ID']);
        $this->set('cpty_ID', $tab['cpty_ID']);
        $this->set('cmp_ID', $tab['cmp_ID']);
        $this->set('availability_date', $tab['availability_date']);

        /* 
		 * The list of reinvestables deposits and rollovers is updated
		 * AS AND WHEN the user scrolls through the form
		 */
        $conditions = array(
            'Transaction.tr_type'    =>    array('Deposit', 'Rollover'),
            'Transaction.tr_state'    =>  array('Confirmed', 'First Notification', 'Second Notification'),
            'Transaction.depo_type !='     =>  'Callable',
        );

        if (!empty($this->request->data['openreinvestform'])) {
            $select = false;
            $conditions['Transaction.mandate_ID'] = $tab['mandate_ID'];

            if (isset($reinvestables) && !empty($reinvestables)) {
                $this->set('reinvs', $reinvestables);
            } else $this->set('reinvs', 'There are no reinvestable deposits or rollovers matching your criteria.');
        }

        if (!empty($tab['mandate_ID']) && !empty($tab['cpty_ID'])) {
            $select = false;
            $conditions['Transaction.cpty_id'] = $tab['cpty_ID'];
        }


        if (!empty($tab['mandate_ID']) && !empty($tab['cmp_ID']) && !empty($tab['cpty_ID'])) {
            $select = false;
            $conditions['Transaction.cmp_ID'] = $tab['cmp_ID'];
        }

        if (!empty($tab['mandate_ID']) && !empty($tab['cmp_ID']) && !empty($tab['cpty_ID']) && !empty($tab['availability_date'])) {
            $select = true;
            $conditions['Transaction.maturity_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $tab['availability_date'])));
        }

        $reinvestables = $this->Transaction->find(
            'all',
            array(
                'conditions' => $conditions,
                'fields'    =>      array(
                    'Transaction.maturity_date', 'Transaction.tr_number', 'Transaction.amount',
                    'Transaction.total_interest', 'Transaction.tax_amount',
                    'Transaction.accountA_IBAN', 'Transaction.accountB_IBAN',
                    'AccountA.ccy'
                ),
            )
        );

        foreach ($reinvestables as $key => $value) {
            $amountsAvailable = $this->Transaction->computeReinvGroup($value['Transaction']['tr_number']);
            $reinvestables[$key]['Transaction']['amountInA'] = $amountsAvailable['amountInA'];
            $reinvestables[$key]['Transaction']['amountInB'] = $amountsAvailable['amountInB'];
        }

        if (isset($reinvestables) && !empty($reinvestables)) {
            $this->set('reinvs', $reinvestables);
        } else $this->set('reinvs', 'There are no reinvestable deposits or rollovers matching your current criteria.');
        $this->set(compact('select'));

        $this->layout = 'ajax';
    }

    function getreinvinfo()
    {
        if (isset($this->request->data['closereinvform']['reinv_group']) and !empty($this->request->data['closereinvform']['reinv_group'])) {
            $params = array(
                "reinv_group" => $this->request->data['closereinvform']['reinv_group'],
            );

            $sasResult = $this->SAS->curl("P_CloseReinvestmentGroup.sas", $params, false);
            $this->set('sas', $sasResult);
        } else $this->set('sas', '');
        $this->layout = 'ajax';
    }

    // Used by Ajax to dynamically populate compartments list based on mandate
    function getcmpbymandate()
    {
        $mandate_id = $this->request->data['Transaction']['mandate_ID'];

        $cmps = $this->Compartment->getcmpbymandate($mandate_id);

        $this->set(compact('cmps'));
        $this->layout = 'ajax';
    }


    function getcmpbymandate_orall_list($mandate_id)
    {
        @$this->validate_param('int', $mandate_id);
        $cmps = array();
        if (strpos($mandate_id, ',') !== false) {
            $mandate_ids = explode(',', $mandate_id);
            foreach ($mandate_ids as $id) {
                $cmps_tmp = $this->Compartment->getcmpbymandate($id, true);
                $cmps = array_merge($cmps, $cmps_tmp);
            }
        } else {
            $cmps = $this->Compartment->getcmpbymandate($mandate_id, true);
        }
        return $cmps;
    }

    // Used by Ajax to dynamically populate compartments list based on mandate OR all compartments
    function getcmpbymandate_orall()
    {
        $mandate_id = $this->request->data['Transaction']['mandate_ID']; //or data[Bondtransaction][mandate_ID]
        if (is_string($mandate_id) && (strpos($mandate_id, ',') != false)) //remove ',' at the end of string if portfolio
        {
            $mandate_id = trim($mandate_id, ",");
            $mandate_id = explode(',', $mandate_id);
        } else {
            $mandate_id = trim($mandate_id, ",");
        }
        $cmps = array();
        if (is_array($mandate_id)) {
            foreach ($mandate_id as $ids) {
                $cmps_tmp = $this->getcmpbymandate_orall_list($ids);
                foreach ($cmps_tmp as $k => $v) {
                    $cmps[$k] = $v;
                }
            }
        } else {
            $cmps = $this->getcmpbymandate_orall_list($mandate_id);
        }

        $this->set(compact('cmps'));
        $this->layout = 'ajax';
        return $this->render('getcmpbymandate');
    }

    // Used by Ajax to dynamically populate counterparties list based on mandate
    function getcptybymandate($modelSource = "Transaction")
    {
        @$this->validate_param('string', $modelSource);
        $mandate_id = $this->request->data[$modelSource]['mandate_ID'];

        $cptys = $this->Mandate->getcptybymandate($mandate_id);

        $this->set(compact('cptys'));
        $this->layout = 'ajax';
    }

    // Used by Ajax to dynamically populate counterparties list based on mandate OR all counterparties
    function getcptybymandate_orall()
    {
        $mandate_id = $this->request->data['Transaction']['mandate_id'];

        if (!empty($mandate_id)) {
            if (is_string($mandate_id) && (strpos($mandate_id, ',') != false)) //remove ',' at the end of string if portfolio
            {
                $mandate_id = trim($mandate_id, ",");
                $mandate_id = explode(',', $mandate_id);
            } else {
                $mandate_id = trim($mandate_id, ",");
            }
            if (is_array($mandate_id)) {
                $cptys = array();
                foreach ($mandate_id as $mandate_id_list) {
                    $cptys_tmp = $this->getcptybymandate_orall_list($mandate_id_list);
                    foreach ($cptys_tmp as $k => $v) {
                        $cptys[$k] = $v;
                    }
                }
            } else {
                $cptys = $this->getcptybymandate_orall_list($mandate_id);
            }
        } else {
            $cptyList = $this->Transaction->find('all', array(
                'conditions' => array('Counterparty.cpty_name !=' => ''),
                'fields' => array('DISTINCT Counterparty.cpty_name', 'Counterparty.cpty_id')
            ));
            $cptys = array();
            foreach ($cptyList as $key => $value) {
                $cptys[$value['Counterparty']['cpty_id']] = $value['Counterparty']['cpty_name'];
            }
        }

        $this->set(compact('cptys'));
        $this->layout = 'ajax';
        return $this->render('getcptybymandate');
    }

    function getcptybymandate_orall_list($param)
    {
        @$this->validate_param('string', $param);
        if (strpos($param, ',') != false) {
            $mandate_ids = trim($param, ",");
            $mandate_ids = explode(',', $mandate_ids);
            $cptys = array();
            foreach ($mandate_ids as $id) {
                $cptys_tmp = $this->Mandate->getcptybymandate($id);
                foreach ($cptys_tmp as $k => $v) {
                    $cptys[$k] = $v;
                }
            }
        } else {
            $cptys = $this->Mandate->getcptybymandate($param);
        }
        return $cptys;
    }

    // Used by Ajax to dynamically populate the custom text list based on mandate and counterparty
    function getcustomtext_bymandatecpty($modelSource = "Transaction")
    {
        @$this->validate_param('string', $modelSource);
        $mandate_id = $this->request->data[$modelSource]['mandate_ID'];
        $cpty_id = $this->request->data[$modelSource]['cpty_id'];

        $ditpl = $this->DItemplate->getTemplate($mandate_id, $cpty_id);
        $custom_texts = $this->CustomText->find('all', array('conditions' => array('CustomText.cpty_id' => $cpty_id)));
        $this->set(compact('ditpl', 'cpty_id', 'custom_texts'));
    }

    // Used by Ajax to dynamically populate the DI previews based on mandate and counterparty
    function get_transactions_bymandatecpty($modelSource = "Transaction")
    {
        @$this->validate_param('string', $modelSource);
        $mandate_id = $this->request->data[$modelSource]['mandate_ID'];
        $cpty_id = $this->request->data[$modelSource]['cpty_id'];
        $type = $this->request->data['Instruction']['type'];

        $transactions = array();
        switch ($type) {
            case 'di':
                $transactions = $this->Transaction->find('all', array(
                    'order' => array('Transaction.commencement_date' => 'ASC'),
                    'fields' => '*',
                    'conditions' => array(
                        'Transaction.mandate_ID'         => $mandate_id,
                        'Transaction.cpty_ID'             => $cpty_id,
                        'Transaction.tr_state'             => "Created",
                        'Transaction.tr_type'             => array("Deposit", "Rollover", "Repayment"),
                        //'Transaction.parent_id' 		=> NULL,//useless rule, removed for http://vmu-sas-01:8080/browse/TREASURY-427
                        //'outFromReinv.reinv_status'		=> 'Closed',
                        'OR' => array(
                            array('outFromReinv.reinv_status' => NULL),
                            array('outFromReinv.reinv_status' => 'Closed'),
                        ),
                        //'foo'=>1,
                    )
                ));
                break;
            case 'call':
                if ($cpty_id == EXIMBANKA_ID) break;
                $transactions = $this->Transaction->find('all', array(
                    'order' => array('Transaction.commencement_date' => 'ASC'),
                    'conditions' => array(
                        'Transaction.mandate_ID'     => $mandate_id,
                        'Transaction.cpty_ID'         => $cpty_id,
                        'Transaction.tr_state'         => "Created",
                        'Transaction.tr_type'         => array("Call")
                    )
                ));
                break;
            case 'break':
                if ($cpty_id == EXIMBANKA_ID) break;
                $transactions = $this->Transaction->find('all', array(
                    'order' => array('Transaction.commencement_date' => 'ASC'),
                    'conditions' => array(
                        'Transaction.mandate_ID'     => $mandate_id,
                        'Transaction.cpty_ID'         => $cpty_id,
                        'Transaction.tr_state'         => "Created",
                        'Transaction.tr_type'         => array("Withdrawal")
                    ),
                    'limit' => 1
                ));
                break;
        }

        //eximbanka hack: keep only ON, 3M, 6M
        /*if(!empty($cpty_id) && $cpty_id==EXIMBANKA_ID){
			foreach($transactions as $key=>$transaction){
				if(strtolower($transaction['Transaction']['tr_type'])!='repayment' && !in_array($transaction['Transaction']['depo_term'], array('ON', '3M', '6M'))){
					unset($transactions[$key]);
				}
			}
		}*/

        $this->set(compact('transactions'));
        $this->set(compact('mandate_id'));
        $this->set(compact('cpty_id'));
        $this->set(compact('type'));
    }


    function getBenchmark()
    {
        $this->layout = 'ajax';
        $benchmark = "";
        if (!empty($this->request->data['Transaction']['mandate_ID']) && !empty($this->request->data['Transaction']['currency'])) {
            $benchmark = $this->Transaction->getBenchmark($this->request->data['Transaction']['mandate_ID'], $this->request->data['Transaction']['currency']);
        }
        die($benchmark);
    }

    function checkLimitBreachAmount($datas = null)
    {

        @$this->validate_param('array', $datas);
        $output = '';
        if (!empty($datas)) {
            if (!isset($datas['Transaction']['commencement_date']) && isset($datas['Transaction']['availability_date'])) {
                $datas['Transaction']['commencement_date'] = $datas['Transaction']['availability_date'];
            }
            @$this->request->data = $datas;
        }

        $this->loadModel('Treasury.Transaction');
        $this->Transaction->create();
        $this->Transaction->set($this->request->data);

        if ($breach = $this->Transaction->validateLimitBreach()) {
            if (isset($breach['amount']['exposure']['message'])) {
                return $breach['amount']['exposure']['message'];
            }
            if (isset($breach['depo_term']['maxmaturity']['message'])) {
                return $breach['depo_term']['maxmaturity']['message'];
            }
        }

        return false;
    }
    function checkLimitBreach()
    {
        $dates = array();
        $mandate_ID = $cpty_id = null;
        $MandateGroup = ClassRegistry::init('Treasury.MandateGroup');
        $Limit = ClassRegistry::init('Treasury.Limit');
        //group trn by date
        if (!empty($this->request->data)) foreach ($this->request->data as $i => $trn) {
            $linum = 1;

            $trn = reset($trn);

            if (empty($cpty_id)) {
                $cpty_id = $trn['cpty_id'];
            }
            if (empty($mandate_ID)) {
                $mandate_ID = $trn['mandate_ID'];
            }

            $commencement_date = strtotime(str_replace('/', '-', $trn['commencement_date']));
            $euramount = floatval(str_replace(',', '', $trn['amount']));
            $days = $this->Transaction->getTransactionDays(array('Transaction' => $trn));

            //get all counterparty group based on the current counterparty
            $cptygroups = $MandateGroup->query('SELECT * FROM counterparty_group_subscriptions WHERE cpty_ID=' . intval($cpty_id));
            $cptygrouplist = array();
            if (!empty($cptygroups)) foreach ($cptygroups as $cptygroup) {
                $cptygroup_ID = $cptygroup['counterparty_group_subscriptions']['counterpartygroup_ID'];
                $cptygrouplist[] = $cptygroup_ID;
            }

            //if amount not in EUR, convert it
            if (!empty($trn['ccy']) && $trn['ccy'] != "EUR") {
                $euramount = CurrencyLib::convert($euramount, $trn['ccy'], 'EUR', $commencement_date);
            }
            if (isset($trn['tr_number'])) {
                $edited_transaction = $this->Transaction->find('first', array(
                    'recursive'    => 1,
                    'conditions' => array(
                        'tr_number' => $trn['tr_number']
                    ),
                ));
                $former_amount = $edited_transaction['Transaction']['amount'];
                $ccy = $edited_transaction['AccountA']['ccy'];
                if ($ccy != "EUR") {
                    $former_amount = CurrencyLib::convert($former_amount, $ccy, 'EUR', $commencement_date);
                }
            }
            if (!isset($dates[$commencement_date])) $dates[$commencement_date] = array('amount' => $euramount);
            else $dates[$commencement_date]['amount'] += $euramount;

            $dates[$commencement_date]['days'] = $days;
        }
        ksort($dates);

        //get mandate groups, and their limits based on current mandate
        $mandategroupLimits = array();
        if (!empty($mandate_ID)) {
            //get mandate groups where the mandate belongs
            $mandategroups = $this->Transaction->query('SELECT * FROM mandate_group_subscriptions WHERE mandate_ID=' . intval($trn['mandate_ID']));
            if (!empty($mandategroups)) foreach ($mandategroups as $mandategroup) {
                $mandategroup_ID = $mandategroup['mandate_group_subscriptions']['mandategroup_ID'];
                $mandategroupdetails = $this->MandateGroup->findById($mandategroup_ID);

                //build conditions to get all related limits
                $conditions = array(
                    'Limit.mandategroup_ID' => $mandategroup_ID,
                    'Limit.limit_date_from <=' => date('Y-m-d'),
                    'OR' => array(
                        array('Limit.limit_date_to' => null),
                        array('Limit.limit_date_to >' => date('Y-m-d')),
                    )
                );
                if (empty($cptygrouplist)) {
                    $conditions[] = array('Limit.cpty_ID' => $cpty_id);
                } else {
                    $conditions[] = array('OR' => array(
                        array(
                            'Limit.cpty_ID' => $cpty_id,
                            'Limit.counterpartygroup_ID <' => 1,
                        ),
                        array(
                            'Limit.cpty_ID <' => 1,
                            'Limit.counterpartygroup_ID' => $cptygrouplist,
                        )
                    ));
                }
                //get all limit for counterparty group related to the current counterparty
                $limits = $Limit->find('all', array('recursive' => -1, 'conditions' => $conditions));
                if (!empty($limits)) {
                    $limits['mandategroup_name'] = $mandategroup_ID;
                    if (!empty($mandategroupdetails['MandateGroup']['mandategroup_name'])) $limits['mandategroup_name'] = $mandategroupdetails['MandateGroup']['mandategroup_name'];
                    $mandategroupLimits[$mandategroup_ID] = $limits;
                }
            }
        }

        //get exposure for each date and each date for each limit
        if (!empty($mandategroupLimits) && !empty($dates)) {

            $totalamount = 0;
            foreach ($dates as $date => &$val) {
                $totalamount += $val['amount'];
                if (!isset($val['groups'])) $val['groups'] = array();

                foreach ($mandategroupLimits as $mandategroup_ID => $limits) {
                    $portfoliosize = null;
                    if (!isset($val['groups'][$mandategroup_ID])) $val['groups'][$mandategroup_ID] = array();

                    $expoByDate[$date] = $this->Transaction->computeExposure($mandategroup_ID, $cpty_id, date("Y-m-d", $date));
                    if (isset($former_amount)) {
                        $expoByDate[$date] -= $former_amount;
                    }
                    $maturity_date = strtotime("+" . $val['days'] . " day", $date);
                    $commencement_date = $date;

                    while ($commencement_date < $maturity_date) {
                        $commencement_date = strtotime("+1 day", $commencement_date);
                        $expoByDate[$commencement_date] = $this->Transaction->computeExposure($mandategroup_ID, $cpty_id, date("Y-m-d", $commencement_date));
                        if (isset($former_amount)) {
                            $expoByDate[$commencement_date] -= $former_amount;
                        }
                    }

                    $val['groups'][$mandategroup_ID]['expo'] = $expoByDate[$date];

                    if (!empty($limits)) foreach ($limits as $limitkey => $limit) {
                        $groupid = null;
                        $msg = null;
                        //maturity
                        if (!empty($limit['Limit']['max_maturity'])) {
                            if ($limit['Limit']['max_maturity'] < $val['days']) {
                                $val['groups'][$mandategroup_ID]['limits']['breach'] = true;
                                $msg .= 'Maturity Limit breach! ';
                            }
                        }

                        //exposure
                        if (!isset($val['groups'][$mandategroup_ID]['limits'])) $val['groups'][$mandategroup_ID]['limits'] = array();

                        if (!empty($limit['Limit']['counterpartygroup_ID'])) {
                            $groupid = $limit['Limit']['counterpartygroup_ID'];
                            if (!isset($expogroups[$groupid])) {
                                if (!isset($CounterpartyGroup)) $CounterpartyGroup = ClassRegistry::init('Treasury.CounterpartyGroup');
                                $groupexpo = 0;
                                $counterparties = $CounterpartyGroup->getCounterparties($groupid);
                                $commencement_date = $date;

                                while ($commencement_date < $maturity_date) {
                                    $groupexpo = 0;
                                    $commencement_date = strtotime("+1 day", $commencement_date);
                                    foreach ($counterparties as $cpty) {
                                        $cptyexposure = $this->Transaction->computeExposure($mandategroup_ID, $cpty['cpty_ID'], date("Y-m-d", $commencement_date));
                                        $groupexpo += $cptyexposure;
                                    }
                                    $expogroups[$groupid][$commencement_date] = $groupexpo;
                                }
                            }
                        }

                        if (!empty($limit['Limit']['limit_eur'])) {
                            $limitamount = floatval(str_replace(',', '', $limit['Limit']['limit_eur']));

                            //check the period if there is a limit breach
                            $totalexpo = 0;
                            $msgExposure = null;
                            $firstdate = null;
                            $lastdate = null;
                            $expoFirstData = 0;
                            foreach ($expoByDate as $dateID => $expo) {
                                if (empty($groupid)) {
                                    $totalexpo = $expo + $totalamount;
                                } elseif (isset($expogroups[$groupid][$dateID])) {
                                    $totalexpo = $expogroups[$groupid][$dateID] + $totalamount;
                                } else continue;

                                $delta = $limit['Limit']['limit_eur'] - $totalexpo;
                                $val['groups'][$mandategroup_ID]['limits']['delta'] = $delta;
                                if ($delta < 0) {
                                    if (empty($firstdate)) {
                                        $firstdate = $dateID;
                                        $expoFirstData = $delta;
                                    }
                                    $lastdate =  $dateID;
                                    $exposure[$dateID] = $delta;
                                    $limit_name = $limit['Limit']['limit_name'];
                                }
                            }

                            $val['groups'][$mandategroup_ID]['limits']['limit'] = $limitamount;
                            $val['groups'][$mandategroup_ID]['limits']['breach'] = false;

                            if (!empty($firstdate) && !empty($lastdate) && $firstdate == $lastdate) {
                                $msgExposure = ' (' . date('d/m/Y', $firstdate) . ': ' . UniformLib::uniform($expoFirstData, 'exposure_amount') . ')';
                            } elseif (!empty($firstdate) && !empty($lastdate)) {
                                $msgExposure = '(From ' . date('d/m/Y', $firstdate) . ' to ' . date('d/m/Y', $lastdate) . ')';
                            }

                            if (!empty($msgExposure)) {
                                $val['groups'][$mandategroup_ID]['limits']['breach'] = true;
                                $msg .= 'Exposure Limit Breach! ' . $msgExposure . ' ';
                            }
                        }

                        //concentration
                        if (!empty($limit['Limit']['max_concentration'])) {
                            $val['groups'][$mandategroup_ID]['limits']['breach'] = false;
                            $maxconcentration = floatval(str_replace(',', '', $limit['Limit']['max_concentration']));
                            if (empty($portfolioSize)) {
                                if (!isset($MandateGroup)) $MandateGroup = ClassRegistry::init('Treasury.MandateGroup');
                                $portfoliosize = $MandateGroup->getSize($mandategroup_ID, date('Y-m-d', $date));
                            }
                            if (!empty($portfoliosize)) {
                                $portfoliosize = $portfoliosize + $totalamount;
                                if ($limit['Limit']['concentration_limit_unit'] == 'ABS') {
                                    if ($totalexpo > $limit['Limit']['max_concentration']) {
                                        $val['groups'][$mandategroup_ID]['limits']['limit'] = $limit['Limit']['max_concentration'];
                                        //$val['groups'][$mandategroup_ID]['limits']['delta']=$limit['Limit']['max_concentration']-$depositAndExposure;
                                        $val['groups'][$mandategroup_ID]['limits']['breach'] = true;
                                    }
                                    // Concentration can be breached if max concentration is less than  100%
                                } elseif ($limit['Limit']['concentration_limit_unit'] == 'PCT') {
                                    $ratio = ($expoByDate[$date] + $totalamount) / $portfoliosize;
                                    if ($ratio > ($limit['Limit']['max_concentration'])) {
                                        $val['groups'][$mandategroup_ID]['limits']['limit'] = $portfoliosize * $limit['Limit']['max_concentration'];
                                        $val['groups'][$mandategroup_ID]['limits']['delta'] = $ratio;
                                        $val['groups'][$mandategroup_ID]['limits']['breach'] = true;
                                    }
                                }
                                if (!empty($val['groups'][$mandategroup_ID]['limits']['breach'])) {
                                    $concentrationDetails = UniformLib::uniform($val['groups'][$mandategroup_ID]['limits']['delta'], 'delta_amount');
                                    if ($limit['Limit']['concentration_limit_unit'] == 'PCT') {
                                        $concentrationDetails = UniformLib::uniform($val['groups'][$mandategroup_ID]['limits']['delta'] * 100, 'concentration_pct') . '%';
                                    }
                                    $msg .= 'Concentration Limit Breach! ' . $concentrationDetails . ' ';
                                }
                            }
                        }

                        if (!empty($msg)) {
                            //$this->log_entry($msg.' , Portfoliosize:'.$portfoliosize.' '.print_r($this->request->data, true),'treasury');
                            die(UniformLib::uniform($limit['Limit']['limit_name'], 'limit_name') . ' : ' . $msg);
                        }
                    }
                }
            }
        }


        //die('ALL CLEAR: No Limit Breach found.');
        die();
    }


    // Used by Ajax to dynamically get Principal (A) and Interest (B) accounts list based on compartment
    function getaccounts()
    {

        $cmp_id = isset($this->request->data['Transaction']['cmp_ID']) ? $this->request->data['Transaction']['cmp_ID'] : NULL;
        if (isset($cmp_id) and !empty($cmp_id)) {
            $accounts = $this->Compartment->find(
                'first',
                array(
                    'conditions' => array('Compartment.cmp_ID' => $cmp_id),
                    'fields' => array('accountA_IBAN', 'accountB_IBAN', 'AccountA.ccy', 'AccountB.ccy'),
                )
            );

            $this->set('accounts', $accounts['Compartment']);
            $this->set('ccyA', $accounts['AccountA']['ccy']);
            $this->set('ccyB', $accounts['AccountB']['ccy']);
        } else {
            $this->set('accounts', array('accountA_IBAN' => '', 'accountB_IBAN' => ''));
            $this->set('ccyA', '');
            $this->set('ccyB', '');
        }

        $this->layout = 'ajax';
    }

    function accountslist()
    {

        $cmp_id = isset($this->request->data['Transaction']['cmp_ID']) ? $this->request->data['Transaction']['cmp_ID'] : NULL;
        if (!$cmp_id && $trn = isset($this->request->data['Transaction']['tr_number']) ? $this->request->data['Transaction']['tr_number'] : NULL) {
            $tr = $this->Transaction->findByTrNumber($trn, array('fields' => 'Transaction.cmp_ID'));
            if (is_array($tr)) $cmp_id = $tr['Transaction']['cmp_ID'];
        }
        if (isset($cmp_id) and !empty($cmp_id)) {
            $this->set('accountslist', $this->Compartment->getAccountsByCmp($cmp_id));
        } else
            $this->set('accountslist', array('accountA_IBAN' => ''));

        $this->layout = 'ajax';
    }

    function getaccounts2()
    {

        $reinv_group = $this->request->data['Transaction']['reinv_group'];

        if (isset($reinv_group) and !empty($reinv_group)) {
            $accounts = $this->Reinvestment->find(
                'all',
                array(
                    'conditions' => array('Reinvestment.reinv_group' => $reinv_group),
                    'fields' => array('Reinvestment.accountA_IBAN'),
                    'recursive' => -1
                )
            );

            $this->set('accountslist', $accounts[0]['Reinvestment']);
        } else $this->set('accountslist', array('accountA_IBAN' => ''));

        $this->layout = 'ajax';
    }

    // To control accounts scheme, if Principal account is B, than Interest account is also B
    function accountscheme()
    {

        $principalAcc = $this->request->data['Transaction']['accountA_IBAN'];
        $cmp_ID = '';
        if (isset($this->request->data['Transaction']['cmp_ID'])) {
            $cmp_ID = $this->request->data['Transaction']['cmp_ID'];
        } elseif (isset($this->request->data['Transaction']['reinv_group'])) {
            $reinv = $this->Reinvestment->find('first', array('conditions' => array('Reinvestment.reinv_group' => $this->request->data['Transaction']['reinv_group'])));
            $cmp_ID = $reinv['Reinvestment']['cmp_ID'];
        }

        if (isset($principalAcc) and !empty($principalAcc)) {
            $accounts = $this->Compartment->getAccountsByCmp($cmp_ID);
            if ($principalAcc == $accounts['accountA_IBAN']) {
                $this->set('accs', $accounts);
            } else {
                $this->set('accs', array('accountB_IBAN' => $accounts['accountB_IBAN']));
            }
        } else $this->set('accs', array('accountA_IBAN' => '', 'accountB_IBAN' => ''));

        $this->layout = 'ajax';
    }

    // Hide accounts div when user selects a new mandate
    function hideaccounts()
    {
        $this->layout = 'ajax';
    }

    // Get currency when compartment is selected
    function getccy()
    {
        if (!empty($this->request->data['Transaction'])) {
            $cmp_id = $this->request->data['Transaction']['cmp_ID'];
        } elseif (!empty($this->request->data['TransactionBond'])) {
            $cmp_id = $this->request->data['TransactionBond']['cmp_ID'];
        }
        $accounts = $this->Compartment->getAccountsByCmp($cmp_id);
        $ccy = $this->Account->getAccountById($accounts['accountA_IBAN']);
        $this->set('ccy', $ccy['Account']['ccy']);
        $this->layout = 'ajax';
    }

    function getreinvacc($id)
    {

        @$this->validate_param('string', $id);
        $reinv_group = $this->request->data[$id]['reinv_group'];

        if (isset($reinv_group) and !empty($reinv_group)) {
            $accounts = $this->Reinvestment->find(
                'first',
                array(
                    'conditions' => array('Reinvestment.reinv_group' => $reinv_group),
                    'fields'    => array('accountA_IBAN', 'accountB_IBAN', 'AccountA.ccy', 'AccountB.ccy'),
                    'recursive' => 1
                )
            );
            $this->set('accounts', $accounts['Reinvestment']);
            $this->set('ccyA', $accounts['AccountA']['ccy']);
            $this->set('ccyB', $accounts['AccountB']['ccy']);
        } else
            $this->set('accountslist', array('accountA_IBAN' => '', 'accountB_IBAN' => ''));

        $this->layout = 'ajax';
    }

    function accounts()
    {
        $reinv_group = $this->request->data['newrepayform']['reinv_group'];

        if (isset($reinv_group) and !empty($reinv_group)) {
            $accounts = $this->Reinvestment->find(
                'all',
                array(
                    'conditions' => array('Reinvestment.reinv_group' => $reinv_group),
                    'fields'    => array('Reinvestment.accountA_IBAN'),
                    'recursive' => -1
                )
            );
            $this->set('accountslist', $accounts[0]['Reinvestment']);
        } else
            $this->set('accountslist', array('accountA_IBAN' => ''));

        $this->layout = 'ajax';
    }

    function callconfvalidate()
    {

        $trn = $this->request->data['validateconf']['trn'];
        $this->loadModel('Treasury.Transaction');
        $transaction = $this->Transaction->find('first', array(
            'conditions'    => array('Transaction.tr_number' => $trn),
            'fields'         => array(
                "Transaction.external_ref", "Transaction.tr_type as Type", "Transaction.depo_type as Term_or_Callable", "Transaction.commencement_date", "Transaction.depo_term as Period", "Transaction.maturity_date", "Transaction.amount", "Transaction.interest_rate", "Transaction.total_interest", "Transaction.date_basis", "Transaction.tax_amount",
                'AccountA.ccy',
            ),
            'recursive' => 1,
        ));
        $this->set(compact('trn'));

        if (!empty($transaction['Transaction']['commencement_date']) && intval($transaction['Transaction']['commencement_date'])) {
            $transaction['Transaction']['commencement_date'] = $transaction['Transaction']['commencement_date'];
        }
        if (!empty($transaction['Transaction']['maturity_date']) && intval($transaction['Transaction']['maturity_date'])) {
            $transaction['Transaction']['maturity_date'] = $transaction['Transaction']['maturity_date'];
        }
        $this->set(compact('transaction'));

        $this->layout = 'ajax';
    }

    function confvalidate($trn = null, $textreturn = null)
    {
        @$this->validate_param('int', $trn);
        @$this->validate_param('string', $textreturn);

        if (!empty($this->request->data['conf']['tr_number'])) {
            $trn = $this->request->data['conf']['tr_number'];
        }

        $this->Transaction->id = $trn;

        $data = array('Transaction' => array(
            'tr_number' => $trn,
            'tr_state'        => 'Confirmed',
        ));

        $this->Transaction->save($data);
        $this->log_entry('Confirmed TRN ' . $trn, 'treasury', $trn);

        if (!empty($textreturn)) {
            $return = array('success' => true, 'action' => 'confvalidate', 'id' => $trn, 'text' => 'Confirmation #' . $trn . ' has been validated');
            print json_encode($return);
            die();
        }

        $this->redirect("/treasury/treasurytransactions/validateconf");

        $this->layout = 'ajax';
    }

    function confreject($trn = null, $textreturn = null)
    {

        @$this->validate_param('int', $trn);
        @$this->validate_param('string', $textreturn);

        if (!empty($this->request->data['rej']['tr_number'])) {
            $trn = $this->request->data['rej']['tr_number'];
        }

        $this->Transaction->id = $trn;
        $data = array('Transaction' => array('tr_number' => $trn, 'tr_state'    => 'Instruction sent'));
        /*$data = array('Transaction' => array(
			'tr_state'		=> 'Instruction sent',
			'external_ref'	=> '',
			'interest_rate'	=> '',
			'total_interest' => '',
			'date_basis'	=> '',
		));*/

        /*if($this->Transaction->depo_term != 'NS'){
			$data['Transaction']['maturity_date'] = '';
		}*/

        $this->Transaction->save($data);
        $this->log_entry('Confirmation rejected TRN ' . $trn, 'treasury', $trn);

        if (!empty($textreturn)) {
            $return = array('success' => true, 'action' => 'confreject', 'id' => $trn, 'text' => 'Confirmation #' . $trn . ' has been rejected');
            print json_encode($return);
            die();
        }

        $this->redirect("/treasury/treasurytransactions/validateconf");
        $this->layout = 'ajax';
    }

    function callconfreject()
    {

        $trn = $this->request->data['rejectconf']['trn'];

        $sasResult = $this->SAS->curl("P_Confirmation_Validation.sas", array("tr_number" => $trn), false);

        $this->set(compact('trn'));
        $this->set('sas', utf8_encode($sasResult));
        $this->set('tables', $this->SAS->get_all_tables_from_webout(utf8_encode($sasResult)));

        $this->layout = 'ajax';
    }

    function getbookingfilename()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            header('Content-type: application/json');

            $params = array(
                "bok_t" => $this->params->query['data']['Accruals']['bok_t']
            );

            $sasResult = (string) trim($this->SAS->curl("get_booking_filename.sas", $params, false));

            echo '?("' . $sasResult . '")';
        }
    }

    function calcTotalInterest()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {

            $amount = '';
            if (!empty($this->request->query['data']['Transaction']['amount'])) $amount = floatval(str_replace(',', '', $this->request->query['data']['Transaction']['amount']));
            if (!empty($this->request->query['data']['Transaction']['commencement_date'])) {
                $commencement = explode('/', $this->request->query['data']['Transaction']['commencement_date']);
                $commencement_date = $commencement[2] . '-' . $commencement[1] . '-' . $commencement[0];
            }

            $commencement_date = '';
            if (!empty($this->request->query['data']['Transaction']['commencement_date'])) {
                $commencement = explode('/', $this->request->query['data']['Transaction']['commencement_date']);
                $commencement_date = $commencement[2] . '-' . $commencement[1] . '-' . $commencement[0];
            }

            $this->request->query['data']['Transaction']['commencement_date'] = $commencement_date;

            if (empty($trn['Transaction']['tr_number'])) $trn['Transaction']['tr_number'] = 1;

            if (empty($this->request->query['data']['Transaction']['maturity_date']) && !empty($this->request->query['data']['Transaction']['depo_term']) && !empty($commencement_date)) {
                $this->request->query['data']['Transaction']['maturity_date'] = $this->Transaction->getIndicativeMaturityDate($commencement_date, $this->request->query['data']['Transaction']['depo_term']);
            } elseif (!empty($maturity_date)) {
                $maturity_date = str_replace('/', '-', $this->request->query['data']['Transaction']['maturity_date']);
                $maturity_date = date('Y-m-d', strtotime($maturity_date));
                $this->request->query['data']['Transaction']['maturity_date'] = $maturity_date;
            }

            header('Content-type: application/json');
            if ((isset($this->request->query['data']['Transaction']['tr_number']) || (!empty($amount) && !empty($commencement_date))) &&
                !empty($this->request->query['data']['Transaction']['maturity_date']) &&
                isset($this->request->query['data']['Transaction']['interest_rate']) &&
                isset($this->request->query['data']['Transaction']['date_basis'])
            ) {

                if (!is_numeric($this->request->query['data']['Transaction']['interest_rate'])) {
                    echo '?(' . " " . ')';
                    exit();
                }

                if (empty($amount) || empty($commencement_date)) {
                    $this->loadModel('Treasury.Transaction');

                    $tr_number = $this->request->query['data']['Transaction']['tr_number'];
                    $trn = $this->Transaction->find('first', array('conditions' => array(
                        'tr_number' => $tr_number
                    )));

                    // NOT IN UAT ACTUAL : $this->request->query['data']['Transaction']['maturity_date'] = (date('Y-m-d', strtotime(str_replace('/','-',$this->request->query['data']['Transaction']['maturity_date']))));
                    if (empty($commencement_date)) {
                        $commencement = explode('/', $trn['Transaction']['commencement_date']);
                        $commencement_date = $commencement[2] . '-' . $commencement[1] . '-' . $commencement[0];
                    }
                    if (empty($amount)) $amount = $trn['Transaction']['amount'];
                }

                $this->request->query['data']['Transaction']['maturity_date'] = (date('Y-m-d', strtotime(str_replace('/', '-', $this->request->query['data']['Transaction']['maturity_date']))));
                $sasResult = $this->SAS->curl("register_confirmation.sas", array(
                    "amount"            => $amount,
                    "maturity_date"        => $this->request->query['data']['Transaction']['maturity_date'],
                    "interest_rate"        => $this->request->query['data']['Transaction']['interest_rate'],
                    "date_basis"        => $this->request->query['data']['Transaction']['date_basis'],
                    "commencement_date"    => $commencement_date,
                ), false);
                echo '?(' . $sasResult . ')';
            } else {
                echo '?(' . "-" . ')';
            }
        }
    }

    function computeTax()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {

            $amount = '';
            if (!empty($this->request->query['data']['Transaction']['amount'])) $amount = $this->request->query['data']['Transaction']['amount'];

            $commencement_date = '';
            if (!empty($this->request->query['data']['Transaction']['commencement_date'])) $commencement_date = $this->request->query['data']['Transaction']['commencement_date'];

            if (empty($trn['Transaction']['tr_number'])) $trn['Transaction']['tr_number'] = 1;

            header('Content-type: application/json');
            if ((isset($this->request->query['data']['Transaction']['tr_number']) || (!empty($amount) && !empty($commencement_date))) &&
                !empty($this->request->query['data']['Transaction']['maturity_date']) &&
                isset($this->request->query['data']['Transaction']['interest_rate']) &&
                isset($this->request->query['data']['Transaction']['date_basis'])
            ) {

                if (!is_numeric($this->request->query['data']['Transaction']['interest_rate'])) {
                    echo '?(' . " " . ')';
                    exit();
                }

                if (empty($amount) || empty($commencement_date)) {
                    $this->loadModel('Treasury.Transaction');

                    $tr_number = $this->request->query['data']['Transaction']['tr_number'];
                    $trn = $this->Transaction->find('first', array('conditions' => array(
                        'tr_number' => $tr_number
                    )));

                    $this->request->query['data']['Transaction']['maturity_date'] = (date('Y-m-d', strtotime(str_replace('/', '-', $this->request->query['data']['Transaction']['maturity_date']))));
                    if (empty($commencement_date)) $commencement_date = (date('Y-m-d', strtotime(str_replace('/', '-', $trn['Transaction']['commencement_date']))));
                    if (empty($amount)) $amount = $trn['Transaction']['amount'];
                }

                $sasResult1 = $this->SAS->curl("tax_computing_screen.sas", array(
                    "tr_number"            => $trn['Transaction']['tr_number'],
                    "amount"            => $amount,
                    "maturity_date"        => $this->request->query['data']['Transaction']['maturity_date'],
                    "interest_rate"        => $this->request->query['data']['Transaction']['interest_rate'],
                    "date_basis"        => $this->request->query['data']['Transaction']['date_basis'],
                    "commencement_date"    => $commencement_date,
                ), false);
                echo '?(' . $sasResult1 . ')';
            } else {
                echo '?(' . "-" . ')';
            }
        }
    }

    function computeTaxFromInterestAndMandateCpty()
    {
        $this->autoRender = false;

        $tax = '';

        $interest = $this->request->query['data']['Transaction']['total_interest'];
        $this->request->query['data']['Transaction']['total_interest'] = str_replace(",", "", $interest);
        if (
            !empty($this->request->query['data']['Transaction']['total_interest']) &&
            !empty($this->request->query['data']['Transaction']['mandate_ID']) &&
            !empty($this->request->query['data']['Transaction']['cpty_id'])
        ) {
            $taxes = $this->Tax->find('first', array('recursive' => -1, 'conditions' => array(
                'Tax.mandate_ID' => $this->request->query['data']['Transaction']['mandate_ID'],
                'Tax.cpty_ID' => $this->request->query['data']['Transaction']['cpty_id'],
            )));
            if (!empty($taxes['Tax']['tax_rate'])) {
                $tax = floatval($this->request->query['data']['Transaction']['total_interest']) * (floatval($taxes['Tax']['tax_rate']) / 100);
                $tax = round($tax * 100) / 100;
            }
        }

        if ($tax < 0) $tax = 0;

        //header('Content-type: application/json');
        die('' . $tax);
        return $tax;
    }

    function getNoLimitOnMandateGroup()
    {
        $mandategroup_id = $this->request->data['limit']['mandate_id'];
        $rep = '';
        if (!empty($mandategroup_id)) {
            $MandateGroup = ClassRegistry::init('Treasury.MandateGroup');
            //$mandategroup = $this->request->Data['data']
            $concentration_unit = $MandateGroup->getConcentrationUnit($mandategroup_id);
            $rep = $concentration_unit;
        }
        $this->set('concentration_unit', $rep);
        //$this->layout = 'ajax';
    }

    function getInterestCallDeposit()
    {
        $this->autoRender = false;
        if ($this->request['data']['Transaction']['date'] == "") {
            die(json_encode(array('interest' => ""), true));
        }
        $tr_number = $this->request['data']['Transaction']['tr_number'];
        $tr = $this->Transaction->getTransactionById($tr_number);
        if ($rt['Transaction']['rate_type'] == 'Fixed') {
            $this->redirect("/treasury/treasuryajax/getInterest");
        } else {
            echo (json_encode(array("error" => "could not calculate interest"), true));
        }
    }

    function getInterest()
    {
        $this->autoRender = false;
        if ($this->request['data']['Transaction']['date'] == "") {
            die(json_encode(array('interest' => ""), true));
        }
        $tr_number = $this->request['data']['Transaction']['tr_number'];
        $tr = $this->Transaction->getTransactionById($tr_number);
        $amount = $tr['Transaction']['amount'];
        $commencement_date = $tr['Transaction']['commencement_date'];
        if (strpos($commencement_date, '/') !== false) {
            $commencement_date = explode('/', $commencement_date);
            $commencement_date = $commencement_date[2] . "-" . $commencement_date[1] . "-" . $commencement_date[0];
        }

        $date_basis = $tr['Transaction']['date_basis'];
        $error = false;
        if ($tr['Transaction']["depo_type"] == "Callable") {
            $date_end = $this->request['data']['Transaction']['date'];
            //change format for $this->request['data']['date'] ?

            try {
                $total_interest = $this->getSASInterest($amount, $date_end, $date_basis, $commencement_date, $tr_number);
            } catch (Exception $e) {
                $error = true;
            }
        } else {
            //only 1 interest rate
            $interest_rate = $tr['Transaction']['interest_rate'];
            if (empty($interest_rate)) {
                die(json_encode(array('interest' => ""), true));
            }
            $interest_rate = $interest_rate['Interest']['interest_rate'];
            $interest_rate = str_replace(',', '', $interest_rate);
            $params = array(
                "amount"            => $amount,
                "new_date"            => $this->request['data']['Transaction']['date'],
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
            echo (json_encode(array("error" => "could not calculate interest"), true));
        } else {
            echo (json_encode(array('interest' => $total_interest), true));
        }
    }


    public function getSASInterest($amount, $date_end, $date_basis, $commencement_date, $tr_number)
    {
        $tc1 = array(array('OR' => array('interest_rate_to >= ' => $commencement_date, 'interest_rate_to' => null)));
        $tc2 = array('interest_rate_from <= ' => $date_end);
        $time_conditions = array(array('AND' => array($tc1, $tc2)));
        $conditions =  array('recursive' => -1, "conditions" => array('trn_number' => $tr_number, $time_conditions));
        $interest_list =  $this->Interest->find("all", $conditions);
        error_log("getInterest condition : " . json_encode($conditions, true));
        error_log("getInterest result : " . json_encode($interest_list, true));
        $imax = count($interest_list);
        $total_interest = '0.00';
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
            if ($interest_rate != null) {
                $params = array(
                    "amount"            => $amount,
                    "new_date"            => $end_date_interest,
                    "interest_rate"        => $interest_rate,
                    "date_basis"        => $date_basis,
                    "commencement_date"    => $start_date_interest,
                );
                error_log("get interest sas call param : " . json_encode($params, true));
                $interest = $this->SAS->curl("register_confirmation_new.sas", $params, false);
                //$interest = $this->SAS->curl("register_confirmation_new.sas", array(),false);
                error_log("sas result get interest : " . $interest);
                if (strpos($interest, 'This request completed with errors') !== false) {
                    //error_log("SAS error on calculation of the interest, Params : ".json_encode($params, true)." for Interest : ".json_encode($interest_list[$i], true));
                    //file_put_contents("/tmp/sas_error_treasury", "\n".date("Y-m-d H:i:s")." : ".$interest);
                    throw new Exception("SAS error on calculation of the interest");
                } else {
                    $interest = mb_convert_encoding($interest, "UTF-8"); // to remove \ufeff
                    $interest = trim($interest); // to remove \r
                    $interest = preg_replace("/[^-0-9\,\.]/", '', $interest);
                    if (strpos($interest, '.') === false) {
                        $interest = $interest . ".00";
                    }
                    //$total_interest +=  (double)filter_var($interest, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    //error_log("interest for ".json_encode($params, true)." : ".$interest." => total => ".$total_interest);
                    $total_interest = bcadd($interest, $total_interest, 2);
                }
            }
        }
        error_log("get interest result : " . json_encode($total_interest, true));
        return $total_interest;
    }

    function getTax()
    {
        $tr = $this->Transaction->getTransactionById($this->request['data']['Transaction']['tr_number']);
        $Taxes = ClassRegistry::init('Treasury.Tax');
        $mandate_id = $tr['Transaction']['mandate_ID'];
        $cpty_id = $tr['Transaction']['cpty_id'];
        $interest = $this->request['data']['Transaction']['interest'];
        $tax_rates = $Taxes->getTaxByMandateCpty($mandate_id, $cpty_id);
        $tax = '';
        if (isset($tax_rates['Tax'])) {
            $tax_rate = str_replace(',', '', $tax_rates['Tax']['tax_rate']);
            $tax_rate = floatval($tax_rate) / 100;
            $interest = str_replace(',', '', $interest);
            $interest = floatval($interest);

            $tax = $tax_rate * $interest;
            if ($tax < 0) {
                $tax = '0.00';
            } else {
                $tax = round($tax, 2);
            }
        }
        die(json_encode(array("tax" => $tax), true));
    }

    function callOriginalTrn($tr_number_call)
    {
        @$this->validate_param('int', $tr_number_call);
        $tr_call = $this->Transaction->getTransactionById($tr_number_call);

        $tr_original = $this->Transaction->find('first', array(
            'conditions' =>
            array(
                'Transaction.tr_state' => "Called",
                'Transaction.reinv_group' => $tr_call['Transaction']['source_group'],
            )
        ));
        if (empty($tr_original)) {
            die(json_encode(array('error' => $tr_number_call, 'data' => $tr_number_call), true));
        }
        die(json_encode(array('success' => $tr_original['Transaction']['tr_number'], 'data' => $tr_original['Transaction']), true));
    }

    function callIsAutomaticFixing($tr_number)
    {
        @$this->validate_param('int', $tr_number);
        $tr = $this->Transaction->getTransactionById($tr_number);
        $ctpy_id = $tr['Transaction']['cpty_id'];
        unset($tr['Transaction']['cpty_id']);
        $cpty = $this->Counterparty->getCounterpartyById($ctpy_id);
        die($cpty['Counterparty']['automatic_fixing']);
    }

    // ajax request, return the interest. 2nd parameter amount ignored. used in treasurytransactions/callconf
    function callInterest($tr_number, $amount)
    {
        @$this->validate_param('int', $tr_number);
        @$this->validate_param('decimal', $amount);
        $tr = $this->Transaction->getTransactionById($tr_number);

        $interest_rate = $tr['Transaction']['interest_rate'];
        if (empty($interest_rate)) {
            die(json_encode(array('empty' => 'transaction #' . $tr_number . ' has no interest rate.'), true));
        }
        $date_basis = $tr['Transaction']['date_basis'];
        if (empty($date_basis)) {
            die(json_encode(array('empty' => 'transaction #' . $tr_number . ' has no date basis.'), true));
        }
        if (empty($tr['Transaction']['commencement_date'])) {
            die(json_encode(array('empty' => 'transaction #' . $tr_number . ' has no commencement date.'), true));
        }
        $amount = $tr['Transaction']['amount'];
        $commencement_date = $tr['Transaction']['commencement_date'];
        $commencement_date = explode('/', $commencement_date);
        $commencement_date = $commencement_date[2] . "-" . $commencement_date[1] . "-" . $commencement_date[0];
        if (empty($tr['Transaction']['maturity_date'])) {
            die(json_encode(array('empty' => 'transaction #' . $tr_number . ' has no maturity date.'), true));
        }
        $maturity_date = $tr['Transaction']['maturity_date'];
        $maturity_date = explode('/', $maturity_date);
        $maturity_date = $maturity_date[2] . "-" . $maturity_date[1] . "-" . $maturity_date[0];
        $date_end = $maturity_date;
        $error = false;
        $total_interest = 0.00;
        try {
            $total_interest = $this->getSASInterest($amount, $date_end, $date_basis, $commencement_date, $tr['Transaction']['tr_number']);
        } catch (Exception $e) {
            $error = true;
        }
        if ($error) {
            echo (json_encode(array("error" => "could not calculate interest"), true));
        } else {
            if (strpos($total_interest, '.') === false) {
                $total_interest = $total_interest . ".00";
            }
            die(json_encode(array("success" => $total_interest), true));
        }
    }

    function callTax($tr_number, $interest)
    {
        @$this->validate_param('int', $tr_number);
        @$this->validate_param('decimal', $interest);
        $tr = $this->Transaction->getTransactionById($tr_number);
        $Taxes = ClassRegistry::init('Treasury.Tax');
        $mandate_id = $tr['Transaction']['mandate_ID'];
        $cpty_id = $tr['Transaction']['cpty_id'];
        $tax_rates = $Taxes->getTaxByMandateCpty($mandate_id, $cpty_id);
        $tax = 0.00;
        if (isset($tax_rates['Tax'])) {
            $tax_rates['Tax']['tax_rate'] = str_replace(',', '', $tax_rates['Tax']['tax_rate']);
            $interest = str_replace(',', '', $interest);
            $tax_rate = floatval($tax_rates['Tax']['tax_rate']) / 100;
            $interest = floatval($interest);
            $tax = $tax_rate * $interest;
            $tax = round($tax, 2);
            if ($tax < 0.00) {
                $tax = 0.00;
            }
        }
        printf("%.2f", $tax);
        exit();
    }

    function export_limit_monitor_excel($mandategroup, $date)
    {
        @$this->validate_param('int', $mandategroup);
        @$this->validate_param('date', $date);
        //date must be Y-m-d
        //limits based on date & portfolio
        $limits = $this->Limit->getByCounterparties($date, $mandategroup);
        if (empty($limits)) {
            $this->Session->setFlash("Nothing to export for this portfolio", "flash/error");
            $this->redirect($this->referer());
        }
        $portfolioSize = 0;
        $portfolioConcentrationUnit = 'ABS';
        $portfolioMaxConcentration = '';
        if (!empty($limits['portfolioSize'])) $portfolioSize = $limits['portfolioSize'];
        if (!empty($limits['portfolioConcentrationUnit'])) $portfolioConcentrationUnit = $limits['portfolioConcentrationUnit'];
        if (!empty($limits['portfolioMaxConcentration'])) {
            $portfolioMaxConcentration = $limits['portfolioMaxConcentration'];
            if ($portfolioConcentrationUnit == 'PCT') {
                $portfolioMaxConcentration *= 100;
                //$portfolioMaxConcentration = $portfolioSize * $limits['portfolioMaxConcentration'];
            }
        }
        $limits_monitor_excel = array();
        foreach ($limits['counterpartygroups'] as $lim) {
            $status = empty($lim["limit"]['status']) ? 'No breach' : 'Breach';
            $detail = "ok";
            if (!empty($lim["limit"]['status'])) {
                $detail = $this->get_limit_monitor_detail($lim["limit"]['status']);
            }
            $limits_monitor_excel[] = array("Limits" => array(
                "#" => $lim["limit"]["limit_ID"],
                "Counterparty or Group" => $lim["CounterpartyGroup"]["counterpartygroup_name"],
                "Retained LT" => $lim["limit"]["rating_lt"],
                "Retained ST" => $lim["limit"]["rating_st"],
                "Max Maturity (days)" => $lim["limit"]["max_maturity"],
                "Limit (in EUR)" => $lim["limit"]["limit_eur"],
                "Exposure (in EUR)" => $lim["CounterpartyGroup"]["exposure"],
                "Portfolio Concentration" => $lim["CounterpartyGroup"]["concentration"],
                "Limit Available (in EUR)" => $lim["CounterpartyGroup"]["limit_available"],
                "Status" => $status,
                "detail" => $detail
            ));
        }
        foreach ($limits['counterparties'] as $lim) {
            $limit = $lim["limits"][0];
            $newline = array("Limits" => array(
                "#" => $limit["limit_ID"],
                "Counterparty or Group" => $lim["counterparty"]["cpty_name"],
                "Retained LT" => $limit["rating_lt"],
                "Retained ST" => $limit["rating_st"],
                "Max Maturity (days)" => $limit["max_maturity"],
                "Limit (in EUR)" => $limit["limit_eur"],
                "Exposure (in EUR)" => $lim["counterparty"]["exposure"],
                "Portfolio Concentration" => $lim["counterparty"]["concentration"],
                "Limit Available (in EUR)" => $lim["counterparty"]["limit_available"]
            ));
            if (empty($lim["counterparty"]['status'])) {
                $newline["Limits"]['status'] = 'No breach';
                $newline["Limits"]['detail'] = "ok";
            } else {
                $newline["Limits"]['status'] = 'Breach';
                $newline["Limits"]['detail'] = $this->get_limit_monitor_detail($lim["counterparty"]['status']);
            }
            $limits_monitor_excel[] = $newline;
        }

        $filename = "export_limit_monitor_" . $date . "-" . date('Y_m_d_h_i_s') . ".xlsx";
        $filepath = WWW . DS . 'data' . DS . 'treasury' . DS . 'export' . DS . $filename;
        $this->Spreadsheet->generateExcel($limits_monitor_excel, array('Limits'), $filepath);

        DownloadLib::Download($filepath);
        exit();
    }

    public function export_trn()
    {
        error_log("generateExcelFromQuery export queries");
        $this->layout = 'ajax';
        if (!empty($this->request->data)) {
            $tr_number = explode(' ', $this->request->data['Transaction']['tr_number']);
            $layout = explode(' ', $this->request->data['Transaction']['layout']);

            $transactions = $this->Transaction->find("all", array(
                'conditions' => array('Transaction.tr_number' => $tr_number),
                'fields' => $layout,
            ));
            $filepath = '/var/www/html/data/treasury/export/transactions_' . time() . '.xlsx';
            try {
                $this->Spreadsheet->generateExcelFromQuery($transactions, array('Transactions'), $filepath);
            } catch (Exception $e) {
                error_log("generateExcelFromQuery " . $e->getMessage());
            }
            echo $filepath;
        }
    }


    public function get_limit_monitor_detail($array)
    {

        @$this->validate_param('array', $array);
        $title = '';
        foreach ($array as $error) {
            if ($title) $title .= ' + ';
            foreach ($error as $key => $val) $title .= $key . ' (' . $val . ')';
        }
        if ($title) $title = 'Limit Breach: ' . $title;
        return $title;
    }

    /*
		called on new bond screen when hiting refresh. updates somes vals on the screen according to already given vals
	*/
    public function refresh_new_bond()
    {
        $nominal = str_replace(',', '', $this->request['data']['Bond']['nominal']);
        $maturity_date = $this->request['data']['Bond']['maturity_date'];
        $settle_date = $this->request['data']['Bond']['settle_date'];
        $first_coupon_accr_date = $this->request['data']['Bond']['first_coupon_accr_date'];
        $first_coupon_payment_date = $this->request['data']['Bond']['first_coupon_payment_date'];
        $date_basis = $this->request['data']['Bond']['date_basis'];
        $coupon_rate = str_replace(',', '', $this->request['data']['Bond']['coupon_rate']); // no ',' for thousands
        $coupon_freq = $this->request['data']['Bond']['coupon_freq'];
        $date_convention = $this->request['data']['Bond']['date_convention'];
        $tax_rate = $this->request['data']['Bond']['tax_rate'];
        $purchase_price = str_replace(',', '', $this->request['data']['Bond']['purchase_price']);

        $maturity_date = $this->date_for_sas($maturity_date);
        $settle_date = $this->date_for_sas($settle_date);
        $first_coupon_accr_date = $this->date_for_sas($first_coupon_accr_date);
        $first_coupon_payment_date = $this->date_for_sas($first_coupon_payment_date);

        $bond_accrued = $this->callSASDateCheck($nominal, $maturity_date, $settle_date, $first_coupon_accr_date,     $first_coupon_payment_date, $date_basis, $coupon_rate, $coupon_freq, $date_convention, $tax_rate, $purchase_price);

        echo json_encode($bond_accrued, true);
        exit(); //no view
    }

    public function callSASDateCheck($nominal, $maturity_date, $settle_date, $first_coupon_accr_date,     $first_coupon_payment_date, $date_basis, $coupon_rate, $coupon_freq, $date_convention, $tax_rate, $purchase_price)
    {
        @$this->validate_param('decimal', $nominal);
        @$this->validate_param('date', $maturity_date);
        @$this->validate_param('date', $settle_date);
        @$this->validate_param('date', $first_coupon_accr_date);
        @$this->validate_param('date', $first_coupon_payment_date);
        @$this->validate_param('string', $date_basis);
        @$this->validate_param('decimal', $coupon_rate);
        @$this->validate_param('string', $coupon_freq);
        @$this->validate_param('string', $date_convention);
        @$this->validate_param('decimal', $tax_rate);
        @$this->validate_param('decimal', $purchase_price);
        if (empty($nominal) || empty($maturity_date) || empty($settle_date) || empty($first_coupon_accr_date) || empty($first_coupon_payment_date) || empty($date_basis) || empty($coupon_rate) || empty($coupon_freq) || empty($date_convention) || empty($tax_rate) || empty($purchase_price)) {
            //all values are mandatory for SAS
            return array('error' => false, 'accrued_coupon' => '', 'total_purchase_amount' => '', 'tax' => '', 'coupon' => '');
        }

        if ($coupon_freq == "semi-annually") {
            $coupon_freq = "semi_annually";
        }
        if ($date_convention == "Modified Following") {
            $date_convention = "Modified_Following";
        }
        if ($date_convention == "Modified Preceding") {
            $date_convention = "Modified_Preceding";
        }

        $params = array(
            "nominal" =>         $nominal,
            "maturity_date" =>     $maturity_date,
            "settle_date" =>     $settle_date,
            "first_coupon_accr_date" =>     $first_coupon_accr_date,
            "first_coupon_payment_date" =>     $first_coupon_payment_date,
            "date_basis" =>     $date_basis,
            "coupon_rate" =>     $coupon_rate,
            "coupon_freq" =>     $coupon_freq,
            "date_convention" =>     $date_convention,
            "tax_rate" => $tax_rate,
            "purchase_price" => $purchase_price,
            "save"            => 0,
        );
        $bond_accrued_sasResult = $this->SAS->curl("bonds_accrued_calculation.sas", $params, false);
        $bond_accrued = array();
        if (strpos($bond_accrued_sasResult, 'This request completed with errors') !== false) {
            //$bond_accrued = array('error' => true, 'message' => $bond_accrued_sasResult);
            return array('error' => false, 'accrued_coupon' => '', 'total_purchase_amount' => '', 'tax' => '', 'coupon' => '');
        } else {
            App::uses("simple_html_dom", "Vendor");
            $html = new simple_html_dom();
            $a = $html->load($bond_accrued_sasResult);
            $warning = $a->find('#error');
            $error = false;
            foreach ($warning as $m) {
                //$error = trim($m->innertext);
                //$error = "<strong>ERROR:</strong>Maturity Date must be equal to the final (last) Calculated Coupon Payment Date.";
                $error = "Maturity Date must be equal to the final (last) Calculated Coupon Payment Date.";
            }
            if (!empty($error)) {
                $bond_accrued = array('error' => true, 'message' => $error);
            } else {
                $bond_accrued_sasResult = mb_convert_encoding($bond_accrued_sasResult, "UTF-8"); // to remove \ufeff
                $bond_accrued_sasResult = trim($bond_accrued_sasResult); // to remove \r
                $bond_accrued_sasResult = explode(" ", $bond_accrued_sasResult);
                //error_log("sas bond calc : ".print_r($bond_accrued_sasResult, true));
                foreach ($bond_accrued_sasResult as &$val) {
                    $val = (float)filter_var($val, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $val = number_format(floatval($val), 2, '.', '');
                }
                /*
					put "&accrued_coupon";
					put "&total_purchase_amount";
					put "&tax";
					put "&coupon"; 
				*/
                $bond_accrued = array('error' => false, 'accrued_coupon' => $bond_accrued_sasResult[0], 'total_purchase_amount' => $bond_accrued_sasResult[1], 'tax' => $bond_accrued_sasResult[2], 'coupon' => $bond_accrued_sasResult[3]);
            }
        }
        return $bond_accrued;
    }

    public function getBondData()
    {
        $ISIN = $this->request['data']['Bond']['ISIN'];
        $bond = $this->Bond->find("first", array('conditions' => array('Bond.bond_id' => $ISIN), 'recursive' => 0));
        if (empty($bond)) {
            echo json_encode(array('error' => true), true);
        } else {
            echo json_encode($bond, true);
        }
        exit(); // no view
    }

    /*mandatory yyyy-mm-dd format for dates to sas*/
    public function date_for_sas($date)
    {
        @$this->validate_param('string', $date);
        if (strpos($date, '/') !== false) {
            $date = explode('/', $date);
            $date = $date[2] . "-" . $date[1] . "-" . $date[0];
        }
        return $date;
    }

    public function new_isin_unique()
    {
        $isin = $this->request['data']['Bond']['ISIN'];
        $bonds = $this->Bond->find('first', array('conditions' => array('Bond.ISIN' => $isin, 'state != ' => 'Deleted'), 'recursive' => -1));
        $unique = empty($bonds);
        $msg = '';
        if (!$unique) {
            if ($bonds['Bond']['state'] == 'Confirmed') {
                $msg = "ISIN code already exists in the database. Please select it from the dropdown.";
            } elseif ($bonds['Bond']['state'] == 'Created') {
                $msg = "ISIN code has been created in the database. Please finalize the previously entered transaction with this ISIN before entering a new one.";
            }
        }
        echo json_encode(array('unique' => $unique, 'msg' => $msg));
        exit(); // no view
    }

    public function check_new_bond()
    {
        // dates checks
        $maturity_date = strtotime($this->date_for_sas($this->request['data']['Bond']['maturity_date']));
        $issuedate = strtotime($this->date_for_sas($this->request['data']['Bond']['issuedate']));
        $trade_date = strtotime($this->date_for_sas($this->request['data']['TransactionBond']['trade_date']));
        $settlement_date = strtotime($this->date_for_sas($this->request['data']['TransactionBond']['settlement_date']));
        $first_coupon_payment_date = strtotime($this->date_for_sas($this->request['data']['Bond']['first_coupon_payment_date']));
        $first_coupon_accrual_date = strtotime($this->date_for_sas($this->request['data']['Bond']['first_coupon_accrual_date']));

        $error = array();
        if (!empty($maturity_date) && !empty($issuedate) && ($maturity_date < $issuedate)) {
            $error[] = array('type' => 'data', 'message' => 'Maturity date cannot be before Issue date.', 'highlight' => array('issuedate', 'maturity_date_bond'));
        }
        if (!empty($maturity_date) && !empty($trade_date) && ($maturity_date < $trade_date)) {
            $error[] = array('type' => 'data', 'message' => 'Maturity date cannot be before Trade date.', 'highlight' => array('trade_date', 'maturity_date_bond'));
        }
        if (!empty($maturity_date) && !empty($settlement_date) && ($maturity_date < $settlement_date)) {
            $error[] = array('type' => 'data', 'message' => 'Maturity date cannot be before Settlement date.', 'highlight' => array('settlement_date', 'maturity_date_bond'));
        }
        if (!empty($maturity_date) && !empty($first_coupon_payment_date) && ($maturity_date < $first_coupon_payment_date)) {
            $error[] = array('type' => 'data', 'message' => 'Maturity date cannot be before First Coupon Payment date.', 'highlight' => array('first_coupon_payment_date', 'maturity_date_bond'));
        }

        if (!empty($first_coupon_payment_date) && !empty($first_coupon_accrual_date) && ($first_coupon_payment_date < $first_coupon_accrual_date)) {
            $error[] = array('type' => 'data', 'message' => 'First Coupon Payment date cannot be before First Coupon Accrual date.', 'highlight' => array('first_coupon_payment_date', 'first_coupon_accrual_date'));
        }
        if (!empty($first_coupon_payment_date) && !empty($issuedate) && ($first_coupon_payment_date < $issuedate)) {
            $error[] = array('type' => 'data', 'message' => 'First Coupon Payment date cannot be before Issue date.', 'highlight' => array('issuedate', 'first_coupon_payment_date'));
        }

        // mandatory fields
        if (empty($this->request['data']['Bond']['issuer'])) {
            $error[] = array('type' => 'field', 'mandatory' => array('issuer'));
        }
        if (empty($this->request['data']['Bond']['issuedate'])) {
            $error[] = array('type' => 'field', 'mandatory' => array('issuedate'));
        }
        if (empty($this->request['data']['Bond']['first_coupon_accrual_date'])) {
            $error[] = array('type' => 'field', 'mandatory' => array('first_coupon_accrual_date'));
        }
        if (empty($this->request['data']['Bond']['first_coupon_payment_date'])) {
            $error[] = array('type' => 'field', 'mandatory' => array('first_coupon_payment_date'));
        }
        if (empty($this->request['data']['Bond']['ccy'])) {
            $error[] = array('type' => 'field', 'mandatory' => array('ccy'));
        }
        if (empty($this->request['data']['Bond']['maturity_date'])) {
            $error[] = array('type' => 'field', 'mandatory' => array('maturity_date_bond'));
        }
        if (empty($this->request['data']['Bond']['coupon_rate'])) {
            $error[] = array('type' => 'field', 'mandatory' => array('coupon_rate'));
        }
        if (empty($this->request['data']['Bond']['coupon_frequency'])) {
            $error[] = array('type' => 'field', 'mandatory' => array('coupon_frequency'));
        }
        if (empty($this->request['data']['Bond']['date_basis'])) {
            $error[] = array('type' => 'field', 'mandatory' => array('date_basis'));
        }
        if (empty($this->request['data']['Bond']['date_convention'])) {
            $error[] = array('type' => 'field', 'mandatory' => array('date_convention'));
        }
        if (empty($this->request['data']['Bond']['tax_rate'])) {
            $error[] = array('type' => 'field', 'mandatory' => array('tax_rate'));
        }
        if (empty($this->request['data']['Bond']['exist_isin']) && empty($this->request['data']['Bond']['new_isin'])) // one or the other minimum
        {
            $error[] = array('type' => 'field', 'mandatory' => array('new_isin', 'exist_isin'));
        }
        if (empty($this->request['data']['TransactionBond']['cmp_ID'])) {
            $error[] = array('type' => 'field', 'mandatory' => array('TransactionBondCmpID'));
        }
        if (empty($this->request['data']['TransactionBond']['trade_date'])) {
            $error[] = array('type' => 'field', 'mandatory' => array('trade_date'));
        }
        if (empty($this->request['data']['TransactionBond']['settlement_date'])) {
            $error[] = array('type' => 'field', 'mandatory' => array('settlement_date'));
        }
        if (empty($this->request['data']['TransactionBond']['nominal'])) {
            $error[] = array('type' => 'field', 'mandatory' => array('nominal'));
        }
        if (empty($this->request['data']['TransactionBond']['purchase_price'])) {
            $error[] = array('type' => 'field', 'mandatory' => array('TransactionBondPurchasePrice'));
        }
        if (empty($this->request['data']['TransactionBond']['accrued_coupon'])) {
            $error[] = array('type' => 'field', 'mandatory' => array('TransactionBondAccruedCoupon'));
        }
        if (empty($this->request['data']['TransactionBond']['total_purchase_amount'])) {
            $error[] = array('type' => 'field', 'mandatory' => array('TransactionBondTotalPurchaseAmount'));
        }
        if (empty($this->request['data']['TransactionBond']['coupon'])) {
            $error[] = array('type' => 'field', 'mandatory' => array('TransactionBondCoupon'));
        }
        if (empty($this->request['data']['TransactionBond']['tax_amount'])) {
            $error[] = array('type' => 'field', 'mandatory' => array('trn_tax_amount'));
        }
        if (empty($this->request['data']['TransactionBond']['yield'])) {
            $error[] = array('type' => 'field', 'mandatory' => array('TransactionBondYield'));
        }
        if (empty($this->request['data']['TransactionBond']['cmp_ID'])) {
            $error[] = array('type' => 'field', 'mandatory' => array('cmp_ID'));
        }
        if (empty($this->request['data']['Transaction']['mandate_ID'])) {
            $error[] = array('type' => 'field', 'mandatory' => array('TransactionMandateID'));
        }
        if (empty($this->request['data']['Transaction']['cpty_id'])) {
            $error[] = array('type' => 'field', 'mandatory' => array('TransactionCptyId'));
        }

        if (!empty($this->request['data']['Bond']['bond_id'])) {
            //edit and isin already exist (any state) and different from before
            if ($this->request['data']['Bond']['bond_id'] != $this->request['data']['Bond']['current_bond_id']) {
                $bond_db = $this->Bond->find('first', array('conditions' => array('bond_id' => $this->request['data']['Bond']['bond_id']), 'recursive' => -1, 'fields' => array('bond_id')));
                if (!empty($bond_db)) {
                    //already exist
                    $error[] = array('type' => 'datasas', 'message' => "ISIN code already exist in the database", 'highlight' => array('new_isin'));
                }
            }
        }

        // call sas to check last error msg
        $nominal = str_replace(',', '', $this->request['data']['TransactionBond']['nominal']);
        $maturity_date = $this->request['data']['Bond']['maturity_date'];
        $settle_date = $this->request['data']['TransactionBond']['settlement_date'];
        $first_coupon_accr_date = $this->request['data']['Bond']['first_coupon_accrual_date'];
        $first_coupon_payment_date = $this->request['data']['Bond']['first_coupon_payment_date'];
        $date_basis = $this->request['data']['Bond']['date_basis'];
        $coupon_rate = str_replace(',', '', $this->request['data']['Bond']['coupon_rate']); // no ',' for thousands
        $coupon_freq = $this->request['data']['Bond']['coupon_frequency'];
        $date_convention = $this->request['data']['Bond']['date_convention'];
        $tax_rate = $this->request['data']['Bond']['tax_rate'];
        $purchase_price = str_replace(',', '', $this->request['data']['TransactionBond']['purchase_price']);

        $maturity_date = $this->date_for_sas($maturity_date);
        $settle_date = $this->date_for_sas($settle_date);
        $first_coupon_accr_date = $this->date_for_sas($first_coupon_accr_date);
        $first_coupon_payment_date = $this->date_for_sas($first_coupon_payment_date);
        $bond_accrued = $this->callSASDateCheck($nominal, $maturity_date, $settle_date, $first_coupon_accr_date,     $first_coupon_payment_date, $date_basis, $coupon_rate, $coupon_freq, $date_convention, $tax_rate, $purchase_price);
        if (!empty($bond_accrued) && !empty($bond_accrued['error']) && !empty($bond_accrued['message'])) {
            $error[] = array('type' => 'data', 'message' => $bond_accrued['message'], 'highlight' => array('maturity_date_bond', 'first_coupon_payment_date'));
        }

        echo json_encode($error, true);
        exit();
    }

    public function download_file()
    {
        $this->autoRender = false;
        $file = $this->params['url']['file'];
        DownloadLib::Download($file);
        exit();
    }

    public function pirat_number_exists()
    {
        $pirat_number = intval($this->request['data']['pirat_number']);
        $this->autoRender = false;
        $exists = array('exists' => false);
        $ratings = $this->Rating->find("first", array('conditions' => array('Rating.pirat_number' => $pirat_number)));
        if (!empty($ratings)) {
            $exists['exists'] = true;
        }
        echo json_encode($exists, true);
        exit();
    }

    public function get_call_calculations()
    {
        $this->autoRender = false;
        $return = array('repaid' => '-', 'reinvested' => '-');
        if (!empty($this->request->data['Transaction']['tr_number']) && !empty($this->request->data['Transaction']['principal']) && !empty($this->request->data['Transaction']['interest']) && !empty($this->request->data['Transaction']['tax'])) {
            $principal = str_replace(',', '', $this->request->data['Transaction']['principal']);
            $interest = str_replace(',', '', $this->request->data['Transaction']['interest']);
            $tax = str_replace(',', '', $this->request->data['Transaction']['tax']);
            $repaid = bcadd($principal, $interest, 2);
            $repaid = bcsub($repaid, $tax, 2);
            $return['repaid'] = $repaid;
            $tr = $this->Transaction->find('first', array('conditions' => array('tr_number' => intval($this->request->data['Transaction']['tr_number'])), 'fields' => array('amount')));
            if (!empty($tr)) {
                $amount = $tr['Transaction']['amount'];
                $reinvested = bcsub($amount, $principal, 2);
                $return['reinvested'] = $reinvested;
            }
        }
        echo json_encode($return);
        exit();
    }
}
