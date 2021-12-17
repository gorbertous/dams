<?php
declare(strict_types=1);

namespace Dsr\Model\Entity;

use Cake\ORM\Entity;

/**
 * Vdsrreport Entity
 *
 * @property int|null $period_year
 * @property string|null $name
 * @property int|null $portfolio_id
 * @property string|null $fi_name
 * @property int $GENDER_MALE
 * @property int $GENDER_FEMALE
 * @property int $GENDER_NI
 * @property int $EMPLOYMENT_EMPLOYED
 * @property int $EMPLOYMENT_UNEMPLOYED
 * @property int $EMPLOYMENT_STUDYING
 * @property int $EMPLOYMENT_INACTIVE
 * @property int $EMPLOYMENT_NI
 * @property int $EDUCATION_NONE
 * @property int $EDUCATION_PRIMARY
 * @property int $EDUCATION_SECONDARY
 * @property int $EDUCATION_POST_SEC
 * @property int $EDUCATION_UNIVERSITY
 * @property int $EDUCATION_NI
 * @property int $AGE_LESS_25
 * @property int $AGE_25_54
 * @property int $AGE_55_MORE
 * @property int $AGE_NI
 * @property int $GROUP_MINORITY
 * @property int $GROUP_DISABLED
 * @property int $GROUP_BOTH
 * @property int $GROUP_NI
 * @property float|null $TOTAL_EMPLOYEES
 * @property float|null $TOTAL_MALE
 * @property float|null $TOTAL_FEMALE
 * @property float|null $TOTAL_LESS_25
 * @property float|null $TOTAL_24_54
 * @property float|null $TOTAL_MORE_55
 * @property float|null $TOTAL_MINORITY
 * @property float|null $TOTAL_DISABLED
 * @property float|null $TOTAL_EXPOST_EMPLOYEES
 *
 * @property \Dsr\Model\Entity\Portfolio $portfolio
 */
class Vdsrreport extends Entity
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
        'period_year' => true,
        'name' => true,
        'portfolio_id' => true,
        'fi_name' => true,
        'GENDER_MALE' => true,
        'GENDER_FEMALE' => true,
        'GENDER_NI' => true,
        'EMPLOYMENT_EMPLOYED' => true,
        'EMPLOYMENT_UNEMPLOYED' => true,
        'EMPLOYMENT_STUDYING' => true,
        'EMPLOYMENT_INACTIVE' => true,
        'EMPLOYMENT_NI' => true,
        'EDUCATION_NONE' => true,
        'EDUCATION_PRIMARY' => true,
        'EDUCATION_SECONDARY' => true,
        'EDUCATION_POST_SEC' => true,
        'EDUCATION_UNIVERSITY' => true,
        'EDUCATION_NI' => true,
        'AGE_LESS_25' => true,
        'AGE_25_54' => true,
        'AGE_55_MORE' => true,
        'AGE_NI' => true,
        'GROUP_MINORITY' => true,
        'GROUP_DISABLED' => true,
        'GROUP_BOTH' => true,
        'GROUP_NI' => true,
        'TOTAL_EMPLOYEES' => true,
        'TOTAL_MALE' => true,
        'TOTAL_FEMALE' => true,
        'TOTAL_LESS_25' => true,
        'TOTAL_24_54' => true,
        'TOTAL_MORE_55' => true,
        'TOTAL_MINORITY' => true,
        'TOTAL_DISABLED' => true,
        'TOTAL_EXPOST_EMPLOYEES' => true,
        'portfolio' => true,
    ];
}
