<?php
declare(strict_types=1);

namespace Damsv2\Controller;

/**
 * Sme Controller
 *
 * @property \App\Model\Table\SmeTable $Sme
 * @method \App\Model\Entity\Sme[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SmeController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Report', 'Portfolio'],
        ];
        $sme = $this->paginate($this->Sme);

        $this->set(compact('sme'));
    }

    /**
     * View method
     *
     * @param string|null $id Sme id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sme = $this->Sme->get($id, [
            'contain' => ['Report', 'Portfolios', 'Portfolio', 'PortfolioLogHistory'],
        ]);

        $this->set(compact('sme'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sme = $this->Sme->newEmptyEntity();
        if ($this->request->is('post')) {
            $sme = $this->Sme->patchEntity($sme, $this->request->getData());
            if ($this->Sme->save($sme)) {
                $this->Flash->success(__('The sme has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sme could not be saved. Please, try again.'));
        }
        $reports = $this->Sme->Reports->find('list', ['limit' => 200]);
        $portfolios = $this->Sme->Portfolios->find('list', ['limit' => 200]);
        $portfolio = $this->Sme->Portfolio->find('list', ['limit' => 200]);
        $portfolioLogHistory = $this->Sme->PortfolioLogHistory->find('list', ['limit' => 200]);
        $this->set(compact('sme', 'reports', 'portfolios', 'portfolio', 'portfolioLogHistory'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sme id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sme = $this->Sme->get($id, [
            'contain' => ['Portfolio', 'PortfolioLogHistory'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sme = $this->Sme->patchEntity($sme, $this->request->getData());
            if ($this->Sme->save($sme)) {
                $this->Flash->success(__('The sme has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sme could not be saved. Please, try again.'));
        }
        $reports = $this->Sme->Reports->find('list', ['limit' => 200]);
        $portfolios = $this->Sme->Portfolios->find('list', ['limit' => 200]);
        $portfolio = $this->Sme->Portfolio->find('list', ['limit' => 200]);
        $portfolioLogHistory = $this->Sme->PortfolioLogHistory->find('list', ['limit' => 200]);
        $this->set(compact('sme', 'reports', 'portfolios', 'portfolio', 'portfolioLogHistory'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sme id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sme = $this->Sme->get($id);
        if ($this->Sme->delete($sme)) {
            $this->Flash->success(__('The sme has been deleted.'));
        } else {
            $this->Flash->error(__('The sme could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
