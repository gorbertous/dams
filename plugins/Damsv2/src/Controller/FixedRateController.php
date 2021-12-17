<?php
declare(strict_types=1);

namespace Damsv2\Controller;

/**
 * FixedRate Controller
 *
 * @property \App\Model\Table\FixedRateTable $FixedRate
 * @method \App\Model\Entity\FixedRate[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FixedRateController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Portfolios'],
        ];
        $fixedRate = $this->paginate($this->FixedRate);

        $this->set(compact('fixedRate'));
    }

    /**
     * View method
     *
     * @param string|null $id Fixed Rate id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $fixedRate = $this->FixedRate->get($id, [
            'contain' => ['Portfolios'],
        ]);

        $this->set(compact('fixedRate'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $fixedRate = $this->FixedRate->newEmptyEntity();
        if ($this->request->is('post')) {
            $fixedRate = $this->FixedRate->patchEntity($fixedRate, $this->request->getData());
            if ($this->FixedRate->save($fixedRate)) {
                $this->Flash->success(__('The fixed rate has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The fixed rate could not be saved. Please, try again.'));
        }
        $portfolios = $this->FixedRate->Portfolios->find('list', ['limit' => 200]);
        $this->set(compact('fixedRate', 'portfolios'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Fixed Rate id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $fixedRate = $this->FixedRate->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $fixedRate = $this->FixedRate->patchEntity($fixedRate, $this->request->getData());
            if ($this->FixedRate->save($fixedRate)) {
                $this->Flash->success(__('The fixed rate has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The fixed rate could not be saved. Please, try again.'));
        }
        $portfolios = $this->FixedRate->Portfolios->find('list', ['limit' => 200]);
        $this->set(compact('fixedRate', 'portfolios'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Fixed Rate id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $fixedRate = $this->FixedRate->get($id);
        if ($this->FixedRate->delete($fixedRate)) {
            $this->Flash->success(__('The fixed rate has been deleted.'));
        } else {
            $this->Flash->error(__('The fixed rate could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
