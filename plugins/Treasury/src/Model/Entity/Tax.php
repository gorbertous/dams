<?php
declare(strict_types=1);

namespace Treasury\Model\Entity;

use Cake\ORM\Entity;

/**
 * Tax Entity
 *
 * @property int $tax_ID
 * @property int|null $mandate_ID
 * @property int|null $cpty_ID
 * @property string|null $tax_rate
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 */
class Tax extends Entity
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
        'mandate_ID' => true,
        'cpty_ID' => true,
        'tax_rate' => true,
        'created' => true,
        'modified' => true,
    ];
}
