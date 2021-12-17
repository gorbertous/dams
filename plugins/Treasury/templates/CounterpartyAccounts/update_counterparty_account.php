<?php
if(!empty($message))
{
	echo $message;
}
elseif(!empty($cpty_account))
{
	$account = $cpty_account['CounterpartyAccount'];
	echo '<tr id="CounterpartyAccount_'.$account['id'].'">';
	//echo $this->Form->create('CounterpartyAccount_'.$account['id']);
	echo $this->Form->input('CounterpartyAccount.id',
		array(
			'type'		=> 'hidden',
			'value'		=> $account['id'],
		)
	);
	echo $this->Form->input('CounterpartyAccount.cpty_id',
		array(
			'type'		=> 'hidden',
			'value'		=> $account['cpty_id'],
		)
	);
	echo '<td>';
	echo $this->Form->input('CounterpartyAccount.currency',
		array(
			'label'		=> false,
			'class'		=> 'span8',
			'options'	=> $currencies,
			'default'	=> $account['currency'],
		)
	);
	echo '</td>';

	echo '<td>';
	echo $this->Form->input('CounterpartyAccount.target',
		array(
			'label'		=> false,
			'type'		=> 'checkbox',
			'default'	=> $account['target'],
		)
	);
	echo '</td>';

	echo '<td>';
	echo $this->Form->input('CounterpartyAccount.correspondent_bank',
		array(
			'label'		=> false,
			'style'		=> 'width: 95%;',
			'default'	=> $account['correspondent_bank'],
		)
	);
	echo '</td>';

	echo '<td>';
	echo $this->Form->input('CounterpartyAccount.correspondent_BIC',
		array(
			'label'		=> false,
			'style'		=> 'width: 95%;',
			'default'	=> $account['correspondent_BIC'],
		)
	);
	echo '</td>';

	echo '<td>';
	echo $this->Form->input('CounterpartyAccount.account_IBAN',
		array(
			'label'		=> false,
			'style'		=> 'width: 95%;',
			'default'	=> $account['account_IBAN'],
		)
	);
	echo '</td>';

	echo '<td><a href="/treasury/Treasurycounterpartyaccount/del_counterparty_account/'.$account['id'].'" id="del_account_'.$account['id'].'" class="btn btn-mini confirmation"><i class="icon-trash"></a></td>';//<a href="/damsv2/DSToolbox/delete/2" class="btn btn-mini confirmation"><i class="icon-trash"></i></a>
	//echo $this->Form->end();
	echo '</tr>';
}
?>