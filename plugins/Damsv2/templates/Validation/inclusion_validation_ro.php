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
        'title'   => $report->report_name,
        'url'     => ['controller' => 'validation', 'action' => 'inclusion-validation-ro', $report->report_id],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-inv-crumb'
            ]
        ]
    ]
])
?>

<h3>Inclusion validation</h3>
<hr>

<?= $this->Form->create(null, ['type' => 'post', 'id' => 'ReportCorrectionForm']) ?>
<?= $this->Form->input('Report.report_id', ['type' => 'hidden', 'value' => $report->report_id]) ?>
<div class="row">
    
    <div class="col-6">
        <?= $this->Form->control('portfolio_name', [
            'label'    => 'Portfolio ID',
            'class' => 'form-control mr-2 my-2',
            'type'     => 'text',
            'disabled' => true,
            'value'    => $report->portfolio->portfolio_name,
        ]);
        ?>
    </div>
</div>
<div class="row">
    
    <div class="col-6">
        <?= $this->Form->control('report_name', [
            'label'    => 'Report ID',
            'class' => 'form-control mr-2 my-2',
            'type'     => 'text',
            'disabled' => true,
            'value'    => $report->report_name,
        ]);
        ?>
    </div>
</div>
<?= $this->Form->end() ?>

<div class="row">
    <div id="sasResults" class="col-12">
        <?= $result ?>
    </div>
</div>

<?php if ($modifications_expected == 'Y') : ?>
<div class="row">
    <div class="col-8 ml-5">
        <?= $this->Form->checkbox('modifications_expected', ['checked ' => true, 'id ' => 'mod_expected', 'class' => 'form-check-input'])?>
        <label class="form-check-label" for="mod_expected">F sheet modifications identified in monitoring visit follow up letter have been processed. <a class="ml-2" href="<?= $m_files_link; ?>">M-files link</a></label>
       
    </div>
</div>
<?php endif ?>

<div class="row">
    <div class="col-6 form-inline">
        <?= $this->Html->link('Back', ['controller' => 'Report', 'action' => 'inclusion'], ['class' => 'btn btn-secondary mr-2 my-2']) ?>
        <?= $this->Html->link('Next', ['action' => 'validation-report-ro/' . $report->report_id], ['class' => 'btn btn-primary my-2']) ?>
    </div>
</div>


<script>
    $(document).ready(function () {
        $("#ReportCorrectionForm").submit(function (event) {

            $("#ReportCorrectionForm [required]").each(function () {
                error = true;
                if ($(this).attr('id')) {
                    if ($(this).val()) {
                        $(this).css('border-color', 'rgb(204, 204, 204)');
                        error = false;
                    } else {
                        $(this).css('border', '1px solid red');
                    }
                }
            });

            if (error) {
                $(".alert").show().fadeOut(4000);
                return false;
            }

        });
    });
</script>
