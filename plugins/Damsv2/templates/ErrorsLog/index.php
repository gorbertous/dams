<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ErrorsLog[]|\Cake\Collection\CollectionInterface $errorsLog
 */
?>
<div class="errorsLog index content">
    <?= $this->Html->link(__('New Errors Log'), ['action' => 'add'], ['class' => 'btn btn-primary float-right']) ?>
    <h3><?= __('Errors Log') ?></h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('error_id') ?></th>
                    <th><?= $this->Paginator->sort('portfolio_id') ?></th>
                    <th><?= $this->Paginator->sort('portfolio_name') ?></th>
                    <th><?= $this->Paginator->sort('mandate') ?></th>
                    <th><?= $this->Paginator->sort('beneficiary_name') ?></th>
                    <th><?= $this->Paginator->sort('period') ?></th>
                    <th><?= $this->Paginator->sort('report_id') ?></th>
                    <th><?= $this->Paginator->sort('total_lines') ?></th>
                    <th><?= $this->Paginator->sort('iterations') ?></th>
                    <th><?= $this->Paginator->sort('file_formats') ?></th>
                    <th><?= $this->Paginator->sort('total_formats') ?></th>
                    <th><?= $this->Paginator->sort('total_dictionaries') ?></th>
                    <th><?= $this->Paginator->sort('total_integrities') ?></th>
                    <th><?= $this->Paginator->sort('total_business_rules') ?></th>
                    <th><?= $this->Paginator->sort('total_warnings') ?></th>
                    <th><?= $this->Paginator->sort('fi_responsivness') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($errorsLog as $errorsLog): ?>
                <tr>
                    <td><?= $this->Number->format($errorsLog->error_id) ?></td>
                    <td><?= $this->Number->format($errorsLog->portfolio_id) ?></td>
                    <td><?= h($errorsLog->portfolio_name) ?></td>
                    <td><?= h($errorsLog->mandate) ?></td>
                    <td><?= h($errorsLog->beneficiary_name) ?></td>
                    <td><?= h($errorsLog->period) ?></td>
                    <td><?= $this->Number->format($errorsLog->report_id) ?></td>
                    <td><?= $this->Number->format($errorsLog->total_lines) ?></td>
                    <td><?= $this->Number->format($errorsLog->iterations) ?></td>
                    <td><?= h($errorsLog->file_formats) ?></td>
                    <td><?= $this->Number->format($errorsLog->total_formats) ?></td>
                    <td><?= $this->Number->format($errorsLog->total_dictionaries) ?></td>
                    <td><?= $this->Number->format($errorsLog->total_integrities) ?></td>
                    <td><?= $this->Number->format($errorsLog->total_business_rules) ?></td>
                    <td><?= $this->Number->format($errorsLog->total_warnings) ?></td>
                    <td><?= h($errorsLog->fi_responsivness) ?></td>
                    <td><?= h($errorsLog->created) ?></td>
                    <td><?= h($errorsLog->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $errorsLog->error_id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $errorsLog->error_id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $errorsLog->error_id], ['confirm' => __('Are you sure you want to delete # {0}?', $errorsLog->error_id)]) ?>
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
