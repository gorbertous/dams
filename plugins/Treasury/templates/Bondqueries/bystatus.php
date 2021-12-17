<?php
  echo $this->Html->css('/treasury/css/dataTableSort');
  echo $this->Html->css('/theme/Cakestrap/js/libs/datatables/extras/TableTools/media/css/dataTables.bootstrap.css');
?>

<div class="">
<div class="tabbable">
  <ul class="nav nav-tabs">
  <?php echo '<li class ="'.$tab1state.'">';?>
    <a href="#tab1" data-toggle="tab">Transaction Query By Status</a></li>
   <?php echo '<li class ="'.$tab2state.'">';?><a href="#tab2" data-toggle="tab">Results</a></li>
  </ul>
  <div class="tab-content">
   <?php echo '<div class= "tab-pane '.$tab1state.'" id="tab1">'; ?>

    <div class="well" id="form">
		<?php echo $this->Form->create('tqbystatus') ?>

		<?php echo $this->Form->input(
        'tr_status', array(
            'label'     => 'Transaction status :',
            'options'   =>  array('Unprocessed'=>'***all unprocessed***','Created'=>'Created','InstructionCreated'=>'Instruction Created',
            					  'Instruction Sent'=>'Instruction Sent','Confirmation Received'=>'Confirmation Received','Confirmed'=>'Confirmed',
            					   'First Notification'=>'First Notification','Second Notification'=>'Second Notification','Matured'=>'Matured',
            					    'Renewed'=>'Renewed','Reinvested'=>'Reinvested'
            				),
            'empty'     => __('(Select a status)'),
            'required'  => true,
    	));
		?>

		<?php echo $this->Form->input(
        'ord', array(
            'label'     => 'Order by :',
            'options'   => array('tr_number'=>'TRN','mandate_name'=>'Mandate','cpty_id'=>'Counterparty','cmp_name'=>'Compartment',
            			          'commencement_date'=>'Commencement Date', 'maturity_date'=>'Maturity Date','instr_num'=>'DI Number'
            			   ),
-
            'empty'     => __('(Select one)'),
            'required'  => true,
    	));
		?>

	<?php echo $this->Form->end(array('label'=>__('Show transactions'), 'class' => 'btn btn-primary')) ?>
	</div>

    </div>
    <?php echo '<div class= "tab-pane '.$tab2state.'" id="tab2">'; ?>

     <div id="results" style="overflow:auto;"><?php echo $msg; echo (isset($sas1))? $this->BootstrapTables->bootstrapMultipleTables($tables):'' ?></div>

    </div>
  </div>
</div>
</div>

<?php
  echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/jquery.dataTables.js');
  echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/dataTables.bootstrap.js');
  echo $this->Html->script('/treasury/js/ColumnFilter/media/js/jquery.dataTables.columnFilter.js');
  echo $this->Html->script('/treasury/js/ColumnFilter/media/js/jquery-ui.js');
?>
<script type="text/javascript">
$(document).ready(function(){
	// Grid SAS _webout table1 (Deposits and Rollovers)
           		$('#table1').dataTable({
           		    "bLengthChange": true,
           		    "aLengthMenu": [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
           		    "iDisplayLength" : -1,
           		    //"bAutoWidth": true,
            }).columnFilter();

   // Grid SAS _webout table3 (Repayments)
           		$('#table3').dataTable({
           			//"sDom": "<'row-fluid'<'span6'T><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
           		    "bLengthChange": true,
           		    "aLengthMenu": [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
           		    "iDisplayLength" : -1,
           		    //"bAutoWidth": true,
            } );

});
</script>
