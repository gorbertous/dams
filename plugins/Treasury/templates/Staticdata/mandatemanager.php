<?php 
	$title = 'New mandate manager';
	$submit = 'Save';
	$id = '';
	$readonly = '';
	$from = '';
	if(!empty($_GET['from'])) $from=$_GET['from'];

	if(!empty($row)){
		$title = 'Edit mandate managers '.$row['Mandate']['mandate_name'];
		$submit = 'Update';
		$id = $row['Mandate']['mandate_ID'];
		$readonly = 'readonly';
	}else $row=null;
	
?><fieldset>
<legend><?php print $title ?></legend>
<div id="form" class = "well span12 noleftmargin">
	<?php echo $this->Form->create('MandateManager', array('data-id'=>$id)) ?>
		<?php echo $this->Form->input('mandate_ID', array('type'=>'hidden', 'default'=>$id)) ?>
		<table id="mandateManagersTable" class="table table-bordered table-striped table-hover table-condensed">
			<tbody>
		<?php if(!empty($row['Managers'])) foreach($row['Managers'] as $manager): ?>
			<tr>
				<td><?php print $manager['name'] ?></td>
				<td><?php print $manager['email'] ?></td>
				<td style="width: 45px;"><a class="btn btn-mini delete_manager" data-id-manager="<?php print $manager['id'] ?>">remove</a></td>				
			</tr>
		<?php endforeach ?>
			<tr><td colspan="3" class="separ">Add a manager</td></tr>
			<tr>
				<td>
					<?php
						echo $this->Form->input('name', array(
								'label'		=>  'Name',
								'class'		=> 'span12',
								'div'		=> 'span12',
								'default'	=> null,
							)
						);
					?>
				</td>
				<td><?php
						echo $this->Form->input('email', array(
								'label'		=>  'Email',
								'class'		=> 'span12',
								'div'		=> 'span12',
								'default'	=> null,
							)
						);
				?></td>
				<td><input type="submit" class="btn btn-success btn-add" value="+ Add"></td>
			</tr>
			</tbody>
		</table>

		<div class="span11"></div>
		<div class="span11">
			<?php
				/*echo $this->Form->submit($submit,
					array(
						'id' 	=> 'createButton',
						'type' 	=> 'submit',
						'class' => 'btn btn-primary pull-right',
						'div'	=> false//array('class' => array('span11'))
					)
				);*/
			?>
			<?php print $this->Html->link(
			    '<i class="icon-chevron-left"></i> Back',
			    (!empty($from))?$from:'mandatemanagers',
			    array('class' => 'btn pull-right btn-small btn-cancel', 'style'=>'margin-right: 5px;', 'escape'=>false)
			); ?>
		</div>
		
	<?php echo $this->Form->end() ?>
</div>
</fieldset>
<style>
	#mandateManagersTable td{ padding: 10px; }
	#mandateManagersTable .separ{ font-size: 15px; font-weight: bold; padding: 20px 10px 10px; }
	#mandateManagersTable .btn-add{ margin-top: 25px; }

</style>
<div style="display:none;">
<?php
echo $this->Form->create('delmanager', array('url'=>'/treasury/treasurystaticdatas/delete_manager'));
echo $this->Form->input('Mandate.remove_id', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Mandate.mandate_id', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
	'value'	=> $id,
));
echo $this->Form->end();
?>
</div>
<script>
$(document).ready(function()
{
	$('.delete_manager').click(function (e)
	{
		if (confirm("are you sure you want to delete this entry?"))
		{
			var id = $(e.target).attr('data-id-manager');
			$("#delmanagerMandatemanagerForm #MandateRemoveId").val( id );
			$("#delmanagerMandatemanagerForm").submit();
		}
	});
});
</script>