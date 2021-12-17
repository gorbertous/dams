<?php
	echo $this->Html->css('/treasury/css/dataTableSort');
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
<div class="tabbable">
	<ul class="nav nav-tabs">
		<li class=" <?php echo $tab1state ?> ">
			<a href="#tab1" data-toggle="tab">New Repayment Form</a>
		</li>
		<li class =" <?php echo $tab2state ?>" >
			<a href="#tab2" data-toggle="tab">Result</a>
		</li>
	</ul>
	<div class="tab-content">
		<div class = "tab-pane <?php echo $tab1state; ?>" id = "tab1">
	   		<div class="row-fluid span12" style="overflow:auto;">
    		<?php if(!empty($reinvGroupOpts) && is_array($reinvGroupOpts)): ?>
				<?php echo $this->Form->create('newrepayform'); ?>

	    		<table id="selectReinvGroup" class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th> Select </th>
			    			<th> Reinv  </th>
							<th> Availability date </th>
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
								<?php echo $this->Form->input('newrepayform.reinv_group' , array(
										'type'		=> 'radio',
										'label'		=> false,
										'class'		=> 'origin_radio',
										'value'		=> $key,
									)
								); ?>
	    					</td>
	    					<td class="text-right"><?php echo $key ?></td>
	    					<td class="availability_date"><?php echo UniformLib::uniform($value['availability_date'], 'availability_date') ?></td>
	    					<td style="text-align:right;"><?php echo UniformLib::uniform($value['amount_leftA'], 'amount_leftA').' '.UniformLib::uniform($value['ccy'], 'ccy') ?></td>
	    					<td style="text-align:right;"><?php echo UniformLib::uniform($value['amount_leftB'], 'amount_leftB').' '.UniformLib::uniform($value['ccy'], 'ccy') ?></td>
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
				<div class="row-fluid hide radio-form">
			    	<div class="span6 well">
						<?php 	echo $this->Form->input('Source',
									array(
							        	'empty' 	=> __('-- Select a fund --'),
							        	'label'		=> "Repayment from Funds",
							            'options'   => array('A'=>'A', 'B'=> 'B')
		    						)
								);
			   			?>

				    	<?php if(isset($amountError) and $amountError): ?>
				    		<div class="alert alert-block alert-error">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								<strong>Error!</strong> Repayment Amount cannot be empty.
							</div>
				    	<?php endif; ?>

				    	<div class="input-append pos-relative input-with-ccy-add-on">
				    		<label>Repayment Amount</label>
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

						<?php 	echo $this->Form->input('RepaymentAcc',
		    	 					array(
										'label'     => 'Repayment account',
										'options'   => array(''),
										'empty' 	=> __('-- Select an account --'),
									)
								);
						
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
						<a href="#" id="SubmitNewRepayment" class="btn btn-primary checkForm">New Repayment</a>
					</div>
					<div class="span6 well" id="accDiv"> </div>
				</div>
				<?php echo $this->Form->end() ?>
			
    		<?php else: ?>
    			<div class="alert alert-info">There is not any open reinvestment. </div>
			<?php endif; ?>
			</div>
		</div>
		<div class = "tab-pane <?php echo $tab2state; ?>" id = "tab2">
			<div id="results" style="overflow:auto;">
		     	<?php echo $msg; ?>
		     	<?php  if (isset($repayment)): ?>
		     		<div class="span12" style="overflow:auto;">
						<div class="alert alert-block alert-info operation-result">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							<strong>Success !</strong> The new repayment has been created (
							<?php print UniformLib::uniform($repayment[0]['Transaction']['amountccy'], 'amountccy').' from '.UniformLib::uniform($repayment[0]['Transaction']['source_fund'], 'source_fund').' to '.UniformLib::uniform($repayment[0]['Transaction']['repayment_account'], 'repayment_account'); ?>)
						</div>
			     		<?php echo $this->BootstrapTables->displayRawsById('repayment', $repayment); ?>
			     	</div>
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

