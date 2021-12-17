<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SamplingEvaluation $samplingEvaluation
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Transactions Sampling information update',
        'url'     => ['controller' => 'sampling-evaluation', 'action' => 'transactions-update'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);

$button_title = !empty($correction) && ($correction != 0) && ($correction != '0') ? 'Reload' : 'Upload';
?>
 <h3>Transactions Sampling information update</h3>
 <hr>
 
<?php if (empty($correction)): ?>
    <div class="row">
        <div class="col-9">
            <p>Update the database with the manual sampling flag. Download the template <a href="/damsv2/ajax/download-file/TRN_sampling_template.xls/sampling">here</a></p>
        </div>
    </div>
<?php endif ?>
 
<?php if (!empty($sasResult)): ?>
    <?= $sasResult ?>
    <p>Please save the XLSX error file as an XLS file for the upload</p>
    <div class="col-6 form-inline">
        <?= $this->Html->link('Reload', ['action' => 'manual-sampling',$version, $correction], ['class' => 'btn btn-primary mr-3  my-3']) ?>
        <?= $this->Html->link('Cancel', ['action' => 'manual-sampling'], ['class' => 'btn btn-danger my-3']) ?>

    </div>
<?php else: ?>
    <?= $this->Form->create(null, ['id' => 'transactionupdate', 'enctype' => 'multipart/form-data']) ?>
    <label class="col-sm-3 col-form-label h6 required">File (xls or xlsx)</label>
    <?= $this->Form->control('transactionupdate.file', ['type' => 'file', 'label' => false, 'class' => 'form-control-file mb-3', 'required' => true]); ?>
    <?= $this->Form->hidden('transactionupdate.version', ['value' => $version])?>
    <?= $this->Form->hidden('transactionupdate.correction', ['value' => $correction])?>
    <div class="col-6 form-inline">
        <?php
		$disabled = !$perm->hasWrite(array('action' => 'transactionUpdate'));
		$class    = 'btn btn-primary form-control mr-3  my-3';
		if($disabled)
		{
			$class    = 'btn btn-primary form-control mr-3  my-3 disabled';
		}
		echo $this->Form->submit($button_title, [
            'class'    => $class,
            'id'       => 'reloadbutton',
			'disabled' => $disabled,
        ])
        ?>
        <?= $this->Html->link('Cancel', ['controller' => 'Home', 'action' => 'home'], ['class' => 'btn btn-danger form-control my-3']) ?>
    </div>
    <?= $this->Form->end(); ?>
<?php endif ?>
