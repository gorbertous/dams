<?php
/**
 * @var \App\View\AppView $this
 * @var \Treasury\Model\Entity\CounterpartyAccount[]|\Cake\Collection\CollectionInterface $counterpartyAccounts
 */
?>
<div class="counterpartyAccounts index content">
    <?= $this->Html->link(__('New Counterparty Account'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Counterparty Accounts') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('cpty_id') ?></th>
                    <th><?= $this->Paginator->sort('correspondent_bank') ?></th>
                    <th><?= $this->Paginator->sort('correspondent_BIC') ?></th>
                    <th><?= $this->Paginator->sort('currency') ?></th>
                    <th><?= $this->Paginator->sort('account_IBAN') ?></th>
                    <th><?= $this->Paginator->sort('target') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($counterpartyAccounts as $counterpartyAccount): ?>
                <tr>
                    <td><?= $this->Number->format($counterpartyAccount->id) ?></td>
                    <td><?= $this->Number->format($counterpartyAccount->cpty_id) ?></td>
                    <td><?= h($counterpartyAccount->correspondent_bank) ?></td>
                    <td><?= h($counterpartyAccount->correspondent_BIC) ?></td>
                    <td><?= h($counterpartyAccount->currency) ?></td>
                    <td><?= h($counterpartyAccount->account_IBAN) ?></td>
                    <td><?= h($counterpartyAccount->target) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $counterpartyAccount->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $counterpartyAccount->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $counterpartyAccount->id], ['confirm' => __('Are you sure you want to delete # {0}?', $counterpartyAccount->id)]) ?>
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
