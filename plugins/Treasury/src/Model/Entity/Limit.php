<?php
declare(strict_types=1);

namespace Treasury\Model\Entity;

use Cake\ORM\Entity;

/**
 * Limit Entity
 *
 * @property int $limit_ID
 * @property string|null $limit_name
 * @property \Cake\I18n\FrozenDate|null $limit_date_from
 * @property \Cake\I18n\FrozenDate|null $limit_date_to
 * @property int|null $mandategroup_ID
 * @property int|null $counterpartygroup_ID
 * @property int|null $cpty_ID
 * @property int|null $automatic
 * @property string|null $rating_lt
 * @property string|null $rating_st
 * @property string|null $cpty_rating
 * @property int|null $max_maturity
 * @property string|null $limit_eur
 * @property string|null $max_concentration
 * @property string|null $concentration_limit_unit
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $is_current
 */
class Limit extends Entity
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
        'limit_name' => true,
        'limit_date_from' => true,
        'limit_date_to' => true,
        'mandategroup_ID' => true,
        'counterpartygroup_ID' => true,
        'cpty_ID' => true,
        'automatic' => true,
        'rating_lt' => true,
        'rating_st' => true,
        'cpty_rating' => true,
        'max_maturity' => true,
        'limit_eur' => true,
        'max_concentration' => true,
        'concentration_limit_unit' => true,
        'created' => true,
        'modified' => true,
        'is_current' => true,
    ];
}
