<?php
declare(strict_types=1);

namespace Damsv2\Controller;

/**
 * ErrorsLogDetailed Controller
 *
 * @property \App\Model\Table\ErrorsLogDetailedTable $ErrorsLogDetailed
 * @method \App\Model\Entity\ErrorsLogDetailed[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ErrorsLogDetailedController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ErrorsLog'],
        ];
        $errorsLogDetailed = $this->paginate($this->ErrorsLogDetailed);

        $this->set(compact('errorsLogDetailed'));
    }

    /**
     * View method
     *
     * @param string|null $id Errors Log Detailed id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $errorsLogDetailed = $this->ErrorsLogDetailed->get($id, [
            'contain' => ['ErrorsLog'],
        ]);

        $this->set(compact('errorsLogDetailed'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $errorsLogDetailed = $this->ErrorsLogDetailed->newEmptyEntity();
        if ($this->request->is('post')) {
            $errorsLogDetailed = $this->ErrorsLogDetailed->patchEntity($errorsLogDetailed, $this->request->getData());
            if ($this->ErrorsLogDetailed->save($errorsLogDetailed)) {
                $this->Flash->success(__('The errors log detailed has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The errors log detailed could not be saved. Please, try again.'));
        }
        $errorsLog = $this->ErrorsLogDetailed->ErrorsLog->find('list', ['limit' => 200]);
        $this->set(compact('errorsLogDetailed', 'errorsLog'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Errors Log Detailed id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $errorsLogDetailed = $this->ErrorsLogDetailed->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $errorsLogDetailed = $this->ErrorsLogDetailed->patchEntity($errorsLogDetailed, $this->request->getData());
            if ($this->ErrorsLogDetailed->save($errorsLogDetailed)) {
                $this->Flash->success(__('The errors log detailed has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The errors log detailed could not be saved. Please, try again.'));
        }
        $errorsLog = $this->ErrorsLogDetailed->ErrorsLog->find('list', ['limit' => 200]);
        $this->set(compact('errorsLogDetailed', 'errorsLog'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Errors Log Detailed id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $errorsLogDetailed = $this->ErrorsLogDetailed->get($id);
        if ($this->ErrorsLogDetailed->delete($errorsLogDetailed)) {
            $this->Flash->success(__('The errors log detailed has been deleted.'));
        } else {
            $this->Flash->error(__('The errors log detailed could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
