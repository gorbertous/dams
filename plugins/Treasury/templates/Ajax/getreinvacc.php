<table class="table table-bordered table-striped">
	<tr>
		<td>Account A</td>
		<td>
			<?php
				if(isset($accounts['accountA_IBAN']) && !empty($accounts['accountA_IBAN']) && $accounts['accountA_IBAN'] != 'N/A') echo UniformLib::uniform($accounts['accountA_IBAN'], 'accountA_IBAN') ;
				else echo 'IBAN: N/A' ; 
			?>
		</td>
		<td class="ccy">
			<?php
				if(isset($ccyA) && !empty($ccyA) && $accounts['accountA_IBAN'] != 'N/A') echo $ccyA ;
				else echo 'CCY: N/A' ; 
			?>
		</td>
	</tr>
	<tr>
	    <td>Account B</td>
	    <td>
		    <?php 
		    	if(isset($accounts['accountB_IBAN']) && !empty($accounts['accountB_IBAN']) && $accounts['accountB_IBAN'] != 'N/A') echo UniformLib::uniform($accounts['accountB_IBAN'], 'accountB_IBAN') ;
		    	 else echo 'IBAN: N/A';
		    ?>
	    </td>
	    <td class="ccy">
		    <?php 
		    	if(isset($ccyB) && !empty($ccyB) && $accounts['accountB_IBAN'] != 'N/A') echo $ccyB ;
		    	 else echo 'CCY: N/A';
		    ?>
	    </td>
	</tr>
</table>
