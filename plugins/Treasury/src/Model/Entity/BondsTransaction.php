<?php
declare(strict_types=1);

namespace Treasury\Model\Entity;

use Cake\ORM\Entity;

/**
 * BondsTransaction Entity
 *
 * @property int $tr_number
 * @property int|null $mandate_id
 * @property int|null $cmp_id
 * @property int|null $cpty_id
 * @property string|null $parent_id
 * @property int|null $instr_num
 * @property int|null $bond_id
 * @property string $tr_type
 * @property string|null $tr_state
 * @property string|null $currency
 * @property string|null $nominal_amount
 * @property string|null $coupon_payment_amount
 * @property string|null $purchase_price
 * @property string|null $purchase_amount
 * @property string|null $accrued_coupon_at_purchase
 * @property string|null $total_purchase_amount
 * @property \Cake\I18n\FrozenDate|null $trade_date
 * @property \Cake\I18n\FrozenDate|null $settlement_date
 * @property string|null $yield_to_maturity
 * @property string|null $accrued_coupon_eom
 * @property string|null $accrued_tax_eom
 * @property string|null $total_coupon
 * @property string|null $total_tax
 * @property string|null $reference_rate
 * @property string|null $spread_bp
 * @property string|null $benchmark
 * @property string|null $comment
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \Treasury\Model\Entity\Mandate $mandate
 * @property \Treasury\Model\Entity\Cmp $cmp
 * @property \Treasury\Model\Entity\Cpty $cpty
 * @property \Treasury\Model\Entity\ParentBondsTransaction $parent_bonds_transaction
 * @property \Treasury\Model\Entity\Bond $bond
 * @property \Treasury\Model\Entity\ChildBondsTransaction[] $child_bonds_transactions
 */
class BondsTransaction extends Entity
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
        'mandate_id' => true,
        'cmp_id' => true,
        'cpty_id' => true,
        'parent_id' => true,
        'instr_num' => true,
        'bond_id' => true,
        'tr_type' => true,
        'tr_state' => true,
        'currency' => true,
        'nominal_amount' => true,
        'coupon_payment_amount' => true,
        'purchase_price' => true,
        'purchase_amount' => true,
        'accrued_coupon_at_purchase' => true,
        'total_purchase_amount' => true,
        'trade_date' => true,
        'settlement_date' => true,
        'yield_to_maturity' => true,
        'accrued_coupon_eom' => true,
        'accrued_tax_eom' => true,
        'total_coupon' => true,
        'total_tax' => true,
        'reference_rate' => true,
        'spread_bp' => true,
        'benchmark' => true,
        'comment' => true,
        'created' => true,
        'modified' => true,
        'mandate' => true,
        'cmp' => true,
        'cpty' => true,
        'parent_bonds_transaction' => true,
        'bond' => true,
        'child_bonds_transactions' => true,
    ];
}
