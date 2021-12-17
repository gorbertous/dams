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
            <?= $this->Html->link(__('Edit Included Transaction'), ['action' => 'edit', $includedTransaction->included_id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Included Transaction'), ['action' => 'delete', $includedTransaction->included_id], ['confirm' => __('Are you sure you want to delete # {0}?', $includedTransaction->included_id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Included Transactions'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Included Transaction'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="includedTransactions view content">
            <h3><?= h($includedTransaction->included_id) ?></h3>
            <table class="table table-striped">
                <tr>
                    <th><?= __('Currency') ?></th>
                    <td><?= h($includedTransaction->currency) ?></td>
                </tr>
                <tr>
                    <th><?= __('Disbursement Ended') ?></th>
                    <td><?= h($includedTransaction->disbursement_ended) ?></td>
                </tr>
                <tr>
                    <th><?= __('Delinquent Transaction') ?></th>
                    <td><?= h($includedTransaction->delinquent_transaction) ?></td>
                </tr>
                <tr>
                    <th><?= __('Defaulted Transaction') ?></th>
                    <td><?= h($includedTransaction->defaulted_transaction) ?></td>
                </tr>
                <tr>
                    <th><?= __('Comments') ?></th>
                    <td><?= h($includedTransaction->comments) ?></td>
                </tr>
                <tr>
                    <th><?= __('Transaction') ?></th>
                    <td><?= $includedTransaction->has('transaction') ? $this->Html->link($includedTransaction->transaction->transaction_id, ['controller' => 'Transactions', 'action' => 'view', $includedTransaction->transaction->transaction_id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Upside Realised') ?></th>
                    <td><?= h($includedTransaction->upside_realised) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sme Rating') ?></th>
                    <td><?= h($includedTransaction->sme_rating) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fi Rating Scale') ?></th>
                    <td><?= h($includedTransaction->fi_rating_scale) ?></td>
                </tr>
                <tr>
                    <th><?= __('Bds Received') ?></th>
                    <td><?= h($includedTransaction->bds_received) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fr Status') ?></th>
                    <td><?= h($includedTransaction->fr_status) ?></td>
                </tr>
                <tr>
                    <th><?= __('Bds Type') ?></th>
                    <td><?= h($includedTransaction->bds_type) ?></td>
                </tr>
                <tr>
                    <th><?= __('Bds Cost') ?></th>
                    <td><?= h($includedTransaction->bds_cost) ?></td>
                </tr>
                <tr>
                    <th><?= __('Covid19 Moratorium') ?></th>
                    <td><?= h($includedTransaction->covid19_moratorium) ?></td>
                </tr>
                <tr>
                    <th><?= __('Covered Dilution') ?></th>
                    <td><?= h($includedTransaction->covered_dilution) ?></td>
                </tr>
                <tr>
                    <th><?= __('Included Id') ?></th>
                    <td><?= $this->Number->format($includedTransaction->included_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fx Rate') ?></th>
                    <td><?= $this->Number->format($includedTransaction->fx_rate) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cumulative Disbursed') ?></th>
                    <td><?= $this->Number->format($includedTransaction->cumulative_disbursed) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cumulative Disbursed Eur') ?></th>
                    <td><?= $this->Number->format($includedTransaction->cumulative_disbursed_eur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cumulative Disbursed Curr') ?></th>
                    <td><?= $this->Number->format($includedTransaction->cumulative_disbursed_curr) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cumulative Repaid') ?></th>
                    <td><?= $this->Number->format($includedTransaction->cumulative_repaid) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cumulative Repaid Eur') ?></th>
                    <td><?= $this->Number->format($includedTransaction->cumulative_repaid_eur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cumulative Repaid Curr') ?></th>
                    <td><?= $this->Number->format($includedTransaction->cumulative_repaid_curr) ?></td>
                </tr>
                <tr>
                    <th><?= __('Outstanding Principal') ?></th>
                    <td><?= $this->Number->format($includedTransaction->outstanding_principal) ?></td>
                </tr>
                <tr>
                    <th><?= __('Outstanding Principal Eur') ?></th>
                    <td><?= $this->Number->format($includedTransaction->outstanding_principal_eur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Outstanding Principal Curr') ?></th>
                    <td><?= $this->Number->format($includedTransaction->outstanding_principal_curr) ?></td>
                </tr>
                <tr>
                    <th><?= __('Daily Avg Outstanding') ?></th>
                    <td><?= $this->Number->format($includedTransaction->daily_avg_outstanding) ?></td>
                </tr>
                <tr>
                    <th><?= __('Daily Avg Outstanding Eur') ?></th>
                    <td><?= $this->Number->format($includedTransaction->daily_avg_outstanding_eur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Daily Avg Outstanding Curr') ?></th>
                    <td><?= $this->Number->format($includedTransaction->daily_avg_outstanding_curr) ?></td>
                </tr>
                <tr>
                    <th><?= __('Daily Sum Outstanding') ?></th>
                    <td><?= $this->Number->format($includedTransaction->daily_sum_outstanding) ?></td>
                </tr>
                <tr>
                    <th><?= __('Daily Sum Outstanding Eur') ?></th>
                    <td><?= $this->Number->format($includedTransaction->daily_sum_outstanding_eur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Daily Sum Outstanding Curr') ?></th>
                    <td><?= $this->Number->format($includedTransaction->daily_sum_outstanding_curr) ?></td>
                </tr>
                <tr>
                    <th><?= __('Delinquency Days') ?></th>
                    <td><?= $this->Number->format($includedTransaction->delinquency_days) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sme Id') ?></th>
                    <td><?= $this->Number->format($includedTransaction->sme_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Portfolio Id') ?></th>
                    <td><?= $this->Number->format($includedTransaction->portfolio_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Report Id') ?></th>
                    <td><?= $this->Number->format($includedTransaction->report_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Upside Amount Curr') ?></th>
                    <td><?= $this->Number->format($includedTransaction->upside_amount_curr) ?></td>
                </tr>
                <tr>
                    <th><?= __('Upside Amount Eur') ?></th>
                    <td><?= $this->Number->format($includedTransaction->upside_amount_eur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Upside Amount') ?></th>
                    <td><?= $this->Number->format($includedTransaction->upside_amount) ?></td>
                </tr>
                <tr>
                    <th><?= __('Permit Add Inter Amount Curr') ?></th>
                    <td><?= $this->Number->format($includedTransaction->permit_add_inter_amount_curr) ?></td>
                </tr>
                <tr>
                    <th><?= __('Permit Add Inter Amount Eur') ?></th>
                    <td><?= $this->Number->format($includedTransaction->permit_add_inter_amount_eur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Permit Add Inter Amount') ?></th>
                    <td><?= $this->Number->format($includedTransaction->permit_add_inter_amount) ?></td>
                </tr>
                <tr>
                    <th><?= __('Amount To Disburse') ?></th>
                    <td><?= $this->Number->format($includedTransaction->amount_to_disburse) ?></td>
                </tr>
                <tr>
                    <th><?= __('Amount To Disburse Curr') ?></th>
                    <td><?= $this->Number->format($includedTransaction->amount_to_disburse_curr) ?></td>
                </tr>
                <tr>
                    <th><?= __('Amount To Disburse Eur') ?></th>
                    <td><?= $this->Number->format($includedTransaction->amount_to_disburse_eur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Contractual Os Principal') ?></th>
                    <td><?= $this->Number->format($includedTransaction->contractual_os_principal) ?></td>
                </tr>
                <tr>
                    <th><?= __('Contractual Os Principal Eur') ?></th>
                    <td><?= $this->Number->format($includedTransaction->contractual_os_principal_eur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Contractual Os Principal Curr') ?></th>
                    <td><?= $this->Number->format($includedTransaction->contractual_os_principal_curr) ?></td>
                </tr>
                <tr>
                    <th><?= __('Actual Os Principal Perf') ?></th>
                    <td><?= $this->Number->format($includedTransaction->actual_os_principal_perf) ?></td>
                </tr>
                <tr>
                    <th><?= __('Actual Os Principal Perf Eur') ?></th>
                    <td><?= $this->Number->format($includedTransaction->actual_os_principal_perf_eur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Actual Os Principal Perf Curr') ?></th>
                    <td><?= $this->Number->format($includedTransaction->actual_os_principal_perf_curr) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cumulative Intr Repaid Curr') ?></th>
                    <td><?= $this->Number->format($includedTransaction->cumulative_intr_repaid_curr) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cumulative Intr Repaid Eur') ?></th>
                    <td><?= $this->Number->format($includedTransaction->cumulative_intr_repaid_eur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cumulative Intr Repaid') ?></th>
                    <td><?= $this->Number->format($includedTransaction->cumulative_intr_repaid) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fair Value') ?></th>
                    <td><?= $this->Number->format($includedTransaction->fair_value) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fair Value Eur') ?></th>
                    <td><?= $this->Number->format($includedTransaction->fair_value_eur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fair Value Curr') ?></th>
                    <td><?= $this->Number->format($includedTransaction->fair_value_curr) ?></td>
                </tr>
                <tr>
                    <th><?= __('Provisioned Amount') ?></th>
                    <td><?= $this->Number->format($includedTransaction->provisioned_amount) ?></td>
                </tr>
                <tr>
                    <th><?= __('Provisioned Amount Eur') ?></th>
                    <td><?= $this->Number->format($includedTransaction->provisioned_amount_eur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Provisioned Amount Curr') ?></th>
                    <td><?= $this->Number->format($includedTransaction->provisioned_amount_curr) ?></td>
                </tr>
                <tr>
                    <th><?= __('Recovery Amount') ?></th>
                    <td><?= $this->Number->format($includedTransaction->recovery_amount) ?></td>
                </tr>
                <tr>
                    <th><?= __('Recovery Amount Eur') ?></th>
                    <td><?= $this->Number->format($includedTransaction->recovery_amount_eur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Recovery Amount Curr') ?></th>
                    <td><?= $this->Number->format($includedTransaction->recovery_amount_curr) ?></td>
                </tr>
                <tr>
                    <th><?= __('Equity Kicker Valuation') ?></th>
                    <td><?= $this->Number->format($includedTransaction->equity_kicker_valuation) ?></td>
                </tr>
                <tr>
                    <th><?= __('Equity Kicker Valuation Eur') ?></th>
                    <td><?= $this->Number->format($includedTransaction->equity_kicker_valuation_eur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Equity Kicker Valuation Curr') ?></th>
                    <td><?= $this->Number->format($includedTransaction->equity_kicker_valuation_curr) ?></td>
                </tr>
                <tr>
                    <th><?= __('Collateral Amount') ?></th>
                    <td><?= $this->Number->format($includedTransaction->collateral_amount) ?></td>
                </tr>
                <tr>
                    <th><?= __('Collateral Amount Eur') ?></th>
                    <td><?= $this->Number->format($includedTransaction->collateral_amount_eur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Collateral Amount Curr') ?></th>
                    <td><?= $this->Number->format($includedTransaction->collateral_amount_curr) ?></td>
                </tr>
                <tr>
                    <th><?= __('Current Income') ?></th>
                    <td><?= $this->Number->format($includedTransaction->current_income) ?></td>
                </tr>
                <tr>
                    <th><?= __('Maximum Exposure') ?></th>
                    <td><?= $this->Number->format($includedTransaction->maximum_exposure) ?></td>
                </tr>
                <tr>
                    <th><?= __('Maximum Exposure Eur') ?></th>
                    <td><?= $this->Number->format($includedTransaction->maximum_exposure_eur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Maximum Exposure Curr') ?></th>
                    <td><?= $this->Number->format($includedTransaction->maximum_exposure_curr) ?></td>
                </tr>
                <tr>
                    <th><?= __('Default Event Date') ?></th>
                    <td><?= h($includedTransaction->default_event_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sme Rating Date') ?></th>
                    <td><?= h($includedTransaction->sme_rating_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Covered Dilution Date') ?></th>
                    <td><?= h($includedTransaction->covered_dilution_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($includedTransaction->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($includedTransaction->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
