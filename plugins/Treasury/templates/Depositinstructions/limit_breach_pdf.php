<!-- limit_breach -->
<html>
<head>
<link rel="stylesheet" href="/theme/Cakestrap/css/bootstrap.css">
<?php 
echo $this->Html->css('/treasury/css/redmond/jquery-ui-1.10.3.custom.css');
	echo $this->Html->script('/treasury/js/jquery-ui-1.10.3.custom.min.js');
	print $this->Html->css('Treasury.limitBreachPDF',null, array('fullBase' => true));
	?>
</head>
<body>
<fieldset>
Limits Monitor Snapshot - DI <?php echo $instr_num ?>
<hr/>
<br>
<div>

	<table class="global">
		<tr>
			<td>Created on:</td>
			<td><?php echo date('d/m/Y H:i:s') ?></td>
			<td>&nbsp;</td>
			<td>Portfolio:</td>
			<td><?php echo $mandateGroupName ?></td>
		</tr>
		<tr>
			<td>with DI:</td>
			<td><?php echo $instr_num ?></td>
			<td>&nbsp;</td>
			<td>Portfolio size:</td>
			<td><?php print UniformLib::uniform($portfolioSize, 'portfolio_size').' EUR'; ?></td>
		</tr>
		<tr>
			<td>Counterparty:</td>
			<td><?php echo $cpty['Counterparty']['cpty_name'] ?></td>
			<td>&nbsp;</td>
			<td>Portfolio Concentration Limit:</td>
			<td><?php if(!empty($portfolioMaxConcentration)) print UniformLib::uniform($portfolioMaxConcentration, $portfolio_concentration_key).$portfolio_concentration_suffix;
			else print 'N/A'; ?></td>
		</tr>
		<tr>
			<td>Mandate:</td>
			<td><?php echo $mandate['Mandate']['mandate_name'] ?></td>
			<td>&nbsp;</td>
			<td>Counterparty Concentration Limit:</td>
			<td><?php
			if (strpos($cptyConcentrationLimit, '%') !== false)
			{
				print UniformLib::uniform($cptyConcentrationLimit, '_pct').'%';
			}
			else
			{
				print UniformLib::uniform($cptyConcentrationLimit, 'amount')." EUR";
			}
			?></td>
		</tr>
	</table>
</div>
</br>
Portfolio Limits / Exposures as of: <?php echo $commencement_date ?>
<hr/>
<div>
<?php if(empty($limits['counterparties']) && empty($limits['counterpartygroups'])): ?>
	<p>No limits for these criteria</p>
<?php else: ?>
	<table class='header'>
		<thead>
			<tr>
				<td width="19%" style="text-align:left">Counterparty or Group</td>
				<td width="6%" style="text-align:center;vertical-align: middle;" colspan="2"><div>Retained</div><div><div style="width: 50%;float: left;">LT</div><div style="width: 50%;float: left;">RT</div></div></td>
				<!--<td width="6%" style="text-align:center;vertical-align: middle;">Retained ST</td>-->
				<td width="8%" style="text-align:center;vertical-align: middle;">Max Maturity (days)</td>
				<td width="8%" style="text-align:center;vertical-align: middle;">Limit (in EUR)</td>
				<td width="9%" style="text-align:center;vertical-align: middle;">Exposure (in EUR)</td>
				<td width="10%" style="text-align:center;vertical-align: middle;">Portfolio Concentration</td>
				<td width="12%" style="text-align:center;vertical-align: middle;">Limit Available (in EUR)</td>
				<td width="5%" style="text-align:center;vertical-align: middle;">Status</th>
			</tr>
		</thead>
