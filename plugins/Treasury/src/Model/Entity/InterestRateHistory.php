<?php
declare(strict_types=1);

namespace Treasury\Model\Entity;

use Cake\ORM\Entity;

/**
 * InterestRateHistory Entity
 *
 * @property int $id
 * @property int $trn_number
 * @property \Cake\I18n\FrozenDate $interest_rate_from
 * @property \Cake\I18n\FrozenDate|null $interest_rate_to
 * @property string|null $interest_rate
 */
class InterestRateHistory extends Entity
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
        'trn_number' => true,
        'interest_rate_from' => true,
        'interest_rate_to' => true,
        'interest_rate' => true,
    ];
}
