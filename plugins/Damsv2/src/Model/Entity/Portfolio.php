<?php

declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Portfolio Entity
 *
 * @property int $portfolio_id
 * @property string|null $deal_name
 * @property string|null $deal_business_key
 * @property string|null $iqid
 * @property string $mandate
 * @property string|null $portfolio_name
 * @property string|null $beneficiary_iqid
 * @property string|null $beneficiary_name
 * @property float|null $maxpv
 * @property float|null $agreed_pv
 * @property float|null $agreed_ga
 * @property float|null $agreed_pv_rate
 * @property float|null $actual_pev
 * @property float|null $minpv
 * @property float|null $reference_volume
 * @property string|null $currency
 * @property string|null $fx_rate_inclusion
 * @property string|null $fx_rate_pdlr
 * @property float|null $guarantee_amount
 * @property float|null $signed_amount
 * @property float|null $cap_amount
 * @property float|null $effective_cap_amount
 * @property float|null $available_cap_amount
 * @property \Cake\I18n\FrozenDate|null $signature_date
 * @property \Cake\I18n\FrozenDate|null $availability_start
 * @property \Cake\I18n\FrozenDate|null $availability_end
 * @property \Cake\I18n\FrozenDate|null $end_reporting_date
 * @property \Cake\I18n\FrozenDate|null $guarantee_termination
 * @property float|null $recovery_rate
 * @property float|null $call_time_to_pay
 * @property string|null $call_time_unit
 * @property float|null $loss_rate_trigger
 * @property float|null $actual_pv
 * @property float|null $apv_at_closure
 * @property float|null $actual_gv
 * @property float|null $default_amount
 * @property string $country
 * @property int $product_id
 * @property string $status_portfolio
 * @property \Cake\I18n\FrozenDate|null $closure_date
 * @property string|null $gs_deal_status
 * @property int $owner
 * @property int|null $max_trn_maturity
 * @property float|null $interest_risk_sharing_rate
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenDate|null $pd_final_payment_date
 * @property int|null $pd_final_payment_notice
 * @property int|null $pd_decl
 * @property \Cake\I18n\FrozenDate|null $in_inclusion_final_date
 * @property int|null $in_decl
 * @property string $capped
 * @property float|null $management_fee_rate
 * @property float|null $cofinancing_rate
 * @property float|null $risk_sharing_rate
 * @property string|null $guarantee_type
 * @property \Cake\I18n\FrozenDate|null $effective_termination_date
 * @property \Cake\I18n\FrozenDate|null $inclusion_start_date
 * @property \Cake\I18n\FrozenDate|null $inclusion_end_date
 * @property string|null $modifications_expected
 * @property string|null $m_files_link
 * @property string|null $kyc_embargo
 *
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\Sme[] $sme
 * @property \App\Model\Entity\Template[] $template
 */
class Portfolio extends Entity
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
        'deal_name'                  => true,
        'deal_business_key'          => true,
        'iqid'                       => true,
        'mandate'                    => true,
        'portfolio_name'             => true,
        'beneficiary_iqid'           => true,
        'beneficiary_name'           => true,
        'maxpv'                      => true,
        'agreed_pv'                  => true,
        'agreed_ga'                  => true,
        'agreed_pv_rate'             => true,
        'actual_pev'                 => true,
        'minpv'                      => true,
        'reference_volume'           => true,
        'currency'                   => true,
        'fx_rate_inclusion'          => true,
        'fx_rate_pdlr'               => true,
        'guarantee_amount'           => true,
        'signed_amount'              => true,
        'cap_amount'                 => true,
        'effective_cap_amount'       => true,
        'available_cap_amount'       => true,
        'signature_date'             => true,
        'availability_start'         => true,
        'availability_end'           => true,
        'end_reporting_date'         => true,
        'guarantee_termination'      => true,
        'recovery_rate'              => true,
        'call_time_to_pay'           => true,
        'call_time_unit'             => true,
        'loss_rate_trigger'          => true,
        'actual_pv'                  => true,
        'apv_at_closure'             => true,
        'actual_gv'                  => true,
        'default_amount'             => true,
        'country'                    => true,
        'product_id'                 => true,
        'status_portfolio'           => true,
        'closure_date'               => true,
        'gs_deal_status'             => true,
        'owner'                      => true,
        'max_trn_maturity'           => true,
        'interest_risk_sharing_rate' => true,
        'created'                    => true,
        'modified'                   => true,
        'pd_final_payment_date'      => true,
        'pd_final_payment_notice'    => true,
        'pd_decl'                    => true,
        'in_inclusion_final_date'    => true,
        'in_decl'                    => true,
        'capped'                     => true,
        'management_fee_rate'        => true,
        'cofinancing_rate'           => true,
        'risk_sharing_rate'          => true,
        'guarantee_type'             => true,
        'effective_termination_date' => true,
        'inclusion_start_date'       => true,
        'inclusion_end_date'         => true,
        'modifications_expected'     => true,
        'm_files_link'               => true,
        'kyc_embargo'                => true,
        'product'                    => true,
        'sme'                        => true,
        'template'                   => true,
    ];

    public function isEditable()
    {

        $Report = ClassRegistry::init('Damsv2.Report');

        $reports = $Report->find('all', [
            'contain'    => ['Template'],
            'conditions' => [
                'Report.portfolio_id'       => $this->portfolio_id,
                'Template.template_type_id' => 1
            ]
        ]);

        foreach ($reports as $report) {
            if ($report->status_id == 4) {
                return false;
            }
        }

        return true;
    }

    public function isEditableDraft()
    {
        $Report = TableRegistry::get('Damsv2.Report');

        $reports = $Report->find('all', [
            'contain'    => ['Template'],
            'conditions' => [
                'Report.portfolio_id'       => $this->portfolio_id,
                'Template.template_type_id' => 1
            ]
        ]);

        foreach ($reports as $report) {
            if ($report->status_id == 23) {
                return false;
            }
        }

        return true;
    }

}
