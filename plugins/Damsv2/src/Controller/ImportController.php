<?php

declare(strict_types=1);

namespace Damsv2\Controller;

use Cake\Event\EventInterface;
use KubAT\PhpSimple\HtmlDomParser;
use Cake\Datasource\ConnectionManager;
//use App\Lib\Helpers;
//use Cake\Cache\Cache;
//use Cake\I18n\Date;
use App\Lib\DownloadLib;
use Laminas\Diactoros;

/**
 * Import Controller
 *
 * @method \App\Model\Entity\Import[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ImportController extends AppController
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

    public function index()
    {
        $connection = ConnectionManager::get('default');
        $ajaxControler = new AjaxController();
        $this->loadModel('Damsv2.Product');
        $products = $this->Product->getProducts();
        $this->loadModel('Damsv2.Portfolio');
        $this->loadModel('Damsv2.Template');

        $portfolios = $this->Portfolio->find('list', [
                    'contain'    => ['Product'],
                    'fields'     => ['Product.name', 'Portfolio.portfolio_name', 'Portfolio.portfolio_id'],
                    'keyField'   => 'portfolio_id',
                    'valueField' => 'portfolio_name',
                    'groupField' => 'product.name',
                    'order'      => ['Product.name', 'Portfolio.portfolio_name'],
                    'conditions' => ['Portfolio.product_id NOT IN' => [22, 23]]
                ])->toArray();

        $umbrellas = $connection->execute('SELECT * FROM umbrella_portfolio u, product pd, portfolio p WHERE pd.product_id=u.product_id AND u.iqid=p.iqid')->fetchAll('assoc');

        foreach ($umbrellas as $umbrella) {
            $portfolios[$umbrella['name']]['u_' . $umbrella["umbrella_portfolio_id"]] = $umbrella["umbrella_portfolio_name"];
            unset($portfolios[$umbrella['name']][$umbrella["portfolio_id"]]);
            asort($portfolios[$umbrella['name']]);
        }
        $this->loadModel('Damsv2.VUser');
        $owners = $this->VUser->find('list', [
                    'fields'     => ['first_name', 'last_name', 'id'],
                    'keyField'   => 'id',
                    'valueField' => ['full_name'],
                    'conditions' => ['id >' => 1],
                    'order'      => ['last_name', 'first_name']
                ])->toArray();
        ;

        $owner = $this->userIdentity()->get('id'); //$this->Flash->read('UserAuth.User.id');
        //$templates = $types = $year = [];


        $types = ['BK' => 'Business keys', 'DATA' => 'Data'];
        $templates = [
            "CR"  => "sme",
            "A1"  => "sme",
            "A2"  => "transactions",
            "A3"  => "guarantees",
            "A4"  => "micro_credit_nb",
            "B"   => "included_transactions",
            "I1"  => "re_performing_start",
            "I2"  => "re_performing_end",
            "D"   => "expired_transactions",
            "E"   => "excluded_transactions",
            "H"   => "transactions",
            "PD"  => "pdlr_transactions",
            "LR"  => "pdlr_transactions",
            "IR"  => "initial_rating",
            "EP"  => "expired_to_performing",
            "EP"  => "expired_to_performing",
            "C"   => "defaulted",
            "S1"  => "impact_data",
            "S2"  => "impact_data_s2",
            "R"   => "recoveries",
            "GGE" => "transactions",
            "IR"  => "transactions",
            "SU"  => "sampling_information",
            "TRS" => "transactions",
            "MS"  => "pdlr_transactions",
            "EP"  => "Expired_Performing",
            "TBE" => "To be Excluded",
            "A21" => 'subtransactions',
            "B1"  => 'included_subtransactions',
        ];
        $current_year = date('Y') + 2;
        $range = range(2014, $current_year);
        $year = array_combine($range, $range);

        $this->set(compact('products', 'portfolios', 'owners', 'owner', 'templates', 'types', 'year'));

        // FORM PROCCESSING PART
        if ($this->request->is('post')) {
            $Post_Data = $this->request->getData();

            //for when errors on page, to keep selected values
            $portfolio_id = $Post_Data['Import']['portfolio_id'];


            if (strpos($portfolio_id, 'u_') !== false) {
                $umbrella_id = substr($portfolio_id, 2);
                $portfolio_id = $ajaxControler->getPortfolioFromUmbrellaId($umbrella_id);
                $templates = $this->Template->getSheetsByUmbrellaIdForEdit($Post_Data['Import']['portfolio_id']);
            } else {
                $templates = $this->Template->getSheetsByPortfolioIdForEdit($portfolio_id);
            }

            $years = $connection->query("select distinct period_year from report where period_year is not null order by period_year desc")->fetchAll('assoc');
            $year = [];
            foreach ($years as $y) {
                $yy = $y['period_year'];
                $year[$yy] = $yy;
            }

            $types = $ajaxControler->getTypeByPortfolio_($portfolio_id);

            // group retrieving  // http://vmu-sas-01:8080/browse/PLATFORM-259
//            $groups = CakeSession::read('UserAuth.UserGroups');
//            if (!is_array($groups))
//                $groups = [$groups];
//            if (!empty($groups))
//                foreach ($groups as $group) {
//                    $groupsnames[] = $group['alias_name'];
//                }
//            if (in_array('ReadOnlyDams', $groupsnames)) {
//                $this->Flash->error("You are currently in a read only profile, this functionality is disabled");
//                $this->redirect($this->referer());
//            }


            $is_umbrella = false;
            if (strpos($portfolio_id, 'u_') !== false) {
                $is_umbrella = true;
                $portfolio_id = substr($portfolio_id, 2);
                $portfolio_id = $ajaxControler->getPortfolioFromUmbrellaId($portfolio_id);
            } else {
                //if run correction
                if ($ajaxControler->isUmbrella($portfolio_id)) {
                    $is_umbrella = true;
                }
            }

            if (!$is_umbrella) {
                if (!$this->Portfolio->isEditable($portfolio_id)) {
                    $this->Flash->error("An inclusion report in reconciliation phase was detected for the selected portfolio. Please save or reject the inclusion report before proceeding with the editing.");
                    return $this->redirect($this->referer());
                }
            }

            if (!$is_umbrella) {
                if (!$this->Portfolio->isEditableDraft($portfolio_id)) {
                    $this->Flash->error("An inclusion report in Draft included stage was detected for the selected portfolio. Please validate the draft inclusion report before proceeding with the editing.");
                    return $this->redirect($this->referer());
                }
            }
            $report_id = -1;
            $type = $Post_Data['Import']['type'];
            $sheet = $Post_Data['Import']['sheet'];

            switch ($sheet) {
                case 'A3':
                case 'B':
                    if ($type == 'BK') {
                        $this->Flash->error("Only data modification is available for $sheet");
                        return $this->redirect($this->referer());
                    }
            }
            $this->loadModel('Damsv2.Report');
            $report = $this->Report->newEmptyEntity();
            $reportData = $this->Report->patchEntity($report, $this->request->getData());

            $data_POST = $this->request->getData();
            if (!empty($data_POST['Import']['file'])) {
                $file = $data_POST['Import']['file'];
            } elseif (!empty($data_POST['Import']['filename'])) {
                $file_name = $data_POST['Import']['filename'];
                $file = '/var/www/html' . DS . 'data' . DS . 'damsv2' . DS . 'bulk' . DS . 'in' . DS . $file_name;
                $file_factory = new \Laminas\Diactoros\UploadedFileFactory();
                $file_stream_factory = new \Laminas\Diactoros\StreamFactory();
                $file_stream = $file_stream_factory->createStreamFromFile($file);
                $file = $file_factory->createUploadedFile($file_stream, filesize($file), 0, $file_name);
            }

            $file_name = $file->getClientFilename();
            //$ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $ext = 'xlsx'; //always xlsx now
            $file_renamed = implode('_', ['bulk', $portfolio_id, $type, $sheet, date("Ymdhi")]) . '.' . $ext;
            //$file['name'] = implode('_', array('bulk', $portfolio_id, $type, $sheet, date("Ymdhi"))) . '.' . $ext;


            $fileMovingPath = '/var/www/html' . DS . 'data' . DS . 'damsv2' . DS . 'bulk' . DS . 'in' . DS . $file_renamed;

            if ($this->File->checkFileInForm($file, $fileMovingPath)) {

                error_log("checkFileInForm line " . __LINE__);
                //retrieve info from creation xlsx file
                $infoFile = $this->Spreadsheet->createXlsxfile($file_renamed, $fileMovingPath);

                if (!$this->Spreadsheet->noError($infoFile['errors'])) {
                    $errors = $this->Spreadsheet->showError($infoFile['errors']);
                    $this->Flash->error($errors, ['escape' => false]);
                    $this->redirect($this->referer());
                    exit();
                }
                error_log("checkFileInForm line " . __LINE__);
                $filename = $infoFile['name'];
                $fileMovingPath = $infoFile['path'];

                if ($type === 'BK') {
                    error_log("checkFileInForm line " . __LINE__);
                    $errors = $this->Spreadsheet->checkSheetBulkBK($Post_Data, $file, $fileMovingPath);
                    if ($this->Spreadsheet->noError($errors['errors'])) {
                        error_log("checkFileInForm line " . __LINE__);
                        $sasResult = $this->SAS->curl(
                                'bk_import.sas', [
                            'entity'       => $Post_Data['Import']['sheet'],
                            'portfolio_id' => $portfolio_id,
                            'filename'     => $filename
                                ],
                                false,
                                false
                        );
                        error_log("checkFileInForm line " . __LINE__);
                        $this->logDams('BK edit portfolio ' . $portfolio_id . ' sheet ' . $Post_Data['Import']['sheet'], 'dams', 'Edit');
                        $dom = HtmlDomParser::str_get_html($sasResult);

                        $table = $dom->find('table');
                        $result = '';
                        $valid = $dom->find('#valid');
                        $warning = $dom->find('#warning');
                        $to_be_excluded_markup = $dom->find('#to_be_excluded');

                        $save = 0;
                        $msgWarning = 0;
                        $to_be_excluded = 0; // to_be_excluded succcessfully saved
                        foreach ($valid as $v) {
                            $val = trim($v->innertext);
                            if ($val == '1')
                                $save = 1;
                        }
                        foreach ($to_be_excluded_markup as $tbe) {
                            $val_tbe = trim($tbe->innertext);
                            if ($val_tbe == '1')
                                $to_be_excluded = 1;
                        }

                        foreach ($warning as $m) {
                            $val = trim($m->innertext);

                            if ($val == '-1')
                                $msgWarning = -1;
                            if ($val == '1')
                                $msgWarning = 1;
                            if ($val == '2')
                                $msgWarning = 2;
                        }

                        foreach ($table as $t) {
                            if ($t->id == 'sasres') {
                                $t->class = 'table table-bordered table-striped';
                                $t->frame = '';
                                $result .= $t->outertext;
                            }
                        }

                        if ($result) {
                            $multi_sme = (($this->request->getData('Import.sheet') == 'A1') && ($save != 0));
                            $result = DownloadLib::change_downloadable_links($result, 'bulk/out');
                            $this->set('table', $result);
                            $this->set('type', $type);
                            $this->set('sheet', $sheet);
                            $this->set('filename', $filename);
                            $this->set('report_id', 0);
                            $this->set('portfolio_id', $portfolio_id);
                            $this->set('save', $save);
                            $this->set('to_be_excluded', $to_be_excluded);
                            $this->set('bkedit', 1);
                            $this->set('msgWarning', $msgWarning);
                            $this->set('multi_sme', $multi_sme);
                        } else {
                            $this->Flash->error("An error occurred. If the problem persists, please contact EIF SAS Support.");
                            $this->redirect($this->referer());
                        }
                    } else {
                        $msg = $this->Spreadsheet->showError($errors['errors']);

                        $this->Flash->error($msg, ['escape' => false]);
                        $this->redirect($this->referer());
                    }
                } else {
                    if (!$is_umbrella) {

                        $portfolio_br = $this->Portfolio->find('all', ['condition' => ['portfolio_id' => $portfolio_id]])->first();

                        $report_br = ['Report'    => ['portfolio_id' => $portfolio_id],
                            'Template'  => ['template_type_id' => 1], //edit = inclusion
                            'Portfolio' => ['product_id' => $portfolio_br->product_id, 'mandate' => $portfolio_br->mandate],
                        ];
                        $this->loadModel('Damsv2.Rules');
                        $brules_valid = $this->Rules->brulesValid($report_br);
                        if (!$brules_valid) {
                            $this->Flash->error("At least 1 consistency rule and 1 eligibility rule applicable to this portfolio are required to process the report");
                            return $this->redirect($this->referer());
                        }
                    }
                    //$this->request->getData('Template.id') = null;

                    $default_template_type_id = 5;
                    if ($sheet == 'H') {
                        //$this->request->getData('Import.sheet') = $sheet = 'H';
                        $default_template_type_id = 6;
                    }
                    if ($sheet == 'GGE') {
                        //$this->request->getData('Import.sheet') = $sheet = 'GGE';
                        $default_template_type_id = 7;
                    }
                    if ($sheet == 'RATING') {
                        //$this->request->getData('Import.sheet') = $sheet = 'RATING';
                        $default_template_type_id = 8;
                    }
                    if ($sheet == 'CR') {
                        //$this->request->getData('Import.sheet') = $sheet;
                        $default_template_type_id = 8;
                    }
                    if ($sheet == 'IR') {
                        //$this->request->getData('Import.sheet') = $sheet;
                        $default_template_type_id = 9;
                    }
                    if ($sheet == 'EP') {
                        //$this->request->getData('Import.sheet') = $sheet;
                        $default_template_type_id = 13;
                    }
                    if ($sheet == 'BDS') {
                        //$this->request->getData('Import.sheet') = $sheet;
                        $default_template_type_id = 15;
                    }
                    if ($sheet == 'TBE') {
                        //$this->request->getData('Import.sheet') = $sheet;
                        $default_template_type_id = 16;
                    }


                    if (empty($Post_Data['Import']['template_id'])) {
                        $templates = $this->Template->find('all', [
                            'conditions' => [
                                'Template.template_type_id' => $default_template_type_id
                            ]
                        ]);
                        $template = $templates->matching('Portfolio', function ($q) use ($portfolio_id) {
                                    return $q->where(['Portfolio.portfolio_id' => $portfolio_id]);
                                })->first();

                        $Post_Data['Template']['id'] = $template['template_id'];
                    } else {
                        $Post_Data['Template']['id'] = $Post_Data['Import']['template_id'];
                    }
                    $result = '';
                    if ($Post_Data['Template']['id']) {
                        $errors = $this->Spreadsheet->checkSheetBulkData($Post_Data, $file, $fileMovingPath);
                        $report = null;
                        if (empty($Post_Data['Import']['report_id'])) {
                            $report = $this->Report->find('all', [
                                        'conditions' => [
                                            'Report.bulk'         => 1,
                                            'Report.portfolio_id' => $portfolio_id
                                        ]
                                    ])->first();
                        } else {
                            $report = $this->Report->find('all', ['conditions' => ['Report.id' => $report_id]])->first();
                        }


                        if ($this->Spreadsheet->noError($errors)) {
                            $version_number = 1;
                            if (!empty($report)) {
                                // $this->Report->id = $report['Report']['report_id'];
                                //$version_number = $report['Report']['version_number'] + 1;
                                $version_number = $report->version_number + 1;
                            } else {
                                $report_data = ['Report' => [
                                        'portfolio_id'   => $portfolio_id,
                                        'template_id'    => $Post_Data['Template']['id'],
                                        'status_id'      => 7,
                                        'owner'          => $owner,
                                        'version_number' => $version_number,
                                        'header'         => 1,
                                        'sheets'         => $sheet,
                                        'input_filename' => $file_renamed,
                                        'visible'        => 0,
                                        'bulk'           => 1,
                                        'report_type'    => 'regular',
                                ]];
                                $report = $this->Report->newEntity($report_data);
                            }

                            //period_dates
                            /* $products = $this->Product->find('list', [
                              'fields'    => ['Product.product_id', 'Product.name'),
                              'recursive' => -1
                              )); */
                            //$year = $this->request->getData('Import']['year'];
                            //$period = $this->request->getData('Import']['quarter'];
                            $portfolio_id = $Post_Data['Import']['portfolio_id'];
                            if (strpos($portfolio_id, 'u_') !== false) {
                                $portfolio_id = substr($portfolio_id, 2);
                                $portfolio_id = $ajax->getPortfolioFromUmbrellaId($portfolio_id);
                            }

                            //$dates = $this->Portfolio->getDatesFromPeriod($period, $year, $portfolio_id);

                            $report->report_name = implode('_', [$portfolio_id, 'bulkupload', $type, $sheet, date("Ymdhi")]);
                            $report->portfolio_id = $portfolio_id;
                            $report->template_id = $Post_Data['Template']['id'];
                            $report->status_id = 7;
                            $report->owner = $owner;
                            $report->version_number = $version_number;
                            $report->header = 1;
                            $report->sheets = $sheet;
                            $report->input_filename = $file_renamed;
                            $report->visible = 0;
                            $report->bulk = 1;
                            $report->report_type = 'regular';

                            //report of the umbrella/portfolio

                            if ((($this->request->getData('Import.sheet') == 'B') || ($this->request->getData('Import.sheet') == 'B1') ) && (!empty($this->request->getData('Import.year')) & !empty($this->request->getData('Import.quarter')))) {
                                $year = $this->request->getData('Import.year');
                                $period = $this->request->getData('Import.quarter');
                                //$portfolio_id = $this->request->getData('Import.portfolio_id');

                                $dates = $this->Portfolio->getDatesFromPeriod($period, $year, $portfolio_id);

                                $report->period_start_date = $dates['period_start'];
                                $report->period_end_date = $dates['period_end'];
                            }

                            $this->Report->save($report);
                            $count = 1;
                            //@$count = count($this->request->getData('Import.sheet'));
                            $sasResult = $this->SAS->curl(
                                    'import_file_edit.sas', [
                                'report_id'        => $report->report_id,
                                'template_type_id' => $default_template_type_id,
                                'correction'       => $this->request->getData('Import.correction'),
                                'user_id'          => $owner,
                                'nb_sheet_name'    => $count,
                                'save'             => 0,
                                    ],
                                    false,
                                    false
                            );
                            $log_info = [
                                'report_id'    => $report->report_id,
                                'portfolio_id' => $portfolio_id,
                                'sheet'        => $sheet,
                                'version'      => $version_number,
                            ];
                            $this->logDams('Report edited: ' . json_encode($log_info), 'dams', 'Edit');
                            $result = '';
                            // ini_set('xdebug.max_nesting_level', 100000);

                            $dom = HtmlDomParser::str_get_html($sasResult);


                            $table = $dom->find('table');
                            $valid = $dom->find('#valid');
                            $expired_to_performing = $dom->find('#expired_to_performing');
                            $bds_support_paid = $dom->find('#bds_support_paid');
                            $warning = $dom->find('#warning');
                            $downloadAll = $dom->find('#download_all');
                            $success = $dom->find('#sasres tr td');

                            foreach ($table as $t) {
                                if ($t->id == 'sasres') {
                                    $t->class = 'table table-bordered table-striped';
                                    $t->frame = '';
                                    $result .= $t->outertext;
                                }
                            }

                            $expired_to_performing_val = 0;
                            foreach ($expired_to_performing as $v) {
                                $expired_to_performing_val = trim($v->innertext);
                            }
                            $bds_support_paid_val = 0;
                            foreach ($bds_support_paid as $v) {
                                $bds_support_paid_val = trim($v->innertext);
                            }

                            $save = 0;
                            foreach ($valid as $v) {
                                $val = trim($v->innertext);
                                if ($val == '1')
                                    $save = 1;
                            }

                            $msgWarning = 0;
                            foreach ($warning as $m) {
                                $val = trim($m->innertext);

                                if ($val == '-1')
                                    $msgWarning = -1;
                                if ($val == '1')
                                    $msgWarning = 1;
                                if ($val == '2')
                                    $msgWarning = 2;
                            }

                            $downloadAllButton = 0;
                            foreach ($downloadAll as $da) {
                                $val = trim($da->innertext);
                                if ($val == '1')
                                    $downloadAllButton = 1;
                            }
                            $success_val = 0;
                            foreach ($success as $succ) {
                                $val = trim($succ->innertext);
                                if ($val == 'Data has been successfully updated in the database!')
                                    $success_val = 1;
                            }

                            if (!empty($result)) {
                                $result = DownloadLib::change_downloadable_links($result, 'bulk/out');
                                $result = str_replace(' Split files: ', '<span id="split_files">Split files:</span>', $result);
                                $this->set('table', $result);
                                $this->set('type', $type);
                                $this->set('sheet', $sheet);
                                $this->set('portfolio_id', $portfolio_id);
                                $this->set('filename', $file_renamed);
                                $this->set('report_id', $report->report_id);
                                $this->set('save', $save);
                                $this->set('expired_to_performing_val', $expired_to_performing_val);
                                $this->set('bds_support_paid_val', $bds_support_paid_val);
                                $this->set('msgWarning', $msgWarning);
                                $this->set('downloadAllButton', $downloadAllButton);
                                $this->set('success_val', $success_val);
                                $this->set('version_number', $version_number);
                            } else {
                                $this->Flash->error("An error occurred. If the problem persists, please contact EIF SAS Support.");
                                error_log("empty results on edit : " . $sasResult);
                                $this->redirect($this->referer());
                            }
                        } else {
                            $msg = $this->Spreadsheet->showError($errors);
                            $this->Flash->error($msg, ['escape' => false]);
                            //$this->redirect($this->request->referer());
                        }
                    } else {
                        $this->Flash->error("There is no attached template for the selected bulk upload");
                        $this->redirect($this->request->referer());
                    }
                }
                // Store in Session
            }
        }
    }

    public function editAction()
    {
        $data_POST = $this->request->getData('Import');
        //debug($data_POST);
        $report_id = $data_POST['report_id'];
        $portfolio_id = $data_POST['portfolio_id'];
        $sheet = $data_POST['sheet'];
        $type = $data_POST['type'];
        $this->loadModel('Damsv2.Report');
        $report = $this->Report->find('all', ['conditions' => ['report_id' => $report_id]])->first();
        if (empty($report)) {
            $this->Flash->error('No load action for Business Key Edit');
            $this->redirect($this->referer());
        }

        $this->set(compact('report', 'report_id', 'portfolio_id', 'sheet', 'type'));
    }

    public function store()
    {
        $valid = false;
        $warning = false;
        $data_POST = $this->request->getData();
        if (!empty($data_POST)) {

            // group retrieving  // http://vmu-sas-01:8080/browse/PLATFORM-259
            /* $groups = CakeSession::read('UserAuth.UserGroups');
              if(!is_array($groups)) $groups = array($groups);
              if(!empty($groups)) foreach($groups as $group){
              $groupsnames[] = $group['alias_name'];
              }
              if (in_array('ReadOnlyDams', $groupsnames))
              {
              $this->Session->setFlash("You are currently in a read only profile, this functionality is disabled", "flash/error");
              $this->redirect($this->referer());
              } */

            $type = $data_POST['Import']['type'];
            $sheet = $data_POST['Import']['sheet'];
            $filename = $data_POST['Import']['filename'];
            $report_id = $data_POST['Import']['report_id'];
            $portfolio_id = $data_POST['Import']['portfolio_id'];
            $user_id = $this->Authentication->getIdentity()->get('id');
            $sasResult = $script = '';
            if ($type == 'BK') {
                $script = 'bk_store.sas';
                $sasResult = $this->SAS->curl(
                        'bk_store.sas', array(
                    'filename'     => $filename,
                    'entity'       => $sheet,
                    'user_id'      => $user_id,
                    'portfolio_id' => $portfolio_id
                        ),
                        false,
                        false
                );
            } else {
                $script = 'inclusion_store.sas';
                $sasResult = $this->SAS->curl(
                        'inclusion_store.sas', array(
                    'report_id'        => $report_id,
                    'template_type_id' => 5,
                    'user_id'          => $user_id,
                    'save'             => 1,
                    'correction'       => 0
                        ),
                        false,
                        false
                );
            }

            $dom = HtmlDomParser::str_get_html($sasResult);

            $validids = $dom->find('#valid_store');
            foreach ($validids as $v) {
                $val = trim($v->innertext);
                if ($val == '1') {
                    $valid = true;
                }
            }

            $warnings = $dom->find('#warning');
            foreach ($warnings as $w) {
                $warning = trim($w->innertext);
            }
        }
        $this->set('valid', $valid);
        $this->set('warning', $warning);
    }

}
