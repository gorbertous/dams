<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TemplateType[]|\Cake\Collection\CollectionInterface $templateType
 */
?>
<div class="templateType index content">
    <?= $this->Html->link(__('New Template Type'), ['action' => 'add'], ['class' => 'btn btn-primary float-right']) ?>
    <h3><?= __('Template Type') ?></h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('type_id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($templateType as $templateType): ?>
                <tr>
                    <td><?= $this->Number->format($templateType->type_id) ?></td>
                    <td><?= h($templateType->name) ?></td>
                    <td><?= h($templateType->created) ?></td>
                    <td><?= h($templateType->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $templateType->type_id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $templateType->type_id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $templateType->type_id], ['confirm' => __('Are you sure you want to delete # {0}?', $templateType->type_id)]) ?>
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
