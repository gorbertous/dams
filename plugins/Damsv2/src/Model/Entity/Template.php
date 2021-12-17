<?php
declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * Template Entity
 *
 * @property int $template_id
 * @property string $name
 * @property int $template_type_id
 * @property int|null $callback_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\TemplateType $template_type
 * @property \App\Model\Entity\Callback $callback
 * @property \App\Model\Entity\Portfolio[] $portfolio
 */
class Template extends Entity
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
        'name' => true,
        'template_type_id' => true,
        'callback_id' => true,
        'created' => true,
        'modified' => true,
        'template_type' => true,
        'callback' => true,
        'portfolio' => true,
    ];
}
