<?php
declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * ExcludedTransaction Entity
 *
 * @property int $excluded_id
 * @property int|null $sme_id
 * @property int|null $transaction_id
 * @property int|null $subtransaction_id
 * @property \Cake\I18n\FrozenDate|null $exclusion_date
 * @property float|null $excluded_transaction_amount
 * @property float|null $excluded_transaction_amount_eur
 * @property float|null $excluded_transaction_amount_curr
 * @property string|null $exclusion_type
 * @property string|null $coverage_implication
 * @property string|null $acceleration_flag
 * @property string|null $comments
 * @property int|null $portfolio_id
 * @property int|null $report_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Sme $sme
 * @property \App\Model\Entity\Transaction $transaction
 * @property \App\Model\Entity\Subtransaction $subtransaction
 * @property \App\Model\Entity\Portfolio $portfolio
 * @property \App\Model\Entity\Report $report
 */
class ExcludedTransaction extends Entity
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
        'sme_id' => true,
        'transaction_id' => true,
        'subtransaction_id' => true,
        'exclusion_date' => true,
        'excluded_transaction_amount' => true,
        'excluded_transaction_amount_eur' => true,
        'excluded_transaction_amount_curr' => true,
        'exclusion_type' => true,
        'coverage_implication' => true,
        'acceleration_flag' => true,
        'comments' => true,
        'portfolio_id' => true,
        'report_id' => true,
        'created' => true,
        'modified' => true,
        'sme' => true,
        'transaction' => true,
        'subtransaction' => true,
        'portfolio' => true,
        'report' => true,
    ];
}
