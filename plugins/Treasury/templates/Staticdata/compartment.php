<?php 
	$title = 'New compartment';
	$submit = 'Save';
	$id = '';
	$readonly = '';
	$from = '';
	if(!empty($_GET['from'])) $from=$_GET['from'];

	if(!empty($row)){
		$title = 'Edit compartment '.$row['Compartment']['cmp_name'];
		$submit = 'Update';
		$id = $row['Compartment']['cmp_ID'];
		$readonly = 'readonly';
	}else $row=null;
	
?><fieldset>
<legend><?php print $title ?></legend>
<div id="form" class = "well span12 noleftmargin">
	<?php echo $this->Form->create('Compartment', array('data-id'=>$id)) ?>
		<?php
			echo $this->Form->input('cmp_ID', array(
					'type'		=> 'hidden',
					'label'		=> false,
					'value'		=> $id
				)
			);
			echo $this->Form->input('action_from', array(
					'type'		=> 'hidden',
					'label'		=> false,
					'value'		=> $from,
					'div'		=> false,
					'default'	=> $row['Compartment']['cmp_name']
				)
			);
			echo $this->Form->input('cmp_name', array(
					'type'		=> 'text',
					'label'		=> 'Name',
					'class'		=> 'span12',
					'div'		=> 'span11',
					'default'	=> $row['Compartment']['cmp_name']
				)
			);
		?>
		<div class="span11">
			<div class="span6">Type</div>
			<div class="span6">Value</div>
		</div>
		<div class="span11">
		<div class="span6"><label for="CompartmentCmpDptCodeValue">Department code</label></div>
		<?php
			echo $this->Form->input('cmp_dpt_code_value', array(
					'label'		=> false,
					'class'		=> 'span12',
					'div'		=> 'span6',
					//'options'	=> array(''=>'', 'source of funding'=>'Source of funding', 'department code'=>'Department code'),
					'default'	=> $row['Compartment']['cmp_dpt_code_value']
				)
			);
		?>
		</div>
		<div class="span11">

		<div class="span6"><label for="CompartmentCmpSofValue">Source of funding</label></div>
		<?php
			echo $this->Form->input('cmp_sof_value', array(
					'label'		=> false,
					'class'		=> 'span12',
					'div'		=> 'span6',
					'default'	=> $row['Compartment']['cmp_sof_value']
				)
			);
		?>
		</div>
		<?php
			echo $this->Form->input('mandate_ID', array(
					'label'		=> 'Mandate',
					'class'		=> 'span12',
					'options'	=> array(''=>'------') + $mandates_list,
					'div'		=> 'span11',
					'required'	=> true,
					'default'	=> $row['Mandate']['mandate_ID'],
					'after'		=> '<span class="help-block addnewform text-right"><a href="'.Router::url('mandate?from='.$this->here).'">+ Add a new Mandate</a></span>'
				)
			);
		?>
		<div class="span11">
		<?php
			echo $this->Form->input('accountA_IBAN', array(
					'label'		=> 'Account A IBAN',
					'class'		=> 'span12',
					'options'	=> array(''=>'------') + $accounts,
					'div'		=> 'span6',
					'required'	=> true,
					'default'	=> $row['Compartment']['accountA_IBAN'],
					'after'		=> '<span class="help-block addnewform text-right"><a href="'.Router::url('account?from='.$this->here).'">+ Add a new Account</a></span>'
				)
			);
		?>
		<?php
			echo $this->Form->input('accountB_IBAN', array(
					'label'		=> 'Account B IBAN',
					'class'		=> 'span12',
					'options'	=> array(''=>'--- No Account B ---') + $accounts,
					'div'		=> 'span6',
					'default'	=> $row['Compartment']['accountB_IBAN'],
					'after'		=> '<span class="help-block addnewform text-right"><a href="'.Router::url('account?from='.$this->here).'">+ Add a new Account</a></span>'
				)
			);
		?></div>
		<div class="span11"></div>

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
					'default'	=> $row['Compartment']['created']
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
					'default'	=> $row['Compartment']['modified']
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
			    (!empty($from))?$from:'compartments',
			    array('class' => 'btn pull-right btn-small btn-cancel', 'style'=>'margin-right: 5px;', 'escape'=>false)
			); ?>
		</div>
		
	<?php echo $this->Form->end() ?>
</div>
</fieldset>