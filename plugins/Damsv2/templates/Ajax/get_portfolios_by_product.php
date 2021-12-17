<?= $this->Form->select('Portfolio.portfolio_id', $portfolios,
        [
            'empty' => '-Any portfolio-',
            'class' => 'form-control mr-2 my-2',
            'id'    => 'portfolioid'
        ]
);?>
