<?php
	//echo $this->Html->css('/treasury/css/DepositInstruction');
	echo $this->Html->css('/treasury/css/dataTableSort');
?>
<fieldset>
	<legend>Validate Instruction</legend>
<div id="validate" class="">
   	<?php echo $this->Form->create('validatedi') ?>
	<?php
	echo $this->Form->input(
		'validatedi.instr', array(
			'type'		=> "hidden",
			'label'     => false,
			'id'		=> 'validateDi',
	));
	?>
	<div id="validateDiSelect">
		<?php if(!empty($instr)): ?>
			<table id="selectInstrToValid" class="table table-bordered table-striped table-hover table-condensed">
				<thead>
					<tr>
					<th> Instruction </th>
					<th> Instrument Type </th>
					<th> Link </th>
					<th> MT 202 </th>
					<th> LM </th>
					<th> Type </th>
					<th> Mandate </th>
					<th> Counterparty </th>
	   				<th> Created By </th>
	   				<th> Creation Date </th>
					<th> Actions </th>
					</tr>
				</thead>

				<tbody>
				<?php foreach ($instr as $key => $value): ?>
					<tr>
						<td><?php echo $key ?></td>
						<td><?php echo $value['type'] ?></td>
						<td><?php
						clearstatcache();
						if (($value['type'] == "Deposit") && (file_exists(WWW ."/data/treasury/pdf/deposit_instruction_$key.pdf")))
						{
							echo $this->Html->link("<i class='icon-file'></i>", "/treasury/treasuryajax/download_file/1?file=/data/treasury/pdf/deposit_instruction_$key.pdf", array('escape' => false, 'target' => '_blank'));
						}
						elseif(($value['type'] == "Bond") &&(file_exists(WWW ."/data/treasury/pdf/bond_instruction_$key.pdf")))
						{
							echo $this->Html->link("<i class='icon-file'></i>", "/treasury/treasuryajax/download_file/1?file=/data/treasury/pdf/bond_instruction_$key.pdf", array('escape' => false, 'target' => '_blank'));
						}
						?></td>
						<td>
						<?php
						$tr_number = '';
						if (!empty($value['trn'][0]))
						{
							$tr_number = $value['trn'][0];
						}
						if(file_exists(WWW ."/data/treasury/swift/archives/$key.zip"))
						{
							echo $this->Html->link("<i class='icon-file'></i>", "/treasury/treasuryajax/download_file/1?file=/data/treasury/swift/archives/$key.zip", array('escape' => false, 'target' => '_blank'));
						}
						elseif(file_exists(WWW ."/data/treasury/swift/temp/$tr_number.txt"))
						{
							echo $this->Html->link("<i class='icon-file'></i>", "/treasury/treasuryajax/download_file/1?file=/data/treasury/swift/temp/$tr_number.txt", array('escape' => false, 'target' => '_blank'));
						}
						?>
						</td>
						<td>
						<?php if(file_exists(WWW ."/data/treasury/pdf/limit_breach_$key.pdf")): ?>
						<?php echo $this->Html->link("<i class='icon-file'></i>", "/treasury/treasuryajax/download_file/1?file=/data/treasury/pdf/limit_breach_$key.pdf", array('escape' => false, 'target' => '_blank')) ?>
						<?php endif ?>
						</td>
						<td><?php if ($value['type'] == "Bond") { echo "BI"; } else { echo "DI";} ?></td>
						<td><?php echo $value['mandate_name'] ?></td>
						<td><?php echo $value['cpty_name'] ?></td>
						<td><?php echo $value['created_by'] ?></td>
						<td><?php echo $value['created'] ?></td>
						<td>
                            <div class="btn-group" data-toggle="buttons-checkbox">
                            	<a title="Reject this Deposit Instruction" class="btn btn-danger confirmation" data-di-number="<?php echo $key; ?>" data-tr-number="<?php echo implode(',',$value['trn']); ?>" href="/treasury/treasurydepositinstructions/action_di/reject/<?php echo $key ?>">Reject</a>
                                <a title="Validate this Deposit Instruction" class="btn btn-success" href="/treasury/treasurydepositinstructions/action_di/validate/<?php echo $key ?>">Validate</a>
                            </div>
                        </td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		
		<?php else: ?>
			<div class="well alert-info">There are no transactions with status instruction created.</div>
		<?php endif; ?>
	</div>
	</form>
</div>
<div id="validateDiResult"></div>
</fieldset>

<?php
	echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/jquery.dataTables.js');
	echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/dataTables.bootstrap.js');
	echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/extras/TableTools/media/js/ZeroClipboard.js');
	//echo $this->Html->script('/theme/Cakestrap/js/libs/jquery-ui.min.js');
?>

<script type="text/javascript">
	$(document).ready(function () {

		$('.confirmation').click(function(e)
		{
			var DI = $(e.currentTarget).attr('data-di-number');
			var TRN = $(e.currentTarget).attr('data-tr-number');
			return confirm('Reject DI:'+DI+' with transactions '+TRN+'?');
		});
	});

	$('#selectInstrToValid tr').click(function () {
    	//$(this).find('td input:radio').prop('checked', true);
    	$(this).find('td input:radio:checked');
    	$('#selectInstrToValid tr').removeClass("active");
    	$(this).addClass("active");
	});
</script>




