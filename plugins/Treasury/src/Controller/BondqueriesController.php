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
class BondqueriesController extends AppController
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
    // Find all transactions
    function all($layout = null)
    {

        @$this->validate_param('string', $layout);
        //reset last submitted values
        if ($this->Session->read('QueryBondForm') && !empty($_GET['reset'])) {
            $this->Session->write('QueryBondForm', null);
        }

        //saving/reloading last submitted form values
        if ($this->request->is('post') && !empty($this->request->data)) {
            $this->Session->write('QueryBondForm', $this->request->data);
        } elseif ($this->Session->check('QueryBondForm')) {
            $this->request->data = $this->Session->read('QueryBondForm');
        }

        //Form options
        //TR Types
        $this->set('tr_types', $this->Bondtransaction->getTypes());

        //TR States
        $this->set('tr_states', $this->Bondtransaction->getStates());
        $this->set('issuer_list', $this->Bond->getIssuers());


        //TR Mandate and portfolio list
        $mandateAndPortfolioList = $this->Mandate->getMandateList(); //first the mandates
        $mandateAndPortfolioList["0"] = "-------------------"; //then a separator
        $portfoliotList = $this->MandateGroup->getMandateGroupList();
        $mandateGroup = ClassRegistry::init('Treasury.MandateGroup');
        foreach ($portfoliotList as $idPortfolio => $portfolio) {
            $mandate_id_list = implode(',', $mandateGroup->getMandateIDsByGroup($idPortfolio));
            $mandateAndPortfolioList[$mandate_id_list . ','] = 'Portfolio ' . $portfolio; //then the protfolios (groupnames)
        }

        $this->set('mandates_portfolio_list', $mandateAndPortfolioList);

        //TR Compartments:: 
        if (isset($this->request->data['Bondtransaction']['mandate_ID']) && !empty($this->request->data['Bondtransaction']['mandate_ID'])) {
            $mandate_ids = $this->request->data['Bondtransaction']['mandate_ID'];
            $compartment_list = array();
            if (strpos($mandate_ids, ',') != false) {
                $mandate_ids = trim($mandate_ids, ",");
                $mandate_ids = explode(',', $mandate_ids);

                foreach ($mandate_ids as $mandate_id) {
                    $compartment_list_tmp = $this->Compartment->getcmpbymandate($mandate_id);
                    foreach ($compartment_list_tmp as $k => $v) {
                        $compartment_list[$k] = $v;
                    }
                }
            } else {
                $compartment_list = $this->Compartment->getcmpbymandate($this->request->data['Bondtransaction']['mandate_ID']);
            }
        } else {
            $compartment_list = $this->Compartment->getCompartmentList();
        }
        $compartments = $compartment_list;
        $this->set('cmp_list', $compartments);
        //TR Counterparties
        //$this->set('cpty_list', (isset($this->request->data['Transaction']['mandate_ID']) && !empty($this->request->data['Transaction']['mandate_ID'])) ? $this->Mandate->getcptybymandate($this->request->data['Transaction']['mandate_ID']) : $this->Counterparty->getCounterpartyList());
        if (isset($this->request->data['Bondtransaction']['mandate_ID']) && !empty($this->request->data['Bondtransaction']['mandate_ID'])) {
            $mandate_ids = $this->request->data['Bondtransaction']['mandate_ID'];
            $cpty_list = array();
            if (strpos($mandate_ids, ',') != false) {
                $mandate_ids = trim($mandate_ids, ",");
                $mandate_ids = explode(',', $mandate_ids);

                foreach ($mandate_ids as $mandate_id) {
                    $cpty_list_tmp = $this->Mandate->getcptybymandate($mandate_id);
                    foreach ($cpty_list_tmp as $k => $v) {
                        $cpty_list[$k] = $v;
                    }
                }
            } else {
                $cpty_list = $this->Mandate->getcptybymandate($this->request->data['Bondtransaction']['mandate_ID']);
            }
        } else {
            $cpty_list = $this->Counterparty->getCounterpartyList();
        }
        $this->set('cpty_list', $cpty_list);
        // condition based on filter values
        if (!empty($this->request->data)) {
            $conditions = array();

            if (!empty($this->request->data['Bondtransaction'])) foreach ($this->request->data['Bondtransaction'] as $key => $value) {
                if ($key == 'mandate_ID' or $key == 'cmp_ID' or $key == 'cpty_id' or $key == 'instr_num' or $key == 'tr_number') $key = 'Bondtransaction.' . $key;
                if ($key == "Bondtransaction.mandate_ID") {
                    if (is_string($value) && (strpos($value, ',') != false)) //remove ',' at the end of string if portfolio
                    {
                        //$value = implode($value);
                        $value = trim($value, ",");
                        $value = explode(',', $value);
                    } else {
                        $value = trim($value, ",");
                    }
                }

                if (!empty($value)) {
                    if ($key != 'tr_number') {
                        $conditions[$key] = $value;
                    } else {
                        $conditions[$key] = explode(',', $value);
                    }
                }
            }
            if (!empty($this->request->data['Bond'])) foreach ($this->request->data['Bond'] as $key => $value) {
                if ($key == 'ISIN') $key = 'Bond.' . $key;


                if (!empty($value)) {
                    if ($key != 'ISIN') {
                        $conditions[$key] = $value;
                    } else {
                        $conditions[$key] = explode(',', $value);
                    }
                }
            }
            if (!empty($conditions['layout'])) // TODO see why i have to remove it
            {
                unset($conditions['layout']);
            }
            $custom_fields = null;
            if (!empty($conditions['customfields'])) // TODO see why i have to remove it
            {
                $custom_fields = $conditions['customfields'];
                unset($conditions['customfields']);
            }
            if (isset($conditions['tr_state']) && $conditions['tr_state'] == 'All unprocessed') {
                unset($conditions['tr_state']);
                $conditions['processed'] = 'No';
            }
            if (!empty($this->request->data['Dates']['issue_from'])) $conditions['Bond.issue_date >='] = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['Dates']['issue_from'])));
            if (!empty($this->request->data['Dates']['issue_to'])) $conditions['Bond.issue_date <='] = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['Dates']['issue_to'])));
            if (!empty($this->request->data['Dates']['settl_from'])) $conditions['settlement_date >='] = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['Dates']['settl_from'])));
            if (!empty($this->request->data['Dates']['settl_to'])) $conditions['settlement_date <='] = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['Dates']['settl_to'])));
            if (!empty($this->request->data['Dates']['mat_from'])) $conditions['maturity_date >='] = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['Dates']['mat_from'])));
            if (!empty($this->request->data['Dates']['mat_to'])) $conditions['maturity_date <='] = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['Dates']['mat_to'])));
        }

        if ($this->request->is('post') || !empty($layout)) {
            $this->Bondtransaction->fieldsToDisplay =  array('*');

            $transactions = $this->Bondtransaction->find('all', array(
                'conditions' => $conditions,
                'fields' => $this->Bondtransaction->fieldsToDisplay,
                'recursive' => 1,
                'order' => 'Bondtransaction.tr_number DESC',
            ));

            $layouts = array();
            $layouts['default'] = array(
                'TRN' => 'Bondtransaction.tr_number',
                'ISIN' => 'Bond.ISIN',
                'Instruction number' => 'Bondtransaction.instr_num',
                'Type' => 'Bondtransaction.tr_type',
                'State' => 'Bondtransaction.tr_state',
                'Mandate' => 'Mandate.mandate_name',
                'Issuer' => 'Bond.issuer',
                'Counterparty' => 'Counterparty.cpty_name',
                'Compartment' => 'Compartment.cmp_name',
                'CCY' => 'Bondtransaction.currency',
                'Nominal' => 'Bondtransaction.nominal_amount',
                'Coupon Rate, %' => 'Bond.coupon_rate',
                'Purchase Price, %' => 'Bondtransaction.purchase_price',
                'Purchase Amount' => 'Bondtransaction.purchase_amount',
                'Total Purchase Amount' => 'Bondtransaction.total_purchase_amount',
                'Issue Date' => 'Bond.issue_date',
                'Settlement Date' => 'Bondtransaction.settlement_date',
                'Maturity Date' => 'Bond.maturity_date',
                'First Coupon Accrual Date' => 'Bond.first_coupon_accrual_date',
                'First Coupon Payment Date' => 'Bond.first_coupon_payment_date',
                'Parent TRN' => 'Bondtransaction.parent_id',
                'Coupon Frequency' => 'Bond.coupon_frequency',
                'Date Basis' => 'Bond.date_basis',
                'Yield, %' => 'Bondtransaction.yield_to_maturity',
                'Reference Rate, %' => 'Bondtransaction.reference_rate',
                'Spread BP' => 'Bondtransaction.spread_bp',
                'Benchmark' => 'Bondtransaction.benchmark',
            );
            $layouts['custom3'] = array(
                'TRN' => 'Bondtransaction.tr_number',
                'Country' => 'Bond.country',
                'EOM Coupon' => 'Bondtransaction.accrued_coupon_eom',
                'EOM Tax' => 'Bondtransaction.accrued_tax_eom',
                'Coupon' => 'Bondtransaction.total_coupon',
                'Tax' => 'Bondtransaction.total_tax',
                'Tax Rate %' => 'Bond.tax_rate',
                'Accrued Coupon' => 'Bondtransaction.accrued_coupon_at_purchase	',
                'Date Convention' => 'Bond.date_convention',
                'Issue Size' => 'Bond.issue_size',
                'Covered' => 'Bond.covered',
                'Secured' => 'Bond.secured',
                'Seniority' => 'Bond.seniority',
                'Guarantor' => 'Bond.guarantor',
                'Structured' => 'Bond.structured',
                'Issuer Type' => 'Bond.issuer_type',
                'Issue Rating S&P' => 'Bond.issue_rating_STP',
                'Issue Rating Moodys' => 'Bond.issue_rating_MDY',
                'Issue Rating Fitch' => 'Bond.issue_rating_FIT',
                'Comment' => 'Bond.comment',
            );

            //all available fields, for custom view
            $allfields = array();
            foreach ($layouts as $lyt) {
                foreach ($lyt as $key => $val) {
                    $allfields[$val] = $key;
                }
            }

            asort($allfields);
            $this->set('allfields', $allfields);

            //select the right layout
            $layout = $layouts['default'];
            if (!empty($this->request->data['Bondtransaction']['layout']) && !empty($layouts[$this->request->data['Bondtransaction']['layout']])) {
                $layout = $layouts[$this->request->data['Bondtransaction']['layout']];

                // OR build custom layout
            } elseif ($this->request->data['Bondtransaction']['layout'] == 'custom' && !empty($this->request->data['Bondtransaction']['customfields'])) {
                $cfields = @json_decode($this->request->data['Bondtransaction']['customfields']);

                $layout = array();
                if (!empty($cfields)) foreach ($cfields as $key) {
                    if (!empty($allfields[$key])) {
                        $layout[$allfields[$key]] = $key;
                    }
                }
            }
            $this->set('layout', $layout);
            $this->set('currentlayout', $this->request->data['Bondtransaction']['layout']);



            $reordered = array();
            if (!empty($transactions)) foreach ($transactions as $label => $trn) {
                $line = array();
                $cnt = 0;
                foreach ($layout as $col) {
                    $exp = explode('.', $col);
                    if (count($exp) == 2) {
                        $line['col-' . $cnt . ' ' . $col] = '';
                        if ($col == 'Taxes.tax_rate') {
                            if (empty($trn[$exp[0]][$exp[1]])) $trn[$exp[0]][$exp[1]] = 'N/A';
                            else $trn[$exp[0]][$exp[1]] = $trn[$exp[0]][$exp[1]] . '%';
                        }
                        if (!empty($trn[$exp[0]][$exp[1]])) $line['col-' . $cnt . ' ' . $col] = $trn[$exp[0]][$exp[1]];
                    }
                    $cnt++;
                }
                $reordered[] = $line;
            }

            $this->set('Bondtransactions', $reordered);

            if (empty($transactions)) {
                $this->Session->setFlash("No results", "flash/error");
            }
        }
    }

    function bymaturity()
    {

        /*
         * Get mandates list and append it to array('-1' => '***all***) without reindexing keys with the true parameter
         */
        $this->set($this->Mandate->getMandateList(true));

        if ($this->request->is('post')) {
            if (empty($this->request->data['tqbymaturity']['Mandate_id'])) {
                $this->Session->setFlash('Please select a Mandate', 'flash/error');
                $error = true;
            }

            if (empty($this->request->data['tqbymaturity']['MaturityDateStart'])) {
                $this->Session->setFlash('Please select maturity start date', 'flash/error');
                $error = true;
            }

            if (empty($this->request->data['tqbymaturity']['MaturityDateEnd'])) {
                $this->Session->setFlash('Please select maturity end date', 'flash/error');
                $error = true;
            }

            if (!isset($error)) {
                $sasResult1 = $this->SAS->curl(
                    "F_TransactionQuery_Maturity.sas",
                    array(
                        "Mandate_id"          => $this->request->data['tqbymaturity']['Mandate_id'],
                        "MaturityDateStart"   => $this->request->data['tqbymaturity']['MaturityDateStart'],
                        "MaturityDateEnd"     => $this->request->data['tqbymaturity']['MaturityDateEnd'],
                    ),
                    false
                );

                $this->set('sas1', utf8_encode($sasResult1));
                $this->set('tables', $this->SAS->get_all_tables_from_webout(utf8_encode($sasResult1)));
            }
        }

        if (isset($sasResult1)) {
            $this->set('msg', '');
            $this->set('tab2state', 'active');
            $this->set('tab1state', '');
        } else {
            $this->set('msg', 'Please fill in the query form.');
            $this->set('tab2state', '');
            $this->set('tab1state', 'active');
        }

        $this->set('title_for_layout', 'Transaction Query By Status');
    }

    function bystatus()
    {

        if ($this->request->is('post')) {
            if (empty($this->request->data['tqbystatus']['tr_status'])) {
                $this->Session->setFlash('Please select a status', 'flash/error');
                $error = true;
            }

            if (empty($this->request->data['tqbystatus']['ord'])) {
                $this->Session->setFlash('Please select an order', 'flash/error');
                $error = true;
            }

            if (!isset($error)) {
                $sasResult1 = $this->SAS->curl(
                    "F_TransactionQuery_Status.sas",
                    array(
                        "tr_status"  => $this->request->data['tqbystatus']['tr_status'],
                        "ord"        => $this->request->data['tqbystatus']['ord']
                    ),
                    false
                );
                $this->set('sas1', utf8_encode($sasResult1));
                $this->set('tables', $this->SAS->get_all_tables_from_webout(utf8_encode($sasResult1)));
            }
        }

        if (isset($sasResult1)) {
            $this->set('msg', '');
            $this->set('tab2state', 'active');
            $this->set('tab1state', '');
        } else {
            $this->set('msg', 'Please fill in the query form.');
            $this->set('tab2state', '');
            $this->set('tab1state', 'active');
        }

        $this->set('title_for_layout', 'Transaction Query By Status');
    }

    function bytrn()
    {

        $this->set('title_for_layout', 'Transaction Query By Number');
    }

    function showquerybytrn()
    {
        if ($this->request->is('ajax') and !empty($this->request->data['tqbytrn']['tr_number'])) {
            $sasResult = $this->SAS->curl(
                "F_TransactionQuery_TRN.sas",
                array(
                    "tr_number" => $this->request->data['tqbytrn']['tr_number'],
                ),
                false
            );

            $this->set('sas', utf8_encode($sasResult));

            $tables = $this->SAS->get_all_tables_from_webout($sasResult);

            $this->set('tables', $this->SAS->get_all_tables_from_webout($sasResult));
            $this->layout = 'ajax';
        }
    }
}
