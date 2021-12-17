<?php
	echo $this->Html->script('/treasury/js/bootstrap-datepicker');
	echo $this->Html->css('/treasury/css/datepicker');
?>
<fieldset>
<legend>Create Instruction</legend>
<div id="form" class = "well span11">
	<?php echo $this->Form->create(null) ?>
		<div class="span12 noleftmargin">
			<?php
				echo $this->Form->input('Transaction.mandate_ID',
					array(
						'label'     => 'Mandate',
						'class'		=> 'span12',
						'div'		=> 'span6',
						'options'   => $mandates_list,
						'empty'     => __('-- Select a mandate --'),
						'required'	=> 'required',
						'default'	=> null
					)
				);
			?>
			<?php
				echo $this->Form->input('Transaction.cpty_id',
					array(
						'label'		=>'Counterparty',
						'class'		=> 'span12',
						'div'		=> 'span6',
						'options'	=> array(),
						'empty' 	=> __('-- Select a counterparty --'),
						'default'	=> null
					)
				);
			?>
		</div>
		<div class="span12 noleftmargin">
			<?php 
				echo $this->Form->input('Instruction.intr_date',
					array(
						'label'	=> 'Date',
						'class'		=> 'span12',
						'div'		=> 'span4',
						'data-date-format'	=> 'dd/mm/yyyy',
						//'dateFormat'	=> 'D/M/Y',
						'required'	=> 'required',
						'default'	=> date('d/m/Y')
					)
				); 
			?>
			<?php
				echo $this->Form->input('Instruction.type',
					array(
						'class'		=> 'span12',
						'div'		=> 'span4',
						/*'options'	=> array(
							'di' 	=> 'Standard',
							'call' 	=> 'Call',
							'break' => 'Break',
						),*/
						'options' 	=> $defaultInstrType,
					)
				);
			?>
			<?php if($displayFunds): ?>
			<?php
				echo $this->Form->input('Instruction.Funds',
					array(
						'label'		=>'Settlement Instructions',
						'class'		=> 'span12',
						'div'		=> 'span4',
						'options'	=> array(
							"" => "---",
							/*"D" => "Cash Funds should be automatically debited from Depositor's account",
							"TM" => "Cash Funds will be available at Treasury Manager's account",
							"CUSTOM" => "CUSTOM TEXT",*/
						),
						/*'default' 	=> 'D'*/
					)
				);
			?>
			<?php endif; ?>
		</div>	

		<div class="span12"></div>
		<div class="span12">
			<div class="span4">
				<label class="checkbox" for="InstructionNotify">
					<?php
						echo $this->Form->checkbox('Instruction.notify',
							array(
								'type'=>'checkbox',
								//'style' => 'margin-left:10px'
							)
						);
					?> Email notification for sending
				</label>				
			</div>
			<div class="span5">
				<label class="checkbox" for="previewMode">
					<?php
						echo $this->Form->checkbox('Preview',
							array(
								'id' 	=> 'previewMode',
								'name'	=> 'data[Instruction][preview]',
								'checked'=> 'checked'
							)
						);
					?> Preview mode
				</label>
			</div>
			<div class="span2">
				<?php
					echo $this->Form->submit('Create DI',
						array(
							'id' 	=> 'createButton',
							'type' 	=> 'submit',
							'class' => 'btn btn-primary pull-right',
							'div'	=> false,//array('class' => array('input submit'))
						)
					);
				?>
			</div>
		</div>
	<?php echo $this->Form->end() ?>
</div>
</fieldset>
<div id="transactions_preview" class="span11"></div>
<div style="display:none;">
<?php
echo $this->Form->create('ctpy', array('url'=>'/treasury/treasuryajax/getcptybymandate'));
echo $this->Form->input('Transaction.mandate_ID', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();

echo $this->Form->create('custom', array('url'=>'/treasury/treasuryajax/getcustomtext_bymandatecpty'));
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

echo $this->Form->create('trn', array('url'=>'/treasury/treasuryajax/get_transactions_bymandatecpty'));
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
echo $this->Form->input('Instruction.type', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();
?>
</div>
<!--  Script for amount formatting, datepicker, showing/hiding/controling maturity date based on depo_term element and commencement date -->
<script type="text/javascript">
	$(document).ready(function(){
		$("#TransactionMandateID, #TransactionCptyId").val(null);
		$('#TransactionMandateID').bind('change', function(e){
			$('#transactions_preview').html('');
			$('#InstructionFunds').html('');
		});
		$('#TransactionCptyId').bind('change', function(e){
			$('#transactions_preview').html('');
			$('#InstructionFunds').html('');
		});

		var InstrDate = $('#InstructionIntrDate').datepicker({dateFormat: 'dd/mm/yy'}).on('changeDate', function(ev) {
			InstrDate.hide();
		}).data('datepicker');

		$('#MandateCreatediForm #TransactionMandateID').change(function (e)
		{
			$('#ctpyCreatediForm #TransactionMandateID').val( $('#MandateCreatediForm #TransactionMandateID').val() );
			var data = $('#ctpyCreatediForm').serialize();
			$.ajax({
				type: "POST",
				url: '/treasury/treasuryajax/getcptybymandate',
				dataType: 'text', 
				data: data,
				async:true,
				success:function (data, textStatus) {
					$('#TransactionCptyId').html(data);
				}
			});
		});

		$('#MandateCreatediForm #TransactionCptyId, #MandateCreatediForm #InstructionType').change(function (e)
		{
			$('#customCreatediForm #TransactionMandateID').val( $('#MandateCreatediForm #TransactionMandateID').val() );
			$('#customCreatediForm #TransactionCptyId').val( $('#MandateCreatediForm #TransactionCptyId').val() );
			var data = $('#customCreatediForm').serialize();
			$.ajax({
				type: "POST",
				url: '/treasury/treasuryajax/getcustomtext_bymandatecpty',
				dataType: 'text', 
				data: data,
				async:true,
				success:function (data, textStatus) {
					$('#InstructionFunds').html(data);
				}
			});
		});

		$('#MandateCreatediForm #TransactionCptyId').change(function (e)
		{
			$('#trnCreatediForm #TransactionMandateID').val( $('#MandateCreatediForm #TransactionMandateID').val() );
			$('#trnCreatediForm #TransactionCptyId').val( $('#MandateCreatediForm #TransactionCptyId').val() );
			$('#trnCreatediForm #InstructionType').val( $('#MandateCreatediForm #InstructionType').val() );
			var data = $('#trnCreatediForm').serialize();
			$.ajax({
				type: "POST",
				url: '/treasury/treasuryajax/get_transactions_bymandatecpty',
				dataType: 'text', 
				data: data,
				async:true,
				success:function (data, textStatus) {
					$('#transactions_preview').html(data);
				}
			});
		});
	});
</script>