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
        'title'   => 'Upload SME Rating Mapping',
        'url'     => ['controller' => 'sme-rating-mapping', 'action' => 'upload-rating'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
 <h3>Upload SME Rating Mapping</h3>
 <hr>
 

<div class="row">
    <div class="col-9">
        <p>Download template <a href="/damsv2/ajax/download-file/SME_rating_mapping_template.xls/sme_rating_mapping/template"><strong>here</strong></a></p>
    </div>
</div>

<?php //if (!empty($sasResult)): ?>
 
    <?php if (!empty($error_file)): ?>
        <?php 
            $file_name = basename($error_file);
        ?>
        <div class="row">
            <div class="col-4">
                <p>Errors were detected in the file. Please correct the issues, delete the <strong>error_message</strong> column and reload the file in xls format.</p> 
                <p class="py-2"><a href="/damsv2/ajax/download-file/<?= $file_name ?>/sme_rating_mapping/error"><strong>Error file</strong></a></p>
            </div>
        </div>
    
    <?php endif ?>
  
    <?= $this->Form->create(null, ['id' => 'SmeRating', 'enctype' => 'multipart/form-data']) ?>
    <label class="col-sm-3 col-form-label h6 required">File (xls)</label>
    <?= $this->Form->control('SmeRating.file', ['type' => 'file', 'label' => false, 'class' => 'form-control-file mb-3', 'required' => true]); ?>
    
    <?= $this->Form->hidden('SmeRating.correction', ['value' => $correction])?>
<?php if($perm->hasWrite()) { ?>
    <div class="col-6 form-inline">
        <?= $this->Form->submit('Upload', [
            'class'    => 'btn btn-primary form-control mr-3  my-3',
            'id'       => 'save_button'
        ])
        ?>
      
    </div>
<?php } ?>
    <?= $this->Form->end(); ?>
<?php // endif ?>

