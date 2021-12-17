<?php

declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use KubAT\PhpSimple\HtmlDomParser;
use Cake\Cache\Cache;

class SASComponent extends Component
{

    public $os;
    public $projet;
    public $server;
    public $broker;
    public $dispatcher;
    public $service;
    public $libname;
    public $logtype;
    public $adminpw;
    private $timeout = 60;
    private $running_filename = "/var/www/html/data/running_sas/running.log";
    private $monothread_scripts = array('Mandate_performance.sas', 'ActivePortfolio_Man.sas', 'Maturity_Breakdown_FLPG.sas', 'Portfolio_Volume_Report.sas', 'Maturity_Breakdown.sas',
        'SMEiTransaction_Coll_Stats.sas', 'MIBOSDS_cash_flow.sas', 'MIBOSDS_Responsible_Officer.sas', 'portfolio_data.sas', 'inclusion_status_report.sas',
        'start_up.sas', 'key_fields_report.sas', 'defaults_analysis.sas', 'generic_data_export.sas', 'seasonality_report.sas', 'fo_report.sas');

    private function sanitizeFilename($string)
    {
        $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
        $string = preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', $string);
        $string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
        $string = preg_replace(array('~[^0-9a-z]~i', '~[ -]+~'), ' ', $string);
        $string = str_replace(' ', '', $string);
        //$string = preg_replace('\t', '', $string);

        return trim($string, ' -');
    }

    public function curl($aprog, $aparam = array(), $adebug = false, $abackground = false) {
        return $this->curlWithId($aprog, $aparam, $adebug, $abackground)['value'];
    }

