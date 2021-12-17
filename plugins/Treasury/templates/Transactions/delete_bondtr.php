<div class = "span12 row-fluid">
	<?php echo $this->Form->create('transaction');?>
	<div style="overflow: auto;">
		<?php if(isset($data)):?>
		<div class="alert alert-danger">
			<?php if(empty($data['Bondtransaction']['parent_id']))
				print 'Are you sure you want to delete the transaction below ? If yes then please leave a comment before deletion.'; 
			else print 'The deposit below is linked to a maturing transaction. Are you sure you want to delete this deposit?' ?>
		</div>
		
		<table id="transaction" class="table table-bordered table-stripped table-hover nowrap small-cell">
			<thead>
				<tr>
					<th>TRN</th>
					<th>State</th>
					<th>Type</th>
					<th>ISIN*</th>
					<th>Issue</th>
					<th>Settlement</th>
					<th>Maturity</th>
					<th>Coup.Rate%</th>
					<th>Nominal</th>
					<th>Ccy</th>
					<th>Mandate</th>
					<th>Compartment</th>
					<th>Counterparty</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="tr_number" ><?php echo $data['Bondtransaction']['tr_number'] ?></td>
					<td class="State" ><?php echo $data['Bondtransaction']['tr_state'] ?></td>
					<td class="Type" >Bond</td>
					<td class="Term_or_Callable" ><?php echo $data['Bond']['ISIN'] ?></td>
					<td class="issue" ><?php echo $data['Bond']['issue_date'] ?></td>
					<td class="Commencement" ><?php echo $data['Bondtransaction']['settlement_date'] ?></td>
					<td class="total_interest" ><?php echo $data['Bond']['maturity_date'] ?></td>
					<td class="date_basis" ><?php echo number_format(floatval($data['Bond']['coupon_rate']),4, '.', ',');  ?></td>
					<td class="principal_account" ><?php echo number_format(floatval($data['Bondtransaction']['nominal_amount']),2, '.', ','); ?></td>
					<td class="amountccy" ><?php echo $data['Bond']['currency'] ?></td>
					<td class="Mandate" ><?php echo $data['Mandate']['mandate_name'] ?></td>
					<td class="Compartment" ><?php echo $data['Compartment']['cmp_name'] ?></td>
					<td class="Counterparty" ><?php echo $data['Counterparty']['cpty_name'] ?></td>
				</tr>
			</tbody>
		</table>
		<?php 
		//debug($data);
		
		endif; ?>
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