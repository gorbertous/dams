<?php

declare(strict_types=1);

namespace Damsv2\Controller;

use Cake\Event\EventInterface;
use App\Lib\DownloadLib;
use Cake\Datasource\ConnectionManager;
use App\Lib\Helpers;
use Cake\Collection\Collection;

class AjaxController extends AppController
{

    public $name = 'Ajax';

    public function initialize(): void
    {
        parent::initialize();
        //$this->loadComponent('Security');
        $this->loadComponent('Spreadsheet');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        //$this->Security->setConfig('blackHoleCallback', 'blackhole');
    }

//    public function calc_equivalencies()
//    {
//        $this->layout = 'ajax';
//
//        $converted = $this->Calc->trn_eur_curr(
//                $this->request->getData['Data']['transaction_id'],
//                $this->request->getData['Data']['field'],
//                $this->request->getData['Data']['value'],
//                $this->request->getData['Data']['fx_type']
//        );
//
//        echo json_encode($converted);
//    }

    /**
     * send back all related to the selected product, portfolio, ...
     */
    public function pdlrReception()
    {
        if ($this->request->is('ajax')) {
            //set view to Ajax
            //$this->viewBuilder()->setLayout('ajax');
            //$this->autoRender = false;

            $results = [
                'portfolios' => "Error during fetching of the contract currency",
                'portfolio'  => null
            ];

            $this->loadModel('Damsv2.Portfolio');
            if (!empty($this->request->getData('Product.product_id'))) {

                $tt = $this->Portfolio->find('all', [
                    'conditions' => [
                        'Portfolio.product_id'        => $this->request->getData('Product.product_id'),
                        'Portfolio.product_id NOT IN' => [22, 23],
                        'Portfolio.iqid NOT IN'       => $this->getUmbrellaIqid()
                    ],
                    'order'      => ['Portfolio.portfolio_name' => 'ASC']
                ]);
                error_log("dams pdlr create portfolios list: " . json_encode($tt));

                $results['portfolios'] = $this->Portfolio->find('all', [
                    'recursive'  => -1,
                    'conditions' => [
                        'Portfolio.product_id'        => $this->request->getData('Product.product_id'),
                        'Portfolio.product_id NOT IN' => [22, 23],
                        'Portfolio.iqid NOT IN'       => $this->getUmbrellaIqid()
                    ],
                    'fields'     => ['Portfolio.portfolio_id', 'Portfolio.portfolio_name'],
                    'order'      => ['Portfolio.portfolio_name' => 'ASC']
                ]);
            }

            if (!empty($this->request->getData('Portfolio.portfolio_id'))) {
                $results['portfolio'] = $this->Portfolio->find('all', [
                            'conditions' => [
                                'Portfolio.portfolio_id'      => $this->request->getData('Portfolio.portfolio_id'),
                                'Portfolio.product_id NOT IN' => [22, 23],
                                'Portfolio.iqid NOT IN'       => $this->getUmbrellaIqid()
                            ],
                                //'fields'     => array('Portfolio.*')
                        ])->first();
            }
            $this->set(['results' => $results]);
            $this->viewBuilder()->setOption('serialize', true);
            $this->RequestHandler->renderAs($this, 'json');
            //return json_encode($results);
        }
    }

