<?php
declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * MappingColumn Entity
 *
 * @property int $column_id
 * @property int $table_id
 * @property string $table_field
 * @property string $datatype
 * @property int $exec_order
 * @property int|null $excel_pk
 * @property int|null $excel_fk
 * @property int|null $excel_column
 * @property bool $is_null
 * @property int|null $db_pk
 * @property int|null $db_fk
 * @property int|null $db_load_pk
 * @property int|null $db_load_fk
 * @property int|null $db_id
 * @property int|null $fk_id
 * @property string|null $sql_formula
 * @property string|null $macro
 * @property int|null $is_cf
 * @property bool $is_converted
 * @property bool $in_view
 * @property bool $transcode
 * @property int|null $not_store
 * @property int|null $dictionary_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Table $table
 * @property \App\Model\Entity\MappingTable $mappingTable
 * @property \App\Model\Entity\Db $db
 * @property \App\Model\Entity\Fk $fk
 * @property \App\Model\Entity\Dictionary $dictionary
 */
class MappingColumn extends Entity
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
        'table_id' => true,
        'table_field' => true,
        'datatype' => true,
        'exec_order' => true,
        'excel_pk' => true,
        'excel_fk' => true,
        'excel_column' => true,
        'is_null' => true,
        'db_pk' => true,
        'db_fk' => true,
        'db_load_pk' => true,
        'db_load_fk' => true,
        'db_id' => true,
        'fk_id' => true,
        'sql_formula' => true,
        'macro' => true,
        'is_cf' => true,
        'is_converted' => true,
        'in_view' => true,
        'transcode' => true,
        'not_store' => true,
        'dictionary_id' => true,
        'created' => true,
        'modified' => true,
        'table' => true,
        'mappingTable' => true,
        'db' => true,
        'fk' => true,
        'dictionary' => true,
    ];
}
