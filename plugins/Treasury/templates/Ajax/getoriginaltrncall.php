<table class='table table-bordered'>
	<tr>
		<td><strong>Original Transaction</strong></td>
		<td><?php echo UniformLib::uniform($origin['Transaction']['tr_number'], 'tr_number') ?> <?php echo UniformLib::uniform($origin['Mandate']['mandate_name'], 'mandate_name') ?> <?php echo UniformLib::uniform($origin['Transaction']['amount'], 'amount') ?> <?php echo UniformLib::uniform($origin['AccountA']['ccy'], 'ccy') ?></td>
	</tr>
	<tr>
		<td><strong>Selected Transaction</strong></td>
		<td><?php echo UniformLib::uniform($selected['Transaction']['tr_number'], 'tr_number') ?> <?php echo UniformLib::uniform($selected['Transaction']['tr_type'], 'tr_type') ?></td>
	</tr>
	<tr>
		<td><strong>Called amount</strong></td>
		<td><?php echo UniformLib::uniform($selected['Transaction']['amount'], 'amount') ?> <?php echo UniformLib::uniform($origin['AccountA']['ccy'], 'ccy') ?></td>
	</tr>
	<tr>
		<td><strong>Principal Into Account</strong></td>
		<td><?php echo UniformLib::uniform($selected['Transaction']['accountA_IBAN'], 'accountA_IBAN') ?></td>
	</tr>
	<tr>
		<td><strong>Interest Into Account</strong></td>
		<td><?php echo UniformLib::uniform($selected['Transaction']['accountB_IBAN'], 'accountB_IBAN') ?></td>
	</tr>
</table>
