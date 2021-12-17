<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MappingTable $mappingTable
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Mapping Table'), ['action' => 'edit', $mappingTable->table_id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Mapping Table'), ['action' => 'delete', $mappingTable->table_id], ['confirm' => __('Are you sure you want to delete # {0}?', $mappingTable->table_id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Mapping Table'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Mapping Table'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="mappingTable view content">
            <h3><?= h($mappingTable->name) ?></h3>
            <table class="table table-striped">
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($mappingTable->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Table Name') ?></th>
                    <td><?= h($mappingTable->table_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sheet Name') ?></th>
                    <td><?= h($mappingTable->sheet_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Table Id') ?></th>
                    <td><?= $this->Number->format($mappingTable->table_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Template Id') ?></th>
                    <td><?= $this->Number->format($mappingTable->template_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Loading Order') ?></th>
                    <td><?= $this->Number->format($mappingTable->loading_order) ?></td>
                </tr>
                <tr>
                    <th><?= __('Is Cf') ?></th>
                    <td><?= $this->Number->format($mappingTable->is_cf) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($mappingTable->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($mappingTable->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
