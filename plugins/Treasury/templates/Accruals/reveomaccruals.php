<fieldset>
	<legend>REV-EOM ACCRUALS</legend>
	<?php echo $this->Form->create("Accruals", array('id' => 'AccrualsForm')); ?>
		<?php echo $this->Form->input('Accruals.bok_t', 
					array('type'=>'hidden', 'value'=>'ZX')); ?>
		<div style="margin-left: 25px">
			<?php
				echo $this->Form->input('trn', array(
					'label'	=> 'TRN',
					'class'	=> 'span2',
				));
			?>
		</div>
		<div class="row-span">
		<div class="span6">
			<div class="input text">
				<label for="AccrualsYearYear">Year:</label>
				<?php echo $this->Form->year('year', date('Y') - 5, date('Y'), array('class' => 'span2')) ?>
			</div>
		</div>
		<div class="span6">
			<div class="input text">
				<label for="AccrualsMonthMonth">Month:</label>
				<?php echo $this->Form->month('month', array('class' => 'span3')) ?>
			</div>
		</div>
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
		$.getJSON('/treasury/treasuryajax/getbookingfilename?', $("#AccrualsForm").serialize(), function(data) {
				$(".transaction_id").text(data);
				$(".transaction_id").attr('value', data);
			}
		);
	});
</script>