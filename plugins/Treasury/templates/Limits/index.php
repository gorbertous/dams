<?php
/**
 * @var \App\View\AppView $this
 * @var \Treasury\Model\Entity\Limit[]|\Cake\Collection\CollectionInterface $limits
 */
?>
<div class="limits index content">
    <?= $this->Html->link(__('New Limit'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Limits') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('limit_ID') ?></th>
                    <th><?= $this->Paginator->sort('limit_name') ?></th>
                    <th><?= $this->Paginator->sort('limit_date_from') ?></th>
                    <th><?= $this->Paginator->sort('limit_date_to') ?></th>
                    <th><?= $this->Paginator->sort('mandategroup_ID') ?></th>
                    <th><?= $this->Paginator->sort('counterpartygroup_ID') ?></th>
                    <th><?= $this->Paginator->sort('cpty_ID') ?></th>
                    <th><?= $this->Paginator->sort('automatic') ?></th>
                    <th><?= $this->Paginator->sort('rating_lt') ?></th>
                    <th><?= $this->Paginator->sort('rating_st') ?></th>
                    <th><?= $this->Paginator->sort('cpty_rating') ?></th>
                    <th><?= $this->Paginator->sort('max_maturity') ?></th>
                    <th><?= $this->Paginator->sort('limit_eur') ?></th>
                    <th><?= $this->Paginator->sort('max_concentration') ?></th>
                    <th><?= $this->Paginator->sort('concentration_limit_unit') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th><?= $this->Paginator->sort('is_current') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($limits as $limit): ?>
                <tr>
                    <td><?= $this->Number->format($limit->limit_ID) ?></td>
                    <td><?= h($limit->limit_name) ?></td>
                    <td><?= h($limit->limit_date_from) ?></td>
                    <td><?= h($limit->limit_date_to) ?></td>
                    <td><?= $this->Number->format($limit->mandategroup_ID) ?></td>
                    <td><?= $this->Number->format($limit->counterpartygroup_ID) ?></td>
                    <td><?= $this->Number->format($limit->cpty_ID) ?></td>
                    <td><?= $this->Number->format($limit->automatic) ?></td>
                    <td><?= h($limit->rating_lt) ?></td>
                    <td><?= h($limit->rating_st) ?></td>
                    <td><?= h($limit->cpty_rating) ?></td>
                    <td><?= $this->Number->format($limit->max_maturity) ?></td>
                    <td><?= $this->Number->format($limit->limit_eur) ?></td>
                    <td><?= $this->Number->format($limit->max_concentration) ?></td>
                    <td><?= h($limit->concentration_limit_unit) ?></td>
                    <td><?= h($limit->created) ?></td>
                    <td><?= h($limit->modified) ?></td>
                    <td><?= $this->Number->format($limit->is_current) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $limit->limit_ID]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $limit->limit_ID]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $limit->limit_ID], ['confirm' => __('Are you sure you want to delete # {0}?', $limit->limit_ID)]) ?>
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
