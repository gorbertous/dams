<?php
	$perms = $this->Permission->getPermissions();
	$title = 'New Rating';
	$submit = 'Save';
	$id = '';
	$readonly = '';
	$from = '';
	if(!empty($_GET['from'])) $from=$_GET['from'];
	
	$readonly_profile = 'readonly';
	if ($perms['is_admin'] || $perms['is_risk'])
	{
		$readonly_profile = '';// only risk or admin can edit the values
	}

	if(!empty($row)){
		$title = 'Edit Rating #: <em>'.$row['Rating']['id'].'</em>';
		$submit = 'Update';
		$id = $row['Rating']['id'];
		$readonly = 'readonly';
	}else $row=null;
	echo $this->Html->script('/treasury/js/autoNumeric.js');

?><fieldset>
<legend><?php print $title ?></legend>
<div id="form" class = "well span12 noleftmargin">
	<?php echo $this->Form->create('Rating', array('data-id'=>$id)) ?>
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
		<div class="span11"></div>
		<div class="span11">
		<?php
			echo $this->Form->input('pirat_number', array(
					'label'		=>  'PiRat',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'required'  => 'required',
					'readonly'  => $readonly_profile,
					//'options'	=> !empty($counterparties)?$counterparties:array(),
					'default'	=> !empty($row['Rating']['pirat_number'])?$row['Rating']['pirat_number']:null,
					//'after'		=> '<span class="help-block addnewform text-right"><a href="'.Router::url('counterpartygroup?from='.$this->here).'">+ Create a new Risk Group</a></span>'
				)
			);
		?>
		<?php
			echo $this->Form->input('pirat_address', array(
					'type'		=> 'text',
					'label'		=> 'PiRat address',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'readonly'  => $readonly,
					'default'	=> $row['Rating']['pirat_address']
				)
			);
		?>
		<?php
			echo $this->Form->input('pirat_cpty_name', array(
					'type'		=> 'text',
					'label'		=> 'PiRat Counterparty name',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'readonly'  => $readonly,
					'default'	=> $row['Rating']['pirat_cpty_name']
				)
			);
		?>
		</div>
		<div class="span11">
		<?php
			echo $this->Form->input('mother_company', array(
					'type'		=> 'text',
					'label'		=> 'Mother company',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'required'	=> false,
					'readonly'  => $readonly_profile,
					'default'	=> $row['Rating']['mother_company']
				)
			);
		?>
		<?php
			echo $this->Form->input('own_funds', array(
					'type'		=> 'text',
					'label'		=> 'Own funds',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'required'	=> false,
					'readonly'  => $readonly_profile,
					'default'	=> $row['Rating']['own_funds']
				)
			);
		?>
		<?php
			echo $this->Form->input('bs_date', array(
					'type'		=> 'text',
					'label'		=> 'BS Date',
					'class'		=> 'span12',
					'required'	=> false,
					'readonly'  => $readonly_profile,
					'div'		=> 'span4',
					'data-date-format'	=> 'dd/mm/yyyy',
					'default'	=> UniformLib::show_date($row['Rating']['bs_date'],'bs_date')
				)
			);
		?>
		</div>
		<div class="span11"></div>
		<div class="span11">
		<?php
			echo $this->Form->input('LT-MDY', array(
					// 'type'		=> 'text',
					'label'		=> 'Long Term Moody\'s',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'readonly'  => $readonly_profile,
					'default'	=> $row['Rating']['LT-MDY'],
					'options'	=> $ratings['LT-MDY'],
					'required'	=> false,
					'empty'		=> '-- select a rating --'
				)
			);
		?>
		<?php
			echo $this->Form->input('LT-MDY_date', array(
					'type'		=> 'text',
					'label'		=> 'Long Term Moody\'s date',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'readonly'  => $readonly_profile,
					'data-date-format'	=> 'dd/mm/yyyy',
					'required'	=> false,
					'default'	=> UniformLib::show_date($row['Rating']['LT-MDY_date'], 'LT-MDY_date')
				)
			);
		?>
		<?php
			echo $this->Form->input('LT-MDY_outlook', array(
					'type'		=> 'text',
					'label'		=> 'Long Term Moody\'s outlook',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'readonly'  => $readonly_profile,
					'required'	=> false,
					'default'	=> $row['Rating']['LT-MDY_outlook']
				)
			);
		?>
		</div>
		
		<div class="span11">
		<?php
			echo $this->Form->input('LT-FIT', array(
					// 'type'		=> 'text',
					'label'		=> 'Long Term Fitch',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'readonly'  => $readonly_profile,
					'default'	=> $row['Rating']['LT-FIT'],
					'options'	=> $ratings['LT-FIT'],
					'required'	=> false,
					'empty'		=> '-- select a rating --'
				)
			);
		?>
		<?php
			echo $this->Form->input('LT-FIT_date', array(
					'type'		=> 'text',
					'label'		=> 'Long Term Fitch date',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'readonly'  => $readonly_profile,
					'data-date-format'	=> 'dd/mm/yyyy',
					'required'	=> false,
					'default'	=> UniformLib::show_date($row['Rating']['LT-FIT_date'], 'LT-FIT_date')
				)
			);
		?>
		<?php
			echo $this->Form->input('LT-FIT_outlook', array(
					'type'		=> 'text',
					'label'		=> 'Long Term Fitch outlook',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'readonly'  => $readonly_profile,
					'required' => false,
					'default'	=> $row['Rating']['LT-FIT_outlook']
				)
			);
		?>
		</div>
		
		<div class="span11">
		<?php
			echo $this->Form->input('LT-STP', array(
					// 'type'		=> 'text',
					'label'		=> 'Long Term S&amp;P',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'readonly'  => $readonly_profile,
					'default'	=> $row['Rating']['LT-STP'],
					'options'	=> $ratings['LT-STP'],
					'required' => false,
					'empty'		=> '-- select a rating --'
				)
			);
		?>
		<?php
			echo $this->Form->input('LT-STP_date', array(
					'type'		=> 'text',
					'label'		=> 'Long Term S&amp;P date',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'readonly'  => $readonly_profile,
					'data-date-format'	=> 'dd/mm/yyyy',
					'required'	=> false,
					'default'	=> UniformLib::show_date($row['Rating']['LT-STP_date'], 'LT-STP_date')
				)
			);
		?>
		<?php
			echo $this->Form->input('LT-STP_outlook', array(
					'type'		=> 'text',
					'label'		=> 'Long Term S&amp;P outlook',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'readonly'  => $readonly_profile,
					'required' => false,
					'default'	=> $row['Rating']['LT-STP_outlook']
				)
			);
		?>
		</div>
		
		<div class="span11">
		<?php
			echo $this->Form->input('LT-EIB', array(
					// 'type'		=> 'text',
					'label'		=> 'Long Term EIB',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'readonly'  => $readonly_profile,
					'default'	=> $row['Rating']['LT-EIB'],
					'options'	=> $ratings['LT-EIB'],
					'required' => false,
					'empty'		=> '-- select a rating --'
				)
			);
		?>
		<?php
			echo $this->Form->input('LT-EIB_date', array(
					'type'		=> 'text',
					'label'		=> 'Long Term EIB date',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'readonly'  => $readonly_profile,
					'data-date-format'	=> 'dd/mm/yyyy',
					'required'	=> false,
					'default'	=> UniformLib::show_date($row['Rating']['LT-EIB_date'], 'LT-EIB_date')
				)
			);
		?>
		</div>
		<div class="span11"></div>
		<div class="span11">
		<?php
			echo $this->Form->input('ST-MDY', array(
					// 'type'		=> 'text',
					'label'		=> 'Short Term Moody\'s',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'readonly'  => $readonly_profile,
					'default'	=> $row['Rating']['ST-MDY'],
					'options'	=> $ratings['ST-MDY'],
					'required' => false,
					'empty'		=> '-- select a rating --'
				)
			);
		?>
		<?php
			echo $this->Form->input('ST-MDY_date', array(
					'type'		=> 'text',
					'label'		=> 'Short Term Moody\'s date',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'readonly'  => $readonly_profile,
					'data-date-format'	=> 'dd/mm/yyyy',
					'required'	=> false,
					'default'	=> UniformLib::show_date($row['Rating']['ST-MDY_date'], 'ST-MDY_date')
				)
			);
		?>
		<?php
			echo $this->Form->input('ST-MDY_outlook', array(
					'type'		=> 'text',
					'label'		=> 'Short Term Moody\'s outlook',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'readonly'  => $readonly_profile,
					'required' => false,
					'default'	=> $row['Rating']['ST-MDY_outlook']
				)
			);
		?>
		</div>
		<div class="span11">
		<?php
			echo $this->Form->input('ST-FIT', array(
					// 'type'		=> 'text',
					'label'		=> 'Short Term Fitch',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'readonly'  => $readonly_profile,
					'default'	=> $row['Rating']['ST-FIT'],
					'options'	=> $ratings['ST-FIT'],
					'required' => false,
					'empty'		=> '-- select a rating --'
				)
			);
		?>
		<?php
			echo $this->Form->input('ST-FIT_date', array(
					'type'		=> 'text',
					'label'		=> 'Short Term Fitch date',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'readonly'  => $readonly_profile,
					'data-date-format'	=> 'dd/mm/yyyy',
					'required'	=> false,
					'default'	=> UniformLib::show_date($row['Rating']['ST-FIT_date'], 'ST-FIT_date')
				)
			);
		?>
		<?php
			echo $this->Form->input('ST-FIT_outlook', array(
					'type'		=> 'text',
					'label'		=> 'Short Term Fitch outlook',
					'class'		=> 'span12',
					'readonly'  => $readonly_profile,
					'div'		=> 'span4',
					'required' => false,
					'default'	=> $row['Rating']['ST-FIT_outlook']
				)
			);
		?>
		</div>
		
		<div class="span11">
		<?php
			echo $this->Form->input('ST-STP', array(
					// 'type'		=> 'text',
					'label'		=> 'Short Term S&amp;P',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'readonly'  => $readonly_profile,
					'default'	=> $row['Rating']['ST-STP'],
					'options'	=> $ratings['ST-STP'],
					'required' => false,
					'empty'		=> '-- select a rating --'
				)
			);
		?>
		<?php
			echo $this->Form->input('ST-STP_date', array(
					'type'		=> 'text',
					'label'		=> 'Short Term S&amp;P date',
					'class'		=> 'span12',
					'readonly'  => $readonly_profile,
					'div'		=> 'span4',
					'data-date-format'	=> 'dd/mm/yyyy',
					'required'	=> false,
					'default'	=> UniformLib::show_date($row['Rating']['ST-STP_date'], 'ST-STP_date')
				)
			);
		?>
		<?php
			echo $this->Form->input('ST-STP_outlook', array(
					'type'		=> 'text',
					'label'		=> 'Short Term S&amp;P outlook',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'readonly'  => $readonly_profile,
					'required' => false,
					'default'	=> $row['Rating']['ST-STP_outlook']
				)
			);
		?>
		</div>

		<div class="span11">
		<?php
			echo $this->Form->input('ST-EIB', array(
					// 'type'		=> 'text',
					'label'		=> 'Short Term EIB',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'readonly'  => $readonly_profile,
					'default'	=> $row['Rating']['ST-EIB'],
					'options'	=> $ratings['ST-EIB'],
					'required' => false,
					'empty'		=> '-- select a rating --'
				)
			);
		?>
		<?php
			echo $this->Form->input('ST-EIB_date', array(
					'type'		=> 'text',
					'label'		=> 'Short Term EIB date',
					'class'		=> 'span12',
					'readonly'  => $readonly_profile,
					'div'		=> 'span4',
					'data-date-format'	=> 'dd/mm/yyyy',
					'required'	=> false,
					'default'	=> UniformLib::show_date($row['Rating']['ST-EIB_date'], 'ST-EIB_date')
				)
			);
		?>
		<?php
			echo $this->Form->input('automatic', array(
					'label'		=>  'Automatic or manual',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'readonly'  => $readonly_profile,
					'options'	=> array(1=>'Automatic', 0=>'Manual'),
					'default'	=> empty($row['Rating']['automatic'])?0:1,
					'required' => false,
					'after'		=> '<span class="help-block addnewform text-right">If manual, the update in PiRat won\'t affect this Rating</span>'
				)
			);
		?>
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
					'default'	=> $row['Rating']['created']
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
					'default'	=> $row['Rating']['modified']
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
						'disabled'  => ($readonly_profile === 'readonly'),
						'class' => 'btn btn-primary pull-right',
						'div'	=> false//array('class' => array('span11'))
					)
				);
			?>
			<?php print $this->Html->link(
			    '<i class="icon-chevron-left"></i> Back',
			    (!empty($from))?$from:'ratings',
			    array('class' => 'btn pull-right btn-small btn-cancel', 'style'=>'margin-right: 5px;', 'escape'=>false)
			); ?>
		</div>

	<?php echo $this->Form->end() ?>
</div>
</fieldset>
<?php
	echo $this->Html->script('/treasury/js/bootstrap-datepicker');
	echo $this->Html->css('/treasury/css/datepicker');
?>
<style>
	.checkboxtabline{ padding-top: 5px !important; }
	.btn-add{ margin-top: 25px !important; }
	#counterpartiesTable tr td{ padding: 10px; }
</style>
<script type="text/javascript">
	$(document).ready(function(){
		$('#RatingBsDate, #RatingLT-MDYDate, #RatingLT-FITDate, #RatingLT-STPDate, #RatingLT-EIBDate, #RatingST-MDYDate, #RatingST-FITDate, #RatingST-STPDate, #RatingST-EIBDate').datepicker({dateFormat: 'dd/mm/yy'});
		
		$('#RatingOwnFunds').autoNumeric('init',{aSep: ',',aDec: '.', vMin:0, mDec:2, vMax: 9999999999999.99, vMin:0.00});
	});
</script>