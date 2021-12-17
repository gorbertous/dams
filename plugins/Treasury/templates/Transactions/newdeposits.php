
<?php
	echo $this->Html->script('/treasury/js/bootstrap-datepicker');
	echo $this->Html->css('/treasury/css/datepicker');
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
</div>
<?php 
	// FILTERS
	echo $this->Form->create('filters', array('id'=>'FiltersForm', 'class'=>'form-inline span12'));
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
				'class'		=> 'span12',
				'options'   => $mandates_list,
				'default'	=> $defaultOpts['mandate_ID'],
				'readonly'	=> $disabledOpts['mandate_ID'],
				'empty'     => __('-- Select a mandate --'),
				'required'	=> 'required',	
				'div'=>'span3 noleftmargin',
				'disabled' => $disabled_mandate
			));
		?>

		<?php echo $this->Form->input(
			'Transaction.cpty_id', array(
				'label'		=>'Counterparty*',
				'class'		=> 'span12',
				'options'	=> $cptys,
				'default'	=> $defaultOpts['cpty_id'],
				'disabled'	=> $disabledOpts['cpty_id'],
				'empty' 	=> __('-- Select a counterparty --'),
				'required'	=> 'required',
				'div'		=>'span3',
				'id'		=> 'TransactionCptyId',
			));
		?>
	
	<?php echo $this->Form->end(); ?>

	<table id="newdeposits" data-ajaxbasepath="<?php print Router::url(array('controller' => 'treasuryajax', 'plugin'=>'treasury')) ?>" class="table table-bordered table-striped table-hover table-condensed">
	<thead>
		<tr>
			<th class="num">#</th>
			<th class="action">TRN</th>
			<th class="cmp_ID">Compartment*</th>
			<th class="ccy">CCY</th>
			<th class="commencement_date">Cmmt. Date*</th>
			<th class="amount">Amount*</th>
			<th class="type">Type*</th>
			<th class="period">Period*</th>
			<th class="scheme">Scheme*</th>
			<th class="maturity_date">Maturity Date</th>
			<th class="interest_rate">Int.Rate %</th>
			<th class="day_basis">Day Basis</th>
			<th class="total_interest">Interest</th>
			<th class="tax_amout">Tax</th>
			<th class="reference_rate">Ref.Rate %</th>
			<th class="parent_trn">Linked TRN</th>
		</tr>
	</thead>
	<?php
	echo $this->Form->input('isErrorForAllLines', array(
		'type' => 'hidden',
		'label'	=> false,
		'div'	=> false,
		'value'	=> 'No',
		'id'	=> 'isErrorForAllLines',
	));
	?>
	<tbody>
		<tr class="formQueueLine" id="row1">
			<td class="num">1</td>
			<td class="action">


				<?php 
				/*
				 * Display the cancel button only when adding new transactions
				 */
				if ($action != 'edit') { ?><a class="btn btn-danger cancel">Cancel</a><?php } else { echo (isset($tr_number))?$tr_number:''; }
				if ($action == 'edit' && isset($tr_number)){
					echo $this->Form->input('Transaction.tr_number', array(
						'type' => 'hidden',
						'label'	=> false,
						'div'	=> false,
						'value'	=> $tr_number,
					));
				}
				echo $this->Form->input('linenum', array(
					'type' => 'hidden',
					'label'	=> false,
					'div'	=> false,
					'value'	=> '1',
					'id'	=> 'linenum'
				));
				echo $this->Form->input('Transaction.depo_renew', array(
					'type' => 'hidden',
					'label'	=> false,
					'div'	=> false,
					'value'	=> 'NO',
				));
				 ?>
			</td>
			<td class="cmp_ID">
				<?php echo $this->Form->input(
						'Transaction.cmp_ID', array(
						'label'		=> '',
						'class'		=> 'cmp_ID',
						'options'	=> $cmps,
						'default'	=> $defaultOpts['cmp_ID'],
						'disabled'	=> $disabledOpts['cmp_ID'],
						'empty' 	=> __('-- Select a compartment --'),
						'required'	=> 'required',
						'onchange'	=> 'closeCreateTransaction()',
					));
				?>
			</td>
			<td class="ccy">
				<?php
				echo $this->Form->input('Transaction.ccy', array(
					'type' => 'text',
					'label'	=> false,
					'div'	=> false,
					'class'	=> 'ccy',
					'disabled'	=> true,
				));
				echo $this->Form->input('Transaction.ccy', array(
					'type' => 'hidden',
					'label'	=> false,
					'div'	=> false,
				));
				?>
			</td>
			<td class="commencement_date">
				<?php
					$input_comm_date_array = array(
						'name'				=> 'data[Transaction][commencement_date]',
						'label'				=> '',
						'id'				=> 'TransactionCommencementDate',
						'class'				=> 'datepckr',
						'data-date-format'	=> 'dd/mm/yyyy',
						'default'			=> $defaultOpts['commencement_date'],
						'required'			=> 'required',
						'disabled'			=> $disabledOpts['commencement_date'],
						'onchange'			=> 'closeCreateTransaction()',
					);
					if ($disabledOpts['commencement_date'] === 'disabled')
					{
						$input_comm_date_array["style"] = "background-color: rgb(238, 238, 238);";
					}
				
					echo $this->Form->input(
						'commencement', $input_comm_date_array);
				?>
			</td>
			<td class="amount">
			<?php echo $this->Form->input(
						'Transaction.amount', array(
						'id'     => 'amount',
						'type'     => 'text',
						'label'		=> false,
						'value'	=> ($defaultOpts['amount'] != 0.00) ? $defaultOpts['amount'] : '',
						'required'	=> 'required',
						'onchange'	=> 'closeCreateTransaction()',
					));
				?>
			
			</td>
			<td class="type">
				<?php echo $this->Form->input(
						'Transaction.depo_type', array(
						'label'     => '',
						'options'   => array('Term' => 'Term','Callable'=>'Callable'),
						'default'	=> $defaultOpts['depo_type'],
						'required'	=> 'required',
						'disabled'	=> $disabledOpts['depo_type'],
						'onchange'	=> 'closeCreateTransaction()',
					));
				?>
			</td>
			<td class="period">
				<?php echo $this->Form->input(
						'Transaction.depo_term', array(
						'label'     => '',
						'options'   => $depoTerm,
						'default'	=> $defaultOpts['depo_term'],
						'required'	=> 'required',
						'onchange'	=> 'closeCreateTransaction()',
					));
				?>
			</td>

			<td class="scheme">
				<?php
					echo $this->Form->input(
						'', array(
						'type'  => 'hidden',
						'id'	=> 'transaction_scheme_hidden',
						'value'	=> isset($defaultOpts['scheme']) ? $defaultOpts['scheme'] : '',
					));
					echo $this->Form->input(
						'Transaction.scheme', array(
						'name'		=> 'data[Transaction][scheme]',
						'label'     => '',
						'options'   => array(
							"AA"	=> "A-A",
						),
						'required'	=> 'required',
						'onchange'	=> 'closeCreateTransaction()',
					));
				?>
				<span class="infotip accounts hidden">
					<span class="icon icon-info-sign"></span>
					<div class="content"></div>
				</span>
			</td>
			<td class="maturity_date ">
				<?php echo $this->Form->input(
						'maturity', array(
						'name'				=> 'data[Transaction][maturity_date]',
						'label'				=> '',
						'id'				=> 'TransactionMaturityDate',
						'class'				=> 'datepckr',
						'data-date-format'	=> 'dd/mm/yyyy',
						'default'			=> $defaultOpts['maturity_date'],
						'required'			=> 'required',
						'onchange'			=> 'closeCreateTransaction()',
					));
				?>
			</td>
			<td class="interest_rate optional">
				<?php echo $this->Form->input(
						'interest_rate', array(
						'name'		=> 'data[Transaction][interest_rate]',
						'label'		=> '',
						'id'		=> 'TransactionInterestRate',
						'default'	=> $defaultOpts['interest_rate'],
						'onchange'	=> 'closeCreateTransaction()',
					));
				?>
			</td>
			<td class="day_basis optional">
				<?php echo $this->Form->input(
						'date_basis', array(
						'name'		=> 'data[Transaction][date_basis]',
						'label'		=> '',
						'id'		=> 'TransactionDateBasis',
						'default'	=> $defaultOpts['date_basis'],
						'options'	=> array(
							"Act/360"	=> "Act/360",
							"Act/365"	=> "Act/365",
							"30/360"	=> "30E/360",
						),
						'onchange'=>'closeCreateTransaction()',
					));
				?>
			</td>
			<td class="total_interest optional">
				<?php echo $this->Form->input(
						'total_interest', array(
						'name'		=> 'data[Transaction][total_interest]',
						'label'		=> '',
						'id'		=> 'TransactionTotalInterest',
						'default'	=> $defaultOpts['total_interest'],
						'onchange'	=> 'closeCreateTransaction()',
						'style'		=> 'text-align: left;',
					));
				?>
			</td>
			<td class="tax_amount optional">
				<?php echo $this->Form->input(
						'tax_amount', array(
						'name'		=> 'data[Transaction][tax_amount]',
						'label'		=> '',
						'id'		=> 'TransactionTaxAmount',
						'default'	=> $defaultOpts['tax_amount'],
						'onchange'	=> 'closeCreateTransaction()',
						'style'		=> 'text-align: left;',
					));
				?>
				<button type="button" class="refreshTaxAmount"> <i class="icon-refresh"></i></button>
			</td>
			<td class="reference_rate optional">
				<?php echo $this->Form->input(
						'Transaction.reference_rate', array(
						'label'	=> false,
						'type'	=> 'text',
						'value'	=> isset($defaultOpts['reference_rate']) ? $defaultOpts['reference_rate'] : '',
					));
				?>
				<span class="infotip benchmark hidden">
					<span class="icon icon-info-sign"></span>
					<?php if (empty($defaultOpts['benchmark'])){ $defaultOpts['benchmark'] = "O/N Bloomberg CMP"; } ?>
					<div class="content"><span class="ccy"><?php echo $defaultOpts['benchmark']; ?></span></div>
				</span>
			</td>
			<td class="linked_trn optional">
				<?php echo $this->Form->input(
						'Transaction.linked_trn', array(
						'label'     => '',
						'type' 		=> 'text',
						'default'	=> $defaultOpts['linked_trn'],
						'onchange'	=> 'closeCreateTransaction()',
					));
				?>
			</td>
		</tr>
	</tbody>
