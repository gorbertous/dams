<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ExcludedTransaction[]|\Cake\Collection\CollectionInterface $excludedTransactions
 */
?>
<div class="excludedTransactions index content">
    <?= $this->Html->link(__('New Excluded Transaction'), ['action' => 'add'], ['class' => 'btn btn-primary float-right']) ?>
    <h3><?= __('Excluded Transactions') ?></h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('excluded_id') ?></th>
                    <th><?= $this->Paginator->sort('sme_id') ?></th>
                    <th><?= $this->Paginator->sort('transaction_id') ?></th>
                    <th><?= $this->Paginator->sort('subtransaction_id') ?></th>
                    <th><?= $this->Paginator->sort('exclusion_date') ?></th>
                    <th><?= $this->Paginator->sort('excluded_transaction_amount') ?></th>
                    <th><?= $this->Paginator->sort('excluded_transaction_amount_eur') ?></th>
                    <th><?= $this->Paginator->sort('excluded_transaction_amount_curr') ?></th>
                    <th><?= $this->Paginator->sort('exclusion_type') ?></th>
                    <th><?= $this->Paginator->sort('coverage_implication') ?></th>
                    <th><?= $this->Paginator->sort('acceleration_flag') ?></th>
                    <th><?= $this->Paginator->sort('comments') ?></th>
                    <th><?= $this->Paginator->sort('portfolio_id') ?></th>
                    <th><?= $this->Paginator->sort('report_id') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($excludedTransactions as $excludedTransaction): ?>
                <tr>
                    <td><?= $this->Number->format($excludedTransaction->excluded_id) ?></td>
                    <td><?= $this->Number->format($excludedTransaction->sme_id) ?></td>
                    <td><?= $excludedTransaction->has('transaction') ? $this->Html->link($excludedTransaction->transaction->transaction_id, ['controller' => 'Transactions', 'action' => 'view', $excludedTransaction->transaction->transaction_id]) : '' ?></td>
                    <td><?= $excludedTransaction->has('subtransaction') ? $this->Html->link($excludedTransaction->subtransaction->subtransaction_id, ['controller' => 'Subtransactions', 'action' => 'view', $excludedTransaction->subtransaction->subtransaction_id]) : '' ?></td>
                    <td><?= h($excludedTransaction->exclusion_date) ?></td>
                    <td><?= $this->Number->format($excludedTransaction->excluded_transaction_amount) ?></td>
                    <td><?= $this->Number->format($excludedTransaction->excluded_transaction_amount_eur) ?></td>
                    <td><?= $this->Number->format($excludedTransaction->excluded_transaction_amount_curr) ?></td>
                    <td><?= h($excludedTransaction->exclusion_type) ?></td>
                    <td><?= h($excludedTransaction->coverage_implication) ?></td>
                    <td><?= h($excludedTransaction->acceleration_flag) ?></td>
                    <td><?= h($excludedTransaction->comments) ?></td>
                    <td><?= $this->Number->format($excludedTransaction->portfolio_id) ?></td>
                    <td><?= $this->Number->format($excludedTransaction->report_id) ?></td>
                    <td><?= h($excludedTransaction->created) ?></td>
                    <td><?= h($excludedTransaction->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $excludedTransaction->excluded_id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $excludedTransaction->excluded_id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $excludedTransaction->excluded_id], ['confirm' => __('Are you sure you want to delete # {0}?', $excludedTransaction->excluded_id)]) ?>
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
