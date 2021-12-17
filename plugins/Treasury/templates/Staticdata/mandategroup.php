<?php 
	$title = 'New Portfolio';
	$submit = 'Save';
	$id = '';
	$readonly = '';
	$from = '';
	//$hidden = 'block';
	if(!empty($_GET['from'])) $from=$_GET['from'];

	if(!empty($row)){
		$title = 'Edit Portfolio '.$row['MandateGroup']['mandategroup_name'];
		$submit = 'Update';
		$id = $row['MandateGroup']['id'];
		$readonly = 'readonly';
	}else $row=null;

	/*if(!empty($user_groups['treasuryRisk']) || in_array('treasuryRisk', $user_groups)){
		$readonly = 'readonly';
		$hidden = 'none';
	}*/
	
?><fieldset>
<legend><?php print $title ?></legend>
<div id="form" class = "well span12 noleftmargin">
	<?php echo $this->Form->create('MandateGroup', array('data-id'=>$id)) ?>
		<?php
			echo $this->Form->input('id', array(
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

			echo $this->Form->input('mandategroup_name', array(
					'label'		=> 'Name',
					'class'		=> 'span12',
					'div'		=> 'span4',
					//'readonly'	=> $readonly,
					'required'	=> true,
					'default'	=> $row['MandateGroup']['mandategroup_name']
				)
			);
		?>		
		<?php
			/*echo $this->Form->input('Mandates', array(
					'size'	=> 8,
					'class'		=> 'span12',
					'div'		=> 'span6',
					'options'	=> $mandates,
				)
			);*/
		?>
		<div class="span7">
			<label>Mandates</label>
			<table id="mandatesTable" class="table table-bordered table-striped table-hover table-condensed">
				<tbody>
			<?php if(!empty($row['Mandates'])) foreach($row['Mandates'] as $mandate): if(!empty($mandate['mandate_name'])): ?>
				<tr>
					<td><?php print $mandate['mandate_name'] ?></td>
					<td style="width: 45px">
						<a class="btn btn-mini delete_mandategrp" data-id-mandate="<?php echo $mandate['mandate_ID']; ?>">Remove</a>
					</td>
				</tr>
			<?php endif; endforeach ?>
				<tr>
					<td>
						<?php
							echo $this->Form->input('add_mandate', array(
									'label'		=>  false,//'Add a mandate',
									'class'		=> 'span12',
									'div'		=> 'span12',
									'required'	=> false,
									'options'	=> $mandates,
									'default'	=> null,
									'after'		=> '<span class="help-block addnewform text-right"><a href="'.Router::url('mandate?from='.$this->here).'">+ Create a new Mandate</a></span>'
								)
							);
						?>
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
					'default'	=> $row['MandateGroup']['created']
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
					'default'	=> $row['MandateGroup']['modified']
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
			    (!empty($from))?$from:'mandategroups',
			    array('class' => 'btn pull-right btn-small btn-cancel', 'style'=>'margin-right: 5px;', 'escape'=>false)
			); ?>
		</div>
		
	<?php echo $this->Form->end() ?>
</div>
</fieldset>
<style>
	.checkboxtabline{ padding-top: 5px !important; }
	.btn-add{ margin-top: 25px !important; }
	#mandatesTable tr td{ padding: 10px; }
</style>
<div style="display:none;">
<?php
echo $this->Form->create('delete_mandategrp', array('url'=>'/treasury/treasurystaticdatas/delete_mandategroup'));
echo $this->Form->input('MandateGroup.del_id', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));echo $this->Form->input('MandateGroup.id', array(
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
	$('.delete_mandategrp').click(function(e)
	{
		var id = $(e.target).attr('data-id-mandate');
		$("#delete_mandategrpMandategroupForm #MandateGroupDelId").val( id );
		$("#delete_mandategrpMandategroupForm").submit();
	});
});
</script>