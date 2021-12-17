<?php

declare(strict_types=1);

namespace Damsv2\Controller;

use Cake\Event\EventInterface;
use KubAT\PhpSimple\HtmlDomParser;
//use Cake\Datasource\ConnectionManager;
//use App\Lib\Helpers;
//use Cake\Cache\Cache;
//use Cake\I18n\Date;
use App\Lib\DownloadLib;

/**
 * Report Controller
 *
 * @property \App\Model\Table\ReportTable $Report
 * @method \App\Model\Entity\Report[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AnalyticsController extends AppController
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
        'limit' => 25,
        'order' => [
            'Report.report_id' => 'desc'
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

    public function forecastReports()
    {
        //load custom reports layout
        $this->viewBuilder()->setLayout('reports');
    }

    public function dataExtractsReports()
    {
        $this->viewBuilder()->setLayout('reports');
    }

    public function analyticsReports()
    {
        $this->viewBuilder()->setLayout('reports');
    }

    public function operationsReports()
    {
        $this->viewBuilder()->setLayout('reports');
    }

    public function faq()
    {
        $this->viewBuilder()->setLayout('reports');
    }

    public function activePortfolioManagement()
    {
        //$mandates_list = $this->Report->query("SELECT DISTINCT mandate FROM portfolio pf, product pd WHERE pd.product_id NOT IN (22,23) AND mandate <> '' and pf.product_id=pd.product_id AND pd.product_type='guarantee' ORDER BY mandate ASC");
        $Portfolio = $this->getTableLocator()->get('Damsv2.Portfolio');
        $mandates_list = $Portfolio->find()->select(['mandate'])->where(['Portfolio.product_id NOT IN (22,23)', 'mandate IS NOT NULL']);
        $mandates_list->matching('Product', function ($q) {
            return $q->where(['product_type' => 'guarantee']);
        })->order(['mandate' => 'ASC'])->all();

        $mandates = array();
        foreach ($mandates_list as $mandate) {
            $mandates[$mandate->mandate] = $mandate->mandate;
        }

        $this->set('mandates', $mandates);
        if ($this->request->is('post')) {
            $POST = $this->request->getData();
            if (empty($POST['Portfolio']['Mandate'])) {
                $this->Flash->error("Please choose one mandate.");
            } else {
                $mm = $POST['Portfolio']['Mandate'];

                $mm = trim($mm, ' ,');

                $user_id = $this->Authentication->getIdentity()->get('id');
                $params = array('mandate' => $mm, 'user_id' => $user_id);
                $sasResult = $this->SAS->curl(
                    "ActivePortfolio_Man.sas",
                    $params,
                    false,
                    false
                );
                $sasResult = preg_replace('/\\s+/', ' ', $sasResult);
                $sasResult = mb_convert_encoding($sasResult, "UTF-8"); // to remove \ufeff
                try {
                    $dom = HtmlDomParser::str_get_html($sasResult);
                    if ($dom) {
                        $res = $dom->find('#sasres');
                        foreach ($res as $r) {
                            $links = $r->find('a');
                            foreach ($links as $l) {
                                $filepath = trim($l->href);
                                $filepath = str_replace('/sas/common/portfolio_analytics/', '', $filepath);
                            }
                        }
                    }
                } catch (Exception $e) {
                    error_log("simple dom error : " . $e->getMessage());
                }

                if (!empty($filepath)) {
                    $download_link = "/damsv2/ajax/download-file/" . $filepath . "/pa";
                    $this->set('download_link', $download_link);
                } else {
                    if (strpos($sasResult, "This request completed with errors.") !== false) {
                        $this->Flash->error('This request completed with errors. Please contact the SAS team');
                    } else {
                        $this->Flash->error('No data');
                    }
                }
            }
        }
    }

    public function mandatePerformanceCountry()
    {
        $ExternalSmeCount2 = $this->getTableLocator()->get('Damsv2.ExternalSmeCount2');
        $country_list = $ExternalSmeCount2->find()->select(['country'])->all();
        $countries = array();
        foreach ($country_list as $country) {
            if (!empty(trim($country->country))) {
                $countries[$country->country] = $country->country;
            }
        }
        $this->set('countries', $countries);


        if ($this->request->is('post')) {
            $POST = $this->request->getData();
            if (empty($POST['Report']['country'])) {
                $this->Flash->error("Please choose a country.");
            } else {
                $mm = $POST['Report']['country'];

                $mm = trim($mm, ' ,');

                $user_id = $this->Authentication->getIdentity()->get('id');
                $params = array('mandate' => $mm, 'user_id' => $user_id);
                $sasResult = $this->SAS->curl(
                    "country_perfomance_mandate.sas",
                    $params,
                    false,
                    false
                );
                $sasResult = preg_replace('/\\s+/', ' ', $sasResult);
                $sasResult = mb_convert_encoding($sasResult, "UTF-8"); // to remove \ufeff
                try {
                    $dom = HtmlDomParser::str_get_html($sasResult);
                    if ($dom) {
                        $res = $dom->find('#sasres');
                        foreach ($res as $r) {
                            $links = $r->find('a');
                            foreach ($links as $l) {
                                $filepath = trim($l->href);
                                $filepath = str_replace('/sas/common/portfolio_analytics/', '', $filepath);
                            }
                        }
                    }
                } catch (Exception $e) {
                    error_log("simple dom error : " . $e->getMessage());
                }

                if (!empty($filepath)) {
                    $download_link = "/damsv2/ajax/download-file/" . $filepath . "/pa";
                    $this->set('download_link', $download_link);
                } else {
                    if (strpos($sasResult, "This request completed with errors.") !== false) {
                        $this->Flash->error('This request completed with errors. Please contact the SAS team');
                    } else {
                        $this->Flash->error('No data');
                    }
                }
            }
        }
    }

    public function cumulativeKeyPortfolio()
    {
        //$mandates_list = $this->Report->query("SELECT DISTINCT mandate FROM portfolio pf, product pd WHERE pd.product_id NOT IN (22,23) AND mandate <> '' and pf.product_id=pd.product_id AND pd.product_type='guarantee' ORDER BY mandate ASC");
        $Portfolio = $this->getTableLocator()->get('Damsv2.Portfolio');
        $mandates_list = $Portfolio->find()->select(['mandate'])->where(['Portfolio.product_id NOT IN (22,23)', 'mandate IS NOT NULL'])->order(['mandate' => 'ASC'])->all();

        $mandates = array();
        foreach ($mandates_list as $mandate) {
            $mandates[$mandate->mandate] = $mandate->mandate;
        }

        $this->set('mandates', $mandates);

        if ($this->request->is('post')) {
            $POST = $this->request->getData();
            if (empty($POST['Portfolio']['Mandate'])) {
                $this->Flash->error("Please choose one or more mandate.");
            } else {
                $mm = $POST['Portfolio']['Mandate'];

                $mm = trim($mm, ' ,');

                $user_id = $this->Authentication->getIdentity()->get('id');
                $params = array('mandate' => $mm, 'user_id' => $user_id);
                $sasResult = $this->SAS->curl(
                    "portfolio_data.sas",
                    $params,
                    false,
                    false
                );
                $sasResult = preg_replace('/\\s+/', ' ', $sasResult);
                $sasResult = mb_convert_encoding($sasResult, "UTF-8"); // to remove \ufeff
                try {
                    $dom = HtmlDomParser::str_get_html($sasResult);
                    if ($dom) {
                        $res = $dom->find('#sasres');
                        foreach ($res as $r) {
                            $links = $r->find('a');
                            foreach ($links as $l) {
                                $filepath = trim($l->href);
                                $filepath = str_replace('/sas/common/portfolio_analytics/', '', $filepath);
                            }
                        }
                    }
                } catch (Exception $e) {
                    error_log("simple dom error : " . $e->getMessage());
                }

                if (!empty($filepath)) {
                    $download_link = "/damsv2/ajax/download-file/" . $filepath . "/pa";
                    $this->set('download_link', $download_link);
                } else {
                    if (strpos($sasResult, "This request completed with errors.") !== false) {
                        $this->Flash->error('This request completed with errors. Please contact the SAS team');
                    } else {
                        $this->Flash->error('No data');
                    }
                }
            }
        }
    }

    public function mainAgriStatistics()
    {
        if ($this->request->is('post')) {
            $POST = $this->request->getData();
            if (empty($POST['Report']['report_end_data'])) {
                $this->Flash->error("Please choose a date.");
            } else {
                $mm = $POST['Report']['report_end_data'];

                $mm = trim($mm, ' ,');

                $user_id = $this->Authentication->getIdentity()->get('id');
                $params = array('report_end_data' => $mm, 'user_id' => $user_id);
                $sasResult = $this->SAS->curl(
                    "Main_AGRI_Statistics.sas",
                    $params,
                    false,
                    false
                );
                $sasResult = preg_replace('/\\s+/', ' ', $sasResult);
                $sasResult = mb_convert_encoding($sasResult, "UTF-8"); // to remove \ufeff
                try {
                    $dom = HtmlDomParser::str_get_html($sasResult);
                    if ($dom) {
                        $res = $dom->find('#sasres');
                        foreach ($res as $r) {
                            $links = $r->find('a');
                            foreach ($links as $l) {
                                $filepath = trim($l->href);
                                $filepath = str_replace('/sas/common/portfolio_analytics/', '', $filepath);
                            }
                        }
                    }
                } catch (Exception $e) {
                    error_log("simple dom error : " . $e->getMessage());
                }

                if (!empty($filepath)) {
                    $download_link = "/damsv2/ajax/download-file/" . $filepath . "/pa";
                    $this->set('download_link', $download_link);
                } else {
                    if (strpos($sasResult, "This request completed with errors.") !== false) {
                        $this->Flash->error('This request completed with errors. Please contact the SAS team');
                    } else {
                        $this->Flash->error('No data');
                    }
                }
            }
        }
    }

    public function mandatePerformance()
    {
        //$mandates_list = $this->Report->query("SELECT DISTINCT mandate FROM portfolio pf, product pd WHERE pd.product_id NOT IN (22,23) AND mandate <> '' and pf.product_id=pd.product_id AND pd.product_type='guarantee' ORDER BY mandate ASC");
        $Portfolio = $this->getTableLocator()->get('Damsv2.Portfolio');
        $mandates_list = $Portfolio->find()->select(['mandate'])->distinct(['mandate'])->where(['mandate IS NOT NULL'])->order(['mandate' => 'ASC'])->all();

        $mandates = array();
        foreach ($mandates_list as $mandate) {
            $mandates[$mandate->mandate] = $mandate->mandate;
        }

        $this->set('mandates', $mandates);
        if ($this->request->is('post')) {
            $POST = $this->request->getData();
            if (empty($POST['Portfolio']['Mandate'])) {
                $this->Flash->error("Please choose one or more mandate.");
            } else {
                $mm = $POST['Portfolio']['Mandate'];

                $mm = trim($mm, ' ,');

                $user_id = $this->Authentication->getIdentity()->get('id');
                $params = array('mandate' => $mm, 'user_id' => $user_id);
                $sasResult = $this->SAS->curl(
                    "Mandate_performance.sas",
                    $params,
                    false,
                    false
                );
                $sasResult = preg_replace('/\\s+/', ' ', $sasResult);
                $sasResult = mb_convert_encoding($sasResult, "UTF-8"); // to remove \ufeff
                try {
                    $dom = HtmlDomParser::str_get_html($sasResult);
                    if ($dom) {
                        $res = $dom->find('#sasres');
                        foreach ($res as $r) {
                            $links = $r->find('a');
                            foreach ($links as $l) {
                                $filepath = trim($l->href);
                                $filepath = str_replace('/sas/common/portfolio_analytics/', '', $filepath);
                            }
                        }
                    }
                } catch (Exception $e) {
                    error_log("simple dom error : " . $e->getMessage());
                }

                if (!empty($filepath)) {
                    $download_link = "/damsv2/ajax/download-file/" . $filepath . "/pa";
                    $this->set('download_link', $download_link);
                } else {
                    if (strpos($sasResult, "This request completed with errors.") !== false) {
                        $this->Flash->error('This request completed with errors. Please contact the SAS team');
                    } else {
                        $this->Flash->error('No data');
                    }
                }
            }
        }
    }

    public function seasonalityReport()
    {
        $Portfolio = $this->getTableLocator()->get('Damsv2.Portfolio');
        $mandates_list = $Portfolio->find()->select(['mandate'])->where(['Portfolio.product_id NOT IN (22,23)', 'mandate IS NOT NULL'])->order(['mandate' => 'ASC'])->all();

        $mandates = array();
        foreach ($mandates_list as $mandate) {
            $mandates[$mandate->mandate] = $mandate->mandate;
        }

        $this->set('mandates', $mandates);
        if ($this->request->is('post')) {
            $POST = $this->request->getData();
            if (empty($POST['Portfolio']['deal_name'])) {
                $this->Flash->error("Please choose one or more deals.");
            } else {
                $mm = $POST['Portfolio']['mandate'];

                $mm = trim($mm, ' ,');

                $deals = $POST['Portfolio']['deal_name'];

                if (!is_array($deals)) {
                    $deals = explode(',', $deals);
                }
                $deals = implode(',', $deals);
                $deals = trim($deals, ', ');

                $user_id = $this->Authentication->getIdentity()->get('id');
                $params = array('deals' => $deals, 'mandate' => $mm, 'user_id' => $user_id);
                $sasResult = $this->SAS->curl(
                    "seasonality_report.sas",
                    $params,
                    false,
                    false
                );
                $sasResult = preg_replace('/\\s+/', ' ', $sasResult);
                $sasResult = mb_convert_encoding($sasResult, "UTF-8"); // to remove \ufeff
                try {
                    $dom = HtmlDomParser::str_get_html($sasResult);
                    if ($dom) {
                        $res = $dom->find('#sasres');
                        foreach ($res as $r) {
                            $links = $r->find('a');
                            foreach ($links as $l) {
                                $filepath = trim($l->href);
                                $filepath = str_replace('/sas/common/portfolio_analytics/', '', $filepath);
                            }
                        }
                    }
                } catch (Exception $e) {
                    error_log("simple dom error : " . $e->getMessage());
                }

                if (!empty($filepath)) {
                    $download_link = "/damsv2/ajax/download-file/" . $filepath . "/pa";
                    $this->set('download_link', $download_link);
                } else {
                    if (strpos($sasResult, "This request completed with errors.") !== false) {
                        $this->Flash->error('This request completed with errors. Please contact the SAS team');
                    } else {
                        $this->Flash->error('No data');
                    }
                }
            }
        }
    }

    public function loanCollateralReport()
    {
        $this->loadModel('Damsv2.Portfolio');
        $mandates = $this->Portfolio->getMandates_product(12);
        $this->set('mandates', $mandates);

        if ($this->request->is('post')) {
            $cc = $this->request->getData('Report.Mandate');
            if (empty($cc)) {
                $this->Flash->error("Please choose a mandate.");
            } else {
                $params = [
                    'mandate' => $this->request->getData('Report.Mandate'),
                    'user_id' => $this->Authentication->getIdentity()->get('id'),
                ];
                foreach ($params as $key => $val) {
                    if (empty($val)) {
                        $params[$key] = "."; //for sas to process empty values
                    }
                }
                $sasResult = $this->SAS->curl(
                    "SMEiTransaction_Coll_Stats.sas",
                    $params,
                    false,
                    false
                );
                $sasResult = preg_replace('/\\s+/', ' ', $sasResult);
                $sasResult = mb_convert_encoding($sasResult, "UTF-8"); // to remove \ufeff
                try {
                    //only keep id sasres in sasResult

                    $dom = HtmlDomParser::str_get_html($sasResult);

                    //$result = '';
                    if ($dom) {
                        $res = $dom->find('#sasres');
                        foreach ($res as $r) {
                            //$result = $r->outertext;
                            $links = $r->find('a');
                            foreach ($links as $l) {
                                $filepath = trim($l->href);
                                $filepath = str_replace('/sas/common/portfolio_analytics/', '', $filepath);
                            }
                        }
                    }
                    $sasResult = DownloadLib::change_downloadable_links($sasResult);
                } catch (Exception $e) {
                    error_log("simple dom error : " . $e->getMessage());
                }
                if (!empty($filepath)) {
                    $download_link = "/damsv2/ajax/download-file/" . $filepath . "/pa";
                    $this->set('download_link', $download_link);
                } else {
                    if (strpos($sasResult, "This request completed with errors.") !== false) {
                        $this->Flash->error('This request completed with errors. Please contact the SAS team');
                    } else {
                        $this->Flash->error('No data');
                    }
                }
            }
        }
    }

    public function transactionMonitoring()
    {
        $user_idendity = $this->Authentication->getIdentity();
        $first_name = $user_idendity->get('first_name');
        $last_name = $user_idendity->get('last_name');
        $user_name = $first_name . ' ' . $last_name;
        $date = date("Y-m-d H:i");
        $Reportanalyticslog = $this->getTableLocator()->get('Damsv2.Reportanalyticslog');

        $log = array('user' => $user_name, 'report' => 'Transaction monitoring', 'datetime_begining' => $date);
        $analitycs_log = $Reportanalyticslog->newEntity($log);
        $Reportanalyticslog->save($analitycs_log);
        $this->redirect("http://boxinew.lux.eib.org/BOE/OpenDocument/opendoc/openDocument.jsp?sIDType=CUID&iDocID=AQCSjrYY3y5OrzcKztw8Qzs&sRefresh=Y ");
    }

    public function genericDataExtract()
    {
        $Portfolio = $this->getTableLocator()->get('Damsv2.Portfolio');
        $mandates_list = $Portfolio->find()->select(['mandate'])->where(['Portfolio.product_id NOT IN (22,23)', 'mandate IS NOT NULL'])->order(['mandate' => 'ASC'])->all();

        $mandates = array();
        foreach ($mandates_list as $mandate) {
            $mandates[$mandate->mandate] = $mandate->mandate;
        }
        //$deals = $this->Portfolio->find("list", array('fields'=> array('portfolio_id', 'deal_name'), 'recursive' => -1));
        $deals = array();

        $this->set('mandates', $mandates);
        $this->set('deals', $deals);
        if ($this->request->is('post')) {
            $cc = $this->request->getData()['Portfolio']['deal_name'];
            if (empty($cc)) {
                $this->Flash->error("Please choose one or more mandate.");
            } else {
                //$mm = implode("\\,\\", $this->request->data['Report']['Mandate']);
                $mm = $this->request->getData()['Portfolio']['mandate'];
                $cc = trim($cc, ' ,');
                $mm = trim($mm, ' ,');
                //$cc = "\\".$cc."\\";
                //$mm = "\\".$mm."\\";
                $user_id = $this->Authentication->getIdentity()->get('id');
                $params = array('deals' => $cc, 'mandate' => $mm, 'user_id' => $user_id);
                $sasResult = $this->SAS->curl(
                    "generic_data_export.sas",
                    $params,
                    false,
                    false
                );

                $sasResult = preg_replace('/\\s+/', ' ', $sasResult);
                $sasResult = mb_convert_encoding($sasResult, "UTF-8"); // to remove \ufeff
                try {
                    $dom = HtmlDomParser::str_get_html($sasResult);
                    //$result = '';
                    if ($dom) {
                        $res = $dom->find('#sasres');

                        foreach ($res as $r) {
                            $result = $r->outertext;
                            $links = $r->find('a');
                            foreach ($links as $l) {
                                $filepath = trim($l->href);
                                $filepath = str_replace('/sas/common/portfolio_analytics/', '', $filepath);
                            }
                        }
                    }
                } catch (Exception $e) {
                    error_log("simple dom error : " . $e->getMessage());
                }
                if (!empty($filepath)) {
                    $download_link = "/damsv2/ajax/download-file/" . $filepath . "/pa";
                    $this->set('download_link', $download_link);
                } else {
                    if (strpos($sasResult, "This request completed with errors.") !== false) {
                        $this->Flash->error('This request completed with errors. Please contact the SAS team');
                    } else {
                        $this->Flash->error('No data');
                    }
                }
            }
        }
    }

    public function keyFieldsReport()
    {
        $Portfolio = $this->getTableLocator()->get('Damsv2.Portfolio');
        $mandates_list = $Portfolio->find()->select(['mandate'])->where(['Portfolio.product_id NOT IN (22,23)', 'mandate IS NOT NULL'])->order(['mandate' => 'ASC'])->all();

        $mandates = array();
        foreach ($mandates_list as $mandate) {
            $mandates[$mandate->mandate] = $mandate->mandate;
        }
        $this->set('mandates', $mandates);

        if ($this->request->is('post')) {
            $POST = $this->request->getData();
            if (empty($POST['Portfolio']['mandate'])) {
                $this->Flash->error('Please choose one mandate.');
            } elseif (empty($POST['Portfolio']['deal_name']) || $POST['Portfolio']['deal_name'][0] === '') {
                $this->Flash->error('Please choose one or more deals.');
            } else {
                $cc = implode(",", $POST['Portfolio']['deal_name']);
                $mm = $POST['Portfolio']['mandate'];
                $cc = trim($cc, ' ,');
                $mm = trim($mm, ' ,');
                $user_id = $this->Authentication->getIdentity()->get('id');
                $params = array('deals' => $cc, 'mandate' => $mm, 'user_id' => $user_id);

                $sasResult = $this->SAS->curl(
                    "key_fields_report.sas",
                    $params,
                    false,
                    false
                );

                $sasResult = preg_replace('/\\s+/', ' ', $sasResult);
                $sasResult = mb_convert_encoding($sasResult, "UTF-8"); // to remove \ufeff

                try {
                    $dom = HtmlDomParser::str_get_html($sasResult);
                    //$result = '';
                    if ($dom) {
                        $res = $dom->find('#sasres');

                        foreach ($res as $r) {
                            //$result = $r->outertext;
                            $links = $r->find('a');
                            foreach ($links as $l) {
                                $filepath = trim($l->href);
                                $filepath = str_replace('/sas/common/portfolio_analytics/', '', $filepath);
                            }
                        }
                    }
                } catch (Exception $e) {
                    error_log("simple dom error : " . $e->getMessage());
                }
                if (!empty($filepath)) {
                    $download_link = "/damsv2/ajax/download-file/" . $filepath . "/pa";
                    $this->set('download_link', $download_link);
                } else {
                    if (strpos($sasResult, "This request completed with errors.") !== false) {
                        $this->Flash->error('This request completed with errors. Please contact the SAS team');
                    } else {
                        $this->Flash->error('No data');
                    }
                }
            }
        }
    }

    public function expectedPortfolioVolumeReport()
    {
        $Product = $this->getTableLocator()->get('Damsv2.Product');
        $product_list = $Product->find()->select(['product_id', 'name'])->where(['Product.product_id IN' => [15, 6, 24, 13, 10, 20, 5, 12, 19]])->order(['name' => 'ASC'])->all();
        $products = array();
        foreach ($product_list as $prod) {
            $products[$prod->product_id] = $prod->name;
        }

        $this->set('products', $products);
        if ($this->request->is('post')) {
            $cc = $this->request->getData()['Product']['product_id'];
            if (empty($cc)) {
                $this->Flash->error("Please choose at least one product.");
            } else {
                $params = array(
                    'product_id' => $cc,
                    'user_id'    => $this->Authentication->getIdentity()->get('id'),
                );
                $sasResult = $this->SAS->curl(
                    "Portfolio_Volume_Report.sas",
                    $params,
                    false,
                    false
                );
                $sasResult = preg_replace('/\\s+/', ' ', $sasResult);
                $sasResult = mb_convert_encoding($sasResult, "UTF-8"); // to remove \ufeff
                try {
                    $dom = HtmlDomParser::str_get_html($sasResult);
                    if ($dom) {
                        $result = '';
                        $res = $dom->find('#sasres');

                        foreach ($res as $r) {
                            $result = $r->outertext;
                            $links = $r->find('a');
                            foreach ($links as $l) {
                                $filepath = trim($l->href);
                                $filepath = str_replace('/sas/common/portfolio_analytics/', '', $filepath);
                            }
                        }
                    }
                    $result = DownloadLib::change_downloadable_links($result);
                } catch (Exception $e) {
                    error_log("simple dom error : " . $e->getMessage());
                }
                if (!empty($filepath)) {
                    $download_link = "/damsv2/ajax/download-file/" . $filepath . "/pa";
                    $this->set('download_link', $download_link);
                } else {
                    $this->Flash->error("This request completed with errors. Please contact the SAS team");
                }
            }
        }
    }

    public function flpgProductRepaymentForecast()
    {
        $Portfolio = $this->getTableLocator()->get('Damsv2.Portfolio');
        $mandates = $Portfolio->find()->select(['Portfolio.mandate'])->where([
            'Portfolio.deal_name LIKE ' => '%FLPG%',
            'Portfolio.mandate LIKE'    => 'JER-%',
            'Portfolio.mandate NOT IN ' => ['JER-004 LITHUANIA']
        ])->order(['mandate' => 'ASC'])->all();
        $mandates_list = array();
        foreach ($mandates as $mand) {
            $mandates_list[$mand->mandate] = $mand->mandate;
        }

        $this->set('mandates', $mandates_list);

        if ($this->request->is('post')) {
            $cc = $this->request->getData()['Portfolio']['mandate'];
            if (empty($cc)) {
                $this->Flash->error("Please choose a mandate.");
            } else {
                if (!is_array($cc)) {
                    $cc = explode(',', $cc);
                }
                $cc = implode(',', $cc);
                $params = array(
                    'mandate'    => $cc,
                    'date_start' => $this->request->getData()['Report']['Date_start'],
                    'date_end'   => $this->request->getData()['Report']['Date_end'],
                    'user_id'    => $this->Authentication->getIdentity()->get('id'),
                );
                foreach ($params as $key => $val) {
                    if (empty($val)) {
                        $params[$key] = "."; //for sas to process empty values
                    }
                }
                $sasResult = $this->SAS->curl(
                    "Maturity_Breakdown_FLPG.sas",
                    $params,
                    false,
                    false
                );
                $sasResult = preg_replace('/\\s+/', ' ', $sasResult);
                $sasResult = mb_convert_encoding($sasResult, "UTF-8"); // to remove \ufeff
                $filepath = null;
                try {
                    $dom = HtmlDomParser::str_get_html($sasResult);
                    if ($dom) {
                        $result = '';
                        $res = $dom->find('#sasres');

                        foreach ($res as $r) {
                            $result = $r->outertext;
                            $links = $r->find('a');
                            foreach ($links as $l) {
                                $filepath = trim($l->href);
                                $filepath = str_replace('/sas/common/portfolio_analytics/', '', $filepath);
                            }
                        }
                        $result = DownloadLib::change_downloadable_links($result);
                    }
                } catch (Exception $e) {
                    error_log("simple dom error : " . $e->getMessage());
                }
                if (!empty($filepath)) {
                    $download_link = "/damsv2/ajax/download-file/" . $filepath . "/pa";
                    $this->set('download_link', $download_link);
                } else {
                    if (!empty($result)) {
                        //$this->set('sas_res', $result);
                        $this->Flash->error("No data");
                    } else {
                        $this->Flash->error("This request completed with errors. Please contact the SAS team");
                    }
                }
            }
        }
    }

    public function cashFlowForecast()
    {
        $Cashflowmandate = $this->getTableLocator()->get('Damsv2.Cashflowmandate');
        $mandates = $Cashflowmandate->find()->select(['mandate'])->all();
        $mandates_list = array();
        foreach ($mandates as $mand) {
            $mandates_list[$mand->mandate] = $mand->mandate;
        }

        $this->set('mandates', $mandates_list);

        if ($this->request->is('post')) {
            $POST = $this->request->getData();
            $cc = $POST['Portfolio']['mandate'];
            if (empty($cc)) {
                $this->Flash->error("Please choose a mandate.");
            } elseif (empty($POST['Report']['inclusion_period_end'])) {
                $this->Flash->error("please select the Inclusion Period End.");
            } else {
                $params = array(
                    'mandate'                => $POST['Portfolio']['mandate'],
                    'inclusion_period_end'   => $POST['Report']['inclusion_period_end'],
                    'pipeline_amount_1'      => $POST['Report']['pipeline_amount_1'],
                    'inclusion_start_pipe_1' => $POST['Report']['inclusion_start_pipe_1'],
                    'inclusion_end_pipe_1'   => $POST['Report']['inclusion_end_pipe_1'],
                    'pipeline_amount_2'      => $POST['Report']['pipeline_amount_2'],
                    'inclusion_start_pipe_2' => $POST['Report']['inclusion_start_pipe_2'],
                    'inclusion_end_pipe_2'   => $POST['Report']['inclusion_end_pipe_2'],
                    'user_id'                => $this->Authentication->getIdentity()->get('id'),
                );
                foreach ($params as $key => $val) {
                    if (empty($val)) {
                        $params[$key] = "."; //for sas to process empty values
                    }
                }
                $sasResult = $this->SAS->curl(
                    "MIBOSDS_cash_flow.sas",
                    $params,
                    false,
                    false
                );
                $sasResult = preg_replace('/\\s+/', ' ', $sasResult);
                $sasResult = mb_convert_encoding($sasResult, "UTF-8"); // to remove \ufeff
                $filepath = null;
                try {
                    $dom = HtmlDomParser::str_get_html($sasResult);
                    if ($dom) {
                        $result = '';
                        $res = $dom->find('#sasres');

                        foreach ($res as $r) {
                            $result = $r->outertext;
                            $links = $r->find('a');
                            foreach ($links as $l) {
                                $filepath = trim($l->href);
                                $filepath = str_replace('/sas/common/portfolio_analytics/', '', $filepath);
                            }
                        }
                    }
                    $result = DownloadLib::change_downloadable_links($result);
                } catch (Exception $e) {
                    error_log("simple dom error : " . $e->getMessage());
                }
                if (!empty($filepath)) {
                    $download_link = "/damsv2/ajax/download-file/" . $filepath . "/pa";
                    $this->set('download_link', $download_link);
                } else {
                    if (!empty($result)) {
                        $this->set('sas_res', $result);
                        $this->Flash->error("No data");
                    } else {
                        $this->Flash->error("This request completed with errors. Please contact the SAS team");
                    }
                }
            }
        }
    }

    public function productRepaymentForecast()
    {
        $Portfolio = $this->getTableLocator()->get('Damsv2.Portfolio');
        $portfolio_loan = $Portfolio->find()->select(['Portfolio.mandate'])->matching('Product', function ($q) {
            return $q->where(['product_type = "loan"']);
        })->all();
        $portfolio_list = array();
        foreach ($portfolio_loan as $portfolio) {
            $portfolio_list[] = $portfolio->mandate;
        }
        $Mandate = $this->getTableLocator()->get('Damsv2.Eifmandate');
        $mandates = $Mandate->find()->where(['mandate NOT IN ' => ['EPMF FCP', 'EREM-CBSI FCP', 'EPMF', 'EREM-CBSI'], 'mandate IN ' => $portfolio_list])->select(['mandate_id', 'mandate'])->order(['mandate' => 'ASC'])->all();

        $mandates_list = array();
        foreach ($mandates as $mand) {
            $mandates_list[$mand->mandate_id] = $mand->mandate;
        }

        $this->set('mandates', $mandates_list);

        if ($this->request->is('post')) {
            $cc = $this->request->getData()['Portfolio']['mandate'];
            if (empty($cc)) {
                $this->Flash->error("Please choose a mandate.");
            } else {
                $POST = $this->request->getData();
                $params = array(
                    'mandate'    =>    $POST['Portfolio']['mandate'],
                    'date_start'    =>    $POST['Report']['Date_start'],
                    'date_end'    =>    $POST['Report']['Date_end'],
                    'user_id'    =>    $this->Authentication->getIdentity()->get('id'),
                );
                foreach ($params as $key => $val) {
                    if (empty($val)) {
                        $params[$key] = "."; //for sas to process empty values
                    }
                }
                $sasResult = $this->SAS->curl(
                    "Maturity_Breakdown.sas",
                    $params,
                    false,
                    false
                );
                $sasResult = preg_replace('/\\s+/', ' ', $sasResult);
                $sasResult = mb_convert_encoding($sasResult, "UTF-8"); // to remove \ufeff
                $filepath = null;
                try {
                    $dom = HtmlDomParser::str_get_html($sasResult);
                    if ($dom) {
                        $result = '';
                        $res = $dom->find('#sasres');

                        foreach ($res as $r) {
                            $result = $r->outertext;
                            $links = $r->find('a');
                            foreach ($links as $l) {
                                $filepath = trim($l->href);
                                $filepath = str_replace('/sas/common/portfolio_analytics/', '', $filepath);
                            }
                        }
                    }
                    $result = DownloadLib::change_downloadable_links($result);
                } catch (Exception $e) {
                    error_log("simple dom error : " . $e->getMessage());
                }
                if (!empty($filepath)) {
                    $download_link = "/damsv2/ajax/download-file/" . $filepath . "/pa";
                    $this->set('download_link', $download_link);
                } else {
                    if (!empty($result)) {
                        $this->set('sas_res', $result);
                        $this->Flash->error("No data");
                    } else {
                        $this->Flash->error("This request completed with errors. Please contact the SAS team");
                    }
                }
            }
        }
    }

    public function inclusionStatus()
    {
        $Product = $this->getTableLocator()->get('Damsv2.Product');
        $product_list = $Product->find()->select(['product_id', 'name'])->where(['Product.product_id NOT IN' => [22, 23]])->order(['name' => 'ASC'])->all();
        $products = array();
        foreach ($product_list as $prod) {
            $products[$prod->product_id] = $prod->name;
        }

        $this->set('products', $products);

        if ($this->request->is('post')) {
            $cc = $this->request->getData('Product.product_id');
            if (empty($cc) || empty($cc[0])) {
                $this->Flash->error("Please choose one or more products.");
            } else {
                if (!is_array($cc)) {
                    $cc = array($cc);
                }
                $cc = implode(',', $cc);
                $params = [
                    'product_id' => $cc,
                    'user_id'    => $this->Authentication->getIdentity()->get('id'),
                ];
                foreach ($params as $key => $val) {
                    if (empty($val)) {
                        $params[$key] = "."; //for sas to process empty values
                    }
                }
                $sasResult = $this->SAS->curl(
                    "inclusion_status_report.sas",
                    $params,
                    false,
                    false
                );
                $sasResult = preg_replace('/\\s+/', ' ', $sasResult);
                $sasResult = mb_convert_encoding($sasResult, "UTF-8"); // to remove \ufeff
                try {
                    //only keep id sasres in sasResult

                    $dom = HtmlDomParser::str_get_html($sasResult);

                    //$result = '';
                    if ($dom) {
                        $res = $dom->find('#sasres');
                        foreach ($res as $r) {
                            //$result = $r->outertext;
                            $links = $r->find('a');
                            foreach ($links as $l) {
                                $filepath = trim($l->href);
                                $filepath = str_replace('/sas/common/portfolio_analytics/', '', $filepath);
                            }
                        }
                    }
                    $sasResult = DownloadLib::change_downloadable_links($sasResult);
                } catch (Exception $e) {
                    error_log("simple dom error : " . $e->getMessage());
                }
                if (!empty($filepath)) {
                    $download_link = "/damsv2/ajax/download-file/" . $filepath . "/pa";
                    $this->set('download_link', $download_link);
                } else {
                    if (strpos($sasResult, "This request completed with errors.") !== false) {
                        $this->Flash->error('This request completed with errors. Please contact the SAS team');
                    } else {
                        $this->Flash->error('No data');
                    }
                }
            }
        }
    }

    public function testzip()
    {
        $this->Spreadsheet->testzip();
    }
}
