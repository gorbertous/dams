<?php /*if(!empty($transactions['EUR'])||!empty($transactions['CURR'])): ?>
		<?php if(!empty($transactions['EUR'])): ?>
			<div class="alert alert-info"> Transactions in EUR </div><br>
			<?php $this->BootstrapTables->displayRawsById('transactions',$transactions['EUR']); ?>
		<?php endif; ?>
		<?php if(!empty($transactions['CURR'])): ?>
			<div class="info"> Other transactions </div><br>
			<?php $this->BootstrapTables->displayRawsById('transactions',$transactions['CURR']); ?>
		<?php endif; ?>
<?php else: ?>
	<div class="alert alert-info">No transactions related to this limit</div>
<?php endif;*/ ?>
<fieldset>
	<legend>Limit : <?php echo $limit['Limit']['limit_name'] ?> (<?php echo $limit['MandateGroup']['mandategroup_name'] ?> ; <?php echo $limit['Counterparty']['cpty_name'] ?>) as of <?php echo $currentdate ?></legend>
</fieldset>
<?php if(empty($transactions['EUR']) && empty($transactions['CURR'])): ?>
	<div class="alert alert-info">No transactions related to this limit</div>
<?php endif ?>
<?php if(!empty($transactions)) foreach($transactions as $transtype=>$trans): if(!empty($trans)): ?>
	<?php if($transtype=='EUR'): ?>
		<div class="alert alert-info"> Transactions in EUR </div>
	<?php else: ?>
		<div class="alert alert-info"> Other transactions </div>
	<?php endif ?>

	<table class="table table-bordered table-striped table-hover table-condensed">
	<thead><tr>
		<th>TRN</th>
		<th>DI</th>
		<th>Status</th>
		<th>Commencement date</th>
		<th>Maturity date</th>
		<th># days</th>
		<th>Depo term</th>
		<th>Amount</th>
		<th>Ccy</th>
		<?php if($transtype!='EUR'): ?>
			<th>Amount in EUR</th>
		<?php endif ?>
		<th>Mandate</th>
		<th>Compartment</th>
		<th>Counterparty</th>
	</tr></thead>
	<tbody>
		<?php foreach($trans as $transaction): ?>
		<tr>
			<td><?php print UniformLib::uniform($transaction['Transaction']['tr_number'], 'tr_number') ?></td>
			<td><?php print UniformLib::uniform($transaction['Instruction']['instr_num'], 'instr_num') ?></td>
			<td><?php print UniformLib::uniform($transaction['Transaction']['tr_state'], 'tr_state') ?></td>
			<td><?php print UniformLib::uniform($transaction['Transaction']['commencement_date'], 'commencement_date') ?></td>
			<td><?php print UniformLib::uniform($transaction['Transaction']['maturity_date'], 'maturity_date') ?></td>
			<td><?php print UniformLib::uniform($transaction['Transaction']['days'], 'days') ?></td>
			<td><?php print UniformLib::uniform($transaction['Transaction']['depo_term'], 'depo_term') ?></td>
			<td class="text-right-force"><?php print UniformLib::uniform($transaction['Transaction']['amount'], 'amount') ?></td>
			<td><?php print UniformLib::uniform($transaction['AccountA']['ccy'], 'ccy') ?></td>
			<?php if($transtype!='EUR'): ?>
				<td class="text-right-force"><?php print UniformLib::uniform($transaction['Transaction']['amount_eur'], 'amount_eur') ?></td>
			<?php endif ?>
			<td><?php print UniformLib::uniform($transaction['Mandate']['mandate_name'], 'mandate_name') ?></td>
			<td><?php print UniformLib::uniform($transaction['Compartment']['cmp_name'], 'cmp_name') ?></td>
			<td><?php print UniformLib::uniform($transaction['Counterparty']['cpty_name'], 'cpty_name') ?></td>
		</tr>
		<?php endforeach ?>
	</tbody>
	</table><br><br>
<?php endif; endforeach ?>