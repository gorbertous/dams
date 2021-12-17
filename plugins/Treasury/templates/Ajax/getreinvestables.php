<?php
	echo $this->Html->css('/treasury/css/dataTableSort');
?>
<div>
<?php
	if(isset($reinvs) && is_array($reinvs) ): ?>
		<table id="selectReinvs" class="table table-bordered table-striped table-hover">
			<thead>
				<?php if($select): ?>
					<th> Select </th>
				<?php endif; ?>
				<th> TRN </th>
				<th> Amount </th>
				<th> Interest </th>
				<th> Tax </th>
				<th> Principal Account </th>
				<th> Interest Account </th>
				<th> Maturity Date </th>
				<th> Funds in account A </th>
				<th> Funds in account B </th>
			</thead>
	        <tbody>
			<?php foreach ($reinvs as $value): ?>
				<tr>
				<?php if($select): ?>
					<td>
					<?php echo $this->Form->input('openreinvestform.reinvestables.', 
							array('type'	=>	'checkbox', 
									'value'	=>	$value['Transaction']['tr_number'],
						)); ?>
					</td>
				<?php endif; ?>
				<td class="trn"><?php echo UniformLib::uniform($value['Transaction']['tr_number'], 'tr_number') ?></td>
				<td class="amount"><?php echo UniformLib::uniform($value['Transaction']['amount'], 'amount') ?> <?php echo $value['AccountA']['ccy'] ?></td>
				<td class="total_interest"><?php echo UniformLib::uniform($value['Transaction']['total_interest'], 'total_interest') ?> <?php echo $value['AccountA']['ccy'] ?></td>
				<td class="tax_amount"><?php echo UniformLib::uniform($value['Transaction']['tax_amount'], 'tax_amount') ?> <?php echo $value['AccountA']['ccy'] ?></td>
				<td class="accountA_IBAN"><?php echo UniformLib::uniform($value['Transaction']['accountA_IBAN'], 'accountA_IBAN') ?></td>
				<td class="accountB_IBAN"><?php echo UniformLib::uniform($value['Transaction']['accountB_IBAN'], 'accountB_IBAN') ?></td>
				<td class="maturity_date"><?php echo UniformLib::uniform($value['Transaction']['maturity_date'], 'maturity_date') ?></td>
				<td class="amountInA"><?php echo UniformLib::uniform($value['Transaction']['amountInA'], 'amountInA') ?> <?php echo $value['AccountA']['ccy'] ?></td>
				<td class="amountInB"><?php echo UniformLib::uniform($value['Transaction']['amountInB'], 'amountInB') ?>  <?php echo $value['AccountA']['ccy'] ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<?php if($select): ?>
			<br>
			<?php echo $this->Form->button(__('Calculate Reinvestment Group'), array('class' => 'btn btn-primary')); ?>
		<?php endif; ?>
	<?php else: ?>
		<div class="alert">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo $reinvs ?>
		</div>
	<?php endif; ?>
</div>
<style>
td.amount,
td.total_interest,
td.tax_amount,
td.amountInA,
td.amountInB{ text-align: right; }
</style>
