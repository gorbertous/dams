<?php
declare(strict_types=1);

namespace Damsv2\Controller;

/**
 * TemplateType Controller
 *
 * @property \App\Model\Table\TemplateTypeTable $TemplateType
 * @method \App\Model\Entity\TemplateType[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TemplateTypeController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $templateType = $this->paginate($this->TemplateType);

        $this->set(compact('templateType'));
    }

    /**
     * View method
     *
     * @param string|null $id Template Type id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $templateType = $this->TemplateType->get($id, [
            'contain' => ['Rules', 'RulesLogHistory', 'Template'],
        ]);

        $this->set(compact('templateType'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $templateType = $this->TemplateType->newEmptyEntity();
        if ($this->request->is('post')) {
            $templateType = $this->TemplateType->patchEntity($templateType, $this->request->getData());
            if ($this->TemplateType->save($templateType)) {
                $this->Flash->success(__('The template type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The template type could not be saved. Please, try again.'));
        }
        $this->set(compact('templateType'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Template Type id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $templateType = $this->TemplateType->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $templateType = $this->TemplateType->patchEntity($templateType, $this->request->getData());
            if ($this->TemplateType->save($templateType)) {
                $this->Flash->success(__('The template type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The template type could not be saved. Please, try again.'));
        }
        $this->set(compact('templateType'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Template Type id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $templateType = $this->TemplateType->get($id);
        if ($this->TemplateType->delete($templateType)) {
            $this->Flash->success(__('The template type has been deleted.'));
        } else {
            $this->Flash->error(__('The template type could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
