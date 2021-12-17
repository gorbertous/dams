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
            <?= $this->Html->link(__('Edit Annual Sampling Parameter'), ['action' => 'edit', $annualSamplingParameter->sample_year_id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Annual Sampling Parameter'), ['action' => 'delete', $annualSamplingParameter->sample_year_id], ['confirm' => __('Are you sure you want to delete # {0}?', $annualSamplingParameter->sample_year_id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Annual Sampling Parameters'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Annual Sampling Parameter'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="annualSamplingParameters view content">
            <h3><?= h($annualSamplingParameter->sample_year_id) ?></h3>
            <table class="table table-striped">
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= h($annualSamplingParameter->user) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sample Year Id') ?></th>
                    <td><?= $this->Number->format($annualSamplingParameter->sample_year_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sampling Year') ?></th>
                    <td><?= $this->Number->format($annualSamplingParameter->sampling_year) ?></td>
                </tr>
                <tr>
                    <th><?= __('Last Sampled Month') ?></th>
                    <td><?= $this->Number->format($annualSamplingParameter->last_sampled_month) ?></td>
                </tr>
                <tr>
                    <th><?= __('Expected Payments Eur') ?></th>
                    <td><?= $this->Number->format($annualSamplingParameter->expected_payments_eur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Number Of Samples') ?></th>
                    <td><?= $this->Number->format($annualSamplingParameter->number_of_samples) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sampling Interval Eur') ?></th>
                    <td><?= $this->Number->format($annualSamplingParameter->sampling_interval_eur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($annualSamplingParameter->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($annualSamplingParameter->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
