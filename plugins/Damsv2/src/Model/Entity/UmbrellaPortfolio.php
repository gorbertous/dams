<?php
declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * UmbrellaPortfolio Entity
 *
 * @property int $umbrella_portfolio_id
 * @property string|null $umbrella_portfolio_name
 * @property string|null $iqid
 * @property int $product_id
 * @property string|null $splitting_field
 * @property string|null $splitting_table
 *
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\Deleted[] $deleted
 */
class UmbrellaPortfolio extends Entity
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
        'umbrella_portfolio_name' => true,
        'iqid' => true,
        'product_id' => true,
        'splitting_field' => true,
        'splitting_table' => true,
        'product' => true,
        'deleted' => true,
    ];
}
