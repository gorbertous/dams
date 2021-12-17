<?php
declare(strict_types=1);

namespace Damsv2\Controller;

/**
 * BrParameter Controller
 *
 * @property \App\Model\Table\BrParameterTable $BrParameter
 * @method \App\Model\Entity\BrParameter[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BrParameterController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['TemplateType', 'Product', 'Mandate', 'Portfolio', 'Dictionary'],
        ];
        $brParameter = $this->paginate($this->BrParameter);

        $this->set(compact('brParameter'));
    }

    /**
     * View method
     *
     * @param string|null $id Br Parameter id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $brParameter = $this->BrParameter->get($id, [
            'contain' => ['TemplateType', 'Product', 'Mandate', 'Portfolio', 'Dictionary'],
        ]);

        $this->set(compact('brParameter'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $brParameter = $this->BrParameter->newEmptyEntity();
        if ($this->request->is('post')) {
            $brParameter = $this->BrParameter->patchEntity($brParameter, $this->request->getData());
            if ($this->BrParameter->save($brParameter)) {
                $this->Flash->success(__('The br parameter has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The br parameter could not be saved. Please, try again.'));
        }
        $templateTypes = $this->BrParameter->TemplateType->find('list', ['limit' => 200]);
        $products = $this->BrParameter->Product->find('list', ['limit' => 200]);
        $mandates = $this->BrParameter->Mandate->find('list', ['limit' => 200]);
        $portfolios = $this->BrParameter->Portfolio->find('list', ['limit' => 200]);
        $dictionaries = $this->BrParameter->Dictionary->find('list', ['limit' => 200]);
        $this->set(compact('brParameter', 'templateTypes', 'products', 'mandates', 'portfolios', 'dictionaries'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Br Parameter id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $brParameter = $this->BrParameter->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $brParameter = $this->BrParameter->patchEntity($brParameter, $this->request->getData());
            if ($this->BrParameter->save($brParameter)) {
                $this->Flash->success(__('The br parameter has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The br parameter could not be saved. Please, try again.'));
        }
        $templateTypes = $this->BrParameter->TemplateType->find('list', ['limit' => 200]);
        $products = $this->BrParameter->Product->find('list', ['limit' => 200]);
        $mandates = $this->BrParameter->Mandate->find('list', ['limit' => 200]);
        $portfolios = $this->BrParameter->Portfolio->find('list', ['limit' => 200]);
        $dictionaries = $this->BrParameter->Dictionary->find('list', ['limit' => 200]);
        $this->set(compact('brParameter', 'templateTypes', 'products', 'mandates', 'portfolios', 'dictionaries'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Br Parameter id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $brParameter = $this->BrParameter->get($id);
        if ($this->BrParameter->delete($brParameter)) {
            $this->Flash->success(__('The br parameter has been deleted.'));
        } else {
            $this->Flash->error(__('The br parameter could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
