<?php
/**
 * @var \App\View\AppView $this
 * @var \Treasury\Model\Entity\Reinvestment $reinvestment
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Reinvestments'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="reinvestments form content">
            <?= $this->Form->create($reinvestment) ?>
            <fieldset>
                <legend><?= __('Add Reinvestment') ?></legend>
                <?php
                    echo $this->Form->control('reinv_status');
                    echo $this->Form->control('mandate_ID');
                    echo $this->Form->control('cmp_ID');
                    echo $this->Form->control('cpty_ID');
                    echo $this->Form->control('availability_date', ['empty' => true]);
                    echo $this->Form->control('accountA_IBAN');
                    echo $this->Form->control('accountB_IBAN');
                    echo $this->Form->control('amount_leftA');
                    echo $this->Form->control('amount_leftB');
                    echo $this->Form->control('reinv_type');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
