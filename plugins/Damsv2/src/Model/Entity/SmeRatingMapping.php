<?php
declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * SmeRatingMapping Entity
 *
 * @property int $sme_rating_mapping_id
 * @property int $portfolio_id
 * @property string|null $sme_fi_rating_scale
 * @property string|null $sme_rating
 * @property string|null $adjusted_sme_fi_scale
 * @property string|null $adjusted_sme_rating
 * @property string|null $equiv_ori_sme_rating
 * @property int|null $user_id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Portfolio $portfolio
 * @property \App\Model\Entity\VUser $user
 */
class SmeRatingMapping extends Entity
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
        'sme_fi_rating_scale' => true,
        'sme_rating' => true,
        'adjusted_sme_fi_scale' => true,
        'adjusted_sme_rating' => true,
        'equiv_ori_sme_rating' => true,
        'user_id' => true,
        'created' => true,
        'modified' => true,
        'portfolio' => true,
        'user' => true,
    ];
}
