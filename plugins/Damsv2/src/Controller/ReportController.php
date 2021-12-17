<?php

declare(strict_types=1);

namespace Damsv2\Controller;

use Cake\Event\EventInterface;
use KubAT\PhpSimple\HtmlDomParser;
use Cake\Datasource\ConnectionManager;
use Cake\Collection\Collection;
//use App\Lib\DownloadLib;
use App\Lib\Helpers;
use Cake\Cache\Cache;
use Cake\I18n\Date;

/**
 * Report Controller
 *
 * @property \App\Model\Table\ReportTable $Report
 * @method \App\Model\Entity\Report[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReportController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        //$this->loadComponent('Security');
        $this->loadComponent('SAS');
        $this->loadComponent('Spreadsheet');
        $this->loadComponent('File');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        //$this->Security->setConfig('unlockedActions', ['inclusion']);
        //$this->Security->setConfig('blackHoleCallback', 'blackhole');
    }

    public $paginate = [
        'limit'          => 25,
        'order'          => [
            'report_id' => 'desc'
        ],
        'sortableFields' => [
            'report_id',
            'report_name',
            'owner',
            'Portfolio.owner',
            'Portfolio.availability_start',
            'Portfolio.availability_end',
            'Template.template_type_id',
            'due_date',
            'reception_date',
            'report_type',
            'Status.stage',
            'Status.status',
            'stage',
            'ccy',
            'amount',
        ]
    ];

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Portfolio', 'Template', 'Status'],
        ];
        $report = $this->paginate($this->Report);

        $this->set(compact('report'));
    }

    public function inclusion()
    {
        $session = $this->request->getSession();
        $this->loadModel('Damsv2.Portfolio');

        $connection = ConnectionManager::get('default');
        $umbrella_portfolio_ids = $connection->query('SELECT portfolio_id FROM portfolio p, umbrella_portfolio u where u.iqid = p.iqid')->fetchAll('assoc');
        $this->set('umbrella_portfolio_ids', $umbrella_portfolio_ids);

        $sub_reports = $connection->query('SELECT report_id from report where portfolio_id in (select portfolio_id from umbrella_portfolio_mapping)')->fetchAll('assoc');
        $this->set('sub_reports', $sub_reports);

        $cond_portfolio = ['Portfolio.product_id NOT IN' => [22, 23]];
        $cond_mandate = ['Product.product_id NOT IN' => [22, 23]];

        // do not show regular reports linked to a closure report until they are in state included
        $regular_reports_to_exclude = $this->Report->find('list', ['fields'     => ['report_id'], 'keyField'   => 'report_id', 'valueField' => ['report_id'],
                    'conditions' => ['description LIKE ' => 'Regular report link to %', 'status_id <> ' => 5, 'report_type' => 'regular']])->toArray();

        //filter defaults
        if (!empty($regular_reports_to_exclude)) {
            $conditions = [
                'Template.template_type_id' => 1, // Only inclusion          
                'Report.visible'            => 1,
                'Report.report_id NOT IN'   => $regular_reports_to_exclude
            ];
        } else {
            $conditions = [
                'Template.template_type_id' => 1, // Only inclusion          
                'Report.visible'            => 1
            ];
        }

        // IF the filter for this dashboard is not stored in Session, we clear the Session object
        if (!$session->read('Form.data.inclusion')) {
            $session->write('Form.data.inclusion', [
                'product_id'       => '',
                'mandate'          => '',
                'portfolio_id'     => '',
                'beneficiary_name' => '',
                'period_quarter'   => '',
                'period_year'      => '',
                'stage'            => '',
                'status'           => '',
                'report_type'      => '',
                'rep_owner'        => '',
                'port_owner'       => '',
                'report_id'        => ''
            ]);
        }

        $prodid = !empty($this->request->getData('product_id')) ? $this->request->getData('product_id') : $session->read('Form.data.inclusion.product_id');
        $manid = !empty($this->request->getData('mandate')) ? $this->request->getData('mandate') : $session->read('Form.data.inclusion.mandate');

        if ($this->request->is('post')) {
            //load session with request data
            $session->write('Form.data.inclusion', $this->request->getData());
            

            //mandate
            if ($manid) {//from mandate name to portfolio_id 
                $getmandate = $this->Portfolio->find('all', ['fields' => ['mandate'], 'conditions' => ['Portfolio.portfolio_id' => $manid]])->first();
                $session->write('Form.data.inclusion.mandate_name', $getmandate->mandate);
                $cond_portfolio = Helpers::arrayPushAssoc($cond_portfolio, 'Portfolio.mandate', $session->read('Form.data.inclusion.mandate_name'));
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
                        $session->write('Form.data.inclusion.mandate', '');
                        $session->write('Form.data.inclusion.mandate_name', '');
                        unset($cond_portfolio['Portfolio.mandate']);
                        unset($cond_mandate['Product.product_id']);
                    } else {
                        $session->write('Form.data.inclusion.mandate_name', $getmandate->mandate);
                        $cond_portfolio = Helpers::arrayPushAssoc($cond_portfolio, 'Portfolio.mandate', $session->read('Form.data.inclusion.mandate_name'));
                    }
                }

                $cond_portfolio = Helpers::arrayPushAssoc($cond_portfolio, 'Product.product_id', $prodid);
                $cond_mandate = Helpers::arrayPushAssoc($cond_portfolio, 'Product.product_id', $prodid);
            }
        }

        // Top dashboard filters
        $periods = [];
        $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
        $before_year = date('Y', strtotime("-3 year", time()));
        $after_year = date('Y', strtotime("+1 year", time()));

        for ($year = $before_year; $year <= $after_year; $year++) {
            foreach ($quarters as $quarter) {
                $periods[$quarter . '_' . $year] = $quarter . ' ' . $year;
            }
        }

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
            'Expected'   => 'Expected',
            'Processing' => 'Processing',
            'Final'      => 'Final',
        ];
        $statuses = [
            'Not received'             => 'Not received',
            'Ready for import'         => 'Ready for import',
            'Errors'                   => 'Errors',
            'Ready for reconciliation' => 'Ready for reconciliation',
            'Draft included'           => 'Draft included',
            'Included'                 => 'Included',
            'Rejected'                 => 'Rejected',
            'No inclusion'             => 'No inclusion',
            'In progress...'           => 'In progress',
            'Split'                    => 'Split'
        ];

        //only show users linked to reports and portfolios
        $ownersr = $this->Report->find('list', ['fields' => ['owner'], 'keyField' => 'owner', 'valueField' => ['owner'],])->group('owner')->toArray();
        $ownersp = $this->Report->Portfolio->find('list', ['fields' => ['owner'], 'keyField' => 'owner', 'valueField' => ['owner'],])->group('owner')->toArray();

        $vusers = $this->getTableLocator()->get('Damsv2.VUser');
        $users_rep = $vusers->find('list', [
            'fields'     => ['first_name', 'last_name', 'id'],
            'keyField'   => 'id',
            'valueField' => ['full_name'],
            'conditions' => ['id in' => $ownersr],
            'order'      => ['last_name', 'first_name']
        ]);

        $users_port = $vusers->find('list', [
            'fields'     => ['first_name', 'last_name', 'id'],
            'keyField'   => 'id',
            'valueField' => ['full_name'],
            'conditions' => ['id in' => $ownersp],
            'order'      => ['last_name', 'first_name']
        ]);

        //product id
        if ($prodid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Product.product_id', $prodid);
        }

        //mandate name
        if (!empty($session->read('Form.data.inclusion.mandate_name'))) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Portfolio.mandate', $session->read('Form.data.inclusion.mandate_name'));
        }

        //portfolio id
        $portid = $session->read('Form.data.inclusion.portfolio_id');
        if ($portid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Report.portfolio_id', $portid);
        }

        //beneficiary
        $benid = $session->read('Form.data.inclusion.beneficiary_name');
        if ($benid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Portfolio.beneficiary_name', $benid);
        }

        //periods
        $perqid = $session->read('Form.data.inclusion.period_quarter');
        if ($perqid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Report.period_quarter', $perqid);
        }

        //period years
        $yearid = $session->read('Form.data.inclusion.period_year');
        if ($yearid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Report.period_year', $yearid);
        }

        //stage
        $stageid = $session->read('Form.data.inclusion.stage');
        if ($stageid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Status.stage', $stageid);
        }

        //status
        $statusid = $session->read('Form.data.inclusion.status');
        if ($statusid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Status.status', $statusid);
        }

        //report type
        $reptypeid = $session->read('Form.data.inclusion.report_type');
        if ($reptypeid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Report.report_type', $reptypeid);
        }

        //report owner
        $repownid = $session->read('Form.data.inclusion.rep_owner');
        if ($repownid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Report.owner', $repownid);
        }

        //portfolio owner
        $portownid = $session->read('Form.data.inclusion.port_owner');
        if ($portownid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Portfolio.owner', $portownid);
        }

        //report id
        $repid = $session->read('Form.data.inclusion.report_id');
        if ($repid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Report.report_id', $repid);
        }


        $query = $this->Report->find('all', [
            'contain'    => ['Portfolio', 'Template', 'Status', 'VUser', 'Portfolio.VUser', 'Portfolio.Product'],
            'conditions' => [$conditions]
        ]);

        $report = $this->paginate($query);

        $this->set('reports', $report);

        $this->set(compact('mandates', 'portfolios', 'stages', 'statuses', 'users_rep', 'users_port', 'products', 'beneficiary', 'periods', 'session'));
    }

    // repeat last inclusion of B sheet DAMS-946
    public function repeatLastInclusion()
    {
        $report_id = $this->request->getData('Report.report_id');

        $report = $this->Report->get($report_id, [
            'contain' => ['Portfolio', 'Status', 'Template'],
        ]);
        if ($report->status_id != 2) {

            $this->Flash->error('Wrong status for report ' . $report_id);
            $this->redirect($this->referer());
            exit();
        }
        $this->loadModel('Damsv2.Rules');
        if (!$this->Rules->brulesValid($report)) {
            $this->Flash->error("At least 1 consistency rule and 1 eligibility rule applicable to this portfolio are required to process the report.");
            $this->redirect($this->referer());
            exit();
        }
        if ($report->portfolio->product_id == 21) {
            $this->Flash->error("Repeating the last inclusion is not available for FRSP product.");
            $this->redirect($this->referer());
            exit();
        }
        $previous_report = $this->Report->find('all', [
                    'contain'    => ['Template'],
                    'conditions' => [
                        'Report.portfolio_id'       => $report->portfolio_id,
                        'Template.template_type_id' => 1, //inclusion flow
                        'Report.report_id != '      => $report_id,
                    ], 'order'      => ['period_start_date DESC', 'report_id DESC']])->first(); //latest
        $sheets = 'B';
        $templates_jeremie_bulgaria = array(327, 328, 329, 330);
        if (in_array($report->template_id, $templates_jeremie_bulgaria)) {
            if (strpos($previous_report->sheets, 'B1') !== false) {
                $sheets = 'B$$B1';
            }
        }
        $report->report_id = $report_id;
        $report->report_name = $report->portfolio->portfolio_name . "_" . $report->period_year . $report->period_quarter . "_v1";
        $report->input_filename = 'repeat_inclusion_' . $report_id . '.xls';
        $report->version_number = 1;
        $report->status_id = 19;
        $report->sheets = $sheets;
        $report->provisional_pv = $previous_report->provisional_pv;
        $report->reception_date = $previous_report->reception_date;
        $report->owner = $this->userIdentity()->get('id');
        $report->portfolio_id = $previous_report->portfolio_id;

        //save to DB
        $saved_report = $this->Report->save($report);

        //$params = ['report_id' => $report_id);
        $params = ['report_id' => $previous_report->report_id, 'new_report_id' => $report_id];
        $sasfile_to_import = $this->SAS->curl(
                'repeat_b_sheet.sas',
                $params,
                false,
                false
        );
        //debug($sasfile_to_import);
        sleep(2); //waiting for the file to be complete before proceeding
        clearstatcache();
        $file_generated = '/var/www/html/data/damsv2/repeat_inclusion/repeat_inclusion_' . $report_id . '.xls';
        if (file_exists($file_generated)) {
            $new_filename = 'repeat_inclusion_' . $report_id . '.xls';
            copy($file_generated, '/var/www/html/data/damsv2/upload/' . $new_filename);
            //Cache::write("import_file_running_report_".$report_id, "running", 'damsv2');
            $this->loadModel('Damsv2.ErrorsLog');
            $error_logged = $this->ErrorsLog->checkErrorImport($report, 'OK');

			$sheet = explode('$$', $sheets);
			$this->loadModel('Damsv2.ErrorsLogDetailed');
			foreach($sheet as $sh)
			{
				$error_detailed = $this->ErrorsLogDetailed->newEmptyEntity();

				$error_detailed->error_id = $error_logged->error_id;
				$error_detailed->sheet = $sh;
				$error_detailed->file_formats = 'OK';
				$this->ErrorsLogDetailed->save($error_detailed);
			}
            /* $data_num_check = array('report_id' => $report['Report']['report_id'],
              'save'=> 0, 'correction'=>0,
              'template_id' => $report['Report']['template_id'],
              'version' => 1,
              'version_number_check' => 1,
              'input_filename_check' => $new_filename,
              'headers_included' => 'yes',
              'template_type_id' => 1);

              $numerical_errors = $this->num_check_sas($data_num_check); */

            $params = [
                'report_id'        => $report_id,
                'template_type_id' => 1,
                'user_id'          => $this->userIdentity()->get('id'), //$this->UserAuth->getUserId(),
                'save'             => 0,
                'correction'       => 0
            ];
            $sasResult = $this->SAS->curl(
                    'import_file.sas',
                    $params,
                    false,
                    false
            );

            //Cache::write("import_file_running_report_".$report_id, $sasResult, 'damsv2');
            $params_log = [
                'report_id'    => $report_id,
                'portfolio_id' => $report->portfolio_id,
                'user_id'      => $this->userIdentity()->get('id'), //$this->UserAuth->getUserId(),
                'save'         => 0,
                'correction'   => 0
            ];
            $this->logDams('Include report repeat: ' . json_encode($params_log), 'dams', 'Include report');
            $this->Flash->success("The report #" . $report_id . " is being processed.");
            $this->redirect($this->referer());
        } else {
            $this->Flash->error("The inclusion for #" . $report_id . " could not be repeated.");
            $this->redirect($this->referer());
        }
    }

    public function pdlr()
    {
        $session = $this->request->getSession();
        $this->loadModel('Damsv2.Portfolio');

        //filter defaults
        $cond_portfolio = ['Portfolio.product_id NOT IN' => [22, 23], 'Portfolio.iqid NOT IN' => $this->getUmbrellaIqid()];
        $cond_mandate = ['Product.product_id NOT IN' => [22, 23]];

        $conditions = [
            'Template.template_type_id IN' => [2, 3], // No inclusion        
        ];

        // IF the filter for this dashboard is not stored in Session, we clear the Session object
        if (!$session->read('Form.data.pdlr')) {
            $session->write('Form.data.pdlr', [
                'product_id'       => '',
                'mandate'          => '',
                'portfolio_id'     => '',
                'beneficiary_name' => '',
                'period_quarter'   => '',
                'period_year'      => '',
                'stage'            => '',
                'status'           => '',
                'rep_owner'        => '',
                'port_owner'       => '',
                'report_id'        => ''
            ]);
        }

        $prodid = !empty($this->request->getData('product_id')) ? $this->request->getData('product_id') : $session->read('Form.data.pdlr.product_id');
        $manid = !empty($this->request->getData('mandate')) ? $this->request->getData('mandate') : $session->read('Form.data.pdlr.mandate');

        if ($this->request->is('post')) {
            //load session with request data
            $session->write('Form.data.pdlr', $this->request->getData());
            //mandate
            if ($manid) {//from mandate name to portfolio_id 
                $getmandate = $this->Portfolio->find('all', ['fields' => ['mandate'], 'conditions' => ['Portfolio.portfolio_id' => $manid]])->first();
                $session->write('Form.data.pdlr.mandate_name', $getmandate->mandate);
                $cond_portfolio = Helpers::arrayPushAssoc($cond_portfolio, 'Portfolio.mandate', $session->read('Form.data.pdlr.mandate_name'));
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
                        $session->write('Form.data.pdlr.mandate', '');
                        $session->write('Form.data.pdlr.mandate_name', '');
                        unset($cond_portfolio['Portfolio.mandate']);
                        unset($cond_mandate['Product.product_id']);
                    } else {
                        $session->write('Form.data.pdlr.mandate_name', $getmandate->mandate);
                        $cond_portfolio = Helpers::arrayPushAssoc($cond_portfolio, 'Portfolio.mandate', $session->read('Form.data.pdlr.mandate_name'));
                    }
                }

                $cond_portfolio = Helpers::arrayPushAssoc($cond_portfolio, 'Product.product_id', $prodid);
                $cond_mandate = Helpers::arrayPushAssoc($cond_portfolio, 'Product.product_id', $prodid);
            }
        }

        // Top dashboard filters
        $periods = [];
        $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
        $before_year = date('Y', strtotime("-3 year", time()));
        $after_year = date('Y', strtotime("+1 year", time()));

        for ($year = $before_year; $year <= $after_year; $year++) {
            foreach ($quarters as $quarter) {
                $periods[$quarter . '_' . $year] = $quarter . ' ' . $year;
            }
        }
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
            'Received'   => 'Received',
            'Processing' => 'Processing',
            'Final'      => 'Final',
        ];
        $statuses = [
            'Ready for import'         => 'Ready for import',
            'Errors'                   => 'Errors',
            'Ready for reconciliation' => 'Ready for reconciliation',
            'Validated'                => 'Validated',
            'Capped'                   => 'Capped',
            'Invoiced'                 => 'Invoiced',
            'Paid'                     => 'Paid',
            'In progress...'           => 'In progress'
        ];

        //only show users linked to reports and portfolios
        $ownersr = $this->Report->find('list', ['fields' => ['owner'], 'keyField' => 'owner', 'valueField' => ['owner'],])->group('owner')->toArray();
        $ownersp = $this->Report->Portfolio->find('list', ['fields' => ['owner'], 'keyField' => 'owner', 'valueField' => ['owner'],])->group('owner')->toArray();

        $vusers = $this->getTableLocator()->get('Damsv2.VUser');
        $users_rep = $vusers->find('list', [
            'fields'     => ['first_name', 'last_name', 'id'],
            'keyField'   => 'id',
            'valueField' => ['full_name'],
            'conditions' => ['id in' => $ownersr],
            'order'      => ['last_name', 'first_name']
        ]);

        $users_port = $vusers->find('list', [
            'fields'     => ['first_name', 'last_name', 'id'],
            'keyField'   => 'id',
            'valueField' => ['full_name'],
            'conditions' => ['id in' => $ownersp],
            'order'      => ['last_name', 'first_name']
        ]);

        //product id
        if ($prodid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Product.product_id', $prodid);
        }

        //mandate name
        if (!empty($session->read('Form.data.pdlr.mandate_name'))) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Portfolio.mandate', $session->read('Form.data.pdlr.mandate_name'));
        }

        //portfolio id
        $portid = $session->read('Form.data.pdlr.portfolio_id');
        if ($portid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Report.portfolio_id', $portid);
        }

        //beneficiary
        $benid = $session->read('Form.data.pdlr.beneficiary_name');
        if ($benid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Portfolio.beneficiary_name', $benid);
        }

        //periods
        $perqid = $session->read('Form.data.pdlr.period_quarter');
        if ($perqid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Report.period_quarter', $perqid);
        }

        //period years
        $yearid = $session->read('Form.data.pdlr.period_year');
        if ($yearid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Report.period_year', $yearid);
        }

        //stage
        $stageid = $session->read('Form.data.pdlr.stage');
        if ($stageid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Status.stage', $stageid);
        }

        //status
        $statusid = $session->read('Form.data.pdlr.status');
        if ($statusid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Status.status', $statusid);
        }

        //report owner
        $repownid = $session->read('Form.data.pdlr.rep_owner');
        if ($repownid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Report.owner', $repownid);
        }

        //portfolio owner
        $portownid = $session->read('Form.data.pdlr.port_owner');
        if ($portownid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Portfolio.owner', $portownid);
        }

        //report id
        $repid = $session->read('Form.data.pdlr.report_id');
        if ($repid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Report.report_id', $repid);
        }


        $query = $this->Report->find('all', [
            'contain'    => ['Portfolio', 'Template', 'Template.TemplateType', 'Status', 'VUser', 'Portfolio.VUser', 'Portfolio.Product'],
            'conditions' => [$conditions]
        ]);

        $report = $this->paginate($query, ['contain' => ['Portfolio', 'Template', 'Template.TemplateType', 'Status', 'VUser', 'Portfolio.VUser', 'Portfolio.Product']]);

        $this->set(compact('report', 'mandates', 'portfolios', 'stages', 'statuses', 'users_rep', 'users_port', 'products', 'beneficiary', 'periods', 'session'));
    }

    public function pdlrValidation($report_id)
    {

        if (!$this->Report->exists($report_id)) {
            throw new NotFoundException(__('Invalid Report'));
        }

        $report = $this->Report->get($report_id, [
            'contain' => ['Portfolio', 'Status', 'Template'],
        ]);

        if (!$report->status_id == 9) {
            $this->Flash->error("Can't validate the report #$report_id. Wrong workflow step.");
            $this->redirect($this->referer());
        }

        $this->set('report', $report);

        $sasResult = $this->SAS->curl(
                'pdlr_validation.sas', [
            'report_id'        => $report_id,
            'template_type_id' => $report->template->template_type_id
                ],
                false,
                false
        );

        $dom = HtmlDomParser::str_get_html($sasResult);
        $table = $dom->find('table');

        $result = '';

        foreach ($table as $key => $t) {
            $t->class = 'table table-bordered table-striped';
            $t->frame = '';

            $result .= $t->outertext;
            ;
        }
        $total_value = $due_eur = $due_curr = $eif_due = '0.00';
        if ($res = $dom->find('#total', 0)) {
            $total_value = $res->innertext;
        }
        if ($res = $dom->find('#due_eur', 0)) {
            $due_eur = $res->innertext;
        }
        if ($res = $dom->find('#eif_due', 0)) {
            $eif_due = $res->innertext;
        }
        if ($res = $dom->find('#due_curr', 0)) {
            $due_curr = $res->innertext;
        }

        $this->set(compact('total_value', 'due_curr', 'due_eur', 'eif_due', 'result'));
    }

    public function pdlrValidationPdf($report_id)
    {
        $this->viewBuilder()->enableAutoLayout(false);
        if (!$this->Report->exists($report_id)) {
            throw new NotFoundException(__('Invalid Report'));
        }

        $report = $this->Report->get($report_id, [
            'contain' => ['Portfolio', 'Status', 'Template'],
        ]);
        $this->set('report', $report);

        $sasResult = $this->SAS->curl(
                'pdlr_validation.sas', [
            'report_id'        => $report_id,
            'template_type_id' => $report->template->template_type_id
                ],
                false,
                false
        );

        $dom = HtmlDomParser::str_get_html($sasResult);
        $table = $dom->find('table');

        $result = '';

        foreach ($table as $key => $t) {
            $t->class = 'table table-bordered table-striped';
            $t->frame = '';

            $result .= $t->outertext;
            ;
        }
        $total_value = $due_eur = $due_curr = $eif_due = '0.00';
        if ($res = $dom->find('#total', 0)) {
            $total_value = $res->innertext;
        }
        if ($res = $dom->find('#due_eur', 0)) {
            $due_eur = $res->innertext;
        }
        if ($res = $dom->find('#eif_due', 0)) {
            $eif_due = $res->innertext;
        }
        if ($res = $dom->find('#due_curr', 0)) {
            $due_curr = $res->innertext;
        }

        $this->set(compact('total_value', 'due_curr', 'due_eur', 'eif_due', 'result'));

        $this->viewBuilder()->setClassName('CakePdf.Pdf');
        $this->viewBuilder()->setOption(
                'pdfConfig',
                [
                    'orientation'      => 'portrait',
                    'download'         => true, // This can be omitted if "filename" is specified.
                    'filename'         => 'report_pdlr_validation_' . $report_id . '.pdf', //// This can be omitted if you want file name based on URL.
                    'user-style-sheet' => WWW_ROOT . 'css/site.css',
                ]
        );

//        error_log("pdlr_validation_pdf line " . __LINE__);
//        $pdfpath = WWW . DS . 'data' . DS . 'damsv2' . DS . 'reports' . DS . "report_pdlr_validation_" . $report_id . ".pdf";
//        $htmlpath = WWW . DS . 'data' . DS . 'damsv2' . DS . 'reports' . DS . "report_pdlr_validation_" . $report_id . ".html";
//        $f = fopen($htmlpath, 'w');
//        fputs($f, $raw);
//        fclose($f);
//        $pdf_generated = true;
//        if (!$pdf->saveAs($pdfpath)) {
//            $pdf_generated = false;
//            error_log('Could not create LM PDF: ' . $pdf->getError() . ' Please contact the administrator');
//            $commmand = 'wkhtmltopdf --footer-right "Page [page]/[topage]" --disable-smart-shrinking --page-size A4 --margin-left 1cm --margin-right 1cm --margin-bottom 1cm --margin-top 1cm --dpi 300 --user-style-sheet /var/www/html/php/app/View/Themed/Cakestrap/webroot/css/bootstrap.css ' . $htmlpath . '  ' . $pdfpath;
//            exec($commmand);
//            if (file_exists($pdfpath)) {
//                error_log('generated by ' . $commmand);
//                $pdf_generated = true;
//            }
//        }
//
//        error_log("pdlr_validation_pdf line " . __LINE__);
//        if ($pdf_generated) {
//            //$pdf_link = '/damsv2/damsv2ajax/download_file/1?file=/data/damsv2/reports/report_pdlr_validation_'.$report_id.".pdf";
//            $pdf_link = '/damsv2/damsv2ajax/download-file/report_pdlr_validation_' . $report_id . '.pdf/reports';
//            $this->set('pdf', $pdf_link);
//            //$this->render('/../Plugin/Damsv2/View/Invoices/add_pdf');
//
//            error_log("pdlr_validation_pdf line " . __LINE__);
//        }
//
//        error_log("pdlr_validation_pdf line " . __LINE__);
    }

    public function validationPage()
    {
        $this->autoRender = false;
        $report_id = $this->request->getData('Report.report_id');
        $action = $this->request->getData('Report.action');
        $this->validation($report_id, $action);
    }

    public function validation($report_id, $action)
    {
        $this->autoRender = false;

        // group retrieving  // http://vmu-sas-01:8080/browse/PLATFORM-259
//        $groups = CakeSession::read('UserAuth.UserGroups');
//        if (!is_array($groups))
//            $groups = array($groups);
//        if (!empty($groups))
//            foreach ($groups as $group) {
//                $groupsnames[] = $group['alias_name'];
//            }
//        if (in_array('ReadOnlyDams', $groupsnames)) {
//            $this->Session->setFlash("You are currently in a read only profile, this functionality is disabled", "flash/error");
//            $this->redirect($this->referer());
//            exit();
//        }

        if (!$this->Report->exists($report_id)) {
            throw new NotFoundException(__('Invalid Report'));
        }

        $report = $this->Report->get($report_id, [
            'contain' => ['Template'],
        ]);
        $action_redirect = 'inclusion';
        $background = false;
        $valid = false;
        $array_PDLR = [2, 3];

        if (!empty($report)) {
            switch ($action) {
                case 'valid':
                    /* $this->Report->read(null,$report_id);
                      $this->Report->set('status_id', 19);
                      $this->Report->save(); */

                    $running = Cache::read("import_file_running_report_" . $report_id, 'damsv2');
                    while ($running == 'running') {
                        sleep(1);
                        error_log("waiting for import_file.sas to be finished with report " . $report_id);
                        $running = Cache::read("import_file_running_report_" . $report_id, 'damsv2');
                    }
                    if (!empty($running)) {
                        $sasResult = $running;
                        error_log("validation report " . $report_id . " import_file.sas saved : " . $sasResult);
                        file_put_contents("/tmp/validation_report_" . $report_id . "_" . time(), "validation report " . $report_id . " import_file.sas saved : " . $sasResult);
                        //check if valid is returned from sas

                        $dom = HtmlDomParser::str_get_html($sasResult);

                        $validids = $dom->find('#valid_store');
                        foreach ($validids as $v) {
                            $val = trim($v->innertext);
                            if ($val == '1')
                                $valid = 1;
                            if ($val == '2')
                                $valid = 2;
                        }
                        $this->Flash->success('The report file <strong>#' . $report_id . '</strong> is being saved', ['escape' => false]);
                    } else {
                        Cache::write("import_file_running_report_" . $report_id, "running", 'damsv2');

                        $params = [
                            'report_id'             => $report_id,
                            'template_type_id'      => $report->template->template_type_id,
                            'user_id'               => $this->userIdentity()->get('id'),
                            'save'                  => 1,
                            'correction'            => 0,
                            'background_validation' => 1
                        ];
                        if (!empty($report->agreed_pv_comments)) {
                            $params['coments_for_agreed_pv'] = 1;
                        }
                        $sasResult = $this->SAS->curl(
                                'import_file.sas',
                                $params,
                                false,
                                true
                        );
                        Cache::write("import_file_running_report_" . $report_id, $sasResult, 'damsv2');
                        if (in_array($report->template->template_type_id, $array_PDLR)) {
                            $this->logDams('PDLR report validated: ' . json_encode($params), 'dams', 'Validate PDLR report');
                            $this->Flash->success("The report file <strong>#" . $report_id . "</strong> is being saved.", ['escape' => false]);
                        } else {
                            $this->logDams('report validated: ' . json_encode($params), 'dams', 'Validate report');
                        }
                        $background = true;
                        sleep(5); //wait 5 sec for SAS to proceed
                        error_log("validation report " . $report_id . " import_file.sas run in background");
                    }
                    break;
            }

            if (in_array($report->template->template_type_id, $array_PDLR)) {
                $action_redirect = 'pdlr';
            }
            $action_redirect_param = null;
            if ($background) {
                $action_redirect_param = ['inclusion_background' => $report_id];
            } elseif (empty($valid)) {
                $action_redirect_param = ['inclusion_error' => $report_id];
            } elseif ($valid == 1) {
                $action_redirect_param = ['inclusion_success' => $report_id];
            } elseif ($valid == 2) {
                $action_redirect_param = ['inclusion_additional_check' => $report_id];
            }
            $this->redirect(['action' => $action_redirect, '?' => $action_redirect_param]);
        } else {
            $this->Flash->error('Missing report data error!');
            $this->redirect($this->referer());
        }
    }

    /**
     * Validate or Reject a PDLR report
     * @param $id Report ID in case of update
     * @return void
     */
    public function pdlrReconciliation($id = null)
    {
        if (!$this->Report->exists($id)) {
            throw new NotFoundException(__('Invalid Report'));
        }
        $report = $this->Report->get($id, [
            'contain' => ['Portfolio', 'VUser', 'Template', 'Template.TemplateType'],
        ]);

        $this->set(compact('report'));

        if ($this->request->is('post')) {
            
        }
    }

    /**
     * View method
     *
     * @param string|null $id Report id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        if (empty($id)) {
            throw new NotFoundException;
        }
        $report = $this->Report->get($id, [
            'contain' => ['Portfolio', 'Template', 'Status', 'Invoice'],
        ]);

        $this->set(compact('report'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $report = $this->Report->newEmptyEntity();
        if ($this->request->is('post')) {
            $report = $this->Report->patchEntity($report, $this->request->getQuery());
            if ($this->Report->save($report)) {
                $this->Flash->success(__('The report has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The report could not be saved. Please, try again.'));
        }
        $portfolios = $this->Report->Portfolio->find('list', ['limit' => 200]);
        $templates = $this->Report->Template->find('list', ['limit' => 200]);
        $statuses = $this->Report->Status->find('list', ['limit' => 200]);
        $invoices = $this->Report->Invoice->find('list', ['limit' => 200]);
        $this->set(compact('report', 'portfolios', 'templates', 'statuses', 'invoices'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Report id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if (empty($id)) {
            throw new NotFoundException;
        }
        $report = $this->Report->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $report = $this->Report->patchEntity($report, $this->request->getQuery());
            if ($this->Report->save($report)) {
                $this->Flash->success(__('The report has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The report could not be saved. Please, try again.'));
        }
        $portfolios = $this->Report->Portfolio->find('list', ['limit' => 200]);
        $templates = $this->Report->Template->find('list', ['limit' => 200]);
        $statuses = $this->Report->Status->find('list', ['limit' => 200]);
        $invoices = $this->Report->Invoice->find('list', ['limit' => 200]);
        $this->set(compact('report', 'portfolios', 'templates', 'statuses', 'invoices'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Report id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
//    public function delete($id = null)
//    {
//        if (empty($id)) {
//            throw new NotFoundException;
//        }
//        $this->request->allowMethod(['post', 'delete']);
//        $report = $this->Report->get($id);
//        if ($this->Report->delete($report)) {
//            $this->Flash->success(__('The report has been deleted.'));
//        } else {
//            $this->Flash->error(__('The report could not be deleted. Please, try again.'));
//        }
//
//        return $this->redirect(['action' => 'index']);
//    }

    public function deletePdlr()
    {
        if ($this->request->is('post')) {
            if (empty($this->request->getData('Report.report_id'))) {
                $this->Flash->error('empty id error!');
                $this->redirect($this->referer());
            } else if (!ctype_digit($this->request->getData('Report.report_id'))) {
                $this->Flash->error('not number error!');
                $this->redirect($this->referer());
            } else {

                $current_url = $_SERVER['REQUEST_URI'];
                $report_id = (int) $this->request->getData('Report.report_id');

                $report = $this->Report->get($report_id);

                if (($report->status_id == 8)) {


                    //delete the report object from db
                    $del = $this->Report->delete($report);

                    if ($del) {
                        $this->Flash->success("The report #$report_id has been deleted.");
                        $log_info = [
                            'report_id'          => $report_id,
                            'report_name'        => $report->report_name,
                            'period_quarter'     => $report->period_quarter,
                            'period_year'        => $report->period_year,
                            'period_start_date'  => $report->period_start_date,
                            'period_end_date'    => $report->period_end_date,
                            'portfolio_id'       => $report->portfolio_id,
                            'template_id'        => $report->template_id,
                            'status_id'          => $report->status_id,
                            'status_id_umbrella' => $report->status_id_umbrella,
                            'owner'              => $report->owner,
                            'report_type'        => $report->report_type,
                            'version_number'     => $report->version_number
                        ];
                        Helpers::logDams('Report deleted: ' . json_encode($log_info), 'dams', 'Delete report', $current_url);
                        $this->logDams('Report deleted: ' . json_encode($log_info), 'dams', 'Delete report');
                    } else {
                        $this->Flash->error("The report #$report_id could not be deleted.");
                    }
                } else {
                    $this->Flash->error("The report #$report_id could not be deleted.");
                }
                $this->redirect($this->referer());
            }
        }
    }

    public function delete()
    {
        if ($this->request->is('post')) {
            if (empty($this->request->getData('Report.report_id'))) {
                $this->Flash->error('empty id error!');
                $this->redirect($this->referer());
            } else if (!ctype_digit($this->request->getData('Report.report_id'))) {
                $this->Flash->error('not number error!');
                $this->redirect($this->referer());
            } else {

                $current_url = $_SERVER['REQUEST_URI'];
                $report_id = (int) $this->request->getData('Report.report_id');

                $report = $this->Report->get($report_id);

                if (($report->status_id == 1) || ($report->status_id == 2)) {
                    $ajaxControler = new AjaxController();
                    //$portfolio_id = $report->portfolio_id;
                    $is_umbrella = $ajaxControler->belongToUmbrella($report->portfolio_id);

                    if ($is_umbrella) {
                        $connection = ConnectionManager::get('default');
                        $report_data = $connection
                                ->execute('SELECT report_id, report_name, status_id, status_id_umbrella, input_filename, input_filename_umbrella, portfolio_id, period_year, period_quarter FROM report WHERE report_id = :id', ['id' => $report_id])
                                ->fetchAll('assoc');
                        $report_data = $report_data[0];

                        if (empty($report_data["status_id_umbrella"])) {
                            $report_data["status_id_umbrella"] = 'null';
                        }

                        if (empty($report_data["input_filename"])) {
                            $report_data["input_filename"] = 'null';
                        }

                        $report_data_sql = $report_data["report_id"] . ", '" . $report_data["report_name"] . "', " . $report_data["status_id"] . ", " . $report_data["status_id_umbrella"] . ", '" . $report_data["input_filename"] . "', " . $report_data["portfolio_id"] . ", '" . $report_data["period_year"] . $report_data["period_quarter"] . "'";

                        $del = $this->Report->delete($report);
                        if ($del) {
                            $connection->execute("INSERT INTO umbrella_portfolio_deleted (report_id, report_name, status_id, status_id_umbrella, input_filename, portfolio_id, period) values (" . $report_data_sql . ")");
                        }
                    } else {
                        //delete the report object from db
                        $del = $this->Report->delete($report);
                    }
                    if ($del) {
                        $this->Flash->success("The report #$report_id has been deleted.");
                        $log_info = [
                            'report_id'          => $report_id,
                            'report_name'        => $report->report_name,
                            'period_quarter'     => $report->period_quarter,
                            'period_year'        => $report->period_year,
                            'period_start_date'  => $report->period_start_date,
                            'period_end_date'    => $report->period_end_date,
                            'portfolio_id'       => $report->portfolio_id,
                            'template_id'        => $report->template_id,
                            'status_id'          => $report->status_id,
                            'status_id_umbrella' => $report->status_id_umbrella,
                            'owner'              => $report->owner,
                            'report_type'        => $report->report_type,
                            'version_number'     => $report->version_number
                        ];
                        Helpers::logDams('Report deleted: ' . json_encode($log_info), 'dams', 'Delete report', $current_url);
                        $this->logDams('Report deleted: ' . json_encode($log_info), 'dams', 'Delete report');
                    } else {
                        $this->Flash->error("The report #$report_id could not be deleted.");
                    }
                } else {
                    $this->Flash->error("The report #$report_id could not be deleted.");
                }
                $this->redirect($this->referer());
            }
        }
    }

    public function rejectReport()
    {
        if ($this->request->is('post')) {
            if (empty($this->request->getData('Report.report_id'))) {
                $this->Flash->error('empty id error!');
                $this->redirect($this->referer());
            } else if (!ctype_digit($this->request->getData('Report.report_id'))) {
                $this->Flash->error('not number error!');
                $this->redirect($this->referer());
            } else {
                $report_id = $this->request->getData('Report.report_id');
                $report_path = '/var/www/html/data/damsv2/reports/';

                Cache::delete('import_file_running_report_' . $report_id, 'damsv2');
                Cache::delete('inclusion_validation_' . $report_id, 'damsv2');
                Cache::delete('inclusion_validation_report_' . $report_id, 'damsv2');
                Cache::delete('inclusion_validation_report_apv_breakdown_' . $report_id, 'damsv2');
                @unlink($report_path . 'eif_import_file_running_report_' . $report_id);
                @unlink($report_path . 'eif_inclusion_validation_report_' . $report_id . '.pdf');
                @unlink($report_path . 'eif_inclusion_validation_' . $report_id);
                @unlink($report_path . 'eif_inclusion_validation_report_' . $report_id);
                @unlink($report_path . 'eif_inclusion_validation_report_apv_breakdown_' . $report_id);
                @unlink('/var/www/html/data/damsv2/waiver_reasons/draft/transactions_exemption_' . $report_id . '.xlsx');
                @unlink('/var/www/html/data/damsv2/waiver_reasons/draft/subtransactions_exemption_' . $report_id . '.xlsx');
                @unlink('/var/www/html/data/damsv2/waiver_reasons/draft/sme_exemption_' . $report_id . '.xlsx');
                @unlink('/var/www/html/data/damsv2/waiver_reasons/validated/transactions_exemption_' . $report_id . '.xlsx');
                @unlink('/var/www/html/data/damsv2/waiver_reasons/validated/subtransactions_exemption_' . $report_id . '.xlsx');
                @unlink('/var/www/html/data/damsv2/waiver_reasons/validated/sme_exemption_' . $report_id . '.xlsx');

                $this->changeStatus($report_id, 2, true, 'inclusion', '');
            }
        }
    }

    public function inclusionHistory($report_id = null)
    {
        if (empty($report_id)) {
            throw new NotFoundException;
        }

        $report = $this->Report->get($report_id, [
            'contain' => ['Portfolio'],
        ]);

        if (empty($report)) {
            $this->Flash->error('Invalid Report!');
            $this->redirect(['action' => 'inclusion']);
        }

        $dir = '/var/www/html' . DS . 'data' . DS . 'damsv2' . DS;

        $uploads = [];

        foreach (glob($dir . "upload" . DS . "inclusion_" . $report_id . "_v*") as $filename) {
            $uploads[] = basename($filename);
        }

        foreach (glob(DS . "backup" . DS . "archiving" . DS . "upload" . DS . "inclusion_" . $report_id . "_v*") as $filename) {
            $uploads[] = basename($filename);
        }
        natsort($uploads);
        $errors = [];
        foreach (glob($dir . "error" . DS . "error_" . $report_id . "_v*") as $filename) {
            $errors[] = basename($filename);
        }
        foreach (glob(DS . "backup" . DS . "archiving" . DS . "error" . DS . "error_" . $report_id . "_v*") as $filename) {
            $errors[] = basename($filename);
        }
//        if (file_exists(DS . "backup" . DS . "data" . DS . "damsv2" . DS . "error" . DS . $report_id . ".json")) {
//            $versions = json_decode(file_get_contents(DS . "backup" . DS . "data" . DS . "damsv2" . DS . "error" . DS . $report_id . ".json"));
//            foreach ($versions as $version) {
//                $errors[] = "error_" . $report_id . "_v" . $version . ".xml";
//            }
//        }
        natsort($errors);
        $this->set(compact('report', 'uploads', 'errors'));
    }

    /**
     * Validate a Report
     * @param Report $id
     */
    public function inclusionValidation($report_id)
    {
        if (!$this->Report->exists($report_id)) {
            throw new NotFoundException(__('Invalid Report'));
        }
        $report = $this->Report->get($report_id, [
            'contain' => ['Portfolio', 'Status'],
        ]);

        $status = $report->status_id;
        $report_path = '/var/www/html/data/damsv2/reports/';
        $viewonly = false;

        if (!$status == 4 OR!$status == 5) {
            $this->Flash->error("Can't validate the report <strong>#$report_id</strong>. Wrong workflow step.", ['escape' => false]);
            $this->redirect($this->referer());
        }
        if ($status == 5) {
            $viewonly = true;
        }

        //caching the report in order to "freeze" it as it before it is saved (and completed)
        clearstatcache(true);
        if (file_exists($report_path . 'eif_inclusion_validation_' . $report_id)) {
            error_log("inclusion validation : cache read");
            $sasResult = file_get_contents($report_path . 'eif_inclusion_validation_' . $report_id);
        } elseif (!$viewonly) {
            $sasResult = $this->SAS->get_cached_content('inclusion_validation_' . $report_id, "damsv2", "inclusion_validation.sas", ['report_id' => $report_id], true);
        }

        if (empty($sasResult)) {
            $this->Flash->error('The inclusion report is missing!');
            $this->redirect($this->referer());
        } else {

            $modifications_expected = $report->portfolio->modifications_expected;
            $m_files_link = !empty($report->m_files_link) ? $report->m_files_link : $report->portfolio->m_files_link;

            $this->set('modifications_expected', $modifications_expected);
            $this->set('m_files_link', $m_files_link);

            error_log("inclusion validation report id : " . $report_id);
            error_log("inclusion validation content : " . $sasResult);

            $dom = HtmlDomParser::str_get_html($sasResult);
            $table = $dom->find('table');

            $h5 = $dom->find('h5');
            $valid = $dom->find('#valid');
            $ths = $dom->find('th'); // th with Number of SME Transactions as text
            $num_sme = 0;

            foreach ($ths as $th) {
                if (trim($th->innertext) == 'Number of SME Transactions') {
                    $tr = $th->parent();
                    $thead = $tr->parent();
                    $table_sme = $thead->parent();
                    $td_sme = $table_sme->find('td', 0); //the first td element of the table

                    $num_sme = intval(trim($td_sme->innertext));
                }
            }
            if (empty($report->inclusion_notice_received)) {
                if ($num_sme > 0) {
                    $report->inclusion_notice_received = 'FALSE';
                    $this->Report->save($report);
                }
            }

            //file_put_contents("/tmp/test_dams", "\n".$sasResult, FILE_APPEND);
            $save = 0;
            foreach ($valid as $v) {
                $val = trim($v->innertext);
                if ($val == '1') {
                    $save = 1;
                }
                if ($val == '0') {
                    $save = 2;
                }
            }

            $result = '';

            foreach ($table as $key => $t) {
                $t->class = 'table table-bordered table-striped';
                $t->frame = '';
                $tds = $t->find('td');

                $error = false;
                if (strpos(strtolower($t->outertext), strtolower('WORK.NOT_REPORTED_INCTR')) !== false) {
                    $error = true;
                }

                foreach ($tds as $td) {
                    $td->width = '20%';
                    if (!empty($error)) {
                        $td->class = 'text-error';
                    }
                }

                if (strpos(strtolower($t->outertext), strtolower('WORK.NOT_REPORTED_INCTR')) !== false) {
                    $t->class .= ' error';
                }

                $result .= $h5[$key]->outertext . $t->outertext;
            }

            $warnings = $this->Report->getWarningsPortfolioVolume($report_id);
            $apvExceeded = $warnings["apvExceeded"];
            $warning_agreed_portfolio_volume = $warnings["warning_agreed_portfolio_volume"];
            $apvDecrease = $warnings["apvDecrease"];
            $mgv = $warnings["mgv"];
            $agreed_ga = $warnings["agreed_ga"];
            $total_principal_disbursement = $warnings["total_principal_disbursement"];
            $aga_nonCOVID19 = $warnings["aga_nonCOVID19"];
            $covid_19_enhanced_rate_transactions = $warnings["covid_19_enhanced_rate_transactions"];
            $agreed_ga_portfolios_list = [5, 6]; //cosme and innovfin

            $this->set(compact('report', 'result', 'viewonly', 'save', 'num_sme', 'apvExceeded', 'warning_agreed_portfolio_volume', 'apvDecrease', 'mgv', 'agreed_ga', 'agreed_ga_portfolios_list', 'total_principal_disbursement', 'aga_nonCOVID19', 'covid_19_enhanced_rate_transactions'));
        }
    }

    public function setInclusionNoticeReceived()
    {
        if ($this->request->is('ajax')) {
            //set view to Ajax
            $this->viewBuilder()->setLayout('ajax');
            if (!empty($this->request->getData('Report.report_id')) && !empty($this->request->getData('Report.inclusion_notice_received'))) {
                $report_id = $this->request->getData('Report.report_id');
                $report = $this->Report->get($report_id);

                if ($this->request->getData('Report.inclusion_notice_received') == 'TRUE') {
                    $report->inclusion_notice_received = 'TRUE';
                    $report->inclusion_notice_validator = $this->userIdentity()->get('id');
                } else {
                    $report->inclusion_notice_received = 'FALSE';
                    $report->inclusion_notice_validator = null;
                }
                $this->Report->save($report);
                $echotext = 'Inclusion Notice set to : ' . $report->inclusion_notice_received;
                $this->set(compact('echotext'));
            }
        }
    }

    /**
     * import method
     * @return void
     */
    public function inclusionImport($id = null)
    {
        if (empty($id)) {
            throw new NotFoundException;
        }

        $report = $this->Report->get($id, [
            'contain' => ['Portfolio', 'Template', 'Status', 'Portfolio.Product', 'VUser'],
        ]);

        $provisional_portfolio_volume = null;
        if (!empty($report) && !empty($report->provisional_pv)) {
            $provisional_portfolio_volume = $report->provisional_pv;
        }
        $product = $report->portfolio->product_id;

        $owner = !(empty($report->v_user)) ? $report->v_user->full_name : '';

        $vusers = $this->getTableLocator()->get('Damsv2.VUser');
        $users = $vusers->find('list', [
            'fields'     => ['first_name', 'last_name', 'id'],
            'keyField'   => 'id',
            'valueField' => ['full_name'],
            'order'      => ['last_name', 'first_name']
        ]);

        $user_id = $this->userIdentity()->get('id');
        $type_id = 1;

        //Warning message when inclusion end date reached
        $warning_closure = false;
        if (!empty($report->portfolio->inclusion_end_date)) {
            $no_closure_products = [27]; // DDF
            if (($report->report_type == 'regular') && (!in_array($report->portfolio_id, $no_closure_products))) {
                //if no closure report for the portfolio
                $closure_report_portfolio = $this->Report->find('all', ['conditions' => ['portfolio_id' => $report->portfolio_id, 'report_type' => 'closure']])->first();

                if (empty($closure_report_portfolio)) {
                    if ($report->portfolio->status_portfolio !== 'CLOSED') {//portfolio not closed
                        if ($report->period_end_date >= $report->portfolio->inclusion_end_date) {
                            $warning_closure = true;
                        }
                    }
                }
            }
        }

        $portfolio = $report->portfolio;

        $modifications_expected = $portfolio->modifications_expected;

        error_log("modifications_expected:" . json_encode($portfolio));
        $this->set('modifications_expected', $modifications_expected);


        // KYC embargo block inclusions
        $KYC_embargo_ongoing = false;
        if (!empty($portfolio->kyc_embargo)) {
            $product_cosme_innofin = [5, 6];
            if (in_array($product, $product_cosme_innofin)) {
                if ($portfolio->kyc_embargo == 'Yes') {
                    // if previous inclusions
                    $previous_inclusions = $this->Report->find('all', [
                                'conditions' => [
                                    'Report.portfolio_id' => $report->portfolio_id,
                                    'Report.status_id'    => 5
                                ]
                            ])->first();
                    if (empty($previous_inclusions)) {
                        $KYC_embargo_ongoing = true;
                    }
                }
            }
        }
        $this->set('warning_closure', $warning_closure);
        $this->set('warning_closure_date', $report->portfolio->inclusion_end_date);
        $this->set(compact('report', 'product', 'owner', 'users', 'user_id', 'type_id', 'provisional_portfolio_volume', 'KYC_embargo_ongoing'));

        //processing the form
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            $reportData = $this->Report->patchEntity($report, $this->request->getData());

            // group retrieving  // http://vmu-sas-01:8080/browse/PLATFORM-259
//            $groups = CakeSession::read('UserAuth.UserGroups');
//            if (!is_array($groups))
//                $groups = array($groups);
//            if (!empty($groups))
//                foreach ($groups as $group) {
//                    $groupsnames[] = $group['alias_name'];
//                }
//            if (in_array('ReadOnlyDams', $groupsnames)) {
//                $this->Flash->error("You are currently in a read only profile, this functionality is disabled");
//                $this->redirect($this->referer());
//            }

            if (!$reportData->getErrors) {
                $ajaxControler = new AjaxController();
                $is_umbrella = $ajaxControler->isUmbrella($reportData->portfolio_id);

                if (!$is_umbrella) {
                    $this->loadModel('Damsv2.Rules');
                    $brules_valid = $this->Rules->brulesValid($reportData);
                }

                if (empty($reportData->template_id)) {
                    $this->Flash->error("This portfolio doesn't have a Template");
                    $this->redirect($this->referer());
                }
                if ((!$is_umbrella) && (!$brules_valid)) {
                    $this->Flash->error("At least 1 consistency rule and 1 eligibility rule applicable to this portfolio are required to process the report");
                    $this->redirect($this->referer());
                }
                $requestTemplateid = $this->request->getData('Template.id');
                foreach ($portfolio as $template) {
                    if ($template->template_type_id == 1) {
                        //$this->request->getData('Template.id') = $template->template_id;
                        $requestTemplateid = $template->template_id;
                    }
                }

                //file info processing
                $file = $this->request->getData('file');
                $file_name = $file->getClientFilename();

                $ext = pathinfo($file_name, PATHINFO_EXTENSION);

                $file_renamed = "inclusion_" . $reportData->report_id . '_v' . $reportData->version_number . '.' . $ext;

                $checks = '';
                $fileMovingPath = '/var/www/html' . DS . 'data' . DS . 'damsv2' . DS . 'upload' . DS . $file_renamed;

                if ($this->File->checkFileInForm($file, $fileMovingPath)) {

                    $checks = $this->Spreadsheet->checkSheetInclusionImport($postData, $file, $fileMovingPath);

                    $filename = "";
                    // saving new filename for sas script, DAMS-476
                    if (!empty($checks['transcode']['filename'])) {

                        $reportData->sheets = implode('$$', $this->request->getData('Report.sheets'));
                        $reportData->input_filename = $checks['transcode']['filename'];

                        $filename = $checks['transcode']['filename'];
                    } else {
                        $filename = $file_renamed;
                    }

                    error_log("import xls check : " . json_encode($checks['errors']));

                    if ($this->Spreadsheet->noError($checks['errors'])) {//do not cumulate errors
                        // DAMS 473
                        $data_num_check = [
                            'report_id'            => $reportData->report_id,
                            'save'                 => 0,
                            'correction'           => 0,
                            'template_id'          => $this->request->getData('Template.id'),
                            'version'              => $reportData->version_number,
                            'version_number_check' => $reportData->version_number,
                            'input_filename_check' => $filename,
                            'headers_included'     => ($this->request->getData('Report.header') == '1' ? 'yes' : 'no'),
                            'template_type_id'     => 1
                        ];


                        $numerical_errors = $this->numCheckSas($data_num_check);
                        $checks['errors'] = array_merge($checks['errors'], $numerical_errors);
                        // END DAMS 473
                    }
                    $errorsLog = $this->getTableLocator()->get('Damsv2.ErrorsLog');
                    if ($this->Spreadsheet->noError($checks['errors'])) {
                        //fill the table errors_logs
                        //create a new row each time you edit import of inclusion file (increment the field iterations)
                        //$errorsLog->checkErrorImport($reportData, 'OK');// done in spreadsheet component

                        $reportData->report_name = $report->portfolio->portfolio_name . "_" . $report->period_year . $report->period_quarter . "_v" . $reportData->version_number;
                        $reportData->provisional_pv = str_replace(',', '', $this->request->getData('Report.provisional_pv'));
                        //unset($reportData['Report']['modified'], $reportData['Report']['file'], $reportData['Report']['id']);

                        $reportData->status_id = 19;

                        if (!empty($checks['transcode']['filename'])) {
                            $reportData->input_filename = $checks['transcode']['filename'];
                        }

                        $this->Report->save($reportData);

                        $ajaxControler = new AjaxController();
                        $portfolio_id = $report->portfolio_id;
                        if ($ajaxControler->testUmbrella('' . $portfolio_id)) {
                            $report_ids = $ajaxControler->getSubPortfoliosReportsFromUmbrellaId($portfolio_id);

                            $sasResult = $this->SAS->curl(
                                    'automatic_inclusion.sas', [
                                'list_auto_rep' => implode('$$', $report_ids)
                                    ],
                                    false,
                                    true
                            );
                        } else {
                            Cache::delete('eif_import_file_running_report_' . $id, 'damsv2');
                            $sasResult = $this->SAS->curl(
                                    'import_file.sas', [
                                'report_id'        => $id,
                                'template_type_id' => 1,
                                'correction'       => 0,
                                'save'             => 0
                                    ],
                                    false,
                                    true
                            );
                            $log_params = [
                                'report_id'    => $id,
                                'portfolio_id' => $report->portfolio_id,
                                'user_id'      => $this->userIdentity()->get('id'),
                                'correction'   => 0,
                                'save'         => 0
                            ];

                            $this->logDams('Include report: ' . json_encode($log_params), 'dams', 'Include report');
                        }

                        $this->Flash->success("The report #" . $id . " is being processed");
                        $this->redirect($this->referer());
                    } else {
                        $reportData->status_id = 2;
                        $this->Report->save($reportData);
                        error_log("reportcontroler l " . __LINE__ . " errors " . json_encode($checks));
                        $msg = $this->Spreadsheet->showError($checks['errors']);
                        $errorsLog->checkErrorImport($report, 'NOT OK');
                        error_log("error xls : " . $msg);
                        $this->Flash->error($msg, ['escape' => false]);
                        $this->redirect($this->referer());
                    }
                }
            }
        }
    }

    public function inclusionValidationReport($report_id = null)
    {
        if (empty($report_id)) {
            throw new NotFoundException;
        }

        $report_path = '/var/www/html/data/damsv2/reports/';

        $report = $this->Report->get($report_id, [
            'contain' => ['Portfolio', 'Status', 'Portfolio.Product'],
        ]);

        $portfolios = $this->getTableLocator()->get('Damsv2.Portfolio');
        $portfolio = $portfolios
                ->find()
                ->where(['portfolio_id' => $report->portfolio_id])
                ->first();

        //check the logic behind this test, old test was comparing to 1, but this value does not exist in the status_portfolio
        $portfolio_apv = $portfolio->status_portfolio == 'CLOSED' &&  !empty($portfolio->apv_at_closure)? $portfolio->apv_at_closure : null;
        $force = $report->status_id == 4 ? true : false;

        $viewonly = false;
        // group retrieving  // http://vmu-sas-01:8080/browse/PLATFORM-259
//        $groups = CakeSession::read('UserAuth.UserGroups');
//        if (!is_array($groups)) {
//            $groups = array($groups);
//        }
//        if (!empty($groups)) {
//            foreach ($groups as $group) {
//                $groupsnames[] = $group['alias_name'];
//            }
//        }
//        if (in_array('ReadOnlyDams', $groupsnames)) {
//            $viewonly = true;
//        }
        //apv breakdown
        clearstatcache(true);
        if (file_exists($report_path . 'eif_inclusion_validation_report_apv_breakdown_' . $report_id)) {
            $sasResult_apv_breakdown = file_get_contents($report_path . 'eif_inclusion_validation_report_apv_breakdown_' . $report_id);
        } elseif (($report->status_id != 5) && (!$viewonly)) {
            $sasResult_apv_breakdown = $this->SAS->get_cached_content('inclusion_validation_report_apv_breakdown_' . $report_id,
                    "damsv2",
                    "apv_breakdown.sas", [
                "portfolio_id" => $report->portfolio_id,
                'report_id'    => $report_id,
                    ],
                    false
            );
        }
        $dom = HtmlDomParser::str_get_html($sasResult_apv_breakdown);

        $apv_breakdown_link = $dom->find('#apv_breakdown');

        $apv_breakdown_path = null;
        foreach ($apv_breakdown_link as $apv) {
            if (!empty($apv->href)) {
                $apv_breakdown_href = $apv->href;
                $apv_breakdown_path_array = explode('/', $apv_breakdown_href);

                $apv_breakdown_path = $apv_breakdown_path_array[count($apv_breakdown_path_array) - 1];
            }
        }

        if (!file_exists($report_path . $apv_breakdown_path)) {
            $apv_breakdown_path = false;
        }

        if (file_exists($report_path . 'eif_inclusion_validation_report_' . $report_id)) {
            $sasResult = file_get_contents($report_path . 'eif_inclusion_validation_report_' . $report_id);
        } elseif ($report->status_id != 5) {
            $sasResult = $this->SAS->get_cached_content('inclusion_validation_report_' . $report_id, "damsv2", "inclusion_validation_report.sas", ['report_id' => $report_id], $force);
        }

        //create pdf from html file
        //$path = DAMSCACHE . 'eif_inclusion_validation_report_' . $report_id;
        $path = $report_path . 'eif_inclusion_validation_report_' . $report_id . '.pdf';
        $this->set('pdf', $path);
        $this->set('report_id', $report_id);
        $this->set('report', $report);

        $dom = HtmlDomParser::str_get_html($sasResult);
        $table = $dom->find('table');
        $warning = $dom->find('#warning');

        //to parse the sas result anf find if there is a warning
        $msgWarning = 0;
        $warning_agreed_portfolio_volume = false;
        foreach ($warning as $m) {
            $val = trim($m->innertext);
            if ($val == '1')
                $msgWarning = 1;
            if ($val == '-1')
                $msgWarning = -1;
            if ($val == '2')
                $msgWarning = 2;
        }
        $apvExceeded = false;
        $apvDecrease = false;
        $mgv = false;
        $agreed_ga = false;
        $aga_nonCOVID19 = false;
        $total_principal_disbursement = false;
        $covid_19_enhanced_rate_transactions = false;
        $warning = $dom->find('.warning');

        foreach ($warning as $m) {
            $val = trim($m->innertext);
            if ($val == 'w4') {
                //condition Agreed Portfolio Volume (AgPV)
                $warning_agreed_portfolio_volume = true;
            }
            if ($val == 'w5') {
                //condition Maximum Portfolio Volume (MPV)
                //$warning_agreed_portfolio_volume = true;
                $apvExceeded = true;
            }
            if ($val == 'w6') {
                //condition APV decrease and cap reached 
                $apvDecrease = true;
            }
            if ($val == 'w7') {
                //condition Maximum Guaranteed Volume (MGV)
                $mgv = true;
            }
            if ($val == 'w8') {
                //condition agreed garantee amount (agreed_ga)
                $agreed_ga = true;
            }
            if ($val == 'w9') {
                //condition agreed garantee amount (agreed_ga)
                $total_principal_disbursement = true;
            }
            if ($val == 'w10') {
                //condition non-COVID19 enhanced rate transactions Actual GA > Max GA
                $aga_nonCOVID19 = true;
            }
            if ($val == 'w10_warning') {
                //condition COVID19 enhanced rate transactions detected in the inclusion file. Please prioritize the inclusion of these transactions.
                $covid_19_enhanced_rate_transactions = true;
            }
        }

        $result = '';

        foreach ($table as $key => $t) {
            $t->class = 'table table-bordered table-striped';
            $t->frame = '';
            $tds = $t->find('td');
            $ths = $t->find('th');
            foreach ($ths as $th) {
                $th->outertext = str_replace("<br>", " ", $th->outertext);
            }

            $result .= $t->outertext;
        }

        $product = $report->portfolio->product->product_id;
        /* $SMEI_Italy_Mandate = in_array($report['Portfolio']['mandate'], array('SMEi Italy', 'SME Initiative - Italy'));
          if ($warning_agreed_portfolio_volume && in_array($product, array(3,5,6,12,26)) && !$SMEI_Italy_Mandate)//blocking for all products, message only for those products (RSI, InnovFin SMEG, SME Initiative,ESIF AGRI FLPG Italy)
          {
          $warning_agreed_portfolio_volume = true;
          }
          else
          {
          $warning_agreed_portfolio_volume = false;
          } */
        //products id : 'PRSL' : 4, EPMF FCP : 7, EREM CBSI : 17
        $prsl_products = [4, 7, 17];
        if ($apvExceeded && in_array($product, $prsl_products)) {
            $apvExceeded = false;
        }

        if ($apvDecrease && $this->Report->getProductForApvDecrease($product, $report_id)) {
            $apvDecrease = true;
        } else {
            $apvDecrease = false;
        }
        //mgv is applicable to InnovFin and COSME products only
        $innofvinANDcosmeProducts = [5, 6, 13, 15];
        if ($mgv && in_array($product, $innofvinANDcosmeProducts)) {
            $mgv = true;
        } else {
            $mgv = false;
        }
        //agreed_ga is applicable to COSME products only + innovfin
        $cosmeProducts = [6, 5];
        if ($agreed_ga && in_array($product, $cosmeProducts)) {
            $agreed_ga = true;
        } else {
            $agreed_ga = false;
        }

        //total_principal_disbursement
        $mandate = $report->portfolio->mandate;

        $total_principal_disbursement_mantade = ['ESIF-Silesia'];
        if ($total_principal_disbursement && in_array($mandate, $total_principal_disbursement_mantade)) {
            $total_principal_disbursement = true;
        } else {
            $total_principal_disbursement = false;
        }

        if ($aga_nonCOVID19) {
            $this->loadModel('Damsv2.PortfolioRates');

            $theme = $this->PortfolioRates
                    ->find()
                    ->where(['portfolio_id' => $report->portfolio_id, 'theme' => 'COVID19'])
                    ->first();
            $aga_nonCOVID19 = !empty($theme) ? true : false;
        }
        $title = $report->report_type !== 'regular' ? 'Closure validation report' : 'Validation report';

        //Warning message when inclusion end date reached
        $warning_closure = false;
        if (!empty($report->portfolio->inclusion_end_date)) {
            $no_closure_products = [27]; // DDF
            if (($report->report_type == 'regular') && (!in_array($report->portfolio_id, $no_closure_products))) {
                //if no closure report for the portfolio
                $closure_report_portfolio = $this->Report->find('all', ['conditions' => ['portfolio_id' => $report->portfolio_id, 'report_type' => 'closure']])->first();

                if (empty($closure_report_portfolio)) {
                    if ($report->portfolio->status_portfolio !== 'CLOSED') {//portfolio not closed
                        if ($report->period_end_date >= $report->portfolio->inclusion_end_date) {
                            $warning_closure = true;
                        }
                    }
                }
            }
        }
        $this->set('warning_closure', $warning_closure);
        $this->set('warning_closure_date', $report->portfolio->inclusion_end_date);
        $this->set('apv_breakdown_path', $apv_breakdown_path);

        $this->set(compact('result', 'apvExceeded', 'portfolio_apv', 'msgWarning', 'title', 'warning_agreed_portfolio_volume', 'apvDecrease', 'mgv', 'agreed_ga', 'total_principal_disbursement', 'aga_nonCOVID19', 'covid_19_enhanced_rate_transactions'));
//        if (!empty($sasResult)) {
//            if (!file_exists($report_path . 'eif_inclusion_validation_report_' . $report_id . '.pdf')) {
//                $pdf = new WkHtmlToPdf();
//                $view = new View($this);
//                $view->layout = false;
//                $raw = $view->render('Reports/inclusion_validation_report_pdf');
//                $pdf->addPage($raw);
//                $pdf->setOptions(array(
//                    'zoom'             => 0.75,
//                    'disable-smart-shrinking',
//                    'page-size'        => 'A4',
//                    'margin-left'      => '1cm',
//                    'margin-right'     => '1cm',
//                    'margin-bottom'    => '1.5cm',
//                    'margin-top'       => '1cm',
//                    'dpi'              => 300,
//                    'user-style-sheet' => WWW . '/php/app/View/Themed/Cakestrap/webroot/css/bootstrap.css',
//                ));
//                $saved = $pdf->saveAs($path . ".pdf");
//                if (!$saved) {
//                    error_log("coud not save validation report : " . $pdf->getError());
//                }
//            }
//        }
    }

    /*
     * 	form FI responsivness
     * 	this form appears is placed afetr Validation&Reconciliation and before saving the inclusion in the database
     *
     */

    public function fiResponsivness($report_id)
    {
        if (!$this->Report->exists($report_id)) {
            throw new NotFoundException(__('Invalid Report'));
        }

        $report = $this->Report->get($report_id, [
            'contain' => ['Portfolio'],
        ]);

        $apvExceeded = false;

        if ($this->request->is('post')) {
            $waiver_path = '/var/www/html/data/damsv2/waiver_reasons/';
            try {
                $this->Spreadsheet->waiverReasonAddColumn($report_id);
                clearstatcache(true);
                if (file_exists($waiver_path . 'draft/sme_exemption_' . $report_id . '.xlsx')) {
                    rename($waiver_path . 'draft/sme_exemption_' . $report_id . '.xlsx', $waiver_path . 'validated/sme_exemption_' . $report_id . '.xlsx');
                }
                if (file_exists($waiver_path . 'draft/transactions_exemption_' . $report_id . '.xlsx')) {
                    rename($waiver_path . 'draft/transactions_exemption_' . $report_id . '.xlsx', $waiver_path . 'validated/transactions_exemption_' . $report_id . '.xlsx');
                }
                if (file_exists($waiver_path . 'draft/subtransactions_exemption_' . $report_id . '.xlsx')) {
                    rename($waiver_path . 'draft/subtransactions_exemption_' . $report_id . '.xlsx', $waiver_path . 'validated/subtransactions_exemption_' . $report_id . '.xlsx');
                }
            } catch (Exception $e) {
                error_log('error moving waiver files : ' . $e->getMessage());
            }

            $report->comments = $this->request->getData('Report.comments');
            //$report->status_id = 23;//Draft Included - DAMS-1145 done in SAS
            $report->validator1 = $this->userIdentity()->get('id'); // DAMS-1145
            $this->Report->save($report);
            $log_info = [
                'report_id'    => $report_id,
                'portfolio_id' => $report->portfolio_id,
                'comments'     => $report->comments,
            ];
            $this->logDams('report commented: ' . json_encode($log_info), 'dams', 'Comment report');
            $this->logDams('report ' . $report_id . ' validated by validator 1: ' . $report->validator1, 'dams', 'Validate report');
            $log_info = [
                'report_id' => $report_id,
                'comment'   => $report->comments,
            ];
            $this->logDams('First validation: ' . json_encode($log_info), 'dams', 'Validate report');
            $this->loadModel('Damsv2.ErrorsLog');
            $this->ErrorsLog->updateError($report, $this->request->getData());
            $url = 'validation/' . $report_id . '/valid'; // DAMS-1145 : inclusion_dashboard
            $this->redirect(['action' => $url]);
        } else {
            $content = Cache::read('inclusion_validation_report_' . $report_id, 'damsv2');
            if ($content) {

                $dom = HtmlDomParser::str_get_html($content);

                $warning = $dom->find('.warning');
                foreach ($warning as $m) {
                    $val = trim($m->innertext);
                    if ($val == 'w5') {
                        $apvExceeded = true;
                    }
                }
            }
        }

        $this->set(compact('report', 'apvExceeded'));
    }

    /*
     * 	form Exceed MPV
     * 	this form appears is placed after Validation&Reconciliation and before saving the inclusion in the database
     *   only the case where the portfolio is capped and APV > MPV
     *
     */

    public function exceededMpv($report_id)
    {
        if (!$this->Report->exists($report_id)) {
            throw new NotFoundException(__('Invalid Report'));
        }

        $report = $this->Report->get($report_id, [
            'contain' => ['Portfolio']
        ]);
        $covid_19_enhanced_rate_transactions = null;
        $content = Cache::read('inclusion_validation_report_' . $report_id, 'damsv2');
        if ($content) {
            $dom = HtmlDomParser::str_get_html($content);

            $warning = $dom->find('.warning');
            foreach ($warning as $m) {
                $val = trim($m->innertext);
                if ($val == 'w10_warning') {
                    $covid_19_enhanced_rate_transactions = true;
                }
            }
        }
        $this->set('covid_19_enhanced_rate_transactions', $covid_19_enhanced_rate_transactions);
        $apvExceeded = false;

        if ($this->request->is('post')) {

            $report->agreed_pv_comments = htmlentities($this->request->getData('Report.comments'));

            if (!empty($this->request->getData('Report.comments_covid_19'))) {
                $report->agreed_pv_comments = "APV comment:" . $report->agreed_pv_comments . " ,COVID19 confirmation:" . htmlentities($this->request->getData('Report.comments_covid_19'));
            }

            $this->Report->save($report);
            $log_info = [
                'report_id'    => $report_id,
                'portfolio_id' => $report->portfolio_id,
                'comments'     => $report->agreed_pv_comments,
            ];
            $this->logDams('report commented: ' . json_encode($log_info), 'dams', 'Comment report');
            $url = 'waiver_reason/' . $report_id;
            $this->redirect(['controller' => 'validation', 'action' => $url]);
        } else {
            $content = Cache::read('inclusion_validation_report_' . $report_id, 'damsv2');
            if ($content) {

                $dom = HtmlDomParser::str_get_html($content);

                $warning = $dom->find('.warning');
                foreach ($warning as $m) {
                    $val = trim($m->innertext);
                    if ($val == 'w5') {
                        $apvExceeded = true;
                    }
                }
            }
        }
        $this->set(compact('report', 'apvExceeded'));
    }

    public function exceededTotalPrincipalDisbursement($report_id)
    {
        if (!$this->Report->exists($report_id)) {
            throw new NotFoundException(__('Invalid Report'));
        }
        $apvExceeded = false;
        if ($this->request->is('post')) {

            $report = $this->Report->get($report_id, [
                'contain' => ['Portfolio']
            ]);

            $report->total_disbursement_comments = htmlentities($this->request->getData('Report.comments'));
            $this->Report->save($report);

            $log_info = [
                'report_id'    => $report_id,
                'portfolio_id' => $report->portfolio_id,
                'comments'     => $report->total_disbursement_comments,
            ];
            $this->logDams('report commented: ' . json_encode($log_info), 'dams', 'Comment report');

            $url = 'waiver_reason/' . $report_id;
            $this->redirect(['controller' => 'validation', 'action' => $url]);
        } else {
            $content = Cache::read('inclusion_validation_report_' . $report_id, 'damsv2');
            if ($content) {

                $dom = HtmlDomParser::str_get_html($content);

                $warning = $dom->find('.warning');
                foreach ($warning as $m) {
                    $val = trim($m->innertext);
                    if ($val == 'w5') {
                        //$apvExceeded = true;
                    }
                }
            }
        }
        $this->set(compact('report_id', 'apvExceeded'));
    }

    public function generatePeriod()
    {
        $products = $this->Report->Portfolio->Product->getProducts();

        $portfolios = $this->Report->Portfolio->find('list', [
                    'fields'     => ['portfolio_id', 'portfolio_name'],
                    'keyField'   => 'portfolio_id',
                    'valueField' => 'portfolio_name',
                ])->toArray();

        $this->set(compact('products', 'portfolios'));

        if ($this->request->is('post')) {
            $connection = ConnectionManager::get('default');
            $list_errors = [];
            $range_error = false;

            $product_id = !empty($this->request->getData('Product.product_id')) ? intval($this->request->getData('Product.product_id')) : null;
            $portfolio_id = !empty($this->request->getData('Portfolio.portfolio_id')) ? $this->request->getData('Portfolio.portfolio_id') : null;
            $period = !empty($this->request->getData('Report.period')) ? filter_var($this->request->getData('Report.period'), FILTER_SANITIZE_STRING) : null;
            $year = !empty($this->request->getData('Report.year')) ? $this->request->getData('Report.year') : null;
            $report_type = !empty($this->request->getData('Report.report_type')) ? filter_var($this->request->getData('Report.report_type'), FILTER_SANITIZE_STRING) : null;

            $conditions = [
                'Portfolio.status_portfolio <>' => 'EARLY TERMINATED',
                'Portfolio.product_id'          => $product_id
            ];
            $is_umbrella = false;
            $portfolio_umbrella = null;

            if (!empty($portfolio_id)) {
                if (strpos($portfolio_id, 'u_') !== false) {//it is an umbrella
                    $is_umbrella = true;
                    $umbrella_id = str_replace('u_', '', $portfolio_id);

                    $umbrella_portfolios = $connection->execute("SELECT portfolio_id FROM umbrella_portfolio_mapping um WHERE umbrella_portfolio_id=" . intval($umbrella_id))->fetchAll('assoc');
                    $get_portfolio_umbrella = $connection->execute("SELECT p.portfolio_id FROM umbrella_portfolio u, portfolio p WHERE u.iqid=p.iqid AND u.umbrella_portfolio_id=" . intval($umbrella_id))->fetchAll('assoc');
                    $portfolio_umbrella = $get_portfolio_umbrella[0]['portfolio_id'];

                    $sub_portfolios = [$portfolio_umbrella]; //fake portfolio of the umbrella
                    foreach ($umbrella_portfolios as $umbrella_portfolio) {
                        $sub_portfolios[] = $umbrella_portfolio['portfolio_id']; //sub portfolios of the umbrella
                    }
                    $conditions['Portfolio.portfolio_id'] = $sub_portfolios;
                } else {
                    $portfolio_id = $this->request->getData('Portfolio.portfolio_id');
                    if (!empty($portfolio_id)) {
                        $conditions['Portfolio.portfolio_id'] = $portfolio_id;
                    }
                }
            }

            $this->loadModel('Damsv2.Portfolio');
            // do not allow generation of sub portfolio as long as the previous umbrella report is not final
            if ($is_umbrella) {
                //previous report umbrella same period
                $reports_past = $connection->execute("SELECT * FROM report r, status s WHERE r.portfolio_id=" . intval($portfolio_umbrella) . " AND r.period_quarter='" . $period . "' AND r.period_year=" . intval($year) . " AND r.status_id=s.status_id AND s.stage != 'FINAL'")->fetchAll('assoc');
                if (!empty($reports_past)) {
                    $this->Flash->error("Latest Umbrella report has not been finalized or exists for the future period");
                    return $this->redirect($this->referer());
                }

                //previous report umbrella previous period
                $previous_period = null;
                switch ($period) {
                    case 'Q1': {
                            $previous_period = ["'Q1'"];
                            break;
                        }
                    case 'Q2': {
                            $previous_period = ["'Q1'", "'Q2'"];
                            break;
                        }
                    case 'Q3': {
                            $previous_period = ["'Q1'", "'Q2'", "'Q3'"];
                            break;
                        }
                    case 'Q4': {
                            $previous_period = ["'Q1'", "'Q2'", "'Q3'", "'Q4'"];
                            break;
                        }
                }
                //previous reports umbrella previous period same year
                $previous_period = implode(',', $previous_period);
                $reports_past = $connection->execute("SELECT * FROM report r, status s WHERE r.portfolio_id=" . $portfolio_umbrella . " AND r.period_quarter IN(" . $previous_period . ") AND r.period_year=" . intval($year) . " AND r.status_id=s.status_id AND s.stage != 'FINAL'")->fetchAll('assoc');
                if (!empty($reports_past)) {
                    $this->Flash->error("Latest Umbrella report has not been finalized or exists for the future period");
                    return $this->redirect($this->referer());
                }

                //previous reports umbrella previous years
                $reports_past = $connection->execute("SELECT * FROM report r, status s WHERE r.portfolio_id=" . $portfolio_umbrella . " AND r.period_year < " . intval($year) . " AND r.status_id=s.status_id AND s.stage != 'FINAL'")->fetchAll('assoc');
                if (!empty($reports_past)) {
                    $this->Flash->error("Latest Umbrella report has not been finalized or exists for the future period");
                    return $this->redirect($this->referer());
                }
            } else {
                //not umbrella
                // if the portfolio is CLOSED and there is no closure report then you cannot generate any closure report
                $conditions_closure = ['conditions' => ['Report.report_type' => 'closure']];
                if (!empty($portfolio_id)) {
                    $conditions_closure['conditions']['Report.portfolio_id'] = $portfolio_id;
                    $closure_reports = $this->Report->find('all', $conditions_closure);
                    $portfolio = $this->Portfolio->find('all', ['conditions' => ['Portfolio.portfolio_id' => $portfolio_id]])->first();
                    if (!empty($portfolio) && $portfolio->status_portfolio == 'CLOSED' && empty($closure_reports) && $report_type == 'closure') {
                        $this->Flash->error("The closure report has not been generated: the portfolio is already closed.");
                        return $this->redirect($this->referer());
                    }
                }
            }

            $portfolios = $this->Portfolio->find('all', ['conditions' => $conditions])->all();


            if ($is_umbrella) {//make sure umbrella portfolio is first
                $umbrella_portfolio = $connection->execute("SELECT * FROM portfolio p, umbrella_portfolio u where u.iqid = p.iqid AND u.umbrella_portfolio_id = " . intval($umbrella_id))->fetchAll('assoc');
                $umbrella_portfolio_ids = [];
                foreach ($umbrella_portfolio as $up) {
                    $umbrella_portfolio_ids[] = $up['portfolio_id'];
                }
                $found = false;
                $i = 0;
                $u_portfolios = $portfolios->toArray();
                $i_max = count($u_portfolios);
                while ((!$found) && ($i < $i_max)) {
                    if ($u_portfolios[$i]['portfolio_id'] == $portfolio_umbrella) {
                        $found = true;
                    } else {
                        $i++;
                    }
                }
                if ($found) {
                    $tmp = $u_portfolios[$i];
                    unset($u_portfolios[$i]);
                    array_push($u_portfolios, $tmp);
                }
            }

            // case where status of portfolios is EARLY TERMINATED
             if (count($portfolios) < 1) {
                $this->Flash->error("The status of selected portfolios is EARLY TERMINATED, No report can be generated");
                return $this->redirect($this->referer());
            }
            $period_start = null;
            $period_end = null;
            //determine start and end date 
            switch ($period) {
                case 'Q1':
                    $period_start = $year . "-01-01";
                    $period_end = $year . "-03-31";
                    break;
                case 'Q2':
                    $period_start = $year . "-04-01";
                    $period_end = $year . "-06-30";
                    break;
                case 'Q3':
                    $period_start = $year . "-07-01";
                    $period_end = $year . "-09-30";
                    break;
                case 'Q4':
                    $period_start = $year . "-10-01";
                    $period_end = $year . "-12-31";
                    break;
                case 'S1':
                    $period_start = $year . "-01-01";
                    $period_end = $year . "-06-30";
                    break;
                case 'S2':
                    $period_start = $year . "-07-01";
                    $period_end = $year . "-12-31";
                    break;
                case 'S1_spe':
                    $period_start = ($year - 1) . "-10-01";
                    $period_end = $year . "-03-31";
                    $period = 'S1';
                    break;
                case 'S2_spe':
                    $period_start = $year . "-04-01";
                    $period_end = $year . "-09-30";
                    $period = 'S2';
                    break;
                default:
                    $periods = [];
                    break;
            }

            $umbrella_sub_portfolio_ids = [];
            if ($is_umbrella) {
                $sub_portfolio_umbrella_ids = $connection->execute("SELECT portfolio_id FROM umbrella_portfolio_mapping WHERE umbrella_portfolio_id=" . intval($umbrella_id))->fetchAll('assoc');
                foreach ($sub_portfolio_umbrella_ids as $sub) {
                    $umbrella_sub_portfolio_ids[] = $sub['portfolio_id'];
                }
            }

            if ($is_umbrella) {//common to closure and regular report
                //no after reports (you cannot go back)
                $conditions_sub = [
                    'Report.portfolio_id'        => $umbrella_sub_portfolio_ids, //all portfolios of umbrella
                    'Template.template_type_id'  => 1,
                    'Report.period_start_date >' => $period_start,
                    'Report.visible'             => 1,
                ];
                $all_subportfolio_reports_futur = $this->Report->find('all', [//umbrella_closure err 1
                    'contain'    => ['Template'],
                    'conditions' => $conditions_sub,
                    'order'      => ['Report.period_year' => 'DESC', 'Report.period_quarter' => 'DESC']
                ]);
                if (!empty($all_subportfolio_reports_futur)) {
                    $error_msg = 'Latest umbrella report has not been finalized or exists for the future period.';
                    /* foreach ($all_subportfolio_reports_futur as $reports_existing) {
                      $error_msg .= '<li>'.$reports_existing['Report']['report_name'].'</li>';
                      } */
                    $this->Flash->error($error_msg);
                    return $this->redirect($this->referer());
                }

                // all sub portfolio must have a template assigned
                $this->loadModel('Damsv2.Template');
                $templates = $this->Template->find("list", ['conditions' => ['template_type_id' => 1], 'fields' => ['template_id']]); //inclusion templates
                $template_list = implode(',', $templates);
                $portfolio_list = implode(',', $umbrella_sub_portfolio_ids);
                $req = "SELECT p.portfolio_id, p.portfolio_name FROM portfolio p WHERE p.portfolio_id IN (" . $portfolio_list . ") AND p.portfolio_id NOT IN (SELECT tp.portfolio_id FROM template_portfolio tp WHERE tp.portfolio_id IN (" . $portfolio_list . ") AND tp.template_id IN (" . $template_list . ")) ";
                $all_subportfolio_no_template_assigned = $connection->execute($req)->fetchAll('assoc');

                if (!empty($all_subportfolio_no_template_assigned)) {
                    $error_msg = 'No Inclusion template found for the following portfolio(s):';
                    foreach ($all_subportfolio_no_template_assigned as $subportfolio_no_template_assigned) {
                        $error_msg .= '---' . $subportfolio_no_template_assigned['portfolio_name'] . '---';
                    }
                    $this->Flash->error($error_msg, ['escape' => false]);
                    return $this->redirect($this->referer());
                }
            }

            if ($is_umbrella && ($report_type != 'closure')) {

                $conditions_sub = [
                    'Report.portfolio_id'       => $umbrella_sub_portfolio_ids, //all portfolios of umbrella
                    'Template.template_type_id' => 1,
                    'Status.stage <>'           => 'FINAL', //not final
                    'Report.period_end_date <=' => $period_start,
                    'Report.visible'            => 1,
                ];
                $all_subportfolio_reports_previous = $this->Report->find('all', [//umbrella_closure err 1
                    'contain'    => ['Template', 'Status'],
                    'conditions' => $conditions_sub,
                    'order'      => ['Report.period_year' => 'DESC', 'Report.period_quarter' => 'DESC']
                ]);
                if (!empty($all_subportfolio_reports_previous)) {
                    error_log("all_subportfolio_reports_previous ");
                    $error_msg = "The previous report has not been finalized or is missing, the report already exists for the next period or the availability date is not in the range :"; //reg 1
                    foreach ($all_subportfolio_reports_previous as $rep) {
                        $error_msg .= "---" . $rep->portfolio_name . "-" . $rep->period_year . $rep->period_quarter . "---";
                    }
                    $this->Flash->error($error_msg, ['escape' => false]);
                    return $this->redirect($this->referer());
                }

                // closure report on same period for subportfolio
                $all_subportfolio_closure_reports_sameperiod = $this->Report->find('all', [//umbrella_closure err 3
                    'contain'    => ['Template', 'Status'],
                    'conditions' => [
                        'Report.portfolio_id'       => $umbrella_sub_portfolio_ids, //all portfolios of umbrella
                        'Template.template_type_id' => 1,
                        'Report.period_quarter'     => $period,
                        'Report.period_year'        => $year,
                        'Report.report_type'        => 'closure',
                        'Status.stage <>'           => 'FINAL', //not final
                        'Report.visible'            => 1,
                    ],
                    'order'      => ['Report.period_year' => 'DESC', 'Report.period_quarter' => 'DESC']
                ]);
                if (!empty($all_subportfolio_closure_reports_sameperiod)) {
                    $error_msg = "The closure report has not been finalized :"; //reg 3
                    foreach ($all_subportfolio_closure_reports_sameperiod as $rep) {
                        $error_msg .= "---" . $rep->portfolio_name . "-" . $rep->period_year . $rep->period_quarter . "---";
                    }
                    $this->Flash->error($error_msg, ['escape' => false]);
                    return $this->redirect($this->referer());
                }
            } elseif ($is_umbrella && ($report_type == 'closure')) {
                // exist closure report for previous period in at least 1 sub portfolio
                $all_subportfolio_closure_reports_previous = $this->Report->find('all', [//umbrella_closure 4
                    'contain'    => 'Template',
                    'conditions' => [
                        'Report.portfolio_id'       => $umbrella_sub_portfolio_ids, //all portfolios of umbrella
                        'Template.template_type_id' => 1,
                        'Report.period_end_date <=' => $period_start,
                        'Report.report_type'        => 'closure',
                        'Report.visible'            => 1,
                    ],
                    'order'      => ['Report.period_year' => 'DESC', 'Report.period_quarter' => 'DESC']
                ]);
                if (!empty($all_subportfolio_closure_reports_previous)) {
                    $error_msg = "The closure report has already been generated for a previous period :<ul>"; //pc 2
                    foreach ($all_subportfolio_closure_reports_previous as $rep) {
                        $error_msg .= "---" . $rep->portfolio_name . "-" . $rep->period_year . $rep->period_quarter . "---";
                    }
                    $this->Flash->error($error_msg, ['escape' => false]);
                    return $this->redirect($this->referer());
                }

                $all_subportfolio_regular_reports_prevperiod = $this->Report->find('all', [//umbrella_closure 5
                    'contain'    => ['Template', 'Status'],
                    'conditions' => [
                        'Report.portfolio_id'       => $umbrella_sub_portfolio_ids, //all portfolios of umbrella
                        'Template.template_type_id' => 1,
                        'Status.stage <>'           => 'FINAL', //not final
                        'Report.period_end_date <=' => $period_start,
                        'Report.report_type'        => 'regular',
                        'Report.visible'            => 1,
                    ],
                    'order'      => ['Report.period_year' => 'DESC', 'Report.period_quarter' => 'DESC']
                ]);
                if (!empty($all_subportfolio_regular_reports_prevperiod)) {
                    $error_msg = "The regular report for the previous period has not been finalized or has already been generated for this period :<ul>"; //pc 1
                    foreach ($all_subportfolio_regular_reports_prevperiod as $rep) {
                        $error_msg .= "---" . $rep->portfolio_name . "-" . $rep->period_year . $rep->period_quarter . "---";
                    }
                    $this->Flash->error($error_msg, ['escape' => false]);
                    return $this->redirect($this->referer());
                }


                /* $all_subportfolio_regular_reports_sameperiod = $this->Report->find('all', array(//umbrella_closure 6
                  'recursive'=>1,
                  'conditions'=>array(
                  'Report.portfolio_id' => $umbrella_sub_portfolio_ids,//all portfolios of umbrella
                  'Template.template_type_id' => 1,
                  //'NOT' => array('Status.stage' => 'Final'),//not final
                  'Report.period_quarter' => $period,
                  'Report.period_year' => $year,
                  'Report.report_type' => 'regular',
                  'Report.visible' => 1,
                  ),
                  'order' => array('period_year'=>'DESC', 'period_quarter'=>'DESC')
                  ));

                  $all_subportfolio_closure_reports_sameperiod = $this->Report->find('all', array(//umbrella_closure 6
                  'recursive'=>1,
                  'conditions'=>array(
                  'Report.portfolio_id' => $umbrella_sub_portfolio_ids,//all portfolios of umbrella
                  'Template.template_type_id' => 1,
                  'NOT' => array('Status.stage' => 'Final'),//not final
                  'Report.period_quarter' => $period,
                  'Report.period_year' => $year,
                  'Report.report_type' => 'closure',
                  'Report.visible' => 1,
                  ),
                  'order' => array('period_year'=>'DESC', 'period_quarter'=>'DESC')
                  ));
                  if (!empty($all_subportfolio_regular_reports_sameperiod) || !empty($all_subportfolio_closure_reports_sameperiod))
                  {
                  //pc 1
                  error_log(__LINE__);
                  $error_msg = "The closure report for this period has not been finalized :<ul>";//pc 1 // here here
                  foreach($all_subportfolio_regular_reports_sameperiod as $rep)
                  {
                  error_log("all_subportfolio_regular_reports_sameperiod");
                  $error_msg .= "<li>".$rep['Portfolio']['portfolio_name']."-".$rep['Report']['period_year'].$rep['Report']['period_quarter']."</li>";
                  }
                  foreach($all_subportfolio_closure_reports_sameperiod as $rep)
                  {
                  error_log("all_subportfolio_closure_reports_sameperiod");
                  $error_msg .= "<li>".$rep['Portfolio']['portfolio_name']."-".$rep['Report']['period_year'].$rep['Report']['period_quarter']."</li>";
                  }
                  $error_msg .= "</ul>";
                  $this->Flash->error($error_msg,"flash/error", [],'error');
                  $this->redirect($this->referer());
                  } */
                /*
                  //closure report : all previous regular report Included and no regular report for selected period
                  $existing_regular_reports_before = $this->Report->find('all', array(
                  'recursive'=>1,
                  'conditions'=>array(
                  'period_start_date <' => $period_start,
                  'Report.portfolio_id' => $umbrella_sub_portfolio_ids,
                  'Template.template_type_id' => 1,
                  'Report.report_type' => 'regular',
                  array('NOT' => array('Status.stage' => array('FINAL'))),
                  'Report.visible' => 1,
                  )
                  ));

                  $existing_regular_reports__same_period = $this->Report->find('all', array(
                  'recursive'=>1,
                  'conditions'=>array(
                  'Report.portfolio_id' => $umbrella_sub_portfolio_ids,
                  'Template.template_type_id' => 1,
                  'Report.report_type' => 'regular',
                  'Report.period_quarter' => $period,
                  'Report.period_year' => $year,
                  'Report.visible' => 1,
                  )
                  ));

                  if (!empty($existing_regular_reports_before) ||!empty($existing_regular_reports__same_period))
                  {
                  $displayError = "The regular report for the previous period has not been finalized or has already been generated for this period.";
                  $displayError = $displayError."<ul>";
                  foreach($existing_regular_reports_before as $rep)
                  {
                  $displayError .= "<li>".$rep['Portfolio']['portfolio_name']."-".$rep['Report']['period_year'].$rep['Report']['period_quarter']."</li>";
                  }
                  foreach($existing_regular_reports__same_period as $rep)
                  {
                  $displayError .= "<li>".$rep['Portfolio']['portfolio_name']."-".$rep['Report']['period_year'].$rep['Report']['period_quarter']."</li>";
                  }
                  $displayError = $displayError."</ul>";
                  $this->Flash->error($displayError, [],'error');
                  $this->redirect($this->referer());
                  exit();
                  } */
            }

            if ((!$is_umbrella) && ($report_type == "closure")) {
                //debug($portfolio_id);
                $condition_closure = array(
                    'Template.template_type_id' => 1,
                    'Report.report_type'        => 'regular',
                    'Report.period_quarter'     => $period,
                    'Report.period_year'        => $year,
                    'Report.visible'            => 1,
                    'Portfolio.product_id'      => $product_id,
                );
                if (!empty($portfolio_id)) {
                    $condition_closure['Report.portfolio_id'] = $portfolio_id;
                }
                $existing_regular_reports__same_period = $this->Report->find('all', [
                            'contain'    => ['Template', 'Portfolio'],
                            'conditions' => $condition_closure
                        ])->toArray();

                if (!empty($existing_regular_reports__same_period)) {
                    $displayError = "The regular report for the previous period has not been finalized or has already been generated for this period.<br>";

                    foreach ($existing_regular_reports__same_period as $rep) {
                        $displayError .= "---" . $rep->portfolio->portfolio_name . "-" . $rep->period_year . $rep->period_quarter . "---<br>";
                    }
                    $this->Flash->error($displayError, ['escape' => false]);
                    return $this->redirect($this->referer());
                }
            }

            // loop on each portfolio
            foreach ($portfolios as $portfolio) {
                $stop_this_one = null;

                //check if portfolio has a valid template
                $template_type_id = 0;

                $template_portfolios = $connection
                        ->execute('SELECT t.template_type_id, t.template_id FROM
                            template_portfolio tp
                                INNER JOIN
                            template t ON t.template_id = tp.template_id
                                INNER JOIN
                            template_type tt ON tt.type_id = t.template_type_id WHERE tp.portfolio_id = :id', ['id' => $portfolio->portfolio_id])
                        ->fetchAll('assoc');


                foreach ($template_portfolios as $template) {
                    if ($template['template_type_id'] == 1) {
                        $template_type_id = $template['template_id'];
                        break;
                    }
                }

                //continue only if a valid inclusion template was found
                if (empty($template_type_id)) {
                    // jira dams 243
                    $errormsg = "No Inclusion template found for the following portfolio:";
                    $errormsg .= '---' . $portfolio->portfolio_name . '---';
                    $this->Flash->error($errormsg);
                    return $this->redirect($this->referer());
                } else {
                    //Check the previous reports for this portfolio
                    $existing_regular_reports = $this->Report->find('all', [
                        'contain'    => ['Template', 'Status'],
                        'conditions' => [
                            'period_year <='            => $year,
                            'Report.portfolio_id'       => $portfolio->portfolio_id,
                            'Template.template_type_id' => 1,
                            'Report.report_type'        => 'regular',
                            'Status.stage <>'           => 'FINAL', //Split for umbrella, Included for closure report,
                            'Report.visible'            => 1,
                        ]
                    ]);

                    //Dismiss period after the current one (and the current one if in loop)
                    if (!empty($existing_regular_reports) && $existing_regular_reports->count() > 0) {

                        foreach ($existing_regular_reports as $key => $report) {
                            //all previous years are blocking, except the one with status 5
                            //current year
                            if ($report->period_year == $year) {
                                $dismisslist = [];
                                switch (strtolower($period)) {
                                    case 'q1':
                                        $dismisslist = ['q2', 'q3', 'q4'];
                                        if (empty($portfolio_id))
                                            $dismisslist = array_merge($dismisslist, ['q1']);
                                        break;
                                    case 'q2':
                                        $dismisslist = ['q3', 'q4'];
                                        if (empty($portfolio_id))
                                            $dismisslist = array_merge($dismisslist, ['q2']);
                                        break;
                                    case 'q3':
                                        $dismisslist = ['q4'];
                                        if (empty($portfolio_id))
                                            $dismisslist = array_merge($dismisslist, ['q3']);
                                        break;
                                    case 'q4':
                                        $dismisslist = [];
                                        if (empty($portfolio_id))
                                            $dismisslist = array_merge($dismisslist, ['q4']);
                                        break;
                                    case 's1':
                                        $dismisslist = ['s2'];
                                        if (empty($portfolio_id))
                                            $dismisslist = array_merge($dismisslist, ['s1']);
                                        break;
                                    case 's2':
                                        $dismisslist = [];
                                        if (empty($portfolio_id))
                                            $dismisslist = array_merge($dismisslist, ['s2']);
                                        break;
                                }
                                if (in_array($report->period_year, $dismisslist)) {
                                    unset($existing_regular_reports[$key]);
                                }
                            }
                        }
                    }

                    //at this step, the remaining periods should be inferior to current Y, or same Y but <= Q
                    //they all are blocking, except if status = 5: then remove them from the blocking list

                    if (!empty($existing_regular_reports) && $existing_regular_reports->count() > 0) {
                        foreach ($existing_regular_reports as $key => $report) {
                            if (!empty($report->status_id) && ($report->status_id == 5 || $report->status_id == 7)) {
                                unset($existing_regular_reports[$key]);
                            }
                        }
                    }

                    //for umbrella portfolio, status split (22) or no inclusion (7) are removed from blocking list
                    if ($is_umbrella && !empty($existing_regular_reports) && $existing_regular_reports->count() > 0) {
                        foreach ($existing_regular_reports as $key => $report) {
                            if (in_array($report->portfolio_id, $umbrella_portfolio_ids)) {
                                if (!empty($report->status_id) && ($report->status_id == 22 || $report->status_id == 7)) {
                                    unset($existing_regular_reports[$key]);
                                }
                            }
                        }
                    }
                    $closure_reports = $this->Report->find('all', [
                        'contain'    => ['Template', 'Status', 'Portfolio'],
                        'conditions' => [
                            'Report.portfolio_id'       => $portfolio->portfolio_id,
                            'Template.template_type_id' => 1,
                            'Report.report_type'        => 'closure',
                            'Report.visible'            => 1,
                        ]
                    ]);

                    $error_msg = null;
                    $error_msg_pc3 = "";
                    $error_msg_pc4 = "";

                    if (!empty($closure_reports) && $closure_reports->count() > 0 && !$is_umbrella) {

                        foreach ($closure_reports as $key => $report) {
                            //if the closure report is before the selected year/quarter, nope
                            $date_closure_report = $report->period_start_date;
                            $date_new_report = new Date($period_start);
//                            debug($date_closure_report);
//                            debug($date_new_report);
//                            debug($report_type);
//                            debug($report->status->status);
//                            debug($report->portfolio->portfolio_name);
//                            debug($report->portfolio->status_portfolio);
//                            debug($date_new_report > $date_closure_report);
                            if (($report_type == 'closure') && ($date_new_report > $date_closure_report)) {
                                $period_ = $report->period_year . "" . $report->period_quarter;
                                $error_msg = "A closure report has already been generated for a previous period : " . $report->portfolio->portfolio_name . " : " . $period_;
                            } elseif (($report_type == 'closure') && ($report->status->status != "Included") && ($date_new_report == $date_closure_report)) {
                                $period_ = $report->period_year . "" . $report->period_quarter;
                                $error_msg_pc3 .= "<li>" . $report->portfolio->portfolio_name . " : " . $period_ . "</li>";
                            } elseif (($report_type == 'closure') && ($date_new_report == $date_closure_report) && ($report->status->status != "Included")) {
                                $period_ = $report->period_year . "" . $report->period_quarter;
                                $error_msg_pc3 .= "<li>" . $report->portfolio->portfolio_name . " : " . $period_ . "</li>";
                            } elseif (($report_type == 'regular') && (!(($report->status->status == "Included") && ($report->portfolio->status_portfolio == 'CLOSED')))) {
//                                debug('we are here');
                                $period_ = $report->period_year . "" . $report->period_quarter;
                                $error_msg_pc4 .= "<li>" . $report->portfolio->portfolio_name . " : " . $period_ . "</li>";
                            }
                        }
                    }

//                    debug($error_msg_pc3);
//                    debug($error_msg_pc4);
//                    dd('endloop');

                    if (!empty($closure_reports) && $closure_reports->count() > 0 && ($error_msg_pc4 != "")) {
                        $error_msg_pc4 = "The closure report has not been finalized:<ul>" . $error_msg_pc4 . "</ul>";
                        $this->Flash->error($error_msg_pc4, ['escape' => false]);
                        return $this->redirect($this->referer());
                    } elseif (!empty($closure_reports) && $closure_reports->count() > 0 && ($error_msg_pc3 != "")) {
                        $error_msg_pc3 = "The closure report for this period has not been finalized:<ul>" . $error_msg_pc3 . "</ul>";
                        error_log("The closure report for this period has not been finalized " . __LINE__);
                        $this->Flash->error($error_msg_pc3, ['escape' => false]);
                        return $this->redirect($this->referer());
                    } elseif (!empty($closure_reports) && $closure_reports->count() > 0 && ($error_msg)) {
                        $this->Flash->error($error_msg, ['escape' => false]);
                        return $this->redirect($this->referer());
                    } elseif (!empty($existing_regular_reports && $existing_regular_reports->count() > 0)) {
                        error_log("blocking reports : " . json_encode($existing_regular_reports));
                        $list = [];

                        if ((!$is_umbrella) && ($report_type == 'closure')) {
                            //single portfolio cases
                            if (!empty($existing_regular_reports && $existing_regular_reports->count() > 0)) {
                                $displayError = "The regular report for the previous period has not been finalized or has already been generated for this period:<ul>";
                                foreach ($existing_regular_reports as $report) {
                                    $displayError .= "<li>" . $report->portfolio->portfolio_name . " : " . $report->period_year . "" . $report->period_quarter . "</li>";
                                }
                                $displayError .= "</ul>";
                                $this->Flash->error($displayError, ['escape' => false]);
                                $existing_regular_reports = [];
                                return $this->redirect($this->referer());
                            }
                        }

                        foreach ($existing_regular_reports as $report) {
                            $list[] = $report->period_year . $report->period_quarter;
                        }

                        if (!empty($list)) {
                            $list = array_unique($list);
                            $list_errors[$portfolio->portfolio_name] = implode(', ', $list);
                            error_log("list_errors " . __LINE__);
                        }
                    } else {
                        //At this step, there is not any report for the same period, or previous ones, except with status 5 in a one-portfolio request: go ahead
                        // http://vmu-sas-01:8080/browse/DAMS-1727
                        /* if (!empty($portfolio->availability_start))
                          {
                          $availability_start_date = strtotime($portfolio->availability_start);
                          $date_in_range = false;
                          $timestamp_period_start = strtotime($period_start);
                          $timestamp_period_end = strtotime($period_end);
                          $portfolio_end_date = null;
                          if (!empty($portfolio->effective_termination_date))
                          {
                          $portfolio_end_date = strtotime($portfolio->effective_termination_date);
                          }
                          elseif (!empty($portfolio['Portfolio']['guarantee_termination']))
                          {
                          $portfolio_end_date = strtotime($portfolio['Portfolio']['guarantee_termination']);
                          }
                          $portfolio_end_timestamp = null;
                          switch ($this->request->getData('Report.period'))
                          {
                          case 'Q1':
                          case 'Q2':
                          case 'Q3':
                          case 'Q4':
                          $portfolio_end_timestamp = $this->get_end_period_q($portfolio_end_date);
                          $availability_start = $this->get_start_period_q($availability_start_date);
                          break;
                          case 'S1':
                          case 'S2':
                          $portfolio_end_timestamp = $this->get_end_period_s($portfolio_end_date);
                          $availability_start = $this->get_start_period_s($availability_start_date);
                          break;
                          case 'S1_spe':
                          case 'S2_spe':
                          $portfolio_end_timestamp = $this->get_end_period_s_spe($portfolio_end_date);
                          $availability_start = $this->get_start_period_s_spe($availability_start_date);
                          break;
                          }

                          if (($timestamp_period_start >= $availability_start) && ($timestamp_period_end <= $portfolio_end_timestamp))
                          {
                          $date_in_range = true;
                          }

                          error_log("date range  : availability_start ".date("Y-m-d",$availability_start));
                          error_log("date range  : portfolio_end_timestamp ".date("Y-m-d",$portfolio_end_timestamp));
                          error_log("date range  : timestamp_period_start ".date("Y-m-d",$timestamp_period_start));
                          error_log("date range  : timestamp_period_end ".date("Y-m-d",$timestamp_period_end));
                          error_log("portfolio : ".$portfolio->portfolio_id);
                          if (! $date_in_range)
                          {
                          $list_errors[$portfolio->portfolio_name] = $year.$period;
                          error_log("date range issue for ".$portfolio->portfolio_name.$year.$period);
                          $stop_this_one = true;
                          }
                          } */

                        // If portfolio.gs_deal_status is IN (Abandoned, Rejected, Terminated), do not generate the period.
                        $deal_status_off = ['Abandoned', 'Rejected', 'Terminated'];

                        if (!empty($portfolio->gs_deal_status) && (in_array($portfolio->gs_deal_status, $deal_status_off))) {
                            $stop_this_one = true;
                            error_log("report not generated : status = " . $portfolio->gs_deal_status);
                            $list_errors[$portfolio->portfolio_name] = $year . $period;
                        }

                        $date_in_range = true; // UAT
                        if (!empty($portfolio->availability_start)) {//check start date if available
                            $availability_start_date = intval($portfolio->availability_start->toUnixString());
                            $timestamp_period_start = strtotime($period_start); //$period_start = real date of starting period

                            switch ($this->request->getData('Report.period')) {
                                case 'Q1':
                                case 'Q2':
                                case 'Q3':
                                case 'Q4':
                                    $availability_start = $this->get_start_period_q($availability_start_date);
                                    break;
                                case 'S1':
                                case 'S2':
                                    $availability_start = $this->get_start_period_s($availability_start_date);
                                    break;
                                case 'S1_spe':
                                case 'S2_spe':
                                    $availability_start = $this->get_start_period_s_spe($availability_start_date);
                                    break;
                            }// $availability_start = start of q1/2....

                            if ($timestamp_period_start < $availability_start) {
                                $date_in_range = false;
                            }

                            if (!$date_in_range) {
                                $list_errors[$portfolio->portfolio_name] = $year . $period;

                                error_log("error new period : date range " . __LINE__);
                                $stop_this_one = true;
                            }
                        }

                        if (!empty($portfolio->effective_termination_date)) {//check end date if available
                            $timestamp_period_end = strtotime($period_end);
                            $portfolio_end_date = !empty($portfolio->effective_termination_date) ? intval($portfolio->effective_termination_date->toUnixString()) : null;

                            /* elseif (!empty($portfolio['Portfolio']['guarantee_termination']))
                              {
                              $portfolio_end_date = strtotime($portfolio['Portfolio']['guarantee_termination']);
                              } */
                            $portfolio_end_timestamp = null;
                            switch ($this->request->getData('Report.period')) {
                                case 'Q1':
                                case 'Q2':
                                case 'Q3':
                                case 'Q4':
                                    $portfolio_end_timestamp = $this->get_end_period_q($portfolio_end_date);
                                    break;
                                case 'S1':
                                case 'S2':
                                    $portfolio_end_timestamp = $this->get_end_period_s($portfolio_end_date);
                                    break;
                                case 'S1_spe':
                                case 'S2_spe':
                                    $portfolio_end_timestamp = $this->get_end_period_s_spe($portfolio_end_date);
                                    break;
                            }
                            if ($timestamp_period_end > $portfolio_end_timestamp) {
                                $date_in_range = false;
                            }

                            if (!$date_in_range) {
                                $list_errors[$portfolio->portfolio_name] = $year . $period;

                                error_log("error new period date range: " . __LINE__);
                                $stop_this_one = true;
                            }
                        }

                        //check if there is report AFTER the current period
                        $afterreports = $this->Report->find('all', [
                                    'contain'    => ['Template'],
                                    'conditions' => [
                                        'Report.period_year >='     => $year,
                                        'Report.portfolio_id'       => $portfolio->portfolio_id,
                                        'Template.template_type_id' => 1,
                                        'Report.visible'            => 1,
                                    ],
                                    'order'      => ['Report.period_year' => 'DESC', 'Report.period_quarter' => 'DESC']
                                ])->toArray();

                        //closure report + umbrella
                        // closure report on same period for subportfolio
                        if ($is_umbrella && $report_type == 'closure') {//err 7
                            $all_subportfolio_regular_reports_sameperiod = $this->Report->find('all', [
                                'contain'    => ['Template'],
                                'conditions' => [
                                    'Report.portfolio_id'       => $portfolio->portfolio_id, //all portfolios of umbrella
                                    'Template.template_type_id' => 1,
                                    'Report.period_quarter'     => $period,
                                    'Report.period_year'        => $year,
                                    'Report.report_type'        => 'regular',
                                    'Report.visible'            => 1,
                                ],
                                'order'      => ['Report.period_year' => 'DESC', 'Report.period_quarter' => 'DESC']
                            ]);

                            // do not block, generate others, list not generated
                            if (!empty($all_subportfolio_regular_reports_sameperiod)) {//pc 1
                                $msg_closure_umbrella_PC3 = "The closure report for this period has not been finalized :<ul>";
                                //err 6
                                error_log("The closure report for this period has not been finalized" . __LINE__);
                                $list = [];
                                foreach ($afterreports as $report) {
                                    $list[] = $report->period_year . $report->period_quarter;
                                }

                                if (!empty($list)) {
                                    $list_errors[$portfolio->portfolio_name] = implode(', ', $list);
                                    error_log("report not generated : L " . __LINE__);
                                }
                                $stop_this_one = true;
                            }
                        } elseif ($is_umbrella && $report_type == 'regular') {
                            $all_subportfolio_regular_reports_sameperiod = $this->Report->find('all', [
                                'contain'    => ['Template', 'Status'],
                                'conditions' => [
                                    'Report.portfolio_id'       => $portfolio->portfolio_id, //all portfolios of umbrella
                                    'Template.template_type_id' => 1,
                                    'Report.period_quarter'     => $period,
                                    'Report.period_year'        => $year,
                                    'Report.report_type'        => 'regular',
                                    'Status.stage <>'           => 'FINAL', //not final,
                                    'Report.visible'            => 1,
                                ],
                                'order'      => ['Report.period_year' => 'DESC', 'Report.period_quarter' => 'DESC']
                            ]);

                            // do not block, generate others, list not generated
                            if (!empty($all_subportfolio_regular_reports_sameperiod)) {//reg
                                //err 2
                                $list = [];
                                foreach ($afterreports as $report) {
                                    $list[] = $report->period_year . $report->period_quarter;
                                }

                                if (!empty($list)) {
                                    $list_errors[$portfolio->portfolio_name] = implode(', ', $list);

                                    error_log("error new period : " . __LINE__ . " : " . json_encode($list));
                                }
                                $stop_this_one = true;
                            }
                        }

                        $msg_not_generated_early_terminated = [];
                        if ($is_umbrella) {
                            //do not generate if the portfolio is early terminated
                            if ($portfolio->status_portfolio == 'EARLY TERMINATED') {
                                $stop_this_one = true;
                                $msg_not_generated_early_terminated[] = $portfolio->portfolio_name;
                            }
                        }

                        //check the reports after the current date
                        if (!empty($afterreports && count($afterreports) > 0)) {
                            foreach ($afterreports as $i => $afterreport) {
                                //if year is greater, keep it in the blocking list
                                //if same year, check periods
                                if ($afterreport->period_year == $year) {
                                    $dismisslist = [];
                                    switch (strtolower($period)) {
                                        //if request is q1 or s1, then q1, q2, q3, q4, s1 & s2 are blocking except if we request for only one portfolio
                                        case 'q1':
                                            if (!empty($portfolio_id))
                                                $dismisslist = ['q1'];
                                            break;

                                        //if request is q2, we can clear q1 reports from the blocking list
                                        case 'q2':
                                            $dismisslist = ['q1'];
                                            if (!empty($portfolio_id))
                                                $dismisslist = array_merge($dismisslist, ['q2']);
                                            break;

                                        //if request is q3, we can clear q1,q2,s1 reports from the blocking list
                                        case 'q3':
                                            $dismisslist = ['q1', 'q2', 's1'];
                                            if (!empty($portfolio_id))
                                                $dismisslist = array_merge($dismisslist, ['q3']);
                                            break;

                                        //if request is q4, we can clear q1,q2,q3,s1 reports from the blocking list
                                        case 'q4':
                                            $dismisslist = ['q1', 'q2', 'q3', 's1'];
                                            if (!empty($portfolio_id))
                                                $dismisslist = array_merge($dismisslist, ['q4']);
                                            break;
                                        //if request is s1 (=q3), we can clear q1,q2 reports from the blocking list
                                        case 's1':
                                            $dismisslist = ['q1', 'q2'];
                                            if (!empty($portfolio_id))
                                                $dismisslist = array_merge($dismisslist, ['s1']);
                                            break;
                                        //if request is s2 (=q3), we can clear q1,q2,s1 reports from the blocking list
                                        case 's2':
                                            $dismisslist = ['q1', 'q2', 's1'];
                                            if (!empty($portfolio_id))
                                                $dismisslist = array_merge($dismisslist, ['s2']);
                                            break;
                                    }

                                    if (in_array(strtolower($afterreport->period_quarter), $dismisslist)) {
                                        unset($afterreports[$i]);
                                    }
                                }
                            }
                        }

                        if (!empty($afterreports && count($afterreports) > 0)) {
                            error_log("after report : should not generate : " . json_encode($afterreports));
                            $list = [];
                            foreach ($afterreports as $report) {
                                $list[] = $report->period_year . $report->period_quarter;
                            }

                            if (!empty($list)) {
                                error_log("error new period : " . __LINE__ . " : " . json_encode($list));
                                $list_errors[$portfolio->portfolio_name] = implode(', ', $list);
                                $stop_this_one = true;
                            }
                        }

                        // DAMS-458
                        // don't allow gaps
                        $reports_nogap = $this->Report->find('all', [
                                    'contain'    => ['Template'],
                                    'conditions' => [
                                        'Report.portfolio_id'       => $portfolio->portfolio_id, //all portfolios of umbrella
                                        'Template.template_type_id' => 1,
                                        'Report.status_id != '      => 21,
                                        'Report.period_end_date < ' => $period_start,
                                        'Report.visible'            => 1,
                                    ],
                                    'order'      => ['Report.period_end_date' => 'DESC']
                                ])->first();

                        if (!empty($reports_nogap)) {

                            $end_prev_rep_tstmp = !empty($reports_nogap->period_end_date) ? intval($reports_nogap->period_end_date->toUnixString()) : null;

                            $period_start_tstmp = strtotime($period_start);

                            $gap = $period_start_tstmp - $end_prev_rep_tstmp;

                            if ($gap > 172800) {//2 days gap between the reports
                                $missing_quart = "";
                                switch ($reports_nogap->period_quarter) {
                                    case 'Q1':
                                        $missing_quart = 'Q2';
                                        break;
                                    case 'Q2':
                                        $missing_quart = 'Q3';
                                        break;
                                    case 'Q3':
                                        $missing_quart = 'Q4';
                                        break;
                                    case 'Q4':
                                        $reports_nogap->period_year++;
                                        $missing_quart = 'Q1';
                                        break;
                                    case 'S1':
                                        $missing_quart = 'S2';
                                        break;
                                    case 'S2':
                                        $reports_nogap->period_year++;
                                        $missing_quart = 'S1';
                                        break;
                                    case 'S1_spe':
                                        $missing_quart = 'S2_spe';
                                        break;
                                    case 'S2_spe':
                                        $reports_nogap->period_year++;
                                        $missing_quart = 'S1_spe';
                                        break;
                                }
                                if (empty($list_errors[$portfolio->portfolio_name])) {
                                    $list_errors[$portfolio->portfolio_name] = $reports_nogap->period_year . $missing_quart . "(missing)";
                                }
                                $stop_this_one = true;
                            }
                        }// DAMS-458 END
                        //DAMS-1155 4 eyes principles: A new report for the portfolio (either in the same or following period)
                        // should not be allowed to be generated if the previous report is still in DRAFT status.


                        if (empty($stop_this_one)) {
                            $reports_draft = $this->Report->find('all', [
                                        'contain'    => ['Template'],
                                        'conditions' => [
                                            'Report.portfolio_id'        => $portfolio->portfolio_id, //all portfolios of umbrella
                                            'Template.template_type_id'  => 1,
                                            'Report.status_id'           => 23,
                                            'Report.period_end_date <= ' => $period_start,
                                            'Report.visible'             => 1,
                                        ],
                                        'order'      => ['Report.period_end_date' => 'DESC']
                                    ])->first();

                            if (!empty($reports_draft)) {
                                $list_errors[$portfolio->portfolio_name] = $reports_draft->period_year . $reports_draft->period_quarter;
                                $stop_this_one = true;
                            }
                        }

                        if (empty($stop_this_one)) {

                            $report_name = $portfolio->portfolio_name . "_" . $year . $period;
                            $newreport = $this->Report->newEmptyEntity();

                            $newreport->report_name = $report_name . "_v1";
                            $newreport->period_quarter = $period;
                            $newreport->period_year = $year;
                            $newreport->period_start_date = $period_start;
                            $newreport->period_end_date = $period_end;
                            $newreport->portfolio_id = $portfolio->portfolio_id;
                            $newreport->version_id = 1;
                            $newreport->template_id = $template_type_id;
                            $newreport->status_id = 1;
                            $newreport->status_id_umbrella = 1;
                            $newreport->owner = $portfolio->owner;
                            $newreport->report_type = $report_type;

                            $period_generated = $this->Report->save($newreport);
                            if ($period_generated) {
                                $created_reports[] = '<li>' . $report_name . '</li>';
                                unset($period_generated->sheets); //remove item with '$' sign
                                $this->logDams('report created: ' . json_encode($period_generated), 'dams', 'Generate period');
                            }
                        }
                    }
                }
            }//end foreach portfolio
            //display the list of report in error
            $displayError = "";

            if (!empty($list_errors) | !empty($msg_closure_umbrella_PC3) | !empty($msg_not_generated_early_terminated)) {
                if (!empty($msg_not_generated_early_terminated)) {
                    $displayError = $displayError . "The status of selected portfolios is EARLY TERMINATED, No report can be generated :<ul>";

                    foreach ($msg_not_generated_early_terminated as $portfolio_name) {
                        $displayError = $displayError . '<li>' . $portfolio_name . '</li>';
                    }
                    $displayError = $displayError . "</ul><br />";
                }
                if (isset($msg_closure_umbrella_PC3)) {
                    $displayError = $displayError . $msg_closure_umbrella_PC3;
                } else {
                    if (!empty($msg_closure_umbrella_PC3)) {
                        error_log("The closure report for this period has not been finalized" . __LINE__);
                        $displayError = "The closure report for this period has not been finalized:" . $msg_closure_umbrella_PC3;
                    } elseif ($range_error) {
                        $displayError = $displayError . 'The previous report has not been finalized or is missing, the report already exists for the next period or the availability date is not in the range';
                        error_log("range_error");
                    } else {
                        error_log("general error");
                        $displayError = $displayError . 'The previous report has not been finalized or is missing, the report already exists for the next period or the availability date is not in the range :<ul>';
                    }
                }
                foreach ($list_errors as $name => $reports_existing) {
                    $displayError = $displayError . '<li>' . $name . ' :' . $reports_existing . '</li>';
                }
                if (!empty($list_errors)) {
                    $displayError = $displayError . "</ul>";
                }
                $this->Flash->error($displayError, ['escape' => false]);
            }

            //display the list of created report
            if (!empty($created_reports)) {
                //$this->Flash->success(sprintf('The following reports have been created : <ul>' . implode($created_reports, '') . '</ul>'));
                $this->Flash->success('The following reports have been created : <ul>' . implode($created_reports, '') . '</ul>', ['escape' => false]);
            }

            $this->redirect($this->referer());
        }
    }

    /**
     * Reception of a Paymend Demand / Loss Recovery
     * @param $id Report ID in case of update
     * @return void
     */
    public function pdlrImport($id = null)
    {
        $report = $this->Report->get($id, [
            'contain' => ['Portfolio', 'Template', 'VUser',],
        ]);

        if (empty($report)) {
            $this->Flash->error('Invalid Report!');
            $this->redirect($this->referer());
            exit;
        }

        // group retrieving  // http://vmu-sas-01:8080/browse/PLATFORM-259
//        $groups = CakeSession::read('UserAuth.UserGroups');
//        if (!is_array($groups))
//            $groups = array($groups);
//        if (!empty($groups))
//            foreach ($groups as $group) {
//                $groupsnames[] = $group['alias_name'];
//            }
//        if (in_array('ReadOnlyDams', $groupsnames)) {
//            $this->Flash->error("You are currently in a read only profile, this functionality is disabled");
//            $this->redirect($this->referer());
//        }
        //$latest_year = $this->Report->getLastestYearFromPortofolioId($report->portfolio_id);
        //$latest_year = $this->Report->find('LastestYearFromPortofolioId');
        //dd($latest_year);
        $this->set(compact('report'));

        if ($this->request->is('post')) {
            error_reporting(0);
            $this->loadModel('Damsv2.Rules');
            $brules_valid = $this->Rules->brulesValidPdlr($report);
            if (!$brules_valid) {
                $errors = "At least 1 consistency rule applicable to this portfolio is required to process the report";
                $this->Flash->error($errors);
                $this->redirect($this->referer());
                exit();
            }

            $postData = $this->request->getData();
            $reportData = $this->Report->patchEntity($report, $this->request->getData());

            if (!$reportData->getErrors) {

                //file info processing
                $file = $this->request->getData('Import.file');
                $file_name = $file->getClientFilename();

                $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                error_log("dams uploaded file: " . json_encode($file));
                $file_renamed = "pdlr_" . $reportData->report_id . '_v' . $reportData->version_number . '.' . $ext;

                $checks = '';
                $fileMovingPath = '/var/www/html' . DS . 'data' . DS . 'damsv2' . DS . 'upload' . DS . $file_renamed;


                if ($this->File->checkFileInForm($file, $fileMovingPath)) {
                    //retrieve info from creation xlsx file

                    $infoFile = $this->Spreadsheet->createXlsxfile($file_renamed, $fileMovingPath);

                    if (!$this->Spreadsheet->noError($infoFile['errors'])) {

                        $errors = $this->Spreadsheet->showError($infoFile['errors']);

                        $this->Flash->error($errors, ['escape' => false]);
                        $this->redirect($this->referer());
                        exit();
                    }

                    $file_renamed = $infoFile['name'];
                    $fileMovingPath = $infoFile['path'];

                    $reportData->input_filename = $file_renamed;

                    //fill the table errors_logs
                    //create a new row each time you edit import of inclusion file (increment the field iterations)
                    //$this->ErrorsLog->checkErrorImport($report, 'N/A');

                    $errors = $this->Spreadsheet->checkSheetInclusionImport($postData, $file, $fileMovingPath);

                    $reportData->report_name = $report->portfolio->portfolio_name . "_" . $report->period_year . $report->period_quarter . "_v" . $reportData->version_number;
                    $reportData->due_date = $this->request->getData('Report.due_date');
                    $reportData->owner = $this->userIdentity()->get('id'); //$this->UserAuth->getUserId();
                    //unset($report_data['Report']['modified'], $report_data['Report']['file'], $report_data['Report']['id']);

                    if ($this->Spreadsheet->noError($checks['errors'])) {//do not cumulate errors
                        // DAMS 473
                        $data_num_check = [
                            'report_id'            => $reportData->report_id,
                            'save'                 => 0,
                            'correction'           => 0,
                            'template_id'          => $this->request->getData('Template.id'),
                            'version'              => $reportData->version_number,
                            'version_number_check' => $reportData->version_number,
                            'input_filename_check' => $file_renamed,
                            'headers_included'     => ($this->request->getData('Report.header') == '1' ? 'yes' : 'no'),
                            'template_type_id'     => $this->request->getData('Template.type_id')];

                        $numerical_errors = $this->numCheckSas($data_num_check);
                        error_log("pdlr import errors num check: " . json_encode($numerical_errors));
                        $errors['errors']['other'] = array_merge($numerical_errors['other'], $errors['errors']['other']);
                        error_log("pdlr import errors: " . json_encode($errors));
                        // END DAMS 473
                    }
                    if ($this->Spreadsheet->noError($errors['errors'])) {
                        $reportData->status_id = 19;
                        $this->Report->save($reportData);

                        $sasResult = $this->SAS->curl(
                                'import_file.sas', [
                            'report_id'        => $id,
                            'template_type_id' => $this->request->getData('Template.type_id'),
                            'correction'       => 0,
                            'save'             => 0
                                ],
                                false,
                                false
                        );
                        $log_info = [
                            'report_id'      => $id,
                            'portfolio_id'   => $report->portfolio_id,
                            'version_number' => $reportData->version_number,
                        ];
                        $this->logDams('PDLR report imported:' . json_encode($log_info), 'dams', 'Import PDLR report');

                        //$this->Session->setFlash($sasResult, "flash/simple");
                        $this->Flash->success("The report #" . $id . " is being processed");
                        $this->redirect($this->referer());

                        // }else{
                        // 	$this->Flash->error("Error during the transcodification in Latin, if the problem persist please contact the SAS support");
                        // 	$this->redirect($this->referer());
                        // }
                    } else {
                        $msg = $this->Spreadsheet->showError($errors['errors']);
                        $this->Flash->error($msg, ['escape' => false]);
                        $this->redirect($this->referer());
                    }
                }
            }
        }
    }

    public function deleteCache()
    {
        if ($this->request->is('post')) {
            $report_id = $this->request->getData('Report.report_id');

            //$getgroupalias = $this->UserAuth->getGroupNameAlias();
            //if (in_array('Admin', $getgroupalias) || in_array('usermanager', $getgroupalias) || in_array('Support', $getgroupalias)) {
            $report_id = intval($report_id);
            $report_path = '/var/www/html/data/damsv2/reports/';
            //inclusion
            @unlink($report_path . 'eif_import_file_running_report_' . $report_id);
            @unlink($report_path . 'eif_inclusion_validation_' . $report_id);
            @unlink($report_path . 'eif_inclusion_validation_report_' . $report_id);
            @unlink($report_path . 'eif_inclusion_validation_report_' . $report_id . '.pdf');
            //pdlr
            @unlink($report_path . 'report_invoice_add_' . $report_id . '.html');
            @unlink($report_path . 'report_invoice_add_' . $report_id . '.pdf');
            clearstatcache();
            //}
            $this->Flash->success('Cache files cleared!');
            $this->redirect($this->referer());
        }
    }

    /**
     * Re-upload an excel file when they are still errors
     * @param  [type] $report_id [description]
     * @return [type]            [description]
     */
    public function correction($report_id = null)
    {
        if (empty($report_id)) {
            throw new NotFoundException;
        }

        $report = $this->Report->get($report_id, [
            'contain' => ['Portfolio', 'Template'],
        ]);

        if (empty($report)) {
            $this->Flash->error('Invalid Report!');
            return $this->redirect($this->referer());
        } else {
            $this->set(compact('report'));
        }

        //form processing
        if ($this->request->is('post')) {

            // group retrieving  // http://vmu-sas-01:8080/browse/PLATFORM-259
//            $groups = CakeSession::read('UserAuth.UserGroups');
//            if (!is_array($groups))
//                $groups = array($groups);
//            if (!empty($groups))
//                foreach ($groups as $group) {
//                    $groupsnames[] = $group['alias_name'];
//                }
//            if (in_array('ReadOnlyDams', $groupsnames)) {
//                $this->Flash->error("You are currently in a read only profile, this functionality is disabled");
//                $this->redirect($this->referer());
//            }

            $new_report_version = ((int) $report->version_number) + 1;

            //$postData = $this->request->getData();

            $file = $this->request->getData('file');

            $file_name = $file->getClientFilename();

            $ext = pathinfo($file_name, PATHINFO_EXTENSION);

            if (isset($report->sheets) && $report->sheets == 'PD' || (isset($report['Report']['sheets']) && $report['Report']['sheets'] == 'PD')) {
                $file_renamed = "pdlr_" . $report->report_id . '_v' . $new_report_version . '.' . $ext;
            } else {
                $file_renamed = "inclusion_" . $report->report_id . '_v' . $new_report_version . '.' . $ext;
            }

            $fileMovingPath = '/var/www/html/' . DS . 'data' . DS . 'damsv2' . DS . 'upload' . DS . $file_renamed;

            if ($this->File->checkFileInForm($file, $fileMovingPath)) {
                //retrieve info from creation xlsx file
                $infoFile = $this->Spreadsheet->checkSheetInclusionImportCorrection($report, $file, $fileMovingPath);

                if (!$this->Spreadsheet->noError($infoFile['errors'])) {
                    $errors = $this->Spreadsheet->showError($infoFile['errors']);
                    $this->Flash->error($errors, ['escape' => false]);
                    return $this->redirect($this->referer());
                }

                // DAMS 473
                $data_num_check = [
                    'report_id'            => $report->report_id,
                    'save'                 => 0,
                    'correction'           => 1,
                    'template_id'          => $report->template_id,
                    'version'              => $new_report_version,
                    'version_number_check' => $new_report_version,
                    'input_filename_check' => $infoFile['name'],
                    'template_type_id'     => $report->template->template_type_id,
                    'headers_included'     => 'yes',
                ];
                $numerical_errors = $this->numCheckSas($data_num_check);
                $infoFile['errors'] = $numerical_errors;

                if (!$this->Spreadsheet->noError($infoFile['errors'])) {
                    $errors = $this->Spreadsheet->showError($infoFile['errors']);
                    $this->Flash->error($errors, ['escape' => false]);
                    return $this->redirect($this->referer());
                }

                $file_renamed = $infoFile['name'];
                $fileMovingPath = $infoFile['path'];

				$report->status_id = 19;
				$report->version_number = $new_report_version;
				$report->input_filename = $file_renamed;
				$report->report_name = $report->portfolio->portfolio_name . "_" . $report->period_year . $report->period_quarter . "_v" . $new_report_version;
				
                $this->Report->save($report);
                //fill the table errors_logs
                //create a new row each time you edit import of inclusion file (increment the field iterations)
                //$this->ErrorsLog->checkErrorImport($report, 'N/A');

                $sasResult = $this->SAS->curl(
                        'import_file.sas', [
                    'report_id'        => $report_id,
                    'template_type_id' => $this->request->getData('Template.type_id'),
                    'correction'       => 1,
                    'save'             => 0
                        ],
                        false,
                        true
                );
                $this->logDams('Include report ' . $report_id . ' included (correction) version ' . $new_report_version, 'dams', 'Include report');
                $PD = (isset($report->sheets) && $report->sheets == 'PD' || (isset($report['Report']['sheets']) && $report['Report']['sheets'] == 'PD'));
                $LR = (isset($report->sheets) && $report->sheets == 'LR' || (isset($report['Report']['sheets']) && $report['Report']['sheets'] == 'LR'));
                if ($PD || $LR) {
                    $log_params = [
                        'report_id'      => $report->report_id,
                        'portfolio_id'   => $report->portfolio_id,
                        'version_number' => $new_report_version,
                    ];
                    $this->logDams('PDLR report imported: ' . json_encode($log_params), 'dams', 'Include report');
                } else {
                    $log_params = [
                        'report_id'      => $report->report_id,
                        'portfolio_id'   => $report->portfolio_id,
                        'user_id'        => $this->userIdentity()->get('id'), //$this->UserAuth->getUserId()
                        'version_number' => $new_report_version,
                        'correction'     => 1,
                        'save'           => 0
                    ];
                    $this->logDams('Include report: ' . json_encode($log_params), 'dams', 'Include report');
                }

                $this->Flash->success("The report #" . $report_id . " is being processed");
                switch ($this->request->getData('Template.type_id')) {
                    case 1: $this->redirect(['action' => 'inclusion']);
                        break;
                    case 2: $this->redirect(['action' => 'pdlr']);
                    case 3: $this->redirect(['action' => 'pdlr']);
                        break;
                    default: $this->redirect(['action' => 'inclusion']);
                }
            }
        }
    }

    public function splitUpload($report_id = null)
    {
        if (empty($report_id)) {
            throw new NotFoundException;
        }

        $connection = ConnectionManager::get('default');

        $this->loadModel('Damsv2.Portfolio');

        $umbrella_portfolio = $this->Report->find('all', [
                    'contain'    => ['Portfolio', 'Portfolio.UmbrellaPortfolio'],
                    'conditions' => [
                        'Report.report_id'             => $report_id,
                        'Report.period_quarter is NOT' => null
                    ]
                ])->first();

        $umbrella_portfolio_name = $umbrella_portfolio->portfolio->umbrella_portfolio->umbrella_portfolio_name;
        $period = $umbrella_portfolio->period_year . $umbrella_portfolio->period_quarter;

        $this->set('report_id', $report_id);
        $this->set('umbrella_portfolio_name', $umbrella_portfolio_name);
        $this->set('period', $period);

        $umbrella_portfolio_id = $umbrella_portfolio->portfolio->umbrella_portfolio->umbrella_portfolio_id;
        $period_quarter = $umbrella_portfolio->period_quarter;
        $period_year = $umbrella_portfolio->period_year;
        $portfolio_of_umbrella = $umbrella_portfolio->portfolio->portfolio_id;


        ////// getting possible template_id
        $this->loadModel('Damsv2.Template');
        $template_id_list = $this->Template->find('list', [
                    'fields'     => ['template_id'],
                    'valueField' => ['template_id'],
                    'conditions' => ['template_type_id' => 1],
                ])->toArray();

        ///////////////////  search version of umbrella report for this period
        //get all umbrella report for period
        $umbrella_portfolio_period = $this->Report->find('all', [
            'fields'     => ['report_id'],
            'conditions' => [
                'template_id IN' => $template_id_list,
                'portfolio_id'   => $portfolio_of_umbrella,
                'period_year'    => $period_year,
                'period_quarter' => $period_quarter
            ],
            'order'      => ['report_id asc']
        ]);

        $umbrella_report_version = 0;
        foreach ($umbrella_portfolio_period as $row) {
            if ($row->report_id == $report_id) {
                $umbrella_report_version++;
            }
        }

        ///////////////////// end version
        //get all portfolio ids

        $this->loadModel('Damsv2.UmbrellaPortfolioMapping');
        $req_sub_portfolios_portfolios_ids = $this->UmbrellaPortfolioMapping->find('all', [
            'conditions' => ['umbrella_portfolio_id' => $umbrella_portfolio_id],
        ]);

        $sub_portfolios_count = $req_sub_portfolios_portfolios_ids->count();

        $sub_portfolio_report_ids = [];
        $deleted_reports = [];
        $not_generated_reports = [];

        foreach ($req_sub_portfolios_portfolios_ids as $sub) {
            $sub_portfolio_id = $sub->portfolio_id;

            $sub_port_report = $this->Report->find('all', [
                        'fields'     => ['report_id'],
                        'conditions' => [
                            'template_id IN' => $template_id_list,
                            'portfolio_id'   => $sub_portfolio_id,
                            'period_year'    => $period_year,
                            'period_quarter' => $period_quarter,
                            'report_id <='   => $report_id,
                        ],
                        'order'      => ['report_id DESC']
                    ])->first();

            if (!empty($sub_port_report)) {
                array_push($sub_portfolio_report_ids, $sub_port_report->report_id);
            } else {
                $statement = $connection->prepare('SELECT r.report_id FROM umbrella_portfolio_deleted r, status s, status_umbrella su, portfolio p WHERE su.status_id_umbrella=r.status_id_umbrella AND s.status_id=r.status_id AND r.portfolio_id=p.portfolio_id AND r.period= :period AND r.portfolio_id = :portfolio AND report_id <= :report ORDER BY report_id DESC LIMIT 1');
                $statement->bind(
                        ['period' => $period_year . $period_quarter, 'portfolio' => $sub_portfolio_id, 'report' => $report_id],
                        ['period' => 'string', 'portfolio' => 'integer', 'report' => 'integer'],
                );
                $sub_portfolio_missing = $statement->fetchAll('assoc');


                if (!empty($sub_portfolio_missing)) {
                    array_push($deleted_reports, $sub_portfolio_missing->report_id);
                } else {
                    error_log("subportfolio " . $sub_portfolio_id . " is totally missing for umbrella " . $umbrella_portfolio_id . " (l " . __LINE__ . " ReportController.php)");
                    array_push($not_generated_reports, $sub_portfolio_id);
                }
            }
        }

        $next_umbrella_report = $this->Report->find('all', [
                    'fields'     => ['report_id'],
                    'conditions' => [
                        'template_id IN' => $template_id_list,
                        'portfolio_id'   => $portfolio_of_umbrella,
                        'period_year'    => $period_year,
                        'period_quarter' => $period_quarter,
                        'report_id >'    => $report_id,
                    ],
                    'order'      => ['report_id DESC']
                ])->first();

//        NOT USED CODE
//        $sql_umbrella = " AND report_id >= " . intval($report_id - $sub_portfolios_count) . " ";
//        //find next report same period if exists
//        $req_next_umbrella = "SELECT r.report_id FROM report r WHERE r.portfolio_id= " . intval($portfolio_of_umbrella) . " AND r.period_quarter='" . $period_quarter . "' AND r.period_year=" . intval($period_year) . " AND r.report_id > " . intval($report_id) . " AND template_id IN (" . $template_id_list . ") ORDER BY r.report_id DESC LIMIT 1";
//        $next_umbrella_report = $this->Portfolio->query($req_next_umbrella);
//        if (!empty($next_umbrella_report)) {
//            $sql_umbrella .= " AND report_id <= " . $next_umbrella_report[0]['r']['report_id'] . " ";
//        }
        //sub portfolios of the umbrella with reports:
        //$sub_portfolios = [];
        $sub_portfolios = $this->Report->find('all', [
                    //'fields'     => ['Report.report_id', 'Report.status_id', 'Report.portfolio_id', 'Portfolio.portfolio_name', 'StatusUmbrella.stage', 'Status.status'],
                    'contain'    => ['Portfolio', 'StatusUmbrella', 'Status'],
                    'conditions' => [
                        'Report.report_id IN' => $sub_portfolio_report_ids,
                    ],
                    'order'      => ['Portfolio.portfolio_name ASC']
                ])->toArray();

        foreach ($sub_portfolios as $sub_p) {
            $not_the_latest = $this->Report->find('all', [
                'conditions' => [
                    'template_id IN' => $template_id_list,
                    'portfolio_id'   => $sub_p->portfolio_id,
                    'visible'        => 1,
                    'report_id >'    => $sub_p->report_id,
                ]
            ]);
            if ($not_the_latest->count() >= 1) {
                $sub_p['disabled'] = true;
            }
        }

        //search for deleted portfolios reports
        $sub_portfolios_missing = [];

        foreach ($deleted_reports as $missing_portfolio_id) {
            $statement = $connection->prepare('SELECT r.report_id FROM umbrella_portfolio_deleted r, status s, status_umbrella su, portfolio p WHERE su.status_id_umbrella=r.status_id_umbrella AND s.status_id=r.status_id AND r.portfolio_id=p.portfolio_id AND r.period= :period  AND report_id = :report ');
            $statement->bind(
                    ['period' => $period_year . $period_quarter, 'report' => $missing_portfolio_id],
                    ['period' => 'string', 'report' => 'integer'],
            );
            $sub_portfolio_missing = $statement->fetchAll('assoc');

            if (!empty($sub_portfolio_missing)) {
                $sub_portfolios_missing[$sub_portfolio_missing->portfolio_name] = $sub_portfolio_missing;
            }
        }

        foreach ($sub_portfolios_missing as $p) {
            $p['disabled'] = true;
            $p['report_id'] = $p->report_id;
            //$p['r']['report_id'] = "N/A"; //temporary comment
            $p['stage'] = "No report found";
            $p['status']['status'] = "";
            $p['status_id'] = $p->status_id;
            $sub_portfolios[] = $p;
        }

        foreach ($not_generated_reports as $portfolio_id_no_report) {
            $portfolio_not_generated = $this->Portfolio->find('all', ['conditions' => [
                            'portfolio_id' => $portfolio_id_no_report,
                ]])->first();

            $p = [];
            $p['disabled'] = true;
            $p['portfolio_name'] = $portfolio_not_generated->portfolio_name;
            $p['report_id'] = "N/A";
            //$p['r'] = ['report_id' => "N/A"];
            $p['input_filename_umbrella'] = "";
            $p['stage'] = "No report found";
            $p['status']['status'] = "";
            $p['status_id'] = "";
            $sub_portfolios[] = $p;
        }

        if (!empty($sub_portfolios)) {
            ksort($sub_portfolios);
        }

        $brules_message = "";
        $brule_missing = false;

        //search for missing business rules
        $this->loadModel('Damsv2.Rules');
        foreach ($sub_portfolios as $p) {
            if (!empty($p->portfolio_id)) {
                $brules = $this->Rules->find('all', [
                    'fields'     => ['Rules.rule_id'],
                    'conditions' => [
                        'Rules.portfolio_id'     => $p->portfolio_id,
                        'Rules.template_type_id' => 1, // 1 for inclusion
                    ]
                ]);

                if (empty($brules)) {
                    $brule_missing = true;
                    $brules_message .= $p->portfolio_name . " doesn't have associated Business Rules";
                }
            }
        }

        $this->set('brule_missing', $brule_missing);
        $this->set('brules_message', $brules_message);

//        foreach ($sub_portfolios as &$p) {
//            //update of version in filenames
//            $pattern = "/_v[0-9]*.xlsx/";
//            $new_version_filename = "_v" . $umbrella_report_version . ".xlsx";
//            if (preg_match($pattern, $p['r']['input_filename_umbrella'])) {
//                $p['r']['input_filename_umbrella'] = preg_replace($pattern, $new_version_filename, $p['r']['input_filename_umbrella'], 1);
//            }
//            // update in DB
//            if ($p['r']['report_id'] != 'N/A') {
//                $connection->update('report', ['input_filename_umbrella' =>  $p['r']['input_filename_umbrella']], ['report_id' => $p['r']['report_id']]);
//                //$this->Portfolio->query("UPDATE report SET input_filename_umbrella='" . $p['r']['input_filename_umbrella'] . "' WHERE report_id=" . $p['r']['report_id']);
//            } else {
//                if (!empty($p['report_id']) && (is_numeric($p['report_id']))) {
//                    $connection->update('report', ['input_filename_umbrella' =>  $p['r']['input_filename_umbrella']], ['report_id' => $p['report_id']]);
//                    //$this->Portfolio->query("UPDATE report SET input_filename_umbrella='" . $p['r']['input_filename_umbrella'] . "' WHERE report_id=" . $p['report_id']);
//                }
//            }
//        }

        $this->set('umbrella_report_version', $umbrella_report_version);
        $this->set('sub_portfolios', $sub_portfolios);

        //check if there is another report for the umbrella for the same period (allowed for umbrella's only), which is to be included manually (redundant with version)

        $double_report = $this->Report->find('all', [
            'conditions' => [
                'portfolio_id'   => $portfolio_of_umbrella,
                'period_year'    => $period_year,
                'period_quarter' => $period_quarter,
                'report_id <'    => $report_id,
            ],
            'order'      => ['report_id DESC']
        ]);

        $double_generation = ($double_report->count() >= 1);
        $this->set('double_generation', $double_generation);

        //form processing part
        if ($this->request->is('post')) {

            // group retrieving  // http://vmu-sas-01:8080/browse/PLATFORM-259
//            $groups = CakeSession::read('UserAuth.UserGroups');
//            if (!is_array($groups))
//                $groups = [$groups];
//            if (!empty($groups))
//                foreach ($groups as $group) {
//                    $groupsnames[] = $group['alias_name'];
//                }
//            if (in_array('ReadOnlyDams', $groupsnames)) {
//                $this->Session->setFlash("You are currently in a read only profile, this functionality is disabled", "flash/error");
//                $this->redirect($this->referer());
//            }

            $report_ids_array = [];
            foreach ($this->request->getData() as $key => $val) {
                error_log("umbrella : selected reports: $key / $val");
                if (strpos($key, "Select_") !== false) {
                    if (1 == intval($val)) {
                        $report_ids_array[] = str_replace("Select_", '', $key);
                    }
                }
            }
            foreach ($report_ids_array as &$rep_id) {
                $rep_id = intval($rep_id);
            }
            error_log("umbrella : reports selecteds :" . json_encode($report_ids_array));
            $report_ids = implode('$$', $report_ids_array); //TODO change dollar sign for another one

            $sasResult = $this->SAS->curl(
                    'automatic_inclusion.sas',
                    [
                        'list_auto_rep' => $report_ids
                    ],
                    false,
                    false
            );
            $rep = $sasResult;
        }
    }

    /**
     * function numCheckSas
     * DAMS 473
     * @return array
     */
    private function numCheckSas($data)
    {
        return []; //just ignore the check
        /* $sasResult = $this->SAS->curl(
          "import_file_check.sas", $data,
          false,
          false
          );
          if (strpos($sasResult, "This request completed with errors.") !== false) {
          $this->Flash->warning("The excel file check failed. Please contact the SAS support.");
          error_log("numcheck failed : " . json_encode($data));
          return []; //just ignore the check
          } else {
          error_log("analyseSasResultofNumCheck params : " . json_encode($data['report_id']) . ", " . json_encode($data['version']));
          return $this->Spreadsheet->analyseSasResultofNumCheck($data['report_id'], $data['version']);
          } */
    }

    /**
     * Reception of a Paymend Demand / Loss Recovery
     * @param $id Report ID in case of update
     * @return void
     */
    public function pdlrReception($id = null)
    {
        if (!empty($id)) {
            $report = $this->Report->get($id, [
                'contain' => ['Portfolio', 'Status', 'Template'],
            ]);
            $default_type = $report->template->template_type_id;
            $default_product = $report->portfolio->product_id;
            $default_portfolio = $report->portfolio->portfolio_id;
            $default_currency = strtoupper($report->ccy);
            $default_quarter = $report->period_quarter;
            $default_year = $report->period_year;
            $default_reception = $report->reception_date;
            $default_due = $report->due_date;
            $default_amount = $report->amount;
            $default_amount_ctr = $report->amount_ctr;
            $default_version_number = (int) $report->version_number + 1;
        } else {
            $report = $this->Report->newEmptyEntity();
            $default_type = null;
            $default_product = null;
            $default_portfolio = null;
            $default_currency = null;
            $default_quarter = "Q" . ceil(date('n', time()) / 3);
            $default_year = date('Y', time());
            $default_reception = null;
            $default_due = null;
            $default_amount = null;
            $default_amount_ctr = null;
            $default_version_number = 1;
        }

        $this->loadModel('Damsv2.Product');
        $products = $this->Product->find('list', [
            'fields'     => ['Product.product_id', 'Product.name'],
            'conditions' => ['OR' => ['Product.product_type' => 'guarantee', 'Product.product_id' => 18]], // Exception for Foster PRSL id=18
            'order'      => ['Product.name']
        ]);

        $this->loadModel('Damsv2.Portfolio');
        $portfolios = $this->Portfolio->find('list', [
                    'contain'    => 'Product',
                    'fields'     => ['portfolio_id', 'portfolio_name', 'mandate'],
                    'keyField'   => 'portfolio_id',
                    'valueField' => 'portfolio_name',
                    'groupField' => 'mandate',
                    'order'      => 'mandate',
                    'conditions' => ['Product.product_id NOT IN' => [22, 23]],
                ])->toArray();

        $this->loadModel('Damsv2.Daily');
        $currencies = $this->Daily->find('list', [
                    'keyField'   => 'currency',
                    'valueField' => 'currency',
                    'fields'     => ['currency']
                ])->toArray();

        //add three new currencies
        $currencies['ALL'] = 'ALL';
        $currencies['BAM'] = 'BAM';
        $currencies['RSD'] = 'RSD';
        $currencies['EUR'] = 'EUR';

        $connection = ConnectionManager::get('default');
        $umbrella_portfolio_ids = $connection->query('SELECT DISTINCT(currency) FROM ncb_rate')->fetchAll('assoc');
        $ncb_rates = $this->Daily->query("SELECT DISTINCT(currency) FROM damsv2.ncb_rate");
        foreach ($ncb_rates as $ncb_rate) {
            $curr = $ncb_rate->currency;
            $currencies[$curr] = $curr;
        }

        ksort($currencies);

        $FXcurrencies = [];
        $FXcurrencies['ALL'] = 'ALL';
        $FXcurrencies['BAM'] = 'BAM';
        $FXcurrencies['RSD'] = 'RSD';

        $this->set(compact('report', 'products', 'portfolios', 'currencies', 'FXcurrencies'));
        $this->set(compact('default_type', 'default_product', 'default_portfolio', 'default_currency', 'default_quarter', 'default_year'));
        $this->set(compact('default_reception', 'default_due', 'default_amount', 'default_amount_ctr', 'default_version_number'));

        if ($this->request->is('post')) {
            if (
                    empty($this->request->getData('Template.template_type_id')) OR
                    empty($this->request->getData('Report.portfolio_id')) OR
                    empty($this->request->getData('Report.reception_date')) OR
                    empty($this->request->getData('Report.period_quarter')) OR
                    empty($this->request->getData('Report.period_year')) OR
                    empty($this->request->getData('Report.due_date')) OR
                    empty($this->request->getData('Report.ccy')) OR
                    empty($this->request->getData('Report.amount')) OR
                    empty($this->request->getData('Report.version_number'))
            ) {
                $this->Flash->error("Some required fields are missing");
                return $this->redirect($this->referer());
            }

            //for FOSTER PRSL => replaced by https://eifsas.atlassian.net/browse/DAMS-223
            /* if (!empty($this->request->getData('Product']['product_id']) && !empty($this->request->getData('Template']['template_type_id']))
              {
              if (($this->request->getData('Product.product_id') == 18) && ($this->request->getData('Template.template_type_id') == 2))
              {
              $this->Flash->error("ERROR: No Payment Demand for this product");
              $this->redirect($this->referer());
              }
              } */

            $portfolio = $this->Portfolio->get($this->request->getData('Report.portfolio_id'), [
                'contain' => ['Template', 'Product'],
            ]);

            if (!empty($portfolio->template)) {
                foreach ($portfolio->template as $template) {
                    if ($template->template_type_id == $this->request->getData('Template.template_type_id')) {
                        $rep_template_id = $template->template_id;
                    }
                }
            }

            if (empty($portfolio->template)) {
                $this->Flash->error("This report type has no associated template for this Portfolio");
                return $this->redirect($this->referer());
            }

            if ($portfolio->status_portfolio == "EARLY TERMINATED" && $this->request->getData('Template.template_type_id') == 2) {
                $this->Flash->error("This porfolio is in status EARLY TERMINATED only Loss Recovery can be created");
                return $this->redirect($this->referer());
            }

            $type_name = '';
            switch ($this->request->getData('Template.template_type_id')) {
                case '2':
                    $type_name = 'pd';
                    break;
                case '3':
                    $type_name = 'lr';
                    break;
                default:
                    $this->Flash->error("Something went wrong during the mapping of the template <--> portfolio");
                    $this->redirect($this->referer());
                    break;
            }

            $period_start = '';
            $period_end = '';
            switch ($this->request->getData('Report.period_quarter')) {
                case 'Q1':
                    $period_start = $this->request->getData('Report.period_year') . "-01-01";
                    $period_end = $this->request->getData('Report.period_year') . "-03-31";
                    break;
                case 'Q2':
                    $period_start = $this->request->getData('Report.period_year') . "-04-01";
                    $period_end = $this->request->getData('Report.period_year') . "-06-30";
                    break;
                case 'Q3':
                    $period_start = $this->request->getData('Report.period_year') . "-07-01";
                    $period_end = $this->request->getData('Report.period_year') . "-09-30";
                    break;
                case 'Q4':
                    $period_start = $this->request->getData('Report.period_year') . "-10-01";
                    $period_end = $this->request->getData('Report.period_year') . "-12-31";
                    break;
            }

            $report = $this->Report->patchEntity($report, $this->request->getData());

            //save report
            $report->status_id = 8;

            if (empty($id)) {
                $report->template_id = $rep_template_id;
            }

            $report->report_name = $portfolio->portfolio_name . "_" . $this->request->getData('Report.period_year') . $this->request->getData('Report.period_quarter') . "_v" . $this->request->getData('Report.version_number');
            $report->period_start_date = $period_start;
            $report->period_end_date = $period_end;
            $report->sheets = strtoupper($type_name);
            $report->owner = $this->userIdentity()->get('id');
            $report->amount_ctr = !empty($this->request->getData('Report.amount_ctr')) ? preg_replace("/[^0-9\.-]/", "", $this->request->getData('Report.amount_ctr')) : '';
            $report->amount = preg_replace("/[^0-9\.-]/", "", $this->request->getData('Report.amount'));
            if (($type_name == 'pd') && ($this->request->getData('Report.clawback') == '1')) {
                $report->clawback = 'Y';
            }

            if ($this->Report->save($report)) {
                $log_info = [
                    'report_id'         => $report->report_id,
                    'report_name'       => $report->report_name,
                    'period_quarter'    => $report->period_quarter,
                    'period_year'       => $report->period_year,
                    'period_start_date' => $report->period_start_date,
                    'period_end_date'   => $report->period_end_date,
                    'portfolio_id'      => $report->portfolio_id,
                    'template_id'       => $report->template_id,
                    'status_id'         => $report->status_id,
                    'sheets'            => $report->sheets,
                    'owner'             => $report->owner,
                    'clawback'          => $report->clawback,
                    'ccy'               => $report->ccy,
                    'amount_ctr'        => $report->amount_ctr,
                    'amount'            => $report->amount,
                    'username'          => $this->userIdentity()->get('username'), //CakeSession::read('UserAuth.User.username'),
                    'version'           => $report->version,
                ];
                $this->logDams('PDLR report created ' . json_encode($log_info) . ' ', 'dams', 'Create PDLR report');

                $this->Flash->success(__('The report has been saved.'));

                return $this->redirect(['action' => 'pdlr']);
            }
            $this->Flash->error(__('The report could not be saved. Please, try again.'));
        }
    }

    public function checkPortfolioTemplate()
    {
        $output = '';
        if ((!empty($this->request->getData()) && $this->request->getData('product') == 18) && (!empty($this->request->getData('type')) && $this->request->getData('type') == 2)) {
            $output .= '<div class="alert" style="font-size: 20px; line-height: 24px; color: #f00;">WARNING: No Payment Demand for this product</div>';
        }

        if (!empty($this->request->getData('type')) && !empty($this->request->getData('portfolio'))) {
            $this->loadModel('Damsv2.Template');
            $templates = $this->Template->portfolioHasPDLR($this->request->getData('portfolio'));
            $type = intval($this->request->getData('type'));

            if (($type == 2) && (!$templates["hasLR"])) {
                die();
            }
            if (($type == 3) && (!$templates["hasPD"])) {
                die();
            }
        }
        die($output);
    }

    public function checkPortfolioRecoveryRate()
    {
        $output = '';
        if (!empty($this->request->getData('type')) && ($this->request->getData('type') == 3) && !empty($this->request->getData('portfolio'))) {
            $this->loadModel('Damsv2.Portfolio');
            $portfolio = $this->Portfolio->find('all', array('conditions' => array('portfolio_id' => $this->request->getData('portfolio'))))->first();
            $array_products = array(6, 24, 16);
            if ((!empty($portfolio)) && (in_array($portfolio->product_id, $array_products)) && ($portfolio->recovery_rate > 0)) {
                $output .= '<div class="alert" style="font-size: 20px; line-height: 24px; color: #f00;">This portfolio has a fixed recovery rate, therefore no recoveries can be uploaded through Loss Recovery template.</div>';
            }
        }
        die($output);
    }

    public function rejectPdlr()
    {
        $report_id = $this->request->getData('Report.report_id');
        $msg = "The report " . $report_id . " has been rejected";
        $this->changeStatus($report_id, 8, true, 'pdlr', $msg);
    }

    public function pdlrReject()
    {
        $report_id = $this->request->getData('Report.report_id');
        $this->autoRender = false;
        if (!$this->Report->exists($report_id)) {
            throw new NotFoundException(__('Invalid Report'));
        }
        // group retrieving  // http://vmu-sas-01:8080/browse/PLATFORM-259
//        $groups = CakeSession::read('UserAuth.UserGroups');
//        if (!is_array($groups))
//            $groups = array($groups);
//        if (!empty($groups))
//            foreach ($groups as $group) {
//                $groupsnames[] = $group['alias_name'];
//            }
//        if (in_array('ReadOnlyDams', $groupsnames)) {
//            $this->Flash->error("You are currently in a read only profile, this functionality is disabled");
//            $this->redirect($this->referer());
//        } else {
//            /* $sasResult = $this->SAS->curl(
//              'pdlr_reject.sas', array(//script does not exists
//              'report_id' => $report_id
//              ),
//              false,
//              false
//              ); */
//            //$this->logDams('PDLR report '.$report_id.' rejected', 'dams');
//            $msg = "The report " . $report_id . " has been rejected";
//            $this->changeStatus($report_id, 8, true, 'pdlr_dashboard', $msg);
//        }

        $msg = "The report " . $report_id . " has been rejected";
        $this->changeStatus($report_id, 8, true, 'pdlr', $msg);
    }

    public function updateStatus()
    {
        $report_id = $this->request->getData('Report.report_id');
        $status_id = $this->request->getData('Report.status_id');
        $rejected = $this->request->getData('Report.rejected') ? $this->request->getData('Report.rejected') : false;
        $redirect = $this->request->getData('Report.redirect') ? $this->request->getData('Report.redirect') : 'inclusion';
        $msg = $this->request->getData('Report.msg') ? $this->request->getData('Report.msg') : '';

        $this->changeStatus($report_id, $status_id, $rejected, $redirect, $msg);
    }

    /**
     * Change the status of a report and redirect where it comes from
     * @param unknown $report_id
     * @param unknown $status_id
     * @throws NotFoundException
     */
    public function changeStatus($report_id, $status_id, $rejected = false, $redirect = 'inclusion', $msg = '')
    {
        // group retrieving  // http://vmu-sas-01:8080/browse/PLATFORM-259
//        $groups = CakeSession::read('UserAuth.UserGroups');
//        if (!is_array($groups))
//            $groups = array($groups);
//        if (!empty($groups))
//            foreach ($groups as $group) {
//                $groupsnames[] = $group['alias_name'];
//            }
//        if (in_array('ReadOnlyDams', $groupsnames)) {
//            $this->Session->setFlash("You are currently in a read only profile, this functionality is disabled", "flash/error");
//            $this->redirect($this->referer());
//            exit();
//        }

        $this->autoRender = false;
        if (!$this->Report->exists($report_id)) {
            throw new NotFoundException(__('Invalid Report'));
        }

//        $this->loadModel('Damsv2.Status');
//        
//        dd($this->Status->exists($status_id));
//        if (!$this->Status->exists($status_id)) {
//            throw new NotFoundException(__('Invalid Status'));
//        }
        if ($rejected) {
            Cache::delete('inclusion_validation_report_' . $report_id, 'damsv2');
            Cache::delete('import_file_running_report_' . $report_id, 'damsv2');
        }
        if (empty($msg)) {
            $msg = "The status of the report #$report_id has been updated";
        } else {
            $msg = preg_replace('/ report ([0-9]+)/i', ' report #${1}', $msg);
        }

        $report = $this->Report->get($report_id);

        $old_status = $report->status_id;
        $report->status_id = $status_id;
        if ($status_id == 7) {
            $report->sheets = '';
        }

        if ($rejected) {
            $report->version_number = (int) ($report->version_number) + 1;
            $report->report_name = $report->report_name . "_" . $report->period_year . $report->period_quarter . "_v" . $report->version_number;
        }
        $ok = $this->Report->save($report);
        $log_info = [
            'report_id'       => $report_id,
            'portfolio_id'    => $report->portfolio_id,
            'previous_status' => $old_status,
            'new_status'      => $status_id,
        ];
        $this->logDams('Report status changed: ' . json_encode($log_info), 'dams', 'Change of report status');
        $this->Flash->success($msg);
        $this->redirect(['action' => $redirect]);
    }

    /*
      pass effective_termination_date or guarantee_termination to have real end date available
     */

    public function get_end_period_q($timestamp = null)
    {
        //@$this->validate_param('int', $timestamp);
        $year = date("Y", $timestamp);

        $period_start_q1 = $year . "-01-01";
        $period_end_q1 = $year . "-03-31";


        //case 'Q2':
        $period_start_q2 = $year . "-04-01";
        $period_end_q2 = $year . "-06-30";

        if ($timestamp < strtotime($period_start_q2)) {
            return strtotime($period_end_q1);
        }

        //case 'Q3':
        $period_start_q3 = $year . "-07-01";
        $period_end_q3 = $year . "-09-30";
        if ($timestamp < strtotime($period_start_q3)) {
            return strtotime($period_end_q2);
        }

        //case 'Q4':
        $period_start_q4 = $year . "-10-01";
        $period_end_q4 = $year . "-12-31";
        if ($timestamp < strtotime($period_start_q4)) {
            return strtotime($period_end_q3);
        }
        return strtotime($period_end_q4);
    }

    public function get_end_period_s($timestamp = null)
    {
        //@$this->validate_param('int', $timestamp);
        $year = date("Y", $timestamp);
        //case 'S1':
        $period_start_s1 = $year . "-01-01";
        $period_end_s1 = $year . "-06-30";

        //case 'S2':
        $period_start_s2 = $year . "-07-01";
        $period_end_s2 = $year . "-12-31";

        if ($timestamp < strtotime($period_start_s2)) {
            return strtotime($period_end_s1);
        }
        if ($timestamp < strtotime($period_end_s2)) {
            return strtotime($period_end_s2);
        }

        return strtotime($period_end_s2);
    }

    public function get_end_period_s_spe($timestamp = null)
    {
        //@$this->validate_param('int', $timestamp);
        $year = date("Y", $timestamp);
        //case 'S1_spe':
        $period_start_s1_s = ($year - 1) . "-10-01";
        $period_end_s1_s = $year . "-03-31";


        //case 'S2_spe':
        $period_start_s2_s = $year . "-04-01";
        $period_end_s2_s = $year . "-09-30";

        if ($timestamp < strtotime($period_start_s2_s)) {
            return strtotime($period_end_s1_s);
        }
        if ($timestamp < strtotime($period_end_s2)) {
            return strtotime($period_end_s2_s);
        }
        return strtotime($period_end_s2_s);
    }

    public function get_start_period_q($timestamp = null)
    {
        //@$this->validate_param('int', $timestamp);
        $year = date("Y", $timestamp);

        $period_start_q1 = $year . "-01-01";
        $period_end_q1 = $year . "-03-31";

        if ($timestamp >= strtotime($period_start_q1) && $timestamp <= strtotime($period_end_q1)) {
            return strtotime($period_start_q1);
        }

        //case 'Q2':
        $period_start_q2 = $year . "-04-01";
        $period_end_q2 = $year . "-06-30";

        if ($timestamp >= strtotime($period_start_q2) && $timestamp <= strtotime($period_end_q2)) {
            return strtotime($period_start_q2);
        }

        //case 'Q3':
        $period_start_q3 = $year . "-07-01";
        $period_end_q3 = $year . "-09-30";

        if ($timestamp >= strtotime($period_start_q3) && $timestamp <= strtotime($period_end_q3)) {
            return strtotime($period_start_q3);
        }

        //case 'Q4':
        $period_start_q4 = $year . "-10-01";
        $period_end_q4 = $year . "-12-31";
        if ($timestamp >= strtotime($period_start_q4) && $timestamp <= strtotime($period_end_q4)) {
            return strtotime($period_start_q4);
        }

        return $timestamp;
    }

    public function get_start_period_s($timestamp = null)
    {
        //@$this->validate_param('int', $timestamp);
        $year = date("Y", $timestamp);

        //case 'S1':
        $period_start_s1 = $year . "-01-01";
        $period_end_s1 = $year . "-06-30";

        if ($timestamp >= strtotime($period_start_s1) && $timestamp <= strtotime($period_end_s1)) {
            return strtotime($period_start_s1);
        }

        //case 'S2':
        $period_start_s2 = $year . "-07-01";
        $period_end_s2 = $year . "-12-31";

        if ($timestamp >= strtotime($period_start_s2) && $timestamp <= strtotime($period_end_s2)) {
            return strtotime($period_start_s2);
        }
        return $timestamp;
    }

    public function get_start_period_s_spe($timestamp = null)
    {
        //@$this->validate_param('int', $timestamp);
        $year = date("Y", $timestamp);

        //case 'S1_spe':
        $period_start_s1_s = ($year - 1) . "-10-01";
        $period_end_s1_s = $year . "-03-31";



        if ($timestamp >= strtotime($period_start_s1_s) && $timestamp <= strtotime($period_end_s1_s)) {
            return strtotime($period_start_s1_s);
        }


        //case 'S2_spe':
        $period_start_s2_s = $year . "-04-01";
        $period_end_s2_s = $year . "-09-30";

        if ($timestamp >= strtotime($period_start_s2_s) && $timestamp <= strtotime($period_end_s2_s)) {
            return strtotime($period_start_s2_s);
        }
        return $timestamp;
    }

    private function getUmbrellaIqid()
    {
        $connection = ConnectionManager::get('default');
        $umbrella_iqid = $connection->query('SELECT iqid FROM umbrella_portfolio')->fetchAll('assoc');

        $collection = new Collection($umbrella_iqid);
        $iqids = $collection->extract('iqid')->toList();
        return $iqids;
    }

}