<?php /** COUNTERPARTY GROUPS **/ ?>
	<?php if(!empty($limits['counterpartygroups'])) foreach($limits['counterpartygroups'] as $group): ?>
		<?php if(!empty($group['limit'])): ?>
			
			<tr <?php if(!empty($group['limit']['status'])) print 'bgcolor="#FF0000"' ?>>
				<td class="cpty"> <?php print $group['CounterpartyGroup']['counterpartygroup_name'] ?></td>
				<td id="rating" class="centerText"><?php print $group['limit']['rating_lt'] ?></td>
				<td id="rating" class="centerText"><?php print $group['limit']['rating_st'] ?></td>
				<td id="maxmaturity"><?php print UniformLib::uniform($group['limit']['max_maturity'], 'max_maturity') ?></td>
				<td id="limit"><?php print UniformLib::uniform($group['limit']['limit_eur'], 'limit_eur') ?></td>
				<td id="exposure"><?php print UniformLib::uniform($group['CounterpartyGroup']['exposure'], 'exposure') ?></td>
				<td id="concentration"><?php
					$concentration = $group['CounterpartyGroup']['concentration'];
					$concentrationkey = 'concentration';
					$concentration_suffix='';
					if($portfolioConcentrationUnit=='PCT' || $portfolioConcentrationUnit=='NA'){
						$concentration*=100;
						$concentrationkey = 'concentration_pct';
						$concentration_suffix = '%';
					}
					print UniformLib::uniform($concentration, $concentrationkey).$concentration_suffix;
				?></td>
				<td id="limitavailable" class="numeric"><?php print UniformLib::uniform($group['CounterpartyGroup']['limit_available'], 'limit_available') ?></td>
				<td class="status actions" style="text-align:center;">
					<?php if(empty($group['limit']['status'])): ?>
						<?php echo "OK" ?>
					<?php else: ?>
						<?php 
							$title = '';
							foreach($group['limit']['status'] as $error){
								if($title) $title.= ' + ';
								foreach($error as $key=>$val) $title.=$key.' ('.$val.')';
							}
							if($title) echo $title;
						?>
					<?php endif ?>
				</td>
			</tr>
	<?php endif ?>
	<?php endforeach ?>

<?php /** SINGLE COUNTERPARTIES **/ ?>		
	<?php if(!empty($limits['counterparties'])) foreach($limits['counterparties'] as $cpty): ?>
		<?php if(!empty($cpty['limits'])) foreach($cpty['limits'] as $limit): ?>
			<tr <?php if(!empty($cpty['counterparty']['status'])) print 'bgcolor="#FF0000"' ?>>
				<td id="cpty"><?php print UniformLib::uniform($cpty['counterparty']['cpty_name'], 'cpty_name') ?></td>
				<td id="rating" class="centerText"><?php print $limit['rating_lt'] ?></td>
				<td id="rating" class="centerText"><?php print $limit['rating_st'] ?></td>
				<td id="maxmaturity" class="centerText"><?php print UniformLib::uniform($limit['max_maturity'], 'max_maturity') ?></td>
				<td id="limit"><?php print UniformLib::uniform($limit['limit_eur'], 'limit_eur') ?></td>
				<td id="exposure"><?php print UniformLib::uniform($cpty['counterparty']['exposure'], 'exposure') ?></td>
				<td id="concentration">
				<?php					
					$concentration = $cpty['counterparty']['concentration'];
					$concentrationkey = 'concentration';
					$concentration_suffix = '';
					if($portfolioConcentrationUnit=='PCT' || $portfolioConcentrationUnit=='NA'){
						$concentration*=100;
						$concentrationkey = 'concentration_pct';
						$concentration_suffix = '%';
					}
					print UniformLib::uniform($concentration, $concentrationkey).$concentration_suffix;
				?></td>
				<td id="limitavailable" class="numeric"><?php print UniformLib::uniform($cpty['counterparty']['limit_available'], 'limit_available') ?></td>
				<td id="status actions" style="text-align:center;">
					<?php if(empty($cpty['counterparty']['status'])): ?>
						<?php echo "OK" ?>
					<?php else: ?>
						<?php 
							$title = '';
							foreach($cpty['counterparty']['status'] as $error){
								if($title) $title.= ' + ';
								foreach($error as $key=>$val) $title.=$key.' ('.$val.')';
							}
							if($title)  echo $title;
						?>
					<?php endif ?>
				</td>
			</tr>
		<?php endforeach ?>
	<?php endforeach ?>
		</tbody>
	</table>
