<fieldset>
	<legend>Edit Upload Result</legend>
<?php if(!empty($valid)): ?>
	<div class="alert alert-success">
		<button type="button" class="close" data-dismiss="alert">×</button>
		<h4>Success!</h4>
		<p>The data has been updated</p>
	</div>
<?php else: ?>
	<div class="alert alert-danger">
		<button type="button" class="close" data-dismiss="alert">×</button>
		<h4>Error</h4>
		<p>An error occurred. If the problem persists, please contact EIF SAS Support.</p>
	</div>
<?php endif ?>
	<?php echo $this->Html->link('Back to edit', '/damsv2/import', array('class' => 'btn btn-danger')) ?>
</fieldset>