<?php
	$this->Js->get('#selectReinvGroup input[type=radio]')->event('click',
		$this->Js->request(
			array(
				'controller'	=>	'treasuryajax',
				'action'		=>	'getreinvacc',
				'newrepayform',
			),
			array(
				'success'=> "jsUpdate();",
				'update'		=>	'#accDiv',
				'async' 		=> 	true,
				'method' 		=> 'post',
				'dataExpression'=>	true,
				//
				'data'=> $this->Js->serializeForm(
					array(
						'isForm' => true,
						'inline' => true
					)
				)
			)
		)
	);

	$this->Js->get('#selectReinvGroup input[type=radio]')->event('click',
		$this->Js->request(
			array(
				'controller'	=>	'treasuryajax',
				'action'		=>	'accounts'
			),
			array(
				'update'		=>	'#newrepayformRepaymentAcc',
				'async' 		=> 	true,
				'method' 		=> 'post',
				'dataExpression'=>	true,
				'data'=> $this->Js->serializeForm(array(
					'isForm' => true,
					'inline' => true
				))
			)
		)
	);
?>


<?php
	echo $this->Html->script('/treasury/js/autoNumeric.js');
	//echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/jquery.dataTables.js');
	//echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/dataTables.bootstrap.js');
	//echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/extras/TableTools/media/js/ZeroClipboard.js');
	//echo $this->Html->script('/theme/Cakestrap/js/libs/jquery-ui.min.js');
?>

<script type="text/javascript">
	var submitting = false;
	$(window).load(function () {
		$("#selectReinvGroup input[type=radio]").click(function(){
			$(".row-fluid.hide").show();
		});

		$('.checkForm').bind('click', function(e){
			clickSubmit(e);
		});
		
		function clickSubmit(e)
		{
			if (! submitting)
			{
				submitting = true;
				$('#SubmitNewRepayment')[0].disabled = true;//to avoid double validation
				$('#SubmitNewRepayment').attr('disabled', true);
				if(!$('#newrepayformSource').val() || !$('#newrepayformAmount').val() || !$('#newrepayformRepaymentAcc').val()){
					$('#newrepayformNewrepaymentForm').submit();
				}else limitbreachCheck();
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

		$('#newrepayformAmount').autoNumeric('init',{aSep: ',',aDec: '.', vMax: 9999999999999.99, vMin:-9999999999999.99});
	});

	/* check limit breach for the new rollover */
function limitbreachCheck(){
	var fields = {};
	var datas = [];
	fields['linenum'] = 1;
	fields['data[newrepayform][mandate_ID]'] = $('#mandate_id').val();
	fields['data[newrepayform][cmp_ID]'] = $('#cmp_id').val();
	fields['data[newrepayform][cpty_id]'] = $('#cpty_id').val();
	fields['data[newrepayform][commencement_date]'] = $('#availability_date').val();
	var f = document.getElementById("newrepayformNewrepaymentForm");
	for (var i in f.elements) {
		if(i.indexOf('data[newrepayform]') != -1){
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
					$('#newrepayformNewrepaymentForm').submit();
				}
				else
				{
					$('#SubmitNewRepayment')[0].disabled = false;
					$('#SubmitNewRepayment').attr('disabled', false);
					submitting = false;
				}

			}else $('#newrepayformNewrepaymentForm').submit();

		},
		type:"post",
		url:"\/treasury\/treasuryajax\/checkLimitBreach"}
	);
}
</script>
<style>
#selectReinvGroup td.text-right{ text-align: right !important; }
.input-append{ width: 100%; position: relative; min-height: 50px; }
.input-append label{ width:230px; }
.input-append input{ width: 160px !important; position: absolute; top: 0; right: 51px; }
.input-append .add-on{ position: absolute; top: 0; right: 0; width: 40px; height: 30px; line-height: 30px; }
</style>


