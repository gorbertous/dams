<?php 
	$perms = $this->Permission->getPermissions();
	$title = 'New mandate';
	$submit = 'Approve';
	$id = '';
	$readonly = '';
	$from = '';
	if(!empty($_GET['from'])) $from=$_GET['from'];

	if(!empty($row)){
		$title = 'Edit mandate '.$row['MandatePending']['mandate_name'];
		$submit = 'Approve';
		$id = $row['MandatePending']['mandate_ID'];
		$readonly = 'readonly';
	}else $row=null;

	$readonly_g1 = $readonly_g2 = false;
	$disabled = '';
	//$user_groups = array(/*'treasuryOP'=>'treasuryOP', 'treasuryVal'=>'treasuryVal',*/ 'treasuryRisk'=>'treasuryRisk'); //debug
	
	$readonly_g1 = true;
	$disabled = 'disabled';
	
?><fieldset>
<legend><?php print $title ?></legend>
<div id="form" class = "well span12 noleftmargin">
	<?php echo $this->Form->create('Mandate', array('data-id'=>$id)) ?>
		<?php
			echo $this->Form->input('mandate_ID', array(
					'type'		=> 'hidden',
					'label'		=> false,
					'value'		=> $id,
					'disabled'	=>	'disabled'
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
					'default'	=> $row['MandatePending']['mandate_name'],
					'disabled'	=>	'disabled'
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
					'default'	=> $row['MandatePending']['BU'],
					'disabled'	=>	'disabled'
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
					'default'	=> $row['MandatePending']['BU_PS'],
					'disabled'	=>	'disabled'
				)
			);
		?>
		<?php
			echo $this->Form->input(empty($readonly_g1)?'to_book_readonly':'to_book', array(
					'type'	=> 'checkbox',
					'label'		=> 'To book >'.$row['MandatePending']['to_book'],
					'disabled'	=> empty($readonly_g1),
					'readonly'	=> empty($readonly_g1),
					'class'		=> '',
					'div'		=> 'span4 checkbox checkboxvlabel',
					'required'	=> false,
					'checked'	=> $row['MandatePending']['to_book']=='Y',
					'value'	=> 'Y',
					'disabled'	=>	'disabled'
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
					'default'	=> $row['MandatePending']['SP_ID'],
					'disabled'	=>	'disabled'
				);
				if( ! $perms['is_risk'])
				{
					$array_input['after'] = '<span class="help-block addnewform text-right"><a href="'.Router::url('counterparty?from='.$this->here).'">+ Add a new Counterparty</a></span>';
				}
			echo $this->Form->input('SP_ID', $array_input);
		?>
		</div>
		<?php for($i=0; $i<30; $i++): ?>
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
					'default'	=> $row['MandatePending']['TM'.($i+1).'_ID'],
					'disabled'	=>	'disabled'
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
					'default'	=> $row['MandatePending']['created'],
					'disabled'	=>	'disabled'
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
					'default'	=> $row['MandatePending']['modified'],
					'disabled'	=>	'disabled'
				)
			);
		?>
		</div><?php endif ?>

		<div class="span11"></div>
		<div class="span11">
			<?php
				echo $this->Form->submit("Approve",
					array(
						'id' 	=> 'validateButton',
						'type' 	=> 'submit',
						'class' => 'btn btn-primary pull-right',
						'div'	=> false,//array('class' => array('span11'))
					)
				);
			?>
			<?php print $this->Html->link(
			    'Reject',
			    'mandate_reject/'.$id,
			    array('class' => 'btn pull-right btn-small btn-danger', 'style'=>'margin-right: 5px;', 'escape'=>false)
			); ?>
		</div>
		
	<?php echo $this->Form->end() ?>
</div>
</fieldset>