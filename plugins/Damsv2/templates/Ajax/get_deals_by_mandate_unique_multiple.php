<label class="col-form-label required" for="PortfolioName">Deal</label>
<div class="controls">
	<?php echo $this->Form->input('Portfolio.deal_name', array(
		'options'	=> $deals,
		'required'  => true,
		'multiple'	=> false,
		'style' => 'width:50em;',
		'size'	=>	'1',
        'empty' => "Please choose one or several deals",
	)) ?>
</div>