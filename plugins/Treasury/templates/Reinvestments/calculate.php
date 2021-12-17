<?php
	//echo $this->Html->script('/treasury/js/bootstrap-datepicker');
	//echo $this->Html->css('/treasury/css/datepicker');
	echo $this->Html->css('/treasury/css/redmond/jquery-ui-1.10.3.custom.css');
	echo $this->Html->script('/treasury/js/jquery-ui-1.10.3.custom.min.js');
?>

	<div class="tabbable">
	  	<ul class="nav nav-tabs">
	  		<li class="<?php echo $tab1state;?>">
	    		<a href="#tab1" data-toggle="tab">Open reinvestment</a>
	    	</li>
	    	<li class="<?php echo $tab2state;?>">
	    		<a href="#tab2" data-toggle="tab">Result</a>
	    	</li>
	  	</ul>
	  	<div class="tab-content">
		  	<div class="tab-pane <?php echo $tab1state; ?>" id="tab1">
		   		<?php echo $this->Form->create('openreinvestform') ?>
		   		<div class="">
					<?php
						echo $this->Form->input(
				        	'mandate_ID', array(
						    'label'     => 'Mandate',
						    'options'   => $mandates_list,
						    'empty'     => __('-- Select a mandate --'),
						    'default'	=> null,
						    'required'  => true,
						    'style'	=> 'width:500px'
						));
				    ?>

					<?php
						echo $this->Form->input('cpty_ID', array(
							'label'		=>'Counterparty',
							'options'	=>array(),
							'empty' 	=> __('-- Select a counterparty --'),
							'required'  	=> true,
							'style'		=> 'width:500px'
						));
					?>

					<?php
						echo $this->Form->input('cmp_ID', array(
							'label'=>'Compartment',
							'options'=>array(),
							'empty' => __('-- Select a compartment --'),
							'required'  => true,
							'style'	 => 'width:500px'
						));
					?>

					<?php
						echo $this->Form->input('availability', array(
								'name'=> 'data[openreinvestform][availability_date]',
								'label'	=> 'Funds availability date',
								'data-date-format'	=> 'dd/mm/yy',
						));
					?>
					<button id="search" class="btn"><i class="icon-search"></i> Search</button>
				</div><br>

				<?php echo $this->Form->input(
				'openreinvestform.reinvestables', array(
					'type'		=> 'hidden',
					'label'     => false,
					'id'		=> 'openreinvestformReinvestables',
			)); ?>
				<div id="reinvestsTab"></div>
				<?php //echo $this->Form->end('') ?>
			</div>
			<div class="tab-pane <?php echo $tab2state; ?>" id="tab2">
		 		<?php echo $msg; ?>
				<?php if(isset($calculated) && $calculated): ?>

			 		<div class="span12" style="overflow:auto;">
						<div class="alert alert-block alert-warning">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							<strong>One more step :</strong> Please check the following details, you can then open the reinvestment group by clicking on 'Open'.
						</div>
						<?php if (isset($reinvestment)) {echo '<h5>Reinvestment group :</h5>'; $this->BootstrapTables->displayRawsById('reinvestment',$reinvestment);} ?>
						<?php if (isset($transactions)) {echo '<h5>Selected incoming transactions :</h5>'; $this->BootstrapTables->displayRawsById('reinvested_transactions',$transactions);} ?>
					</div>
					</br></br>
					<h5>Available funds :</h5>
					<div id="amountsAvailable">
						<table class="table table-bordered table-striped">
							<th> Account </th>
							<th> Amount available </th>
							<tr>
								<td>A : <?php echo UniformLib::uniform($accounts['Compartment']['accountA_IBAN'], 'accountA_IBAN'); ?></td>
								<td><?php echo UniformLib::uniform($amountsAvailable['amountInA'], 'amount_leftA'); ?> <?php echo UniformLib::uniform($accounts['AccountA']['ccy'], 'ccy') ?></td>
							</tr>
							<tr>
							    <td>B : <?php echo UniformLib::uniform($accounts['Compartment']['accountB_IBAN'], 'accountB_IBAN'); ?></td>
							    <td><?php echo UniformLib::uniform($amountsAvailable['amountInB'], 'amount_leftB'); ?> <?php echo UniformLib::uniform($accounts['AccountA']['ccy'], 'ccy') ?></td>
							</tr>
						</table>
						<?php
						echo $this->Html->link('Open Reinvestment', array('controller' => 'treasuryreinvestments', 'action' => 'open'), array('class' => 'btn btn-primary', 'id' => 'open_reinv'));
						?>
						<?php
						echo $this->Html->link('Cancel', array('controller' => 'treasuryreinvestments', 'action' => 'calculate'), array('class' => 'btn btn-warning'));
						?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
