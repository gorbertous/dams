<?php
	echo $this->Html->script('/treasury/js/autoNumeric.js');
	echo $this->Html->script('/treasury/js/form_ajax.js');
	echo $this->Html->script('/treasury/js/transactions.js');
	echo $this->Html->script('/treasury/js/bootstrap-datepicker');
	echo $this->Html->css('/treasury/css/datepicker');
?>

<style>
	select { width: 100% }
</style>

<div class="row-fluid">
	<?php echo $this->Form->create('transaction', array(
		'default'=>false,
		'data-ajaxbasepath' => Router::url(array('controller' => 'treasuryajax', 'plugin'=>'treasury'))
	));?>
	<div id="alertMsg" style="display:none" class="alert alert-warning">
	<button type="button" class="hide" data-dismiss="alert">&times;</button>
		<h4>Warning!</h4>
		<span id="alertText"></span>
	</div>
	<div class = "well span5">

		<?php if(isset($fromReinv)): ?>
			<div class="alert alert-info">
				The selected transaction is a rollover outgoing from reinvestment :
				<?php echo $fromReinv; ?>
				<?php echo $this->Form->input('Transaction.pool' , array(
					'type'		=> 'hidden',
					'label'		=> false,
					'value'		=> $pool,
				)
			); ?>
			</div>
		<?php endif; ?>

		<?php echo $this->Form->input(
			'Transaction.mandate_ID', array(
				'label'     => 'Mandate',
				'class'		=> 'span12',
				'options'   => $mandates_list,
				'default'	=> $defaultOpts['mandate_ID'],
				'readonly'	=> $disabledOpts['mandate_ID'],
				'empty'     => __('-- Select a mandate --'),
				'required'	=> 'required',

				)
			);
		?>

		<?php echo $this->Form->input(
			'Transaction.cmp_ID', array(
				'label'		=>'Compartment',
				'class'		=> 'span12',
				'options'	=> $cmps,
				'default'	=> $defaultOpts['cmp_ID'],
				'disabled'	=> $disabledOpts['cmp_ID'],
				'empty' 	=> __('-- Select a compartment --'),
				'required'	=> 'required',
				)
			);
		?>

		<?php echo $this->Form->input(
			'Transaction.cpty_id', array(
				'label'		=>'Counterparty',
				'class'		=> 'span12',
				'options'	=> $cptys,
				'default'	=> $defaultOpts['cpty_id'],
				'disabled'	=> $disabledOpts['cpty_id'],
				'empty' 	=> __('-- Select a counterparty --'),
				'required'	=> 'required',
				)
			);
		?>
		<div class="span12" id="TransactionAccountsIBAN"></div>
	</div>

	<div class="well span3">
		<div class="input-append pos-relative">
			<label for="TransactionAmount">Deposit Amount (</label>
			<label id="ccy"><?php echo $defaultOpts['ccy']; ?></label>
			<label>)</label>
		</div>

			<?php echo $this->Form->input('Transaction.amount' , array(
					'type'		=> 'text',
					'label'		=> false,
					'value'		=> $pool,
					'class'		=> "span10 text-right",
					'id'		=> 'TransactionAmount',
					'value'		=> ($defaultOpts['amount'] != 0.00) ? $defaultOpts['amount'] : '',
				)
			); ?>

		<?php echo $this->Form->input('Transaction.ccy', array('type'=>'hidden')) ?>

		<?php echo $this->Form->input(
				'Transaction.accountA_IBAN', array(
				'label'     => 'Principal Account',
				'class'		=> 'span12',
				'options'   => $accountA_IBAN,
				'default'	=> $defaultOpts['accountA_IBAN'],
				'empty' 	=> __('-- Select an account --'),
			));
		?>

		<?php echo $this->Form->input(
				'Transaction.accountB_IBAN', array(
				'label'     => 'Interest Account',
				'class'		=> 'span12',
				'options'   => $accountA_IBAN,
				'default'	=> $defaultOpts['accountB_IBAN'],
				'empty' 	=> __('-- Select an account --'),
			));
		?>

	</div>

	<div class="well span3">

		<?php
			echo $this->Form->input(
				'Transaction.depo_type', array(
				'label'     => 'Deposit Type',
				'options'   => array('Term' => 'Term','Callable'=>'Callable'),
				'default'	=> $defaultOpts['depo_type'],
				'class'		=> 'span12',
				/*'empty' 	=> __('-- Choose One --'),*/
			));
		?>
		
		<div id="depoTermDiv">
			<?php echo $this->Form->input(
					'Transaction.depo_term', array(
					'label'     => 'Depo term',
					'class'		=> 'span12',
					'options'   => $depoTerm,
					'default'	=> $defaultOpts['depo_term'],
				));
			?>
		</div>
