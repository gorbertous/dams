<?php
declare(strict_types=1);

namespace Damsv2\Controller;

/**
 * ExpiredTransactions Controller
 *
 * @property \App\Model\Table\ExpiredTransactionsTable $ExpiredTransactions
 * @method \App\Model\Entity\ExpiredTransaction[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ExpiredTransactionsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Transactions', 'Subtransactions', 'Smes', 'Portfolios', 'Reports'],
        ];
        $expiredTransactions = $this->paginate($this->ExpiredTransactions);

        $this->set(compact('expiredTransactions'));
    }

    /**
     * View method
     *
     * @param string|null $id Expired Transaction id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $expiredTransaction = $this->ExpiredTransactions->get($id, [
            'contain' => ['Transactions', 'Subtransactions', 'Smes', 'Portfolios', 'Reports'],
        ]);

        $this->set(compact('expiredTransaction'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $expiredTransaction = $this->ExpiredTransactions->newEmptyEntity();
        if ($this->request->is('post')) {
            $expiredTransaction = $this->ExpiredTransactions->patchEntity($expiredTransaction, $this->request->getData());
            if ($this->ExpiredTransactions->save($expiredTransaction)) {
                $this->Flash->success(__('The expired transaction has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The expired transaction could not be saved. Please, try again.'));
        }
        $transactions = $this->ExpiredTransactions->Transactions->find('list', ['limit' => 200]);
        $subtransactions = $this->ExpiredTransactions->Subtransactions->find('list', ['limit' => 200]);
        $smes = $this->ExpiredTransactions->Smes->find('list', ['limit' => 200]);
        $portfolios = $this->ExpiredTransactions->Portfolios->find('list', ['limit' => 200]);
        $reports = $this->ExpiredTransactions->Reports->find('list', ['limit' => 200]);
        $this->set(compact('expiredTransaction', 'transactions', 'subtransactions', 'smes', 'portfolios', 'reports'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Expired Transaction id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $expiredTransaction = $this->ExpiredTransactions->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $expiredTransaction = $this->ExpiredTransactions->patchEntity($expiredTransaction, $this->request->getData());
            if ($this->ExpiredTransactions->save($expiredTransaction)) {
                $this->Flash->success(__('The expired transaction has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The expired transaction could not be saved. Please, try again.'));
        }
        $transactions = $this->ExpiredTransactions->Transactions->find('list', ['limit' => 200]);
        $subtransactions = $this->ExpiredTransactions->Subtransactions->find('list', ['limit' => 200]);
        $smes = $this->ExpiredTransactions->Smes->find('list', ['limit' => 200]);
        $portfolios = $this->ExpiredTransactions->Portfolios->find('list', ['limit' => 200]);
        $reports = $this->ExpiredTransactions->Reports->find('list', ['limit' => 200]);
        $this->set(compact('expiredTransaction', 'transactions', 'subtransactions', 'smes', 'portfolios', 'reports'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Expired Transaction id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $expiredTransaction = $this->ExpiredTransactions->get($id);
        if ($this->ExpiredTransactions->delete($expiredTransaction)) {
            $this->Flash->success(__('The expired transaction has been deleted.'));
        } else {
            $this->Flash->error(__('The expired transaction could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
