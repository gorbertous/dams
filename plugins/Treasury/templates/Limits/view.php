<?php
/**
 * @var \App\View\AppView $this
 * @var \Treasury\Model\Entity\Limit $limit
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Limit'), ['action' => 'edit', $limit->limit_ID], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Limit'), ['action' => 'delete', $limit->limit_ID], ['confirm' => __('Are you sure you want to delete # {0}?', $limit->limit_ID), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Limits'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Limit'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="limits view content">
            <h3><?= h($limit->limit_ID) ?></h3>
            <table>
                <tr>
                    <th><?= __('Limit Name') ?></th>
                    <td><?= h($limit->limit_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Rating Lt') ?></th>
                    <td><?= h($limit->rating_lt) ?></td>
                </tr>
                <tr>
                    <th><?= __('Rating St') ?></th>
                    <td><?= h($limit->rating_st) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cpty Rating') ?></th>
                    <td><?= h($limit->cpty_rating) ?></td>
                </tr>
                <tr>
                    <th><?= __('Concentration Limit Unit') ?></th>
                    <td><?= h($limit->concentration_limit_unit) ?></td>
                </tr>
                <tr>
                    <th><?= __('Limit ID') ?></th>
                    <td><?= $this->Number->format($limit->limit_ID) ?></td>
                </tr>
                <tr>
                    <th><?= __('Mandategroup ID') ?></th>
                    <td><?= $this->Number->format($limit->mandategroup_ID) ?></td>
                </tr>
                <tr>
                    <th><?= __('Counterpartygroup ID') ?></th>
                    <td><?= $this->Number->format($limit->counterpartygroup_ID) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cpty ID') ?></th>
                    <td><?= $this->Number->format($limit->cpty_ID) ?></td>
                </tr>
                <tr>
                    <th><?= __('Automatic') ?></th>
                    <td><?= $this->Number->format($limit->automatic) ?></td>
                </tr>
                <tr>
                    <th><?= __('Max Maturity') ?></th>
                    <td><?= $this->Number->format($limit->max_maturity) ?></td>
                </tr>
                <tr>
                    <th><?= __('Limit Eur') ?></th>
                    <td><?= $this->Number->format($limit->limit_eur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Max Concentration') ?></th>
                    <td><?= $this->Number->format($limit->max_concentration) ?></td>
                </tr>
                <tr>
                    <th><?= __('Is Current') ?></th>
                    <td><?= $this->Number->format($limit->is_current) ?></td>
                </tr>
                <tr>
                    <th><?= __('Limit Date From') ?></th>
                    <td><?= h($limit->limit_date_from) ?></td>
                </tr>
                <tr>
                    <th><?= __('Limit Date To') ?></th>
                    <td><?= h($limit->limit_date_to) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($limit->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($limit->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
