<?php
declare(strict_types=1);

namespace Dsr\Model\Entity;

use Cake\ORM\Entity;

/**
 * Loan Entity
 *
 * @property int $id
 * @property int|null $report_id
 * @property int|null $portfolio_id
 * @property string|null $deal_name
 * @property int|null $start_year
 * @property int|null $end_year
 * @property string|null $loan_reference
 * @property string|null $file_reference
 * @property string|null $intermediary
 * @property int|null $gender
 * @property int|null $employment
 * @property int|null $education
 * @property int|null $age
 * @property int|null $specific_group
 * @property string|null $country
 * @property string|null $region
 * @property float|null $total_employees
 * @property float|null $total_male
 * @property float|null $total_female
 * @property float|null $total_less_25
 * @property float|null $total_25_54
 * @property float|null $total_more_55
 * @property float|null $total_minority
 * @property float|null $total_disabled
 * @property float|null $expost_total_employees
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \Dsr\Model\Entity\Report $report
 * @property \Dsr\Model\Entity\Portfolio $portfolio
 */
class Loan extends Entity
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
        'report_id' => true,
        'portfolio_id' => true,
        'deal_name' => true,
        'start_year' => true,
        'end_year' => true,
        'loan_reference' => true,
        'file_reference' => true,
        'intermediary' => true,
        'gender' => true,
        'employment' => true,
        'education' => true,
        'age' => true,
        'specific_group' => true,
        'country' => true,
        'region' => true,
        'total_employees' => true,
        'total_male' => true,
        'total_female' => true,
        'total_less_25' => true,
        'total_25_54' => true,
        'total_more_55' => true,
        'total_minority' => true,
        'total_disabled' => true,
        'expost_total_employees' => true,
        'created' => true,
        'modified' => true,
        'report' => true,
        'portfolio' => true,
    ];
}
