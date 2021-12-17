<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\AnnualSamplingParameter[]|\Cake\Collection\CollectionInterface $annualSamplingParameters
 */
?>
<div class="annualSamplingParameters index content">
    <?= $this->Html->link(__('New Annual Sampling Parameter'), ['action' => 'add'], ['class' => 'btn btn-primary float-right']) ?>
    <h3><?= __('Annual Sampling Parameters') ?></h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('sample_year_id') ?></th>
                    <th><?= $this->Paginator->sort('sampling_year') ?></th>
                    <th><?= $this->Paginator->sort('last_sampled_month') ?></th>
                    <th><?= $this->Paginator->sort('expected_payments_eur') ?></th>
                    <th><?= $this->Paginator->sort('number_of_samples') ?></th>
                    <th><?= $this->Paginator->sort('sampling_interval_eur') ?></th>
                    <th><?= $this->Paginator->sort('user') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($annualSamplingParameters as $annualSamplingParameter): ?>
                <tr>
                    <td><?= $this->Number->format($annualSamplingParameter->sample_year_id) ?></td>
                    <td><?= $this->Number->format($annualSamplingParameter->sampling_year) ?></td>
                    <td><?= $this->Number->format($annualSamplingParameter->last_sampled_month) ?></td>
                    <td><?= $this->Number->format($annualSamplingParameter->expected_payments_eur) ?></td>
                    <td><?= $this->Number->format($annualSamplingParameter->number_of_samples) ?></td>
                    <td><?= $this->Number->format($annualSamplingParameter->sampling_interval_eur) ?></td>
                    <td><?= h($annualSamplingParameter->user) ?></td>
                    <td><?= h($annualSamplingParameter->created) ?></td>
                    <td><?= h($annualSamplingParameter->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $annualSamplingParameter->sample_year_id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $annualSamplingParameter->sample_year_id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $annualSamplingParameter->sample_year_id], ['confirm' => __('Are you sure you want to delete # {0}?', $annualSamplingParameter->sample_year_id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
