<?php

declare(strict_types=1);

namespace Damsv2\Controller;

use Cake\Event\EventInterface;
use KubAT\PhpSimple\HtmlDomParser;
use Cake\Datasource\ConnectionManager;
use Cake\Collection\Collection;
use App\Lib\Helpers;

/**
 * Invoice Controller
 *
 * @property \App\Model\Table\InvoicesTable $Invoice
 * @method \App\Model\Entity\Invoice[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class InvoiceController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        //$this->loadComponent('Security');
        $this->loadComponent('SAS');
        //$this->loadComponent('FormProtection');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        //$this->Security->setConfig('blackHoleCallback', 'blackhole');
        //$this->FormProtection->setConfig('unlockedActions', ['avCapAmount']);
    }

    public $paginate = [
        'limit'          => 25,
        'order'          => [
            'invoice_id' => 'desc'
        ],
        'sortableFields' => [
            // associations and computed columns must be whitelisted, and if
            // you do that, the valid main model columns must be specified too
            'invoice_id',
            'Portfolio.deal_name',
            'due_date',
            'expected_payment_date',
            'Portfolio.owner',
            'invoice_owner',
            'amount_curr',
            'contract_currency',
            'stage',
            'status_id',
        ]
    ];

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $session = $this->request->getSession();
        $this->loadModel('Damsv2.Portfolio');
        //filter defaults
        $conditions = [];
        $cond_portfolio = ['Portfolio.product_id NOT IN' => [22, 23], 'Portfolio.iqid NOT IN' => $this->getUmbrellaIqid()];
        $cond_mandate = ['Product.product_id NOT IN' => [22, 23]];

        // IF the filter for this dashboard is not stored in Session, we clear the Session object
        if (!$session->read('Form.data.invoices')) {
            $session->write('Form.data.invoices', [
                'product_id'       => '',
                'mandate'          => '',
                'portfolio_id'     => '',
                'beneficiary_name' => '',
                'stage'            => '',
                'status'           => '',
                'rep_owner'        => '',
                'port_owner'       => '',
                'invoice_id'       => ''
            ]);
        }
        
        $prodid = !empty($this->request->getData('product_id')) ? $this->request->getData('product_id') : $session->read('Form.data.invoices.product_id');
        $manid = !empty($this->request->getData('mandate')) ? $this->request->getData('mandate') : $session->read('Form.data.invoices.mandate');

        if ($this->request->is('post')) {
            //load session with request data
            $session->write('Form.data.invoices', $this->request->getData());
           
            //mandate
            if ($manid) {//from mandate name to portfolio_id 
                $getmandate = $this->Portfolio->find('all', ['fields' => ['mandate'], 'conditions' => ['Portfolio.portfolio_id' => $manid]])->first();
                $session->write('Form.data.invoices.mandate_name', $getmandate->mandate);
                $cond_portfolio = Helpers::arrayPushAssoc($cond_portfolio, 'Portfolio.mandate', $session->read('Form.data.invoices.mandate_name'));
            }

            //manadate, portfolio dependent filters
            if ($prodid) {
                if (($prodid) && ($manid)) {
                    $getmandate = $this->Portfolio->find('all', ['fields' => ['mandate'], 'conditions' => ['Portfolio.portfolio_id' => $manid]])->first();

                    $mandate_possible = $this->Portfolio->find('all', [
                                'fields'     => ['portfolio_name', 'product_id'],
                                'conditions' => [
                                    'Portfolio.product_id' => $prodid,
                                    'Portfolio.mandate'    => $getmandate->mandate
                        ]])->first();

                    if (empty($mandate_possible)) {
                        $session->write('Form.data.invoices.mandate', '');
                        $session->write('Form.data.invoices.mandate_name', '');
                        unset($cond_portfolio['Portfolio.mandate']);
                        unset($cond_mandate['Product.product_id']);
                    } else {
                        $session->write('Form.data.invoices.mandate_name', $getmandate->mandate);
                        $cond_portfolio = Helpers::arrayPushAssoc($cond_portfolio, 'Portfolio.mandate', $session->read('Form.data.invoices.mandate_name'));
                    }
                }

                $cond_portfolio = Helpers::arrayPushAssoc($cond_portfolio, 'Product.product_id', $prodid);
                $cond_mandate = Helpers::arrayPushAssoc($cond_portfolio, 'Product.product_id', $prodid);
            }
        }

        //filters
        $products = $this->Portfolio->Product->getProducts();

        $mandates = $this->Portfolio->find('list', [
                    'contain'    => ['Product'],
                    'fields'     => ['portfolio_id', 'mandate'],
                    'keyField'   => 'portfolio_id',
                    'valueField' => 'mandate',
                    'group'      => 'mandate',
                    'order'      => 'mandate',
                    'conditions' => [$cond_mandate]
                ])->toArray();
        
       
        $portfolios = $this->Portfolio->find('list', [
                    'contain'    => ['Product'],
                    'fields'     => ['Product.name', 'Portfolio.portfolio_name', 'Portfolio.portfolio_id'],
                    'keyField'   => 'portfolio_id',
                    'valueField' => 'portfolio_name',
                    'groupField' => 'product.name',
                    'order'      => ['Product.name', 'Portfolio.portfolio_name'],
                    'conditions' => [$cond_portfolio]
                ])->toArray();


        $beneficiary = $this->Portfolio->find('list', [
                    'contain'    => ['Product'],
                    'fields'     => 'beneficiary_name',
                    'keyField'   => 'beneficiary_name',
                    'valueField' => 'beneficiary_name',
                    'order'      => 'beneficiary_name',
                    'conditions' => [$cond_portfolio]
                ])->toArray();

        $stages = [
            'Processing' => 'Processing',
            'Final'      => 'Final',
        ];

        $statuses = [
            'Paid' => 'Paid',
            'G&S'  => 'G&S'
        ];

        $users = $this->Invoice->VUser->find('list', [
            'fields'     => ['first_name', 'last_name', 'id'],
            'keyField'   => 'id',
            'valueField' => ['full_name'],
//            'conditions' => ['id in' => $ownersplist],
            'order'      => ['last_name', 'first_name']
        ]);

        //form data filtering
        //product id
        if ($prodid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Product.product_id', $prodid);
        }

        //mandate name
        if (!empty($session->read('Form.data.invoices.mandate_name'))) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Portfolio.mandate', $session->read('Form.data.invoices.mandate_name'));
        }

        //portfolio id
        $portid = $session->read('Form.data.invoices.portfolio_id');
        if ($portid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Portfolio.portfolio_id', $portid);
        }

        //beneficiary
        $benid = $session->read('Form.data.invoices.beneficiary_name');
        if ($benid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Portfolio.beneficiary_name', $benid);
        }

        //stage
        $stageid = $session->read('Form.data.invoices.stage');
        if ($stageid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Status.stage', $stageid);
        }

        //status
        $statusid = $session->read('Form.data.invoices.status');
        if ($statusid) {
            //$statusid = ($statusid === 'GS') ? 'G&S' : $statusid;
            $conditions = Helpers::arrayPushAssoc($conditions, 'Status.status', $statusid);
        }

        //report owner
        $repownid = $session->read('Form.data.invoices.rep_owner');
        if ($repownid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Invoice.invoice_owner', $repownid);
        }

        //portfolio owner
        $portownid = $session->read('Form.data.invoices.port_owner');
        if ($portownid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Portfolio.owner', $portownid);
        }

        //invoice id
        $invid = $session->read('Form.data.invoices.invoice_id');
        if ($invid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Invoice.invoice_id', $invid);
        }

        $query = $this->Invoice->find('all', [
            'contain'    => ['Portfolio', 'Status', 'VUser', 'Portfolio.VUser', 'Portfolio.Product'],
            'conditions' => [$conditions]
        ]);

        $invoices = $this->paginate($query, ['contain' => ['Portfolio', 'Status', 'VUser', 'Portfolio.VUser', 'Portfolio.Product']]);

        $this->set(compact('invoices', 'mandates', 'portfolios', 'stages', 'statuses', 'users', 'products', 'beneficiary', 'session'));
    }

    // this function destroys session filters - called in several places e.g. inclusion, pdlr, 
    public function resetFilters()
    {
        $session = $this->request->getSession();
        $session->destroy();
        $this->redirect($this->referer());
    }

    public function processingForm($invoice_id = null, $pdf = false)
    {
        if (!$this->Invoice->exists($invoice_id)) {
            throw new NotFoundException(__('Invalid Invoice'));
        } else {

            $sasResult = $this->SAS->curl('invoice_validation_report.sas', [
                'invoice_id' => $invoice_id,
                'user_id'    => $this->userIdentity()->get('id') //$this->UserAuth->getUserId()
                    ],
                    false,
                    false
            );
            $this->log('download of invoice validation form ' . $invoice_id, 'info');

            $result = $this->sasCallResult($sasResult);

            if (empty($result)) {
                $this->Flash->error('SAS RETURN ERROR!');
            }
            $this->set(compact('result'));
            $this->set('invoice_id', $invoice_id);
        }
    }

    public function finalForm($invoice_id = null)
    {
        if (!$this->Invoice->exists($invoice_id)) {
            throw new NotFoundException(__('Invalid Invoice'));
        } else {
            $sasResult = $this->SAS->curl(
                    'final_payment_form.sas', [
                'invoice_id' => $invoice_id,
                'user_id'    => $this->userIdentity()->get('id') //$this->UserAuth->getUserId()
                    ],
                    false,
                    false
            );
            $result = $this->sasCallResult($sasResult);

            if (empty($result)) {
                $this->Flash->error('SAS RETURN ERROR!');
            }

            $this->set(compact('result'));
            $this->set('invoice_id', $invoice_id);
        }
    }

    public function accounting($invoice_id = null)
    {
        if (!$this->Invoice->exists($invoice_id)) {
            throw new NotFoundException(__('Invalid Invoice'));
        } else {
            $invoice = $this->Invoice->get($invoice_id, [
                'contain' => ['Portfolio'],
            ]);

            $this->loadModel('Damsv2.Report');
            $pdlr_reports = $this->Report->find('all', ['conditions' => ['Report.invoice_id' => $invoice_id]]);

            $ccys = [];
            $total_by_currency = [];
            $total_all_curr = 0;
            $this->loadModel('Damsv2.PdlrTransaction');
            foreach ($pdlr_reports as $report) {
                $pdlr_transactions = $this->PdlrTransaction->find('all', ['conditions' => ['report_id' => $report->report_id]]);
                //loop to populate the CCY array
                foreach ($pdlr_transactions as $transaction) {
                    $ccys[$transaction->currency] = $transaction->currency;
                    if (!isset($total_by_currency[$transaction->currency])) {
                        $total_by_currency[$transaction->currency] = 0;
                    }
                    $total_by_currency[$transaction->currency] = $total_by_currency[$transaction->currency] + $transaction->eif_due_amount;
                    $total_all_curr = $total_all_curr + $transaction->eif_due_amount;
                }
            }

            $this->set('invoice', $invoice);
            $this->set(compact('ccys'));
            $this->set(compact('total_by_currency'));
            $this->set(compact('total_all_curr'));

            if ($this->request->is('post')) {
//                $ccy_array = array_keys($ccys); //[0];
//                $first_ccy = $ccy_array[0];

                $save = true;

                $contract_currency = $invoice->contract_currency;

                $this->set(compact('contract_currency'));

                if (!empty($this->request->getData('paid.paid_ammount_' . $contract_currency))) {
                    $check_ammount = preg_replace('/[^0-9\.-]/', "", $this->request->getData('paid.paid_ammount_' . $contract_currency));
                    $check_ammount = floatval($check_ammount);
                    if (abs($check_ammount - $total_by_currency[$contract_currency]) > 0.01) {
                        $save = false;
                        $this->set('amount_error', true);
                    }
                }

                if ($save) {
                    // group retrieving  // http://vmu-sas-01:8080/browse/PLATFORM-259
//                    $groups = CakeSession::read('UserAuth.UserGroups');
//                    if (!is_array($groups))
//                        $groups = array($groups);
//                    if (!empty($groups))
//                        foreach ($groups as $group) {
//                            $groupsnames[] = $group['alias_name'];
//                        }
//                    if (in_array('ReadOnlyDams', $groupsnames)) {
//                        $this->Flash->error("You are currently in a read only profile, this functionality is disabled");
//                        $this->redirect($this->referer());
//                    }
                    $values = [];
                    $empty = false;

                    foreach ($this->request->getData('paid') as $value) {
                        if (empty($value)) {
                            $empty = true;
                        }
                        $value = preg_replace("/[^0-9\.-]/", "", $value);
                        $values[] = $value;
                    }
                    if ($empty || empty($this->request->getData('payment_date'))) {
                        $this->Flash->error('All the fields are mandatory');
                        $this->redirect($this->referer());
                    }
                 
                    $sas_data = [
                        'invoice_id'       => $invoice_id,
                        'acc_payment_date' => $this->request->getData('payment_date'),
                        'value_paid'       => implode(',', $values),
                        'curr_paid'        => implode(',', $ccys),
                        'user_id'          => $this->userIdentity()->get('id') //$this->UserAuth->getUserId()
                    ];
                    $sasResult = $this->SAS->curl(
                            'update_paid_invoice.sas', $sas_data,
                            false,
                            false
                    );
                    $log_info = [
                        'invoice_id'    => $invoice_id,
                        'payment_date'  => $this->request->getData('payment_date'),
                        'value_paid'    => implode(',', $values),
                        'currency_paid' => implode(',', $ccys),
                    ];
                    $this->logDams('Invoice saved with payment information: ' . json_encode($log_info), 'dams', 'Invoice accounting data input');

                    $this->Flash->success('The invoice has been paid');
                    $this->redirect(['controller' => 'invoice', 'action' => 'index']);
                }
            }
        }
    }

    public function avCapAmount()
    {
        $this->viewBuilder()->setLayout('ajax');
        if ($this->request->is('ajax', 'post')) {
            //$this->response->disableCache();
            //$this->autoRender = false;
            //$this->ajax = true;

            $report_id = $this->request->getData('add.actual_reportid');
            $selected = [];
            if (!empty($this->request->getData('add.selected'))) {
                $selected[] = $this->request->getData('add.selected');
            }
            if (empty($selected)) {
                die(""); //no error message
            }

            $this->loadModel('Damsv2.Report');
            $r = $this->Report->find('all', ['conditions' => ['Report.report_id' => $report_id]])->first();

            $sasparams = [
                'report_id'    => $selected,
                'portfolio_id' => $r->portfolio_id,
                'currency'     => $this->request->getData('add.CCY'),
                //'due_date' => $this->request->getData('add.due_date'),
                'save'         => 0,
                'user_id'      => $this->userIdentity()->get('id') //$this->UserAuth->getUserId(),
            ];
            if (!empty($selected)) {
                $sasparams['selected'] = implode($selected, ',');
            }

            $sasResult = $this->SAS->curl(
                    'invoice_pd_lr.sas', $sasparams,
                    false,
                    false
            );

            $dom = HtmlDomParser::str_get_html($sasResult);

            $span = $dom->find('span');
            $result = '';

            if (empty($span)) {
                $result = "<span id='Error'>not enough data</span>"; # error Message
            }

            foreach ($span as $key => $s) {
                $result .= $s->outertext;
            }

            //print_r($sasResult);
            echo($result);
            exit;
        }
    }

    public function add($report_id = null)
    {
        $this->loadModel('Damsv2.Report');
        if (!$this->Report->exists($report_id)) {
            throw new NotFoundException(__('Invalid Report'));
        } else {
            $actual_report = $this->Report->get($report_id, [
                'contain' => ['Portfolio'],
            ]);

            $reception_date = $actual_report->reception_date;
            if (!empty($actual_report->portfolio->call_time_to_pay) && !empty($reception_date)) {
                //$due_date = date('Y-m-d', '+' . $actual_report->portfolio->call_time_to_pay . ' day', $reception_date);
                $due_date = $reception_date->modify('+' . $actual_report->portfolio->call_time_to_pay . ' days');
            } else {
                $due_date = $actual_report->due_date;
            }
        }

        $conditions = [
            'Template.template_type_id IN' => [2, 3],
            'Report.status_id !='          => 14,
            'Portfolio.portfolio_id'       => $actual_report->portfolio_id
        ];

        $query = $this->Report->find('all', [
            'contain'    => ['Portfolio', 'Status', 'Template', 'Template.TemplateType', 'VUser', 'Portfolio.VUser'],
            'conditions' => [$conditions]
        ]);

        $this->loadModel('Damsv2.Daily');
        foreach ($query as $report) {
            $last_included = $report->status_id == 10 ? $report : null;

            if (!empty($report->ccy) && !empty($report->amount)) {
                $rate = $this->Daily->find('all', [
                            'conditions' => ['currency' => $report->ccy],
                            'fields'     => ['obs_value']
                        ])->first();

                if (isset($rate->obs_value)) {
                    $report->amount_ctr = (float) $report->amount / (float) $rate->obs_value;
                }
            }
        }
        $user_id = $this->userIdentity()->get('id');
        $reports = $this->paginate($query, [
            'contain' => ['Portfolio', 'Status', 'Template', 'Template.TemplateType', 'VUser', 'Portfolio.VUser'],
            'order'   => ['Report.report_id' => 'desc']]);

        $this->set(compact('reports', 'actual_report', 'due_date', 'last_included', 'user_id'));


        // form processing - if filters are selected
        if ($this->request->is('post')) {
            if (empty($this->request->getData('selected'))) {
                $this->Flash->error('Please select at least one PDLR report!');
                return $this->redirect($this->referer());
            } else {
                // group retrieving  // http://vmu-sas-01:8080/browse/PLATFORM-259
//            $groups = CakeSession::read('UserAuth.UserGroups');
//            if (!is_array($groups))
//                $groups = array($groups);
//            if (!empty($groups))
//                foreach ($groups as $group) {
//                    $groupsnames[] = $group['alias_name'];
//                }
//            if (in_array('ReadOnlyDams', $groupsnames)) {
//                $this->Session->setFlash("You are currently in a read only profile, this functionality is disabled", "flash/error");
//                $this->redirect($this->referer());
//            }
               
                $report_id = $this->request->getData('actual_reportid');

                $r = $this->Report->find('all', ['contain' => ['Portfolio'], 'conditions' => ['Report.report_id' => $report_id]])->first();
            
                $sasResult = $this->SAS->curl(
                        'invoice_pd_lr.sas', array(
                    'report_id'    => $this->request->getData('selected'),
                    'portfolio_id' => $r->portfolio_id,
                    'currency'     => $this->request->getData('CCY'),
                    'due_date'     => $this->request->getData('due_date'),
                    'save'         => 1,
                    'user_id'      => $this->userIdentity()->get('id')
                        ),
                        false,
                        false
                );
                
                //reduce the size of sas result to avoid drowning the dom parser
                $target = strpos($sasResult, 'invoice_number', 0);
                $sasResult = substr($sasResult, ($target-10));
                error_log("line ".__LINE__." invoice_pd_lr.sas sasres ".$sasResult);

                $dom = HtmlDomParser::str_get_html($sasResult);

                $new_invoice_id = null;
//                $new_filename = null;
                $success_sas = $dom->find('#invoice_number');
//                $file_sas = $dom->find('#filename');
                foreach ($success_sas as $sas_response) {
                    $new_invoice_id = trim($sas_response->innertext);
                    error_log("line ".__LINE__." invoice_pd_lr.sas created invoice ".$new_invoice_id);
                }
//                foreach ($file_sas as $sas_file) {
//                    $new_filename = trim($sas_response->innertext);
//                }
                error_log("line ".__LINE__." invoice_pd_lr.sas created invoice ".$new_invoice_id);
                // for efront webservice
//                $directory = "/var/www/html/data/damsv2/efront/pdlr/upload";
//                $destination = "/sftp/eif_user/home/OUT/PDLR_REPORTS";
//                $files = scandir($directory);
                if (!empty($new_invoice_id)) {
                    $this->log('invoice add ' . $this->request->getData('selected') . ', portfolio:' . $r->portfolio->portfolio_id . ', currency:' . $this->request->getData('CCY') . ', due_date:' . $this->request->getData('due_date') . ', payment date:' . $this->request->getData('payment_date'), 'info');
                    $params = array(
                        'invoice_id'   => $new_invoice_id,
                        'report_id'    => $this->request->getData('selected'),
                        'portfolio_id' => $r->portfolio->portfolio_id,
                        //'expected_payment_date' => $this->request->getData['add']['payment_date'],
                        //'amount' => $this->request->getData['add']['cap_amount'],//$this->request->getData['add']['available_cap_amount'],  $this->request->getData['add']['remaining_cap_amount'],
                        'amount'       => $this->request->getData('cap_amount'),
                        'currency'     => $this->request->getData('CCY'),
                        'username'     => $this->userIdentity()->get('username')
                    );
                    $this->logDams('Invoice created: ' . json_encode($params), 'dams', 'Create invoice');
                    $this->Flash->success("The invoice #" . $new_invoice_id . " has been saved.");
                } else {
                    $this->Flash->error("The invoice could not be saved.");
                }
                return $this->redirect(['action' => 'index']);
            }
        }
    }

    public function pdfAdd($report_id = null)
    {
        if ($this->request->is('post')) {
            if (empty($this->request->getData('selected_rows_id'))) {
                $this->Flash->error('Please select at least one PDLR report!');
                return $this->redirect($this->referer());
            } else {
                $this->viewBuilder()->enableAutoLayout(false);
                $this->loadModel('Damsv2.Report');

                $actual_report = $this->Report->get($report_id, [
                    'contain' => ['Portfolio'],
                ]);

                $reception_date = $actual_report->reception_date;
                if (!empty($actual_report->portfolio->call_time_to_pay) && !empty($reception_date)) {
                    $due_date = $reception_date->modify('+' . $actual_report->portfolio->call_time_to_pay . ' days');
                } else {
                    $due_date = $actual_report->due_date;
                }
                $conditions = [
                    'Template.template_type_id IN' => [2, 3],
                    'Report.status_id !='          => 14,
                    'Portfolio.portfolio_id'       => $this->request->getData('portfolio_id')
                ];

                $reports = $this->Report->find('all', [
                    'contain'    => ['Portfolio', 'Status', 'Template', 'Template.TemplateType', 'VUser', 'Portfolio.VUser'],
                    'conditions' => [$conditions],
                    'order'      => ['Report.report_id' => 'asc']
                ]);

                $this->loadModel('Damsv2.Daily');
                foreach ($reports as $report) {
                    $last_included = $report->status_id == 10 ? $report : null;

                    if (!empty($report->ccy) && !empty($report->amount)) {
                        $rate = $this->Daily->find('all', [
                                    'conditions' => ['currency' => $report->ccy],
                                    'fields'     => ['obs_value']
                                ])->first();

                        if (isset($rate->obs_value)) {
                            $report->amount_ctr = (float) $report->amount / (float) $rate->obs_value;
                        }
                    }
                }
                $user_id = $this->userIdentity()->get('id');

                $values = $this->request->getData();

                $this->set(compact('reports', 'actual_report', 'due_date', 'last_included', 'user_id', 'values'));

                $this->viewBuilder()->setClassName('CakePdf.Pdf');
                $this->viewBuilder()->setOption(
                        'pdfConfig',
                        [
                            'orientation'      => 'landscape',
                            'download'         => true, // This can be omitted if "filename" is specified.
                            'filename'         => 'report_invoice_add_' . $report_id . '.pdf', //// This can be omitted if you want file name based on URL.
                            'user-style-sheet' => WWW_ROOT . 'css/site.css'
                        ]
                );
            }
        }
    }

    /**
     * View method
     *
     * @param string|null $id Invoice id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $invoice = $this->Invoice->get($id, [
            'contain' => ['Portfolio', 'Status'],
        ]);

        $this->set(compact('invoice'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Invoice id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $invoice = $this->Invoice->get($id, [
            'contain' => ['Portfolio', 'Status'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $invoice = $this->Invoice->patchEntity($invoice, $this->request->getData());
            if ($this->Invoice->save($invoice)) {
                $this->Flash->success(__('The invoice has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The invoice could not be saved. Please, try again.'));
        }
        $portfolio = $this->Invoice->Portfolio->find('list', ['limit' => 200]);
        $status = $this->Invoice->Status->find('list', ['limit' => 200]);
        $this->set(compact('invoice', 'portfolio', 'status'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Invoice id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $invoice = $this->Invoice->get($id);
        if ($this->Invoice->delete($invoice)) {
            $this->Flash->success(__('The invoice has been deleted.'));
        } else {
            $this->Flash->error(__('The invoice could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    private function getUmbrellaIqid()
    {
        $connection = ConnectionManager::get('default');
        $umbrella_iqid = $connection->query('SELECT iqid FROM umbrella_portfolio')->fetchAll('assoc');

        $collection = new Collection($umbrella_iqid);
        $iqids = $collection->extract('iqid')->toList();
        return $iqids;
    }

    private function sasCallResult($sasResult)
    {
        $dom = HtmlDomParser::str_get_html($sasResult);

        $table = $dom->find('table');
        $result = '';

        foreach ($table as $key => $t) {
            $t->class = 'table table-bordered table-striped';
            $t->frame = '';
            $result .= $t->outertext;
        }
        return $result;
    }

    public function pdfFinal($id = null)
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $sasResult = $this->SAS->curl(
                'final_payment_form.sas', [
            'invoice_id' => $id,
            'user_id'    => $this->userIdentity()->get('id'),
                ],
                false,
                false
        );
        $result = $this->sasCallResult($sasResult);

        $this->set(compact('result'));
        $this->set('invoice_id', $id);

        $this->viewBuilder()->setClassName('CakePdf.Pdf');
        $this->viewBuilder()->setOption(
                'pdfConfig',
                [
                    'orientation'      => 'portrait',
                    'download'         => true, // This can be omitted if "filename" is specified.
                    'filename'         => 'report_final_PDLR_form_' . $id . '.pdf', //// This can be omitted if you want file name based on URL.
                    'user-style-sheet' => WWW_ROOT . 'css/site.css',
                ]
        );
    }

    public function pdfProcessing($id = null)
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $sasResult = $this->SAS->curl('invoice_validation_report.sas', [
            'invoice_id' => $id,
            'user_id'    => $this->userIdentity()->get('id') //$this->UserAuth->getUserId()
                ],
                false,
                false
        );
        $result = $this->sasCallResult($sasResult);

        $this->set(compact('result'));
        $this->set('invoice_id', $id);

        $this->viewBuilder()->setClassName('CakePdf.Pdf');
        $this->viewBuilder()->setOption(
                'pdfConfig',
                [
                    'orientation'      => 'portrait',
                    'download'         => true, // This can be omitted if "filename" is specified.
                    'filename'         => 'report_PDLR_processing_form_' . $id . '.pdf', //// This can be omitted if you want file name based on URL.
                    'user-style-sheet' => WWW_ROOT . 'css/site.css',
                ]
        );
    }

}
