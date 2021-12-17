<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * BrParameter Entity
 *
 * @property int|null $template_type_id
 * @property int|null $product_id
 * @property int|null $mandate_id
 * @property int|null $portfolio_id
 * @property string|null $checked_entity
 * @property string|null $checked_field
 * @property string|null $datatype
 * @property int|null $dictionary_id
 * @property int|null $is_cf
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\TemplateType $template_type
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\Mandate $mandate
 * @property \App\Model\Entity\Portfolio $portfolio
 * @property \App\Model\Entity\Dictionary $dictionary
 */
class BrParameter extends Entity
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
        'template_type_id' => true,
        'product_id' => true,
        'mandate_id' => true,
        'portfolio_id' => true,
        'checked_entity' => true,
        'checked_field' => true,
        'datatype' => true,
        'dictionary_id' => true,
        'is_cf' => true,
        'created' => true,
        'modified' => true,
        'template_type' => true,
        'product' => true,
        'mandate' => true,
        'portfolio' => true,
        'dictionary' => true,
    ];
}
