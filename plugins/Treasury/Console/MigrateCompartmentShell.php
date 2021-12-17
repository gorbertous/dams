<?php

declare(strict_types=1);

namespace Treasury\Console;

App::uses('Shell', 'Console');
App::uses('UniformLib', 'Lib');
App::uses('CurrencyLib', 'Lib');

// /var/www/html/php/app/Console/cake Treasury.MigrateCompartment


class MigrateCompartmentShell extends Shell
{

	public $uses = array('Treasury.Compartment');
	public $tasks = array('ResetOwner');

	public function main($debug = false)
	{
		$lines = $this->Compartment->find('all', array('fields' => array('cmp_ID', 'cmp_type', 'cmp_value', 'cmp_dpt_code_value', 'cmp_sof_value')));
		foreach ($lines as $line) {
			if ($line['Compartment']['cmp_type'] == 'source of funding') {
				$line['Compartment']['cmp_sof_value'] = $line['Compartment']['cmp_value'];
			} elseif ($line['Compartment']['cmp_type'] == 'department code') {
				$line['Compartment']['cmp_dpt_code_value'] = $line['Compartment']['cmp_value'];
			}
			echo "\nsaving: " . json_encode($line, true);
			$saved = $this->Compartment->save($line);
			echo "\nsaved: " . json_encode($saved, true);
			if (empty($saved)) {
				echo "\nvalidation error: " . json_encode($this->Compartment->validationErrors, true);
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
