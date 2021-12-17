<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Dstoolbox $dstoolbox
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Dstoolbox'), ['action' => 'edit', $dstoolbox->dstoolbox_id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Dstoolbox'), ['action' => 'delete', $dstoolbox->dstoolbox_id], ['confirm' => __('Are you sure you want to delete # {0}?', $dstoolbox->dstoolbox_id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Dstoolbox'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Dstoolbox'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="dstoolbox view content">
            <h3><?= h($dstoolbox->name) ?></h3>
            <table class="table table-striped">
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($dstoolbox->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Filename') ?></th>
                    <td><?= h($dstoolbox->filename) ?></td>
                </tr>
                <tr>
                    <th><?= __('BO Url') ?></th>
                    <td><?= h($dstoolbox->BO_url) ?></td>
                </tr>
                <tr>
                    <th><?= __('Dstoolbox Id') ?></th>
                    <td><?= $this->Number->format($dstoolbox->dstoolbox_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Domain Id') ?></th>
                    <td><?= $this->Number->format($dstoolbox->domain_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Creation Date') ?></th>
                    <td><?= h($dstoolbox->creation_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modification Date') ?></th>
                    <td><?= h($dstoolbox->modification_date) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Description') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($dstoolbox->description)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>
