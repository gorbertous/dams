<?= $this->Form->select('Portfolio.portfolio_id', $portfolios,[
	'label'		=> false, 
	'empty' 	=> $portfolio_empty,
	'options' 	=> $portfolios,
]); ?>