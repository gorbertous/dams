<?php
	echo $this->Html->css('/treasury/css/dataTableSort');
	echo $this->Html->css('/treasury/css/datepicker');
	//echo $this->Html->css('/treasury/css/radio-fx');
    //echo $this->Html->script('/treasury/js/radio-fx');
    echo $this->Html->script('/treasury/js/radio-fx-replacement');
?>
<div class="tabbable">
	<div id="alertMsg" style="display:<?php if(!empty($msg)){echo 'block';}else{echo 'none';}?>" class="alert alert-warning">
		<button type="button" class="close" data-dismiss="alert">×</button>
		<h4>Warning!</h4>
		<span id="alertText"><?php echo $msg ?></span>
	</div>
		<ul class="nav nav-tabs">
			<li class=" <?php echo $tab1state ;?> ">
			<a href="#tab1" data-toggle="tab">New Rollover Form</a>
		</li>
			<li class =" <?php echo $tab2state ;?>" >
				<a href="#tab2" data-toggle="tab">Result</a>
			</li>
		</ul>
	<div class="tab-content">
		<div class = "tab-pane <?php echo $tab1state; ?>" id = "tab1">
	   		<div class="span12" style="overflow:auto;">
			<?php if(!empty($reinvGroupOpts) && (is_array($reinvGroupOpts))) : ?>
			    <?php echo $this->Form->create('Transaction'); ?>
			    	<?php /*$this->BootstrapTables->displayRawsAsInputs('selectReinvGroup', $reinvGroup, 'radio', 'Transaction', 'reinv_group', false);*/ ?>
			    	<table id="selectReinvGroup" class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th> Select </th>
								<th> Reinv group </th>
								<th> Availibility date </th>
								<th> Amount left A </th>
								<th> Amount left B </th>
								<th> Mandate </th>
								<th> Compartment </th>
								<th> Counterparty </th>
								<th style = "display:none"> </th>
								<th style = "display:none"> </th>
								<th style = "display:none"> </th>
							</tr>
						</thead>
						<tbody>
		    			<?php foreach ($reinvGroupOpts as $key => $value): ?>
		    				<tr>
		    					<td>
								<?php
								echo $this->Form->input('Transaction.reinv_group', array(
									'type' => 'hidden',
									'label'	=> false,
									'div'	=> false,
								));
								?>
									<input class="origin_radio" type="radio" name="data[Transaction][reinv_group]" value="<?php echo $key ?>">
		    					</td>
		    					<td><?php echo UniformLib::uniform($key, 'reinv_group') ?></td>
		    					<td class="availability_date"><?php echo UniformLib::uniform($value['availability_date'], 'availability_date') ?></td>
		    					<td style="text-align:right;"><?php echo UniformLib::uniform($value['amount_leftA'],'amount_leftA').' '.UniformLib::uniform($value['ccy'], 'ccy') ?></td>
		    					<td style="text-align:right;"><?php echo UniformLib::uniform($value['amount_leftB'],'amount_leftB').' '.UniformLib::uniform($value['ccy'], 'ccy') ?></td>
		    					<td><?php echo UniformLib::uniform($value['mandate_name'], 'mandate_name') ?></td>
		    					<td><?php echo UniformLib::uniform($value['cmp_name'], 'cmp_name') ?></td>
		    					<td><?php echo UniformLib::uniform($value['cpty_name'], 'cpty_name') ?></td>
		    					<td class="mandate" style = "display:none"><?php echo $value['mandate_ID']?></td>
		    					<td class="cmp" style = "display:none"><?php echo $value['cmp_ID']?></td>
		    					<td class="cpty" style = "display:none"><?php echo $value['cpty_ID']?></td>
		    				</tr>
		    			<?php endforeach; ?>
		    			</tbody>
		    		</table>
		    		<br>
		    		<div class="row-fluid radio-form">
				    	<div class="span6 well">
							<?php 	echo $this->Form->input('source_fund',
										array(
								        	//'empty' 	=> __('-- Select a fund --'),
								        	'label'		=> "Rollover from Funds",
								            'options'   => array('A'=>'A')
			    						)
									);
				   			?>

		   					<div class="input-append pos-relative input-with-ccy-add-on">
					    		<label>Rollover Amount</label>
			   					<?php 	echo $this->Form->input('amount',
		    								array(
									        	'type' 			=> 'text',
									            'label'     	=> false,//'Repayment Amount',
									            'placeholder'   => '0.00',
									            'class'=> 'text-right',
									            'div'=>false,
									            //'data-a-sign'=> ' '.,
									            //'data-p-sign'=> 's',
									            'before'=>'<div class="add-on pos-absolute-top-right ccy"></div>',
									            /*'required'  	=> true,*/
							            	)
					            		);
			   					?>
		   					</div>

							<?php 	echo $this->Form->input('accountA_IBAN',
											array(
								            'label'     => 'Principal Account',
								            'options'   => array(''),
								            //'empty' 	=> __('-- Select an account --'),
								            /*'required'  => true,*/
									    )
					            	);
			            	?>

							<?php 	echo $this->Form->input('accountB_IBAN',
										array(
										    'label'     => 'Interest Account',
										    'options'   => array(''),
										    //'empty' 	=> __('-- Select an account --'),
										    /*'required'  => true,*/
										)
									);
	
								echo $this->Form->input('TransactionScheme', array(
									'type' => 'hidden',
									'label'	=> false,
									'div'	=> false,
									'id'	=> 'TransactionScheme',
								));
								echo $this->Form->input('depo_type',
										array(
											'label'     => 'Rollover Type',
											'options'   => array('Term' => 'Term'),
											/*'empty' 	=> __('-- Choose One --'),*/
										)
									);
							?>

							<div id="depoTermDiv">
							    <?php 	echo $this->Form->input('depo_term',
			 								array(
									            'label'     => 'Depo term',
									            'options'   => $depoTerm,
									            'empty'		=> __('-- Select a depo term --'),
									            /*'required'  => true,*/
								            )
						            	);
							   	?>
						   	</div>

					    	<div id="MaturityDateDiv" class="input file" style="display:none">
								<?php
									echo $this->Form->input(
											'maturity', array(
											'name'=> 'data[Transaction][maturity_date]',
											'label'	=> 'Maturity date',
											'id'	=> 'TransactionMaturityDate',
											'data-date-format'	=> 'dd/mm/yyyy',
									));
								?>
							<span id="week_end_date_error"></span>
							</div>

							<div id="automaticRenewalDiv">
					   			<?php 	echo $this->Form->input('depo_renew',
											array(
									        	'label'		=> "Automatic renewal at maturity",
									            'options'   => array('Yes'=>'Yes','No'=>'No'),
												'default' 	=> 'No',
				    						)
										);
					   			?>
				   			</div>
							<div style="display:none;">
							<?php
							echo $this->Form->input('cmp_id', array(
								'type' => 'text',
								'label'	=> false,
								'div'	=> false,
								'id'	=> 'cmp_id',
							));
							echo $this->Form->input('mandate_id', array(
								'type' => 'text',
								'label'	=> false,
								'div'	=> false,
								'id'	=> 'mandate_id',
							));
							echo $this->Form->input('cpty_id', array(
								'type' => 'text',
								'label'	=> false,
								'div'	=> false,
								'id'	=> 'cpty_id',
							));
							echo $this->Form->input('availability_date', array(
								'type' => 'text',
								'label'	=> false,
								'div'	=> false,
								'id'	=> 'availability_date',
							));
							echo $this->Form->input('Transaction.reinv_group', array(
								'type' => 'text',
								'label'	=> false,
								'div'	=> false,
								'id'	=> 'reinv_group_submit',
							));
							?>
							</div>
							<a href="#" class="btn btn-default btn-radio-cancel">Cancel</a>
							<a href="#" id="SubmitNewRollover" class="btn btn-primary checkForm">New Rollover</a>
						</div>
						<div class="span6 well" id="accDiv"> </div>
					</div>
					<?php echo $this->Form->end(); ?>
			<?php else: ?>
					<div class="alert alert-info">There is not any open reinvestment. </div>
			<?php endif; ?>
			</div>
		</div>
		<div class = "tab-pane <?php echo $tab2state; ?>" id = "tab2">
			<div id="results" style="overflow:auto;">
		     	<?php echo $msg; ?>
		     	<?php  if (isset($rollover)): ?>
		     		<div class="alert alert-block alert-info success operation-result">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Success !</strong> The new rollover has been created (
							<?php print UniformLib::uniform($rollover[0]['Transaction']['Amount'], 'Amount').' '.UniformLib::uniform($rollover[0]['Transaction']['ccy'], 'ccy').' from '.UniformLib::uniform($rollover[0]['Transaction']['source_fund'], 'source_fund').' to '.UniformLib::uniform($rollover[0]['Transaction']['principal_account'], 'principal_account').' / '.UniformLib::uniform($rollover[0]['Transaction']['interest_account'], 'interest_account'); ?>)
					</div>
		     		<?php echo $this->BootstrapTables->displayRawsById('rollover', $rollover); ?>
			    <?php endif; ?>
	     	</div>
	    </div>
	</div>
