<?php

declare(strict_types=1);

namespace Treasury\Console;

App::uses('Shell', 'Console');
App::uses('UniformLib', 'Lib');
class FillOriginTrnShell extends Shell
{

	public $uses = array('Treasury.Transaction');
	public $tasks = array('ResetOwner');
	protected $_trn = array();
	protected $_allTrn = array();
	protected $_originVal = null;

	public function main($debug = false)
	{
		$tr = $this->Transaction->find('all', array(
			'fields' => array('Transaction.tr_number', 'Transaction.original_id', 'Transaction.parent_id'),
			'conditions' => array('Transaction.original_id' => null),
			'recursive' => -1
		));
		foreach ($tr as $key => $transaction) {
			if (is_null($transaction['Transaction']['original_id']) && !is_null($transaction['Transaction']['parent_id'])) {
				$this->_searchOrigin($transaction);
			}
		}
		$this->ResetOwner->execute();
	}

	protected function _searchOrigin($transaction)
	{
		$tr = $this->Transaction->find('first', array(
			'fields' => array('Transaction.tr_number', 'Transaction.original_id', 'Transaction.parent_id'),
			'conditions' => array('Transaction.tr_number' => $transaction['Transaction']['parent_id']),
			'recursive' => -1
		));

		$this->_trn[] = $transaction['Transaction']['tr_number'];


		if (!empty($tr['Transaction']['parent_id'])) {
			$this->_searchOrigin($tr);
		} else {
			$this->_originVal =  $tr['Transaction']['tr_number'];
			$this->_updateVal();
		}
	}

	protected function _updateVal()
	{
		foreach ($this->_trn as $tr_number) {
			if (in_array($tr_number, $this->_allTrn)) continue;

			$q = 'UPDATE transactions set original_id =' . $this->_originVal . ' WHERE tr_number=' . intval($tr_number) . ";\r\n";
			echo $q;
			$db = ConnectionManager::getDataSource('treasury');
			$myData = $db->query($q);
			$this->_allTrn[] = $tr_number;
		}
		$this->_trn = array();
		$this->_originVal = null;
	}
}
