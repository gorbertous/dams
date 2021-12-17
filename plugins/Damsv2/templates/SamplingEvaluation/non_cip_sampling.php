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
        'title'   => 'Non CIP Sampling',
        'url'     => ['controller' => 'sampling-evaluation', 'action' => 'non-cip-sampling'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>

<h3>Non-CIP Sampling</h3>
<hr>

<?php if (!empty($sasResult)): ?>
    <?= $sasResult ?>
<?php else: ?>
    <?= $this->Form->create(null, ['id' => 'non_cip_sampling']) ?>
    <p><?= __('Last sample has been drawn for the year ') . $last_execution_year . "."; ?></p>
    <div class="row col-6 form-inline">
        <label class="col-sm-3 col-form-label h6">Year</label>
        <?= $this->Form->input('new_year', ['disabled' => true, 'label' => false, 'value' => $next_execution_year, 'class' => 'form-control mb-3']); ?>
        <?= $this->Form->hidden('non_cip_sampling.new_year', ['value' => $next_execution_year]) ?>
    </div>
    <div class="row col-6 form-inline">
        <?=
        $this->Form->control('finalized', [
            'label'   => 'All Payment Demands have been finalized for the year of drawing.',
            'type'    => 'checkbox',
            'checked' => true,
            'class'   => 'mr-2 my-3 py-3',
            'id'      => 'id_finalized',
        ])
        ?>
    </div>
    <div class="row col-6 my-2">
        <?= $msg ?>
    </div>
    <div class="row col-6 form-inline">
        <?=
        $this->Form->submit('Sample drawing', [
            'class' => 'btn btn-primary form-control mr-3  my-3',
            'id'    => 'submit_button'
        ])
        ?>
    <?= $this->Html->link('Cancel', ['controller' => 'Home', 'action' => 'home'], ['class' => 'btn btn-danger form-control my-3']) ?>
    </div>
    <?= $this->Form->end(); ?>
<?php endif ?>


<script>
    $(document).ready(function () {
        $('#id_finalized').change(function (e) {
            document.getElementById("submit_button").disabled = !$('#id_finalized:checked').length;
        });
    });
</script>

