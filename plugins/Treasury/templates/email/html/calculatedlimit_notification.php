<p>Update of Calculated Limit:</p>
<table border="1">
	<thead>
		<tr>
			<th>Field</th>
			<th>Old</th>
			<th>New</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($old as $key => $value): ?>
			<?php if(array_key_exists($key, $diff)) $key_diff = true; else $key_diff = false; ?>
			<?php if($key=='eligibility'){
				if(empty($value)) $value='NO'; else $value='YES'; 
				if(empty($new[$key])) $new[$key]='NO'; else $new[$key]='YES'; 
			} ?>
			<tr>
				<td><?php if ($key == 'mandategroup_ID') echo 'Portfolio';
				elseif($key == 'cpty_ID') echo 'Counterparty';
				else echo $key; ?></td>
				<td><?php if($key_diff) echo "<strong style='color:red'>".UniformLib::uniform($value, $key)."</strong>"; else print UniformLib::uniform($value, $key) ?></td>
				<td><?php if($key_diff) echo "<strong style='color:red'>".UniformLib::uniform($new[$key], $key)."</strong>"; else print UniformLib::uniform($new[$key], $key) ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>