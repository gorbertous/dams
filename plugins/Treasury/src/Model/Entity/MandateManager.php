<?php
declare(strict_types=1);

namespace Treasury\Model\Entity;

use Cake\ORM\Entity;

/**
 * MandateManager Entity
 *
 * @property int $id
 * @property int|null $mandate_ID
 * @property string|null $name
 * @property string|null $email
 */
class MandateManager extends Entity
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
        'name' => true,
        'email' => true,
    ];
}
