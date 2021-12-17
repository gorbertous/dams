<?php
declare(strict_types=1);

namespace Damsv2\Controller;

/**
 * Guarantees Controller
 *
 * @property \App\Model\Table\GuaranteesTable $Guarantees
 * @method \App\Model\Entity\Guarantee[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GuaranteesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Transactions', 'Portfolio', 'Sme', 'Report'],
        ];
        $guarantees = $this->paginate($this->Guarantees);

        $this->set(compact('guarantees'));
    }

    /**
     * View method
     *
     * @param string|null $id Guarantee id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $guarantee = $this->Guarantees->get($id, [
            'contain' => ['Transactions', 'Portfolio', 'Sme', 'Report'],
        ]);

        $this->set(compact('guarantee'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $guarantee = $this->Guarantees->newEmptyEntity();
        if ($this->request->is('post')) {
            $guarantee = $this->Guarantees->patchEntity($guarantee, $this->request->getData());
            if ($this->Guarantees->save($guarantee)) {
                $this->Flash->success(__('The guarantee has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The guarantee could not be saved. Please, try again.'));
        }
        $transactions = $this->Guarantees->Transactions->find('list', ['limit' => 200]);
        $portfolios = $this->Guarantees->Portfolio->find('list', ['limit' => 200]);
        $smes = $this->Guarantees->Sme->find('list', ['limit' => 200]);
        $reports = $this->Guarantees->Report->find('list', ['limit' => 200]);
        $this->set(compact('guarantee', 'transactions', 'portfolios', 'smes', 'reports'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Guarantee id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $guarantee = $this->Guarantees->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $guarantee = $this->Guarantees->patchEntity($guarantee, $this->request->getData());
            if ($this->Guarantees->save($guarantee)) {
                $this->Flash->success(__('The guarantee has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The guarantee could not be saved. Please, try again.'));
        }
        $transactions = $this->Guarantees->Transactions->find('list', ['limit' => 200]);
        $portfolios = $this->Guarantees->Portfolio->find('list', ['limit' => 200]);
        $smes = $this->Guarantees->Sme->find('list', ['limit' => 200]);
        $reports = $this->Guarantees->Report->find('list', ['limit' => 200]);
        $this->set(compact('guarantee', 'transactions', 'portfolios', 'smes', 'reports'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Guarantee id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $guarantee = $this->Guarantees->get($id);
        if ($this->Guarantees->delete($guarantee)) {
            $this->Flash->success(__('The guarantee has been deleted.'));
        } else {
            $this->Flash->error(__('The guarantee could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
