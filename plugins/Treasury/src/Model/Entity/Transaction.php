<?php
declare(strict_types=1);

namespace Treasury\Model\Entity;

use Cake\ORM\Entity;

/**
 * Transaction Entity
 *
 * @property int $tr_number
 * @property string|null $tr_type
 * @property string|null $tr_state
 * @property int|null $source_group
 * @property int|null $reinv_group
 * @property int|null $original_id
 * @property int|null $parent_id
 * @property int|null $linked_trn
 * @property string|null $external_ref
 * @property string|null $amount
 * @property \Cake\I18n\FrozenDate|null $commencement_date
 * @property \Cake\I18n\FrozenDate|null $maturity_date
 * @property \Cake\I18n\FrozenDate|null $indicative_maturity_date
 * @property string|null $depo_term
 * @property string|null $interest_rate
 * @property string|null $total_interest
 * @property string|null $tax_amount
 * @property string|null $depo_type
 * @property string|null $depo_renew
 * @property string|null $rate_type
 * @property string|null $date_basis
 * @property int|null $mandate_ID
 * @property int|null $cmp_ID
 * @property string|null $scheme
 * @property string|null $accountA_IBAN
 * @property string|null $accountB_IBAN
 * @property int|null $instr_num
 * @property int|null $cpty_id
 * @property string|null $ps_account
 * @property string|null $booking_status
 * @property string $eom_booking
 * @property string|null $accrued_interst
 * @property string|null $accrued_tax
 * @property \Cake\I18n\FrozenDate|null $fixing_date
 * @property string|null $eom_interest
 * @property string|null $eom_tax
 * @property int|null $tax_ID
 * @property string|null $source_fund
 * @property string|null $comment
 * @property string|null $reference_rate
 * @property string|null $spread_bp
 * @property string|null $benchmark
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \Treasury\Model\Entity\Original $original
 * @property \Treasury\Model\Entity\ParentTransaction $parent_transaction
 * @property \Treasury\Model\Entity\Cpty $cpty
 * @property \Treasury\Model\Entity\HistoP[] $histo_ps
 * @property \Treasury\Model\Entity\ChildTransaction[] $child_transactions
 * @property \Treasury\Model\Entity\Bond[] $bonds
 * @property \Treasury\Model\Entity\LimitBreach[] $limit_breaches
 */
class Transaction extends Entity
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
        'tr_type' => true,
        'tr_state' => true,
        'source_group' => true,
        'reinv_group' => true,
        'original_id' => true,
        'parent_id' => true,
        'linked_trn' => true,
        'external_ref' => true,
        'amount' => true,
        'commencement_date' => true,
        'maturity_date' => true,
        'indicative_maturity_date' => true,
        'depo_term' => true,
        'interest_rate' => true,
        'total_interest' => true,
        'tax_amount' => true,
        'depo_type' => true,
        'depo_renew' => true,
        'rate_type' => true,
        'date_basis' => true,
        'mandate_ID' => true,
        'cmp_ID' => true,
        'scheme' => true,
        'accountA_IBAN' => true,
        'accountB_IBAN' => true,
        'instr_num' => true,
        'cpty_id' => true,
        'ps_account' => true,
        'booking_status' => true,
        'eom_booking' => true,
        'accrued_interst' => true,
        'accrued_tax' => true,
        'fixing_date' => true,
        'eom_interest' => true,
        'eom_tax' => true,
        'tax_ID' => true,
        'source_fund' => true,
        'comment' => true,
        'reference_rate' => true,
        'spread_bp' => true,
        'benchmark' => true,
        'created' => true,
        'modified' => true,
        'original' => true,
        'parent_transaction' => true,
        'cpty' => true,
        'histo_ps' => true,
        'child_transactions' => true,
        'bonds' => true,
        'limit_breaches' => true,
    ];
}
