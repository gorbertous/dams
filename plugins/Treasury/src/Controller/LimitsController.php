<?php

declare(strict_types=1);

namespace Treasury\Controller;

use Cake\Event\EventInterface;

/**
 * Limits Controller
 *
 * @property \Treasury\Model\Table\LimitsTable $Limits
 * @method \Treasury\Model\Entity\Limit[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LimitsController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        //$this->loadComponent('Security');
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
        $limits = $this->paginate($this->Limits);

        $this->set(compact('limits'));
    }

    /**
     * View method
     *
     * @param string|null $id Limit id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $limit = $this->Limits->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('limit'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $limit = $this->Limits->newEmptyEntity();
        if ($this->request->is('post')) {
            $limit = $this->Limits->patchEntity($limit, $this->request->getData());
            if ($this->Limits->save($limit)) {
                $this->Flash->success(__('The limit has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The limit could not be saved. Please, try again.'));
        }
        $this->set(compact('limit'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Limit id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $limit = $this->Limits->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $limit = $this->Limits->patchEntity($limit, $this->request->getData());
            if ($this->Limits->save($limit)) {
                $this->Flash->success(__('The limit has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The limit could not be saved. Please, try again.'));
        }
        $this->set(compact('limit'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Limit id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $limit = $this->Limits->get($id);
        if ($this->Limits->delete($limit)) {
            $this->Flash->success(__('The limit has been deleted.'));
        } else {
            $this->Flash->error(__('The limit could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function shell()
    {
        $command = 'treasury.limit uat';
        $args = explode(' ', $command);

        $dispatcher = new ShellDispatcher($args, false);

        $dispatcher->dispatch();
        die();
    }
    /*   public function index(){
        $this->redirect(array('action'=>'limits'));
    } */

    public function transactions($limit_id, $currentdate)
    {

        @$this->validate_param('int', $limit_id);
        @$this->validate_param('date', $currentdate);
        if (!$this->Limit->exists($limit_id)) {
            throw new NotFoundException(__('Invalid Limit'));
        }
        $options = array('conditions' => array('Limit.' . $this->Limit->primaryKey => $limit_id));
        $this->set('limit', $this->Limit->find('first', $options));

        $date = DateTime::createFromFormat('Y-m-d', $currentdate);
        $currentdate = $date->format("d/m/Y");
        $this->set('currentdate', $currentdate);

        $transactions = $this->Transaction->getTransactionsByLimit($limit_id, $date->format("Y-m-d"));
        $this->set(compact('transactions'));
    }

    public function limits()
    {
        //list of mandategroups
        $mandategroups = $this->MandateGroup->find('list', array(
            'conditions' => array('mandategroup_name <>' => null, 'mandategroup_name <>' => ''),
            'fields' => array('id', 'mandategroup_name'),
            'order' => 'mandategroup_name'
        ));

        $date = date('Y-m-d');
        if (!empty($this->request->data['filter']['Date'])) $date = $this->request->data['filter']['Date'];
        if (strpos($date, '/') !== false) $date = date('Y-m-d', strtotime(str_replace('/', '-', $date)));

        //portfolio
        $mandategroup = null;
        if (!empty($this->request->data['filter']['Portfolio'])) $mandategroup = $this->request->data['filter']['Portfolio'];
        elseif (!empty($mandategroups)) {
            $tmp = array_keys($mandategroups);
            $mandategroup = reset($tmp);
        }

        //limits based on date & portfolio
        $limits = $this->Limit->getByCounterparties($date, $mandategroup);
        $portfolioSize = 0;
        $portfolioConcentrationUnit = 'ABS';
        $portfolioMaxConcentration = '';
        if (!empty($limits['portfolioSize'])) $portfolioSize = $limits['portfolioSize'];
        if (!empty($limits['portfolioConcentrationUnit'])) $portfolioConcentrationUnit = $limits['portfolioConcentrationUnit'];
        if (!empty($limits['portfolioMaxConcentration'])) {
            $portfolioMaxConcentration = $limits['portfolioMaxConcentration'];
            if ($portfolioConcentrationUnit == 'PCT') {
                $portfolioMaxConcentration *= 100;
                //$portfolioMaxConcentration = $portfolioSize * $limits['portfolioMaxConcentration'];
            }
        }

        $breaches = array();
        if (isset($this->request->data['filter']['Date'])) // if submited
        {
            @$this->validate_param('date', $date);
            $req = "SELECT * FROM limit_breaches lb, limit_breaches_transactions lbt WHERE lb.id = lbt.id_breach AND lbt.mandategroup_ID = " . intval($this->request->data['filter']['Portfolio']) . " AND  ( ( lb.breach_date LIKE '" . $date . "%') OR (lbt.date LIKE '" . $date . "%' AND lb.breach_date IS NULL)) GROUP BY lb.id";
            $breaches = $this->Limit->query($req);
        }

        $this->set(compact('date', 'mandategroups', 'limits', 'portfolioSize', 'portfolioMaxConcentration', 'portfolioConcentrationUnit', 'breaches'));
    }
    public function details($type, $id, $date)
    {
        @$this->validate_param('string', $type);
        @$this->validate_param('int', $id);
        @$this->validate_param('date', $date);
        $date = DateTime::createFromFormat('Y-m-d', $date);
        $currentdate = $date->format("d/m/Y");
        $this->set('date', $currentdate);

        if (!$this->Limit->exists($id)) {
            throw new NotFoundException(__('Invalid Limit'));
        }
        $conditions = array('Limit.' . $this->Limit->primaryKey => $id);
        $this->set('limit', $this->Limit->find('first', array(
            'conditions' => $conditions,
        )));

        $transactions = $this->Transaction->getTransactionsByLimit($id, $date->format("Y-m-d"), $type);
        $this->set(compact('transactions'));
    }
    public function updateRatings()
    {
        $command = 'treasury.rating_update -d';
        $args = explode(' ', $command);

        $dispatcher = new ShellDispatcher($args, false);

        $dispatcher->dispatch();
        die();
    }
}
