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
        'url'     => ['controller' => 'validation', 'action' => 'inclusion-validation', $report->report_id],
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
        'url'     => ['controller' => 'validation', 'action' => 'validation-report', $report->report_id],
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
        'url'     => ['controller' => 'validation', 'action' => 'waiver-reason-view', $report->report_id],
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
        'url'     => ['controller' => 'validation', 'action' => 'draft-validation', $report->report_id],
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
        <?= $this->Form->control('Report.comment_validator2', ['type' => 'textarea', 'rows' => '10', 'class' => 'form-control', 'label' => false]) ?>
    </div>
</div>
<div class="row">
    <div class="col-6 form-inline">
        <?= $this->Form->Html->link('Back', ['action' => 'waiver-reason-view', $report->report_id], ['class' => 'btn btn-secondary mr-5']) ?>
        <?= $this->Form->submit('Validate report', [
            'class' => 'btn btn-success',
            'id'    => 'save_comment',
            'disabled'	=> (!$perm->hasWrite(array('controller' => 'Validation', 'action' => 'draftValidation')) || ($current_user_id == $draft_user_id)),
        ])
        ?>
    </div>
</div>
<?= $this->Form->end() ?>

<?php
    if ($current_user_id == $draft_user_id) {
        echo '<p>You have submitted the draft report. Second validation has to be performed by another user.</p>';
    }
    //        if (in_array("DAMS_inclusion_operator", $user_profiles)) {
    //            echo '<p>User with "DAMS inclusion operator" access profile cannot validate inclusion reports.</p>';
    //        }
?>


<script>
    $(document).ready(function () {
        $("form").submit(function (e)
        {
            document.getElementById("save_comment").disabled = true;
        });
    });
</script>