
<?php
	echo $this->Html->script('/treasury/js/bootstrap-datepicker');
	echo $this->Html->css('/treasury/css/datepicker');
	echo $this->Html->css('/treasury/css/bonds');
	echo $this->Html->script('/treasury/js/autoNumeric.js');
?>
<div id="processformcontainer">
<?php 
	if(!empty($form_process)){
		if($form_process['result']=='success') print '<div id="formprocess" data-trnum="'.$form_process['trnum'].'" data-result="success" class="alert alert-success">';
		elseif($form_process['result']=='success-check') print '<div id="formprocess" data-trnum="'.$form_process['trnum'].'" data-result="successcheck" class="alert alert-success">';
	  	else print '<div id="formprocess" data-trnum="'.$form_process['trnum'].'" data-result="error" class="alert alert-error">';
	  	print '<span>'.$form_process['result_text'].'</span></div>';
	}
?>
	<legend>New bond transaction</legend>
</div>
<?php 
	// FILTERS
	echo $this->Form->create('filters', array('id'=>'FiltersForm', 'class'=>'form-inline span11'));
	?>
	
	<?php
	if (isset($tr_number))
	{
		$disabled_mandate = true;
	}
	else
	{
		$disabled_mandate = false;
	}

	echo $this->Form->input(
			'Transaction.mandate_ID', array(
				'label'     => 'Mandate*',
				'class'		=> 'span11',
				'options'   => $mandates_list,
				'default'	=> $defaultOpts['mandate_id'],
				'readonly'	=> $disabledOpts['mandate_id'],
				'empty'     => __('-- Select a mandate --'),
				'required'	=> 'required',	
				'div'=>'span3 noleftmargin',
				'disabled' => $disabled_mandate
			));
		?>

		<?php echo $this->Form->input(
			'Transaction.cpty_id', array(
				'label'		=>'Counterparty*',
				'class'		=> 'span11',
				'options'	=> $cptys,
				'default'	=> $defaultOpts['cpty_id'],
				'disabled'	=> $disabledOpts['cpty_id'],
				'empty' 	=> __('-- Select a counterparty --'),
				'required'	=> 'required',
				'div'=>'span3'
			));
		?>
	
	<?php echo $this->Form->end(); ?>


<?php
	echo $this->Form->create('filter', array('id'=>'addform', 'class'=>'form-inline span11'));

	echo $this->Form->input('Bond.cpty_id', array(
		'name'				=> 'data[Bond][cpty_id]',
		'type'				=> 'text',
		'value'				=> $defaultOpts['cpty_id'],
		'label'				=> false,
		'style'				=> "visibility:hidden;",
	));
	echo $this->Form->input('Bond.mandate_ID', array(
		'name'				=> 'data[Bond][mandate_ID]',
		'type'				=> 'text',
		'value'				=> $defaultOpts['mandate_id'],
		'label'				=> false,
		'style'				=> "visibility:hidden;",
	));
?>
<h5>Bond static data</h5>
<p class="note">Please select an existing ISIN or enter a new ISIN with all the related information</p>
	<table id="newbonds" class="table table-bordered table-striped table-hover table-condensed">
	<thead>
		<tr>
			<?php if (!empty($trnIsSet)) echo "<th class='tr_number'>Bond ID</th>"; ?>
			<th class="new_isin short_field">New ISIN*</th>
			<th class="existing_isin">Existing ISIN*</th>
			<th class="issuer">Issuer*</th>
			<th class="ccy short_field">CCY</th>
			<th class="issue_date">Issue Date*</th>
			<th class="first_coup_accr_date">First Coupon Accrual Date*</th>
			<th class="first_coup_pay_date">First Coupon Payment Date*</th>
			<th class="maturity_date">Maturity Date*</th>
			<th class="coupon_rate short_field">Coupon Rate %*</th>
			<th class="coupon_freq">Coupon Frequency*</th>
			<th class="date_basis">Date Basis*</th>
			<th class="date_convention">Date Convention*</th>
			<th class="tax_rate short_field">Tax Rate %*</th>
		</tr>
	</thead>
		<tbody>
		<tr class="formQueueLine" id="row1">
			<?php
				if (!empty($trnIsSet))
				{
					echo "<td class='tr_number'>".$defaultOpts['current_bond_id'];
					echo $this->Form->input('Bond.bond_id', array(
						'name'				=> 'data[Bond][current_bond_id]',
						'type'				=> 'hidden',
						'id'				=> 'bond_id',
						'value'				=> $defaultOpts['current_bond_id'],
					));
					echo "</td>";
				}
				?>
			<td class="new_isin field">
				<?php
					echo $this->Form->input('Bond.new_isin', array(
						'name'				=> 'data[Bond][new_isin]',
						'label'				=> '',
						'type'				=> 'text',
						'id'				=> 'new_isin',
						'required'			=> 'required',
						'style'				=> 'width: 70%',
						'default'			=> $defaultOpts['ISIN'],
						'disabled'			=> $disabledOpts['ISIN'],
					));
				?>
			</td>
			<td class="exist_isin field">
				<?php
				
					if ($trnIsSet)
					{
						echo $this->Form->input('Bond.current_isin', array(
							'type'				=> 'hidden',
							'label'				=> '',
							'value'				=> $defaultOpts['current_isin'],
						));
						echo $this->Form->input('Bond.current_bond_id', array(
							'type'				=> 'hidden',
							'label'				=> '',
							'value'				=> $defaultOpts['current_bond_id'],
						));
					}
					echo $this->Form->input('Bond.exist_isin', array(
						'label'				=> '',
						'empty'				=> '- Select ISIN -',
						'options'			=> $isin_list,
						'id'				=> 'exist_isin',
						'required'			=> 'required',
						'default'			=> $defaultOpts['bond_id'],
						'disabled'			=> $disabledOpts['exist_isin'],
					));
				?>
			</td>
			<td class="issuer field">
				<?php
					echo $this->Form->input('Bond.issuer', array(
						'label'				=> '',
						'type'				=> 'text',
						'id'				=> 'issuer',
						'default'			=> $defaultOpts['issuer'],
						'required'			=> 'required',
						'disabled'			=> $disabledOpts['issuer'],
					));
				?>
			</td>
			<td class="Bond_ccy field">
				<?php
					echo $this->Form->input('Bond.ccy', array(
						'label'				=> '',
						'empty'				=> '',
						'id'				=> 'ccy',
						'default'			=> $defaultOpts['currency'],
						'required'			=> 'required',
						'disabled'			=> $disabledOpts['currency'],
						'options'			=> $ccy_list,
					));
				?>
			</td>
			<td class="issuedate field">
				<?php
					echo $this->Form->input('Bond.issuedate', array(
						'label'				=> '',
						'type'				=> 'text',
						'data-date-format'	=> 'dd/mm/yyyy',
						'id'				=> 'issuedate',
						'class'				=> 'datepckr',
						'default'			=> $defaultOpts['issue_date'],
						'required'			=> 'required',
						'disabled'			=> $disabledOpts['issue_date'],
					));
				?>
			</td>
			<td class="first_coupon_accrual_date field">
				<?php
					echo $this->Form->input('Bond.first_coupon_accrual_date', array(
						'label'				=> '',
						'type'				=> 'text',
						'class'				=> 'datepckr',
						'data-date-format'	=> 'dd/mm/yyyy',
						'id'				=> 'first_coupon_accrual_date',
						'default'			=> $defaultOpts['first_coupon_accrual_date'],
						'required'			=> 'required',
						'disabled'			=> $disabledOpts['first_coupon_accrual_date'],
					));
				?>
			</td>
			<td class="first_coupon_payment_date field">
				<?php
					echo $this->Form->input('Bond.first_coupon_payment_date', array(
						'label'				=> '',
						'type'				=> 'text',
						'class'				=> 'datepckr',
						'data-date-format'	=> 'dd/mm/yyyy',
						'id'				=> 'first_coupon_payment_date',
						'default'			=> $defaultOpts['first_coupon_payment_date'],
						'required'			=> 'required',
						'disabled'			=> $disabledOpts['first_coupon_payment_date'],
					));
				?>
			</td>
			<td class="maturity_date_bond field">
				<?php
					echo $this->Form->input('Bond.maturity_date', array(
						'label'				=> '',
						'type'				=> 'text',
						'data-date-format'	=> 'dd/mm/yyyy',
						'class'				=> 'datepckr',
						'id'				=> 'maturity_date_bond',
						'default'			=> $defaultOpts['maturity_date'],
						'required'			=> 'required',
						'disabled'			=> $disabledOpts['maturity_date'],
					));
				?>
			</td>
			<td class="coupon_rate field">
				<?php echo $this->Form->input('Bond.coupon_rate', array(
						'label'		=> '',
						'type'		=> 'text',
						'id'		=> 'coupon_rate',
						'required'	=> 'required',
						'class'		=> 'rate',
						'default'	=> $defaultOpts['coupon_rate'],
						'disabled'	=> $disabledOpts['coupon_rate'],
						'onchange'	=> 'closeCreateTransaction()',
						'readonly'	=>	$disabledOpts['coupon_rate'],
					));
				?>
			</td>
			<td class="coupon_frequency field">
				<?php echo $this->Form->input('Bond.coupon_frequency', array(
						'label'		=> '',
						'empty'		=> '--',
						'required'	=> 'required',
						'id'		=> 'coupon_frequency',
						'default'	=> $defaultOpts['coupon_frequency'],
						'disabled'	=> $disabledOpts['coupon_frequency'],
						'options'	=> array(
							"monthly"	=> "Monthly",
							"quaterly"	=> "Quaterly",
							"semi-annually"	=> "Semi-annually",
							"yearly"	=> "Yearly",
						),
						'onchange'=>'closeCreateTransaction()',
					));
				?>
			</td>
			<td class="date_basis field">
				<?php echo $this->Form->input('Bond.date_basis', array(
						'label'		=> '',
						'id'		=> 'date_basis',
						'empty'		=> '--',
						'required'	=> 'required',
						'default'	=> $defaultOpts['date_basis'],
						'disabled'	=> $disabledOpts['date_basis'],
						'options'	=> array(
							"Act/360"	=> "Act/360",
							"Act/365"	=> "Act/365",
							"30/360"	=> "30E/360",
							"Act/Act"	=> "Act/Act",
						),
					));
				?>
			</td>
			<td class="date_convention field">
				<?php echo $this->Form->input('Bond.date_convention', array(
						'label'		=> '',
						'empty'		=> '--',
						'required'	=> 'required',
						'id'		=> 'date_convention',
						'disabled'	=> $disabledOpts['date_convention'],
						'default'	=> $defaultOpts['date_convention'],
						'options'	=> array(
							"Following"	=> "Following",
							"Preceding"	=> "Preceding",
							"Modified Following"	=> "Modified Following",
							"Modified Preceding"	=> "Modified Preceding",
						),
					));
				?>
			</td>
			<td class="tax_rate field">
				<?php echo $this->Form->input('Bond.tax_rate', array(
						'label'		=> '',
						'type'		=> 'text',
						'id'		=> 'tax_rate',
						'required'	=> 'required',
						'class'		=> 'rate',
						'default'	=> $defaultOpts['tax_rate'],
						'disabled'	=> $disabledOpts['tax_rate'],
						'style'		=> 'text-align: left;',
					));
				?>
			</td>
				<!--<button type="button" class="refreshTaxAmount"> <i class="icon-refresh"></i></button>-->
		</tr>
	</tbody>
