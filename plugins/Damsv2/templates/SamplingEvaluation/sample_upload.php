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
        'title'   => 'Sampling information update',
        'url'     => ['controller' => 'sampling-evaluation', 'action' => 'sample-upload'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
 <h3>Sampling information update</h3>
 <hr>
 
<?php if (empty($correction)): ?>
    <div class="row">
        <div class="col-9">
            <p>Update the database with the sampling information. Download template <a href="/damsv2/ajax/download-file/Sampling_update_template.xlsx/sampling">here</a></p>
        </div>
    </div>
<?php endif ?>
 
<?php if (!empty($sasResult)): ?>
    <?= $sasResult ?>
    <p>Please save the XLSX error file as an XLS file for the upload</p>
    <div class="col-6 form-inline">
        <?= $this->Html->link('Reload', ['action' => 'sample-upload',$version, $correction], ['class' => 'btn btn-primary mr-3  my-3']) ?>
        <?= $this->Html->link('Cancel', ['action' => 'sample-upload'], ['class' => 'btn btn-danger my-3']) ?>

    </div>
<?php else: ?>
    <?= $this->Form->create(null, ['id' => 'manualsampling', 'enctype' => 'multipart/form-data']) ?>
    <label class="col-sm-3 col-form-label h6 required">File (xls or xlsx)</label>
    <?= $this->Form->control('sampleupdate.file', ['type' => 'file', 'label' => false, 'class' => 'form-control-file mb-3', 'required' => true]); ?>
    <?= $this->Form->hidden('sampleupdate.version', ['value' => $version])?>
    <?= $this->Form->hidden('sampleupdate.correction', ['value' => $correction])?>
    <div class="col-6 form-inline">
        <?php
		$disabled = !$perm->hasWrite(array('action' => 'sampleUpload'));
		$class    = 'btn btn-primary form-control mr-3  my-3';
		if($disabled)
		{
			$class    = 'btn btn-primary form-control mr-3  my-3 disabled';
		}
		echo $this->Form->submit('Upload', [
            'class'    => $class,
            'id'       => 'save_button',
			'disabled' => $disabled,
        ])
        ?>
        <?= $this->Html->link('Cancel', ['controller' => 'Home', 'action' => 'home'], ['class' => 'btn btn-danger form-control my-3']) ?>
    </div>
    <?= $this->Form->end(); ?>
<?php endif ?>
