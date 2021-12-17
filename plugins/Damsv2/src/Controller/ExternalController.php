<?php

declare(strict_types=1);

namespace Damsv2\Controller;

use Cake\Event\EventInterface;

/**
 * Validation Controller
 *
 * @method \App\Model\Entity\Validation[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ExternalController extends AppController
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

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {

        if ($this->request->is('post')) {
            ini_set('upload_max_filesize', '2G');
            // group retrieving  // http://vmu-sas-01:8080/browse/PLATFORM-259
            $groups = CakeSession::read('UserAuth.UserGroups');
            if (!is_array($groups))
                $groups = array($groups);
            if (!empty($groups))
                foreach ($groups as $group) {
                    $groupsnames[] = $group['alias_name'];
                }
            if (in_array('ReadOnlyDams', $groupsnames)) {
                $this->Session->setFlash("You are currently in a read only profile, this functionality is disabled", "flash/error");
                $this->redirect($this->referer());
            }

            if (!empty($this->request->data['Sme']['file']['tmp_name'])) {
                $file = $this->request->data['Sme']['file'];
                $file['name'] = $this->File->cleanName($file['name']);
                //$tmp_file = file_get_contents($file['tmp_name']);
                $fileMovingPath = WWW . DS . 'data' . DS . 'damsv2' . DS . 'sme_external_data' . DS . $file['name'];
                if ($this->File->checkFileInForm($file, $fileMovingPath)) {
                    $id_format = '/^[0-9]+$/';
                    $char_format = '/[a-zA-Z]+/'; //at least one letter
                    $numeric_format = '/^[0-9.]*$/';
                    $format = array(0 => $id_format, 1 => $char_format, 2 => $char_format, 3 => $char_format, 4 => $char_format, 5 => $numeric_format, 6 => $numeric_format);

                    $format_check = $this->Spreadsheet->checkFormat($fileMovingPath, $format);
                    if (!empty($format_check)) {
                        $this->Session->setFlash("Wrong values detected in the following cell(s) : " . implode(',', $format_check), "flash/error");
                        $this->redirect($this->referer());
                    }

                    //retrieve info from creation xlsx file
                    $infoFile = $this->Spreadsheet->createXlsxfile($file['name'], $fileMovingPath);
                    if (!$this->Spreadsheet->noError($infoFile['errors'])) {
                        $errors = $this->Spreadsheet->showError($infoFile['errors']);
                        $this->Session->setFlash($errors, "flash/error");
                    } else {
                        $file['name'] = $infoFile['name'];
                        $fileMovingPath = $infoFile['path'];
                        $user_id = $this->userIdentity()->get('id'); //$this->UserAuth->getUserId();
                        //Send information to SAS
                        $sasResult = $this->SAS->curl(
                                "insert_sme_external_data.sas", array(
                            "input_file" => $file['name'],
                                ),
                                false,
                                false
                        );

                        App::uses("simple_html_dom", "Vendor");
                        $html = new simple_html_dom();

                        $a = $html->load($sasResult);
                        $table = $a->find('table');
                        foreach ($table as $t) {
                            $t->class = 'table table-bordered table-striped';
                            $t->frame = '';
                            $sasResult = $t->outertext;
                        }

                        //$this->Session->setFlash($sasResult, "flash/simple");
                        //$this->redirect($this->referer());
                    }
                }
            } else {
                $this->Session->setFlash("Please choose a file to upload", "flash/error");
            }

            $this->set(compact('sasResult'));
        }
    }

}