<?php endif ?>
<?php //debug($limits) ?>

</br>
<?php /** TRANSACTIONS  OF COUNTERPARTIES, from limits/details.ctp **/ ?>	
Counterparty transactions as of: <?php echo $commencement_date ?>
<hr/>	
<?php if(empty($transactions['EUR']) && empty($transactions['CURR'])): ?>
	<div class="alert alert-info">No transactions related to this limit</div>
<?php endif ?>
<?php if(!empty($transactions)) foreach($transactions as $transtype=>$trans): if(!empty($trans)): ?>
	<?php if($transtype=='EUR'): ?>
		<div class="alert alert-info"> Transactions in EUR </div>
	<?php else: ?>
		<div class="alert alert-info"> Other transactions </div>
	<?php endif ?>

	<table class="header">
	<thead><tr>
		<td width="5%">TRN</td>
		<td width="5%">DI</td>
		<td width="16%">Status</td>
		<td width="10%">Commencement date</td>
		<td width="10%">Maturity date</td>
		<td width="7%"># days</td>
		<td width="7%">Depo term</td>
		<td width="12%">Amount</td>
		<td width="5%">Ccy</td>
		<?php if($transtype!='EUR'): ?>
			<td>Amount in EUR</td>
		<?php endif ?>
		<td width="10%">Mandate</td>
		<td width="10%">Compartment</td>
	</tr></thead>
	<tbody>
		<?php foreach($trans as $transaction): ?>
		<tr>
			<td><div class="nosplit"><?php print UniformLib::uniform($transaction['Transaction']['tr_number'], 'tr_number') ?></div></td>
			<td><div class="nosplit"><?php print UniformLib::uniform($transaction['Instruction']['instr_num'], 'instr_num') ?></div></td>
			<td class="leftText"><div class="nosplit"><?php print UniformLib::uniform($transaction['Transaction']['tr_state'], 'tr_state') ?></div></td>
			<td class="leftText"><div class="nosplit"><?php print UniformLib::uniform($transaction['Transaction']['commencement_date'], 'commencement_date') ?></div></td>
			<td class="leftText"><div class="nosplit"><?php print UniformLib::uniform($transaction['Transaction']['maturity_date'], 'maturity_date') ?></div></td>
			<td class="leftText"><div class="nosplit"><?php print UniformLib::uniform($transaction['Transaction']['days'], 'days') ?></div></td>
			<td class="leftText"><div class="nosplit"><?php print UniformLib::uniform($transaction['Transaction']['depo_term'], 'depo_term') ?></div></td>
			<td id="amount" class="text-right-force"><div class="nosplit"><?php print UniformLib::uniform($transaction['Transaction']['amount'], 'amount') ?></div></td>
			<td class="leftText"><div class="nosplit"><?php print UniformLib::uniform($transaction['AccountA']['ccy'], 'ccy') ?></div></td>
			<?php if($transtype!='EUR'): ?>
				<td class="text-right-force"><div class="nosplit"><?php print UniformLib::uniform($transaction['Transaction']['amount_eur'], 'amount_eur') ?></div></td>
			<?php endif ?>
			<td class="leftText"><div class="nosplit"><?php print UniformLib::uniform($transaction['Mandate']['mandate_name'], 'mandate_name') ?></div></td>
			<td><div class="nosplit"><?php print UniformLib::uniform($transaction['Compartment']['cmp_name'], 'cmp_name') ?></div></td>
		</tr>
		<?php endforeach ?>
	</tbody>
	</table><br><br>
<?php endif; endforeach ?>



</div>
</fieldset>
</body>
</html>
<!-- end limit_breach -->
