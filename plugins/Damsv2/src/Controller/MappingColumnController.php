<?php
declare(strict_types=1);

namespace Damsv2\Controller;

/**
 * MappingColumn Controller
 *
 * @property \App\Model\Table\MappingColumnTable $MappingColumn
 * @method \App\Model\Entity\MappingColumn[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MappingColumnController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Tables', 'Dbs', 'Fks', 'Dictionaries'],
        ];
        $mappingColumn = $this->paginate($this->MappingColumn);

        $this->set(compact('mappingColumn'));
    }

    /**
     * View method
     *
     * @param string|null $id Mapping Column id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $mappingColumn = $this->MappingColumn->get($id, [
            'contain' => ['Tables', 'Dbs', 'Fks', 'Dictionaries'],
        ]);

        $this->set(compact('mappingColumn'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $mappingColumn = $this->MappingColumn->newEmptyEntity();
        if ($this->request->is('post')) {
            $mappingColumn = $this->MappingColumn->patchEntity($mappingColumn, $this->request->getData());
            if ($this->MappingColumn->save($mappingColumn)) {
                $this->Flash->success(__('The mapping column has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The mapping column could not be saved. Please, try again.'));
        }
        $tables = $this->MappingColumn->Tables->find('list', ['limit' => 200]);
        $dbs = $this->MappingColumn->Dbs->find('list', ['limit' => 200]);
        $fks = $this->MappingColumn->Fks->find('list', ['limit' => 200]);
        $dictionaries = $this->MappingColumn->Dictionaries->find('list', ['limit' => 200]);
        $this->set(compact('mappingColumn', 'tables', 'dbs', 'fks', 'dictionaries'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Mapping Column id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $mappingColumn = $this->MappingColumn->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $mappingColumn = $this->MappingColumn->patchEntity($mappingColumn, $this->request->getData());
            if ($this->MappingColumn->save($mappingColumn)) {
                $this->Flash->success(__('The mapping column has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The mapping column could not be saved. Please, try again.'));
        }
        $tables = $this->MappingColumn->Tables->find('list', ['limit' => 200]);
        $dbs = $this->MappingColumn->Dbs->find('list', ['limit' => 200]);
        $fks = $this->MappingColumn->Fks->find('list', ['limit' => 200]);
        $dictionaries = $this->MappingColumn->Dictionaries->find('list', ['limit' => 200]);
        $this->set(compact('mappingColumn', 'tables', 'dbs', 'fks', 'dictionaries'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Mapping Column id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $mappingColumn = $this->MappingColumn->get($id);
        if ($this->MappingColumn->delete($mappingColumn)) {
            $this->Flash->success(__('The mapping column has been deleted.'));
        } else {
            $this->Flash->error(__('The mapping column could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