</table>
	
<h5>Bond transaction</h5>
<table id="newbondtransaction" class="table table-bordered table-striped table-hover table-condensed">
	<thead>
		<tr>
		
			<?php if (!empty($trnIsSet)) echo "<th class='tr_number'>TRN</th>"; ?>
			<th class="compartment">Compartment*</th>
			<th class="trn_ccy short_field">CCY</th>
			<th class="trade_date">Trade Date*</th>
			<th class="settlement_date">Settlement Date*</th>
			<th class="nominal">Nominal*</th>
			<th class="purchase_price" style="width:75px;">Purchase Price%*</th>
			<th class="trn_accrued_coupon">Accrued Coupon*</th>
			<th class="total_purchase_amount">Total Purchase Amount*</th>
			<th class="trn_coupon">Coupon*</th>
			<th class="trn_tax">Tax*</th>
			<th class="yield short_field">Yield %*</th>
			<th class="reference_rate short_field">Reference Rate%</th>
		</tr>
	</thead>
	
	<tbody>
		<tr>
			<?php if (!empty($trnIsSet))
			{
				echo "<td class='tr_number'>".$defaultOpts['tr_number'];
				echo $this->Form->input('TransactionBond.tr_number', array(
					'type'				=> 'hidden',
					'id'				=> 'tr_number',
					'value'				=> $defaultOpts['tr_number'],
				));
				echo "</td>";
			}
			?>
			<td class="cmp_ID">
				<?php echo $this->Form->input('TransactionBond.cmp_ID', array(
						'label'		=> '',
						'class'		=> 'cmp_ID',
						'options'	=> $cmps,
						'default'	=> $defaultOpts['cmp_id'],
						'disabled'	=> $disabledOpts['cmp_id'],
						'empty' 	=> __('-- Select a compartment --'),
						'required'	=> 'required',
						'onchange'	=> 'closeCreateTransaction()',
					));
				?>
			</td>
			<td class="trn_ccy">
				<?php
				/* echo $this->Form->input('disabled2' , array(
					'type'		=> 'text',
					'label'		=> false,
					'class'		=> 'trn_ccy',
					'id'		=> 'trn_currency_view',
					'disabled'	=> true,
					'div'		=> false,
					'style'		=> "visibility:hidden;",
				)
			);*/
			echo $this->Form->input('TransactionBond.ccy' , array(
					'type'		=> 'text',
					'label'		=> false,
					'id'		=> 'trn_currency_input',
				)
			); ?>
			</td>
			<td class="trade_date field">
				<?php
					echo $this->Form->input('TransactionBond.trade_date', array(
						'label'				=> '',
						'id'				=> 'trade_date',
						'class'				=> 'datepckr',
						'data-date-format'	=> 'dd/mm/yyyy',
						'type'				=> 'text',
						'default'			=> $defaultOpts['trade_date'],
						'required'			=> 'required',
						'disabled'			=> $disabledOpts['trade_date'],
						'onchange'			=> 'closeCreateTransaction()',
					));
				?>
			</td>
			<td class="settlement_date field">
				<?php
					echo $this->Form->input('TransactionBond.settlement_date', array(
						'label'				=> '',
						'id'				=> 'settlement_date',
						'type'				=> 'text',
						'class'				=> 'datepckr',
						'data-date-format'	=> 'dd/mm/yyyy',
						'default'			=> $defaultOpts['settlement_date'],
						'required'			=> 'required',
						'disabled'			=> $disabledOpts['settlement_date'],
						'onchange'			=> 'closeCreateTransaction()',
					));
				?>
			</td>
			<td class="nominal field">
				<?php
					echo $this->Form->input('TransactionBond.nominal', array(
						'label'				=> '',
						'type'				=> 'text',
						'id'				=> 'nominal',
						'default'			=> $defaultOpts['nominal_amount'],
						'required'			=> 'required',
						'class'				=> 'amount',
						'disabled'			=> $disabledOpts['nominal_amount'],
						'onchange'			=> 'closeCreateTransaction()',
					));
				?>
			</td>
			<td class="purchase_price field">
				<?php echo $this->Form->input('TransactionBond.purchase_price', array(
						'label'     => '',
						'type'		=> 'text',
						'class'		=> 'rate',
						'default'	=> $defaultOpts['purchase_price'],
						'disabled'	=> $disabledOpts['purchase_price'],
						'required'	=> 'required',
						'onchange'	=> 'closeCreateTransaction()',
					));
				?>
			</td>

			<td class="trn_accrued_coupon field">
				<?php echo $this->Form->input('TransactionBond.accrued_coupon', array(
						'label'     => '',
						'type'		=> 'text',
						'default'	=> $defaultOpts['accrued_coupon_at_purchase'],
						'disabled'	=> $disabledOpts['accrued_coupon_at_purchase'],
						'required'	=> 'required',
						'class'		=> 'amount',
						'onchange'	=> 'closeCreateTransaction()',
					));
				?>
			</td>
			<td class="total_purchase_amount field">
				<?php echo $this->Form->input('TransactionBond.total_purchase_amount', array(
						'label'     => '',
						'type'		=> 'text',
						'default'	=> $defaultOpts['total_purchase_amount'],
						'disabled'	=> $disabledOpts['total_purchase_amount'],
						'required'	=> 'required',
						'class'				=> 'amount',
						'onchange'	=> 'closeCreateTransaction()',
					));
				?>
			</td>

			<td class="trn_coupon field">
				<?php echo $this->Form->input('TransactionBond.coupon', array(
						'label'     => '',
						'type'		=> 'text',
						'default'	=> $defaultOpts['total_coupon'],
						'disabled'	=> $disabledOpts['total_coupon'],
						'required'	=> 'required',
						'class'				=> 'amount',
						'onchange'	=> 'closeCreateTransaction()',
					));
				?>
			</td>

			<td class="tax_amount field">
				<?php echo $this->Form->input('TransactionBond.tax_amount', array(
						'label'		=> '',
						'type'		=> 'text',
						'id'		=> 'trn_tax_amount',
						'default'	=> $defaultOpts['total_tax'],
						'onchange'	=> 'closeCreateTransaction()',
						'required'	=> 'required',
						'style'		=> 'text-align: left; width: 60%; float: left;',
						'class'		=> 'amount',
						'after'		=> '<button type="button" id="refreshTaxAmount" style="width: 25%;"> <i class="icon-refresh"></i></button>',
					));
				?>
			</td>
			<td class="yield field">
				<?php echo $this->Form->input('TransactionBond.yield', array(
						'label'		=> '',
						'type'		=> 'text',
						'id'		=> 'trn_tax_amount',
						'default'	=> $defaultOpts['yield_to_maturity'],
						'onchange'	=> 'closeCreateTransaction()',
						'required'	=> 'required',
						'class'		=> 'rate',
						'style'		=> 'text-align: left;',
					));
				?>
			</td>
			<td class="reference_rate field">
				<?php echo $this->Form->input('TransactionBond.reference_rate', array(
						'label'     => '',
						'type' 		=> 'text',
						'default'	=> $defaultOpts['reference_rate'],
						'class'		=> 'rate',
						'onchange'	=> 'closeCreateTransaction()',
					));
				?>
			</td>
		</tr>
	</tbody>
