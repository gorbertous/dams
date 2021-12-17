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
        'url'     => ['controller' => 'report', 'action' => 'fi-responsivness', $report->report_id],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-inv-crumb'
            ]
        ]
    ]
]);
$link = '';
?>
<a class="btn btn-secondary float-right ml-5" href="/damsv2/report/inclusion_validation/<?= $report->report_id ?>">Back to Inclusion validation</a>
<h3>FI responsiveness and cooperation for this inclusion was:</h3>

<hr>
<?= $this->Form->create(null, ['type' => 'post', 'id' => 'Damsv2.fi_form']) ?>
<?= $this->Form->input('Report.report_id', ['type' => 'hidden', 'value' => $report->report_id]) ?>
<div class="row">
    <div class="col-6 pl-3">
        <?= $this->Form->radio('Report.responsiveness', ['Good' => 'Good', 'Fair' => 'Fair', 'Poor' => 'Poor'],
                ['required' => true, 'id' => 'responsiveness', 'label' => '','class' => 'ml-3 py-2'])
        ?>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <?= $this->Form->label('Report.comments', 'Additional comments:', ['class' => 'h6 my-2 py-2']); ?>
        <?= $this->Form->control('Report.comments', ['type' => 'textarea', 'rows' => '5', 'cols' => '5', 'class' => 'form-control', 'label' => false]); ?>
    </div>
</div>
<div class="row">
    <div class="col-6 form-inline">

        <?=
        $this->Form->submit('Save the inclusion', [
            'class'    => 'btn btn-success form-control mr-3  my-3',
            'disabled' => false,
            'name'     => 'save_inclusion',
            'id'       => 'save_inclusion',
        ])
        ?>
        <?= $this->Html->link('Cancel', ['action' => 'inclusion_validation', $report->report_id], ['class' => 'btn btn-danger form-control my-3']) ?>

    </div>
</div>

<?= $this->Form->end() ?>


<script language="javascript" type="text/javascript">
    $(document).ready(function () {
        $("fieldset form").submit(function (event) {
            document.getElementById("save_inclusion").disabled = true;
        });
    });
</script>
