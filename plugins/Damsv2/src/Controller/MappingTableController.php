<?php
declare(strict_types=1);

namespace Damsv2\Controller;

/**
 * MappingTable Controller
 *
 * @property \App\Model\Table\MappingTableTable $MappingTable
 * @method \App\Model\Entity\MappingTable[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MappingTableController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Templates'],
        ];
        $mappingTable = $this->paginate($this->MappingTable);

        $this->set(compact('mappingTable'));
    }

    /**
     * View method
     *
     * @param string|null $id Mapping Table id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $mappingTable = $this->MappingTable->get($id, [
            'contain' => ['Templates'],
        ]);

        $this->set(compact('mappingTable'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $mappingTable = $this->MappingTable->newEmptyEntity();
        if ($this->request->is('post')) {
            $mappingTable = $this->MappingTable->patchEntity($mappingTable, $this->request->getData());
            if ($this->MappingTable->save($mappingTable)) {
                $this->Flash->success(__('The mapping table has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The mapping table could not be saved. Please, try again.'));
        }
        $templates = $this->MappingTable->Templates->find('list', ['limit' => 200]);
        $this->set(compact('mappingTable', 'templates'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Mapping Table id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $mappingTable = $this->MappingTable->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $mappingTable = $this->MappingTable->patchEntity($mappingTable, $this->request->getData());
            if ($this->MappingTable->save($mappingTable)) {
                $this->Flash->success(__('The mapping table has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The mapping table could not be saved. Please, try again.'));
        }
        $templates = $this->MappingTable->Templates->find('list', ['limit' => 200]);
        $this->set(compact('mappingTable', 'templates'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Mapping Table id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $mappingTable = $this->MappingTable->get($id);
        if ($this->MappingTable->delete($mappingTable)) {
            $this->Flash->success(__('The mapping table has been deleted.'));
        } else {
            $this->Flash->error(__('The mapping table could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
