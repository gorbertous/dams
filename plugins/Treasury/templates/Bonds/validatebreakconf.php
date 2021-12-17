<?php
	//echo $this->Html->script('/treasury/js/bootstrap-datepicker');
	echo $this->Html->script('/treasury/js/autoNumeric.js');
	echo $this->Html->css('/treasury/css/dataTableSort');

	// echo $this->Html->css('/treasury/css/radio-fx');
    // echo $this->Html->script('/treasury/js/radio-fx');
    echo $this->Html->script('/treasury/js/radio-fx-replacement');
?>
<fieldset id="register" class="">
	<legend>Break Deposit - Confirmation - Validatation</legend>
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
									'value'		=> $tr['Transaction']['parent_id'],
									'class'		=> 'parent_trn',
									
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
							echo $this->Form->input(
								'', array(
									'type'		=> "hidden",
									'label'     => false,
									'value'		=> $tr['AccountA']['ccy'],
									'class'		=> 'currency',
									
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
			<div class="radio-form">
		        <div id="ifselected"></div>
		        <?php 
		        	echo $this->Form->submit('Validate', array(
		            	'class'	=> 'btn btn-primary hide',
		            	'div'	=> false,
		            	'name'	=> 'validate'
		            ))
		        ?>
		        <?php 
		        	echo $this->Form->submit('Reject', array(
		            	'class'	=> 'btn btn-primary hide',
		            	'div'	=> false,
		            	'name'	=> 'reject'
		            ))
		        ?>
			</div>
			<?php echo $this->Form->end(); ?>
		<?php else: ?>
			<div class="well alert-info">There are no withdrawals with status Confirmation Received.</div>
		<?php endif; ?>
	</div>
 </fieldset>



<?php
	echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/jquery.dataTables.js');
	echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/dataTables.bootstrap.js');
	echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/extras/TableTools/media/js/ZeroClipboard.js');
	echo $this->Html->css('/treasury/css/redmond/jquery-ui-1.10.3.custom.css');
	echo $this->Html->script('/treasury/js/jquery-ui-1.10.3.custom.min.js');
	//echo $this->Html->script('/theme/Cakestrap/js/libs/jquery-ui.min.js');
?>

<?php
$this->Js->get('#selectTRNToRegister input:radio')->event('click', 
	$this->Js->request(
		array(
			'controller'      => 'treasuryajax',
			'action'          => 'getoriginaltrnbreakvalid'
		),
		array(
			'update'          => '#ifselected',
			'async'           => true,
			'method'          => 'post',
			'dataExpression'  => true,
			'data'=> $this->Js->serializeForm(
				array(
					'isForm'  => true,
					'inline'  => true
				)
			)
		)
    )
);
?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#TransactionWithInterest, #TransactionWithTax, #TransactionRetInterest, #TransactionRetTax').autoNumeric('init',{aSep: ',',aDec: '.'});

		$('#TransactionValidatebreakconfForm input:radio').click(function(){
        		$("#TransactionValidatebreakconfForm input:submit").show();
		});
		$('body').bind('refreshSubcontent', function(e){
			$('#ifselected').html('');
		});
	});
</script>