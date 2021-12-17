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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $sme->sme_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $sme->sme_id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Sme'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="sme form content">
            <?= $this->Form->create($sme) ?>
            <fieldset>
                <legend><?= __('Edit Sme') ?></legend>
                <?php
                    echo $this->Form->control('fiscal_number');
                    echo $this->Form->control('siret');
                    echo $this->Form->control('name');
                    echo $this->Form->control('surname');
                    echo $this->Form->control('first_name');
                    echo $this->Form->control('phone');
                    echo $this->Form->control('address');
                    echo $this->Form->control('email');
                    echo $this->Form->control('gender');
                    echo $this->Form->control('postal_code');
                    echo $this->Form->control('place');
                    echo $this->Form->control('region');
                    echo $this->Form->control('region_lau');
                    echo $this->Form->control('country');
                    echo $this->Form->control('country_main_operations');
                    echo $this->Form->control('nationality');
                    echo $this->Form->control('degree_m');
                    echo $this->Form->control('degree_f');
                    echo $this->Form->control('study_field');
                    echo $this->Form->control('university');
                    echo $this->Form->control('study_duration');
                    echo $this->Form->control('country_study');
                    echo $this->Form->control('country_edu');
                    echo $this->Form->control('small_farm');
                    echo $this->Form->control('young_farmer');
                    echo $this->Form->control('mountain_area');
                    echo $this->Form->control('land_size');
                    echo $this->Form->control('establishment_date', ['empty' => true]);
                    echo $this->Form->control('sector');
                    echo $this->Form->control('sector_lpa');
                    echo $this->Form->control('nbr_employees');
                    echo $this->Form->control('sme_rating');
                    echo $this->Form->control('startup');
                    echo $this->Form->control('innovative');
                    echo $this->Form->control('waiver');
                    echo $this->Form->control('waiver_reason');
                    echo $this->Form->control('turnover');
                    echo $this->Form->control('assets');
                    echo $this->Form->control('ebitda');
                    echo $this->Form->control('net_debt_to_ebitda');
                    echo $this->Form->control('eligible_beneficiary');
                    echo $this->Form->control('eligible_beneficiary_type');
                    echo $this->Form->control('target_beneficiary');
                    echo $this->Form->control('borrower_type');
                    echo $this->Form->control('micro_borrowers');
                    echo $this->Form->control('eligibility_criteria');
                    echo $this->Form->control('level_digitalization');
                    echo $this->Form->control('thematic_criteria');
                    echo $this->Form->control('sme_comments');
                    echo $this->Form->control('error_message');
                    echo $this->Form->control('category');
                    echo $this->Form->control('fr_category');
                    echo $this->Form->control('report_id');
                    echo $this->Form->control('total_loan_amount_curr');
                    echo $this->Form->control('total_loan_amount_eur');
                    echo $this->Form->control('portfolio_id');
                    echo $this->Form->control('legal_form');
                    echo $this->Form->control('employment_status');
                    echo $this->Form->control('fi_rating_scale');
                    echo $this->Form->control('share_contacts');
                    echo $this->Form->control('natural_person');
                    echo $this->Form->control('natural_person_calc');
                    echo $this->Form->control('website');
                    echo $this->Form->control('social_enterprise');
                    echo $this->Form->control('social_sector_org');
                    echo $this->Form->control('holding_company');
                    echo $this->Form->control('part_of_group');
                    echo $this->Form->control('bds_paid');
                    echo $this->Form->control('nbr_young_employed');
                    echo $this->Form->control('nbr_young_training');
                    echo $this->Form->control('youth_participant');
                    echo $this->Form->control('personnel_cost');
                    echo $this->Form->control('labor_market_status');
                    echo $this->Form->control('name_ori_alphabet');
                    echo $this->Form->control('address_ori_alphabet');
                    echo $this->Form->control('place_ori_alphabet');
                    echo $this->Form->control('sme_id_ori');
                    echo $this->Form->control('pkid');
                    echo $this->Form->control('portfolio._ids', ['options' => $portfolio]);
                    echo $this->Form->control('portfolio_log_history._ids', ['options' => $portfolioLogHistory]);
                ?>
            </fieldset>
                        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-outline-secondary my-3']); ?>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
