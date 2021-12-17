<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PortfolioRates[]|\Cake\Collection\CollectionInterface $portfolioRates
 */
?>
<div class="portfolioRates index content">
    <?= $this->Html->link(__('New Portfolio Rate'), ['action' => 'add'], ['class' => 'btn btn-primary float-right']) ?>
    <h3><?= __('Portfolio Rates') ?></h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('portfolio_rates_id') ?></th>
                    <th><?= $this->Paginator->sort('portfolio_id') ?></th>
                    <th><?= $this->Paginator->sort('theme') ?></th>
                    <th><?= $this->Paginator->sort('effective_date') ?></th>
                    <th><?= $this->Paginator->sort('availability_start') ?></th>
                    <th><?= $this->Paginator->sort('availability_end') ?></th>
                    <th><?= $this->Paginator->sort('rate_application_date') ?></th>
                    <th><?= $this->Paginator->sort('guarantee_rate') ?></th>
                    <th><?= $this->Paginator->sort('cap_rate') ?></th>
                    <th><?= $this->Paginator->sort('commitment') ?></th>
                    <th><?= $this->Paginator->sort('cap_amount') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($portfolioRates as $portfolioRate): ?>
                <tr>
                    <td><?= $this->Number->format($portfolioRate->portfolio_rates_id) ?></td>
                    <td><?= $portfolioRate->has('portfolio') ? $this->Html->link($portfolioRate->portfolio->portfolio_id, ['controller' => 'Portfolio', 'action' => 'view', $portfolioRate->portfolio->portfolio_id]) : '' ?></td>
                    <td><?= h($portfolioRate->theme) ?></td>
                    <td><?= h($portfolioRate->effective_date) ?></td>
                    <td><?= h($portfolioRate->availability_start) ?></td>
                    <td><?= h($portfolioRate->availability_end) ?></td>
                    <td><?= h($portfolioRate->rate_application_date) ?></td>
                    <td><?= $this->Number->format($portfolioRate->guarantee_rate) ?></td>
                    <td><?= $this->Number->format($portfolioRate->cap_rate) ?></td>
                    <td><?= $this->Number->format($portfolioRate->commitment) ?></td>
                    <td><?= $this->Number->format($portfolioRate->cap_amount) ?></td>
                    <td><?= h($portfolioRate->created) ?></td>
                    <td><?= h($portfolioRate->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $portfolioRate->portfolio_rates_id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $portfolioRate->portfolio_rates_id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $portfolioRate->portfolio_rates_id], ['confirm' => __('Are you sure you want to delete # {0}?', $portfolioRate->portfolio_rates_id)]) ?>
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
