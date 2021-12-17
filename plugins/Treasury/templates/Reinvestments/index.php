<?php
/**
 * @var \App\View\AppView $this
 * @var \Treasury\Model\Entity\Reinvestment[]|\Cake\Collection\CollectionInterface $reinvestments
 */
?>
<div class="reinvestments index content">
    <?= $this->Html->link(__('New Reinvestment'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Reinvestments') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('reinv_group') ?></th>
                    <th><?= $this->Paginator->sort('reinv_status') ?></th>
                    <th><?= $this->Paginator->sort('mandate_ID') ?></th>
                    <th><?= $this->Paginator->sort('cmp_ID') ?></th>
                    <th><?= $this->Paginator->sort('cpty_ID') ?></th>
                    <th><?= $this->Paginator->sort('availability_date') ?></th>
                    <th><?= $this->Paginator->sort('accountA_IBAN') ?></th>
                    <th><?= $this->Paginator->sort('accountB_IBAN') ?></th>
                    <th><?= $this->Paginator->sort('amount_leftA') ?></th>
                    <th><?= $this->Paginator->sort('amount_leftB') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th><?= $this->Paginator->sort('reinv_type') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reinvestments as $reinvestment): ?>
                <tr>
                    <td><?= $this->Number->format($reinvestment->reinv_group) ?></td>
                    <td><?= h($reinvestment->reinv_status) ?></td>
                    <td><?= $this->Number->format($reinvestment->mandate_ID) ?></td>
                    <td><?= $this->Number->format($reinvestment->cmp_ID) ?></td>
                    <td><?= $this->Number->format($reinvestment->cpty_ID) ?></td>
                    <td><?= h($reinvestment->availability_date) ?></td>
                    <td><?= h($reinvestment->accountA_IBAN) ?></td>
                    <td><?= h($reinvestment->accountB_IBAN) ?></td>
                    <td><?= $this->Number->format($reinvestment->amount_leftA) ?></td>
                    <td><?= $this->Number->format($reinvestment->amount_leftB) ?></td>
                    <td><?= h($reinvestment->created) ?></td>
                    <td><?= h($reinvestment->modified) ?></td>
                    <td><?= h($reinvestment->reinv_type) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $reinvestment->reinv_group]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $reinvestment->reinv_group]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $reinvestment->reinv_group], ['confirm' => __('Are you sure you want to delete # {0}?', $reinvestment->reinv_group)]) ?>
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
