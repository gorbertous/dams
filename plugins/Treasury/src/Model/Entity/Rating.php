<?php
declare(strict_types=1);

namespace Treasury\Model\Entity;

use Cake\ORM\Entity;

/**
 * Rating Entity
 *
 * @property int $id
 * @property int $automatic
 * @property string $pirat_number
 * @property string|null $pirat_cpty_name
 * @property string|null $pirat_address
 * @property string|null $pirat_country
 * @property string|null $mother_company
 * @property string|null $own_funds
 * @property \Cake\I18n\FrozenDate|null $bs_date
 * @property string|null $LT-MDY
 * @property \Cake\I18n\FrozenDate|null $LT-MDY_date
 * @property string|null $LT-MDY_outlook
 * @property string|null $LT-FIT
 * @property \Cake\I18n\FrozenDate|null $LT-FIT_date
 * @property string|null $LT-FIT_outlook
 * @property string|null $LT-STP
 * @property \Cake\I18n\FrozenDate|null $LT-STP_date
 * @property string|null $LT-STP_outlook
 * @property string|null $LT-EIB
 * @property \Cake\I18n\FrozenDate|null $LT-EIB_date
 * @property string|null $ST-MDY
 * @property \Cake\I18n\FrozenDate|null $ST-MDY_date
 * @property string|null $ST-MDY_outlook
 * @property string|null $ST-FIT
 * @property \Cake\I18n\FrozenDate|null $ST-FIT_date
 * @property string|null $ST-FIT_outlook
 * @property string|null $ST-STP
 * @property \Cake\I18n\FrozenDate|null $ST-STP_date
 * @property string|null $ST-STP_outlook
 * @property string|null $ST-EIB
 * @property \Cake\I18n\FrozenDate|null $ST-EIB_date
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class Rating extends Entity
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
        'automatic' => true,
        'pirat_number' => true,
        'pirat_cpty_name' => true,
        'pirat_address' => true,
        'pirat_country' => true,
        'mother_company' => true,
        'own_funds' => true,
        'bs_date' => true,
        'LT-MDY' => true,
        'LT-MDY_date' => true,
        'LT-MDY_outlook' => true,
        'LT-FIT' => true,
        'LT-FIT_date' => true,
        'LT-FIT_outlook' => true,
        'LT-STP' => true,
        'LT-STP_date' => true,
        'LT-STP_outlook' => true,
        'LT-EIB' => true,
        'LT-EIB_date' => true,
        'ST-MDY' => true,
        'ST-MDY_date' => true,
        'ST-MDY_outlook' => true,
        'ST-FIT' => true,
        'ST-FIT_date' => true,
        'ST-FIT_outlook' => true,
        'ST-STP' => true,
        'ST-STP_date' => true,
        'ST-STP_outlook' => true,
        'ST-EIB' => true,
        'ST-EIB_date' => true,
        'created' => true,
        'modified' => true,
    ];
}
