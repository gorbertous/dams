<?php

declare(strict_types=1);

namespace Treasury\Console;

App::uses('Shell', 'Console');

App::uses('SASLib', 'Lib');

App::import('Vendor', 'PHPExcel');

class AutomaticFixingShell extends AppShell
{

	public $uses = array('Treasury.Transaction', 'Treasury.Compartment', 'Treasury.Reinvestment');
	public $components = array('Damsv2.Excel');

	public $tasks = array('ResetOwner');

	public $new_reinv = array();
	public $new_trn = array();

	public $objPHPExcel = null;
	public $line_rollover = 2;
	public $line_repayment = 2;
	public $line_reinv = 2;
	public $line_trn = 2;

	public function main()
	{
		$filepath = "/tmp/automatic_fixing.xlsx";

		$this->objPHPExcel = PHPExcel_IOFactory::load("/tmp/automatic_fixing.xlsx");

		$this->objPHPExcel->setActiveSheetIndex(0);
		$worksheet = $this->objPHPExcel->getActiveSheet();
		$objects = array();


		$month = 6;
		$year = 2016;
		echo ("automatic_fixing : " . $month . " / " . $year);
		//automatic fixing
		if (empty($month) || empty($year)) {
			return 0;
		}

		$selected_date = strtotime('last day of ' . $year . "-" . $month);
		$current_month = strtotime('last day of ' . date("15-m-Y")); //current month (end)
		$last_month = strtotime('-1 month ', $current_month); //last month (beginning)
		$last_month = strtotime('first day of ', $last_month); //last month (beginning)
		if (($selected_date < $last_month) || ($selected_date > $current_month)) {
			return array('wrong_period');
		}

		$month_matching_period = array(
			'monthly' => array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12), //every month
			'Quarterly' => array(3, 6, 9, 12),
			'Semi-annually' => array(6, 12),
			'Annually' => array(12)
		);


		$period = null;
		//automatic counterparties
		/*$ctpy_auto = $this->query("SELECT cpty_ID FROM counterparties WHERE automatic_fixing=1");
		$ctpy_automatic = array();
		foreach($ctpy_auto as $ctpy)
		{
			$ctpy_automatic[] = $ctpy['counterparties']['cpty_ID'];
		}*/

		//counterparties : bgl (65) & bcee (2)
		$ctpy_automatic = array(2, 65);
		$transactions_confirmed = $this->Transaction->find('all', array(
			'conditions' => array(
				'depo_type' => 'Callable',
				'tr_state'	=> 'Confirmed',
				'tr_type'	=> array('Deposit', 'Rollover'),
				'commencement_date < ' => date("Y-M-d", $selected_date),
				'Transaction.cpty_id' => $ctpy_automatic
			)
		));

