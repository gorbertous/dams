<table class="table table-bordered table-striped">
	<tr>
		<td>Account A</td>
		<td>
			<?php
				if(isset($accounts['accountA_IBAN']) && !empty($accounts['accountA_IBAN']) && $accounts['accountA_IBAN'] != 'N/A') echo UniformLib::uniform($accounts['accountA_IBAN'], 'accountA_IBAN');
				else echo 'IBAN: N/A' ; 
			?>
		</td>
		<td>
			<?php
				if(isset($ccyA) && !empty($ccyA) && $accounts['accountA_IBAN'] != 'N/A') echo $ccyA ;
				else echo 'CCY: N/A' ; 
			?>
		</td>
	</tr>
</table>
