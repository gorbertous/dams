<?php

declare(strict_types=1);

namespace Damsv2\Controller;

use App\Lib\Helpers;
use Cake\Event\EventInterface;
use KubAT\PhpSimple\HtmlDomParser;
use Cake\Http\Exception\NotFoundException;

/**
 * Validation Controller
 *
 * @method \App\Model\Entity\Validation[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ControlController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        //$this->loadComponent('Security');
        $this->loadComponent('Spreadsheet');
        $this->loadComponent('SAS');
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
    }

    public function home()
    {
        $productTable = $this->getTableLocator()->get('Damsv2.Product');
        $products = $productTable->getProducts();

        $portfolioTable = $this->getTableLocator()->get('Damsv2.Portfolio');
        $portfolios = $portfolioTable->find('list', [
            'fields'     => ['portfolio_id', 'portfolio_name', 'mandate'],
            'keyField'   => 'portfolio_id',
            'valueField' => 'portfolio_name',
            'groupField' => 'mandate',
            'order'      => 'mandate'
        ])->toArray();

        $templatesTable = $this->getTableLocator()->get('Damsv2.Template');

        $templates = $templatesTable->find('list', [
            'keyField'   => 'template_id',
            'valueField' => 'name',
            'conditions' => ['template_type_id' => 1]
        ])->toArray();

        $conditions = [
            'Report.template_id IN' => array_keys($templates),
            'Report.bulk'           => 0,
            'Report.visible'        => 1,
        ];

        if (!empty($this->request->getData('product_id'))) {
            $portfolios = $portfolioTable->find('list', ['conditions' => ['Portfolio.product_id' => $this->request->getData('product_id')], 'fields' => ['portfolio_id', 'portfolio_name', 'Product.name'], 'recursive' => 0]);
        }


        $reportTable = $this->getTableLocator()->get('Damsv2.Report');

        if (!empty($this->request->getData('Portfolio.portfolio_id'))) {

            $conditions_latest = Helpers::arrayPushAssoc($conditions, 'Report.portfolio_id', $this->request->getData('Portfolio.portfolio_id'));
            $latest_report = $reportTable->find('all', [
                'conditions' => $conditions_latest,
                'order'      => 'Report.report_id DESC'
            ])->first();

            if (empty($latest_report)) {
                $conditions = Helpers::arrayPushAssoc($conditions, 'Report.portfolio_id', $this->request->getData('Portfolio.portfolio_id'));
            } else {
                $conditions = Helpers::arrayPushAssoc($conditions, 'Report.portfolio_id', $this->request->getData('Portfolio.portfolio_id'));
                $conditions = Helpers::arrayPushAssoc($conditions, 'Report.report_id', $latest_report->report_id);
            }
        } else {
            $conditions_latest = $conditions;
            $latest_reports = $reportTable->find('list', ['conditions' => $conditions_latest, 'group' => ['Report.portfolio_id'], 'order' => 'Report.report_id DESC', 'fields' => ['Report.report_id'], 'recursive' => -1])->toArray();

            $conditions = $conditions + [
                'Report.report_id IN' => array_keys($latest_reports),
            ];
        }

        $query = $reportTable->find('all', [
            'contain'    => ['Portfolio', 'Template', 'Status', 'Portfolio.Product'],
            'conditions' => [$conditions]
        ]);

        $reports = $this->paginate($query);
        $this->set(compact('reports', 'products', 'portfolios'));
    }

    public function pdlrList()
    {
        $productTable = $this->getTableLocator()->get('Damsv2.Product');
        $products = $productTable->getProducts();

        $portfolioTable = $this->getTableLocator()->get('Damsv2.Portfolio');
        $portfolios = $portfolioTable->find('list', [
            'fields'     => ['portfolio_id', 'portfolio_name', 'mandate'],
            'keyField'   => 'portfolio_id',
            'valueField' => 'portfolio_name',
            'groupField' => 'mandate',
            'order'      => 'mandate'
        ])->toArray();

        $templatesTable = $this->getTableLocator()->get('Damsv2.Template');

        $templates = $templatesTable->find('list', [
            'keyField'   => 'template_id',
            'valueField' => 'name',
            'conditions' => ['template_type_id IN ' => [2, 3]]
        ])->toArray();

        $conditions = [
            'Report.template_id IN'    => array_keys($templates),
            'Report.bulk'              => 0,
            'Report.status_id NOT IN ' => [8, 9, 3, 11]
        ];

        if (!empty($this->request->getData('product_id'))) {
            $portfolios = $portfolioTable->find('list', ['conditions' => ['Portfolio.product_id' => $this->request->getData('product_id')], 'fields' => ['portfolio_id', 'portfolio_name', 'Product.name'], 'recursive' => 0]);
        }

        $reportTable = $this->getTableLocator()->get('Damsv2.Report');

        if (!empty($this->request->getData('Portfolio.portfolio_id'))) {

            $conditions_latest = Helpers::arrayPushAssoc($conditions, 'Report.portfolio_id', $this->request->getData('Portfolio.portfolio_id'));
            $latest_report = $reportTable->find('all', [
                'conditions' => $conditions_latest,
                'order'      => 'Report.report_id DESC'
            ])->first();

            if (empty($latest_report)) {
                $conditions = Helpers::arrayPushAssoc($conditions, 'Report.portfolio_id', $this->request->getData('Portfolio.portfolio_id'));
            } else {
                $conditions = Helpers::arrayPushAssoc($conditions, 'Report.portfolio_id', $this->request->getData('Portfolio.portfolio_id'));
                $conditions = Helpers::arrayPushAssoc($conditions, 'Report.report_id', $latest_report->report_id);
            }
        } else {
            $conditions_latest = $conditions;
            $latest_reports = $reportTable->find('list', ['conditions' => $conditions_latest, 'group' => ['Report.portfolio_id'], 'order' => 'Report.report_id DESC', 'fields' => ['Report.report_id'], 'recursive' => -1])->toArray();

            $conditions = $conditions + [
                'Report.report_id IN' => array_keys($latest_reports),
            ];
        }

        $query = $reportTable->find('all', [
            'contain'    => ['Portfolio', 'Template', 'Status', 'Portfolio.Product'],
            'conditions' => [$conditions]
        ]);

        $reports = $this->paginate($query);
        $this->set(compact('reports', 'products', 'portfolios'));
    }

    function deleteReport($report_id = null)
    {
        if (empty($report_id)) {
            throw new NotFoundException;
        }
        $this->loadModel('Damsv2.Report');
        $report = $this->Report->get($report_id, [
            'contain' => ['Portfolio', 'Template', 'Status'],
        ]);

        $this->set('report', $report);

        if ($report->report_type == 'closure') {
            $this->Flash->warning("This is a closure report !!");
        }
        //$this->set('report_template_type', $report_template_type);
        // if is clawback: error message + cannot delete 
        $is_clawback = false;
        $e_sheet_trn = $this->Spreadsheet->getExcludedTransaction($report_id);

        error_log("file e sheet : " . json_encode($e_sheet_trn));
        if ($e_sheet_trn['count'] > 0) {
            $this->loadModel('Damsv2.Transactions');
            $transactions = $this->Transactions->find('all', [
                'fields' => ['transaction_id', 'fiscal_number', 'transaction_reference', 'exclusion_flag'],
                'conditions' => [
                    //'Transaction.report_id' => $report_id,
                    'fiscal_number in'         => $e_sheet_trn['fiscal_number'],
                    'transaction_reference in' => $e_sheet_trn['transaction_reference'],
                    'exclusion_flag IS NOT NULL',
                ],
            ]);
            if ($transactions->count() > 0) {
                $is_clawback = true;
            }
        }
        $this->set('is_clawback', $is_clawback);

        $closure_same_period = false;
        $conditions_closure_same_period = [
            'Report.report_type'    => 'closure',
            'Report.period_quarter' => $report->period_quarter,
            'Report.period_year'    => $report->period_year,
            'Report.portfolio_id'   => $report->portfolio_id,
            'Report.template_id'    => $report->template_id,
        ];
        $closure_same_period_list = $this->Report->find('all', ['conditions' => $conditions_closure_same_period])->first();
        $closure_same_period = !empty($closure_same_period_list);
        if (($report->report_type == 'regular') && ($closure_same_period)) {
            $this->Flash->error("Closure report exists for the same period!!");
        }
        if (!empty($this->request->getData('Report.report_id'))) {
            if ($report->report_type == 'closure') {
                $this->Flash->error("Closure report cannot be deleted and require intervention in the database!!");
            } elseif (($report->report_type == 'regular') && ($closure_same_period)) {
                $this->Flash->error("Closure report exists for the same period!!");
            } else {
                $report_id = intval($this->request->getData('Report.report_id'));
                //$deleted = $this->Report->Delete($this->request->getData('Report.report_id'), false);
                //SAS script
                $sas_params = ['report_id' => $report_id, 'user_id' => $this->userIdentity()->get('id')]; //$this->UserAuth->getUserId()];
                $sasResult = $this->SAS->curl(
                    "help_deletion.sas",
                    $sas_params,
                    false,
                    false
                );
                //                $deleted = ($sasResult != "<span id='sasres'><h3>This request completed with errors.</h3></span>");
                $deleted = (strpos($sasResult, "This request completed with errors") !== false);
                if ($deleted) {
                    //clean cache
                    @unlink('/var/www/html/data/damsv2/reports/eif_import_file_running_report_' . $report_id);
                    @unlink('/var/www/html/data/damsv2/reports/eif_inclusion_validation_' . $report_id);
                    @unlink('/var/www/html/data/damsv2/reports/eif_inclusion_validation_report_' . $report_id);
                    @unlink('/var/www/html/data/damsv2/reports/eif_inclusion_validation_report_' . $report_id . '.pdf');
                    @unlink('/var/www/html/data/damsv2/reports/eif_inclusion_validation_report_apv_breakdown_' . $report_id);
                    @unlink('/var/www/html/data/damsv2/waiver_reasons/draft/transactions_exemption_' . $report_id . '.xlsx');
                    @unlink('/var/www/html/data/damsv2/waiver_reasons/draft/subtransactions_exemption_' . $report_id . '.xlsx');
                    @unlink('/var/www/html/data/damsv2/waiver_reasons/draft/sme_exemption_' . $report_id . '.xlsx');
                    @unlink('/var/www/html/data/damsv2/waiver_reasons/validated/transactions_exemption_' . $report_id . '.xlsx');
                    @unlink('/var/www/html/data/damsv2/waiver_reasons/validated/subtransactions_exemption_' . $report_id . '.xlsx');
                    @unlink('/var/www/html/data/damsv2/waiver_reasons/validated/sme_exemption_' . $report_id . '.xlsx');

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
                    $this->logDams('Report deleted: ' . json_encode($log_info), 'dams', 'Delete report');
                    $msg = "The report " . $this->request->getData('Report.report_id') . " has been deleted.";
                    $this->Flash->success($msg);
                    $this->redirect(['action' => 'home']);
                } else {
                    $msg = "The report " . $this->request->getData('Report.report_id') . " could not be deleted.";
                    $msg .= $sasResult;
                    $this->Flash->error($msg, ['escape' => false]);
                }
            }
        }
    }

    function deletePdlrReport($report_id = null)
    {
        if (empty($report_id)) {
            throw new NotFoundException;
        }
        $this->loadModel('Damsv2.Report');
        $report = $this->Report->get($report_id, [
            'contain' => ['Portfolio', 'Template', 'Status'],
        ]);

        $this->set('report', $report);
        if ($report->template->template_type_id == 2) {
            $flow = 'PD';
        }
        if ($report->template->template_type_id == 3) {
            $flow = 'LR';
        }

        $pdlr_target_status = [10 => 'Validated', 12 => 'Invoiced', 100 => 'Deleted'];
        switch ($report->status_id) {
            case 10: //validated
                $pdlr_target_status = [100 => 'Deleted'];
                break;
            case 12: //invoiced
                $pdlr_target_status = [100 => 'Deleted'];
                break;
            case 14: //paid
            case 16: //paid
                $pdlr_target_status = [12 => 'Invoiced', 10 => 'Validated', 100 => 'Deleted'];
                break;
        }

        // if is clawback: error message + cannot delete
        $is_clawback = false;
        if ($report->clawback == 'Y') {
            $is_clawback = true;
        }
        $this->set('is_clawback', $is_clawback);
        //removing current status from list
        unset($pdlr_target_status[$report->status_id]);
        $this->set('pdlr_target_status', $pdlr_target_status);

        if (!empty($this->request->getData('Report.report_id'))) {
            $status_target = $this->request->getData('Report.status_target');
            $status = $report->status_id;

            //count report with same invoice_id, if > 1 => error
            $invoice_id = !empty($report->invoice_id) ? $report->invoice_id : 0;
            $invoice_count = $this->Report->find('all', ['conditions' => ['Report.invoice_id' => $invoice_id]]);

            if (in_array($status, [12, 14]) && ($status_target == 10) && ($invoice_count->count() > 1)) { //setting to validated
                $this->Flash->error("The report is linked to an invoice containing multiple reports.");
                $this->redirect(['action' => 'pdlr-list']);
            } else {
                $report_id = intval($this->request->getData('Report.report_id'));
                //SAS script
                $sas_params = ['report_id' => $report_id, 'user_id' => $this->userIdentity()->get('id')]; //$this->UserAuth->getUserId()];
                $capped = $this->request->getData('Report.capped');
                $sas_params = $sas_params + ['capped' => $capped, 'status_target' => $status_target];
                $sasResult = $this->SAS->curl(
                    "help_del_PDLR.sas",
                    $sas_params,
                    false,
                    false
                );

                $dom = HtmlDomParser::str_get_html($sasResult);

                $warning = $dom->find('#res'); //if found, we should show a specific error message
                $error_msg = false;
                foreach ($warning as $m) {
                    $error_msg = true;
                }
                $deleted = ($sasResult != "<span id='sasres'><h3>This request completed with errors.</h3></span>");
                if ($deleted & (!$error_msg)) {
                    // clear cache
                    @unlink('/var/www/html/data/damsv2/reports/eif_import_file_running_report_' . $report_id);
                    @unlink('/var/www/html/data/damsv2/reports/report_invoice_add_' . $report_id . '.html');
                    @unlink('/var/www/html/data/damsv2/reports/report_invoice_add_' . $report_id . '.pdf');
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
                    $this->logDams('Report deleted: ' . json_encode($log_info), 'dams', 'Delete report');
                    $msg = '';
                    switch ($status_target) {
                        case 10:
                            $msg = "The report " . $this->request->getData('Report.report_id') . " has been set back to Validated.";
                            break;
                        case 12:
                            $msg = "The report " . $this->request->getData('Report.report_id') . " has been set back to Invoiced.";
                            break;
                        case 100:
                            $msg = "The report " . $this->request->getData('Report.report_id') . " has been deleted.";
                            break;
                    }
                    $this->Flash->success($msg);
                    $this->redirect(['action' => 'pdlr-list']);
                } else {
                    $msg = "The report " . $this->request->getData('Report.report_id') . " could not be updated.";
                    if ($error_msg) {
                        $msg = 'PD report cannot be deleted because there is an LR report uploaded after this PD.';
                    }
                    $this->Flash->error($msg);
                }
            }
        }
    }
}
