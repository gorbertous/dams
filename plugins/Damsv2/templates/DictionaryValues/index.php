<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DictionaryValue[]|\Cake\Collection\CollectionInterface $dictionaryValues
 */
?>
<div class="dictionaryValues index content">
    <?= $this->Html->link(__('New Dictionary Value'), ['action' => 'add'], ['class' => 'btn btn-primary float-right']) ?>
    <h3><?= __('Dictionary Values') ?></h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('dicoval_id') ?></th>
                    <th><?= $this->Paginator->sort('dictionary_id') ?></th>
                    <th><?= $this->Paginator->sort('code') ?></th>
                    <th><?= $this->Paginator->sort('translation') ?></th>
                    <th><?= $this->Paginator->sort('label') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dictionaryValues as $dictionaryValue): ?>
                <tr>
                    <td><?= $this->Number->format($dictionaryValue->dicoval_id) ?></td>
                    <td><?= $dictionaryValue->has('dictionary') ? $this->Html->link($dictionaryValue->dictionary->name, ['controller' => 'Dictionary', 'action' => 'view', $dictionaryValue->dictionary->dictionary_id]) : '' ?></td>
                    <td><?= h($dictionaryValue->code) ?></td>
                    <td><?= h($dictionaryValue->translation) ?></td>
                    <td><?= h($dictionaryValue->label) ?></td>
                    <td><?= h($dictionaryValue->created) ?></td>
                    <td><?= h($dictionaryValue->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $dictionaryValue->dicoval_id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $dictionaryValue->dicoval_id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $dictionaryValue->dicoval_id], ['confirm' => __('Are you sure you want to delete # {0}?', $dictionaryValue->dicoval_id)]) ?>
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
