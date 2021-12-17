<?php
/**
 * @var \App\View\AppView $this
 * @var \Dsr\Model\Entity\Dictionary[]|\Cake\Collection\CollectionInterface $dictionaries
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
<h3><?= __('Dictionaries') ?></h3>
<?= $this->Html->link(__('New Dictionary'), ['action' => 'add'], ['class' => 'btn btn-primary float-right my-2 py-2']) ?>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('name') ?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th><?= $this->Paginator->sort('modified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dictionaries as $dictionary): ?>
                <tr>
                    <td><?= $this->Number->format($dictionary->id) ?></td>
                    <td><?= h($dictionary->name) ?></td>
                    <td><?= !empty($dictionary->created) ? h($dictionary->created->format('Y-m-d H:m:s')): '' ?></td>
                    <td><?= !empty($dictionary->modified) ? h($dictionary->created->format('Y-m-d H:m:s')): '' ?></td>
                    <td class="actions">
                        <?= $this->Html->link('<i class="fas fa-eye"></i>', ['action' => 'view', $dictionary->id], ['escape' => false, 'title' => __('View'), 'class' => 'btn btn-info btn-xs']) ?>

                        <?= $this->Html->link('<i class="fas fa-pencil-alt"></i>', ['action' => 'edit', $dictionary->id], ['escape' => false, 'title' => __('Edit'), 'class' => 'btn btn-info btn-xs']) ?>
                        <?= $this->Form->postLink('<i class="fas fa-trash-alt"></i>', ['action' => 'delete', $dictionary->id], ['escape' => false, 'title' => __('Delete'), 'class' => 'btn btn-danger btn-xs'], ['confirm' => __('Are you sure you want to delete # {0}?', $dictionary->id)]) ?>

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

