<?php 
	$perms = $this->Permission->getPermissions();
	$title = 'New mandate';
	$submit = 'Save';
	$id = '';
	$readonly = '';
	$from = '';
	if(!empty($_GET['from'])) $from=$_GET['from'];

	if(!empty($row)){
		$title = 'Edit mandate '.$row['Mandate']['mandate_name'];
		$submit = 'Update';
		$id = $row['Mandate']['mandate_ID'];
		$readonly = 'readonly';
	}else $row=null;

	$readonly_g1 = $readonly_g2 = false;
	$disabled = '';
	//$user_groups = array(/*'treasuryOP'=>'treasuryOP', 'treasuryVal'=>'treasuryVal',*/ 'treasuryRisk'=>'treasuryRisk'); //debug
	

	if(!empty($user_groups['treasuryOP']) || in_array('treasuryOP', $user_groups)){
		$readonly_g1 = true;
		$disabled = 'disabled';
	}
	if(!empty($user_groups['treasuryVal']) || in_array('treasuryVal', $user_groups)){
		$readonly_g1 = true;
		$disabled = 'disabled';
	}
	if(!empty($user_groups['treasuryRisk']) || in_array('treasuryRisk', $user_groups)){
		$readonly_g2 = true;
	}
	if(in_array('Admin', $user_groups)){
		$readonly_g1 = $readonly_g2 = true;
	}
	
?><fieldset>
<legend><?php print $title ?></legend>
<div id="form" class = "well span12 noleftmargin">
	<?php echo $this->Form->create('Mandate', array('data-id'=>$id)) ?>
		<?php
			echo $this->Form->input('mandate_ID', array(
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

			echo $this->Form->input('mandate_name', array(
					'label'		=> 'Name',
					'class'		=> 'span12',
					'div'		=> 'span11',
					'readonly'	=> empty($readonly_g1),
					'default'	=> $row['Mandate']['mandate_name']
				)
			);
		?>
		<div class="span11">
		<?php
			echo $this->Form->input('BU', array(
					'label'		=> 'BU',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'readonly'	=> empty($readonly_g1),
					'required'	=> false,
					'default'	=> $row['Mandate']['BU']
				)
			);
		?>
		<?php
			echo $this->Form->input('BU_PS', array(
					'label'		=> 'BU PS',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'readonly'	=> empty($readonly_g1),
					'required'	=> false,
					'default'	=> $row['Mandate']['BU_PS']
				)
			);
		?>
		<?php
			echo $this->Form->input(empty($readonly_g1)?'to_book_readonly':'to_book', array(
					'type'	=> 'checkbox',
					'label'		=> 'To book >'.$row['Mandate']['to_book'],
					'disabled'	=> empty($readonly_g1),
					'readonly'	=> empty($readonly_g1),
					'class'		=> '',
					'div'		=> 'span4 checkbox checkboxvlabel',
					'required'	=> false,
					'checked'	=> $row['Mandate']['to_book']=='Y',
					'value'	=> 'Y'
				)
			);
		?>
		</div>
		<div class="span11"></div>

		<div class="span11">
		<?php
		
			$array_input = array(
					'label'		=> 'SP',
					'class'		=> 'span12',
					'div'		=> 'span6',
					'readonly'	=> empty($readonly_g2),
					'required'	=> false,
					'disabled' => $disabled,
					'options'	=> $counterparties,
					'empty' 	=> __('-- Select a counterparty --'),
					'default'	=> $row['Mandate']['SP_ID']
				);
				if( ! $perms['is_risk'])
				{
					$array_input['after'] = '<span class="help-block addnewform text-right"><a href="'.Router::url('counterparty?from='.$this->here).'">+ Add a new Counterparty</a></span>';
				}
			echo $this->Form->input('SP_ID', $array_input);
		?>
		</div>
		<?php for($i=0; $i<50; $i++): ?>
			<?php 
				if(($i%2)==0) print '<div class="span11 loop-'.$i.'">';

				$array_input =  array(
					'label'		=> 'TM'.($i+1),
					'class'		=> 'span12',
					'div'		=> 'span6',
					'required'	=> false,
					'disabled' => $disabled,
					'readonly'	=> empty($readonly_g2),
					'options'	=> $counterparties,
					'empty' 	=> __('-- Select a counterparty --'),
					'default'	=> $row['Mandate']['TM'.($i+1).'_ID']
					);
					if( ! $perms['is_risk'])
					{
						$array_input['after'] = '<span class="help-block addnewform text-right"><a href="'.Router::url('counterparty?from='.$this->here).'">+ Add a new Counterparty</a></span>';
					}
				echo $this->Form->input('TM'.($i+1).'_ID', $array_input);

				if(($i%2)==1) print '</div>';
				
			?>
		<?php endfor ?>
		

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
					'default'	=> $row['Mandate']['created']
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
					'default'	=> $row['Mandate']['modified']
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
			    (!empty($from))?$from:'mandates',
			    array('class' => 'btn pull-right btn-small btn-cancel', 'style'=>'margin-right: 5px;', 'escape'=>false)
			); ?>
		</div>
		
	<?php echo $this->Form->end() ?>
</div>
</fieldset>