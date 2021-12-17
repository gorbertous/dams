<?php
	echo $this->Html->css('/treasury/css/redmond/jquery-ui-1.10.3.custom.css');
	echo $this->Html->script('/treasury/js/jquery-ui-1.10.3.custom.min.js');
?><?php 
	$title = 'New Limit';
	$submit = 'Save';
	$id = '';
	$readonly = '';
	$from = '';
	if(!empty($_GET['from'])) $from=$_GET['from'];

	if(!empty($row)){
		$title = 'Edit Limit '.$row['Limit']['limit_name'];
		$submit = 'Update';
		$id = $row['Limit']['limit_ID'];
		$readonly = 'readonly';
	}else $row=null;
	
?><fieldset>
<legend><?php print $title ?></legend>
<div id="form" class = "well span12 noleftmargin">
	<?php echo $this->Form->create('Limit', array('data-id'=>$id)) ?>
	<?php //echo $this->Session->flash('error'); ?>
	<div id="alertMsg" style="display:none" class="alert alert-error">
		<h4>Error!</h4>
		<span id="alertText"></span>
	</div>
		<?php
			echo $this->Form->input('limit_ID', array(
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
			
			echo $this->Form->input('limit_name', array(
					'label'		=> 'Name',
					'class'		=> 'span12',
					'div'		=> 'span11',
					'default'	=> $row['Limit']['limit_name']
				)
			);
		?>
		<div class="span11">
		<?php
			echo $this->Form->input('mandategroup_ID', array(
					'label'		=> 'Portfolio',
					'class'		=> 'span12',
					'div'		=> 'span6',
					'default'	=> $row['Limit']['mandategroup_ID'],
					'options'	=> $mandategroups,
					'after'		=> '<span class="help-block addnewform text-right"><a href="'.Router::url('mandategroup?from='.$this->here).'">+ Add a new Portfolio</a></span>'
				)
			);
		?>
		<?php
			echo $this->Form->input('cpty_ID', array(
					'label'		=> 'Counterparty or risk group',
					'class'		=> 'span12',
					'div'		=> 'span6',
					'default'	=> $row['Limit']['cpty_ID'],
					'options'	=> $counterparties,
					'after'		=> '<span class="help-block addnewform text-right"><a href="'.Router::url('counterparty?from='.$this->here).'">+ Add a new Counterparty</a></span>'
				)
			);
		?>
		</div>
		<div class="span11"></div>
		<?php /*<div class="span11">
		<?php
			echo $this->Form->input('limit_date_from', array(
					'label'		=> 'Date from',
					'class'		=> 'span12 datefield',
					'div'		=> 'span6',
					'type'		=> 'text',
					'data-date-format'	=> 'dd/mm/yyyy',
					'default'	=> $row['Limit']['limit_date_from'],
				)
			);
		?>
		<?php
			echo $this->Form->input('limit_date_to', array(
					'label'		=> 'Date to',
					'class'		=> 'span12 datefield',
					'div'		=> 'span6',
					'type'		=> 'text',
					'data-date-format'	=> 'dd/mm/yyyy',
					'default'	=> $row['Limit']['limit_date_to'],
				)
			);
		?>
		</div> */ ?>

		<div class="span11">
			<?php
				echo $this->Form->input('automatic', array(
						'label'		=> 'Calculation',
						'class'		=> 'span12',
						'div'		=> 'span4',
						'default'	=> $row['Limit']['automatic'],
						'options'	=> array(0=>'Manual input of limits', 1=>'Automatic calculation and update of limits')
					)
				);
			?>
		</div>

		<div class="manualrating">
			<div class="span4" style="margin-left:25px;margin-top:25px; "><label>Retained Ratings</label></div>
			<div class="span11">
				<?php
					echo $this->Form->input('rating_lt', array(
							'label'		=> 'LT&nbsp;&nbsp;&nbsp;&nbsp;',
							'div'		=> 'span4',
							'default'	=> $row['Limit']['rating_lt'],
						)
					);
					echo $this->Form->input('rating_st', array(
							'label'		=> 'ST&nbsp;&nbsp;&nbsp;&nbsp;',
							'div'		=> 'span4',
							'default'	=> $row['Limit']['rating_st'],
						)
					);
				?>
			</div>
			<br>
			<span class="span4" style="margin-left:25px;margin-top:25px;"><label>Max Maturity Limits</label></span>
			<span class="span4" style="margin-left:25px;margin-top:25px;"><label style="margin-left:-30px;">Exposure Limit</label></span>
			<div class="span11">
			<?php
				echo $this->Form->input('max_maturity', array(
						'label'		=> '',
						'class'		=> 'span7',
						'div'		=> 'span4',
						'type'		=> 'text',
						'style' => 'float: left !important; text-align: right;',
						'default'	=> $row['Limit']['max_maturity'],
						'after' => '<div class="span2" style="line-height: 30px;">days</div>',
					)
				);		
				echo $this->Form->input('limit_eur', array(
						'label'		=> false,
						'class'		=> 'span7',
						'div'		=> 'span4',
						'type'		=> 'text',
						'style' => 'float: left !important; text-align: right;',
						'default'	=> UniformLib::uniform($row['Limit']['limit_eur'],'limit_eur'),
						'after' => '<div class="span2" style="line-height: 30px;">EUR</div>',
					)
				);
			?>
			</div>
			<div class="span4" style="margin-left:25px;margin-top:25px;"><label>Max Portfolio concentration</label></div>
			<div class="span11">
				<?php
				echo $this->Form->input('max_concentration', array(
						'label'     => '',
						'class'		=> 'span7 optional',
						'div'		=> 'span4',
						'type'		=> 'text',
						'default'	=> $row['Limit']['max_concentration'],
						'style' => 'float: left !important',
						'after' => '<div class="span5" style="line-height: 30px;">% of Portfolio &nbsp; OR</div>',
					)
				);		
				echo $this->Form->input('max_concentration_abs', array(
						'label'		=> '',//false,//''Limit (in EUR)',
						'class'		=> 'span7 optional',
						'div'		=> 'span4',
						'type'		=> 'text',
						'default'	=> UniformLib::uniform($row['Limit']['max_concentration_abs'],'max_concentration_abs'),
						'style' => 'float: left !important; text-align: right;',
						//'default'	=> $row['Limit']['limit_eur']
						'after' => '<div class="span5" style="line-height: 30px;">Absolute Concentration in EUR</div>',
					)
				);
			?>
			</div>

			<div class="span4">
				<?php 
				echo $this->Form->input('no_limit', array(
						'label'		=> '&nbsp;No Limit',
						'class'		=> ' optional',
						'div'		=> 'span4',
						'type'		=> 'checkbox',
						'default'	=> ($row['Limit']['max_concentration_abs'] == ''),
						'style' => 'float: left !important; text-align: right;',
						'onchange' => 'setNoLimit();'
					)
				);

				echo $this->Form->input('no_limit_hidden', array(
						'type'		=> 'hidden',
						'default'	=> ($row['Limit']['max_concentration_abs'] == '')
					)
				);
				?>
			</div>
			
		</div>
		

		<?php if(!empty($id)): ?>
		<div class="span11"></div>
		<div class="span11"></div>
		<div class="span11"><?php
			echo $this->Form->input('created',
				array(
					'type'		=> 'text',
					'label'		=> 'Created on',
					'disabled'	=> true,
					'class'		=> 'span12',
					'div'		=> 'span6',
					'default'	=> $row['Limit']['created']
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
					'default'	=> $row['Limit']['modified']
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
			<?php if(!empty($id)): ?>
				<?php print $this->Html->link(
				    '<i class="icon-trash icon-white"></i> Remove',
				    'limit/'.$id.'/delete',
				    array('class' => 'link-confirm btn btn-danger pull-right btn-small', 'style'=>'margin-right: 5px;', 'escape'=>false)
				); ?>
			<?php endif ?>
			<?php print $this->Html->link(
			    '<i class="icon-chevron-left"></i> Cancel',
			    (!empty($from))?$from:'limits',
			    array('class' => 'btn pull-right btn-small btn-cancel', 'style'=>'margin-right: 5px;', 'escape'=>false)
			); ?>
		</div>
		
	<?php echo $this->Form->end() ?>
</div>
</fieldset>

<script>
	var automaticValues = {};
	$(document).ready(function(){
		$('.datefield').datepicker({ dateFormat: "dd/mm/yy" });
		$('#LimitAutomatic').bind('change', function(e){
			manualAutomaticToggle(e);
		});
		manualAutomaticToggle(null);

		$('#LimitMandategroupID').bind('change', getAutomaticValues);
		$('#LimitCptyID').bind('change', getAutomaticValues);

		$('#LimitMandategroupID').bind('change', getMandateUnit);
		
		$('#LimitLimitForm').submit(send_value);

	});
	
	function send_value()
	{
		$('#LimitNoLimit').prop('disabled', false);
		var no_limit = $('#LimitNoLimit:checked').length;
		if (no_limit > 0)
		{
			$('#LimitNoLimitHidden').val('1');
		}
		else
		{
			$('#LimitNoLimitHidden').val('0');
		}
	}

	function manualAutomaticToggle(e, noval){
		if($('#LimitAutomatic').val()==1){
			$('.manualrating input').prop('readonly', true);
			$('.manualrating input').prop('required', false);
			if(!noval) populateAutomaticValues();
			init_no_limit();//if reset to automatic -> reset to default value
			$('#LimitNoLimit').prop("disabled", true);
		}else{
			$('.manualrating input').prop('readonly', false);
			$('.manualrating input').not('.optional').prop('required', true);
			$('#LimitNoLimit').prop("disabled", false);//http://vmu-sas-01:8080/browse/TREASURY-388
		}
	}

	function getAutomaticValues(e){
		//ajax to get automatic values
		var url = '/treasury/treasurystaticdatas/limitGetCalculated/'+$('#LimitMandategroupID').val()+'/'+$('#LimitCptyID').val();
		automaticValues = {};
		$.ajax({
			type: "POST",
			url: url,
			dataType: 'json', 
			async:true,
			success:function (data, textStatus) {
				$('#alertMsg').hide();
				if(data.result){
					automaticValues = data.result;
					if($('#LimitAutomatic').val()==1){
						populateAutomaticValues(e);
					}
				}else{
					automaticValues = {};
					if($('#LimitAutomatic').val()==1){
						$('#LimitAutomatic').val(0);
						$('#LimitAutomatic').trigger('change');
						$('#alertMsg').show();
						$('#alertText').text(data['error']);
						$('#LimitRatingLt').val('');
						$('#LimitRatingSt').val('');
						$('#LimitMaxConcentrationAbs').val('');
						$('#LimitMaxConcentration').val('');
						$('#LimitMaxMaturity').val('');
						$('#LimitLimitEur').val('');
					}
				}
			}
		});
		
		
	}
	function populateAutomaticValues(e){
		if($('#LimitAutomatic').val()==1){
			if(automaticValues.id){
				//assign LT, ST, maturity, etc...
				$('#LimitRatingLt').val('');
				$('#LimitRatingSt').val('');
				$('#LimitMaxConcentrationAbs').val('');
				$('#LimitMaxConcentration').val('');

				if(automaticValues['LT-R']) $('#LimitRatingLt').val(automaticValues['LT-R']);
				if(automaticValues['ST-R']) $('#LimitRatingSt').val(automaticValues['ST-R']);
				$('#LimitMaxMaturity').val(automaticValues.calculated_max_maturity);
				$('#LimitLimitEur').val(automaticValues.calculated_limit);
				if(automaticValues.concentration_limit_unit=='PCT'){
					$('#LimitMaxConcentration').val(automaticValues.calculated_max_concentration*100);
				}else if(automaticValues.concentration_limit_unit=='ABS'){
					$('#LimitMaxConcentrationAbs').val(automaticValues.calculated_max_concentration);
				}
				
			}else{
				getAutomaticValues(e);
			}
		}
	}

	function init_no_limit()
	{
		if (is_not_limit_automaticMode)
		{
			$('#LimitNoLimit').prop('checked', true);
			$('#LimitNoLimitHidden').val('1');
			$('#LimitMaxConcentrationAbs').attr('disabled', true);
			$('#LimitMaxConcentration').attr('disabled', true);
		}
		else		
		{
			$('#LimitNoLimit').prop('checked', false);
			$('#LimitNoLimitHidden').val('0');
			$('#LimitMaxConcentrationAbs').attr('disabled', false);
			$('#LimitMaxConcentration').attr('disabled', false);
		}
	}
	//trigered by change of checkbox 'no limit'
	function setNoLimit()
	{
		var no_limit = $('#LimitNoLimit:checked').length;
		if (no_limit > 0)
		{
			$('#LimitMaxConcentrationAbs').attr('disabled', true);
			$('#LimitMaxConcentration').attr('disabled', true);
			$('#LimitNoLimitHidden').val('1');
		}
		else
		{
			$('#LimitMaxConcentrationAbs').attr('disabled', false);
			$('#LimitMaxConcentration').attr('disabled', false);
			$('#LimitNoLimitHidden').val('0');
		}
	}

	var is_not_limit_automaticMode = <?php echo $is_not_limit_automaticMode ? 'true' : 'false'; ?>;//default value of tick box according to eligibility criteria of mandate
	var is_not_limited_original = <?php echo $is_not_limited ? 'true' : 'false'; ?>;//db value
	if (is_not_limited_original)
	{
		$('#LimitNoLimit').prop('checked', true);
		$('#LimitNoLimitHidden').val('1');
		$('#LimitMaxConcentrationAbs').attr('disabled', true);
		$('#LimitMaxConcentration').attr('disabled', true);
	}
	else		
	{
		$('#LimitNoLimit').prop('checked', false);
		$('#LimitNoLimitHidden').val('0');
		$('#LimitMaxConcentrationAbs').attr('disabled', false);
		$('#LimitMaxConcentration').attr('disabled', false);
	}
	//trigered by change on select Portfolio
	function getMandateUnit(e)
	{
		var MandateGroup = $('#LimitMandategroupID').val();

		var url = '/treasury/treasuryajax/getNoLimitOnMandateGroup/' + MandateGroup;
		$.ajax({
			type: "GET",
			url: url,
			dataType: 'text', 
			async:true,
			success:function (data, textStatus) {
				is_not_limit_automaticMode = (data == 'NA');
				
				init_no_limit();
			}
		});
	}

</script>