<?php if(1||!empty($defaultOpts['tr_number'])): //should it be displayed on creation or not? ?>
		<div id="linkedTrnDiv">
			<?php echo $this->Form->input(
					'Transaction.linked_trn', array(
					'label'     => 'Linked TRN',
					'type' => 'text',
					'class'		=> 'span12',
					'default'	=> $defaultOpts['linked_trn']
				));
			?>
		</div>
<?php endif ?>

		<div id="automaticRenewalDiv">
			<div class="input file">Automatic renewal at maturity</div>
			<table class="table table-stripped">
				<tr>
					<td>
						<?php echo $this->Form->input('Transaction.depo_renew', array(
							'label'     => false,
							'type' => 'radio',
							'default'	=> $defaultOpts['linked_trn'],
							'value'		=> 'Yes',
							'id'		=> 'TransactionDepoRenewYes',
							'checked'	=> ($defaultOpts['depo_renew'] == 'Yes')? 'checked' : '',
						));
					?>
						
						<label for="TransactionDepoRenewYes">Yes</label>
					</td>
					<td>
						<?php echo $this->Form->input('Transaction.depo_renew', array(
							'label'     => false,
							'type' => 'radio',
							'default'	=> $defaultOpts['linked_trn'],
							'value'		=> 'No',
							'id'		=> 'TransactionDepoRenewNo',
							'checked'	=> ($defaultOpts['depo_renew'] == 'No')? 'checked' : '',
						));
					?>
						
						
						<label for="TransactionDepoRenewNo">No</label>
					</td>
				</tr>
			</table>
		</div>


		<?php
		echo $this->Form->input(
				'commencement', array(
				'name'=> 'data[Transaction][commencement_date]',
				'label'	=> 'Commencement date',
				'id'	=> 'TransactionCommencementDate',
				'class'		=> 'span12',
				'data-date-format'	=> 'dd/mm/yyyy',
				// 'disabled'			=> $disabledOpts['commencement_date'],
				'default'			=> $defaultOpts['commencement_date'],
		));
		?>
		<!-- </div>  -->

		<!--  Maturity Date is displayed using Ajax whenever "Non Standard Depo Term" is selected by the user -->
		<div id="MaturityDateDiv" class="input file">
			<?php
			echo $this->Form->input(
					'maturity', array(
					'name'=> 'data[Transaction][maturity_date]',
					'label'	=> 'Maturity date',
					'id'	=> 'TransactionMaturityDate',
					'class'		=> 'span12',
					'data-date-format'	=> 'dd/mm/yyyy',
					'default'	=> $defaultOpts['maturity_date'],
			));
			?>
		</div>

		<?php
		echo $this->Form->input(
				'interest_rate', array(
				'name'		=> 'data[Transaction][interest_rate]',
				'label'		=> 'Int.Rate %',
				'id'		=> 'TransactionInterestRate',
				'class'		=> 'span12',
				'default'	=> $defaultOpts['interest_rate'],
		));
		?>
		<?php
		echo $this->Form->input(
				'date_basis', array(
				'name'		=> 'data[Transaction][date_basis]',
				'label'		=> 'Day Basis',
				'id'		=> 'TransactionDateBasis',
				'class'		=> 'span12',
				'default'	=> $defaultOpts['date_basis'],
				'options'	=> array(
					"Act/360"	=> "Act/360",
					"Act/365"	=> "Act/365",
					"30/360"	=> "30E/360",
				)
		));
		?>
		<?php
		echo $this->Form->input(
				'total_interest', array(
				'name'		=> 'data[Transaction][total_interest]',
				'label'		=> 'Interest',
				'id'		=> 'TransactionTotalInterest',
				'class'		=> 'span12',
				'default'	=> $defaultOpts['total_interest'],
		));
		?>
		<div class="tax_amount optional">
			<?php
			echo $this->Form->input(
					'tax_amount', array(
					'name'		=> 'data[Transaction][tax_amount]',
					'label'		=> 'Tax',
					'type' => 'text',
					'id'		=> 'TransactionTaxAmount',
					'class'		=> 'span9',
					'default'	=> $defaultOpts['tax_amount'],
			));
			?>
			<button type="button" class="refreshTaxAmount"> <i class="icon-refresh"></i></button>
		</div>
 
		<?php if($trnIsSet)
		{
			echo $this->Form->input(
				'Transaction.tr_number', array(
				'type' => 'hidden',
				'value'	=>  $defaultOpts['tr_number'],
			));
		
			echo $this->Form->input(
				'Transaction.modified', array(
				'type' => 'hidden',
				'value'	=> $defaultOpts['modified'],
			));
			
		}

		echo $this->Form->input(
				'Transaction.comment', array(
				'type' => 'textarea',
				'label'	=> 'Comment',
				'class'		=> 'span12',
		));
		?>
		<?php endif; ?>

	</div>
	<?php echo $this->Form->input(
				'msg', array(
				'type' => 'textarea',
				'label'	=> false,
				'id'		=> 'lineWarn',
				'type'		=> 'hidden',
				'value'		=> '',
		)); ?>
	<div class="span5"></div>
	<div class="span3"></div>
	<div class="span3">
		<a href="#" id="SubmitNewdeposit" class="btn btn-info pull-right"><?php echo $submitButtonLabel ?></a>
	</div>
