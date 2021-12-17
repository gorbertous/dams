<?php

/**
 * @var \App\View\AppView $this
 * @var \Dsr\Model\Entity\Report $report
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
        'title'   => 'Import',
        'url'     => ['controller' => 'Reports', 'action' => 'import'],
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

<h3><?= __('Import Report'); ?></h3>
<hr>

<?= $this->Form->create(null, ['id' => 'import', 'enctype' => 'multipart/form-data']) ?>

<div class="row col-6 form-inline">
    <?= $this->Form->label('portfolio_id', 'Portfolio', ['class' => 'col-sm-3 col-form-label h6 my-2 required']); ?>
    <?= $this->Form->select(
        'portfolio_id',
        $portfolios,
        [
            'empty' => '-- Any portfolio --',
            'class' => 'form-control ml-2 my-2',
            'label'    => false,
        ]
    );
    ?>
</div>

<div class="row col-6 form-inline">
    <?= $this->Form->label('report_date', 'Report Date', ['class' => 'col-sm-3 col-form-label h6 my-2 required']); ?>
    <?= $this->Form->input('report_date',  [
        'class'    => 'form-control datepicker ml-2 my-2',
        'id'   => 'ReportReportDate',
        'required' => true,
    ]);
    ?>
</div>

<div class="row col-6 form-inline">
    <?= $this->Form->label('file', 'File', ['class' => 'col-sm-3 col-form-label h6 my-2 required']); ?>
    <?= $this->Form->control('file', ['type' => 'file', 'class' => 'form-control-file ml-2 my-2', 'required' => true, 'label' => false]); ?>
</div>

<div class="row col-6">
    <?= $this->Form->submit('Submit', [
        'class'    => 'btn btn-primary form-control  ml-2 my-3',
        'id'       => 'upload_report',
    ])
    ?>
</div>

<?= $this->Form->end() ?>


<script>
    $(document).ready(function() {
        $('#ReportReportDate').datepicker({
            dateFormat: "yy-mm-dd"
        });
    });
</script>