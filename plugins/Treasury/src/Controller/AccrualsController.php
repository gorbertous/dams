<?php

declare(strict_types=1);

namespace Treasury\Controller;


use Cake\Event\EventInterface;

/**
 * Taxes Controller
 *
 * @property \Treasury\Model\Table\TaxesTable $Taxes
 * @method \Treasury\Model\Entity\Tax[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AccrualsController extends AppController
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
    
    function calculation()
    {

        /*
		 * Get mandates list and append it to array('-1' => '***all***) without reindexing keys with the true parameter
		 */
        $this->set('mandates_list', $this->Mandate->getMandateList(true));

        /*
		 * After form submit, launch accruals calculation in SAS
		 */
        if ($this->request->is('post')) {

            // group retrieving  // http://vmu-sas-01:8080/browse/PLATFORM-259
            $groups = CakeSession::read('UserAuth.UserGroups');
            if (!is_array($groups)) $groups = array($groups);
            if (!empty($groups)) foreach ($groups as $group) {
                $groupsnames[] = $group['alias_name'];
            }
            if (in_array('readOnlyTreasury', $groupsnames)) {
                $this->Session->setFlash("You are currently in a read only profile, this functionality is disabled", "flash/error");
                $this->redirect($this->referer());
            }
            if (
                !($this->request->data['accrualsform']['mandate_ID']) ||
                !($this->request->data['accrualsform']['cpty_ID']) ||
                !($this->request->data['accrualsform']['StartDate']) ||
                !($this->request->data['accrualsform']['EndDate'])
            ) {
                $this->Session->setFlash('All fields are required', 'flash/error');
                $this->redirect($this->referer());
            }

            $startdate = (!empty($this->request->data['accrualsform']['StartDate'])) ? date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['accrualsform']['StartDate']))) : $startdate = $this->request->data['accrualsform']['StartDate'];
            $enddate = (!empty($this->request->data['accrualsform']['EndDate'])) ? date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['accrualsform']['EndDate']))) : $this->request->data['accrualsform']['EndDate'];

            $params = array(
                "Mandate_id"        => $this->request->data['accrualsform']['mandate_ID'],
                "Counterparty_id"    => $this->request->data['accrualsform']['cpty_ID'],
                "StartDate"            => $startdate,
                "EndDate"            => $enddate,
            );

            $sasResult1 = $this->SAS->curl("F_AccrualsCalc.sas", $params, false);

            $this->set('sas1', utf8_encode($sasResult1));
            $this->set('tables', $this->SAS->get_all_tables_from_webout(utf8_encode($sasResult1)));
        }

        if (isset($sasResult1)) {
            $this->set('msg', '');
            $this->set('tab2state', 'active');
            $this->set('tab1state', '');
        } else {
            $this->set('msg', 'Please first fill in the form.');
            $this->set('tab2state', '');
            $this->set('tab1state', 'active');
        }

        $this->set('title_for_layout', 'Accruals Calculation');
    }

    public function eom()
    {
        if (Cache::read("automatic_fixing", 'treasury')) {
            $this->Session->setFlash("Process of the automatic interest fixing is currently running. Your modification on the transactions could create a conflict in the database. Please try again in 5 minutes.", 'flash/warning');
        }
        $log = $this->LogEntry->find('first', array(
            "conditions" => array(
                "message LIKE 'EOM-ACCRUALS%'"
            ),
            "order" => array(
                'datetime DESC'
            ),
        ));
        $this->set(compact("log"));
        if ($this->request->is('post')) {

            // group retrieving  // http://vmu-sas-01:8080/browse/PLATFORM-259
            $groups = CakeSession::read('UserAuth.UserGroups');
            if (!is_array($groups)) $groups = array($groups);
            if (!empty($groups)) foreach ($groups as $group) {
                $groupsnames[] = $group['alias_name'];
            }
            if (in_array('readOnlyTreasury', $groupsnames)) {
                $this->Session->setFlash("You are currently in a read only profile, this functionality is disabled", "flash/error");
                $this->redirect($this->referer());
            }

            if (
                (strlen($this->request->data['Accruals']['month']['month']) <= 0) ||
                (strlen($this->request->data['Accruals']['year']['year']) <= 0)
            ) {
                $this->Session->setFlash("All the fields are required", "flash/error");
                $this->redirect($this->referer());
            }

            //remove BOM
            $this->request->data['Accruals']['transaction_id'] = mb_convert_encoding($this->request->data['Accruals']['transaction_id'], "UTF-8");
            $params = array(
                "year"                => $this->request->data['Accruals']['year']['year'],
                "month"                => $this->request->data['Accruals']['month']['month'],
                "transaction_id"    => (string) $this->request->data['Accruals']['transaction_id'],
                "save"                => $this->request->data['Accruals']['save']
            );

            $sasResult = $this->SAS->get_cached_content('eom_booking', 'treasury', "eom_booking.sas", $params, true);
            if ($this->request->data['Accruals']['save'] == 0) {
                if (strstr(strtolower($sasResult), 'error')) {
                    $flash = "flash/error";
                } elseif (strstr(strtolower($sasResult), 'warning')) {
                    $sasResult = str_replace('WARNING:', '', $sasResult);
                    $this->set('save', true);
                    $flash = "flash/default";
                } else {
                    $this->set('save', true);
                    $flash = "flash/success";
                }
                $this->Session->setFlash($sasResult, $flash);
            } else {
                $this->log_entry("EOM-ACCRUALS " . $this->request->data['Accruals']['month']['month'] . "/" . $this->request->data['Accruals']['year']['year'] . " - " . (string)$this->request->data['Accruals']['transaction_id'], "treasury");
                $this->Session->setFlash($sasResult, "flash/success");
                $this->redirect("/treasury/treasuryaccruals/eom_result/" . (string)$this->request->data['Accruals']['transaction_id'] . "/" . $this->request->data['Accruals']['year']['year'] . "/" . $this->request->data['Accruals']['month']['month']);
            }
        }
    }

    public function eom_result($transaction_id = null, $year = null, $month = null)
    {
        @$this->validate_param('string', $transaction_id);
        @$this->validate_param('string', $year);
        @$this->validate_param('string', $month);
        $this->Session->setFlash(Cache::read('eom_booking', 'treasury'), "flash/info");
        $rows = array();
        if ($transaction_id) {
            $this->paginate = array(
                'conditions' => array('transaction_id' => $transaction_id),
                'limit' => 20,
            );
            $rows = $this->paginate('HistoPs');
        }

        $this->set(compact('transaction_id', 'rows', 'year', 'month'));
    }

    function eom_pdf($transaction_id = null, $year = null, $month = null)
    {
        @$this->validate_param('string', $transaction_id);
        @$this->validate_param('string', $year);
        @$this->validate_param('string', $month);
        if (isset($transaction_id) && isset($year) && isset($month)) {
            /*$rows = array();
			$this->paginate = array(
		    	'conditions' => array('transaction_id' => $transaction_id),
		    	'limit' => 100000,
		    );
			$rows = $this->paginate('HistoPs');
			*/
            $conditions = array('conditions' => array('transaction_id' => $transaction_id));
            $rows = $this->HistoPs->find("all", $conditions);
            $this->set(compact('transaction_id', 'rows', 'year', 'month'));

            $view = new View($this);

            /* PDF generation */
            $raw = $view->render('Accruals/eom_pdf');
            $raw = strstr($raw, '<!-- di -->'); // remove cake styling
            $raw = strstr($raw, '<!-- end di -->', true);

            // write to the database
            $pdf_file = array('Pdf' => array(
                'name' => $transaction_id, // change this to something in a form
                'raw' => base64_encode($raw) // encode the data to save space
            ));

            $this->autoRender = false;
            // get an instance of wkhtmltopdf
            $pdf = new WkHtmlToPdf();

            // decode the database and add the html to the pdf
            $html = base64_decode($pdf_file['Pdf']['raw']);

            $pdf->addPage($html);
            $pdf->setOptions(array('footer-right' => '"Page [page]/[topage]"'));
            if (!$pdf->send($transaction_id . ".pdf")) {
                $this->Session->setFlash('Could not create PDF: ' . $pdf->getError() . ' Please contact the administrator', 'flash/error');
                $this->redirect($this->referer());
            }
        }
    }

    function inoutbooking()
    {
        if (Cache::read("automatic_fixing", 'treasury')) {
            $this->Session->setFlash("Process of the automatic interest fixing is currently running. Your modification on the transactions could create a conflict in the database. Please try again in 5 minutes.", 'flash/warning');
        }
        $log = $this->LogEntry->find('first', array(
            "conditions" => array(
                "message LIKE 'IN-OUT-BOOKING%'"
            ),
            "order" => array(
                'datetime DESC'
            ),
        ));
        $this->set(compact("log"));
        if ($this->request->is('post')) {

            // group retrieving
            $groups = CakeSession::read('UserAuth.UserGroups');
            if (!is_array($groups)) $groups = array($groups);
            if (!empty($groups)) foreach ($groups as $group) {
                $groupsnames[] = $group['alias_name'];
            }
            if (in_array('readOnlyTreasury', $groupsnames)) {
                $this->Session->setFlash("You are currently in a read only profile, this functionality is disabled", "flash/error");
                $this->redirect($this->referer());
            }

            if (
                (strlen($this->request->data['Accruals']['start_date']) <= 0) ||
                (strlen($this->request->data['Accruals']['end_date']) <= 0)
            ) {
                $this->Session->setFlash("All the fields are required", "flash/error");
                $this->redirect($this->referer());
            }
            //remove BOM 
            $this->request->data['Accruals']['transaction_id'] = mb_convert_encoding($this->request->data['Accruals']['transaction_id'], "UTF-8");
            $startdate = $this->request->data['Accruals']['start_date'];
            if (!empty($this->request->data['Accruals']['start_date'])) {
                $startdate = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['Accruals']['start_date'])));
            }
            $enddate = $this->request->data['Accruals']['end_date'];
            if (!empty($this->request->data['Accruals']['end_date'])) {
                $enddate = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['Accruals']['end_date'])));
            }

            $params = array(
                "save"            => $this->request->data['Accruals']['save'],
                "start_date"    => $startdate,
                "end_date"        => $enddate,
            );

            $sasResult = $this->SAS->get_cached_content('inout_booking', 'treasury', "inout_booking.sas", $params, true);
            if ($this->request->data['Accruals']['save'] == 0) {
                if (strstr(strtolower($sasResult), 'error')) {
                    $flash = "flash/error";
                } elseif (strstr(strtolower($sasResult), 'warning')) {
                    $this->set('save', true);
                    $flash = "flash/default";
                } else {
                    $this->set('save', true);
                    $flash = "flash/success";
                }
                $this->Session->setFlash($sasResult, $flash);
            } else {

                $this->log_entry("IN-OUT-BOOKING - " . (string)$this->request->data['Accruals']['transaction_id'] . " from " . $startdate . " to " . $enddate, "treasury");
                $this->Session->setFlash($sasResult, "flash/success");
                $this->redirect("/treasury/treasuryaccruals/inout_result/" . (string)$this->request->data['Accruals']['transaction_id'] . "/" . $startdate . "/" . $enddate);
            }
        }
    }

    function inout_result($transaction_id = null, $start_date = null, $end_date = null)
    {
        @$this->validate_param('string', $transaction_id);
        @$this->validate_param('date', $start_date);
        @$this->validate_param('date', $end_date);
        $this->Session->setFlash(Cache::read('inout_booking', 'treasury'), "flash/info");
        $rows = array();
        if ($transaction_id) {
            $this->paginate = array(
                'conditions' => array('transaction_id' => $transaction_id),
                'limit' => 20, //migration : limit = -1 will limit to 1
            );
            $rows = $this->paginate('HistoPs');
        }

        $this->set(compact('transaction_id', 'rows', 'start_date', 'end_date'));
    }

    function inout_pdf($transaction_id = null, $start_date = null, $end_date = null)
    {
        @$this->validate_param('string', $transaction_id);
        @$this->validate_param('date', $start_date);
        @$this->validate_param('date', $end_date);
        if (isset($transaction_id) && isset($start_date) && isset($end_date)) {
            $rows = array();
            /*$this->paginate = array(
			    	'conditions' => array('transaction_id' => $transaction_id),
			    	'limit' => 450,
			);*/
            //$rows = $this->paginate('HistoPs');
            $conditions = array('conditions' => array('transaction_id' => $transaction_id));
            $rows = $this->HistoPs->find("all", $conditions);
            $this->set(compact('transaction_id', 'rows', 'start_date', 'end_date'));

            $view = new View($this);

            /* PDF generation */
            $raw = $view->render('Accruals/inout_pdf');
            $raw = strstr($raw, '<!-- di -->'); // remove cake styling
            $raw = strstr($raw, '<!-- end di -->', true);

            // write to the database
            $pdf_file = array('Pdf' => array(
                'name' => $transaction_id, // change this to something in a form
                'raw' => base64_encode($raw) // encode the data to save space
            ));

            $this->autoRender = false;
            // get an instance of wkhtmltopdf
            $pdf = new WkHtmlToPdf();

            // decode the database and add the html to the pdf
            $html = base64_decode($pdf_file['Pdf']['raw']);

            $pdf->addPage($html);
            $pdf->setOptions(array('footer-right' => '"Page [page]/[topage]"'));
            if (!$pdf->send($transaction_id . ".pdf")) {
                $this->Session->setFlash('Could not create PDF: ' . $pdf->getError() . ' Please contact the administrator', 'flash/error');
                $this->redirect($this->referer());
            }
        }
    }

    function reveomaccruals()
    {
        $log = $this->LogEntry->find('first', array(
            "conditions" => array(
                "message LIKE 'REV-EOM-ACCRUALS%'"
            ),
            "order" => array(
                'datetime DESC'
            ),
        ));
        $this->set(compact("log"));
        if ($this->request->is('post')) {

            // group retrieving
            $groups = CakeSession::read('UserAuth.UserGroups');
            if (!is_array($groups)) $groups = array($groups);
            if (!empty($groups)) foreach ($groups as $group) {
                $groupsnames[] = $group['alias_name'];
            }
            if (in_array('readOnlyTreasury', $groupsnames)) {
                $this->Session->setFlash("You are currently in a read only profile, this functionality is disabled", "flash/error");
                $this->redirect($this->referer());
            }


            if (
                (strlen($this->request->data['Accruals']['month']['month']) <= 0) ||
                (strlen($this->request->data['Accruals']['year']['year']) <= 0) ||
                (strlen($this->request->data['Accruals']['trn']) <= 0)
            ) {
                $this->Session->setFlash("All the fields are required", "flash/error");
                $this->redirect($this->referer());
            }
            $params = array(
                "year"        => (int) $this->request->data['Accruals']['year']['year'],
                "month"        => (int) $this->request->data['Accruals']['month']['month'],
                "trn"        => $this->request->data['Accruals']['trn']
            );

            $sasResult = trim($this->SAS->curl("rev_eom_booking.sas", $params, false));
            if (substr($sasResult, 0, 2) == 'ZX') {
                $this->Session->setFlash("Booking successfully created", "flash/success");
                $this->log_entry("REV-EOM-ACCRUALS " . $this->request->data['Accruals']['month']['month'] . "/" . $this->request->data['Accruals']['year']['year'] . " - " . $sasResult, "treasury");
                $this->redirect("/treasury/treasuryaccruals/reveomaccruals_result/" . $sasResult . "/" . $this->request->data['Accruals']['year']['year'] . "/" . $this->request->data['Accruals']['month']['month']);
            } else {
                $this->Session->setFlash($sasResult, "flash/default");
            }
        }
    }

    public function reveomaccruals_result($transaction_id = null, $year = null, $month = null)
    {
        @$this->validate_param('int', $transaction_id);
        @$this->validate_param('string', $year);
        @$this->validate_param('string', $month);
        $rows = array();
        if ($transaction_id) {
            $this->paginate = array(
                'conditions' => array('transaction_id' => $transaction_id),
                'limit' => 20,
            );
            $rows = $this->paginate('HistoPs');
        }

        $this->set(compact('transaction_id', 'rows', 'year', 'month'));
    }

    function reveomaccruals_pdf($transaction_id = null, $start_date = null, $end_date = null)
    {
        @$this->validate_param('int', $transaction_id);
        @$this->validate_param('date', $start_date);
        @$this->validate_param('date', $end_date);
        if (isset($transaction_id) && isset($start_date) && isset($end_date)) {
            /*$rows = array();
			$this->paginate = array(
			    	'conditions' => array(
					'transaction_id' => $transaction_id
				),
			    	'limit' => 100000,
			);
			$rows = $this->paginate('HistoPs');
			*/
            $conditions = array('conditions' => array('transaction_id' => $transaction_id));
            $rows = $this->HistoPs->find("all", $conditions);
            $this->set(compact('transaction_id', 'rows', 'start_date', 'end_date'));

            $view = new View($this);

            /* PDF generation */
            $raw = $view->render('Accruals/reveomaccruals_pdf');
            $raw = strstr($raw, '<!-- di -->'); // remove cake styling
            $raw = strstr($raw, '<!-- end di -->', true);

            // write to the database
            $pdf_file = array('Pdf' => array(
                'name' => $transaction_id, // change this to something in a form
                'raw' => base64_encode($raw) // encode the data to save space
            ));

            $this->autoRender = false;
            // get an instance of wkhtmltopdf
            $pdf = new WkHtmlToPdf();

            // decode the database and add the html to the pdf
            $html = base64_decode($pdf_file['Pdf']['raw']);

            $pdf->addPage($html);
            $pdf->setOptions(array('footer-right' => '"Page [page]/[topage]"'));
            if (!$pdf->send($transaction_id . ".pdf")) {
                $this->Session->setFlash('Could not create PDF: ' . $pdf->getError() . ' Please contact the administrator', 'flash/error');
                $this->redirect($this->referer());
            }
        }
    }

    function revinoutbooking()
    {
        $log = $this->LogEntry->find('first', array(
            "conditions" => array(
                "message LIKE 'REV-IN-OUT-BOOKING%'"
            ),
            "order" => array(
                'datetime DESC'
            ),
        ));
        $this->set(compact("log"));
        if ($this->request->is('post')) {

            // group retrieving
            $groups = CakeSession::read('UserAuth.UserGroups');
            if (!is_array($groups)) $groups = array($groups);
            if (!empty($groups)) foreach ($groups as $group) {
                $groupsnames[] = $group['alias_name'];
            }
            if (in_array('readOnlyTreasury', $groupsnames)) {
                $this->Session->setFlash("You are currently in a read only profile, this functionality is disabled", "flash/error");
                $this->redirect($this->referer());
            }


            if (
                (strlen($this->request->data['Accruals']['start_date']) <= 0) ||
                (strlen($this->request->data['Accruals']['end_date']) <= 0) ||
                (strlen($this->request->data['Accruals']['trn']) <= 0)
            ) {
                $this->Session->setFlash("All the fields are required", "flash/error");
                $this->redirect($this->referer());
            }
            $params = array(
                "trn"            => $this->request->data['Accruals']['trn'],
                "start_date"    => $this->request->data['Accruals']['start_date'],
                "end_date"        => $this->request->data['Accruals']['end_date'],
            );

            $sasResult = trim($this->SAS->curl("rev_inout_booking.sas", $params, false));
            if (substr($sasResult, 0, 2) == 'ZY') {
                $this->Session->setFlash("Booking successfully created", "flash/success");
                $this->log_entry("REV-IN-OUT BOOKING - " . $sasResult . " from " . $this->request->data['Accruals']['start_date'] . " to " . $this->request->data['Accruals']['end_date'], "treasury");
                $this->redirect("/treasury/treasuryaccruals/revinout_result/" . $sasResult . "/" . $this->request->data['Accruals']['start_date'] . "/" . $this->request->data['Accruals']['end_date']);
            } else {
                $this->Session->setFlash("Result is : " . $sasResult, "flash/default");
            }
        }
    }

    function revinout_result($transaction_id = null, $start_date = null, $end_date = null)
    {
        @$this->validate_param('int', $transaction_id);
        @$this->validate_param('date', $start_date);
        @$this->validate_param('date', $end_date);
        $rows = array();
        if ($transaction_id) {
            $this->paginate = array(
                'conditions' => array('transaction_id' => $transaction_id),
                'limit' => 20,
            );
            $rows = $this->paginate('HistoPs');
        }

        $this->set(compact('transaction_id', 'rows', 'start_date', 'end_date'));
    }

    function revinout_pdf($transaction_id = null, $start_date = null, $end_date = null)
    {

        @$this->validate_param('int', $transaction_id);
        @$this->validate_param('date', $start_date);
        @$this->validate_param('date', $end_date);
        if (isset($transaction_id) && isset($start_date) && isset($end_date)) {
            /*$rows = array();
			$this->paginate = array(
			    	'conditions' => array('transaction_id' => $transaction_id),
			    	'limit' => 1000000,
			);
			$rows = $this->paginate('HistoPs');
			*/
            $conditions = array('conditions' => array('transaction_id' => $transaction_id));
            $rows = $this->HistoPs->find("all", $conditions);
            $this->set(compact('transaction_id', 'rows', 'start_date', 'end_date'));

            $view = new View($this);

            /* PDF generation */
            $raw = $view->render('Accruals/revinout_pdf');
            $raw = strstr($raw, '<!-- di -->'); // remove cake styling
            $raw = strstr($raw, '<!-- end di -->', true);

            // write to the database
            $pdf_file = array('Pdf' => array(
                'name' => $transaction_id, // change this to something in a form
                'raw' => base64_encode($raw) // encode the data to save space
            ));

            $this->autoRender = false;
            // get an instance of wkhtmltopdf
            $pdf = new WkHtmlToPdf();

            // decode the database and add the html to the pdf
            $html = base64_decode($pdf_file['Pdf']['raw']);

            $pdf->addPage($html);
            $pdf->setOptions(array('footer-right' => '"Page [page]/[topage]"'));
            if (!$pdf->send($transaction_id . ".pdf")) {
                $this->Session->setFlash('Could not create PDF: ' . $pdf->getError() . ' Please contact the administrator', 'flash/error');
                $this->redirect($this->referer());
            }
        }
    }
}
