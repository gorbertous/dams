<?php

declare(strict_types=1);

namespace Damsv2\Controller;

use Cake\Event\EventInterface;
use Cake\Datasource\ConnectionManager;
use Cake\Collection\Collection;
use App\Lib\Helpers;

/**
 * SmePortfolio Controller
 *
 * @property \App\Model\Table\SmePortfolioTable $SmePortfolio
 * @method \App\Model\Entity\SmePortfolio[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SmePortfolioController extends AppController
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
            'SmePortfolio.portfolio_id' => 'desc'
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

        $this->loadModel('Damsv2.Portfolio');

        $cond_portfolio = ['Portfolio.iqid NOT IN' => $this->getUmbrellaIqid()];

        if ($this->request->is('post')) {
            //load session with request data
            $session->write('Form.data.smeportfolio', $this->request->getData());
        }

        //filter dropdowns
        $prodid = !empty($this->request->getData('product_id')) ? $this->request->getData('product_id') : $session->read('Form.data.smeportfolio.product_id');
        //product
        if ($prodid) {
            $cond_portfolio = Helpers::arrayPushAssoc($cond_portfolio, 'Product.product_id', $prodid);
        }
        $conditions = $this->collectRequestData($session, $prodid);

        // IF the filter for this dashboard is not stored in Session, we clear the Session object
        if (!$session->read('Form.data.smeportfolio')) {
            $session->write('Form.data.smeportfolio', [
                'product_id'    => '',
                'portfolio_id'  => '',
                'fiscal_number' => '',
                'name'          => '',
                'sector'        => ''
            ]);
        }
        // Top dashboard filters
        $products = $prodtable->getProducts();
        $portfolios = $this->Portfolio->find('list', [
                    'contain'    => ['Product'],
                    'fields'     => ['Product.name', 'Portfolio.portfolio_name', 'Portfolio.portfolio_id'],
                    'keyField'   => 'portfolio_id',
                    'valueField' => 'portfolio_name',
                    'groupField' => 'product.name',
                    'order'      => ['Product.name', 'Portfolio.portfolio_name'],
                    'conditions' => [$cond_portfolio]
                ])->toArray();

        if (!empty($conditions)) {
            $query = $this->SmePortfolio->find('all', [
                'contain'    => ['Sme', 'Report', 'Portfolio', 'Portfolio.Product'],
                'conditions' => [$conditions]
            ]);

            $smePortfolios = $this->paginate($query);
            $this->set(compact('smePortfolios'));
        }

        $this->set(compact('portfolios', 'products', 'session'));
    }

    public function export()
    {
        if ($this->request->is('ajax')) {
            $session = $this->request->getSession();
            $prodid = !empty($this->request->getData('product_id')) ? $this->request->getData('product_id') : $session->read('Form.data.smeportfolio.product_id');
            //set view to Ajax
            $this->viewBuilder()->setLayout('ajax');

            $conditions = $this->collectRequestData($session, $prodid);
            if (!empty($conditions)) {
                $results = $this->SmePortfolio->find('all', [
                            'fields'     => ['Portfolio.portfolio_name', 'SmePortfolio.fiscal_number', 'SmePortfolio.name', 'SmePortfolio.sector', 'SmePortfolio.region', 'SmePortfolio.nbr_employees'],
                            'contain'    => ['Portfolio', 'Portfolio.Product'],
                            'conditions' => [$conditions]
                        ])->toArray();

                $filepath = '/var/www/html/data/damsv2/export/export_sme' . time() . '.xlsx';
                $skeleton = ['PortfolioSME'];

                $this->Spreadsheet->generateExcelFromQuery($results, $skeleton, $filepath);
                $this->set('filepath', basename($filepath));
            }
        }
    }

    private function collectRequestData($session, $prodid)
    {
        $conditions = [];

        //product id
        if ($prodid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Product.product_id', $prodid);
        }

        //portfolio id
        $portid = $session->read('Form.data.smeportfolio.portfolio_id');
        if ($portid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'SmePortfolio.portfolio_id', $portid);
        }

        //fiscal id
        $fiscid = $session->read('Form.data.smeportfolio.fiscal_number');
        if ($fiscid) {
            $fiscid = '%' . $fiscid . '%';
            $conditions = Helpers::arrayPushAssoc($conditions, 'SmePortfolio.fiscal_number LIKE', $fiscid);
        }

        //sme name
        $smename = $session->read('Form.data.smeportfolio.name');
        if ($smename) {
            $smename = '%' . $smename . '%';
            $conditions = Helpers::arrayPushAssoc($conditions, 'SmePortfolio.name LIKE', $smename);
        }

        //sector
        $sectorid = $session->read('Form.data.smeportfolio.sector');
        if ($sectorid) {
            $sectorid = '%' . $sectorid . '%';
            $conditions = Helpers::arrayPushAssoc($conditions, 'SmePortfolio.sector LIKE', $sectorid);
        }

        return $conditions;
    }

    /**
     * View method
     *
     * @param string|null $id Sme Portfolio id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $smePortfolio = $this->SmePortfolio->get($id, [
            'contain' => ['Sme', 'Report', 'Portfolio'],
        ]);
      
        $mapping_columns = [];
        $this->loadModel('Damsv2.Transactions');
        $transactions = $this->Transactions->find('all', ['conditions' => ['Transactions.sme_id' => $smePortfolio->sme_id], 'contain' => ['Portfolio', 'Sme']]);
        if (!empty($smePortfolio->report)) {
            $this->loadModel('Damsv2.MappingTable');
            $mapping_tables = $this->MappingTable->find('all', [
                        'conditions' => ['template_id' => $smePortfolio->report->template_id]
                    ])->first();

            $this->loadModel('Damsv2.MappingColumn');
            $mapping_columns = $this->MappingColumn->find('all', ['conditions' => [
                            'table_id' => $mapping_tables->table_id
                ]])->toArray();
        }

        $this->set(compact('smePortfolio', 'transactions', 'mapping_columns'));
    }

    /**
     * redirect_to_sme_portfolio method
     *
     * @throws NotFoundException
     * @param string $sme_id, string portfolio_id
     * @return void
     */
    public function toSmeportfolio($sme_id, $portfolio_id)
    {
        $sp = $this->SmePortfolio->find()
                ->where(['sme_id' => $sme_id, 'portfolio_id' => $portfolio_id])
                ->first();
        empty($sp) ? $this->Flash->error('not found!') : '';
        return !empty($sp) ? $this->redirect(['controller' => 'SmePortfolio', 'action' => 'view', $sp->sme_portfolio_id]) : $this->redirect($this->referer());
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $smePortfolio = $this->SmePortfolio->newEmptyEntity();
        if ($this->request->is('post')) {
            $smePortfolio = $this->SmePortfolio->patchEntity($smePortfolio, $this->request->getData());
            if ($this->SmePortfolio->save($smePortfolio)) {
                $this->Flash->success(__('The sme portfolio has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sme portfolio could not be saved. Please, try again.'));
        }
        $smes = $this->SmePortfolio->Smes->find('list', ['limit' => 200]);
        $reports = $this->SmePortfolio->Reports->find('list', ['limit' => 200]);
        $portfolios = $this->SmePortfolio->Portfolios->find('list', ['limit' => 200]);
        $this->set(compact('smePortfolio', 'smes', 'reports', 'portfolios'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sme Portfolio id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $smePortfolio = $this->SmePortfolio->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $smePortfolio = $this->SmePortfolio->patchEntity($smePortfolio, $this->request->getData());
            if ($this->SmePortfolio->save($smePortfolio)) {
                $this->Flash->success(__('The sme portfolio has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sme portfolio could not be saved. Please, try again.'));
        }
        $smes = $this->SmePortfolio->Smes->find('list', ['limit' => 200]);
        $reports = $this->SmePortfolio->Reports->find('list', ['limit' => 200]);
        $portfolios = $this->SmePortfolio->Portfolios->find('list', ['limit' => 200]);
        $this->set(compact('smePortfolio', 'smes', 'reports', 'portfolios'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sme Portfolio id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $smePortfolio = $this->SmePortfolio->get($id);
        if ($this->SmePortfolio->delete($smePortfolio)) {
            $this->Flash->success(__('The sme portfolio has been deleted.'));
        } else {
            $this->Flash->error(__('The sme portfolio could not be deleted. Please, try again.'));
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
