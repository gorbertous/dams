<?php
declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * UmbrellaPortfolioDeleted Entity
 *
 * @property int|null $report_id
 * @property string|null $report_name
 * @property int|null $status_id
 * @property string|null $input_filename
 * @property int|null $status_id_umbrella
 * @property int|null $portfolio_id
 * @property string|null $period
 * @property int $id_deleted
 * @property string|null $input_filename_umbrella
 * @property \Cake\I18n\FrozenTime|null $created
 *
 * @property \App\Model\Entity\Report $report
 * @property \App\Model\Entity\Status $status
 * @property \App\Model\Entity\Portfolio $portfolio
 */
class UmbrellaPortfolioDeleted extends Entity
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
        'report_id' => true,
        'report_name' => true,
        'status_id' => true,
        'input_filename' => true,
        'status_id_umbrella' => true,
        'portfolio_id' => true,
        'period' => true,
        'input_filename_umbrella' => true,
        'created' => true,
        'report' => true,
        'status' => true,
        'portfolio' => true,
    ];
}
