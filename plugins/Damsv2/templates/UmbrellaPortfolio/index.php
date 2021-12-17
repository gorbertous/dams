<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UmbrellaPortfolio[]|\Cake\Collection\CollectionInterface $umbrellaPortfolio
 */
?>
<div class="umbrellaPortfolio index content">
    <?= $this->Html->link(__('New Umbrella Portfolio'), ['action' => 'add'], ['class' => 'btn btn-primary float-right']) ?>
    <h3><?= __('Umbrella Portfolio') ?></h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('umbrella_portfolio_id') ?></th>
                    <th><?= $this->Paginator->sort('umbrella_portfolio_name') ?></th>
                    <th><?= $this->Paginator->sort('iqid') ?></th>
                    <th><?= $this->Paginator->sort('product_id') ?></th>
                    <th><?= $this->Paginator->sort('splitting_field') ?></th>
                    <th><?= $this->Paginator->sort('splitting_table') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($umbrellaPortfolio as $umbrellaPortfolio): ?>
                <tr>
                    <td><?= $this->Number->format($umbrellaPortfolio->umbrella_portfolio_id) ?></td>
                    <td><?= h($umbrellaPortfolio->umbrella_portfolio_name) ?></td>
                    <td><?= h($umbrellaPortfolio->iqid) ?></td>
                    <td><?= $umbrellaPortfolio->has('product') ? $this->Html->link($umbrellaPortfolio->product->name, ['controller' => 'Product', 'action' => 'view', $umbrellaPortfolio->product->product_id]) : '' ?></td>
                    <td><?= h($umbrellaPortfolio->splitting_field) ?></td>
                    <td><?= h($umbrellaPortfolio->splitting_table) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $umbrellaPortfolio->umbrella_portfolio_id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $umbrellaPortfolio->umbrella_portfolio_id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $umbrellaPortfolio->umbrella_portfolio_id], ['confirm' => __('Are you sure you want to delete # {0}?', $umbrellaPortfolio->umbrella_portfolio_id)]) ?>
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
