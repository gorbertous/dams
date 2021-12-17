<?php
echo $this->Html->script('/treasury/js/jquery-ui-1.10.3.custom.min.js');
echo $this->Html->script('/treasury/js/autoNumeric.js');
echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/jquery.dataTables.js');
echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/dataTables.bootstrap.js');
echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/extras/TableTools/media/js/ZeroClipboard.js');
echo $this->Html->css('/treasury/css/redmond/jquery-ui-1.10.3.custom.css');
echo $this->Html->css('/treasury/css/dataTableSort');
// echo $this->Html->css('/treasury/css/radio-fx');
// echo $this->Html->script('/treasury/js/radio-fx');
echo $this->Html->script('/treasury/js/radio-fx-replacement');
?>
<fieldset>
	<legend>Break deposit - Confirmation</legend>
	<?php if (sizeof($transactions) > 0): ?>
	<?php echo $this->Form->create('Transaction') ?>
	<table id="selectTRNToRegister" class="table table-bordered table-striped table-hover table-condensed">
		<thead>
			<th> Select </th>
			<th> DI </th>
			<th> TRN </th>
			<th> Mandate </th>
			<th> Compartment </th>
			<th> Value Date </th>
			<th> Amount </th>
		</thead>
		<tbody>
			<?php foreach ($transactions as $tr): ?>
			<tr>
				<td>
<?php echo $this->Form->input('Transaction.tr_number', array(
		'type'		=> 'radio',
		'label'		=> false,
		'value'		=> $tr['Transaction']['tr_number'],
		'class'		=> "origin_radio",
		'id'		=> "data[Transaction][tr_number]",
	)
);
echo $this->Form->input('Transaction.original_id', array(
		'type'		=> 'hidden',
		'label'		=> false,
		'value'		=> UniformLib::uniform($tr['Transaction']['original_id'], 'original_id'),
		'id'		=> "origin_trn",
	)
);
echo $this->Form->input('Transaction.original_id', array(
		'type'		=> 'hidden',
		'label'		=> false,
		'value'		=> UniformLib::uniform($tr['Transaction']['amount'], 'amount'),
		'class'		=> "amount",
	)
);
echo $this->Form->input('Transaction.original_id', array(
		'type'		=> 'hidden',
		'label'		=> false,
		'value'		=> UniformLib::uniform($tr['AccountA']['ccy'], 'ccy'),
		'class'		=> "currency",
	)
);
?>
				</td>
				<td><?php echo UniformLib::uniform($tr['Transaction']['instr_num'], 'instr_num') ?></td>
				<td><?php echo UniformLib::uniform($tr['Transaction']['tr_number'], 'tr_number') ?></td>
				<td><?php echo UniformLib::uniform($tr['Mandate']['mandate_name'], 'mandate_name') ?></td>
				<td><?php echo UniformLib::uniform($tr['Compartment']['cmp_name'], 'cmp_name') ?></td>
				<td style="text-align:right;"><?php echo UniformLib::uniform($tr['Transaction']['commencement_date'], 'commencement_date') ?></td>
				<td style="text-align:right;">
					<?php echo UniformLib::uniform($tr['Transaction']['amount'], 'amount')." ".UniformLib::uniform($tr['AccountA']['ccy'], 'ccy'); ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<div class="well radio-form">
        <div id="ifselected"></div><hr>
        <div class="row-fluid">
        	<div class="span6">
        		<p><u>Part Withdrawal:</u></p>
        		<div class="input text">
					<label for="TransactionInterest">Interest amount:</label>
					
					
					<?php echo $this->Form->input('Transaction.with_interest', array(
							'type'		=> 'text',
							'label'		=> false,
							'value'		=> UniformLib::uniform($tr['AccountA']['ccy'], 'ccy'),
							'id'		=> "TransactionWithInterest",
							'class'		=> "span3",
						)
					);
					?>
					<span class="currency"></span>
        		</div>
        		<div class="input text">
					<label for="TransactionTax">Tax amount:</label>
					<?php echo $this->Form->input('Transaction.with_tax', array(
									'type'		=> 'text',
									'label'		=> false,
									'id'		=> "TransactionWithTax",
									'class'		=> "span3",
								)
							);
?>
					<span class="currency"></span>
        		</div>
        	</div>
        	<div class="span6">
        		<p><u>Part Retained:</u></p>
        		<div class="input text">
					<label for="TransactionInterest">Interest amount:</label>
					<?php echo $this->Form->input('Transaction.ret_interest', array(
									'type'		=> 'text',
									'label'		=> false,
									'id'		=> "TransactionRetInterest",
									'class'		=> "span3",
								)
							);
?>
					<span class="currency"></span>
        		</div>
        		<div class="input text">
					<label for="TransactionTax">Tax amount:</label>
					<?php echo $this->Form->input('Transaction.ret_tax', array(
									'type'		=> 'text',
									'label'		=> false,
									'id'		=> "TransactionRetTax",
									'class'		=> "span3",
								)
							);
?>
					<span class="currency"></span>
        		</div>
        	</div>
        </div>
        <?php echo $this->Form->submit('Register Confirmation', array(
              'class' => 'btn btn-primary',
              'div'      => false
              )) ?>
	</div>
    <?php echo $this->Form->end(); ?>
	<?php else: ?>
		<div class="well alert-info">There are no instructed withdrawals.</div>
	<?php endif ?>
</fieldset>

<?php
$this->Js->get('#TransactionBreakconfForm table input')->event('change',
	$this->Js->request(
		array(
			'controller'      => 'treasuryajax',
			'action'          => 'getoriginaltrnbreak'
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
		$('#TransactionWithInterest, #TransactionRetInterest').autoNumeric('init',{aSep: ',',aDec: '.', vMax: 9999999999999.99, vMin: -9999999999999.99});
        $('#TransactionWithTax, #TransactionRetTax').autoNumeric('init',{aSep: ',',aDec: '.', vMax: 9999999999999.99, vMin: 0});

		$('#TransactionBreakconfForm input:radio').change(function(){
        	$("#rejectButton").show();
			$('span.currency').text($(this).parent().find('.currency').val());
			$('#TransactionPrincipal').autoNumeric('set', $(this).parent().find('.amount').val());
			$('#TransactionInterest, #TransactionTax').val('');
            $('#repay_amount').text('');
		});
	});
</script>