</div>
<script>
function jsUpdate(){
	setTimeout(function(e){
		var ccy = '';
		if($('#accDiv td.ccy:first').length){
			ccy = $.trim($('#accDiv td.ccy:first').text())
		}
		$('.input-with-ccy-add-on .add-on').text(ccy);
	}, 100);
	
}
</script>
<style>
.input-append{ width: 100%; position: relative; min-height: 50px; }
.input-append label{ width:230px; }
.input-append input{ width: 160px !important; position: absolute; top: 0; right: 51px; }
.input-append .add-on{ position: absolute; top: 0; right: 0; width: 40px; height: 30px; line-height: 30px; }
#week_end_date_error { color: red; }
</style>

<div style="display:none;">
<?php
echo $this->Form->create('accountscheme', array('url'=>'/treasury/treasuryajax/accountscheme'));
echo $this->Form->input('Transaction.accountA_IBAN', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.reinv_group', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();

echo $this->Form->create('accountscheme2', array('url'=>'/treasury/treasuryajax/getaccounts2'));
echo $this->Form->input('Transaction.reinv_group', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();

echo $this->Form->create('getreinvacc', array('url'=>'/treasury/treasuryajax/getreinvacc/Transaction'));
echo $this->Form->input('Transaction.reinv_group', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();

echo $this->Form->create('check', array('url'=>'/treasury/treasuryajax/checkLimitBreach'));
echo $this->Form->input('data.Transaction.mandate_ID', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('data.Transaction.cmp_ID', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('data.Transaction.cpty_id', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('data.Transaction.commencement_date', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('data.Transaction.amount', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('data.Transaction.reinv_group', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('data.Transaction.TransactionScheme', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('data.Transaction.maturity_date', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('data.Transaction.ccy', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('data.Transaction.reinv_group', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
	'id'	=> 'reinv_group_check'
));
echo $this->Form->end();
?>
</div>
<?php
	echo $this->Html->script('/treasury/js/autoNumeric.js');
	echo $this->Html->script('/treasury/js/bootstrap-datepicker');
	echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/jquery.dataTables.js');
	echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/dataTables.bootstrap.js');
	echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/extras/TableTools/media/js/ZeroClipboard.js');
	//echo $this->Html->script('/theme/Cakestrap/js/libs/jquery-ui.min.js');
?>

<script type="text/javascript">
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
	var submitting = false;
	$(document).ready(function () {
	
		//evenement when click on create rollover
		$('.checkForm').bind('click', function(e){
			clickSubmit(e);
		});
		
		function clickSubmit(e)
		{
			if (!submitting)
			{
				submitting = true;
				$('#SubmitNewRollover')[0].disabled = true;//to avoid double validation
				$('#SubmitNewRollover').attr('disabled', true);//to avoid double validation
				if(!$('#TransactionSourceFund').val() || !$('#TransactionAmount').val() || !$('#TransactionAccountAIBAN').val() || !$('#TransactionAccountBIBAN').val() || !$('#TransactionDepoTerm').val()){
					$('#TransactionNewrolloverForm').submit();
				}
				else
				{
					limitbreachCheck();
				}
			}
			else
			{
				e.preventDefault();
			}
		}

		$("#submit").hide();
		  $("input[type = radio]").change(function() {
	          $("#submit").show();
	   	});


		$('#TransactionAmount').autoNumeric('init',{aSep: ',',aDec: '.', vMax: 9999999999999.99, vMin:-9999999999999.99});


		$('#TransactionDepoTerm').change(function() {
		    if ($(this).val() == "NS") {
				$('#MaturityDateDiv').show();
				$("#TransactionMaturityDate").change(function(e){
					e = $(e.target);
					if ( isWeekEnd(e.val()) )
					{
						$("#week_end_date_error").html("<p>The maturity date falls on a weekend.</p>");
					}
					else
					{
						$("#week_end_date_error").empty();
					}
				});
				 
		    }
		    else {
		    	$("#TransactionMaturityDate").val('');
				$('#MaturityDateDiv').hide();
		    }
		});

		$('#TransactionDepoType').change(function() {
		    if ($(this).val() == "Callable") {
			     $('#depoTermDiv').hide();
			     $('#automaticRenewalDiv').hide();
			     $('#TransactionDepoTerm').val('');
			     $("#TransactionMaturityDate").val('');
				 $('#MaturityDateDiv').hide();
		    }
		    else {
		    	$('#automaticRenewalDiv').show();
				$('#depoTermDiv').show()
		    }
		});



		var nowTemp = new Date();
		var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		$('#TransactionMaturityDate').datepicker({
			onRender: function(date) {
		    	return date.valueOf() < now.valueOf() ? 'disabled' : '';
			}
		});


		$('#selectReinvGroup tr').click(function () {
	    	//$(this).find('td input:radio').prop('checked', true);
	    	$(this).find('td input:radio:checked');
	    	$('#selectReinvGroup tr').removeClass("active");
	    	$(this).addClass("active");
		});

		$('#TransactionAccountAIBAN').change(function(e)
		{
			var reinv_group = $('tr.active').find('input.origin_radio').val();
			$('#accountschemeNewrolloverForm #TransactionAccountAIBAN').val( $('#TransactionNewrolloverForm #TransactionAccountAIBAN').val() );
			$('#accountschemeNewrolloverForm #TransactionReinvGroup').val( reinv_group );
			var data = $('#accountschemeNewrolloverForm').serialize();
			$.ajax({
				type: "POST",
				url: '/treasury/treasuryajax/accountscheme',
				dataType: 'text', 
				data: data,
				async:true,
				success:function (data, textStatus) {
					$('#TransactionAccountBIBAN').html(data);
				}
			});
		});

		$('#selectReinvGroup input[type=radio]').click(function(e)
		{
			var reinv_group = $(e.currentTarget).val();
			$('#accountscheme2NewrolloverForm #TransactionReinvGroup').val( reinv_group );

			var data = $('#accountscheme2NewrolloverForm').serialize();
			$.ajax({
				type: "POST",
				url: '/treasury/treasuryajax/getaccounts2',
				dataType: 'text', 
				data: data,
				async:true,
				success:function (data, textStatus) {
					$('#TransactionAccountAIBAN').html(data);
					$('#TransactionAccountBIBAN').html(data);
				}
			});
		});

		$('#selectReinvGroup input[type=radio]').click(function(e)
		{
			var reinv_group = $(e.currentTarget).val();
			$('#reinv_group_submit').val(reinv_group);
			$('#getreinvaccNewrolloverForm #TransactionReinvGroup').val( reinv_group );

			var data = $('#getreinvaccNewrolloverForm').serialize();
			$.ajax({
				type: "POST",
				url: '/treasury/treasuryajax/getreinvacc/Transaction',
				dataType: 'text', 
				data: data,
				async:true,
				success:function (data, textStatus) {
					jsUpdate();
					$('#accDiv').html(data);
				}
			});
		});
		
	});

	/* check limit breach for the new rollover */
	function limitbreachCheck(){

		$('#checkNewrolloverForm input[name="data[data][Transaction][mandate_ID]]"]').val( $('#mandate_id').val() );
		$('#checkNewrolloverForm input[name="data[data][Transaction][cmp_ID]]"]').val( $('#cmp_id').val() );
		$('#checkNewrolloverForm input[name="data[data][Transaction][cpty_id]]"]').val( $('#cpty_id').val() );
		$('#checkNewrolloverForm input[name="data[data][Transaction][commencement_date]]"]').val( $('#availability_date').val() );
		$('#checkNewrolloverForm input[name="data[data][Transaction][amount]]"]').val( $('#TransactionAmount').val() );
		$('#checkNewrolloverForm input[name="data[data][Transaction][reinv_group]]"]').val( $('#TransactionNewrolloverForm #reinv_group_submit').val() );
		$('#checkNewrolloverForm input[name="data[data][Transaction][TransactionScheme]]"]').val( $('#TransactionNewrolloverForm #TransactionScheme').val() );
		$('#checkNewrolloverForm input[name="data[data][Transaction][maturity_date]]"]').val( $('#TransactionNewrolloverForm #TransactionMaturityDate').val() );
		$('#reinv_group_check').val( $('.active').parents('td').find('.origin_radio').val() );
		$('#checkNewrepaymentForm input[name="data[data][Transaction][ccy]"]').val( $('#ccy_check').text() );
		var data = $('#checkNewrolloverForm').serialize();

		$.ajax({
			async:true,
			data:data,
			dataType:"html",
			success:function (data, textStatus) {
				if(data){
					$('#alertMsg').show();
					$('#alertText').text(data);
					if(confirm('Create the transaction(s) in spite of limit breach') == true){
						$('#TransactionNewrolloverForm').submit();
					}
					else
					{
						submitting = false;
						$('#SubmitNewRollover')[0].disabled = false;
						$('#SubmitNewRollover').attr('disabled', false);
					}
				}
				else
				{
					$('#TransactionNewrolloverForm').submit();
					$('#SubmitNewRollover').attr('disabled', false);
				}
			},
			type:"post",
			url:"\/treasury\/treasuryajax\/checkLimitBreach"}
		);
	}
</script>

