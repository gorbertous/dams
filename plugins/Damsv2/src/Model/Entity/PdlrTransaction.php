<?php
declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * PdlrTransaction Entity
 *
 * @property int $pdlr_id
 * @property int|null $parent_pdlr_id
 * @property int|null $sme_id
 * @property int|null $transaction_id
 * @property int|null $subtransaction_id
 * @property int|null $portfolio_id
 * @property int|null $report_id
 * @property int|null $parent_report_id
 * @property string|null $default_type
 * @property string|null $default_reason
 * @property string|null $default_flag
 * @property \Cake\I18n\FrozenDate|null $default_date
 * @property string|null $currency
 * @property float|null $fx_rate
 * @property float|null $principal_loss_amount
 * @property float|null $principal_loss_amount_eur
 * @property float|null $principal_loss_amount_curr
 * @property float|null $unpaid_interest
 * @property float|null $unpaid_interest_eur
 * @property float|null $unpaid_interest_curr
 * @property float|null $permit_add_inter_amount
 * @property float|null $permit_add_inter_amount_eur
 * @property float|null $permit_add_inter_amount_curr
 * @property float|null $other_costs
 * @property float|null $other_costs_eur
 * @property float|null $other_costs_curr
 * @property float|null $total_loss
 * @property float|null $total_loss_eur
 * @property float|null $total_loss_curr
 * @property \Cake\I18n\FrozenDate|null $recovery_date
 * @property float|null $recovery_amount
 * @property float|null $recovery_amount_eur
 * @property float|null $recovery_amount_curr
 * @property float|null $total_interest
 * @property float|null $total_interest_eur
 * @property float|null $total_interest_curr
 * @property float|null $eif_due_amount
 * @property float|null $eif_due_amount_eur
 * @property float|null $eif_due_amount_curr
 * @property \Cake\I18n\FrozenDate|null $fi_guarantee_call_date
 * @property \Cake\I18n\FrozenDate|null $fi_payment_date
 * @property float|null $fi_paid_amount
 * @property float|null $fi_paid_amount_eur
 * @property float|null $fi_paid_amount_curr
 * @property string|null $comments
 * @property \Cake\I18n\FrozenDate|null $receive_date
 * @property \Cake\I18n\FrozenDate|null $due_date
 * @property \Cake\I18n\FrozenDate|null $value_date
 * @property string|null $status
 * @property string|null $waiver
 * @property string|null $report_type
 * @property int|null $included_frsp_id
 * @property float|null $interest_repaid_curr
 * @property float|null $interest_repaid_eur
 * @property float|null $interest_repaid
 * @property string|null $error_message
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string|null $pkid
 * @property string|null $sampled
 * @property \Cake\I18n\FrozenDate|null $sampling_date
 * @property int|null $sampled_month
 * @property int|null $sampled_year
 * @property \Cake\I18n\FrozenDate|null $document_request_date
 * @property \Cake\I18n\FrozenDate|null $document_receive_date
 * @property \Cake\I18n\FrozenDate|null $sampling_closing_date
 * @property string|null $sampling_finding
 * @property float|null $sample_impact_eur
 * @property string|null $sample_comment
 *
 * @property \App\Model\Entity\ParentPdlr $parent_pdlr
 * @property \App\Model\Entity\Sme $sme
 * @property \App\Model\Entity\Transaction $transaction
 * @property \App\Model\Entity\Subtransaction $subtransaction
 * @property \App\Model\Entity\Portfolio $portfolio
 * @property \App\Model\Entity\Report $report
 * @property \App\Model\Entity\ParentReport $parent_report
 * @property \App\Model\Entity\IncludedFrsp $included_frsp
 */
class PdlrTransaction extends Entity
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
        'parent_pdlr_id' => true,
        'sme_id' => true,
        'transaction_id' => true,
        'subtransaction_id' => true,
        'portfolio_id' => true,
        'report_id' => true,
        'parent_report_id' => true,
        'default_type' => true,
        'default_reason' => true,
        'default_flag' => true,
        'default_date' => true,
        'currency' => true,
        'fx_rate' => true,
        'principal_loss_amount' => true,
        'principal_loss_amount_eur' => true,
        'principal_loss_amount_curr' => true,
        'unpaid_interest' => true,
        'unpaid_interest_eur' => true,
        'unpaid_interest_curr' => true,
        'permit_add_inter_amount' => true,
        'permit_add_inter_amount_eur' => true,
        'permit_add_inter_amount_curr' => true,
        'other_costs' => true,
        'other_costs_eur' => true,
        'other_costs_curr' => true,
        'total_loss' => true,
        'total_loss_eur' => true,
        'total_loss_curr' => true,
        'recovery_date' => true,
        'recovery_amount' => true,
        'recovery_amount_eur' => true,
        'recovery_amount_curr' => true,
        'total_interest' => true,
        'total_interest_eur' => true,
        'total_interest_curr' => true,
        'eif_due_amount' => true,
        'eif_due_amount_eur' => true,
        'eif_due_amount_curr' => true,
        'fi_guarantee_call_date' => true,
        'fi_payment_date' => true,
        'fi_paid_amount' => true,
        'fi_paid_amount_eur' => true,
        'fi_paid_amount_curr' => true,
        'comments' => true,
        'receive_date' => true,
        'due_date' => true,
        'value_date' => true,
        'status' => true,
        'waiver' => true,
        'report_type' => true,
        'included_frsp_id' => true,
        'interest_repaid_curr' => true,
        'interest_repaid_eur' => true,
        'interest_repaid' => true,
        'error_message' => true,
        'created' => true,
        'modified' => true,
        'pkid' => true,
        'sampled' => true,
        'sampling_date' => true,
        'sampled_month' => true,
        'sampled_year' => true,
        'document_request_date' => true,
        'document_receive_date' => true,
        'sampling_closing_date' => true,
        'sampling_finding' => true,
        'sample_impact_eur' => true,
        'sample_comment' => true,
        'parent_pdlr' => true,
        'sme' => true,
        'transaction' => true,
        'subtransaction' => true,
        'portfolio' => true,
        'report' => true,
        'parent_report' => true,
        'included_frsp' => true,
    ];
}
