<?php
declare(strict_types=1);

namespace Treasury\Model\Entity;

use Cake\ORM\Entity;

/**
 * CustomText Entity
 *
 * @property int $custom_id
 * @property int|null $cpty_id
 * @property string|null $dropdown_txt
 * @property string|null $custom_txt
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \Treasury\Model\Entity\Cpty $cpty
 */
class CustomText extends Entity
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
        'cpty_id' => true,
        'dropdown_txt' => true,
        'custom_txt' => true,
        'created' => true,
        'modified' => true,
        'cpty' => true,
    ];
}
