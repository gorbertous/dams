<fieldset>
	<legend>EOM Accruals</legend>
	<?php echo $this->Form->create("Accruals", array('id' => 'AccrualsForm')); ?>
		<?php echo $this->Form->input('Accruals.bok_t', 
					array('type'=>'hidden', 'value'=>'XX')); ?>
	<div class="row-span">
		<div class="span6">
			<div class="input text">
				<label for="MandateYearYear">Year:</label>
				<?php echo $this->Form->year('year', date('Y') - 5, date('Y')) ?>
			</div>
		</div>
		<div class="span6">
			<div class="input text">
				<label for="MandateMonthMonth">Month:</label>
				<?php echo $this->Form->month('month') ?>
			</div>
		</div>
	</div>
	<?php echo $this->Form->input('Accruals.transaction_id', 
					array('type'=>'text', 'class'=>'transaction_id', 'div' => false, 'label' => false, 'style' => 'display:none;')); ?>
	<p>Output file will be: <strong class='transaction_id'></strong>.csv</p>
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
			<p><?php echo $log["LogEntry"]["message"] ?></p>
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
		$.getJSON('/treasury/treasuryajax/getbookingfilename?', $("#AccrualsForm").serialize(), function(data) {
			$(".transaction_id").text(data);
			$(".transaction_id").attr('value', data);
		});

		$("#AccrualsYearYear, #AccrualsMonthMonth").change(function(event) {
			$("#AccrualsSave").val("0");
			$("input[type=submit]").removeClass('btn-success').addClass('btn-primary').attr("value", "Check Booking entries");
		});
	});
</script>