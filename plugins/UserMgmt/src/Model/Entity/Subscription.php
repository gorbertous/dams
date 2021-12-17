<?php
declare(strict_types=1);

namespace UserMgmt\Model\Entity;

use Cake\Core\Configure;
use Cake\I18n\Time;
use Cake\ORM\Entity;
use Cake\Utility\Security;

/**
 * Subscription Entity.
 *
 * @property int $user_id
 * @property int $group_id
 * 
 */
class Subscription extends Entity
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