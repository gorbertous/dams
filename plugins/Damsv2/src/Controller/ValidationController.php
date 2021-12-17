<?php

declare(strict_types=1);

namespace Damsv2\Controller;

use Cake\Cache\Cache;
use Cake\Event\EventInterface;
use KubAT\PhpSimple\HtmlDomParser;
use Cake\Http\Exception\NotFoundException;

/**
 * Validation Controller
 *
 * @method \App\Model\Entity\Validation[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ValidationController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        //$this->loadComponent('Security');
        $this->loadComponent('SAS');
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
        $validation = $this->paginate($this->Validation);

        $this->set(compact('validation'));
    }

    /**
     * View method
     *
     * @param string|null $id Validation id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $validation = $this->Validation->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('validation'));
    }

    public function inclusionValidation($report_id)
    {
        if (empty($report_id)) {
            throw new NotFoundException;
        }
        $report_path = '/var/www/html/data/damsv2/reports/';
        clearstatcache(true);
        if (file_exists($report_path . 'eif_inclusion_validation_' . $report_id)) {
            $sasResult = file_get_contents($report_path . 'eif_inclusion_validation_' . $report_id);
        } else {
            $sasResult = $this->SAS->get_cached_content('inclusion_validation_' . $report_id, "damsv2", "inclusion_validation.sas", ['report_id' => $report_id], false);
        }
        if (empty($sasResult)) {
            $this->Flash->error('The inclusion report is missing!');
            $this->redirect($this->referer());
        } else {
            $this->loadModel('Damsv2.Report');
            $report = $this->Report->get($report_id, [
                'contain' => ['Portfolio']
            ]);

            if (!empty($report)) {
                $modifications_expected = $report->portfolio->modifications_expected;
                $m_files_link = $report->portfolio->m_files_link;
                $this->set('modifications_expected', $modifications_expected);
                if (!empty($report->m_files_link)) {
                    $m_files_link = $report->m_files_link;
                }
                $this->loadModel('Damsv2.VUser');
                if (!empty($report->inclusion_notice_validator)) {
                    $user = $this->VUser->find('all', array(
                        'conditions' => array('id' => $report->inclusion_notice_validator),
                    ))->first();
                    $report->inclusion_notice_validator = $user->full_name;
                }

                error_log("dams: report " . $report_id . ", portfolio " . $report->portfolio_id . ", modifications_expected: " . $modifications_expected);
                $this->set('m_files_link', $m_files_link);

                $result = '';
                if (($report->validation_status != 'DRAFT') && ($report->status_id != 23)) { // TODO: replace with status id
                    $result = '<h3 class="my-3">This report has no draft yet or is already validated.</h3>';
                } else {
                    $dom = HtmlDomParser::str_get_html($sasResult);
                    $table = $dom->find('table');
                    $h5 = $dom->find('h5');

                    foreach ($table as $key => $t) {
                        $t->class = 'table table-bordered table-striped';
                        $t->frame = '';
                        $tds = $t->find('td');
                        $error = false;
                        if (strpos(strtolower($t->outertext), strtolower('WORK.NOT_REPORTED_INCTR')) !== false) {
                            $error = true;
                        }

                        foreach ($tds as $td) {
                            $td->width = '20%';
                            if (!empty($error))
                                $td->class = 'text-error';
                        }

                        if (strpos(strtolower($t->outertext), strtolower('WORK.NOT_REPORTED_INCTR')) !== false) {
                            $t->class .= ' error';
                        }

                        $result .= $h5[$key]->outertext . $t->outertext;
                    }
                }

                $this->set('result', $result);
                $this->set('report', $report);
            } else {
                $this->Flash->error('The inclusion report does not exist in the database!');
                $this->redirect($this->referer());
            }
        }
    }

    public function inclusionValidationRo($report_id)
    {
        if (empty($report_id)) {
            throw new NotFoundException;
        }
        $report_path = '/var/www/html/data/damsv2/reports/';
        clearstatcache(true);
        if (file_exists($report_path . 'eif_inclusion_validation_' . $report_id)) {
            $sasResult = file_get_contents($report_path . 'eif_inclusion_validation_' . $report_id);
        } else {
            error_log("error inclusion report cache " . $report_id . " : ");
            $sasResult = '';
        }

        if (empty($sasResult)) {
            $this->Flash->error('The inclusion report is missing!');
            $this->redirect($this->referer());
        } else {
            $this->loadModel('Damsv2.Report');
            $report = $this->Report->get($report_id, [
                'contain' => ['Portfolio']
            ]);

            if (!empty($report)) {
                $modifications_expected = $report->portfolio->modifications_expected;
                $m_files_link = $report->portfolio->m_files_link;
                $this->set('modifications_expected', $modifications_expected);

                if (!empty($report->m_files_link)) {
                    $m_files_link = $report->m_files_link;
                }

                error_log("dams: report " . $report_id . ", portfolio " . $report->portfolio_id . ", modifications_expected: " . $modifications_expected);
                $this->set('m_files_link', $m_files_link);

                $result = '';

                $dom = HtmlDomParser::str_get_html($sasResult);
                $table = $dom->find('table');
                $h5 = $dom->find('h5');

                foreach ($table as $key => $t) {
                    $t->class = 'table table-bordered table-striped';
                    $t->frame = '';
                    $tds = $t->find('td');
                    $error = false;
                    if (strpos(strtolower($t->outertext), strtolower('WORK.NOT_REPORTED_INCTR')) !== false) {
                        $error = true;
                    }

                    foreach ($tds as $td) {
                        $td->width = '20%';
                        if (!empty($error))
                            $td->class = 'text-error';
                    }

                    if (strpos(strtolower($t->outertext), strtolower('WORK.NOT_REPORTED_INCTR')) !== false) {
                        $t->class .= ' error';
                    }

                    $result .= $h5[$key]->outertext . $t->outertext;
                }

                $this->set('result', $result);
                $this->set('report', $report);
            } else {
                $this->Flash->error('The inclusion report does not exist in the database!');
                $this->redirect($this->referer());
            }
        }
    }

    public function validationReport($report_id)
    {
        if (empty($report_id)) {
            throw new NotFoundException;
        }
        $report_path = '/var/www/html/data/damsv2/reports/';
        $this->loadModel('Damsv2.Report');

        $report = $this->Report
            ->find()
            ->where(['report_id' => $report_id])
            ->first();

        $portfolios = $this->getTableLocator()->get('Damsv2.Portfolio');
        $portfolio = $portfolios
            ->find()
            ->where(['portfolio_id' => $report->portfolio_id])
            ->first();

        //caching the report in order to "freeze" it as it before it is saved (and completed)//inclusion_validation_report_
        clearstatcache(true);
        if (file_exists($report_path . 'eif_inclusion_validation_report_' . $report_id)) {
            $sasResult = file_get_contents($report_path . 'eif_inclusion_validation_report_' . $report_id);
        } else {
            $sasResult = $this->SAS->get_cached_content('inclusion_validation_report_' . $report_id, "damsv2", "inclusion_validation_report.sas", ['report_id' => $report_id], false);
        }

        $dom = HtmlDomParser::str_get_html($sasResult);
        $table = $dom->find('table');
        $warning = $dom->find('#warning');

        //to parse the sas result anf find if there is a warning
        $msgWarning = 0;
        $warning_agreed_portfolio_volume = false;
        foreach ($warning as $m) {
            $val = trim($m->innertext);
            if ($val == '1')
                $msgWarning = 1;
            if ($val == '-1')
                $msgWarning = -1;
            if ($val == '2')
                $msgWarning = 2;
        }

        $result = '';
        foreach ($table as $key => $t) {
            $t->class = 'table table-bordered table-striped';
            $t->frame = '';
            $tds = $t->find('td');

            $error = false;
            if (strpos(strtolower($t->outertext), strtolower('WORK.NOT_REPORTED_INCTR')) !== false) {
                $error = true;
            }

            foreach ($tds as $td) {
                $td->width = '20%';
                if (!empty($error))
                    $td->class = 'text-error';
            }

            if (strpos(strtolower($t->outertext), strtolower('WORK.NOT_REPORTED_INCTR')) !== false) {
                $t->class .= ' error';
            }

            $result .= $t->outertext;
        }

        $warnings = $this->Report->getWarningsPortfolioVolume($report_id);

        $apvExceeded = $warnings["apvExceeded"];
        $apvDecrease = $warnings["apvDecrease"];
        $warning_agreed_portfolio_volume = $warnings["warning_agreed_portfolio_volume"];
        $mgv = $warnings["mgv"];
        $agreed_ga = $warnings["agreed_ga"];

        clearstatcache(true);
        if (file_exists($report_path . 'eif_inclusion_validation_report_apv_breakdown_' . $report_id)) {
            $sasResult_apv_breakdown = file_get_contents($report_path . 'eif_inclusion_validation_report_apv_breakdown_' . $report_id);
        } else {
            $sasResult_apv_breakdown = $this->SAS->get_cached_content(
                'inclusion_validation_report_apv_breakdown_' . $report_id,
                'damsv2',
                'apv_breakdown.sas',
                [
                    'portfolio_id' => $report->portfolio_id,
                    'report_id'    => $report_id,
                ],
                false
            );
        }
        $dom = HtmlDomParser::str_get_html($sasResult_apv_breakdown);
        $apv_breakdown_link = $dom->find('#apv_breakdown');
        $apv_breakdown_path = null;
        foreach ($apv_breakdown_link as $apv) {
            if (!empty($apv->href)) {
                $apv_breakdown_href = $apv->href;
                $apv_breakdown_path_array = explode('/', $apv_breakdown_href);
                $apv_breakdown_path = $apv_breakdown_path_array[count($apv_breakdown_path_array) - 1];
            }
        }
        clearstatcache(true);
        if (!file_exists($report_path . $apv_breakdown_path)) {
            $apv_breakdown_path = false;
        }
        $this->set('apv_breakdown_path', $apv_breakdown_path);

        //$path = DAMSCACHE . 'eif_inclusion_validation_report_' . $report_id;
        $path = $report_path . 'eif_inclusion_validation_report_' . $report_id . '.pdf';
        $this->set('pdf', $path);

        //check the logic behind this test, old test was comparing to 1, but this value does not exist in the status_portfolio
        $portfolio_apv = $portfolio->status_portfolio != 'OPEN' ? $portfolio->actual_pv : null;

        //Warning message when inclusion end date reached
        $warning_closure = false;
        if (!empty($portfolio->inclusion_end_date)) {
            $no_closure_products = [27]; // DDF
            if (($report->report_type == 'regular') && (!in_array($report->portfolio_id, $no_closure_products))) {
                //dd($report->portfolio_id);
                //if no closure report for the portfolio
                $closure_report_portfolio = $this->Report->find('all', ['conditions' => ['portfolio_id' => $report->portfolio_id, 'report_type' => 'closure']])->first();

                if (empty($closure_report_portfolio)) {
                    if ($portfolio->status_portfolio !== 'CLOSED') { //portfolio not closed
                        if ($report->period_end_date >= $portfolio->inclusion_end_date) {
                            $warning_closure = true;
                        }
                    }
                }
            }
        }
        //add $msgWarning

        $this->set('warning_closure', $warning_closure);
        $this->set('warning_closure_date', $portfolio->inclusion_end_date);
        $this->set('apvDecrease', $apvDecrease);
        $this->set('portfolio_apv', $portfolio_apv);
        $this->set('msgWarning', $msgWarning);
        $this->set('result', $result);
        $this->set('report', $report);
        $this->set('portfolio', $portfolio);
        $title = $report->report_type !== 'regular' ? 'Closure validation report' : 'Validation report';
        $this->set('title', $title);
        $this->set('warning_agreed_portfolio_volume', $warning_agreed_portfolio_volume);
        $this->set('apvExceeded', $apvExceeded);
        $this->set('mgv', $mgv);
        $this->set('agreed_ga', $agreed_ga);
    }

    public function validationReportRo($report_id)
    {
        if (empty($report_id)) {
            throw new NotFoundException;
        }
        $report_path = '/var/www/html/data/damsv2/reports/';
        $this->loadModel('Damsv2.Report');

        $report = $this->Report
            ->find()
            ->where(['report_id' => $report_id])
            ->first();

        $portfolios = $this->getTableLocator()->get('Damsv2.Portfolio');
        $portfolio = $portfolios
            ->find()
            ->where(['portfolio_id' => $report->portfolio_id])
            ->first();

        //caching the report in order to "freeze" it as it before it is saved (and completed)//inclusion_validation_report_
        clearstatcache(true);
        if (file_exists($report_path . 'eif_inclusion_validation_report_' . $report_id)) {
            $sasResult = file_get_contents($report_path . 'eif_inclusion_validation_report_' . $report_id);
        } else {
            $sasResult = $this->SAS->get_cached_content('inclusion_validation_report_' . $report_id, "damsv2", "inclusion_validation_report.sas", ['report_id' => $report_id], false);
        }

        $dom = HtmlDomParser::str_get_html($sasResult);
        $table = $dom->find('table');
        $warning = $dom->find('#warning');

        //to parse the sas result anf find if there is a warning
        $msgWarning = 0;
        $warning_agreed_portfolio_volume = false;
        foreach ($warning as $m) {
            $val = trim($m->innertext);
            if ($val == '1')
                $msgWarning = 1;
            if ($val == '-1')
                $msgWarning = -1;
            if ($val == '2')
                $msgWarning = 2;
        }

        $result = '';
        foreach ($table as $key => $t) {
            $t->class = 'table table-bordered table-striped';
            $t->frame = '';
            $tds = $t->find('td');

            $error = false;
            if (strpos(strtolower($t->outertext), strtolower('WORK.NOT_REPORTED_INCTR')) !== false) {
                $error = true;
            }

            foreach ($tds as $td) {
                $td->width = '20%';
                if (!empty($error))
                    $td->class = 'text-error';
            }

            if (strpos(strtolower($t->outertext), strtolower('WORK.NOT_REPORTED_INCTR')) !== false) {
                $t->class .= ' error';
            }

            $result .= $t->outertext;
        }

        $warnings = $this->Report->getWarningsPortfolioVolume($report_id);

        $apvExceeded = $warnings["apvExceeded"];
        $apvDecrease = $warnings["apvDecrease"];
        $warning_agreed_portfolio_volume = $warnings["warning_agreed_portfolio_volume"];
        $mgv = $warnings["mgv"];
        $agreed_ga = $warnings["agreed_ga"];

        clearstatcache(true);
        if (file_exists($report_path . 'eif_inclusion_validation_report_apv_breakdown_' . $report_id)) {
            $sasResult_apv_breakdown = file_get_contents($report_path . 'eif_inclusion_validation_report_apv_breakdown_' . $report_id);
        } else {
            $sasResult_apv_breakdown = $this->SAS->get_cached_content(
                'inclusion_validation_report_apv_breakdown_' . $report_id,
                'damsv2',
                'apv_breakdown.sas',
                [
                    'portfolio_id' => $report->portfolio_id,
                    'report_id'    => $report_id,
                ],
                false
            );
        }
        $dom = HtmlDomParser::str_get_html($sasResult_apv_breakdown);
        $apv_breakdown_link = $dom->find('#apv_breakdown');
        $apv_breakdown_path = null;
        foreach ($apv_breakdown_link as $apv) {
            if (!empty($apv->href)) {
                $apv_breakdown_href = $apv->href;
                $apv_breakdown_path_array = explode('/', $apv_breakdown_href);
                $apv_breakdown_path = $apv_breakdown_path_array[count($apv_breakdown_path_array) - 1];
            }
        }
        clearstatcache(true);
        if (!file_exists($report_path . $apv_breakdown_path)) {
            $apv_breakdown_path = false;
        }
        $this->set('apv_breakdown_path', $apv_breakdown_path);

        //$path = DAMSCACHE . 'eif_inclusion_validation_report_' . $report_id;
        $path = $report_path . 'eif_inclusion_validation_report_' . $report_id;
        $this->set('pdf', $path . '.pdf');

        //check the logic behind this test, old test was comparing to 1, but this value does not exist in the status_portfolio
        $portfolio_apv = $portfolio->status_portfolio != 'OPEN' ? $portfolio->actual_pv : null;

        //Warning message when inclusion end date reached
        $warning_closure = false;
        if (!empty($portfolio->inclusion_end_date)) {
            $no_closure_products = [27]; // DDF
            if (($report->report_type == 'regular') && (!in_array($report->portfolio_id, $no_closure_products))) {
                //dd($report->portfolio_id);
                //if no closure report for the portfolio
                $closure_report_portfolio = $this->Report->find('all', ['conditions' => ['portfolio_id' => $report->portfolio_id, 'report_type' => 'closure']])->first();

                if (empty($closure_report_portfolio)) {
                    if ($portfolio->status_portfolio !== 'CLOSED') { //portfolio not closed
                        if ($report->period_end_date >= $portfolio->inclusion_end_date) {
                            $warning_closure = true;
                        }
                    }
                }
            }
        }
        //add $msgWarning

        $this->set('warning_closure', $warning_closure);
        $this->set('warning_closure_date', $portfolio->inclusion_end_date);
        $this->set('apvDecrease', $apvDecrease);
        $this->set('portfolio_apv', $portfolio_apv);
        $this->set('msgWarning', $msgWarning);
        $this->set('result', $result);
        $this->set('report', $report);
        $this->set('portfolio', $portfolio);
        $title = $report->report_type !== 'regular' ? 'Closure validation report' : 'Validation report';
        $this->set('title', $title);
        $this->set('warning_agreed_portfolio_volume', $warning_agreed_portfolio_volume);
        $this->set('apvExceeded', $apvExceeded);
        $this->set('mgv', $mgv);
        $this->set('agreed_ga', $agreed_ga);
    }

    public function waiverReason($report_id)
    {
        $this->loadModel('Damsv2.Report');
        $this->loadModel('Damsv2.Transactions');

        $report = $this->Report->find('all', ['contain' => 'Portfolio', 'conditions' => ['report_id' => $report_id]])->first();
        //call sas to have the waived SME's and waived trn
        if (!$this->request->is('post')) {
            $sasResult = $this->SAS->curl(
                'waiver_reasons.sas',
                ['report_id' => $report_id],
                false,
                false
            );
        }
        //if need inclusion_notice_received
        if (($report->inclusion_notice_received == 'FALSE') && ($report->inclusion_notice_reason === null)) {
            // skip if no new trn included
            $trn = $this->Transactions->find()->where(['report_id' => $report_id])->first();
            if (!empty($trn)) {
                // repush to comment page for inclusion notice not received
                $this->redirect(['controller' => 'Validation', 'action' => 'inclusion_notice_reason/' . $report_id]);
            }
        }

        $reasons = $this->Spreadsheet->waiverRead($report_id);

        $this->set('reasons', $reasons);
        $this->set('report', $report);
        $warnings = $this->Report->getWarningsPortfolioVolume($report_id);
        $apvExceeded = $warnings["apvExceeded"];
        $warning_agreed_portfolio_volume = $warnings["warning_agreed_portfolio_volume"];
        $this->set('apvExceeded', $apvExceeded);
        $this->set('warning_agreed_portfolio_volume', $warning_agreed_portfolio_volume);

        if ($this->request->is('post')) {
            $has_empty_value = false;
            Cache::write("waiver_reason_comments_" . $report_id, json_encode($this->request->getData()), 'damsv2');
            if (!empty($this->request->getData['SME'])) {
                //check each line has a comment
                foreach ($this->request->getData('SME') as $key => $val) {
                    if (trim($val) === '') {
                        $has_empty_value = true;
                    }
                }
            }
            if (!empty($this->request->getData('TRN'))) {
                //check each line has a comment
                foreach ($this->request->getData('TRN') as $key => $val) {
                    if (trim($val) === '') {
                        $has_empty_value = true;
                    }
                }
            }
            if (!empty($this->request->getData('SUB'))) {
                //check each line has a comment
                foreach ($this->request->getData('SUB') as $key => $val) {
                    if (trim($val) === '') {
                        $has_empty_value = true;
                    }
                }
            }
            if ($has_empty_value) {
                $this->Flash->error('Please enter an exemption reason for each line.');
            } else {
                if (empty($this->request->getData('SME')) && empty($this->request->getData('TRN')) && empty($this->request->getData('SUB'))) {
                    //nothing to save
                } else {
                    //save in excel files the reasons
                    try {
                        $saved = $this->Spreadsheet->waiverReasonWrite($this->request);
                    } catch (Exception $e) {
                        error_log("error writing weaver file report " . $report_id . " : " . $e->getMessage());
                        $this->Flash->error('The weaver file for this report is missing.');
                        $this->redirect(['controller' => 'report', 'action' => 'inclusion']);
                    }
                }
                $this->redirect(['controller' => 'report', 'action' => 'fi_responsivness', $report_id]);
            }
        }
    }

    public function waiverReasonView($report_id)
    {

        if ($this->request->is('post')) {
            $this->redirect(['action' => 'draft-validation', $report_id]);
        }
        $this->loadModel('Damsv2.Report');
        $report = $this->Report->find('all', ['contain' => 'Portfolio', 'conditions' => ['report_id' => $report_id]])->first();

        $reasons = $this->Spreadsheet->waiverRead($report_id);
        error_log("waiver view : " . json_encode($reasons));
        $this->set('reasons', $reasons);
        $this->set('report', $report);
        $warnings = $this->Report->getWarningsPortfolioVolume($report_id);

        $apvExceeded = $warnings["apvExceeded"];
        $warning_agreed_portfolio_volume = $warnings["warning_agreed_portfolio_volume"];
        $this->set('apvExceeded', $apvExceeded);
        $this->set('warning_agreed_portfolio_volume', $warning_agreed_portfolio_volume);
    }

    public function waiverReasonRo($report_id)
    {

        $this->loadModel('Damsv2.Report');
        $report = $this->Report->find('all', ['contain' => 'Portfolio', 'conditions' => ['report_id' => $report_id]])->first();

        try {
            $reasons = $this->Spreadsheet->waiverRead($report_id);
        } catch (Exception $e) {
            $reasons = ['SME' => [], 'TRN' => [], 'SUB' => []];
        }
        $this->set('reasons', $reasons);
        $this->set('report', $report);
        $warnings = $this->Report->getWarningsPortfolioVolume($report_id);
        $apvExceeded = $warnings["apvExceeded"];
        $warning_agreed_portfolio_volume = $warnings["warning_agreed_portfolio_volume"];
        $this->set('apvExceeded', $apvExceeded);
        $this->set('warning_agreed_portfolio_volume', $warning_agreed_portfolio_volume);
    }

    public function draftValidationRo($report_id)
    {
        $this->loadModel('Damsv2.Report');
        if (!$this->Report->exists($report_id)) {
            throw new NotFoundException(__('Invalid Report'));
        }
        $report = $this->Report->find('all', ['contain' => 'Portfolio', 'conditions' => ['report_id' => $report_id]])->first();


        $this->set('current_user_id', $this->userIdentity()->get('id'));
        $this->set('draft_user_id', $report->validator1);

        $this->set('validator2', $report->validator2);
        $this->loadModel('Damsv2.VUser');
		if ($report->validator2 != null)
		{
			$user_validator2 = $this->VUser->find('all', array(
				//'fields' => array('first_name','last_name','id'),
				'conditions' => array('id' => $report->validator2)
			))->first();
		}
		else
		{
			$user_validator2 = $this->VUser->find('all', array(
				//'fields' => array('first_name','last_name','id'),
				'conditions' => array('id' => 2)
			))->first();
		}
        $this->set('user_validator2', $user_validator2);

        //        $getgroupalias = $this->UserAuth->getGroupNameAlias();
        //        $this->set('user_profiles', $getgroupalias);
        $this->set('report', $report);
    }

    public function exceededMpv($report_id)
    {
        $this->loadModel('Damsv2.Report');
        if (!$this->Report->exists($report_id)) {
            throw new NotFoundException(__('Invalid Report'));
        }
        $report = $this->Report->find('all', ['contain' => 'Portfolio', 'conditions' => ['report_id' => $report_id]])->first();
        $this->set(compact('report', 'report'));
    }

    public function comment($report_id)
    {
        $this->loadModel('Damsv2.Report');
        if (!$this->Report->exists($report_id)) {
            throw new NotFoundException(__('Invalid Report'));
        }
        $report = $this->Report->find('all', ['contain' => 'Portfolio', 'conditions' => ['report_id' => $report_id]])->first();
        $this->set(compact('report', 'report'));
    }

    public function draftValidation($report_id)
    {
        $this->loadModel('Damsv2.Report');
        $report = $this->Report->find('all', ['conditions' => ['report_id' => $report_id]])->first();

        if ($this->request->is('post')) {
            //validate
            $report->validation_status = 'VALIDATED';
            $report->status_id = 5; //final included
            if ($report->report_type == 'closure') {
                // also update status_id for regular report if $report is closure
                $regular = $this->Report->find('all', ['conditions' => [
                    'portfolio_id'   => $report->portfolio_id,
                    'period_quarter' => $report->period_quarter,
                    'period_year'    => $report->period_year,
                    'template_id'    => $report->template_id,
                    //'visible' => 1,
                    'bulk'           => 0,
                    'report_type'    => 'regular',
                    'report_id > '   => $report_id,
                ], 'order'      => 'report_id ASC'])->first();
                if (!empty($regular)) {
                    $regular->status_id = 5;
                    $regular->validation_status = 'VALIDATED';
                    $regular->comments_validator2 = $this->request->getData('Report.comment_validator2');
                    $regular->validator2 = $this->userIdentity()->get('id');
                    $saved_regular = $this->Report->save($regular);
                    if (!$saved_regular) {
                        error_log("could not update the status of report: " . json_encode($regular));
                    }
                }
            }
            $this->loadModel('Damsv2.Portfolio');
            $portfolio = $this->Portfolio->find('all', ['conditions' => ['portfolio_id' => $report->portfolio_id]])->first();
            if ((!empty($portfolio)) && ($portfolio->modifications_expected == 'Y')) {
                //$report->modifications_expected = 'Y';
                $report->m_files_link = $portfolio->m_files_link;
                $portfolio->m_files_link = null;
                $portfolio->modifications_expected = 'N';
                $this->Portfolio->save($portfolio);
            }
            $report->comments_validator2 = $this->request->getData('Report.comment_validator2');
            $report->validator2 = $this->userIdentity()->get('id');
            $saved = $this->Report->save($report);
            $log_info = [
                'report_id'    => $report_id,
                'comment'      => $saved->comments_validator2,
                'm_files_link' => $report->m_files_link,
            ];
            $this->logDams('Second validation :' . json_encode($log_info), 'dams', 'Second validation');
            $this->Flash->success("The report has been validated.");
            $this->redirect(['controller' => 'report', 'action' => 'inclusion']);
        }

        $this->set('current_user_id', $this->userIdentity()->get('id'));
        $this->set('draft_user_id', $report->validator1);

        //        $getgroupalias = $this->UserAuth->getGroupNameAlias();
        //        $this->set('user_profiles', $getgroupalias);
        $this->set('report', $report);
    }

    public function inclusionNoticeFollowup()
    {
        if ($this->request->is('post')) {
            // inclusion_notice_validator int
            $user_id = $this->userIdentity()->get('id');
            //$user_name = $this->User->read(array('User.first_name', 'User.last_name'), $user_id);
            foreach ($this->request->getData('Report') as $report => $val) {
                if ($val === 'TRUE') {
                    $report_id = str_replace('inclusion_notice_received_', '', $report);
                    $rep = $this->Report->find('all', array('conditions' => array('Report.report_id' => $report_id)))->first();

                    $rep['Report']['inclusion_notice_received'] = 'TRUE';
                    $rep['Report']['inclusion_notice_validator'] = $user_id;
                    $saved = $this->Report->save($rep);
                }
            }
        }
        $session = $this->request->getSession();
        if (!$session->read('Form.data.inclusionnf')) {
            $session->write('Form.data.inclusionnf', array(
                'Product'   => array(
                    'product_id' => ''
                ),
                'Portfolio' => array(
                    'portfolio_id' => '',
                    'owner'        => '',
                ),
                'Report'    => array(
                    'period_quarter'            => '',
                    'report_id'                 => '',
                    'period_year'               => '',
                    'inclusion_notice_received' => '',
                ),
            ));
        }
        if ($this->request->is('post')) {
            $session->write('Form.data.inclusionnf', $this->request->getData());
        }

        $conditions = array(
            'Template.template_type_id'            => '1', // Only inclusion
            'Report.visible'                       => 1,
            'Report.status_id IN'                  => array(5, 23),
            'Report.inclusion_notice_received IN ' => array('TRUE', 'FALSE'),
        );
        if ($session->read('Form.data.inclusionnf.owner')) {
            $conditions['Portfolio.owner'] = $session->read('Form.data.inclusionnf.owner');
        }
        if ($session->read('Form.data.inclusionnf.product_id')) {
            $conditions['Portfolio.product_id'] = $session->read('Form.data.inclusionnf.product_id');
        }
        if ($session->read('Form.data.inclusionnf.portfolio_id')) {
            $conditions['Portfolio.portfolio_id'] = $session->read('Form.data.inclusionnf.portfolio_id');
        }
        if ($session->read('Form.data.inclusionnf.report_id')) {
            $conditions['Report.report_id'] = $session->read('Form.data.inclusionnf.report_id');
        }
        if ($session->read('Form.data.inclusionnf.period_quarter')) {
            $conditions['Report.period_quarter'] = $session->read('Form.data.inclusionnf.period_quarter');
        }
        if ($session->read('Form.data.inclusionnf.period_year')) {
            $conditions['Report.period_year'] = $session->read('Form.data.inclusionnf.period_year');
        }
        if ($session->read('Form.data.inclusionnf.inclusion_notice_received')) {
            if (($session->read('Form.data.inclusionnf.inclusion_notice_received') == 'TRUE') || ($session->read('Form.data.inclusionnf.inclusion_notice_received') == 'FALSE')) {
                $conditions['Report.inclusion_notice_received'] = $session->read('Form.data.inclusionnf.inclusion_notice_received');
            }
        }
        //$this->Paginator->settings = $this->paginate;
        //$this->Paginator->settings['Report']['order'] = 'Report.report_id ASC';
        $this->loadModel('Damsv2.Report');
        $this->loadModel('Damsv2.Portfolio');
        $this->loadModel('Damsv2.VUser');
        $this->loadModel('Damsv2.Product');
        $query = $this->Report->find('all', [
            'contain'    => ['VUser', 'Portfolio', 'Template'],
            'conditions' => [$conditions]
        ]);

        $this->paginate = [
            'limit'          => 20,
            'order'          => ['report_id' => 'desc'],
            'sortableFields' => [
                'report_id',
                'report_name',
                'Portfolio.owner',
                'inclusion_notice_received',
            ],
        ];

        $reports = $this->paginate($query);

        $this->set('reports', $reports);
        $owner_ids = array();
        $owners = $this->Portfolio->find()->select(['owner'])->distinct()->toArray();
        foreach ($owners as $owner) {
            $owner_ids[$owner->owner] = $owner->owner;
        }
        //debug($owners);
        $user_list = $this->VUser->find()->select(['first_name', 'last_name', 'id'])->where(['id IN ' => $owner_ids])->order(['last_name', 'first_name'])->toArray();
        //$user_list[] = array('User'=> array('id' => 2, 'last_name' => 'N/A', 'first_name' => ''));

        $users = array(); // = Set::combine($user_list, '{n}.User.id', array('{0} {1}', '{n}.User.last_name', '{n}.User.first_name'));
        foreach ($user_list as $ul) {
            $users[$ul->id] = $ul->last_name . ' ' . $ul->first_name;
        }
        $products = $this->Product->getProducts();
        $this->set('products', $products);
        $cond_portfolio = array(
            'NOT' => array('Product.product_id IN ' => array(22, 23)),
        );
        if ($session->read('Form.data.inclusionnf.product_id')) {
            $cond_mandate['Product.product_id'] = $session->read('Form.data.inclusionnf.product_id');
            $cond_portfolio['Product.product_id'] = $session->read('Form.data.inclusionnf.product_id');
        }
        if (!empty($this->request->data['Product']['product_id'])) {
            $cond_mandate['Product.product_id'] = $this->request->data['Product']['product_id'];
            $cond_portfolio['Product.product_id'] = $this->request->data['Product']['product_id'];
        }
        $portfolios = $this->Portfolio->find('list', ['contain' => ['Product']])->select(['Portfolio.portfolio_id', 'Portfolio.portfolio_name', 'Product.name'])
            ->where($cond_portfolio)->order(['Portfolio.portfolio_name'])->toArray();
        $this->set('portfolios', $portfolios);
        $this->set('users', $users);
        $this->set('session', $session);
    }

    public function inclusionNoticeFollowupSave()
    {
        $this->autoRender = false;
        $this->layout = 'ajax';
        if ($this->request->is('post')) {
            $this->loadModel('Damsv2.Report');
            $user_id = $this->userIdentity()->get('id');
            $Post = $this->request->getData();
            $report_id = $Post['Report']['report_id'];
            $rep = $this->Report->find()->where(['report_id' => $report_id])->first();

            $val = $Post['Report']['inclusion_notice_received'];
            if (($val != 'TRUE') && ($val != 'FALSE')) {
                die("error");
            }
            $rep->inclusion_notice_received = $val;
            $rep->inclusion_notice_validator = $user_id;
            $saved = $this->Report->save($rep);
        }
    }

    public function inclusionNoticeFollowupCsv()
    {
        $Post = $this->request->getData();
        $portfolio_id = $Post['Portfolio']['portfolio_id'];
        $period_quarter = $Post['Report']['period_quarter'];
        $period_year = $Post['Report']['period_year'];
        $owner = $Post['Portfolio']['owner'];
        $inclusion_notice_received = $Post['Report']['inclusion_notice_received'];
        $conditions = [
            'Report.inclusion_notice_received IS NOT NULL',
            'Report.status_id IN '      => [5, 23],
            'Report.visible'            => 1,
            'Template.template_type_id' => '1',
        ];
        if (!empty($portfolio_id)) {
            $conditions[] = "Report.portfolio_id  = '" . $portfolio_id . "'";
        }
        if (!empty($period_quarter)) {
            $conditions[] = "Report.period_quarter = '" . $period_quarter . "'";
        }
        if (!empty($period_year)) {
            $conditions[] = "Report.period_year = '" . $period_year . "'";
        }
        if (!empty($owner)) {
            $conditions[] = "Portfolio.owner = '" . $owner . "'";
        }
        if (!empty($inclusion_notice_received)) {
            $conditions[] = "Report.inclusion_notice_received = '" . $inclusion_notice_received . "'";
        }
        $this->loadModel('Damsv2.Report');

        $results = $this->Report->find()->contain(['Portfolio', 'Template', 'Portfolio.VUser']);
        $results->select(['Report_ID' => 'Report.report_id', 'Report_Name' => 'Report.report_name', 'Inclusion_Notice_Received' => 'Report.inclusion_notice_received', 'First_Name' => 'VUser.first_name', 'Last_Name' => 'VUser.last_name']);
        $results->where($conditions);

        $filename = 'notice_follow_up_' . time() . '.xlsx';
        $filepath = '/var/www/html/data/damsv2/export/' . $filename;

        $skeleton = ['Report'];
        $this->Spreadsheet->generateExcelFromQueryDefaultHeaderMapping($results->toArray(), $skeleton, $filepath);
        $this->set('downloadfilepath', $filename . '/export');
    }

    public function inclusionNoticeReason($report_id = null)
    {
        $Post = $this->request->getData();
        if (!empty($Post['Report']['report_id'])) {
            $report_id = $Post['Report']['report_id'];
        }
        $this->loadModel('Damsv2.Report');
        $report = $this->Report->find()->where(['Report.report_id' => $report_id])->first();

        if ($this->request->is('post')) {
            if (!empty($Post['Report']['report_id']) && isset($Post['Report']['inclusion_notice_reason'])) {
                $report->inclusion_notice_reason = $Post['Report']['inclusion_notice_reason'];
                $saved = $this->Report->save($report);
                $this->redirect(array('action' => 'waiver_reason/' . $report_id));
            }
        }

        $this->set('report', $report);
        $this->set('report_id', $report_id);
    }

    public function inclusionNoticeReasonRo($report_id = null)
	{
		if (!empty($this->request->getData('Report.report_id'))) {
            $report_id = $this->request->getData('Report.report_id');
        }

        if (empty($report_id)) {
            error_log("inclusion_notice_reason no report id");
        }

        $this->loadModel('Damsv2.Report');
        $report = $this->Report->find()->where(['Report.report_id' => $report_id])->first();
      
        $this->loadModel('Damsv2.VUser');
        if (!empty($report->inclusion_notice_validator)) {
            $user = $this->VUser->find('all', array(
                'conditions' => array('id' => $report->inclusion_notice_validator),
            ))->first();
            $report->inclusion_notice_validator = $user->full_name;
        } else {
            $user = $this->VUser->find('all', array(
                'conditions' => array('id' => $report->validator1),
            ))->first();
            $report->inclusion_notice_validator = $user->full_name;
        }
       
        if ($this->request->is('post')) {
            $this->redirect(array('action' => 'waiver-reason-ro/' . $report_id));
        }

        $this->set('report', $report);
        $this->set('report_id', $report_id);
	}

    public function inclusionNoticeReasonView($report_id = null)
    {       
        if (!empty($this->request->getData('Report.report_id'))) {
            $report_id = $this->request->getData('Report.report_id');
        }

        if (empty($report_id)) {
            error_log("inclusion_notice_reason no report id");
        }

        $this->loadModel('Damsv2.Report');
        $report = $this->Report->find()->where(['Report.report_id' => $report_id])->first();
      
        $this->loadModel('Damsv2.VUser');
        if (!empty($report->inclusion_notice_validator)) {
            $user = $this->VUser->find('all', array(
                'conditions' => array('id' => $report->inclusion_notice_validator),
            ))->first();
            $report->inclusion_notice_validator = $user->full_name;
        } else {
            $user = $this->VUser->find('all', array(
                'conditions' => array('id' => $report->validator1),
            ))->first();
            $report->inclusion_notice_validator = $user->full_name;
        }
       
        if ($this->request->is('post')) {
            $this->redirect(array('action' => 'waiver_reason_view/' . $report_id));
        }

        $this->set('report', $report);
        $this->set('report_id', $report_id);
    }
}
