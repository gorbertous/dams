<div class="span12" style="overflow:auto;">
	<h5> Reinvestment to be opened : </h5>
	<?php $this->BootstrapTables->displayRawsById('reopenableReinv',$reopenableReinv); ?> 
	<h5> Incoming Transactions : </h5>
	<?php $this->BootstrapTables->displayRawsById('inTransactions',$inTransactions); ?>
	<h5> Outgoing Transactions : </h5>
	<?php $this->BootstrapTables->displayRawsById('outTransactions',$outTransactions); ?>
	<br><br>
	<?php echo $this->Form->create('reinvestment');?>
	<?php echo $this->Form->end(array('label'=> 'Reopen above reinvestment', 'class' => 'btn btn-primary')) ?>
</div>