<?php
	echo $this->Html->script('/treasury/js/bootstrap-datepicker');
	echo $this->Html->css('/treasury/css/datepicker');
?>
<fieldset>
    <legend>Interest Rate Change</legend>
	<?php
		echo $this->Form->create('updateInterestRate');
		echo $this->Form->input('interest_rate.tr_numbers' , array(
					'type'		=> 'hidden',
					'label'		=> false,
					'value'		=> $transactions_ids,
				)
			);
	?>
	<table>
		<tbody>
		<tr>
			<td>Counterparty :</td>
			<td><?php echo $counterparty['Counterparty']['cpty_name']; ?></td>
		</tr>
		<tr>
			<td>Transactions :</td>
			<td><?php echo $transactions_ids; ?></td>
		</tr>
		<tr>
			<td>Value date</td>
			<td><?php 
				echo $this->Form->input('interest_rate.interest_rate_from',
					array(
						'label'		=>	'',
						'class'		=> 'span12',
						'div'		=> 'span4',
						'data-date-format'	=> 'dd/mm/yyyy',
						'required'	=> 'required',
						'default'	=> $latest_interest_rate_from,
						'style'	=>	'width: 7em;',
					)
				); 
			?></td>
		</tr>
		<tr>
			<td>New Interest Rate</td>
			<td><?php
				echo $this->Form->input('Transaction.interest_rate',
					array(
						'label'		=>	'',
						'class'		=> 'span12',
						'div'		=> 'span4',
						'required'	=> 'required',
						'style'		=>	'width: 7em;',
					)
				);
			?></td>
		</tr>

		</tbody>
	</table>
	<div class="span4">
		<?php
			echo $this->Form->submit('Save new rate',
				array(
					'id' 	=> 'createButton',
					'type' 	=> 'submit',
					'class' => 'btn btn-primary pull-right',
					'div'	=> false,//array('class' => array('input submit'))
					'style'	=>	'align-right'
				)
			);
		?>
		<?php
		echo $this->Html->link('Cancel', array('action' => 'interest_rate_change'), array('class' => 'btn btn-warning'));
		?>
	</div>
	<?php echo $this->Form->end(); ?>
</fieldset>
<script type="text/javascript">
$(document).ready(function () {
	var fromDate = $('#interest_rateInterestRateFrom').datepicker({dateFormat: 'dd/mm/yy', startDate:'<?php echo $latest_interest_rate_from;?>', setDate:'<?php echo $latest_interest_rate_from; ?>'}).on('changeDate', function(ev) {
		fromDate.hide();
	}).data('datepicker');
		
	$("#createButton").click(function(e){//no double validation
		$("#updateInterestRateInterestRateChangeUpdateForm").submit();
		document.getElementById('createButton').disabled = true;
	});
});
</script>