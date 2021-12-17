<?php
$time = time();
	echo '<tr id="CounterpartyAccount_new_'.$time.'" class="newline">';
	//echo $this->Form->create('CounterpartyAccount_new_'.$time);
	echo '<td>';
	echo $this->Form->input('CounterpartyAccount.currency',
		array(
			'label'		=> false,
			'class'		=> 'span8',
			'options'	=> $currencies,
		)
	);
	echo '</td>';

	echo '<td>';
	echo $this->Form->input('CounterpartyAccount.target',
		array(
			'label'		=> false,
			'type'		=> 'checkbox',
		)
	);
	echo '</td>';

	echo '<td>';
	echo $this->Form->input('CounterpartyAccount.correspondent_bank',
		array(
			'label'		=> false,
			'style'		=> 'width: 95%;',
		)
	);
	echo '</td>';

	echo '<td>';
	echo $this->Form->input('CounterpartyAccount.correspondent_BIC',
		array(
			'label'		=> false,
			'style'		=> 'width: 95%;',
		)
	);
	echo '</td>';

	echo '<td>';
	echo $this->Form->input('CounterpartyAccount.account_IBAN',
		array(
			'label'		=> false,
			'style'		=> 'width: 95%;',
		)
	);
	echo '</td>';
	echo '<td><a id="del_account_'.$time.'" class="btn btn-mini confirmation"><i class="icon-trash"></i></a><a id="add_account_'.$time.'" class="btn btn-mini confirmation"><i class="icon-pencil"></i></a></td>';
	echo '</tr>';
?>
<script type="text/javascript">
	$("#del_account_<?php echo $time; ?>").click(function()
	{
		$("#CounterpartyAccount_new_<?php echo $time; ?>").remove();
	});

</script>