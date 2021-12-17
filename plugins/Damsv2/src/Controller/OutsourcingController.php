<?php

declare(strict_types=1);

namespace Damsv2\Controller;

use Cake\Event\EventInterface;
use Cake\Collection\Collection;
use App\Lib\Helpers;

/**
 * Outsourcing Controller
 *
 * @property \Damsv2\Model\Table\OutsourcingLogTable $Outsourcing
 * @method \Damsv2\Model\Entity\Outsourcing[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OutsourcingController extends AppController
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

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $session = $this->request->getSession();

        $conditions = [];
        $conditions_filter = [];
        $conditions_portfolio = [];
        $filter = [];

        if (!$session->read('Form.data.outsourcing')) {
            $session->write('Form.data.outsourcing', [
                'period_quarter'  => '',
                'inclusion_deadline' => '',
                'dh_resp'  => '',
                'mandate_id' => '',
                'prioritised' => '',
                'inclusion_resp' => '',
                'portfolio_id' => '',
                'inclusion_status' => ''
            ]);
        }

        $manid = !empty($this->request->getData('mandate_id')) ? $this->request->getData('mandate_id') : $session->read('Form.data.outsourcing.mandate_id');

        if ($this->request->is('post') && $this->request->getData('storeinsession') == true) {
            //load session with request data
            $session->write('Form.data.outsourcing', $this->request->getData());
        }

        $perqid = $session->read('Form.data.outsourcing.period_quarter');
        // index filters
        if ($perqid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'period_quarter', $perqid);
            $conditions_filter = Helpers::arrayPushAssoc($conditions_filter, 'period_quarter', $perqid);

            $incdeadline = $session->read('Form.data.outsourcing.inclusion_deadline');
            if ($incdeadline) {
                $conditions = Helpers::arrayPushAssoc($conditions, 'inclusion_deadline', $incdeadline);
            }
            $dhresp = $session->read('Form.data.outsourcing.dh_resp');
            if ($dhresp) {
                $conditions = Helpers::arrayPushAssoc($conditions, 'dh_resp', $dhresp);
            }
            if ($manid) {
                $conditions = Helpers::arrayPushAssoc($conditions, 'mandate_id', $manid);
                $conditions_portfolio = Helpers::arrayPushAssoc($conditions_portfolio, 'mandate_id', $manid);
            }
            $prior = $session->read('Form.data.outsourcing.prioritised');
            if ($prior) {
                $conditions = Helpers::arrayPushAssoc($conditions, 'prioritised', $prior);
            }
            $incresp = $session->read('Form.data.outsourcing.inclusion_resp');
            if ($incresp) {
                $conditions = Helpers::arrayPushAssoc($conditions, 'inclusion_resp', $incresp);
            }
            $portid = $session->read('Form.data.outsourcing.portfolio_id');
            if ($portid) {
                $conditions = Helpers::arrayPushAssoc($conditions, 'portfolio_id', $portid);
            }
            $incstatus = $session->read('Form.data.outsourcing.inclusion_status');
            if ($incstatus) {
                $conditions = Helpers::arrayPushAssoc($conditions, 'inclusion_status', $incstatus);
            }
        }
        //saving process
        if (!empty($this->request->getData('OutsourcingLog'))) {
            $error_message = [];
            $num_saves = 0;
            foreach ($this->request->getData('OutsourcingLog') as $log_id => $log) {
                if ($log['sel'] == '1') {
                    $outsourcingLog = $this->Outsourcing->get($log_id);

                    $outsourcingLog->prioritised = $log['prioritised'];
                    $outsourcingLog->inclusion_status = $log['inclusion_status'];
                    $outsourcingLog->email_date = !empty($log['email_date']) ? $log['email_date'] : null;
                    $outsourcingLog->dh_resp = $log['dh_resp'];
                    $outsourcingLog->inclusion_resp = $log['inclusion_resp'];
                    $outsourcingLog->received_date = !empty($log['received_date']) ? $log['received_date'] : null;
                    $outsourcingLog->first_email_date = !empty($log['first_email_date']) ? $log['first_email_date'] : null;
                    $outsourcingLog->inclusion_date = !empty($log['inclusion_date']) ? $log['inclusion_date'] : null;
                    $outsourcingLog->c_sheet = $log['c_sheet'];
                    $outsourcingLog->follow_up = htmlentities($log['follow_up']);
                    $outsourcingLog->comments = htmlentities($log['comments']);

                    $saved = $this->Outsourcing->save($outsourcingLog);

                    error_log('outsourcing save result: ' . json_encode($saved));
                    $num_saves++;
                    if (!$saved) {
                        $error_message[] = $log_id;
                    }
                }
            }
            if (!empty($error_message)) {
                $this->Flash->error("Saving failed for logs " . implode(',', $error_message), ['escape' => false]);
            } else {
                $msg = "Outsourcing Log has been successfully updated. Number of updated inclusions: " . $num_saves;
                $this->Flash->success($msg, ['escape' => false]);
            }
        }
        // top filters
        $quarters = [
            'Q1' => 'Q1',
            'Q2' => 'Q2',
            'Q3' => 'Q3',
            'Q4' => 'Q4'
        ];
        $prioritised = [
            '' => '',
            'Priority-Complex' => 'Priority-Complex',
        ];
        $inclusion_status = [
            '0' => 'Not received',
            '1' => 'Not started',
            '2' => 'Started',
            '3a' => 'In reconciliation BlackRock',
            '3b' => 'In reconciliation OIM (DH)',
            '3c' => 'In reconciliation OIM (ICE)',
            '3d' => 'In reconciliation OIM (FI)',
            '4' => 'Included within inclusion_deadline',
            '5' => 'Included after inclusion_deadline',
            '6' => 'No inclusions',
        ];

        $query = $this->Outsourcing->find();
        $query->select(['inclusion_deadline'])->distinct(['inclusion_deadline']);
        $query->enableHydration(false);
        $query_to_list = $query->toList();
        $collection = new Collection($query_to_list);
        $deadlines = $collection->map(function ($value) {
            return $value['inclusion_deadline']->format('Y-m-d');
        });
        $outsource_array = $deadlines->toArray();
        $deadlines = array_combine($outsource_array, $outsource_array);

        $query2 = $this->Outsourcing->find();
        $query2->select(['dh_resp'])->distinct(['dh_resp']);
        $query2->enableHydration(false);
        $query2_to_list = $query2->toList();
        $collection2 = new Collection($query2_to_list);
        $dhresponsible = $collection2->map(function ($value) {
            return $value['dh_resp'];
        });
        $outsource2_array = $dhresponsible->toArray();
        $dh_resp = array_combine($outsource2_array, $outsource2_array);

        $query3 = $this->Outsourcing->find();
        $query3->select(['inclusion_resp'])->distinct(['inclusion_resp']);
        $query3->enableHydration(false);
        $query3_to_list = $query3->toList();
        $collection3 = new Collection($query3_to_list);
        $inresponsible = $collection3->map(function ($value) {
            return $value['inclusion_resp'];
        });
        $outsource3_array = $inresponsible->toArray();
        $inclusion_resp = array_combine($outsource3_array, $outsource3_array);

        $portfolios = $this->Outsourcing->find('list', [
            'fields'     => ['portfolio_id', 'portfolio_name'],
            'keyField'   => 'portfolio_id',
            'valueField' => 'portfolio_name',
            'conditions' => $conditions_portfolio,
            'order'      => 'portfolio_name'
        ])->toArray();

        $mandates = $this->Outsourcing->find('list', [
            'fields'     => ['mandate_id', 'mandate'],
            'keyField'   => 'mandate_id',
            'valueField' => 'mandate',
            'conditions' => $conditions_filter,
            'order'      => 'mandate_id'
        ])->toArray();

        if (!empty($conditions)) {
            $query = $this->Outsourcing->find('all', [
                'conditions' => [$conditions]
            ]);
            $outsourcing_log_list = $this->paginate($query);
            $this->set(compact('outsourcing_log_list'));
        }

        $this->set(compact('quarters', 'prioritised', 'inclusion_status', 'portfolios', 'mandates', 'deadlines', 'dh_resp', 'inclusion_resp', 'session'));
    }

    /**
     * View method
     *
     * @param string|null $id Outsourcing Log id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $outsourcingLog = $this->Outsourcing->get($id, [
            'contain' => ['Portfolio'],
        ]);

        $this->set(compact('outsourcingLog'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $outsourcingLog = $this->Outsourcing->newEmptyEntity();
        if ($this->request->is('post')) {
            $outsourcingLog = $this->Outsourcing->patchEntity($outsourcingLog, $this->request->getData());
            if ($this->Outsourcing->save($outsourcingLog)) {
                $this->Flash->success(__('The outsourcing log has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The outsourcing log could not be saved. Please, try again.'));
        }
        $portfolios = $this->Outsourcing->Portfolio->find('list', ['limit' => 200]);
        $this->set(compact('outsourcingLog', 'portfolios'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Outsourcing Log id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $outsourcingLog = $this->Outsourcing->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $outsourcingLog = $this->Outsourcing->patchEntity($outsourcingLog, $this->request->getData());
            if ($this->Outsourcing->save($outsourcingLog)) {
                $this->Flash->success(__('The outsourcing log has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The outsourcing log could not be saved. Please, try again.'));
        }
        $portfolios = $this->Outsourcing->Portfolio->find('list', ['limit' => 200]);
        $this->set(compact('outsourcingLog', 'portfolios'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Outsourcing Log id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $outsourcingLog = $this->Outsourcing->get($id);
        if ($this->Outsourcing->delete($outsourcingLog)) {
            $this->Flash->success(__('The outsourcing log has been deleted.'));
        } else {
            $this->Flash->error(__('The outsourcing log could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function export()
    {
        if ($this->request->is('ajax')) {
            //set view to Ajax
            $this->viewBuilder()->setLayout('ajax');
            //dd($this->request->getData('OutsourcingLog.log_id'));
            if (!empty($this->request->getData('OutsourcingLog.log_id'))) {
                $logs = explode(',', $this->request->getData('OutsourcingLog.log_id'));
                $results = $this->Outsourcing->find('all', [
                    'conditions' => [
                        'log_id in' => $logs
                    ]
                ])->toArray();

                $filepath = '/var/www/html/data/damsv2/export/outsourcing_log_' . time() . '.xlsx';
                $skeleton = ['OutsourcingLog'];

                $this->Spreadsheet->generateExcelFromQuery($results, $skeleton, $filepath);
                $this->set('filepath', basename($filepath));
            }
        }
    }
}
