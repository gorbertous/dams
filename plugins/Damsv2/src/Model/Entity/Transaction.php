<?php
declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * Transaction Entity
 *
 * @property int $transaction_id
 * @property string|null $fiscal_number
 * @property string|null $siret
 * @property string $transaction_reference
 * @property string|null $currency
 * @property float|null $fx_rate
 * @property string|null $purpose
 * @property float|null $investment_amount
 * @property float|null $investment_amount_eur
 * @property float|null $investment_amount_curr
 * @property float|null $working_capital
 * @property float|null $working_capital_eur
 * @property float|null $working_capital_curr
 * @property float|null $principal_amount
 * @property float|null $principal_amount_eur
 * @property float|null $principal_amount_curr
 * @property float|null $purchase_price
 * @property float|null $purchase_price_eur
 * @property float|null $purchase_price_curr
 * @property float|null $down_payment
 * @property float|null $down_payment_eur
 * @property float|null $down_payment_curr
 * @property float|null $baloon_amount
 * @property float|null $baloon_amount_eur
 * @property float|null $baloon_amount_curr
 * @property float|null $maturity
 * @property float|null $additional_maturity
 * @property float|null $grace_period
 * @property \Cake\I18n\FrozenDate|null $final_maturity_date
 * @property \Cake\I18n\FrozenDate|null $signature_date
 * @property \Cake\I18n\FrozenDate|null $first_disbursement_date
 * @property \Cake\I18n\FrozenDate|null $first_instalment_date
 * @property string|null $repayment_frequency
 * @property float|null $collateralisation_rate
 * @property string|null $standard_rate
 * @property string|null $reference_rate
 * @property \Cake\I18n\FrozenDate|null $interest_rate_date
 * @property float|null $interest_rate
 * @property string|null $interest_rate_txt
 * @property string|null $interest_rate_type
 * @property float|null $rsi_guarantee_fee_rate
 * @property float|null $lgd
 * @property float|null $total_project_cost
 * @property float|null $total_project_cost_eur
 * @property float|null $total_project_cost_curr
 * @property float|null $allocation_amount
 * @property float|null $allocation_amount_eur
 * @property float|null $allocation_amount_curr
 * @property string|null $project_description
 * @property string|null $on_lending_bank
 * @property string|null $olb_address
 * @property string|null $olb_postal_code
 * @property string|null $olb_place
 * @property string|null $pass_through_institution
 * @property string|null $acc_flag
 * @property \Cake\I18n\FrozenDate|null $acc_date
 * @property string|null $acc_type
 * @property float|null $ori_principal_amount
 * @property float|null $ori_principal_amount_eur
 * @property float|null $ori_principal_amount_curr
 * @property string|null $partial_exclusion
 * @property string|null $amortisation_profile
 * @property string|null $investment_location
 * @property string|null $investment_location_lau
 * @property string|null $territory_type
 * @property float|null $gge_amount
 * @property float|null $gge_amount_eur
 * @property float|null $gge_amount_curr
 * @property int|null $gge_change
 * @property \Cake\I18n\FrozenDate|null $gge_modification_date
 * @property float|null $gge_additional
 * @property float|null $gge_additional_eur
 * @property float|null $gge_additional_curr
 * @property string|null $gge_calc_method
 * @property int|null $sme_history_at_trn
 * @property int|null $sme_history_at_report
 * @property string|null $transaction_comments
 * @property string|null $error_message
 * @property int|null $sme_id
 * @property int|null $portfolio_id
 * @property int|null $report_id
 * @property string|null $loan_type
 * @property string|null $invest_nace_bg
 * @property string|null $waiver
 * @property string|null $waiver_reason
 * @property string|null $waiver_details
 * @property float|null $applied_guarantee_rate
 * @property float|null $applied_cap_rate
 * @property string|null $thematic_category
 * @property string|null $transaction_status
 * @property string|null $trn_exclusion_flag
 * @property string|null $early_termination
 * @property float|null $sme_turnover
 * @property float|null $sme_assets
 * @property string|null $sme_target_beneficiary
 * @property float|null $sme_nbr_employees
 * @property string|null $sme_sector
 * @property string|null $sme_rating
 * @property string|null $sme_current_rating
 * @property string|null $sme_borrower_type
 * @property string|null $sme_eligibility_criteria
 * @property string|null $sme_level_digitalization
 * @property float|null $tangible_assets
 * @property float|null $tangible_assets_eur
 * @property float|null $tangible_assets_curr
 * @property float|null $intangible_assets
 * @property float|null $intangible_assets_eur
 * @property float|null $intangible_assets_curr
 * @property float|null $collateral_amount
 * @property float|null $collateral_amount_eur
 * @property float|null $collateral_amount_curr
 * @property string|null $collateral_type
 * @property string|null $eu_program
 * @property string|null $EFSI_trn
 * @property string|null $retroactivity_flag
 * @property string|null $sme_fi_rating_scale
 * @property string|null $sme_current_fi_rating_scale
 * @property string|null $publication
 * @property float|null $fi_risk_sharing_rate
 * @property \Cake\I18n\FrozenDate|null $fi_signature_date
 * @property string|null $eco_innovation
 * @property string|null $converted_reference
 * @property \Cake\I18n\FrozenDate|null $conversion_date
 * @property string|null $product_type
 * @property float|null $recovery_rate
 * @property float|null $interest_reduction
 * @property \Cake\I18n\FrozenDate|null $reperforming_start
 * @property \Cake\I18n\FrozenDate|null $reperforming_end
 * @property string|null $reperforming
 * @property string|null $fi
 * @property string|null $periodic_fee
 * @property string|null $permitted_add_inter_freq
 * @property string|null $permitted_add_interest
 * @property string|null $sampled
 * @property \Cake\I18n\FrozenDate|null $sampling_date
 * @property string|null $fi_review_sme_status
 * @property string|null $fi_review_refinancing
 * @property string|null $fi_review_purpose
 * @property string|null $fi_review_sector
 * @property string|null $linked_trn
 * @property string|null $stand_alone_loan
 * @property float|null $operation_type
 * @property string|null $priority_theme
 * @property float|null $state_aid_benefit
 * @property string|null $cn_code
 * @property string|null $renewal_generation
 * @property string|null $thematic_focus
 * @property string|null $agricultural_branch
 * @property float|null $agg_co_lenders_financing
 * @property float|null $agg_co_lenders_financing_eur
 * @property float|null $agg_co_lenders_financing_curr
 * @property float|null $nb_co_lenders
 * @property float|null $commitment_fee
 * @property float|null $ori_issue_discount
 * @property float|null $prepayment_penalty
 * @property float|null $upfront_fee
 * @property float|null $pik_interest_rate
 * @property string|null $pik_frequency
 * @property string|null $equity_kicker
 * @property float|null $residual_value
 * @property float|null $residual_value_eur
 * @property float|null $residual_value_curr
 * @property string|null $third_party_guarantor
 * @property float|null $guaranteed_percentage
 * @property string|null $primary_investment
 * @property string|null $senior_debt
 * @property string|null $non_distressed_instrument
 * @property string|null $exclusion_flag
 * @property string|null $exclusion_reason
 * @property string|null $youth_employment_loan
 * @property string|null $eligible_investment_skills
 * @property string|null $field_study
 * @property string|null $level_edu_programme
 * @property string|null $country_study
 * @property float|null $study_duration
 * @property float|null $periodic_fee_rate
 * @property string|null $fee_int_rate_period
 * @property float|null $one_off_fee
 * @property string|null $covid19_moratorium
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string|null $pkid
 *
 * @property \App\Model\Entity\Sme $sme
 * @property \App\Model\Entity\Portfolio $portfolio
 * @property \App\Model\Entity\Report $report
 */
