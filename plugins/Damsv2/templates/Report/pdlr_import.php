<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Report[]|\Cake\Collection\CollectionInterface $report
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Payment Demands/Recoveries dashboard',
        'url'     => ['controller' => 'Report', 'action' => 'pdlr'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Import of PD/LR file',
        'url'     => ['controller' => 'Report', 'action' => 'pdlr-import', $report->report_id],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-port-crumb'
            ]
        ]
    ]
]);

//set owners full name
//debug($report);
$username = '- -';
if (!empty($report->v_user))
{
	$username = $report->v_user->last_name . ' ' . $report->v_user->first_name;
}
?>

<h3>Import of PD/LR file</h3>
<hr>

<div class="row">
    <div class="col-6">

        <?= $this->Form->create(null, ['id' => 'Damsv2.Report', 'enctype' => 'multipart/form-data']) ?>
        <?= $this->Form->hidden('Template.type_id', ['value' => $report->template->template_type_id]) ?>
        <?= $this->Form->hidden('Template.id', ['value' => $report->template_id]) ?>
        <?= $this->Form->hidden('Report.id', ['value' => $report->report_id]) ?>
        <?php
        if ($report->template->template_type_id == 2):
            echo $this->Form->hidden('Report.sheets', ['value' => 'PD']);
        else:
            echo $this->Form->hidden('Report.sheets', ['value' => 'LR']);
        endif;
        ?>

        <?= $this->Form->control('Portfolio.portfolio_name', ['type' => 'text', 'class' => 'form-control mb-3', 'default' => $report->portfolio->portfolio_name, 'disabled' => true]); ?>

        <?= $this->Form->control('Report.due_date', ['type' => 'text', 'label' => 'Due Date', 'class' => 'form-control mb-3 datepicker', 'value' => !empty($report->due_date) ? $report->due_date->format('Y-m-d') : '']);?>

        <div class="row">
            <div class="col-6">
                <?= $this->Form->control('Report.period_quarter', ['type' => 'text', 'class' => 'form-control mb-3', 'default' => $report->period_quarter, 'disabled' => true]); ?>
            </div>
            <div class="col-6">
                <?= $this->Form->control('Report.period_year', ['type' => 'text', 'class' => 'form-control mb-3', 'default' => $report->period_year, 'disabled' => true]); ?>
            </div>
        </div>
        <?= $this->Form->control('Report.owner', ['class' => 'form-control mb-3', 'type' => 'text', 'value' => $username, 'disabled' => true]); ?>

        <?= $this->Form->control('Report.report_name', ['type' => 'text', 'class' => 'form-control mb-3', 'default' => $report->report_name, 'disabled' => true]); ?>

        <?= $this->Form->control('Report.header', ['type' => 'checkbox', 'label' => ' File includes header', 'checked' => true]); ?>

        <div class="form-group row form-inline">
            <label class="col-sm-4 col-form-label required" for="file">Report File</label>
            <div class="col-6">
                 <?= $this->Form->control('Import.file', ['type' => 'file', 'label' => false, 'class' => 'form-control-file my-2 mb-3', 'required' => true]); ?>
            </div>
        </div>
        

        <?= $this->Form->control('Report.description', ['type' => 'textarea', 'class' => 'form-control mb-3']); ?>


        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary my-3', 'id' => 'upload_report', 'name' => 'upload_report']); ?>
        <?= $this->Html->link('Cancel', ['action' => 'pdlr'], ['class' => 'btn btn-secondary']) ?>

        <?= $this->Form->end() ?>
    </div>
</div>
