<?php
declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * SamplingEvaluation Entity
 *
 * @property int $evaluation_id
 * @property int $evaluation_year
 * @property float $value_pds
 * @property float $value_pds_sampled
 * @property int $nb_pds_sampled
 * @property int $nb_hv_sampled
 * @property int $nb_lv_sampled
 * @property float $value_hv
 * @property float $overstatements_hv
 * @property float $materiality_threshold
 * @property float $materiality_threshold_eur
 * @property float $res_materiality_threshold
 * @property float $average_taint_lv
 * @property float $confidence_no_overstate
 * @property float $probability_overstate
 * @property string|null $user
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class SamplingEvaluation extends Entity
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
        'evaluation_year' => true,
        'value_pds' => true,
        'value_pds_sampled' => true,
        'nb_pds_sampled' => true,
        'nb_hv_sampled' => true,
        'nb_lv_sampled' => true,
        'value_hv' => true,
        'overstatements_hv' => true,
        'materiality_threshold' => true,
        'materiality_threshold_eur' => true,
        'res_materiality_threshold' => true,
        'average_taint_lv' => true,
        'confidence_no_overstate' => true,
        'probability_overstate' => true,
        'user' => true,
        'created' => true,
        'modified' => true,
    ];
}
