<!-- di -->
<html>
<head>
  <link rel="stylesheet" href="http://localhost/theme/Cakestrap/css/bootstrap.css">
</head>
<body>
	<fieldset>
		<legend>Bond Instruction <?php echo $bond_trn['Instruction']['instr_num']; ?></legend>
		<br />
		<h4>European Investment Fund</h4>
		<h4>Created on <?php echo date("d F Y"); ?> at <?php echo date("H:i"); ?> by <?php echo $username; ?></h4>
		<table style="font-size:12px;" class="table table-striped">
		    <tbody>
				<tr><td>TRN</td><td><?php echo $bond_trn['Bondtransaction']['tr_number']; ?></td></tr>
				<tr><td>ISIN</td><td><?php echo $bond_trn['Bond']['ISIN']; ?></td></tr>
				<tr><td>Issuer</td><td><?php echo $bond_trn['Bond']['issuer']; ?></td></tr>
				<tr><td>Mandate</td><td><?php echo $bond_trn['Bond']['mandate']; ?></td></tr>
				<tr><td>Counterparty</td><td><?php echo $bond_trn['Bond']['counterparty']; ?></td></tr>
				<tr><td>CCY</td><td><?php echo $bond_trn['Bond']['currency']; ?></td></tr>
				<tr><td>Issue Date</td><td><?php echo UniformLib::uniform($bond_trn['Bond']['issue_date'], '_date'); ?></td></tr>
				<tr><td>Trade Date</td><td><?php echo UniformLib::uniform($bond_trn['Bondtransaction']['trade_date'], 'date'); ?></td></tr>
				<tr><td>Settlement Date</td><td><?php echo UniformLib::uniform($bond_trn['Bondtransaction']['settlement_date'], 'date'); ?></td></tr>
				<tr><td>First Coupon Accrual Date</td><td><?php echo UniformLib::uniform($bond_trn['Bond']['first_coupon_accrual_date'], '_date'); ?></td></tr>
				<tr><td>First Coupon Payment Date</td><td><?php echo UniformLib::uniform($bond_trn['Bond']['first_coupon_payment_date'], '_date'); ?></td></tr>
				<tr><td>Maturity Date</td><td><?php echo UniformLib::uniform($bond_trn['Bond']['maturity_date'], '_date'); ?></td></tr>
				<tr><td>Nominal</td><td><?php echo number_format($bond_trn['Bondtransaction']['nominal_amount'],2, '.', ',' ); ?></td></tr>
				<tr><td>Purchase Price, %</td><td><?php echo number_format($bond_trn['Bondtransaction']['purchase_price'],4, '.', ',' ); ?></td></tr>
				<tr><td>Purchase Amount</td><td><?php echo number_format($bond_trn['Bondtransaction']['purchase_amount'],2, '.', ',' ); ?></td></tr>
				<tr><td>Coupon Rate, %</td><td><?php echo number_format($bond_trn['Bond']['coupon_rate'],4, '.', ',' ); ?></td></tr>
				<tr><td>Coupon Frequency</td><td><?php echo ucfirst($bond_trn['Bond']['coupon_frequency']); ?></td></tr>
				<tr><td>Date Basis</td><td><?php echo $bond_trn['Bond']['date_basis']; ?></td></tr>
				<tr><td>Date Convention</td><td><?php echo ucfirst($bond_trn['Bond']['date_convention']); ?></td></tr>
				<tr><td>Accrued Coupon</td><td><?php echo number_format($bond_trn['Bondtransaction']['accrued_coupon_at_purchase'],2, '.', ',' ); ?></td></tr>
				<tr><td>Total Purchase Amount </td><td><?php echo number_format($bond_trn['Bondtransaction']['total_purchase_amount'],2, '.', ',' ); ?></td></tr>
				<tr><td>Coupon</td><td><?php echo number_format($bond_trn['Bondtransaction']['total_coupon'],2, '.', ',' ); ?></td></tr>
				<tr><td>Tax</td><td><?php echo number_format($bond_trn['Bondtransaction']['total_tax'],2, '.', ',' ); ?></td></tr>
				<tr><td>Yield, %</td><td><?php echo number_format($bond_trn['Bondtransaction']['yield_to_maturity'],4, '.', ',' ); ?></td></tr>
		    </tbody>
		</table>
	</fieldset>
	<style>
	
	body
	{
		height: 99%;
	}
	</style>
</body>
</html>
<!-- end di -->