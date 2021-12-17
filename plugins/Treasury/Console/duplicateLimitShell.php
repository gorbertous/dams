<?php

declare(strict_types=1);

namespace Treasury\Console;


App::uses('Shell', 'Console');
App::uses('UniformLib', 'Lib');
App::uses('CurrencyLib', 'Lib');
class DuplicateLimitsShell extends Shell
{

	public $uses = array('Treasury.MandateGroup', 'Treasury.Counterparty', 'Treasury.CounterpartyGroup');
	public $tasks = array('ResetOwner');
	public function main()
	{
		$results = array();
		$duplicates = $this->query("select `limit_ID`,`mandategroup_ID`,`counterpartygroup_ID`,`cpty_ID`, limit_date_to from limits WHERE limit_date_to is null group by `mandategroup_ID`,`counterpartygroup_ID`,`cpty_ID` HAVING count(limit_id) > 1");
		if (!empty($duplicates)) {
			foreach ($duplicates as $dup) {
				$mandategroup = $this->MandateGroup->find("first", array('conditions' => array("id" => $dup['limits']['mandategroup_ID'])));
				$counterparty = null;
				$counterpartygroup = null;
				if ($dup['limits']['cpty_ID'] == '0') {
					$counterpartygroup = $this->CounterpartyGroup->find("first", array('conditions' => array("counterpartygroup_ID" => $dup['limits']['counterpartygroup_ID'])));
				} else {
					$counterparty = $this->Counterparty->find("first", array('conditions' => array("cpty_ID" => $dup['limits']['cpty_ID'])));
				}
				$res = array("MandateGroup" => $mandategroup['MandateGroup']['mandategroup_name']);
				if (!empty($counterpartygroup)) {
					$res['CounterpartyGroup'] = $counterpartygroup['CounterpartyGroup']['counterpartygroup_name'];
				}
				if (!empty($counterparty)) {
					$res['Counterparty'] = $counterparty['Counterparty']['cpty_name'];
				}
				$request = "SELECT * FROM limits WHERE limit_date_to IS NULL AND mandategroup_ID=" . $dup['limits']['mandategroup_ID'] . " AND counterpartygroup_ID=" . $dup['limits']['counterpartygroup_ID'] . " AND cpty_ID=" . $dup['limits']['cpty_ID'];
				$res['request'] = $request;
				$results[] = $res;
			}

			$recipients = array("i.ribassin@eif.org");

			App::uses('CakeEmail', 'Network/Email');
			$Email = new CakeEmail();
			$Email->template('Treasury.duplicate_limit')
				->emailFormat('html')
				->from(array('no-reply@eifaws.com' => 'EIFSAS Platform'))
				->to($recipients)
				->subject($subject)
				->viewVars(array('results' => $results));
			try {
				@$Email->send();
			} catch (Exception $e) {
				print($e->getMessage());
			}
		}
	}

	public function getOptionParser()
	{
		$parser = parent::getOptionParser();
		$parser->addOption('display', array('short' => 'd', 'help' => 'Display in browser mode', 'boolean' => TRUE));
		return $parser;
	}
}
