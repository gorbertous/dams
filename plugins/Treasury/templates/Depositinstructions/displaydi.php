<?php 
  //echo $this->Html->css('/treasury/css/dataTableSort');
?>
<?php 
	// FILTERS
	echo $this->Form->create('filters', array('id'=>'FiltersForm', 'class'=>'form-inline')); ?>
	<?php echo $this->Form->input('Instruction.instr_num', array(
		'label'		=> '#&nbsp;', 'div' => false,
		'class' => 'instr_num',
		'type'		=> 'number',
		'placeholder' => 'Number',
		'default'	=> $this->Session->read('Form.data.Instruction.instr_num')
	)); ?>
	<?php echo $this->Form->input('Instruction.instr_type', array(
		'label'		=> false, 'div' => false,
		'class' => 'instr_type',
		'type' => 'select',
		'empty' 	=> '-- Type --',
		'options' 	=> $instr_types,
		'default'	=> $this->Session->read('Form.data.Instruction.instr_type')
	)); ?>

	<?php echo $this->Form->input('Instruction.instr_date_month', array(
		'label'		=> false, 'div' => false,
		'class' => 'instr_date_month',
		'type' => 'select',
		'empty' 	=> '-- Month --',
		'options' 	=> $months,
		'default'	=> $this->Session->read('Form.data.Instruction.instr_date_month')
	)); ?>
	<?php echo $this->Form->input('Instruction.instr_date_year', array(
		'label'		=> false, 'div' => false,
		'class' => 'instr_date_year',
		'type' => 'select',
		'empty' 	=> '-- Year --',
		'options' 	=> $years,
		'default'	=> $this->Session->read('Form.data.Instruction.instr_date_year')
	)); ?>

	<?php echo $this->Form->input('Instruction.cpty_ID', array(
		'label'		=> false, 'div' => false,
		'class' => 'cpty_ID',
		'type' => 'select',
		'empty' 	=> '-- Counterparty --',
		'options' 	=> $instr_counterparties,
		'default'	=> $this->Session->read('Form.data.Instruction.cpty_ID')
	)); ?>
	<?php echo $this->Form->input('Instruction.mandate_ID', array(
		'label'		=> false, 'div' => false,
		'class' => 'mandate_ID',
		'type' => 'select',
		'empty' 	=> '-- Mandate --',
		'options' 	=> $instr_mandates,
		'default'	=> $this->Session->read('Form.data.Instruction.mandate_ID')
	)); ?>
	
	<?php echo $this->Form->end(); ?>