</div>


<style type="text/css">
	#transactionNewdepositForm div.tax_amount{ position: relative; }
	#transactionNewdepositForm div.tax_amount input{ margin-right: 40px;}
	#transactionNewdepositForm div.tax_amount button{ position: absolute; top: 25px; right: 8px; }
</style>

<!-- Ajax script used for Compartments population based on Mandate_ID -->
<?php
	$this->Js->get('#TransactionMandateID')->event('change',
		$this->Js->request(
			array(
				'controller'	=>	'treasuryajax',
				'action'		=>	'getcmpbymandate'
				),
			array(
				'update'		=>	'#TransactionCmpID',
				'async' 		=> 	true,
				'method' 		=> 	'post',
				'dataExpression'=>	true,
				'data'=> $this->Js->serializeForm(
					array(
						'isForm' => true,
						'inline' => true
						)
					)
				)
			)
		);
?>

<!-- Ajax script used for Counterparties population based on Mandate_ID -->
<?php
	$this->Js->get('#TransactionMandateID')->event('change',
		$this->Js->request(
			array(
				'controller'	=>	'treasuryajax',
				'action'		=>	'getcptybymandate'
				),
			array(
				'update'		=>	'#TransactionCptyId',
				/*'async' 		=> 	false,*/
				'async' 		=> 	true,
				'method' 		=> 	'post',
				'dataExpression'=>	true,
				'data'=> $this->Js->serializeForm(
					array(
						'isForm' => true,
						'inline' => true
						)
					)
				)
			)
		);
?>
<!--  Get accounts A and B from compartment and display them in a table-->
<?php
	$this->Js->get('#TransactionCmpID')->event('change',
		$this->Js->request(
			array(
				'controller'	=>	'treasuryajax',
				'action'		=>	'getaccounts'
				),
			array(
				'update'		=>	'#TransactionAccountsIBAN',
				'async' 		=> 	true,
				'method' 		=> 	'post',
				'dataExpression'=>	true,
				'data'=> $this->Js->serializeForm(
					array(
						'isForm' => true,
						'inline' => true
						)
					)
				)
			)
		);
?>
<!--  Get currency -->
<?php
	$this->Js->get('#TransactionCmpID')->event('change',
		$this->Js->request(
			array(
				'controller'	=>	'treasuryajax',
				'action'		=>	'getccy'
				),
			array(
				'update'		=>	'#ccy',
				'async' 		=> 	true,
				'method' 		=> 	'post',
				'dataExpression'=>	true,
				'data'=> $this->Js->serializeForm(
					array(
						'isForm' => true,
						'inline' => true
						)
					)
				)
			)
		);
?>

<!-- Get accounts A and B and put them in a Select options -->
<?php
	$this->Js->get('#TransactionCmpID')->event('change',
		$this->Js->request(
			array(
				'controller'	=>	'treasuryajax',
				'action'		=>	'accountslist'
				),
			array(
				'update'		=>	'#TransactionAccountAIBAN',
				'async' 		=> 	true,
				'method' 		=> 'post',
				'dataExpression'=>	true,
				'data'=> $this->Js->serializeForm(
					array(
						'isForm' => true,
						'inline' => true
						)
					)
				)
			)
		);
?>

