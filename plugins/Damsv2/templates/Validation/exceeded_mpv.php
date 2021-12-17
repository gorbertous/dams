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
        'title'   => $report->report_name,
        'url'     => ['controller' => 'validation', 'action' => 'exceeded-mpv', $report->report_id],
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

<h3>The reason for exceeding Agreed Portfolio Volume:</h3>
<hr>
<?= $this->Form->create(null, ['id' => 'Damsv2.mpv_form']); ?>
<?= $this->Form->hidden('report_id', ['value' => $report->report_id]); ?>
<div class="row">
    <div class="col-6">
        <?= $this->Form->control('Report.comments',
                ['type' => 'textarea', 'rows' => '5', 'label' => false, 'class' => 'form-control mb-3', 'value' => $report->agreed_pv_comments]);
        ?>
    </div>

</div>

<div class="row">
    <div class="col-6">
        <?= $this->Html->link('Back', ['action' => 'inclusion_validation', $report->report_id], ['class' => 'btn btn-secondary mr-2']) ?>
        <?= $this->Html->link('Next', ['action' => 'waiver_reason_view', $report->report_id], ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?= $this->Form->end() ?>

<script language="javascript" type="text/javascript">
    $(document).ready(function () {
        $("fieldset form").submit(function (event) {
            document.getElementById("save_comment").disabled = true;
        });
    });
</script>