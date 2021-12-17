<?php

declare(strict_types=1);

namespace Treasury\Console;

App::uses('Shell', 'Console');
App::uses('UniformLib', 'Lib');
class InstructionNotificationShell extends Shell
{

	public $uses = array('Treasury.Instruction');
	public $tasks = array('ResetOwner');
	public function main($debug = false)
	{
		$instrlist = $this->Instruction->find('all', array(
			'conditions' => array(
				'instr_status !=' => "Rejected",
				'notify' => true,
				'notified' => 0,
				'notify_date' => date('Y-m-d')
			)
		));

		$counter = 0;
		if (!empty($instrlist)) foreach ($instrlist as $instr) {
			//send mail
			$basepath = '/var/www/html';
			$filepath = "/data/treasury/pdf/deposit_instruction_" . $instr['Instruction']['instr_num'] . ".pdf";
			if (substr(dirname(__FILE__), 0, 1) != '/') { //windows server: local?
				$basepath = 'C:\wamp\www\eif';
				$filepath = str_replace('/', '\\', $filepath);
			}

			$status = '- Send email notification for Instruction #' . $instr['Instruction']['instr_num'] . ' + ' . $basepath . $filepath;
			$this->out($status);

			if (!empty($this->params['display'])) {
				print '<br>' . $status;
				debug($instr);
			}

			$subject = 'Treasury: Deposit instruction notification #' . $instr['Instruction']['instr_num'] . ': ' . $instr['Mandate']['BU'] . '-' . $instr['Counterparty']['cpty_code'];
			$prefix = $sufix = $server = '';

			//detecting the current server
			if (!empty($_SERVER['HTTP_HOST'])) $server = $_SERVER['HTTP_HOST'];
			elseif (!empty($_SERVER['HOSTNAME'])) $server = $_SERVER['HOSTNAME'];
			elseif (!empty($_SERVER['windir'])) $server = 'localhost';

			$prefix = '[TEST] ';
			$recipients = array('eifsas-support@eif.org');
			if (EIFENV == 'dev' || $this->args[0] == 'dev') {
				$prefix = '[VMD - TEST] ';
			} elseif (EIFENV == 'uat' || $this->args[0] == 'uat') {
				$prefix = '[VMU - TEST] ';
			} elseif (EIFENV == 'prod' || $this->args[0] == 'prod') {
				$prefix = '';
				$recipients = array('eifsas-support@eif.org', 'eif-treasury@eif.org');
			}
			//$recipients = array('v.tissot@eif.org');
			$subject = $prefix . $subject;

			App::uses('CakeEmail', 'Network/Email');
			$Email = new CakeEmail();
			$Email->template('Treasury.di_notification')
				->emailFormat('html')
				->from(array('eifsas-support@eif.org' => 'EIFSAS Platform'))
				->to($recipients)
				->subject($subject)
				->viewVars(array('instr' => $instr));
			if (file_exists($basepath . $filepath)) $Email->attachments($basepath . $filepath);

			try {
				@$Email->send();
			} catch (Exception $e) {
				if (!empty($this->params['display'])) print($e->getMessage());
			}

			$this->Instruction->save(array('Instruction' => array(
				'instr_num' => $instr['Instruction']['instr_num'],
				'notified' => time()
			)));
			$counter++;
		}

		//end message
		$endstatus = 'END: ' . $counter . ' notification(s) sent';
		if (!empty($this->params['display'])) print '<br>' . $endstatus;
		$this->out($endstatus);
		$this->ResetOwner->execute();
		return true;
	}

	public function getOptionParser()
	{
		$parser = parent::getOptionParser();
		$parser->addOption('display', array('short' => 'd', 'help' => 'Display in browser mode', 'boolean' => TRUE));
		return $parser;
	}
}
