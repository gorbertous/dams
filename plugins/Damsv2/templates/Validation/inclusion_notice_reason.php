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
        'title'   => 'Inclusion notice follow up',
        'url'     => ['controller' => 'validation', 'action' => 'inclusion-notice-reason'],
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

<h3>Inclusion notice reason</h3>

<hr>
<?= $this->Form->create(null, ['id' => 'form_notif']) ?>

<div class="row">
    <div class="col-6 form-group">
        <h4>The reason for validating the report without notice:</h4>
        <?= $this->Form->hidden('Report.report_id', ['value' => $report->report_id]); ?>
        <?= $this->Form->control('Report.inclusion_notice_reason', ['type' => 'textarea', 'value' => $report->inclusion_notice_reason, 'rows' => '5', 'cols' => '5', 'class' => 'form-control', 'label' => false]); ?>
    </div>
</div>
<div class="row">
    <div class="col-6 form-inline">
        <?= $this->Form->Html->link('Back', ['action' => 'inclusion-validation', $report->report_id], ['class' => 'btn btn-secondary mr-2']) ?>
        <?= $this->Form->button('Next', ['controller' => 'Validation', 'action'=>'inclusion-notice-reason/'.$report_id,'class'=>'btn btn-primary', 'id' => 'notif_valid']) ?>
    </div>
</div>
<?= $this->Form->end() ?>

<script>
$(document).ready(function(){
    $('#notif_valid').click(function(e){
        $('#form_notif').submit();
    });
});
</script>