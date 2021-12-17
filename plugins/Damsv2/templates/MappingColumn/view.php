<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MappingColumn $mappingColumn
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Mapping Column'), ['action' => 'edit', $mappingColumn->column_id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Mapping Column'), ['action' => 'delete', $mappingColumn->column_id], ['confirm' => __('Are you sure you want to delete # {0}?', $mappingColumn->column_id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Mapping Column'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Mapping Column'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="mappingColumn view content">
            <h3><?= h($mappingColumn->column_id) ?></h3>
            <table class="table table-striped">
                <tr>
                    <th><?= __('Table Field') ?></th>
                    <td><?= h($mappingColumn->table_field) ?></td>
                </tr>
                <tr>
                    <th><?= __('Datatype') ?></th>
                    <td><?= h($mappingColumn->datatype) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sql Formula') ?></th>
                    <td><?= h($mappingColumn->sql_formula) ?></td>
                </tr>
                <tr>
                    <th><?= __('Macro') ?></th>
                    <td><?= h($mappingColumn->macro) ?></td>
                </tr>
                <tr>
                    <th><?= __('Column Id') ?></th>
                    <td><?= $this->Number->format($mappingColumn->column_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Table Id') ?></th>
                    <td><?= $this->Number->format($mappingColumn->table_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Exec Order') ?></th>
                    <td><?= $this->Number->format($mappingColumn->exec_order) ?></td>
                </tr>
                <tr>
                    <th><?= __('Excel Pk') ?></th>
                    <td><?= $this->Number->format($mappingColumn->excel_pk) ?></td>
                </tr>
                <tr>
                    <th><?= __('Excel Fk') ?></th>
                    <td><?= $this->Number->format($mappingColumn->excel_fk) ?></td>
                </tr>
                <tr>
                    <th><?= __('Excel Column') ?></th>
                    <td><?= $this->Number->format($mappingColumn->excel_column) ?></td>
                </tr>
                <tr>
                    <th><?= __('Db Pk') ?></th>
                    <td><?= $this->Number->format($mappingColumn->db_pk) ?></td>
                </tr>
                <tr>
                    <th><?= __('Db Fk') ?></th>
                    <td><?= $this->Number->format($mappingColumn->db_fk) ?></td>
                </tr>
                <tr>
                    <th><?= __('Db Load Pk') ?></th>
                    <td><?= $this->Number->format($mappingColumn->db_load_pk) ?></td>
                </tr>
                <tr>
                    <th><?= __('Db Load Fk') ?></th>
                    <td><?= $this->Number->format($mappingColumn->db_load_fk) ?></td>
                </tr>
                <tr>
                    <th><?= __('Db Id') ?></th>
                    <td><?= $this->Number->format($mappingColumn->db_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fk Id') ?></th>
                    <td><?= $this->Number->format($mappingColumn->fk_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Is Cf') ?></th>
                    <td><?= $this->Number->format($mappingColumn->is_cf) ?></td>
                </tr>
                <tr>
                    <th><?= __('Not Store') ?></th>
                    <td><?= $this->Number->format($mappingColumn->not_store) ?></td>
                </tr>
                <tr>
                    <th><?= __('Dictionary Id') ?></th>
                    <td><?= $this->Number->format($mappingColumn->dictionary_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($mappingColumn->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($mappingColumn->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Is Null') ?></th>
                    <td><?= $mappingColumn->is_null ? __('Yes') : __('No'); ?></td>
                </tr>
                <tr>
                    <th><?= __('Is Converted') ?></th>
                    <td><?= $mappingColumn->is_converted ? __('Yes') : __('No'); ?></td>
                </tr>
                <tr>
                    <th><?= __('In View') ?></th>
                    <td><?= $mappingColumn->in_view ? __('Yes') : __('No'); ?></td>
                </tr>
                <tr>
                    <th><?= __('Transcode') ?></th>
                    <td><?= $mappingColumn->transcode ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
