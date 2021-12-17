<?php

declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * Invoice Entity
 *
 * @property int $invoice_id
 * @property int|null $portfolio_id
 * @property int|null $invoice_owner
 * @property \Cake\I18n\FrozenDate|null $invoice_date
 * @property \Cake\I18n\FrozenDate|null $due_date
 * @property \Cake\I18n\FrozenDate|null $expected_payment_date
 * @property \Cake\I18n\FrozenDate|null $accounting_payment_date
 * @property string|null $contract_currency
 * @property string|null $amount_curr
 * @property string|null $amount_eur
 * @property string|null $fx_rate
 * @property string|null $fx_rate_label
 * @property string|null $invoice_number
 * @property int|null $status_id
 * @property string|null $stage
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string|null $pkid
 *
 * @property \App\Model\Entity\Portfolio $portfolio
 * @property \App\Model\Entity\Status $status
 */
class Invoice extends Entity
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
        'portfolio_id'            => true,
        'invoice_owner'           => true,
        'invoice_date'            => true,
        'due_date'                => true,
        'expected_payment_date'   => true,
        'accounting_payment_date' => true,
        'contract_currency'       => true,
        'amount_curr'             => true,
        'amount_eur'              => true,
        'fx_rate'                 => true,
        'fx_rate_label'           => true,
        'invoice_number'          => true,
        'status_id'               => true,
        'stage'                   => true,
        'created'                 => true,
        'modified'                => true,
        'pkid'                    => true,
        'portfolio'               => true,
        'status'                  => true,
    ];

    protected function _getInvoiceActionLink()
    {
        switch ($this->status_id) {
            case 15:
                $href = "/damsv2/invoice/accounting/" . $this->invoice_id;
                $link1 = "<a href='$href'>" . $this->status->action . "</a>";
                $link = $link1;
                break;
            default://empty status action
                $link = "";
                break;
        }
        return $link;
    }

}
