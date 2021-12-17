<?php
	//echo $this->Html->script('/treasury/js/bootstrap-datepicker');
	echo $this->Html->script('/treasury/js/autoNumeric.js');
	// echo $this->Html->css('/treasury/css/dataTableSort');
	// echo $this->Html->css('/treasury/css/radio-fx');
    // echo $this->Html->script('/treasury/js/radio-fx');
    echo $this->Html->script('/treasury/js/radio-fx-replacement');
?>
<fieldset id="register" class="">
	<legend>Call Confirmation - Validation</legend>
   	<?php echo $this->Form->create('Transaction') ?>
	<div id="registerConfSelect" class="">
		<?php if(sizeof($transactions) > 0): ?>
			<table id="selectTRNToRegister" class="table table-bordered table-striped table-hover table-condensed">
   				<thead>
   					<th> Select </th>
					<th> DI </th>
					<th> TRN </th>
					<th> Mandate </th>
	   				<th> Counterparty </th>
	   				<th> Value Date </th>
					<th> Amount </th>
					<th> Interest </th>
					<th> Taxes </th>
   				</thead>
				<tbody>
				<?php foreach ($transactions as $tr): ?>
					<tr>
						<td>
							<?php
						
							echo $this->Form->input(
								'Transaction.tr_number', array(
									'type'		=> "radio",
									'label'     => false,
									'value'		=> $tr['Transaction']['tr_number'],
									'class'		=> 'origin_radio',
									'id'		=> "data[Transaction][tr_number]",
									
							));
							echo $this->Form->input(
								'Transaction.original_id', array(
									'type'		=> "hidden",
									'label'     => false,
									'value'		=> $tr['Transaction']['original_id'],
									'class'		=> 'origin_trn',
									
							));
							echo $this->Form->input(
								'Transaction.parent_id', array(
									'type'		=> "hidden",
									'label'     => false,
									'value'		=> $tr['Transaction']['amount'],
									'class'		=> 'amount',
									
							));
							echo $this->Form->input(
								'', array(
									'type'		=> "hidden",
									'label'     => false,
									'value'		=> $tr['Transaction']['total_interest'],
									'class'		=> 'interest',
									
							));
							echo $this->Form->input(
								'', array(
									'type'		=> "hidden",
									'label'     => false,
									'value'		=> $tr['Transaction']['tax_amount'],
									'class'		=> 'tax',
									
							));
							?>
						</td>
						<td><?php echo UniformLib::uniform($tr['Transaction']['instr_num'], 'instr_num') ?></td>
						<td><?php echo UniformLib::uniform($tr['Transaction']['tr_number'], 'tr_number') ?></td>
						<td><?php echo UniformLib::uniform($tr['Mandate']['mandate_name'], 'mandate_name') ?></td>
						<td><?php echo UniformLib::uniform($tr['Counterparty']['cpty_name'], 'cpty_name') ?></td>
						<td style="text-align:right;"><?php echo UniformLib::uniform($tr['Transaction']['commencement_date'], 'commencement_date') ?></td>
						<td style="text-align:right;">
							<?php echo UniformLib::uniform($tr['Transaction']['amount'], 'amount')." ".UniformLib::uniform($tr['AccountA']['ccy'], 'ccy'); ?>
						</td>
						<td style="text-align:right;">
							<?php echo UniformLib::uniform($tr['Transaction']['total_interest'], 'total_interest')." ".UniformLib::uniform($tr['AccountA']['ccy'], 'ccy'); ?>
						</td>
						<td style="text-align:right;">
							<?php echo UniformLib::uniform($tr['Transaction']['tax_amount'], 'tax_amount')." ".UniformLib::uniform($tr['AccountA']['ccy'], 'ccy'); ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
			<br><br>
			<div class="well radio-form">
				<div id="ifselected"></div><hr>
				<div class="input text">
					<label for="TransactionPrincipal">Principal amount:</label>
					<?php
					echo $this->Form->input(
						'Transaction.principal', array(
							'type'		=> "text",
							'label'     => false,
							'value'		=> $tr['Transaction']['original_id'],
							'class'		=> 'span3',
							'id'		=> 'TransactionPrincipal',
							'disabled'	=> true,
					));
					?>
					<span class="currency"></span>
				</div>

				<div class="input text">
					<label for="TransactionInterest">Interest amount:</label>
					<?php
					echo $this->Form->input(
						'Transaction.interest', array(
							'type'		=> "text",
							'label'     => false,
							'class'		=> 'span3',
							'id'		=> 'TransactionInterest',
							'disabled'	=> true,
					));
					?>
					<span class="currency"></span>
				</div>

				<div class="input text">
					<label for="TransactionTax">Tax amount:</label>
					<?php
					echo $this->Form->input(
						'Transaction.tax', array(
							'type'		=> "text",
							'label'     => false,
							'class'		=> 'span3',
							'id'		=> 'TransactionTax',
							'disabled'	=> true,
					));
					?>
					<span class="currency"></span>
				</div>
				<p>Repayment amount: <span id="repay_amount"></span> <span class="currency"></span></p>
				<div class="row-fluid">
					<div class="span3">
						<?php
					echo $this->Form->input(
						'Transaction.reject', array(
							'type'		=> "hidden",
							'label'     => false,
							'value'		=> '0',
							'id'		=> 'rejectInput',
					));
					?>
						<?php echo $this->Form->button('Reject Confirmation', array(
							'class' => 'btn btn-danger hide',
							'id'	=> 'rejectButton',
							'div'	=> false
						)) ?>
					</div>
					<div class="span4">
						<?php echo $this->Form->submit('Validate Confirmation', array(
							'class' => 'btn btn-primary hide',
							'id'	=> 'validateButton',
							'div'	=> false
						)) ?>
					</div>
				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		<?php else: ?>
			<div class="well alert-info">There are no instructed calls.</div>
		<?php endif; ?>
	</div>
 </fieldset>
