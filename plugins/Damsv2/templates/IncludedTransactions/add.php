<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\IncludedTransaction $includedTransaction
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Included Transactions'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="includedTransactions form content">
            <?= $this->Form->create($includedTransaction) ?>
            <fieldset>
                <legend><?= __('Add Included Transaction') ?></legend>
                <?php
                    echo $this->Form->control('currency');
                    echo $this->Form->control('fx_rate');
                    echo $this->Form->control('cumulative_disbursed');
                    echo $this->Form->control('cumulative_disbursed_eur');
                    echo $this->Form->control('cumulative_disbursed_curr');
                    echo $this->Form->control('cumulative_repaid');
                    echo $this->Form->control('cumulative_repaid_eur');
                    echo $this->Form->control('cumulative_repaid_curr');
                    echo $this->Form->control('outstanding_principal');
                    echo $this->Form->control('outstanding_principal_eur');
                    echo $this->Form->control('outstanding_principal_curr');
                    echo $this->Form->control('disbursement_ended');
                    echo $this->Form->control('daily_avg_outstanding');
                    echo $this->Form->control('daily_avg_outstanding_eur');
                    echo $this->Form->control('daily_avg_outstanding_curr');
                    echo $this->Form->control('daily_sum_outstanding');
                    echo $this->Form->control('daily_sum_outstanding_eur');
                    echo $this->Form->control('daily_sum_outstanding_curr');
                    echo $this->Form->control('delinquent_transaction');
                    echo $this->Form->control('delinquency_days');
                    echo $this->Form->control('defaulted_transaction');
                    echo $this->Form->control('comments');
                    echo $this->Form->control('transaction_id', ['options' => $transactions, 'empty' => true]);
                    echo $this->Form->control('sme_id');
                    echo $this->Form->control('portfolio_id');
                    echo $this->Form->control('report_id');
                    echo $this->Form->control('default_event_date', ['empty' => true]);
                    echo $this->Form->control('upside_realised');
                    echo $this->Form->control('upside_amount_curr');
                    echo $this->Form->control('upside_amount_eur');
                    echo $this->Form->control('upside_amount');
                    echo $this->Form->control('permit_add_inter_amount_curr');
                    echo $this->Form->control('permit_add_inter_amount_eur');
                    echo $this->Form->control('permit_add_inter_amount');
                    echo $this->Form->control('amount_to_disburse');
                    echo $this->Form->control('amount_to_disburse_curr');
                    echo $this->Form->control('amount_to_disburse_eur');
                    echo $this->Form->control('contractual_os_principal');
                    echo $this->Form->control('contractual_os_principal_eur');
                    echo $this->Form->control('contractual_os_principal_curr');
                    echo $this->Form->control('sme_rating');
                    echo $this->Form->control('fi_rating_scale');
                    echo $this->Form->control('actual_os_principal_perf');
                    echo $this->Form->control('actual_os_principal_perf_eur');
                    echo $this->Form->control('actual_os_principal_perf_curr');
                    echo $this->Form->control('cumulative_intr_repaid_curr');
                    echo $this->Form->control('cumulative_intr_repaid_eur');
                    echo $this->Form->control('cumulative_intr_repaid');
                    echo $this->Form->control('fair_value');
                    echo $this->Form->control('fair_value_eur');
                    echo $this->Form->control('fair_value_curr');
                    echo $this->Form->control('sme_rating_date', ['empty' => true]);
                    echo $this->Form->control('provisioned_amount');
                    echo $this->Form->control('provisioned_amount_eur');
                    echo $this->Form->control('provisioned_amount_curr');
                    echo $this->Form->control('recovery_amount');
                    echo $this->Form->control('recovery_amount_eur');
                    echo $this->Form->control('recovery_amount_curr');
                    echo $this->Form->control('equity_kicker_valuation');
                    echo $this->Form->control('equity_kicker_valuation_eur');
                    echo $this->Form->control('equity_kicker_valuation_curr');
                    echo $this->Form->control('collateral_amount');
                    echo $this->Form->control('collateral_amount_eur');
                    echo $this->Form->control('collateral_amount_curr');
                    echo $this->Form->control('current_income');
                    echo $this->Form->control('bds_received');
                    echo $this->Form->control('fr_status');
                    echo $this->Form->control('bds_type');
                    echo $this->Form->control('bds_cost');
                    echo $this->Form->control('covid19_moratorium');
                    echo $this->Form->control('maximum_exposure');
                    echo $this->Form->control('maximum_exposure_eur');
                    echo $this->Form->control('maximum_exposure_curr');
                    echo $this->Form->control('covered_dilution');
                    echo $this->Form->control('covered_dilution_date', ['empty' => true]);
                ?>
            </fieldset>
                        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-outline-secondary my-3']); ?>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
