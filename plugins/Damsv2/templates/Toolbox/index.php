<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Toolbox[]|\Cake\Collection\CollectionInterface $toolbox
 */
?>
<div class="toolbox index content">
    <?= $this->Html->link(__('New Toolbox'), ['action' => 'add'], ['class' => 'btn btn-primary float-right']) ?>
    <h3><?= __('Toolbox') ?></h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('toolbox_id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('filename') ?></th>
                    <th><?= $this->Paginator->sort('creation_date') ?></th>
                    <th><?= $this->Paginator->sort('modification_date') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($toolbox as $toolbox): ?>
                <tr>
                    <td><?= $this->Number->format($toolbox->toolbox_id) ?></td>
                    <td><?= h($toolbox->name) ?></td>
                    <td><?= h($toolbox->filename) ?></td>
                    <td><?= h($toolbox->creation_date) ?></td>
                    <td><?= h($toolbox->modification_date) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $toolbox->toolbox_id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $toolbox->toolbox_id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $toolbox->toolbox_id], ['confirm' => __('Are you sure you want to delete # {0}?', $toolbox->toolbox_id)]) ?>
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
