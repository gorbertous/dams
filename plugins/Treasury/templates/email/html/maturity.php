<h1>Transaction Maturity Alert</h1>

<table border="1">
	<thead>
		<tr>
			<th>Days</th>
			<th>TRN</th>
			<th>Type</th>
			<th>Status</th>
			<th>Currency</th>
			<th>Amount</th>
			<th>Interest</th>
			<th>Tax</th>
			<th>Commencement Date</th>
			<th>Maturity Date</th>
			<th>Period</th>
			<th>Term/Call</th>
			<th>Renewal</th>
			<th>Rate type</th>
			<th>Mandate name</th>
			<th>Compartment name</th>
			<th>Principal Account</th>
			<th>Interest Account</th>
		</tr>
	</thead>
	<tbody>
	<?php if(!empty($transactions)) foreach ($transactions as $tr): ?>
		<?php if(!empty($tr['Transaction'])): ?>
		<tr>
			<td><?php echo UniformLib::uniform($tr['Transaction']['batch_days'], 'batch_days') ?></td>
			<td><?php echo UniformLib::uniform($tr['Transaction']['tr_number'], 'tr_number') ?></td>
			<td><?php echo UniformLib::uniform($tr['Transaction']['tr_type'], 'tr_type') ?></td>
			<td><?php echo UniformLib::uniform($tr['Transaction']['tr_state'], 'tr_state') ?></td>
			<td><?php echo UniformLib::uniform($tr['AccountA']['ccy'], 'ccy') ?></td>
			<td><?php echo UniformLib::uniform($tr['Transaction']['amount'], 'amount') ?></td>
			<td><?php echo UniformLib::uniform($tr['Transaction']['total_interest'], 'total_interest') ?></td>
			<td><?php echo UniformLib::uniform($tr['Transaction']['tax_amount'], 'tax_amount') ?></td>
			<td><?php echo UniformLib::uniform($tr['Transaction']['commencement_date'], 'commencement_date') ?></td>
			<td><?php echo UniformLib::uniform($tr['Transaction']['maturity_date'], 'maturity_date') ?></td>
			<td><?php echo UniformLib::uniform($tr['Transaction']['depo_term'], 'depo_term') ?></td>
			<td><?php echo UniformLib::uniform($tr['Transaction']['depo_type'], 'depo_type') ?></td>
			<td><?php echo UniformLib::uniform($tr['Transaction']['depo_renew'], 'depo_renew') ?></td>
			<td><?php echo UniformLib::uniform($tr['Transaction']['rate_type'], 'rate_type') ?></td>
			<td><?php echo UniformLib::uniform($tr['Mandate']['mandate_name'], 'mandate_name') ?></td>
			<td><?php echo UniformLib::uniform($tr['Compartment']['cmp_name'], 'cmp_name') ?></td>
			<td><?php echo UniformLib::uniform($tr['Transaction']['accountA_IBAN'], 'accountA_IBAN') ?></td>
			<td><?php echo UniformLib::uniform($tr['Transaction']['accountB_IBAN'], 'accountB_IBAN') ?></td>
		</tr>
		<?php endif ?>
	<?php endforeach ?>
	</tbody>
</table>
<p>Generated on <?php echo date("d/m/Y", time()) ?></p>
<p>Please reply to this email to <strong>EIF TREASURY</strong> (<a href="mailto:eif-treasury@eif.org">eif-treasury@eif.org</a>) at latest <strong>3 days</strong> before transaction maturity date.</p>