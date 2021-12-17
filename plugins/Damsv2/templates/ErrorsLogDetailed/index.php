<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ErrorsLogDetailed[]|\Cake\Collection\CollectionInterface $errorsLogDetailed
 */
?>
<div class="errorsLogDetailed index content">
    <?= $this->Html->link(__('New Errors Log Detailed'), ['action' => 'add'], ['class' => 'btn btn-primary float-right']) ?>
    <h3><?= __('Errors Log Detailed') ?></h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('error_detail_id') ?></th>
                    <th><?= $this->Paginator->sort('error_id') ?></th>
                    <th><?= $this->Paginator->sort('sheet') ?></th>
                    <th><?= $this->Paginator->sort('lines') ?></th>
                    <th><?= $this->Paginator->sort('file_formats') ?></th>
                    <th><?= $this->Paginator->sort('formats') ?></th>
                    <th><?= $this->Paginator->sort('dictionaries') ?></th>
                    <th><?= $this->Paginator->sort('integrities') ?></th>
                    <th><?= $this->Paginator->sort('business_rules') ?></th>
                    <th><?= $this->Paginator->sort('warnings') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($errorsLogDetailed as $errorsLogDetailed): ?>
                <tr>
                    <td><?= $this->Number->format($errorsLogDetailed->error_detail_id) ?></td>
                    <td><?= $errorsLogDetailed->has('errors_log') ? $this->Html->link($errorsLogDetailed->errors_log->error_id, ['controller' => 'ErrorsLog', 'action' => 'view', $errorsLogDetailed->errors_log->error_id]) : '' ?></td>
                    <td><?= h($errorsLogDetailed->sheet) ?></td>
                    <td><?= $this->Number->format($errorsLogDetailed->lines) ?></td>
                    <td><?= h($errorsLogDetailed->file_formats) ?></td>
                    <td><?= $this->Number->format($errorsLogDetailed->formats) ?></td>
                    <td><?= $this->Number->format($errorsLogDetailed->dictionaries) ?></td>
                    <td><?= $this->Number->format($errorsLogDetailed->integrities) ?></td>
                    <td><?= $this->Number->format($errorsLogDetailed->business_rules) ?></td>
                    <td><?= $this->Number->format($errorsLogDetailed->warnings) ?></td>
                    <td><?= h($errorsLogDetailed->created) ?></td>
                    <td><?= h($errorsLogDetailed->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $errorsLogDetailed->error_detail_id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $errorsLogDetailed->error_detail_id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $errorsLogDetailed->error_detail_id], ['confirm' => __('Are you sure you want to delete # {0}?', $errorsLogDetailed->error_detail_id)]) ?>
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
