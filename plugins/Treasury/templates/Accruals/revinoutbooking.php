<?php
	echo $this->Html->css('/treasury/css/redmond/jquery-ui-1.10.3.custom.css');
	echo $this->Html->script('/treasury/js/jquery-ui-1.10.3.custom.min.js');
?>
<fieldset>
	<legend>REV-IN-OUT BOOKING</legend>
	<?php echo $this->Form->create("Accruals", array('id' => 'AccrualsForm')); ?>
	<?php echo $this->Form->input('Accruals.bok_t', 
					array('type'=>'hidden', 'value'=>'ZY')); ?>
		<?php
			echo $this->Form->input('trn', array(
				'label'	=> 'TRN',
				'class'	=> 'span2',
			));
		?>
		<div class="input text input-prepend">
			<label for="ValueDate">Start Date</label>
			<span class="add-on"><i class="icon-calendar"></i></span>
			<?php
				echo $this->Form->input('start_date', array(
					'label'	=> false,
					'div'	=> false,
					'class'	=> 'span4',
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
				));
			?>
		</div>
		<p>Output file will be: <strong class='transaction_id'></strong>.csv</p>
		<?php echo $this->Form->input('Accruals.transaction_id', 
					array('type'=>'hidden', 'class'=>'transaction_id')); ?>
		<?php echo $this->Form->submit('Generate Booking entries', array('class' => 'btn btn-primary')) ?>
	<?php echo $this->Form->end(); ?>
	<hr>
	<div class="well">
		<h4>Last Process run:</h4>
		<?php if (isset($log['LogEntry'])): ?>
			<p><?php echo $log["LogEntry"]["message"] ?></p>
			<p>by <?php echo $log["LogEntry"]["user"] ?></p>
			<p>on <?php echo $log["LogEntry"]["datetime"] ?></p>
		<?php else: ?>
			<p>No previous run for the report</p>
		<?php endif ?>
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
	});
</script>