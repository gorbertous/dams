<div class="span12" style="overflow:auto;">
	<div class="alert alert-block alert-info">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<strong>Success !</strong> 
		<!-- if $confTables contains a single element (parent transaction), that means there is no capitalisation -->
		<?php if(sizeof($confTables) > 1) : ?>
			Interest has been successfully both fixed and capitalised.
		<?php else: ?>
			Parent transaction has been updated to fix end of month interest on the entered date.
		<?php endif; ?>
	</div>
	<h5> Parent transaction </h5>
	<table id="parent_transaction" class="table table-bordered table-stripped table-hover nowrap small-cell">
	<thead>
		<tr>
			<th>TRN</th>
			<th>Original TRN</th>
			<th>Parent TRN</th>
			<th>State</th>
			<th>Type</th>
			<th>Commencement</th>
			<th>Maturity</th>
			<th>Total interest</th>
			<th>Date basis</th>
			<th>EOM interest</th>
			<th>EOM tax</th>
			<th>Fixing date</th>
			<th>Principal account</th>
			<th>Interest account</th>
			<th>Source fund</th>
			<th>Eom tax</th>
			<th>Term or Callable</th>
			<th>Amount</th>
			<th>Ccy</th>
			<th>Days</th>
			<th>Reinv availability date</th>
			<th>Mandate</th>
			<th>Compartment</th>
			<th>Counterparty</th>
			<th>FromReinv</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="tr_number"><?php echo $parent_transaction['Transaction']['tr_number']; ?></td>
			<td class="original_TRN"><?php echo $parent_transaction['Transaction']['original_TRN']; ?></td>
			<td class="parent_TRN"><?php echo $parent_transaction['Transaction']['parent_TRN']; ?></td>
			<td class="State"><?php echo $parent_transaction['Transaction']['State']; ?></td>
			<td class="Type"><?php echo $parent_transaction['Transaction']['Type']; ?></td>
			<td class="Commencement" ><?php echo $parent_transaction['Transaction']['Commencement']; ?></td>
			<td class="Maturity"><?php echo $parent_transaction['Transaction']['Maturity']; ?></td>
			<td class="total_interest"><?php echo $parent_transaction['Transaction']['total_interest']; ?></td>
			<td class="date_basis"><?php echo $parent_transaction['Transaction']['date_basis']; ?></td>
			<td class="EOM_interest"><?php echo $parent_transaction['Transaction']['EOM_interest']; ?></td>
			<td class="EOM_tax"><?php echo $parent_transaction['Transaction']['eom_tax']; ?></td>
			<td class="Fixing_date"><?php echo $parent_transaction['Transaction']['Fixing_date']; ?></td>
			<td class="principal_account"><?php echo $parent_transaction['Transaction']['principal_account']; ?></td>
			<td class="interest_account"><?php echo $parent_transaction['Transaction']['interest_account']; ?></td>
			<td class="source_fund"><?php echo $parent_transaction['Transaction']['source_fund']; ?></td>
			<td class="Term_or_Callable"><?php echo $parent_transaction['Transaction']['Term_or_Callable']; ?></td>
			<td class="Amount"><?php echo $parent_transaction['Transaction']['Amount']; ?></td>
			<td class="ccy"><?php echo $parent_transaction['Transaction']['ccy']; ?></td>
			<td class="days"><?php echo $parent_transaction['Transaction']['days']; ?></td>
			<td class="reinv_availability_date"><?php echo $parent_transaction['Transaction']['reinv_availability_date']; ?></td>
			<td class="Mandate"><?php echo $parent_transaction['Mandate']['Mandate']; ?></td>
			<td class="Compartment"><?php echo $parent_transaction['Compartment']['Compartment']; ?> </td>
			<td class="Counterparty"><?php echo $parent_transaction['Counterparty']['Counterparty']; ?> </td>
			<td class="fromReinv"><?php echo $parent_transaction['outFromReinv']['fromReinv']; ?> </td>
		</tr>
	</tbody>
</table>
	<?php
	foreach($confTables as $key => $value): ?>
		<h5> <?php  echo $this->BootstrapTables->deleteUnderScore($key); ?> </h5>
		<?php $this->BootstrapTables->displayRawsById($key, $value); ?>
	<?php endforeach; ?>
	<a href="/treasury/treasurytransactions/interest_fixing" class="btn btn-primary">Ok</a>
</div>