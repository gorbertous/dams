<?php
declare(strict_types=1);

namespace Treasury\Model\Entity;

use Cake\ORM\Entity;

/**
 * Reinvestment Entity
 *
 * @property int $reinv_group
 * @property string $reinv_status
 * @property int|null $mandate_ID
 * @property int|null $cmp_ID
 * @property int|null $cpty_ID
 * @property \Cake\I18n\FrozenDate|null $availability_date
 * @property string|null $accountA_IBAN
 * @property string|null $accountB_IBAN
 * @property string|null $amount_leftA
 * @property string|null $amount_leftB
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property string|null $reinv_type
 */
class Reinvestment extends Entity
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
        'reinv_status' => true,
        'mandate_ID' => true,
        'cmp_ID' => true,
        'cpty_ID' => true,
        'availability_date' => true,
        'accountA_IBAN' => true,
        'accountB_IBAN' => true,
        'amount_leftA' => true,
        'amount_leftB' => true,
        'created' => true,
        'modified' => true,
        'reinv_type' => true,
    ];
}
