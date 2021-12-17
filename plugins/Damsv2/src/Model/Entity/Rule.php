<?php
declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * Rule Entity
 *
 * @property int $rule_id
 * @property string|null $rule_number
 * @property string $rule_category
 * @property string $rule_level
 * @property int|null $product_id
 * @property int|null $mandate_id
 * @property int|null $portfolio_id
 * @property string|null $is_warning
 * @property string $inclusion_and_edit
 * @property string|null $rule_name
 * @property string|null $top_level
 * @property string|null $rule_type
 * @property string|null $checked_entity
 * @property string|null $checked_field
 * @property string|null $operator
 * @property string|null $param_1_value
 * @property string|null $param_2_value
 * @property string|null $truepart_id
 * @property string|null $falsepart_id
 * @property string|null $description
 * @property int|null $version_number
 * @property int $template_type_id
 * @property int $user_id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\Mandate $mandate
 * @property \App\Model\Entity\Portfolio $portfolio
 * @property \App\Model\Entity\Rule $truepart
 * @property \App\Model\Entity\Rule $falsepart
 * @property \App\Model\Entity\TemplateType $template_type
 * @property \App\Model\Entity\VUser $user
 */
class Rule extends Entity
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
        'rule_number' => true,
        'rule_category' => true,
        'rule_level' => true,
        'product_id' => true,
        'mandate_id' => true,
        'portfolio_id' => true,
        'is_warning' => true,
        'inclusion_and_edit' => true,
        'rule_name' => true,
        'top_level' => true,
        'rule_type' => true,
        'checked_entity' => true,
        'checked_field' => true,
        'operator' => true,
        'param_1_value' => true,
        'param_2_value' => true,
        'truepart_id' => true,
        'falsepart_id' => true,
        'description' => true,
        'version_number' => true,
        'template_type_id' => true,
        'user_id' => true,
        'created' => true,
        'modified' => true,
        'product' => true,
        'mandate' => true,
        'portfolio' => true,
        'truepart' => true,
        'falsepart' => true,
        'template_type' => true,
        'user' => true,
    ];
    protected function _getTruepartKey()
    {
        return $this->truepart->rule_id;
    }
    protected function _getFalsepartKey()
    {
        return $this->falsepart->rule_id;
    }
}
