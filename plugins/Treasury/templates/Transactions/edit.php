<?php
/**
 * @var \App\View\AppView $this
 * @var \Treasury\Model\Entity\Transaction $transaction
 * @var string[]|\Cake\Collection\CollectionInterface $parentTransactions
 * @var string[]|\Cake\Collection\CollectionInterface $bonds
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $transaction->tr_number],
                ['confirm' => __('Are you sure you want to delete # {0}?', $transaction->tr_number), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Transactions'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="transactions form content">
            <?= $this->Form->create($transaction) ?>
            <fieldset>
                <legend><?= __('Edit Transaction') ?></legend>
                <?php
                    echo $this->Form->control('tr_type');
                    echo $this->Form->control('tr_state');
                    echo $this->Form->control('source_group');
                    echo $this->Form->control('reinv_group');
                    echo $this->Form->control('original_id');
                    echo $this->Form->control('parent_id', ['options' => $parentTransactions, 'empty' => true]);
                    echo $this->Form->control('linked_trn');
                    echo $this->Form->control('external_ref');
                    echo $this->Form->control('amount');
                    echo $this->Form->control('commencement_date', ['empty' => true]);
                    echo $this->Form->control('maturity_date', ['empty' => true]);
                    echo $this->Form->control('indicative_maturity_date', ['empty' => true]);
                    echo $this->Form->control('depo_term');
                    echo $this->Form->control('interest_rate');
                    echo $this->Form->control('total_interest');
                    echo $this->Form->control('tax_amount');
                    echo $this->Form->control('depo_type');
                    echo $this->Form->control('depo_renew');
                    echo $this->Form->control('rate_type');
                    echo $this->Form->control('date_basis');
                    echo $this->Form->control('mandate_ID');
                    echo $this->Form->control('cmp_ID');
                    echo $this->Form->control('scheme');
                    echo $this->Form->control('accountA_IBAN');
                    echo $this->Form->control('accountB_IBAN');
                    echo $this->Form->control('instr_num');
                    echo $this->Form->control('cpty_id');
                    echo $this->Form->control('ps_account');
                    echo $this->Form->control('booking_status');
                    echo $this->Form->control('eom_booking');
                    echo $this->Form->control('accrued_interst');
                    echo $this->Form->control('accrued_tax');
                    echo $this->Form->control('fixing_date', ['empty' => true]);
                    echo $this->Form->control('eom_interest');
                    echo $this->Form->control('eom_tax');
                    echo $this->Form->control('tax_ID');
                    echo $this->Form->control('source_fund');
                    echo $this->Form->control('comment');
                    echo $this->Form->control('reference_rate');
                    echo $this->Form->control('spread_bp');
                    echo $this->Form->control('benchmark');
                    echo $this->Form->control('bonds._ids', ['options' => $bonds]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
