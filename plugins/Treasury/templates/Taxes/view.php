<?php
/**
 * @var \App\View\AppView $this
 * @var \Treasury\Model\Entity\Tax $tax
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Tax'), ['action' => 'edit', $tax->tax_ID], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Tax'), ['action' => 'delete', $tax->tax_ID], ['confirm' => __('Are you sure you want to delete # {0}?', $tax->tax_ID), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Taxes'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Tax'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="taxes view content">
            <h3><?= h($tax->tax_ID) ?></h3>
            <table>
                <tr>
                    <th><?= __('Tax ID') ?></th>
                    <td><?= $this->Number->format($tax->tax_ID) ?></td>
                </tr>
                <tr>
                    <th><?= __('Mandate ID') ?></th>
                    <td><?= $this->Number->format($tax->mandate_ID) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cpty ID') ?></th>
                    <td><?= $this->Number->format($tax->cpty_ID) ?></td>
                </tr>
                <tr>
                    <th><?= __('Tax Rate') ?></th>
                    <td><?= $this->Number->format($tax->tax_rate) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($tax->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($tax->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
