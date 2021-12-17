<?php
declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * Daily Entity
 *
 * @property int $ORDER
 * @property \Cake\I18n\FrozenDate $DATE
 * @property string $CURRENCY
 * @property float $OBS_VALUE
 * @property int $TREND
 * @property float $DIFF
 */
class Daily extends Entity
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
        'ORDER' => true,
        'DATE' => true,
        'OBS_VALUE' => true,
        'TREND' => true,
        'DIFF' => true,
    ];
}
