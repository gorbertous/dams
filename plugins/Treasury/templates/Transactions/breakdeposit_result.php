<div class="span12" style="overflow:auto;">
	<div class="alert alert-block alert-success">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<h4>Success !</h4> The withdrawal has been successfully submitted. 
	</div>
	<?php foreach($confTables as $key => $value): ?>
		<h5> <?php  echo $this->BootstrapTables->deleteUnderScore($key); ?> </h5>
		<?php $this->BootstrapTables->displayRawsById($key, $value); ?>
	<?php endforeach; ?>
	<a href="/treasury/treasurytransactions/breakdeposit" class="btn btn-primary">Ok</a>
</div>