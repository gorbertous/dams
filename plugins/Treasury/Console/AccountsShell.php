<?php

declare(strict_types=1);

namespace Treasury\Console;

App::uses('Shell', 'Console');
App::uses('UniformLib', 'Lib');
App::uses('CurrencyLib', 'Lib');

class AccountsShell extends Shell
{

	public $uses = array('Treasury.Transaction', 'Treasury.Account', 'Treasury.Compartment', 'Treasury.Reinvestment');
	public $tasks = array('ResetOwner');
	public function main()
	{

		// TODO : execute first the 3 requests below (once)
		//  ALTER TABLE `transactions` ADD `accountA_IBAN` VARCHAR(50) NULL AFTER `accountB_ID`, ADD `accountB_IBAN` VARCHAR(50) NULL AFTER `accountA_IBAN`;
		//  ALTER TABLE `reinvestments` ADD `accountA_IBAN` VARCHAR(50) NULL AFTER `accountB_ID`, ADD `accountB_IBAN` VARCHAR(50) NULL AFTER `accountA_IBAN`;
		//  ALTER TABLE `compartments` ADD `accountA_IBAN` VARCHAR(50) NULL AFTER `accountB_ID`, ADD `accountB_IBAN` VARCHAR(50) NULL AFTER `accountA_IBAN`;

		$accounts = $this->Account->find('list', array(
			'fields' => array('account_ID', 'IBAN')
		));

		//updating trn:
		$trns = $this->Transaction->find('all', array('fields' => array('tr_number', 'accountA_ID', 'accountB_ID')));
		foreach ($trns as $key => $tr) {
			if (!empty($tr['Transaction']['accountA_ID']))
				$tr['Transaction']['accountA_IBAN'] = $accounts[$tr['Transaction']['accountA_ID']];

			if (!empty($tr['Transaction']['accountB_ID']))
				$tr['Transaction']['accountB_IBAN'] = $accounts[$tr['Transaction']['accountB_ID']];

			$this->Transaction->save($tr);
		}

		// update compartments
		$cmps = $this->Compartment->find('all', array('fields' => array('cmp_ID', 'accountA_ID', 'accountB_ID')));
		foreach ($cmps as $key => $tr) {
			if (!empty($tr['Compartment']['accountA_ID']))
				$tr['Compartment']['accountA_IBAN'] = $accounts[$tr['Compartment']['accountA_ID']];

			if (!empty($tr['Compartment']['accountB_ID']))
				$tr['Compartment']['accountB_IBAN'] = $accounts[$tr['Compartment']['accountB_ID']];

			$this->Compartment->save($tr);
		}

		// update Reinvestment
		$rvt = $this->Reinvestment->find('all', array('fields' => array('reinv_group', 'accountA_ID', 'accountB_ID')));
		foreach ($rvt as $key => $tr) {
			if (!empty($tr['Reinvestment']['accountA_ID']))
				$tr['Reinvestment']['accountA_IBAN'] = $accounts[$tr['Reinvestment']['accountA_ID']];

			if (!empty($tr['Reinvestment']['accountB_ID']))
				$tr['Reinvestment']['accountB_IBAN'] = $accounts[$tr['Reinvestment']['accountB_ID']];

			$this->Reinvestment->save($tr);
		}

		/*$this->Reinvestment->query("ALTER TABLE reinvestments CHANGE COLUMN accountA_IBAN accountA_IBAN INT UNSIGNED;");
		$this->Reinvestment->query("ALTER TABLE reinvestments CHANGE COLUMN accountB_IBAN accountB_IBAN INT UNSIGNED;");
		$this->Reinvestment->query("ALTER TABLE compartments CHANGE COLUMN accountA_IBAN accountA_IBAN INT UNSIGNED;");
		$this->Reinvestment->query("ALTER TABLE compartments CHANGE COLUMN accountB_IBAN accountB_IBAN INT UNSIGNED;");
		$this->Reinvestment->query("ALTER TABLE transactions CHANGE COLUMN accountA_IBAN accountA_IBAN INT UNSIGNED;");
		$this->Reinvestment->query("ALTER TABLE transactions CHANGE COLUMN accountB_IBAN accountB_IBAN INT UNSIGNED;");*/


		/*
		ALTER TABLE `transactions`
		  DROP `accountA_ID`,
		  DROP `accountB_ID`;

		ALTER TABLE `reinvestments`
		  DROP `accountA_ID`,
		  DROP `accountB_ID`;
		  
		ALTER TABLE `compartments`
		  DROP `accountA_ID`,
		  DROP `accountB_ID`;
		  
		*/

		/* //recreate the table
		CREATE TABLE IF NOT EXISTS `accounts` (
  `IBAN` varchar(45) NOT NULL,
  `BIC` varchar(11) DEFAULT NULL,
  `ccy` varchar(3) DEFAULT NULL,
  `PS_account` varchar(45) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `accounts`
  ADD PRIMARY KEY (`IBAN`);

  
  INSERT INTO accounts (IBAN, BIC, ccy, PS_account, created, modified) SELECT old.IBAN, old.BIC, old.ccy, old.PS_account, old.created, old.modified FROM accounts_id old;
  
  DROP TABLE accounts_id
		*/
	}

	public function getOptionParser()
	{
		$parser = parent::getOptionParser();
		$parser->addOption('display', array('short' => 'd', 'help' => 'Display in browser mode', 'boolean' => TRUE));
		return $parser;
	}
}
