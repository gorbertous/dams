<?php
declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * IncludedTransaction Entity
 *
 * @property int $included_id
 * @property string|null $currency
 * @property float|null $fx_rate
 * @property float|null $cumulative_disbursed
 * @property float|null $cumulative_disbursed_eur
 * @property float|null $cumulative_disbursed_curr
 * @property float|null $cumulative_repaid
 * @property float|null $cumulative_repaid_eur
 * @property float|null $cumulative_repaid_curr
 * @property float|null $outstanding_principal
 * @property float|null $outstanding_principal_eur
 * @property float|null $outstanding_principal_curr
 * @property string|null $disbursement_ended
 * @property float|null $daily_avg_outstanding
 * @property float|null $daily_avg_outstanding_eur
 * @property float|null $daily_avg_outstanding_curr
 * @property float|null $daily_sum_outstanding
 * @property float|null $daily_sum_outstanding_eur
 * @property float|null $daily_sum_outstanding_curr
 * @property string|null $delinquent_transaction
 * @property int|null $delinquency_days
 * @property string|null $defaulted_transaction
 * @property string|null $comments
 * @property int|null $transaction_id
 * @property int|null $sme_id
 * @property int|null $portfolio_id
 * @property int|null $report_id
 * @property \Cake\I18n\FrozenDate|null $default_event_date
 * @property string|null $upside_realised
 * @property float|null $upside_amount_curr
 * @property float|null $upside_amount_eur
 * @property float|null $upside_amount
 * @property float|null $permit_add_inter_amount_curr
 * @property float|null $permit_add_inter_amount_eur
 * @property float|null $permit_add_inter_amount
 * @property float|null $amount_to_disburse
 * @property float|null $amount_to_disburse_curr
 * @property float|null $amount_to_disburse_eur
 * @property float|null $contractual_os_principal
 * @property float|null $contractual_os_principal_eur
 * @property float|null $contractual_os_principal_curr
 * @property string|null $sme_rating
 * @property string|null $fi_rating_scale
 * @property float|null $actual_os_principal_perf
 * @property float|null $actual_os_principal_perf_eur
 * @property float|null $actual_os_principal_perf_curr
 * @property float|null $cumulative_intr_repaid_curr
 * @property float|null $cumulative_intr_repaid_eur
 * @property float|null $cumulative_intr_repaid
 * @property float|null $fair_value
 * @property float|null $fair_value_eur
 * @property float|null $fair_value_curr
 * @property \Cake\I18n\FrozenDate|null $sme_rating_date
 * @property float|null $provisioned_amount
 * @property float|null $provisioned_amount_eur
 * @property float|null $provisioned_amount_curr
 * @property float|null $recovery_amount
 * @property float|null $recovery_amount_eur
 * @property float|null $recovery_amount_curr
 * @property float|null $equity_kicker_valuation
 * @property float|null $equity_kicker_valuation_eur
 * @property float|null $equity_kicker_valuation_curr
 * @property float|null $collateral_amount
 * @property float|null $collateral_amount_eur
 * @property float|null $collateral_amount_curr
 * @property float|null $current_income
 * @property string|null $bds_received
 * @property string|null $fr_status
 * @property string|null $bds_type
 * @property string|null $bds_cost
 * @property string|null $covid19_moratorium
 * @property float|null $maximum_exposure
 * @property float|null $maximum_exposure_eur
 * @property float|null $maximum_exposure_curr
 * @property string|null $covered_dilution
 * @property \Cake\I18n\FrozenDate|null $covered_dilution_date
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Transaction $transaction
 * @property \App\Model\Entity\Sme $sme
 * @property \App\Model\Entity\Portfolio $portfolio
 * @property \App\Model\Entity\Report $report
 */
class IncludedTransaction extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'currency' => true,
        'fx_rate' => true,
        'cumulative_disbursed' => true,
        'cumulative_disbursed_eur' => true,
        'cumulative_disbursed_curr' => true,
        'cumulative_repaid' => true,
        'cumulative_repaid_eur' => true,
        'cumulative_repaid_curr' => true,
        'outstanding_principal' => true,
        'outstanding_principal_eur' => true,
        'outstanding_principal_curr' => true,
        'disbursement_ended' => true,
        'daily_avg_outstanding' => true,
        'daily_avg_outstanding_eur' => true,
        'daily_avg_outstanding_curr' => true,
        'daily_sum_outstanding' => true,
        'daily_sum_outstanding_eur' => true,
        'daily_sum_outstanding_curr' => true,
        'delinquent_transaction' => true,
        'delinquency_days' => true,
        'defaulted_transaction' => true,
        'comments' => true,
        'transaction_id' => true,
        'sme_id' => true,
        'portfolio_id' => true,
        'report_id' => true,
        'default_event_date' => true,
        'upside_realised' => true,
        'upside_amount_curr' => true,
        'upside_amount_eur' => true,
        'upside_amount' => true,
        'permit_add_inter_amount_curr' => true,
        'permit_add_inter_amount_eur' => true,
        'permit_add_inter_amount' => true,
        'amount_to_disburse' => true,
        'amount_to_disburse_curr' => true,
        'amount_to_disburse_eur' => true,
        'contractual_os_principal' => true,
        'contractual_os_principal_eur' => true,
        'contractual_os_principal_curr' => true,
        'sme_rating' => true,
        'fi_rating_scale' => true,
        'actual_os_principal_perf' => true,
        'actual_os_principal_perf_eur' => true,
        'actual_os_principal_perf_curr' => true,
        'cumulative_intr_repaid_curr' => true,
        'cumulative_intr_repaid_eur' => true,
        'cumulative_intr_repaid' => true,
        'fair_value' => true,
        'fair_value_eur' => true,
        'fair_value_curr' => true,
        'sme_rating_date' => true,
        'provisioned_amount' => true,
        'provisioned_amount_eur' => true,
        'provisioned_amount_curr' => true,
        'recovery_amount' => true,
        'recovery_amount_eur' => true,
        'recovery_amount_curr' => true,
        'equity_kicker_valuation' => true,
        'equity_kicker_valuation_eur' => true,
        'equity_kicker_valuation_curr' => true,
        'collateral_amount' => true,
        'collateral_amount_eur' => true,
        'collateral_amount_curr' => true,
        'current_income' => true,
        'bds_received' => true,
        'fr_status' => true,
        'bds_type' => true,
        'bds_cost' => true,
        'covid19_moratorium' => true,
        'maximum_exposure' => true,
        'maximum_exposure_eur' => true,
        'maximum_exposure_curr' => true,
        'covered_dilution' => true,
        'covered_dilution_date' => true,
        'created' => true,
        'modified' => true,
        'transaction' => true,
        'sme' => true,
        'portfolio' => true,
        'report' => true,
    ];
}
