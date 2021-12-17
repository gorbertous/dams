<div class="well">
	<table class='table table-bordered'>
		<tr>
			<td><strong>Parent Transaction</strong></td>
			<td><?php echo UniformLib::uniform($parent['Transaction']['tr_number'], 'tr_number') ?> <?php echo UniformLib::uniform($parent['Mandate']['mandate_name'], 'mandate_name') ?> <?php echo UniformLib::uniform($parent['Transaction']['amount'], 'amount') ?> <?php echo UniformLib::uniform($parent['AccountA']['ccy'], 'ccy') ?></td>
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
		<tr>
			<td><strong>Maturity Date</strong></td>
			<td><?php echo UniformLib::uniform($selected['Transaction']['maturity_date'], 'maturity_date') ?></td>
		</tr>
	</table>
	<hr>
	<div class="row-fluid">
		<div class="span6">
			<p><u>Part Withdrawal:</u></p>
			<div class="input text">
				<label for="TransactionWithdrawal">TRN: <?php echo UniformLib::uniform($selected['Transaction']['tr_number'], 'tr_number') ?> <?php echo UniformLib::uniform($selected['Transaction']['tr_type'], 'tr_type') ?></label>
				<?php echo $this->Form->input('Withdrawal.tr_number', 
					array('type'	=>	'hidden', 
							'value'	=>	UniformLib::uniform($selected['Transaction']['tr_number'], 'tr_number'),
						)); ?>
			</div>
			<div class="input text">
				<label for="TransactionWithAmount">Withdrawal amount:</label>
				<span id="TransactionWithAmount"><?php echo UniformLib::uniform($selected['Transaction']['amount'], 'amount') ?> <?php echo UniformLib::uniform($selected['AccountA']['ccy'], 'ccy') ?></span>
			</div>
			<div class="input text">
				<label for="TransactionWithInterest">Interest amount:</label>
				<span id="TransactionWithInterest"><?php echo UniformLib::uniform($selected['Transaction']['total_interest'], 'total_interest') ?> <?php echo UniformLib::uniform($selected['AccountA']['ccy'], 'ccy') ?></span>
			</div>
			<div class="input text">
				<label for="TransactionTax">Tax amount:</label>
				<span id="TransactionWithTax"><?php echo UniformLib::uniform($selected['Transaction']['tax_amount'], 'tax_amount') ?> <?php echo UniformLib::uniform($selected['AccountA']['ccy'], 'ccy') ?></span>
			</div>
		</div>
		<div class="span6">
			<p><u>Part Retained:</u></p>
			<div class="input text">
				<label for="TransactionRetained">TRN: <?php echo UniformLib::uniform($sister['Transaction']['tr_number'], 'tr_number') ?> <?php echo UniformLib::uniform($sister['Transaction']['tr_type'], 'tr_type') ?></label>
				<?php echo $this->Form->input('Retained.tr_number', 
					array('type'	=>	'hidden', 
							'value'	=>	UniformLib::uniform($sister['Transaction']['tr_number'], 'tr_number'),
						)); ?>
				
			</div>
			<div class="input text">
				<label for="TransactionRetAmount">Deposited amount:</label>
				<span id="TransactionWithAmount"><?php echo UniformLib::uniform($sister['Transaction']['amount'], 'amount') ?> <?php echo UniformLib::uniform($sister['AccountA']['ccy'], 'ccy') ?></span>
			</div>
			<div class="input text">
				<label for="TransactionRetInterest">Interest amount:</label>
				<span id="TransactionRetInterest"><?php echo UniformLib::uniform($sister['Transaction']['total_interest'], 'total_interest') ?> <?php echo UniformLib::uniform($sister['AccountA']['ccy'], 'ccy') ?></span>
			</div>
			<div class="input text">
				<label for="TransactionRetTax">Tax amount:</label>
				<span id="TransactionRetTax"><?php echo UniformLib::uniform($sister['Transaction']['tax_amount'], 'tax_amount') ?> <?php echo UniformLib::uniform($sister['AccountA']['ccy'], 'ccy') ?></span>
			</div>
		</div>
	</div>
</div>
