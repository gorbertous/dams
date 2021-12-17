<?php 
	$title = 'New Depo Term';
	$submit = 'Save';
	$id = '';
	$readonly = '';
	$from = '';
	if(!empty($_GET['from'])) $from=$_GET['from'];

	if(!empty($row)){
		$title = 'Edit Depot term '.$row['DepoTerm']['label'];
		$submit = 'Update';
		$id = $row['DepoTerm']['value'];
		$readonly = 'readonly';
	}else $row=null;
	
?><fieldset>
<legend><?php print $title ?></legend>
<div id="form" class = "well span12 noleftmargin">
	<?php echo $this->Form->create('DepoTerm', array('data-id'=>$id)) ?>
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
		?>
		<div class="span11">
		<?php
			echo $this->Form->input('val', array(
					'label'		=> 'Code',
					'class'		=> 'span12',
					'div'		=> 'span6',
					'readonly'	=> $readonly,
					'default'	=> $row['DepoTerm']['value']
				)
			);
		?>
		<?php
			echo $this->Form->input('label', array(
					'label'		=> 'Label',
					'class'		=> 'span12',
					'div'		=> 'span6',
					'default'	=> $row['DepoTerm']['label']
				)
			);
		?>
		</div>

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
			    (!empty($from))?$from:'depoterms',
			    array('class' => 'btn pull-right btn-small btn-cancel', 'style'=>'margin-right: 5px;', 'escape'=>false)
			); ?>
		</div>
		
	<?php echo $this->Form->end() ?>
</div>
</fieldset>