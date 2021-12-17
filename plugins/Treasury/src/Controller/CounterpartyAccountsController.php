<?php

declare(strict_types=1);

namespace Treasury\Controller;

use Cake\Event\EventInterface;

/**
 * CounterpartyAccounts Controller
 *
 * @property \Treasury\Model\Table\CounterpartyAccountsTable $CounterpartyAccounts
 * @method \Treasury\Model\Entity\CounterpartyAccount[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CounterpartyAccountsController extends AppController
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
    var    $currencies = array(
        'BGN' => 'BGN',
        'CZK' => 'CZK',
        'DKK' => 'DKK',
        'EUR' => 'EUR',
        'GBP' => 'GBP',
        'HRK' => 'HRK',
        'HUF' => 'HUF',
        'NOK' => 'NOK',
        'PLN' => 'PLN',
        'USD' => 'USD',
        'RON' => 'RON',
        'SEK' => 'SEK',
        'TRY' => 'TRY'
    );
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Cpties'],
        ];
        $counterpartyAccounts = $this->paginate($this->CounterpartyAccounts);

        $this->set(compact('counterpartyAccounts'));
    }

    /**
     * View method
     *
     * @param string|null $id Counterparty Account id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $counterpartyAccount = $this->CounterpartyAccounts->get($id, [
            'contain' => ['Cpties'],
        ]);

        $this->set(compact('counterpartyAccount'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $counterpartyAccount = $this->CounterpartyAccounts->newEmptyEntity();
        if ($this->request->is('post')) {
            $counterpartyAccount = $this->CounterpartyAccounts->patchEntity($counterpartyAccount, $this->request->getData());
            if ($this->CounterpartyAccounts->save($counterpartyAccount)) {
                $this->Flash->success(__('The counterparty account has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The counterparty account could not be saved. Please, try again.'));
        }
        $cpties = $this->CounterpartyAccounts->Cpties->find('list', ['limit' => 200]);
        $this->set(compact('counterpartyAccount', 'cpties'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Counterparty Account id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $counterpartyAccount = $this->CounterpartyAccounts->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $counterpartyAccount = $this->CounterpartyAccounts->patchEntity($counterpartyAccount, $this->request->getData());
            if ($this->CounterpartyAccounts->save($counterpartyAccount)) {
                $this->Flash->success(__('The counterparty account has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The counterparty account could not be saved. Please, try again.'));
        }
        $cpties = $this->CounterpartyAccounts->Cpties->find('list', ['limit' => 200]);
        $this->set(compact('counterpartyAccount', 'cpties'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Counterparty Account id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $counterpartyAccount = $this->CounterpartyAccounts->get($id);
        if ($this->CounterpartyAccounts->delete($counterpartyAccount)) {
            $this->Flash->success(__('The counterparty account has been deleted.'));
        } else {
            $this->Flash->error(__('The counterparty account could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function del_counterparty_account()
    {
        $id_cpty_acc = $this->request->data['CounterpartyAccount']['id'];
        //@$this->validate_param('int', $id_cpty_acc);
        if (!empty($id_cpty_acc)) {
            $del = $this->CounterpartyAccount->delete($id_cpty_acc);
            error_log("delete cpty account : " . json_encode($del, true));
            $msg = "counterparty account " . $id_cpty_acc . " deleted";
            $this->log_entry($msg, 'treasury');
            $this->Session->setFlash($msg, 'flash/success');
        }
        $this->redirect($this->referer());
    }

    public function update_counterparty_account()
    {
        $this->set('currencies', $this->currencies);
        if (!empty($this->request->data)) {
            $exist = $this->CounterpartyAccount->read(null, $this->request->data['CounterpartyAccount']['id']);
            if (empty($exist)) {
                $this->create_counterparty_account();
            } else {
                if (!$this->check_iban($this->request->data['CounterpartyAccount']['account_IBAN'])) {
                    $this->set('message', "The IBAN is not valid");
                } else {
                    if ($this->request->data['CounterpartyAccount']['target'] == 'false') {
                        $this->request->data['CounterpartyAccount']['target'] = 0;
                    } elseif ($this->request->data['CounterpartyAccount']['target'] == 'true') {
                        $this->request->data['CounterpartyAccount']['target'] = 1;
                    }
                    $this->CounterpartyAccount->set('currency', $this->request->data['CounterpartyAccount']['currency']);
                    $this->CounterpartyAccount->set('target', $this->request->data['CounterpartyAccount']['target']);
                    $this->CounterpartyAccount->set('correspondent_bank', $this->request->data['CounterpartyAccount']['correspondent_bank']);
                    $this->CounterpartyAccount->set('correspondent_BIC', $this->request->data['CounterpartyAccount']['correspondent_BIC']);
                    $this->CounterpartyAccount->set('account_IBAN', str_replace(' ', '', $this->request->data['CounterpartyAccount']['account_IBAN']));
                    $this->CounterpartyAccount->set('cpty_id', $this->request->data['CounterpartyAccount']['cpty_id']);
                    //$val = array('CounterpartyAccount' => $this->request->data['CounterpartyAccount']);

                    $saved = $this->CounterpartyAccount->save();
                    if ($saved) {
                        $msg = "Counterparty account " . $this->request->data['CounterpartyAccount']['id'] . " updated : " . json_encode($this->request->data, true);
                        $this->log_entry($msg, 'treasury');
                    }
                    $this->set('cpty_account', $saved);
                }
            }
        }
    }

    public function create_counterparty_account()
    {
        $this->set('currencies', $this->currencies);
        if (!empty($this->request->data)) {
            $cpty_id = $this->request->data['CounterpartyAccount']['cpty_id'];
            $curr = $this->request->data['CounterpartyAccount']['currency'];
            $duplicate = $this->CounterpartyAccount->find('first', array('conditions' => array('cpty_id' => $cpty_id, 'currency' => $curr)));
            if (empty($duplicate)) {
                if ($this->check_iban($this->request->data['CounterpartyAccount']['account_IBAN'])) {
                    if ($this->request->data['CounterpartyAccount']['target'] == 'false') {
                        $this->request->data['CounterpartyAccount']['target'] = 0;
                    } elseif ($this->request->data['CounterpartyAccount']['target'] == 'true') {
                        $this->request->data['CounterpartyAccount']['target'] = 1;
                    }
                    $this->CounterpartyAccount->create();
                    $this->request->data['CounterpartyAccount']['account_IBAN'] = str_replace(' ', '', $this->request->data['CounterpartyAccount']['account_IBAN']);
                    $cpty_account = $this->CounterpartyAccount->save($this->request->data);
                    $msg = "counterparty account created : " . json_encode($this->request->data, true);
                    $this->log_entry($msg, 'treasury');
                    $this->set('cpty_account', $cpty_account);
                } else {
                    $this->set('message', "The IBAN is not valid");
                }
            } else {
                $this->set('message', "A single account can exist for each currency.");
            }
        }
    }

    private function check_iban($iban)
    {

        @$this->validate_param('string', $iban);

        if ($iban == '') {
            return true;
        }
        $valid = false;
        $matches = array();
        $pattern = '/^[A-Za-z]{2}/';
        $regex = preg_match($pattern, $iban, $matches);
        if ($regex) {
            try {

                $valid = @$this->IBAN::test($iban);
            } catch (Exception $e) {
                $valid = false;
                error_log("error validating IBAN : " . $e->getMessage());
            }
        } else {
            $valid = true;
        }


        return $valid;
    }

    public function new_counterparty_account()
    {
        //this is view to blank new line
        $this->set('currencies', $this->currencies);
    }
}
