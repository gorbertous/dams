<?php
declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * DictionaryValue Entity
 *
 * @property int $dicoval_id
 * @property int|null $dictionary_id
 * @property string|null $code
 * @property string|null $translation
 * @property string|null $label
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Dictionary $dictionary
 */
class DictionaryValue extends Entity
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
        'translation' => true,
        'label' => true,
        'created' => true,
        'modified' => true,
        'dictionary' => true,
    ];
}
