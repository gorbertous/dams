<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Transaction $transaction
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $transaction->transaction_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $transaction->transaction_id), 'class' => 'side-nav-item']
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
                    echo $this->Form->control('fiscal_number');
                    echo $this->Form->control('siret');
                    echo $this->Form->control('transaction_reference');
                    echo $this->Form->control('currency');
                    echo $this->Form->control('fx_rate');
                    echo $this->Form->control('purpose');
                    echo $this->Form->control('investment_amount');
                    echo $this->Form->control('investment_amount_eur');
                    echo $this->Form->control('investment_amount_curr');
                    echo $this->Form->control('working_capital');
                    echo $this->Form->control('working_capital_eur');
                    echo $this->Form->control('working_capital_curr');
                    echo $this->Form->control('principal_amount');
                    echo $this->Form->control('principal_amount_eur');
                    echo $this->Form->control('principal_amount_curr');
                    echo $this->Form->control('purchase_price');
                    echo $this->Form->control('purchase_price_eur');
                    echo $this->Form->control('purchase_price_curr');
                    echo $this->Form->control('down_payment');
                    echo $this->Form->control('down_payment_eur');
                    echo $this->Form->control('down_payment_curr');
                    echo $this->Form->control('baloon_amount');
                    echo $this->Form->control('baloon_amount_eur');
                    echo $this->Form->control('baloon_amount_curr');
                    echo $this->Form->control('maturity');
                    echo $this->Form->control('additional_maturity');
                    echo $this->Form->control('grace_period');
                    echo $this->Form->control('final_maturity_date', ['empty' => true]);
                    echo $this->Form->control('signature_date', ['empty' => true]);
                    echo $this->Form->control('first_disbursement_date', ['empty' => true]);
                    echo $this->Form->control('first_instalment_date', ['empty' => true]);
                    echo $this->Form->control('repayment_frequency');
                    echo $this->Form->control('collateralisation_rate');
                    echo $this->Form->control('standard_rate');
                    echo $this->Form->control('reference_rate');
                    echo $this->Form->control('interest_rate_date', ['empty' => true]);
                    echo $this->Form->control('interest_rate');
                    echo $this->Form->control('interest_rate_txt');
                    echo $this->Form->control('interest_rate_type');
                    echo $this->Form->control('rsi_guarantee_fee_rate');
                    echo $this->Form->control('lgd');
                    echo $this->Form->control('total_project_cost');
                    echo $this->Form->control('total_project_cost_eur');
                    echo $this->Form->control('total_project_cost_curr');
                    echo $this->Form->control('allocation_amount');
                    echo $this->Form->control('allocation_amount_eur');
                    echo $this->Form->control('allocation_amount_curr');
                    echo $this->Form->control('project_description');
                    echo $this->Form->control('on_lending_bank');
                    echo $this->Form->control('olb_address');
                    echo $this->Form->control('olb_postal_code');
                    echo $this->Form->control('olb_place');
                    echo $this->Form->control('pass_through_institution');
                    echo $this->Form->control('acc_flag');
                    echo $this->Form->control('acc_date', ['empty' => true]);
                    echo $this->Form->control('acc_type');
                    echo $this->Form->control('ori_principal_amount');
                    echo $this->Form->control('ori_principal_amount_eur');
                    echo $this->Form->control('ori_principal_amount_curr');
                    echo $this->Form->control('partial_exclusion');
                    echo $this->Form->control('amortisation_profile');
                    echo $this->Form->control('investment_location');
                    echo $this->Form->control('investment_location_lau');
                    echo $this->Form->control('territory_type');
                    echo $this->Form->control('gge_amount');
                    echo $this->Form->control('gge_amount_eur');
                    echo $this->Form->control('gge_amount_curr');
                    echo $this->Form->control('gge_change');
                    echo $this->Form->control('gge_modification_date', ['empty' => true]);
                    echo $this->Form->control('gge_additional');
                    echo $this->Form->control('gge_additional_eur');
                    echo $this->Form->control('gge_additional_curr');
                    echo $this->Form->control('gge_calc_method');
                    echo $this->Form->control('sme_history_at_trn');
                    echo $this->Form->control('sme_history_at_report');
                    echo $this->Form->control('transaction_comments');
                    echo $this->Form->control('error_message');
                    echo $this->Form->control('sme_id');
                    echo $this->Form->control('portfolio_id');
                    echo $this->Form->control('report_id');
                    echo $this->Form->control('loan_type');
                    echo $this->Form->control('invest_nace_bg');
                    echo $this->Form->control('waiver');
                    echo $this->Form->control('waiver_reason');
                    echo $this->Form->control('waiver_details');
                    echo $this->Form->control('applied_guarantee_rate');
                    echo $this->Form->control('applied_cap_rate');
                    echo $this->Form->control('thematic_category');
                    echo $this->Form->control('transaction_status');
                    echo $this->Form->control('trn_exclusion_flag');
                    echo $this->Form->control('early_termination');
                    echo $this->Form->control('sme_turnover');
                    echo $this->Form->control('sme_assets');
                    echo $this->Form->control('sme_target_beneficiary');
                    echo $this->Form->control('sme_nbr_employees');
                    echo $this->Form->control('sme_sector');
                    echo $this->Form->control('sme_rating');
                    echo $this->Form->control('sme_current_rating');
                    echo $this->Form->control('sme_borrower_type');
                    echo $this->Form->control('sme_eligibility_criteria');
                    echo $this->Form->control('sme_level_digitalization');
                    echo $this->Form->control('tangible_assets');
                    echo $this->Form->control('tangible_assets_eur');
                    echo $this->Form->control('tangible_assets_curr');
                    echo $this->Form->control('intangible_assets');
                    echo $this->Form->control('intangible_assets_eur');
                    echo $this->Form->control('intangible_assets_curr');
                    echo $this->Form->control('collateral_amount');
                    echo $this->Form->control('collateral_amount_eur');
                    echo $this->Form->control('collateral_amount_curr');
                    echo $this->Form->control('collateral_type');
                    echo $this->Form->control('eu_program');
                    echo $this->Form->control('EFSI_trn');
                    echo $this->Form->control('retroactivity_flag');
                    echo $this->Form->control('sme_fi_rating_scale');
                    echo $this->Form->control('sme_current_fi_rating_scale');
                    echo $this->Form->control('publication');
                    echo $this->Form->control('fi_risk_sharing_rate');
                    echo $this->Form->control('fi_signature_date', ['empty' => true]);
                    echo $this->Form->control('eco_innovation');
                    echo $this->Form->control('converted_reference');
                    echo $this->Form->control('conversion_date', ['empty' => true]);
                    echo $this->Form->control('product_type');
                    echo $this->Form->control('recovery_rate');
                    echo $this->Form->control('interest_reduction');
                    echo $this->Form->control('reperforming_start', ['empty' => true]);
                    echo $this->Form->control('reperforming_end', ['empty' => true]);
                    echo $this->Form->control('reperforming');
                    echo $this->Form->control('fi');
                    echo $this->Form->control('periodic_fee');
                    echo $this->Form->control('permitted_add_inter_freq');
                    echo $this->Form->control('permitted_add_interest');
                    echo $this->Form->control('sampled');
                    echo $this->Form->control('sampling_date', ['empty' => true]);
                    echo $this->Form->control('fi_review_sme_status');
                    echo $this->Form->control('fi_review_refinancing');
                    echo $this->Form->control('fi_review_purpose');
                    echo $this->Form->control('fi_review_sector');
                    echo $this->Form->control('linked_trn');
                    echo $this->Form->control('stand_alone_loan');
                    echo $this->Form->control('operation_type');
                    echo $this->Form->control('priority_theme');
                    echo $this->Form->control('state_aid_benefit');
                    echo $this->Form->control('cn_code');
                    echo $this->Form->control('renewal_generation');
                    echo $this->Form->control('thematic_focus');
                    echo $this->Form->control('agricultural_branch');
                    echo $this->Form->control('agg_co_lenders_financing');
                    echo $this->Form->control('agg_co_lenders_financing_eur');
                    echo $this->Form->control('agg_co_lenders_financing_curr');
                    echo $this->Form->control('nb_co_lenders');
                    echo $this->Form->control('commitment_fee');
                    echo $this->Form->control('ori_issue_discount');
                    echo $this->Form->control('prepayment_penalty');
                    echo $this->Form->control('upfront_fee');
                    echo $this->Form->control('pik_interest_rate');
                    echo $this->Form->control('pik_frequency');
                    echo $this->Form->control('equity_kicker');
                    echo $this->Form->control('residual_value');
                    echo $this->Form->control('residual_value_eur');
                    echo $this->Form->control('residual_value_curr');
                    echo $this->Form->control('third_party_guarantor');
                    echo $this->Form->control('guaranteed_percentage');
                    echo $this->Form->control('primary_investment');
                    echo $this->Form->control('senior_debt');
                    echo $this->Form->control('non_distressed_instrument');
                    echo $this->Form->control('exclusion_flag');
                    echo $this->Form->control('exclusion_reason');
                    echo $this->Form->control('youth_employment_loan');
                    echo $this->Form->control('eligible_investment_skills');
                    echo $this->Form->control('field_study');
                    echo $this->Form->control('level_edu_programme');
                    echo $this->Form->control('country_study');
                    echo $this->Form->control('study_duration');
                    echo $this->Form->control('periodic_fee_rate');
                    echo $this->Form->control('fee_int_rate_period');
                    echo $this->Form->control('one_off_fee');
                    echo $this->Form->control('covid19_moratorium');
                    echo $this->Form->control('pkid');
                ?>
            </fieldset>
                        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-outline-secondary my-3']); ?>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
