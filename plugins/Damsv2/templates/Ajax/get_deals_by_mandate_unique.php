<?= $this->Form->select('Portfolio.deal_name', $deals,
        [
            'empty' => '--Select a deal--',
            'class' => 'form-control mr-2 my-2',
            'id'    => 'deal_id',
            'required' => true,
			'style'	=> 'width:220px;',
        ]
);?>