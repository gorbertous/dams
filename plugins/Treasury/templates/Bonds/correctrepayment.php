<div class="row-fluid span12" style="overflow:auto;">
	<?php if(isset($repayment)): ?>
		<h5> Correction of following repayment : </h5>
		<?php $this->BootstrapTables->displayRawsById('correctrepayment',$repayment,false); ?>
	<?php endif; ?>
	<br><br>
	<?php echo $this->Form->create('correctrepayment'); ?>
	
	<div class="span6 well">
		
		<?php echo $this->Form->input('Transaction.amount',
				array(
		        	'type' 			=> 'text',
		            'label'     	=> 'Repayment Amount',
		         	'default'		=> $defaultAmount
		            /*'required'  	=> true,*/
            	)
    		);
		?>
		
		<?php echo $this->Form->input('Transaction.accountA_IBAN',
 					array(
						'label'     => 'Repayment Account',
						'options'   => $accountA_IBAN,
						'default'	=> $defaultAccount,
						'empty' 	=> __('-- Select an account --'),
					)
				);
	 	?>

		<br>
		<?php echo $this->Form->end(array('label'=>__('Correct Repayment'), 'class' => 'btn btn-primary')) ; ?>
		<br>
	</div>
</div>
<?php
	
	echo $this->Html->script('/treasury/js/autoNumeric.js');
?>
<script type="text/javascript">
	$(document).ready(function(){
		$('#TransactionAmount').autoNumeric('init',{aSep: ',',aDec: '.'});
	});
</script>