</table>
<div class="span12 subactions noleftmargin">
	<?php if ($action != 'edit') { ?>
	<a href="#" id="addnewdepositbt" class="btn btn-default add">+ New Deposit</a>
	<?php } ?>
</div>
<div class="span12 subactions noleftmargin" id="week_end_date_error">
</div>

<div class="actions span12 noleftmargin">
	<a href="#" id="sendnewdepositbt" class="btn btn-default disabled pull-right">Save transaction(s)</a>
	<a href="#" id="checknewdepositbt" class="btn btn-info pull-right formQueueCheckAll">Check</a>
</div>

<style type="text/css">
	/*#sendnewdepositbt{pointer-events: none;cursor: default;background: #dddddd;}*/
	#FiltersForm{ margin-bottom: 20px; }
	table input[type="text"]{ width: 50px; height: 20px !important; background: #fff; border: 0; box-shadow: none !important; outline: none 0 !important; margin: 0; border: #eee 1px solid; }
	 table input.datepckr{ width: 70px;}
	 table select{ width: 90px; margin: 0; }
	 table select.cmp_ID{ width: 150px; }
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

	.subactions{ margin-bottom: 20px; margin-top: -10px; }

	#newdeposits td.scheme{ position: relative; }
	#newdeposits td.scheme select{ margin-right: 20px; width: 58px; }
	#newdeposits td.period select{ width: 58px; }
	#newdeposits td.reference_rate{ position: relative; }
	#newdeposits td.reference_rate input { margin-right: 19px; }

	.infotip.hidden{ display: none; }
	.infotip{ margin-left:5px; position: absolute; top: 8px; right: 0;  }
	.infotip .content{  min-width: 130px; display: none; position: absolute; background: #fff; border: 1px solid #eee; padding: 10px; z-index: 100; }
	.infotip:hover .content{ display: block; right: 0; }

	#newdeposits td.tax_amount{ position: relative; }
	#newdeposits td.tax_amount input{ margin-right: 40px; width: 85px; }
	#newdeposits td.tax_amount button{ position: absolute; top: 5px; right: 8px; }

	.actions .btn.pull-right{ margin-left: 10px; }

	#week_end_date_error { color: red; }

</style>
<div style="display:none;">
<?php
echo $this->Form->create('benchmark', array('url'=>'/treasury/treasuryajax/getBenchmark'));
echo $this->Form->input('Transaction.mandate_ID', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.currency', array(
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

echo $this->Form->create('cptyMandate', array('url'=>'/treasury/treasuryajax/getcptybymandate'));
echo $this->Form->input('Transaction.mandate_ID', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();

echo $this->Form->create('cmpMandate', array('url'=>'/treasury/treasuryajax/getcmpbymandate'));
echo $this->Form->input('Transaction.mandate_ID', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();

echo $this->Form->create('account', array('url'=>'/treasury/treasuryajax/accountslist'));
echo $this->Form->input('Transaction.cmp_ID', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();

echo $this->Form->create('calcInterest', array('url'=>'/treasury/treasuryajax/calcTotalInterest'));
echo $this->Form->input('linenum', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.depo_renew', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.cmp_ID', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.ccy', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.commencement_date', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.amount', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.depo_type', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.depo_term', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.scheme', array(
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
echo $this->Form->input('Transaction.linked_trn', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
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
echo $this->Form->end();

echo $this->Form->create('calcTax', array('url'=>'/treasury/treasuryajax/calcTotalInterest'));
echo $this->Form->input('linenum', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.depo_renew', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.cmp_ID', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.ccy', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.commencement_date', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.amount', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.depo_type', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.depo_term', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.scheme', array(
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
echo $this->Form->input('Transaction.linked_trn', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
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
echo $this->Form->end();

$url_action = '/treasury/treasurytransactions/newdeposits/add';
if ($action == 'edit')
{
	$url_action = '/treasury/treasurytransactions/newdeposits/edit/'.$tr_number;
}
echo $this->Form->create('checkSave', array('url'=>$url_action));
echo $this->Form->input('linenum', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.depo_renew', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.cmp_ID', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.ccy', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.commencement_date', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.amount', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.depo_type', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.depo_term', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.scheme', array(
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
echo $this->Form->input('Transaction.linked_trn', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
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
echo $this->Form->input('checkOnly', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('force', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('msg', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();
?>
</div>
<script>
function closeCreateTransaction(){
	if($('#sendnewdepositbt').hasClass('btn-success'))
	{
		$('#sendnewdepositbt').removeClass('btn-success');
		$('#sendnewdepositbt').removeClass('active');
		$('#sendnewdepositbt').addClass('btn-default');
		$('#sendnewdepositbt').addClass('disabled');

		//reactivate Check button id=checknewdepositbt
		$('#checknewdepositbt').removeClass('disabled');
	}
}

var save_value_TransactionDepoTerm = null;
var save_value_TransactionMaturityDate = null;
function Callable_limitations()
{
	$(".formQueueLine").each(function(index, element)
	{
		var row = $(element);
		var id_row = row.attr('id');
		if ($("#TransactionDepoType", row).val() == "Callable")
		{
			var tmp = $("#TransactionDepoTerm option:selected", row).val();
			if (tmp != null)
			{
				save_value_TransactionDepoTerm = tmp;
			}
			$("#TransactionDepoTerm", row).val(null);
			var tmp_mat_date = $("#TransactionMaturityDate", row).val();
			if (tmp_mat_date != null && tmp_mat_date!= "")
			{
				save_value_TransactionMaturityDate = $("#TransactionMaturityDate", row).val();
			}
			$("#TransactionMaturityDate", row).val(null);
			$("#TransactionTotalInterest", row).val(null);
			$("#TransactionTaxAmount", row).val(null);
			$("#TransactionDepoTerm", row)[0].disabled = true;
			$("#TransactionMaturityDate", row)[0].disabled = true;
			$("#TransactionTotalInterest", row)[0].disabled = true;
			$("#TransactionTaxAmount", row)[0].disabled = true;
			$("#ind"+id_row).hide();
			$("#mat"+id_row).remove();
		}
		else
		{
			$("#TransactionDepoTerm", row)[0].disabled = false;
			$("#TransactionMaturityDate", row)[0].disabled = false;
			if (save_value_TransactionMaturityDate != null && save_value_TransactionMaturityDate != "")
			{
				$("#TransactionMaturityDate", row).datepicker("setDate", save_value_TransactionMaturityDate);
			}
			$("#TransactionTotalInterest", row)[0].disabled = false;
			$("#TransactionTaxAmount", row)[0].disabled = false;
			// remove empty option for #TransactionDepoTerm
			if (save_value_TransactionDepoTerm)
			{
				$("#TransactionDepoTerm", row).val(save_value_TransactionDepoTerm);
			}
			$("#ind"+id_row).show();
		}
	});
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

$(document).ready(function(e){
	var EximbankaID = 13;
	var errors = [];
	var success = [];
	var reporturl = '';
	// FILTERS, INPUT FORMAT && LINE ADD/REMOVE
	var ajaxpath = '';
	if($('#newdeposits').attr('data-ajaxbasepath')) ajaxpath=$('#newdeposits').attr('data-ajaxbasepath');
	
	$("select[name='data[Transaction][cpty_id]']").change(function (e)
	{
		if ($("select[name='data[Transaction][cpty_id]']").val() == 65)//BGL BNP PARIBAS
		{
			// Act/365 only
			var option = $('<option value="Act/365">Act/365</option>');
			$("select[name='data[Transaction][date_basis]']").empty().append(option);
		}
		else if ($("select[name='data[Transaction][cpty_id]']").val() == 2)//Banque et Caisse d'Epargne de l'Etat
		{
			// Act/360 only
			var option = $('<option value="Act/360">Act/360</option>');
			$("select[name='data[Transaction][date_basis]']").empty().append(option);
		}
		else
		{
			$("select[name='data[Transaction][date_basis]']").empty().append('<option value="Act/360">Act/360</option>')
			.append('<option value="Act/365">Act/365</option>')
			.append('<option value="30/360">30/360</option>');
		}
	});

		//to update the benchmark infobulle
	function updateBenchmark(e)
	{
		var context = $(e.target).parents("tr");
		var mandate = $("#TransactionMandateID").val();
		var currency = $("input[name='data[Transaction][ccy]']").val();
		if (mandate != "" && currency != "")
		{
			$('#benchmarkNewdepositsForm #TransactionMandateID').val( $("#TransactionMandateID").val() );
			$('#benchmarkNewdepositsForm #TransactionCurrency').val( $("input[name='data[Transaction][ccy]']").val() );
			var data = $('#benchmarkNewdepositsForm').serialize();
			$.ajax({
			  url: ajaxpath+'/getBenchmark',
			  type: "POST",
			  data: data,
			  context: $(this)
			}).done(function( data ){
				if (data == "")
				{
					$("span.ccy", context).text("O/N Bloomberg CMP");
				}
				else
				{
					$("span.ccy", context).text(data);
				}
			});
		}
		else
		{
			$("span.ccy", context).text("O/N Bloomberg CMP");
		}
	}
	$("#TransactionMandateID").change(function(e)
	{
		updateBenchmark(e);
	});

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
		$("#week_end_date_error .maturity").css('display', 'none');
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

	$('input[name="data[Transaction][tax_amount]"]').autoNumeric('init',{aSep: ',',aDec: '.', vMin:0, mDec:2, vMax: 9999999999999.99, vMin:0.00});

	$("#TransactionDepoType").change(Callable_limitations);
	Callable_limitations();

	$('#newdeposits').delegate('select.cmp_ID', 'change', function(e){
		$('#ccyNewdepositsForm #TransactionCmpID').val( $('#newdeposits #TransactionCmpID').val() );
		var data = $('#ccyNewdepositsForm').serialize();
		$.ajax({
		  url: ajaxpath+'/getccy',
		  type: "POST",
		  data: data,
		  context: $(this)
		}).done(function( data ){
			var line = $(this).parents('tr.formQueueLine');
			$('td.ccy input', line).val(data);
			$('td.reference_rate .infotip .ccy', line).text(data);
			$('td.reference_rate .infotip', line).removeClass('hidden');

			if(data=='GBP' || data=='PLN' || data=='NOK'){
				$('td.day_basis select',line).val('Act/365');
			}else{
				$('td.day_basis select',line).val('Act/360');
			}

			//update benchmark
			updateBenchmark(e);
		});

		$('#accountNewdepositsForm #TransactionCmpID').val( $('#newdeposits #TransactionCmpID').val() );
		var data = $('#accountNewdepositsForm').serialize();
		$.ajax({
		  url: ajaxpath+'/accountslist',
		  type: "POST",
		  data: data,
		  context: $(this)
		}).done(function( data ){
			var line = $(this).parents('tr.formQueueLine');
			var html = $('<select/>').html(data);

			
			// Adjust scheme select
		
			/*if($('option', html).length==3){
				$('td.scheme select', line).html('<option value="">--</option><option value="AA">A-A</option><option value="BB">B-B</option><option value="AB">A-B</option>');

				
				 //Action to set the schema after retrieving the possible values if the transaction is in edition mode
 		
				if (($('#transaction_scheme_hidden').val() != "") && ($('td.scheme select option[value="' + $('#transaction_scheme_hidden').val() + '"]').length)) $('td.scheme select').val($('#transaction_scheme_hidden').val());

			}else if($('option', html).length==2){
				$('td.scheme select', line).html('<option value="">--</option><option value="AA">A-A</option>');
				$('td.scheme select', line).val('AA');
			}else{
				$('td.scheme select', line).html('<option value="">--</option>');
			}*/

			/*
			 * Fill in infotip
			 */
			var txt = '';
			$('option', html).each(function(i, item){
				if($(item).val()){
					if(txt)txt+='<br>';
					txt+=$(item).text();
				}
			});
			if(txt){
				$('td.scheme .infotip .content', line).html(txt);
				$('td.scheme .infotip', line).removeClass('hidden');
			}else{
				$('td.scheme .infotip .content', line).html('');
				$('td.scheme .infotip', line).addClass('hidden');
			}
			
		});
	});

	/*
	 * Call the "onchange" function to load the ccy and scheme if the transaction is in edition mode
	 */
	$('select.cmp_ID').change();
	

	function updateSelectsByMandate(e){
		var mandate = $('#TransactionMandateID').val();

		$('td.scheme select', $('#newdeposits tr').not('.success')).html('<option value="">--</option>');

		$('td.ccy input', $('#newdeposits tr').not('.success')).val('');
		$('td.scheme .infotip', $('#newdeposits tr').not('.success')).addClass('hidden');
		$('td.scheme .infotip .content', $('#newdeposits tr').not('.success')).html('');
		$('.alert').remove();
		$('.error').removeClass('error');
		$('.warning').removeClass('warning');

		$('#cptyMandateNewdepositsForm #TransactionMandateID').val( $('#FiltersForm #TransactionMandateID').val() );
		var data = $('#cptyMandateNewdepositsForm').serialize();
		$.ajax({
		  url: ajaxpath+'/getcptybymandate',
		  type: "POST",
		  data: data,
		}).done(function( data ){
			$('#TransactionCptyId').html(data);
		});

		$('#cmpMandateNewdepositsForm #TransactionMandateID').val( $('#FiltersForm #TransactionMandateID').val() );
		data = $('#cmpMandateNewdepositsForm').serialize();
		$.ajax({
		  url: ajaxpath+'/getcmpbymandate',
		  type: "POST",
		  data: data,
		}).done(function( data ){
			$('select.cmp_ID', $('#newdeposits tr').not('.success')).html(data);
		});

		$('.formQueueLine').each(function(i, line){
			if($('#linenum', line).val()!=1){
				//$(line).remove();
			}else{
				$('td.ccy input').val('');
				$('td.commencement_date input').val('');
				$('td.amount input').val('');
				$('td.type select').val('Term');
				$('td.period select').val('1W');
				$('td.day_basis select').val('Act/360');
				$('td.optional input').val('');
			}
		});
	}
	

	var firstline = $($('#newdeposits tr.formQueueLine')[0]);
	var firstlineHtml = $($('#newdeposits tr.formQueueLine')[0]).parent().html();
	$('#addnewdepositbt').bind('click', function(e){
		var line = $(firstlineHtml);
		$(line).removeClass('success');
		$(line).removeClass('successcheck');
		$(line).removeClass('warning');
		$(line).removeClass('error');

		closeCreateTransaction();

		$('.alert').remove();
		$('input, select', line).val('');
		$('input, select', line).removeAttr('disabled');
		$('td.period select', line).val('1W');
		$('input[name="data[Transaction][depo_renew]"]', line).val('No');
		$('td.type select', line).val('Term');
		$('td.scheme select', line).html('<option value="">--</option>');
		$('td.day_basis select', line).val('Act/360');

		$('input[name="data[Transaction][tax_amount]"]', line).autoNumeric('init',{aSep: ',',aDec: '.', vMin:0, mDec:2, vMax: 9999999999999.99, vMin:0.00});

		if($(firstline).length) $('td.cmp_ID select', line).html( $('td.cmp_ID select', firstline).html() );

		$('#newdeposits tbody').append(line);
		$('#newdeposits tbody tr.formQueueLine').each(function(i, item){
			$('#linenum',item).val((i+1));
			$('td.num',item).text((i+1));
			$('td', item).parent().attr('id', 'row' + (i+1));
		});

		formatFields(line);
		//checkMaturityDateAvailability();


		if(e) e.preventDefault();
		return false;
	});

	$('#newdeposits').delegate('.btn.cancel', 'click', function(e){
		//get row number
		var row_num = $(this).parents('tr.formQueueLine').attr('id');
		$("#comm"+row_num).remove();
		$("#mat"+row_num).remove();
		$("#ind"+row_num).remove();
		$(this).parents('tr.formQueueLine').remove();
		$('.alert').remove();
		$('#newdeposits tbody tr').each(function(i, item){
			$('#linenum',item).val((i+1));
			$('td.num',item).text((i+1));
		});

		if(e) e.preventDefault();
		return false;
	});

	function formatFields(line){
		//datepicker
		$('input.datepckr', line).each(function(i, item){
			item.dtpckr = $(item).datepicker({ dateFormat: 'dd/mm/yy' }).on('changeDate', function(ev) {
				item.dtpckr.hide(); 
				$('.refreshTaxAmount',line).trigger('click');
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

		$(line).find('#TransactionDepoTerm').bind("change keyup copy paste cut focusout",function(e)
		{
			var val = $(e.target).parents(".formQueueLine")[0];
			val = $(val).attr('id');
			indicative_maturity_date_week_end($(e.target), val);
		});

		//maturity date should always be AFTER commencement date
		$('.commencement_date input.datepckr', line).datepicker().on('changeDate', function(e){
			var startdate = e.date;
			if(startdate) startdate.setDate(startdate.getDate()+1);
			$('.maturity_date input.datepckr', line).datepicker('setStartDate', startdate);

			//clear date if before
			var cmmt = mat = null;
			var tmp = $('.commencement_date input.datepckr', line).val().split('/');
			if(tmp.length==3){
				cmmt = new Date(tmp[2], tmp[1]-1, tmp[0]);
			}
			var tmp = $('.maturity_date input.datepckr', line).val().split('/');
			if(tmp.length==3){
				mat = new Date(tmp[2], tmp[1]-1, tmp[0]);
			}
			if(mat && cmmt){
				if(mat<cmmt){
					$('.maturity_date input.datepckr', line).datepicker('setDate', null);
				}
			}
		});
		$("#TransactionDepoType", line).change(Callable_limitations);
		//number format
		$('input[name="data[Transaction][amount]"], input[name="data[Transaction][total_interest]"]', line).autoNumeric('init',{aSep: ',',aDec: '.',vMax: 9999999999999.99, vMin:-9999999999999.99});
		$('input[name="data[Transaction][tax_amount]"]', line).autoNumeric('init',{aSep: ',',aDec: '.',vMax: 9999999999999.99, vMin:0.00});
		$('input[name="data[Transaction][interest_rate]"], input[name="data[Transaction][reference_rate]"]', line).autoNumeric('init',{aSep: false,aDec: '.', vMin:-99999999.999, vMax: 99999999.999});
		$('input[name="data[Transaction][parent_id]"]', line).autoNumeric('init',{aSep: '',vMin:0, vMax: 99999});
	}
	formatFields(firstline);

	//rates calculation
	$('#newdeposits tbody').delegate('.refreshTaxAmount', 'click', function(e){
		var line = $(this).parents('.formQueueLine');
		var datas = formQueueGetAllFields(line);
		datas['data[Transaction][mandate_ID]'] = $('#TransactionMandateID').val();
		datas['data[Transaction][cpty_id]'] = $('#TransactionCptyId').val();

		$('input[name="data[Transaction][total_interest]"]', line).val('');
		$('input[name="data[Transaction][tax_amount]"]', line).val('');
		
		$('#calcInterestNewdepositsForm #calcInterestLinenum').val( $('.num', line).text() );
		$('#calcInterestNewdepositsForm #TransactionDepoRenew').val( $('#TransactionCmpID', line).val() );
		$('#calcInterestNewdepositsForm #TransactionCmpID').val( $('#TransactionCmpID', line).val() );
		$('#calcInterestNewdepositsForm #TransactionCcy').val( $('#TransactionCcy', line).val() );
		$('#calcInterestNewdepositsForm #TransactionCommencementDate').val( $('#TransactionCommencementDate', line).val() );
		$('#calcInterestNewdepositsForm #TransactionAmount').val( $('#amount', line).val() );
		$('#calcInterestNewdepositsForm #TransactionDepoType').val( $('#TransactionDepoType', line).val() );
		$('#calcInterestNewdepositsForm #TransactionDepoTerm').val( $('#TransactionDepoTerm', line).val() );
		$('#calcInterestNewdepositsForm #TransactionScheme').val( $('#TransactionScheme', line).val() );
		$('#calcInterestNewdepositsForm #TransactionMaturityDate').val( $('#TransactionMaturityDate', line).val() );
		$('#calcInterestNewdepositsForm #TransactionInterestRate').val( $('#TransactionInterestRate', line).val() );
		$('#calcInterestNewdepositsForm #TransactionDateBasis').val( $('#TransactionDateBasis', line).val() );
		$('#calcInterestNewdepositsForm #TransactionTotalInterest').val( $('#TransactionTotalInterest', line).val() );
		$('#calcInterestNewdepositsForm #TransactionTaxAmount').val( $('#TransactionTaxAmount', line).val() );
		$('#calcInterestNewdepositsForm #TransactionReferenceRate').val( $('#TransactionReferenceRate', line).val() );
		$('#calcInterestNewdepositsForm #TransactionLinkedTrn').val( $('#TransactionLinkedTrn', line).val() );
		$('#calcInterestNewdepositsForm #TransactionMandateID').val( $('#FiltersForm #TransactionMandateID').val() );
		$('#calcInterestNewdepositsForm #TransactionCptyId').val( $('#newdeposits #TransactionCptyId').val() );
		var datas = $('#calcInterestNewdepositsForm').serialize();

		//calculate INTEREST then TAX
		if(!$('input[name="data[Transaction][total_interest]"]', line).val()){
			$.ajax({context: {line: line}, data: datas, url: ajaxpath+'/calcTotalInterest?'})
			.done(function( data ){
				data = $.trim(data.split('(').join('').split(')').join('').split('?').join(''));
				
				if(data && data!='.' && data!='-' && data.length<20){
					if(data=='0') data='0.00';
					$('input[name="data[Transaction][total_interest]"]', this.line).autoNumeric('set', data);

					/*datas = formQueueGetAllFields(this.line);
					datas['data[Transaction][mandate_ID]'] = $('#TransactionMandateID').val();
					datas['data[Transaction][cpty_id]'] = $('#TransactionCptyId').val();*/
					$('#calcTaxNewdepositsForm #calcInterestLinenum').val( $('.num', line).text() );
					$('#calcTaxNewdepositsForm #TransactionDepoRenew').val( $('#TransactionCmpID', line).val() );
					$('#calcTaxNewdepositsForm #TransactionCmpID').val( $('#TransactionCmpID', line).val() );
					$('#calcTaxNewdepositsForm #TransactionCcy').val( $('#TransactionCcy', line).val() );
					$('#calcTaxNewdepositsForm #TransactionCommencementDate').val( $('#TransactionCommencementDate', line).val() );
					$('#calcTaxNewdepositsForm #TransactionAmount').val( $('#amount', line).val() );
					$('#calcTaxNewdepositsForm #TransactionDepoType').val( $('#TransactionDepoType', line).val() );
					$('#calcTaxNewdepositsForm #TransactionDepoTerm').val( $('#TransactionDepoTerm', line).val() );
					$('#calcTaxNewdepositsForm #TransactionScheme').val( $('#TransactionScheme', line).val() );
					$('#calcTaxNewdepositsForm #TransactionMaturityDate').val( $('#TransactionMaturityDate', line).val() );
					$('#calcTaxNewdepositsForm #TransactionInterestRate').val( $('#TransactionInterestRate', line).val() );
					$('#calcTaxNewdepositsForm #TransactionDateBasis').val( $('#TransactionDateBasis', line).val() );
					$('#calcTaxNewdepositsForm #TransactionTotalInterest').val( $('#TransactionTotalInterest', line).val() );
					$('#calcTaxNewdepositsForm #TransactionTaxAmount').val( $('#TransactionTaxAmount', line).val() );
					$('#calcTaxNewdepositsForm #TransactionReferenceRate').val( $('#TransactionReferenceRate', line).val() );
					$('#calcTaxNewdepositsForm #TransactionLinkedTrn').val( $('#TransactionLinkedTrn', line).val() );
					$('#calcTaxNewdepositsForm #TransactionMandateID').val( $('#FiltersForm #TransactionMandateID').val() );
					$('#calcTaxNewdepositsForm #TransactionCptyId').val( $('#newdeposits #TransactionCptyId').val() );
					var data_tax = $('#calcTaxNewdepositsForm').serialize();
					$.ajax({context: {line: this.line}, data: datas, url: ajaxpath+'/computeTaxFromInterestAndMandateCpty?'})
					.done(function( data ){
						if(!data) data='0.00';

						$('input[name="data[Transaction][tax_amount]"]', this.line).autoNumeric('set', data);
					});
				}
				
			});
		}

		e.preventDefault();
		return false;
	});
	
	//refresh interest/tax if any changed in the concerned fields
	$('#newdeposits').delegate('td input, td select', 'change', function(e){
		if($.inArray($(this).attr('name'), ['data[Transaction][commencement_date]', 'data[Transaction][amount]', 'data[Transaction][depo_term]', 'data[Transaction][maturity_date]', 'data[Transaction][interest_rate]'])>=0){
			
			var line = $(this).parents('.formQueueLine');
			if($(this).attr('name')!='data[Transaction][total_interest]' && $(this).attr('name')!='data[Transaction][tax_amount]'){
				$('.refreshTaxAmount',line).trigger('click');
			}
		}
	});

	//--- Form queueing ---
	var formQueue = [];
	var formQueueParams = {checkOnly: 0, force : 0};
	var limitbreachChecked = false;
	var checkError = false;
	
<?php
	if (empty($UserIsInCheckerGroup)){
?>
		$('#sendnewdepositbt').bind('click', function(e){
			if($('#sendnewdepositbt').hasClass('btn-success')){
				formQueueParams.checkOnly = 0;
				limitbreachChecked = true;
				formQueueLaunchAll();
			}
			$('#sendnewdepositbt').removeClass('btn-success');

			e.preventDefault();
			return false;
		});
<?php
	}
?>
	$('.formQueueCheckAll').bind('click', function(e){
		$('alertmandatory').remove();
		$('#isErrorForAllLines').val('No');
		formQueueParams.checkOnly = 1;
		formQueueLaunchAll();

		
		e.preventDefault();
		return false;
	});


	function formQueueLaunchAll(e){
		formQueue = [];
		if(!$('#TransactionMandateID').val() || !$('#TransactionCptyId').val()) return;

		$('.formQueueLine').each(function(i, line){
			if(!$(line).hasClass('success')){
				var datas = formQueueGetAllFields(line);
				datas['data[Transaction][mandate_ID]'] = $('#TransactionMandateID').val();
				datas['data[Transaction][cpty_id]'] = $('#TransactionCptyId').val();

				formQueue.push(datas);
			}
		});
		formQueueLaunch(formQueue);
		
		if(e) e.preventDefault();
		return false;
	}
	function formQueueGetAllFields(parent){
		var fields = {};
		$('input[name], select[name], textarea[name]', parent).each(function(i, field){
			fields[$(field).attr('name')] = $(field).val();
		});
		return fields;
	}
	function formQueueLaunch(){
		$('#checknewdepositbt').addClass('disabled');
		if(formQueue.length){
			errors = [];
			success = [];
			limitbreachChecked = false;
			$('.alert').remove();
			$('#processformcontainer .alert-error').remove();
			$('#newdeposits tr.error').removeClass('error');
			$('#newdeposits tr.successcheck').removeClass('successcheck');
			$('#newdeposits tr.successcheck').removeClass('warning');
			$('#newdeposits .error-message').remove();
			$('#newdeposits td').removeClass('error');
			formQueueNext();
		}
	}
	function formQueueNext(){
		
		if(formQueue.length){
			if(!formQueueParams.checkOnly && !limitbreachChecked){
				limitbreachChecked=true;
				formQueueParams.force = 1;
				formQueueNext();
			}else{
				var datas = formQueue.shift();

				if(formQueueParams) $.extend(datas, formQueueParams);

				$('#checkSaveNewdepositsForm #calcInterestLinenum').val( datas['data[linenum]'] );
				$('#checkSaveNewdepositsForm #TransactionDepoRenew').val( datas['data[Transaction][depo_renew]'] );
				$('#checkSaveNewdepositsForm #TransactionCmpID').val( datas['data[Transaction][cmp_ID]'] );
				$('#checkSaveNewdepositsForm #TransactionCcy').val( datas['data[Transaction][ccy]'] );
				$('#checkSaveNewdepositsForm #TransactionCommencementDate').val( datas['data[Transaction][commencement_date]'] );
				$('#checkSaveNewdepositsForm #TransactionAmount').val( datas['data[Transaction][amount]'] );
				$('#checkSaveNewdepositsForm #TransactionDepoType').val( datas['data[Transaction][depo_type]'] );
				$('#checkSaveNewdepositsForm #TransactionDepoTerm').val( datas['data[Transaction][depo_term]'] );
				$('#checkSaveNewdepositsForm #TransactionScheme').val( datas['data[Transaction][scheme]'] );
				$('#checkSaveNewdepositsForm #TransactionMaturityDate').val( datas['data[Transaction][maturity_date]'] );
				$('#checkSaveNewdepositsForm #TransactionInterestRate').val( datas['data[Transaction][interest_rate]'] );
				$('#checkSaveNewdepositsForm #TransactionDateBasis').val( datas['data[Transaction][date_basis]'] );
				$('#checkSaveNewdepositsForm #TransactionTotalInterest').val( datas['data[Transaction][total_interest]'] );
				$('#checkSaveNewdepositsForm #TransactionTaxAmount').val( datas['data[Transaction][tax_amount]'] );
				$('#checkSaveNewdepositsForm #TransactionReferenceRate').val( datas['data[Transaction][reference_rate]'] );
				$('#checkSaveNewdepositsForm #TransactionLinkedTrn').val( datas['data[Transaction][linked_trn]'] );
				$('#checkSaveNewdepositsForm #TransactionMandateID').val( datas['data[Transaction][mandate_ID]'] );
				$('#checkSaveNewdepositsForm #TransactionCptyId').val( datas['data[Transaction][cpty_id]'] );
				$('#checkSaveNewdepositsForm #checkSaveCheckOnly').val( formQueueParams.checkOnly );
				$('#checkSaveNewdepositsForm #checkSaveForce').val( datas['data[Transaction][force]'] );
				$('#checkSaveNewdepositsForm #checkSaveMsg').val( datas['data[Transaction][msg]'] );
				if($('#lineWarn'+datas['linenum']).val())
				{
					$('#checkSaveNewdepositsForm #checkSaveMsg').val( $('#lineWarn'+datas['linenum']).val() );
				}
				var datas = $('#checkSaveNewdepositsForm').serialize();
				$.ajax({
				  type: "POST",
				  data: datas,
				}).done(function( data ){

					formQueueLoaded(data);
					limitbreachChecked=false;
				});
			}
			
		}else{
			if(($('#isErrorForAllLines').val() == 'No') && ($(".alert-error").length < 1)){
				$('#sendnewdepositbt').removeClass('btn-default');
				<?php if (empty($UserIsInCheckerGroup))
				{
					echo "$('#sendnewdepositbt').removeClass('disabled');";
				}
				?>
				
				$('#sendnewdepositbt').addClass('btn-success');
				$('#sendnewdepositbt').addClass('active');
				
			}

			if(!errors.length || formQueueParams.force){
				//check the global limit breach amount 
				if(success.length>0 && reporturl){
					window.location = reporturl+'/'+success.join(',');
				}
			}
		}
	}

	function formQueueLoaded(data){
		var html = $('<div/>').html(data);
		var process = $('#processformcontainer', html);
		var linenum = $('#formprocess', process).attr('data-trnum');
		var row=null;
		$('#newdeposits tbody tr').each(function(i, line){
			if($('#linenum', line).val()==linenum){
				row = line;
			}
		});
		//new request: display only message if its an error

		if($('#formprocess',process).attr('data-result')=='error' && !formQueueParams.force){
			var errormsg = 'Please fill in all mandatory* fields';
			var limitbreach = false;
			$('ul.errorlist li', process).each(function(i, li){
				var field = $(li).attr('data-field');
				var error = $(li).text();
				//special case: exposure limit message
				if(error.toLowerCase().indexOf('limit breach')>=0){
					errormsg = error;
					limitbreach = true;
				}

				//special case: commencement_date should be before maturity
				if(error.toLowerCase().indexOf('should be')>=0){
					errormsg = error;
				}

				//special case: parent ID should be the same mandate/cpty/cmp
				if(error.toLowerCase().indexOf('parent transaction')>=0){
					errormsg = error;
				}

				//special case: linked TRN should be the same mandate/cpty/cmp
				if(error.toLowerCase().indexOf('linked transaction')>=0){
					errormsg = error;
				}
				
				
				//special case: rollover, too much amount
				if(error.toLowerCase().indexOf('cannot rollover more than available in the pool.')>=0){
					errormsg = error;
					closeCreateTransaction();
				}

				//add error class to the criminal field
				$('td.'+field, row).addClass('error');
				if ( jQuery.inArray(row.id+errormsg, errors) == -1)
				{
					errors.push(row.id+errormsg);
				
					if(errormsg.toLowerCase().indexOf('limit breach')>=0)
					{
						$(row).after('<tr class="alert"><td colspan=16>'+ errormsg + '</td></tr>');
						$('#FiltersForm').append('<input type="hidden" id="lineWarn'+linenum+'" name="lineWarn'+linenum+'" value="'+errormsg+'" />');
						$(row).addClass('warning');
					}
					else
					{
						$(row).after('<tr class="alert alert-error"><td colspan=16 style="background-color: #f2dede;">'+ errormsg + '</td></tr>');
						$(row).addClass('error');

						$('#sendnewdepositbt').removeClass('btn-success active');
						$('#sendnewdepositbt').addClass('btn-default disabled');
					}
				}
			});

			//enable the button create transactions if the error is only limit breach
			if(!limitbreach) {
				$('#isErrorForAllLines').val('Yes');
			}
			$('ul.errorlist', process).remove();
			$('#alertmandatory').remove();
		}else if($('#formprocess',process).attr('data-result')=='success' || formQueueParams.force){
		///////////////////
			var errormsg = 'Please fill in all mandatory* fields';
			var limitbreach = false;
			$('ul.errorlist li', process).each(function(i, li){
				var field = $(li).attr('data-field');
				var error = $(li).text();
				//special case: exposure limit message
				if(error.toLowerCase().indexOf('limit breach')>=0){
					errormsg = error;
					limitbreach = true;
				}

				//special case: commencement_date should be before maturity
				if(error.toLowerCase().indexOf('should be')>=0){
					errormsg = error;
				}

				//special case: parent ID should be the same mandate/cpty/cmp
				if(error.toLowerCase().indexOf('parent transaction')>=0){
					errormsg = error;
				}

				//special case: linked TRN should be the same mandate/cpty/cmp
				if(error.toLowerCase().indexOf('linked transaction')>=0){
					errormsg = error;
				}
				
				
				//special case: rollover, too much amount
				if(error.toLowerCase().indexOf('cannot rollover more than available in the pool.')>=0){
					errormsg = error;
					closeCreateTransaction();
				}

				//add error class to the criminal field
				$('td.'+field, row).addClass('error');
				if ( jQuery.inArray(row.id+errormsg, errors) == -1)
				{
					errors.push(row.id+errormsg);
				
					if(errormsg.toLowerCase().indexOf('limit breach')>=0)
					{
						$(row).after('<tr class="alert"><td colspan=16 style="background-color: #f2dede;">'+ errormsg + '</td></tr>');
						$('#FiltersForm').append('<input type="hidden" id="lineWarn'+linenum+'" name="lineWarn'+linenum+'" value="'+errormsg+'" />');
						$(row).addClass('warning');
					}
					else
					{
						$(row).after('<tr class="alert alert-error"><td colspan=16  style="background-color: #f2dede;">'+ errormsg + '</td></tr>');
						$(row).addClass('error');

						$('#sendnewdepositbt').removeClass('btn-success active');
						$('#sendnewdepositbt').addClass('btn-default disabled');
					}
				}
			});

			//enable the button create transactions if the error is only limit breach
			if(!limitbreach) {
				$('#isErrorForAllLines').val('Yes');
			}
			$('ul.errorlist', process).remove();
			$('#alertmandatory').remove();

			/*if(limitbreach && !$('#alertmandatory').length){
				$(row).after('<tr class="alert"><td colspan=16>'+ errormsg + '</td></tr>');
				$('#FiltersForm').append('<input type="hidden" id="lineWarn'+linenum+'" name="lineWarn'+linenum+'" value="'+errormsg+'" />');
				$(row).addClass('warning');
			}else if(!limitbreach && !$('#alertmandatory').length){
				$(row).after('<tr class="alert alert-error"><td colspan=16>'+ errormsg + '</td></tr>');
				$(row).addClass('error');
			}*/
			
			////////////////////
			var link = $('<div/>');
			var trnum = $('.trnum', process).text();
			if(!reporturl){
				if($('.trreport', process).length && trnum){
					if($('.trreport', process).attr('href')){
						var tmp = $('.trreport', process).attr('href').split('/'+trnum);
						if(tmp[0]) reporturl = tmp[0];
					}
				}
			}

			success.push(trnum);
			$(link).append( trnum+' ' );
			$(link).append( $('.trreport', process) );

			$('td.action',row).html('');
			$('td.action',row).append(link);
			
			$(row).addClass('success');
			$('input, select',row).attr('disabled','disabled');
		}else{
			$(row).addClass('successcheck');
		}

		//next line (if queue)!
		formQueueNext();
	}
	

});
</script>
