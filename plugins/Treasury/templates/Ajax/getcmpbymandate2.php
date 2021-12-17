<option value="">-- Select a compartment --</option>
<?php
foreach ($cmps as $key => $value): ?>
	<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
<?php endforeach; ?>