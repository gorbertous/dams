<?php
declare(strict_types=1);

namespace Damsv2\Controller;

/**
 * AnnualSamplingParameters Controller
 *
 * @property \App\Model\Table\AnnualSamplingParametersTable $AnnualSamplingParameters
 * @method \App\Model\Entity\AnnualSamplingParameter[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AnnualSamplingParametersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $annualSamplingParameters = $this->paginate($this->AnnualSamplingParameters);

        $this->set(compact('annualSamplingParameters'));
    }

    /**
     * View method
     *
     * @param string|null $id Annual Sampling Parameter id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $annualSamplingParameter = $this->AnnualSamplingParameters->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('annualSamplingParameter'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $annualSamplingParameter = $this->AnnualSamplingParameters->newEmptyEntity();
        if ($this->request->is('post')) {
            $annualSamplingParameter = $this->AnnualSamplingParameters->patchEntity($annualSamplingParameter, $this->request->getData());
            if ($this->AnnualSamplingParameters->save($annualSamplingParameter)) {
                $this->Flash->success(__('The annual sampling parameter has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The annual sampling parameter could not be saved. Please, try again.'));
        }
        $this->set(compact('annualSamplingParameter'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Annual Sampling Parameter id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $annualSamplingParameter = $this->AnnualSamplingParameters->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $annualSamplingParameter = $this->AnnualSamplingParameters->patchEntity($annualSamplingParameter, $this->request->getData());
            if ($this->AnnualSamplingParameters->save($annualSamplingParameter)) {
                $this->Flash->success(__('The annual sampling parameter has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The annual sampling parameter could not be saved. Please, try again.'));
        }
        $this->set(compact('annualSamplingParameter'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Annual Sampling Parameter id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $annualSamplingParameter = $this->AnnualSamplingParameters->get($id);
        if ($this->AnnualSamplingParameters->delete($annualSamplingParameter)) {
            $this->Flash->success(__('The annual sampling parameter has been deleted.'));
        } else {
            $this->Flash->error(__('The annual sampling parameter could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
