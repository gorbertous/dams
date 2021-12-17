<?php
declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * SummaryTable Entity
 *
 * @property string|null $mandate
 * @property float|null $sum_princ_guar_amount_eur
 * @property float|null $sum_disb_guar_amount_eur
 * @property float|null $number_of_loans
 * @property float|null $number_of_SMEs
 */
class SummaryTable extends Entity
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
        'mandate' => true,
        'total_principal_amount_eur' => true,
        'total_disbursed_amount_eur' => true,
        'number_of_loans' => true,
        'number_of_supported_SMEs' => true,
    ];
}
