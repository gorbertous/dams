<?php
/**
 * @var \App\View\AppView $this
 * @var \Treasury\Model\Entity\Bond[]|\Cake\Collection\CollectionInterface $bonds
 */
?>
<div class="bonds index content">
    <?= $this->Html->link(__('New Bond'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Bonds') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('bond_id') ?></th>
                    <th><?= $this->Paginator->sort('issue_date') ?></th>
                    <th><?= $this->Paginator->sort('first_coupon_accrual_date') ?></th>
                    <th><?= $this->Paginator->sort('first_coupon_payment_date') ?></th>
                    <th><?= $this->Paginator->sort('maturity_date') ?></th>
                    <th><?= $this->Paginator->sort('coupon_rate') ?></th>
                    <th><?= $this->Paginator->sort('tax_rate') ?></th>
                    <th><?= $this->Paginator->sort('issue_size') ?></th>
                    <th><?= $this->Paginator->sort('covered') ?></th>
                    <th><?= $this->Paginator->sort('secured') ?></th>
                    <th><?= $this->Paginator->sort('structured') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bonds as $bond): ?>
                <tr>
                    <td><?= $this->Number->format($bond->bond_id) ?></td>
                    <td><?= h($bond->issue_date) ?></td>
                    <td><?= h($bond->first_coupon_accrual_date) ?></td>
                    <td><?= h($bond->first_coupon_payment_date) ?></td>
                    <td><?= h($bond->maturity_date) ?></td>
                    <td><?= $this->Number->format($bond->coupon_rate) ?></td>
                    <td><?= $this->Number->format($bond->tax_rate) ?></td>
                    <td><?= $this->Number->format($bond->issue_size) ?></td>
                    <td><?= h($bond->covered) ?></td>
                    <td><?= h($bond->secured) ?></td>
                    <td><?= h($bond->structured) ?></td>
                    <td><?= h($bond->created) ?></td>
                    <td><?= h($bond->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $bond->bond_id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $bond->bond_id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $bond->bond_id], ['confirm' => __('Are you sure you want to delete # {0}?', $bond->bond_id)]) ?>
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
