<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SmeRatingMapping $smeRatingMapping
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Sme Rating Mapping'), ['action' => 'edit', $smeRatingMapping->sme_rating_mapping_id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Sme Rating Mapping'), ['action' => 'delete', $smeRatingMapping->sme_rating_mapping_id], ['confirm' => __('Are you sure you want to delete # {0}?', $smeRatingMapping->sme_rating_mapping_id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Sme Rating Mapping'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Sme Rating Mapping'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="smeRatingMapping view content">
            <h3><?= h($smeRatingMapping->sme_rating_mapping_id) ?></h3>
            <table class="table table-striped">
                <tr>
                    <th><?= __('Portfolio') ?></th>
                    <td><?= $smeRatingMapping->has('portfolio') ? $this->Html->link($smeRatingMapping->portfolio->portfolio_id, ['controller' => 'Portfolio', 'action' => 'view', $smeRatingMapping->portfolio->portfolio_id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Sme Fi Rating Scale') ?></th>
                    <td><?= h($smeRatingMapping->sme_fi_rating_scale) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sme Rating') ?></th>
                    <td><?= h($smeRatingMapping->sme_rating) ?></td>
                </tr>
                <tr>
                    <th><?= __('Adjusted Sme Fi Scale') ?></th>
                    <td><?= h($smeRatingMapping->adjusted_sme_fi_scale) ?></td>
                </tr>
                <tr>
                    <th><?= __('Adjusted Sme Rating') ?></th>
                    <td><?= h($smeRatingMapping->adjusted_sme_rating) ?></td>
                </tr>
                <tr>
                    <th><?= __('Equiv Ori Sme Rating') ?></th>
                    <td><?= h($smeRatingMapping->equiv_ori_sme_rating) ?></td>
                </tr>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $smeRatingMapping->has('v_user') ? $this->Html->link($smeRatingMapping->v_user->full_name, ['controller' => 'User', 'action' => 'view', $smeRatingMapping->v_user->full_name]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Sme Rating Mapping Id') ?></th>
                    <td><?= $this->Number->format($smeRatingMapping->sme_rating_mapping_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($smeRatingMapping->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($smeRatingMapping->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
