<?php

declare(strict_types=1);

namespace Damsv2\Controller;

//use Cake\Datasource\ConnectionManager;
use KubAT\PhpSimple\HtmlDomParser;
//use App\Lib\DownloadLib;
use Cake\Event\EventInterface;

/**
 * SmeRatingMapping Controller
 *
 * @property \App\Model\Table\SmeRatingMappingTable $SmeRatingMapping
 * @method \App\Model\Entity\SmeRatingMapping[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SmeRatingMappingController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        //$this->loadComponent('Security');
        $this->loadComponent('SAS');
        $this->loadComponent('Spreadsheet');
        $this->loadComponent('File');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        //$this->Security->setConfig('unlockedActions', ['inclusion']);
        //$this->Security->setConfig('blackHoleCallback', 'blackhole');
    }

    public function uploadRating()
    {
        $correction = !empty($this->request->getData('SmeRating.correction')) ? intval($this->request->getData('SmeRating.correction')) : 0;

        if ($this->request->is('post')) {
            // group retrieving  // http://vmu-sas-01:8080/browse/PLATFORM-259
//            $groups = CakeSession::read('UserAuth.UserGroups');
//            if (!is_[$groups))
//                $groups = array($groups);
//            if (!empty($groups))
//                foreach ($groups as $group) {
//                    $groupsnames[] = $group['alias_name'];
//                }
//            if (in_array('ReadOnlyDams', $groupsnames)) {
//                $this->Flash->error("You are currently in a read only profile, this functionnality is disabled", "flash/error");
//                $this->redirect($this->referer());
//            }

            $file = $this->request->getData('SmeRating.file');
            $file_name = $file->getClientFilename();

            $ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $file_renamed = $this->File->cleanName($file_name);

            $fileMovingPath = '/var/www/html' . DS . 'data' . DS . 'damsv2' . DS . 'sme_rating_mapping' . DS . 'tmp' . DS . $file_renamed;

            if ($this->File->checkFileInForm($file, $fileMovingPath, ['xls'])) {
               
                //$file['name'] = $file_renamed;
                $report_id = 16066; //for DEV 5258, for UAT 11152,  for PROD 16066 since we passed PROD DATA in UAT
             
                $data = ['SmeRating' => ['sheets' => ['mapping']], 'Template' => ['id' => 242], 'correction' => $correction]; //TODO update template id

                $checks = $this->Spreadsheet->checkSheetSmeRatingUpdate($data, $file, $fileMovingPath);

                if ($this->Spreadsheet->noError($checks['errors'])) {

                    $file_renamed = $checks['result']['filename']; //converted to xlsx or xls
                    $owner = $this->userIdentity()->get('id'); //CakeSession::read('UserAuth.User.id');
                  
                    $this->loadModel('Damsv2.Report');
                    $report_data = $this->Report->find()->where(['report_id' => $report_id])->first();
                    $report_data->input_filename = $file_renamed;
                    $report_data->owner = $owner;
                    
                    $this->Report->save($report_data);
                    $this->logDams('SME Rating Mapping upload {"report_id": "' . $report_id . '"}', 'dams', 'SME Rating Mapping upload');

                    $sasResult = $this->SAS->curl(
                            'import_file.sas', [
                        'report_id'        => $report_id,
                        'template_type_id' => 14, //only one for sme rating => always 14
                        'correction'       => $correction,
                        'save'             => 0,
                        'user_id'          => $owner,
                            ],
                            false,
                            false
                    );

                    $dom = HtmlDomParser::str_get_html($sasResult);

                    $table = $dom->find('table');
                    $result = '';
                    foreach ($table as $t) {
                        if ($t->id == 'sasres') {
                            $t->class = 'table table-bordered table-striped';
                            $t->frame = '';
                            $result .= $t->outertext;
                        }
                    }
                    $error_file = '';
                    $link = $dom->find('#error_file');
                    foreach ($link as $l) {
                        $error_file = trim($l->href);
                    }
                    if (strpos($result, 'The SME rating mapping has been successfully updated in the database!') !== false) {// TODO : have this message in SAS part
                        $this->set('success', true);
                        $this->Flash->success("The SME rating mapping has been successfully updated in the database.");
                    } else {
                        //correction
                        $correction = 1;
                        //$result = DownloadLib::change_downloadable_links($result);
                        $this->set('correction', $correction);
                        $this->set('error_file', $error_file);
                        $this->set('sasResult', $sasResult);
                        if ($error_file == '') {
                            $this->Flash->error("An error occurred. Please contact EIF SAS Support for more information.");
                        }
                    }
                } else {
                    //$this->ErrorsLog->checkErrorImport($report, 'NOT OK');
                    $msg = $this->Spreadsheet->showError($checks['errors']);

                    $this->Flash->error($msg, ['escape' => false]);
                    $this->redirect($this->referer());
                }
            }
        }
        $this->set('correction', $correction);
    }

    /**
     * download_rating method
     *
     * @throws NotFoundException
     *
     * @return void
     */
    public function downloadRating()
    {
        //$this->loadModel('Damsv2.SmeRatingMapping');

        $portfolios = $this->SmeRatingMapping->find('list', [
            'keyField'   => 'portfolio_id',
            'valueField' => 'portfolio_id',
            'group'      => 'portfolio_id',
            'order'      => 'portfolio_id ASC'
        ]);

        $this->set('portfolios', $portfolios);

        if ($this->request->is('post') && !empty($this->request->getData('SmeRating.portfolio_id'))) {
            
            $smeportfolios = $this->SmeRatingMapping->find();
            $smeportfolios->where(['portfolio_id' => $this->request->getData('SmeRating.portfolio_id')]);
            $smeportfolios->select(['Portfolio_ID' => 'portfolio_id', 'Reported_FI_scale' => 'sme_fi_rating_scale', 'Reported_SME_rating' => 'sme_rating', 'Adjusted_FI_rating_Scale' => 'adjusted_sme_fi_scale', 'Adjusted_SME_rating' => 'adjusted_sme_rating', 'Equivalent_Originator_SME_rating' => 'equiv_ori_sme_rating']);
    
            $filepath = '/var/www/html/data/damsv2/sme_rating_mapping/download/sme_rating_mapping_' . time() . '.xlsx';
            $sheetname = ['SME Rating Mapping'];
            $this->Spreadsheet->generateExcelFromQueryDefaultHeaderMapping($smeportfolios->toArray(), $sheetname, $filepath);
           
            $download_link = '/damsv2/ajax/download-file/' . basename($filepath) . '/sme_rating_mapping/download';
           
            $this->set('download_link', $download_link);
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Portfolio', 'VUser'],
        ];
        $smeRatingMapping = $this->paginate($this->SmeRatingMapping);

        $this->set(compact('smeRatingMapping'));
    }

    /**
     * View method
     *
     * @param string|null $id Sme Rating Mapping id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $smeRatingMapping = $this->SmeRatingMapping->get($id, [
            'contain' => ['Portfolio', 'VUser'],
        ]);

        $this->set(compact('smeRatingMapping'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $smeRatingMapping = $this->SmeRatingMapping->newEmptyEntity();
        if ($this->request->is('post')) {
            $smeRatingMapping = $this->SmeRatingMapping->patchEntity($smeRatingMapping, $this->request->getData());
            if ($this->SmeRatingMapping->save($smeRatingMapping)) {
                $this->Flash->success(__('The sme rating mapping has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sme rating mapping could not be saved. Please, try again.'));
        }
        $portfolio = $this->SmeRatingMapping->Portfolio->find('list', ['limit' => 200]);
        $users = $this->SmeRatingMapping->VUser->find('list', ['limit' => 200]);
        $this->set(compact('smeRatingMapping', 'portfolio', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sme Rating Mapping id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $smeRatingMapping = $this->SmeRatingMapping->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $smeRatingMapping = $this->SmeRatingMapping->patchEntity($smeRatingMapping, $this->request->getData());
            if ($this->SmeRatingMapping->save($smeRatingMapping)) {
                $this->Flash->success(__('The sme rating mapping has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sme rating mapping could not be saved. Please, try again.'));
        }
        $portfolio = $this->SmeRatingMapping->Portfolio->find('list', ['limit' => 200]);
        $users = $this->SmeRatingMapping->VUser->find('list', ['limit' => 200]);
        $this->set(compact('smeRatingMapping', 'portfolio', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sme Rating Mapping id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $smeRatingMapping = $this->SmeRatingMapping->get($id);
        if ($this->SmeRatingMapping->delete($smeRatingMapping)) {
            $this->Flash->success(__('The sme rating mapping has been deleted.'));
        } else {
            $this->Flash->error(__('The sme rating mapping could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

}
