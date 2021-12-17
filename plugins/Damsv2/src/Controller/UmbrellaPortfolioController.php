<?php
declare(strict_types=1);

namespace Damsv2\Controller;

/**
 * UmbrellaPortfolio Controller
 *
 * @property \App\Model\Table\UmbrellaPortfolioTable $UmbrellaPortfolio
 * @method \App\Model\Entity\UmbrellaPortfolio[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UmbrellaPortfolioController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Product'],
        ];
        $umbrellaPortfolio = $this->paginate($this->UmbrellaPortfolio);

        $this->set(compact('umbrellaPortfolio'));
    }

    /**
     * View method
     *
     * @param string|null $id Umbrella Portfolio id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $umbrellaPortfolio = $this->UmbrellaPortfolio->get($id, [
            'contain' => ['Product', 'Deleted'],
        ]);

        $this->set(compact('umbrellaPortfolio'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $umbrellaPortfolio = $this->UmbrellaPortfolio->newEmptyEntity();
        if ($this->request->is('post')) {
            $umbrellaPortfolio = $this->UmbrellaPortfolio->patchEntity($umbrellaPortfolio, $this->request->getData());
            if ($this->UmbrellaPortfolio->save($umbrellaPortfolio)) {
                $this->Flash->success(__('The umbrella portfolio has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The umbrella portfolio could not be saved. Please, try again.'));
        }
        $product = $this->UmbrellaPortfolio->Product->find('list', ['limit' => 200]);
        $deleted = $this->UmbrellaPortfolio->Deleted->find('list', ['limit' => 200]);
        $this->set(compact('umbrellaPortfolio', 'product', 'deleted'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Umbrella Portfolio id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $umbrellaPortfolio = $this->UmbrellaPortfolio->get($id, [
            'contain' => ['Deleted'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $umbrellaPortfolio = $this->UmbrellaPortfolio->patchEntity($umbrellaPortfolio, $this->request->getData());
            if ($this->UmbrellaPortfolio->save($umbrellaPortfolio)) {
                $this->Flash->success(__('The umbrella portfolio has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The umbrella portfolio could not be saved. Please, try again.'));
        }
        $product = $this->UmbrellaPortfolio->Product->find('list', ['limit' => 200]);
        $deleted = $this->UmbrellaPortfolio->Deleted->find('list', ['limit' => 200]);
        $this->set(compact('umbrellaPortfolio', 'product', 'deleted'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Umbrella Portfolio id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $umbrellaPortfolio = $this->UmbrellaPortfolio->get($id);
        if ($this->UmbrellaPortfolio->delete($umbrellaPortfolio)) {
            $this->Flash->success(__('The umbrella portfolio has been deleted.'));
        } else {
            $this->Flash->error(__('The umbrella portfolio could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
