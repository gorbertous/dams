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
        'title'   => $report->report_name,
        'url'     => ['controller' => 'report', 'action' => 'exceeded-total-principal-disbursment', $report->report_id],
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
<a class="btn btn-secondary float-right ml-5" href="/damsv2/report/inclusion_validation/<?= $report->report_id ?>">Back to Inclusion validation</a>
<h3>The reason for exceeding Total principal disbursement:</h3>

<hr>

<?= $this->Form->create(null, ['type' => 'post', 'id' => 'Damsv2.mpv_form', 'onsubmit' => '']) ?>
<?= $this->Form->input('report_id', ['type' => 'hidden', 'value' => $report->report_id]) ?>


<div class="row">
    <div class="col-6">
        <?= $this->Form->textarea('Report.comments', ['rows' => '5', 'cols' => '5', 'class' => 'form-control', 'label' => false]); ?>
    </div>
</div>

<div class="row">
    <div class="col-6 form-inline">
        <?= $this->Html->link('Cancel', ['action' => 'inclusion-validation', $report->report_id], ['class' => 'btn btn-danger form-control mr-3 my-3']) ?>

        <?=
        $this->Form->submit('Proceed', [
            'class'    => 'btn btn-success form-control my-3',
            'disabled' => ($apvExceeded === true),
            'name'     => 'save_comment',
            'id'       => 'save_comment',
        ])
        ?>

    </div>
</div>

<?= $this->Form->end() ?>


<script>
$(document).ready(function(){
    $("form").submit(function (event) {
        document.getElementById("save_comment").disabled = true;
    });
});
</script>