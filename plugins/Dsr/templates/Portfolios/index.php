<?php
/**
 * @var \App\View\AppView $this
 * @var \Dsr\Model\Entity\Portfolio[]|\Cake\Collection\CollectionInterface $portfolios
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Portfolios',
        'url'     => ['controller' => 'portfolios', 'action' => 'index'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
<h3><?= __('Portfolios') ?></h3>

<?= $this->Html->link(__('New Portfolio'), ['action' => 'add'], ['class' => 'btn btn-primary float-right my-2 py-2']) ?>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('product_id') ?></th>
                <th><?= $this->Paginator->sort('name') ?></th>
                <th><?= $this->Paginator->sort('fi_name') ?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th><?= $this->Paginator->sort('modified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($portfolios as $portfolio): ?>
                <tr>
                    <td><?= $this->Number->format($portfolio->id) ?></td>
                    <td><?= $portfolio->product->name ?></td>
                    <td><?= h($portfolio->name) ?></td>
                    <td><?= h($portfolio->fi_name) ?></td>
                    <td><?= !empty($portfolio->created) ? h($portfolio->created->format('Y-m-d H:m:s')): '' ?></td>
                    <td><?= !empty($portfolio->modified) ? h($portfolio->created->format('Y-m-d H:m:s')): '' ?></td>
                    <td class="actions">
                        <?= $this->Html->link('<i class="fas fa-eye"></i> '.__('View'), ['action' => 'view', $portfolio->id], ['escape' => false, 'title' => __('View'), 'class' => 'btn btn-info btn-xs']) ?>
                       
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
