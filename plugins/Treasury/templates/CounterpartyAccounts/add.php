<?php
/**
 * @var \App\View\AppView $this
 * @var \Treasury\Model\Entity\CounterpartyAccount $counterpartyAccount
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Counterparty Accounts'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="counterpartyAccounts form content">
            <?= $this->Form->create($counterpartyAccount) ?>
            <fieldset>
                <legend><?= __('Add Counterparty Account') ?></legend>
                <?php
                    echo $this->Form->control('cpty_id');
                    echo $this->Form->control('correspondent_bank');
                    echo $this->Form->control('correspondent_BIC');
                    echo $this->Form->control('currency');
                    echo $this->Form->control('account_IBAN');
                    echo $this->Form->control('target');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
