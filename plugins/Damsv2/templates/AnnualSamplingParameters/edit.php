<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\AnnualSamplingParameter $annualSamplingParameter
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $annualSamplingParameter->sample_year_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $annualSamplingParameter->sample_year_id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Annual Sampling Parameters'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="annualSamplingParameters form content">
            <?= $this->Form->create($annualSamplingParameter) ?>
            <fieldset>
                <legend><?= __('Edit Annual Sampling Parameter') ?></legend>
                <?php
                    echo $this->Form->control('sampling_year');
                    echo $this->Form->control('last_sampled_month');
                    echo $this->Form->control('expected_payments_eur');
                    echo $this->Form->control('number_of_samples');
                    echo $this->Form->control('sampling_interval_eur');
                    echo $this->Form->control('user');
                ?>
            </fieldset>
                        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-outline-secondary my-3']); ?>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
