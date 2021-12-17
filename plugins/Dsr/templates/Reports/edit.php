<?php
/**
 * @var \App\View\AppView $this
 * @var \Dsr\Model\Entity\Report $report
 * @var string[]|\Cake\Collection\CollectionInterface $portfolios
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Reports',
        'url'     => ['controller' => 'Reports', 'action' => 'index'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => $report->portfolio->name,
        'url'     => ['controller' => 'Reports', 'action' => 'edit', $report->id],
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

        <?= $this->Form->create($report) ?>

        <legend><?= __('Edit Report') ?></legend>
        <?php
        echo $this->Form->control('portfolio_id', ['options' => $portfolios, 'class' => 'form-control mb-3', 'empty' => true]);
        echo $this->Form->control('period_quarter', ['class' => 'form-control mb-3']);
        echo $this->Form->control('period_year', ['class' => 'form-control mb-3']);
        echo $this->Form->control('report_date', ['class' => 'form-control mb-3']);
        ?>

        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-outline-secondary my-3']); ?> 
        <?= $this->Form->end() ?>

    </div>
</div>
