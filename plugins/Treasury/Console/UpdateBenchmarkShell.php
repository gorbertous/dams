<?php

declare(strict_types=1);

namespace Treasury\Console;

App::uses('Shell', 'Console');
App::uses('CurrencyLib', 'Lib');
class UpdateBenchmarkShell extends Shell
{

	public $uses = array('Treasury.Transaction');
	public $tasks = array('ResetOwner');

	public function main($debug = false)
	{
		$tr = $this->Transaction->find('all', array(
			'conditions' => array('Transaction.mandate_ID' => array(36, 35, 38, 41, 46, 52, 58)),
			'recursive' => 1
		));
		foreach ($tr as $key => $transaction) {
			echo "\n" . $transaction['Transaction']['tr_number'];
			$currency = $transaction['Transaction']['ccy'];
			$mandate_ID = $transaction['Transaction']['mandate_ID'];
			switch ($mandate_ID) {
				case 36:
				case 35:
				case 38:
				case 41:
				case 46:
				case 52:
				case 58:
					if ($currency == 'EUR') $benchmark = 'EURIBID 1M';
					elseif ($currency == 'GBP') $benchmark = 'LIBID 1M';
					elseif ($currency == 'HUF') $benchmark = 'BUBOR 1M'; //HUF: BUBOR 1m
					elseif ($currency == 'DKK') $benchmark = 'CIBOR 1M'; //DKK: CIBOR 1m
					elseif ($currency == 'BGN') $benchmark = 'SOFIBID 1M'; //BGN: SOFIBID 1m
					elseif ($currency == 'PLN') $benchmark = 'WIBOR 1M';
					elseif ($currency == 'RON') $benchmark = 'BUBOR 1M';
					elseif ($currency == 'SEK') $benchmark = 'STIBOR 1M';
					elseif ($currency == 'TRY') $benchmark = 'TRLIB 1M';
					elseif ($currency == 'HRK') $benchmark = 'ZIBOR 1M';
					elseif ($currency == 'CZK') $benchmark = 'PRIBOR 1M';

					else $benchmark = 't.b.d.';
					break;
				default:
					$benchmark = strtoupper($currency) . ' O/N Bloomberg CMP';
			}
			echo "\n" . $benchmark . " saving";
			$transaction['Transaction']['benchmark'] = $benchmark;
			$this->Transaction->save($transaction);
			echo "\n" . $benchmark . " saved";
		}
		//$this->ResetOwner->execute();
	}
}
