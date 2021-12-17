<?php

declare(strict_types=1);

namespace Dsr\Controller;

/**
 * Portfolios Controller
 *
 * @property \Dsr\Model\Table\PortfoliosTable $Portfolios
 * @method \Dsr\Model\Entity\Portfolio[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PortfoliosController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Products'],
        ];
        $portfolios = $this->paginate($this->Portfolios);

        $this->set(compact('portfolios'));
    }

    /**
     * View method
     *
     * @param string|null $id Portfolio id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $portfolio = $this->Portfolios->get($id, [
            'contain' => ['Products', 'Reports'],
        ]);
        $this->loadModel('Dsr.Loans');

        $query = $this->Loans->find('all', [
            'contain'    => ['Reports', 'Portfolios'],
            'conditions' => ['Loans.portfolio_id' => $id]
        ]);

        $loans = $this->paginate($query, ['contain' => ['Reports', 'Portfolios']]);
        $this->set(compact('portfolio', 'loans'));

        //$this->set(compact('portfolio'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $portfolio = $this->Portfolios->newEmptyEntity();
        if ($this->request->is('post')) {
            $portfolio = $this->Portfolios->patchEntity($portfolio, $this->request->getData());
            if ($this->Portfolios->save($portfolio)) {
                $this->Flash->success(__('The portfolio has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The portfolio could not be saved. Please, try again.'));
        }
        $products = $this->Portfolios->Products->find('list', ['limit' => 200]);
        $this->set(compact('portfolio', 'products'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Portfolio id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $portfolio = $this->Portfolios->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $portfolio = $this->Portfolios->patchEntity($portfolio, $this->request->getData());
            if ($this->Portfolios->save($portfolio)) {
                $this->Flash->success(__('The portfolio has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The portfolio could not be saved. Please, try again.'));
        }
        $products = $this->Portfolios->Products->find('list', ['limit' => 200]);
        $this->set(compact('portfolio', 'products'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Portfolio id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $portfolio = $this->Portfolios->get($id);
        if ($this->Portfolios->delete($portfolio)) {
            $this->Flash->success(__('The portfolio has been deleted.'));
        } else {
            $this->Flash->error(__('The portfolio could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

}
