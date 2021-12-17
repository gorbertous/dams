
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
        'title'   => 'Edit',
        'url'     => ['controller' => 'Import', 'action' => 'index'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
<h3>Load correction file</h3>
<hr>
<div class="alert alert-danger alert-dismissible" style="margin-top:10px;">
	<b>Warning</b>: Do not click several times on the "Save" button or refresh the page while processing modifications!
</div>

<?= $this->Form->create(null, ['id' => 'import', 'enctype' => 'multipart/form-data', 'action' => '/damsv2/import']) ?>
<?php echo $this->Form->hidden('Import.correction', array('value'=>1)) ?>
<?php echo $this->Form->hidden('Import.template_id', array('value'=>$report->template_id)) ?>
<?php echo $this->Form->hidden('Import.report.id', array('value'=>$report->report_id)) ?>
<?php echo $this->Form->hidden('Import.portfolio_id', array('value'=>$portfolio_id)) ?>
<?php echo $this->Form->hidden('Import.sheet', array('value'=>$sheet)) ?>
<?php echo $this->Form->hidden('Import.type', array('value'=>$type)) ?>

<div class="control-group">
	<label class="col-form-label required" for="ReportName">Correction file </label>
	<div class="controls">
		<?php echo $this->Form->input('Import.file', array('type' => 'file', 'required'=>true)); ?>
	</div>
</div>

<?php echo $this->Form->submit('Run correction', array('div' => false,'class'=>'btn btn-primary', 'id' => 'submit_id')) ?>
<?php echo $this->Html->link('Cancel', '/damsv2/import', array('class' => 'btn btn-danger my-3')) ?>
<?php echo $this->Form->end(); ?>

<?= $this->Form->end() ?>
