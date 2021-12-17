<?php
declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * FixedRate Entity
 *
 * @property int $rate_id
 * @property int $portfolio_id
 * @property string $currency
 * @property float $obs_value
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Portfolio $portfolio
 */
class FixedRate extends Entity
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
        'portfolio_id' => true,
        'currency' => true,
        'obs_value' => true,
        'created' => true,
        'modified' => true,
        'portfolio' => true,
    ];
}