<div id="registerConfResult"></div>

<?php
	echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/jquery.dataTables.js');
	echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/dataTables.bootstrap.js');
	echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/extras/TableTools/media/js/ZeroClipboard.js');
	echo $this->Html->css('/treasury/css/redmond/jquery-ui-1.10.3.custom.css');
	echo $this->Html->script('/treasury/js/jquery-ui-1.10.3.custom.min.js');
	//echo $this->Html->script('/theme/Cakestrap/js/libs/jquery-ui.min.js');
?>

<?php
	$this->Js->get('#TransactionValidatecallconfForm table input')->event('change',
		$this->Js->request(
			array(
				'controller'	=>	'treasuryajax',
				'action'		=>	'getoriginaltrncall'
				),
			array(
				'update'		=>	'#ifselected',
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

<script type="text/javascript">
	$(document).ready(function() {
		$('#TransactionPrincipal, #TransactionInterest, #TransactionTax, #repay_amount').autoNumeric('init',{aSep: ',',aDec: '.', vMin: '-99999999999'});

		$("#rejectButton").click(function(e){
			$("#rejectInput").val(1);
			$("#TransactionValidatecallconfForm").submit();
		});
		$('#TransactionValidatecallconfForm input:radio').click(function(){
			$("#rejectButton, #validateButton").show();
			$('span.currency').text($(this).parent().find('.currency').val());
			$('#TransactionPrincipal').autoNumeric('set', $(this).parent().find('.amount').val());
			$('#TransactionInterest').autoNumeric('set', $(this).parent().find('.interest').val());
			$('#TransactionTax').autoNumeric('set', $(this).parent().find('.tax').val());

			var repay_amount = (parseFloat($('#TransactionPrincipal').autoNumeric('get')) + parseFloat($('#TransactionInterest').autoNumeric('get'))) - parseFloat($('#TransactionTax').autoNumeric('get'));
			$("#repay_amount").autoNumeric('set', repay_amount);
		});
		$('body').bind('refreshSubcontent', function(e){
			$('#ifselected').html('');
		});
	});

</script>