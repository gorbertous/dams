<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Template[]|\Cake\Collection\CollectionInterface $template
 */
?>
<div class="template index content">
    <?= $this->Html->link(__('New Template'), ['action' => 'add'], ['class' => 'btn btn-primary float-right']) ?>
    <h3><?= __('Template') ?></h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('template_id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('template_type_id') ?></th>
                    <th><?= $this->Paginator->sort('callback_id') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($template as $template): ?>
                <tr>
                    <td><?= $this->Number->format($template->template_id) ?></td>
                    <td><?= h($template->name) ?></td>
                    <td><?= $this->Number->format($template->template_type_id) ?></td>
                    <td><?= $this->Number->format($template->callback_id) ?></td>
                    <td><?= h($template->created) ?></td>
                    <td><?= h($template->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $template->template_id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $template->template_id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $template->template_id], ['confirm' => __('Are you sure you want to delete # {0}?', $template->template_id)]) ?>
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
