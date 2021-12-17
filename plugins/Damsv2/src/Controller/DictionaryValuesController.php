<?php
declare(strict_types=1);

namespace Damsv2\Controller;

/**
 * DictionaryValues Controller
 *
 * @property \App\Model\Table\DictionaryValuesTable $DictionaryValues
 * @method \App\Model\Entity\DictionaryValue[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DictionaryValuesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Dictionary'],
        ];
        $dictionaryValues = $this->paginate($this->DictionaryValues);

        $this->set(compact('dictionaryValues'));
    }

    /**
     * View method
     *
     * @param string|null $id Dictionary Value id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $dictionaryValue = $this->DictionaryValues->get($id, [
            'contain' => ['Dictionary'],
        ]);

        $this->set(compact('dictionaryValue'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $dictionaryValue = $this->DictionaryValues->newEmptyEntity();
        if ($this->request->is('post')) {
            $dictionaryValue = $this->DictionaryValues->patchEntity($dictionaryValue, $this->request->getData());
            if ($this->DictionaryValues->save($dictionaryValue)) {
                $this->Flash->success(__('The dictionary value has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The dictionary value could not be saved. Please, try again.'));
        }
        $dictionary = $this->DictionaryValues->Dictionary->find('list', ['limit' => 200]);
        $this->set(compact('dictionaryValue', 'dictionary'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Dictionary Value id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $dictionaryValue = $this->DictionaryValues->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $dictionaryValue = $this->DictionaryValues->patchEntity($dictionaryValue, $this->request->getData());
            if ($this->DictionaryValues->save($dictionaryValue)) {
                $this->Flash->success(__('The dictionary value has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The dictionary value could not be saved. Please, try again.'));
        }
        $dictionary = $this->DictionaryValues->Dictionary->find('list', ['limit' => 200]);
        $this->set(compact('dictionaryValue', 'dictionary'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Dictionary Value id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dictionaryValue = $this->DictionaryValues->get($id);
        if ($this->DictionaryValues->delete($dictionaryValue)) {
            $this->Flash->success(__('The dictionary value has been deleted.'));
        } else {
            $this->Flash->error(__('The dictionary value could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
