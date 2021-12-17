<?php

declare(strict_types=1);

namespace Damsv2\Controller;

use Cake\Datasource\ConnectionManager;
use KubAT\PhpSimple\HtmlDomParser;
use App\Lib\DownloadLib;
use Cake\Event\EventInterface;
use App\Lib\Helpers;

/**
 * SamplingEvaluation Controller
 *
 * @property \App\Model\Table\SamplingEvaluationTable $SamplingEvaluation
 * @method \App\Model\Entity\SamplingEvaluation[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SamplingEvaluationController extends AppController
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

    public function index()
    {
        //$identity = $this->Authentication->getIdentity();
        //dd($identity);
        $connection = ConnectionManager::get('default');
        $years = $connection->execute('SELECT * FROM annual_sampling_parameters as asp WHERE sampling_year = ( SELECT MAX(cast(sampling_year AS unsigned)) FROM annual_sampling_parameters )')->fetchAll('assoc');

//        $session = $this->request->getSession();
//        $user = $session->read('User.first_name') . " " . $session->read('User.last_name');
//        dd($user);
        if ($years[0]['last_sampled_month'] == '12') {
            //increment year
            $year = "" . ($years[0]['sampling_year'] + 1);
            $sampled_month = 1;
            //no other default values
            $expected_amount = "";
            $number_of_samples = "";
            $sampling_interval = "";
            $disabled = false;
        } else {
            $this->Flash->success(__('Sampling draw has to be executed for all months of the year before parameters for the next year can be entered'));
            //$this->Flash->success(__('Sampling draw has to be executed for all months of the year before parameters for the next year can be entered'), 'flash/success');
            $year = $years[0]['sampling_year'];
            $sampled_month = $years[0]['last_sampled_month'] + 1;
            $expected_amount = $years[0]["expected_payments_eur"];
            $number_of_samples = $years[0]["number_of_samples"];
            $sampling_interval = $years[0]["sampling_interval_eur"];
            $disabled = true;
        }

        /* if ((intval($year) >= intval(date("Y"))) && (intval($month) >= intval(date("m"))))
          {
          $this->set('submit_disabled', true);
          }
          else
          {
          $this->set('submit_disabled', false);
          } */

        $last_sampled_month = $years[0]['last_sampled_month'];

        if ($this->request->is('post')) {

            // group retrieving  // http://vmu-sas-01:8080/browse/PLATFORM-259
//            $groups = $session->read('UserAuth.UserGroups');
//            if (!is_array($groups))
//                $groups = array($groups);
//            if (!empty($groups))
//                foreach ($groups as $group) {
//                    $groupsnames[] = $group['alias_name'];
//                }
//            if (in_array('ReadOnlyDams', $groupsnames)) {
//                $this->Flash->error('You are currently in a read only profile, this functionality is disabled');
//                $this->redirect($this->referer());
//            }

            $year = $this->request->getData('AnnualSamplingParameter.year');
            $sampled_month = $this->request->getData('AnnualSamplingParameter.sampled_month');
            //$last_sampled_month = $this->request->getData('last_sampled_month'];
            $expected_payment_eur = $this->request->getData('AnnualSamplingParameter.expected_amount');
            $number_of_samples = $this->request->getData('AnnualSamplingParameter.number_of_samples');
            $sampling_interval = $this->request->getData('AnnualSamplingParameter.sampling_interval');
            $user = $this->Authentication->getIdentity()->get('id');
            ; //CakeSession::read('UserAuth.User.first_name') . " " . strtoupper(CakeSession::read('UserAuth.User.last_name'));

            if ($last_sampled_month == "12" && !empty($expected_payment_eur) && !empty($number_of_samples)) {
                if ((!is_numeric($expected_payment_eur)) || (!is_numeric($number_of_samples))) {
                    $this->Flash->error(__('Please enter numbers only.'));
                } else {
                    //new line/new year
                    if (empty($sampling_interval)) {
                        $sampling_interval = $expected_payment_eur / $number_of_samples;
                    }
                    $connection->execute("INSERT INTO annual_sampling_parameters (sampling_year, expected_payments_eur, number_of_samples, sampling_interval_eur, user) VALUES (" . $year . ", " . $expected_payment_eur . ", " . $number_of_samples . ", " . $sampling_interval . ", '" . $user . "')");

                    $sasResult = $this->SAS->curl(
                            "annual_sampling.sas", array(
                        "year" => $year
                            ),
                            false,
                            false
                    );
                    $log_info = array(
                        'year'                  => $year,
                        'expected_payments_eur' => $expected_payment_eur,
                        'number_of_samples'     => $number_of_samples,
                    );
                    $this->logDams('Annual CIP sampling parameters:' . json_encode($log_info), 'dams', 'Annual CIP sampling parameters');
                    error_log("annual_sampling.sas called and returned " . $sasResult);
                    $this->Flash->success(__('Annual CIP sampling parameters have been updated'));
                    $this->redirect($this->referer());
                }
            } elseif (empty($expected_payment_eur) || empty($number_of_samples)) {
                $this->Flash->success(__('Please enter all parameters.'));
            }
        }
        $years_input = array($year => $year);
        $this->set('years', $years_input);
        $this->set('sampled_month', $sampled_month);
        $this->set('last_sampled_month', $last_sampled_month);
        $this->set('disabled', $disabled);
        $this->set('expected_amount', $expected_amount);
        $this->set('number_of_samples', $number_of_samples);
        $this->set('sampling_interval', $sampling_interval);
    }

    public function drawing()
    {
        $connection = ConnectionManager::get('default');
        $years = $connection->execute('SELECT * FROM annual_sampling_parameters as asp WHERE sampling_year = ( SELECT MAX(cast(sampling_year AS unsigned)) FROM annual_sampling_parameters )')->fetchAll('assoc');

        $last_year = $years[0]['sampling_year'];
        $last_month = $years[0]['last_sampled_month'];

        $months = [
            null => 'January',
            1    => 'January',
            2    => 'February',
            3    => 'March',
            4    => 'April',
            5    => 'May',
            6    => 'June',
            7    => 'July',
            8    => 'August',
            9    => 'September',
            10   => 'October',
            11   => 'November',
            12   => 'December'
        ];
        /* $expected_amount = $years[0]["expected_payments_eur"];
          $number_of_samples = $years[0]["number_of_samples"];
          $sampling_interval = $years[0]["sampling_interval_eur"]; */

        $this->set('last_year', $last_year);
        $this->set('last_month', $months[$last_month]);
        $submit_disabled = false;
        if ($last_month === '12') {
            $new_year = $last_year + 1;
            $new_month = 1;
            $this->Flash->error(__('Annual sampling parameters have not been entered in the database.'));
            $submit_disabled = true;
        } else {
            $new_year = $last_year;
            $new_month = $last_month + 1;
        }
        $new_month_libel = $months[$new_month];

        $time_now = time();
        $date_string = $new_year . "-" . $new_month . "-15";
        $date_end_of_month = date("Y-m-t", strtotime($date_string)); //t for last day of month
        $time_end_of_month = strtotime($date_end_of_month . " 23:59:59");
        $msg = "";
        if ($time_now <= $time_end_of_month) {//rule : the month must have fully elapsed
            //$this->Flash->error(__('WARNING: The month of drawing has not yet elapsed.'));
            $msg = '<p style="font-size:1.5em;">WARNING: The month of drawing has not yet elapsed.</p>';
        }

        $this->set('msg', $msg);
        $this->set('new_year', $new_year);
        $this->set('new_month', $new_month);
        $this->set('new_month_libel', $new_month_libel);
        $this->set('months', $months);
        $this->set('submit_disabled', $submit_disabled);

        if ($this->request->is('post')) {
            // group retrieving  // http://vmu-sas-01:8080/browse/PLATFORM-259
//            $groups = CakeSession::read('UserAuth.UserGroups');
//            if (!is_array($groups))
//                $groups = array($groups);
//            if (!empty($groups))
//                foreach ($groups as $group) {
//                    $groupsnames[] = $group['alias_name'];
//                }
//            if (in_array('ReadOnlyDams', $groupsnames)) {
//                $this->Flash->error("You are currently in a read only profile, this functionality is disabled");
//                $this->redirect($this->referer());
//            }
            $year = $this->request->getData('sampleDrawing.new_year');
            $month = $this->request->getData('sampleDrawing.new_month'); //text
            //$new_month//numeric
            if (!empty($this->request->getData('sampleDrawing.finalized')) && $this->request->getData('sampleDrawing.finalized') == 1) {
                $msg = "";

                if ($last_month != '12') {
                    //update line of the year
                    $years = $connection->execute("UPDATE annual_sampling_parameters set last_sampled_month = " . $new_month . " WHERE sampling_year = " . $year);
                }
                $user = $this->Authentication->getIdentity()->get('id');
                $sasResult = $this->SAS->curl(
                        "sampling_drawing.sas", array(
                    "year"    => $year,
                    "month"   => $month,
                    "user_id" => $user
                        ),
                        false,
                        false
                );
                $log_info = array(
                    'year'  => $year,
                    'month' => $month,
                );
                $this->logDams('CIP sample drawing:' . json_encode($log_info), 'dams', 'CIP sample drawing');

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

                $result = DownloadLib::change_downloadable_links($result, 'sampling/error');

                $this->set('sasResult', $result);

                $msg .= 'The drawing was executed.';

                $this->Flash->success(__($msg, ['escape' => false]));
            } else {
                $this->Flash->error(__('You must validate that all Payment Demands have been finalized for the month of drawing. The sampling process was not triggered.'));
            }
        }
    }

    public function nonCipSampling()
    {
        $connection = ConnectionManager::get('default');
        $years_latest_executed = $connection->execute('SELECT MAX(sample_year) as sample_year FROM non_cip_sample_execution WHERE executed = 1')->fetchAll('assoc');
        $years_latest_not_executed = $connection->execute('SELECT MAX(sample_year) as sample_year FROM non_cip_sample_execution WHERE executed = 0')->fetchAll('assoc');
        //dd($years_latest_not_executed);
        $next_execution_year = date("Y") - 1;
        if (!empty($years_latest_not_executed[0]['sample_year'])) {
            $next_execution_year = $years_latest_not_executed[0]['sample_year'];
        }

        $this->set('last_execution_year', $years_latest_executed[0]['sample_year']);
        $this->set('next_execution_year', $next_execution_year);

        $msg = "";
        if ($next_execution_year === intval(date("Y"))) {
            $msg = '<h6>WARNING: The year of drawing has not yet lapsed.</h6>';
        }


        $this->set('msg', $msg);

        if ($this->request->is('post')) {
            if (!empty($this->request->getData('finalized') && ($this->request->getData('finalized') == 1))) {
                $msg = "";
                $year = $this->request->getData('non_cip_sampling.new_year');

                $user = $this->Authentication->getIdentity()->get('id');
                $sasResult = $this->SAS->curl(
                        "Non_CIP_Sample.sas", array(
                    "year"    => $year,
                    "user_id" => $user
                        ),
                        false,
                        false
                );
                $log_info = array(
                    'year' => $year,
                );
                $this->logDams('NON-CIP sample drawing:' . json_encode($log_info), 'dams', 'non-CIP sample drawing');

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

                $result = DownloadLib::change_downloadable_links($result);

                $this->set('sasResult', $result);

                $msg .= 'The drawing was executed.';

                $this->Flash->success(__($msg));
            }
        }
    }
	
	public function listSamplesNonCip()
	{
		$files_path = "/var/www/html/data/damsv2/sampling/";
		$dir_list = scandir($files_path, 1);// 'Desc' order
		$dir_list = array_diff($dir_list, array('..', '.'));

		function DateSort($a, $b) {

			// If the dates are equal, do nothing.
			if($a == $b) return 0;
			
			if ((substr_count ($a, '_') < 3) || (substr_count($b, '_') < 3))
			{
				return 0;
			}
			
			// Dissassemble dates
			list($amonth, $ayear) = explode('_',$a);
			list($bmonth, $byear) = explode('_',$b);

			// Pad the month with a leading zero if leading number not present
			$amonth = str_pad($amonth, 2, "0", STR_PAD_LEFT);
			$bmonth = str_pad($bmonth, 2, "0", STR_PAD_LEFT);

			// Reassemble dates
			$a = $ayear . $amonth;
			$b = $byear . $bmonth;

			// Determine whether date $a > $date b
			return ($a < $b) ? 1 : -1;
		}
		
		usort($dir_list, 'DateSort');

		$files_path = "/data/damsv2/sampling/";
		
		$this->set('file_path', $files_path);

		$current_year = intval(date("Y"));
		$year_list = array('0' => '-Any year-');
		foreach($dir_list as $k=>$file)
		{
			if (strpos($file, '_Non_CIP_Sample.xlsx') === false)
			{
				unset($dir_list[$k]);
			}
			else
			{
				$file_name = explode('_', $file);
				if (count($file_name) > 2)
				{
					$year = $file_name[0];
					$year = intval($year);
					if (($year > 2000) && ($year < 2100))
					{
						$year_list[$year] = $year;
					}
					else
					{
						unset($dir_list[$k]);
					}
				}
			}
		}
		$this->set('dir_list', $dir_list);
		$this->set('year_list', $year_list);
	
	}

    public function listSamples()
    {
        $files_path = "/var/www/html/data/damsv2/sampling/error/";
        $dir_list = scandir($files_path, 1); // 'Desc' order
        $dir_list = array_diff($dir_list, ['..', '.']);
        if (is_array($dir_list)) {
            usort($dir_list, function($a, $b) {

                // If the dates are equal, do nothing.
                if ($a == $b)
                    return 0;

                if ((substr_count($a, '_') < 3) || (substr_count($b, '_') < 3)) {
                    return 0;
                }

                // Dissassemble dates
                list($amonth, $ayear) = explode('_', $a);
                list($bmonth, $byear) = explode('_', $b);

                // Pad the month with a leading zero if leading number not present
                $amonth = str_pad($amonth, 2, "0", STR_PAD_LEFT);
                $bmonth = str_pad($bmonth, 2, "0", STR_PAD_LEFT);

                // Reassemble dates
                $a = $ayear . $amonth;
                $b = $byear . $bmonth;

                // Determine whether date $a > $date b
                return ($a < $b) ? 1 : -1;
            });
        } else {
            $dir_list = array();
        }
        $files_path = "/data/damsv2/sampling/error/";

        $this->set('file_path', $files_path);

        $month_list = [
            '0'  => '-Any month-',
            '1'  => 'January',
            '2'  => 'February',
            '3'  => 'March',
            '4'  => 'April',
            '5'  => 'May',
            '6'  => 'June',
            '7'  => 'July',
            '8'  => 'August',
            '9'  => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December'
        ];

        $this->set('month_list', $month_list);
        $current_year = intval(date("Y"));
        $year_list = ['0' => '-Any year-'];
        foreach ($dir_list as $k => $file) {
            $file_name = explode('_', $file);
            if (count($file_name) > 2) {
                $year = $file_name[1];
                $year = intval($year);
                if (($year > 2000) && ($year < 2100)) {
                    $year_list[$year] = $year;
                } else {
                    unset($dir_list[$k]);
                }
            }
        }
        $this->set('dir_list', $dir_list);
        $this->set('year_list', $year_list);
    }

    public function sampleUpload($version = 1, $correction = 0)
    {
        if ($this->request->is('post')) {

            // group retrieving  // http://vmu-sas-01:8080/browse/PLATFORM-259
//            $groups = CakeSession::read('UserAuth.UserGroups');
//            if (!is_array($groups))
//                $groups = array($groups);
//            if (!empty($groups))
//                foreach ($groups as $group) {
//                    $groupsnames[] = $group['alias_name'];
//                }
//            if (in_array('ReadOnlyDams', $groupsnames)) {
//                $this->Flash->error("You are currently in a read only profile, this functionality is disabled");
//                $this->redirect($this->referer());
//            }
            //get the fake report_id
            $connection = ConnectionManager::get('default');
            $this->loadModel('Damsv2.Report');
            $report = $this->Report->find('all', [
                        'conditions' => [
                            'Report.template_id' => 145,
                ]])->first();
            //$report = $this->Report->query("SELECT report_id FROM report WHERE template_id=145")->fetchAll('assoc');
            $report_id = $report->report_id;
            //$report_id = 9999;//TODO update if push ; // id of the report for the sampling (fake one)
            //data[sampleupdate][file]
            $file = $this->request->getData('sampleupdate.file');
            $file_name = $file->getClientFilename();

            $ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $file_renamed = $this->File->cleanName($file_name);

            $fileMovingPath = '/var/www/html' . DS . 'data' . DS . 'damsv2' . DS . 'sampling' . DS . 'upload' . DS . $file_renamed;

            $version = $this->request->getData('sampleupdate.version');
            $correction = $this->request->getData('sampleupdate.correction');
            $checks = '';
            if ($this->File->checkFileInForm($file, $fileMovingPath)) {
                //$this->request->getData('Report.input_filename') = $file['name'];

                $data = ['Sampling' => ['sheets' => ['SU']], 'Template' => ['id' => 145], 'correction' => '1'];
                $checks = $this->Spreadsheet->checkSheetsamplingUpdate($data, $file, $fileMovingPath);
                if (!empty($checks['transcode'])) {
                    $fileMovingPath = $checks['transcode']['filename']; //converted to xlsx
                }


                /* 	if($this->Spreadsheet->noError($checks['errors']))
                  {
                  //new sas check on file DAMS 473
                  $data_num_check = array('report_id' => $report_id,
                  'save'=> 0, 'correction'=>0,
                  'template_id' => 145,
                  'version' => 1,//we don't have a version for sampling
                  'version_number_check' => 1,
                  'headers_included' => 'yes',
                  'input_filename_check' => $fileMovingPath,
                  'template_type_id' => 10);

                  // DAMS 473
                  $numerical_errors = $this->num_check_sas($data_num_check);
                  $checks['errors']['other'] = $numerical_errors['other'];
                  } */


                if ($this->Spreadsheet->noError($checks['errors'])) {
                    $owner = $this->userIdentity()->get('id'); //CakeSession::read('UserAuth.User.id');
                    //$report_name = "Sampling_information_update";
                    $report->owner = $owner;
                    $report->version_number = $version;
                    $report->input_filename = $fileMovingPath;

                    $this->Report->save($report);

                    $sasResult = $this->SAS->curl(
                            'import_file_edit.sas', [
                        'report_id'        => $report_id,
                        'correction'       => $correction, //0 / 1
                        'template_type_id' => 10, //only one for sampling => always 10
                            ],
                            false,
                            false
                    );
                    $this->logDams('Sampling information update: {"report_id": "' . $report_id . '" ,"version":"' . $version . '"}', 'dams', 'Sampling information update');
                    error_log("import_file_edit.sas called and returned " . $sasResult);

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
                    if (strpos($result, 'Data has been successfully updated in the database!') !== false) {
                        $this->Flash->success("Data has been successfully updated in the database!");
                        $this->set('success', true);
                    } else {
                        //correction
                        $correction = 1;
                        $version++;
                        $result = DownloadLib::change_downloadable_links($result, 'bulk/out');
                        //$this->redirect(array('action' => 'sample_upload', 'correction' => $correction, 'version' => $version));
                        $this->set('sasResult', $result);
                        $this->set('correction', $correction);
                        $this->set('version', $version);
                    }
                } else {
                    $msg = '';
                    //$this->ErrorsLog->checkErrorImport($report, 'NOT OK');
                    $msg = $this->Spreadsheet->showError($checks['errors']);

                    $this->Flash->error($msg, ['escape' => false]);
                    $this->redirect($this->referer());
                }
            }
        }
        $this->set('correction', $correction);
        $this->set('version', $version);
    }

    public function yearlyEvaluation()
    {
        // already generated pdf list
        $files_path = "/var/www/html/data/damsv2/sampling/pdf/";
        $dir_list = scandir($files_path, 1); // 'Desc' order
        $dir_list = array_diff($dir_list, array('..', '.'));
        if (is_array($dir_list)) {
            usort($dir_list, function($a, $b) {

                // If the dates are equal, do nothing.
                if ($a == $b)
                    return 0;

                if ((substr_count($a, '_') < 3) || (substr_count($b, '_') < 3)) {
                    return 0;
                }

                // Dissassemble dates
                list($amonth, $ayear) = explode('_', $a);
                list($bmonth, $byear) = explode('_', $b);

                // Pad the month with a leading zero if leading number not present
                $amonth = str_pad($amonth, 2, "0", STR_PAD_LEFT);
                $bmonth = str_pad($bmonth, 2, "0", STR_PAD_LEFT);

                // Reassemble dates
                $a = $ayear . $amonth;
                $b = $byear . $bmonth;

                // Determine whether date $a > $date b
                return ($a < $b) ? 1 : -1;
            });
        } else {
            $dir_list = array();
        }

        $this->set('pdf_list', $dir_list);

        $disabled_submit = false;
        $error = 0;
        $error_msg = [];
        $connection = ConnectionManager::get('default');
        $years = $connection->execute("SELECT distinct(sampling_year) FROM annual_sampling_parameters as asp")->fetchAll('assoc');

        $year_list = ['0' => '-Year-'];
        foreach ($years as $year) {
            $y = $year['sampling_year'];
            $year_list[$y] = $y;
        }
        $this->set('year_list', $year_list);

        if ($this->request->is('post')) {

            // group retrieving  // http://vmu-sas-01:8080/browse/PLATFORM-259
//            $groups = CakeSession::read('UserAuth.UserGroups');
//            if (!is_array($groups))
//                $groups = array($groups);
//            if (!empty($groups))
//                foreach ($groups as $group) {
//                    $groupsnames[] = $group['alias_name'];
//                }
//            if (in_array('ReadOnlyDams', $groupsnames)) {
//                $this->Flash->error("You are currently in a read only profile, this functionality is disabled");
//                $this->redirect($this->referer());
//            }


            $year = $this->request->getData('sampleevaluation.year');
            $search_year = $connection->execute("SELECT * FROM sampling_evaluation where evaluation_year = " . intval($year))->fetchAll('assoc');

            if (!empty($search_year)) {
                //rule : year must not be already evaluated
                $error_msg[] = __('Sample evaluation has already been executed for the selected year.');
                $error++;
            }

            $search_month = $connection->execute("SELECT last_sampled_month FROM annual_sampling_parameters where sampling_year = " . intval($year))->fetchAll('assoc');

            if (count($search_month) >= 1) {
                $last_sampled_month = $search_month[0]['last_sampled_month'];
                if ($last_sampled_month != '12') {
                    //rule : sample draw must have been executed for the selected year
                    $error_msg[] = __('Sample draw has not been executed for all months of the selected year.');
                    $error++;
                }
            }

            $search_pdlr = $connection->execute("SELECT * FROM pdlr_transactions where sampled_year = " . intval($year) . " AND sampled='YES' AND sample_impact_eur is null")->fetchAll('assoc');

            if (!empty($search_pdlr)) {
                //rule : sample_impact_eur must be filled for all pdlr_transactions of sampled_year = year and random_sampled_flag='YES'
                $error_msg[] = __('FI sample information has not been uploaded for some PD lines sampled in the selected year.');
                $error++;
            }

            if ($error == 0) {
                $user = $this->Authentication->getIdentity()->get('id');
                $sasResult = $this->SAS->curl(
                        'yearly_evaluation.sas', [
                    'year'    => $year,
                    'user_id' => $user,
                        ],
                        false,
                        false
                );
                $this->logDams('Yearly CIP sample evaluation:{"year":"' . $year . '"}', 'dams', 'Yearly CIP sample evaluation');
                //extract class 'branch'
                $dom = HtmlDomParser::str_get_html($sasResult);

                $divs = $dom->find('div');
                $result = '';
                foreach ($divs as $t) {
                    if ($t->class == 'branch') {
                        $result = $t->outertext;
                    }
                }

                //pdf of $sasResult
                if (!empty($result)) {
                    $this->yearlyEvaluationPdf($result, $year);
                }

                $this->viewBuilder()->enableAutoLayout(true);

//                $this->autoRender = true;
//                $this->autoLayout = true;
                $this->set('sasResult', $result);
            } else {
                $msg = implode('<br />', $error_msg);
                $this->Flash->error($msg, ['escape' => false]);
                $disabled_submit = true;
            }
        }
        $this->set('disabled_submit', $disabled_submit);
    }

    private function yearlyEvaluationPdf($sasresult, $year)
    {
        $this->viewBuilder()->enableAutoLayout(false);

        $user = $this->userIdentity()->get('username');
        $path = '/var/www/html/data/damsv2/sampling/pdf/' . $year . '_CIP_Yearly_Sample_Evaluation.pdf';
        $this->set('user', $user);
        $this->set('sasResult', $sasresult);

        $this->viewBuilder()->setClassName('CakePdf.Pdf');
        $this->viewBuilder()->setOption(
                'pdfConfig',
                [
                    'orientation'      => 'portrait',
                    'download'         => true, // This can be omitted if "filename" is specified.
                    'filename'         => $year . '_CIP_Yearly_Sample_Evaluation.pdf', //// This can be omitted if you want file name based on URL.
                    'user-style-sheet' => WWW_ROOT . 'css/site.css',
                ]
        );
    }

    public function transactionsUpdate($version = 1, $correction = 0)
    {
        if (!empty($this->request->getData('transactionupdate.version'))) {
            $version = $this->request->getData('transactionupdate.version');
        }
        if (!empty($this->request->getData('transactionupdate.correction'))) {
            $correction = $this->request->getData('transactionupdate.correction');
        }
        if ($this->request->is('post')) {
            // group retrieving  // http://vmu-sas-01:8080/browse/PLATFORM-259
//            $groups = CakeSession::read('UserAuth.UserGroups');
//            if (!is_array($groups))
//                $groups = array($groups);
//            if (!empty($groups))
//                foreach ($groups as $group) {
//                    $groupsnames[] = $group['alias_name'];
//                }
//            if (in_array('ReadOnlyDams', $groupsnames)) {
//                $this->Flash->error("You are currently in a read only profile, this functionnality is disabled");
//                $this->redirect($this->referer());
//            }
            //get the fake report_id
            $connection = ConnectionManager::get('default');
            $report = $connection->execute("SELECT report_id FROM report WHERE template_id=211")->fetchAll('assoc');
            $report_id = $report[0]["report_id"];
            //$report_id = 9999;//TODO update if push ; // id of the report for the sampling (fake one)

            $file = $this->request->getData('transactionupdate.file');
            $file_name = $file->getClientFilename();

            $file_renamed = $this->File->cleanName($file_name);
            $ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $file_renamed = $ext === 'xls' ? substr_replace($file_renamed, 'xlsx', strrpos($file_renamed, '.') + 1) : $file_renamed;
            $fileMovingPath = '/var/www/html' . DS . 'data' . DS . 'damsv2' . DS . 'sampling' . DS . 'upload' . DS . $file_renamed;

            if (empty($this->request->getData('transactionupdate.correction'))) {
                $correction = 0;
            } else {
                $correction = $this->request->getData('transactionupdate.correction');
            }

            if ($this->File->checkFileInForm($file, $fileMovingPath)) {

                $data = ['Sampling' => ['sheets' => ['TRS']], 'Template' => ['id' => 211], 'correction' => $correction];
                $checks = $this->Spreadsheet->checkSheetsamplingUpdate($data, $file, $fileMovingPath);

                if (empty($this->request->getData('transactionupdate.version'))) {
                    $version = 1;
                } else {
                    $version = $this->request->getData('transactionupdate.version');
                }

                if ($this->Spreadsheet->noError($checks['errors'])) {
                    $owner = $this->userIdentity()->get('id'); //CakeSession::read('UserAuth.User.id');

                    $this->loadModel('Damsv2.Report');
                    $report_data = $this->Report->find('all', [
                                'conditions' => [
                                    'Report.report_id' => $report_id,
                        ]])->first();

                    $report_data->owner = $owner;
                    $report_data->version_number = $version;
                    $report_data->input_filename = $file_renamed;

                    $this->Report->save($report_data);

                    $sasResult = $this->SAS->curl(
                            'import_file_edit.sas', [
                        'report_id'        => $report_id,
                        'correction'       => $correction, //0 / 1
                        'template_type_id' => 11, //only one for transaction sampling => always 11
                        'user_id'          => $owner,
                            ],
                            false,
                            false
                    );
                    $this->logDams('Transaction sampling: {"report_id":"' . $report_id . '","version": "' . $version . '"}', 'dams', 'Transaction sampling');

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
                    if (strpos($result, 'Data has been successfully updated in the database!') !== false) {
                        $this->Flash->success("Data has been successfully updated in the database!");
                        $this->set('success', true);
                        $correction = 0;
                        $version = 0;
                        $this->set('correction', $correction);
                        $this->set('version', $version);
                    } else {
                        //correction
                        $correction = 1;
                        $version++;
                        $result = DownloadLib::change_downloadable_links($result, 'bulk/out');
                        //$this->redirect(array('action' => 'sample_upload', 'correction' => $correction, 'version' => $version));
                        $this->set('sasResult', $result);
                        $this->set('correction', $correction);
                        $this->set('version', $version);
                    }
                } else {
                    $msg = '';
                    $msg = $this->Spreadsheet->showError($checks['errors']);
                    $this->Flash->error($msg, ['escape' => false]);
                }
            }
        }
        $this->set('version', $version);
        $this->set('correction', $correction);
    }

    public function manualSampling($version = 1, $correction = 0)
    {
        if ($this->request->is('post')) {
            // group retrieving  // http://vmu-sas-01:8080/browse/PLATFORM-259
//            $groups = CakeSession::read('UserAuth.UserGroups');
//            if (!is_array($groups))
//                $groups = array($groups);
//            if (!empty($groups))
//                foreach ($groups as $group) {
//                    $groupsnames[] = $group['alias_name'];
//                }
//            if (in_array('ReadOnlyDams', $groupsnames)) {
//                $this->Flash->error("You are currently in a read only profile, this functionnality is disabled");
//                $this->redirect($this->referer());
//            }
            //get the fake report_id
            $connection = ConnectionManager::get('default');
            $report = $connection->execute("SELECT report_id FROM report WHERE template_id=212")->fetchAll('assoc'); // check dummy report (did not exist in DEV)

            $report_id = $report[0]["report_id"];
            //$report_id = 9999;//TODO update if push ; // id of the report for the sampling (fake one)
            //data[sampleupdate][file]

            $file = $this->request->getData('manualsampling.file');
            $file_name = $file->getClientFilename();

            $ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $file_renamed = $this->File->cleanName($file_name);

            $fileMovingPath = '/var/www/html' . DS . 'data' . DS . 'damsv2' . DS . 'sampling' . DS . 'upload' . DS . $file_renamed; // TODO maybe change folder

            $version = $this->request->getData('manualsampling.version');
            $correction = $this->request->getData('manualsampling.correction');

            if ($this->File->checkFileInForm($file, $fileMovingPath)) {
                //$this->request->getData('Report.input_filename') = $file['name'];

                $data = ['Sampling' => ['sheets' => ['MS']], 'Template' => ['id' => 212], 'correction' => '1']; // no correction for this one, action and error_message are part of mapping (no taken into account, so correction = 1 always)
                $checks = $this->Spreadsheet->checkSheetsamplingUpdate($data, $file, $fileMovingPath);
                if (!empty($checks['transcode'])) {
                    //debug($checks);
                    $fileMovingPath = $checks['transcode']['filename']; //converted to xlsx
                }

                /* 	if($this->Spreadsheet->noError($checks['errors']))
                  {
                  //new sas check on file DAMS 473
                  $data_num_check = array('report_id' => $report_id,
                  'save'=> 0, 'correction'=>0,
                  'template_id' => 212,
                  'version' => 1,//we don't have a version for sampling
                  'version_number_check' => 1,
                  'headers_included' => 'yes',
                  'input_filename_check' => $fileMovingPath,
                  'template_type_id' => 12);

                  // DAMS 473
                  $numerical_errors = $this->num_check_sas($data_num_check);
                  $checks['errors']['other'] = $numerical_errors['other'];
                  } */

                if ($this->Spreadsheet->noError($checks['errors'])) {
                    $owner = $this->userIdentity()->get('id'); //CakeSession::read('UserAuth.User.id');
                    //$report_name = "Sampling_information_update";

                    $this->loadModel('Damsv2.Report');
                    $report_data = $this->Report->find('all', [
                                'conditions' => [
                                    'Report.report_id' => $report_id,
                        ]])->first();
                    $report_data->owner = $owner;
                    $report_data->version_number = $version;
                    $report_data->input_filename = $fileMovingPath;
                    $this->Report->save($report_data); // TODO maybe not needed anymore


                    $sasResult = $this->SAS->curl(// TODO update with real params and script name
                            'import_file_edit.sas', [
                        'report_id'        => $report_id,
                        'correction'       => $correction, //0 / 1
                        'template_type_id' => 12, //only one for manual sampling => always 12
                            ],
                            false,
                            false
                    );
                    $this->logDams('Manual sampling: {"report_id":"' . $report_id . '","version":"' . $version . '"}', 'dams', 'Manual sampling');
                    error_log("import_file_edit.sas (manual sampling) called and returned " . $sasResult);
                    file_put_contents('/tmp/manual_sampling_sas', $sasResult);

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
                    if (strpos($result, 'Data has been successfully updated in the database!') !== false) {
                        $version++;
                        $this->set('success', true);
                        $this->Flash->success("Data has been successfully updated in the database!");
                    } else {
                        //correction
                        $correction = 1;
                        $version++;
                        $result = DownloadLib::change_downloadable_links($result, 'bulk/out');
                        //$this->redirect(array('action' => 'sample_upload', 'correction' => $correction, 'version' => $version));
                        $this->set('sasResult', $result);
                        $this->set('correction', $correction);
                        $this->set('version', $version);
                    }
                } else {
                    $msg = '';
                    //$this->ErrorsLog->checkErrorImport($report, 'NOT OK');
                    $msg = $this->Spreadsheet->showError($checks['errors']);
                    $this->Flash->error($msg, ['escape' => false]);
                    $this->redirect($this->referer());
                }
            }
        }
        $this->set('version', $version);
        $this->set('correction', $correction);
    }

    public function manualPdSampling()
    {
        $this->loadModel('Damsv2.Product');
        $products = $this->Product->getProducts();
        $this->set('products', $products);
        $this->loadModel('Damsv2.Portfolio');
        $portfolios = $this->Portfolio->find('list', [
                    'contain'    => 'Product',
                    'fields'     => ['portfolio_id', 'portfolio_name', 'mandate'],
                    'keyField'   => 'portfolio_id',
                    'valueField' => 'portfolio_name',
                    'groupField' => 'mandate',
                    'order'      => 'mandate',
                    'conditions' => ['Product.product_id NOT IN' => [22, 23]],
                ])->toArray();

        $this->set('portfolios', $portfolios);

        $month_list = [
            '0'  => '-Any month-',
            '1'  => 'January',
            '2'  => 'February',
            '3'  => 'March',
            '4'  => 'April',
            '5'  => 'May',
            '6'  => 'June',
            '7'  => 'July',
            '8'  => 'August',
            '9'  => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December'
        ];
        $this->set('month_list', $month_list);

        $connection = ConnectionManager::get('default');
        $years_sampling = $connection->execute("SELECT distinct (sampled_year) as year FROM pdlr_transactions where sampled_year is not null ORDER BY `pdlr_transactions`.`sampled_year` ASC")->fetchAll('assoc');

        $year_list = ['0' => '-Any year-'];
        foreach ($years_sampling as $y) {
            $year_list[intval($y['year'])] = $y['year'];
        }

        $types = [0 => '-Any type-', 1 => 'PERIODIC MANUAL', 2 => 'DM', 3 => 'MV'];
        $findings = [0 => '-Any finding-', 1 => 'Finding missing', 2 => 'Finding exists'];
        $this->set('year_list', $year_list);
        $this->set('types', $types);
        $this->set('findings', $findings);

        if ($this->request->is('post')) {
            $this->loadModel('Damsv2.PdlrTransaction');
            // group retrieving  // http://vmu-sas-01:8080/browse/PLATFORM-259
//            $groups = CakeSession::read('UserAuth.UserGroups');
//            if (!is_array($groups))
//                $groups = array($groups);
//            if (!empty($groups))
//                foreach ($groups as $group) {
//                    $groupsnames[] = $group['alias_name'];
//                }
//            if (in_array('ReadOnlyDams', $groupsnames)) {
//                $this->Flash->error("You are currently in a read only profile, this functionnality is disabled");
//                $this->redirect($this->referer());
//            }
            //filter defaults
            $conditions = [
                'PdlrTransaction.sampled <>'     => 'PERIODIC RANDOM',
                'PdlrTransaction.sampled IS NOT' => null, // Only inclusion      
            ];

            $query = "SELECT * from dams.pdlr_transactions pt WHERE sampled IS NOT NULL AND sampled !='PERIODIC RANDOM'  ";

            $portfolio_id = $this->request->getData('Portfolio.portfolio_id');
            if (empty($portfolio_id)) {
                $portfolio_id = 0;
            } else {
                $portfolio_id = intval($portfolio_id);
                $query .= " AND pt.portfolio_id = " . $portfolio_id;
            }

            $product_id = $this->request->getData('Product.product_id');

            if (empty($product_id)) {
                $this->Flash->error(__('You must select a product, please, try again!'));
                return $this->redirect(['action' => 'manual-pd-sampling']);
            } else {
                if ($portfolio_id === 0) {
                    $portfolio_list = $this->Portfolio->find('list', [
                                'conditions' => ['product_id' => $product_id],
                                'fields'     => 'portfolio_id',
                                'keyField'   => 'portfolio_id',
                                'valueField' => 'portfolio_id',
                            ])->toArray();

                    $portfolios = implode(',', array_keys($portfolio_list));
                    $query .= " AND pt.portfolio_id IN (" . $portfolios . ")";
                    $conditions = Helpers::arrayPushAssoc($conditions, 'PdlrTransaction.portfolio_id IN', $portfolio_list);
                } else {
                    $conditions = Helpers::arrayPushAssoc($conditions, 'PdlrTransaction.portfolio_id', $portfolio_id);
                    $query .= " AND pt.portfolio_id = " . $portfolio_id;
                }
            }

            $year = $this->request->getData('year');
            if ($year !== '0') {
                $conditions = Helpers::arrayPushAssoc($conditions, 'PdlrTransaction.sampled_year', $year);
                $query .= " AND pt.sampled_year = " . $year;
            }

            $month = $this->request->getData('month');
            if ($month !== '0') {
                $conditions = Helpers::arrayPushAssoc($conditions, 'PdlrTransaction.sampled_month', $month);
                $query .= " AND pt.sampled_month = " . $month;
            }

            $type = $this->request->getData('type');
            if ($type !== '0') {
                $type = $types[$type];
                $conditions = Helpers::arrayPushAssoc($conditions, 'PdlrTransaction.sampled', $type);
                $query .= " AND pt.sampled = '" . $type . "'";
            }

            $finding = $this->request->getData('finding');

            if ($finding === '1') {
                $conditions = Helpers::arrayPushAssoc($conditions, 'PdlrTransaction.sampling_finding IS', null);
                $query .= " AND pt.sampling_finding IS NULL ";
            } elseif ($finding === '2') {
                $conditions = Helpers::arrayPushAssoc($conditions, 'PdlrTransaction.sampling_finding IS NOT', null);
                $query .= " AND pt.sampling_finding IS NOT NULL ";
            }

            $query_php = $this->PdlrTransaction->find('all', [
                'conditions' => [$conditions]
            ]);
            $results = $query_php->toArray();

            if (empty($results)) {
                error_log("no results on manual_PD_sampling : " . $query);
                $this->Flash->error("There are no Payment Demands matching the selected criteria");
            } else {
                $params = [
                    'product_id'     => $product_id,
                    'portfolio_id'   => $portfolio_id,
                    'year'           => $year,
                    'month'          => $month,
                    'sampling_type'  => $type,
                    'sample_finding' => $finding,
                    'query'          => $query,
                ];

                $sasResult = $this->SAS->curlWithId('non_random_sampled.sas', $params);
                $this->logDams('Manual PD Sampling: ' . json_encode($params), 'dams', 'Manual PD sampling');
                error_log("non_random_sampled.sas called and returned " . $sasResult['value']);

                if (preg_match('/This request completed with errors/', $sasResult['value'])) {
                    $this->Flash->error(__('Sas request error, please contact support.') . '(' . $sasResult['id'] . ')');
                }
                $dom = HtmlDomParser::str_get_html($sasResult['value']);

                $table = $dom->find('table');
                $result = '';
                foreach ($table as $t) {
                    if ($t->id == 'sasres') {
                        $t->class = 'table table-bordered table-striped';
                        $t->frame = '';
                        $result .= $t->outertext;
                    }
                }

                $result = DownloadLib::change_downloadable_links($result, 'sampling/error');
                if (!empty($result)) {
                    $this->Flash->success('The file was successfully generated!');
                }
                $this->set('result', $result);
            }
        }
    }

    /**
     * function num_check_sas
     * DAMS 473
     * @return array
     */
    private function num_check_sas($data)
    {
        @$this->validate_param('array', $data);
        $sasResult = $this->SAS->curl(
                "import_file_check.sas", $data,
                false,
                false
        );
        if (strpos($sasResult, "This request completed with errors.") !== false) {
            $this->Flash->warning("The excel file check failed. Please contact the SAS support.");
            error_log("numcheck failed : " . json_encode($data));
            return array(); //just ignore the check
        } else {
            error_log("analyseSasResultofNumCheck params : " . json_encode($data['report_id']) . ", " . json_encode($data['version']));
            return $this->Spreadsheet->analyseSasResultofNumCheck($data['report_id'], $data['version']);
        }
    }

    /**
     * View method
     *
     * @param string|null $id Sampling Evaluation id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $samplingEvaluation = $this->SamplingEvaluation->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('samplingEvaluation'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $samplingEvaluation = $this->SamplingEvaluation->newEmptyEntity();
        if ($this->request->is('post')) {
            $samplingEvaluation = $this->SamplingEvaluation->patchEntity($samplingEvaluation, $this->request->getData());
            if ($this->SamplingEvaluation->save($samplingEvaluation)) {
                $this->Flash->success(__('The sampling evaluation has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sampling evaluation could not be saved. Please, try again.'));
        }
        $this->set(compact('samplingEvaluation'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sampling Evaluation id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $samplingEvaluation = $this->SamplingEvaluation->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $samplingEvaluation = $this->SamplingEvaluation->patchEntity($samplingEvaluation, $this->request->getData());
            if ($this->SamplingEvaluation->save($samplingEvaluation)) {
                $this->Flash->success(__('The sampling evaluation has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sampling evaluation could not be saved. Please, try again.'));
        }
        $this->set(compact('samplingEvaluation'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sampling Evaluation id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $samplingEvaluation = $this->SamplingEvaluation->get($id);
        if ($this->SamplingEvaluation->delete($samplingEvaluation)) {
            $this->Flash->success(__('The sampling evaluation has been deleted.'));
        } else {
            $this->Flash->error(__('The sampling evaluation could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

}