<table id="downloadInstruction" class="table table-bordered table-striped table-hover table-condensed">
	<thead>
		<th class="instr_num"><?php echo $this->Paginator->sort('instr_num', 'Instruction Number', array('url' => array('page' => 1))) ?></th>
		<th class="instr_type"><?php echo $this->Paginator->sort('instr_type', 'Instrument Type', array('url' => array('page' => 1))) ?></th>
		<th class="instr_type"><?php echo $this->Paginator->sort('instr_type', 'Instruction Type', array('url' => array('page' => 1))) ?></th>
		<th class="instr_status"><?php echo $this->Paginator->sort('instr_status', 'Instruction Status', array('url' => array('page' => 1))) ?></th>
		<th class="date"><?php echo $this->Paginator->sort('instr_date', 'Date', array('url' => array('page' => 1))) ?></th>
		<th class="di_link">Link</th>
		<th class="di_link">Signed DI</th>
		<th class="di_link">Trade request</th>
		<th class="di_confirmation"><?php echo $this->Paginator->sort('confirmation_file', 'Confirmation', array('url' => array('page' => 1))) ?></th>
		<th class="di_MT202">MT 202</th>
		<th class="di_link">LM Link</th>
		<th class="mandate_name"><?php echo $this->Paginator->sort('Mandate.mandate_name', 'Mandate', array('url' => array('page' => 1))) ?></th>
		<th class="cpty_name"><?php echo $this->Paginator->sort('Counterparty.cpty_name', 'Counterparty', array('url' => array('page' => 1))) ?></th>
		<?php if(!empty($user_groups) && !in_array('Auditors', $user_groups)): ?>
			<th class="actions">Actions</th>
		<?php endif ?>
	</thead>
	<tbody>
		<?php foreach ($instructions as $key => $value): ?>
			<?php 
				/*$date = '';
				if(!empty($value['transaction'])){
					$trs = reset($value['transaction']);
					if(!empty($trs['commencement_date'])) $date=date('d/m/Y', strtotime($trs['commencement_date']));
				}
				//if(empty($date)) $date = print_r($value['transaction'], true);*/
			?>
			<tr id="rowInstr<?php echo $value['Instruction']['instr_num']; ?>">
				<td><?php echo UniformLib::uniform($value['Instruction']['instr_num'], 'instr_num'); ?></td>
			    <td><?php
				$instr_type = 'Deposit';
				if ($value['Instruction']['instr_type'] == "Bond")
				{
					$instr_type = "Bond";
				}
				echo UniformLib::uniform($instr_type, 'instr_type'); ?></td>
				<td><?php 
				$instruc_type = $value['Instruction']['instr_type'];
				if ($value['Instruction']['instr_type'] == "Bond")
				{
					$instruc_type = "BI";
				}
				echo UniformLib::uniform($instruc_type, 'instr_type'); ?></td>
				<td><?php echo UniformLib::uniform($value['Instruction']['instr_status'], 'instr_status'); ?></td>
				<td><?php print UniformLib::uniform($value['Instruction']['instr_date'], 'instr_date') ?></td>
				<td><?php 
				if ($value['Instruction']['instr_type'] == "Bond")
				{
					echo $this->Html->link("<i class='icon-file'></i>", "/treasury/treasuryajax/download_file/1?file=/data/treasury/pdf/bond_instruction_".$value['Instruction']['instr_num'].".pdf", array('escape' => false, 'target' => '_blank')) ;
				}
				else
				{
					echo $this->Html->link("<i class='icon-file'></i>", "/treasury/treasuryajax/download_file/1?file=/data/treasury/pdf/deposit_instruction_".$value['Instruction']['instr_num'].".pdf", array('escape' => false, 'target' => '_blank')) ;
				}
				
				?>	
				</td>
				
				<td class="signed_di">
				<?php
				$perms = $this->Permission->getPermissions();

					if (!$perms['is_risk']): ?>
						<?php if(empty($value['Instruction']['signedDI_file'])): ?>
							<?php if(!empty($user_groups) && !in_array('Auditors', $user_groups)): ?>
								<a href="#" data-num="<?php echo $value['Instruction']['instr_num']; ?>" class="btn btn-mini btn-attach-signedDI">Attach</a>
							<?php endif ?>
						<?php else: ?>
							<?php echo $this->Html->link("<i class='icon-file'></i>", "/treasury/treasuryajax/download_file/1?file=/data/treasury/pdf/".$value['Instruction']['signedDI_file'], array('escape' => false, 'target' => '_blank')) ?>
							<?php if(!empty($user_groups) && !in_array('Auditors', $user_groups)): ?>
								<a href="#" data-num="<?php echo $value['Instruction']['instr_num']; ?>" class="btn-attach-remove-signedDI">(remove)</a>
							<?php endif ?>
						<?php endif ?>
					<?php else: ?>
						<?php if(!empty($value['Instruction']['signedDI_file'])): ?>
							<?php echo $this->Html->link("<i class='icon-file'></i>", "/treasury/treasuryajax/download_file/1?file=/data/treasury/pdf/".$value['Instruction']['signedDI_file'], array('escape' => false, 'target' => '_blank')) ?>
						<?php endif ?>
					<?php endif ?>
				</td>

				<td class="trade_request">
				<?php if (!$perms['is_risk']): ?>
						<?php if(empty($value['Instruction']['traderequest_file'])): ?>
							<?php if(!empty($user_groups) && !in_array('Auditors', $user_groups)): ?>
								<a href="#" data-num="<?php echo $value['Instruction']['instr_num']; ?>" class="btn btn-mini btn-attach-trade_request">Attach</a>
							<?php endif ?>
						<?php else: ?>
							<?php echo $this->Html->link("<i class='icon-file'></i>", "/treasury/treasuryajax/download_file/1?file=/data/treasury/pdf/".$value['Instruction']['traderequest_file'], array('escape' => false, 'target' => '_blank')) ?>
							<?php if(!empty($user_groups) && !in_array('Auditors', $user_groups)): ?>
								<a href="#" data-num="<?php echo $value['Instruction']['instr_num']; ?>" class="btn-attach-remove-trade_request">(remove)</a>
							<?php endif ?>
						<?php endif ?>
					<?php else: ?>
						<?php if(!empty($value['Instruction']['traderequest_file'])): ?>
							<?php echo $this->Html->link("<i class='icon-file'></i>", "/treasury/treasuryajax/download_file/1?file=/data/treasury/pdf/".$value['Instruction']['traderequest_file'], array('escape' => false, 'target' => '_blank')) ?>
						<?php endif ?>
					<?php endif ?>
				</td>

				<td>
					<?php
					if (!$perms['is_risk']): ?>
						<?php if(empty($value['Instruction']['confirmation_file'])): ?>
							<?php if(!empty($user_groups) && !in_array('Auditors', $user_groups)): ?>
								<a href="#" data-num="<?php echo $value['Instruction']['instr_num']; ?>" class="btn btn-mini btn-attach-confirmation">Attach</a>
							<?php endif ?>
						<?php else: ?>
							<?php echo $this->Html->link("<i class='icon-file'></i>", "/treasury/treasuryajax/download_file/1?file=/data/treasury/pdf/".$value['Instruction']['confirmation_file'], array('escape' => false, 'target' => '_blank')) ?>
							<?php if(!empty($user_groups) && !in_array('Auditors', $user_groups)): ?>
								<a href="#" data-num="<?php echo $value['Instruction']['instr_num']; ?>" class="btn-attach-remove-confirmation">(remove)</a>
							<?php endif ?>
						<?php endif ?>
					<?php else: ?>
						<?php if(!empty($value['Instruction']['confirmation_file'])): ?>
							<?php echo $this->Html->link("<i class='icon-file'></i>", "/treasury/treasuryajax/download_file/1?file=/data/treasury/pdf/".$value['Instruction']['confirmation_file'], array('escape' => false, 'target' => '_blank')) ?>
						<?php endif ?>
					<?php endif ?>
				</td>
				<td><?php if(file_exists(WWW ."/data/treasury/swift/archives/".$value['Instruction']['instr_num'].".zip")): ?>
				<?php echo $this->Html->link("<i class='icon-file'></i>", "/treasury/treasuryajax/download_file/1?file=/data/treasury/swift/archives/".$value['Instruction']['instr_num'].".zip", array('escape' => false, 'target' => '_blank')) ?>	
				<?php elseif(!empty($value['Transactions'][0]) && file_exists(WWW ."/data/treasury/swift/temp/".$value['Transactions'][0]['tr_number'].".txt")): ?>
				<?php echo $this->Html->link("<i class='icon-file'></i>", "/treasury/treasuryajax/download_file/1?file=/data/treasury/swift/archives/".$value['Instruction']['instr_num'].".zip", array('escape' => false, 'target' => '_blank')) ?>	
				<?php endif ?>
				</td>
				<td><?php if(file_exists(WWW ."/data/treasury/pdf/limit_breach_".$value['Instruction']['instr_num'].".pdf")): ?>
				<?php echo $this->Html->link("<i class='icon-file'></i>", "/treasury/treasuryajax/download_file/1?file=/data/treasury/pdf/limit_breach_".$value['Instruction']['instr_num'].".pdf", array('escape' => false, 'target' => '_blank')) ?>	
				<?php endif ?>
				</td>
				<td><?php echo UniformLib::uniform($value['Mandate']['mandate_name'], 'mandate_name'); ?></td>
				<td><?php echo UniformLib::uniform($value['Counterparty']['cpty_name'], 'cpty_name'); ?></td>
				<?php if(!empty($user_groups) && !in_array('Auditors', $user_groups)): ?>
					<td>
						<?php
						$tr_nums = array();
						foreach($value['Transactions'] as $tr_line)
						{
							$tr_nums[] = $tr_line['tr_number'];
						}
						$tr_nums = implode(',', $tr_nums);
						if($value['Instruction']['instr_status']=='Created' && (in_array('Admin', $user_groups) || in_array ('Treasury Validator', $user_groups)) ): ?>
							<?php
								$validatelink = $rejectlink = '';
								if(!empty($value['Instruction']['instr_type'])){
									//if(strtolower($value['Instruction']['instr_type'])=='di'){
										$rejectlink = Router::url(array('controller' => 'treasurydepositinstructions', 'action' => 'action_di', 'reject', $value['Instruction']['instr_num'], 'disp'));
										$validatelink = Router::url(array('controller' => 'treasurydepositinstructions', 'action' => 'action_di', 'validate', $value['Instruction']['instr_num']));
									/*}elseif(strtolower($value['Instruction']['instr_type'])=='call'){
										$validatelink = $rejectlink = Router::url(array('controller' => 'treasurytransactions', 'action' => 'callconf'));
									}elseif(strtolower($value['Instruction']['instr_type'])=='break'){
										$validatelink = $rejectlink = Router::url(array('controller' => 'treasurytransactions', 'action' => 'breakconf'));
									}*/
								}
							?>
							<div class="btn-group" data-toggle="buttons-checkbox">
								<div class="btn disabled">Instruction: </div>
		                        <a title="Reject this Instruction" class="btn btn-danger confirmation" data-tr-number="<?php echo $tr_nums; ?>" data-di-number="<?php echo $value['Instruction']['instr_num'];?>" href="<?php print $rejectlink ?>">Reject</a>
								<a title="Validate this Instruction" class="btn btn-success" href="<?php print $validatelink ?>">Validate</a>
		                    </div>
		                <?php endif ?>
						
						<?php if($value['Instruction']['instr_status']=='Created' && (in_array('Admin', $user_groups) || in_array ('Treasury Operator', $user_groups)) ): ?>
							<?php
								$rejectlink = '';
								if(!empty($value['Instruction']['instr_type'])){
										$rejectlink = Router::url(array('controller' => 'treasurydepositinstructions', 'action' => 'action_di', 'reject', $value['Instruction']['instr_num'], 'disp'));
								}
							?>
							<div class="btn-group" data-toggle="buttons-checkbox">
								<div class="btn disabled">Instruction: </div>
		                        <a title="Reject this Instruction" class="btn btn-danger confirmation" data-tr-number="<?php echo $tr_nums; ?>" data-di-number="<?php echo $value['Instruction']['instr_num'];?>" href="<?php print $rejectlink ?>">Reject</a>
		                    </div>
		                <?php endif ?>

	                    <?php if($value['Instruction']['instr_status']=='Sent'): ?>
	                    	<?php
								$validatelink = $registerlink = '';
								if(!empty($value['Instruction']['instr_type'])){
									if(strtolower($value['Instruction']['instr_type'])=='di'){
										$registerlink = Router::url(array('controller' => 'treasurytransactions', 'action' => 'registerInstrConf', $value['Instruction']['instr_num']));
										$validatelink = Router::url(array('controller' => 'treasurytransactions', 'action' => 'validateInstrConf', $value['Instruction']['instr_num']));
									}elseif(strtolower($value['Instruction']['instr_type'])=='call'){
										$registerlink = Router::url(array('controller' => 'treasurytransactions', 'action' => 'callconf'));
										$validatelink = Router::url(array('controller' => 'treasurytransactions', 'action' => 'validatecallconf'));
									}elseif(strtolower($value['Instruction']['instr_type'])=='break'){
										$registerlink = Router::url(array('controller' => 'treasurytransactions', 'action' => 'breakconf'));
										$validatelink = Router::url(array('controller' => 'treasurytransactions', 'action' => 'validatebreakconf'));
									}
								}
							?>	
	                    	<div class="btn-group" data-toggle="buttons-checkbox">
	                    	<?php $grouphead = '<div class="btn disabled">Confirmation: </div>'; ?>
	                    	<?php if (!$perms['is_risk']): ?>
		                    	<?php if(!empty($value['Instruction']['trn_need_confirmation']) && (in_array('Admin', $user_groups) || in_array ("Treasury Operator", $user_groups))): ?>
		                    		<?php if($grouphead){ print $grouphead; $grouphead=''; } ?>
			                    	<a title="Register this Confirmation" class="btn btn-primary" href="<?php print $registerlink ?>">Register</a>
			                    <?php endif ?>

			                    <?php if(!empty($value['Instruction']['trn_need_validation']) && (in_array('Admin', $user_groups) || in_array ('Treasury Validator', $user_groups))): ?>
			                    	<?php if($grouphead){ print $grouphead; $grouphead=''; } ?>
									<a title="Validate this Confirmation" class="btn btn-success" href="<?php print $validatelink ?>">Validate</a>
								<?php endif ?>
							<?php endif ?>
							</div>

	                    <?php endif ?>

					</td>
				<?php endif ?>
			</tr>
			<tr class="uploadrow uploadrow-<?php echo $value['Instruction']['instr_num']; ?><?php if(!empty($_GET['attach']) && $_GET['attach']==$value['Instruction']['instr_num']) print ' open' ?>">
				<td colspan="9">					
					<?php echo $this->Form->create('confirmation', array("url" => 'displaydi', 'enctype' => 'multipart/form-data', 'class'=>'form-inline')) ?>
						<?php 
							echo $this->Form->input('instr_num',
								array(
									'div'=>false,
									'type'=>'hidden',
									'value'	=> $value['Instruction']['instr_num'],
								)
							); 
						?>
						<?php 
							echo $this->Form->input('file_uploaded',
								array(
									'div'=>false,
									'type'=>'text',
									'style' => "display: none",
									'label' => false,
								)
							); 
						?>
						<?php 
							echo $this->Form->input('attachment',
								array(
									'div'=>false,
									'type'=>'file',
									'label'	=> false,
									'required'	=> 'required',
								)
							); 
						?>
						<?php
							echo $this->Form->submit('submit',
								array(
									'div'=>false,
									'type' 	=> 'submit',
									'class' => 'btn btn-primary',
								)
							);
						?>
					<?php echo $this->Form->end() ?>
				</td>
			</tr>
			<tr class="remove-uploadrow remove-uploadrow-<?php echo $value['Instruction']['instr_num']; ?><?php if(!empty($_GET['attach']) && !empty($_GET['remove_attach']) && $_GET['remove_attach']==$value['Instruction']['instr_num']) print ' open' ?>">
				<td colspan="9">					
					<?php echo $this->Form->create('remove_confirmation', array("url" => 'displaydi', 'enctype' => 'multipart/form-data', 'class'=>'form-inline')) ?>
						<p>The document will be definitely erased. Do you really want to continue?</p>
						<?php 
							echo $this->Form->input('instr_num',
								array(
									'div'=>false,
									'type'=>'hidden',
									'value'	=> $value['Instruction']['instr_num'],
								)
							); 
						?>
						<?php 
							echo $this->Form->input('file_uploaded',
								array(
									'div'=>false,
									'type'=>'text',
									'style' => "display: none",
									'label' => false,
								)
							); 
						?>
						<?php
							echo $this->Form->submit('Confirm',
								array(
									'div'=>false,
									'type' 	=> 'submit',
									'class' => 'btn btn-danger',
								)
							);
						?>
					<?php echo $this->Form->end() ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php echo $this->Paginator->counter(
    'Page {:page} of {:pages}, showing {:current} records out of
     {:count} total, starting on record {:start}, ending on {:end}'
); ?>
	<div class="pagination">
	    <ul>
	        <?php 
	            echo $this->Paginator->prev( '<<', array( 'class' => '', 'tag' => 'li' ), null, array( 'class' => 'disabled myclass', 'tag' => 'li' ) );
	            echo $this->Paginator->numbers( array( 'tag' => 'li', 'separator' => '', 'currentClass' => 'disabled myclass' ) );
	            echo $this->Paginator->next( '>>', array( 'class' => '', 'tag' => 'li' ), null, array( 'class' => 'disabled myclass', 'tag' => 'li' ) );
	        ?>
	    </ul>
	</div>

	<style type="text/css">

		#FiltersForm .instr_num{ width: 50px; height: 20px; }
		#FiltersForm .instr_type{ width: 95px; }
		#FiltersForm .instr_date_year,
		#FiltersForm .instr_date_month{ width: 105px; }
		#FiltersForm .mandate_ID{ float: right; margin-right: 3px; }
		#FiltersForm .cpty_ID{ float: right; }

		.uploadrow{ display: none; }
		.uploadrow, .remove-uploadrow{ display: none; }
		.uploadrow.open, .remove-uploadrow.open{ display: table-row; }
		.uploadrow form, .remove-uploadrow form{ margin-left: 40%; margin-top: 20px; }

		.btn-attach-remove-confirmation,.btn-attach-remove-signedDI, .btn-attach-remove-trade_request { font-size: 10px; color: #aaa; }

	</style>
<?php 
  /*echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/jquery.dataTables.js');
  echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/dataTables.bootstrap.js');
  echo $this->Html->script('/treasury/js/ColumnFilter/media/js/jquery-ui.js'); 
  echo $this->Html->script('/treasury/js/ColumnFilter/media/js/jquery.dataTables.columnFilter.js');
?>
<script type="text/javascript">
$(document).ready(function(){
	// Grid SAS _webout table1 (Deposits and Rollovers)
    $('#downloadInstruction').dataTable({
    	'aaSorting': [[ 0, "desc" ]]
	});
});
</script>*/ ?>
<script>
	$(document).ready(function() {
		$("#FiltersForm input, #FiltersForm select").change(function(event) {
			$("#FiltersForm").submit();
		});

		$('.btn-attach-confirmation').bind('click', function(e){
			e.preventDefault();
			$('.uploadrow').removeClass('open');
			$('.remove-uploadrow').removeClass('open');
			$('.btn-attach-remove').show();
			if($(this).attr('data-num')){
				$('.uploadrow-'+$(this).attr('data-num')).addClass('open');
				$('.uploadrow-'+$(this).attr('data-num')).find('#confirmationFileUploaded').val('confirmation');
			}
		});
		$('.btn-attach-signedDI').bind('click', function(e){
			e.preventDefault();
			$('.uploadrow').removeClass('open');
			$('.remove-uploadrow').removeClass('open');
			$('.btn-attach-remove').show();
			if($(this).attr('data-num')){
				$('.uploadrow-'+$(this).attr('data-num')).addClass('open');
				$('.uploadrow-'+$(this).attr('data-num')).find('#confirmationFileUploaded').val('signedDI');
			}
		});
		$('.btn-attach-trade_request').bind('click', function(e){
			e.preventDefault();
			$('.uploadrow').removeClass('open');
			$('.remove-uploadrow').removeClass('open');
			$('.btn-attach-remove').show();
			if($(this).attr('data-num')){
				$('.uploadrow-'+$(this).attr('data-num')).addClass('open');
				$('.uploadrow-'+$(this).attr('data-num')).find('#confirmationFileUploaded').val('trade_request');
			}
		});
		$('.btn-attach-remove-confirmation').bind('click', function(e){
			e.preventDefault();
			$('.uploadrow').removeClass('open');
			$('.remove-uploadrow').removeClass('open');
			$('.btn-attach-remove').show();
			$(this).hide();
			if($(this).attr('data-num')){
				$('.remove-uploadrow-'+$(this).attr('data-num')).addClass('open');
				$('.remove-uploadrow-'+$(this).attr('data-num')).find('#remove_confirmationFileUploaded').val('confirmation');
			}
		});
		$('.btn-attach-remove-trade_request').bind('click', function(e){
			e.preventDefault();
			$('.uploadrow').removeClass('open');
			$('.remove-uploadrow').removeClass('open');
			$('.btn-attach-remove').show();
			$(this).hide();
			if($(this).attr('data-num')){
				$('.remove-uploadrow-'+$(this).attr('data-num')).addClass('open');
				$('.remove-uploadrow-'+$(this).attr('data-num')).find('#remove_confirmationFileUploaded').val('trade_request');
			}
		});
		$('.btn-attach-remove-signedDI').bind('click', function(e){
			e.preventDefault();
			$('.uploadrow').removeClass('open');
			$('.remove-uploadrow').removeClass('open');
			$('.btn-attach-remove').show();
			$(this).hide();
			if($(this).attr('data-num')){
				$('.remove-uploadrow-'+$(this).attr('data-num')).addClass('open');
				$('.remove-uploadrow-'+$(this).attr('data-num')).find('#remove_confirmationFileUploaded').val('signedDI');
			}
		});
		
		$('.confirmation').click(function(e)
		{
			var DI = $(e.currentTarget).attr('data-di-number');
			var TRN = $(e.currentTarget).attr('data-tr-number');
			return confirm('Reject DI:'+DI+' with transactions '+TRN+'?');
		});
	});
</script>
