<?php $dates = array(); $terms = array(); ?>
<?php if(!empty($footer_force)) print $this->Form->input('Retained.tr_number', array('type' => 'hidden','value' => 1, 'class' => 'force_footer' )); ?>
<?php if(!empty($transactions)): ?>
	<h4>Deposit Instruction content:</h4>
	<table id="createDItransactions" class="table table-bordered table-striped table-hover table-condensed <?php print 'cpty-'.$cpty_id.' mandate-'.$mandate_id ?>">
		<thead>
			<tr>
				<th class="num">TRN</th>
				<th class="type">Type</th>
				<th class="comp">Comp</th>
				<th class="amount">Amount</th>
				<th class="ccy">CCY</th>
				<th class="date">Comm/Rep Date</th>
				<th class="term">Term</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($transactions as $key=>$trans): ?>
			<?php
				//row class depending on date and term
				$trsclass = '';
				$date = '';
				if(1||(!empty($trans['Transaction']['commencement_date']) && intval($trans['Transaction']['commencement_date']))){
					$date = $trans['Transaction']['commencement_date'];//date('d/m/Y',
					if(array_search($date, $dates)===false){
						array_push($dates, $date);
					}
					$trsclass = 'date-'.array_search($date, $dates);
				}

				if(!empty($trans['Transaction']['depo_term'])){
					$term = strtolower($trans['Transaction']['depo_term']);
					$trsclass.= ' term-'.$term;
					if(empty($terms[$term])) $terms[$term]=1;
					else $terms[$term]+=1;
					$trsclass.= ' term-'.$term.'-'.($terms[$term]-1);
				}

				$trsclass.= ' type-'.strtolower($trans['Transaction']['tr_type']);
			?>
			<tr class="<?php print $trsclass ?>">
				<td><?php print UniformLib::uniform($trans['Transaction']['tr_number'], 'tr_number') ?></td>
				<td><?php print UniformLib::uniform($trans['Transaction']['tr_type'], 'tr_type') ?></td>
				<td style="text-align: right;"><?php print UniformLib::uniform($trans['Compartment']['cmp_value'], 'cmp_value') ?></td>
				<td style="text-align: right;"><?php print UniformLib::uniform($trans['Transaction']['amount'],'amount') ?></td>
				<td><?php print UniformLib::uniform($trans['AccountA']['ccy'], 'ccy') ?></td>
				<td><?php print UniformLib::uniform($date, 'commencement_date') ?></td>
				<td><?php print UniformLib::uniform($trans['Transaction']['depo_term'], 'depo_term') ?></td>
			</tr>
		<?php endforeach ?>
		</tbody>
	</table>
<?php else: ?>
<h4>This Deposit instruction is empty</h4>
<?php endif ?>