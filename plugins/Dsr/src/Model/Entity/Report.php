<?php
declare(strict_types=1);

namespace Dsr\Model\Entity;

use Cake\ORM\Entity;

/**
 * Report Entity
 *
 * @property int $id
 * @property int|null $portfolio_id
 * @property string|null $period_quarter
 * @property int|null $period_year
 * @property \Cake\I18n\FrozenDate|null $report_date
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \Dsr\Model\Entity\Portfolio $portfolio
 * @property \Dsr\Model\Entity\Loan[] $loans
 */
class Report extends Entity
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
        'period_quarter' => true,
        'period_year' => true,
        'report_date' => true,
        'created' => true,
        'modified' => true,
        'portfolio' => true,
        'loans' => true,
    ];
}
