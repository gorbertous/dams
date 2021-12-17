<?php
declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * Dstoolbox Entity
 *
 * @property int $dstoolbox_id
 * @property string|null $name
 * @property string|null $description
 * @property string|null $filename
 * @property string|null $BO_url
 * @property int|null $domain_id
 * @property \Cake\I18n\FrozenTime|null $creation_date
 * @property \Cake\I18n\FrozenTime|null $modification_date
 *
 * @property \App\Model\Entity\Domain $domain
 */
class Dstoolbox extends Entity
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
        'name' => true,
        'description' => true,
        'filename' => true,
        'BO_url' => true,
        'domain_id' => true,
        'creation_date' => true,
        'modification_date' => true,
        'domain' => true,
    ];
}
