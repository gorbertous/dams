<?php
/**
 * @var \App\View\AppView $this
 * @var \Dsr\Model\Entity\DicoValue[]|\Cake\Collection\CollectionInterface $dicoValues
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Dictionaries',
        'url'     => ['controller' => 'dictionaries', 'action' => 'index'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>

<?= $this->Html->link(__('New Dic Value'), ['action' => 'add'], ['class' => 'btn btn-primary float-right my-2 py-2']) ?>
<h3><?= __('Dico Values') ?></h3>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('dictionary_id') ?></th>
                <th><?= $this->Paginator->sort('code') ?></th>
                <th><?= $this->Paginator->sort('label') ?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th><?= $this->Paginator->sort('modified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dicoValues as $dicoValue): ?>
                <tr>
                    <td><?= $this->Number->format($dicoValue->id) ?></td>
                    <td><?= $dicoValue->has('dictionary') ? $this->Html->link($dicoValue->dictionary->name, ['controller' => 'Dictionaries', 'action' => 'view', $dicoValue->dictionary->id]) : '' ?></td>
                    <td><?= h($dicoValue->code) ?></td>
                    <td><?= h($dicoValue->label) ?></td>
                    <td><?= h($dicoValue->created) ?></td>
                    <td><?= h($dicoValue->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link('<i class="fas fa-eye"></i>', ['action' => 'view', $dicoValue->id], ['escape' => false, 'title' => __('View'), 'class' => 'btn btn-info btn-xs']) ?>
                        <?= $this->Html->link('<i class="fas fa-pencil-alt"></i>', ['action' => 'edit', $dicoValue->id], ['escape' => false, 'title' => __('Edit'), 'class' => 'btn btn-info btn-xs']) ?>

                        <?= $this->Form->postLink('<i class="fas fa-trash-alt"></i>', ['action' => 'delete', $dicoValue->id], ['escape' => false, 'title' => __('Delete'), 'class' => 'btn btn-danger btn-xs my-2'], ['confirm' => __('Are you sure you want to delete # {0}?', $dicoValue->id)]) ?>
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

