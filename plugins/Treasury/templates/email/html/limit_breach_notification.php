<p>Limit Breach Alert:</p>
<ul>
<?php if(!empty($breach['limit']['limit_name'])): ?>
	<?php
		if(!empty($breach['counterpartygroup'])){
			$concentration = $breach['counterpartygroup']['concentration'];
			$name = $breach['counterpartygroup']['counterpartygroup_name'];
			$exposure = $breach['counterpartygroup']['exposure'];
		}else{
			$concentration = $breach['counterparty']['concentration'];
			$name = $breach['counterparty']['cpty_name'];
			$exposure = $breach['counterparty']['exposure'];
		}
		
		if(!empty($breach['limit']['concentration_limit_unit']) && ( $breach['limit']['concentration_limit_unit']=='PCT' ||
			$breach['limit']['concentration_limit_unit']=='ABS') && empty($breach['limit']['automatic']) && $concentration<=1){
			$concentration*=100;
			$concentration = number_format($concentration, 2);	
			$concentration.='%';
		}else{
			if($concentration<=1){
				 $concentration*=100;
				 $concentration = number_format($concentration, 2);
				 $concentration.='%';
			}
			else $concentration = number_format($concentration, 2);	
		}//TODO : check concentration in case no concentration limit
	?>
	<li>Limit: <?php echo UniformLib::uniform($breach['limit']['limit_name'], 'limit_name') ?></li>
	<li>Portfolio: <?php echo UniformLib::uniform($breach['mandategroup_name'], 'mandategroup_name') ?></li>
	<?php if(!empty($breach['counterpartygroup'])): ?>
	<li>Risk Group: <?php echo UniformLib::uniform($name, 'counterpartygroup_name') ?></li>
	<?php else: ?>
	<li>Counterparty: <?php echo UniformLib::uniform($name, 'cpty_name') ?></li>
	<?php endif ?>
	<li>Rating LT: <?php echo UniformLib::uniform($breach['limit']['rating_lt'], 'rating_lt') ?></li>
	<li>Rating ST: <?php echo UniformLib::uniform($breach['limit']['rating_st'], 'rating_st') ?></li>
	<li>Max Maturity: <?php echo UniformLib::uniform($breach['limit']['max_maturity'], 'max_maturity') ?>d</li>
	<li>Limit in EUR: <?php echo UniformLib::uniform($breach['limit']['limit_eur'],'limit_eur') ?></li>
	<li>Exposure in EUR: <?php echo UniformLib::uniform($exposure,'exposure_eur') ?></li>
	<li>Portfolio Concentration: <?php echo UniformLib::uniform($concentration,'concentration') ?></li>
	<li>Limit Available in EUR: 	<?php echo UniformLib::uniform($breach['limit']['limit_available'],'limit_available') ?></li>
	<li>Breach type: <?php echo UniformLib::uniform(ucfirst($breach['breachtype']),'breach_type') ?></li>
	<li>Breach details<?php 
	if ($breach['breachtype'] == 'exposure') {
		echo ' (cparty exposure / limit): ';	
	}elseif ($breach['breachtype'] == 'concentration') {
		echo ' (cparty concentration / max concentration): ';

		$val1 = preg_replace('/(.*) \/.*/', '$1', $breach['details']);
		$val2 = preg_replace('/.*\/(.*)/', '$1', $breach['details']);

		$val1 = preg_replace('/(.*) \/.*/', '$1', $breach['details']);
        $val2 = preg_replace('/.* \/(.*)/', '$1', $breach['details']);

        //check if the value contains , to compare if percentage
        if (str_replace(',', '', $val1) < 100 ) $val1 .='%';
        if (str_replace(',', '', $val2) < 100 ) $val2 .='%';
        $breach['details'] = $val1 .' / ' .$val2;
	}
	echo ': '.UniformLib::uniform(ucfirst($breach['details']),'breach_details'); ?></li>
<?php else: ?>
	<li>Limit: 	<?php echo UniformLib::uniform($breach['limit_name'], 'limit_name') ?></li>
	<li>Portfolio: 	<?php echo UniformLib::uniform($breach['mandategroup_name'], 'mandategroup_name') ?></li>
	<li>Counterparty: 	<?php echo UniformLib::uniform($breach['cpty_name'], 'cpty_name') ?></li>
	<li>Rating: 	<?php echo UniformLib::uniform($breach['cpty_rating'], 'cpty_rating') ?></li>
	<li>Max Maturity: 	<?php echo UniformLib::uniform($breach['max_maturity'], 'max_maturity') ?>d</li>
	<li>Limit in EUR: 	<?php echo UniformLib::uniform($breach['limit_eur'],'limit_eur') ?></li>
	<li>Exposure in EUR: 	<?php echo UniformLib::uniform($breach['exposure_eur'],'exposure_eur') ?></li>
	<li>Limit Available in EUR: 	<?php echo UniformLib::uniform($breach['limit_available'],'limit_available') ?></li>
