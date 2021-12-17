<?php
	echo $this->Html->css('/treasury/css/dataTableSort');
	//echo $this->Html->css('/treasury/css/radio-fx');
	//echo $this->Html->script('/treasury/js/radio-fx');
	echo $this->Html->script('/treasury/js/radio-fx-replacement');
?>
<div class="tabbable">
	<ul class="nav nav-tabs">
		<li class=" <?php echo $tab1state ;?> ">
		<a href="#tab1" data-toggle="tab">Close Reinvestment Form</a>
	</li>
		<li class =" <?php echo $tab2state ;?>" >
			<a href="#tab2" data-toggle="tab">Result</a>
		</li>
	</ul>
	<div class="tab-content">
		<div class = "tab-pane <?php echo $tab1state; ?>" id = "tab1">
	    	<?php if($openReinvNo > 0): ?>
	    		<?php echo $this->Form->create('closereinvform') ; ?>

		    	<table id="selectReinvGroup" class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th> Select </th>
			    			<th> Reinv  </th>
							<th> Availibility date </th>
							<th> Funds left A </th>
							<th> Funds left B </th>
							<th> Mandate </th>
							<th> Compartment </th>
							<th> Counterparty </th>
						</tr>
					</thead>
					<tbody>
	    			<?php if (! is_string($reinvGroupOpts)) foreach ($reinvGroupOpts as $key => $value): ?>
	    				<tr>
	    					<td>
								<?php
								echo $this->Form->input('closereinvform.reinv_group', array(
									'type' => 'radio',
									'div'	=> false,
									'options'	=> array($key),
									'data-value'	=> $key,
									'class'	=> "origin_radio",
								));
								?>
	    					</td>
	    					<td><?php echo $key ?></td>
	    					<td><?php echo UniformLib::uniform($value['availability_date'], 'availability_date') ?></td>
	    					<td style="text-align:right;"><?php echo UniformLib::uniform($value['amount_leftA'], 'amount_leftA').' '.UniformLib::uniform($value['ccy'], 'ccy') ?></td>
	    					<td style="text-align:right;"><?php echo UniformLib::uniform($value['amount_leftB'], 'amount_leftB').' '.UniformLib::uniform($value['ccy'], 'ccy') ?></td>
	    					<td><?php echo UniformLib::uniform($value['mandate_name'], 'mandate_name') ?></td>
	    					<td><?php echo UniformLib::uniform($value['cmp_name'], 'cmp_name') ?></td>
	    					<td><?php echo UniformLib::uniform($value['cpty_name'], 'cpty_name') ?></td>
	    				</tr>
	    			<?php endforeach; ?>
	    			</tbody>
	    		</table>
	    		<br>
	    		<?php if (is_string($reinvGroupOpts)) echo $reinvGroupOpts; ?>
	    		<div class="radio-form">
		    		<div id="reinvInfo" class=""></div>

			    	<div id="submit">
					<?php echo $this->Form->input('closereinvform.reinv_group', array(
							'type' => 'text',
							'label'	=> false,
							'div'	=> false,
							'style'	=> 'display: none;',
						));
						echo $this->Form->submit(__('Close reinvestment'), array('class' => 'btn btn-primary')); ?>
					</div>
				</div>
			<?php echo $this->Form->end() ?>
			<?php else: ?>
				<div class="alert alert-info">There is not any open reinvestment.</div>
			<?php endif; ?>
		</div>

    	<div class = "tab-pane <?php echo $tab2state; ?>" id = "tab2">
      		<div class="">
      				<div id="results" style="overflow:auto;">
     					<?php echo $msg;  echo (isset($closed_reinv))? $this->BootstrapTables->displayRawsById('closed_reinv',$closed_reinv):'' ?>
     				</div>
     		</div>
    	</div>
  	</div>
</div>
<div style="display:none;">
<?php

echo $this->Form->create('getreinvinfo', array('url'=>'/treasury/treasuryajax/getreinvinfo'));
echo $this->Form->input('closereinvform.reinv_group', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();
?>
</div>

<?php
	echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/jquery.dataTables.js');
	echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/dataTables.bootstrap.js');
	echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/extras/TableTools/media/js/ZeroClipboard.js');
	//echo $this->Html->script('/theme/Cakestrap/js/libs/jquery-ui.min.js');
?>

<script type="text/javascript">
$(document).ready(function () {
	$("#submit").hide();
	$("input[type = radio]").click(function() {
          $("#submit").show();
   	});
	$('label[for="closereinvformReinvGroup0"]').remove();//label of radio

	$('#selectReinvGroup input[type=radio]').change(function (e)
	{
		var reinv_group = $(e.currentTarget).attr('data-value');
		$('#closereinvformReinvGroup').val(reinv_group);
		$('#getreinvinfoCloseForm #closereinvformReinvGroup').val( reinv_group );
		var data = $('#getreinvinfoCloseForm').serialize();
		$.ajax({
			type: "POST",
			url: '/treasury/treasuryajax/getreinvinfo',
			dataType: 'text', 
			data: data,
			async:true,
			success:function (data, textStatus) {
				$('#reinvInfo').html(data);
			}
		});
	});

});

</script>
