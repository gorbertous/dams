<?php 
	$title = 'New bank';
	$submit = 'Save';
	$id = '';
	$readonly = '';
	$from = '';
	if(!empty($_GET['from'])) $from=$_GET['from'];

	if(!empty($row)){
		$title = 'Edit bank '.$row['Bank']['bank_name'];
		$submit = 'Update';
		$id = $row['Bank']['BIC'];
		$readonly = 'readonly';
	}else $row=null;
	
?><fieldset>
<legend><?php print $title ?></legend>
<div id="form" class = "well span12 noleftmargin">
	<?php echo $this->Form->create('Bank', array('data-id'=>$id)) ?>
		<?php
			echo $this->Form->input('id',
				array(
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

			echo $this->Form->input('BIC',
				array(
					'type'		=> 'text',
					'label'		=> 'BIC',
					'readonly' => $readonly,
					'class'		=> 'span7',
					'div'		=> 'span11',
					'default'	=> $row['Bank']['BIC'],
					'maxlength' => 11,
				)
			);
		?>
		<div class="span11">
			<?php
				echo $this->Form->input('bank_name',
					array(
						'label'		=> 'Name',
						'class'		=> 'span12',
						'div'		=> 'span9',
						'required'	=> true,
						'default'	=> $row['Bank']['bank_name']
					)
				);
			?>	
			<?php
				echo $this->Form->input('short_name',
					array(
						'label'		=> 'Short name',
						'class'		=> 'span12',
						'div'		=> 'span3',
						'required'	=> true,
						'default'	=> $row['Bank']['short_name']
					)
				);
			?>	
		</div>
		<div class="span11"></div>
		<div class="span11">
			<?php
				echo $this->Form->input('address',
					array(
						'label'		=> 'Address',
						'class'		=> 'span12',
						'div'		=> 'span6',
						'required'	=> true,
						'default'	=> $row['Bank']['address']
					)
				);
			?>	
			<?php
				echo $this->Form->input('city',
					array(
						'label'		=> 'City',
						'class'		=> 'span12',
						'div'		=> 'span6',
						'required'	=> true,
						'default'	=> $row['Bank']['city']
					)
				);
			?>
			</div>
			<div class="span11">
			<?php
				echo $this->Form->input('country',
					array(
						'label'		=> 'Country',
						'class'		=> 'span12',
						'div'		=> 'span6',
						'required'	=> true,
						'default'	=> $row['Bank']['country']
					)
				);
			?>
			<?php
				echo $this->Form->input('zipcode',
					array(
						'label'		=> 'Zipcode',
						'class'		=> 'span12',
						'div'		=> 'span6',
						'required'	=> true,
						'default'	=> $row['Bank']['zipcode']
					)
				);
			?>
			
		</div>
		<div class="span11"></div>
		<div class="span11">
		
		<?php
			echo $this->Form->input('contact_person',
				array(
					'label'		=> 'Contact person',
					'class'		=> 'span12',
					'div'		=> 'span6',
					'required'	=> true,
					'default'	=> $row['Bank']['contact_person']
				)
			);
		?>
		<?php
			echo $this->Form->input('email',
				array(
					'label'		=> 'Email',
					'class'		=> 'span12',
					'div'		=> 'span6',
					'required'	=> true,
					'default'	=> $row['Bank']['email']
				)
			);
		?>
		</div>
		<div class="span11">
		<?php
			echo $this->Form->input('tel',
				array(
					'label'		=> 'Tel',
					'class'		=> 'span12',
					'div'		=> 'span6',
					'required'	=> true,
					'default'	=> $row['Bank']['tel']
				)
			);
		?>
		<?php
			echo $this->Form->input('fax',
				array(
					'label'		=> 'Fax',
					'class'		=> 'span12',
					'div'		=> 'span6',
					'required'	=> true,
					'default'	=> $row['Bank']['fax']
				)
			);
		?>		
		</div>
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
					'default'	=> $row['Bank']['created']
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
					'default'	=> $row['Bank']['modified']
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
			    (!empty($from))?$from:'banks',
			    array('class' => 'btn pull-right btn-small btn-cancel', 'style'=>'margin-right: 5px;', 'escape'=>false)
			); ?>
		</div>
		
	<?php echo $this->Form->end() ?>
</div>
</fieldset>