<?php
declare(strict_types=1);

namespace Damsv2\Controller;

/**
 * Toolbox Controller
 *
 * @property \App\Model\Table\ToolboxTable $Toolbox
 * @method \App\Model\Entity\Toolbox[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ToolboxController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $toolbox = $this->paginate($this->Toolbox);

        $this->set(compact('toolbox'));
    }

    /**
     * View method
     *
     * @param string|null $id Toolbox id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $toolbox = $this->Toolbox->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('toolbox'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $toolbox = $this->Toolbox->newEmptyEntity();
        if ($this->request->is('post')) {
            $toolbox = $this->Toolbox->patchEntity($toolbox, $this->request->getData());
            if ($this->Toolbox->save($toolbox)) {
                $this->Flash->success(__('The toolbox has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The toolbox could not be saved. Please, try again.'));
        }
        $this->set(compact('toolbox'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Toolbox id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $toolbox = $this->Toolbox->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $toolbox = $this->Toolbox->patchEntity($toolbox, $this->request->getData());
            if ($this->Toolbox->save($toolbox)) {
                $this->Flash->success(__('The toolbox has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The toolbox could not be saved. Please, try again.'));
        }
        $this->set(compact('toolbox'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Toolbox id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $toolbox = $this->Toolbox->get($id);
        if ($this->Toolbox->delete($toolbox)) {
            $this->Flash->success(__('The toolbox has been deleted.'));
        } else {
            $this->Flash->error(__('The toolbox could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
