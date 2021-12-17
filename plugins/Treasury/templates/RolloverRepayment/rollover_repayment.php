<div id="notificationsContainer"></div>
<?php
echo $this->Form->input(
	'msg', array(
		'type'		=> 'hidden',
		'label'     => false,
		'value'		=> '',
		'id'		=> 'msg',
));
	echo $this->Html->script('/treasury/js/bootstrap-datepicker');
	echo $this->Html->css('/treasury/css/datepicker');
	echo $this->Html->script('/treasury/js/autoNumeric.js');
	echo $this->Html->script('/treasury/js/rolloverRepayment/rollover_repayment');

	if(!empty($updateSuccess['status'])){
		print '<div id="updateSuccess" class="alert alert-'.$updateSuccess['status'].'">'.$updateSuccess['message'].'</div>';
	}

	// FILTERS
	echo $this->Form->create('filters', array('id'=>'FiltersForm', 'class'=>'form-inline')); ?>
	
	
	<?php echo $this->Form->input('Transaction.mandate_ID', array(
		'label'		=> false, 'div' => false,
		'class' => 'mandate_ID',
		'type' => 'select',
		'empty' 	=> '-- Mandate --',
		'options' 	=> $instr_mandates,
		'default'	=> $this->Session->read('Form.data.Transaction.mandate_ID')
	)); ?>
	<?php echo $this->Form->input('Transaction.cpty_id', array(
		'label'		=> false, 'div' => false,
		'class' => 'cpty_ID',
		'type' => 'select',
		'empty' 	=> '-- Counterparty --',
		'options' 	=> $instr_counterparties,
		'default'	=> $this->Session->read('Form.data.Transaction.cpty_id')
	)); ?>
	<?php echo $this->Form->input('Transaction.cmp_ID', array(
		'label'		=> false, 'div' => false,
		'class' => 'cmp_id',
		'type' => 'select',
		'empty' 	=> '-- Compartment --',
		'options' 	=> $instr_cmp,
		'default'	=> $this->Session->read('Form.data.Transaction.cmp_ID')
	)); ?>
	<?php echo $this->Form->input('Transaction.maturity_date', array(
		'label'		=> false, 'div' => false,
		'type'=>'text',
		'class' => 'maturity_date datepckr',
		'data-date-format'=>'dd/mm/yyyy',
		'default'	=> $this->Session->read('Form.data.Transaction.maturity_date')
	)); ?>
	<input type="submit" class="btn btn-default" value="Search">
	
	<?php echo $this->Form->end(); ?>

