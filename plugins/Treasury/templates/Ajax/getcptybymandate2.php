<option value="">-- Select a counterparty --</option>
<?php foreach ($cptys as $key => $value): ?>
	<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
<?php endforeach; ?>