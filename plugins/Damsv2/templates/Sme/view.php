<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Sme $sme
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Sme'), ['action' => 'edit', $sme->sme_id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Sme'), ['action' => 'delete', $sme->sme_id], ['confirm' => __('Are you sure you want to delete # {0}?', $sme->sme_id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Sme'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Sme'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="sme view content">
            <h3><?= h($sme->name) ?></h3>
            <table class="table table-striped">
                <tr>
                    <th><?= __('Fiscal Number') ?></th>
                    <td><?= h($sme->fiscal_number) ?></td>
                </tr>
                <tr>
                    <th><?= __('Siret') ?></th>
                    <td><?= h($sme->siret) ?></td>
                </tr>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($sme->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Surname') ?></th>
                    <td><?= h($sme->surname) ?></td>
                </tr>
                <tr>
                    <th><?= __('First Name') ?></th>
                    <td><?= h($sme->first_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Phone') ?></th>
                    <td><?= h($sme->phone) ?></td>
                </tr>
                <tr>
                    <th><?= __('Address') ?></th>
                    <td><?= h($sme->address) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($sme->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Gender') ?></th>
                    <td><?= h($sme->gender) ?></td>
                </tr>
                <tr>
                    <th><?= __('Postal Code') ?></th>
                    <td><?= h($sme->postal_code) ?></td>
                </tr>
                <tr>
                    <th><?= __('Place') ?></th>
                    <td><?= h($sme->place) ?></td>
                </tr>
                <tr>
                    <th><?= __('Region') ?></th>
                    <td><?= h($sme->region) ?></td>
                </tr>
                <tr>
                    <th><?= __('Region Lau') ?></th>
                    <td><?= h($sme->region_lau) ?></td>
                </tr>
                <tr>
                    <th><?= __('Country') ?></th>
                    <td><?= h($sme->country) ?></td>
                </tr>
                <tr>
                    <th><?= __('Country Main Operations') ?></th>
                    <td><?= h($sme->country_main_operations) ?></td>
                </tr>
                <tr>
                    <th><?= __('Nationality') ?></th>
                    <td><?= h($sme->nationality) ?></td>
                </tr>
                <tr>
                    <th><?= __('Degree M') ?></th>
                    <td><?= h($sme->degree_m) ?></td>
                </tr>
                <tr>
                    <th><?= __('Degree F') ?></th>
                    <td><?= h($sme->degree_f) ?></td>
                </tr>
                <tr>
                    <th><?= __('University') ?></th>
                    <td><?= h($sme->university) ?></td>
                </tr>
                <tr>
                    <th><?= __('Country Study') ?></th>
                    <td><?= h($sme->country_study) ?></td>
                </tr>
                <tr>
                    <th><?= __('Country Edu') ?></th>
                    <td><?= h($sme->country_edu) ?></td>
                </tr>
                <tr>
                    <th><?= __('Small Farm') ?></th>
                    <td><?= h($sme->small_farm) ?></td>
                </tr>
                <tr>
                    <th><?= __('Young Farmer') ?></th>
                    <td><?= h($sme->young_farmer) ?></td>
                </tr>
                <tr>
                    <th><?= __('Mountain Area') ?></th>
                    <td><?= h($sme->mountain_area) ?></td>
                </tr>
                <tr>
                    <th><?= __('Land Size') ?></th>
                    <td><?= h($sme->land_size) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sector') ?></th>
                    <td><?= h($sme->sector) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sector Lpa') ?></th>
                    <td><?= h($sme->sector_lpa) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sme Rating') ?></th>
                    <td><?= h($sme->sme_rating) ?></td>
                </tr>
                <tr>
                    <th><?= __('Startup') ?></th>
                    <td><?= h($sme->startup) ?></td>
                </tr>
                <tr>
                    <th><?= __('Innovative') ?></th>
                    <td><?= h($sme->innovative) ?></td>
                </tr>
                <tr>
                    <th><?= __('Waiver') ?></th>
                    <td><?= h($sme->waiver) ?></td>
                </tr>
                <tr>
                    <th><?= __('Waiver Reason') ?></th>
                    <td><?= h($sme->waiver_reason) ?></td>
                </tr>
                <tr>
                    <th><?= __('Eligible Beneficiary') ?></th>
                    <td><?= h($sme->eligible_beneficiary) ?></td>
                </tr>
                <tr>
                    <th><?= __('Eligible Beneficiary Type') ?></th>
                    <td><?= h($sme->eligible_beneficiary_type) ?></td>
                </tr>
                <tr>
                    <th><?= __('Target Beneficiary') ?></th>
                    <td><?= h($sme->target_beneficiary) ?></td>
                </tr>
                <tr>
                    <th><?= __('Borrower Type') ?></th>
                    <td><?= h($sme->borrower_type) ?></td>
                </tr>
                <tr>
                    <th><?= __('Micro Borrowers') ?></th>
                    <td><?= h($sme->micro_borrowers) ?></td>
                </tr>
                <tr>
                    <th><?= __('Eligibility Criteria') ?></th>
                    <td><?= h($sme->eligibility_criteria) ?></td>
                </tr>
                <tr>
                    <th><?= __('Level Digitalization') ?></th>
                    <td><?= h($sme->level_digitalization) ?></td>
                </tr>
                <tr>
                    <th><?= __('Thematic Criteria') ?></th>
                    <td><?= h($sme->thematic_criteria) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sme Comments') ?></th>
                    <td><?= h($sme->sme_comments) ?></td>
                </tr>
                <tr>
                    <th><?= __('Error Message') ?></th>
                    <td><?= h($sme->error_message) ?></td>
                </tr>
                <tr>
                    <th><?= __('Category') ?></th>
                    <td><?= h($sme->category) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fr Category') ?></th>
                    <td><?= h($sme->fr_category) ?></td>
                </tr>
                <tr>
                    <th><?= __('Legal Form') ?></th>
                    <td><?= h($sme->legal_form) ?></td>
                </tr>
                <tr>
                    <th><?= __('Employment Status') ?></th>
                    <td><?= h($sme->employment_status) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fi Rating Scale') ?></th>
                    <td><?= h($sme->fi_rating_scale) ?></td>
                </tr>
                <tr>
                    <th><?= __('Share Contacts') ?></th>
                    <td><?= h($sme->share_contacts) ?></td>
                </tr>
                <tr>
                    <th><?= __('Natural Person') ?></th>
                    <td><?= h($sme->natural_person) ?></td>
                </tr>
                <tr>
                    <th><?= __('Natural Person Calc') ?></th>
                    <td><?= h($sme->natural_person_calc) ?></td>
                </tr>
                <tr>
                    <th><?= __('Website') ?></th>
                    <td><?= h($sme->website) ?></td>
                </tr>
                <tr>
                    <th><?= __('Social Enterprise') ?></th>
                    <td><?= h($sme->social_enterprise) ?></td>
                </tr>
                <tr>
                    <th><?= __('Social Sector Org') ?></th>
                    <td><?= h($sme->social_sector_org) ?></td>
                </tr>
                <tr>
                    <th><?= __('Holding Company') ?></th>
                    <td><?= h($sme->holding_company) ?></td>
                </tr>
                <tr>
                    <th><?= __('Part Of Group') ?></th>
                    <td><?= h($sme->part_of_group) ?></td>
                </tr>
                <tr>
                    <th><?= __('Bds Paid') ?></th>
                    <td><?= h($sme->bds_paid) ?></td>
                </tr>
                <tr>
                    <th><?= __('Youth Participant') ?></th>
                    <td><?= h($sme->youth_participant) ?></td>
                </tr>
                <tr>
                    <th><?= __('Name Ori Alphabet') ?></th>
                    <td><?= h($sme->name_ori_alphabet) ?></td>
                </tr>
                <tr>
                    <th><?= __('Address Ori Alphabet') ?></th>
                    <td><?= h($sme->address_ori_alphabet) ?></td>
                </tr>
                <tr>
                    <th><?= __('Place Ori Alphabet') ?></th>
                    <td><?= h($sme->place_ori_alphabet) ?></td>
                </tr>
                <tr>
                    <th><?= __('Pkid') ?></th>
                    <td><?= h($sme->pkid) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sme Id') ?></th>
                    <td><?= $this->Number->format($sme->sme_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Study Field') ?></th>
                    <td><?= $this->Number->format($sme->study_field) ?></td>
                </tr>
                <tr>
                    <th><?= __('Study Duration') ?></th>
                    <td><?= $this->Number->format($sme->study_duration) ?></td>
                </tr>
                <tr>
                    <th><?= __('Nbr Employees') ?></th>
                    <td><?= $this->Number->format($sme->nbr_employees) ?></td>
                </tr>
                <tr>
                    <th><?= __('Turnover') ?></th>
                    <td><?= $this->Number->format($sme->turnover) ?></td>
                </tr>
                <tr>
                    <th><?= __('Assets') ?></th>
                    <td><?= $this->Number->format($sme->assets) ?></td>
                </tr>
                <tr>
                    <th><?= __('Ebitda') ?></th>
                    <td><?= $this->Number->format($sme->ebitda) ?></td>
                </tr>
                <tr>
                    <th><?= __('Net Debt To Ebitda') ?></th>
                    <td><?= $this->Number->format($sme->net_debt_to_ebitda) ?></td>
                </tr>
                <tr>
                    <th><?= __('Report Id') ?></th>
                    <td><?= $this->Number->format($sme->report_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Total Loan Amount Curr') ?></th>
                    <td><?= $this->Number->format($sme->total_loan_amount_curr) ?></td>
                </tr>
                <tr>
                    <th><?= __('Total Loan Amount Eur') ?></th>
                    <td><?= $this->Number->format($sme->total_loan_amount_eur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Portfolio Id') ?></th>
                    <td><?= $this->Number->format($sme->portfolio_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Nbr Young Employed') ?></th>
                    <td><?= $this->Number->format($sme->nbr_young_employed) ?></td>
                </tr>
                <tr>
                    <th><?= __('Nbr Young Training') ?></th>
                    <td><?= $this->Number->format($sme->nbr_young_training) ?></td>
                </tr>
                <tr>
                    <th><?= __('Personnel Cost') ?></th>
                    <td><?= $this->Number->format($sme->personnel_cost) ?></td>
                </tr>
                <tr>
                    <th><?= __('Labor Market Status') ?></th>
                    <td><?= $this->Number->format($sme->labor_market_status) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sme Id Ori') ?></th>
                    <td><?= $this->Number->format($sme->sme_id_ori) ?></td>
                </tr>
                <tr>
                    <th><?= __('Establishment Date') ?></th>
                    <td><?= h($sme->establishment_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($sme->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($sme->modified) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Portfolio') ?></h4>
                <?php if (!empty($sme->portfolio)) : ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th><?= __('Portfolio Id') ?></th>
                            <th><?= __('Deal Name') ?></th>
                            <th><?= __('Deal Business Key') ?></th>
                            <th><?= __('Iqid') ?></th>
                            <th><?= __('Mandate') ?></th>
                            <th><?= __('Portfolio Name') ?></th>
                            <th><?= __('Beneficiary Iqid') ?></th>
                            <th><?= __('Beneficiary Name') ?></th>
                            <th><?= __('Maxpv') ?></th>
                            <th><?= __('Agreed Pv') ?></th>
                            <th><?= __('Agreed Ga') ?></th>
                            <th><?= __('Agreed Pv Rate') ?></th>
                            <th><?= __('Actual Pev') ?></th>
                            <th><?= __('Minpv') ?></th>
                            <th><?= __('Reference Volume') ?></th>
                            <th><?= __('Currency') ?></th>
                            <th><?= __('Fx Rate Inclusion') ?></th>
                            <th><?= __('Fx Rate Pdlr') ?></th>
                            <th><?= __('Guarantee Amount') ?></th>
                            <th><?= __('Signed Amount') ?></th>
                            <th><?= __('Cap Amount') ?></th>
                            <th><?= __('Effective Cap Amount') ?></th>
                            <th><?= __('Available Cap Amount') ?></th>
                            <th><?= __('Signature Date') ?></th>
                            <th><?= __('Availability Start') ?></th>
                            <th><?= __('Availability End') ?></th>
                            <th><?= __('End Reporting Date') ?></th>
                            <th><?= __('Guarantee Termination') ?></th>
                            <th><?= __('Recovery Rate') ?></th>
                            <th><?= __('Call Time To Pay') ?></th>
                            <th><?= __('Call Time Unit') ?></th>
                            <th><?= __('Loss Rate Trigger') ?></th>
                            <th><?= __('Actual Pv') ?></th>
                            <th><?= __('Apv At Closure') ?></th>
                            <th><?= __('Actual Gv') ?></th>
                            <th><?= __('Default Amount') ?></th>
                            <th><?= __('Country') ?></th>
                            <th><?= __('Product Id') ?></th>
                            <th><?= __('Status Portfolio') ?></th>
                            <th><?= __('Closure Date') ?></th>
                            <th><?= __('Gs Deal Status') ?></th>
                            <th><?= __('Owner') ?></th>
                            <th><?= __('Max Trn Maturity') ?></th>
                            <th><?= __('Interest Risk Sharing Rate') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Pd Final Payment Date') ?></th>
                            <th><?= __('Pd Final Payment Notice') ?></th>
                            <th><?= __('Pd Decl') ?></th>
                            <th><?= __('In Inclusion Final Date') ?></th>
                            <th><?= __('In Decl') ?></th>
                            <th><?= __('Capped') ?></th>
                            <th><?= __('Management Fee Rate') ?></th>
                            <th><?= __('Cofinancing Rate') ?></th>
                            <th><?= __('Risk Sharing Rate') ?></th>
                            <th><?= __('Guarantee Type') ?></th>
                            <th><?= __('Effective Termination Date') ?></th>
                            <th><?= __('Inclusion Start Date') ?></th>
                            <th><?= __('Inclusion End Date') ?></th>
                            <th><?= __('Modifications Expected') ?></th>
                            <th><?= __('M Files Link') ?></th>
                            <th><?= __('Kyc Embargo') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($sme->portfolio as $portfolio) : ?>
                        <tr>
                            <td><?= h($portfolio->portfolio_id) ?></td>
                            <td><?= h($portfolio->deal_name) ?></td>
                            <td><?= h($portfolio->deal_business_key) ?></td>
                            <td><?= h($portfolio->iqid) ?></td>
                            <td><?= h($portfolio->mandate) ?></td>
                            <td><?= h($portfolio->portfolio_name) ?></td>
                            <td><?= h($portfolio->beneficiary_iqid) ?></td>
                            <td><?= h($portfolio->beneficiary_name) ?></td>
                            <td><?= h($portfolio->maxpv) ?></td>
                            <td><?= h($portfolio->agreed_pv) ?></td>
                            <td><?= h($portfolio->agreed_ga) ?></td>
                            <td><?= h($portfolio->agreed_pv_rate) ?></td>
                            <td><?= h($portfolio->actual_pev) ?></td>
                            <td><?= h($portfolio->minpv) ?></td>
                            <td><?= h($portfolio->reference_volume) ?></td>
                            <td><?= h($portfolio->currency) ?></td>
                            <td><?= h($portfolio->fx_rate_inclusion) ?></td>
                            <td><?= h($portfolio->fx_rate_pdlr) ?></td>
                            <td><?= h($portfolio->guarantee_amount) ?></td>
                            <td><?= h($portfolio->signed_amount) ?></td>
                            <td><?= h($portfolio->cap_amount) ?></td>
                            <td><?= h($portfolio->effective_cap_amount) ?></td>
                            <td><?= h($portfolio->available_cap_amount) ?></td>
                            <td><?= h($portfolio->signature_date) ?></td>
                            <td><?= h($portfolio->availability_start) ?></td>
                            <td><?= h($portfolio->availability_end) ?></td>
                            <td><?= h($portfolio->end_reporting_date) ?></td>
                            <td><?= h($portfolio->guarantee_termination) ?></td>
                            <td><?= h($portfolio->recovery_rate) ?></td>
                            <td><?= h($portfolio->call_time_to_pay) ?></td>
                            <td><?= h($portfolio->call_time_unit) ?></td>
                            <td><?= h($portfolio->loss_rate_trigger) ?></td>
                            <td><?= h($portfolio->actual_pv) ?></td>
                            <td><?= h($portfolio->apv_at_closure) ?></td>
                            <td><?= h($portfolio->actual_gv) ?></td>
                            <td><?= h($portfolio->default_amount) ?></td>
                            <td><?= h($portfolio->country) ?></td>
                            <td><?= h($portfolio->product_id) ?></td>
                            <td><?= h($portfolio->status_portfolio) ?></td>
                            <td><?= h($portfolio->closure_date) ?></td>
                            <td><?= h($portfolio->gs_deal_status) ?></td>
                            <td><?= h($portfolio->owner) ?></td>
                            <td><?= h($portfolio->max_trn_maturity) ?></td>
                            <td><?= h($portfolio->interest_risk_sharing_rate) ?></td>
                            <td><?= h($portfolio->created) ?></td>
                            <td><?= h($portfolio->modified) ?></td>
                            <td><?= h($portfolio->pd_final_payment_date) ?></td>
                            <td><?= h($portfolio->pd_final_payment_notice) ?></td>
                            <td><?= h($portfolio->pd_decl) ?></td>
                            <td><?= h($portfolio->in_inclusion_final_date) ?></td>
                            <td><?= h($portfolio->in_decl) ?></td>
                            <td><?= h($portfolio->capped) ?></td>
                            <td><?= h($portfolio->management_fee_rate) ?></td>
                            <td><?= h($portfolio->cofinancing_rate) ?></td>
                            <td><?= h($portfolio->risk_sharing_rate) ?></td>
                            <td><?= h($portfolio->guarantee_type) ?></td>
                            <td><?= h($portfolio->effective_termination_date) ?></td>
                            <td><?= h($portfolio->inclusion_start_date) ?></td>
                            <td><?= h($portfolio->inclusion_end_date) ?></td>
                            <td><?= h($portfolio->modifications_expected) ?></td>
                            <td><?= h($portfolio->m_files_link) ?></td>
                            <td><?= h($portfolio->kyc_embargo) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Portfolio', 'action' => 'view', $portfolio->portfolio_id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Portfolio', 'action' => 'edit', $portfolio->portfolio_id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Portfolio', 'action' => 'delete', $portfolio->portfolio_id], ['confirm' => __('Are you sure you want to delete # {0}?', $portfolio->portfolio_id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Portfolio Log History') ?></h4>
                <?php if (!empty($sme->portfolio_log_history)) : ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th><?= __('Log Id') ?></th>
                            <th><?= __('Portfolio Id') ?></th>
                            <th><?= __('Deal Name') ?></th>
                            <th><?= __('Deal Business Key') ?></th>
                            <th><?= __('Iqid') ?></th>
                            <th><?= __('Mandate') ?></th>
                            <th><?= __('Portfolio Name') ?></th>
                            <th><?= __('Beneficiary Iqid') ?></th>
                            <th><?= __('Beneficiary Name') ?></th>
                            <th><?= __('Maxpv') ?></th>
                            <th><?= __('Agreed Pv') ?></th>
                            <th><?= __('Agreed Ga') ?></th>
                            <th><?= __('Agreed Pv Rate') ?></th>
                            <th><?= __('Actual Pev') ?></th>
                            <th><?= __('Minpv') ?></th>
                            <th><?= __('Reference Volume') ?></th>
                            <th><?= __('Currency') ?></th>
                            <th><?= __('Fx Rate Inclusion') ?></th>
                            <th><?= __('Fx Rate Pdlr') ?></th>
                            <th><?= __('Guarantee Amount') ?></th>
                            <th><?= __('Signed Amount') ?></th>
                            <th><?= __('Cap Amount') ?></th>
                            <th><?= __('Effective Cap Amount') ?></th>
                            <th><?= __('Available Cap Amount') ?></th>
                            <th><?= __('Signature Date') ?></th>
                            <th><?= __('Availability Start') ?></th>
                            <th><?= __('Availability End') ?></th>
                            <th><?= __('End Reporting Date') ?></th>
                            <th><?= __('Guarantee Termination') ?></th>
                            <th><?= __('Recovery Rate') ?></th>
                            <th><?= __('Call Time To Pay') ?></th>
                            <th><?= __('Call Time Unit') ?></th>
                            <th><?= __('Loss Rate Trigger') ?></th>
                            <th><?= __('Actual Pv') ?></th>
                            <th><?= __('Apv At Closure') ?></th>
                            <th><?= __('Actual Gv') ?></th>
                            <th><?= __('Default Amount') ?></th>
                            <th><?= __('Country') ?></th>
                            <th><?= __('Product Id') ?></th>
                            <th><?= __('Status Portfolio') ?></th>
                            <th><?= __('Closure Date') ?></th>
                            <th><?= __('Gs Deal Status') ?></th>
                            <th><?= __('Owner') ?></th>
                            <th><?= __('Max Trn Maturity') ?></th>
                            <th><?= __('Interest Risk Sharing Rate') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Pd Final Payment Date') ?></th>
                            <th><?= __('Pd Final Payment Notice') ?></th>
                            <th><?= __('Pd Decl') ?></th>
                            <th><?= __('In Inclusion Final Date') ?></th>
                            <th><?= __('In Decl') ?></th>
                            <th><?= __('Capped') ?></th>
                            <th><?= __('Management Fee Rate') ?></th>
                            <th><?= __('Cofinancing Rate') ?></th>
                            <th><?= __('Risk Sharing Rate') ?></th>
                            <th><?= __('Guarantee Type') ?></th>
                            <th><?= __('Effective Termination Date') ?></th>
                            <th><?= __('Inclusion Start Date') ?></th>
                            <th><?= __('Inclusion End Date') ?></th>
                            <th><?= __('Modifications Expected') ?></th>
                            <th><?= __('M Files Link') ?></th>
                            <th><?= __('Kyc Embargo') ?></th>
                            <th><?= __('User') ?></th>
                            <th><?= __('Action') ?></th>
                            <th><?= __('Datetime') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($sme->portfolio_log_history as $portfolioLogHistory) : ?>
                        <tr>
                            <td><?= h($portfolioLogHistory->log_id) ?></td>
                            <td><?= h($portfolioLogHistory->portfolio_id) ?></td>
                            <td><?= h($portfolioLogHistory->deal_name) ?></td>
                            <td><?= h($portfolioLogHistory->deal_business_key) ?></td>
                            <td><?= h($portfolioLogHistory->iqid) ?></td>
                            <td><?= h($portfolioLogHistory->mandate) ?></td>
                            <td><?= h($portfolioLogHistory->portfolio_name) ?></td>
                            <td><?= h($portfolioLogHistory->beneficiary_iqid) ?></td>
                            <td><?= h($portfolioLogHistory->beneficiary_name) ?></td>
                            <td><?= h($portfolioLogHistory->maxpv) ?></td>
                            <td><?= h($portfolioLogHistory->agreed_pv) ?></td>
                            <td><?= h($portfolioLogHistory->agreed_ga) ?></td>
                            <td><?= h($portfolioLogHistory->agreed_pv_rate) ?></td>
                            <td><?= h($portfolioLogHistory->actual_pev) ?></td>
                            <td><?= h($portfolioLogHistory->minpv) ?></td>
                            <td><?= h($portfolioLogHistory->reference_volume) ?></td>
                            <td><?= h($portfolioLogHistory->currency) ?></td>
                            <td><?= h($portfolioLogHistory->fx_rate_inclusion) ?></td>
                            <td><?= h($portfolioLogHistory->fx_rate_pdlr) ?></td>
                            <td><?= h($portfolioLogHistory->guarantee_amount) ?></td>
                            <td><?= h($portfolioLogHistory->signed_amount) ?></td>
                            <td><?= h($portfolioLogHistory->cap_amount) ?></td>
                            <td><?= h($portfolioLogHistory->effective_cap_amount) ?></td>
                            <td><?= h($portfolioLogHistory->available_cap_amount) ?></td>
                            <td><?= h($portfolioLogHistory->signature_date) ?></td>
                            <td><?= h($portfolioLogHistory->availability_start) ?></td>
                            <td><?= h($portfolioLogHistory->availability_end) ?></td>
                            <td><?= h($portfolioLogHistory->end_reporting_date) ?></td>
                            <td><?= h($portfolioLogHistory->guarantee_termination) ?></td>
                            <td><?= h($portfolioLogHistory->recovery_rate) ?></td>
                            <td><?= h($portfolioLogHistory->call_time_to_pay) ?></td>
                            <td><?= h($portfolioLogHistory->call_time_unit) ?></td>
                            <td><?= h($portfolioLogHistory->loss_rate_trigger) ?></td>
                            <td><?= h($portfolioLogHistory->actual_pv) ?></td>
                            <td><?= h($portfolioLogHistory->apv_at_closure) ?></td>
                            <td><?= h($portfolioLogHistory->actual_gv) ?></td>
                            <td><?= h($portfolioLogHistory->default_amount) ?></td>
                            <td><?= h($portfolioLogHistory->country) ?></td>
                            <td><?= h($portfolioLogHistory->product_id) ?></td>
                            <td><?= h($portfolioLogHistory->status_portfolio) ?></td>
                            <td><?= h($portfolioLogHistory->closure_date) ?></td>
                            <td><?= h($portfolioLogHistory->gs_deal_status) ?></td>
                            <td><?= h($portfolioLogHistory->owner) ?></td>
                            <td><?= h($portfolioLogHistory->max_trn_maturity) ?></td>
                            <td><?= h($portfolioLogHistory->interest_risk_sharing_rate) ?></td>
                            <td><?= h($portfolioLogHistory->created) ?></td>
                            <td><?= h($portfolioLogHistory->modified) ?></td>
                            <td><?= h($portfolioLogHistory->pd_final_payment_date) ?></td>
                            <td><?= h($portfolioLogHistory->pd_final_payment_notice) ?></td>
                            <td><?= h($portfolioLogHistory->pd_decl) ?></td>
                            <td><?= h($portfolioLogHistory->in_inclusion_final_date) ?></td>
                            <td><?= h($portfolioLogHistory->in_decl) ?></td>
                            <td><?= h($portfolioLogHistory->capped) ?></td>
                            <td><?= h($portfolioLogHistory->management_fee_rate) ?></td>
                            <td><?= h($portfolioLogHistory->cofinancing_rate) ?></td>
                            <td><?= h($portfolioLogHistory->risk_sharing_rate) ?></td>
                            <td><?= h($portfolioLogHistory->guarantee_type) ?></td>
                            <td><?= h($portfolioLogHistory->effective_termination_date) ?></td>
                            <td><?= h($portfolioLogHistory->inclusion_start_date) ?></td>
                            <td><?= h($portfolioLogHistory->inclusion_end_date) ?></td>
                            <td><?= h($portfolioLogHistory->modifications_expected) ?></td>
                            <td><?= h($portfolioLogHistory->m_files_link) ?></td>
                            <td><?= h($portfolioLogHistory->kyc_embargo) ?></td>
                            <td><?= h($portfolioLogHistory->user) ?></td>
                            <td><?= h($portfolioLogHistory->action) ?></td>
                            <td><?= h($portfolioLogHistory->Datetime) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'PortfolioLogHistory', 'action' => 'view', $portfolioLogHistory->log_id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'PortfolioLogHistory', 'action' => 'edit', $portfolioLogHistory->log_id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'PortfolioLogHistory', 'action' => 'delete', $portfolioLogHistory->log_id], ['confirm' => __('Are you sure you want to delete # {0}?', $portfolioLogHistory->log_id)]) ?>
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
