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
        'title'   => 'Manual PDs sampling',
        'url'     => ['controller' => 'sampling-evaluation', 'action' => 'manual-sampling'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
 <h3>Manual PDs sampling</h3>
 <hr>
 
<?php if (empty($correction)): ?>
    <div class="row">
        <div class="col-9">
            <p>Update the database with the manual sampling flag. Download the template <a href="/damsv2/ajax/download-file/Manual_sampling_template.xlsx/sampling">here</a></p>
        </div>
    </div>
<?php endif ?>
 
<?php if (!empty($sasResult)): ?>
    <?= $sasResult ?>
    <p>Please save the XLSX error file as an XLS file for the upload</p>
    <div class="col-6 form-inline">
        <?= $this->Html->link('Sampling', ['action' => 'manual-sampling',$version, $correction], ['class' => 'btn btn-primary mr-3  my-3']) ?>
        <?= $this->Html->link('Cancel', ['action' => 'manual-sampling'], ['class' => 'btn btn-danger my-3']) ?>

    </div>
<?php else: ?>
    <?= $this->Form->create(null, ['id' => 'manualsampling', 'enctype' => 'multipart/form-data']) ?>
    <label class="col-sm-3 col-form-label h6 required">File (xls or xlsx)</label>
    <?= $this->Form->control('manualsampling.file', ['type' => 'file', 'label' => false, 'class' => 'form-control-file mb-3', 'required' => true]); ?>
    <?= $this->Form->hidden('manualsampling.version', ['value' => $version])?>
    <?= $this->Form->hidden('manualsampling.correction', ['value' => $correction])?>
    <div class="col-6 form-inline">
        <?= $this->Form->submit('Upload', [
            'class'    => 'btn btn-primary form-control mr-3  my-3',
            'id'       => 'save_button'
        ])
        ?>
        <?= $this->Html->link('Cancel', ['controller' => 'Home', 'action' => 'home'], ['class' => 'btn btn-danger form-control my-3']) ?>
    </div>
    <?= $this->Form->end(); ?>
<?php endif ?>


