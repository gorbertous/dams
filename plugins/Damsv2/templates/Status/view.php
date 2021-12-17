<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Status $status
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Status'), ['action' => 'edit', $status->status_id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Status'), ['action' => 'delete', $status->status_id], ['confirm' => __('Are you sure you want to delete # {0}?', $status->status_id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Status'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Status'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="status view content">
            <h3><?= h($status->status_id) ?></h3>
            <table class="table table-striped">
                <tr>
                    <th><?= __('Stage') ?></th>
                    <td><?= h($status->stage) ?></td>
                </tr>
                <tr>
                    <th><?= __('Status') ?></th>
                    <td><?= h($status->status) ?></td>
                </tr>
                <tr>
                    <th><?= __('Action') ?></th>
                    <td><?= h($status->action) ?></td>
                </tr>
                <tr>
                    <th><?= __('Status Id') ?></th>
                    <td><?= $this->Number->format($status->status_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($status->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($status->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
