<?php
declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * Guarantee Entity
 *
 * @property int $guarantee_id
 * @property int|null $transaction_id
 * @property int|null $portfolio_id
 * @property int|null $sme_id
 * @property string|null $transaction_reference
 * @property string|null $fiscal_number
 * @property int|null $report_id
 * @property float|null $fi_guarantee_amount
 * @property float|null $fi_guarantee_amount_eur
 * @property float|null $fi_guarantee_amount_curr
 * @property float|null $fi_guarantee_rate
 * @property \Cake\I18n\FrozenDate|null $fi_guarantee_signature_date
 * @property \Cake\I18n\FrozenDate|null $fi_guarantee_maturity_date
 * @property string|null $subintermediary
 * @property string|null $guarantee_comments
 * @property string|null $error_message
 * @property string|null $subintermediary_address
 * @property string|null $subintermediary_postcode
 * @property string|null $subintermediary_place
 * @property string|null $subintermediary_type
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Transaction $transaction
 * @property \App\Model\Entity\Portfolio $portfolio
 * @property \App\Model\Entity\Sme $sme
 * @property \App\Model\Entity\Report $report
 */
class Guarantee extends Entity
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
        'portfolio_id' => true,
        'sme_id' => true,
        'transaction_reference' => true,
        'fiscal_number' => true,
        'report_id' => true,
        'fi_guarantee_amount' => true,
        'fi_guarantee_amount_eur' => true,
        'fi_guarantee_amount_curr' => true,
        'fi_guarantee_rate' => true,
        'fi_guarantee_signature_date' => true,
        'fi_guarantee_maturity_date' => true,
        'subintermediary' => true,
        'guarantee_comments' => true,
        'error_message' => true,
        'subintermediary_address' => true,
        'subintermediary_postcode' => true,
        'subintermediary_place' => true,
        'subintermediary_type' => true,
        'created' => true,
        'modified' => true,
        'transaction' => true,
        'portfolio' => true,
        'sme' => true,
        'report' => true,
    ];
}
