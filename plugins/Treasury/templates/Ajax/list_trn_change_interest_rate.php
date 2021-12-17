
		<thead>
			<tr>
				<th>Select all</th>
				<th>TRN</th>
				<th>Counterparty</th>
				<th>Mandate</th>
				<th>Amount</th>
				<th>Newest Interest Rate</th>
				<th>Rate Value Date</th>
			</tr>
		</thead>
		<tbody id="transactions">
		<?php
		foreach($callables as $k => $trn)
		{
			$tr_number = $trn['Transaction']['tr_number'];
			echo '<tr class="cpty_'.$trn['Transaction']['cpty_id'].'">';
			//echo '<td><input type="checkbox" name="data[Transaction]['.$tr_number.']">';
			//echo '<input type="hidden" name="value_date_'.$tr_number.'" value="'.$trn['Transaction']['maturity_date'].'"/></td>';
			echo $this->Form->input('Transaction.'.$tr_number, 
					array('type'=>'checkbox', 'value'=>$trn['Transaction']['maturity_date']));
			echo '<td>'.$tr_number.'</td>';
			echo '<td>'.$trn['Counterparty']['cpty_name'].'</td>';
			echo '<td>'.$trn['Mandate']['mandate_name'].'</td>';
			echo '<td style="text-align: right;">'.$trn['Transaction']['amount'].'</td>';
			echo '<td style="text-align: right;">'.$trn['Interest']['interest_rate'].'</td>';
			echo '<td>'.$trn['Interest']['interest_rate_from'].'</td>';
			echo '</tr>';
		}
		?>
		</tbody>