class Transaction extends Entity
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
        'fiscal_number' => true,
        'siret' => true,
        'transaction_reference' => true,
        'currency' => true,
        'fx_rate' => true,
        'purpose' => true,
        'investment_amount' => true,
        'investment_amount_eur' => true,
        'investment_amount_curr' => true,
        'working_capital' => true,
        'working_capital_eur' => true,
        'working_capital_curr' => true,
        'principal_amount' => true,
        'principal_amount_eur' => true,
        'principal_amount_curr' => true,
        'purchase_price' => true,
        'purchase_price_eur' => true,
        'purchase_price_curr' => true,
        'down_payment' => true,
        'down_payment_eur' => true,
        'down_payment_curr' => true,
        'baloon_amount' => true,
        'baloon_amount_eur' => true,
        'baloon_amount_curr' => true,
        'maturity' => true,
        'additional_maturity' => true,
        'grace_period' => true,
        'final_maturity_date' => true,
        'signature_date' => true,
        'first_disbursement_date' => true,
        'first_instalment_date' => true,
        'repayment_frequency' => true,
        'collateralisation_rate' => true,
        'standard_rate' => true,
        'reference_rate' => true,
        'interest_rate_date' => true,
        'interest_rate' => true,
        'interest_rate_txt' => true,
        'interest_rate_type' => true,
        'rsi_guarantee_fee_rate' => true,
        'lgd' => true,
        'total_project_cost' => true,
        'total_project_cost_eur' => true,
        'total_project_cost_curr' => true,
        'allocation_amount' => true,
        'allocation_amount_eur' => true,
        'allocation_amount_curr' => true,
        'project_description' => true,
        'on_lending_bank' => true,
        'olb_address' => true,
        'olb_postal_code' => true,
        'olb_place' => true,
        'pass_through_institution' => true,
        'acc_flag' => true,
        'acc_date' => true,
        'acc_type' => true,
        'ori_principal_amount' => true,
        'ori_principal_amount_eur' => true,
        'ori_principal_amount_curr' => true,
        'partial_exclusion' => true,
        'amortisation_profile' => true,
        'investment_location' => true,
        'investment_location_lau' => true,
        'territory_type' => true,
        'gge_amount' => true,
        'gge_amount_eur' => true,
        'gge_amount_curr' => true,
        'gge_change' => true,
        'gge_modification_date' => true,
        'gge_additional' => true,
        'gge_additional_eur' => true,
        'gge_additional_curr' => true,
        'gge_calc_method' => true,
        'sme_history_at_trn' => true,
        'sme_history_at_report' => true,
        'transaction_comments' => true,
        'error_message' => true,
        'sme_id' => true,
        'portfolio_id' => true,
        'report_id' => true,
        'loan_type' => true,
        'invest_nace_bg' => true,
        'waiver' => true,
        'waiver_reason' => true,
        'waiver_details' => true,
        'applied_guarantee_rate' => true,
        'applied_cap_rate' => true,
        'thematic_category' => true,
        'transaction_status' => true,
        'trn_exclusion_flag' => true,
        'early_termination' => true,
        'sme_turnover' => true,
        'sme_assets' => true,
        'sme_target_beneficiary' => true,
        'sme_nbr_employees' => true,
        'sme_sector' => true,
        'sme_rating' => true,
        'sme_current_rating' => true,
        'sme_borrower_type' => true,
        'sme_eligibility_criteria' => true,
        'sme_level_digitalization' => true,
        'tangible_assets' => true,
        'tangible_assets_eur' => true,
        'tangible_assets_curr' => true,
        'intangible_assets' => true,
        'intangible_assets_eur' => true,
        'intangible_assets_curr' => true,
        'collateral_amount' => true,
        'collateral_amount_eur' => true,
        'collateral_amount_curr' => true,
        'collateral_type' => true,
        'eu_program' => true,
        'EFSI_trn' => true,
        'retroactivity_flag' => true,
        'sme_fi_rating_scale' => true,
        'sme_current_fi_rating_scale' => true,
        'publication' => true,
        'fi_risk_sharing_rate' => true,
        'fi_signature_date' => true,
        'eco_innovation' => true,
        'converted_reference' => true,
        'conversion_date' => true,
        'product_type' => true,
        'recovery_rate' => true,
        'interest_reduction' => true,
        'reperforming_start' => true,
        'reperforming_end' => true,
        'reperforming' => true,
        'fi' => true,
        'periodic_fee' => true,
        'permitted_add_inter_freq' => true,
        'permitted_add_interest' => true,
        'sampled' => true,
        'sampling_date' => true,
        'fi_review_sme_status' => true,
        'fi_review_refinancing' => true,
        'fi_review_purpose' => true,
        'fi_review_sector' => true,
        'linked_trn' => true,
        'stand_alone_loan' => true,
        'operation_type' => true,
        'priority_theme' => true,
        'state_aid_benefit' => true,
        'cn_code' => true,
        'renewal_generation' => true,
        'thematic_focus' => true,
        'agricultural_branch' => true,
        'agg_co_lenders_financing' => true,
        'agg_co_lenders_financing_eur' => true,
        'agg_co_lenders_financing_curr' => true,
        'nb_co_lenders' => true,
        'commitment_fee' => true,
        'ori_issue_discount' => true,
        'prepayment_penalty' => true,
        'upfront_fee' => true,
        'pik_interest_rate' => true,
        'pik_frequency' => true,
        'equity_kicker' => true,
        'residual_value' => true,
        'residual_value_eur' => true,
        'residual_value_curr' => true,
        'third_party_guarantor' => true,
        'guaranteed_percentage' => true,
        'primary_investment' => true,
        'senior_debt' => true,
        'non_distressed_instrument' => true,
        'exclusion_flag' => true,
        'exclusion_reason' => true,
        'youth_employment_loan' => true,
        'eligible_investment_skills' => true,
        'field_study' => true,
        'level_edu_programme' => true,
        'country_study' => true,
        'study_duration' => true,
        'periodic_fee_rate' => true,
        'fee_int_rate_period' => true,
        'one_off_fee' => true,
        'covid19_moratorium' => true,
        'created' => true,
        'modified' => true,
        'pkid' => true,
        'sme' => true,
        'portfolio' => true,
        'report' => true,
    ];
}
