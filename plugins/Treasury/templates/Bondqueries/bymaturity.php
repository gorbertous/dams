<?php
	echo $this->Html->css('/treasury/css/datepicker');
	echo $this->Html->script('/treasury/js/bootstrap-datepicker');
	echo $this->Html->css('/treasury/css/dataTableSort');
?>
<div class="well">
	<div class="tabbable">
		<ul class="nav nav-tabs">
			<?php echo '<li class ="'.$tab1state.'">';?>
		    	<a href="#tab1" data-toggle="tab">Transaction Query By Status</a></li>
		   	<?php echo '<li class ="'.$tab2state.'">';?><a href="#tab2" data-toggle="tab">Results</a></li>
		</ul>
	  	<div class="tab-content">
	   	<?php echo '<div class= "tab-pane '.$tab1state.'" id="tab1">'; ?>
	   	<p>
	    <div id="form">
			<?php echo $this->Form->create('tqbymaturity') ?>
			<?php echo $this->Form->input(
	        'Mandate_id', array(
	            'label'     => 'Mandate :',
	            'options'   =>  $mandates_list,
	            'empty'     => __('(Select a mandate)'),
	            'required'  => true,
	    	));
			?>


			<?php echo $this->Form->input(
			        'MaturityDateStart', array(
			        	'name'		=> 'data[tqbymaturity][MaturityDateStart]',
			            'empty'     => __('(Select a date)'),
			            'label'		=> 'Transaction maturity from:',
			            'data-date-format'	=> 'yyyy-mm-dd',
			    ));
			?>

			<?php echo $this->Form->input(
			        'MaturityDateEnd', array(
			        	'name'		=> 'data[tqbymaturity][MaturityDateEnd]',
			            'empty'     => __('(Select a date)'),
			            'label'=> 'Transaction maturity date to:',
			            'data-date-format'	=> 'yyyy-mm-dd',
			    ));
			?>


		<?php echo $this->Form->end(array('label'=>__('Show transactions'), 'class' => 'btn btn-primary')) ?>
		</div>
	   	</p>
	    </div>
	    <?php echo '<div class= "tab-pane '.$tab2state.'" id="tab2">'; ?>
	      <p>
	    <div id="results" style="overflow:auto;"><?php echo $msg; echo (isset($sas1))? $this->BootstrapTables->bootstrapMultipleTables($tables):'' ?>
	    </div>
	      </p>
	    </div>
	  </div>
	</div>
</div>


<?php
	echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/jquery.dataTables.js');
	echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/dataTables.bootstrap.js');
	echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/extras/TableTools/media/js/ZeroClipboard.js');
	//echo $this->Html->script('/theme/Cakestrap/js/libs/jquery-ui.min.js');
?>

<script type="text/javascript">
var checkin = $('#tqbymaturityMaturityDateStart').datepicker({}).on('changeDate', function(ev) {
	  if (ev.date.valueOf() > checkout.date.valueOf()) {
	    var newDate = new Date(ev.date);
	    newDate.setDate(newDate.getDate() + 1);
	    checkout.setValue(newDate);
	  }
	  checkin.hide();
	  $('#tqbymaturityMaturityDateEnd')[0].focus();
	}).data('datepicker');
	var checkout = $('#tqbymaturityMaturityDateEnd').datepicker({
		  onRender: function(date) {
		    return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
		  }
		}).on('changeDate', function(ev) {
		  checkout.hide();
		}).data('datepicker');

$(document).ready(function(){

            // Grid SAS _webout table1 (Deposits and Rollovers)
           		$('#table1').dataTable({
           			//"sDom": "<'row-fluid'<'span6'T><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
           		    "bLengthChange": true,
           		     "bAutoWidth": true,
            } );

      	  // Grid SAS _webout table3 (Repaymenents)
           		$('#table3').dataTable({
           			//"sDom": "<'row-fluid'<'span6'T><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
           		    "bLengthChange": true,
           		     "bAutoWidth": true,
            } );

        });
</script>