</table>

<div class="span11 subactions noleftmargin" id="week_end_date_error">
</div>

<div class="actions span11">
	<a href="#" id="sendnewdepositbt" class="btn btn-default disabled pull-right">Save transaction</a>
	<a href="#" id="checknewdepositbt" class="btn btn-info pull-right formQueueCheckAll">Check</a>
</div>
<?php echo $this->Form->end(); ?>
<div style="display:none;">
<?php
echo $this->Form->create('isinUnique', array('url'=>'/treasury/treasuryajax/new_isin_unique'));
echo $this->Form->input('Bond.ISIN', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();

echo $this->Form->create('bondData', array('url'=>'/treasury/treasuryajax/getBondData'));
echo $this->Form->input('Bond.ISIN', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();

echo $this->Form->create('ccy', array('url'=>'/treasury/treasuryajax/getccy'));
echo $this->Form->input('Transaction.cmp_ID', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();

echo $this->Form->create('newBond', array('url'=>'/treasury/treasuryajax/check_new_bond'));
echo $this->Form->input('Transaction.mandate_ID', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.cpty_id', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Bond.cpty_id', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Bond.mandate_ID', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Bond.new_isin', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Bond.exist_isin', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Bond.issuer', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Bond.ccy', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Bond.issuedate', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Bond.issuedate', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Bond.first_coupon_accrual_date', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Bond.first_coupon_payment_date', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Bond.maturity_date', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Bond.coupon_rate', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Bond.coupon_frequency', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Bond.date_basis', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Bond.date_convention', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Bond.tax_rate', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('TransactionBond.cmp_ID', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('TransactionBond.ccy', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('TransactionBond.trade_date', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('TransactionBond.settlement_date', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('TransactionBond.nominal', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('TransactionBond.purchase_price', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('TransactionBond.accrued_coupon', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('TransactionBond.total_purchase_amount', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('TransactionBond.coupon', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('TransactionBond.tax_amount', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('TransactionBond.yield', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('TransactionBond.reference_rate', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();

echo $this->Form->create('cpty', array('url'=>'/treasury/treasuryajax/getcptybymandate'));
echo $this->Form->input('Transaction.mandate_ID', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();

echo $this->Form->create('cmp', array('url'=>'/treasury/treasuryajax/getcmpbymandate'));
echo $this->Form->input('Transaction.mandate_ID', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();

echo $this->Form->create('refreshBondData', array('url'=>'/treasury/treasuryajax/refresh_new_bond'));
echo $this->Form->input('Bond.nominal', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Bond.maturity_date', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Bond.settle_date', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Bond.first_coupon_accr_date', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Bond.first_coupon_payment_date', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Bond.date_basis', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Bond.coupon_rate', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Bond.coupon_freq', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Bond.date_convention', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Bond.tax_rate', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Bond.purchase_price', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();


?>
</div>
<style type="text/css">
	/*#sendnewdepositbt{pointer-events: none;cursor: default;background: #dddddd;}*/
	#FiltersForm{ margin-bottom: 20px; }
	table input[type="text"]{ width: 50px; height: 20px !important; background: #fff; border: 0; box-shadow: none !important; outline: none 0 !important; margin: 0; border: #eee 1px solid; }
	 table input.datepckr{ width: 70px;}
	 table select{ width: 90px; margin: 0; }
	 table .input-prepend{ margin: 0; }
	 table td{ vertical-align: middle !important; }

	 #newdeposits th.action{ min-width: 100px; }
	 #newdeposits td.action{ text-align: center; }
	 #newdeposits td.error input,
	 #newdeposits td.error select{
	 	border: 2px solid #b94a48 !important;
	 }
	 #newdeposits td input,
	 #newdeposits td select{ border-color: #49afcd !important; }
	 #newdeposits td.optional input,
	 #newdeposits td.optional select{ border-color: #D9EDF7 !important; }

	 table input.ccy{ width: 30px; background: none transparent; border: 0; color: #232323 !important; }
	 td.amount input{ width: 139px; text-align: right;}
	 td.total_interest input{ width: 141px; text-align: right;}

	#newdeposits .formQueueLine .btn.cancel{ visibility: hidden; }

	#newdeposits tbody tr td{ vertical-align: top !important; }
	#newdeposits tbody tr.successcheck td,
	#newdeposits tbody tr.success td{ background-color: #dff0d8; color: #3c763d !important; }
	#newdeposits tbody tr.successcheck td input,
	#newdeposits tbody tr.successcheck td select,
	#newdeposits tbody tr.success td input,
	#newdeposits tbody tr.success td select{ border-color: #5bb75b !important; color: #3c763d !important; }
	#newdeposits tbody tr.error td{ background-color: #f2dede; }

	#newdeposits .formQueueLine + .formQueueLine .btn.cancel{ visibility: visible; }
	#newdeposits .alert + .formQueueLine .btn.cancel{ visibility: visible; }

	#newdeposits td.scheme{ position: relative; }
	#newdeposits td.scheme select{ margin-right: 20px; width: 58px; }
	#newdeposits td.period select{ width: 58px; }
	#newdeposits td.reference_rate{ position: relative; }
	#newdeposits td.reference_rate input { margin-right: 19px; }

	#newdeposits td.tax_amount{ position: relative; }
	#newdeposits td.tax_amount input{ margin-right: 40px; width: 85px; }
	#newdeposits td.tax_amount button{ position: absolute; top: 5px; right: 8px; }

	.actions .btn.pull-right{ margin-left: 10px; }

	#week_end_date_error { color: red; }
	
	td.tr_number
	{
		width: 50px;
		text-align: center;
	}
	
</style>

<script>
function closeCreateTransaction(){

}


function isWeekEnd(date)
{
	if (date)
	{
		var date_array = date.split("/");
		var date_YMD = date_array[2] + '-' + date_array[1] + '-' + date_array[0];
		var d = new Date();
		d.setTime(Date.parse(date_YMD));
		var n = d.getDay();
		return ((n == 6) || (n == 0));
	}
	else
	{
		return false;
	}
}


Date.prototype.addDays = function(days)
{
    var dat = new Date(this.valueOf());
    dat.setDate(dat.getDate() + days);
    return dat;
}

var checked = false;

<?php
if (!empty($tr_number))
{
	echo "var colspan=13";
}
else
{
	echo "var colspan=12";
}
?>


$(document).ready(function(e)
{
	var EximbankaID = 13;
	var errors = [];
	var success = [];
	var reporturl = '';
	// FILTERS, INPUT FORMAT && LINE ADD/REMOVE
	var ajaxpath = '/treasury/treasuryajax';
	
	$("select[name='data[Transaction][cpty_id]']").change(function (e)
	{
		if ($("select[name='data[Transaction][cpty_id]']").val() == 65)//BGL BNP PARIBAS
		{
			// Act/365 only
			var option = $('<option value="Act/365">Act/365</option>');
			$("select[name='data[Bond][date_basis]']").empty().append(option);
		}
		else if ($("select[name='data[Transaction][cpty_id]']").val() == 2)//Banque et Caisse d'Epargne de l'Etat
		{
			// Act/360 only
			var option = $('<option value="Act/360">Act/360</option>');
			$("select[name='data[Bond][date_basis]']").empty().append(option);
		}
		else
		{
			$("select[name='data[Bond][date_basis]']").empty()
			.append('<option value="Act/360">Act/360</option>')
			.append('<option value="Act/365">Act/365</option>')
			.append('<option value="30/360">30/360</option>')
			.append('<option value="Act/Act">Act/Act</option>');
		}
	});

	//isin changing
	function change_new_isin()
	{
		if ($("#new_isin").val() == '')
		{
			$("#exist_isin")[0].disabled = false;
			$("#error_ISIN_unique").remove();
		}
		else
		{
			$("#exist_isin")[0].disabled = true;
			
			//checking unicity of new ISIN
			//var ISIN = $("#new_isin").val();
			$('#isinUniqueNewbondsForm #BondISIN').val( $('#new_isin').val() );
			var data  = $('#isinUniqueNewbondsForm').serialize();
			$.ajax({
				url: ajaxpath+'/new_isin_unique',
				type: "POST",
				dataType: "json",
				data: data,
			}).done(function( data ){
				$("#error_ISIN_unique").remove();
				$("#newbonds tbody tr").css('background-color');
				if (data.unique)
				{
					// ISIN is unique, all good
				}
				else
				{
					// ISIN not unique
					var error_line = $("<tr id='error_ISIN_unique' class='alert alert-error'><td colspan="+(colspan+1)+">"+data.msg+"</td></tr>");
					$("#newbonds tbody").append(error_line);
					$("#newbonds tbody tr").css('background-color', '#f2dede');
				}
				canSubmit();
			});
		}
	}
	
	function change_exist_isin()
	{
		var val = $("#exist_isin").val();
		if (val == '')
		{
			$("#new_isin")[0].disabled = false;
			// clear fields for bond
			$("#issuer").val('');
			$("#issuedate").val('');
			$("#first_coupon_accrual_date").val('');
			$("#first_coupon_payment_date").val('');
			$("#maturity_date_bond").val('');
			$("#coupon_rate").val('');
			$("#tax_rate").val('');
			$("#ccy").val([]);
			$("#coupon_frequency").val([]);
			$("#date_basis").val([]);
			$("#date_convention").val([]);
			
			// remove greying out
			$("#issuer")[0].disabled = false;
			$("#issuedate")[0].disabled = false;
			$("#first_coupon_accrual_date")[0].disabled = false;
			$("#first_coupon_payment_date")[0].disabled = false;
			$("#maturity_date_bond")[0].disabled = false;
			$("#coupon_rate")[0].disabled = false;
			$("#tax_rate")[0].disabled = false;
			$("#ccy")[0].disabled = false;
			$("#coupon_frequency")[0].disabled = false;
			$("#date_basis")[0].disabled = false;
			$("#date_convention")[0].disabled = false;
		}
		else
		{
			$("#new_isin").val('');
			$("#new_isin")[0].disabled = true;
			// prefill data for bond
			$('#bondDataNewbondsForm #BondISIN').val( $("#exist_isin").val() );
			var data = $('#bondDataNewbondsForm').serialize();
			$.ajax({
				url: ajaxpath+'/getBondData',
				type: "POST",
				dataType: "json",
				data: data,
			}).done(function( data ){
				$("#issuer").val(data.Bond.issuer);
				$("#issuedate").val(data.Bond.issue_date);
				$("#first_coupon_accrual_date").val(data.Bond.first_coupon_accrual_date);
				$("#first_coupon_payment_date").val(data.Bond.first_coupon_payment_date);
				$("#maturity_date_bond").val(data.Bond.maturity_date);
				$("#coupon_rate").val(data.Bond.coupon_rate);
				$("#tax_rate").val(data.Bond.tax_rate);
				$("#ccy").val(data.Bond.currency);
				$("#coupon_frequency").val(data.Bond.coupon_frequency);
				$("#date_basis").val(data.Bond.date_basis);
				$("#date_convention").val(data.Bond.date_convention);
				
				//grey out the fields for bond
				$("#issuer")[0].disabled = true;
				$("#issuedate")[0].disabled = true;
				$("#first_coupon_accrual_date")[0].disabled = true;
				$("#first_coupon_payment_date")[0].disabled = true;
				$("#maturity_date_bond")[0].disabled = true;
				$("#coupon_rate")[0].disabled = true;
				$("#tax_rate")[0].disabled = true;
				$("#ccy")[0].disabled = true;
				$("#coupon_frequency")[0].disabled = true;
				$("#date_basis")[0].disabled = true;
				$("#date_convention")[0].disabled = true;
			});
		}
	}
	$("#exist_isin").change(change_exist_isin);
	$("#new_isin").change(change_new_isin);

	$("#refreshTaxAmount").click(function(e)
	{
		$('#refreshBondDataNewbondsForm #refreshBondDataNominal').val( $("#nominal").val() );
		$('#refreshBondDataNewbondsForm #refreshBondDataMaturityDate').val( $("#maturity_date_bond").val() );
		$('#refreshBondDataNewbondsForm #refreshBondDataSettleDate').val( $("#settlement_date").val() );
		$('#refreshBondDataNewbondsForm #refreshBondDataFirstCouponAccrDate').val( $("#first_coupon_accrual_date").val() );
		$('#refreshBondDataNewbondsForm #refreshBondDataFirstCouponPaymentDate').val( $("#first_coupon_payment_date").val() );
		$('#refreshBondDataNewbondsForm #refreshBondDataDateBasis').val( $("#date_basis").val() );
		$('#refreshBondDataNewbondsForm #refreshBondDataCouponRate').val( $("#coupon_rate").val() );
		$('#refreshBondDataNewbondsForm #refreshBondDataCouponFreq').val( $("#coupon_frequency").val() );
		$('#refreshBondDataNewbondsForm #refreshBondDataDateConvention').val( $("#date_convention").val() );
		$('#refreshBondDataNewbondsForm #refreshBondDataTaxRate').val( $("#tax_rate").val() );
		$('#refreshBondDataNewbondsForm #refreshBondDataPurchasePrice').val( $("#TransactionBondPurchasePrice").val() );
		var data = $('#refreshBondDataNewbondsForm').serialize();
		$.ajax({
			url: ajaxpath+'/refresh_new_bond',
			type: "POST",
			data: data,
			dataType: "json"
		}).done(function( data ){
			var res_sas = data;
			//remove_errors();
			$('.theSASone').remove();
			if (res_sas.error)
			{
				//show_error(res_sas.message);// only do the fields filling
				var error_line = $("<tr class='alert alert-error date_constraint theSASone'><td colspan="+colspan+">"+res_sas.message+"</td></tr>");
				$("#newbondtransaction tbody").append(error_line);
			}
			else
			{
				$('#TransactionBondAccruedCoupon').autoNumeric('set', res_sas.accrued_coupon);
				$('#TransactionBondTotalPurchaseAmount').autoNumeric('set', res_sas.total_purchase_amount);
				$('#trn_tax_amount').autoNumeric('set', res_sas.tax);
				$('#TransactionBondCoupon').autoNumeric('set', res_sas.coupon);
			}
			canSubmit();
		});

	});
	
	$("#addform").submit(beforesubmit);
	
	function beforesubmit()
	{
		//if bonddata is greyed out, put them back so they are sent
		$("#issuer")[0].readonly = false;
		$("#issuedate")[0].disabled = false;
		$("#first_coupon_accrual_date")[0].disabled = false;
		$("#first_coupon_payment_date")[0].disabled = false;
		$("#maturity_date_bond")[0].disabled = false;
		$("#coupon_rate")[0].disabled = false;
		$("#tax_rate")[0].disabled = false;
		$("#ccy")[0].disabled = false;
		$("#coupon_frequency")[0].disabled = false;
		$("#date_basis")[0].disabled = false;
		$("#date_convention")[0].disabled = false;
		
		
		$('#newbondtransaction tbody tr').css("background-color", '#dff0d8');
		$('#newbondtransaction tbody tr td').css("background-color", '#dff0d8');
		$('#newbonds tbody tr').css("background-color", '#dff0d8');
		$('#newbonds tbody tr td').css("background-color", '#dff0d8');
	}

	function canSubmit()
	{
		if (($(".alert").length > 0) || ($(".field_wrong").length > 0) )
		{
			$('#sendnewdepositbt')[0].disabled = true;
			$('#sendnewdepositbt').addClass('disabled');
			$('#sendnewdepositbt').removeClass('btn-success');
			$('#newbondtransaction tbody tr').css("background-color", '#f2dede');
			$('#newbondtransaction tbody tr td').css("background-color", '#f2dede');
			$('#newbonds tbody tr').css("background-color", '#f2dede');
			$('#newbonds tbody tr td').css("background-color", '#f2dede');
			
			if ($(".field_wrong").length > 0)
			{
				if ($(".mandatory").length < 1)
				{
					var missing = false;
					//check if actual missing values or wrong data
					$(".field_wrong").each(function(i, item)
					{
						if ($(item).val()=='')
						{
							missing = true;
						}
					});
					if (missing)
					{
						var error_line = $("<tr class='alert alert-error mandatory' style='background-color: rgb(242, 222, 222);'><td colspan="+colspan+" style='background-color: rgb(242, 222, 222);'>Please fill in all mandatory* fields</td></tr>");
						$("#newbondtransaction tbody").append(error_line);
					}
				}
			}
			else
			{
				$(".mandatory").remove();
			}
		}
		else
		{
			var trn_ccy = $("#trn_currency_input").val();
			var bond_ccy = $("#ccy").val();
			if (bond_ccy != '' && trn_ccy != '')
			{
				var allfilled = check_all_filled();
				if (allfilled && checked)
				{
					$('#sendnewdepositbt')[0].disabled = false;
					$('#sendnewdepositbt').removeClass('disabled');
					$('#sendnewdepositbt').addClass('btn-success');
					$('#newbondtransaction tbody tr').css("background-color", 'rgb(223, 240, 216)');
					$('#newbondtransaction tbody tr td').css("background-color", 'rgb(223, 240, 216)');
					$('#newbonds tbody tr').css("background-color", 'rgb(223, 240, 216)');
					$('#newbonds tbody tr td').css("background-color", 'rgb(223, 240, 216)');
					
					$("input, select").change(remove_all_ok);//remove ok if modification of values
				}
			}
			
		}
	}
	
	function remove_all_ok()
	{
		if ($('#newbondtransaction tbody tr').css("background-color") == 'rgb(223, 240, 216)')
		{
			$('#newbondtransaction tbody tr').css("background-color", '');
			$('#newbondtransaction tbody tr td').css("background-color", '');
			$('#newbonds tbody tr').css("background-color", '');
			$('#newbonds tbody tr td').css("background-color", '');
			
			$('#sendnewdepositbt')[0].disabled = true;
			$('#sendnewdepositbt').addClass('disabled');
			$('#sendnewdepositbt').removeClass('btn-success');
		}
	}
	
	function check_all_filled()
	{
		var emptys = $('.field_wrong');
		if (emptys.length > 0)
		{
			return false;
		}
		else
		{
			var emptys_text = $('.table input:text').filter(function() { return $(this).val() == ""; });
			var emptys_select = $('.tableselect').filter(function() { return $(this).val() == ""; });
			emptys = $.merge(emptys_text, emptys_select);
			emptys = emptys.not(".table #TransactionBondReferenceRate").not(".table #new_isin").not(".table #exist_isin");// for when you choose the compartment (ref rate is not mandatory)
			if (($(".table #new_isin").val() == "") && ($(".table #exist_isin").val() == ""))
			{
				emptys = emptys.add($(".table #new_isin"));
			}
		}
		return emptys.length < 1;
	}
	
	$("#checknewdepositbt").mousedown(function (e)
	{
		checked = true;
		var data_dom = $("#addform input, #addform select, #FiltersForm select");
		var Bond = {};
		
		data_dom.each(function (i, item)
		{
			Bond[ $(item).attr('name') ] = $(item).val();
		});

		$('#newBondNewbondsForm #TransactionMandateID').val( Bond['data[Transaction][mandate_ID]'] );
		$('#newBondNewbondsForm #TransactionCptyId').val( Bond['data[Transaction][cpty_id]'] );
		$('#newBondNewbondsForm #BondCptyId').val( Bond['data[Bond][cpty_id]'] );
		$('#newBondNewbondsForm #BondMandateID').val( Bond['data[Bond][mandate_ID]'] );
		$('#newBondNewbondsForm #BondNewIsin').val( Bond['data[Bond][new_isin]'] );
		$('#newBondNewbondsForm #BondExistIsin').val( Bond['data[Bond][exist_isin]'] );
		$('#newBondNewbondsForm #BondIssuer').val( Bond['data[Bond][issuer]'] );
		$('#newBondNewbondsForm #BondCcy').val( Bond['data[Bond][ccy]'] );
		$('#newBondNewbondsForm #BondIssuedate').val( Bond['data[Bond][issuedate]'] );
		$('#newBondNewbondsForm #BondFirstCouponAccrualDate').val( Bond['data[Bond][first_coupon_accrual_date]'] );
		$('#newBondNewbondsForm #BondFirstCouponPaymentDate').val( Bond['data[Bond][first_coupon_payment_date]'] );
		$('#newBondNewbondsForm #BondMaturityDate').val( Bond['data[Bond][maturity_date]'] );
		$('#newBondNewbondsForm #BondCouponRate').val( Bond['data[Bond][coupon_rate]'] );
		$('#newBondNewbondsForm #BondCouponFrequency').val( Bond['data[Bond][coupon_frequency]'] );
		$('#newBondNewbondsForm #BondDateBasis').val( Bond['data[Bond][date_basis]'] );
		$('#newBondNewbondsForm #BondDateConvention').val( Bond['data[Bond][date_convention]'] );
		$('#newBondNewbondsForm #BondTaxRate').val( Bond['data[Bond][tax_rate]'] );
		$('#newBondNewbondsForm #TransactionBondCmpID').val( Bond['data[TransactionBond][cmp_ID]'] );
		$('#newBondNewbondsForm #TransactionBondCcy').val( Bond['data[TransactionBond][ccy]'] );
		$('#newBondNewbondsForm #TransactionBondTradeDate').val( Bond['data[TransactionBond][trade_date]'] );
		$('#newBondNewbondsForm #TransactionBondSettlementDate').val( Bond['data[TransactionBond][settlement_date]'] );
		$('#newBondNewbondsForm #TransactionBondNominal').val( Bond['data[TransactionBond][nominal]'] );
		$('#newBondNewbondsForm #TransactionBondPurchasePrice').val( Bond['data[TransactionBond][purchase_price]'] );
		$('#newBondNewbondsForm #TransactionBondAccruedCoupon').val( Bond['data[TransactionBond][accrued_coupon]'] );
		$('#newBondNewbondsForm #TransactionBondTotalPurchaseAmount').val( Bond['data[TransactionBond][total_purchase_amount]'] );
		$('#newBondNewbondsForm #TransactionBondCoupon').val( Bond['data[TransactionBond][coupon]'] );
		$('#newBondNewbondsForm #TransactionBondTaxAmount').val( Bond['data[TransactionBond][tax_amount]'] );
		$('#newBondNewbondsForm #TransactionBondYield').val( Bond['data[TransactionBond][yield]'] );
		$('#newBondNewbondsForm #TransactionBondReferenceRate').val( Bond['data[TransactionBond][reference_rate]'] );
		var data = $('#newBondNewbondsForm').serialize();
		// check form values
		$.ajax({
		  url: ajaxpath+'/check_new_bond',
		  type: "POST",
		  data: data,
		  dataType: 'json',
		}).done(function( data ){
			remove_errors();
			//check for currency filled and matching
			compare_ccy_trn_bond();
			for(var i in data)
			{
				if (data[i].type == 'field')
				{
					// mandatory field empty
					for (var highlight_count in data[i].mandatory)
					{
						var id = data[i].mandatory[highlight_count];
						$("#"+id).addClass('field_wrong');
					}
				}
				else
				{
					if (data[i].type == 'data')//invalid data
					{
						for (var id in data[i].highlight)
						{
							var id_el = data[i].highlight[id];
							$("#"+id_el).addClass('field_wrong');
						}
						show_error(data[i].message);
					}
					else
					{
						if (data[i].type == 'datasas')//invalid data
						{
							for (var id in data[i].highlight)
							{
								var id_el = data[i].highlight[id];
								$("#"+id_el).addClass('field_wrong');
							}
							show_error_sas(data[i].message);
						}
					}
				}
			}
			canSubmit();
			checked = false;
		});

		e.preventDefault();
		return false;
	});
	
	function show_error(message)
	{
		var error_line = $("<tr class='alert alert-error date_constraint'><td colspan="+colspan+">"+message+"</td></tr>");
		$("#newbondtransaction tbody").append(error_line);
		canSubmit();
	}
	
	function show_error_sas(message)
	{
		if ($(".sas-error").length < 1)
		{
			var error_line = $("<tr class='alert alert-error date_constraint sas-error'><td colspan="+colspan+">"+message+"</td></tr>");
			$("#newbondtransaction tbody").append(error_line);
		}
		canSubmit();
	}
	
	function remove_errors()
	{
		//error messages
		$(".date_constraint").remove();
		
		// fields highlights
		$('form input').removeClass('field_wrong');
		$('form select').removeClass('field_wrong');
		canSubmit();
	}
	
	$("#TransactionMandateID").change(function (e)
	{
		$("#week_end_date_error").empty();
	});
	
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
		if ($("tr#"+id+" input[name='data[Transaction][maturity_date]']").val() != "")//if no maturity date
		{
			$("#ind"+id).remove();
			return 0;
		}
		else
		{
			$("#ind"+id).remove();
			$("#mat"+id).remove();
			var translation = $("tr#"+id+" select[name='data[Transaction][depo_term]'] option:selected").val();
			if (translation == 'NS')
			{
				maturity_date_week_end($(e.target),id);
			}
			else
			{
				var val = $("tr#"+id+" #TransactionCommencementDate").val();//commencement date
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
					if ($("#ind"+id).length < 1)
					{
						$("#week_end_date_error").append("<p id='ind"+id+"' class='maturity'>The indicative maturity date of "+date_formated+" falls on a weekend.</p>");
					}
				}
			}
		}
	}
	
	
	function commencement_date_week_end(e,id)
	{
		e = $(e);
		if (e.val() == '')
		{
			return 0;
		}
		$("#comm"+id).remove();
		if ( isWeekEnd(e.val()) )
		{
			if ($("#comm"+id).length < 1)
			{
				$("#week_end_date_error").append("<p id='comm"+id+"'>The commencement date of "+e.val()+" falls on a weekend.</p>");
			}
		}
		indicative_maturity_date_week_end(e,id);
	}
	$("#TransactionCommencementDate").bind("change keyup copy paste cut focusout", function(e){
		var val = $(e.target).parents(".formQueueLine")[0];
		val = $(val).attr('id');

		commencement_date_week_end($("#TransactionCommencementDate"), val);
	});
	if ($("#TransactionCommencementDate").val() != '')
	{
		commencement_date_week_end($("#TransactionCommencementDate"), 'row1');
	}
	function maturity_date_week_end(e,id)
	{
		e = $(e);
		if (e.val() == '')
		{
			indicative_maturity_date_week_end($("#"+id+" select[name='data[Transaction][depo_term]']"),id);//if maturity date is removed
		}
		else
		{
			$("#mat"+id).remove();
			$("#ind"+id).remove();
			if ( isWeekEnd(e.val()) )
			{
				if ($("#mat"+id).length < 1)
				{
					$("#week_end_date_error").append("<p id='mat"+id+"' class='maturity'>The maturity date of "+e.val()+" falls on a weekend.</p>");
				}
			}
		}
	}
	$("#TransactionMaturityDate").bind("change keyup copy paste cut focusout",function(e){
		var val = $(e.target).parents(".formQueueLine")[0];
		val = $(val).attr('id');
		maturity_date_week_end($(e.target),val);
	});
	if ($("#TransactionMaturityDate").val() != '')
	{
		maturity_date_week_end($("#TransactionMaturityDate"), 'row1');
	}
	
	$("select[name='data[Transaction][depo_term]']").bind("change keyup copy paste cut focusout",function(e)
	{
		indicative_maturity_date_week_end($("select[name='data[Transaction][depo_term]']"), 'row1');
	});

	if ($("#TransactionDepoType").val() == "Callable")//hide maturity date warnings for callable on edit
	{
		$("#week_end_date_error .week_end_err").css('display', 'none');
	}

	$('#TransactionMandateID').bind('change', function(e){
		updateSelectsByMandate(e);
		closeCreateTransaction();
	});
	$('#TransactionCptyId').bind('change', function(e){
		$('#newdeposits input.cpty_id').val($(this).val());
		closeCreateTransaction();
	});

	$("fieldset form").bind("change", function (event) {
		generate_amount_ccy();
	});

	//$('input[name="data[Bond][new_isin]"]').autoNumeric('init',{aSep: '',aDec: '.', vMin:0, mDec:0,vMax: 9999999999999});
	//$('input[name="data[Bond][coupon_rate]"]').autoNumeric('init',{aSep: ',',aDec: '.', vMin:0, mDec:0,vMax: 9999999999999});
	$('#coupon_rate').autoNumeric('init',{aSep: ',',aDec: '.', vMin:-9999999.9999, mDec:4,vMax: 9999999.9999});
	$('#tax_rate').autoNumeric('init',{aSep: ',',aDec: '.', vMin:-9999999.9999, mDec:4,vMax: 9999999.9999});
	$('#TransactionBondReferenceRate').autoNumeric('init',{aSep: ',',aDec: '.', vMin:-9999999.9999, mDec:4,vMax: 9999999.9999});
	$('#TransactionBondPurchasePrice').autoNumeric('init',{aSep: ',',aDec: '.', vMin:0, mDec:4,vMax: 9999999.9999});
	$('#TransactionBondTotalPurchaseAmount').autoNumeric('init',{aSep: ',',aDec: '.', vMin:0, mDec:2,vMax: 9999999999999.99});
	$('#TransactionBondAccruedCoupon').autoNumeric('init',{aSep: ',',aDec: '.', vMin:-9999999999999.99, mDec:2,vMax: 9999999999999.99});
	$('#nominal').autoNumeric('init',{aSep: ',',aDec: '.', vMin:0, mDec:2,vMax: 9999999999999.99});
	$('#TransactionBondCoupon').autoNumeric('init',{aSep: ',',aDec: '.', vMin:-9999999999999.99, mDec:2,vMax: 9999999999999.99});
	$('#trn_tax_amount').autoNumeric('init',{aSep: ',',aDec: '.', vMin:-9999999999999.99, mDec:2,vMax: 9999999999999.99});

	$('#ccy').change(compare_ccy_trn_bond);
	$('#TransactionBondCmpID').change(function(e){
		$('#ccyNewbondsForm #TransactionCmpID').val( $('#newbondtransaction #TransactionBondCmpID').val() );
		var data = $('#ccyNewbondsForm').serialize();
		$.ajax({
		  url: ajaxpath+'/getccy',
		  type: "POST",
		  data: data,
		}).done(function( data ){
			var trn_ccy = data;
			//$("#trn_currency_view").val(trn_ccy);
			$("#trn_currency_input").val(trn_ccy);
			// check if currency is the same for BOND
			compare_ccy_trn_bond();
		});
	});
// week end checks
	$("#issuedate").bind("change keyup copy paste cut focusout",function(e)
	{
		$("#issuedate_week_end").remove();
		e = $(e.target);
		if ( isWeekEnd(e.val()) )
		{
			if ($("#issuedate_week_end").length < 1)
			{
				$("#week_end_date_error").append("<p id='issuedate_week_end' class='week_end_err'>The issue date of "+e.val()+" falls on a weekend.</p>");
			}
		}
	});
	$("#first_coupon_accrual_date").bind("change keyup copy paste cut focusout",function(e)
	{
		$("#first_coupon_accural_week_end").remove();
		e = $(e.target);
		if ( isWeekEnd(e.val()) )
		{
			if ($("#first_coupon_accural_week_end").length < 1)
			{
				$("#week_end_date_error").append("<p id='first_coupon_accural_week_end' class='week_end_err'>The first coupon accrual date of "+e.val()+" falls on a weekend.</p>");
			}
		}
	});


	$("#first_coupon_payment_date").bind("change keyup copy paste cut focusout",function(e)
	{
		$("#first_coupon_payment_week_end").remove();
		e = $(e.target);
		if ( isWeekEnd(e.val()) )
		{
			if ($("#first_coupon_payment_week_end").length < 1)
			{
				$("#week_end_date_error").append("<p id='first_coupon_payment_week_end' class='week_end_err'>The first coupon payment date of "+e.val()+" falls on a weekend.</p>");
			}
		}
	});

	$("#maturity_date_bond").bind("change keyup copy paste cut focusout",function(e)
	{
		$("#maturity_week_end").remove();
		e = $(e.target);
		if ( isWeekEnd(e.val()) )
		{
			if ($("#maturity_week_end").length < 1)
			{
				$("#week_end_date_error").append("<p id='maturity_week_end' class='week_end_err'>The maturity date of "+e.val()+" falls on a weekend.</p>");
			}
		}
	});
	$("#trade_date").bind("change keyup copy paste cut focusout",function(e)
	{
		$("#trade_week_end").remove();
		e = $(e.target);
		if ( isWeekEnd(e.val()) )
		{
			if ($("#trade_week_end").length < 1)
			{
				$("#week_end_date_error").append("<p id='trade_week_end' class='week_end_err'>The trade date of "+e.val()+" falls on a weekend.</p>");
			}
		}
	});

	$("#settlement_date").bind("change keyup copy paste cut focusout",function(e)
	{
		$("#settlement_week_end").remove();
		e = $(e.target);
		if ( isWeekEnd(e.val()) )
		{
			if ($("#settlement_week_end").length < 1)
			{
				$("#week_end_date_error").append("<p id='settlement_week_end' class='week_end_err'>The settlement date of "+e.val()+" falls on a weekend.</p>");
			}
		}
	});




	function compare_ccy_trn_bond()
	{
		/*remove error message*/
		$("#error_currency").remove();
		$("#newbondtransaction tbody").css('background-color', '');
		$("#newbondtransaction tbody tr").css('background-color', '');
		$("#newbondtransaction tbody tr td").css('background-color', '');
		$("#newbonds tbody").css('background-color', '');
		$("#newbonds tbody tr").css('background-color', '');
		$("#newbonds tbody tr td").css('background-color', '');

		/*compare currencies of bond and bond transaction*/
		var trn_ccy = $("#trn_currency_input").val();
		var bond_ccy = $("#ccy").val();
		if (bond_ccy != '' && trn_ccy != '')
		{
			if (trn_ccy != bond_ccy)
			{
				// show error, ccy should be the same
				var error_line = $("<tr id='error_currency' class='alert alert-error'><td colspan="+colspan+">Currency of the bond and currency of the compartment must be the same.</td></tr>")
				$("#newbondtransaction tbody").append(error_line);
				$("#newbondtransaction tbody tr").css('background-color', '#f2dede');
				$("#newbonds tbody tr").css('background-color', '#f2dede');
			}
		}
		canSubmit();
	}

	/*
	 * Call the "onchange" function to load the ccy and scheme if the transaction is in edition mode
	 */
	$('select.cmp_ID').change();

	function updateSelectsByMandate(e)
	{
		var mandate = $('#TransactionMandateID').val();
		
		$("#BondMandateID").val(mandate);//update value of mandate for bond form
		
		$('td.ccy input', $('#newdeposits tr').not('.success')).val('');
		$('.alert').remove();
		$('.error').removeClass('error');
		$('.warning').removeClass('warning');

		$('#cptyNewbondsForm #TransactionMandateID').val( mandate );
		var data = $('#cptyNewbondsForm').serialize();
		$.ajax({
		  url: ajaxpath+'/getcptybymandate',
		  type: "POST",
		  data: data,
		}).done(function( data ){
			$('#TransactionCptyId').html(data);
		});

		$('#cmpNewbondsForm #TransactionMandateID').val( mandate );
		data = $('#cmpNewbondsForm').serialize();
		$.ajax({
		  url: ajaxpath+'/getcmpbymandate',
		  type: "POST",
		  data: data,
		}).done(function( data ){
			$('#TransactionBondCmpID').html(data);
		});
	}
	
	$("#TransactionCptyId").change(function(e)//update value of counterparty for bond form
	{
		$("#BondCptyId").val( $("#TransactionCptyId").val() );
	});


	function formatFields(){
		//datepicker
		$('input.datepckr').each(function(i, item){
			item.dtpckr = $(item).datepicker({ dateFormat: 'dd/mm/yy' }).bind('changeDate', function(ev) {
				item.dtpckr.hide(); 
				//$('.refreshTaxAmount').trigger('click');
			}).data('datepicker');

			//detection week ends
			if ($(item).attr('id') == 'TransactionCommencementDate')
			{
				$(item).bind("change keyup copy paste cut focusout",function(e){
					var val = $(e.target).parents(".formQueueLine")[0];
					val = $(val).attr('id');
					commencement_date_week_end($(item),val);
				});
			}
			else if ($(item).attr('id') == 'TransactionMaturityDate')
			{
				$(item).bind("change keyup copy paste cut focusout",function(e){
					var val = $(e.target).parents(".formQueueLine")[0];
					val = $(val).attr('id');
					maturity_date_week_end($(item), val);
				});
			}
		});

		$("body").find('#TransactionDepoTerm').bind("change keyup copy paste cut focusout",function(e)
		{
			var val = $(e.target).parents(".formQueueLine")[0];
			val = $(val).attr('id');
			indicative_maturity_date_week_end($(e.target), val);
		});


		//number format
		$('input[name="data[Transaction][amount]"], input[name="data[Transaction][total_interest]"], input[name="data[Transaction][tax_amount]"]').autoNumeric('init',{aSep: ',', aDec: '.',vMax: 9999999999999.99, vMin:-9999999999999.99});
		$('input[name="data[Transaction][interest_rate]"], input[name="data[Transaction][reference_rate]"]').autoNumeric('init',{aSep: false, aDec: '.', vMin:-999999999.999});
		$('input[name="data[Transaction][parent_id]"]').autoNumeric('init',{aSep: '',vMin:0, vMax: 99999});
		$('input.amount').autoNumeric('init',{aSep: '', aDec: '.',vMin:0, vMax: 9999999999999.99});
		$('input.rate').autoNumeric('init',{aSep: '', aDec: '.',vMin:-9999999.9999, vMax: 9999999.9999});
	}
	formatFields();

	
	//refresh interest/tax if any changed in the concerned fields
	$('td input, td select', 'change', function(e){
		if($.inArray($(this).attr('name'), ['data[Transaction][commencement_date]', 'data[Transaction][amount]', 'data[Transaction][depo_term]', 'data[Transaction][maturity_date]', 'data[Transaction][interest_rate]'])>=0){
			
			if($(this).attr('name')!='data[Transaction][total_interest]' && $(this).attr('name')!='data[Transaction][tax_amount]'){
				$('.refreshTaxAmount').trigger('click');
			}
		}
	});
	
<?php
if (empty($UserIsInCheckerGroup)){
?>
	$('#sendnewdepositbt').bind('click', function(e){
		if($('#sendnewdepositbt').hasClass('btn-success')){
			$("#addform").find('input, select').each(function(i,element){ element.disabled = false; });
			$("#addform").submit();//submit the form
		}
		$('#sendnewdepositbt').removeClass('btn-success').addClass('disabled');
		$('#sendnewdepositbt')[0].disabled = true;

		e.preventDefault();
		return false;
	});
<?php
}
?>



});
</script>
