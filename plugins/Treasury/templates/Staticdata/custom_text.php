<?php 
	$title = 'New settlement';
	$submit = 'Save';
	$id = '';
	$readonly = '';
	$from = '';
	if(!empty($_GET['from'])) $from=$_GET['from'];

	if(!empty($row)){
		$title = 'Edit settlement '.$row['CustomText']['custom_id'];
		$submit = 'Update';
		$id = $row['CustomText']['custom_id'];
		$readonly = 'readonly';
	}else $row=null;
	
?><fieldset>
<legend><?php print $title ?></legend>
<div id="form" class = "well span12 noleftmargin">
	<?php echo $this->Form->create('CustomText', array('data-id'=>$id)) ?>
		<?php
			echo $this->Form->input('custom_id', array(
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
		<div class="span11">
		<?php
			echo $this->Form->input('dropdown_txt', array(
					'label'		=> 'Dropdown text',
					'class'		=> 'span12',
					'div'		=> 'span6',
					'default'	=> $row['CustomText']['dropdown_txt']
				)
			);
		?>
		<?php
			echo $this->Form->input('custom_txt', array(
					'label'		=> 'DI settlement',
					'class'		=> 'span12',
					'div'		=> 'span6',
					'default'	=> $row['CustomText']['custom_txt']
				)
			);
		?>

		</div>
		<?php
			echo $this->Form->input('cpty_id', array(
					'label'		=> 'Counterparty',
					'class'		=> 'span12',
					'div'		=> 'span6',
					'options'	=> $counterparties,
					'default'	=> $row['CustomText']['cpty_id'],
					'after'		=> '<span class="help-block addnewform text-right"><a href="'.Router::url('counterparty?from='.$this->here).'">+ Add a new Counterparty</a></span>'
				)
			);
		?>

		<?php if(!empty($id)): ?>
		<div class="span11"></div><div class="span11">
		<?php
			echo $this->Form->input('created',
				array(
					'type'		=> 'text',
					'label'		=> 'Created on',
					'disabled'	=> true,
					'class'		=> 'span12',
					'div'		=> 'span6',
					'default'	=> $row['CustomText']['created']
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
					'default'	=> $row['CustomText']['modified']
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
			    '<i class="icon-chevron-left"></i> Cancel',
			    (!empty($from))?$from:'custom_texts',
			    array('class' => 'btn pull-right btn-small', 'style'=>'margin-right: 5px;', 'escape'=>false)
			); ?>
		</div>
		
	<?php echo $this->Form->end() ?>
</div>
</fieldset>