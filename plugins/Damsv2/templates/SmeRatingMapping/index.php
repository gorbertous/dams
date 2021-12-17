<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SmeRatingMapping[]|\Cake\Collection\CollectionInterface $smeRatingMapping
 */
?>
<div class="smeRatingMapping index content">
    <?= $this->Html->link(__('New Sme Rating Mapping'), ['action' => 'add'], ['class' => 'btn btn-primary float-right']) ?>
    <h3><?= __('Sme Rating Mapping') ?></h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('sme_rating_mapping_id') ?></th>
                    <th><?= $this->Paginator->sort('portfolio_id') ?></th>
                    <th><?= $this->Paginator->sort('sme_fi_rating_scale') ?></th>
                    <th><?= $this->Paginator->sort('sme_rating') ?></th>
                    <th><?= $this->Paginator->sort('adjusted_sme_fi_scale') ?></th>
                    <th><?= $this->Paginator->sort('adjusted_sme_rating') ?></th>
                    <th><?= $this->Paginator->sort('equiv_ori_sme_rating') ?></th>
                    <th><?= $this->Paginator->sort('user_id') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($smeRatingMapping as $smeRatingMapping): ?>
                <tr>
                    <td><?= $this->Number->format($smeRatingMapping->sme_rating_mapping_id) ?></td>
                    <td><?= $smeRatingMapping->has('portfolio') ? $this->Html->link($smeRatingMapping->portfolio->portfolio_id, ['controller' => 'Portfolio', 'action' => 'view', $smeRatingMapping->portfolio->portfolio_id]) : '' ?></td>
                    <td><?= h($smeRatingMapping->sme_fi_rating_scale) ?></td>
                    <td><?= h($smeRatingMapping->sme_rating) ?></td>
                    <td><?= h($smeRatingMapping->adjusted_sme_fi_scale) ?></td>
                    <td><?= h($smeRatingMapping->adjusted_sme_rating) ?></td>
                    <td><?= h($smeRatingMapping->equiv_ori_sme_rating) ?></td>
                    <td><?= $smeRatingMapping->has('v_user') ? $this->Html->link($smeRatingMapping->v_user->full_name, ['controller' => 'User', 'action' => 'view', $smeRatingMapping->v_user->full_name]) : '' ?></td>
                    <td><?= h($smeRatingMapping->created) ?></td>
                    <td><?= h($smeRatingMapping->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $smeRatingMapping->sme_rating_mapping_id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $smeRatingMapping->sme_rating_mapping_id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $smeRatingMapping->sme_rating_mapping_id], ['confirm' => __('Are you sure you want to delete # {0}?', $smeRatingMapping->sme_rating_mapping_id)]) ?>
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
