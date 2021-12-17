<?php echo $this->Form->input('Report.Country', array(
	'options'	=> $countries,
	'required'  => true,
	'multiple'	=> true
)) ?>