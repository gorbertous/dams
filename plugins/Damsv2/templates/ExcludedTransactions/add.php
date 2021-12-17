<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ExcludedTransaction $excludedTransaction
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Excluded Transactions'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="excludedTransactions form content">
            <?= $this->Form->create($excludedTransaction) ?>
            <fieldset>
                <legend><?= __('Add Excluded Transaction') ?></legend>
                <?php
                    echo $this->Form->control('sme_id');
                    echo $this->Form->control('transaction_id', ['options' => $transactions, 'empty' => true]);
                    echo $this->Form->control('subtransaction_id', ['options' => $subtransactions, 'empty' => true]);
                    echo $this->Form->control('exclusion_date', ['empty' => true]);
                    echo $this->Form->control('excluded_transaction_amount');
                    echo $this->Form->control('excluded_transaction_amount_eur');
                    echo $this->Form->control('excluded_transaction_amount_curr');
                    echo $this->Form->control('exclusion_type');
                    echo $this->Form->control('coverage_implication');
                    echo $this->Form->control('acceleration_flag');
                    echo $this->Form->control('comments');
                    echo $this->Form->control('portfolio_id');
                    echo $this->Form->control('report_id');
                ?>
            </fieldset>
                        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-outline-secondary my-3']); ?>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
