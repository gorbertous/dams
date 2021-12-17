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
	<?php
	if (!empty($confTables['parent_transaction']))
	{
	?>
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
			<?php if ($no_capitalisation == '0'){ echo "<th>Maturity</th>"; } ?>
			<th>Interest rate</th>
			<?php if ($no_capitalisation == '0'){ echo "<th>Total interest</th>"; } ?>
			<th>Date basis</th>
			<th>EOM interest</th>
			<th>EOM tax</th>
			<th>Fixing date</th>
			<th>Principal account</th>
			<th>Interest account</th>
			<th>Source fund</th>
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
			<td class="tr_number"><?php echo $confTables['parent_transaction']['Transaction']['tr_number']; ?></td>
			<td class="original_TRN"><?php echo $confTables['parent_transaction']['Transaction']['original_id']; ?></td>
			<td class="parent_TRN"><?php echo $confTables['parent_transaction']['Transaction']['parent_id']; ?></td>
			<td class="State"><?php echo $confTables['parent_transaction']['Transaction']['tr_state']; ?></td>
			<td class="Type"><?php echo $confTables['parent_transaction']['Transaction']['tr_type']; ?></td>
			<td class="Commencement" ><?php echo $confTables['parent_transaction']['Transaction']['commencement_date']; ?></td>
			<?php if ($no_capitalisation == '0'){ echo '<td class="Maturity">'.$confTables['parent_transaction']['Transaction']['maturity_date'].'</td>'; } ?>
			<td class="interest_rate" ><?php echo $confTables['parent_transaction']['Transaction']['interest_rate']; ?></td>
			<?php if ($no_capitalisation == '0'){ echo '<td class="total_interest">'.$confTables['parent_transaction']['Transaction']['total_interest'].'</td>'; } ?>
			<td class="date_basis"><?php echo $confTables['parent_transaction']['Transaction']['date_basis']; ?></td>
			<td class="EOM_interest"><?php echo $confTables['parent_transaction']['Transaction']['eom_interest']; ?></td>
			<td class="EOM_tax"> <?php echo $confTables['parent_transaction']['Transaction']['eom_tax']; ?></td>
			<td class="Fixing_date"> <?php echo $confTables['parent_transaction']['Transaction']['fixing_date']; ?></td>
			<td class="principal_account"><?php echo $confTables['parent_transaction']['Transaction']['accountA_IBAN']; ?></td>
			<td class="interest_account"><?php echo $confTables['parent_transaction']['Transaction']['accountB_IBAN']; ?></td>
			<td class="source_fund"><?php echo $confTables['parent_transaction']['Transaction']['source_fund']; ?></td>
			<td class="Term_or_Callable"><?php echo $confTables['parent_transaction']['Transaction']['depo_type']; ?></td>
			<td class="Amount"><?php echo $confTables['parent_transaction']['Transaction']['amount']; ?></td>
			<td class="ccy"><?php echo $confTables['parent_transaction']['Transaction']['ccy']; ?></td>
			<td class="days"><?php echo $confTables['parent_transaction']['Transaction']['days']; ?></td>
			<td class="reinv_availability_date"><?php echo $confTables['parent_transaction']['Transaction']['reinv_availability_date']; ?></td>
			<td class="Mandate"><?php echo $confTables['parent_transaction']['Mandate']['mandate_name']; ?></td>
			<td class="Compartment"><?php echo $confTables['parent_transaction']['Compartment']['cmp_name']; ?> </td>
			<td class="Counterparty"><?php echo $confTables['parent_transaction']['Counterparty']['cpty_name']; ?> </td>
			<td class="fromReinv"><?php echo $confTables['parent_transaction']['outFromReinv']['reinv_group']; ?> </td>
		</tr>
	</tbody>
</table>
	<?php
	}
	
		
	if (!empty($confTables['rollover_of_principal']))
	{
		?>
		
	<h5> Rollover of principal </h5>
	<table id="rollover_of_principal" class="table table-bordered table-stripped table-hover nowrap small-cell">
	<thead>
		<tr>
			<th>TRN</th>
			<th>State</th>
			<th>Type</th>
			<th>Principal account</th>
			<th>Interest account</th>
			<th>Term or Callable</th>
			<th>Commencement date</th>
			<th>Amount</th>
			<th>Ccy</th>
			<th>Days</th>
			<th>Reinv availability date</th>
			<th>Mandate</th>
			<th>Compartment</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="tr_number"><?php echo $confTables ['rollover_of_principal']['Transaction']['tr_number']; ?></td>
			<td class="State"><?php echo $confTables ['rollover_of_principal']['Transaction']['tr_state']; ?></td>
			<td class="Type"><?php echo $confTables ['rollover_of_principal']['Transaction']['tr_type']; ?></td>
			<td class="principal_account"><?php echo $confTables ['rollover_of_principal']['Transaction']['accountA_IBAN']; ?></td>
			<td class="interest_account"><?php echo $confTables ['rollover_of_principal']['Transaction']['accountB_IBAN']; ?></td>
			<td class="Term_or_Callable"><?php echo $confTables ['rollover_of_principal']['Transaction']['depo_type']; ?></td>
			<td class="Commencement" ><?php echo $confTables ['rollover_of_principal']['Transaction']['commencement_date']; ?></td>
			<td class="Amount"><?php echo $confTables ['rollover_of_principal']['Transaction']['amount']; ?></td>
			<td class="ccy"><?php echo $confTables ['rollover_of_principal']['Transaction']['ccy']; ?></td>
			<td class="days"><?php echo $confTables ['rollover_of_principal']['Transaction']['days']; ?></td>
			<td class="reinv_availability_date"><?php echo $confTables ['rollover_of_principal']['Transaction']['reinv_availability_date']; ?></td>
			<td class="Mandate"><?php echo $confTables ['rollover_of_principal']['Mandate']['mandate_name']; ?></td>
			<td class="Compartment"><?php echo $confTables ['rollover_of_principal']['Compartment']['cmp_name']; ?> </td>
		</tr>
	</tbody>
</table>
		<?php
	}	
		
	if (!empty($confTables['repayment_of_interest']))
	{
		?>
		
	<h5> Repayment of interest </h5>
	<table id="repayment_of_interest" class="table table-bordered table-stripped table-hover nowrap small-cell">
	<thead>
		<tr>
			<th>TRN</th>
			<th>State</th>
			<th>Type</th>
			<th>Principal account</th>
			<th>Interest account</th>
			<th>Term or Callable</th>
			<th>Commencement date</th>
			<th>Amount</th>
			<th>Ccy</th>
			<th>Days</th>
			<th>Reinv availability date</th>
			<th>Mandate</th>
			<th>Compartment</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="tr_number"><?php echo $confTables ['repayment_of_interest']['Transaction']['tr_number']; ?></td>
			<td class="State"><?php echo $confTables ['repayment_of_interest']['Transaction']['tr_state']; ?></td>
			<td class="Type"><?php echo $confTables ['repayment_of_interest']['Transaction']['tr_type']; ?></td>
			<td class="principal_account"><?php echo $confTables ['repayment_of_interest']['Transaction']['accountA_IBAN']; ?></td>
			<td class="interest_account"><?php echo $confTables ['repayment_of_interest']['Transaction']['accountB_IBAN']; ?></td>
			<td class="Term_or_Callable"><?php echo $confTables ['repayment_of_interest']['Transaction']['depo_type']; ?></td>
			<td class="Commencement" ><?php echo $confTables ['repayment_of_interest']['Transaction']['commencement_date']; ?></td>
			<td class="Amount"><?php echo $confTables ['repayment_of_interest']['Transaction']['amount']; ?></td>
			<td class="ccy"><?php echo $confTables ['repayment_of_interest']['Transaction']['ccy']; ?></td>
			<td class="days"><?php echo $confTables ['repayment_of_interest']['Transaction']['days']; ?></td>
			<td class="reinv_availability_date"><?php echo $confTables ['repayment_of_interest']['Transaction']['reinv_availability_date']; ?></td>
			<td class="Mandate"><?php echo $confTables ['repayment_of_interest']['Mandate']['mandate_name']; ?></td>
			<td class="Compartment"><?php echo $confTables ['repayment_of_interest']['Compartment']['cmp_name']; ?> </td>
		</tr>
	</tbody>
</table>
		<?php
	}
	
	if (!empty($confTables['rollover_of_principal_and_interest']))
	{
		?>
		
	<h5> Rollover of principal and interest </h5>
	<table id="rollover_of_principal_and_interest" class="table table-bordered table-stripped table-hover nowrap small-cell">
	<thead>
		<tr>
			<th>TRN</th>
			<th>State</th>
			<th>Type</th>
			<th>Principal account</th>
			<th>Interest account</th>
			<th>Term or Callable</th>
			<th>Commencement date</th>
			<th>Amount</th>
			<th>Ccy</th>
			<th>Days</th>
			<th>Reinv availability date</th>
			<th>Mandate</th>
			<th>Compartment</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="tr_number"><?php echo $confTables ['rollover_of_principal_and_interest']['Transaction']['tr_number']; ?></td>
			<td class="State"><?php echo $confTables ['rollover_of_principal_and_interest']['Transaction']['tr_state']; ?></td>
			<td class="Type"><?php echo $confTables ['rollover_of_principal_and_interest']['Transaction']['tr_type']; ?></td>
			<td class="principal_account"><?php echo $confTables ['rollover_of_principal_and_interest']['Transaction']['accountA_IBAN']; ?></td>
			<td class="interest_account"><?php echo $confTables ['rollover_of_principal_and_interest']['Transaction']['accountB_IBAN']; ?></td>
			<td class="Term_or_Callable"><?php echo $confTables ['rollover_of_principal_and_interest']['Transaction']['depo_type']; ?></td>
			<td class="Commencement" ><?php echo $confTables ['rollover_of_principal_and_interest']['Transaction']['commencement_date']; ?></td>
			<td class="Amount"><?php echo $confTables ['rollover_of_principal_and_interest']['Transaction']['amount']; ?></td>
			<td class="ccy"><?php echo $confTables ['rollover_of_principal_and_interest']['Transaction']['ccy']; ?></td>
			<td class="days"><?php echo $confTables ['rollover_of_principal_and_interest']['Transaction']['days']; ?></td>
			<td class="reinv_availability_date"><?php echo $confTables ['rollover_of_principal_and_interest']['Transaction']['reinv_availability_date']; ?></td>
			<td class="Mandate"><?php echo $confTables ['rollover_of_principal_and_interest']['Mandate']['mandate_name']; ?></td>
			<td class="Compartment"><?php echo $confTables ['rollover_of_principal_and_interest']['Compartment']['cmp_name']; ?> </td>
		</tr>
	</tbody>
</table>
	<?php
	}
	?>
	<a href="/treasury/treasurytransactions/interest_fixing" class="btn btn-primary">Ok</a>
</div>