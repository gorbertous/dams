<?php
	//echo $this->Html->css('/treasury/css/redmond/jquery-ui-1.10.3.custom.css');
	//echo $this->Html->script('/treasury/js/jquery-ui-1.10.3.custom.min.js');
	echo $this->Html->css('/treasury/css/datepicker');
	echo $this->Html->script('/treasury/js/bootstrap-datepicker');
?>
<fieldset>
	<legend>IN-OUT Booking</legend>
	<?php echo $this->Form->create("Accruals", array('id' => 'AccrualsForm')); ?>
		<?php echo $this->Form->input('Accruals.bok_t', 
					array('type'=>'hidden', 'value'=>'YY')); ?>
		<div class="input text input-prepend">
			<label for="ValueDate">Start Date</label>
			<span class="add-on"><i class="icon-calendar"></i></span>
			<?php
				echo $this->Form->input('start_date', array(
					'label'	=> false,
					'div'	=> false,
					'class'	=> 'span4',
              		'data-date-format'  => 'dd/mm/yyyy',
				));
			?>
		</div>
		<div class="input text input-prepend">
			<label for="calldepositValueDate">End Date</label>
			<span class="add-on"><i class="icon-calendar"></i></span>
			<?php
				echo $this->Form->input('end_date', array(
					'label'	=> false,
					'div'	=> false,
					'class'	=> 'span4',
              		'data-date-format'  => 'dd/mm/yyyy',
				));
			?>
		</div>
		<p>Output file will be: <strong class='transaction_id'></strong>.csv</p>
		<?php echo $this->Form->input('Accruals.transaction_id', 
					array('type'=>'text', 'class'=>'transaction_id', 'div' => false, 'label' => false, 'style' => 'display:none;')); ?>
		<?php if (!empty($save)): ?>
			<?php echo $this->Form->input('save', array('type'=>'hidden', 'value'=>1)) ?>
			<?php echo $this->Form->submit('Generate Booking entries', array('class' => 'btn btn-success')) ?>
		<?php else: ?>
			<?php echo $this->Form->input('save', array('type'=>'hidden', 'value'=>0)) ?>
			<?php echo $this->Form->submit('Check Booking entries', array('class' => 'btn btn-primary')) ?>
		<?php endif ?>
	<?php echo $this->Form->end(); ?>
	<hr>
	<div class="well">
		<h4>Last Process run:</h4>
		<?php if (isset($log['LogEntry'])): ?>
			<?php 
				//clean the log message as well
				$message = $log["LogEntry"]["message"];
				$exp = explode(' from ', $message);
				if(count($exp)==2){
					$message = $exp[0];
					$exp = explode(' to ', $exp[1]);
					if(count($exp)==2){
						$message.=' from '.UniformLib::uniform($exp[0], 'from_date');
						$message.=' to '.UniformLib::uniform($exp[1], 'to_date');
					}
				}
			?>
			<p><?php echo $message ?></p>
			<p>by <?php echo $log["LogEntry"]["user"] ?></p>
			<p>on <?php echo UniformLib::uniform($log["LogEntry"]["datetime"], 'datetime') ?></p>
		<?php else: ?>
			<p>No previous run for the report</p>
		<?php endif ?>
	</div>
	<div>
		<p><a href="https://vmd.eifaws.com/document_manager/documents/index/">Download Booking files</a></p>
	</div>
</fieldset>

<script type="text/javascript">
	$(document).ready(function() {
		$('#AccrualsStartDate, #AccrualsEndDate').datepicker({ dateFormat: "yy-mm-dd" });

		$.getJSON('/treasury/treasuryajax/getbookingfilename?', $("#AccrualsForm").serialize(), function(data) {
				$(".transaction_id").text(data);
				$(".transaction_id").val(data);
			}
		);

		$("#AccrualsStartDate, #AccrualsEndDate").change(function(event) {
			$("#AccrualsSave").val("0");
			$("input[type=submit]").removeClass('btn-success').addClass('btn-primary').attr("value", "Check Booking entries");
		});
	});
</script>