<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Mandate[]|\Cake\Collection\CollectionInterface $mandate
 */
?>
<div class="mandate index content">
    <?= $this->Html->link(__('New Mandate'), ['action' => 'add'], ['class' => 'btn btn-primary float-right']) ?>
    <h3><?= __('Mandate') ?></h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('mandate_id') ?></th>
                    <th><?= $this->Paginator->sort('mandate_iqid') ?></th>
                    <th><?= $this->Paginator->sort('mandate_name') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mandate as $mandate): ?>
                <tr>
                    <td><?= $this->Number->format($mandate->mandate_id) ?></td>
                    <td><?= h($mandate->mandate_iqid) ?></td>
                    <td><?= h($mandate->mandate_name) ?></td>
                    <td><?= h($mandate->created) ?></td>
                    <td><?= h($mandate->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $mandate->mandate_id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $mandate->mandate_id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $mandate->mandate_id], ['confirm' => __('Are you sure you want to delete # {0}?', $mandate->mandate_id)]) ?>
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
