<?php
/**
 * @var \App\View\AppView $this
 * @var \Treasury\Model\Entity\Bond $bond
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Bond'), ['action' => 'edit', $bond->bond_id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Bond'), ['action' => 'delete', $bond->bond_id], ['confirm' => __('Are you sure you want to delete # {0}?', $bond->bond_id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Bonds'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Bond'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="bonds view content">
            <h3><?= h($bond->bond_id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Bond Id') ?></th>
                    <td><?= $this->Number->format($bond->bond_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Coupon Rate') ?></th>
                    <td><?= $this->Number->format($bond->coupon_rate) ?></td>
                </tr>
                <tr>
                    <th><?= __('Tax Rate') ?></th>
                    <td><?= $this->Number->format($bond->tax_rate) ?></td>
                </tr>
                <tr>
                    <th><?= __('Issue Size') ?></th>
                    <td><?= $this->Number->format($bond->issue_size) ?></td>
                </tr>
                <tr>
                    <th><?= __('Issue Date') ?></th>
                    <td><?= h($bond->issue_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('First Coupon Accrual Date') ?></th>
                    <td><?= h($bond->first_coupon_accrual_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('First Coupon Payment Date') ?></th>
                    <td><?= h($bond->first_coupon_payment_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Maturity Date') ?></th>
                    <td><?= h($bond->maturity_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($bond->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($bond->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Covered') ?></th>
                    <td><?= $bond->covered ? __('Yes') : __('No'); ?></td>
                </tr>
                <tr>
                    <th><?= __('Secured') ?></th>
                    <td><?= $bond->secured ? __('Yes') : __('No'); ?></td>
                </tr>
                <tr>
                    <th><?= __('Structured') ?></th>
                    <td><?= $bond->structured ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('ISIN') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($bond->ISIN)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('State') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($bond->state)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Currency') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($bond->currency)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Issuer') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($bond->issuer)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Coupon Frequency') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($bond->coupon_frequency)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Date Basis') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($bond->date_basis)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Date Convention') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($bond->date_convention)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Country') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($bond->country)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Seniority') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($bond->seniority)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Guarantor') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($bond->guarantor)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Issuer Type') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($bond->issuer_type)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Issue Rating STP') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($bond->issue_rating_STP)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Issue Rating MDY') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($bond->issue_rating_MDY)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Issue Rating FIT') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($bond->issue_rating_FIT)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Retained Rating') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($bond->retained_rating)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Transactions') ?></h4>
                <?php if (!empty($bond->transactions)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Transaction Id') ?></th>
                            <th><?= __('Fiscal Number') ?></th>
                            <th><?= __('Siret') ?></th>
                            <th><?= __('Transaction Reference') ?></th>
                            <th><?= __('Currency') ?></th>
                            <th><?= __('Fx Rate') ?></th>
                            <th><?= __('Purpose') ?></th>
                            <th><?= __('Investment Amount') ?></th>
                            <th><?= __('Investment Amount Eur') ?></th>
                            <th><?= __('Investment Amount Curr') ?></th>
                            <th><?= __('Working Capital') ?></th>
                            <th><?= __('Working Capital Eur') ?></th>
                            <th><?= __('Working Capital Curr') ?></th>
                            <th><?= __('Principal Amount') ?></th>
                            <th><?= __('Principal Amount Eur') ?></th>
                            <th><?= __('Principal Amount Curr') ?></th>
                            <th><?= __('Purchase Price') ?></th>
                            <th><?= __('Purchase Price Eur') ?></th>
                            <th><?= __('Purchase Price Curr') ?></th>
                            <th><?= __('Down Payment') ?></th>
                            <th><?= __('Down Payment Eur') ?></th>
                            <th><?= __('Down Payment Curr') ?></th>
                            <th><?= __('Baloon Amount') ?></th>
                            <th><?= __('Baloon Amount Eur') ?></th>
                            <th><?= __('Baloon Amount Curr') ?></th>
                            <th><?= __('Maturity') ?></th>
                            <th><?= __('Additional Maturity') ?></th>
                            <th><?= __('Grace Period') ?></th>
                            <th><?= __('Final Maturity Date') ?></th>
                            <th><?= __('Signature Date') ?></th>
                            <th><?= __('First Disbursement Date') ?></th>
                            <th><?= __('First Instalment Date') ?></th>
                            <th><?= __('Repayment Frequency') ?></th>
                            <th><?= __('Collateralisation Rate') ?></th>
                            <th><?= __('Standard Rate') ?></th>
                            <th><?= __('Reference Rate') ?></th>
                            <th><?= __('Interest Rate Date') ?></th>
                            <th><?= __('Interest Rate') ?></th>
                            <th><?= __('Interest Rate Txt') ?></th>
                            <th><?= __('Interest Rate Type') ?></th>
                            <th><?= __('Rsi Guarantee Fee Rate') ?></th>
                            <th><?= __('Lgd') ?></th>
                            <th><?= __('Total Project Cost') ?></th>
                            <th><?= __('Total Project Cost Eur') ?></th>
                            <th><?= __('Total Project Cost Curr') ?></th>
                            <th><?= __('Allocation Amount') ?></th>
                            <th><?= __('Allocation Amount Eur') ?></th>
                            <th><?= __('Allocation Amount Curr') ?></th>
                            <th><?= __('Project Description') ?></th>
                            <th><?= __('On Lending Bank') ?></th>
                            <th><?= __('Olb Address') ?></th>
                            <th><?= __('Olb Postal Code') ?></th>
                            <th><?= __('Olb Place') ?></th>
                            <th><?= __('Pass Through Institution') ?></th>
                            <th><?= __('Acc Flag') ?></th>
                            <th><?= __('Acc Date') ?></th>
                            <th><?= __('Acc Type') ?></th>
                            <th><?= __('Ori Principal Amount') ?></th>
                            <th><?= __('Ori Principal Amount Eur') ?></th>
                            <th><?= __('Ori Principal Amount Curr') ?></th>
                            <th><?= __('Partial Exclusion') ?></th>
                            <th><?= __('Amortisation Profile') ?></th>
                            <th><?= __('Investment Location') ?></th>
                            <th><?= __('Investment Location Lau') ?></th>
                            <th><?= __('Territory Type') ?></th>
                            <th><?= __('Gge Amount') ?></th>
                            <th><?= __('Gge Amount Eur') ?></th>
                            <th><?= __('Gge Amount Curr') ?></th>
                            <th><?= __('Gge Change') ?></th>
                            <th><?= __('Gge Modification Date') ?></th>
                            <th><?= __('Gge Additional') ?></th>
                            <th><?= __('Gge Additional Eur') ?></th>
                            <th><?= __('Gge Additional Curr') ?></th>
                            <th><?= __('Gge Calc Method') ?></th>
                            <th><?= __('Sme History At Trn') ?></th>
                            <th><?= __('Sme History At Report') ?></th>
                            <th><?= __('Transaction Comments') ?></th>
                            <th><?= __('Error Message') ?></th>
                            <th><?= __('Sme Id') ?></th>
                            <th><?= __('Portfolio Id') ?></th>
                            <th><?= __('Report Id') ?></th>
                            <th><?= __('Loan Type') ?></th>
                            <th><?= __('Invest Nace Bg') ?></th>
                            <th><?= __('Waiver') ?></th>
                            <th><?= __('Waiver Reason') ?></th>
                            <th><?= __('Waiver Details') ?></th>
                            <th><?= __('Applied Guarantee Rate') ?></th>
                            <th><?= __('Applied Cap Rate') ?></th>
                            <th><?= __('Thematic Category') ?></th>
                            <th><?= __('Transaction Status') ?></th>
                            <th><?= __('Trn Exclusion Flag') ?></th>
                            <th><?= __('Early Termination') ?></th>
                            <th><?= __('Sme Turnover') ?></th>
                            <th><?= __('Sme Assets') ?></th>
                            <th><?= __('Sme Target Beneficiary') ?></th>
                            <th><?= __('Sme Nbr Employees') ?></th>
                            <th><?= __('Sme Sector') ?></th>
                            <th><?= __('Sme Rating') ?></th>
                            <th><?= __('Sme Current Rating') ?></th>
                            <th><?= __('Sme Borrower Type') ?></th>
                            <th><?= __('Sme Eligibility Criteria') ?></th>
                            <th><?= __('Sme Level Digitalization') ?></th>
                            <th><?= __('Tangible Assets') ?></th>
                            <th><?= __('Tangible Assets Eur') ?></th>
                            <th><?= __('Tangible Assets Curr') ?></th>
                            <th><?= __('Intangible Assets') ?></th>
                            <th><?= __('Intangible Assets Eur') ?></th>
                            <th><?= __('Intangible Assets Curr') ?></th>
                            <th><?= __('Collateral Amount') ?></th>
                            <th><?= __('Collateral Amount Eur') ?></th>
                            <th><?= __('Collateral Amount Curr') ?></th>
                            <th><?= __('Collateral Type') ?></th>
                            <th><?= __('Eu Program') ?></th>
                            <th><?= __('EFSI Trn') ?></th>
                            <th><?= __('Retroactivity Flag') ?></th>
                            <th><?= __('Sme Fi Rating Scale') ?></th>
                            <th><?= __('Sme Current Fi Rating Scale') ?></th>
                            <th><?= __('Publication') ?></th>
                            <th><?= __('Fi Risk Sharing Rate') ?></th>
                            <th><?= __('Fi Signature Date') ?></th>
                            <th><?= __('Eco Innovation') ?></th>
                            <th><?= __('Converted Reference') ?></th>
                            <th><?= __('Conversion Date') ?></th>
                            <th><?= __('Product Type') ?></th>
                            <th><?= __('Recovery Rate') ?></th>
                            <th><?= __('Interest Reduction') ?></th>
                            <th><?= __('Reperforming Start') ?></th>
                            <th><?= __('Reperforming End') ?></th>
                            <th><?= __('Reperforming') ?></th>
                            <th><?= __('Fi') ?></th>
                            <th><?= __('Periodic Fee') ?></th>
                            <th><?= __('Permitted Add Inter Freq') ?></th>
                            <th><?= __('Permitted Add Interest') ?></th>
                            <th><?= __('Sampled') ?></th>
                            <th><?= __('Sampling Date') ?></th>
                            <th><?= __('Fi Review Sme Status') ?></th>
                            <th><?= __('Fi Review Refinancing') ?></th>
                            <th><?= __('Fi Review Purpose') ?></th>
                            <th><?= __('Fi Review Sector') ?></th>
                            <th><?= __('Linked Trn') ?></th>
                            <th><?= __('Stand Alone Loan') ?></th>
                            <th><?= __('Operation Type') ?></th>
                            <th><?= __('Priority Theme') ?></th>
                            <th><?= __('State Aid Benefit') ?></th>
                            <th><?= __('Cn Code') ?></th>
                            <th><?= __('Renewal Generation') ?></th>
                            <th><?= __('Thematic Focus') ?></th>
                            <th><?= __('Agricultural Branch') ?></th>
                            <th><?= __('Agg Co Lenders Financing') ?></th>
                            <th><?= __('Agg Co Lenders Financing Eur') ?></th>
                            <th><?= __('Agg Co Lenders Financing Curr') ?></th>
                            <th><?= __('Nb Co Lenders') ?></th>
                            <th><?= __('Commitment Fee') ?></th>
                            <th><?= __('Ori Issue Discount') ?></th>
                            <th><?= __('Prepayment Penalty') ?></th>
                            <th><?= __('Upfront Fee') ?></th>
                            <th><?= __('Pik Interest Rate') ?></th>
                            <th><?= __('Pik Frequency') ?></th>
                            <th><?= __('Equity Kicker') ?></th>
                            <th><?= __('Residual Value') ?></th>
                            <th><?= __('Residual Value Eur') ?></th>
                            <th><?= __('Residual Value Curr') ?></th>
                            <th><?= __('Third Party Guarantor') ?></th>
                            <th><?= __('Guaranteed Percentage') ?></th>
                            <th><?= __('Primary Investment') ?></th>
                            <th><?= __('Senior Debt') ?></th>
                            <th><?= __('Non Distressed Instrument') ?></th>
                            <th><?= __('Exclusion Flag') ?></th>
                            <th><?= __('Exclusion Reason') ?></th>
                            <th><?= __('Exclusion Comment') ?></th>
                            <th><?= __('Youth Employment Loan') ?></th>
                            <th><?= __('Eligible Investment Skills') ?></th>
                            <th><?= __('Field Study') ?></th>
                            <th><?= __('Level Edu Programme') ?></th>
                            <th><?= __('Country Study') ?></th>
                            <th><?= __('Study Duration') ?></th>
                            <th><?= __('Periodic Fee Rate') ?></th>
                            <th><?= __('Fee Int Rate Period') ?></th>
                            <th><?= __('One Off Fee') ?></th>
                            <th><?= __('Covid19 Moratorium') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Pkid') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($bond->transactions as $transactions) : ?>
                        <tr>
                            <td><?= h($transactions->transaction_id) ?></td>
                            <td><?= h($transactions->fiscal_number) ?></td>
                            <td><?= h($transactions->siret) ?></td>
                            <td><?= h($transactions->transaction_reference) ?></td>
                            <td><?= h($transactions->currency) ?></td>
                            <td><?= h($transactions->fx_rate) ?></td>
                            <td><?= h($transactions->purpose) ?></td>
                            <td><?= h($transactions->investment_amount) ?></td>
                            <td><?= h($transactions->investment_amount_eur) ?></td>
                            <td><?= h($transactions->investment_amount_curr) ?></td>
                            <td><?= h($transactions->working_capital) ?></td>
                            <td><?= h($transactions->working_capital_eur) ?></td>
                            <td><?= h($transactions->working_capital_curr) ?></td>
                            <td><?= h($transactions->principal_amount) ?></td>
                            <td><?= h($transactions->principal_amount_eur) ?></td>
                            <td><?= h($transactions->principal_amount_curr) ?></td>
                            <td><?= h($transactions->purchase_price) ?></td>
                            <td><?= h($transactions->purchase_price_eur) ?></td>
                            <td><?= h($transactions->purchase_price_curr) ?></td>
                            <td><?= h($transactions->down_payment) ?></td>
                            <td><?= h($transactions->down_payment_eur) ?></td>
                            <td><?= h($transactions->down_payment_curr) ?></td>
                            <td><?= h($transactions->baloon_amount) ?></td>
                            <td><?= h($transactions->baloon_amount_eur) ?></td>
                            <td><?= h($transactions->baloon_amount_curr) ?></td>
                            <td><?= h($transactions->maturity) ?></td>
                            <td><?= h($transactions->additional_maturity) ?></td>
                            <td><?= h($transactions->grace_period) ?></td>
                            <td><?= h($transactions->final_maturity_date) ?></td>
                            <td><?= h($transactions->signature_date) ?></td>
                            <td><?= h($transactions->first_disbursement_date) ?></td>
                            <td><?= h($transactions->first_instalment_date) ?></td>
                            <td><?= h($transactions->repayment_frequency) ?></td>
                            <td><?= h($transactions->collateralisation_rate) ?></td>
                            <td><?= h($transactions->standard_rate) ?></td>
                            <td><?= h($transactions->reference_rate) ?></td>
                            <td><?= h($transactions->interest_rate_date) ?></td>
                            <td><?= h($transactions->interest_rate) ?></td>
                            <td><?= h($transactions->interest_rate_txt) ?></td>
                            <td><?= h($transactions->interest_rate_type) ?></td>
                            <td><?= h($transactions->rsi_guarantee_fee_rate) ?></td>
                            <td><?= h($transactions->lgd) ?></td>
                            <td><?= h($transactions->total_project_cost) ?></td>
                            <td><?= h($transactions->total_project_cost_eur) ?></td>
                            <td><?= h($transactions->total_project_cost_curr) ?></td>
                            <td><?= h($transactions->allocation_amount) ?></td>
                            <td><?= h($transactions->allocation_amount_eur) ?></td>
                            <td><?= h($transactions->allocation_amount_curr) ?></td>
                            <td><?= h($transactions->project_description) ?></td>
                            <td><?= h($transactions->on_lending_bank) ?></td>
                            <td><?= h($transactions->olb_address) ?></td>
                            <td><?= h($transactions->olb_postal_code) ?></td>
                            <td><?= h($transactions->olb_place) ?></td>
                            <td><?= h($transactions->pass_through_institution) ?></td>
                            <td><?= h($transactions->acc_flag) ?></td>
                            <td><?= h($transactions->acc_date) ?></td>
                            <td><?= h($transactions->acc_type) ?></td>
                            <td><?= h($transactions->ori_principal_amount) ?></td>
                            <td><?= h($transactions->ori_principal_amount_eur) ?></td>
                            <td><?= h($transactions->ori_principal_amount_curr) ?></td>
                            <td><?= h($transactions->partial_exclusion) ?></td>
                            <td><?= h($transactions->amortisation_profile) ?></td>
                            <td><?= h($transactions->investment_location) ?></td>
                            <td><?= h($transactions->investment_location_lau) ?></td>
                            <td><?= h($transactions->territory_type) ?></td>
                            <td><?= h($transactions->gge_amount) ?></td>
                            <td><?= h($transactions->gge_amount_eur) ?></td>
                            <td><?= h($transactions->gge_amount_curr) ?></td>
                            <td><?= h($transactions->gge_change) ?></td>
                            <td><?= h($transactions->gge_modification_date) ?></td>
                            <td><?= h($transactions->gge_additional) ?></td>
                            <td><?= h($transactions->gge_additional_eur) ?></td>
                            <td><?= h($transactions->gge_additional_curr) ?></td>
                            <td><?= h($transactions->gge_calc_method) ?></td>
                            <td><?= h($transactions->sme_history_at_trn) ?></td>
                            <td><?= h($transactions->sme_history_at_report) ?></td>
                            <td><?= h($transactions->transaction_comments) ?></td>
                            <td><?= h($transactions->error_message) ?></td>
                            <td><?= h($transactions->sme_id) ?></td>
                            <td><?= h($transactions->portfolio_id) ?></td>
                            <td><?= h($transactions->report_id) ?></td>
                            <td><?= h($transactions->loan_type) ?></td>
                            <td><?= h($transactions->invest_nace_bg) ?></td>
                            <td><?= h($transactions->waiver) ?></td>
                            <td><?= h($transactions->waiver_reason) ?></td>
                            <td><?= h($transactions->waiver_details) ?></td>
                            <td><?= h($transactions->applied_guarantee_rate) ?></td>
                            <td><?= h($transactions->applied_cap_rate) ?></td>
                            <td><?= h($transactions->thematic_category) ?></td>
                            <td><?= h($transactions->transaction_status) ?></td>
                            <td><?= h($transactions->trn_exclusion_flag) ?></td>
                            <td><?= h($transactions->early_termination) ?></td>
                            <td><?= h($transactions->sme_turnover) ?></td>
                            <td><?= h($transactions->sme_assets) ?></td>
                            <td><?= h($transactions->sme_target_beneficiary) ?></td>
                            <td><?= h($transactions->sme_nbr_employees) ?></td>
                            <td><?= h($transactions->sme_sector) ?></td>
                            <td><?= h($transactions->sme_rating) ?></td>
                            <td><?= h($transactions->sme_current_rating) ?></td>
                            <td><?= h($transactions->sme_borrower_type) ?></td>
                            <td><?= h($transactions->sme_eligibility_criteria) ?></td>
                            <td><?= h($transactions->sme_level_digitalization) ?></td>
                            <td><?= h($transactions->tangible_assets) ?></td>
                            <td><?= h($transactions->tangible_assets_eur) ?></td>
                            <td><?= h($transactions->tangible_assets_curr) ?></td>
                            <td><?= h($transactions->intangible_assets) ?></td>
                            <td><?= h($transactions->intangible_assets_eur) ?></td>
                            <td><?= h($transactions->intangible_assets_curr) ?></td>
                            <td><?= h($transactions->collateral_amount) ?></td>
                            <td><?= h($transactions->collateral_amount_eur) ?></td>
                            <td><?= h($transactions->collateral_amount_curr) ?></td>
                            <td><?= h($transactions->collateral_type) ?></td>
                            <td><?= h($transactions->eu_program) ?></td>
                            <td><?= h($transactions->EFSI_trn) ?></td>
                            <td><?= h($transactions->retroactivity_flag) ?></td>
                            <td><?= h($transactions->sme_fi_rating_scale) ?></td>
                            <td><?= h($transactions->sme_current_fi_rating_scale) ?></td>
                            <td><?= h($transactions->publication) ?></td>
                            <td><?= h($transactions->fi_risk_sharing_rate) ?></td>
                            <td><?= h($transactions->fi_signature_date) ?></td>
                            <td><?= h($transactions->eco_innovation) ?></td>
                            <td><?= h($transactions->converted_reference) ?></td>
                            <td><?= h($transactions->conversion_date) ?></td>
                            <td><?= h($transactions->product_type) ?></td>
                            <td><?= h($transactions->recovery_rate) ?></td>
                            <td><?= h($transactions->interest_reduction) ?></td>
                            <td><?= h($transactions->reperforming_start) ?></td>
                            <td><?= h($transactions->reperforming_end) ?></td>
                            <td><?= h($transactions->reperforming) ?></td>
                            <td><?= h($transactions->fi) ?></td>
                            <td><?= h($transactions->periodic_fee) ?></td>
                            <td><?= h($transactions->permitted_add_inter_freq) ?></td>
                            <td><?= h($transactions->permitted_add_interest) ?></td>
                            <td><?= h($transactions->sampled) ?></td>
                            <td><?= h($transactions->sampling_date) ?></td>
                            <td><?= h($transactions->fi_review_sme_status) ?></td>
                            <td><?= h($transactions->fi_review_refinancing) ?></td>
                            <td><?= h($transactions->fi_review_purpose) ?></td>
                            <td><?= h($transactions->fi_review_sector) ?></td>
                            <td><?= h($transactions->linked_trn) ?></td>
                            <td><?= h($transactions->stand_alone_loan) ?></td>
                            <td><?= h($transactions->operation_type) ?></td>
                            <td><?= h($transactions->priority_theme) ?></td>
                            <td><?= h($transactions->state_aid_benefit) ?></td>
                            <td><?= h($transactions->cn_code) ?></td>
                            <td><?= h($transactions->renewal_generation) ?></td>
                            <td><?= h($transactions->thematic_focus) ?></td>
                            <td><?= h($transactions->agricultural_branch) ?></td>
                            <td><?= h($transactions->agg_co_lenders_financing) ?></td>
                            <td><?= h($transactions->agg_co_lenders_financing_eur) ?></td>
                            <td><?= h($transactions->agg_co_lenders_financing_curr) ?></td>
                            <td><?= h($transactions->nb_co_lenders) ?></td>
                            <td><?= h($transactions->commitment_fee) ?></td>
                            <td><?= h($transactions->ori_issue_discount) ?></td>
                            <td><?= h($transactions->prepayment_penalty) ?></td>
                            <td><?= h($transactions->upfront_fee) ?></td>
                            <td><?= h($transactions->pik_interest_rate) ?></td>
                            <td><?= h($transactions->pik_frequency) ?></td>
                            <td><?= h($transactions->equity_kicker) ?></td>
                            <td><?= h($transactions->residual_value) ?></td>
                            <td><?= h($transactions->residual_value_eur) ?></td>
                            <td><?= h($transactions->residual_value_curr) ?></td>
                            <td><?= h($transactions->third_party_guarantor) ?></td>
                            <td><?= h($transactions->guaranteed_percentage) ?></td>
                            <td><?= h($transactions->primary_investment) ?></td>
                            <td><?= h($transactions->senior_debt) ?></td>
                            <td><?= h($transactions->non_distressed_instrument) ?></td>
                            <td><?= h($transactions->exclusion_flag) ?></td>
                            <td><?= h($transactions->exclusion_reason) ?></td>
                            <td><?= h($transactions->exclusion_comment) ?></td>
                            <td><?= h($transactions->youth_employment_loan) ?></td>
                            <td><?= h($transactions->eligible_investment_skills) ?></td>
                            <td><?= h($transactions->field_study) ?></td>
                            <td><?= h($transactions->level_edu_programme) ?></td>
                            <td><?= h($transactions->country_study) ?></td>
                            <td><?= h($transactions->study_duration) ?></td>
                            <td><?= h($transactions->periodic_fee_rate) ?></td>
                            <td><?= h($transactions->fee_int_rate_period) ?></td>
                            <td><?= h($transactions->one_off_fee) ?></td>
                            <td><?= h($transactions->covid19_moratorium) ?></td>
                            <td><?= h($transactions->created) ?></td>
                            <td><?= h($transactions->modified) ?></td>
                            <td><?= h($transactions->pkid) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Transactions', 'action' => 'view', $transactions->transaction_id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Transactions', 'action' => 'edit', $transactions->transaction_id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Transactions', 'action' => 'delete', $transactions->transaction_id], ['confirm' => __('Are you sure you want to delete # {0}?', $transactions->transaction_id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Bonds') ?></h4>
                <?php if (!empty($bond->bonds)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Bond Id') ?></th>
                            <th><?= __('ISIN') ?></th>
                            <th><?= __('State') ?></th>
                            <th><?= __('Currency') ?></th>
                            <th><?= __('Issuer') ?></th>
                            <th><?= __('Issue Date') ?></th>
                            <th><?= __('First Coupon Accrual Date') ?></th>
                            <th><?= __('First Coupon Payment Date') ?></th>
                            <th><?= __('Maturity Date') ?></th>
                            <th><?= __('Coupon Rate') ?></th>
                            <th><?= __('Coupon Frequency') ?></th>
                            <th><?= __('Date Basis') ?></th>
                            <th><?= __('Date Convention') ?></th>
                            <th><?= __('Tax Rate') ?></th>
                            <th><?= __('Country') ?></th>
                            <th><?= __('Issue Size') ?></th>
                            <th><?= __('Covered') ?></th>
                            <th><?= __('Secured') ?></th>
                            <th><?= __('Seniority') ?></th>
                            <th><?= __('Guarantor') ?></th>
                            <th><?= __('Structured') ?></th>
                            <th><?= __('Issuer Type') ?></th>
                            <th><?= __('Issue Rating STP') ?></th>
                            <th><?= __('Issue Rating MDY') ?></th>
                            <th><?= __('Issue Rating FIT') ?></th>
                            <th><?= __('Retained Rating') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($bond->bonds as $bonds) : ?>
                        <tr>
                            <td><?= h($bonds->bond_id) ?></td>
                            <td><?= h($bonds->ISIN) ?></td>
                            <td><?= h($bonds->state) ?></td>
                            <td><?= h($bonds->currency) ?></td>
                            <td><?= h($bonds->issuer) ?></td>
                            <td><?= h($bonds->issue_date) ?></td>
                            <td><?= h($bonds->first_coupon_accrual_date) ?></td>
                            <td><?= h($bonds->first_coupon_payment_date) ?></td>
                            <td><?= h($bonds->maturity_date) ?></td>
                            <td><?= h($bonds->coupon_rate) ?></td>
                            <td><?= h($bonds->coupon_frequency) ?></td>
                            <td><?= h($bonds->date_basis) ?></td>
                            <td><?= h($bonds->date_convention) ?></td>
                            <td><?= h($bonds->tax_rate) ?></td>
                            <td><?= h($bonds->country) ?></td>
                            <td><?= h($bonds->issue_size) ?></td>
                            <td><?= h($bonds->covered) ?></td>
                            <td><?= h($bonds->secured) ?></td>
                            <td><?= h($bonds->seniority) ?></td>
                            <td><?= h($bonds->guarantor) ?></td>
                            <td><?= h($bonds->structured) ?></td>
                            <td><?= h($bonds->issuer_type) ?></td>
                            <td><?= h($bonds->issue_rating_STP) ?></td>
                            <td><?= h($bonds->issue_rating_MDY) ?></td>
                            <td><?= h($bonds->issue_rating_FIT) ?></td>
                            <td><?= h($bonds->retained_rating) ?></td>
                            <td><?= h($bonds->created) ?></td>
                            <td><?= h($bonds->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Bonds', 'action' => 'view', $bonds->bond_id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Bonds', 'action' => 'edit', $bonds->bond_id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Bonds', 'action' => 'delete', $bonds->bond_id], ['confirm' => __('Are you sure you want to delete # {0}?', $bonds->bond_id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
