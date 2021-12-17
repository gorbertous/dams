<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MappingTable[]|\Cake\Collection\CollectionInterface $mappingTable
 */
?>
<div class="mappingTable index content">
    <?= $this->Html->link(__('New Mapping Table'), ['action' => 'add'], ['class' => 'btn btn-primary float-right']) ?>
    <h3><?= __('Mapping Table') ?></h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('table_id') ?></th>
                    <th><?= $this->Paginator->sort('template_id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('table_name') ?></th>
                    <th><?= $this->Paginator->sort('sheet_name') ?></th>
                    <th><?= $this->Paginator->sort('loading_order') ?></th>
                    <th><?= $this->Paginator->sort('is_cf') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mappingTable as $mappingTable): ?>
                <tr>
                    <td><?= $this->Number->format($mappingTable->table_id) ?></td>
                    <td><?= $this->Number->format($mappingTable->template_id) ?></td>
                    <td><?= h($mappingTable->name) ?></td>
                    <td><?= h($mappingTable->table_name) ?></td>
                    <td><?= h($mappingTable->sheet_name) ?></td>
                    <td><?= $this->Number->format($mappingTable->loading_order) ?></td>
                    <td><?= $this->Number->format($mappingTable->is_cf) ?></td>
                    <td><?= h($mappingTable->created) ?></td>
                    <td><?= h($mappingTable->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $mappingTable->table_id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $mappingTable->table_id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $mappingTable->table_id], ['confirm' => __('Are you sure you want to delete # {0}?', $mappingTable->table_id)]) ?>
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
