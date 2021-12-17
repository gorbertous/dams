<p>Deposit Instruction Reminder:</p>
<ul>
	<li>Instruction Number: 	<?php echo $instr['Instruction']['instr_num'] ?></li>
	<li>Mandate: 	<?php echo $instr['Mandate']['mandate_name'] ?></li>
	<li>Counterparty: 	<?php echo $instr['Counterparty']['cpty_name'] ?></li>
	<li>Transaction(s):<ul>
		<?php foreach($instr['Transactions'] as $trans): ?>
			<li>
				<?php 
					print $trans['tr_type'].' '.$trans['depo_type'].' '.$trans['tr_number'];
					if(!empty($trans['commencement_date'])) print ', '.$trans['commencement_date'];
				?>
			</li>
		<?php endforeach ?>
	</ul></li>	
</ul>
<p>Please find the attached Instruction in PDF</p>