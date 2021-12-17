<?php
declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * RuleParameter Entity
 *
 * @property int $template_type_id
 * @property int|null $product_id
 * @property int|null $mandate_id
 * @property int|null $portfolio_id
 * @property string  $checked_entity
 * @property string  $checked_field
 * @property string $datatype
 * @property int|null $dictionary_id
 * @property int|null $is_cf
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Dictionary $dictionary
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\Mandate $mandate
 * @property \App\Model\Entity\Portfolio $portfolio
 * @property \App\Model\Entity\TemplateType $template_type
 */
class RuleParameter extends Entity
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
        'is_cf' => true,
        'dictionary_id' => true,
        'created' => true,
        'modified' => true,
        'product' => true,
        'mandate' => true,
        'portfolio' => true,
        'dictionary' => true,
        'template_type' => true,
    ];
}
