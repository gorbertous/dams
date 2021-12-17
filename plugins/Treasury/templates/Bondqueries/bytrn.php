<?php 
	echo $this->Html->css('/treasury/css/dataTableSort');
?>
<div class="well">
   <div id="form">
	   <?php echo $this->Form->create('tqbytrn') ?>
	   <?php echo $this->Form->input(
        	'tr_number', array(
            'label'     => 'Transaction Number(s) seperated by commas (TRN) :',
            'required'  => true,
    		));
			?>
    <div id="queryTrnResult" style="overflow:auto;"></div>
  </div>
</div>

<!-- Ajax script used for displaying query result based on tr_number -->
<?php
$this->Js->get('#tqbytrnTrNumber')->event('keyup',
	$this->Js->request(
		array(
			'controller'	=>	'treasuryqueries',
			'action'		=>	'showquerybytrn'
			),
		array(
			'update'		=>	'#queryTrnResult',
			'async' 		=> 	true,
			'method' 		=> 	'post',
			'dataExpression'=>	true,
			'data'=> $this->Js->serializeForm(
				array(
					'isForm' => false,
					'inline' => true
					)
				)
			)
		)
	);
?>