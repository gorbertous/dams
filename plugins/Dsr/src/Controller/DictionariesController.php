<?php
declare(strict_types=1);

namespace Dsr\Controller;

/**
 * Dictionaries Controller
 *
 * @property \Dsr\Model\Table\DictionariesTable $Dictionaries
 * @method \Dsr\Model\Entity\Dictionary[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DictionariesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $dictionaries = $this->paginate($this->Dictionaries);

        $this->set(compact('dictionaries'));
    }

    /**
     * View method
     *
     * @param string|null $id Dictionary id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $dictionary = $this->Dictionaries->get($id, [
            'contain' => ['DicoValues'],
        ]);

        $this->set(compact('dictionary'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $dictionary = $this->Dictionaries->newEmptyEntity();
        if ($this->request->is('post')) {
            $dictionary = $this->Dictionaries->patchEntity($dictionary, $this->request->getData());
            if ($this->Dictionaries->save($dictionary)) {
                $this->Flash->success(__('The dictionary has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The dictionary could not be saved. Please, try again.'));
        }
        $this->set(compact('dictionary'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Dictionary id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $dictionary = $this->Dictionaries->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $dictionary = $this->Dictionaries->patchEntity($dictionary, $this->request->getData());
            if ($this->Dictionaries->save($dictionary)) {
                $this->Flash->success(__('The dictionary has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The dictionary could not be saved. Please, try again.'));
        }
        $this->set(compact('dictionary'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Dictionary id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dictionary = $this->Dictionaries->get($id);
        if ($this->Dictionaries->delete($dictionary)) {
            $this->Flash->success(__('The dictionary has been deleted.'));
        } else {
            $this->Flash->error(__('The dictionary could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
