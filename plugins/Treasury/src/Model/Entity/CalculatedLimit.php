<?php
declare(strict_types=1);

namespace Treasury\Model\Entity;

use Cake\ORM\Entity;

/**
 * CalculatedLimit Entity
 *
 * @property int $id
 * @property int $mandategroup_ID
 * @property int $cpty_ID
 * @property string|null $pirat_number
 * @property string|null $LT-R
 * @property \Cake\I18n\FrozenDate|null $LT-R_date
 * @property string|null $ST-R
 * @property \Cake\I18n\FrozenDate|null $ST-R_date
 * @property bool $eligibility
 * @property string $calculated_limit
 * @property int $calculated_max_maturity
 * @property string|null $calculated_max_concentration
 * @property string|null $concentration_limit_unit
 * @property \Cake\I18n\FrozenTime|null $effective_date
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class CalculatedLimit extends Entity
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
        'mandategroup_ID' => true,
        'cpty_ID' => true,
        'pirat_number' => true,
        'LT-R' => true,
        'LT-R_date' => true,
        'ST-R' => true,
        'ST-R_date' => true,
        'eligibility' => true,
        'calculated_limit' => true,
        'calculated_max_maturity' => true,
        'calculated_max_concentration' => true,
        'concentration_limit_unit' => true,
        'effective_date' => true,
        'created' => true,
        'modified' => true,
    ];
}
