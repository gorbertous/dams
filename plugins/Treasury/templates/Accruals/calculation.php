<?php
	echo $this->Html->css('/treasury/css/datepicker');
	echo $this->Html->script('/treasury/js/bootstrap-datepicker');
?>
<div class="tabbable">
	<ul class="nav nav-tabs">
		<li class=" <?php echo $tab1state ;?> ">
		<a href="#tab1" data-toggle="tab">Accruals Calculation Form</a>
	</li>
		<li class =" <?php echo $tab2state ;?>" >
			<a href="#tab2" data-toggle="tab">Report</a>
		</li>
	</ul>
	<div class="tab-content">
		<div class = "tab-pane <?php echo $tab1state; ?>" id = "tab1">
	   		<div class="well">
	   			<div id="form">
	 				<?php echo $this->Form->create('accrualsform') ?>


					<?php echo $this->Form->input('mandate_ID',
													array(
											            'label'     =>	'Mandate',
											            'options'   =>	$mandates_list,
											            'empty'     =>	__('-- Select a mandate --'),
				            							'required'  =>	true,
			    									)
			    								);
			    	?>

					<?php echo $this->Form->input('cpty_ID',
													array(
														'label'		=>	'Counterparty',
														'options'	=>	array(),
														'empty' 	=>	__('-- Select a counterparty --'),
														'required'  =>	true,
													)
												);
					?>



			    	<?php echo $this->Form->input('StartDate',
			    									array(
			    										'name'				=> 'data[accrualsform][StartDate]',
			    										'id'				=> 'accrualsformStartDate',
		    											'required'  		=> true,
														'data-date-format'	=> 'dd/mm/yyyy',
													)
												);
					?>



			    	<?php echo $this->Form->input('EndDate',
										    		array(
										    			'name'				=>	'data[accrualsform][EndDate]',
										    			'id'				=>	'accrualsformEndDate',
														'required'   		=>	true,
														'data-date-format'	=>	'dd/mm/yyyy',
													)
												);
					?>


					<?php echo $this->Form->end(array('label'=>__('Run Accruals Report'), 'class' => 'btn btn-primary')) ?>
				</div>
			</div>
    	</div>
    	<div class = "tab-pane <?php echo $tab2state; ?>" id = "tab2">
      		<div class="well">
      			<div id="results" class="" style="overflow:auto;">
     				<?php echo $msg;  echo (isset($sas1))? $this->BootstrapTables->bootstrapMultipleTables($tables):'' ?>
			</div>
     		</div>
      	</div>
  	</div>
</div>
	<div style="display:none;">
<?php
echo $this->Form->create('cpty', array('url'=>'/treasury/treasuryajax/getcptybymandate2/accrualsform/1'));

echo $this->Form->input('accrualsform.mandate_ID', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();
?>
	</div>
<script type="text/javascript">
	var checkin = $('#accrualsformStartDate').datepicker({ dateFormat: "dd/mm/yy" }).on('changeDate', function(ev) {
		if (ev.date.valueOf() > checkout.date.valueOf()) {
	    var newDate = new Date(ev.date);
	    newDate.setDate(newDate.getDate() + 1);
	    checkout.setValue(newDate);
	  }
	  checkin.hide();
	  $('#accrualsformEndDate')[0].focus();
	}).data('datepicker');
	var checkout = $('#accrualsformEndDate').datepicker({
		  onRender: function(date) {
		    return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
		  }
		}).on('changeDate', function(ev) {
		  checkout.hide();
		}).data('datepicker');
	
	$(document).ready(function()
	{
		$("#accrualsformCalculationForm #accrualsformMandateID").bind("change", function (event) {
			$('#cptyCalculationForm #accrualsformMandateID').val( $('#accrualsformCalculationForm #accrualsformMandateID').val() );
			var data = $('#cptyCalculationForm').serialize();
			$.ajax({
				url: '/treasury/treasuryajax/getcptybymandate2/accrualsform/1',
				type: "POST",
				data: data,
				context: $(this)
			}).done(function( data ){
				$('#accrualsformCalculationForm #accrualsformCptyID').html(data);
			});

		});
	});
</script>
