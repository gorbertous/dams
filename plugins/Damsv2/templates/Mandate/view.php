<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Mandate $mandate
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Mandate'), ['action' => 'edit', $mandate->mandate_id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Mandate'), ['action' => 'delete', $mandate->mandate_id], ['confirm' => __('Are you sure you want to delete # {0}?', $mandate->mandate_id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Mandate'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Mandate'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="mandate view content">
            <h3><?= h($mandate->mandate_id) ?></h3>
            <table class="table table-striped">
                <tr>
                    <th><?= __('Mandate Iqid') ?></th>
                    <td><?= h($mandate->mandate_iqid) ?></td>
                </tr>
                <tr>
                    <th><?= __('Mandate Name') ?></th>
                    <td><?= h($mandate->mandate_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Mandate Id') ?></th>
                    <td><?= $this->Number->format($mandate->mandate_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($mandate->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($mandate->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
