<?php

declare(strict_types=1);

namespace Damsv2\Controller;

use Cake\Event\EventInterface;
use Cake\Datasource\ConnectionManager;
use Cake\Collection\Collection;
use App\Lib\Helpers;

/**
 * Transactions Controller
 *
 * @property \App\Model\Table\TransactionsTable $Transactions
 * @method \App\Model\Entity\Transaction[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TransactionsController extends AppController
{

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

    public $paginate = [
        'limit' => 25,
        'order' => [
            'Transactions.portfolio_id' => 'desc'
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
        $prodtable = $this->getTableLocator()->get('Damsv2.Product');
        $products = $prodtable->getProducts();
        $this->loadModel('Damsv2.Portfolio');

        $cond_portfolio = ['Portfolio.iqid NOT IN' => $this->getUmbrellaIqid()];
        $cond_mandate = ['Portfolio.iqid NOT IN' => $this->getUmbrellaIqid()];

        // IF the filter for this dashboard is not stored in Session, we clear the Session object
        if (!$session->read('Form.data.transactions')) {
            $session->write('Form.data.transactions', [
                'product_id'            => '',
                'mandate'               => '',
                'portfolio_id'          => '',
                'transaction_reference' => '',
                'amount_from'           => '',
                'amount_to'             => '',
                'status'                => '',
                'fiscal_number'         => '',
                'maturity_from'         => '',
                'maturity_to'           => '',
                'currency'              => '',
                'exclusion_flag'        => '',
                'exclusion_reason'      => ''
            ]);
        }

        if ($this->request->is('post')) {
            //load session with request data
            $session->write('Form.data.transactions', $this->request->getData());
        }

        //filter dropdowns
        $prodid = !empty($this->request->getData('product_id')) ? $this->request->getData('product_id') : $session->read('Form.data.transactions.product_id');
        $manid = !empty($this->request->getData('mandate')) ? $this->request->getData('mandate') : $session->read('Form.data.transactions.mandate');
        
        //product
        if ($prodid) {
            $cond_portfolio = Helpers::arrayPushAssoc($cond_portfolio, 'Product.product_id', $prodid);
            $cond_mandate = Helpers::arrayPushAssoc($cond_portfolio, 'Product.product_id', $prodid);
        }
        //mandate
        if ($manid) {//from mandate name to portfolio_id 
            $getmandate = $this->Portfolio->find('all', ['fields' => ['mandate'], 'conditions' => ['Portfolio.portfolio_id' => $manid]])->first();
            $session->write('Form.data.transactions.mandate_name', $getmandate->mandate);
            $cond_portfolio = Helpers::arrayPushAssoc($cond_portfolio, 'Portfolio.mandate', $session->read('Form.data.transactions.mandate_name'));
        }

        if ($prodid) {
            if (($prodid) && ($manid)) {
                $mandate_possible = $this->Portfolio->find('all', [
                            'fields'     => ['portfolio_name', 'product_id'],
                            'conditions' => [
                                'Portfolio.product_id' => $prodid,
                                'Portfolio.mandate'    => $session->read('Form.data.transactions.mandate_name')
                    ]])->first();

                if (empty($mandate_possible)) {
                    $session->write('Form.data.transactions.mandate', null);
                } else {
                    $cond_portfolio = Helpers::arrayPushAssoc($cond_portfolio, 'Portfolio.mandate', $session->read('Form.data.transactions.mandate_name'));
                }
            }
            $cond_portfolio = Helpers::arrayPushAssoc($cond_portfolio, 'Product.product_id', $prodid);
            $cond_mandate = Helpers::arrayPushAssoc($cond_portfolio, 'Product.product_id', $prodid);
        }

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

        $statuses = [
            "EXPIRED"      => "EXPIRED",
            "PERFORMING"   => "PERFORMING",
            "EXCLUDED"     => "EXCLUDED",
            "DEFAULTED"    => "DEFAULTED",
            "NOT ELIGIBLE" => "NOT ELIGIBLE",
            "CANCELLED"    => "CANCELLED"
        ];

        $currencies = $this->Transactions->find('list', [
                    'fields'     => ['currency'],
                    'keyField'   => 'currency',
                    'valueField' => 'currency',
                    'group'      => 'currency',
                    'order'      => 'currency'
                ])->toArray();

        $exclusion_flags = [
            'To be excluded'                 => 'To be excluded',
            'Excluded, waiting for clawback' => 'Excluded, waiting for clawback',
            'Clawback paid'                  => 'Clawback paid',
            'Exclusion finalized'            => 'Exclusion finalized'
        ];

        $exclusion_reasons = [
            'E1' => 'SME Status',
            'E2' => 'Transaction Purpose',
            'E3' => 'Sector',
            'E4' => 'Refinancing',
            'E5' => 'Other',
            'E6' => 'Non-Compliance with maintenance of records'
        ];

        $conditions = $this->collectRequestData($session, $prodid, $manid);

        if (!empty($conditions)) {
            $query = $this->Transactions->find('all', [
                'contain'    => ['Portfolio', 'Sme', 'Portfolio.Product'],
                'conditions' => [$conditions]
            ]);
            $transactions = $this->paginate($query);
            $this->set(compact('transactions'));
        }
        $this->set(compact('products', 'portfolios', 'mandates', 'statuses', 'currencies', 'exclusion_flags', 'exclusion_reasons', 'session'));
    }

    public function export()
    {
        if ($this->request->is('ajax')) {
            $session = $this->request->getSession();
            //set view to Ajax
            $this->viewBuilder()->setLayout('ajax');
            $prodid = !empty($this->request->getData('product_id')) ? $this->request->getData('product_id') : $session->read('Form.data.transactions.product_id');
            $manid = !empty($this->request->getData('mandate')) ? $this->request->getData('mandate') : $session->read('Form.data.transactions.mandate');

            $conditions = $this->collectRequestData($session, $prodid, $manid);

            if (!empty($conditions)) {
                $results = $this->Transactions->find('all', [
                            'fields'     => ['Portfolio.portfolio_name', 'Transactions.transaction_reference', 'Transactions.fiscal_number', 'Transactions.transaction_status', 'Transactions.currency', 'Transactions.principal_amount', 'Transactions.maturity',],
                            'contain'    => ['Portfolio', 'Portfolio.Product'],
                            'conditions' => [$conditions]
                        ])->toArray();

                $filepath = '/var/www/html/data/damsv2/export/export_transactions' . time() . '.xlsx';
                $skeleton = ['Transactions'];

                $this->Spreadsheet->generateExcelFromQuery($results, $skeleton, $filepath);
                $this->set('filepath', basename($filepath));
            }
        }
    }

    private function collectRequestData($session, $prodid, $manid)
    {
        $conditions = [];

        //product id
        if ($prodid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Product.product_id', $prodid);
        }

        //portfolio id
        $portid = $session->read('Form.data.transactions.portfolio_id');

        if ($manid && !$portid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Transactions.portfolio_id', $manid);
        } else if (($portid && $manid) || $portid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Transactions.portfolio_id', $portid);
        }

        //fiscal number
        $fiscid = $session->read('Form.data.transactions.fiscal_number');
        if ($fiscid) {
            $fiscid = '%' . $fiscid . '%';
            $conditions = Helpers::arrayPushAssoc($conditions, 'Transactions.fiscal_number LIKE', $fiscid);
        }

        //transaction reference        
        $trref = $session->read('Form.data.transactions.transaction_reference');
        if ($trref) {
            $trref = '%' . $trref . '%';
            $conditions = Helpers::arrayPushAssoc($conditions, 'Transactions.transaction_reference LIKE', $trref);
        }

        //status        
        $statid = $session->read('Form.data.transactions.status');
        if ($statid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Transactions.transaction_status', $statid);
        }

        //currency
        $currid = $session->read('Form.data.transactions.currency');
        if ($currid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Transactions.currency', $currid);
        }

        //exclusion flag
        $exclid = $session->read('Form.data.transactions.exclusion_flag');
        if ($exclid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Transactions.exclusion_flag', $exclid);
        }

        //exclusion reason
        $exclreasonid = $session->read('Form.data.transactions.exclusion_reason');
        if ($exclreasonid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Transactions.exclusion_reason', $exclreasonid);
        }

        //dates
        $amform = $session->read('Form.data.transactions.amount_from');
        if ($amform) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Transactions.principal_amount >= ', $amform);
        }

        $amto = $session->read('Form.data.transactions.amount_to');
        if ($amto) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Transactions.principal_amount <= ', $amto);
        }

        $matform = $session->read('Form.data.transactions.maturity_from');
        if ($matform) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Transactions.maturity >= ', $matform);
        }

        $matto = $session->read('Form.data.transactions.maturity_to');
        if ($matto) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Transactions.maturity <= ', $matto);
        }

        return $conditions;
    }

    /**
     * View method
     *
     * @param string|null $id Transaction id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $transaction = $this->Transactions->get($id, [
            'contain' => ['Sme', 'Portfolio', 'Report', 'IncludedTransactions'],
        ]);

        $this->loadModel('Damsv2.ExpiredTransactions');
        $expired_transaction = $this->ExpiredTransactions->find('all', ['conditions' => [
                        'transaction_id' => $transaction->transaction_id
            ]])->first();
        $this->loadModel('Damsv2.ExcludedTransactions');
        $excluded_transaction = $this->ExcludedTransactions->find('all', ['conditions' => [
                        'transaction_id' => $transaction->transaction_id
            ]])->first();
        $this->loadModel('Damsv2.Guarantees');
        $guarantee = $this->Guarantees->find('all', ['conditions' => [
                        'transaction_id' => $transaction->transaction_id
            ]])->first();
        $this->loadModel('Damsv2.MappingTable');
        $this->loadModel('Damsv2.MappingColumn');
        $mapping_tables = $this->MappingTable->find('all', [
            'contain'    => ['MappingColumn'],
            'conditions' => ['MappingTable.template_id' => $transaction->report->template_id]
        ]);

        //update name of SME from sme_portfolio_id
        $this->loadModel('Damsv2.SmePortfolio');
        $sme_portfolio = $this->SmePortfolio->find('all', [
                    'fields'     => ['name'],
                    'conditions' => ['sme_id' => $transaction->sme_id, 'portfolio_id' => $transaction->portfolio_id]
                ])->first();

        $smeportname = !empty($sme_portfolio) ? $sme_portfolio->name : $transaction->sme->name;


        // if product frsp
        $portfolio_id_report = $transaction->report->portfolio_id;
        $portfolio_id_trn = $transaction->portfolio_id;
        $portfolio_id_trn_incl = -1;

        if (sizeof($transaction->included_transactions) > 0) {
            $portfolio_id_trn_incl = $transaction->included_transactions[sizeof($transaction->included_transactions) - 1]['portfolio_id'];
        }

        $this->loadModel('Damsv2.Portfolio');
        $is_frsp = $this->Portfolio->find('all', [
                    'conditions' => ['Portfolio.portfolio_id IN' => [$portfolio_id_report, $portfolio_id_trn, $portfolio_id_trn_incl], 'Portfolio.product_id' => 21]
                ])->first();

        // specific fields added (not in template)
        $mapping_columns = !empty($is_frsp) ? ['cumulative_disbursed', 'cumulative_repaid', 'cumulative_intr_repaid'] : [];


        $this->loadModel('Damsv2.PdlrTransaction');
        $pdlr_trns = $this->PdlrTransaction->find('all', ['conditions' => ['transaction_id' => $id]]);
        $this->set(compact('transaction', 'guarantee', 'expired_transaction', 'excluded_transaction', 'mapping_columns', 'pdlr_trns', 'smeportname', 'mapping_tables'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $transaction = $this->Transactions->newEmptyEntity();
        if ($this->request->is('post')) {
            $transaction = $this->Transactions->patchEntity($transaction, $this->request->getData());
            if ($this->Transactions->save($transaction)) {
                $this->Flash->success(__('The transaction has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The transaction could not be saved. Please, try again.'));
        }
        $smes = $this->Transactions->Smes->find('list', ['limit' => 200]);
        $portfolios = $this->Transactions->Portfolios->find('list', ['limit' => 200]);
        $reports = $this->Transactions->Reports->find('list', ['limit' => 200]);
        $this->set(compact('transaction', 'smes', 'portfolios', 'reports'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Transaction id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $transaction = $this->Transactions->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $transaction = $this->Transactions->patchEntity($transaction, $this->request->getData());
            if ($this->Transactions->save($transaction)) {
                $this->Flash->success(__('The transaction has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The transaction could not be saved. Please, try again.'));
        }
        $smes = $this->Transactions->Smes->find('list', ['limit' => 200]);
        $portfolios = $this->Transactions->Portfolios->find('list', ['limit' => 200]);
        $reports = $this->Transactions->Reports->find('list', ['limit' => 200]);
        $this->set(compact('transaction', 'smes', 'portfolios', 'reports'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Transaction id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $transaction = $this->Transactions->get($id);
        if ($this->Transactions->delete($transaction)) {
            $this->Flash->success(__('The transaction has been deleted.'));
        } else {
            $this->Flash->error(__('The transaction could not be deleted. Please, try again.'));
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

}
