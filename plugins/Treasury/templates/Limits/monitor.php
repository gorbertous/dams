<?php
	echo $this->Html->css('/treasury/css/redmond/jquery-ui-1.10.3.custom.css');
	echo $this->Html->script('/treasury/js/jquery-ui-1.10.3.custom.min.js');
?>
<fieldset>
<legend>
<?php echo $this->Form->create('mandategroup'); ?>
Limits / Exposure as of 
<?php 
	echo $this->Form->input('currentdate', array(
			'name'=> 'data[mandategroup][currentdate]',
			'div'=>false,
			'label'	=> false,
			'data-date-format'	=> 'dd/mm/yyyy',
			'default'=>date('d/m/Y')
	));
	echo $this->Form->end();
?>
</legend>
<div id='limits_dashboard'></div>

<?php
	$this->Js->get('#mandategroupCurrentdate')->event('change',
		$this->Js->request(
			array(
				'controller'=>'treasurylimits',
				'action'=>'monitorAjax'
			),
			array(
				'update'=>'#limits_dashboard',
				'async' => false,
				'method' => 'post',
				'dataExpression'=>true,
				'data'=> $this->Js->serializeForm(
					array(
						'isForm' => false,
						'inline' => true,
					)
				)
			)
		)
	);
?>
</fieldset>
<style>
	#mandategroupCurrentdate{ margin-left: 10px; width: 100px; }
</style>
<script>
	$(document).ready(function(){
		$('#mandategroupCurrentdate').datepicker({ dateFormat: "dd/mm/yy" });
		$('#mandategroupCurrentdate').bind('change', function(e){
			$('#limits_dashboard').html('');
		});
		$('#mandategroupMonitorformForm').bind('submit', function(e){
			$('#mandategroupCurrentdate').trigger('change');
			e.preventDefault();
			return false;
		})

		//default date on load
		setTimeout(function(){ $('#mandategroupCurrentdate').trigger('change'); }, 100);
		
	});
</script>