<?php
declare(strict_types=1);

namespace Dsr\Model\Entity;

use Cake\ORM\Entity;

/**
 * DicoValue Entity
 *
 * @property int $id
 * @property int $dictionary_id
 * @property string $code
 * @property string $label
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \Dsr\Model\Entity\Dictionary $dictionary
 */
class DicoValue extends Entity
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
        'dictionary_id' => true,
        'code' => true,
        'label' => true,
        'created' => true,
        'modified' => true,
        'dictionary' => true,
    ];
}
