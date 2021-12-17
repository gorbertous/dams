<table class='table table-bordered'>
	<tr>
		<td><strong>Original Transaction</strong></td>
		<td>
			<?php 
				if(isset($origin['Transaction'])): 
					echo UniformLib::uniform($origin['Transaction']['tr_number'], 'tr_number')." ";
					echo UniformLib::uniform($origin['Mandate']['mandate_name'], 'mandate_name')." ";
					echo UniformLib::uniform($origin['Transaction']['amount'], 'amount')." "; 
					echo UniformLib::uniform($origin['AccountA']['ccy'], 'ccy');
				else: 
					echo "No Original transaction set"; 
				endif; 
			?>
		</td>
	</tr>
	<tr>
		<td><strong>Selected Transaction</strong></td>
		<td><?php echo UniformLib::uniform($selected['Transaction']['tr_number'], 'tr_number') ?> <?php echo UniformLib::uniform($selected['Transaction']['tr_type'], 'tr_type') ?></td>
	</tr>
	<tr>
		<td><strong>Withdrawal amount</strong></td>
		<td><?php echo UniformLib::uniform($selected['Transaction']['amount'], 'amount') ?> <?php if(isset($origin['AccountA'])) echo UniformLib::uniform($origin['AccountA']['ccy'], 'ccy') ?></td>
	</tr>
	<tr>
		<td><strong>Principal Into Account</strong></td>
		<td><?php echo UniformLib::uniform($selected['Transaction']['accountA_IBAN'], 'accountA_IBAN') ?></td>
	</tr>
	<tr>
		<td><strong>Interest Into Account</strong></td>
		<td><?php echo UniformLib::uniform($selected['Transaction']['accountB_IBAN'], 'accountB_IBAN') ?></td>
	</tr>
	<tr>
		<td><strong>Value Date</strong></td>
		<td><?php echo UniformLib::uniform($selected['Transaction']['commencement_date'], 'commencement_date') ?></td>
	</tr>
</table>
