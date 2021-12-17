<?php

declare(strict_types=1);

namespace Damsv2\Controller;

use Cake\Event\EventInterface;
use Cake\Datasource\ConnectionManager;
use Cake\Collection\Collection;
use KubAT\PhpSimple\HtmlDomParser;
use DateInterval;
use DatePeriod;
use Cake\I18n\FrozenTime;
use App\Lib\Helpers;

/**
 * Portfolio Controller
 *
 * @property \App\Model\Table\PortfolioTable $Portfolio
 * @method \App\Model\Entity\Portfolio[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PortfolioController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        //$this->loadComponent('Security');
        $this->loadComponent('SAS');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        //$this->Security->setConfig('blackHoleCallback', 'blackhole');
    }

    public $paginate = [
        'limit' => 25,
        'order' => [
            'Portfolio.portfolio_id' => 'desc'
        ]
    ];

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        // data for search dropdowns
        $guarantee_type = $this->Portfolio->find('list', [
                    'keyField'   => 'guarantee_type',
                    'valueField' => 'guarantee_type',
                    'group'      => 'guarantee_type',
                    'conditions' => ['guarantee_type is not' => null]
                ])->toArray();
        $mandates = $this->getTableLocator()->get('Damsv2.Mandate');
        $mandate_type = $mandates->find('list', [
                    'keyField'   => 'mandate_name',
                    'valueField' => 'mandate_name',
                    'order'      => 'mandate_name'
                ])->toArray();

        $gsdeal_status = $this->Portfolio->find('list', [
                    'keyField'   => 'gs_deal_status',
                    'valueField' => 'gs_deal_status',
                    'group'      => 'gs_deal_status',
                    'conditions' => ['gs_deal_status is not' => null]
                ])->toArray();

        $status_list = $this->Portfolio->find('list', [
                    'keyField'   => 'status_portfolio',
                    'valueField' => 'status_portfolio',
                    'group'      => 'status_portfolio'
                ])->toArray();

        //get request params
        $guarantee_key = $this->request->getQuery('guarid');
        $portfolio_key = $this->request->getQuery('portid');
        $deal_key = $this->request->getQuery('dealid');
        $man_key = $this->request->getQuery('manid');
        $con_key = $this->request->getQuery('contid');
        $st_key = $this->request->getQuery('stid');

        $conditions = [];

        if ($portfolio_key) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Portfolio.portfolio_id', $portfolio_key);
        }
        if ($deal_key) {
            $deal_key = '%' . $deal_key . '%';
            $conditions = Helpers::arrayPushAssoc($conditions, 'Portfolio.deal_name LIKE', $deal_key);
        }
        if ($man_key) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Portfolio.mandate', $man_key);
        }
        if ($guarantee_key) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Portfolio.guarantee_type', $guarantee_key);
        }
        if ($con_key) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Portfolio.gs_deal_status', $con_key);
        }
        if ($st_key) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Portfolio.status_portfolio', $st_key);
        }

        $query = $this->Portfolio->find('all', [
            'conditions' => [$conditions]
        ]);

        $portfolio = $this->paginate($query, ['contain' => ['Product']]);
        $this->set(compact('portfolio', 'guarantee_type', 'mandate_type', 'gsdeal_status', 'status_list'));
    }

    public function detail($id = null)
    {
        if (empty($id)) {
            throw new NotFoundException;
        }

        $portfolio = $this->Portfolio->get($id, [
            'contain' => ['Product'],
        ]);

        //portfolio rates
        $rates = $this->getTableLocator()->get('Damsv2.PortfolioRates');
        $portfolio_rates = $rates->find()
                ->where(['portfolio_id' => $id])
                ->all();

        //fixed rates
        $fixedrates = $this->getTableLocator()->get('Damsv2.FixedRate');
        $fixed_fx_rate = $fixedrates->find()
                ->where(['portfolio_id' => $id])
                ->where(['currency NOT IN' => $portfolio->currency])
                ->order(['modified' => 'DESC']);

        //number of periods to include
        $reporting_frequency = $portfolio->product->reporting_frequency;
        if (!empty($portfolio->inclusion_start_date) && !empty($portfolio->inclusion_end_date)) {
            $interval = $reporting_frequency == 'Quarterly' ? new DateInterval('P3M') : new DateInterval('P6M'); //3 months = 1 quarter, 6 months = 1 semester
            $period = new DatePeriod($portfolio->inclusion_start_date, $interval, $portfolio->inclusion_end_date);
            $number_of_periods = iterator_count($period);
        } else {
            $number_of_periods = 0;
        }

        $this->set('fixed_fx_rate', $fixed_fx_rate);
        $this->set('portfolio_rates', $portfolio_rates);
        $this->set('number_of_periods', $number_of_periods);
        $this->set(compact('portfolio'));

        //form processing part
        $user_id = $this->userIdentity()->get('id'); //$this->UserAuth->getUserId();
        // store dictionary translations
        if (!empty($this->request->getData('Portfolio.store'))) {
            // group retrieving // http://vmu-sas-01:8080/browse/PLATFORM-259
//            $groups = CakeSession::read('UserAuth.UserGroups');
//            if (!is_array($groups))
//                $groups = array($groups);
//            if (!empty($groups))
//                foreach ($groups as $group) {
//                    $groupsnames[] = $group['alias_name'];
//                }
//            if (in_array('ReadOnlyDams', $groupsnames)) {
//                $this->Flash->error(__('You have a read only profile. The status cannot be changed.'));
//                $this->redirect("/damsv2/portfolio/status");
//            }

            $sasResult = $this->SAS->curl(
                    "dico_db.sas", [
                "portfolio_id" => $this->request->getData('Portfolio.portfolio_id'),
                "save"         => 0
                    ],
                    false,
                    false
            );
            $sasResult = $this->SAS->curl(
                    "dico_db.sas", [
                "portfolio_id" => $this->request->getData('Portfolio.portfolio_id'),
                "save"         => 1
                    ],
                    false,
                    false
            );
            $this->logDams('Store dictionaries translations:{"portfolio_id": ' . $this->request->getData('Portfolio.portfolio_id') . '} ', 'dams', 'Save dictionary translation');

            $dom = HtmlDomParser::str_get_html($sasResult);

            $table = $dom->find('table');
            if (!empty($table)) {
                foreach ($table as $t) {
                    $t->class = 'table table-bordered table-striped';
                    $t->frame = '';
                    $sasResult = $t->outertext;
                }
                $this->Flash->success('The following information has been saved: ' . $sasResult, ['escape' => false]);
            } else {
                $this->Flash->success('Dictionaries translations have been stored', ['escape' => false]);
            }
        }

        if(!empty($this->request->getData('Portfolio.statistics'))) {
            if (!$this->Portfolio->isEditableDraft($this->request->getData('Portfolio.portfolio_id'))) {
                $this->Flash->warning('Warning: Statistics based on the latest included DRAFT report (pending validation)');
            }
            
            //apv breakdown
            $sasResult_apv_breakdown = $this->SAS->curl(
                    "cb_apv_breakdown_db.sas", array(
                "portfolio_id" => $this->request->getData('Portfolio.portfolio_id'),
                    ),
                    false,
                    false
            );

            // show statistics
            $sasResult = $this->SAS->curl(
                    'portfolio_stats.sas', array(
                'portfolio_id' => $this->request->getData('Portfolio.portfolio_id'),
                'user_id'      => $user_id,
                    ),
                    false,
                    false
            );

            $res = $this->removeEmptyLineInSasResult($sasResult);
            $this->Flash->success($res, ['escape' => false]);           
        }

        //apv breakdown
        if (!empty($this->request->getData('Portfolio.refresh_apv'))) {
            if (!$this->Portfolio->isEditableDraft($this->request->getData('Portfolio.portfolio_id'))) {
                $this->Flash->error('The latest report for this portfolio is still in draft version. The report has to be validated before proceeding with the recalculation.');
                $this->redirect($this->referer());
            }

            $sasResult_apv_refresh = $this->SAS->curl(
                    'call_back_db.sas', [
                'portfolio_id' => $this->request->getData('Portfolio.portfolio_id'),
                'save'         => 0,
                'close'        => 0,
                'user_id'      => $user_id,
                    ],
                    false,
                    false
            );

            sleep(2);

            // save
            $sasResult = $this->SAS->curl(
                    'call_back_db.sas', [
                'portfolio_id' => $this->request->getData('Portfolio.portfolio_id'),
                'save'         => 1,
                'close'        => 0,
                'user_id'      => $user_id,
                    ],
                    false,
                    false
            );

            if (strpos($sasResult, 'This request completed with errors.') !== false) {
                $error_message = 'SAS response : This request completed with errors.';
            }
            $dom = HtmlDomParser::str_get_html($sasResult_apv_refresh);

            $table = $dom->find('table');
            $sasResult = '';
            foreach ($table as $t) {
                $t->class = 'table table-bordered table-striped';
                $t->frame = '';
                $sasResult .= $t->outertext;
            }
            if (empty($sasResult)) {
                $this->Flash->error($error_message);
            } else {
                $this->Flash->success($sasResult, ['escape' => false]);
            }
        }

        //change status portfolio
        if (!empty($this->request->getData('Portfolio.status_portfolio'))) {
            // group retrieving // http://vmu-sas-01:8080/browse/PLATFORM-259
//            $groups = CakeSession::read('UserAuth.UserGroups');
//            if (!is_array($groups))
//                $groups = [$groups];
//            if (!empty($groups))
//                foreach ($groups as $group) {
//                    $groupsnames[] = $group['alias_name'];
//                }
//            if (in_array('ReadOnlyDams', $groupsnames)) {
//                $this->Flash->error(__('You have a read only profile. The status cannot be changed.'));
//            } else {

            $previous_status = $portfolio->status_portfolio;
            $portfolio->status_portfolio = $this->request->getData('Portfolio.status_portfolio');

            switch ($this->request->getData('Portfolio.status_portfolio')) {
                case 'OPEN':
                    $early_termination = 'No';
                    $portfolio->apv_at_closure = NULL;
                    $portfolio->closure_date = NULL;
                    break;
                case 'EARLY TERMINATED':
                    $early_termination = 'Yes';
                    $portfolio->closure_date = date('Y-m-d');
                    break;
            }
            $this->Portfolio->save($portfolio);

            $this->loadModel('Damsv2.Transactions');
            $tcount = $this->Transactions->find()
                            ->where(['portfolio_id' => $this->request->getData('Portfolio.portfolio_id')])
                            ->all()->count();

            if ($tcount > 0) {
                $connection = ConnectionManager::get('default');
                $connection->update('transactions', ['early_termination' => $early_termination], ['portfolio_id' => $this->request->getData('Portfolio.portfolio_id')]);
                $msg = 'The status of the Portfolio - ' . $portfolio->portfolio_name . ' - has been successfully changed. ' . $tcount . ' Transactions have been updated with the early termination set to : ' . $early_termination;

                $this->Flash->success($msg, ['escape' => false]);
            } else {
                $msg = 'The status of the Portfolio - ' . $portfolio->portfolio_name . ' - has been successfully changed. No transactions have been updated with the early termination set to : ' . $early_termination;
                $this->Flash->warning($msg, ['escape' => false]);
            }
            $log_info = [
                'portfolio_id'    => $this->request->getData('Portfolio.portfolio_id'),
                'previous_status' => $previous_status,
                'new_status'      => $this->request->getData('Portfolio.status_portfolio'),
            ];
            $this->logDams('Status change: ' . json_encode($log_info), 'dams', 'Status change');
//            }
        }
    }

    public function statistics()
    {
        if ($this->request->is('post') && !empty($this->request->getData('Portfolio.portfolio_id'))) {
            $portfolio = $this->Portfolio->get($this->request->getData('Portfolio.portfolio_id'));

            $this->set(compact('portfolio'));

            if (!$portfolio->isEditableDraft()) {
                $this->Flash->warning('Warning: Statistics based on the latest included DRAFT report - pending validation');
            }

            $user_id = $this->userIdentity()->get('id');
            //$date = UniformLib::uniform(time(), 'datetime');
            $current_date = FrozenTime::now();
            $date = $current_date->i18nFormat('yyyy-MM-dd HH:mm:ss');

            // show statistics
            $sasResult = $this->SAS->curl(
                    'portfolio_stats.sas', [
                'portfolio_id' => $portfolio->portfolio_id,
                'user_id'      => $user_id,
                    ],
                    false,
                    false
            );
            $dom = HtmlDomParser::str_get_html($sasResult);

            $apv_breakdown_link = $dom->find('#apv_breakdown');

            $apv_breakdown_path = null;
            foreach ($apv_breakdown_link as $apv) {
                if (!empty($apv->href)) {
                    $apv_breakdown_href = $apv->href;
                    $apv_breakdown_path_array = explode('/', $apv_breakdown_href);
                    $apv_breakdown_path = $apv_breakdown_path_array[count($apv_breakdown_path_array) - 1];
                }
                $apv->outertext = '';
            }

            $res = $this->removeEmptyLineInSasResult($dom->outertext);

            if (strpos($res, 'This request completed with errors.') !== false) {
                $this->Flash->error('SAS response: This request completed with errors.');
            }

            $this->set('apv_breakdown_path', $apv_breakdown_path);

            $this->set('result', $res);
            $this->set('date', $date);
        } else {
            //set input values
            $this->loadModel('Damsv2.Product');
            $this->set('products', $this->Product->getProducts());

            $portfolios = $this->Portfolio->find('list', [
                        'contain'    => ['Product'],
                        'fields'     => ['Product.name', 'Portfolio.portfolio_name', 'Portfolio.portfolio_id'],
                        'keyField'   => 'portfolio_id',
                        'valueField' => 'portfolio_name',
                        'groupField' => 'product.name',
                        'order'      => ['Product.name', 'Portfolio.portfolio_name'],
                        'conditions' => ['Portfolio.product_id NOT IN' => [22, 23], 'Portfolio.iqid NOT IN' => $this->getUmbrellaIqid()]
                    ])->toArray();
            $this->set('portfolios', $portfolios);
        }
    }

    private function removeEmptyLineInSasResult($sasResult)
    {
        $dom = HtmlDomParser::str_get_html($sasResult);

        $table = $dom->find('table');
        foreach ($table as $t) {
            $th = $t->find('th');
            if (!empty($th[0]) && $th[0]->innertext == '') {
                $thead = $t->find('thead');
                $thead[0]->outertext = '';
                $first_tr = $t->find('tr');
                if (count($first_tr) > 0) {
                    $td = $first_tr[1]->find('td');
                    foreach ($td as $td_top) {
                        $td_top->style = "border-top-color: #808080;";
                    }
                }
            }
        }
        return $dom->save();
    }

    /**
     * View method
     *
     * @param string|null $id Portfolio id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        if (empty($id)) {
            throw new NotFoundException;
        }
        $portfolio = $this->Portfolio->get($id, [
            'contain' => ['Product', 'Sme', 'Template'],
        ]);

        $this->set(compact('portfolio'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $portfolio = $this->Portfolio->newEmptyEntity();
        if ($this->request->is('post')) {
            $portfolio = $this->Portfolio->patchEntity($portfolio, $this->request->getData());
            if ($this->Portfolio->save($portfolio)) {
                $this->Flash->success(__('The portfolio has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The portfolio could not be saved. Please, try again.'));
        }
        $products = $this->Portfolio->Product->find('list', ['limit' => 200]);
        $sme = $this->Portfolio->Sme->find('list', ['limit' => 200]);
        $template = $this->Portfolio->Template->find('list', ['limit' => 200]);
        $this->set(compact('portfolio', 'products', 'sme', 'template'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Portfolio id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if (empty($id)) {
            throw new NotFoundException;
        }
        $portfolio = $this->Portfolio->get($id, [
            'contain' => ['Sme', 'Template'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $portfolio = $this->Portfolio->patchEntity($portfolio, $this->request->getData());
            if ($this->Portfolio->save($portfolio)) {
                $this->Flash->success(__('The portfolio has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The portfolio could not be saved. Please, try again.'));
        }
        $products = $this->Portfolio->Product->find('list', ['limit' => 200]);
        $sme = $this->Portfolio->Sme->find('list', ['limit' => 200]);
        $template = $this->Portfolio->Template->find('list', ['limit' => 200]);
        $this->set(compact('portfolio', 'products', 'sme', 'template'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Portfolio id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $portfolio = $this->Portfolio->get($id);
        if ($this->Portfolio->delete($portfolio)) {
            $this->Flash->success(__('The portfolio has been deleted.'));
        } else {
            $this->Flash->error(__('The portfolio could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function changeStatus()
    {
        $connection = ConnectionManager::get('default');
        //post processing
        if ($this->request->is('post') || !empty($this->request->getData('Portfolio.portfolio_id'))) {

            if (!empty($this->request->getData('Portfolio.portfolio_id')) && !empty($this->request->getData('Portfolio.status'))) {
                $portid = $this->request->getData('Portfolio.portfolio_id');
                $portfoliostatus = $this->Portfolio->get($portid);


                // group retrieving // http://vmu-sas-01:8080/browse/PLATFORM-259
//                $groups = CakeSession::read('UserAuth.UserGroups');
//                if (!is_array($groups))
//                    $groups = [$groups];
//                if (!empty($groups))
//                    foreach ($groups as $group) {
//                        $groupsnames[] = $group['alias_name'];
//                    }
//                if (in_array('ReadOnlyDams', $groupsnames)) {
//                    $this->Flash->error(__('You have a read only profile. The status cannot be changed.'));
//                    $this->redirect("/damsv2/portfolio/status");
//                }              
                //store the old status
                $previous_status = $portfoliostatus->status_portfolio;
                $portfoliostatus->status_portfolio = $this->request->getData('Portfolio.status');

                switch ($this->request->getData('Portfolio.status')) {
                    case 'OPEN':
                        $early_termination = 'No';
                        $portfoliostatus->apv_at_closure = NULL;
                        $portfoliostatus->closure_date = NULL;
                        break;
                    case 'EARLY TERMINATED':
                        $early_termination = 'Yes';
                        $portfoliostatus->closure_date = date('Y-m-d');
                        break;
                }

                //save to database
                $this->Portfolio->save($portfoliostatus);

                $this->loadModel('Damsv2.Transactions');
                $tcount = $this->Transactions->find()
                                ->where(['portfolio_id' => $portid])
                                ->all()->count();

                if ($tcount > 0) {
                    $connection->update('transactions', ['early_termination' => $early_termination], ['portfolio_id' => $portid]);
                    $msg = 'The status of the Portfolio - ' . $portfoliostatus->portfolio_name . ' - has been successfully changed. ' . $tcount . ' Transactions have been updated with the early termination set to : ' . $early_termination;
                    $this->Flash->success($msg, ['escape' => false]);
                } else {
                    $msg = 'The status of the Portfolio - ' . $portfoliostatus->portfolio_name . ' - has been successfully changed. No transactions have been updated with the early termination set to : ' . $early_termination;
                    $this->Flash->warning($msg, ['escape' => false]);
                }

                $log_info = [
                    'portfolio_id'    => $portid,
                    'previous_status' => $previous_status,
                    'new_status'      => $this->request->getData('Portfolio.status'),
                ];
                $this->logDams('Status change: ' . json_encode($log_info), 'dams', 'Status change');
            }
        }

        //portfolio list with the pagination
        $conditions = [];

        $cond_portfolio = ['product_id NOT IN' => [22, 23], 'iqid NOT IN' => $this->getUmbrellaIqid()];

        // Top dashboard filters
        $portfolios = $this->Portfolio->find('list', [
                    'fields'     => ['portfolio_id', 'portfolio_name', 'mandate'],
                    'keyField'   => 'portfolio_id',
                    'valueField' => 'portfolio_name',
                    'groupField' => 'mandate',
                    'order'      => 'mandate',
                    'conditions' => $cond_portfolio
                ])->toArray();
        $this->loadModel('Damsv2.Product');
        $products = $this->Product->getProducts();

        $this->set(compact('portfolios', 'products'));

        if ($this->request->is("get")) {
            //product id
            $prodid = $this->request->getQuery('product_id');
            if ($prodid) {
                $conditions = Helpers::arrayPushAssoc($conditions, 'product_id', $prodid);
                //$cond_portfolio = Helpers::arrayPushAssoc($cond_portfolio, 'product_id', $prodid);
            }

            //portfolio id
            $portid = $this->request->getQuery('portfolio_id');
            if ($portid) {
                $conditions = Helpers::arrayPushAssoc($conditions, 'Portfolio.portfolio_id', $portid);
            }
        }

        try {
            $query = $this->Portfolio->find('all', [
                'conditions' => [$conditions]
            ]);
            $portfolio = $this->paginate($query);
        } catch (NotFoundException $e) {//redirect to 
            $this->redirect([
                "controller" => "portfolio",
                "action"     => "change-status"
                    ]
            );
        }
        $this->set(compact('portfolio'));
    }

    public function eurCurr()
    {
        $this->set('tables', [
            'transactions'          => 'A2',
            'guarantees'            => 'A3',
            'included_transactions' => 'B',
            'expired_transactions'  => 'D',
            'excluded_transactions' => 'E',
        ]);
        $this->loadModel('Damsv2.Product');
        $this->set('products', $this->Product->getProducts());

        $portfolios = $this->Portfolio->find('list', [
                    'contain'    => ['Product'],
                    'fields'     => ['Product.name', 'Portfolio.portfolio_name', 'Portfolio.portfolio_id'],
                    'keyField'   => 'portfolio_id',
                    'valueField' => 'portfolio_name',
                    'groupField' => 'product.name',
                    'order'      => ['Product.name', 'Portfolio.portfolio_name'],
                    'conditions' => ['Portfolio.product_id NOT IN' => [22, 23], 'Portfolio.iqid NOT IN' => $this->getUmbrellaIqid()]
                ])->toArray();
        $this->set('portfolios', $portfolios);

        $sasResult = '';
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
//                $this->Session->setFlash("You are currently in a read only profile, this functionality is disabled", "flash/error");
//                $this->redirect($this->referer());
//            }

            if (!empty($this->request->getData('Portfolio.portfolio_id')) && !empty($this->request->getData('table'))) {

                if (!$this->Portfolio->isEditableDraft($this->request->getData('Portfolio.portfolio_id'))) {
                    $this->Flash->error("The latest report for this portfolio is still in draft version. The report has to be validated before proceeding with the recalculation.");
                    $this->redirect($this->referer());
                }

                $sasResult = $this->SAS->curl(
                        "conversions_db.sas", array(
                    "portfolio_id" => $this->request->getData('Portfolio.portfolio_id'),
                    "table"        => $this->request->getData('table')
                        ),
                        false,
                        false
                );

                $dom = HtmlDomParser::str_get_html($sasResult);

                $rep = $dom->find('#sasres');
                foreach ($rep as $t) {
                    $sasResult = $t->innertext;
                }
                if (strpos($sasResult, 'The Values were converted for') === false) {
                    $this->Flash->error($sasResult, ['escape' => false]);
                } else {
                    $log_info = [
                        'portfolio_id' => $this->request->getData('Portfolio.portfolio_id'),
                        'table'        => $this->request->getData('table'),
                    ];
                    $this->logDams('Eur and contract currency equivalencies calculated: ' . json_encode($log_info), 'dams', 'Calculate curr equivalencies');
                    $this->Flash->success($sasResult, ['escape' => false]);
                }
            }
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

    public function pdf($id = null)
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $portfolio = $this->Portfolio->get($id);

        if (!$portfolio->isEditableDraft()) {
            $this->set('warningmsg', 'Warning: Statistics based on the latest included DRAFT report (pending validation)');
        }

        $user_id = $this->userIdentity()->get('id');
        //$date = UniformLib::uniform(time(), 'datetime');
        $current_date = FrozenTime::now();
        $date = $current_date->i18nFormat('yyyy-MM-dd HH:mm:ss');

        // show statistics
        $sasResult = $this->SAS->curl(
                'portfolio_stats.sas', [
            'portfolio_id' => $portfolio->portfolio_id,
            'user_id'      => $user_id,
                ],
                false,
                false
        );
        $dom = HtmlDomParser::str_get_html($sasResult);

        $apv_breakdown_link = $dom->find('#apv_breakdown');

        foreach ($apv_breakdown_link as $apv) {
            $apv->outertext = '';
        }

        $res = $this->removeEmptyLineInSasResult($dom->outertext);

        $this->set('date', $date);
        $this->set('title', $portfolio->portfolio_name);
        $this->set('result', $res);


        $this->viewBuilder()->setClassName('CakePdf.Pdf');
        $this->viewBuilder()->setOption(
                'pdfConfig',
                [
                    'orientation'      => 'portrait',
                    'download'         => true, // This can be omitted if "filename" is specified.
                    'filename'         => 'Statistics_' . $id . '.pdf', //// This can be omitted if you want file name based on URL.
                    'user-style-sheet' => WWW_ROOT . 'css/site.css',
                ]
        );
    }

}
