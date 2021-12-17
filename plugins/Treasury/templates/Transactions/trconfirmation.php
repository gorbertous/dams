<?php 
  echo $this->Html->css('/treasury/css/custom-theme/jquery-ui-1.10.0.custom');
  echo $this->Html->script('/treasury/js/jquery-ui-1.10.3.custom.min');
  echo $this->Html->script('/treasury/js/jquery-ui.min.js')
?>
<fieldset>
  
  <?php foreach($transactions as $transaction): ?>
    <?php if(empty($_GET['aftr'])): ?><legend>New deposit Confirmation <?php print $transaction[0]['Transaction']['tr_number'] ?></legend><?php endif ?>
    <div id="trconfirmation" title="Success">
      <?php if(isset($transaction[0]['Transaction']['Term_or_Callable']) && $transaction[0]['Transaction']['Term_or_Callable'] == 'Term') :?>
        <div class="">
          <?php $this->BootstrapTables->drawTransaction($transaction); ?>
        </div>
      <?php endif; ?>
      <div class="span12">
        <?php $this->BootstrapTables->displayOneRow('deposit',$transaction);?>
      </div>
    </div>
  <?php endforeach ?>
  <?php if(empty($_GET['aftr'])): ?>
        <a href="/treasury/treasurytransactions/newdeposits" class="btn btn-primary">Ok</a>
  <?php endif ?>
</fieldset>