		//$transactions_confirmed
		$fail = array();
		$success = array();
		foreach ($transactions_confirmed as $trn) {
			$interest_rate = $trn['Transaction']['interest_rate'];
			$tr_number = $trn['Transaction']['tr_number'];
			if (empty($interest_rate)) {
				$fail[] = $tr_number;
			}
			$capitalisation_frequency = $trn['Counterparty']['capitalisation_frequency'];


			//if (!empty($capitalisation_frequency))
			//{
			$Taxes = ClassRegistry::init('Treasury.Tax');
			$mandate_id = $trn['Transaction']['mandate_ID'];
			$cpty_id = $trn['Transaction']['cpty_id'];
			$tax_rates = $Taxes->getTaxByMandateCpty($mandate_id, $cpty_id);
			//echo (json_encode($tax_rates, true));

			if (empty($tax_rates)) {
				$tax_rate = 0.00;
			} else {
				$tax_rate = floatval($tax_rates['Tax']['tax_rate']) / 100;
			}

			$amount = $trn['Transaction']['amount'];
			$date_basis = $trn['Transaction']['date_basis'];
			$commencement_date = $trn['Transaction']['commencement_date'];
			$commencement_date = explode('/', $commencement_date);
			$commencement_date = $commencement_date[2] . "-" . $commencement_date[1] . "-" . $commencement_date[0];


			//fixing_date = last day of selected month
			$fixing_date = date("Y-m-d", $selected_date);
			$fixing_interest = $this->getInterest($amount, $fixing_date, $interest_rate, $date_basis, $commencement_date);
			$tax_fixing = $tax_rate * floatval($fixing_interest);

			$capitaliser = false; //fixing only by default
			if ($cpty_id == 2) //BCEE => capitalisation
			{
				$capitaliser = true;
			}
			if ($capitaliser) {
				//capitalisation process
				//capitalisation_date = first day of next month
				$capitalisation_date = date("Y-m-d", strtotime('+1 day', $selected_date));
				$capitalidation_interest = $this->getInterest($amount, $capitalisation_date, $interest_rate, $date_basis, $commencement_date);

				$tax_capitalisation = $tax_rate * floatval($capitalidation_interest);
				$this->fixing_capitalisation(false, $tr_number, $trn, $capitalisation_date, $tax_capitalisation, $capitalidation_interest, $fixing_date, $fixing_interest, $capitalidation_interest, $tax_fixing);
				$success[] = $tr_number;
			} else {
				//fixing process / no capitalisation
				$this->fixing_capitalisation(true, $tr_number, $trn, null, null, null, $fixing_date, $fixing_interest, null, $tax_fixing);
				$success[] = $tr_number;
			}
			/*}
				else
				{
					$fail[] = $tr_number;
				}*/
			/*}
			else
			{
				$fail[] = $tr_number;
			}*/
		}
		try {
			$objWriter = new PHPExcel_Writer_Excel2007($this->objPHPExcel);
			$objWriter->save($filepath);
		} catch (Exception $e) {
			error_log("excel file  could not be written : " . $e->getMessage());
			//$checks['errors'][] = "The file <strong>".basename($filepath)."</strong> could not be saved. Please contact the support team.";
		}
		unset($this->objPHPExcel);
		$this->ResetOwner->execute();
		//return array('success' => $success, 'fail' => $fail);
	}

	public function getInterest($amount, $date, $interest_rate, $date_basis, $commencement_date)
	{
		if ($interest_rate == null) {
			return 0;
		}
		$params = array(
			"amount"			=> $amount,
			"new_date"			=> $date,
			"interest_rate"		=> $interest_rate,
			"date_basis"		=> $date_basis,
			"commencement_date"	=> $commencement_date,
		);
		$interest_sasResult = SASLib::curl("register_confirmation_new.sas", $params, false);

		//echo " interest = ".$interest_sasResult." for ".json_encode($params, true)."\n";

		$interest_sasResult = mb_convert_encoding($interest_sasResult, "UTF-8"); // to remove \ufeff
		$interest_sasResult = trim($interest_sasResult); // to remove \r
		$interest_sasResult = preg_replace("/[^-0-9\,\.]/", '', $interest_sasResult);

		return $interest_sasResult;
	}



	/*
	 $capitalisation_date, $capitalisation_tax can be null if $no_capitalisation is true
	 
	*/
	public function fixing_capitalisation($no_capitalisation, $tr_number, $tr, $capitalisation_date, $tax = 0.00, $interest_capitalisation = null, $fixing_date, $fixing_interest, $capitalidation_interest = null, $tax_fixing = null)
	{
		$error_count = 0;

		// INIT: amount left calculation based on account scheme
		$int_cap = 0.00;
		/*if(!empty($interest_capitalisation)){
			$int_cap = implode('', explode(',', $this->request->data['intfixing']['interest_capitalisation']));
		}*/

		switch ($tr['Transaction']['scheme']) {
			case 'AA':
				$amount_leftA 			= (float) ($tr['Transaction']['amount'] + $int_cap - $tax);
				$amount_leftB 			= 0.00;
				$new_repayment 			= false;
				$amount 				= $amount_leftA;
				$rollover_source_fund	= 'A';
				break;
			case 'BB':
				$amount_leftA 			= 0.00;
				$amount_leftB 			= (float) ($tr['Transaction']['amount'] + $int_cap - $tax);
				$new_repayment 			= false;
				$amount 				= $amount_leftB;
				$rollover_source_fund	= 'B';
				break;
			case 'AB':
				$amount_leftA 			= (float) ($tr['Transaction']['amount']);
				$amount_leftB 			= (float) ($int_cap - $tax);
				$new_repayment 			= true;
				$amount 				= $amount_leftA;
				$amount_repay			= $amount_leftB;
				$rollover_source_fund	= 'A';
				$repay_source_fund		= 'B';
				break;
			default:
				echo ("Error in the Transaction scheme ID: $tr_number, please contact the administrator");
				$error_count += 1;
				return 0;
				break;
		}


		if ($error_count < 1) {
			if (!$no_capitalisation) {

				// Get compartment's accounts
				$accounts = $this->Compartment->find(
					'first',
					array(
						'conditions' => array(
							'Compartment.cmp_ID' => $this->Transaction->getAttribByTrn('cmp_ID', $tr_number)
						),
						'fields' => array(
							'Compartment.accountA_IBAN as accountA', 'Compartment.accountB_IBAN as accountB'
						),
					)
				);

				$accounts = array_shift($accounts);

				// STEP1: Creation of the reivestment group
				$this->Reinvestment->create();
				$this->Reinvestment->set('reinv_status', 'Open');
				$this->Reinvestment->set('mandate_ID', $tr['Transaction']['mandate_ID']);
				$this->Reinvestment->set('cmp_ID', $tr['Transaction']['cmp_ID']);
				$this->Reinvestment->set('cpty_ID', $tr['Transaction']['cpty_id']);
				$this->Reinvestment->set('availability_date', $capitalisation_date);
				$this->Reinvestment->set('accountA_IBAN', $accounts['accountA']);
				$this->Reinvestment->set('accountB_IBAN', $accounts['accountB']);
				$this->Reinvestment->set('amount_leftA', $amount_leftA);
				$this->Reinvestment->set('amount_leftB', $amount_leftB);
				$this->Reinvestment->set('reinv_type', 'Fixing');
				//$this->Reinvestment->save();
				//echo ("creation reinvestment ".json_encode($this->Reinvestment, true));
				$dat_reinv = array("reinvestment created") + array('Open', $tr['Transaction']['mandate_ID'], $tr['Transaction']['cmp_ID'], $tr['Transaction']['cpty_id'], $capitalisation_date, $accounts['accountA'], $accounts['accountB']);
				$reinv_id = -1;

				// STEP2: Creation of a rollover
				$this->Transaction->create();
				$data = array('Transaction' => array(
					'tr_type'			=> 'Rollover',
					'tr_state'			=> 'Confirmed',
					'depo_type'			=> 'Callable',
					'depo_renew'		=> $tr['Transaction']['depo_renew'],
					'rate_type'			=> $tr['Transaction']['rate_type'],
					'booking_status'	=> 'Not booked',
					'source_group'		=> $reinv_id,
					'interest_rate'		=> $tr['Transaction']['interest_rate'],
					'tax_ID'			=> $tr['Transaction']['tax_ID'],
					'date_basis'		=> $tr['Transaction']['date_basis'],
					'reference_rate'	=> $tr['Transaction']['reference_rate'],
					'original_id'		=> $tr['Transaction']['original_id'],
					'parent_id'			=> $tr_number,
					'amount'			=> $amount,
					'commencement_date'	=> $capitalisation_date,
					'accountA_IBAN'		=> $tr['Transaction']['accountA_IBAN'],
					'accountB_IBAN'		=> $tr['Transaction']['accountB_IBAN'],
					'scheme'			=> $tr['Transaction']['scheme'],
					'mandate_ID'		=> $tr['Transaction']['mandate_ID'],
					'cmp_ID'			=> $tr['Transaction']['cmp_ID'],
					'cpty_id'			=> $tr['Transaction']['cpty_id'],
					'source_fund'		=> $rollover_source_fund,
					'instr_num'			=> $tr['Transaction']['instr_num'],
				));
				//$this->Transaction->save($data);
				//echo ("creation transaction rollover : ".json_encode($data, true));
				$this->new_trn[] = (array)$data['Transaction'];
				$dat = array("rollover creation") + (array)$data['Transaction'];
				$this->write_excel($dat, $this->line_rollover, 1);
				$rollover_id = -1;
				if ($new_repayment) {
					$this->Transaction->create();
					$data = array(
						'Transaction' => array(
							'tr_type'			=> 'Repayment',
							'tr_state'			=> 'Confirmed',
							'source_group'		=> $reinv_id,
							'original_id'		=> $tr['Transaction']['original_id'],
							'parent_id'			=> $tr_number,
							'amount'			=> $amount_repay,
							'commencement_date'	=> $capitalisation_date,
							'maturity_date'		=> $capitalisation_date,
							'booking_status'	=> 'Not booked',
							'accountA_IBAN'		=> $tr['Transaction']['accountB_IBAN'],
							'accountB_IBAN'		=> $tr['Transaction']['accountB_IBAN'],
							'mandate_ID'		=> $tr['Transaction']['mandate_ID'],
							'cmp_ID'			=> $tr['Transaction']['cmp_ID'],
							'cpty_id'			=> $tr['Transaction']['cpty_id'],
							'source_fund'		=> $repay_source_fund,
						)
					);
					//$tab = array_merge($tr['Transaction'], $data['Transaction']);
					//$this->Transaction->save($data);
					//echo ("creation transaction repayment : ".json_encode($data, true));
					//$this->new_trn[] = $data['Transaction'];
					$dat = array("repayment creation") + $data['Transaction'];
					$this->write_excel($dat, $this->line_repayment, 2);
					$repayment_id = -1;
					//$this->log('Interest capitalised TRN '.$tr_number.' '.print_r($tr['Transaction'],true), 'treasury');
				}

				/*$event = new CakeEvent('Model.Treasury.Transaction.change', $this, array("transaction" => $this));
				$this->getEventManager()->dispatch($event);*/


				//$reinv = $this->Reinvestment->getRawsById($reinv_id);
				$reinv = (array) $this->Reinvestment;
				echo "\n" . json_encode($this->Reinvestment, true);
				//echo "\n".json_encode($this->Reinvestment, true);
				$reinv['data']['Reinvestment']['amount_leftA'] = 0.00;
				$reinv['data']['Reinvestment']['amount_leftB'] = 0.00;
				$reinv['data']['Reinvestment']['reinv_status'] = 'Closed';
				//$this->Reinvestment->save($reinv);
				//echo ("closing reinvestment : ".json_encode($reinv, true));
				//$this->new_reinv[] = (array)$reinv['Reinvestment'];
				$dat_reinv = $reinv['data']['Reinvestment'];
				//echo "\n".json_encode($dat_reinv, true);
				$this->write_excel($dat_reinv, $this->line_reinv, 3);
			}

			$this->Transaction->read(null, $tr_number);
			if (!$no_capitalisation) {
				$this->Transaction->set('tr_state', 'Reinvested');
				$this->Transaction->set('total_interest', $int_cap);
				$this->Transaction->set('reinv_group', $reinv_id);
				$this->Transaction->set('maturity_date', $capitalisation_date);
			}

			$this->Transaction->set('fixing_date', $fixing_date);
			$this->Transaction->set('eom_interest', $this->formatAmounts($fixing_interest));
			$this->Transaction->set('accrued_tax', $this->formatAmounts($tax_fixing));
			$this->Transaction->set('tax_amount', $tax);
			$this->Transaction->set('total_interest', $this->formatAmounts($capitalidation_interest));
			//$this->Transaction->save();
			//echo ("updating current transaction : ".json_encode($this->Transaction, true));
			$tr =  (array)$this->Transaction;
			//echo "   TRTRTR  : ".json_encode($tr, true);
			//$this->new_trn[] = $tr['data']['Transaction'];
			$dat_tr = array('fixed transaction') + $tr['data']['Transaction'];
			$this->write_excel($dat_tr, $this->line_trn, 0);


			//$this->set('_serialize', array('success'));
		} else {
			echo "***errors****";
		}
	}

	// This function if for amounts formatting. it's not a proper model function
	public function formatAmounts($amount)
	{
		return implode('', explode(',', $amount));
	}


	public function write_excel($row, &$line, $sheet)
	{
		$i = 0;

		$this->objPHPExcel->setActiveSheetIndex($sheet);
		foreach ($row as $column => $data) {
			$this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i++, $line, $data);
		}
		$line++;
	}


	public function key_contains($key, $contains)
	{
		foreach ($contains as $contain) {
			if (strpos(strtolower($key), strtolower($contain)) !== false) {
				return true;
			}
		}
		return false;
	}
}
