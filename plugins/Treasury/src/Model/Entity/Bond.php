<?php
declare(strict_types=1);

namespace Treasury\Model\Entity;

use Cake\ORM\Entity;

/**
 * Bond Entity
 *
 * @property int $bond_id
 * @property string $ISIN
 * @property string|null $state
 * @property string|null $currency
 * @property string|null $issuer
 * @property \Cake\I18n\FrozenDate|null $issue_date
 * @property \Cake\I18n\FrozenDate|null $first_coupon_accrual_date
 * @property \Cake\I18n\FrozenDate|null $first_coupon_payment_date
 * @property \Cake\I18n\FrozenDate|null $maturity_date
 * @property string|null $coupon_rate
 * @property string|null $coupon_frequency
 * @property string|null $date_basis
 * @property string|null $date_convention
 * @property string|null $tax_rate
 * @property string|null $country
 * @property string|null $issue_size
 * @property bool|null $covered
 * @property bool|null $secured
 * @property string|null $seniority
 * @property string|null $guarantor
 * @property bool|null $structured
 * @property string|null $issuer_type
 * @property string|null $issue_rating_STP
 * @property string|null $issue_rating_MDY
 * @property string|null $issue_rating_FIT
 * @property string|null $retained_rating
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \Treasury\Model\Entity\Bond[] $bonds
 * @property \Treasury\Model\Entity\CouponSchedule[] $coupon_schedule
 * @property \Treasury\Model\Entity\Transaction[] $transactions
 */
class Bond extends Entity
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
        'ISIN' => true,
        'state' => true,
        'currency' => true,
        'issuer' => true,
        'issue_date' => true,
        'first_coupon_accrual_date' => true,
        'first_coupon_payment_date' => true,
        'maturity_date' => true,
        'coupon_rate' => true,
        'coupon_frequency' => true,
        'date_basis' => true,
        'date_convention' => true,
        'tax_rate' => true,
        'country' => true,
        'issue_size' => true,
        'covered' => true,
        'secured' => true,
        'seniority' => true,
        'guarantor' => true,
        'structured' => true,
        'issuer_type' => true,
        'issue_rating_STP' => true,
        'issue_rating_MDY' => true,
        'issue_rating_FIT' => true,
        'retained_rating' => true,
        'created' => true,
        'modified' => true,
        'bonds' => true,
        'coupon_schedule' => true,
        'transactions' => true,
    ];
}
