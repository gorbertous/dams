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
class QueriesController extends AppController
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

        @$this->validate_param('int', $reinv_group_id_forced);
        //reset last submitted values
        if ($this->Session->read('QueryForm') && !empty($_GET['reset'])) {
            $this->Session->write('QueryForm', null);
        }

        //saving/reloading last submitted form values
        if ($this->request->is('post') && !empty($this->request->data)) {
            $this->Session->write('QueryForm', $this->request->data);
        } elseif ($this->Session->check('QueryForm')) {
            $this->request->data = $this->Session->read('QueryForm');
        }

        //Form options
        //TR Types
        $this->set('tr_types', $this->Transaction->getTypes());

        //TR States
        $this->set('tr_states', $this->Transaction->getStates());

        //TR Depo Types
        $this->set('depo_types', $this->Transaction->getDepoTerm());

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
        if (isset($this->request->data['Transaction']['mandate_ID']) && !empty($this->request->data['Transaction']['mandate_ID'])) {
            $mandate_ids = $this->request->data['Transaction']['mandate_ID'];
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
                $compartment_list = $this->Compartment->getcmpbymandate($this->request->data['Transaction']['mandate_ID']);
            }
        } else {
            $compartment_list = $this->Compartment->getCompartmentList();
        }
        $compartments = $compartment_list;
        $this->set('cmp_list', $compartments);
        //TR Counterparties
        //$this->set('cpty_list', (isset($this->request->data['Transaction']['mandate_ID']) && !empty($this->request->data['Transaction']['mandate_ID'])) ? $this->Mandate->getcptybymandate($this->request->data['Transaction']['mandate_ID']) : $this->Counterparty->getCounterpartyList());
        if (isset($this->request->data['Transaction']['mandate_ID']) && !empty($this->request->data['Transaction']['mandate_ID'])) {
            $mandate_ids = $this->request->data['Transaction']['mandate_ID'];
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
                $cpty_list = $this->Mandate->getcptybymandate($this->request->data['Transaction']['mandate_ID']);
            }
        } else {
            $cpty_list = $this->Counterparty->getCounterpartyList();
        }
        $this->set('cpty_list', $cpty_list);
        // condition based on filter values
        if (!empty($this->request->data)) {
            $conditions = array();

            if (!empty($this->request->data['Transaction'])) foreach ($this->request->data['Transaction'] as $key => $value) {
                if ($key == 'mandate_ID' or $key == 'cmp_ID' or $key == 'cpty_id' or $key == 'instr_num') $key = 'Transaction.' . $key;
                if ($key == "Transaction.mandate_ID") {
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

            if (isset($conditions['tr_state']) && $conditions['tr_state'] == 'All unprocessed') {
                unset($conditions['tr_state']);
                $conditions['processed'] = 'No';
            }
            if (!empty($this->request->data['Dates']['com_from'])) $conditions['commencement_date >='] = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['Dates']['com_from'])));
            if (!empty($this->request->data['Dates']['com_to'])) $conditions['commencement_date <='] = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['Dates']['com_to'])));
            if (!empty($this->request->data['Dates']['mat_from'])) $conditions['maturity_date >='] = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['Dates']['mat_from'])));
            if (!empty($this->request->data['Dates']['mat_to'])) $conditions['maturity_date <='] = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['Dates']['mat_to'])));
        }

        if ($this->request->is('post') || !empty($layout)) {
            $this->Transaction->virtualFields  =  array(
                'amount'    => "FORMAT(Transaction.amount, 2)",
                'interest'  => "FORMAT(Transaction.total_interest, 2)",
                'tax'       => "FORMAT(Transaction.tax_amount, 2)",
                'fromReinv' => "IF(source_group <> 1, source_group, ' ')",
                'inReinv'   => "IF(Transaction.reinv_group <> 1, Transaction.reinv_group, ' ')",
                'reinv_type'   => "IF(Transaction.reinv_group <> 1, Reinvestment.reinv_type, ' ')",
                'processed' => "IF(Transaction.tr_state in ('Created','Instruction Created','Instruction Sent','Confirmation Received','First Notification','Second Notification') or (Transaction.tr_state = 'Reinvested' and inReinv.reinv_status = 'Open'),'No','Yes')",
            );

            $this->Transaction->fieldsToDisplay =  array('*');

            $transactions = $this->Transaction->find('all', array(
                'conditions' => $conditions,
                'fields' => $this->Transaction->fieldsToDisplay,
                'recursive' => 1,
                'order' => 'Transaction.tr_number DESC',
            ));

            $layouts = array();
            $layouts['default'] = array(
                'TRN' => 'Transaction.tr_number',
                'TR type' => 'Transaction.tr_type',
                'State' => 'Transaction.tr_state',
                'DI' => 'Transaction.instr_num',
                'Term/Call' => 'Transaction.depo_type',
                'CCY' => 'AccountA.ccy',
                'Amount' => 'Transaction.amount',
                'Cmmt Date' => 'Transaction.commencement_date',
                'Maturity Date' => 'Transaction.maturity_date',
                'Period' => 'Transaction.depo_term',
                'Interest Rate' => 'Transaction.interest_rate',
                'Date Basis' => 'Transaction.date_basis',
                'Interest' => 'Transaction.interest',
                'Tax' => 'Transaction.tax',
                'Principal + Interest' => 'Principal + Interest',
                'Principal + Net Interest' => 'Principal + Net Interest',
                'Mandate' => 'Mandate.mandate_name',
                'Compartment' => 'Compartment.cmp_name',
                'Counterparty' => 'Counterparty.cpty_name',
                'Scheme' => 'Transaction.scheme',
            );
            $layouts['reinvestment'] = array(
                'TRN' => 'Transaction.tr_number',
                'TR type' => 'Transaction.tr_type',
                'State' => 'Transaction.tr_state',
                'DI' => 'Transaction.instr_num',
                'From Reinv' => 'Transaction.fromReinv',
                'In Reinv' => 'Transaction.inReinv',
                'Reinv Type' => 'Reinvestment.reinv_type',
                'Origin TRN' => 'Transaction.original_id',
                'Parent TRN' => 'Transaction.parent_id',
                'Term/Call' => 'Transaction.depo_type',
                'Cmmt Date' => 'Transaction.commencement_date',
                'Maturity Date' => 'Transaction.maturity_date',
                'CCY' => 'AccountA.ccy',
                'Amount' => 'Transaction.amount',
                'Interest' => 'Transaction.interest',
                'Tax' => 'Transaction.tax',
                'Principal + Interest' => 'Principal + Interest',
                'Principal + Net Interest' => 'Principal + Net Interest',
                'Tax Rate' => 'Taxes.tax_rate',
                'Mandate' => 'Mandate.mandate_name',
                'Compartment' => 'Compartment.cmp_name',
                'Counterparty' => 'Counterparty.cpty_name',
                'Scheme' => 'Transaction.scheme',
            );
            $layouts['booking'] = array(
                'TRN' => 'Transaction.tr_number',
                'TR type' => 'Transaction.tr_type',
                'State' => 'Transaction.tr_state',
                'Mandate' => 'Mandate.mandate_name',
                'CCY' => 'AccountA.ccy',
                'Amount' => 'Transaction.amount',
                'Cmmt Date' => 'Transaction.commencement_date',
                'Maturity Date' => 'Transaction.maturity_date',
                'Days' => 'Transaction.days',
                'Interest Rate' => 'Transaction.interest_rate',
                'Interest' => 'Transaction.interest',
                'Tax' => 'Transaction.tax',
                'Principal + Interest' => 'Principal + Interest',
                'Principal + Net Interest' => 'Principal + Net Interest',
                'Tax Rate' => 'Taxes.tax_rate',
                'Principal account' => 'AccountA.IBAN',
                'Interest account' => 'AccountB.IBAN',
                'Booking status' => 'Transaction.booking_status',
                'EOM Booking' => 'Transaction.eom_booking',
                'EOM Interest' => 'Transaction.eom_interest',
                'EOM Tax' => 'Transaction.eom_tax',
                'Fixing Date' => 'Transaction.fixing_date',
                'Accrued Interest' => 'Transaction.accrued_interst',
                'Accrued Tax' => 'Transaction.accrued_tax'
            );
            $layouts['limits'] = array(
                'TRN' => 'Transaction.tr_number',
                'TR type' => 'Transaction.tr_type',
                'State' => 'Transaction.tr_state',
                'Counterparty' => 'Counterparty.cpty_name',
                'Mandate' => 'Mandate.mandate_name',
                'CCY' => 'AccountA.ccy',
                'Amount' => 'Transaction.amount',
                'Amount EUR' => 'Transaction.amount_eur',
                'Cmmt Date' => 'Transaction.commencement_date',
                'Indicative Maturity Date' => 'Transaction.indicative_maturity_date',
                'Days' => 'Transaction.days',
                'Interest Rate' => 'Transaction.interest_rate',
                'Reference Rate' => 'Transaction.reference_rate',
                'Interest' => 'Transaction.interest',
                'Interest EUR' => 'Transaction.total_interest'
            );
            $layouts['custom3'] = array(
                'Rate Type' => 'Transaction.rate_type',
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
            if (!empty($this->request->data['transaction']['layout']) && !empty($layouts[$this->request->data['transaction']['layout']])) {
                $layout = $layouts[$this->request->data['transaction']['layout']];

                // OR build custom layout
            } elseif ($this->request->data['transaction']['layout'] == 'custom' && !empty($this->request->data['transaction']['customfields'])) {
                $cfields = @json_decode($this->request->data['transaction']['customfields']);
                $layout = array();
                if (!empty($cfields)) foreach ($cfields as $key) {
                    if (!empty($allfields[$key])) {
                        $layout[$allfields[$key]] = $key;
                    }
                }
            }

            $this->set('layout', $layout);
            $this->set('currentlayout', $this->request->data['transaction']['layout']);

            $tr_numbers = array();
            $reordered = array();
            if (!empty($transactions)) foreach ($transactions as $label => &$trn) {
                $line = array();
                $cnt = 0;
                foreach ($layout as $col) {
                    $exp = explode('.', $col);
                    //if(count($exp)==2){
                    $line['col-' . $cnt . ' ' . $col] = '';
                    if ($col == 'Taxes.tax_rate') {
                        if (empty($trn[$exp[0]][$exp[1]])) $trn[$exp[0]][$exp[1]] = 'N/A';
                        else $trn[$exp[0]][$exp[1]] = $trn[$exp[0]][$exp[1]] . '%';
                    }
                    $amount = str_replace(',', '', $trn['Transaction']['amount']);
                    $interest = str_replace(',', '', $trn['Transaction']['total_interest']);
                    $tax = str_replace(',', '', $trn['Transaction']['tax_amount']);
                    if ($col == 'Principal + Interest') {
                        if (!empty($trn['Transaction']['amount']) && !empty($trn['Transaction']['total_interest'])) {
                            $valPI = bcadd($amount, $interest, 4);
                            $valPI = number_format($valPI, 2, '.', ',');
                            $line['col-' . $cnt . ' ' . $col] = $valPI;
                        }
                    }
                    if ($col == 'Principal + Net Interest') {
                        if (!empty($trn['Transaction']['amount']) && !empty($trn['Transaction']['total_interest']) && !empty($trn['Transaction']['total_interest'])) {
                            $valPNI = bcadd($amount, $interest, 4);
                            $valPNI = bcsub($valPNI, $tax, 4);
                            $valPNI = number_format($valPNI, 2, '.', ',');
                            $line['col-' . $cnt . ' ' . $col] = $valPNI;
                        }
                    }
                    if (!empty($trn[$exp[0]][$exp[1]]))
                        $line['col-' . $cnt . ' ' . $col] = $trn[$exp[0]][$exp[1]];
                    //}
                    $cnt++;
                }
                $tr_numbers[] = $trn['Transaction']['tr_number'];
                $reordered[] = $line;
            }

            $this->set('tr_numbers', $tr_numbers);
            $this->set('transactions', $reordered);

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

    function audit_trail_logs()
    {
        if (!empty($this->request->data['Transaction']['tr_number'])) {
            $tr_numbers = explode(',', $this->request->data['Transaction']['tr_number']);
            foreach ($tr_numbers as &$tr_num) {
                $tr_num = intval($tr_num);
            }
            $query = "SELECT tr_number,datetime,user,message FROM treasury.log_entries WHERE tr_number IN (" . implode(',', $tr_numbers) . ") ORDER BY tr_number ASC, datetime asc";
            $results = $this->Transaction->query($query);
            $filename = "audit_trail_" . time() . ".pdf";
            $filepath = "/var/www/html/data/treasury/audit_trail/" . $filename;
            $pdf_file_name = explode('/', $filepath);
            $pdf_file_name = $pdf_file_name[count($pdf_file_name) - 1];
            $this->set('content', $results);
            $view = new View($this);
            /* PDF generation */
            $raw = $view->render('Queries/audit_trail_pdf');
            $raw = strstr($raw, '<!-- AUDIT -->'); // remove cake styling
            $raw = strstr($raw, '<!-- END AUDIT -->', true);
            $this->autoRender = false;
            // get an instance of wkhtmltopdf
            $pdf = new WkHtmlToPdf();
            $pdf_file = array('Pdf' => array(
                'name' => 'Audit trail',
                'raw' => base64_encode($raw)
            ));
            $html = base64_decode($pdf_file['Pdf']['raw']);
            $pdf->addPage($html);
            $pdf->setOptions(array('footer-right' => '"Page [page]/[topage]"'));
            $pdf->saveAs($filepath);
            if (!$pdf->send($pdf_file_name)) {
                $this->Session->setFlash('Could not create PDF: ' . $pdf->getError() . ' Please contact the administrator', 'flash/error');
            }
        }
    }
}
