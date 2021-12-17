<?php

declare(strict_types=1);

namespace Treasury\Console;

App::uses('Shell', 'Console');
App::uses('UniformLib', 'Lib');
App::uses('CurrencyLib', 'Lib');
class RatingUpdateShell extends Shell
{

	//public $uses = array('Treasury.Rating', 'Treasury.Counterparty');
	public $tasks = array('ResetOwner');
	public $fail_interval = 660; //11 min
	public $fail_interval_limit = 360; //6 min

	public function main($debug = false)
	{
		$url = '';
		$updated = array();

		App::uses('CakeEmail', 'Network/Email');
		$Email = new CakeEmail();

		//subject: prepend server
		$prefix = '[TEST] ';
		$emailto = array('eifsas-support@eif.org');
		//$url = Configure::read('PiratUrl');
		$url = "https://sdk.gds.standardandpoors.com/gdssdk/rest/v2/clientservice.json";
		if ($this->args[0] == 'dev') {
			$prefix = '[VMD - TEST] ';
		} elseif ($this->args[0] == 'uat') {
			$prefix = '[VMU - TEST] ';
		} elseif ($this->args[0] == 'prod') {
			$prefix = '';
			$url = Configure::read('PiratUrlProd');
			$emailto = array('eifsas-support@eif.org', 'l.chavarri@eif.org', 'm.hickey@eif.org', 'd.berkhoff@eif.org', 'n.engelputzeder@eif.org', 'g.nicolay@eif.org');
		}

		$emailto = array('i.ribassin@eif.org');

		//debug curl: test if curl lib works better than file_get_content
		if (!empty($_GET['curl'])) {
			$curl = $this->get_data($url);
			debug('Arg:' . $this->args[0]);
			debug($url);
			debug($curl);
			die();
		}

		//basic auth
		$context = null;
		$auth_user = 'APIADMIN@EIF.ORG';
		$auth_password = 'sp_API2017?';

		$POST_data = array('inputRequests' => array('research_document_ids' => array(123, 456))); //array form
		$POST_data = array('research_document_ids' => array(12)); //array form

		$funcs = array();

		//$identifier = "RX306248";//bgl bnp paribas
		//$identifier = "RX483079";//abn amro bank NV
		//$identifier = "RX104092";//AB SEB
		//$identifier = "RX402748";//caceis
		//$identifier = "RX116373";//ing bank nv
		//$identifier = "RX106114";// HSBC france
		//$identifier = "RX324119";// KBC BANK NV
		//$identifier = "RX116603";// banque et caisse de letat luxembourg
		//$identifier = "RX102029";// royal bank of canada
		$identifier = "RX104102"; // societe generale


		$funcs[] = '{function:"GDSP",identifier:"' . $identifier . '",mnemonic:"ICR_FC_LONG_TERM_RATING"}';
		$funcs[] = '{function:"GDSP",identifier:"' . $identifier . '",mnemonic:"ICR_FC_LONG_TERM_RATING_DATE"}';
		$funcs[] = '{function:"GDSP",identifier:"' . $identifier . '",mnemonic:"ICR_FC_LONG_TERM_CWOL"}';
		$funcs[] = '{function:"GDSP",identifier:"' . $identifier . '",mnemonic:"ICR_FC_LONG_TERM_CWOL_DATE"}';
		$funcs[] = '{function:"GDSP",identifier:"' . $identifier . '",mnemonic:"ICR_FC_SHORT_TERM_CWOL"}';
		$funcs[] = '{function:"GDSP",identifier:"' . $identifier . '",mnemonic:"ICR_FC_SHORT_TERM_CWOL_DATE"}';
		$funcs[] = '{function:"GDSP",identifier:"' . $identifier . '",mnemonic:"ICR_FC_SHORT_TERM_RATING"}';
		$funcs[] = '{function:"GDSP",identifier:"' . $identifier . '",mnemonic:"ICR_FC_SHORT_TERM_RATING_DATE"}';

		/*$funcs[] = '{function:"GDSP",identifier:"'.$identifier.'",mnemonic:"FC_LONG_TERM_RATING"}';
		$funcs[] = '{function:"GDSP",identifier:"'.$identifier.'",mnemonic:"FC_LONG_TERM_RATING_DATE"}';
		$funcs[] = '{function:"GDSP",identifier:"'.$identifier.'",mnemonic:"FC_LONG_TERM_CWOL"}';
		$funcs[] = '{function:"GDSP",identifier:"'.$identifier.'",mnemonic:"FC_LONG_TERM_CWOL_DATE"}';
		$funcs[] = '{function:"GDSP",identifier:"'.$identifier.'",mnemonic:"FC_SHORT_TERM_CWOL"}';
		$funcs[] = '{function:"GDSP",identifier:"'.$identifier.'",mnemonic:"FC_SHORT_TERM_CWOL_DATE"}';
		$funcs[] = '{function:"GDSP",identifier:"'.$identifier.'",mnemonic:"FC_SHORT_TERM_RATING"}';
		$funcs[] = '{function:"GDSP",identifier:"'.$identifier.'",mnemonic:"FC_SHORT_TERM_RATING_DATE"}';*/


		/*$funcs[] = '{function:"GDSP",identifier:"KX1WK48MPD4Y2NCUIZ63",mnemonic:"ICR_FC_LONG_TERM_RATING"}';
		$funcs[] = '{function:"GDSP",identifier:"LEI:KX1WK48MPD4Y2NCUIZ63",mnemonic:"ICR_FC_LONG_TERM_RATING"}';
		$funcs[] = '{function:"GDSP",identifier:"GLEI:KX1WK48MPD4Y2NCUIZ63",mnemonic:"ICR_FC_LONG_TERM_RATING"}';*/
		//$funcs[] = '{function:"GDSP",identifier:"GLEI:KX1WK48MPD4Y2NCUIZ63",mnemonic:"ICR_FC_LONG_TERM_RATING"}';

		$params = "inputRequests={inputRequests:[" . implode(',', $funcs) . "]}";

		//$params = 'inputRequests={inputRequests:[{function:"GDSP",identifier:"IBM:",mnemonic:"IQ_TOTAL_REV",properties:{PERIODTYPE:"IQ_FQ"}},		{function:"GDSP",identifier:"WMT:",mnemonic:"IQ_TOTAL_REV",properties:{PERIODTYPE:"IQ_FQ"}},		{function:"GDSP",identifier:"IBM:",mnemonic:"IQ_TOTAL_REV",properties:{PERIODTYPE:"IQ_FQ-1"}},		{function:"GDSP",identifier:"WMT:",mnemonic:"IQ_TOTAL_REV",properties:{PERIODTYPE:"IQ_FQ-1"}},		{function:"GDSP",identifier:"IBM:",mnemonic:"IQ_TOTAL_REV",properties:{PERIODTYPE:"IQ_FQ-2"}},		{function:"GDSP",identifier:"WMT:",mnemonic:"IQ_TOTAL_REV",properties:{PERIODTYPE:"IQ_FQ-2"}},		{function:"GDSP",identifier:"IBM:",mnemonic:"IQ_TOTAL_REV",properties:{PERIODTYPE:"IQ_FQ-3"}},{function:"GDSP",identifier:"WMT:",mnemonic:"IQ_TOTAL_REV",properties:{PERIODTYPE:"IQ_FQ-3"}},{function:"GDSP",identifier:"00083BAB1",mnemonic:"ASK_PRICE"},{function:"GDSP",identifier:"00083BAB1",mnemonic:"ASK_PRICE",properties:{STARTDATE:"09/01/2015"}},{function:"GDST",identifier:"00083BAB1",mnemonic:"ASK_PRICE",properties:{STARTDATE:"08/26/2015",FREQUENCY:"D"}}]}';

		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $url);
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 0);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_handle, CURLOPT_USERAGENT, 'EIF Treasury');
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
		//curl_setopt($curl_handle, CURLOPT_CAINFO, "/var/www/html/php/app/Vendor/cert/0_eibca-ms.cer");
		curl_setopt($curl_handle, CURLOPT_HTTPGET, false);
		curl_setopt($curl_handle, CURLOPT_POST, true);
		curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $params);
		curl_setopt(
			$curl_handle,
			CURLOPT_HTTPHEADER,
			array(
				'ContentType: application/x-www-form-urlencoded',
				'Accept-Encoding: gzip,deflate',
				'Authorization: Basic ' . base64_encode($auth_user . ":" . $auth_password)
			)
		);
		$content = curl_exec($curl_handle);

		$err     = curl_errno($curl_handle);
		$errmsg  = curl_error($curl_handle);
		if ($err) {
			echo "\n" . $err;
		}
		if ($errmsg) {
			echo "\n" . $errmsg;
		}
		//echo 'Authorization: Basic '. base64_encode($auth_user.":".$auth_password);
		//echo "\n".$query;

		curl_close($curl_handle);

		echo "\nresults : " . $content . "\n";
		exit();

		if ($url && $content = file_get_contents($url, false, $context)) {
			if ($json = json_decode($content, true)) {

				echo json_encode($json, true);
				exit();
				//rows: sometimes they are in json['row'] sometimes elsewhere, depending on the environment... lets fix it!
				$rows = array();
				if (!empty($json['NS1:payload']['out:getEIFCounterpartyRatingsResponse']['row'])) $rows = $json['NS1:payload']['out:getEIFCounterpartyRatingsResponse']['row'];
				elseif (!empty($json['row'])) $rows = $json['row'];

				//debug output for check/uncheck tests
				if (!empty($_GET['summary'])) {
					debug($url);
					if (!empty($context) && !empty($auth_user)) {
						debug('Using BASIC auth: ' . $auth_user);
					}
					debug(count($rows) . ' counterparties provided: ');

					$list = array();
					if (!empty($rows)) foreach ($rows as $new) {
						$list[] = array('NUM_TIERS' => $new['NUM_TIERS'], 'NAME' => $new['NAME']);
					}
					debug($list);
					die();
				}
				//debug output for check/uncheck tests
				if (!empty($_GET['list'])) {
					debug($url);
					debug(count($rows) . ' counterparties provided: ');
					debug($rows);
					die();
				}

				if (!empty($rows)) foreach ($rows as $new) {
					if (!empty($new['NUM_TIERS'])) {
						//search for the related rating in the db
						$existing = $this->Rating->find('first', array('recursive' => -1, 'conditions' => array(
							'Rating.pirat_number' => $new['NUM_TIERS']
						)));
						//if we found the rating to update, or we are creating new ones...
						//if(!empty($existing) || !empty($_GET['create'])){
						if (empty($existing) || !empty($existing['Rating']['automatic'])) {
							$up = array('Rating' => array('automatic' => 1));

							if (!empty($new['NAME'])) $up['Rating']['pirat_cpty_name'] = $new['NAME'];
							if (!empty($new['NUM_TIERS_HOG']) && !is_array($new['NUM_TIERS_HOG']) && $new['NUM_TIERS_HOG'] != $new['NUM_TIERS']) $up['Rating']['mother_company'] = $new['NUM_TIERS_HOG'];
							if (!empty($new['ADDRESS'])) $up['Rating']['pirat_address'] = $new['ADDRESS'];
							if (!empty($new['COD_PAYS_LOCAL'])) $up['Rating']['pirat_country'] = $new['COD_PAYS_LOCAL'];
							if (!empty($new['OWN_FUNDS_AMOUNT']) && !is_array($new['OWN_FUNDS_AMOUNT']) && is_numeric($new['OWN_FUNDS_AMOUNT'])) {
								$up['Rating']['own_funds'] = $new['OWN_FUNDS_AMOUNT'] * 1000000; //own funds amount provided in MILLIONS
							}
							if (!empty($new['BS_DATE']) && !is_array($new['BS_DATE'])) $up['Rating']['bs_date'] = $this->dbdate($new['BS_DATE']);

							$updatedRating = false;
							if (!empty($new['LT_MOODYS_RATING']) && !is_array($new['LT_MOODYS_RATING'])) {
								$up['Rating']['LT-MDY'] = trim($new['LT_MOODYS_RATING']);
								$updatedRating = true;
							}
							if (!empty($new['LT_MOODYS_OUTLOOK']) && !is_array($new['LT_MOODYS_OUTLOOK'])) {
								$up['Rating']['LT-MDY_outlook'] = trim($new['LT_MOODYS_OUTLOOK']);
								$updatedRating = true;
							}
							if (!empty($new['LT_MOODYS_DATE']) && !is_array($new['LT_MOODYS_DATE'])) {
								$up['Rating']['LT-MDY_date'] = $this->dbdate($new['LT_MOODYS_DATE']);
								$updatedRating = true;
							}

							if (!empty($new['LT_FITCH_RATING']) && !is_array($new['LT_FITCH_RATING'])) {
								$up['Rating']['LT-FIT'] = trim($new['LT_FITCH_RATING']);
								$updatedRating = true;
							}
							if (!empty($new['LT_FITCH_OUTLOOK']) && !is_array($new['LT_FITCH_OUTLOOK'])) {
								$up['Rating']['LT-FIT_outlook'] = trim($new['LT_FITCH_OUTLOOK']);
								$updatedRating = true;
							}
							if (!empty($new['LT_FITCH_DATE']) && !is_array($new['LT_FITCH_DATE'])) {
								$up['Rating']['LT-FIT_date'] = $this->dbdate($new['LT_FITCH_DATE']);
								$updatedRating = true;
							}

							if (!empty($new['LT_SP_RATING']) && !is_array($new['LT_SP_RATING'])) {
								$up['Rating']['LT-STP'] = trim($new['LT_SP_RATING']);
								$updatedRating = true;
							}
							if (!empty($new['LT_SP_OUTLOOK']) && !is_array($new['LT_SP_OUTLOOK'])) {
								$up['Rating']['LT-STP_outlook'] = trim($new['LT_SP_OUTLOOK']);
								$updatedRating = true;
							}
							if (!empty($new['LT_SP_DATE']) && !is_array($new['LT_SP_DATE'])) {
								$up['Rating']['LT-STP_date'] = $this->dbdate($new['LT_SP_DATE']);
								$updatedRating = true;
							}

							if (!empty($new['LT_RATING']) && !is_array($new['LT_RATING'])) {
								$up['Rating']['LT-EIB'] = trim($new['LT_RATING']);
								$updatedRating = true;
							}
							if (!empty($new['LT_RATING_DATE']) && !is_array($new['LT_RATING_DATE'])) {
								$up['Rating']['LT-EIB_date'] = $this->dbdate($new['LT_RATING_DATE']);
								$updatedRating = true;
							}

							if (!empty($new['ST_MOODYS_RATING']) && !is_array($new['ST_MOODYS_RATING'])) {
								$up['Rating']['ST-MDY'] = trim($new['ST_MOODYS_RATING']);
								$updatedRating = true;
							}
							if (!empty($new['ST_MOODYS_OUTLOOK']) && !is_array($new['ST_MOODYS_OUTLOOK'])) {
								$up['Rating']['ST-MDY_outlook'] = trim($new['ST_MOODYS_OUTLOOK']);
								$updatedRating = true;
							}
							if (!empty($new['ST_MOODYS_DATE']) && !is_array($new['ST_MOODYS_DATE'])) {
								$up['Rating']['ST-MDY_date'] = $this->dbdate($new['ST_MOODYS_DATE']);
								$updatedRating = true;
							}

							if (!empty($new['ST_FITCH_RATING']) && !is_array($new['ST_FITCH_RATING'])) {
								$up['Rating']['ST-FIT'] = trim($new['ST_FITCH_RATING']);
								$updatedRating = true;
							}
							if (!empty($new['ST_FITCH_OUTLOOK']) && !is_array($new['ST_FITCH_OUTLOOK'])) {
								$up['Rating']['ST-FIT_outlook'] = trim($new['ST_FITCH_OUTLOOK']);
								$updatedRating = true;
							}
							if (!empty($new['ST_FITCH_DATE']) && !is_array($new['ST_FITCH_DATE'])) {
								$up['Rating']['ST-FIT_date'] = $this->dbdate($new['ST_FITCH_DATE']);
								$updatedRating = true;
							}

							if (!empty($new['ST_SP_RATING']) && !is_array($new['ST_SP_RATING'])) {
								$up['Rating']['ST-STP'] = trim($new['ST_SP_RATING']);
								$updatedRating = true;
							}
							if (!empty($new['ST_SP_OUTLOOK']) && !is_array($new['ST_SP_OUTLOOK'])) {
								$up['Rating']['ST-STP_outlook'] = trim($new['ST_SP_OUTLOOK']);
								$updatedRating = true;
							}
							if (!empty($new['ST_SP_DATE']) && !is_array($new['ST_SP_DATE'])) {
								$up['Rating']['ST-STP_date'] = $this->dbdate($new['ST_SP_DATE']);
								$updatedRating = true;
							}

							if (!empty($new['ST_RATING']) && !is_array($new['ST_RATING'])) {
								$up['Rating']['ST-EIB'] = trim($new['ST_RATING']);
								$updatedRating = true;
							}
							if (!empty($new['ST_RATING_DATE']) && !is_array($new['ST_RATING_DATE'])) {
								$up['Rating']['ST-EIB_date'] = trim($new['ST_RATING_DATE']);
								$updatedRating = true;
							}

							//if "extract" GET paramater, just show an extract
							if (!empty($_GET['extract']) && !empty($updatedRating)) {
								foreach ($new as $key => $val) {
									print $key . ' : ';
									if (!is_array($val)) print $val;
									else print '-';
									print '<br>';
								}
								die();
							}

							//if we are not creating a new entry OR this new entry has at least one rating...
							if (empty($_GET['update']) || !empty($updatedRating)) {
								if (!empty($new['EU_CENTRAL_BANK'])) {
									if ($cpty = $this->Counterparty->find('first', array('recursive' => -1, 'conditions' => array(
										'pirat_number' => $new['NUM_TIERS']
									)))) {
										if ($new['EU_CENTRAL_BANK'] != $cpty['Counterparty']['eu_central_bank']) {
											$cptyup = array('Counterparty' => array('cpty_ID' => $cpty['Counterparty']['cpty_ID']));
											$cptyup['Counterparty']['eu_central_bank'] = $new['EU_CENTRAL_BANK'];
											if ($cptyupdated = $this->Counterparty->save($cptyup)) {
												$eucentralupdated = true;
											}
										}
									}
								}

								//check if values has changed since the one in database
								if (!empty($up['Rating'])) foreach ($up['Rating'] as $key => $val) {
									if (isset($existing['Rating'][$key])) {
										//if its a date, convert them to match
										if (strpos($key, 'date') !== false) {
											$date = strtotime($val);
											$val = date('d/m/Y', $date);
										}
										if ($key == 'own_funds') {
											if (abs(round($existing['Rating'][$key]) - $val) < 0.00001) {
												unset($up['Rating'][$key]);
											}
										} elseif ($existing['Rating'][$key] == $val) {
											unset($up['Rating'][$key]);
										}
									}
								}

								//if there are changes, update (and then calculation and notification will be triggered)
								if (!empty($up['Rating'])) {
									$up['Rating']['pirat_number'] = $new['NUM_TIERS'];
									if (!empty($existing['Rating']['id'])) {
										$status = 'updating Rating #' . $existing['Rating']['id'] . ' / Pirat #' . $new['NUM_TIERS'];
										$up['Rating']['id'] = $existing['Rating']['id'];
									} else {
										$status = 'creating Rating for Pirat #' . $new['NUM_TIERS'];
										$this->Rating->create();
									}

									if (!empty($this->params['display'])) {
										print('<br>' . $status);
										//debug($up['Rating']);
									}

									//save it only if we are not creating, or if we are creating a rating with at least one rating data...
									$up['Rating']['server'] = $this->args[0];

									if ($saved = $this->Rating->save($up)) {
										$updated[] = $saved;
									}
								}
							}
						}
					}
				}
			}

			if (!empty($updated) || !empty($eucentralupdated)) {
				//trigger event to update all calculations & notifications
				$event = new CakeEvent('Model.Treasury.Rating.updated', $this, array());
				$this->Rating->getEventManager()->dispatch($event);
				$count = count($updated);
				if (!empty($eucentralupdated)) $eucentralupdated++;
				$status = 'Updated ' . $count . ' ratings';
			} else {
				$status = 'No rating requiring update (on ' . count($rows) . ' provided)';
			}
			$this->set_update_success();
		} else {
			echo "no connection";
			$this->set_update_failure();
			$status = 'Unable to contact EIB servers...';
			$emailto = array('igor.ribassin@eif.org');
			if (!empty($emailto)) {
				$Email->template('Treasury.rating_updatefail')
					->emailFormat('html')
					->from(array('eifsas-support@eif.org' => 'EIFSAS Platform'))
					->to($emailto)
					->subject($prefix . 'Treasury Rating Update FAILED')
					->viewVars(array('url' => $url));
				try {
					//@$Email->send();
				} catch (Exception $e) {
					if (!empty($this->params['display'])) print($e->getMessage());
				}
			}
		}

		if (!empty($this->params['display'])) print('<h2>' . $status . '</h2>');
		$this->ResetOwner->execute();
		return true;
	}

	public function set_update_failure()
	{

		if (file_exists('/tmp/rating_failure.log')) {
			$time = file_get_contents('/tmp/rating_failure.log');
			$time = intval($time);
			if (((time() - $time) > $this->fail_interval) && ((time() - $time) < ($this->fail_interval + $this->fail_interval_limit))) {
				//send notification failure
				if (!empty($emailto)) {
					$Email->template('Treasury.rating_updatefail_long')
						->emailFormat('html')
						->from(array('eifsas-support@eif.org' => 'EIFSAS Platform'))
						->to($emailto)
						->subject($prefix . 'Treasury Rating Update FAILED for more than once')
						->viewVars(array('time' => date("d/m/Y H:i", $time)));
					try {
						@$Email->send();
					} catch (Exception $e) {
						if (!empty($this->params['display'])) print($e->getMessage());
					}
				}
			}
		} else {
			//first fail
			$time = time();
			$msg = $time;
			file_put_contents('/tmp/rating_failure.log', $msg, FILE_APPEND);
		}
	}

	public function set_update_success()
	{
		if (file_exists('/tmp/rating_failure.log')) {
			$time = file_get_contents('/tmp/rating_failure.log');
			$time = intval($time);
			$duration = time() - $time;
			if ($duration > $this->fail_interval) {
				//send notification working again
				$msg = "Update rating service down for " . ($duration / 60) . " minutes (" . $duration . " seconds) is back.";
				file_put_contents('/tmp/rating_failure.log', $msg, FILE_APPEND);
				rename('/tmp/rating_failure.log', '/tmp/rating_failure' . $time . '.log');

				if (!empty($emailto)) {
					$Email->template('Treasury.rating_updatesuccess_long')
						->emailFormat('html')
						->from(array('eifsas-support@eif.org' => 'EIFSAS Platform'))
						->to($emailto)
						->subject($prefix . 'Treasury Rating Update works again')
						->viewVars(array('time' => $duration));
					try {
						@$Email->send();
					} catch (Exception $e) {
						if (!empty($this->params['display'])) print($e->getMessage());
					}
				}
			}
		}
	}

	public function dbdate($fulldate)
	{
		$date = strtotime($fulldate);
		return date('Y-m-d', $date);
	}
	/* gets the data from a URL */
	function get_data($url)
	{
		$ch = curl_init();
		$timeout = 30;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

		curl_setopt($ch, CURLOPT_POST, false);
		//curl_setopt($ch, CURLOPT_HEADER, false);

		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}


	public function getOptionParser()
	{
		$parser = parent::getOptionParser();
		$parser->addOption('display', array('short' => 'd', 'help' => 'Display in browser mode', 'boolean' => TRUE));
		return $parser;
	}
}
