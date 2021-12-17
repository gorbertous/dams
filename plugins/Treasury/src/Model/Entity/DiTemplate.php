<?php
declare(strict_types=1);

namespace Treasury\Model\Entity;

use Cake\ORM\Entity;

/**
 * DiTemplate Entity
 *
 * @property int $dit_id
 * @property string|null $template
 * @property int|null $mandate_ID
 * @property int|null $cpty_ID
 * @property string|null $attn
 * @property string|null $preamble
 * @property string|null $deposits_footer
 * @property int|null $footer_force
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 */
class DiTemplate extends Entity
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
        'template' => true,
        'mandate_ID' => true,
        'cpty_ID' => true,
        'attn' => true,
        'preamble' => true,
        'deposits_footer' => true,
        'footer_force' => true,
        'created' => true,
        'modified' => true,
    ];
}