<table id="reinvestments" class="table table-bordered table-striped table-condensed" 
data-ajaxbasepath="<?php print Router::url(array('controller' => 'treasury_rollover_repayment', 'plugin'=>'treasury', 'action'=>'/')) ?>" 
data-ajaxservicepath="<?php print Router::url(array('controller' => 'treasuryajax', 'plugin'=>'treasury', 'action'=>'/')) ?>" 
>
	<thead>
		<th class="instr_num">TRN</th>
		<th class="di">DI</th>
		<th class="cmp">Compartment</th>
		<th class="availability_date">Availability Date</th>
		<th class="ccy">CCY</th>
		<th class="principal">Principal</th>
		<th class="interest">Interest</th>
		<th class="tax">Tax</th>
		<th class="scheme">Scheme</th>
		<th class="actions">Reinvestment action</th>
	</thead>
	<tbody>
		<?php foreach ($reinv as $key => $tr): ?>
			<?php 
				$scheme_from = substr($tr['scheme'], 0,1);
				$scheme_to = substr($tr['scheme'], 1,1);
			?>
			<tr class="trn trn-<?php print $tr['trn'] ?>">
				<td class="trn"><?php print UniformLib::uniform($tr['trn'], 'trn') ?></td>
				<td class="di"><?php print UniformLib::uniform($tr['di'], 'di') ?></td>
				<td class="cmp"><?php print UniformLib::uniform($tr['cmp'], 'cmp') ?></td>
				<td class="availability_date"><?php print UniformLib::uniform($tr['availability_date'], 'availability_date') ?></td>
				<td class="ccy"><?php print UniformLib::uniform($tr['ccy'], 'ccy') ?></td>
				<td class="principal number"><?php print UniformLib::uniform($tr['principal'], 'principal_amount') ?></td>
				<td class="interest number"><?php print UniformLib::uniform($tr['interest'], 'interest_amount') ?></td>
				<td class="tax number"><?php print UniformLib::uniform($tr['tax'], 'tax_amount') ?></td>
				<td class="scheme"><?php print UniformLib::uniform($tr['scheme'], 'scheme') ?></td>
				<td class="action">
					<div class="btn-group">
					
					<?php if(in_array($tr['scheme'], array('AA','BB'))){ ?>
					  <button class="btn btn-mini" data-action="rollover_pi" data-trnum="<?php print $tr['trn'] ?>">Rollover (P+I)</button>
					<?php } ?>
					
					<?php if(in_array($tr['scheme'], array('AA','BB', 'AB'))){ ?>
					  <button class="btn btn-mini" data-action="rollover_p" data-trnum="<?php print $tr['trn'] ?>">Rollover (P)</button>
					<?php } ?>

					<?php if(in_array($tr['scheme'], array('AA','BB', 'AB'))){ ?>
					  <button class="btn btn-mini" data-action="partial_rollover" data-trnum="<?php print $tr['trn'] ?>">Partial Rollover</button>
					<?php } ?>

					<?php if(in_array($tr['scheme'], array('AA','BB', 'AB'))){ ?>
					  <button class="btn btn-mini" data-action="partial_repayment" data-trnum="<?php print $tr['trn'] ?>">Partial Repayment</button>
					<?php } ?>
					</div>
				</td>
			</tr>

			<?php if(!empty($tr['actions']['rollover_pi'])){ ?>
			
			<tr style="display: none;" class="action action-<?php print $tr['trn'] ?> action-<?php print $tr['trn'] ?>-rollover_pi" data-trnval-principal="<?php print $tr['principal'] ?>" data-trnval-interest="<?php print $tr['interest'] ?>" data-trnval-remain_principal="<?php print $tr['accountA_amount'] ?>" data-trnval-remain_interest="<?php print $tr['accountB_amount'] ?>" data-trnval-tax="<?php print $tr['tax'] ?>" data-trnval-scheme="<?php print $tr['scheme'] ?>" data-trnval-trn="<?php print $tr['trn'] ?>"  data-trnval-action="rollover_pi" data-trnval-commencementdate="<?php print $tr['availability_date'] ?>">
				<?php
				echo $this->Form->input(
					'trnval-action', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> 'rollover_pi',
				));
				echo $this->Form->input(
					'trnval-trn', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['trn'],
				));
				echo $this->Form->input(
					'trnval-scheme', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['scheme'],
				));
				echo $this->Form->input(
					'trnval-principal', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['principal'],
				));
				echo $this->Form->input(
					'trnval-interest', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['interest'],
				));
				echo $this->Form->input(
					'trnval-tax', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['tax'],
				));
				echo $this->Form->input(
					'trnval-account-a', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['accountA_amount'],
				));
				echo $this->Form->input(
					'trnval-account-b', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['accountB_amount'],
				));
				echo $this->Form->input(
					'trnval-mandate', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['mandate'],
				));
				echo $this->Form->input(
					'trnval-cmp', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['cmp_ID'],
				));
				echo $this->Form->input(
					'trnval-cpty', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['cpty_id'],
				));
				echo $this->Form->input(
					'trnval-commencement_date', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['availability_date'],
				));
				echo $this->Form->input(
					'0.controller_action', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> 'newrollover',
				));
				echo $this->Form->input(
					'0.Transaction.commencement_date', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['availability_date'],
				));
				echo $this->Form->input(
					'0.Transaction.tr_number', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['trn'],
				));
				echo $this->Form->input(
					'0.Transaction.mandate_ID', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['mandate'],
				));
				echo $this->Form->input(
					'0.Transaction.cmp_ID', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['cmp_ID'],
				));
				echo $this->Form->input(
					'0.Transaction.cpty_id', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['cpty_id'],
				));
				echo $this->Form->input(
					'0.Transaction.availability_date', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['availability_date'],
				));
				echo $this->Form->input(
					'0.Transaction.reinv_group', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['reinv_group'],
				));
				echo $this->Form->input(
					'0.Transaction.source_fund', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $scheme_from,
				));
				echo $this->Form->input(
					'0.Transaction.accountA_IBAN', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['account'.$scheme_from.'_IBAN'],
				));
				echo $this->Form->input(
					'0.Transaction.accountB_IBAN', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['account'.$scheme_to.'_IBAN'],
				));
				echo $this->Form->input(
					'0.Transaction.depo_type', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['depo_type'],
				));
				echo $this->Form->input(
					'0.Transaction.depo_renew', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> 'No',
				));
				echo $this->Form->input(
					'0.Transaction.scheme', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['scheme'],
				));
				?>
				<td colspan="10">

					<table>
						<thead><tr>
							<th class="type">Tr Type</th>
							<th class="date">Cmmt/Rep Date</th>
							<th class="ccy">CCY</th>
							<th class="amount">Amount</th>
							<th class="term">Term</th>
							<th class="scheme">Scheme</th>
						</tr></thead>
						<tbody>
							<tr class="operation" data-operation-type="rollover" data-operation-account="<?php print $scheme_from ?>">
								<td class="type">Rollover</td>
								<td class="date"><?php print $tr['availability_date'] ?></td>
								<td class="ccy"><?php print $tr['ccy'] ?></td>
								<td class="amount">
								<?php echo $this->Form->input(
									'0.Transaction.amount', array(
										'type'		=> 'text',
										'label'     => false,
										'class'		=> 'number',
										'value'		=> $tr['actions']['rollover_pi']['rollover'],
										'onchange'	=> 'closeCreateButton()',
								)); ?>
								</td>
								<td class="term">
									<?php
									$options = array(
										"ON" => 'Overnight',
										"1W" => '1W',
										"1M" => '1M',
										"2M" => '2M',
										"3M" => '3M',
										"6M" => '6M',
										"9M" => '9M',
										"1Y" => '1Y',
										"NS" => 'Non Standard',
									);
									echo $this->Form->input('0.Transaction.depo_term', array(
										'type'		=> 'select',
										'label'     => false,
										'value'		=> $tr['depo_term'],
										'options'	=> $options,
									));
								?>
								</td>
								<td class="scheme"><?php print $tr['scheme'] ?></td>
							</tr>
						</tbody>
						<tfoot>
							<tr class="separ">
								<td colspan="6">
									<table class="table">
										<tr class="account_summary account_summary_A">
											<td class="account_lbl">Account A:</td>
											<td class="account_iban" colspan="3"><?php print UniformLib::uniform($tr['accountA_IBAN'], 'accountA_IBAN') ?></td>
											<td class="account_val_lbl" >Amount left A:</td>
											<td class="number account_val">
												<?php print UniformLib::uniform($tr['accountA_amount'],'accountA_amount') ?>
											</td>
										</tr>
										<?php if(!empty($tr['accountB_IBAN'])): ?>
										<tr class="account_summary account_summary_B">
											<td class="account_lbl">Account B:</td>
											<td class="account_iban" colspan="3"><?php print UniformLib::uniform($tr['accountB_IBAN'], 'accountB_IBAN') ?></td>
											<td class="account_val_lbl">Amount left B:</td>
											<td class="number account_val">
												<?php print UniformLib::uniform($tr['accountB_amount'],'accountB_amount') ?>
											</td>
										</tr>
										<?php endif ?>
									</table>
								</td>
							</tr>
						</tfoot>
					</table>

					<table>
						<thead><tr>
							<th class="maturity_date">Maturity Date</th>
							<th class="int_rate">Int.Rate%</th>
							<th class="day_basis">Day Basis</th>
							<th class="interest">Interest</th>
							<th class="tax">Tax</th>
							<th class="reference_rate">Ref.Rate%</th>
						</tr></thead>
						<tbody>
							<tr class="opt">
								<td class="maturity_date">
								<?php echo $this->Form->input(
									'0.Transaction.maturity_date', array(
										'type'		=> 'text',
										'label'     => false,
										'class'		=> 'datepckr',
										'onchange'	=> 'closeCreateButton()',
										'data-date-format'	=> "dd/mm/yyyy",
								)); ?>
								</td>
								<td class="int_rate">
								<?php echo $this->Form->input(
									'0.Transaction.interest_rate', array(
										'type'		=> 'text',
										'label'     => false,
										'class'		=> 'rate',
										'onchange'	=> 'closeCreateButton()',
								)); ?>
								</td>
								<td class="day_basis">
									<?php
									$options = array("Act/360" => "Act/360", "Act/365" => "Act/365", "30/360" => "30E/360");
									if ($tr['cpty_id'] == 65)
									{
										$options = array( "Act/365" => "Act/365");
									}
									if ($tr['cpty_id'] == 2)
									{
										$options = array( "Act/360" => "Act/360");
									}
									$value = '';
									if ($tr['ccy'] != 'GBP' && $tr['ccy'] != 'PLN' && $tr['ccy'] != 'NOK')
									{
										$value = "Act/360";
									}
									if ($tr['ccy'] == 'GBP' || $tr['ccy'] == 'PLN' || $tr['ccy'] == 'NOK')
									{
										$value = "Act/365";
									}
									echo $this->Form->input('0.Transaction.date_basis', array(
										'type'		=> 'select',
										'label'     => false,
										'options'	=> $options,
										'value'		=> $value,
								)); ?>
								</td>
								<td class="interest">
									<?php echo $this->Form->input('0.Transaction.total_interest', array(
										'type'		=> 'text',
										'label'     => false,
										'class'		=> 'number',
										'onchange'	=> "closeCreateButton()",
										
									)); ?>
								</td>
								<td class="tax">
									<?php echo $this->Form->input('0.Transaction.tax_amount', array(
										'type'		=> 'text',
										'label'     => false,
										'class'		=> 'number',
										'onchange'	=> "closeCreateButton()",
										
									)); ?>
									<button class="refreshTaxAmount" type="button" onchange="closeCreateButton()"> <i class="icon-refresh"></i></button>
								</td>
								<td class="reference_rate">
								<?php echo $this->Form->input('0.Transaction.reference_rate', array(
										'type'		=> 'text',
										'label'     => false,
										'class'		=> 'rate',
										'onchange'	=> "closeCreateButton()",
										
									)); ?>
								</td>
							</tr>
							<tr>
								<td colspan="6">
									<div class="subactions">
										<a href="#" class="btn btn-default check">Check</a>
										<span class="checkstatus"></span> 
										<a href="#" class="btn btn-default disabled create">Create Reinvestment &amp; Transactions</a>
										<a href="#" class="btn btn-default pull-right cancel">Cancel</a>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<?php } ?>

			<?php if(!empty($tr['actions']['rollover_p'])){ ?>
			<tr style="display: none;" class="action action-<?php print $tr['trn'] ?> action-<?php print $tr['trn'] ?>-rollover_p" data-trnval-principal="<?php print $tr['principal'] ?>" data-trnval-interest="<?php print $tr['interest'] ?>" data-trnval-remain_principal="<?php print $tr['accountA_amount'] ?>" data-trnval-remain_interest="<?php print $tr['accountB_amount'] ?>" data-trnval-tax="<?php print $tr['tax'] ?>" data-trnval-scheme="<?php print $tr['scheme'] ?>" data-trnval-trn="<?php print $tr['trn'] ?>"  data-trnval-action="rollover_p" data-trnval-commencementdate="<?php print $tr['availability_date'] ?>">
				<?php
				echo $this->Form->input('trnval-action', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> 'rollover_p',
					'onchange'	=> "closeCreateButton()",
				));
				echo $this->Form->input('trnval-trn', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['trn'],
					'onchange'	=> "closeCreateButton()",
				));
				echo $this->Form->input('trnval-scheme', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['scheme'],
					'onchange'	=> "closeCreateButton()",
				));
				echo $this->Form->input('trnval-principal', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['principal'],
					'onchange'	=> "closeCreateButton()",
				));
				echo $this->Form->input('trnval-interest', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['interest'],
					'onchange'	=> "closeCreateButton()",
				));
				echo $this->Form->input('trnval-tax', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['tax'],
					'onchange'	=> "closeCreateButton()",
				));
				echo $this->Form->input('trnval-account-a', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['accountA_amount'],
					'onchange'	=> "closeCreateButton()",
				));
				echo $this->Form->input('trnval-account-b', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['accountB_amount'],
					'onchange'	=> "closeCreateButton()",
				));
				echo $this->Form->input('trnval-mandate', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['mandate'],
					'onchange'	=> "closeCreateButton()",
				));
				echo $this->Form->input('trnval-cmp', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['cmp_ID'],
					'onchange'	=> "closeCreateButton()",
				));
				echo $this->Form->input('trnval-cpty', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['cpty_id'],
					'onchange'	=> "closeCreateButton()",
				));
				echo $this->Form->input('trnval-commencement_date', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['availability_date'],
					'onchange'	=> "closeCreateButton()",
				));
				echo $this->Form->input('0.controller_action', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> 'newrollover',
				));
				echo $this->Form->input('0.Transaction.tr_number', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['trn'],
				));
				echo $this->Form->input('0.Transaction.mandate_ID', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['mandate'],
				));
				echo $this->Form->input('0.Transaction.cmp_ID', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['cmp_ID'],
				));
				echo $this->Form->input('0.Transaction.cpty_id', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['cpty_id'],
				));
				echo $this->Form->input('0.Transaction.availability_date', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['availability_date'],
				));
				echo $this->Form->input('0.Transaction.reinv_group', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['reinv_group'],
				));
				echo $this->Form->input('0.Transaction.source_fund', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $scheme_from,
				));
				echo $this->Form->input('0.Transaction.source_fund', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $scheme_from,
				));
				echo $this->Form->input('0.Transaction.accountA_IBAN', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['account'.$scheme_from.'_IBAN'],
				));
				echo $this->Form->input('0.Transaction.accountB_IBAN', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['account'.$scheme_to.'_IBAN'],
				));
				echo $this->Form->input('0.Transaction.accountB_IBAN', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['account'.$scheme_to.'_IBAN'],
				));
				echo $this->Form->input('0.Transaction.depo_type', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['depo_type'],
				));
				echo $this->Form->input('0.Transaction.depo_renew', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> "No",
				));
				echo $this->Form->input('0.Transaction.scheme', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['scheme'],
				));
				echo $this->Form->input('1.controller_action', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> 'newrepayment',
				));
				echo $this->Form->input('1.Transaction.tr_number', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['trn'],
				));
				echo $this->Form->input('1.Transaction.reinv_group', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['reinv_group'],
				));
				echo $this->Form->input('1.newrepayform.reinv_group', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['reinv_group'],
				));
				echo $this->Form->input('1.newrepayform.Source', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $scheme_to,
				));
				echo $this->Form->input('1.newrepayform.RepaymentAcc', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['account'.$scheme_to.'_IBAN'],
				));
				?>
				<td colspan="10">

					<table>
						<thead><tr>
							<th class="type">Tr Type</th>
							<th class="date">Cmmt/Rep Date</th>
							<th class="ccy">CCY</th>
							<th class="amount">Amount</th>
							<th class="term">Term</th>
							<th class="scheme">Scheme</th>
						</tr></thead>
						<tbody>
							<tr class="operation" data-operation-type="repayment" data-operation-account="<?php print $scheme_from ?>">
								<td class="type">Rollover</td>
								<td class="date"><?php print $tr['availability_date'] ?></td>
								<td class="ccy"><?php print $tr['ccy'] ?></td>
								<td class="amount">
								<?php
								echo $this->Form->input('0.Transaction.amount', array(
									'type'		=> 'text',
									'label'     => false,
									'value'		=> $tr['actions']['rollover_p']['rollover'],
									'class'		=> "number",
									'onchange'	=> 'closeCreateButton()',
								));
								?>
								</td>
								<td class="term">
									<?php
									$options = array('ON' => 'Overnight',
										"1W"=>'1W',
										"1M"=> '1M',
										"2M"=> '2M',
										"3M"=> '3M',
										"6M"=> '6M',
										"9M"=> '9M',
										"1Y"=> '1Y',
										"NS"=> 'Non Standard',
									);
									echo $this->Form->input('0.Transaction.depo_term', array(
										'type'		=> 'select',
										'label'     => false,
										'value'		=> $tr['depo_term'],
										'options'	=> $options,
									));
									?>
								</td>
								<td class="scheme"><?php print $tr['scheme'] ?></td>
							</tr>
							<tr class="operation" data-operation-type="repayment" data-operation-account="<?php print $scheme_to ?>">
								<td class="type">Repayment</td>
								<td class="date"><?php print $tr['availability_date'] ?></td>
								<td class="ccy"><?php print $tr['ccy'] ?></td>
								<td class="amount">
									<?php
									echo $this->Form->input('1.newrepayform.amount', array(
										'type'		=> 'text',
										'label'     => false,
										'class'		=> "number",
										'onchange'	=> 'closeCreateButton()',
									));
									?>
