<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Status[]|\Cake\Collection\CollectionInterface $status
 */
?>
<div class="status index content">
    <?= $this->Html->link(__('New Status'), ['action' => 'add'], ['class' => 'btn btn-primary float-right']) ?>
    <h3><?= __('Status') ?></h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('status_id') ?></th>
                    <th><?= $this->Paginator->sort('stage') ?></th>
                    <th><?= $this->Paginator->sort('status') ?></th>
                    <th><?= $this->Paginator->sort('action') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($status as $status): ?>
                <tr>
                    <td><?= $this->Number->format($status->status_id) ?></td>
                    <td><?= h($status->stage) ?></td>
                    <td><?= h($status->status) ?></td>
                    <td><?= h($status->action) ?></td>
                    <td><?= h($status->created) ?></td>
                    <td><?= h($status->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $status->status_id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $status->status_id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $status->status_id], ['confirm' => __('Are you sure you want to delete # {0}?', $status->status_id)]) ?>
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