    public function curlWithId($aprog, $aparam = array(), $adebug = false, $abackground = false)
    {
        ob_start();
        /* NEW call Torben */

        $no_rerun = false;
        $sasOutputdir = "/tmp/"; /* Make sure to clean up this directory frequently */
        $requestId = "request-" . $this->guidv4();
        $result = '';

        $serial_data = array('prog' => $aprog, 'params' => $aparam);
        $serial = md5($this->sanitizeFilename(serialize($serial_data)));
        $double_run_id = Cache::read('sas_viya_running_' . $serial, 'damsv2');
        if (!empty($double_run_id)) {
            $time = time();
            $max_time = time() + 60 * 60;
            while ((time() < $max_time) && !empty(Cache::read('sas_viya_running_' . $serial, 'damsv2'))) {
                sleep(10);
            }
            $no_rerun = true;
            $requestId = $double_run_id; //re-use previous run



            clearstatcache(true);
            if (file_exists($sasOutputdir . $requestId . ".html")) {
                $myfile = fopen($sasOutputdir . $requestId . ".html", "r");
                $result = fread($myfile, filesize($sasOutputdir . $requestId . ".html"));
                fclose($myfile);
            } else {
                error_log("could not read result file for SAS call " . $sasOutputdir . $requestId . ".html");
                error_log(ob_get_contents());
                ob_end_clean();
                return ['value' => "<span id='sasres'><h1>This request completed with errors.</h1></span>", 'id'=>$requestId];
            }

            if (file_exists($sasOutputdir . $requestId . ".log")) {
                // reading log file to get the sas log file
                // searching for "proc printto log="/logapp/sas/projects/damsv2/apv_breakdown_report_19084.log" new;"
                $search_command = "cat " . $sasOutputdir . $requestId . ".log " . " 2>>" . $sasOutputdir . $requestId . ".err |grep 'proc printto log='";
                $output_logs = null;
                $retval_logs = null;
                $result_exec_logs = exec($search_command, $output_logs, $retval_logs);
                if (!empty($output_logs)) {//if success
                    // sas log file in $output_logs[0] : ["MPRINT(PORTFOLIO_STATS):   proc printto log=\\"\\/logapp\\/sas\\/projects\\/damsv2\\/portfolio_stats_1617.log\\" new;"]
                    $search_text = str_replace('\\', '', $output_logs[0]);

                    //$regex_sas_log_file = '/proc printto log="([-"]*)" new;/';
                    $regex_sas_log_file = '/proc printto log="([\/_a-zA-Z0-9\.]*)" new;/'; // /logapp/sas/projects/damsv2/portfolio_stats_1617.log
                    $match_logs = array();
                    $regex_sas_log_file_result = preg_match($regex_sas_log_file, $search_text, $match_logs);
                    if ($regex_sas_log_file_result !== false) {
                        $sas_log_file_path = $match_logs[1];
                        $search_command_error = "cat " . $sas_log_file_path . " 2>>" . $sasOutputdir . $requestId . ".err". " |grep 'ERROR: '";
                        $output_logs_error = null;
                        $retval_logs_error = null;
                        $result_exec_logs_error = exec($search_command_error, $output_logs_error, $retval_logs_error);
                        if (!empty($output_logs_error)) {
                            $result = "<span id='sasres'><h1>This request completed with errors:</h1><br />" . implode('<br />', $output_logs_error) . "</span>";
                            error_log(ob_get_contents());
                            ob_end_clean();
                            return ['value' => $result, 'id'=>$requestId];
                        }
                    }
                }
            }
        } else {
            Cache::write('sas_viya_running_' . $serial, $requestId, 'damsv2');
        }

        //$aparam['_ADMINPW'] = $this->adminpw;
        //$aparam['_program'] = $this->libname.".".$this->dispatcher;
        //$aparam['_service'] = $this->service;
        $aparam['sasfile'] = $aprog;
        $aparam['_program'] = '/Public/PHP/dispatcher';
        $aparam['project'] = 'Damsv2';
        //$aparam['usertype'] = $this->logtype;
        //$aparam['os'] = $this->os;
        //$aparam['nb_unik_sogeti'] = time();


        /* OLD call Torben */
        //$aparam['_ADMINPW'] = $this->adminpw;
        /* $aparam['_username'] = '!trVBOF.5EEg5AIcG3tu0S.';
          $aparam['_password'] = '!jQBPRlW2YaI3v0XQsU4nC1'; */
        //$aparam['_program'] = $this->libname.".".$this->dispatcher;
        //$aparam['_service'] = $this->service;
        // $aparam['sasfile'] = $aprog;
        //$aparam['_program'] = '/Public/PHP/dispatcher&project=Damsv2&sasfile=import_file_edit.sas';//'/Public/DAMS/'.$aprog;
        // $aparam['project'] = $this->projet;
        //$aparam['usertype'] = $this->logtype;
        //$aparam['os'] = $this->os;
        //$aparam['nb_unik_sogeti'] = time();
        $background_validation = false;
        if (isset($aparam['background_validation'])) {
            $background_validation = true;
            unset($aparam['background_validation']);
        }

        /* foreach ($aparam as $key => &$val) {
          if (is_array($val)) $val = implode(',', $val);
          if (!is_string($val)) $val = ''.$val;
          } */



        $sasbin = '/var/www/html/php/app/webroot/dispatcher.sh';
        $initstmt = " %let webout=" . $sasOutputdir . $requestId . ".html; ";
        foreach ($aparam as $key => &$val) {
            if (is_array($val)) {
                $val = implode(',', $val);
            }
            if (is_string($val)) {
                $val = escapeshellcmd($val);
            }
            $initstmt .= " %let " . $key . "=" . $val . "; ";
        }

        /* Generate command to launch SAS  */
        $sascmd = $sasbin . " -log " . $sasOutputdir . $requestId . ".log" . ' -initstmt "' . $initstmt . '"';


        if (!$no_rerun) {
            if ($abackground) {
                error_log("background call for " . $sascmd);
                $date = strtotime("now");
                $tmpfile = '/tmp/' . $aprog . '_' . $date;
                $cmd = $sascmd;
                //create temporary file to know the pid and the created time 
                if ($background_validation) {
                    $cmd = "/var/www/html/php/app/Console/cake Damsv2.sas_process_validation " . $aparam['report_id'] . " " . $aparam['template_type_id'] . " " . $aparam['user_id'];
                    $shell = "/var/www/html/data/work_list/" . $aprog . '_' . $date;
                    error_log("background process : " . $shell . " " . $cmd);
                } elseif (isset($aparam['report_id'])) {
                    $tmpfile = '/tmp/' . $aprog . '_' . $aparam['report_id'] . '_' . strtotime("now");
                    $cmd = $cmd . " > /dev/null & echo $! > " . $tmpfile;
                    $shell = "/var/www/html/data/work_list/" . $aprog . '_' . $date;
                } elseif (isset($aparam['list_auto_rep'])) {
                    $tmpfile = '/tmp/' . $aprog . '_' . str_replace("$$", "--", $aparam['list_auto_rep']) . '_' . strtotime("now");
                    $cmd = $cmd . " > /dev/null & echo $! > " . $tmpfile;
                    $shell = "/var/www/html/data/work_list/" . $aprog . '_' . $date;
                }

                file_put_contents($shell, $sascmd, FILE_APPEND);
            } else {
                //error_log("sas instantiate curl request: line ".__LINE__);
                set_time_limit(0);


                error_log("php call : " . $sascmd . "  " . date("m/d/Y h:i:s", time()));
                /* Call SAS. 

                  After execution the generated HHTML will be avaliable in
                  $sasOutputdir.$requestId.".html"
                 */
                $output = null;
                $retval = null;
                $sascmd = "sudo -u cakephp@eif.org " . $sascmd . " 2>>" . $sasOutputdir . $requestId . ".err";
                //$sascmd = escapeshellcmd($sascmd);

                try {
                    $result_exec = exec($sascmd, $output, $retval);
                } catch (Exception $e) {
                    error_log("error execution shell script " . $sascmd . " ||  (" . $e->getMessage() . ") " . date("m/d/Y h:i:s", time()));
                }
                //echo "Returned with status $retval\n"; /* $retval will be 0,1 if execution where successfull */
                error_log("end execution shell script " . $sascmd . " ||  (" . json_encode($output) . ") [" . json_encode($retval) . "] " . date("m/d/Y h:i:s", time()));

                @Cache::delete('sas_viya_running_' . $serial, 'damsv2');
                clearstatcache(true);
				
				$output_sas = $sasOutputdir . $requestId . ".html";
				if (file_exists($sasOutputdir . 'new_' . $requestId . ".html"))
				{
					$output_sas = $sasOutputdir . 'new_' . $requestId . ".html";
				}
				if (file_exists($output_sas)) {
					$myfile = fopen($output_sas, "r");
					$result = fread($myfile, filesize($output_sas));
                    fclose($myfile);
                } else {
                    error_log("could not read result file for SAS call " . $sasOutputdir . $requestId . ".html");
                    error_log(ob_get_contents());
                    ob_end_clean();
                    return ['value'=>"<span id='sasres'><h1>This request completed with errors.</h1></span>", 'id'=>$requestId];
                }

                if (file_exists($sasOutputdir . $requestId . ".log")) {
                    // reading log file to get the sas log file
                    // searching for "proc printto log="/logapp/sas/projects/damsv2/apv_breakdown_report_19084.log" new;"
                    $search_command = "cat " . $sasOutputdir . $requestId . ".log " . " 2>>" . $sasOutputdir . $requestId . ".err" . "|grep 'proc printto log='";
                    $output_logs = null;
                    $retval_logs = null;
                    $result_exec_logs = exec($search_command, $output_logs, $retval_logs);
                    if (!empty($output_logs)) {//if success
                        // sas log file in $output_logs[0] : ["MPRINT(PORTFOLIO_STATS):   proc printto log=\\"\\/logapp\\/sas\\/projects\\/damsv2\\/portfolio_stats_1617.log\\" new;"]
                        $search_text = str_replace('\\', '', $output_logs[0]);

                        //$regex_sas_log_file = '/proc printto log="([-"]*)" new;/';
                        $regex_sas_log_file = '/proc printto log="([\/_a-zA-Z0-9\.]*)" new;/'; // /logapp/sas/projects/damsv2/portfolio_stats_1617.log
                        $match_logs = array();
                        $regex_sas_log_file_result = preg_match($regex_sas_log_file, $search_text, $match_logs);
                        if (($regex_sas_log_file_result !== false)&& !empty($match_logs[1])) {
                            $sas_log_file_path = $match_logs[1];
                            $search_command_error = "cat " . $sas_log_file_path . " 2>>" . $sasOutputdir . $requestId . ".err" . " |grep 'ERROR: '";
                            $output_logs_error = null;
                            $retval_logs_error = null;
                            $result_exec_logs_error = exec($search_command_error, $output_logs_error, $retval_logs_error);
                            if (!empty($output_logs_error)) {
                                $result = "<span id='sasres'><h1>This request completed with errors:</h1><br />" . implode('<br />', $output_logs_error) . "</span>";
                                error_log(ob_get_contents());
                                ob_end_clean();
                                return ['value'=>$result, 'id'=>$requestId];
                            }
                        }
                    }
                }
            }//end not background
        }//end no rerun
        error_log("SAS results (" . $sascmd . ") : " . $result);

        @Cache::delete('sas_viya_running_' . $serial, 'damsv2');

        if (substr($result, 0, 3) == "\xef\xbb\xbf") {
            $result = substr($result, 3);
        }
        if (substr($result, -3) == "\xef\xbb\xbf") {
            $result = substr($result, 0, -3);
        }

        $dom = HtmlDomParser::str_get_html($result);
        if (!empty($dom)) {
			$type_errors = $dom->find('#valid_type');
			$type_stop = false;
			foreach ($type_errors as $type_error) {
				if ($type_error->innertext != ' OK ') {
					$result = $type_error->outertext;
					//$type_stop = true;
				}
				$type_error->outertext = '';
			}
			if ($type_stop == false) {
				$error = $dom->find('.jobexec_message_text, iframe');
				if (count($error) > 0) {
					file_put_contents("/tmp/sas_error_" . time(), $result);
					$result = "<span id='sasres'><h1>This request completed with errors.</h1></span>";
				} else {
					$scripts = $dom->find('script');
					foreach ($scripts as $node) {
						$node->outertext = '';
					}
					$result = $dom->outertext;
				}
			}
        }

        error_log(ob_get_contents());
        ob_end_clean();
        return ['value'=>$result, 'id'=>$requestId];
    }

