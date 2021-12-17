<?php 
	$title = 'New DI template';
	$submit = 'Save';
	$id = '';
	$readonly = '';
	$from = '';
	if(!empty($_GET['from'])) $from=$_GET['from'];

	if(!empty($row)){
		$title = 'Edit DI template '.$row['DItemplate']['dit_id'];
		$submit = 'Update';
		$id = $row['DItemplate']['dit_id'];
		$readonly = 'readonly';
	}else $row=null;
	
?><fieldset>
<legend><?php print $title ?></legend>
<div id="form" class = "well span12 noleftmargin">
	<?php echo $this->Form->create('DItemplate', array('data-id'=>$id)) ?>
		<?php
			echo $this->Form->input('dit_id',
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
		?>
		<div class="span11">
		<?php
			echo $this->Form->input('mandate_ID',
				array(
					'label'		=> 'Mandate',
					'class'		=> 'span12',
					'div'		=> 'span6',
					'options'	=> $mandates,
					'default'	=> $row['DItemplate']['mandate_ID']
				)
			);
		?>
		<?php
			echo $this->Form->input('cpty_ID',
				array(
					'label'		=> 'Counterparty',
					'class'		=> 'span12',
					'div'		=> 'span6',
					'options'	=> $counterparties,
					'default'	=> $row['DItemplate']['cpty_ID']
				)
			);
		?>
		</div>
		<?php
			echo $this->Form->input('template',
				array(
					'type'		=> 'text',
					'label'		=> 'Template file key <small class="muted">(di_[KEY]_pdf, call_[KEY]_pdf, break_[KEY]_pdf)</small>',
					'class'		=> 'span12',
					'div'		=> 'span11',
					'default'	=> $row['DItemplate']['template']
				)
			);
		?>
		<?php
			echo $this->Form->input('attn',
				array(
					'type'		=> 'textarea',
					'label'		=> 'ATTN',
					'class'		=> 'span12',
					'div'		=> 'span11',
					'default'	=> $row['DItemplate']['attn']
				)
			);
		?>
		<?php
			echo $this->Form->input('preamble',
				array(
					'type'		=> 'textarea',
					'label'		=> 'Preamble',
					'class'		=> 'span12',
					'div'		=> 'span11',
					'default'	=> $row['DItemplate']['preamble']
				)
			);
		?>
		<div class="span11">
		<label>Footers</label>
		<?php
			$footers = array();
			if(!empty($row['DItemplate']['deposits_footer'])){
				
				if(substr($row['DItemplate']['deposits_footer'],0,1)=='{'){
					$footers = json_decode($row['DItemplate']['deposits_footer'], true);
				}else{
					$footers[] = $row['DItemplate']['deposits_footer'];
				}
			}
			$footers[''] = '';
				
			$counter = 0;
			if(!empty($footers)) foreach($footers as $key=>$footer){
				
				print '<div class="span12 spancol footeritem footeritem'.$counter.'"><h3 class="span1">'.($counter+1).'</h3><div class="span11 spancol">';
				echo $this->Form->input('deposits_footer.'.$counter.'.key',
					array(
						'type'		=> 'text',
						'label'		=> false,
						'class'		=> 'span12',
						'div'		=> 'span12',
						'default'	=> $key
					)
				);
				echo $this->Form->input('deposits_footer.'.$counter.'.value',
					array(
						'type'		=> 'textarea',
						'label'		=> false,
						'class'		=> 'span12',
						'div'		=> 'span12',
						'default'	=> $footer
					)
				);
				if(!$counter){
					print '<div class="span12">';
					print $this->Form->label('footer_force', 'Force this footer', array(
						'class'=>'span3',
						'style'=>'width: auto; line-height: 35px;'
					));
				
					echo  $this->Form->input('footer_force',
						array(
							'type'		=> 'checkbox',
							'label'		=> false,//'Force this footer',
							'class'		=> 'span12',
							'div'		=> 'span1',
							'value'		=> 1,
							'default'	=> $row['DItemplate']['footer_force']
						)
					);
					print '</div>';
				}
				print '</div></div><div class="span12"></div>';
				$counter++;
			}
			
			/*echo $this->Form->input('deposits_footer_new',
				array(
					'type'		=> 'textarea',
					'label'		=> 'Footers',
					'class'		=> 'span12',
					'div'		=> 'span11',
					'default'	=> $row['DItemplate']['deposits_footer']
				)
			);*/
		?></div>
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
					'default'	=> $row['DItemplate']['created']
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
					'default'	=> $row['DItemplate']['modified']
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
				<a class="btn btn-danger pull-right btn-small delete_ditemplate" style="margin-right: 5px;" data-id-ditemplate="<?php echo $id; ?>">
				    <i class="icon-trash icon-white"></i> Remove
				</a>
			<?php endif ?>
			<?php print $this->Html->link(
			    '<i class="icon-chevron-left"></i> Cancel',
			    (!empty($from))?$from:'ditemplates',
			    array('class' => 'btn pull-right btn-small btn-cancel', 'style'=>'margin-right: 5px;', 'escape'=>false)
			); ?>
		</div>
		
	<?php echo $this->Form->end() ?>
</div>
</fieldset>
<?php
echo $this->Form->create('delmanager', array('url'=>'/treasury/treasurystaticdatas/delete_ditemplate'));
echo $this->Form->input('DItemplate.dit_id', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
	'value'	=> $id,
));
echo $this->Form->end();
?>
<script>
$(document).ready(function(e){
	checkFooterForce();
	$('#DItemplateFooterForce').bind('change', function(e){ checkFooterForce(); });

	function checkFooterForce(){
		if($('#DItemplateFooterForce').is(':checked')){
			$('.footeritem').css('display', 'none');
			$('.footeritem0').css('display', '');
		}else{
			$('.footeritem').css('display', '');
		}
	}
	
	$('.delete_ditemplate').click(function (e)
	{
		if (confirm("are you sure you want to delete this entry?"))
		{
			$("#delmanagerDitemplateForm").submit();
		}
	});
})

</script>