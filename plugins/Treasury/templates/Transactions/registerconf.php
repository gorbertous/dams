<?php
	echo $this->Html->script('/treasury/js/bootstrap-datepicker');
	echo $this->Html->script('/treasury/js/autoNumeric.js');
	//echo $this->Html->css('/treasury/css/dataTableSort');

	echo $this->Html->script('/treasury/js/form_ajax.js');
	echo $this->Html->script('/treasury/js/transactions.js');

	//echo $this->Html->css('/treasury/css/radio-fx');
    //echo $this->Html->script('/treasury/js/radio-fx');
    echo $this->Html->script('/treasury/js/radio-fx-replacement');
?>
<fieldset>
	<legend>Register Confirmation</legend>
<div id="register" class="">
   	<?php echo $this->Form->create('Transaction') ?>
	<?php
	echo $this->Form->input('Transaction.tr_state', array(
		'type' => 'hidden',
		'label'	=> false,
		'div'	=> false,
		'value'	=> 'Confirmation Received',
	));
	?>
	<div id="registerConfSelect" class="">
		<?php if(isset($trList) && is_array($trList)): ?>
			<table id="selectTRNToRegister" class="table table-bordered table-striped table-hover table-condensed">
   				<thead>
   					<th> Select </th>
					<th> DI </th>
					<th> TRN </th>
					<th> Amount </th>
					<th> Period </th>
					<th> Mandate </th>
	   				<th> Compartment </th>
	   				<th> Commencement Date </th>
   				</thead>
				<tbody>
				<?php foreach ($trList as $key => $value): ?>
					<tr>
						<td>
							<?php /*<input type="radio" class="fxrad origin_radio tr_number_radio" name="data[Transaction][tr_number]" value="<?php echo $key ?>" id="TransactionTrNumber<?php echo $key ?>"/>*/ ?>
							<a href="<?php print Router::url(array(
	    'controller' => 'treasurytransactions', 'action' => 'registerInstrConf', $value['instr_num'])) ?>" class="btn btn-default">Select</a>

							<?php
							echo $this->Form->input('', array(
									'type' => 'hidden',
									'label'	=> false,
									'div'	=> false,
									'class'	=> 'depo_type',
									'value'	=> $value['depo_type']
								));
							echo $this->Form->input('', array(
									'type' => 'hidden',
									'label'	=> false,
									'div'	=> false,
									'class'	=> 'depo_term',
									'value'	=> $value['depo_term']
								));
							?>
						</td>
						<td><?php echo UniformLib::uniform($value['instr_num'], 'instr_num') ?></td>
						<td><?php echo UniformLib::uniform($key, 'trn') ?></td>
						<td style="text-align:right;"><?php echo UniformLib::uniform($value['amount'],'amount')." ".UniformLib::uniform($value['currency'], 'currency'); ?></td>
						<td style="text-align:right;"><?php echo UniformLib::uniform($value['depo_term'], 'depo_term') ?></td>
						<td><?php echo UniformLib::uniform($value['mandate_name'], 'mandate_name') ?></td>
						<td><?php echo UniformLib::uniform($value['cmp_name'], 'cmp_name') ?></td>
						<?php $date  = new DateTime(str_replace('/','-',$value['commencement_date'])); ?>
						<?php $date2  = new DateTime(str_replace('/','-',$value['maturity_date'])); ?>
						<td style="text-align:right;"><?php echo UniformLib::uniform($value['commencement_date'], 'commencement_date') ?></td>

						<?php
							echo $this->Form->input('', array(
									'type' => 'hidden',
									'label'	=> false,
									'div'	=> false,
									'class'	=> 'com_day',
									'value'	=> $date->format('j'),
								));
							echo $this->Form->input('', array(
									'type' => 'hidden',
									'label'	=> false,
									'div'	=> false,
									'class'	=> 'com_mon',
									'value'	=> $date->format('n'),
								));
							echo $this->Form->input('', array(
									'type' => 'hidden',
									'label'	=> false,
									'div'	=> false,
									'class'	=> 'com_year',
									'value'	=> $date->format('Y'),
								));
							echo $this->Form->input('', array(
									'type' => 'hidden',
									'label'	=> false,
									'div'	=> false,
									'class'	=> 'mat_day',
									'value'	=> $date->format('d'),
								));
							echo $this->Form->input('', array(
									'type' => 'hidden',
									'label'	=> false,
									'div'	=> false,
									'class'	=> 'mat_mon',
									'value'	=> $date->format('m'),
								));
							echo $this->Form->input('', array(
									'type' => 'hidden',
									'label'	=> false,
									'div'	=> false,
									'class'	=> 'mat_year',
									'value'	=> $date->format('Y'),
								));
							echo $this->Form->input('', array(
									'type' => 'hidden',
									'label'	=> false,
									'div'	=> false,
									'class'	=> 'currency',
									'value'	=> UniformLib::uniform($value['currency'], 'currency'),
								));
							?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
			<div id="ifselected" class="well radio-form">
				<div class="row-fluid">
					<div class="span12">
						<?php
							echo $this->Form->input(
								'external_ref', array(
										'type' 			=> 'text',
										'label'     	=> 'External Reference',
										/*'required'  	=> true,*/
							));
						?>
					</div>
				</div>
				<div class="row-fluid" id="interest_at_maturity">
					<div class="span6">
						<?php
							echo $this->Form->input(
								'interest_rate', array(
										'type' 			=> 'text',
										'label'     	=> 'Deposit Interest Rate (% p.a.)',
										/*'required'  	=> true,*/
							));
						?>

						<div class="input text">
							<label for="TransactionTotalInterest">Total Interest</label>
							<div class="input-prepend">
								<button id="refreshTotalInterest" class="btn" type="button"> <i class="icon-refresh"></i></button>
								<?php
								echo $this->Form->input('Transaction.total_interest', array(
									'type' => 'text',
									'label'	=> false,
									'div'	=> false,
									'class'	=> 'span10',
									'id'	=> 'TransactionTotalInterest',
								));
								?>
							</div>
						</div>

						<div class="input text">
							<label for="TransactionTaxAmount">Tax amount</label>
							<div class="input-prepend">
								<button id="refreshTaxAmount" class="btn" type="button"> <i class="icon-refresh"></i></button>
								<?php
								echo $this->Form->input('Transaction.tax_amount', array(
									'type' => 'text',
									'label'	=> false,
									'div'	=> false,
									'class'	=> 'span10',
									'id'	=> 'TransactionTaxAmount',
								));
								?>
							</div>
						</div>


					</div>
					<div class="span6">
						<?php
							echo $this->Form->input(
								'date_basis', array(
										'label'     => 'Accruals Date basis',
										'options'   => array(
											"Act/360"	=>	"Act/360",
											"Act/365"	=>	"Act/365",
											"30/360"	=>	"30E/360"
										) ,
										'empty'		=> __('-- Choose one --'),
							));
						?>
						<?php
							echo $this->Form->input(
								'maturity', array(
									'name'=> 'data[Transaction][maturity_date]',
									'label'=> 'Deposit Maturity date',
									'id'	=> 'registerConfMaturityDate',
									'data-date-format'	=> 'dd/mm/yyyy',
							));
						?>
					</div>
				</div>
				<!-- <p><small>Please make sure you reload the Total interest before Register</small></p> -->
				<?php echo $this->Form->submit('Register Confirmation', array('class' => 'btn btn-primary hide')) ?>
				<?php echo $this->Form->end() ?>
			</div>

			</div>
		<?php else: ?>
			<div class="well alert-info">There are no instructed transactions.</div>';
		<?php endif; ?>
	</div>
 </div>
