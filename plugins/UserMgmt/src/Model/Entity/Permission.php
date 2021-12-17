<?php
declare(strict_types=1);

namespace UserMgmt\Model\Entity;

use Cake\Core\Configure;
use Cake\I18n\Time;
use Cake\ORM\Entity;
use Cake\Utility\Security;

/**
 * Permission Entity.
 *
 * @property int $user_group_id
 * @property string $plugin
 * @property string $controller
 * @property string $action
 * @property int $allowed
 * 
 */
class Permission extends Entity
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

    // protected function _getAllowed($allowed) {
    //     return (int) $allowed;
    // }
    // protected function _setAllowed($allowed) {
    //     return (int) $allowed;
    // }
}