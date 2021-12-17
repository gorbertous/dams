<?php
declare(strict_types=1);

namespace Damsv2\Controller;

/**
 * PortfolioRates Controller
 *
 * @property \App\Model\Table\PortfolioRatesTable $PortfolioRates
 * @method \App\Model\Entity\PortfolioRates[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PortfolioRatesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Portfolio'],
        ];
        $portfolioRates = $this->paginate($this->PortfolioRates);

        $this->set(compact('portfolioRates'));
    }

    /**
     * View method
     *
     * @param string|null $id Portfolio Rate id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $portfolioRate = $this->PortfolioRates->get($id, [
            'contain' => ['Portfolio'],
        ]);

        $this->set(compact('portfolioRate'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $portfolioRate = $this->PortfolioRates->newEmptyEntity();
        if ($this->request->is('post')) {
            $portfolioRate = $this->PortfolioRates->patchEntity($portfolioRate, $this->request->getData());
            if ($this->PortfolioRates->save($portfolioRate)) {
                $this->Flash->success(__('The portfolio rate has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The portfolio rate could not be saved. Please, try again.'));
        }
        $portfolio = $this->PortfolioRates->Portfolio->find('list', ['limit' => 200]);
        $this->set(compact('portfolioRate', 'portfolio'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Portfolio Rate id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $portfolioRate = $this->PortfolioRates->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $portfolioRate = $this->PortfolioRates->patchEntity($portfolioRate, $this->request->getData());
            if ($this->PortfolioRates->save($portfolioRate)) {
                $this->Flash->success(__('The portfolio rate has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The portfolio rate could not be saved. Please, try again.'));
        }
        $portfolio = $this->PortfolioRates->Portfolio->find('list', ['limit' => 200]);
        $this->set(compact('portfolioRate', 'portfolio'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Portfolio Rate id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $portfolioRate = $this->PortfolioRates->get($id);
        if ($this->PortfolioRates->delete($portfolioRate)) {
            $this->Flash->success(__('The portfolio rate has been deleted.'));
        } else {
            $this->Flash->error(__('The portfolio rate could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
