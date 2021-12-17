<?php
declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * ExpiredTransaction Entity
 *
 * @property int $expired_id
 * @property int|null $transaction_id
 * @property int|null $subtransaction_id
 * @property int|null $sme_id
 * @property \Cake\I18n\FrozenDate|null $repayment_date
 * @property int|null $portfolio_id
 * @property int|null $report_id
 * @property int|null $nbr_employees_expired
 * @property \Cake\I18n\FrozenDate|null $sale_date
 * @property float|null $sale_price
 * @property float|null $sale_price_eur
 * @property float|null $sale_price_curr
 * @property \Cake\I18n\FrozenDate|null $write_off_date
 * @property float|null $write_off
 * @property float|null $write_off_eur
 * @property float|null $write_off_curr
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Transaction $transaction
 * @property \App\Model\Entity\Subtransaction $subtransaction
 * @property \App\Model\Entity\Sme $sme
 * @property \App\Model\Entity\Portfolio $portfolio
 * @property \App\Model\Entity\Report $report
 */
class ExpiredTransaction extends Entity
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
        'transaction_id' => true,
        'subtransaction_id' => true,
        'sme_id' => true,
        'repayment_date' => true,
        'portfolio_id' => true,
        'report_id' => true,
        'nbr_employees_expired' => true,
        'sale_date' => true,
        'sale_price' => true,
        'sale_price_eur' => true,
        'sale_price_curr' => true,
        'write_off_date' => true,
        'write_off' => true,
        'write_off_eur' => true,
        'write_off_curr' => true,
        'created' => true,
        'modified' => true,
        'transaction' => true,
        'subtransaction' => true,
        'sme' => true,
        'portfolio' => true,
        'report' => true,
    ];
}
