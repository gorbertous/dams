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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $tax->tax_ID],
                ['confirm' => __('Are you sure you want to delete # {0}?', $tax->tax_ID), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Taxes'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="taxes form content">
            <?= $this->Form->create($tax) ?>
            <fieldset>
                <legend><?= __('Edit Tax') ?></legend>
                <?php
                    echo $this->Form->control('mandate_ID');
                    echo $this->Form->control('cpty_ID');
                    echo $this->Form->control('tax_rate');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>