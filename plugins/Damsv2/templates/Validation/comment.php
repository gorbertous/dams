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
        'title'   => 'Waiver Reasons',
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
        'url'     => ['controller' => 'validation', 'action' => 'comment', $report->report_id],
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

<h3>Comments</h3>
<hr>
<?= $this->Form->create(null, ['id' => 'Damsv2.validationDraft']); ?>
<?= $this->Form->hidden('report_id', ['value' => $report->report_id]); ?>
<div class="row">
    <div class="col-6">
        <?php
        /* if (!empty($report['Report']['comment_validator2']))// stays in its own screen
          {
          echo '<h6>Second validator comment</h6>';
          echo '<div class="control-group">';
          echo $this->Form->control('Report.comment_validator2',
          ['type' => 'textarea','rows' => '5','cols' => '10','label' => false, 'value' => $report->comment_validator2]);
          echo '</div>';
          } */

        if (!empty($report->comments)) {
            echo '<h6>Fi responsiveness comment</h6>';
            echo $this->Form->control('Report.comments',
                    ['type' => 'textarea', 'rows' => '5', 'cols' => '10', 'label' => false, 'class' => 'form-control mb-3', 'value' => $report->comments]);
        }

        if (!empty($report->agreed_pv_comments)) {
            echo '<h6>Agreed PV comment</h6>';
            echo $this->Form->control('Report.agreed_pv_comments',
                    ['type' => 'textarea', 'rows' => '5', 'cols' => '10', 'label' => false, 'class' => 'form-control mb-3', 'value' => $report->agreed_pv_comments]);
        }

        if (!empty($report->total_disbursement_comments)) {
            echo '<h6>Total disbursment comment</h6>';
            echo $this->Form->control('Report.total_disbursement_comments',
                    ['type' => 'textarea', 'rows' => '5', 'cols' => '10', 'label' => false, 'class' => 'form-control mb-3', 'value' => $report->total_disbursement_comments]);
        }
        ?>
    </div>

</div>
<div class="row">
    <div class="col-6">
        <?= $this->Html->link('Back', ['action' => 'waiver-reason-ro', $report->report_id], ['class' => 'btn btn-secondary mr-2']) ?>
        <?= $this->Html->link('Next', ['action' => 'draft-validation-ro', $report->report_id], ['class' => 'btn btn-primary']) ?>

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