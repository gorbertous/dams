<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Sme[]|\Cake\Collection\CollectionInterface $sme
 */
?>
<div class="sme index content">
    <?= $this->Html->link(__('New Sme'), ['action' => 'add'], ['class' => 'btn btn-primary float-right']) ?>
    <h3><?= __('Sme') ?></h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('sme_id') ?></th>
                    <th><?= $this->Paginator->sort('fiscal_number') ?></th>
                    <th><?= $this->Paginator->sort('siret') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('surname') ?></th>
                    <th><?= $this->Paginator->sort('first_name') ?></th>
                    <th><?= $this->Paginator->sort('phone') ?></th>
                    <th><?= $this->Paginator->sort('address') ?></th>
                    <th><?= $this->Paginator->sort('email') ?></th>
                    <th><?= $this->Paginator->sort('gender') ?></th>
                    <th><?= $this->Paginator->sort('postal_code') ?></th>
                    <th><?= $this->Paginator->sort('place') ?></th>
                    <th><?= $this->Paginator->sort('region') ?></th>
                    <th><?= $this->Paginator->sort('region_lau') ?></th>
                    <th><?= $this->Paginator->sort('country') ?></th>
                    <th><?= $this->Paginator->sort('country_main_operations') ?></th>
                    <th><?= $this->Paginator->sort('nationality') ?></th>
                    <th><?= $this->Paginator->sort('degree_m') ?></th>
                    <th><?= $this->Paginator->sort('degree_f') ?></th>
                    <th><?= $this->Paginator->sort('study_field') ?></th>
                    <th><?= $this->Paginator->sort('university') ?></th>
                    <th><?= $this->Paginator->sort('study_duration') ?></th>
                    <th><?= $this->Paginator->sort('country_study') ?></th>
                    <th><?= $this->Paginator->sort('country_edu') ?></th>
                    <th><?= $this->Paginator->sort('small_farm') ?></th>
                    <th><?= $this->Paginator->sort('young_farmer') ?></th>
                    <th><?= $this->Paginator->sort('mountain_area') ?></th>
                    <th><?= $this->Paginator->sort('land_size') ?></th>
                    <th><?= $this->Paginator->sort('establishment_date') ?></th>
                    <th><?= $this->Paginator->sort('sector') ?></th>
                    <th><?= $this->Paginator->sort('sector_lpa') ?></th>
                    <th><?= $this->Paginator->sort('nbr_employees') ?></th>
                    <th><?= $this->Paginator->sort('sme_rating') ?></th>
                    <th><?= $this->Paginator->sort('startup') ?></th>
                    <th><?= $this->Paginator->sort('innovative') ?></th>
                    <th><?= $this->Paginator->sort('waiver') ?></th>
                    <th><?= $this->Paginator->sort('waiver_reason') ?></th>
                    <th><?= $this->Paginator->sort('turnover') ?></th>
                    <th><?= $this->Paginator->sort('assets') ?></th>
                    <th><?= $this->Paginator->sort('ebitda') ?></th>
                    <th><?= $this->Paginator->sort('net_debt_to_ebitda') ?></th>
                    <th><?= $this->Paginator->sort('eligible_beneficiary') ?></th>
                    <th><?= $this->Paginator->sort('eligible_beneficiary_type') ?></th>
                    <th><?= $this->Paginator->sort('target_beneficiary') ?></th>
                    <th><?= $this->Paginator->sort('borrower_type') ?></th>
                    <th><?= $this->Paginator->sort('micro_borrowers') ?></th>
                    <th><?= $this->Paginator->sort('eligibility_criteria') ?></th>
                    <th><?= $this->Paginator->sort('level_digitalization') ?></th>
                    <th><?= $this->Paginator->sort('thematic_criteria') ?></th>
                    <th><?= $this->Paginator->sort('sme_comments') ?></th>
                    <th><?= $this->Paginator->sort('error_message') ?></th>
                    <th><?= $this->Paginator->sort('category') ?></th>
                    <th><?= $this->Paginator->sort('fr_category') ?></th>
                    <th><?= $this->Paginator->sort('report_id') ?></th>
                    <th><?= $this->Paginator->sort('total_loan_amount_curr') ?></th>
                    <th><?= $this->Paginator->sort('total_loan_amount_eur') ?></th>
                    <th><?= $this->Paginator->sort('portfolio_id') ?></th>
                    <th><?= $this->Paginator->sort('legal_form') ?></th>
                    <th><?= $this->Paginator->sort('employment_status') ?></th>
                    <th><?= $this->Paginator->sort('fi_rating_scale') ?></th>
                    <th><?= $this->Paginator->sort('share_contacts') ?></th>
                    <th><?= $this->Paginator->sort('natural_person') ?></th>
                    <th><?= $this->Paginator->sort('natural_person_calc') ?></th>
                    <th><?= $this->Paginator->sort('website') ?></th>
                    <th><?= $this->Paginator->sort('social_enterprise') ?></th>
                    <th><?= $this->Paginator->sort('social_sector_org') ?></th>
                    <th><?= $this->Paginator->sort('holding_company') ?></th>
                    <th><?= $this->Paginator->sort('part_of_group') ?></th>
                    <th><?= $this->Paginator->sort('bds_paid') ?></th>
                    <th><?= $this->Paginator->sort('nbr_young_employed') ?></th>
                    <th><?= $this->Paginator->sort('nbr_young_training') ?></th>
                    <th><?= $this->Paginator->sort('youth_participant') ?></th>
                    <th><?= $this->Paginator->sort('personnel_cost') ?></th>
                    <th><?= $this->Paginator->sort('labor_market_status') ?></th>
                    <th><?= $this->Paginator->sort('name_ori_alphabet') ?></th>
                    <th><?= $this->Paginator->sort('address_ori_alphabet') ?></th>
                    <th><?= $this->Paginator->sort('place_ori_alphabet') ?></th>
                    <th><?= $this->Paginator->sort('sme_id_ori') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th><?= $this->Paginator->sort('pkid') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sme as $sme): ?>
                <tr>
                    <td><?= $this->Number->format($sme->sme_id) ?></td>
                    <td><?= h($sme->fiscal_number) ?></td>
                    <td><?= h($sme->siret) ?></td>
                    <td><?= h($sme->name) ?></td>
                    <td><?= h($sme->surname) ?></td>
                    <td><?= h($sme->first_name) ?></td>
                    <td><?= h($sme->phone) ?></td>
                    <td><?= h($sme->address) ?></td>
                    <td><?= h($sme->email) ?></td>
                    <td><?= h($sme->gender) ?></td>
                    <td><?= h($sme->postal_code) ?></td>
                    <td><?= h($sme->place) ?></td>
                    <td><?= h($sme->region) ?></td>
                    <td><?= h($sme->region_lau) ?></td>
                    <td><?= h($sme->country) ?></td>
                    <td><?= h($sme->country_main_operations) ?></td>
                    <td><?= h($sme->nationality) ?></td>
                    <td><?= h($sme->degree_m) ?></td>
                    <td><?= h($sme->degree_f) ?></td>
                    <td><?= $this->Number->format($sme->study_field) ?></td>
                    <td><?= h($sme->university) ?></td>
                    <td><?= $this->Number->format($sme->study_duration) ?></td>
                    <td><?= h($sme->country_study) ?></td>
                    <td><?= h($sme->country_edu) ?></td>
                    <td><?= h($sme->small_farm) ?></td>
                    <td><?= h($sme->young_farmer) ?></td>
                    <td><?= h($sme->mountain_area) ?></td>
                    <td><?= h($sme->land_size) ?></td>
                    <td><?= h($sme->establishment_date) ?></td>
                    <td><?= h($sme->sector) ?></td>
                    <td><?= h($sme->sector_lpa) ?></td>
                    <td><?= $this->Number->format($sme->nbr_employees) ?></td>
                    <td><?= h($sme->sme_rating) ?></td>
                    <td><?= h($sme->startup) ?></td>
                    <td><?= h($sme->innovative) ?></td>
                    <td><?= h($sme->waiver) ?></td>
                    <td><?= h($sme->waiver_reason) ?></td>
                    <td><?= $this->Number->format($sme->turnover) ?></td>
                    <td><?= $this->Number->format($sme->assets) ?></td>
                    <td><?= $this->Number->format($sme->ebitda) ?></td>
                    <td><?= $this->Number->format($sme->net_debt_to_ebitda) ?></td>
                    <td><?= h($sme->eligible_beneficiary) ?></td>
                    <td><?= h($sme->eligible_beneficiary_type) ?></td>
                    <td><?= h($sme->target_beneficiary) ?></td>
                    <td><?= h($sme->borrower_type) ?></td>
                    <td><?= h($sme->micro_borrowers) ?></td>
                    <td><?= h($sme->eligibility_criteria) ?></td>
                    <td><?= h($sme->level_digitalization) ?></td>
                    <td><?= h($sme->thematic_criteria) ?></td>
                    <td><?= h($sme->sme_comments) ?></td>
                    <td><?= h($sme->error_message) ?></td>
                    <td><?= h($sme->category) ?></td>
                    <td><?= h($sme->fr_category) ?></td>
                    <td><?= $this->Number->format($sme->report_id) ?></td>
                    <td><?= $this->Number->format($sme->total_loan_amount_curr) ?></td>
                    <td><?= $this->Number->format($sme->total_loan_amount_eur) ?></td>
                    <td><?= $this->Number->format($sme->portfolio_id) ?></td>
                    <td><?= h($sme->legal_form) ?></td>
                    <td><?= h($sme->employment_status) ?></td>
                    <td><?= h($sme->fi_rating_scale) ?></td>
                    <td><?= h($sme->share_contacts) ?></td>
                    <td><?= h($sme->natural_person) ?></td>
                    <td><?= h($sme->natural_person_calc) ?></td>
                    <td><?= h($sme->website) ?></td>
                    <td><?= h($sme->social_enterprise) ?></td>
                    <td><?= h($sme->social_sector_org) ?></td>
                    <td><?= h($sme->holding_company) ?></td>
                    <td><?= h($sme->part_of_group) ?></td>
                    <td><?= h($sme->bds_paid) ?></td>
                    <td><?= $this->Number->format($sme->nbr_young_employed) ?></td>
                    <td><?= $this->Number->format($sme->nbr_young_training) ?></td>
                    <td><?= h($sme->youth_participant) ?></td>
                    <td><?= $this->Number->format($sme->personnel_cost) ?></td>
                    <td><?= $this->Number->format($sme->labor_market_status) ?></td>
                    <td><?= h($sme->name_ori_alphabet) ?></td>
                    <td><?= h($sme->address_ori_alphabet) ?></td>
                    <td><?= h($sme->place_ori_alphabet) ?></td>
                    <td><?= $this->Number->format($sme->sme_id_ori) ?></td>
                    <td><?= h($sme->created) ?></td>
                    <td><?= h($sme->modified) ?></td>
                    <td><?= h($sme->pkid) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $sme->sme_id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $sme->sme_id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $sme->sme_id], ['confirm' => __('Are you sure you want to delete # {0}?', $sme->sme_id)]) ?>
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
