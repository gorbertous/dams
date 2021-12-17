<div class="tabbable"> 
  <ul class="nav nav-tabs">
    <li class="active"><a href="#table1" data-toggle="tab">Source transactions</a></li>
    <li><a href="#table2" data-toggle="tab">Deposits and Rollovers</a></li>
    <li><a href="#table3" data-toggle="tab">Repayments</a></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="table1">
      <?php echo (isset($sas))? $this->BootstrapTables->bootstrapOneTable('table1',$table1):''; ?>
    </div>
    <div class="tab-pane" id="table2">
     	<?php echo (isset($sas))? $this->BootstrapTables->bootstrapOneTable('table2',$table2):''; ?>
    </div>
     <div class="tab-pane" id="table3">
     	<?php echo (isset($sas))? $this->BootstrapTables->bootstrapOneTable('table3',$table3):''; ?>
    </div>
  </div>
</div>