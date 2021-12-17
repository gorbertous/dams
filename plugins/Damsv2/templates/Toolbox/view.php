<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Toolbox $toolbox
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Toolbox'), ['action' => 'edit', $toolbox->toolbox_id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Toolbox'), ['action' => 'delete', $toolbox->toolbox_id], ['confirm' => __('Are you sure you want to delete # {0}?', $toolbox->toolbox_id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Toolbox'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Toolbox'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="toolbox view content">
            <h3><?= h($toolbox->name) ?></h3>
            <table class="table table-striped">
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($toolbox->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Filename') ?></th>
                    <td><?= h($toolbox->filename) ?></td>
                </tr>
                <tr>
                    <th><?= __('Toolbox Id') ?></th>
                    <td><?= $this->Number->format($toolbox->toolbox_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Creation Date') ?></th>
                    <td><?= h($toolbox->creation_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modification Date') ?></th>
                    <td><?= h($toolbox->modification_date) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Description') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($toolbox->description)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>
