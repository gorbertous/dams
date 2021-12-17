<?php 
	$title = 'New account';
	$submit = 'Save';
	$id = '';
	$readonly = '';

	if(!empty($row)){
		$title = 'Edit account '.UniformLib::uniform($row['Account']['IBAN'], 'account_IBAN');
		$submit = 'Update';
		$id = $row['Account']['IBAN'];
		$readonly = 'readonly';
	}else $row=null;
	

?>
<fieldset>
<legend><?php print $title ?></legend>
<div id="form" class = "well span12 noleftmargin">
	<?php echo $this->Form->create('Account', array('data-id'=>$id)) ?>
		
		<div class="span11"><?php
			echo $this->Form->input('IBAN',
				array(
					'type'		=> 'text',
					'label'		=> 'IBAN',
					'readonly' => $readonly,
					'class'		=> 'span12',
					'div'		=> 'span7',
					'default'	=> $row['Account']['IBAN'],
				)
			);
		?>
		<?php
			echo $this->Form->input('BIC',
				array(
					'label'		=> 'BIC',
					'class'		=> 'span12',
					'div'		=> 'span5',
					'required'	=> true,
					'options'	=> array(''=>'---') + $bics,
					'default'	=> $row['Account']['BIC'],
					'maxlength' => 11,
					'after'	=> '<span class="help-block addnewform text-right"><a data-toggle="modal_" data-target="#formsModal" href="'.Router::url('bank?from='.$this->here).'">+ Add a new Bank</a></span>'

				)
			);
		?>
		</div>
		<?php
			echo $this->Form->input('ccy',
				array(
					'label'		=> 'Currency',
					'class'		=> 'span3',
					'div'		=> 'span11',
					'required'	=> true,
					'options'	=> array(''=>'---')+$ccies,
					'default'	=> $row['Account']['ccy']
				)
			);
		?>
		<?php
			echo $this->Form->input('PS_account',
				array(
					'type'		=> 'text',
					'label'		=> 'PS Account',
					'class'		=> 'span3',
					'div'		=> 'span11',
					'default'	=> $row['Account']['PS_account']
				)
			);
		?>
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
					'default'	=> $row['Account']['created']
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
					'default'	=> $row['Account']['modified']
				)
			);
		?>
		</div>
		<?php endif ?>

		<div class="span11"></div>
		<div class="span11">
			<?php
				echo $this->Form->submit($submit,
					array(
						'id' 	=> 'createButton',
						'type' 	=> 'submit',
						'class' => 'btn btn-primary pull-right',
						'div'	=> false
					)
				);
			?>
			<?php print $this->Html->link(
			    '<i class="icon-chevron-left"></i> Cancel',
			    (!empty($from))?$from:'accounts',
			    array('class' => 'btn pull-right btn-small btn-cancel', 'style'=>'margin-right: 5px;', 'escape'=>false)
			); ?>
		</div>
		
	<?php echo $this->Form->end() ?>
</div>
</fieldset>