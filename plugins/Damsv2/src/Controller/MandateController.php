<?php
declare(strict_types=1);

namespace Damsv2\Controller;

/**
 * Mandate Controller
 *
 * @property \App\Model\Table\MandateTable $Mandate
 * @method \App\Model\Entity\Mandate[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MandateController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $mandate = $this->paginate($this->Mandate);

        $this->set(compact('mandate'));
    }

    /**
     * View method
     *
     * @param string|null $id Mandate id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $mandate = $this->Mandate->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('mandate'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $mandate = $this->Mandate->newEmptyEntity();
        if ($this->request->is('post')) {
            $mandate = $this->Mandate->patchEntity($mandate, $this->request->getData());
            if ($this->Mandate->save($mandate)) {
                $this->Flash->success(__('The mandate has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The mandate could not be saved. Please, try again.'));
        }
        $this->set(compact('mandate'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Mandate id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $mandate = $this->Mandate->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $mandate = $this->Mandate->patchEntity($mandate, $this->request->getData());
            if ($this->Mandate->save($mandate)) {
                $this->Flash->success(__('The mandate has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The mandate could not be saved. Please, try again.'));
        }
        $this->set(compact('mandate'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Mandate id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $mandate = $this->Mandate->get($id);
        if ($this->Mandate->delete($mandate)) {
            $this->Flash->success(__('The mandate has been deleted.'));
        } else {
            $this->Flash->error(__('The mandate could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
