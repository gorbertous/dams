<?php
declare(strict_types=1);

namespace Damsv2\Controller;

/**
 * ErrorsLog Controller
 *
 * @property \App\Model\Table\ErrorsLogTable $ErrorsLog
 * @method \App\Model\Entity\ErrorsLog[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ErrorsLogController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Portfolio', 'Report'],
        ];
        $errorsLog = $this->paginate($this->ErrorsLog);

        $this->set(compact('errorsLog'));
    }

    /**
     * View method
     *
     * @param string|null $id Errors Log id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $errorsLog = $this->ErrorsLog->get($id, [
            'contain' => ['Portfolio', 'Report'],
        ]);

        $this->set(compact('errorsLog'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $errorsLog = $this->ErrorsLog->newEmptyEntity();
        if ($this->request->is('post')) {
            $errorsLog = $this->ErrorsLog->patchEntity($errorsLog, $this->request->getData());
            if ($this->ErrorsLog->save($errorsLog)) {
                $this->Flash->success(__('The errors log has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The errors log could not be saved. Please, try again.'));
        }
        $portfolios = $this->ErrorsLog->Portfolio->find('list', ['limit' => 200]);
        $reports = $this->ErrorsLog->Report->find('list', ['limit' => 200]);
        $this->set(compact('errorsLog', 'portfolios', 'reports'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Errors Log id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $errorsLog = $this->ErrorsLog->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $errorsLog = $this->ErrorsLog->patchEntity($errorsLog, $this->request->getData());
            if ($this->ErrorsLog->save($errorsLog)) {
                $this->Flash->success(__('The errors log has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The errors log could not be saved. Please, try again.'));
        }
        $portfolios = $this->ErrorsLog->Portfolio->find('list', ['limit' => 200]);
        $reports = $this->ErrorsLog->Report->find('list', ['limit' => 200]);
        $this->set(compact('errorsLog', 'portfolios', 'reports'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Errors Log id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $errorsLog = $this->ErrorsLog->get($id);
        if ($this->ErrorsLog->delete($errorsLog)) {
            $this->Flash->success(__('The errors log has been deleted.'));
        } else {
            $this->Flash->error(__('The errors log could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
