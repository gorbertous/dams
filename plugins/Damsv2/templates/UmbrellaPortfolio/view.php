<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UmbrellaPortfolio $umbrellaPortfolio
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Umbrella Portfolio'), ['action' => 'edit', $umbrellaPortfolio->umbrella_portfolio_id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Umbrella Portfolio'), ['action' => 'delete', $umbrellaPortfolio->umbrella_portfolio_id], ['confirm' => __('Are you sure you want to delete # {0}?', $umbrellaPortfolio->umbrella_portfolio_id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Umbrella Portfolio'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Umbrella Portfolio'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="umbrellaPortfolio view content">
            <h3><?= h($umbrellaPortfolio->umbrella_portfolio_id) ?></h3>
            <table class="table table-striped">
                <tr>
                    <th><?= __('Umbrella Portfolio Name') ?></th>
                    <td><?= h($umbrellaPortfolio->umbrella_portfolio_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Iqid') ?></th>
                    <td><?= h($umbrellaPortfolio->iqid) ?></td>
                </tr>
                <tr>
                    <th><?= __('Product') ?></th>
                    <td><?= $umbrellaPortfolio->has('product') ? $this->Html->link($umbrellaPortfolio->product->name, ['controller' => 'Product', 'action' => 'view', $umbrellaPortfolio->product->product_id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Splitting Field') ?></th>
                    <td><?= h($umbrellaPortfolio->splitting_field) ?></td>
                </tr>
                <tr>
                    <th><?= __('Splitting Table') ?></th>
                    <td><?= h($umbrellaPortfolio->splitting_table) ?></td>
                </tr>
                <tr>
                    <th><?= __('Umbrella Portfolio Id') ?></th>
                    <td><?= $this->Number->format($umbrellaPortfolio->umbrella_portfolio_id) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Deleted') ?></h4>
                <?php if (!empty($umbrellaPortfolio->deleted)) : ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Table Deleted') ?></th>
                            <th><?= __('Id Deleted') ?></th>
                            <th><?= __('Id 2 Deleted') ?></th>
                            <th><?= __('Deletion Date') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($umbrellaPortfolio->deleted as $deleted) : ?>
                        <tr>
                            <td><?= h($deleted->id) ?></td>
                            <td><?= h($deleted->table_deleted) ?></td>
                            <td><?= h($deleted->id_deleted) ?></td>
                            <td><?= h($deleted->id_2_deleted) ?></td>
                            <td><?= h($deleted->deletion_date) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Deleted', 'action' => 'view', $deleted->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Deleted', 'action' => 'edit', $deleted->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Deleted', 'action' => 'delete', $deleted->id], ['confirm' => __('Are you sure you want to delete # {0}?', $deleted->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
