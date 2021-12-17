<?php
declare(strict_types=1);

namespace Damsv2\Controller;

/**
 * ExcludedTransactions Controller
 *
 * @property \App\Model\Table\ExcludedTransactionsTable $ExcludedTransactions
 * @method \App\Model\Entity\ExcludedTransaction[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ExcludedTransactionsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Smes', 'Transactions', 'Subtransactions', 'Portfolios', 'Reports'],
        ];
        $excludedTransactions = $this->paginate($this->ExcludedTransactions);

        $this->set(compact('excludedTransactions'));
    }

    /**
     * View method
     *
     * @param string|null $id Excluded Transaction id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $excludedTransaction = $this->ExcludedTransactions->get($id, [
            'contain' => ['Smes', 'Transactions', 'Subtransactions', 'Portfolios', 'Reports'],
        ]);

        $this->set(compact('excludedTransaction'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $excludedTransaction = $this->ExcludedTransactions->newEmptyEntity();
        if ($this->request->is('post')) {
            $excludedTransaction = $this->ExcludedTransactions->patchEntity($excludedTransaction, $this->request->getData());
            if ($this->ExcludedTransactions->save($excludedTransaction)) {
                $this->Flash->success(__('The excluded transaction has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The excluded transaction could not be saved. Please, try again.'));
        }
        $smes = $this->ExcludedTransactions->Smes->find('list', ['limit' => 200]);
        $transactions = $this->ExcludedTransactions->Transactions->find('list', ['limit' => 200]);
        $subtransactions = $this->ExcludedTransactions->Subtransactions->find('list', ['limit' => 200]);
        $portfolios = $this->ExcludedTransactions->Portfolios->find('list', ['limit' => 200]);
        $reports = $this->ExcludedTransactions->Reports->find('list', ['limit' => 200]);
        $this->set(compact('excludedTransaction', 'smes', 'transactions', 'subtransactions', 'portfolios', 'reports'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Excluded Transaction id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $excludedTransaction = $this->ExcludedTransactions->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $excludedTransaction = $this->ExcludedTransactions->patchEntity($excludedTransaction, $this->request->getData());
            if ($this->ExcludedTransactions->save($excludedTransaction)) {
                $this->Flash->success(__('The excluded transaction has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The excluded transaction could not be saved. Please, try again.'));
        }
        $smes = $this->ExcludedTransactions->Smes->find('list', ['limit' => 200]);
        $transactions = $this->ExcludedTransactions->Transactions->find('list', ['limit' => 200]);
        $subtransactions = $this->ExcludedTransactions->Subtransactions->find('list', ['limit' => 200]);
        $portfolios = $this->ExcludedTransactions->Portfolios->find('list', ['limit' => 200]);
        $reports = $this->ExcludedTransactions->Reports->find('list', ['limit' => 200]);
        $this->set(compact('excludedTransaction', 'smes', 'transactions', 'subtransactions', 'portfolios', 'reports'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Excluded Transaction id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $excludedTransaction = $this->ExcludedTransactions->get($id);
        if ($this->ExcludedTransactions->delete($excludedTransaction)) {
            $this->Flash->success(__('The excluded transaction has been deleted.'));
        } else {
            $this->Flash->error(__('The excluded transaction could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
