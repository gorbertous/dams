<?php

declare(strict_types=1);

namespace Damsv2\Controller;

use Cake\Event\EventInterface;
use App\Lib\Helpers;

/**
 * Dictionary Controller
 *
 * @property \App\Model\Table\DictionaryTable $Dictionary
 * @method \App\Model\Entity\Dictionary[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DictionaryController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        //$this->loadComponent('Security');
        $this->loadComponent('Spreadsheet');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        //$this->Security->setConfig('blackHoleCallback', 'blackhole');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        //get request params
        $dictid = $this->request->getQuery('Dictionary.id');
        $dictname = $this->request->getQuery('Dictionary.name');
        $mandate = $this->request->getQuery('Dictionary.mandate');
        $template = $this->request->getQuery('Dictionary.template');
        $field = $this->request->getQuery('Dictionary.field');

        // data for search dropdowns
        $this->loadModel('Damsv2.Portfolio');
        $mandates = $this->Portfolio->find('list', [
                    'keyField'   => 'mandate',
                    'valueField' => 'mandate'
                ])->where(['mandate <>' => ''])->order(['mandate' => 'ASC'])->toArray();



        $conditions = [];

        if ($dictid) {
            $dictid = '%' . $dictid . '%';
            $conditions = Helpers::arrayPushAssoc($conditions, 'cast(dictionary_id as char) LIKE', $dictid);
        }
        if ($dictname) {
            $dictname = '%' . $dictname . '%';
            $conditions = Helpers::arrayPushAssoc($conditions, 'name LIKE', $dictname);
        }
        if ($mandate) {
            $portfolios = $this->Portfolio->find('list', [
                        'conditions' => ['mandate' => $mandate],
                        'fields'     => ['portfolio_id'],
                        'keyField'   => 'portfolio_id',
                        'valueField' => 'portfolio_id'
                    ])->toArray();

            $this->loadModel('Damsv2.Report');
            $templates_ids = $this->Report->find('list', [
                        'conditions' => ['portfolio_id IN' => $portfolios],
                        'fields'     => ['template_id'],
                        'keyField'   => 'template_id',
                        'valueField' => 'template_id'
                    ])->toArray();

            $this->loadModel('Damsv2.MappingTable');
            $dictionary_id_list = [];
            if (count($templates_ids) >= 1) {
                $table_ids = $this->MappingTable->find('list', [
                            'conditions' => ['template_id IN' => $templates_ids],
                            'fields'     => ['table_id'],
                            'keyField'   => 'table_id',
                            'valueField' => 'table_id'
                        ])->toArray();
                $this->loadModel('Damsv2.MappingColumn');
                if (count($table_ids) >= 1) {
                    $dictionary_id_list = $this->MappingColumn->find('list', [
                        'conditions' => ['table_id IN' => $table_ids, 'dictionary_id is not' => null],
                        'fields'     => ['dictionary_id'],
                        'keyField'   => 'dictionary_id',
                        'valueField' => 'dictionary_id'
                    ])->toArray();
                }
                if (count($dictionary_id_list) < 1) {
                    $conditions = Helpers::arrayPushAssoc($conditions, 'dictionary_id is', null);
                } else {
                    $conditions = Helpers::arrayPushAssoc($conditions, 'dictionary_id IN', $dictionary_id_list);
                }
                $this->loadModel('Damsv2.Template');            
                $templates = $this->Template->find('list', [
                            'keyField'   => 'template_id',
                            'valueField' => 'name'
                        ])->where(['template_id IN' => $templates_ids])->order(['name' => 'ASC'])->toArray();    
            } else {
                $templates = [];
                $conditions = Helpers::arrayPushAssoc($conditions, 'dictionary_id is', null);
            }
        } else {
            $templates = [];
        }
        if ($mandate && $template) {
            $this->loadModel('Damsv2.MappingTable');
            $table_ids = $this->MappingTable->find('list', [
                        'conditions' => ['template_id' => $template],
                        'fields'     => ['table_id'],
                        'keyField'   => 'table_id',
                        'valueField' => 'table_id'
                    ])->toArray();

            $this->loadModel('Damsv2.MappingColumn');
            $dictionary_id_list = $this->MappingColumn->find('list', [
                        'conditions' => ['table_id IN' => $table_ids, 'dictionary_id is not' => null],
                        'fields'     => ['dictionary_id'],
                        'keyField'   => 'dictionary_id',
                        'valueField' => 'dictionary_id'
                    ])->toArray();
            $fields = $this->MappingColumn->find('list', [
                'keyField'   => 'table_field',
                'valueField' => 'table_field'
            ])->where(['table_id IN' => $table_ids, 'dictionary_id is not' => null])->group(['table_field'])->order(['table_field' => 'ASC'])->toArray();    

            $conditions = Helpers::arrayPushAssoc($conditions, 'dictionary_id IN', $dictionary_id_list);
        } else {
            $fields = [];
        }

        if ($mandate && $template && $field) {
            $this->loadModel('Damsv2.MappingColumn');
            $dictionary_id_list = $this->MappingColumn->find('list', [
                        'conditions' => ['table_id IN' => $table_ids, 'table_field' => $field, 'dictionary_id is not' => null],
                        'fields'     => ['dictionary_id'],
                        'keyField'   => 'dictionary_id',
                        'valueField' => 'dictionary_id'
                    ])->toArray();
                    $conditions = Helpers::arrayPushAssoc($conditions, 'dictionary_id IN', $dictionary_id_list);
        }
        if (isset($conditions['dictionary_id IN']) && $conditions['dictionary_id IN']==[]) {
            unset($conditions['dictionary_id IN']);
            $conditions = Helpers::arrayPushAssoc($conditions, 'dictionary_id is', null);
        }
        $query = $this->Dictionary->find('all', [
            'conditions' => [$conditions]
        ]);
        
        $dictionary = $this->paginate($query);
        $this->set(compact('dictionary', 'mandates', 'templates', 'fields'));
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
        $conditions = ['DictionaryValues.dictionary_id' => $id];
        $this->loadModel('Damsv2.Portfolio');

        //get request params
        $did = $this->request->getQuery('Dictionary.id');
        $code = $this->request->getQuery('Dictionary.code');
        $translation = $this->request->getQuery('Dictionary.translation');
        $label = $this->request->getQuery('Dictionary.label');

        if ($did != null && $did !== '') {
            $did = '%' . $did . '%';
            $conditions = Helpers::arrayPushAssoc($conditions, 'cast(dicoval_id as char) LIKE', $did);
        }

        if ($code) {
            $code = '%' . $code . '%';
            $conditions = Helpers::arrayPushAssoc($conditions, 'code LIKE', $code);
        }

        if ($translation) {
            $translation = '%' . $translation . '%';
            $conditions = Helpers::arrayPushAssoc($conditions, 'translation LIKE', $translation);
        }

        if ($label) {
            $label = '%' . $label . '%';
            $conditions = Helpers::arrayPushAssoc($conditions, 'label LIKE', $label);
        }


        $dvalues = $this->getTableLocator()->get('Damsv2.DictionaryValues');
        $query = $dvalues->find('all', [
            'conditions' => [$conditions]
        ]);

        $dictionary = $this->Dictionary->get($id);

        $dictionaryValues = $this->paginate($query);
        $this->set(compact('dictionaryValues', 'dictionary'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $dictionary = $this->Dictionary->newEmptyEntity();
        if ($this->request->is('post')) {
            $dictionary = $this->Dictionary->patchEntity($dictionary, $this->request->getData());
            if ($this->Dictionary->save($dictionary)) {
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
        $dictionary = $this->Dictionary->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $dictionary = $this->Dictionary->patchEntity($dictionary, $this->request->getData());
            if ($this->Dictionary->save($dictionary)) {
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
        $dictionary = $this->Dictionary->get($id);
        if ($this->Dictionary->delete($dictionary)) {
            $this->Flash->success(__('The dictionary has been deleted.'));
        } else {
            $this->Flash->error(__('The dictionary could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

}
