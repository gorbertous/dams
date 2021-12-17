<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product[]|\Cake\Collection\CollectionInterface $product
 */
?>
<div class="product index content">
    <?= $this->Html->link(__('New Product'), ['action' => 'add'], ['class' => 'btn btn-primary float-right']) ?>
    <h3><?= __('Product') ?></h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('product_id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('product_type') ?></th>
                    <th><?= $this->Paginator->sort('capped') ?></th>
                    <th><?= $this->Paginator->sort('fixed_rate') ?></th>
                    <th><?= $this->Paginator->sort('reporting_frequency') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($product as $product): ?>
                <tr>
                    <td><?= $this->Number->format($product->product_id) ?></td>
                    <td><?= h($product->name) ?></td>
                    <td><?= h($product->product_type) ?></td>
                    <td><?= h($product->capped) ?></td>
                    <td><?= h($product->fixed_rate) ?></td>
                    <td><?= h($product->reporting_frequency) ?></td>
                    <td><?= h($product->created) ?></td>
                    <td><?= h($product->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $product->product_id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $product->product_id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $product->product_id], ['confirm' => __('Are you sure you want to delete # {0}?', $product->product_id)]) ?>
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
