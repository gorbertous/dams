<?php
declare(strict_types=1);

namespace Dsr\Controller;

/**
 * DicoValues Controller
 *
 * @property \Dsr\Model\Table\DicoValuesTable $DicoValues
 * @method \Dsr\Model\Entity\DicoValue[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DicoValuesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Dictionaries'],
        ];
        $dicoValues = $this->paginate($this->DicoValues);

        $this->set(compact('dicoValues'));
    }

    /**
     * View method
     *
     * @param string|null $id Dico Value id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $dicoValue = $this->DicoValues->get($id, [
            'contain' => ['Dictionaries'],
        ]);

        $this->set(compact('dicoValue'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $dicoValue = $this->DicoValues->newEmptyEntity();
        if ($this->request->is('post')) {
            $dicoValue = $this->DicoValues->patchEntity($dicoValue, $this->request->getData());
            if ($this->DicoValues->save($dicoValue)) {
                $this->Flash->success(__('The dico value has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The dico value could not be saved. Please, try again.'));
        }
        $dictionaries = $this->DicoValues->Dictionaries->find('list', ['limit' => 200]);
        $this->set(compact('dicoValue', 'dictionaries'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Dico Value id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $dicoValue = $this->DicoValues->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $dicoValue = $this->DicoValues->patchEntity($dicoValue, $this->request->getData());
            if ($this->DicoValues->save($dicoValue)) {
                $this->Flash->success(__('The dico value has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The dico value could not be saved. Please, try again.'));
        }
        $dictionaries = $this->DicoValues->Dictionaries->find('list', ['limit' => 200]);
        $this->set(compact('dicoValue', 'dictionaries'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Dico Value id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dicoValue = $this->DicoValues->get($id);
        if ($this->DicoValues->delete($dicoValue)) {
            $this->Flash->success(__('The dico value has been deleted.'));
        } else {
            $this->Flash->error(__('The dico value could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