<div style="display:none;">
<?php

echo $this->Form->create('empty', array('url'=>'/treasury/treasuryajax/getcmpbymandate2'));
echo $this->Form->input('empty.empty', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();

echo $this->Form->create('getcmpbymandate2', array('url'=>'/treasury/treasuryajax/getcmpbymandate2'));
echo $this->Form->input('openreinvestform.mandate_ID', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();

echo $this->Form->create('getcptybymandate2', array('url'=>'/treasury/treasuryajax/getcptybymandate2'));
echo $this->Form->input('openreinvestform.mandate_ID', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();

echo $this->Form->create('getreinvestables', array('url'=>'/treasury/treasuryajax/getreinvestables'));
echo $this->Form->input('openreinvestform.mandate_ID', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('openreinvestform.availability_date', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('openreinvestform.reinvestables', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();
?>
</div>

<script>
	$(document).ready(function(){
		//$('#openreinvestformAvailability').datepicker({});
		$('#openreinvestformAvailability').datepicker({ dateFormat: "dd/mm/yy" });

		$("#search").click(function(e){
			e.preventDefault();
			return false;
		});

		$("#openreinvestformAvailability").keydown(function(e){
			if(e.keyCode == 13){
				e.preventDefault();
				return false;
			}
		});

		$("#openreinvestformCalculateForm").submit(function(e){
			// prevent double submit
            $('.btn-primary')[0].disabled = true;
            $('.btn-primary').attr('disabled', true);
		});

		$("#open_reinv").on('click', function(e)
		{
            $('#open_reinv')[0].disabled = true;
            $('#open_reinv').attr('disabled', true);
		});

		$(".btn-primary").click(function(e){
			// prevent double submit
			//console.dir(e);
            $('.btn-primary')[0].disabled = true;
            $('.btn-primary').attr('disabled', true);
		});

		$('#openreinvestformMandateID').change(function (e)
		{
			var mandate_id = $('#openreinvestformCalculateForm #openreinvestformMandateID').val();
			$('#getcptybymandate2CalculateForm #openreinvestformMandateID').val( mandate_id );
			var data = $('#getcptybymandate2CalculateForm').serialize();
			$.ajax({
				type: "POST",
				url: '/treasury/treasuryajax/getcptybymandate2',
				dataType: 'text', 
				data: data,
				async:true,
				success:function (data, textStatus) {
					$('#openreinvestformCptyID').html(data);
				}
			});
		});

		$('#openreinvestformMandateID').change(function (e)
		{
			var mandate_id = $('#openreinvestformCalculateForm #openreinvestformMandateID').val();
			$('#getcmpbymandate2CalculateForm #openreinvestformMandateID').val( mandate_id );
			var data = $('#getcmpbymandate2CalculateForm').serialize();
			$.ajax({
				type: "POST",
				url: '/treasury/treasuryajax/getcmpbymandate2',
				dataType: 'text', 
				data: data,
				async:true,
				success:function (data, textStatus) {
					$('#openreinvestformCmpID').html(data);
				}
			});
		});
		function reinvestsTab(e)
		{
			$('#getreinvestablesCalculateForm #openreinvestformMandateID').val( $('#openreinvestformMandateID').val() );
			$('#getreinvestablesCalculateForm #openreinvestformAvailabilityDate').val( $('#openreinvestformAvailability').val() );
			$('#getreinvestablesCalculateForm #openreinvestformReinvestables').val(  );
			var data = $('#getreinvestablesCalculateForm').serialize();
			$.ajax({
				type: "POST",
				url: '/treasury/treasuryajax/getreinvestables',
				dataType: 'text', 
				data: data,
				async:true,
				success:function (data, textStatus) {
					$('#reinvestsTab').html(data);
				}
			});
		};
		$('#openreinvestformAvailability').change(reinvestsTab);
		$('#openreinvestformCptyID').change(reinvestsTab);
		$('#openreinvestformCmpID').change(reinvestsTab);
		$('#openreinvestformMandateID').change(reinvestsTab);

	});
</script>