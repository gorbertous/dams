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
								<th> Reinv  </th>
								<th> Availibility date </th>
								<th> Funds left A </th>
								<th> Funds left B </th>
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
									<?php echo $this->Form->input('Transaction.reinv_group' , array(
											'type'		=> 'radio',
											'label'		=> false,
											'class'		=> 'origin_radio',
											'value'		=> $key
										)
									); ?>
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
								        	'empty' 	=> __('-- Select a fund --'),
								        	'label'		=> "Rollover from Funds",
								            'options'   => array('A'=>'A', 'B'=> 'B')
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
								            'empty' 	=> __('-- Select an account --'),
								            /*'required'  => true,*/
									    )
					            	);
									echo $this->Form->input('accountB_IBAN',
										array(
										    'label'     => 'Interest Account',
										    'options'   => array(''),
										    'empty' 	=> __('-- Select an account --'),
										    /*'required'  => true,*/
										)
									);
									echo $this->Form->input('' , array(
											'type'		=> 'hidden',
											'label'		=> false,
											'id'		=> 'TransactionScheme',
										)
									);
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
							<?php
							echo $this->Form->input('cmp_id' , array(
									'type'		=> 'hidden',
									'label'		=> false,
									'id'		=> 'cmp_id',
								)
							);
							echo $this->Form->input('mandate_id' , array(
									'type'		=> 'hidden',
									'label'		=> false,
									'id'		=> 'mandate_id',
								)
							);
							echo $this->Form->input('cpty_id' , array(
									'type'		=> 'hidden',
									'label'		=> false,
									'id'		=> 'cpty_id',
								)
							);
							echo $this->Form->input('availability_date' , array(
									'type'		=> 'hidden',
									'label'		=> false,
									'id'		=> 'availability_date',
								)
							);
							
							?>
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

<?php
	$this->Js->get('#selectReinvGroup input[type=radio]')->event('click',
		$this->Js->request(
			array(
				'controller'	=>	'treasuryajax',
				'action'		=>	'getreinvacc', 'Transaction'
			),
			array(
				'success'=> "jsUpdate();",
				'update'		=>	'#accDiv',
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
	$this->Js->get('#selectReinvGroup input[type=radio]')->event('click',
		$this->Js->request(
			array(
				'controller'	=>	'treasuryajax',
				'action'		=>	'getaccounts2'
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

<!-- Control Accounts Scheme A-A, A-B, B-B -->
<?php
	$data = $this->Js->get('#TransactionNewrolloverForm')->serializeForm(array('isForm' => true, 'inline' => true));

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

		
		
	});

	/* check limit breach for the new rollover */
	function limitbreachCheck(){
		var fields = {};
		var datas = [];
		fields['linenum'] = 1;
		fields['data[Transaction][mandate_ID]'] = $('#mandate_id').val();
		fields['data[Transaction][cmp_ID]'] = $('#cmp_id').val();
		fields['data[Transaction][cpty_id]'] = $('#cpty_id').val();
		fields['data[Transaction][commencement_date]'] = $('#availability_date').val();
		var f = document.getElementById("TransactionNewrolloverForm");
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

