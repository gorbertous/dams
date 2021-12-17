<?php 
  echo $this->Html->css('/treasury/css/custom-theme/jquery-ui-1.10.0.custom');
  echo $this->Html->css('/treasury/js/jquery-ui-1.10.3.custom.min');
  echo $this->Html->script('/treasury/js/jquery-ui.min.js');
  echo $this->Html->css('/treasury/css/dataTableSort');
?>
<?php 
  echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/jquery.dataTables.js');
  echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/dataTables.bootstrap.js');
  //echo $this->Html->script('/treasury/js/ColumnFilter/media/js/jquery-ui.js'); 
  echo $this->Html->script('/treasury/js/ColumnFilter/media/js/jquery.dataTables.columnFilter.js');
?>
<style type="text/css">
	input[type = text] {
		width: 80px;
    }      
</style>
<script>
  $(function() {
    /*$( "#alertBatch" ).dialog({
    	resizable: true,
      	height:600,
      	width:1200,
      	modal: true,
      	buttons: {
        	Close: function() {
          		$( this ).dialog( "close" );
          		$(location).attr('href','/treasury');
        	}
      	}
    });*/
  });
</script>
<div id="alertBatch">
	<div style="overflow: auto;">
<?php if(empty($launch)): ?>
	<fieldset>
		<legend>Maturity Alert Batch</legend>
		<a href="<?php print Router::url('alertbatch/1') ?>" class="btn btn-big btn-danger">Run Maturity Alert Batch</a>
	</fieldset>
<?php else: ?>
		<fieldset>
			<legend>Transaction Maturity Alert</legend>
			<?php if (sizeof($processed)>0): ?>
				<table id ="maturity" class="table table-bordered table-condenced table-stripped">
					<thead>
						<tr>
							<th>Days</th>
							<th>TRN</th>
							<th>Type</th>
							<th>Status</th>
							<th>Amount</th>
							<th>Commencement Date</th>
							<th>Maturity Date</th>
							<th>Period</th>
							<th>Term/Call</th>
							<th>Renewal</th>
							<th>Rate type</th>
							<th>Mandate name</th>
							<th>Compartment name</th>
							<th>Principal Account</th>
							<th>Interest Account</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($transactions as $tr): ?>
						<tr>
							<td><?php echo UniformLib::uniform($tr['Transaction']['days'], 'days') ?></td>
							<td><?php echo UniformLib::uniform($tr['Transaction']['tr_number'], 'tr_number') ?></td>
							<td><?php echo UniformLib::uniform($tr['Transaction']['tr_type'], 'tr_type') ?></td>
							<td><?php echo UniformLib::uniform($tr['Transaction']['tr_state'], 'tr_state') ?></td>
							<td><?php echo UniformLib::uniform($tr['Transaction']['amount'], 'amount').' '.UniformLib::uniform($tr['AccountA']['ccy'], 'ccy'); ?></td>
							<td><?php echo UniformLib::uniform($tr['Transaction']['commencement_date'], 'commencement_date') ?></td>
							<td><?php echo UniformLib::uniform($tr['Transaction']['maturity_date'], 'maturity_date') ?></td>
							<td><?php echo UniformLib::uniform($tr['Transaction']['depo_term'], 'depo_term') ?></td>
							<td><?php echo UniformLib::uniform($tr['Transaction']['depo_type'], 'depo_type') ?></td>
							<td><?php echo UniformLib::uniform($tr['Transaction']['depo_renew'], 'depo_renew') ?></td>
							<td><?php echo UniformLib::uniform($tr['Transaction']['rate_type'], 'rate_type') ?></td>
							<td><?php echo UniformLib::uniform($tr['Mandate']['mandate_name'], 'mandate_name') ?></td>
							<td><?php echo UniformLib::uniform($tr['Compartment']['cmp_name'], 'cmp_name') ?></td>
							<td><?php echo UniformLib::uniform($tr['Transaction']['accountA_IBAN'], 'accountA_IBAN') ?></td>
							<td><?php echo UniformLib::uniform($tr['Transaction']['accountB_IBAN'], 'accountB_IBAN') ?></td>
						</tr>
					<?php endforeach ?>
					</tbody>
				</table>
			<?php else: ?>
				<p class="well"> No transactions to process. </p>
			<?php endif ?>
		</fieldset>
	</div>
	<div style="overflow: auto;">
		<fieldset>
			<legend>All notifications</legend>
			<?php if (sizeof($notifications)>0): ?>
				<table id ="maturity" class="table table-bordered table-condenced table-stripped">
					<thead>
						<tr>
							<th>Days</th>
							<th>TRN</th>
							<th>Type</th>
							<th>Status</th>
							<th>Amount</th>
							<th>Commencement Date</th>
							<th>Maturity Date</th>
							<th>Period</th>
							<th>Term/Call</th>
							<th>Renewal</th>
							<th>Rate type</th>
							<th>Mandate name</th>
							<th>Compartment name</th>
							<th>Principal Account</th>
							<th>Interest Account</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($notifications as $tr): ?>
						<tr>
							<td><?php echo UniformLib::uniform($tr['Transaction']['days'], 'days') ?></td>
							<td><?php echo UniformLib::uniform($tr['Transaction']['tr_number'], 'tr_number') ?></td>
							<td><?php echo UniformLib::uniform($tr['Transaction']['tr_type'], 'tr_type') ?></td>
							<td><?php echo UniformLib::uniform($tr['Transaction']['tr_state'], 'tr_state') ?></td>
							<td><?php echo UniformLib::uniform($tr['Transaction']['amount'], 'amount').' '.UniformLib::uniform($tr['AccountA']['ccy'], 'ccy'); ?></td>
							<td><?php echo UniformLib::uniform($tr['Transaction']['commencement_date'], 'commencement_date') ?></td>
							<td><?php echo UniformLib::uniform($tr['Transaction']['maturity_date'], 'maturity_date') ?></td>
							<td><?php echo UniformLib::uniform($tr['Transaction']['depo_term'], 'depo_term') ?></td>
							<td><?php echo UniformLib::uniform($tr['Transaction']['depo_type'], 'depo_type') ?></td>
							<td><?php echo UniformLib::uniform($tr['Transaction']['depo_renew'], 'depo_renew') ?></td>
							<td><?php echo UniformLib::uniform($tr['Transaction']['rate_type'], 'rate_type') ?></td>
							<td><?php echo UniformLib::uniform($tr['Mandate']['mandate_name'], 'mandate_name') ?></td>
							<td><?php echo UniformLib::uniform($tr['Compartment']['cmp_name'], 'cmp_name') ?></td>
							<td><?php echo UniformLib::uniform($tr['Transaction']['accountA_IBAN'], 'accountA_IBAN') ?></td>
							<td><?php echo UniformLib::uniform($tr['Transaction']['accountB_IBAN'], 'accountB_IBAN') ?></td>
						</tr>
					<?php endforeach ?>
					</tbody>
				</table>
			<?php else: ?>
				<p class="well"> No transactions processed in the previous run. </p>
			<?php endif ?>
		</fieldset>
<?php endif ?>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	// Grid SAS _webout table1 (Deposits and Rollovers)
        $('#maturity').dataTable().columnFilter();
});
</script>