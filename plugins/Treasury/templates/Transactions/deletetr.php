<div class = "span12 row-fluid">
	<?php echo $this->Form->create('transaction');?>
	<div style="overflow: auto;">
		<?php if(isset($transaction)):?>
		<div class="alert alert-danger">
			<?php if(empty($transaction[0]['Transaction']['parent_TRN'])) 
				print 'Are you sure you want to delete the transaction below ? If yes then please leave a comment before deletion.'; 
			else print 'The deposit below is linked to a maturing transaction. Are you sure you want to delete this deposit?' ?>
		</div>
		<?php $this->BootstrapTables->displayRawsById('transaction',$transaction,false); ?>
		<?php endif; ?>
		<?php 
			echo $this->Form->input(
				'Transaction.comment', array(
				'type' => 'textarea',
				'label'	=> 'Comment',
				'class'		=> 'span12',
			));
		?>

	</div>
	<div class="">
		<?php echo $this->Form->end(array('label'=> 'Delete transaction', 'div'=>false, 'class' => 'btn btn-primary')) ?>
	</div>
</div>