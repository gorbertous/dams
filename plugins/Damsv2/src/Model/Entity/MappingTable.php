<?php
declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * MappingTable Entity
 *
 * @property int $table_id
 * @property int $template_id
 * @property string $name
 * @property string $table_name
 * @property string|null $sheet_name
 * @property int|null $loading_order
 * @property int $is_cf
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Template $template
 */
class MappingTable extends Entity
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
        'template_id' => true,
        'name' => true,
        'table_name' => true,
        'sheet_name' => true,
        'loading_order' => true,
        'is_cf' => true,
        'created' => true,
        'modified' => true,
        'template' => true,
    ];
}
