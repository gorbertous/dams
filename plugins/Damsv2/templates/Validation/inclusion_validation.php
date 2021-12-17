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
        'url'     => ['controller' => 'validation', 'action' => 'inclusion-validation', $report->report_id],
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

<h3>Inclusion validation</h3>
<hr>
<div class="row">
    <div class="col-12">
        <?php
		if (($report->status_id == 5) || ($report->status_id == 23)){
			echo '<a class="btn btn-secondary float-right ml-3" href="https://eif-alteryx-uat.theinformationlab.lu/gallery#!page/pec" >Portfolio Concentration</a>';
        } ?>
    </div>
</div>
<?= $this->Form->create(null, ['type' => 'post', 'id' => 'ReportCorrectionForm']) ?>
<?= $this->Form->input('Report.report_id', ['type' => 'hidden', 'value' => $report->report_id]) ?>

<div class="form-group row form-inline">
    <label class="col-sm-1 col-form-label h6" for="portfolio_name">Portfolio ID</label>
    <div class="col-4">
       <?= $this->Form->control('portfolio_name', [
            'label'    => false,
            'class' => 'form-control w-100 mr-2 my-2 py-2',
            'type'     => 'text',
            'disabled' => true,
            'value'    => $report->portfolio->portfolio_name,
        ]);
        ?>
    </div>
</div>
<div class="form-group row form-inline">
    <label class="col-sm-1 col-form-label h6" for="report_name">Report ID</label>
    <div class="col-4">
       <?= $this->Form->control('report_name', [
            'label'    => false,
            'class' => 'form-control w-100 mr-2 my-2 py-2',
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

<?php if (!empty($report->inclusion_notice_received)) : ?>
<div class="row">
    <div class="col-8">
        <table class="table table-bordered table-striped">
	<thead>
            <tr>
                <th>Inclusion notice validator</th>
                <th>Inclusion notice reason</th>
            </tr>
	</thead>
	<tbody>
            <tr>
                <td width="20%"><?= $report->inclusion_notice_validator ?></td>
                <td width="60%"><?= $report->inclusion_notice_reason ?></td>
            </tr>
	</tbody>
	</table>
    </div>
</div>
<?php endif ?>

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
        <?= $this->Html->link('Next', ['action' => 'validation-report/' . $report->report_id], ['class' => 'btn btn-primary my-2']) ?>
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
