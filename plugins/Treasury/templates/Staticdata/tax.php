<?php 
	$title = 'New tax';
	$submit = 'Save';
	$id = '';
	$readonly = '';
	$from = '';
	if(!empty($_GET['from'])) $from=$_GET['from'];

	if(!empty($row)){
		$title = 'Edit tax '.$row['Tax']['tax_ID'];
		$submit = 'Update';
		$id = $row['Tax']['tax_ID'];
		$readonly = 'readonly';
	}else $row=null;
	
	echo $this->Html->script('/treasury/js/autoNumeric.js');
?><fieldset>
<legend><?php print $title ?></legend>
<div id="form" class = "well span12 noleftmargin">
	<?php echo $this->Form->create('Tax', array('data-id'=>$id)) ?>
		<?php
			echo $this->Form->input('tax_ID', array(
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
			echo $this->Form->input('mandate_ID', array(
					'label'		=> 'Mandate',
					'class'		=> 'span12',
					'div'		=> 'span6',
					'options'	=> $mandates,
					'default'	=> $row['Tax']['mandate_ID'],
					'after'		=> '<span class="help-block addnewform text-right"><a href="'.Router::url('mandate?from='.$this->here).'">+ Add a new Mandate</a></span>'
				)
			);
		?>
		<?php
			echo $this->Form->input('cpty_ID', array(
					'label'		=> 'Counterparty',
					'class'		=> 'span12',
					'div'		=> 'span6',
					'options'	=> $counterparties,
					'default'	=> $row['Tax']['cpty_ID'],
					'after'		=> '<span class="help-block addnewform text-right"><a href="'.Router::url('counterparty?from='.$this->here).'">+ Add a new Counterparty</a></span>'
				)
			);
		?></div>
		<?php
			echo $this->Form->input('tax_rate', array(
					'label'		=> 'Rate %',
					'type'		=> 'text',
					'class'		=> 'span4 tax_numeric',
					'div'		=> 'span11',
					'style'		=> 'text-align: right',
					'default'	=> $row['Tax']['tax_rate']
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
					'default'	=> $row['Tax']['created']
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
					'default'	=> $row['Tax']['modified']
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
			    (!empty($from))?$from:'taxes',
			    array('class' => 'btn pull-right btn-small', 'style'=>'margin-right: 5px;', 'escape'=>false)
			); ?>
		</div>
		
	<?php echo $this->Form->end() ?>
</div>
</fieldset>
<script type="text/javascript">
	$(document).ready(function(){
		$(".tax_numeric").autoNumeric('init',{aSep: ',',aDec: '.', vMin:0, mDec:3, vMax: 99999999.999});
	});
</script>