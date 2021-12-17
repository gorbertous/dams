<?php
/**
 * @var \App\View\AppView $this
 * @var \Dsr\Model\Entity\Product[]|\Cake\Collection\CollectionInterface $products
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Products',
        'url'     => ['controller' => 'products', 'action' => 'index'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>

<h3><?= __('Products') ?></h3>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('name') ?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th><?= $this->Paginator->sort('modified') ?></th>
                
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= $product->id ?></td>
                    <td><?= h($product->name) ?></td>
                    <td><?= !empty($product->created) ? h($product->created->format('Y-m-d H:m:s')): '' ?></td>
                    <td><?= !empty($product->modified) ? h($product->created->format('Y-m-d H:m:s')): '' ?></td>
                   
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

