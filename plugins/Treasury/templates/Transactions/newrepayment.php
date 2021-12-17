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
			    			<th> Reinv group </th>
							<th> Availability date </th>
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
								echo $this->Form->input('newrepayform.reinv_group', array(//just for CSRF
									'type' => 'hidden',
									'label'	=> false,
									'div'	=> false,
									
								));
								?>
								<input class="origin_radio" type="radio" name="data[newrepayform][reinv_group]" value="<?php echo $key ?>">
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
							        	//'empty' 	=> __('-- Select a fund --'),
							        	'label'		=> "Repayment from Funds",
							            'options'   => array('A'=>'A')
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
										//'empty' 	=> __('-- Select an account --'),
									)
								);

						echo $this->Form->input('cmp_id', array(
							'type' => 'text',
							'label'	=> false,
							'div'	=> false,
							'id'	=> 'cmp_id',
							'style'	=> 'display: none;',
						));
						echo $this->Form->input('mandate_id', array(
							'type' => 'text',
							'label'	=> false,
							'div'	=> false,
							'id'	=> 'mandate_id',
							'style'	=> 'display: none;',
						));
						echo $this->Form->input('availability_date', array(
							'type' => 'text',
							'label'	=> false,
							'div'	=> false,
							'id'	=> 'availability_date',
							'style'	=> 'display: none;',
						));
						echo $this->Form->input('ccy', array(
							'type' => 'text',
							'label'	=> false,
							'div'	=> false,
							'id'	=> 'ccy_check',
							'style'	=> 'display: none;',
						));
						echo $this->Form->input('reinv_group', array(
							'type' => 'text',
							'label'	=> false,
							'div'	=> false,
							'id'	=> 'reinv_group_check',
							'style'	=> 'display: none;',
						));
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
	var ccy = '';
	if($('#accDiv td.ccy:first').length){
		ccy = $.trim($('#accDiv td.ccy:first').text())
	}
	$('.input-with-ccy-add-on .add-on').text(ccy);
	$('#ccy_check').val(ccy);
}
</script>

<div style="display:none;">
<?php
echo $this->Form->create('accounts', array('url'=>'/treasury/treasuryajax/accounts'));
echo $this->Form->input('newrepayform.reinv_group', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();

echo $this->Form->create('getreinvacc', array('url'=>'/treasury/treasuryajax/getreinvacc/newrepayform'));
echo $this->Form->input('newrepayform.reinv_group', array(
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
?>
</div>

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

		$('#selectReinvGroup input[type=radio]').click(function(e)
		{
			var reinv_group = $(e.currentTarget).val();
			$('#accountsNewrepaymentForm #newrepayformReinvGroup').val( reinv_group );
			var data = $('#accountsNewrepaymentForm').serialize();
			$.ajax({
				type: "POST",
				url: '/treasury/treasuryajax/accounts',
				dataType: 'text', 
				data: data,
				async:true,
				success:function (data, textStatus) {
					$('#newrepayformRepaymentAcc').html(data);
				}
			});
		});

		$('#selectReinvGroup input[type=radio]').click(function(e)
		{
			var reinv_group = $(e.currentTarget).val();
			$('#getreinvaccNewrepaymentForm #newrepayformReinvGroup').val( reinv_group );
			var data = $('#getreinvaccNewrepaymentForm').serialize();
			$.ajax({
				type: "POST",
				url: '/treasury/treasuryajax/getreinvacc/newrepayform',
				dataType: 'text', 
				data: data,
				async:true,
				success:function (data, textStatus) {
					$('#accDiv').html(data);
					jsUpdate();
				}
			});
		});

	});

	/* check limit breach for the new rollover */
function limitbreachCheck(){

	$('#checkNewrepaymentForm #checkLinenum').val( 1 );
	$('#checkNewrepaymentForm input[name="data[data][Transaction][mandate_ID]"]').val( $('#mandate_id').val() );
	$('#checkNewrepaymentForm input[name="data[data][Transaction][cmp_ID]"]').val( $('#cmp_id').val() );
	$('#checkNewrepaymentForm input[name="data[data][Transaction][cpty_id]"]').val( $('#cpty_id').val() );
	$('#checkNewrepaymentForm input[name="data[data][Transaction][commencement_date]"]').val( $('#availability_date').val() );
	$('#checkNewrepaymentForm input[name="data[data][Transaction][amount]"]').val( $('#newrepayformAmount').val() );
	$('#checkNewrepaymentForm input[name="data[data][Transaction][ccy]"]').val( $('#ccy_check').text() );
	$('#reinv_group_check').val( $('.active').parents('td').find('.origin_radio').val() );
	var data = $('#checkNewrepaymentForm').serialize();

	$.ajax({
		async:true,
		data:data,
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


