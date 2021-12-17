<?php 
  echo $this->Html->css('/treasury/css/custom-theme/jquery-ui-1.10.0.custom');
  echo $this->Html->css('/treasury/js/jquery-ui-1.10.3.custom.min');
  echo $this->Html->script('/treasury/js/jquery-ui.min.js')
?>
<fieldset>
<?php if(!empty($confirmedTransactions)): ?>
  <div id="registerConfResult" title="Success : confirmation received.">
<?php foreach($confirmedTransactions as $confirmedTransaction): ?>
<?php if(empty($_GET['aftr'])): ?><legend>Confirmation received for transaction <?php print $confirmedTransaction['Transaction']['tr_number'] ?></legend><?php endif ?>

  <?php if($confirmedTransaction['Transaction']['Term_or_Callable'] == 'Term') :?>
    <div class="">
      <?php $this->BootstrapTables->drawTransaction(array($confirmedTransaction)); ?>
    </div>
  <?php endif; ?>
  <div class="span12">
    <?php 
      $this->BootstrapTables->displayOneRow('confirmedTransaction',array($confirmedTransaction)); 
    ?>
  </div>

<?php endforeach ?>
  
  <?php if(empty($_GET['aftr'])): ?>
    <?php if(1||empty($instr_num)): ?>
      <a href="/treasury/treasurytransactions/registerconf" class="btn btn-primary">Ok</a>
    <?php else: ?>
      <a href="/treasury/treasurytransactions/registerInstrConf/<?php print $instr_num ?>" class="btn btn-primary">Ok</a>
    <?php endif ?>
  <?php endif ?>
  </div>

</fieldset>
<?php endif ?>
<script>
  /*$(function() {
    $( "#registerConfResult" ).dialog({
    	resizable: true,
      	height:800,
      	width:1200,
      	modal: true,
      	buttons: {
        	Close: function() {
          		$( this ).dialog( "close" );
          		$(location).attr('href','/treasury/treasurytransactions/registerconf');
        	}
      	}
    });
  });*/
</script>
