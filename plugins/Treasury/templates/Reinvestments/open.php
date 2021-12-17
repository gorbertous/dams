<div class="span12" style="overflow:auto;">
	<div class="alert alert-block alert-success">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<strong>Success !</strong> The reinvestment group has been created and is listed below with its incoming transactions.
	</div>
	
	
	<table id="reinvestment" class="table table-bordered table-stripped table-hover nowrap small-cell">
		<thead><tr><th>Reinv group</th><th>Status</th><th>Mandate</th><th>Compartment</th><th>Counterparty ID</th><th>Availability date</th><th>Amount leftA</th><th>Amount leftB</th></tr></thead>
		<tbody>
		<tr><td><?php echo $reinvestment[0]['Reinvestment']['reinv_group']; ?></td>
		<td><?php echo $reinvestment[0]['Reinvestment']['Status']; ?></td>
		<td><?php echo $reinvestment[0]['Mandate']['mandate_name']; ?></td>
		<td><?php echo $reinvestment[0]['Compartment']['cmp_name']; ?></td>
		<td><?php echo $reinvestment[0]['Reinvestment']['cpty_ID']; ?></td>
		<td><?php echo $reinvestment[0]['Reinvestment']['availability_date']; ?></td>
		<td><?php echo $reinvestment[0]['Reinvestment']['amount_leftA']; ?></td>
		<td><?php echo $reinvestment[0]['Reinvestment']['amount_leftB']; ?></td>
		</tr>
		</tbody>
	</table>
	
	
	<?php if (!empty($transactions) && is_array($transactions)) {echo '<h5>Incoming transactions :</h5>'; $this->BootstrapTables->displayRawsById('reinvested_transactions',$transactions);} ?>
</div>
