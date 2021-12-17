<?php

declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * CountSmesAltfinal Entity
 *
 * @property \Cake\I18n\FrozenDate|null $period_end_date
 * @property float|null $total_nbr_of_SMEs
 */
class CountSmesAltfinal extends Entity
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
        'period_end_date' => true,
        'total_nbr_of_SMEs' => true,
    ];

}