</td>
								<td class="term"></td>
								<td class="scheme"><?php print $scheme_to ?></td>
							</tr>
						</tbody>
						<tfoot>
							<tr class="separ">
								<td colspan="6">
									<table class="table">
										<tr class="account_summary account_summary_A">
											<td class="account_lbl">Account A:</td>
											<td class="account_iban" colspan="3"><?php print UniformLib::uniform($tr['accountA_IBAN'], 'accountA_IBAN') ?></td>
											<td class="account_val_lbl" >Amount left A:</td>
											<td class="number account_val">
												<?php print UniformLib::uniform($tr['accountA_amount'],'accountA_amount') ?>
											</td>
										</tr>
										<?php if(!empty($tr['accountB_IBAN'])): ?>
										<tr class="account_summary account_summary_B">
											<td class="account_lbl">Accouny B:</td>
											<td class="account_iban" colspan="3"><?php print UniformLib::uniform($tr['accountB_IBAN'], 'accountB_IBAN') ?></td>
											<td class="account_val_lbl">Amount left B:</td>
											<td class="number account_val">
												<?php print UniformLib::uniform($tr['accountB_amount'], 'accountB_amount') ?>
											</td>
										</tr>
										<?php endif ?>
									</table>
								</td>
							</tr>
						</tfoot>
					</table>

					<table>
						<thead><tr>
							<th class="maturity_date">Maturity Date</th>
							<th class="int_rate">Int.Rate%</th>
							<th class="day_basis">Day Basis</th>
							<th class="interest">Interest</th>
							<th class="tax">Tax</th>
							<th class="reference_rate">Ref.Rate%</th>
						</tr></thead>
						<tbody>
							<tr class="opt">
								<td class="maturity_date">
								<?php
									echo $this->Form->input('0.Transaction.maturity_date', array(
										'type'		=> 'text',
										'label'     => false,
										'class'		=> "datepckr",
										'onchange'	=> 'closeCreateButton()',
										'data-date-format' => "dd/mm/yyyy",
									));
									?>
								</td>
								<td class="int_rate">
								<?php
								echo $this->Form->input('0.Transaction.interest_rate', array(
									'type'		=> 'text',
									'label'     => false,
									'empty'		=> '--',
									'class'		=> 'rate',
									'onchange'	=> "closeCreateButton()",
								));
								?>
								</td>
								<td class="day_basis">
									<?php
									
									$options = array("Act/360" => "Act/360", "Act/365" => "Act/365", "30/360" => "30E/360");
									if ($tr['cpty_id'] == 65)
									{
										$options = array( "Act/365" => "Act/365");
									}
									if ($tr['cpty_id'] == 2)
									{
										$options = array( "Act/360" => "Act/360");
									}
									$value = '';
									if ($tr['ccy'] != 'GBP' && $tr['ccy'] != 'PLN' && $tr['ccy'] != 'NOK')
									{
										$value = "Act/360";
									}
									if ($tr['ccy'] == 'GBP' || $tr['ccy'] == 'PLN' || $tr['ccy'] == 'NOK')
									{
										$value = "Act/365";
									}
									echo $this->Form->input('0.Transaction.date_basis', array(
										'type'		=> 'select',
										'label'     => false,
										'value'		=> $value,
										'empty'		=> '--',
										'options'	=> $options,
									));
									?>
								</td>
								<td class="interest">
								<?php
									echo $this->Form->input('0.Transaction.total_interest', array(
										'type'		=> 'text',
										'label'     => false,
										'class'		=> "number",
										'onchange'	=> 'closeCreateButton()',
									));
								?>
								<td class="tax">
								<?php
									echo $this->Form->input('0.Transaction.tax_amount', array(
										'type'		=> 'text',
										'label'     => false,
										'class'		=> "number",
										'onchange'	=> 'closeCreateButton()',
									));
								?>
									<button class="refreshTaxAmount" type="button"> <i class="icon-refresh"></i></button>
								</td>
								<td class="reference_rate">
								<?php
									echo $this->Form->input('0.Transaction.reference_rate', array(
										'type'		=> 'text',
										'label'     => false,
										'class'		=> "rate",
										'onchange'	=> 'closeCreateButton()',
									));
								?>
							</tr>
							<tr>
								<td colspan="6">
									<div class="subactions">
										<a href="#" class="btn btn-default check">Check</a>
										<span class="checkstatus"></span> 
										<a href="#" class="btn btn-default disabled create">Create Reinvestment &amp; Transactions</a>
										<a href="#" class="btn btn-default pull-right cancel">Cancel</a>
									</div>
								</td>
							</tr>
						</tbody>
					</table>

				</td>
			</tr>
			<?php } ?>

			<?php if(!empty($tr['actions']['partial_rollover'])){ ?>
			<tr style="display: none;" class="action action-<?php print $tr['trn'] ?> action-<?php print $tr['trn'] ?>-partial_rollover" data-trnval-principal="<?php print $tr['principal'] ?>" data-trnval-interest="<?php print $tr['interest'] ?>" data-trnval-remain_principal="<?php print $tr['accountA_amount'] ?>" data-trnval-remain_interest="<?php print $tr['accountB_amount'] ?>" data-trnval-tax="<?php print $tr['tax'] ?>" data-trnval-scheme="<?php print $tr['scheme'] ?>" data-trnval-trn="<?php print $tr['trn'] ?>"  data-trnval-action="partial_rollover" data-trnval-commencementdate="<?php print $tr['availability_date'] ?>">
				<?php
				echo $this->Form->input('trnval-action', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> "partial_rollover",
				));
				echo $this->Form->input('trnval-trn', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['trn'],
				));
				echo $this->Form->input('trnval-scheme', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['scheme'],
				));
				echo $this->Form->input('trnval-principal', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['principal'],
				));
				echo $this->Form->input('trnval-principal', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['principal'],
				));
				echo $this->Form->input('trnval-interest', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['interest'],
				));
				echo $this->Form->input('trnval-tax', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['tax'],
				));
				echo $this->Form->input('trnval-account-a', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['accountA_amount'],
				));
				echo $this->Form->input('trnval-account-b', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['accountB_amount'],
				));
				echo $this->Form->input('trnval-mandate', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['mandate'],
				));
				echo $this->Form->input('trnval-cmp', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['cmp_ID'],
				));
				echo $this->Form->input('trnval-cpty', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['cpty_id'],
				));
				echo $this->Form->input('trnval-commencement_date', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['availability_date'],
				));
				echo $this->Form->input('0.controller_action', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> "newrollover",
				));
				echo $this->Form->input('0.Transaction.commencement_date', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['availability_date'],
				));
				echo $this->Form->input('0.Transaction.tr_number', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['trn'],
				));
				echo $this->Form->input('0.Transaction.mandate_ID', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['mandate'],
				));
				echo $this->Form->input('0.Transaction.cmp_ID', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['cmp_ID'],
				));
				echo $this->Form->input('0.Transaction.cpty_id', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['cpty_id'],
				));
				echo $this->Form->input('0.Transaction.availability_date', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['availability_date'],
				));
				echo $this->Form->input('0.Transaction.reinv_group', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['reinv_group'],
				));
				echo $this->Form->input('0.Transaction.source_fund', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $scheme_from,
				));
				echo $this->Form->input('0.Transaction.accountA_IBAN', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['account'.$scheme_from.'_IBAN'],
				));
				echo $this->Form->input('0.Transaction.accountB_IBAN', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['account'.$scheme_to.'_IBAN'],
				));
				echo $this->Form->input('0.Transaction.depo_type', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['depo_type'],
				));
				echo $this->Form->input('0.Transaction.depo_renew', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> 'No',
				));
				echo $this->Form->input('0.Transaction.scheme', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['scheme'],
				));
				
				?>
				<?php if($tr['scheme']!='AB'){
				
				
					echo $this->Form->input('1.controller_action', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> "newrepayment",
					));
					echo $this->Form->input('1.Transaction.tr_number', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['trn'],
					));
					echo $this->Form->input('1.Transaction.reinv_group', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['reinv_group'],
					));
					echo $this->Form->input('1.newrepayform.reinv_group', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['reinv_group'],
					));
					echo $this->Form->input('1.newrepayform.Source', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $scheme_from,
					));
					echo $this->Form->input('1.newrepayform.RepaymentAcc', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['account'.$scheme_from.'_IBAN'],
					));
				}else{

					echo $this->Form->input('1.controller_action', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> 'newrepayment',
					));
					echo $this->Form->input('1.Transaction.tr_number', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['trn'],
					));
					echo $this->Form->input('1.Transaction.reinv_group', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['reinv_group'],
					));
					echo $this->Form->input('1.newrepayform.Source', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $scheme_from,
					));
					echo $this->Form->input('1.newrepayform.RepaymentAcc', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['account'.$scheme_from.'_IBAN'],
					));

					echo $this->Form->input('2.controller_action', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> "newrepayment",
					));
					echo $this->Form->input('2.Transaction.tr_number', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['trn'],
					));
					echo $this->Form->input('2.Transaction.reinv_group', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['reinv_group'],
					));
					echo $this->Form->input('2.newrepayform.reinv_group', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['reinv_group'],
					));
					echo $this->Form->input('2.newrepayform.Source', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $scheme_to,
					));
					echo $this->Form->input('2.newrepayform.RepaymentAcc', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['account'.$scheme_to.'_IBAN'],
					));
				}
				?>
				<td colspan="10">
					<table>
						<thead><tr>
							<th class="type">Tr Type</th>
							<th class="date">Cmmt/Rep Date</th>
							<th class="ccy">CCY</th>
							<th class="amount">Amount</th>
							<th class="term">Term</th>
							<th class="scheme">Scheme</th>
						</tr></thead>
						<tbody>
							<tr class="operation" data-operation-type="rollover" data-operation-account="<?php print $scheme_from ?>">
								<td class="type">Rollover</td>
								<td class="date"><?php print $tr['availability_date'] ?></td>
								<td class="ccy"><?php print $tr['ccy'] ?></td>
								<td class="amount">
								<?php
								echo $this->Form->input('0.Transaction.amount', array(
									'type'		=> 'text',
									'label'     => false,
									'value'		=> $tr['actions']['partial_rollover']['rollover'],
									'class'		=> 'number',
									"onchange"	=> "closeCreateButton()",
								));
								?>
								</td>
								<td class="term">
									<?php
									$options = array(
										"ON" => 'Overnight',
										"1W" => '1W',
										"1M" => '1M',
										"2M" => '2M',
										"3M" => '3M',
										"6M" => '6M',
										"9M" => '9M',
										"1Y" => '1Y',
										"NS" => 'Non Standard',
									);
									echo $this->Form->input('0.Transaction.depo_term', array(
										'type'		=> 'select',
										'label'     => false,
										'value'		=> $tr['depo_term'],
										'options'	=> $options,
									));
								?>
								</td>
								<td class="scheme"><?php print $tr['scheme'] ?></td>
							</tr>
					<?php if($tr['scheme']!='AB'){ ?>
							<tr class="operation" data-operation-type="repayment" data-operation-account="<?php print $scheme_to ?>">
								<td class="type">Repayment</td>
								<td class="date"><?php print $tr['availability_date'] ?></td>
								<td class="ccy"><?php print $tr['ccy'] ?></td>
								<td class="amount">
								
								<?php
									echo $this->Form->input('1.newrepayform.amount', array(
										'type'		=> 'text',
										'label'     => false,
										'value'		=> $tr['actions']['partial_rollover']['repayment'],
										'class'		=> "number",
									));
								?>
								</td>
								<td class="term"></td>
								<td class="scheme"><?php print $scheme_to ?></td>
							</tr>
					<?php }else{ ?>
							<tr class="operation" data-operation-type="repayment" data-operation-account="<?php print $scheme_from ?>">
								<td class="type">Repayment</td>
								<td class="date"><?php print $tr['availability_date'] ?></td>
								<td class="ccy"><?php print $tr['ccy'] ?></td>
								<td class="amount">
								<?php
									echo $this->Form->input('1.newrepayform.amount', array(
										'type'		=> 'text',
										'label'     => false,
										'value'		=> $tr['actions']['partial_rollover']['repayment'],
										'class'		=> "number",
										'onchange'	=> "closeCreateButton()",
									));
								?>
								</td>
								<td class="term"></td>
								<td class="scheme"><?php print $scheme_from ?></td>
							</tr>
							<tr class="operation" data-operation-type="repayment" data-operation-account="<?php print $scheme_to ?>">
								<td class="type">Repayment</td>
								<td class="date"><?php print $tr['availability_date'] ?></td>
								<td class="ccy"><?php print $tr['ccy'] ?></td>
								<td class="amount">
								<?php
									echo $this->Form->input('2.newrepayform.amount', array(
										'type'		=> 'text',
										'label'     => false,
										'value'		=> $tr['actions']['partial_rollover']['repayment_b'],
										'class'		=> "number",
										'onchange'	=> "closeCreateButton()",
									));
								?>
								</td>
								<td class="term"></td>
								<td class="scheme"><?php print $scheme_to ?></td>
							</tr>
					<?php } ?>
						</tbody>
						<tfoot>
							<tr class="separ">
								<td colspan="6">
									<table class="table">
										<tr class="account_summary account_summary_A">
											<td class="account_lbl">Account A:</td>
											<td class="account_iban" colspan="3"><?php print UniformLib::uniform($tr['accountA_IBAN'], 'accountA_IBAN') ?></td>
											<td class="account_val_lbl" >Amount left A:</td>
											<td class="number account_val">
												<?php print UniformLib::uniform($tr['accountA_amount'], 'accountA_amount') ?>
											</td>
										</tr>
										<?php if(!empty($tr['accountB_IBAN'])): ?>
										<tr class="account_summary account_summary_B">
											<td class="account_lbl">Accouny B:</td>
											<td class="account_iban" colspan="3"><?php print UniformLib::uniform($tr['accountB_IBAN'], 'accountB_IBAN') ?></td>
											<td class="account_val_lbl">Amount left B:</td>
											<td class="number account_val">
												<?php print UniformLib::uniform($tr['accountB_amount'],'accountB_amount') ?>
											</td>
										</tr>
										<?php endif ?>
									</table>
								</td>
							</tr>
						</tfoot>
					</table>

					<table>
						<thead><tr>
							<th class="maturity_date">Maturity Date</th>
							<th class="int_rate">Int.Rate%</th>
							<th class="day_basis">Day Basis</th>
							<th class="interest">Interest</th>
							<th class="tax">Tax</th>
							<th class="reference_rate">Ref.Rate%</th>
						</tr></thead>
						<tbody>
							<tr class="opt">
								<td class="maturity_date">
								<?php
									echo $this->Form->input('0.Transaction.maturity_date', array(
										'type'		=> 'text',
										'label'     => false,
										'class'		=> "datepckr",
										'onchange'	=> "closeCreateButton()",
										'data-date-format' => "dd/mm/yyyy",
									));
								?>
								</td>
								<td class="int_rate">
								<?php
									echo $this->Form->input('0.Transaction.interest_rate', array(
										'type'		=> 'text',
										'label'     => false,
										'onchange'	=> "closeCreateButton()",
										'class'		=> "rate",
									));
								?>
								</td>
								<td class="day_basis">
								<?php
									$value = '';
									if ($tr['ccy'] != 'GBP' && $tr['ccy'] != 'PLN' && $tr['ccy'] != 'NOK')
									{
										$value = "Act/360";
									}
									if ($tr['ccy'] == 'GBP' || $tr['ccy'] == 'PLN' || $tr['ccy'] == 'NOK')
									{
										$value = "Act/365";
									}
									$options = array("Act/360" => "Act/360", "Act/365" => "Act/365", "30/360" => "30E/360");
									if ($tr['cpty_id'] == 65)
									{
										$options = array( "Act/365" => "Act/365");
									}
									if ($tr['cpty_id'] == 2)
									{
										$options = array( "Act/360" => "Act/360");
									}
									echo $this->Form->input('0.Transaction.date_basis', array(
										'type'		=> 'select',
										'label'     => false,
										'onchange'	=> "closeCreateButton()",
										'value'		=> $value,
										'options'	=> $options,
									));
								?>
								</td>
								<td class="interest">
								<?php
								echo $this->Form->input('0.Transaction.total_interest', array(
									'type'		=> 'text',
									'label'     => false,
									'onchange'	=> "closeCreateButton()",
									'class'	=> 'number',
								));
								?>
								</td>
								<td class="tax">
									<?php
									echo $this->Form->input('0.Transaction.tax_amount', array(
										'type'		=> 'text',
										'label'     => false,
										'onchange'	=> "closeCreateButton()",
										'class'	=> 'number',
									));
									?>
									<button class="refreshTaxAmount" type="button"> <i class="icon-refresh"></i></button>
								</td>
								<td class="reference_rate">
								<?php
								echo $this->Form->input('0.Transaction.reference_rate', array(
									'type'		=> 'text',
									'label'     => false,
									'onchange'	=> "closeCreateButton()",
									'class'	=> 'rate',
								));
								?>
								</td>
							</tr>
							<tr>
								<td colspan="6">
									<div class="subactions">
										<a href="#" class="btn btn-default check">Check</a>
										<span class="checkstatus"></span> 
										<a href="#" class="btn btn-default disabled create">Create Reinvestment &amp; Transactions</a>
										<a href="#" class="btn btn-default pull-right cancel">Cancel</a>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<?php } ?>

			<?php if(!empty($tr['actions']['partial_repayment'])){ ?>
			<tr style="display: none;" class="action action-<?php print $tr['trn'] ?> action-<?php print $tr['trn'] ?>-partial_repayment" data-trnval-principal="<?php print $tr['principal'] ?>" data-trnval-interest="<?php print $tr['interest'] ?>" data-trnval-remain_principal="<?php print $tr['accountA_amount'] ?>" data-trnval-remain_interest="<?php print $tr['accountB_amount'] ?>" data-trnval-tax="<?php print $tr['tax'] ?>" data-trnval-scheme="<?php print $tr['scheme'] ?>" data-trnval-trn="<?php print $tr['trn'] ?>"  data-trnval-action="partial_repayment" data-trnval-commencementdate="<?php print $tr['availability_date'] ?>">
				
				<?php
				echo $this->Form->input('trnval-action', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> 'partial_repayment',
				));
				echo $this->Form->input('trnval-trn', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['trn'],
				));
				echo $this->Form->input('trnval-scheme', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['scheme'],
				));
				echo $this->Form->input('trnval-principal', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['principal'],
				));
				echo $this->Form->input('trnval-interest', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['interest'],
				));
				echo $this->Form->input('trnval-tax', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['tax'],
				));
				echo $this->Form->input('trnval-account-a', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['accountA_amount'],
				));
				echo $this->Form->input('trnval-account-b', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['accountB_amount'],
				));
				echo $this->Form->input('trnval-mandate', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['mandate'],
				));
				echo $this->Form->input('trnval-cmp', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['cmp_ID'],
				));
				echo $this->Form->input('trnval-cpty', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['cpty_id'],
				));
				echo $this->Form->input('trnval-commencement_date', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['availability_date'],
				));
				echo $this->Form->input('1.controller_action', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> "newrollover",
				));
				echo $this->Form->input('1.Transaction.commencement_date', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['availability_date'],
				));
				echo $this->Form->input('1.Transaction.tr_number', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['trn'],
				));
				echo $this->Form->input('1.Transaction.reinv_group', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['reinv_group'],
				));
				echo $this->Form->input('1.Transaction.reinv_group', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['reinv_group'],
				));
				echo $this->Form->input('1.Transaction.source_fund', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $scheme_from,
				));
				echo $this->Form->input('1.Transaction.accountA_IBAN', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['account'.$scheme_from.'_IBAN'],
				));
				echo $this->Form->input('1.Transaction.accountB_IBAN', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['account'.$scheme_to.'_IBAN'],
				));
				echo $this->Form->input('1.Transaction.accountB_IBAN', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['account'.$scheme_to.'_IBAN'],
				));
				echo $this->Form->input('1.Transaction.depo_type', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['depo_type'],
				));
				echo $this->Form->input('1.Transaction.depo_renew', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> 'No',
				));
				echo $this->Form->input('1.Transaction.scheme', array(
					'type'		=> 'hidden',
					'label'     => false,
					'value'		=> $tr['scheme'],
				));
				if($tr['scheme']!='AB'){
					echo $this->Form->input('0.controller_action', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> "newrepayment",
					));
					echo $this->Form->input('0.Transaction.tr_number', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['trn'],
					));
					echo $this->Form->input('0.Transaction.mandate_ID', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['mandate'],
					));
					echo $this->Form->input('0.Transaction.cmp_ID', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['cmp_ID'],
					));
					echo $this->Form->input('0.Transaction.cpty_id', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['cpty_id'],
					));
					echo $this->Form->input('0.Transaction.availability_date', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['availability_date'],
					));
					echo $this->Form->input('0.Transaction.reinv_group', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['reinv_group'],
					));
					echo $this->Form->input('0.newrepayform.reinv_group', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['reinv_group'],
					));
					echo $this->Form->input('0.newrepayform.Source', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $scheme_to,
					));
					echo $this->Form->input('0.newrepayform.RepaymentAcc', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['account'.$scheme_to.'_IBAN'],
					));
				}else{
					echo $this->Form->input('0.controller_action', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> "newrepayment",
					));
					echo $this->Form->input('0.Transaction.tr_number', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['trn'],
					));
					echo $this->Form->input('0.Transaction.mandate_ID', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['mandate'],
					));
					echo $this->Form->input('0.Transaction.cmp_ID', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['cmp_ID'],
					));
					echo $this->Form->input('0.Transaction.cpty_id', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['cpty_id'],
					));
					echo $this->Form->input('0.Transaction.availability_date', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['availability_date'],
					));
					echo $this->Form->input('0.Transaction.reinv_group', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['reinv_group'],
					));
					echo $this->Form->input('0.newrepayform.reinv_group', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['reinv_group'],
					));
					echo $this->Form->input('0.newrepayform.Source', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $scheme_from,
					));
					echo $this->Form->input('0.newrepayform.RepaymentAcc', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['account'.$scheme_from.'_IBAN'],
					));
					echo $this->Form->input('2.controller_action', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> "newrepayment",
					));
					echo $this->Form->input('2.Transaction.tr_number', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['trn'],
					));
					echo $this->Form->input('2.Transaction.reinv_group', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['reinv_group'],
					));
					echo $this->Form->input('2.newrepayform.reinv_group', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['reinv_group'],
					));
					echo $this->Form->input('2.newrepayform.Source', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $scheme_to,
					));
					echo $this->Form->input('2.newrepayform.RepaymentAcc', array(
						'type'		=> 'hidden',
						'label'     => false,
						'value'		=> $tr['account'.$scheme_to.'_IBAN'],
					));
				}
				?>
				
				<td colspan="10">
					<table>
						<thead><tr>
							<th class="type">Tr Type</th>
							<th class="date">Cmmt/Rep Date</th>
							<th class="ccy">CCY</th>
							<th class="amount">Amount</th>
							<th class="term">Term</th>
							<th class="scheme">Scheme</th>
						</tr></thead>
						<tbody>
							<tr class="operation" data-operation-type="repayment" data-operation-account="<?php print $scheme_from ?>">
								<td class="type">Repayment</td>
								<td class="date"><?php print $tr['availability_date'] ?></td>
								<td class="ccy"><?php print $tr['ccy'] ?></td>
								<td class="amount">
								<?php
								echo $this->Form->input('0.newrepayform.amount', array(
									'type'		=> 'text',
									'label'     => false,
									'value'		=> $tr['actions']['partial_repayment']['repayment'],
									'class'		=> "number",
									'onchange'	=> 'closeCreateButton()',
								));
								?>
								</td>
								<td class="term"></td>
								<td class="scheme"><?php print $scheme_from ?></td>
							</tr>
							<tr class="operation" data-operation-type="rollover" data-operation-account="<?php print $scheme_from ?>">
								<td class="type">Rollover</td>
								<td class="date"><?php print $tr['availability_date'] ?></td>
								<td class="ccy"><?php print $tr['ccy'] ?></td>
								<td class="amount">
								<?php
								echo $this->Form->input('1.Transaction.amount', array(
									'type'		=> 'text',
									'label'     => false,
									'value'		=> $tr['actions']['partial_repayment']['rollover'],
									'class'		=> "number",
									'onchange'	=> 'closeCreateButton()',
								));
								?>
								</td>
								<td class="term">
									<?php
									$options = array(
										"ON" => 'Overnight',
										"1W" => '1W',
										"1M" => '1M',
										"2M" => '2M',
										"3M" => '3M',
										"6M" => '6M',
										"9M" => '9M',
										"1Y" => '1Y',
										"NS" => 'Non Standard',
									);
									echo $this->Form->input('1.Transaction.depo_term', array(
										'type'		=> 'select',
										'label'     => false,
										'value'		=> $tr['depo_term'],
										'options'	=> $options,
									));
								?>
								</td>
								<td class="scheme"><?php print $tr['scheme'] ?></td>
							</tr>
					<?php if($tr['scheme']=='AB'){ ?>
							<tr class="operation" data-operation-type="repayment" data-operation-account="<?php print $scheme_to ?>">
								<td class="type">Repayment</td>
								<td class="date"><?php print $tr['availability_date'] ?></td>
								<td class="ccy"><?php print $tr['ccy'] ?></td>
								<td class="amount">
								<?php
								echo $this->Form->input('2.newrepayform.amount', array(
									'type'		=> 'text',
									'label'     => false,
									'value'		=> $tr['actions']['partial_repayment']['repayment_b'],
									'class'		=> "number",
									'onchange'	=> 'closeCreateButton()',
								));
								?>
								</td>
								<td class="term"></td>
								<td class="scheme"><?php print $scheme_to ?></td>
							</tr>
					<?php } ?>
						</tbody>
						<tfoot>
							<tr class="separ">
								<td colspan="6">
									<table class="table">
										<tr class="account_summary account_summary_A">
											<td class="account_lbl">Account A:</td>
											<td class="account_iban" colspan="3"><?php print UniformLib::uniform($tr['accountA_IBAN'], 'accountA_IBAN') ?></td>
											<td class="account_val_lbl" >Amount left A:</td>
											<td class="number account_val">
												<?php print UniformLib::uniform($tr['accountA_amount'], 'accountA_amount') ?>
											</td>
										</tr>
										<?php if(!empty($tr['accountB_IBAN'])): ?>
										<tr class="account_summary account_summary_B">
											<td class="account_lbl">Accouny B:</td>
											<td class="account_iban" colspan="3"><?php print UniformLib::uniform($tr['accountB_IBAN'], 'accountB_IBAN') ?></td>
											<td class="account_val_lbl">Amount left B:</td>
											<td class="number account_val">
												<?php print UniformLib::uniform($tr['accountB_amount'],'accountB_amount') ?>
											</td>
										</tr>
										<?php endif ?>
									</table>
								</td>
							</tr>
						</tfoot>
					</table>

					<table>
						<thead><tr>
							<th class="maturity_date">Maturity Date</th>
							<th class="int_rate">Int.Rate%</th>
							<th class="day_basis">Day Basis</th>
							<th class="interest">Interest</th>
							<th class="tax">Tax</th>
							<th class="reference_rate">Ref.Rate%</th>
						</tr></thead>
						<tbody>
							<tr class="opt"><td colspan="6">&nbsp;</td></tr>
							<tr class="opt">
								<td class="maturity_date">
								<?php
								echo $this->Form->input('1.Transaction.maturity_date', array(
									'type'		=> 'text',
									'label'     => false,
									'class'		=> "datepckr",
									'onchange'	=> 'closeCreateButton()',
									'data-date-format' => "dd/mm/yyyy",
								));
								?>
								</td>
								<td class="int_rate">
								
								<?php
								echo $this->Form->input('1.Transaction.interest_rate', array(
									'type'		=> 'text',
									'label'     => false,
									'class'		=> "rate",
									'onchange'	=> 'closeCreateButton()',
								));
								?>
								</td>
								<td class="day_basis">
									<?php
									$value = '';
									if ($tr['ccy'] != 'GBP' && $tr['ccy'] != 'PLN' && $tr['ccy'] != 'NOK')
									{
										$value = "Act/360";
									}
									if ($tr['ccy'] == 'GBP' || $tr['ccy'] == 'PLN' || $tr['ccy'] == 'NOK')
									{
										$value = "Act/365";
									}
									$options = array("Act/360" => "Act/360", "Act/365" => "Act/365", "30/360" => "30E/360");
									if ($tr['cpty_id'] == 65)
									{
										$options = array( "Act/365" => "Act/365");
									}
									if ($tr['cpty_id'] == 2)
									{
										$options = array( "Act/360" => "Act/360");
									}
									echo $this->Form->input('1.Transaction.date_basis', array(
										'type'		=> 'select',
										'label'     => false,
										'onchange'	=> "closeCreateButton()",
										'value'		=> $value,
										'options'	=> $options,
										'empty'		=> '--',
									));
								?>
								</td>
								<td class="interest">
								<?php
								echo $this->Form->input('1.Transaction.total_interest', array(
									'type'		=> 'text',
									'label'     => false,
									'class'		=> "number",
									'onchange'	=> 'closeCreateButton()',
								));
								?>
								</td>
								<td class="tax">
									<?php
									echo $this->Form->input('1.Transaction.tax_amount', array(
										'type'		=> 'text',
										'label'     => false,
										'class'		=> "number",
										'onchange'	=> 'closeCreateButton()',
									));
									?>
									<button class="refreshTaxAmount" type="button"> <i class="icon-refresh"></i></button>
								</td>
								<td class="reference_rate">
								<?php
									echo $this->Form->input('1.Transaction.reference_rate', array(
										'type'		=> 'text',
										'label'     => false,
										'class'		=> "rate",
										'onchange'	=> 'closeCreateButton()',
									));
									?>
								</td>
							</tr>
							<tr>
								<td colspan="6">
									<div class="subactions">
										<a href="#" class="btn btn-default check">Check</a>
										<span class="checkstatus"></span> 
										<a href="#" class="btn btn-default disabled create">Create Reinvestment &amp; Transactions</a>
										<a href="#" class="btn btn-default pull-right cancel">Cancel</a>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<?php } ?>
			<tr style="display: none;" class="results results-<?php print $tr['trn'] ?>">
				<td colspan="10"></td>
			</tr>
			
		<?php endforeach ?>
	</tbody>
