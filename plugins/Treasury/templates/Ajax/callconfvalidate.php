<div class="row-fluid">
	<div class="span6">
		<table style="width:400px;" class="table table-bordered  table-hover table-stripped">
			<tbody>
				<?php foreach ($transaction['Transaction'] as $key => $value): ?>
						<tr>
						<td><strong><?php echo $this->BootstrapTables->deleteUnderScore($key); if($key == 'interest_rate') echo ' (% p.a.)' ?></strong></td>
						<td><?php echo $value ?></td>
						</tr>
				<?php endforeach; ?>
				<tr>
					<td><strong>Ccy</strong></td>
					<td><?php echo $transaction['AccountA']['ccy']; ?></td>
				</tr>	
			</tbody>
		</table>
	</div>
	<div class="span6">
		<div class="row">
			<div class="span4">
	<?php echo $this->Form->create('submitconfvalide', array('url'=>'/treasury/treasuryajax/confvalidate/'.$trn)) ?>

	<?php echo $this->Form->end(array('label'=>__('Validate Confirmation'), 'class' => 'btn btn-primary')) ?>
			</div>
			<a href="#" class="btn btn-default btn-radio-cancel">Cancel</a>
			<div class="span4">
	<?php echo $this->Form->create('submitconfreject', array('url'=>'/treasury/treasuryajax/confreject/'.$trn)) ?>
	<?php echo $this->Form->end(array('label'=>__('Reject Confirmation'), 'class' => 'btn btn-primary')) ?>
			</div>
		</div>
	</div>
</div>