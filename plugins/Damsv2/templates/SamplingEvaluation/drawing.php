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
        'title'   => 'CIP Sample drawing',
        'url'     => ['controller' => 'sampling-evaluation', 'action' => 'drawing'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>

<h3>CIP Sample drawing</h3>
<hr>

<?php if (isset($sasResult)): ?>
    <div class="row">
        <div class="col-8">
            <?= $sasResult ?>
        </div>
    </div>

<?php else: ?>
    <p><?= __('Last sample has been drawn for the month of ') . $last_month . ", " . $last_year . "."; ?></p>
    <?= $this->Form->create(null, ['id' => 'sampleDrawing']) ?>
    <div class="form-group row">
        <div class="col-6">
        <label class="col-sm-3 col-form-label h6">Year</label>
       
        <?= $this->Form->input('new_year', ['class' => 'mr-2 my-2 py-2', 'disabled' => true, 'label' => false, 'value' => $new_year]) ?>
        <?= $this->Form->hidden('sampleDrawing.new_year', ['value' => $new_year])?>
      
        </div>
    </div>
    <div class="form-group row">
        <div class="col-6">
            <label class="col-sm-3 col-form-label h6">Month</label>

            <?= $this->Form->input('new_month', ['class' => 'mr-2 my-2 py-2', 'disabled' => true, 'label' => false, 'value' => $new_month_libel]) ?>
            <?= $this->Form->hidden('sampleDrawing.new_month', ['value' => $new_month])?>
           
        </div>
    </div>
    <div class="form-group row">
        <div class="col-6">
            <?= $this->Form->control('sampleDrawing.finalized', ['type' => 'checkbox', 'class' => 'mr-2 my-2 py-2', 'id'    => 'id_finalized', 'label' => ' All Payment Demands have been finalized for the month of drawing.']); ?>
           
        </div>
    </div>
    <div class="row">
    <div class="col-6 form-inline">
        <?= $this->Form->submit('Draw sample', [
            'class'    => 'btn btn-primary form-control mr-3  my-3',
            'id'       => 'save_button',
            'disabled' => $submit_disabled,
        ])
        ?>
        <?= $this->Html->link('Cancel', ['controller' => 'Home', 'action' => 'home'], ['class' => 'btn btn-danger form-control my-3']) ?>

    </div>
</div>

<?= $this->Form->end(); ?>
<?php endif ?>

   