<?php endif ?>
</ul>
<?php if(!empty($breach['Transactions'])): ?>
<?php
	$transtype = '';
	if(!empty($breach['Transactions']['EUR'])): ?>
		<?php $transtype='EUR'; ?>
		<?php $title = 'Transactions in EUR';  ?>
		<?php $transactions = $breach['Transactions']['EUR'];  ?>
		<p><?php echo $title ?></p>
		<table border="1">
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
			</tr></thead>
			<tbody>
			<?php if(!empty($transactions)) foreach($transactions as $transaction): ?>
				<tr>
					<td><?php print UniformLib::uniform($transaction['Transaction']['tr_number'], 'tr_number') ?></td>
					<td><?php print UniformLib::uniform($transaction['Instruction']['instr_num'], 'instr_num') ?></td>
					<td><?php print UniformLib::uniform($transaction['Transaction']['tr_state'], 'tr_state') ?></td>
					<td><?php print UniformLib::uniform($transaction['Transaction']['commencement_date'], 'commencement_date') ?></td>
					<td><?php print UniformLib::uniform($transaction['Transaction']['maturity_date'], 'maturity_date') ?></td>
					<td><?php print UniformLib::uniform($transaction['Transaction']['days'], 'days') ?></td>
					<td><?php print UniformLib::uniform($transaction['Transaction']['depo_term'], 'depo_term') ?></td>
					<td style="text-align: right;"><?php print UniformLib::uniform($transaction['Transaction']['amount'],'amount') ?></td>
					<td><?php print UniformLib::uniform($transaction['AccountA']['ccy'], 'ccy') ?></td>
					<?php if($transtype!='EUR'): ?>
						<td class="text-right-force"><?php print UniformLib::uniform($transaction['Transaction']['amount_eur'], 'amount_eur') ?></td>
					<?php endif ?>
					<td><?php print UniformLib::uniform($transaction['Mandate']['mandate_name'], 'mandate_name') ?></td>
					<td><?php print UniformLib::uniform($transaction['Compartment']['cmp_name'], 'cmp_name') ?></td>
				</tr>
			<?php endforeach ?>
			</tbody>
		</table>
	<?php endif; ?>
	<?php if(!empty($breach['Transactions']['CURR'])): ?>
		<?php $transtype='CURR'; ?>
		<?php $title = 'Other transactions'; ?>
		<?php $transactions = $breach['Transactions']['CURR']; ?>
		<p><?php echo $title ?></p>
		<table border="1">
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
			</tr></thead>
			<tbody>
			<?php if(!empty($transactions)) foreach($transactions as $transaction): ?>
				<tr>
					<td><?php print UniformLib::uniform($transaction['Transaction']['tr_number'], 'tr_number') ?></td>
					<td><?php print UniformLib::uniform($transaction['Instruction']['instr_num'], 'instr_num') ?></td>
					<td><?php print UniformLib::uniform($transaction['Transaction']['tr_state'], 'tr_state') ?></td>
					<td><?php print UniformLib::uniform($transaction['Transaction']['commencement_date'], 'commencement_date') ?></td>
					<td><?php print UniformLib::uniform($transaction['Transaction']['maturity_date'], 'maturity_date') ?></td>
					<td><?php print UniformLib::uniform($transaction['Transaction']['days'], 'days') ?></td>
					<td><?php print UniformLib::uniform($transaction['Transaction']['depo_term'], 'depo_term') ?></td>
					<td style="text-align: right;"><?php print UniformLib::uniform($transaction['Transaction']['amount'],'amount') ?></td>
					<td><?php print UniformLib::uniform($transaction['AccountA']['ccy'], 'ccy') ?></td>
					<?php if($transtype!='EUR'): ?>
						<td class="text-right-force"><?php print UniformLib::uniform($transaction['Transaction']['amount_eur'], 'amount_eur') ?></td>
					<?php endif ?>
					<td><?php print UniformLib::uniform($transaction['Mandate']['mandate_name'], 'mandate_name') ?></td>
					<td><?php print UniformLib::uniform($transaction['Compartment']['cmp_name'], 'cmp_name') ?></td>
				</tr>
			<?php endforeach ?>
			</tbody>
		</table>
	<?php endif; ?>
<?php endif ?>