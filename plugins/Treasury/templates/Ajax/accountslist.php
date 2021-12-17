<?php
	if(isset($accountslist['accountA_IBAN']) && !empty($accountslist['accountA_IBAN']) && $accountslist['accountA_IBAN'] != 'N/A')
	echo '<option value="'.$accountslist['accountA_IBAN'].'"> Account A : '.UniformLib::uniform($accountslist['accountA_IBAN'], 'accountA_IBAN').'</option>';
?>

