<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SamplingEvaluation $samplingEvaluation
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Sampling Evaluation'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="samplingEvaluation form content">
            <?= $this->Form->create($samplingEvaluation) ?>
            <fieldset>
                <legend><?= __('Add Sampling Evaluation') ?></legend>
                <?php
                    echo $this->Form->control('evaluation_year');
                    echo $this->Form->control('value_pds');
                    echo $this->Form->control('value_pds_sampled');
                    echo $this->Form->control('nb_pds_sampled');
                    echo $this->Form->control('nb_hv_sampled');
                    echo $this->Form->control('nb_lv_sampled');
                    echo $this->Form->control('value_hv');
                    echo $this->Form->control('overstatements_hv');
                    echo $this->Form->control('materiality_threshold');
                    echo $this->Form->control('materiality_threshold_eur');
                    echo $this->Form->control('res_materiality_threshold');
                    echo $this->Form->control('average_taint_lv');
                    echo $this->Form->control('confidence_no_overstate');
                    echo $this->Form->control('probability_overstate');
                    echo $this->Form->control('user');
                ?>
            </fieldset>
                        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-outline-secondary my-3']); ?>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
