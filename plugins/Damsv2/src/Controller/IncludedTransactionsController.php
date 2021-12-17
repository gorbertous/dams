<?php
declare(strict_types=1);

namespace Damsv2\Controller;

/**
 * IncludedTransactions Controller
 *
 * @property \App\Model\Table\IncludedTransactionsTable $IncludedTransactions
 * @method \App\Model\Entity\IncludedTransaction[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class IncludedTransactionsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Transactions', 'Smes', 'Portfolios', 'Reports'],
        ];
        $includedTransactions = $this->paginate($this->IncludedTransactions);

        $this->set(compact('includedTransactions'));
    }

    /**
     * View method
     *
     * @param string|null $id Included Transaction id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $includedTransaction = $this->IncludedTransactions->get($id, [
            'contain' => ['Transactions', 'Smes', 'Portfolios', 'Reports'],
        ]);

        $this->set(compact('includedTransaction'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $includedTransaction = $this->IncludedTransactions->newEmptyEntity();
        if ($this->request->is('post')) {
            $includedTransaction = $this->IncludedTransactions->patchEntity($includedTransaction, $this->request->getData());
            if ($this->IncludedTransactions->save($includedTransaction)) {
                $this->Flash->success(__('The included transaction has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The included transaction could not be saved. Please, try again.'));
        }
        $transactions = $this->IncludedTransactions->Transactions->find('list', ['limit' => 200]);
        $smes = $this->IncludedTransactions->Smes->find('list', ['limit' => 200]);
        $portfolios = $this->IncludedTransactions->Portfolios->find('list', ['limit' => 200]);
        $reports = $this->IncludedTransactions->Reports->find('list', ['limit' => 200]);
        $this->set(compact('includedTransaction', 'transactions', 'smes', 'portfolios', 'reports'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Included Transaction id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $includedTransaction = $this->IncludedTransactions->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $includedTransaction = $this->IncludedTransactions->patchEntity($includedTransaction, $this->request->getData());
            if ($this->IncludedTransactions->save($includedTransaction)) {
                $this->Flash->success(__('The included transaction has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The included transaction could not be saved. Please, try again.'));
        }
        $transactions = $this->IncludedTransactions->Transactions->find('list', ['limit' => 200]);
        $smes = $this->IncludedTransactions->Smes->find('list', ['limit' => 200]);
        $portfolios = $this->IncludedTransactions->Portfolios->find('list', ['limit' => 200]);
        $reports = $this->IncludedTransactions->Reports->find('list', ['limit' => 200]);
        $this->set(compact('includedTransaction', 'transactions', 'smes', 'portfolios', 'reports'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Included Transaction id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $includedTransaction = $this->IncludedTransactions->get($id);
        if ($this->IncludedTransactions->delete($includedTransaction)) {
            $this->Flash->success(__('The included transaction has been deleted.'));
        } else {
            $this->Flash->error(__('The included transaction could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
