<?php
declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * ErrorsLog Entity
 *
 * @property int $error_id
 * @property int $portfolio_id
 * @property string|null $portfolio_name
 * @property string|null $mandate
 * @property string|null $beneficiary_name
 * @property string|null $period
 * @property int $report_id
 * @property int|null $total_lines
 * @property int $iterations
 * @property string|null $file_formats
 * @property int|null $total_formats
 * @property int|null $total_dictionaries
 * @property int|null $total_integrities
 * @property int|null $total_business_rules
 * @property int|null $total_warnings
 * @property string|null $fi_responsivness
 * @property string|null $comments
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Portfolio $portfolio
 * @property \App\Model\Entity\Report $report
 */
class ErrorsLog extends Entity
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
        'portfolio_name' => true,
        'mandate' => true,
        'beneficiary_name' => true,
        'period' => true,
        'report_id' => true,
        'total_lines' => true,
        'iterations' => true,
        'file_formats' => true,
        'total_formats' => true,
        'total_dictionaries' => true,
        'total_integrities' => true,
        'total_business_rules' => true,
        'total_warnings' => true,
        'fi_responsivness' => true,
        'comments' => true,
        'created' => true,
        'modified' => true,
        'portfolio' => true,
        'report' => true,
    ];
}
