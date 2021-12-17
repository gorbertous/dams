<?php
declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * SmePortfolio Entity
 *
 * @property int $sme_portfolio_id
 * @property int|null $sme_id
 * @property string|null $fiscal_number
 * @property string|null $siret
 * @property string|null $name
 * @property string|null $surname
 * @property string|null $first_name
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $email
 * @property string|null $gender
 * @property string|null $postal_code
 * @property string|null $place
 * @property string|null $region
 * @property string|null $region_lau
 * @property string|null $country
 * @property string|null $country_main_operations
 * @property string|null $nationality
 * @property string|null $degree_m
 * @property string|null $degree_f
 * @property int|null $study_field
 * @property string|null $university
 * @property int|null $study_duration
 * @property string|null $country_study
 * @property string|null $country_edu
 * @property string|null $small_farm
 * @property string|null $young_farmer
 * @property string|null $mountain_area
 * @property string|null $land_size
 * @property \Cake\I18n\FrozenDate|null $establishment_date
 * @property string|null $sector
 * @property string|null $sector_lpa
 * @property float|null $nbr_employees
 * @property string|null $sme_rating
 * @property string|null $startup
 * @property string|null $innovative
 * @property string|null $waiver
 * @property string|null $waiver_reason
 * @property float|null $turnover
 * @property float|null $assets
 * @property float|null $ebitda
 * @property float|null $net_debt_to_ebitda
 * @property string|null $eligible_beneficiary
 * @property string|null $eligible_beneficiary_type
 * @property string|null $target_beneficiary
 * @property string|null $borrower_type
 * @property string|null $micro_borrowers
 * @property string|null $eligibility_criteria
 * @property string|null $level_digitalization
 * @property string|null $thematic_criteria
 * @property string|null $sme_comments
 * @property string|null $error_message
 * @property string|null $category
 * @property string|null $fr_category
 * @property int|null $report_id
 * @property float|null $total_loan_amount_curr
 * @property float|null $total_loan_amount_eur
 * @property int|null $portfolio_id
 * @property string|null $legal_form
 * @property string|null $employment_status
 * @property string|null $fi_rating_scale
 * @property string|null $share_contacts
 * @property string|null $natural_person
 * @property string|null $natural_person_calc
 * @property string|null $website
 * @property string|null $social_enterprise
 * @property string|null $social_sector_org
 * @property string|null $holding_company
 * @property string|null $part_of_group
 * @property string|null $bds_paid
 * @property float|null $nbr_young_employed
 * @property float|null $nbr_young_training
 * @property string|null $youth_participant
 * @property float|null $personnel_cost
 * @property float|null $labor_market_status
 * @property int|null $sme_id_ori
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property string|null $name_ori_alphabet
 * @property string|null $address_ori_alphabet
 * @property string|null $place_ori_alphabet
 * @property string|null $pkid
 *
 * @property \App\Model\Entity\Sme $sme
 * @property \App\Model\Entity\Report $report
 * @property \App\Model\Entity\Portfolio $portfolio
 */
class SmePortfolio extends Entity
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
        'sme_id' => true,
        'fiscal_number' => true,
        'siret' => true,
        'name' => true,
        'surname' => true,
        'first_name' => true,
        'phone' => true,
        'address' => true,
        'email' => true,
        'gender' => true,
        'postal_code' => true,
        'place' => true,
        'region' => true,
        'region_lau' => true,
        'country' => true,
        'country_main_operations' => true,
        'nationality' => true,
        'degree_m' => true,
        'degree_f' => true,
        'study_field' => true,
        'university' => true,
        'study_duration' => true,
        'country_study' => true,
        'country_edu' => true,
        'small_farm' => true,
        'young_farmer' => true,
        'mountain_area' => true,
        'land_size' => true,
        'establishment_date' => true,
        'sector' => true,
        'sector_lpa' => true,
        'nbr_employees' => true,
        'sme_rating' => true,
        'startup' => true,
        'innovative' => true,
        'waiver' => true,
        'waiver_reason' => true,
        'turnover' => true,
        'assets' => true,
        'ebitda' => true,
        'net_debt_to_ebitda' => true,
        'eligible_beneficiary' => true,
        'eligible_beneficiary_type' => true,
        'target_beneficiary' => true,
        'borrower_type' => true,
        'micro_borrowers' => true,
        'eligibility_criteria' => true,
        'level_digitalization' => true,
        'thematic_criteria' => true,
        'sme_comments' => true,
        'error_message' => true,
        'category' => true,
        'fr_category' => true,
        'report_id' => true,
        'total_loan_amount_curr' => true,
        'total_loan_amount_eur' => true,
        'portfolio_id' => true,
        'legal_form' => true,
        'employment_status' => true,
        'fi_rating_scale' => true,
        'share_contacts' => true,
        'natural_person' => true,
        'natural_person_calc' => true,
        'website' => true,
        'social_enterprise' => true,
        'social_sector_org' => true,
        'holding_company' => true,
        'part_of_group' => true,
        'bds_paid' => true,
        'nbr_young_employed' => true,
        'nbr_young_training' => true,
        'youth_participant' => true,
        'personnel_cost' => true,
        'labor_market_status' => true,
        'sme_id_ori' => true,
        'created' => true,
        'modified' => true,
        'name_ori_alphabet' => true,
        'address_ori_alphabet' => true,
        'place_ori_alphabet' => true,
        'pkid' => true,
        'sme' => true,
        'report' => true,
        'portfolio' => true,
    ];
}