    //http://vmu-sas-01:8080/browse/DAMS-1600
    public function getAmountCcy()
    {
        if ($this->request->is('ajax')) {

            //set view to Ajax
            $this->viewBuilder()->setLayout('ajax');
            $this->autoRender = false;
            $this->loadModel('Damsv2.Portfolio');
            $this->loadModel('Damsv2.FixedRate');
            $this->loadModel('Damsv2.Daily');
            $connection = ConnectionManager::get('default');            
            if (!empty($this->request->getData('Report.ccy')) && !empty($this->request->getData('Report.amount')) && !empty($this->request->getData('Report.portfolio_id'))) {
                
                $rep_amount = (double) preg_replace('/[^0-9\.-]/', "", $this->request->getData('Report.amount')); //remove ','
                
                $portfolio = $this->Portfolio->find('all', array(
                            'conditions' => array('Portfolio.portfolio_id' => $this->request->getData('Report.portfolio_id')),
                            'fields'     => array('Portfolio.currency', 'Portfolio.fx_rate_pdlr')
                        ))->first();
                
                if (!empty($portfolio->currency)) {
                    $ccy = $portfolio->currency;
                    $source = $portfolio->fx_rate_pdlr;
                    if ($ccy == 'EUR') {
                        if ($this->request->getData('Report.ccy') == 'EUR') {
                            echo $rep_amount;
                        } else {
                            $rate = $this->Daily->find('all', array(
                                        'conditions' => array('currency' => $this->request->getData('Report.ccy')),
                                        'fields'     => array('obs_value')
                                    ))->first();
                            if ($source == 'ECB_latest') {
                                $result = (double) $rep_amount / (double) $rate->obs_value;
                            } elseif ($source == 'FIXED') {
                                $fixed_rates = $this->FixedRate->find('all', array(
                                            'conditions' => array('portfolio_id' => $this->request->getData('Report.portfolio_id')),
                                            'currency'   => $this->request->getData('Report.ccy'),
                                            'fields'     => array('obs_value')
                                        ))->first();
                                if (!empty($fixed_rates->obs_value)) {
                                    $result = (double) $rep_amount / (double) $fixed_rates->obs_value;
                                } else {
                                    exit;
                                }
                            } elseif ($source == 'NCB_latest') {
                                $ccy = $this->request->getData('Report.ccy');
                                $ccy = filter_var($ccy, FILTER_SANITIZE_STRING);
                                $ncb_rate = $connection->execute("SELECT * FROM ncb_rate WHERE currency='" . $ccy . "' ORDER BY time_period DESC LIMIT 1")->fetchAll('assoc');
                                if (!empty($ncb_rate)) {
                                    $result = (double) $rep_amount / (double) $ncb_rate[0]['OBS_VALUE'];
                                } else {
                                    exit;
                                }
                            }
                            echo $result;
                        }
                    } else {
                        if ($this->request->getData('Report.ccy') == 'EUR') {
                            $rate = $this->Daily->find('all', array(
                                        'conditions' => array(
                                            'currency' => $portfolio->currency,
                                        ),
                                        'fields'     => array(
                                            'obs_value'
                                        )
                                    ))->first();

                            if ($source == 'ECB_latest') {
                                echo (double) $rep_amount * (double) $rate->obs_value;
                            } elseif ($source == 'FIXED') {
                                $fixed_rates = $this->FixedRate->find('all', array(
                                            'conditions' => array('portfolio_id' => $this->request->getData('Report.portfolio_id')),
                                            'currency'   => $portfolio->currency,
                                            'fields'     => array('obs_value')
                                        ))->first();
                                if (!empty($fixed_rates->obs_value)) {
                                    $result = (double) $rep_amount * (double) $fixed_rates->obs_value;
                                    //echo $result;//fix later when asked
                                } else {
                                    exit;
                                }
                            } elseif ($source == 'NCB_latest') {
                                $ccy = $portfolio->currency;
                                $ccy = filter_var($ccy, FILTER_SANITIZE_STRING);
                                $ncb_rate = $connection->execute("SELECT * FROM ncb_rate WHERE currency='" . $ccy . "' ORDER BY time_period DESC LIMIT 1")->fetchAll('assoc');
                                if (!empty($ncb_rate)) {
                                    echo (double) $rep_amount * (double) $ncb_rate[0]['OBS_VALUE'];
                                } else {
                                    exit;
                                }
                            }
                        } else {
                            $rate = $this->Daily->find('all', array(
                                        'conditions' => array(
                                            'currency' => $this->request->getData('Report.ccy'),
                                        ),
                                        'fields'     => array(
                                            'obs_value'
                                        )
                                    ))->first();
                            if ($source == 'ECB_latest') {
                                $result = (double) $rep_amount / (double) $rate->obs_value;
                            } elseif ($source == 'FIXED') {
                                $fixed_rates = $this->FixedRate->find('all', array(
                                            'conditions' => array('portfolio_id' => $this->request->getData('Report.portfolio_id')),
                                            'currency'   => $this->request->getData('Report.ccy'),
                                            'fields'     => array('obs_value')
                                        ))->first();
                                if (!empty($fixed_rates->obs_value)) {
                                    $result = (double) $rep_amount / (double) $fixed_rates->obs_value;
                                } else {
                                    exit;
                                }
                            } elseif ($source == 'NCB_latest') {
                                $ccy = $this->request->getData('Report.ccy');
                                $ccy = filter_var($ccy, FILTER_SANITIZE_STRING);
                                $ncb_rate1 = $connection->execute("SELECT * FROM ncb_rate WHERE currency='" . $ccy . "' ORDER BY time_period DESC LIMIT 1")->fetchAll('assoc');
                                if (!empty($ncb_rate1)) {
                                    //$result = (double)$this->request->getData('Report.amount'] / (double)$ncb_rate[0]['OBS_VALUE'];
                                    $ccy = $portfolio->currency;
                                    $ccy = filter_var($ccy, FILTER_SANITIZE_STRING);
                                    $ncb_rate2 = $connection->execute("SELECT * FROM ncb_rate WHERE currency='" . $ccy . "' ORDER BY time_period DESC LIMIT 1")->fetchAll('assoc');
                                    if (!empty($ncb_rate2)) {
                                        $rate = (double) $ncb_rate1[0]['OBS_VALUE'] / (double) $ncb_rate2[0]['OBS_VALUE'];
                                        $rate = round($rate, 6);
                                        echo (double) $rep_amount / $rate;
                                        exit;
                                    } else {
                                        exit;
                                    }
                                } else {
                                    exit;
                                }
                            }

                            $rate = $this->Daily->find('all', array(
                                        'conditions' => array(
                                            'currency' => $portfolio->currency,
                                        ),
                                        'fields'     => array(
                                            'obs_value'
                                        )
                                    ))->first();

                            if ($source == 'ECB_latest') {
                                echo (double) $result * (double) $rate->obs_value;
                            } elseif ($source == 'FIXED') {
                                $fixed_rates = $this->FixedRate->find('all', array(
                                            'conditions' => array('portfolio_id' => $this->request->getData('Report.portfolio_id')),
                                            'currency'   => $portfolio->currency,
                                            'fields'     => array('obs_value')
                                        ))->first();
                                if (!empty($fixed_rates->obs_value)) {
                                    echo (double) $result * (double) $fixed_rates->obs_value;
                                } else {
                                    exit;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    // used by ajax to populate portfolios and period from product
    // no umbrella portfolio but allows umbrella sub portfolios
    public function getPortfoliosByProduct()
    {
        if ($this->request->is('ajax')) {
            //set view to Ajax
            $this->viewBuilder()->setLayout('ajax');
            $product_id = !empty($this->request->getData('Product.product_id')) ? $this->request->getData('Product.product_id') : $this->request->getData('product_id');
            $this->loadModel('Damsv2.Portfolio');

            if (empty($product_id)) {
                $portfolios = $this->Portfolio->find('list', [
                            'contain'    => ['Product'],
                            'fields'     => ['Product.name', 'Portfolio.portfolio_name', 'Portfolio.portfolio_id'],
                            'keyField'   => 'portfolio_id',
                            'valueField' => 'portfolio_name',
                            'groupField' => 'product.name',
                            'order'      => ['Product.name', 'Portfolio.portfolio_name'],
                            'conditions' => ['Portfolio.product_id NOT IN' => [22, 23], 'Portfolio.iqid NOT IN' => $this->getUmbrellaIqid()]
                        ])->toArray();
            } else {
                $portfolios = $this->Portfolio->getPortfoliosByProductId($product_id);
            }

            $portfolio_empty = !empty($this->request->getData('Portfolio.portfolio_empty')) ? $this->request->getData('Portfolio.portfolio_empty') : null;

            $this->set(compact('portfolios', 'portfolio_empty'));
        }
    }

    public function getPortfoliosByProductAndMandate()
    {
        if ($this->request->is('ajax')) {
            //set view to Ajax
            $this->viewBuilder()->setLayout('ajax');
            $product_id = $this->request->getData('Product.product_id');
            $mandate_id = $this->request->getData('Portfolio.mandate_id');
            $this->loadModel('Damsv2.Portfolio');

            if (empty($product_id) && empty($mandate_id)) {

                $portfolios = $this->Portfolio->find('list', [
                            'contain'    => ['Product'],
                            'fields'     => ['Product.name', 'Portfolio.portfolio_name', 'Portfolio.portfolio_id'],
                            'keyField'   => 'portfolio_id',
                            'valueField' => 'portfolio_name',
                            'groupField' => 'product.name',
                            'order'      => ['Product.name', 'Portfolio.portfolio_name'],
                            'conditions' => ['Portfolio.product_id NOT IN' => [22, 23], 'Portfolio.iqid NOT IN' => $this->getUmbrellaIqid()]
                        ])->toArray();
            } else {
                $mandate_name = null;
                if (!empty($mandate_id)) {
                    $this->loadModel('Damsv2.Mandate');
                    $mandate_name = $this->Mandate->find()->select(['mandate_name'])->where(['mandate_id' => $mandate_id]);

                    $mandate_possible = $this->Portfolio->find('all', [
                                'fields'     => ['portfolio_name', 'product_id'],
                                'conditions' => [
                                    'Portfolio.product_id' => $product_id,
                                    'Portfolio.mandate'    => $mandate_name
                        ]])->first();
                    if (empty($mandate_possible)) {
                        $mandate_name = null;
                    }
                }
                $portfolios = $this->Portfolio->getPortfoliosByProductIdAndMandate($product_id, $mandate_name);
            }

            $portfolio_empty = !empty($this->request->getData('Portfolio.portfolio_empty')) ? $this->request->getData('Portfolio.portfolio_empty') : null;

            $this->set(compact('portfolios', 'portfolio_empty'));
        }
    }

    public function getMandatesByProduct()
    {
        if ($this->request->is('ajax')) {
            //set view to Ajax
            $this->viewBuilder()->setLayout('ajax');
            $this->loadModel('Damsv2.Portfolio');
            $this->loadModel('Damsv2.Mandate');
            $product_id = !empty($this->request->getData('Product.product_id')) ? $this->request->getData('Product.product_id') : $this->request->getData('product_id');

            if (empty($product_id)) {
                $portfolio = $this->Portfolio->find('list', [
                            'fields'     => ['Portfolio.mandate'],
                            'keyField'   => 'mandate',
                            'valueField' => 'mandate',
                            'conditions' => ['Portfolio.portfolio_id NOT IN = ' => $umbrella_portfolios_ids, 'Portfolio.product_id NOT IN' => [22, 23]],
                            'order'      => 'Portfolio.mandate',
                        ])->toArray();
            } else {
                $portfolio = $this->Portfolio->find('list', [
                            'fields'     => ['Portfolio.mandate'],
                            'keyField'   => 'mandate',
                            'valueField' => 'mandate',
                            'conditions' => ['Portfolio.product_id' => $product_id],
                            'order'      => 'Portfolio.mandate',
                        ])->toArray();
            }
            $mandates = $this->Mandate->find('list', [
                        'fields'     => ['Mandate.mandate_id', 'Mandate.mandate_name'],
                        'keyField'   => 'mandate_id',
                        'valueField' => 'mandate_name',
                        'conditions' => ['Mandate.mandate_name IN' => $portfolio],
                        'order'      => 'Mandate.mandate_name',
                    ])->toArray();
            $this->set(compact('mandates'));
        }
    }

    // used by ajax to populate portfolios and period from product
    // no umbrella portfolio but allows umbrella sub portfolios
    public function getPortfoliosByProductAllPortfolio()
    {
        if ($this->request->is('ajax')) {
            //set view to Ajax
            $this->viewBuilder()->setLayout('ajax');
            $product_id = $this->request->getData('Product.product_id');
            $this->loadModel('Damsv2.Portfolio');

            if (empty($product_id)) {
                $portfolios = $this->Portfolio->find('list', [
                            'contain'    => ['Product'],
                            'fields'     => ['Product.name', 'Portfolio.portfolio_name', 'Portfolio.portfolio_id'],
                            'keyField'   => 'portfolio_id',
                            'valueField' => 'portfolio_name',
                            'groupField' => 'product.name',
                            'order'      => ['Product.name', 'Portfolio.portfolio_name'],
                            'conditions' => ['Portfolio.product_id NOT IN' => [22, 23]]
                        ])->toArray();
            } else {
                $portfolios = $this->Portfolio->getPortfoliosByProductIdwithUmbrellaAndSubportfolio($product_id);
            }

            $portfolio_empty = !empty($this->request->getData('Portfolio.portfolio_empty')) ? $this->request->getData('Portfolio.portfolio_empty') : null;

            $this->set(compact('portfolios', 'portfolio_empty'));
        }
    }

    // used by ajax to populate portfolios and umbrella from product
    // allow umbrella portfolio and show umbrella sub portfolio disabled
    public function getPortfoliosAndUmbrellaByProduct()
    {
        if ($this->request->is('ajax')) {
            //set view to Ajax
            $this->viewBuilder()->setLayout('ajax');
            $product_id = $this->request->getData('Product.product_id');
            $this->loadModel('Damsv2.Portfolio');
            $connection = ConnectionManager::get('default');

            $umbrella_portfolios = $connection
                    ->execute('SELECT distinct p.portfolio_id FROM umbrella_portfolio u, umbrella_portfolio_mapping um, portfolio p WHERE u.umbrella_portfolio_id = um.umbrella_portfolio_id AND p.iqid=u.iqid')
                    ->fetchAll('assoc');

            $umbrella_portfolios_ids = [];
            foreach ($umbrella_portfolios as $umb) {
                $umbrella_portfolios_ids[] = $umb['portfolio_id'];
            }
            $disabled = [];

            if (empty($product_id)) {
                $portfolios = $this->Portfolio->find('list', [
                    'fields'     => ['Portfolio.portfolio_id', 'Portfolio.portfolio_name', 'Product.name'],
                    'conditions' => ['Portfolio.portfolio_id NOT IN = ' => $umbrella_portfolios_ids, 'Portfolio.product_id NOT IN' => [22, 23]],
                    'keyField'   => 'portfolio_id',
                    'valueField' => 'portfolio_name',
                    'order'      => 'Portfolio.portfolio_name'
                ]);
                $umbrellas = $connection->execute('SELECT distinct u.umbrella_portfolio_id, u.umbrella_portfolio_name FROM umbrella_portfolio u, umbrella_portfolio_mapping um WHERE u.umbrella_portfolio_id = um.umbrella_portfolio_id')->fetchAll('assoc');
                $portfolios["00"] = "-- Umbrella --";
                foreach ($umbrellas as $umbrella) {
                    $portfolios['u_' . $umbrella["umbrella_portfolio_id"]] = $umbrella["umbrella_portfolio_name"];
                    //removing portfolio under umbrella to avoid generation conflict (only through umbrella)
                    //$disabled[] = $umbrella["portfolio_id"];
                }
                asort($portfolios);
            } else {
                $portfolios = $this->Portfolio->getPortfoliosByProductId($product_id);

                $umbrellas = $connection->execute('SELECT * FROM umbrella_portfolio u, umbrella_portfolio_mapping um WHERE u.umbrella_portfolio_id = um.umbrella_portfolio_id AND product_id=' . intval($product_id))->fetchAll('assoc');
                foreach ($umbrellas as $umbrella) {
                    $portfolios['u_' . $umbrella["umbrella_portfolio_id"]] = $umbrella["umbrella_portfolio_name"];
                    //removing portfolio under umbrella to avoid generation conflict (only through umbrella)
                    $disabled[] = $umbrella["portfolio_id"];
                }
                asort($portfolios);
            }

            $portfolio_empty = !empty($this->request->getData('Portfolio.portfolio_empty')) ? $this->request->getData('Portfolio.portfolio_empty') : null;

            $this->set(compact('portfolios', 'portfolio_empty', 'disabled'));
        }
    }

    // used by ajax to populate portfolios and umbrella from product
    // allow umbrella portfolio and umbrella sub portfolio
    public function getPortfoliosAndUmbrellaByProduct2()
    {
        if ($this->request->is('ajax')) {
            $this->loadModel('Damsv2.Portfolio');
            //set view to Ajax
            $this->viewBuilder()->setLayout('ajax');
            $connection = ConnectionManager::get('default');
            $product_id = $this->request->getData('product_id');

            $umbrella_portfolios = $connection->query("SELECT * FROM umbrella_portfolio u, umbrella_portfolio_mapping um, portfolio p WHERE u.umbrella_portfolio_id = um.umbrella_portfolio_id AND p.iqid=u.iqid")->fetchAll('assoc');
            $umbrella_portfolios_ids = [];
            foreach ($umbrella_portfolios as $umb) {
                $umbrella_portfolios_ids[] = $umb['portfolio_id'];
            }
            $disabled = [];
            if (empty($product_id)) {
                $portfolios = $this->Portfolio->find('list', [
                            'contain'    => ['Product'],
                            'fields'     => ['Product.name', 'Portfolio.portfolio_name', 'Portfolio.portfolio_id'],
                            'keyField'   => 'portfolio_id',
                            'valueField' => 'portfolio_name',
                            'groupField' => 'product.name',
                            'order'      => ['Product.name', 'Portfolio.portfolio_name'],
                            'conditions' => ['Portfolio.product_id NOT IN' => [22, 23], 'Portfolio.portfolio_id NOT IN' => $umbrella_portfolios_ids]
                        ])->toArray();
                $umbrellas = $connection->query("SELECT * FROM umbrella_portfolio u, umbrella_portfolio_mapping um WHERE u.umbrella_portfolio_id = um.umbrella_portfolio_id")->fetchAll('assoc');
                foreach ($umbrellas as $umbrella) {
                    $portfolios['u_' . $umbrella["umbrella_portfolio_id"]] = $umbrella["umbrella_portfolio_name"];
                    //removing portfolio under umbrella to avoid generation conflict (only through umbrella)
                    //$disabled[] = $umbrella["um"]["portfolio_id"];
                }
                asort($portfolios);
            } else {
                $portfolios = $this->Portfolio->getPortfoliosByProductId($product_id);
                $umbrellas = $connection->execute("SELECT * FROM umbrella_portfolio u, umbrella_portfolio_mapping um WHERE u.umbrella_portfolio_id = um.umbrella_portfolio_id AND product_id=" . intval($product_id))->fetchAll('assoc');

                if (!empty($umbrellas)) {
                    foreach ($umbrellas as $umbrella) {
                        $portfolios['u_' . $umbrella["umbrella_portfolio_id"]] = $umbrella["umbrella_portfolio_name"];
                        //removing portfolio under umbrella to avoid generation conflict (only through umbrella)
                        //$disabled[] = $umbrella["um"]["portfolio_id"];
                    }
                    asort($portfolios);
                }
            }

            $portfolio_empty = !empty($this->request->getData('Portfolio.portfolio_empty')) ? $this->request->getData('Portfolio.portfolio_empty') : null;

            $this->set(compact('portfolios', 'portfolio_empty', 'disabled'));
        }
    }

//    public function remove_from_list($id_portfolio, $list_portfolios)
//    {
//        @$this->validate_param('int', $id_portfolio);
//        @$this->validate_param('array', $list_portfolios);
//        $found = false;
//        $imax = 1000;
//        $i_product = 0;
//        $i_product_max = count($list_portfolios);
//        $products = array_keys($list_portfolios);
//        while (($i_product < $i_product_max) && !$found) {
//            $i_portfolio = 0;
//            $i_portfolio_max = count($list_portfolios[$products[$i_product]]);
//            $portfolios_keys = array_keys($list_portfolios[$products[$i_product]]);
//            while (!$found && ($i_portfolio < $i_portfolio_max)) {
//                $found = ($portfolios_keys[$i_portfolio] == $id_portfolio);
//                $i_portfolio++;
//            }
//            if ($found) {
//                unset($list_portfolios[$products[$i_product]][$portfolios_keys[$i_portfolio]]);
//            }
//            $i_product++;
//        }
//        return $list_portfolios;
//    }

    public function getCountriesByMandate()
    {
        $mandates = explode(',', $this->request->getData['data']['ReportMandate']);
        $mandates = implode("','", $mandates);
        $mandates = "'" . $mandates . "'";
        $countries = $this->Portfolio->query("SELECT distinct country FROM portfolio where mandate in (" . $mandates . ")");
        $countries_list = array();
        foreach ($countries as $c) {
            $countries_list[$c['portfolio']['country']] = $c['portfolio']['country'];
        }
        $this->set('countries', $countries_list);
        $this->layout = 'ajax';
    }

    public function getDealsByMandate()
    {
        $mandates = explode(',', $this->request->getData()['Portfolio']['mandate']);
        $mandates = implode("','", $mandates);

        $deals_list = array();

        $Portfolio = $this->getTableLocator()->get('Damsv2.Portfolio');
        $deals = $Portfolio->find()->select(['portfolio_id', 'deal_name'])->where(['Portfolio.mandate IN' => $mandates])->order(['deal_name' => 'ASC'])->all();

        foreach ($deals as $deal) {
            $deals_list[$deal->portfolio_id] = $deal->deal_name;
        }

        $this->set('deals', $deals_list);
        $this->viewBuilder()->setLayout('ajax');
    }

    public function getDealsByMandateUnique()
    {
        $mandates = explode(',', $this->request->getData()['Portfolio']['mandate']);
        $mandates = implode("','", $mandates);

        $deals_list = array();

        $Portfolio = $this->getTableLocator()->get('Damsv2.Portfolio');
        $deals = $Portfolio->find()->select(['portfolio_id', 'deal_name'])->where(['Portfolio.mandate IN' => $mandates])->order(['deal_name' => 'ASC'])->all();

        foreach ($deals as $deal) {
            $deals_list[$deal->portfolio_id] = $deal->deal_name;
        }

        $this->set('deals', $deals_list);
        $this->viewBuilder()->setLayout('ajax');
    }

    public function getDealsByMandateUnique_multiple()
    {
        if (strpos($this->request->getData['data']['ReportMandate'], ',')) {
            $mandates = explode(",", $this->request->getData['data']['ReportMandate']);
            $mandates = implode("','", $mandates);
        } else {
            $mandates = $this->request->getData['data']['ReportMandate'];
        }
        $mandates = "'" . $mandates . "'";
        $req = "SELECT portfolio_id, deal_name FROM portfolio where mandate in (" . $mandates . ") ORDER BY deal_name ASC";
        $deals = $this->Portfolio->query($req);
        $deals_list = array();
        foreach ($deals as $d) {
            $deals_list[$d['portfolio']['portfolio_id']] = $d['portfolio']['deal_name'];
        }
        $this->set('deals', $deals_list);
        $this->layout = 'ajax';
    }

    // used by ajax to populate periods from product
    public function getPeriodsByProduct()
    {
        if ($this->request->is('ajax')) {

            //set view to Ajax
            $this->viewBuilder()->setLayout('ajax');
            $product_id = $this->request->getData('Product.product_id');

            $this->loadModel('Damsv2.Product');
            $period = $this->Product->find('all', [
                        'fields'     => ['Product.reporting_frequency'],
                        'conditions' => ['Product.product_id' => $product_id],
                            ]
                    )->firstOrFail();

            switch ($period->reporting_frequency) {
                case 'Quarterly':
                    $periods = ['Q1' => 'Q1', 'Q2' => 'Q2', 'Q3' => 'Q3', 'Q4' => 'Q4'];
                    break;
                case 'Semi-annually':
                    $periods = ['S1' => 'S1 (From 01/01 to 30/06)', 'S2' => 'S2 (From 01/07 to 31/12)'];
                    break;
                case 'Semi-annually (-3 months)':
                    $periods = ['S1_spe' => 'S1 (From 01/10 to 31/03)', 'S2_spe' => 'S2 (From 01/04 to 30/09)'];
                    break;
                default:
                    $periods = [];
                    break;
            }

            $this->set(compact('periods'));
        }
    }

    //update Template list in EDIT
    public function getTemplatesByPortfolio()
    {
        if ($this->request->is('ajax')) {
            //set view to Ajax
            $this->viewBuilder()->setLayout('ajax');
            $portfolio_id = $this->request->getData('portfolio_id');
            $this->loadModel('Damsv2.Template');
            if (strpos($portfolio_id, 'u_') !== false) {
				if ($this->perm->hasWrite(array('controller' => 'Import', 'action' => 'bds')))//if cfm profile
				{
					$templates = array('EP' => 'Expired to Performing');
				}
				else
				{
					$templates = $this->Template->getSheetsByUmbrellaIdForEdit($portfolio_id);
				}
            } else {
				if ($this->perm->hasWrite(array('controller' => 'Import', 'action' => 'bds')))//if cfm profile
				{
					$templates = [
						'EP'  => 'Expired to Performing',
						'BDS' => 'BDS',
					];
				}
				else
				{
					$templates = $this->Template->getSheetsByPortfolioIdForEdit($portfolio_id);
				}	
            }

            $this->set(compact('templates'));
        }
    }

    //update Type list in EDIT
    public function getTypeByPortfolio()
    {
        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setLayout('ajax');
            $portfolio_id = $this->request->getData('portfolio_id');
            $types = $this->getTypeByPortfolio_($portfolio_id);

            $this->set(compact('types'));
        }
    }

    public function getTypeByPortfolio_($portfolio_id)
    {

        $types = [];

        if ($this->testUmbrella($portfolio_id)) {
            $portfolio_id = $this->getPortfolioFromUmbrellaId($portfolio_id);
        }

        if (!empty($portfolio_id)) {
            $types = ['BK' => 'Business keys', 'DATA' => 'Data', 'SPLIT' => 'Split'];
            $this->loadModel('Damsv2.Portfolio');
            $portfolio = $this->Portfolio->find('all', [
                        'conditions' => ['Portfolio.portfolio_id' => $portfolio_id],
                        'recursive'  => -1
                            ]
                    )->first();

            //case for CYPEF
            if ($portfolio->product_id == '9') {
                unset($types['BK']);
            }
        }
        return $types;
    }

    public function isUmbrella($portfolio_id)
    {
        Helpers::isInt($portfolio_id);
        $isUmbrella = null;
        if (Helpers::isInt($portfolio_id) && !empty($portfolio_id)) {
            $connection = ConnectionManager::get('default');
            $umbrella = $connection
                    ->execute('SELECT p.portfolio_id from umbrella_portfolio u, portfolio p WHERE u.iqid = p.iqid and p.portfolio_id = :id', ['id' => $portfolio_id])
                    ->fetchAll('assoc');

            $isUmbrella = (count($umbrella) > 0);
        }
        return $isUmbrella;
    }

    public function belongToUmbrella($portfolio_id)
    {
        Helpers::isInt($portfolio_id);
        if (Helpers::isInt($portfolio_id)) {
            $connection = ConnectionManager::get('default');
            $belongUmbrella = $connection
                    ->execute('SELECT * FROM umbrella_portfolio_mapping u WHERE u.portfolio_id = :id', ['id' => $portfolio_id])
                    ->fetchAll('assoc');
            return !empty($belongUmbrella) ? true : false;
        } else {
            return false;
        }
    }

    public function getSubPortfoliosReportsFromUmbrellaId($umbrella_id)
    {
        $connection = ConnectionManager::get('default');

        $umbrella_portfolios = $connection
                ->execute('SELECT * FROM umbrella_portfolio_mapping u WHERE u.portfolio_id = :id', ['id' => $umbrella_id])
                ->fetchAll('assoc');

        $portfolio_umbrella = $connection
                ->execute('SELECT p.portfolio_id FROM umbrella_portfolio u, portfolio p WHERE u.iqid=p.iqid AND u.umbrella_portfolio_id= :id', ['id' => $umbrella_id])
                ->fetchAll('assoc');

        $sub_portfolios = array($portfolio_umbrella[0]['p']['portfolio_id']); //fake portfolio of the umbrella
        foreach ($umbrella_portfolios as $umbrella_portfolio) {
            $sub_portfolios[] = $umbrella_portfolio['um']['portfolio_id']; //sub portfolios of the umbrella
        }

        $reports_ids = $this->Report->query("SELECT report_id FROM report WHERE portfolio_id IN ( " . implode(',', $sub_portfolios) . " )");
        $report = array();
        foreach ($reports_ids as $reports) {
            $report[] = $reports['report'];
        }
        return $report;
    }

    public function testUmbrella($portfolio_id)
    {
        //@$this->validate_param('string', $portfolio_id);
        return (strpos($portfolio_id, 'u_') !== false);
    }

    public function getPortfolioFromUmbrellaId($umbrella)
    {
        //@$this->validate_param('string', $umbrella);
        $connection = ConnectionManager::get('default');
        $umbrella_id = str_replace('u_', '', $umbrella);
        //$umbrella_portfolios = $this->Report->query("SELECT portfolio_id FROM umbrella_portfolio_mapping um WHERE umbrella_portfolio_id=".intval($umbrella_id));
        $portfolio_umbrella = $connection->execute("SELECT p.portfolio_id FROM umbrella_portfolio u, portfolio p WHERE u.iqid=p.iqid AND u.umbrella_portfolio_id=" . intval($umbrella_id))->fetchAll('assoc');

        return $portfolio_umbrella[0]['portfolio_id'];
    }

    public function get_sub_portfolios_from_umbrella_id($umbrella_id)
    {
        @$this->validate_param('string', $umbrella_id);
        $umbrella_id = str_replace('u_', '', $umbrella_id);
        $umbrella_portfolios = $this->Report->query("SELECT portfolio_id FROM umbrella_portfolio_mapping um WHERE umbrella_portfolio_id=" . intval($umbrella_id));
        $portfolio_umbrella = $this->Report->query("SELECT p.portfolio_id FROM umbrella_portfolio u, portfolio p WHERE u.iqid=p.iqid AND u.umbrella_portfolio_id=" . intval($umbrella_id));
        $sub_portfolios = array($portfolio_umbrella[0]['p']['portfolio_id']); //fake portfolio of the umbrella
        foreach ($umbrella_portfolios as $umbrella_portfolio) {
            $sub_portfolios[] = $umbrella_portfolio['um']['portfolio_id']; //sub portfolios of the umbrella
        }
        return $sub_portfolios;
    }

    //get last year and the earliest from report table
    public function getLastYear()
    {
        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setLayout('ajax');
            $connection = ConnectionManager::get('default');
            //$year = $this->Report->getLastestYearFromPortofolioId($this->request->getData['portfolio_id']);
            $years = $connection->query("select distinct period_year from report where period_year is not null order by period_year desc")->fetchAll('assoc');

            $this->set(compact('years'));
        }
    }

    public function getPeriodByPortfolio()
    {
        if ($this->request->is('ajax')) {
            //set view to Ajax
            $this->viewBuilder()->setLayout('ajax');
            $portfolio_id = $this->request->getData('portfolio_id');
            $periods = [];
            if (!empty($portfolio_id)) {
                if ($this->testUmbrella($portfolio_id)) {
                    $portfolio_id = $this->getPortfolioFromUmbrellaId($portfolio_id);
                }

                $this->loadModel('Damsv2.Portfolio');
                $portfolio = $this->Portfolio->find('all', ['conditions' => [
                                'Portfolio.portfolio_id' => $portfolio_id
                            ], 'recursive'  => 0])->first();

                $product_id = $portfolio->product_id;
                $this->loadModel('Damsv2.Product');
                $periods = $this->Product->getPeriodsByProduct($product_id);
            }

            $this->set(compact('periods'));
        }
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
            'inclusion'          => "/data/damsv2/upload/",
            'archive'            => "/upload/",
            'error'              => "/data/damsv2/error/",
            'docs'               => "/data/docs/",
            'reports'            => "/data/damsv2/reports/",
            'export'             => "/data/damsv2/export/",
            'pa'                 => "/sas/common/portfolio_analytics/",
            'sampling'           => "/data/damsv2/sampling/",
            'waiver_reasons'     => "/data/damsv2/waiver_reasons/",
            'sme_rating_mapping' => "/data/damsv2/sme_rating_mapping/",
            'templates_test'     => "/data/damsv2/templates_test/",
            'bulk'               => "/data/damsv2/bulk/",
            'dstoolbox'          => "/data/damsv2/DSToolbox/",
        );
        if (!(empty($download_file[2]))) {
            $download_file_path = $path[$download_file[1]] . '/' . $download_file[2] . '/' . $download_file[0];
        } else {
            $download_file_path = $path[$download_file[1]] . $download_file[0];
        }

//        $extra = array_keys($this->params['url']);
//        unset($extra[0]); //remove file index
//        //error_log("download extra : ".json_encode($this->params['url']));
//        $file = $this->params['url']['file'];
//        if (!empty($extra)) {
//            $file = $file . '&' . implode('&', $extra);
//        }
//        $file = str_replace('_xls', '.xls', $file);
//        $file = str_replace('_xlsx', '.xlsx', $file);
//        $file = str_replace('_xml', '.xml', $file);
        //error_log("download : ".$file);
        DownloadLib::download($download_file_path);
        exit();
    }

    public function getInclusionMaxPeriod()
    {
        if (!empty($this->request->getData('Product.product')) && !empty($this->request->getData('Report.year')) && !empty($this->request->getData('Report.period'))) {
            //$product_id = $this->request->getData('Product.product');

            $date_max = time();

            $year_selected = $this->request->getData('Report.year');
            $period = $this->request->getData('Report.period');

            $selected_month = null;
            $selected_day = null;
            switch ($period) {
                case "Q1":
                    $selected_month = 1;
                    $selected_day = 1;
                    break;

                case "Q2":
                    $selected_month = 3;
                    $selected_day = 1;
                    break;

                case "Q3":
                    $selected_month = 6;
                    $selected_day = 1;
                    break;

                case "Q4":
                    $selected_month = 9;
                    $selected_day = 1;
                    break;

                case "S1":
                    $selected_month = 1;
                    $selected_day = 1;
                    break;

                case "S2":
                    $selected_month = 6;
                    $selected_day = 1;
                    break;

                case "S2_spe":
                    $selected_month = 4;
                    $selected_day = 1;
                    break;

                case "S1_spe":
                    $selected_month = 10;
                    $selected_day = 1;
                    $year_selected--;
                    break;

                default:
                    return;
            }

            $date_selected = strtotime($selected_day . "-" . $selected_month . "-" . $year_selected);
            $return = json_encode(($date_selected >= $date_max));
        } else {
            $return = json_encode(false);
        }
        echo $return;
        exit();
    }

    public function pdlrMaxPeriod()
    {
        if (!empty($this->request->getData('Product.product')) && !empty($this->request->getData('Report.period_year')) && !empty($this->request->getData('Report.period_quarter'))) {
            //$product_id = $this->request->getData('Product.product');

            $date = time();

            $year_selected = $this->request->getData('Report.period_year');
            $period = $this->request->getData('Report.period_quarter');

            $selected_month = null;
            $selected_day = null;
            switch ($period) {
                case "Q1":
                    $selected_month = 1;
                    $selected_day = 1;
                    break;

                case "Q2":
                    $selected_month = 4;
                    $selected_day = 1;
                    break;

                case "Q3":
                    $selected_month = 7;
                    $selected_day = 1;
                    break;

                case "Q4":
                    $selected_month = 10;
                    $selected_day = 1;
                    break;

                default:
                    return;
            }
            $date_selected = strtotime($selected_day . "-" . $selected_month . "-" . $year_selected);

            $return = json_encode(($date_selected >= $date), 1);
        } else {
            $return = json_encode(false, 0);
        }
        echo $return;
        exit();
    }

    public function portfolioHasPDLR()
    {
        $result = ['hasLR' => true, 'hasPD' => true];
        if (!empty($this->request->getData('Portfolio.portfolio_id'))) {
            $portfolio_id = $this->request->getData('Portfolio.portfolio_id');
            $this->loadModel('Damsv2.Template');
            $result = $this->Template->portfolioHasPDLR($portfolio_id);
        }
        die(json_encode($result));
    }

    public function exportGraphData()
    {
//        $user_name = $this->Session->read('UserAuth.User.first_name') . ' ' . $this->Session->read('UserAuth.User.last_name');
//        $log_query = "INSERT INTO `eif`.`report_analytics_log` (user, report, datetime_begining) values ('" . $user_name . "', 'start page chart exported', NOW())";
//        $this->Report->query($log_query);
        $path_data = "/var/www/html/data/damsv2/export/graph_data.xlsx";
        $this->loadModel('Damsv2.CountSmesAltfinal');

        $result_graph = $this->CountSmesAltfinal->find()->all()->toArray();
        $this->Spreadsheet->generateExcelFromQuery($result_graph, ["count_smes_altfinal"], $path_data);

        $redirect = "/damsv2/ajax/download-file/graph_data.xlsx/export";
        $this->redirect($redirect);
    }

    public function exportSummaryData()
    {
//        $user_name = $this->Session->read('UserAuth.User.first_name') . ' ' . $this->Session->read('UserAuth.User.last_name');
//        $user_name = filter_var($user_name, FILTER_SANITIZE_STRING);
//        $log_query = "INSERT INTO `eif`.`report_analytics_log` (user, report, datetime_begining) values ('" . $user_name . "', 'start page summary exported', NOW())";
//        $this->Report->query($log_query);
        $path_data = "/var/www/html/data/damsv2/export/summary_data.xlsx";
        $this->loadModel('Damsv2.SummaryTable');
        $summary_data = $this->SummaryTable->find()->all()->toArray();

        $this->Spreadsheet->generateExcelFromQuery($summary_data, ["summary_table"], $path_data);

        $redirect = "/damsv2/ajax/download-file/summary_data.xlsx/export";
        $this->redirect($redirect);
    }

    /*
      check that the rates actually exists for the couple of currencies
     */

    public function currenciesHaveRate()
    {
        //portfolio.fx_rate_pdlr
        $report_curr = substr($this->request->getData('report_curr'), 0, 3);
        $report_curr = filter_var($report_curr, FILTER_SANITIZE_STRING);
        $contract_curr = substr($this->request->getData('contract_curr'), 0, 3);
        $contract_curr = filter_var($contract_curr, FILTER_SANITIZE_STRING);
        $portfolio_id = intval($this->request->getData('portfolio_id'));
        $this->loadModel('Damsv2.Portfolio');
        $portfolio = $this->Portfolio->find('all', array('conditions' => array('portfolio_id' => $portfolio_id)))->first();

        $rate_1 = array();
        if ($report_curr == "EUR") {
            $rate_1[] = true;
        }
        $rate_2 = array();
        if ($contract_curr == "EUR") {
            $rate_2[] = true;
        }
        $result = array('rate_report' => false, 'rate_contract' => false);
        if (!empty($portfolio) && !empty($portfolio->fx_rate_pdlr)) {
            $this->loadModel('Damsv2.FixedRate');
            $this->loadModel('Damsv2.Daily');
            $connection = ConnectionManager::get('default');
            $source = $portfolio->fx_rate_pdlr;
            $result['source'] = $source;
            switch ($source) {
                case 'FIXED':
                    // check in damsv2.fixed_rate
                    if ($report_curr != 'EUR') {
                        $rate_1 = $this->FixedRate->find('all', array('conditions' => array('portfolio_id' => $portfolio_id, 'currency' => $report_curr)))->first();
                    }
                    if ($contract_curr != 'EUR') {
                        $rate_2 = $this->FixedRate->find('all', array('conditions' => array('portfolio_id' => $portfolio_id, 'currency' => $contract_curr)))->first();
                    }
                    break;
                case 'ECB_latest':
                    // check in ecb.daily
                    if ($report_curr != 'EUR') {
                        $rate_1 = $this->Daily->find('all', array('conditions' => array('CURRENCY' => $report_curr)))->first();
                    }
                    if ($contract_curr != 'EUR') {
                        $rate_2 = $this->Daily->find('all', array('conditions' => array('CURRENCY' => $contract_curr)))->first();
                    }
                    break;
                case 'NCB_latest':
                    // damsv2.ncb_rate
                    if ($report_curr != 'EUR') {
                        $rate_1 = $connection->execute("SELECT * FROM ncb_rate WHERE currency='" . $report_curr . "'")->fetchAll('assoc');
                    }
                    if ($contract_curr != 'EUR') {
                        $rate_2 = $connection->execute("SELECT * FROM ncb_rate WHERE currency='" . $contract_curr . "'")->fetchAll('assoc');
                    }
                    break;
                case 'ECB_eop':
                case 'ECB_eopp':
                    // no check in this case
                    $rate_1 = true;
                    $rate_2 = true;
                    break;
                default:
                    $rate_1 = $rate_2 = array();
                    error_log("missing portfolio value fx_rate_pdlr for  " . $portfolio_id);
                    break;
            }
            $result = array('rate_report' => !empty($rate_1), 'rate_contract' => !empty($rate_2));
        } else {
            error_log("missing portfolio " . $portfolio_id . " : does not exists of missing fx_rate_pdlr");
        }
        $this->set(['result' => $result]);
        $this->viewBuilder()->setOption('serialize', true);
        $this->RequestHandler->renderAs($this, 'json');
    }

    public function portfolioHasTemplateBR()
    {
        $portfolio_id = $this->request->getData('Import.portfolio_id');
        $this->loadModel('Damsv2.Template');
        $templates_br = $this->Template->portfolioHasTemplateBR($portfolio_id);
        echo json_encode($templates_br);
        exit();
    }

    public function getFieldsMapping()
    {
        if ($this->request->is('ajax')) {
            $this->loadModel('Damsv2.MappingTable');
            $this->loadModel('Damsv2.MappingColumn');
            //set view to Ajax
            $this->viewBuilder()->setLayout('ajax');
            $table_name = $this->request->getData('Mapping_table.table_name');

            $field_name = !empty($this->request->getData('MappingColumn.table_field')) ? $this->request->getData('MappingColumn.table_field') : null;

            $conditions = ['excel_column >' => 0, 'table_field <>' => 'action'];
            if (!empty($table_name)) {
                $table = $this->MappingTable->find('all', [
                            'fields'     => ['table_id'],
                            'conditions' => ['table_name' => $table_name],
                        ])->firstOrFail();

                $conditions = Helpers::arrayPushAssoc($conditions, 'table_id', $table->table_id);

                $fields = $this->MappingColumn->find('list', [
                    'keyField'   => 'table_field',
                    'valueField' => 'table_field',
                    'conditions' => [$conditions],
                    'order'      => 'table_field asc',
                ]);
            } else {
                $fields = $this->MappingColumn->find('list', [
                    'keyField'   => 'table_field',
                    'valueField' => 'table_field',
                    'conditions' => [$conditions],
                    'order'      => 'table_field asc',
                ]);
            }
            $this->set('fields', $fields);
            $this->set('field_name', $field_name);
        }
    }

    private function getUmbrellaIqid()
    {
        $connection = ConnectionManager::get('default');
        $umbrella_iqid = $connection->query('SELECT iqid FROM umbrella_portfolio')->fetchAll('assoc');

        $collection = new Collection($umbrella_iqid);
        $iqids = $collection->extract('iqid')->toList();
        return $iqids;
    }

    public function exportDico($id = null)
    {
        //if ($this->request->is('ajax')) {
        //set view to Ajax
        $this->viewBuilder()->setLayout('ajax');
        $id = $this->request->getData('DictionaryValue.dictionary_id');


        $this->loadModel('Damsv2.Portfolio');

        //get request params
        $did = $this->request->getQuery('Dictionary.id');
        $code = $this->request->getData('Dictionary.code');
        $translation = $this->request->getData('Dictionary.translation');
        $label = $this->request->getData('Dictionary.label');
        $conditions = array();
        $conditions = Helpers::arrayPushAssoc($conditions, 'dictionary_id', $id);

        if ($did != null && $did !== '') {
            $did = '%' . $did . '%';
            $conditions = Helpers::arrayPushAssoc($conditions, 'cast(dicoval_id as char) LIKE', $did);
        }

        if ($code) {
            $code = '%' . $code . '%';
            $conditions = Helpers::arrayPushAssoc($conditions, 'code LIKE', $code);
        }

        if ($translation) {
            $translation = '%' . $translation . '%';
            $conditions = Helpers::arrayPushAssoc($conditions, 'translation LIKE', $translation);
        }

        if ($label) {
            $label = '%' . $label . '%';
            $conditions = Helpers::arrayPushAssoc($conditions, 'label LIKE', $label);
        }

        $dvalues = $this->getTableLocator()->get('Damsv2.DictionaryValues');

        $results = $dvalues->find('all', [
                    'conditions' => [$conditions]
                ])->toArray();

        //dd($results);
        $filepath = '/var/www/html/data/damsv2/export/dico_values' . time() . '.xlsx';
        $skeleton = ['DictionaryValue'];
        //error_log("export_dico_values values " . json_encode($results));
        $this->Spreadsheet->generateExcelFromQuery($results, $skeleton, $filepath);
        $filepath = basename($filepath);
        $this->set(compact('filepath'));
        //}
    }
	

	public function getperiodsdoublereport()
	{
		$POST = $this->request->getData('Report');
		if (!empty($POST['product_id'])
			&& !empty($POST['period_quarter'])
			&& !empty($POST['period_year'])
		)
		{
			$product_id = $POST['product_id'];
			$portfolio_id = null;
			if (!empty($POST['portfolio_id']))
			{
				// should not work for umbrella (out of scope)
				$portfolio_id = $POST['portfolio_id'];
			}
			else
			{
				$this->loadModel('Damsv2.Portfolio');
				$portfolio_id = $this->Portfolio->find('list', array(
					'conditions' => array('Portfolio.product_id' => $product_id),
					'fields' => array('Portfolio.portfolio_id'),
				));
			}
			$period = $POST['period_quarter'];
			$year = $POST['period_year'];

			$conditions = array(
				'Report.period_quarter' => $period,
				'Report.period_year' => $year,
				'Report.portfolio_id' => $portfolio_id,
				'Report.visible' => 1,
				'Report.report_type' => 'regular',
				'Template.template_type_id' => 1,
			);
			$this->loadModel('Damsv2.Report');
			$existing_reports = $this->Report->find('all', array(
				'contain'    => ['Template'],
				'conditions' => $conditions))->first();
			$show_warning = !empty($existing_reports);
			$this->set('show_warning', $show_warning);
		}
		else
		{
			$this->set('show_warning', false);
		}
	}

}
