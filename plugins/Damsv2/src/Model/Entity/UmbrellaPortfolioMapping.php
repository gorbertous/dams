<?php
declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * UmbrellaPortfolioMapping Entity
 *
 * @property int $umbrella_portfolio_mapping_id
 * @property int $umbrella_portfolio_id
 * @property int $portfolio_id
 * @property string|null $portfolio_name
 *
 * @property \App\Model\Entity\UmbrellaPortfolio $umbrella_portfolio
 * @property \App\Model\Entity\Portfolio $portfolio
 */
class UmbrellaPortfolioMapping extends Entity
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
        'umbrella_portfolio_id' => true,
        'portfolio_id' => true,
        'portfolio_name' => true,
        'umbrella_portfolio' => true,
        'portfolio' => true,
    ];
}
