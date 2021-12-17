<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\FixedRate[]|\Cake\Collection\CollectionInterface $fixedRate
 */
?>
<div class="fixedRate index content">
    <?= $this->Html->link(__('New Fixed Rate'), ['action' => 'add'], ['class' => 'btn btn-primary float-right']) ?>
    <h3><?= __('Fixed Rate') ?></h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('rate_id') ?></th>
                    <th><?= $this->Paginator->sort('portfolio_id') ?></th>
                    <th><?= $this->Paginator->sort('currency') ?></th>
                    <th><?= $this->Paginator->sort('obs_value') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fixedRate as $fixedRate): ?>
                <tr>
                    <td><?= $this->Number->format($fixedRate->rate_id) ?></td>
                    <td><?= $this->Number->format($fixedRate->portfolio_id) ?></td>
                    <td><?= h($fixedRate->currency) ?></td>
                    <td><?= $this->Number->format($fixedRate->obs_value) ?></td>
                    <td><?= h($fixedRate->created) ?></td>
                    <td><?= h($fixedRate->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $fixedRate->rate_id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $fixedRate->rate_id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $fixedRate->rate_id], ['confirm' => __('Are you sure you want to delete # {0}?', $fixedRate->rate_id)]) ?>
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
