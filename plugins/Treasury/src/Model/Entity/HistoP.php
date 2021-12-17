<?php
declare(strict_types=1);

namespace Treasury\Model\Entity;

use Cake\ORM\Entity;

/**
 * HistoP Entity
 *
 * @property int $histo_id
 * @property string|null $bu_ps
 * @property string|null $bu_gl
 * @property string|null $ledger_group
 * @property string|null $transaction_id
 * @property int|null $transaction_line
 * @property \Cake\I18n\FrozenDate|null $accounting_dt
 * @property string|null $account
 * @property string|null $deptid
 * @property string|null $product
 * @property string|null $pic_tiers
 * @property string|null $portefeuille
 * @property string|null $type_de_taux
 * @property string|null $idpgl
 * @property string|null $code_region
 * @property string|null $grp_produit
 * @property string|null $origine_fond
 * @property string|null $code_mandat
 * @property string|null $project_code
 * @property string|null $jrnl_ln_ref
 * @property string|null $foreign_currency
 * @property string|null $foreign_amount
 * @property string|null $line_descr
 * @property \Cake\I18n\FrozenDate|null $abd_date
 * @property string|null $trans_ref_num
 * @property int|null $tr_number
 * @property string|null $header_description
 * @property string|null $revision
 * @property string|null $book_type
 * @property string|null $eom_booking
 * @property \Cake\I18n\FrozenTime|null $created
 *
 * @property \Treasury\Model\Entity\Transaction $transaction
 */
class HistoP extends Entity
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
        'bu_ps' => true,
        'bu_gl' => true,
        'ledger_group' => true,
        'transaction_id' => true,
        'transaction_line' => true,
        'accounting_dt' => true,
        'account' => true,
        'deptid' => true,
        'product' => true,
        'pic_tiers' => true,
        'portefeuille' => true,
        'type_de_taux' => true,
        'idpgl' => true,
        'code_region' => true,
        'grp_produit' => true,
        'origine_fond' => true,
        'code_mandat' => true,
        'project_code' => true,
        'jrnl_ln_ref' => true,
        'foreign_currency' => true,
        'foreign_amount' => true,
        'line_descr' => true,
        'abd_date' => true,
        'trans_ref_num' => true,
        'tr_number' => true,
        'header_description' => true,
        'revision' => true,
        'book_type' => true,
        'eom_booking' => true,
        'created' => true,
        'transaction' => true,
    ];
}