<!-- Hide accounts table when mandate is changed  -->
<?php
	$this->Js->get('#TransactionMandateID')->event('change',
		$this->Js->request(
			array(
				'controller'	=>	'treasuryajax',
				'action'		=>	'hideaccounts'
				),
			array(
				'update'		=>	'#TransactionAccountsIBAN',
				'async' 		=> 	true,
				'method' 		=> 'post',
				'dataExpression'=>	true,
				'data'=> $this->Js->serializeForm(
					array(
						'isForm' => true,
						'inline' => true
						)
					)
				)
			)
		);
?>

<?php
	$this->Js->get('#TransactionCmpID')->event('change',
		$this->Js->request(
			array(
				'controller'	=>	'treasuryajax',
				'action'		=>	'accountslist'
			),
			array(
				'update'		=>	'#TransactionAccountBIBAN',
				'async' 		=> 	true,
				'method' 		=> 'post',
				'dataExpression'=>	true,
				'data'=> $this->Js->serializeForm(
					array(
						'isForm' => true,
						'inline' => true
						)
					)
				)
			)
		);
?>

<!-- Control Accounts Scheme A-A, A-B, B-B -->
<?php

	$data = $this->Js->get('#transactionNewdepositForm')->serializeForm(array('isForm' => true, 'inline' => true));

	$this->Js->get('#TransactionAccountAIBAN')->event('change',
		$this->Js->request(
			array(
				'controller'	=>	'treasuryajax',
				'action'		=>	'accountscheme'
			),
			array(
				'update'		=>	'#TransactionAccountBIBAN',
				'async' 		=> 	true,
				'method' 		=> 'post',
				'dataExpression'=>	true,
				'data'=> $data
			)
		)
	);
?>


