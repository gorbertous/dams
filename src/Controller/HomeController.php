<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Home Controller
 *
 */
class HomeController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        //$this->loadComponent('Security');
        $this->loadComponent('RequestHandler');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        //$this->Security->setConfig('blackHoleCallback', 'blackhole');
    }

    /**
     * Home method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function home()
    {
        //load custom home layout
        //$this->viewBuilder()->setLayout('damsHome');

       
    }

    public function daily()
    {
        $this->RequestHandler->renderAs($this, 'json');
        if ($this->request->is('ajax')) {
            header('Content-type: application/json');

            //if(isset($this->params['url']['callback'])) {
            if (!empty($this->request->getQueryParams()['callback'])) {
                $this->loadModel('Daily');

                $result = $this->Daily->find('all', array('order' => array('Daily.ORDER')))->toArray();
                if (count($result) > 0) {
                    foreach ($result as $row) {
                        $trend = $row['TREND'];
                        $obs_value = floatval($row['OBS_VALUE']);
                        $currency = $row['CURRENCY'];

                        $data[] = array('currency' => $currency, 'trend' => $trend, 'value' => $obs_value);
                    }

                    echo $this->request->getQueryParams()['callback'] . '(' . json_encode($data) . ')';
                }
            }
        }
    }

    public function jsonp()
    {
        if ($this->request->is('ajax')) {
            $this->RequestHandler->renderAs($this, 'json');
            //header('Content-type: application/json');
            //if(isset($this->params['url']['currency']) && isset($this->params['url']['callback'])) {
            if (!empty($this->request->getQueryParams()['currency']) && !empty($this->request->getQueryParams()['callback'])) {
                $names = array(
                    'USD' => 'US dollar (USD)',
                    'JPY' => 'Japanese yen (JPY)',
                    'BGN' => 'Bulgarian lev (BGN)',
                    'CZK' => 'Czech koruna (CZK)',
                    'DKK' => 'Danish krone (DKK)',
                    'GBP' => 'Pound sterling (GBP)',
                    'HUF' => 'Hungarian forint (HUF)',
                    'LTL' => 'Lithuanian litas (LTL)',
                    'LVL' => 'Latvian lats (LVL)',
                    'PLN' => 'Polish zloty (PLN)',
                    'RON' => 'New Romanian leu (RON)',
                    'SEK' => 'Swedish krona (SEK)',
                    'CHF' => 'Swiss franc (CHF)',
                    'NOK' => 'Norwegian krone (NOK)',
                    'RUB' => 'Russian rouble (RUB)',
                    'TRY' => 'Turkish lira (TRY)',
                    'AUD' => 'Australian dollar (AUD)',
                    'CAD' => 'Canadian dollar (CAD)',
                    'HRK' => 'Croatian kuna (HRK)',
                    'ILS' => 'Israeli shekel (ILS)',
                    'NOK' => 'Norwegian krone (NOK)',
                );

                $currency = $this->request->getQueryParams()['currency'];

                $this->loadModel('Rate');
                $result = $this->Rate->find('all', array(
                            "conditions" => array("Rate.CURRENCY" => $currency)
                        ))->toArray();

                date_default_timezone_set('UTC');

                if (count($result) > 0) {
                    $lastest_date = null;
                    foreach ($result as $row) {
                        $date = strtotime('' . $row['TIME_PERIOD']) * 1000;
                        $obs_value = floatval($row['OBS_VALUE']);
                        $currency = $row['CURRENCY'];

                        $respons[] = array($date, $obs_value);
                    }
                    $lastest_date = date('d F Y', strtotime('' . $row['TIME_PERIOD']));

                    $data = array(
                        'chart'         => array(
                            'renderTo' => 'container'
                        ),
                        'credits'       => array(
                            'text' => 'Source: European Central Bank',
                            'href' => 'http://www.ecb.int/stats/exchange/eurofxref/html/eurofxref-graph-' . strtolower($currency) . '.en.html'
                        ),
                        'title'         => array(
                            'text' => $names[$currency]
                        ),
                        'subtitle'      => array(
                            'text' => 'Latest (' . $lastest_date . '): EUR 1 = ' . $currency . ' ' . number_format($obs_value, 4, ".", ",")
                        ),
                        'rangeSelector' => array(
                            'selected' => 3,
                            'buttons'  => array(
                                array('type' => 'month', 'count' => 1, 'text' => '1m'),
                                array('type' => 'month', 'count' => 3, 'text' => '3m'),
                                array('type' => 'month', 'count' => 6, 'text' => '6m'),
                                array('type' => 'ytd', 'text' => 'YTD'),
                                array('type' => 'year', 'count' => 1, 'text' => '1y'),
                                array('type' => 'year', 'count' => 5, 'text' => '5y'),
                                array('type' => 'year', 'count' => 10, 'text' => '10y'),
                                array('type' => 'all', 'text' => 'All')
                            )
                        ),
                        'series'        => array(
                            array(
                                'name'    => array($currency),
                                'data'    => $respons,
                                'tooltip' => array('valueDecimals', '4')
                            )
                        )
                    );

                    $this->set('out', $this->request->getQueryParams()['callback'] . '(' . json_encode($data) . ')');
                }
            }
        }
    }

}
