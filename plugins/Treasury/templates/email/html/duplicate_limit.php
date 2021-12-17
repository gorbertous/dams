<h1>Duplicated limits detected :</h1>
<table>
	<thead>
		<tr><th>MandateGroup</th><th>CounterpartyGroup</th><th>Counterparty</th><th>sql request</th></tr>
	</thead>
	<tbody>
		<?php
		foreach($result as $line)
		{
			echo "<tr>";
			echo "<td>".$line['MandateGroup']."</td>";
			echo "<td>".$line['CounterpartyGroup']."</td>";
			echo "<td>".$line['Counterparty']."</td>";
			echo "<td>".$line['request']."</td>";
			echo "</tr>";
		}
		
		?>
	</tbody>
</table>