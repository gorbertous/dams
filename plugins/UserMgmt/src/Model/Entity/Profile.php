<?php
declare(strict_types=1);

namespace UserMgmt\Model\Entity;

use Cake\Core\Configure;
use Cake\I18n\Time;
use Cake\ORM\Entity;
use Cake\Utility\Security;

/**
 * Profile Entity.
 *
 * @property int $id
 * @property string $name
 * @property string $alias_name
 * @property bool $allowRegistration
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property \CakeDC\Users\Model\Entity\Permission[] $permissions
 * 
 */
class Profile extends Entity
{
        /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];

    /**
     * Fields that are excluded from JSON an array versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
    ];
}