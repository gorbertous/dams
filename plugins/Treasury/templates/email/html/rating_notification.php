<p>Update of Rating:</p>
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
			<tr>
				<td><?php echo $key; ?></td>
				<td><?php if($key_diff) echo "<strong style='color:red'>".UniformLib::uniform($value, $key)."</strong>"; else print UniformLib::uniform($value, $key) ?></td>
				<td><?php if($key_diff) echo "<strong style='color:red'>".UniformLib::uniform($new[$key], $key)."</strong>"; else print UniformLib::uniform($new[$key], $key) ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>