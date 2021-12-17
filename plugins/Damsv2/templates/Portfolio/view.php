<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Portfolio $portfolio
 */

$this->Breadcrumbs->add([
        [
            'title' => 'Home', 
            'url' => ['controller' => 'Home', 'action' => 'home'],
            'options' => ['class'=> 'breadcrumb-item']
        ],
        [
            'title' => 'Portfolios', 
            'url' => ['controller' => 'Portfolio', 'action' => 'index'],
            'options' => ['class'=> 'breadcrumb-item']
        ],
        [
            'title' => $portfolio->deal_name, 
            'url' => ['controller' => 'Portfolio', 'action' => 'view', $portfolio->portfolio_id],
            'options' => [
                'class' => 'breadcrumb-item active',
                'innerAttrs' => [
                    'class' => 'test-list-class',
                    'id' => 'the-port-crumb'
                ]
            ]
        ]
    ]);
?>
<div class="row">
    
    <div class="column-responsive column-80">
        <div class="portfolio view content">
            <h3><?= h($portfolio->deal_name) ?></h3>
            <table class="table table-striped">
                <tr>
                    <th><?= __('Deal Name') ?></th>
                    <td><?= h($portfolio->deal_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Deal Business Key') ?></th>
                    <td><?= h($portfolio->deal_business_key) ?></td>
                </tr>
                <tr>
                    <th><?= __('Iqid') ?></th>
                    <td><?= h($portfolio->iqid) ?></td>
                </tr>
                <tr>
                    <th><?= __('Mandate') ?></th>
                    <td><?= h($portfolio->mandate) ?></td>
                </tr>
                <tr>
                    <th><?= __('Portfolio Name') ?></th>
                    <td><?= h($portfolio->portfolio_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Beneficiary Iqid') ?></th>
                    <td><?= h($portfolio->beneficiary_iqid) ?></td>
                </tr>
                <tr>
                    <th><?= __('Beneficiary Name') ?></th>
                    <td><?= h($portfolio->beneficiary_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Currency') ?></th>
                    <td><?= h($portfolio->currency) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fx Rate Inclusion') ?></th>
                    <td><?= h($portfolio->fx_rate_inclusion) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fx Rate Pdlr') ?></th>
                    <td><?= h($portfolio->fx_rate_pdlr) ?></td>
                </tr>
                <tr>
                    <th><?= __('Call Time Unit') ?></th>
                    <td><?= h($portfolio->call_time_unit) ?></td>
                </tr>
                <tr>
                    <th><?= __('Country') ?></th>
                    <td><?= h($portfolio->country) ?></td>
                </tr>
                <tr>
                    <th><?= __('Status Portfolio') ?></th>
                    <td><?= h($portfolio->status_portfolio) ?></td>
                </tr>
                <tr>
                    <th><?= __('Gs Deal Status') ?></th>
                    <td><?= h($portfolio->gs_deal_status) ?></td>
                </tr>
                <tr>
                    <th><?= __('Capped') ?></th>
                    <td><?= h($portfolio->capped) ?></td>
                </tr>
                <tr>
                    <th><?= __('Guarantee Type') ?></th>
                    <td><?= h($portfolio->guarantee_type) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modifications Expected') ?></th>
                    <td><?= h($portfolio->modifications_expected) ?></td>
                </tr>
                <tr>
                    <th><?= __('M Files Link') ?></th>
                    <td><?= h($portfolio->m_files_link) ?></td>
                </tr>
                <tr>
                    <th><?= __('Kyc Embargo') ?></th>
                    <td><?= h($portfolio->kyc_embargo) ?></td>
                </tr>
                <tr>
                    <th><?= __('Portfolio Id') ?></th>
                    <td><?= $this->Number->format($portfolio->portfolio_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Maxpv') ?></th>
                    <td><?= $this->Number->format($portfolio->maxpv) ?></td>
                </tr>
                <tr>
                    <th><?= __('Agreed Pv') ?></th>
                    <td><?= $this->Number->format($portfolio->agreed_pv) ?></td>
                </tr>
                <tr>
                    <th><?= __('Agreed Ga') ?></th>
                    <td><?= $this->Number->format($portfolio->agreed_ga) ?></td>
                </tr>
                <tr>
                    <th><?= __('Agreed Pv Rate') ?></th>
                    <td><?= $this->Number->format($portfolio->agreed_pv_rate) ?></td>
                </tr>
                <tr>
                    <th><?= __('Actual Pev') ?></th>
                    <td><?= $this->Number->format($portfolio->actual_pev) ?></td>
                </tr>
                <tr>
                    <th><?= __('Minpv') ?></th>
                    <td><?= $this->Number->format($portfolio->minpv) ?></td>
                </tr>
                <tr>
                    <th><?= __('Reference Volume') ?></th>
                    <td><?= $this->Number->format($portfolio->reference_volume) ?></td>
                </tr>
                <tr>
                    <th><?= __('Guarantee Amount') ?></th>
                    <td><?= $this->Number->format($portfolio->guarantee_amount) ?></td>
                </tr>
                <tr>
                    <th><?= __('Signed Amount') ?></th>
                    <td><?= $this->Number->format($portfolio->signed_amount) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cap Amount') ?></th>
                    <td><?= $this->Number->format($portfolio->cap_amount) ?></td>
                </tr>
                <tr>
                    <th><?= __('Effective Cap Amount') ?></th>
                    <td><?= $this->Number->format($portfolio->effective_cap_amount) ?></td>
                </tr>
                <tr>
                    <th><?= __('Available Cap Amount') ?></th>
                    <td><?= $this->Number->format($portfolio->available_cap_amount) ?></td>
                </tr>
                <tr>
                    <th><?= __('Recovery Rate') ?></th>
                    <td><?= $this->Number->format($portfolio->recovery_rate) ?></td>
                </tr>
                <tr>
                    <th><?= __('Call Time To Pay') ?></th>
                    <td><?= $this->Number->format($portfolio->call_time_to_pay) ?></td>
                </tr>
                <tr>
                    <th><?= __('Loss Rate Trigger') ?></th>
                    <td><?= $this->Number->format($portfolio->loss_rate_trigger) ?></td>
                </tr>
                <tr>
                    <th><?= __('Actual Pv') ?></th>
                    <td><?= $this->Number->format($portfolio->actual_pv) ?></td>
                </tr>
                <tr>
                    <th><?= __('Apv At Closure') ?></th>
                    <td><?= $this->Number->format($portfolio->apv_at_closure) ?></td>
                </tr>
                <tr>
                    <th><?= __('Actual Gv') ?></th>
                    <td><?= $this->Number->format($portfolio->actual_gv) ?></td>
                </tr>
                <tr>
                    <th><?= __('Default Amount') ?></th>
                    <td><?= $this->Number->format($portfolio->default_amount) ?></td>
                </tr>
                <tr>
                    <th><?= __('Product Id') ?></th>
                    <td><?= $this->Number->format($portfolio->product_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Owner') ?></th>
                    <td><?= $this->Number->format($portfolio->owner) ?></td>
                </tr>
                <tr>
                    <th><?= __('Max Trn Maturity') ?></th>
                    <td><?= $this->Number->format($portfolio->max_trn_maturity) ?></td>
                </tr>
                <tr>
                    <th><?= __('Interest Risk Sharing Rate') ?></th>
                    <td><?= $this->Number->format($portfolio->interest_risk_sharing_rate) ?></td>
                </tr>
                <tr>
                    <th><?= __('Pd Final Payment Notice') ?></th>
                    <td><?= $this->Number->format($portfolio->pd_final_payment_notice) ?></td>
                </tr>
                <tr>
                    <th><?= __('Pd Decl') ?></th>
                    <td><?= $this->Number->format($portfolio->pd_decl) ?></td>
                </tr>
                <tr>
                    <th><?= __('In Decl') ?></th>
                    <td><?= $this->Number->format($portfolio->in_decl) ?></td>
                </tr>
                <tr>
                    <th><?= __('Management Fee Rate') ?></th>
                    <td><?= $this->Number->format($portfolio->management_fee_rate) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cofinancing Rate') ?></th>
                    <td><?= $this->Number->format($portfolio->cofinancing_rate) ?></td>
                </tr>
                <tr>
                    <th><?= __('Risk Sharing Rate') ?></th>
                    <td><?= $this->Number->format($portfolio->risk_sharing_rate) ?></td>
                </tr>
                <tr>
                    <th><?= __('Signature Date') ?></th>
                    <td><?= h($portfolio->signature_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Availability Start') ?></th>
                    <td><?= h($portfolio->availability_start) ?></td>
                </tr>
                <tr>
                    <th><?= __('Availability End') ?></th>
                    <td><?= h($portfolio->availability_end) ?></td>
                </tr>
                <tr>
                    <th><?= __('End Reporting Date') ?></th>
                    <td><?= h($portfolio->end_reporting_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Guarantee Termination') ?></th>
                    <td><?= h($portfolio->guarantee_termination) ?></td>
                </tr>
                <tr>
                    <th><?= __('Closure Date') ?></th>
                    <td><?= h($portfolio->closure_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($portfolio->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($portfolio->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Pd Final Payment Date') ?></th>
                    <td><?= h($portfolio->pd_final_payment_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('In Inclusion Final Date') ?></th>
                    <td><?= h($portfolio->in_inclusion_final_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Effective Termination Date') ?></th>
                    <td><?= h($portfolio->effective_termination_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Inclusion Start Date') ?></th>
                    <td><?= h($portfolio->inclusion_start_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Inclusion End Date') ?></th>
                    <td><?= h($portfolio->inclusion_end_date) ?></td>
                </tr>
            </table>
          
        </div>
    </div>
</div>
