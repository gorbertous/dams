<?php

declare(strict_types=1);

namespace Damsv2\Controller;

use Cake\Event\EventInterface;
use App\Lib\Helpers;

/**
 * Dstoolbox Controller
 *
 * @property \App\Model\Table\DstoolboxTable $Dstoolbox
 * @method \App\Model\Entity\Dstoolbox[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DstoolboxController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        //$this->loadComponent('Security');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        //$this->Security->setConfig('blackHoleCallback', 'blackhole');
    }

    public function beforeMarshal(EventInterface $event, \ArrayObject $data, \ArrayObject $options)
    {
        if ($data['filename_temp'] === '') {
            unset($data['filename_temp']);
        }
    }


    // file upload path
    public $dstuploadpath = "/var/www/html/data/damsv2/DSToolbox/";

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => [],
        ];
        $dstoolbox = $this->paginate($this->Dstoolbox);

        $this->set(compact('dstoolbox'));
    }

    /**
     * View method
     *
     * @param string|null $id Dstoolbox id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $dstoolbox = $this->Dstoolbox->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('dstoolbox'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $dstoolbox = $this->Dstoolbox->newEmptyEntity();
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            $dstoolbox = $this->Dstoolbox->patchEntity($dstoolbox, $postData);

            if (!$dstoolbox->getErrors) {
                $fileobject = $this->request->getData('filename_temp');
                $fileName = $fileobject->getClientFilename();

                if (!empty($fileName)) {
                    //clean up file name
                    Helpers::normalise(str_replace(' ', '', $fileName));
                    $destination = $this->dstuploadpath . $fileName;
                    $fileobject->moveTo($destination);
                    $dstoolbox->filename = $fileName;
                }
            }

            if ($this->Dstoolbox->save($dstoolbox)) {

                $this->Flash->success(__('The data has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The data could not be saved. Please, try again.'));
        }

        //$domains = $this->Dstoolbox->Domains->find('list', ['limit' => 200]);
        $this->set(compact('dstoolbox'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Dstoolbox id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $dstoolbox = $this->Dstoolbox->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            $dstoolbox = $this->Dstoolbox->patchEntity($dstoolbox, $this->request->getData());

            if (!$dstoolbox->getErrors) {
                $fileobject = $this->request->getData('filename_temp');
                $fileName = $fileobject->getClientFilename();

                if (!empty($fileName)) {
                    //clean up file name
                    Helpers::normalise(str_replace(' ', '', $fileName));
                    $destination = $this->dstuploadpath . $fileName;
                    $fileobject->moveTo($destination);
                    $dstoolbox->filename = $fileName;
                }
            }

            if ($this->Dstoolbox->save($dstoolbox)) {
                $this->Flash->success(__('The data has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The data could not be saved. Please, try again.'));
        }

        $this->set(compact('dstoolbox'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Dstoolbox id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dstoolbox = $this->Dstoolbox->get($id);
        if ($this->Dstoolbox->delete($dstoolbox)) {
            $this->Flash->success(__('The data has been deleted.'));
        } else {
            $this->Flash->error(__('The data could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

}