<div id="registerConfResult"></div>
</fieldset>

<?php
	//echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/jquery.dataTables.js');
	//echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/dataTables.bootstrap.js');
	//echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/extras/TableTools/media/js/ZeroClipboard.js');
	echo $this->Html->css('/treasury/css/redmond/jquery-ui-1.10.3.custom.css');
	echo $this->Html->script('/treasury/js/jquery-ui-1.10.3.custom.min.js');
	//echo $this->Html->script('/theme/Cakestrap/js/libs/jquery-ui.min.js');
?>

<script type="text/javascript">
	$(document).ready(function () {
		
		$('#registerconfInterestRate, #TransactionInterestRate').autoNumeric('init',{aSep: false,aDec: '.', vMin:-999999999.999});
		$('#TransactionTotalInterest, #TransactionTaxAmount').autoNumeric('init',{aSep: ',',aDec: '.', vMin:-999999999.99});
		$('#registerConfMaturityDate').datepicker({ dateFormat: "dd/mm/yy" });

		$("#TransactionDateBasis, #registerConfMaturityDate").change(function(){
			refreshTotalInterest();
			refreshTaxAmount();
		});

		$("#TransactionInterestRate").focusout(function(){
			refreshTotalInterest();
			refreshTaxAmount();
		});

		$("#refreshTotalInterest").click(function(){
			refreshTotalInterest();
			refreshTaxAmount();
		});

		$("#refreshTaxAmount").click(function(){
			refreshTaxAmount();
		});

		$("#TransactionTotalInterest").change(function(){
			if(parseFloat($('#TransactionTotalInterest').autoNumeric('get'))){
				$("#TransactionRegisterconfForm input[type=submit]").show();
			}else{
				$("#TransactionRegisterconfForm input[type=submit]").hide();
			}
		});

		$('#register form').delegate('input.tr_number_radio', 'change', function(e){
			//action !
			if($(this).parent().find(".depo_type").val() == "Callable"){
				$("#interest_at_maturity").hide();
				$("#TransactionRegisterconfForm input[type=submit]").show();
			}else{
				$("#interest_at_maturity").show();
				$("#TransactionRegisterconfForm input[type=submit]").hide();
			}
			
			$("#TransactionRegisterconfForm input[type=text]").val("");
			$(".currenc").text($("#currency").val());

			var depo_term = $(this).parent().find('.depo_term').val();

			var com_year = parseInt($(this).parent().parent().find(".com_year").val());
			var com_month = parseInt($(this).parent().parent().find(".com_mon").val()) - 1;
			var com_day = parseInt($(this).parent().parent().find(".com_day").val());
			var mat_year = parseInt($(this).parent().parent().find(".mat_year").val());
			var mat_month = parseInt($(this).parent().parent().find(".mat_mon").val()) - 1;
			var mat_day = parseInt($(this).parent().parent().find(".mat_day").val());

			var comDate = new Date(com_year, com_month, com_day);
			var matDate = new Date(mat_year, mat_month, mat_day);
			$('#registerConfMaturityDate').datepicker("option", "defaultDate", matDate);
			$('#registerConfMaturityDate').datepicker("option", "minDate", comDate);
			$("#registerConfMaturityDate").datepicker("option", "dateFormat", "dd/mm/yy");
			if(depo_term == 'NS'){
				$("#registerConfMaturityDate").datepicker("setDate", matDate);
			}
		});
	});

	function refreshTotalInterest(){
		$.getJSON('/treasury/treasuryajax/calcTotalInterest?callback=?', $("#TransactionRegisterconfForm").serialize(), function(data) {
				$("#TransactionTotalInterest").val(data);
				if($('#TransactionTotalInterest').val()){
					$("#TransactionRegisterconfForm input[type=submit]").show();
				}else{
					$("#TransactionRegisterconfForm input[type=submit]").hide();
				}
			}
		);
	}

	function refreshTaxAmount(){
		$.getJSON('/treasury/treasuryajax/computeTax?callback=?', $("#TransactionRegisterconfForm").serialize(), function(data)
		{
				$("#TransactionTaxAmount").val(data);
				/*if(parseFloat($('#TransactionTaxAmount').autoNumeric('get'))){
					$("#TransactionRegisterconfForm input[type=submit]").show();
				}else{
					$("#TransactionRegisterconfForm input[type=submit]").hide();
				}*/
		});
	}
</script>
<style>

form .radio, form .radio-checked, form a.radio-fx {
    display: block;
    height: 34px;
    width: 34px;
}
.radio {
    background: url("http://cdn4.iconfinder.com/data/icons/fatcow/32x32_0160/bullet_red.png") no-repeat scroll 0 0 rgba(0, 0, 0, 0);
}
.radio, .checkbox {
    min-height: 20px;
    padding-left: 20px;
}
.radio-checked {
    background: url("http://cdn4.iconfinder.com/data/icons/fatcow/32x32_0160/bullet_green.png") no-repeat scroll 0 0 rgba(0, 0, 0, 0);
}
</style>

