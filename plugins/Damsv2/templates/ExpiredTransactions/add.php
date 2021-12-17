<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ExpiredTransaction $expiredTransaction
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Expired Transactions'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="expiredTransactions form content">
            <?= $this->Form->create($expiredTransaction) ?>
            <fieldset>
                <legend><?= __('Add Expired Transaction') ?></legend>
                <?php
                    echo $this->Form->control('transaction_id', ['options' => $transactions, 'empty' => true]);
                    echo $this->Form->control('subtransaction_id', ['options' => $subtransactions, 'empty' => true]);
                    echo $this->Form->control('sme_id');
                    echo $this->Form->control('repayment_date', ['empty' => true]);
                    echo $this->Form->control('portfolio_id');
                    echo $this->Form->control('report_id');
                    echo $this->Form->control('nbr_employees_expired');
                    echo $this->Form->control('sale_date', ['empty' => true]);
                    echo $this->Form->control('sale_price');
                    echo $this->Form->control('sale_price_eur');
                    echo $this->Form->control('sale_price_curr');
                    echo $this->Form->control('write_off_date', ['empty' => true]);
                    echo $this->Form->control('write_off');
                    echo $this->Form->control('write_off_eur');
                    echo $this->Form->control('write_off_curr');
                ?>
            </fieldset>
                        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-outline-secondary my-3']); ?>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
