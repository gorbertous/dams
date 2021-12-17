<?php 
	$title = 'New risk group';
	$submit = 'Save';
	$id = '';
	$readonly = '';
	$from = '';
	if(!empty($_GET['from'])) $from=$_GET['from'];

	if(!empty($row)){
		$title = 'Edit risk group '.$row['CounterpartyGroup']['counterpartygroup_name'];
		$submit = 'Update';
		$id = $row['CounterpartyGroup']['counterpartygroup_ID'];
		$readonly = 'readonly';
	}else $row=null;
	
?><fieldset>
<legend><?php print $title ?></legend>
<div id="form" class = "well span12 noleftmargin">
	<?php echo $this->Form->create('CounterpartyGroup', array('data-id'=>$id)) ?>
		<?php
			echo $this->Form->input('counterpartygroup_ID', array(
					'type'		=> 'hidden',
					'label'		=> false,
					'value'		=> $id
				)
			);
			
			echo $this->Form->input('action_from',
				array(
					'type'		=> 'hidden',
					'label'		=> false,
					'value'		=> $from
				)
			);
		?>
		<div class="span4">
		<?php
			echo $this->Form->input('counterpartygroup_name', array(
					'label'		=> 'Name',
					'class'		=> 'span12',
					//'div'		=> 'span4',
					'default'	=> $row['CounterpartyGroup']['counterpartygroup_name']
				)
			);
		?>
		<?php
			echo $this->Form->input('head', array(
					'label'		=>  'Head Office',
					'class'		=> 'span12',
					//'div'		=> 'span4',
					'options'	=> !empty($counterparties)?$counterparties:array(),
					'default'	=> !empty($row['CounterpartyGroup']['head'])?$row['CounterpartyGroup']['head']:null,					
				)
			);
		?></div>
		<div class="span7">
			<label>Counterparties</label>
			<table id="counterpartiesTable" class="table table-bordered table-striped table-hover table-condensed">
				<tbody>
			<?php if(!empty($row['Counterparties'])) foreach($row['Counterparties'] as $counterparty): if(!empty($counterparty['cpty_name'])): ?>
				<tr>
					<td><?php print $counterparty['cpty_name'] ?></td>
					<td style="width: 45px">
						<a class="btn btn-mini delete_cpty" data-id-cpty="<?php echo $counterparty['cpty_ID']; ?>">Remove</a>
					</td>
				</tr>
			<?php endif; endforeach ?>
				<tr>
					<td>
						<?php echo $this->Form->input('add_counterparty', array(
							'label'		=>  false,
							'class'		=> 'span12',
							'div'		=> 'span12',
							'required'	=> false,
							'options'	=> !empty($counterparties)?array(''=>'---')+$counterparties:array(),
							'default'	=> null,
							'after'		=> '<span class="help-block addnewform text-right"><a href="'.Router::url('counterparty?from='.$this->here).'">+ Create a new Counterparty</a></span>'
						)); ?>
					</td>
					<td><input type="submit" class="btn btn-success" value="+ Add"></td>
				</tr>
				</tbody>
			</table>
		</div>

		<?php if(!empty($id)): ?>
		<div class="span11"></div>
		<div class="span11"><?php
			echo $this->Form->input('created',
				array(
					'type'		=> 'text',
					'label'		=> 'Created on',
					'disabled'	=> true,
					'class'		=> 'span12',
					'div'		=> 'span6',
					'default'	=> $row['CounterpartyGroup']['created']
				)
			);
		?>
		<?php
			echo $this->Form->input('modified',
				array(
					'type'		=> 'text',
					'label'		=> 'Last update',
					'disabled'	=> true,
					'class'		=> 'span12',
					'div'		=> 'span6',
					'required'	=> true,
					'default'	=> $row['CounterpartyGroup']['modified']
				)
			);
		?>
		</div><?php endif ?>		

		<div class="span11"></div>
		<div class="span11">
			<?php
				echo $this->Form->submit($submit,
					array(
						'id' 	=> 'createButton',
						'type' 	=> 'submit',
						'class' => 'btn btn-primary pull-right',
						'div'	=> false//array('class' => array('span11'))
					)
				);
			?>
			<?php print $this->Html->link(
			    '<i class="icon-chevron-left"></i> Back',
			    (!empty($from))?$from:'counterpartygroups',
			    array('class' => 'btn pull-right btn-small btn-cancel', 'style'=>'margin-right: 5px;', 'escape'=>false)
			); ?>
		</div>
		
	<?php echo $this->Form->end() ?>
</div>
</fieldset>
<style>
	.checkboxtabline{ padding-top: 5px !important; }
	.btn-add{ margin-top: 25px !important; }
	#counterpartiesTable tr td{ padding: 10px; }
</style>
<div style="display:none;">
<?php
echo $this->Form->create('delete_cptygrp', array('url'=>'/treasury/treasurystaticdatas/delete_counterpartygroup'));
echo $this->Form->input('CounterpartyGroup.del_counterpartygroup_ID', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('CounterpartyGroup.counterpartygroup_ID', array(
	'type' => 'hidden',
	'label'	=> false,
	'div'	=> false,
	'value' => $id,
));
echo $this->Form->end();
?>
</div>
<script>
$(document).ready(function(){
	$('.delete_cpty').click(function(e)
	{
		if (confirm("are you sure you want to delete this entry?"))
		{
			var id = $(e.target).attr('data-id-cpty');
			$("#delete_cptygrpCounterpartygroupForm #CounterpartyGroupDelCounterpartygroupID").val( id );
			$("#delete_cptygrpCounterpartygroupForm").submit();
		}
	});
});
</script>