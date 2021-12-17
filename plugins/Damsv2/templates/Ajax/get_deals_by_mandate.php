<?= $this->Form->select('Portfolio.deal_name', $deals,
        [
            //'empty' => '-Any deal-',
            'class' => 'form-control mr-2 my-2',
            'id'    => 'deal_id',
            'multiple' => true,
			'style'	=> 'width:220px;',
        ]
);?>