    public function guidv4()
    {
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public function is_already_running($url, $action)
    {
        if (in_array($action, $this->monothread_scripts)) {
            //check cache
            if (!empty(Cache::read('report_analytics_' . $action, 'damsv2'))) {
                return true;
            }
        }
        $url = $this->delete_timestamp_url($url);
        $running = file_get_contents($this->running_filename);
        $urls = explode('||', $running);
        return in_array($url, $urls);
    }

    public function set_running($url, $action)
    {
        $url = $this->delete_timestamp_url($url);
        $running = file_get_contents($this->running_filename);
        $running = $running . "||" . $url;
        if (in_array($action, $this->monothread_scripts)) {
            //set cache
            Cache::write('report_analytics_' . $action, 'running', 'damsv2');
        }
        file_put_contents($this->running_filename, $running);
    }

    public function set_finished_running($url, $action)
    {
        $url = $this->delete_timestamp_url($url);
        $running = file_get_contents($this->running_filename);
        $urls = explode('||', $running);
        $urls = array_diff($urls, array($url));
        $running = implode('||', $urls);
        if (in_array($action, $this->monothread_scripts)) {
            //set cache
            Cache::delete('report_analytics_' . $action, 'running', 'damsv2');
        }
        file_put_contents($this->running_filename, $running);
    }

    private function delete_timestamp_url($url)
    {
        $url_exp = explode('&', $url);
        array_pop($url_exp);
        array_pop($url_exp);
        return implode('&', $url_exp);
    }

    public function get_cached_content($key, $config = 'default', $saspgr, $params, $force = false)
    {
        $content = Cache::read($key, $config);
        //error_log("cache value for ".$key." : ".serialize($written));
        if (!$content OR $force) {
            if (!empty($_GET['cachedebug'])) {
                die('>>> SAS CACHE NOT FOUND FOR ' . $saspgr);
            }
            $content = $this->curl($saspgr, $params, false);
            $written = Cache::write($key, $content, $config);
            //error_log("cache written after sas request : ".serialize($written));
        } else {
            if (!empty($_GET['cachedebug'])) {
                die('>>> SAS CACHE FOUND FOR ' . $saspgr);
            }
        }

        return $content;
    }

    //Temporary function to pasrse _webout and get the i-th table, which assumes that the php developer knows how many tables
    // there are in the SAS _webout
    public function get_ith_table_from_webout($res, $i)
    {
        $search = preg_match_all('#<table [^>]*>(.+?)</table>#is', $res, $tables);
        if ($search And $i < count($tables[1])) {
            $weboutTable = $tables[1][$i];
        } else {
            $weboutTable = '<tr class="alert-error"><td>There was an error when trying to
                display this content, please contact the administrator</td></tr>';
        }
        return $weboutTable;
    }

    // Temporary function, get all tables from _webout (and only tables, other contents will be ignored)
    public function get_all_tables_from_webout($res)
    {
        $search = preg_match_all('#<table [^>]*>(.+?)</table>#is', $res, $tables);
        if ($search) {
            $weboutTables = $tables[1];
        } else {
            $weboutTables = array('<tr class="alert-error"><td>There was an error when trying to
                display this content, please contact the administrator</td></tr>');
        }
        return $weboutTables;
    }

}