</table>
<div class="to_empty" id="week_end_date_error">
</div>
<div class="pagination-infos">
	<?php echo $this->Paginator->counter(
	    'Page {:page} of {:pages}, showing {:current} records out of
	     {:count} total, starting on record {:start}, ending on {:end}'
	); ?>
	<?php if(intval($this->Paginator->counter('{:pages}'))>1): ?>
		<div class="pagination">
		    <ul>
		        <?php 
		            echo $this->Paginator->prev( '<<', array( 'class' => '', 'tag' => 'li' ), null, array( 'class' => 'disabled myclass', 'tag' => 'li' ) );
		            echo $this->Paginator->numbers( array( 'tag' => 'li', 'separator' => '', 'currentClass' => 'disabled myclass' ) );
		            echo $this->Paginator->next( '>>', array( 'class' => '', 'tag' => 'li' ), null, array( 'class' => 'disabled myclass', 'tag' => 'li' ) );
		        ?>
		    </ul>
		</div>
	<?php endif ?>
</div>

<style type="text/css">

	table input[type="text"]{ width: 92%; height: 20px !important; background: #fff; border: 0; box-shadow: none !important; outline: none 0 !important; margin: 0; border: #aaa 1px solid; }
	table input.datepckr{ width: 70px;}
	table select{ width: 80px; margin: 0; }
	table select.cmp{ width: 150px; }
	table .input-prepend{ margin: 0; }
	table td{ vertical-align: middle !important; }

	table table{ width: 50%; float: left; }
	table table th,
	table table td{ border: 0 !important; }

	#FiltersForm{ margin-bottom: 20px; }
	#reinvestments table td{ vertical-align: middle !important; }

	#reinvestments tbody tr.successcheck td,
	#reinvestments tbody tr.success td{ background-color: #DFF0D8; }
	#reinvestments tbody tr.error td{ background-color: #f2dede; }

	#reinvestments td.spreada{ position: relative; }
	#reinvestments td.spreada input{ margin-right: 40px; background: none transparent; border: 0; color: #232323 !important;}
	#reinvestments td.spreada button{ position: absolute; top: 7px; right: 4%; }

	#reinvestments tfoot{ border-top: 2px solid #ccc;}
	#reinvestments tfoot > tr > td{ padding: 0; }
	#reinvestments tfoot table{ margin: 0; }

	.actions .btn.pull-right{ margin-left: 10px; }

	td.account_lbl{ min-width: 62px; }
	th.date{ min-width: 90px; }
	th.cmp{ min-width: 190px; }
	th.availabillity_date{ min-width: 90px; }
	td.term select{ width: 52px; }

	input.datepckr{ 
		width: 70px;
		height: 20px !important;
	    margin: 0;
	}

	.rate,
	.number{ text-align: right !important; }
	.amount input{ width: 139px !important; }
	#reinvestments tr.action{ display: none; position: relative; }
	#reinvestments tr.action.visible{ display: table-row; background-color: #dadada; }

	#reinvestments tr.trn.active{ display: table-row; background-color: #dadada; }
	#reinvestments tr.trn.active tr,
	#reinvestments tr.trn.active td{ background-color: transparent !important; border-color: #ccc; } 

	#reinvestments tr.action.visible table,
	#reinvestments tr.action.visible tr,
	#reinvestments tr.action.visible td{ background-color: transparent !important; border-color: #ccc; }
	#reinvestments tr td{ height: 30px; }
	#reinvestments tr.results{ display: none; background-color: #dadada; }
	#reinvestments tr.results .alert{ margin-bottom: 5px !important; }
	#reinvestments tr.results.visible{ display: table-row; }

	input.rate.astext,
	input.number.astext{ border: 0; ountline: 0; background: transparent; width: 100px; color: #323232;}

	tr.action{ position: relative; }
	tr.action > td{ background-color: transparent !important; position: relative;  }
	tr.action .subactions{ position: absolute; bottom: 10px; right: 10px; }
	tr.action .subactions .checkstatus{ margin-left: 10px; width: 20px; text-align: center; display: inline-block; }
	tr.action .subactions .btn{ margin-left: 10px; }

	#reinvestments .error{ color: #f00 !important; }

	#reinvestments td.tax{ position: relative; }
	#reinvestments td.tax input{ width: 55%; }
	#reinvestments td.tax button{ position: absolute; top: 5px; width: 30%; right: 2%; }
	#reinvestments td.int_rate input{ width: 40px; }
	#reinvestments td.reference_rate input{ width: 40px; }

	#reinvestments .account_summary .account_val{ width: 100px; }

	#week_end_date_error { color: red; }
	.amount .number{ width: 144px !important;}

</style>
<script>
	<?php
	if (isset($UserIsInCheckerGroup) && $UserIsInCheckerGroup)
	{
		echo "var UserIsInCheckerGroup = true;";
	}
	else
	{
		echo "var UserIsInCheckerGroup = false;";
	}
?>

Date.prototype.addDays = function(days)
{
    var dat = new Date(this.valueOf());
    dat.setDate(dat.getDate() + days);
    return dat;
}

function isWeekEnd(date)
{
	var date_array = date.split("/");
	var date_YMD = date_array[2] + '-' + date_array[1] + '-' + date_array[0];
	var d = new Date();
	d.setTime(Date.parse(date_YMD));
	var n = d.getDay();
	return ((n == 6) || (n == 0));
}

function indicative_maturity_date_week_end(e,id)
{
	var term2days = {
	'ON' : 1,
	'1W' : 7,
	'1M' : 30,
	'2M' : 60,
	'3M' : 90,
	'6M' : 180,
	'9M' : 270,
	'1Y' : 360,
	};
	for (var i = 0 ; i <= 1 ; i++)
	{
		var matu_date = $(".visible input[name='data["+i+"][Transaction][maturity_date]']").val();
		if (matu_date != ""  )//if no maturity date
		{
			$("#ind"+i).remove();
			//return 0;
		}
		else
		{
			$("#ind"+i).remove();
			var translation = $(".visible select[name='data["+i+"][Transaction][depo_term]'] option:selected").val();
			var val = $(".visible .operation .date").first().text();//commencement date
			var split = val.split('/');
			var month = split[1] - 1;
			var day = split[0];
			var year = split[2];
			var date = new Date();
			date.setFullYear(year);
			date.setMonth(month);
			date.setDate(day);
			date = date.addDays(term2days[translation]);
			var dday = date.getDate();
			if (dday < 10)
			{
				dday = "0"+dday;
			}
			var dmonth = (date.getMonth()+1);
			if (dmonth < 10)
			{
				dmonth = "0"+dmonth;
			}
			var dyear = date.getFullYear();
			var date_formated = dday+"/"+dmonth+"/"+dyear;
			if ( isWeekEnd(date_formated) )
			{
				if ($("#ind"+i).length < 1)
				{
					$("#week_end_date_error").append("<p id='ind"+i+"' class='maturity'>The indicative maturity date of "+date_formated+" falls on a weekend.</p>");
				}
			}
		}
	}
}


$(document).ready(function(e){
	$('.amount .number').autoNumeric('init',{aSep: ',',aDec: '.', vMax: 9999999999999.99, vMin:-9999999999999.99});

	$(".datepckr").change(function(e){
		var el = $(e.target);
		if (el.val() == "")
		{
			// no maturity date => maybe indicative maturity date
			indicative_maturity_date_week_end($("select[name='data[0][Transaction][depo_term]']"), 'row1');
		}
		else
		{
			$("#ind0").remove();
			$("#ind1").remove();
		}
		if ( isWeekEnd(el.val()) )
		{
			if ($("#mat").length < 1)
			{
				$("#ind0").remove();
				$("#ind1").remove();
				$("#week_end_date_error").append("<p id='mat'>The maturity date of "+el.val()+" falls on a weekend.</p>");
			}
		}
		else
		{
			$("#mat").remove();
		}
	});
	
	
	$("select[name='data[0][Transaction][depo_term]']").bind("change keyup copy paste cut focusout",function(e)
	{
		indicative_maturity_date_week_end($("select[name='data[0][Transaction][depo_term]']"), 'row1');
	});
	
	$("select[name='data[1][Transaction][depo_term]']").bind("change keyup copy paste cut focusout",function(e)
	{
		indicative_maturity_date_week_end($("select[name='data[1][Transaction][depo_term]']"), 'row1');
	});
});

</script>

<div style="display:none;">
<?php
echo $this->Form->create('reinv_op', array('url'=>'/treasury/treasury_rollover_repayment/reinv_op'));
echo $this->Form->input('controller_action', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.tr_number', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.mandate_ID', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.cmp_ID', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.cpty_id', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.availability_date', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.reinv_group', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.source_fund', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.accountA_IBAN', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.accountB_IBAN', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.depo_type', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.depo_renew', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.scheme', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.amount', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.depo_term', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.maturity_date', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.interest_rate', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.date_basis', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.total_interest', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.tax_amount', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.reference_rate', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('checkonly', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('newrepayform.amount', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('newrepayform.RepaymentAcc', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('newrepayform.Source', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('newrepayform.reinv_group', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();
echo $this->Form->create('reinv_open', array('url'=>'/treasury/treasury_rollover_repayment/reinv_open'));
echo $this->Form->input('controller_action', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.tr_number', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.mandate_ID', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.cmp_ID', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.cpty_id', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.availability_date', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.reinv_group', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.source_fund', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.accountA_IBAN', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.accountB_IBAN', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.depo_type', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.depo_renew', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.scheme', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.amount', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.depo_term', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.maturity_date', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.interest_rate', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.date_basis', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.total_interest', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.tax_amount', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.reference_rate', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('checkonly', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('newrepayform.amount', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('newrepayform.RepaymentAcc', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('newrepayform.Source', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('newrepayform.reinv_group', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();
?>
</div>
