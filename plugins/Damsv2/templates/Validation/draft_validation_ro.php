<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Report $report
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Inclusion Dashboard',
        'url'     => ['controller' => 'Report', 'action' => 'inclusion'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Inclusion Validation',
        'url'     => ['controller' => 'validation', 'action' => 'inclusion-validation-ro', $report->report_id],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-inv-crumb'
            ]
        ]
    ],
    [
        'title'   => 'Validation Report',
        'url'     => ['controller' => 'validation', 'action' => 'validation-report-ro', $report->report_id],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-inv-crumb'
            ]
        ]
    ],
    [
        'title'   => 'Waiver Reason',
        'url'     => ['controller' => 'validation', 'action' => 'waiver-reason-ro', $report->report_id],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-inv-crumb'
            ]
        ]
    ],
    [
        'title'   => $report->report_name,
        'url'     => ['controller' => 'validation', 'action' => 'draft-validation-ro', $report->report_id],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-inv-crumb'
            ]
        ]
    ]
]);
?>

<h3>Validation of draft report</h3>
<hr>

<div class="row">
    <div class="col-6 font-italic">
        <p>By validating this report,
            the validator confirms that the report has been verified for the inclusion,
            based on the data provided by an intermediary,
            in terms of draft report data quality checks and aggregate portfolio figures,
            and that any needed adjustments to portfolio level information have been processed.
        </p>
    </div>
</div>

<?= $this->Form->create(null, ['id' => 'validationDraft']) ?>
<div class="row">
    <div class="col-6 form-group">
        <?= $this->Form->hidden('Report.report_id', ['value' => $report->report_id]); ?>
        <?= $this->Form->control('Report.comment_validator2', ['type' => 'textarea', 'disabled' => true, 'value' => $report->comments_validator2, 'rows' => '10',  'class' => 'form-control', 'label' => false]) ?>
    </div>
</div>
<div class="row">
    <div class="col-6 form-group">
        Validated by <?php
            echo $user_validator2->full_name ?>
            <br />
        Last modification on <?php echo $report->modified->format('d/m/Y H:i:s') ?>
    </div>
</div>
<div class="row">
    <div class="col-6 form-inline">
        <?= $this->Form->Html->link('Back', ['action' => 'comment', $report->report_id], ['class' => 'btn btn-secondary mr-5']) ?>
        <?= $this->Form->Html->link('Next', ['controller' => 'validation', 'action' => 'inclusion-validation-ro', $report->report_id], ['class' => 'btn btn-success', 'disabled' => true]) ?>
    </div>
</div>
<?= $this->Form->end() ?>


<script>
    $(document).ready(function () {
        $("form").submit(function (e)
        {
            document.getElementById("save_comment").disabled = true;
        });
    });
</script>
