<?= $this->Form->select('Portfolio.mandate', $mandates,
    [
        'empty' => '-- Any mandate --',
        'class' => 'w-25 form-control mr-2 py-2',
        'id'    => 'mandateid'
    ]
); ?>