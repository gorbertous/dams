<?php 
  echo $this->Html->css('/treasury/css/custom-theme/jquery-ui-1.10.0.custom');
  echo $this->Html->script('/treasury/js/jquery-ui-1.10.3.custom.min');
  echo $this->Html->script('/treasury/js/jquery-ui.min.js')
?>
<fieldset>
  <legend>Call deposit success</legend>
  <div class="span12" id="callDepositResult" title="Call deposit success">
    <div style="overflow: auto;">
        <?php foreach($confTables as $key => $value): ?>
          <?php foreach($value as &$val) ?>
          <h5> <?php  echo $this->BootstrapTables->deleteUnderScore($key); ?> </h5>
          <?php $this->BootstrapTables->displayRawsById($key, $value); ?>
        <?php endforeach; ?>
    </div>
    <a href="/treasury/treasurytransactions/calldeposit" class="btn btn-primary">Ok</a>
  </div>
</fieldset>
<script>
  /*$(function() {
    $( "#callDepositResult" ).dialog({
    	resizable: true,
      	height:700,
      	width:1300,
      	modal: true,
      	buttons: {
        	Close: function() {
          		$( this ).dialog( "close" );
          		$(location).attr('href','/treasury/treasurytransactions/calldeposit');
        	}
      	}
    });
  });*/
</script>