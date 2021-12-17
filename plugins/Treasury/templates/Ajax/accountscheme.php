<option value="">-- Select an account --</option>
<?php
	if(isset($accs['accountA_IBAN']) && !empty($accs['accountA_IBAN']))
	echo '<option value="'.$accs['accountA_IBAN'].'"> Account A : '.UniformLib::uniform($accs['accountA_IBAN'], 'accountA_IBAN').'</option>';
?>
<?php
	if(isset($accs['accountB_IBAN']) && !empty($accs['accountB_IBAN']))
	echo '<option value="'.$accs['accountB_IBAN'].'"> Account B : '.UniformLib::uniform($accs['accountB_IBAN'], 'accountB_IBAN').'</option>';
?>


