<?php 
  echo $this->Html->css('/treasury/css/custom-theme/jquery-ui-1.10.0.custom');
  echo $this->Html->script('/treasury/js/jquery-ui-1.10.3.custom.min');
  echo $this->Html->script('/treasury/js/jquery-ui.min.js')
?>
<fieldset>
  
  
    <legend>New Bond Transaction Confirmation <?php print $transactions[0]['Bondtransaction']['tr_number'] ?></legend>
    <div id="trconfirmation" title="Success">
<div class="span12">
	<div id="message" class="span5">
Bond transaction was successfully created in the database. Download bond instruction <a href="/treasury/treasuryajax/download_file/1?file=/data/treasury/pdf/bond_instruction_<?php print $transactions[0]['Bondtransaction']['instr_num']; ?>.pdf">here</a>.
	</div>

</div>
    </div>
        <a href="/treasury/treasurybonds/newbonds" class="btn btn-primary">Ok</a>

</fieldset>
<style>
#message
{
	font-size: 15px;
}
</style>