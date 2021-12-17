<?php
declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * AnnualSamplingParameter Entity
 *
 * @property int $sample_year_id
 * @property int $sampling_year
 * @property int|null $last_sampled_month
 * @property float $expected_payments_eur
 * @property int $number_of_samples
 * @property float $sampling_interval_eur
 * @property string|null $user
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class AnnualSamplingParameter extends Entity
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
        'sampling_year' => true,
        'last_sampled_month' => true,
        'expected_payments_eur' => true,
        'number_of_samples' => true,
        'sampling_interval_eur' => true,
        'user' => true,
        'created' => true,
        'modified' => true,
    ];
}
