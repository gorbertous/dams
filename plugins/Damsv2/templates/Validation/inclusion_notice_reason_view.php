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
        'url'     => ['controller' => 'report', 'action' => 'inclusion'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Inclusion Validation',
        'url'     => ['controller' => 'report', 'action' => 'inclusion-validation', $report->report_id],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-inv-crumb'
            ]
        ]
    ],
    [
        'title'   => 'Inclusion notice reason',
        'url'     => ['controller' => 'validation', 'action' => 'inclusion-notice-reason-view', $report->report_id],
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

<h3>The reason for validating the report without notice:</h3>

<hr>
<?= $this->Form->create(null, ['type' => 'post', 'id' => 'form_notif']) ?>
<?= $this->Form->input('Report.report_id', ['type' => 'hidden', 'value' => $report->report_id]) ?>

<div class="row">
    <div class="col-6">
        <?= $this->Form->control('Report.inclusion_notice_reason', ['type' => 'textarea', 'value' => $report->inclusion_notice_reason, 'rows' => '5', 'cols' => '5', 'class' => 'form-control', 'label' => false, 'disabled' => true]); ?>
    </div>
</div>
<div class="row my-2">
    <div class="col-6">
        <?php
        if (!empty($report->inclusion_notice_validator1)) {
            echo '<h4>Report validated by ' . $report->inclusion_notice_validator1 . '</h4>';
        } else {
            echo '<h4>Follow up notice validated by ' . $report->inclusion_notice_validator . '</h4>';
        }
        ?>
    </div>
</div>
<div class="row">
    <div class="col-6 form-inline">
        <?= $this->Html->link('Back', array('controller' => 'Validation', 'action' => 'validation-report/' . $report_id), array('class' => 'btn btn-secondary form-control my-3')) ?>
        <?= $this->Form->button('Next', array('controller' => 'Validation', 'action' => 'inclusion-notice-reason-view/' . $report_id, 'class' => 'btn btn-primary form-control ml-2 my-3', 'id' => 'notif_valid')); ?>
    </div>
</div>


<?= $this->Form->end() ?>


<script>
    $(document).ready(function() {

        $('#notif_valid').click(function(e) {
            $('#form_notif').submit();
        });
    });
</script>