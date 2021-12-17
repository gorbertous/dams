<?php
declare(strict_types=1);

namespace Treasury\Model\Entity;

use Cake\ORM\Entity;

/**
 * CounterpartyGroup Entity
 *
 * @property int $counterpartygroup_ID
 * @property string|null $counterpartygroup_name
 * @property int $head
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 */
class CounterpartyGroup extends Entity
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
        'counterpartygroup_name' => true,
        'head' => true,
        'created' => true,
        'modified' => true,
    ];
}
