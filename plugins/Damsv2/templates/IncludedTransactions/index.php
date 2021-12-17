<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\IncludedTransaction[]|\Cake\Collection\CollectionInterface $includedTransactions
 */
?>
<div class="includedTransactions index content">
    <?= $this->Html->link(__('New Included Transaction'), ['action' => 'add'], ['class' => 'btn btn-primary float-right']) ?>
    <h3><?= __('Included Transactions') ?></h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('included_id') ?></th>
                    <th><?= $this->Paginator->sort('currency') ?></th>
                    <th><?= $this->Paginator->sort('fx_rate') ?></th>
                    <th><?= $this->Paginator->sort('cumulative_disbursed') ?></th>
                    <th><?= $this->Paginator->sort('cumulative_disbursed_eur') ?></th>
                    <th><?= $this->Paginator->sort('cumulative_disbursed_curr') ?></th>
                    <th><?= $this->Paginator->sort('cumulative_repaid') ?></th>
                    <th><?= $this->Paginator->sort('cumulative_repaid_eur') ?></th>
                    <th><?= $this->Paginator->sort('cumulative_repaid_curr') ?></th>
                    <th><?= $this->Paginator->sort('outstanding_principal') ?></th>
                    <th><?= $this->Paginator->sort('outstanding_principal_eur') ?></th>
                    <th><?= $this->Paginator->sort('outstanding_principal_curr') ?></th>
                    <th><?= $this->Paginator->sort('disbursement_ended') ?></th>
                    <th><?= $this->Paginator->sort('daily_avg_outstanding') ?></th>
                    <th><?= $this->Paginator->sort('daily_avg_outstanding_eur') ?></th>
                    <th><?= $this->Paginator->sort('daily_avg_outstanding_curr') ?></th>
                    <th><?= $this->Paginator->sort('daily_sum_outstanding') ?></th>
                    <th><?= $this->Paginator->sort('daily_sum_outstanding_eur') ?></th>
                    <th><?= $this->Paginator->sort('daily_sum_outstanding_curr') ?></th>
                    <th><?= $this->Paginator->sort('delinquent_transaction') ?></th>
                    <th><?= $this->Paginator->sort('delinquency_days') ?></th>
                    <th><?= $this->Paginator->sort('defaulted_transaction') ?></th>
                    <th><?= $this->Paginator->sort('comments') ?></th>
                    <th><?= $this->Paginator->sort('transaction_id') ?></th>
                    <th><?= $this->Paginator->sort('sme_id') ?></th>
                    <th><?= $this->Paginator->sort('portfolio_id') ?></th>
                    <th><?= $this->Paginator->sort('report_id') ?></th>
                    <th><?= $this->Paginator->sort('default_event_date') ?></th>
                    <th><?= $this->Paginator->sort('upside_realised') ?></th>
                    <th><?= $this->Paginator->sort('upside_amount_curr') ?></th>
                    <th><?= $this->Paginator->sort('upside_amount_eur') ?></th>
                    <th><?= $this->Paginator->sort('upside_amount') ?></th>
                    <th><?= $this->Paginator->sort('permit_add_inter_amount_curr') ?></th>
                    <th><?= $this->Paginator->sort('permit_add_inter_amount_eur') ?></th>
                    <th><?= $this->Paginator->sort('permit_add_inter_amount') ?></th>
                    <th><?= $this->Paginator->sort('amount_to_disburse') ?></th>
                    <th><?= $this->Paginator->sort('amount_to_disburse_curr') ?></th>
                    <th><?= $this->Paginator->sort('amount_to_disburse_eur') ?></th>
                    <th><?= $this->Paginator->sort('contractual_os_principal') ?></th>
                    <th><?= $this->Paginator->sort('contractual_os_principal_eur') ?></th>
                    <th><?= $this->Paginator->sort('contractual_os_principal_curr') ?></th>
                    <th><?= $this->Paginator->sort('sme_rating') ?></th>
                    <th><?= $this->Paginator->sort('fi_rating_scale') ?></th>
                    <th><?= $this->Paginator->sort('actual_os_principal_perf') ?></th>
                    <th><?= $this->Paginator->sort('actual_os_principal_perf_eur') ?></th>
                    <th><?= $this->Paginator->sort('actual_os_principal_perf_curr') ?></th>
                    <th><?= $this->Paginator->sort('cumulative_intr_repaid_curr') ?></th>
                    <th><?= $this->Paginator->sort('cumulative_intr_repaid_eur') ?></th>
                    <th><?= $this->Paginator->sort('cumulative_intr_repaid') ?></th>
                    <th><?= $this->Paginator->sort('fair_value') ?></th>
                    <th><?= $this->Paginator->sort('fair_value_eur') ?></th>
                    <th><?= $this->Paginator->sort('fair_value_curr') ?></th>
                    <th><?= $this->Paginator->sort('sme_rating_date') ?></th>
                    <th><?= $this->Paginator->sort('provisioned_amount') ?></th>
                    <th><?= $this->Paginator->sort('provisioned_amount_eur') ?></th>
                    <th><?= $this->Paginator->sort('provisioned_amount_curr') ?></th>
                    <th><?= $this->Paginator->sort('recovery_amount') ?></th>
                    <th><?= $this->Paginator->sort('recovery_amount_eur') ?></th>
                    <th><?= $this->Paginator->sort('recovery_amount_curr') ?></th>
                    <th><?= $this->Paginator->sort('equity_kicker_valuation') ?></th>
                    <th><?= $this->Paginator->sort('equity_kicker_valuation_eur') ?></th>
                    <th><?= $this->Paginator->sort('equity_kicker_valuation_curr') ?></th>
                    <th><?= $this->Paginator->sort('collateral_amount') ?></th>
                    <th><?= $this->Paginator->sort('collateral_amount_eur') ?></th>
                    <th><?= $this->Paginator->sort('collateral_amount_curr') ?></th>
                    <th><?= $this->Paginator->sort('current_income') ?></th>
                    <th><?= $this->Paginator->sort('bds_received') ?></th>
                    <th><?= $this->Paginator->sort('fr_status') ?></th>
                    <th><?= $this->Paginator->sort('bds_type') ?></th>
                    <th><?= $this->Paginator->sort('bds_cost') ?></th>
                    <th><?= $this->Paginator->sort('covid19_moratorium') ?></th>
                    <th><?= $this->Paginator->sort('maximum_exposure') ?></th>
                    <th><?= $this->Paginator->sort('maximum_exposure_eur') ?></th>
                    <th><?= $this->Paginator->sort('maximum_exposure_curr') ?></th>
                    <th><?= $this->Paginator->sort('covered_dilution') ?></th>
                    <th><?= $this->Paginator->sort('covered_dilution_date') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($includedTransactions as $includedTransaction): ?>
                <tr>
                    <td><?= $this->Number->format($includedTransaction->included_id) ?></td>
                    <td><?= h($includedTransaction->currency) ?></td>
                    <td><?= $this->Number->format($includedTransaction->fx_rate) ?></td>
                    <td><?= $this->Number->format($includedTransaction->cumulative_disbursed) ?></td>
                    <td><?= $this->Number->format($includedTransaction->cumulative_disbursed_eur) ?></td>
                    <td><?= $this->Number->format($includedTransaction->cumulative_disbursed_curr) ?></td>
                    <td><?= $this->Number->format($includedTransaction->cumulative_repaid) ?></td>
                    <td><?= $this->Number->format($includedTransaction->cumulative_repaid_eur) ?></td>
                    <td><?= $this->Number->format($includedTransaction->cumulative_repaid_curr) ?></td>
                    <td><?= $this->Number->format($includedTransaction->outstanding_principal) ?></td>
                    <td><?= $this->Number->format($includedTransaction->outstanding_principal_eur) ?></td>
                    <td><?= $this->Number->format($includedTransaction->outstanding_principal_curr) ?></td>
                    <td><?= h($includedTransaction->disbursement_ended) ?></td>
                    <td><?= $this->Number->format($includedTransaction->daily_avg_outstanding) ?></td>
                    <td><?= $this->Number->format($includedTransaction->daily_avg_outstanding_eur) ?></td>
                    <td><?= $this->Number->format($includedTransaction->daily_avg_outstanding_curr) ?></td>
                    <td><?= $this->Number->format($includedTransaction->daily_sum_outstanding) ?></td>
                    <td><?= $this->Number->format($includedTransaction->daily_sum_outstanding_eur) ?></td>
                    <td><?= $this->Number->format($includedTransaction->daily_sum_outstanding_curr) ?></td>
                    <td><?= h($includedTransaction->delinquent_transaction) ?></td>
                    <td><?= $this->Number->format($includedTransaction->delinquency_days) ?></td>
                    <td><?= h($includedTransaction->defaulted_transaction) ?></td>
                    <td><?= h($includedTransaction->comments) ?></td>
                    <td><?= $includedTransaction->has('transaction') ? $this->Html->link($includedTransaction->transaction->transaction_id, ['controller' => 'Transactions', 'action' => 'view', $includedTransaction->transaction->transaction_id]) : '' ?></td>
                    <td><?= $this->Number->format($includedTransaction->sme_id) ?></td>
                    <td><?= $this->Number->format($includedTransaction->portfolio_id) ?></td>
                    <td><?= $this->Number->format($includedTransaction->report_id) ?></td>
                    <td><?= h($includedTransaction->default_event_date) ?></td>
                    <td><?= h($includedTransaction->upside_realised) ?></td>
                    <td><?= $this->Number->format($includedTransaction->upside_amount_curr) ?></td>
                    <td><?= $this->Number->format($includedTransaction->upside_amount_eur) ?></td>
                    <td><?= $this->Number->format($includedTransaction->upside_amount) ?></td>
                    <td><?= $this->Number->format($includedTransaction->permit_add_inter_amount_curr) ?></td>
                    <td><?= $this->Number->format($includedTransaction->permit_add_inter_amount_eur) ?></td>
                    <td><?= $this->Number->format($includedTransaction->permit_add_inter_amount) ?></td>
                    <td><?= $this->Number->format($includedTransaction->amount_to_disburse) ?></td>
                    <td><?= $this->Number->format($includedTransaction->amount_to_disburse_curr) ?></td>
                    <td><?= $this->Number->format($includedTransaction->amount_to_disburse_eur) ?></td>
                    <td><?= $this->Number->format($includedTransaction->contractual_os_principal) ?></td>
                    <td><?= $this->Number->format($includedTransaction->contractual_os_principal_eur) ?></td>
                    <td><?= $this->Number->format($includedTransaction->contractual_os_principal_curr) ?></td>
                    <td><?= h($includedTransaction->sme_rating) ?></td>
                    <td><?= h($includedTransaction->fi_rating_scale) ?></td>
                    <td><?= $this->Number->format($includedTransaction->actual_os_principal_perf) ?></td>
                    <td><?= $this->Number->format($includedTransaction->actual_os_principal_perf_eur) ?></td>
                    <td><?= $this->Number->format($includedTransaction->actual_os_principal_perf_curr) ?></td>
                    <td><?= $this->Number->format($includedTransaction->cumulative_intr_repaid_curr) ?></td>
                    <td><?= $this->Number->format($includedTransaction->cumulative_intr_repaid_eur) ?></td>
                    <td><?= $this->Number->format($includedTransaction->cumulative_intr_repaid) ?></td>
                    <td><?= $this->Number->format($includedTransaction->fair_value) ?></td>
                    <td><?= $this->Number->format($includedTransaction->fair_value_eur) ?></td>
                    <td><?= $this->Number->format($includedTransaction->fair_value_curr) ?></td>
                    <td><?= h($includedTransaction->sme_rating_date) ?></td>
                    <td><?= $this->Number->format($includedTransaction->provisioned_amount) ?></td>
                    <td><?= $this->Number->format($includedTransaction->provisioned_amount_eur) ?></td>
                    <td><?= $this->Number->format($includedTransaction->provisioned_amount_curr) ?></td>
                    <td><?= $this->Number->format($includedTransaction->recovery_amount) ?></td>
                    <td><?= $this->Number->format($includedTransaction->recovery_amount_eur) ?></td>
                    <td><?= $this->Number->format($includedTransaction->recovery_amount_curr) ?></td>
                    <td><?= $this->Number->format($includedTransaction->equity_kicker_valuation) ?></td>
                    <td><?= $this->Number->format($includedTransaction->equity_kicker_valuation_eur) ?></td>
                    <td><?= $this->Number->format($includedTransaction->equity_kicker_valuation_curr) ?></td>
                    <td><?= $this->Number->format($includedTransaction->collateral_amount) ?></td>
                    <td><?= $this->Number->format($includedTransaction->collateral_amount_eur) ?></td>
                    <td><?= $this->Number->format($includedTransaction->collateral_amount_curr) ?></td>
                    <td><?= $this->Number->format($includedTransaction->current_income) ?></td>
                    <td><?= h($includedTransaction->bds_received) ?></td>
                    <td><?= h($includedTransaction->fr_status) ?></td>
                    <td><?= h($includedTransaction->bds_type) ?></td>
                    <td><?= h($includedTransaction->bds_cost) ?></td>
                    <td><?= h($includedTransaction->covid19_moratorium) ?></td>
                    <td><?= $this->Number->format($includedTransaction->maximum_exposure) ?></td>
                    <td><?= $this->Number->format($includedTransaction->maximum_exposure_eur) ?></td>
                    <td><?= $this->Number->format($includedTransaction->maximum_exposure_curr) ?></td>
                    <td><?= h($includedTransaction->covered_dilution) ?></td>
                    <td><?= h($includedTransaction->covered_dilution_date) ?></td>
                    <td><?= h($includedTransaction->created) ?></td>
                    <td><?= h($includedTransaction->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $includedTransaction->included_id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $includedTransaction->included_id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $includedTransaction->included_id], ['confirm' => __('Are you sure you want to delete # {0}?', $includedTransaction->included_id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
