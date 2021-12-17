<?php
declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * PortfolioRates Entity
 *
 * @property int $portfolio_rates_id
 * @property int $portfolio_id
 * @property string|null $theme
 * @property \Cake\I18n\FrozenDate|null $effective_date
 * @property \Cake\I18n\FrozenDate|null $availability_start
 * @property \Cake\I18n\FrozenDate|null $availability_end
 * @property \Cake\I18n\FrozenDate|null $rate_application_date
 * @property float|null $guarantee_rate
 * @property float|null $cap_rate
 * @property float|null $commitment
 * @property float|null $cap_amount
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Portfolio $portfolio
 */
class PortfolioRates extends Entity
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
        'theme' => true,
        'effective_date' => true,
        'availability_start' => true,
        'availability_end' => true,
        'rate_application_date' => true,
        'guarantee_rate' => true,
        'cap_rate' => true,
        'commitment' => true,
        'cap_amount' => true,
        'created' => true,
        'modified' => true,
        'portfolio' => true,
    ];
}
