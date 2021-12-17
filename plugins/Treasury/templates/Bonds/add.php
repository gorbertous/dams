<?php
/**
 * @var \App\View\AppView $this
 * @var \Treasury\Model\Entity\Bond $bond
 * @var \Cake\Collection\CollectionInterface|string[] $transactions
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Bonds'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="bonds form content">
            <?= $this->Form->create($bond) ?>
            <fieldset>
                <legend><?= __('Add Bond') ?></legend>
                <?php
                    echo $this->Form->control('ISIN');
                    echo $this->Form->control('state');
                    echo $this->Form->control('currency');
                    echo $this->Form->control('issuer');
                    echo $this->Form->control('issue_date', ['empty' => true]);
                    echo $this->Form->control('first_coupon_accrual_date', ['empty' => true]);
                    echo $this->Form->control('first_coupon_payment_date', ['empty' => true]);
                    echo $this->Form->control('maturity_date', ['empty' => true]);
                    echo $this->Form->control('coupon_rate');
                    echo $this->Form->control('coupon_frequency');
                    echo $this->Form->control('date_basis');
                    echo $this->Form->control('date_convention');
                    echo $this->Form->control('tax_rate');
                    echo $this->Form->control('country');
                    echo $this->Form->control('issue_size');
                    echo $this->Form->control('covered');
                    echo $this->Form->control('secured');
                    echo $this->Form->control('seniority');
                    echo $this->Form->control('guarantor');
                    echo $this->Form->control('structured');
                    echo $this->Form->control('issuer_type');
                    echo $this->Form->control('issue_rating_STP');
                    echo $this->Form->control('issue_rating_MDY');
                    echo $this->Form->control('issue_rating_FIT');
                    echo $this->Form->control('retained_rating');
                    echo $this->Form->control('transactions._ids', ['options' => $transactions]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
