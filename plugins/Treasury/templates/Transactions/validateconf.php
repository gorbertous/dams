<?php
	echo $this->Html->css('/treasury/css/dataTableSort');
	//echo $this->Html->css('/treasury/css/radio-fx');
    //echo $this->Html->script('/treasury/js/radio-fx');
    echo $this->Html->script('/treasury/js/radio-fx-replacement');
?>
<fieldset>
	<legend>Validate Confirmation</legend>
<div id="validate">
	<?php echo $this->Form->create('validateconf');
	
	echo $this->Form->input('validateconf.trn', array(
									'type' => 'hidden',
									'label'	=> false,
									'div'	=> false,
									'id'	=> 'validateConf',
								));
	?>
	<div id="validateConfSelect">
		<?php
			if(isset($instr) && count($instr)>0): ?>
				<table id="selectConfToValid" class="table table-bordered table-striped table-hover table-condensed">
					<thead>
						<th> Select </th>
						<th> TRN </th>
						<th> DI </th>
						<th> Amount </th>
						<th> Mandate </th>
						<th> Compartment </th>
					</thead>

					<tbody>
				<?php foreach ($instr as $key => $value): ?>
						<tr>
							<td>
								<?php /*<input class="origin_radio" type="radio" name="data[validateconf][trn]" value="<?php echo $key ?>" id="data[validateconf][trn]">*/ ?>
								<a href="<?php print Router::url(array(
	    'controller' => 'treasurytransactions', 'action' => 'validateInstrConf', $value['instr_num'])) ?>?from=o" class="btn btn-default">Select</a>
							</td>
							<td><?php echo UniformLib::uniform($key, 'trn') ?></td>
							<td><?php echo UniformLib::uniform($value['instr_num'], 'instr_num') ?></td>
							<td style="text-align:right;"><?php echo UniformLib::uniform($value['amount'],'amount').' '.UniformLib::uniform($value['ccy'], 'ccy') ?></td>
							<td><?php echo UniformLib::uniform($value['mandate_name'], 'mandate_name') ?></td>
							<td><?php echo UniformLib::uniform($value['cmp_name'], 'cmp_name') ?></td>
						</tr>
				<?php endforeach; ?>
					</tbody>
				</table>
			<?php else: ?>
				<div class="well alert-info">There are no deposits or rollovers at status Confirmation Received.</div>
			<?php endif; ?>
	</div>
	<?php echo $this->Form->end() ?>
	<div id="validateConfResult" class="radio-form" style="overflow:auto;">
	</div>
</div>
</fieldset>


<?php
	$this->Js->get('#selectConfToValid input[type=radio]')->event('click',
		$this->Js->request(
			array(
				'controller'=>'treasuryajax',
				'action'=>'callconfvalidate'
			),
			array(
				'update'=>'#validateConfResult',
				'async' => true,
				'method' => 'post',
				'dataExpression'=>true,
				'data'=> $this->Js->serializeForm(
					array(
						'isForm' => true,
						'inline' => true,
					)
				)
			)
		)
	);
?>

<?php
	echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/jquery.dataTables.js');
	echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/dataTables.bootstrap.js');
	echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/extras/TableTools/media/js/ZeroClipboard.js');
	//echo $this->Html->script('/theme/Cakestrap/js/libs/jquery-ui.min.js');
?>

<script type="text/javascript">
$(document).ready(function () {
	/*$("#selectConfToValid").dataTable({
		"sScrollY": "200px",
		"bPaginate": false,
	 	"bLengthChange": true,
	    "aLengthMenu": [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
	    "iDisplayLength" : 5,
	});*/

	$('body').bind('refreshSubcontent', function(e){
		$('#validateConfResult').html('');
	});
});


</script>