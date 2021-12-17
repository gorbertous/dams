<?php
/**
 * @var \App\View\AppView $this
 * @var \Dsr\Model\Entity\Loan $loan
 * @var \Cake\Collection\CollectionInterface|string[] $reports
 * @var \Cake\Collection\CollectionInterface|string[] $portfolios
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Loans',
        'url'     => ['controller' => 'Loans', 'action' => 'index'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'New Loan',
        'url'     => ['controller' => 'Loans', 'action' => 'add'],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-port-crumb'
            ]
        ]
    ]
]);
?>
<div class="row">

    <div class="col-4">
        <?= $this->Form->create($loan) ?>

        <legend><?= __('New Loan') ?></legend>
        <?php
        echo $this->Form->control('report_id', ['options' => $reports, 'empty' => true, 'class' => 'form-control mb-3']);
        echo $this->Form->control('portfolio_id', ['options' => $portfolios, 'empty' => true, 'class' => 'form-control mb-3']);
        echo $this->Form->control('deal_name', ['class' => 'form-control mb-3']);
        echo $this->Form->control('start_year', ['type' => 'number','class' => 'form-control mb-3']);
        echo $this->Form->control('end_year', ['type' => 'number','class' => 'form-control mb-3']);
        echo $this->Form->control('loan_reference', ['class' => 'form-control mb-3']);
        echo $this->Form->control('file_reference', ['class' => 'form-control mb-3']);
        echo $this->Form->control('intermediary', ['class' => 'form-control mb-3']);
        echo $this->Form->control('gender', ['type' => 'number','class' => 'form-control mb-3']);
        echo $this->Form->control('employment', ['type' => 'number','class' => 'form-control mb-3']);
        echo $this->Form->control('education', ['type' => 'number','class' => 'form-control mb-3']);
        echo $this->Form->control('age', ['type' => 'number','class' => 'form-control mb-3']);
        echo $this->Form->control('specific_group', ['type' => 'number','class' => 'form-control mb-3']);
        echo $this->Form->control('country', ['class' => 'form-control mb-3']);
        echo $this->Form->control('region', ['class' => 'form-control mb-3']);
        echo $this->Form->control('total_employees', ['type' => 'number','class' => 'form-control mb-3']);
        echo $this->Form->control('total_male', ['type' => 'number','class' => 'form-control mb-3']);
        echo $this->Form->control('total_female', ['type' => 'number','class' => 'form-control mb-3']);
        echo $this->Form->control('total_less_25', ['type' => 'number','class' => 'form-control mb-3']);
        echo $this->Form->control('total_25_54', ['type' => 'number','class' => 'form-control mb-3']);
        echo $this->Form->control('total_more_55', ['type' => 'number','class' => 'form-control mb-3']);
        echo $this->Form->control('total_minority', ['type' => 'number','class' => 'form-control mb-3']);
        echo $this->Form->control('total_disabled', ['type' => 'number','class' => 'form-control mb-3']);
        echo $this->Form->control('expost_total_employees', ['type' => 'number','class' => 'form-control mb-3']);
        ?>

        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-outline-secondary my-3']); ?> 
        <?= $this->Form->end() ?>
    </div>

</div>
