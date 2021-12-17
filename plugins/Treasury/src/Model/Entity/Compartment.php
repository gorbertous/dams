<?php
declare(strict_types=1);

namespace Treasury\Model\Entity;

use Cake\ORM\Entity;

/**
 * Compartment Entity
 *
 * @property int $cmp_ID
 * @property string|null $cmp_name
 * @property string|null $cmp_type
 * @property string|null $cmp_value
 * @property string|null $cmp_dpt_code_value
 * @property string|null $cmp_sof_value
 * @property int|null $mandate_ID
 * @property string|null $accountA_IBAN
 * @property string|null $accountB_IBAN
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 */
class Compartment extends Entity
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
        'cmp_name' => true,
        'cmp_type' => true,
        'cmp_value' => true,
        'cmp_dpt_code_value' => true,
        'cmp_sof_value' => true,
        'mandate_ID' => true,
        'accountA_IBAN' => true,
        'accountB_IBAN' => true,
        'created' => true,
        'modified' => true,
    ];
}
