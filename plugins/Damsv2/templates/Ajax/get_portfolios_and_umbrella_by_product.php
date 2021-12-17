<?= $this->Form->select('Portfolio.portfolio_id', $portfolios,
    [
        'empty' => $portfolio_empty,
        'class' => 'form-control mr-2 my-2',
        'label'    => false,
        'disabled' => $disabled
    ]
);?>