<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MappingColumn[]|\Cake\Collection\CollectionInterface $mappingColumn
 */
?>
<div class="mappingColumn index content">
    <?= $this->Html->link(__('New Mapping Column'), ['action' => 'add'], ['class' => 'btn btn-primary float-right']) ?>
    <h3><?= __('Mapping Column') ?></h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('column_id') ?></th>
                    <th><?= $this->Paginator->sort('table_id') ?></th>
                    <th><?= $this->Paginator->sort('table_field') ?></th>
                    <th><?= $this->Paginator->sort('datatype') ?></th>
                    <th><?= $this->Paginator->sort('exec_order') ?></th>
                    <th><?= $this->Paginator->sort('excel_pk') ?></th>
                    <th><?= $this->Paginator->sort('excel_fk') ?></th>
                    <th><?= $this->Paginator->sort('excel_column') ?></th>
                    <th><?= $this->Paginator->sort('is_null') ?></th>
                    <th><?= $this->Paginator->sort('db_pk') ?></th>
                    <th><?= $this->Paginator->sort('db_fk') ?></th>
                    <th><?= $this->Paginator->sort('db_load_pk') ?></th>
                    <th><?= $this->Paginator->sort('db_load_fk') ?></th>
                    <th><?= $this->Paginator->sort('db_id') ?></th>
                    <th><?= $this->Paginator->sort('fk_id') ?></th>
                    <th><?= $this->Paginator->sort('sql_formula') ?></th>
                    <th><?= $this->Paginator->sort('macro') ?></th>
                    <th><?= $this->Paginator->sort('is_cf') ?></th>
                    <th><?= $this->Paginator->sort('is_converted') ?></th>
                    <th><?= $this->Paginator->sort('in_view') ?></th>
                    <th><?= $this->Paginator->sort('transcode') ?></th>
                    <th><?= $this->Paginator->sort('not_store') ?></th>
                    <th><?= $this->Paginator->sort('dictionary_id') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mappingColumn as $mappingColumn): ?>
                <tr>
                    <td><?= $this->Number->format($mappingColumn->column_id) ?></td>
                    <td><?= $this->Number->format($mappingColumn->table_id) ?></td>
                    <td><?= h($mappingColumn->table_field) ?></td>
                    <td><?= h($mappingColumn->datatype) ?></td>
                    <td><?= $this->Number->format($mappingColumn->exec_order) ?></td>
                    <td><?= $this->Number->format($mappingColumn->excel_pk) ?></td>
                    <td><?= $this->Number->format($mappingColumn->excel_fk) ?></td>
                    <td><?= $this->Number->format($mappingColumn->excel_column) ?></td>
                    <td><?= h($mappingColumn->is_null) ?></td>
                    <td><?= $this->Number->format($mappingColumn->db_pk) ?></td>
                    <td><?= $this->Number->format($mappingColumn->db_fk) ?></td>
                    <td><?= $this->Number->format($mappingColumn->db_load_pk) ?></td>
                    <td><?= $this->Number->format($mappingColumn->db_load_fk) ?></td>
                    <td><?= $this->Number->format($mappingColumn->db_id) ?></td>
                    <td><?= $this->Number->format($mappingColumn->fk_id) ?></td>
                    <td><?= h($mappingColumn->sql_formula) ?></td>
                    <td><?= h($mappingColumn->macro) ?></td>
                    <td><?= $this->Number->format($mappingColumn->is_cf) ?></td>
                    <td><?= h($mappingColumn->is_converted) ?></td>
                    <td><?= h($mappingColumn->in_view) ?></td>
                    <td><?= h($mappingColumn->transcode) ?></td>
                    <td><?= $this->Number->format($mappingColumn->not_store) ?></td>
                    <td><?= $this->Number->format($mappingColumn->dictionary_id) ?></td>
                    <td><?= h($mappingColumn->created) ?></td>
                    <td><?= h($mappingColumn->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $mappingColumn->column_id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $mappingColumn->column_id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $mappingColumn->column_id], ['confirm' => __('Are you sure you want to delete # {0}?', $mappingColumn->column_id)]) ?>
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
