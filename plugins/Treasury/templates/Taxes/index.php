<?php
/**
 * @var \App\View\AppView $this
 * @var \Treasury\Model\Entity\Tax[]|\Cake\Collection\CollectionInterface $taxes
 */
?>
<div class="taxes index content">
    <?= $this->Html->link(__('New Tax'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Taxes') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('tax_ID') ?></th>
                    <th><?= $this->Paginator->sort('mandate_ID') ?></th>
                    <th><?= $this->Paginator->sort('cpty_ID') ?></th>
                    <th><?= $this->Paginator->sort('tax_rate') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($taxes as $tax): ?>
                <tr>
                    <td><?= $this->Number->format($tax->tax_ID) ?></td>
                    <td><?= $this->Number->format($tax->mandate_ID) ?></td>
                    <td><?= $this->Number->format($tax->cpty_ID) ?></td>
                    <td><?= $this->Number->format($tax->tax_rate) ?></td>
                    <td><?= h($tax->created) ?></td>
                    <td><?= h($tax->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $tax->tax_ID]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $tax->tax_ID]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $tax->tax_ID], ['confirm' => __('Are you sure you want to delete # {0}?', $tax->tax_ID)]) ?>
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
