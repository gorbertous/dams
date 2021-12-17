<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ErrorsLogDetailed $errorsLogDetailed
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Errors Log Detailed'), ['action' => 'edit', $errorsLogDetailed->error_detail_id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Errors Log Detailed'), ['action' => 'delete', $errorsLogDetailed->error_detail_id], ['confirm' => __('Are you sure you want to delete # {0}?', $errorsLogDetailed->error_detail_id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Errors Log Detailed'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Errors Log Detailed'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="errorsLogDetailed view content">
            <h3><?= h($errorsLogDetailed->error_detail_id) ?></h3>
            <table class="table table-striped">
                <tr>
                    <th><?= __('Errors Log') ?></th>
                    <td><?= $errorsLogDetailed->has('errors_log') ? $this->Html->link($errorsLogDetailed->errors_log->error_id, ['controller' => 'ErrorsLog', 'action' => 'view', $errorsLogDetailed->errors_log->error_id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Sheet') ?></th>
                    <td><?= h($errorsLogDetailed->sheet) ?></td>
                </tr>
                <tr>
                    <th><?= __('File Formats') ?></th>
                    <td><?= h($errorsLogDetailed->file_formats) ?></td>
                </tr>
                <tr>
                    <th><?= __('Error Detail Id') ?></th>
                    <td><?= $this->Number->format($errorsLogDetailed->error_detail_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Lines') ?></th>
                    <td><?= $this->Number->format($errorsLogDetailed->lines) ?></td>
                </tr>
                <tr>
                    <th><?= __('Formats') ?></th>
                    <td><?= $this->Number->format($errorsLogDetailed->formats) ?></td>
                </tr>
                <tr>
                    <th><?= __('Dictionaries') ?></th>
                    <td><?= $this->Number->format($errorsLogDetailed->dictionaries) ?></td>
                </tr>
                <tr>
                    <th><?= __('Integrities') ?></th>
                    <td><?= $this->Number->format($errorsLogDetailed->integrities) ?></td>
                </tr>
                <tr>
                    <th><?= __('Business Rules') ?></th>
                    <td><?= $this->Number->format($errorsLogDetailed->business_rules) ?></td>
                </tr>
                <tr>
                    <th><?= __('Warnings') ?></th>
                    <td><?= $this->Number->format($errorsLogDetailed->warnings) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($errorsLogDetailed->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($errorsLogDetailed->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
