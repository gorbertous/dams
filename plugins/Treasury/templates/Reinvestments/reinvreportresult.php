<?php 
  echo $this->Html->css('/treasury/css/custom-theme/jquery-ui-1.10.0.custom');
  echo $this->Html->script('/treasury/js/jquery-ui-1.10.3.custom.min');
  echo $this->Html->script('/treasury/js/jquery-ui.min.js')
?>
<div id="reinvReport" class=""> 
  <div>
    <h5>Source transactions :</h5>
    <?php (sizeof($source_transactions) > 0)? $this->BootstrapTables->displayRawsById('source_transactions', $source_transactions): '' ?>
  </div>
   <div>
    <h5>Deposits and Rollovers :</h5>
   <?php if (sizeof($depo_rollovers) >0) $this->BootstrapTables->displayRawsById('depo_rollovers', $depo_rollovers); else echo  'The selected reinvestment has no outgoing rollovers'; ?>
  </div>
  <div>
    <h5>Repayments :</h5>
   <?php if (sizeof($repayments) >0) $this->BootstrapTables->displayRawsById('repayments', $repayments); else echo 'The selected reinvestment has no outgoing repayments'; ?>
  </div>

  <!--<ul class="nav nav-tabs">
      <li class="active"><a href="#table1" data-toggle="tab">Source transactions</a></li>
      <li><a href="#table2" data-toggle="tab">Deposits and Rollovers</a></li>
      <li><a href="#table3" data-toggle="tab">Repayments</a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="table1">
        <?php //(sizeof($source_transactions) > 0)? $this->BootstrapTables->displayRawsById('source_transactions', $source_transactions): '' ?>
      </div>
      <div class="tab-pane" id="table2">
        <?php //if (sizeof($depo_rollovers) >0) $this->BootstrapTables->displayRawsById('depo_rollovers', $depo_rollovers); else echo  'The selected reinvestment has no outgoing rollovers'; ?>
      </div>
      <div class="tab-pane" id="table3">
        <?php //(sizeof($repayments) >0)? $this->BootstrapTables->displayRawsById('repayments', $repayments):''  ?>
      </div>
  </div>-->
</div>
<script>
  /*$(function() {
    $( "#reinvReport" ).dialog({
      resizable: true,
        height:800,
        width:1200,
        modal: true,
        buttons: {
          Close: function() {
              $( this ).dialog( "close" );
              $(location).attr('href','#');
          }
        }
    });
  });*/
</script>