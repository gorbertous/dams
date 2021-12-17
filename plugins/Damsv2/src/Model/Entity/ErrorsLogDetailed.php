<?php
declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * ErrorsLogDetailed Entity
 *
 * @property int $error_detail_id
 * @property int $error_id
 * @property string $sheet
 * @property int|null $lines
 * @property string|null $file_formats
 * @property int|null $formats
 * @property int|null $dictionaries
 * @property int|null $integrities
 * @property int|null $business_rules
 * @property int|null $warnings
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\ErrorsLog $errors_log
 */
class ErrorsLogDetailed extends Entity
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
        'error_id' => true,
        'sheet' => true,
        'lines' => true,
        'file_formats' => true,
        'formats' => true,
        'dictionaries' => true,
        'integrities' => true,
        'business_rules' => true,
        'warnings' => true,
        'created' => true,
        'modified' => true,
        'errors_log' => true,
    ];
}