<!--  Script for amount formatting, datepicker, showing/hiding/controling maturity date based on depo_term element and commencement date -->
<script type="text/javascript">
	$(document).ready(function(e){

		//custom for Eximbanka: always show the maturity date input
		var EximbankaID = 13;
		$('#TransactionCptyId').bind('change', function(e){
			$('#TransactionDepoTerm').trigger('change');
		});

		if($('#SubmitNewdeposit').hasClass('btn-info')){
			$('#SubmitNewdeposit').bind('click', function(e){
				$('#alertMsg').hide();
				$('.error-message').each(function(i, line){
					$(line).remove();
				});
				if($('#TransactionMandateID').val() && $('#TransactionCptyId').val() && $('#TransactionCmpID').val() && $('#TransactionCommencementDate').val() && $('#TransactionAccountAIBAN').val() && $('#TransactionAccountBIBAN').val()){
					$('#SubmitNewdeposit').removeClass('btn-info');
					$('#SubmitNewdeposit').removeClass('active');
					$('#SubmitNewdeposit').addClass('btn-default');
					$('#SubmitNewdeposit').addClass('disabled');
					limitbreachCheck();
				}else $('#transactionNewdepositForm').submit();
			});
		}

		/*$('#TransactionDepoTerm').change(function() {
			if ($(this).val() == "NS" || $('#TransactionCptyId').val()==EximbankaID) {
			     $('#MaturityDateDiv').show();
		    } else {
		    	$("#TransactionMaturityDate").val();
				$('#MaturityDateDiv').hide();
		    }
		});*/
		$('#TransactionDepoTerm').trigger('change');

		$('#TransactionTaxAmount').autoNumeric('init',{aSep: ',',aDec: '.', vMin:0});
		$('#TransactionAmount').autoNumeric('init',{aSep: ',',aDec: '.'});

		$('#TransactionDepoType').change(function(){
		    if ($(this).val() == "Callable") {
			     $('#depoTermDiv').hide();
			     $('#automaticRenewalDiv').hide();
			     $('#TransactionDepoTerm').val('');
			     $("#TransactionMaturityDate").val('');
				 $('#MaturityDateDiv').hide();
		    }else {
		    	$('#automaticRenewalDiv').show();
				$('#depoTermDiv').show()
		    }
		});
	});
	var checkout = {};
	var checkin = $('#TransactionCommencementDate').datepicker({ dateFormat: 'dd/mm/yy' }).on('changeDate', function(ev) {
			if (checkout.date && ev.date.valueOf() > checkout.date.valueOf() && $('#TransactionDepoTerm').val() == 'NS') {
				var newDate = new Date(ev.date);
				newDate.setDate(newDate.getDate() + 1);
				checkout.setValue(newDate);
			}
			checkin.hide();
			$('#TransactionMaturityDate')[0].focus();
		}).data('datepicker');

	checkout = $('#TransactionMaturityDate').datepicker({
		dateFormat: 'dd/mm/yy',
		onRender: function(date) {
			return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
		}
	}).on('changeDate', function(ev) {
		checkout.hide();
	}).data('datepicker');

	$("#TransactionCmpID").bind("change", function (event) {
		$.ajax({
			async:true,
			data:$("#TransactionCmpID").serialize(),
			dataType:"html",
			success:function (data, textStatus) {
				$("#TransactionCcy").val(data);
			},
			type:"post",
			url:"\/treasury\/treasuryajax\/getccy"}
		);
	});

	//refresh interest/tax if any changed in the concerned fields
	$('#transactionNewdepositForm').delegate('.input input, .input select', 'change', function(e){
		if($.inArray($(this).attr('name'), ['data[Transaction][commencement_date]', 'data[Transaction][amount]', 'data[Transaction][depo_term]', 'data[Transaction][maturity_date]', 'data[Transaction][interest_rate]'])>=0){
			
			if($(this).attr('name')!='data[Transaction][total_interest]' && $(this).attr('name')!='data[Transaction][tax_amount]'){
				$('.refreshTaxAmount').trigger('click');
			}
		}
	});

	//rates calculation
	$('.refreshTaxAmount').click(function(e){
		var ajaxpath = '';
		if($('#transactionNewdepositForm').attr('data-ajaxbasepath')) ajaxpath=$('#transactionNewdepositForm').attr('data-ajaxbasepath');

		var datas = {};

		datas['data[Transaction][mandate_ID]'] = $('#TransactionMandateID').val();
		datas['data[Transaction][cpty_id]'] = $('#TransactionCptyId').val();

		datas['data[Transaction][maturity_date]'] = $('#TransactionMaturityDate').val();
		datas['data[Transaction][commencement_date]'] = $('#TransactionCommencementDate').val();

		datas['data[Transaction][total_interest]'] = $('#TransactionTotalInterest').val();
		datas['data[Transaction][tax_amount]']     = $('#TransactionTaxAmount').val();

		//console.log(datas);

		//calculate INTEREST then TAX
		// if(!$('#TransactionTotalInterest"]').val()){
			$.ajax({data: $("#transactionNewdepositForm").serialize(), url: ajaxpath+'/calcTotalInterest?'})
			.done(function( data ){
				data = $.trim(data.split('(').join('').split(')').join('').split('?').join(''));
				//console.log(data);
				
				//console.log(data);
				if(data && data!='.' && data!='-' && data.length<20){
					if(data=='0') data='0.00';
					$('input[name="data[Transaction][total_interest]"]', this.line).val(data);

					datas = formQueueGetAllFields(this.line);
					datas['data[Transaction][mandate_ID]'] = $('#TransactionMandateID').val();
					datas['data[Transaction][cpty_id]'] = $('#TransactionCptyId').val();

					$.ajax({data: datas, url: ajaxpath+'/computeTaxFromInterestAndMandateCpty?'})
					.done(function( data ){
						if(!data) data='0.00';
						$('#TransactionTaxAmount').val(data);
					});
				}
				
			});
		// }

		e.preventDefault();
		return false;
	});

	function formQueueGetAllFields(parent){
		var fields = {};
		$('input[name], select[name], textarea[name]', parent).each(function(i, field){
			fields[$(field).attr('name')] = $(field).val();
		});
		return fields;
	}

	/* check limit breach for the whole batch of new deposits */
	function limitbreachCheck(){
		var fields = {};
		var datas = [];
		fields['linenum'] = 1;
		
		var f = document.getElementById("transactionNewdepositForm");
		for (var i in f.elements) {
			if(i.indexOf('data[Transaction]') != -1){
				fields[i] = f.elements[i].value;
			}
			
		}
		datas[0] = fields;
		$.ajax({
			async:true,
			data:{data:datas},
			dataType:"html",
			success:function (data, textStatus) {
				if(data){
					$('#alertMsg').show();
					$('#alertText').text(data);
					if(confirm('Create the transaction(s) in spite of limit breach') == true){
						$('#lineWarn').val(data);
						$('#transactionNewdepositForm').submit();
					}else{
						$('#SubmitNewdeposit').removeClass('btn-default');
						$('#SubmitNewdeposit').removeClass('disabled');
						$('#SubmitNewdeposit').addClass('btn-info');
						$('#SubmitNewdeposit').addClass('active');
					}

				}else $('#transactionNewdepositForm').submit();

			},
			type:"post",
			url:"\/treasury\/treasuryajax\/checkLimitBreach"}
		);
	}
</script>
