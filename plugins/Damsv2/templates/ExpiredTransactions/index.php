<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ExpiredTransaction[]|\Cake\Collection\CollectionInterface $expiredTransactions
 */
?>
<div class="expiredTransactions index content">
    <?= $this->Html->link(__('New Expired Transaction'), ['action' => 'add'], ['class' => 'btn btn-primary float-right']) ?>
    <h3><?= __('Expired Transactions') ?></h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('expired_id') ?></th>
                    <th><?= $this->Paginator->sort('transaction_id') ?></th>
                    <th><?= $this->Paginator->sort('subtransaction_id') ?></th>
                    <th><?= $this->Paginator->sort('sme_id') ?></th>
                    <th><?= $this->Paginator->sort('repayment_date') ?></th>
                    <th><?= $this->Paginator->sort('portfolio_id') ?></th>
                    <th><?= $this->Paginator->sort('report_id') ?></th>
                    <th><?= $this->Paginator->sort('nbr_employees_expired') ?></th>
                    <th><?= $this->Paginator->sort('sale_date') ?></th>
                    <th><?= $this->Paginator->sort('sale_price') ?></th>
                    <th><?= $this->Paginator->sort('sale_price_eur') ?></th>
                    <th><?= $this->Paginator->sort('sale_price_curr') ?></th>
                    <th><?= $this->Paginator->sort('write_off_date') ?></th>
                    <th><?= $this->Paginator->sort('write_off') ?></th>
                    <th><?= $this->Paginator->sort('write_off_eur') ?></th>
                    <th><?= $this->Paginator->sort('write_off_curr') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($expiredTransactions as $expiredTransaction): ?>
                <tr>
                    <td><?= $this->Number->format($expiredTransaction->expired_id) ?></td>
                    <td><?= $expiredTransaction->has('transaction') ? $this->Html->link($expiredTransaction->transaction->transaction_id, ['controller' => 'Transactions', 'action' => 'view', $expiredTransaction->transaction->transaction_id]) : '' ?></td>
                    <td><?= $expiredTransaction->has('subtransaction') ? $this->Html->link($expiredTransaction->subtransaction->subtransaction_id, ['controller' => 'Subtransactions', 'action' => 'view', $expiredTransaction->subtransaction->subtransaction_id]) : '' ?></td>
                    <td><?= $this->Number->format($expiredTransaction->sme_id) ?></td>
                    <td><?= h($expiredTransaction->repayment_date) ?></td>
                    <td><?= $this->Number->format($expiredTransaction->portfolio_id) ?></td>
                    <td><?= $this->Number->format($expiredTransaction->report_id) ?></td>
                    <td><?= $this->Number->format($expiredTransaction->nbr_employees_expired) ?></td>
                    <td><?= h($expiredTransaction->sale_date) ?></td>
                    <td><?= $this->Number->format($expiredTransaction->sale_price) ?></td>
                    <td><?= $this->Number->format($expiredTransaction->sale_price_eur) ?></td>
                    <td><?= $this->Number->format($expiredTransaction->sale_price_curr) ?></td>
                    <td><?= h($expiredTransaction->write_off_date) ?></td>
                    <td><?= $this->Number->format($expiredTransaction->write_off) ?></td>
                    <td><?= $this->Number->format($expiredTransaction->write_off_eur) ?></td>
                    <td><?= $this->Number->format($expiredTransaction->write_off_curr) ?></td>
                    <td><?= h($expiredTransaction->created) ?></td>
                    <td><?= h($expiredTransaction->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $expiredTransaction->expired_id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $expiredTransaction->expired_id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $expiredTransaction->expired_id], ['confirm' => __('Are you sure you want to delete # {0}?', $expiredTransaction->expired_id)]) ?>
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
