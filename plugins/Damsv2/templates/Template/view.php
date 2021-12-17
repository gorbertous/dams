<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Template $template
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Template'), ['action' => 'edit', $template->template_id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Template'), ['action' => 'delete', $template->template_id], ['confirm' => __('Are you sure you want to delete # {0}?', $template->template_id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Template'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Template'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="template view content">
            <h3><?= h($template->name) ?></h3>
            <table class="table table-striped">
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($template->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Template Id') ?></th>
                    <td><?= $this->Number->format($template->template_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Template Type Id') ?></th>
                    <td><?= $this->Number->format($template->template_type_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Callback Id') ?></th>
                    <td><?= $this->Number->format($template->callback_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($template->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($template->modified) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Portfolio') ?></h4>
                <?php if (!empty($template->portfolio)) : ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th><?= __('Portfolio Id') ?></th>
                            <th><?= __('Deal Name') ?></th>
                            <th><?= __('Deal Business Key') ?></th>
                            <th><?= __('Iqid') ?></th>
                            <th><?= __('Mandate') ?></th>
                            <th><?= __('Portfolio Name') ?></th>
                            <th><?= __('Beneficiary Iqid') ?></th>
                            <th><?= __('Beneficiary Name') ?></th>
                            <th><?= __('Maxpv') ?></th>
                            <th><?= __('Agreed Pv') ?></th>
                            <th><?= __('Agreed Ga') ?></th>
                            <th><?= __('Agreed Pv Rate') ?></th>
                            <th><?= __('Actual Pev') ?></th>
                            <th><?= __('Minpv') ?></th>
                            <th><?= __('Reference Volume') ?></th>
                            <th><?= __('Currency') ?></th>
                            <th><?= __('Fx Rate Inclusion') ?></th>
                            <th><?= __('Fx Rate Pdlr') ?></th>
                            <th><?= __('Guarantee Amount') ?></th>
                            <th><?= __('Signed Amount') ?></th>
                            <th><?= __('Cap Amount') ?></th>
                            <th><?= __('Effective Cap Amount') ?></th>
                            <th><?= __('Available Cap Amount') ?></th>
                            <th><?= __('Signature Date') ?></th>
                            <th><?= __('Availability Start') ?></th>
                            <th><?= __('Availability End') ?></th>
                            <th><?= __('End Reporting Date') ?></th>
                            <th><?= __('Guarantee Termination') ?></th>
                            <th><?= __('Recovery Rate') ?></th>
                            <th><?= __('Call Time To Pay') ?></th>
                            <th><?= __('Call Time Unit') ?></th>
                            <th><?= __('Loss Rate Trigger') ?></th>
                            <th><?= __('Actual Pv') ?></th>
                            <th><?= __('Apv At Closure') ?></th>
                            <th><?= __('Actual Gv') ?></th>
                            <th><?= __('Default Amount') ?></th>
                            <th><?= __('Country') ?></th>
                            <th><?= __('Product Id') ?></th>
                            <th><?= __('Status Portfolio') ?></th>
                            <th><?= __('Closure Date') ?></th>
                            <th><?= __('Gs Deal Status') ?></th>
                            <th><?= __('Owner') ?></th>
                            <th><?= __('Max Trn Maturity') ?></th>
                            <th><?= __('Interest Risk Sharing Rate') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Pd Final Payment Date') ?></th>
                            <th><?= __('Pd Final Payment Notice') ?></th>
                            <th><?= __('Pd Decl') ?></th>
                            <th><?= __('In Inclusion Final Date') ?></th>
                            <th><?= __('In Decl') ?></th>
                            <th><?= __('Capped') ?></th>
                            <th><?= __('Management Fee Rate') ?></th>
                            <th><?= __('Cofinancing Rate') ?></th>
                            <th><?= __('Risk Sharing Rate') ?></th>
                            <th><?= __('Guarantee Type') ?></th>
                            <th><?= __('Effective Termination Date') ?></th>
                            <th><?= __('Inclusion Start Date') ?></th>
                            <th><?= __('Inclusion End Date') ?></th>
                            <th><?= __('Modifications Expected') ?></th>
                            <th><?= __('M Files Link') ?></th>
                            <th><?= __('Kyc Embargo') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($template->portfolio as $portfolio) : ?>
                        <tr>
                            <td><?= h($portfolio->portfolio_id) ?></td>
                            <td><?= h($portfolio->deal_name) ?></td>
                            <td><?= h($portfolio->deal_business_key) ?></td>
                            <td><?= h($portfolio->iqid) ?></td>
                            <td><?= h($portfolio->mandate) ?></td>
                            <td><?= h($portfolio->portfolio_name) ?></td>
                            <td><?= h($portfolio->beneficiary_iqid) ?></td>
                            <td><?= h($portfolio->beneficiary_name) ?></td>
                            <td><?= h($portfolio->maxpv) ?></td>
                            <td><?= h($portfolio->agreed_pv) ?></td>
                            <td><?= h($portfolio->agreed_ga) ?></td>
                            <td><?= h($portfolio->agreed_pv_rate) ?></td>
                            <td><?= h($portfolio->actual_pev) ?></td>
                            <td><?= h($portfolio->minpv) ?></td>
                            <td><?= h($portfolio->reference_volume) ?></td>
                            <td><?= h($portfolio->currency) ?></td>
                            <td><?= h($portfolio->fx_rate_inclusion) ?></td>
                            <td><?= h($portfolio->fx_rate_pdlr) ?></td>
                            <td><?= h($portfolio->guarantee_amount) ?></td>
                            <td><?= h($portfolio->signed_amount) ?></td>
                            <td><?= h($portfolio->cap_amount) ?></td>
                            <td><?= h($portfolio->effective_cap_amount) ?></td>
                            <td><?= h($portfolio->available_cap_amount) ?></td>
                            <td><?= h($portfolio->signature_date) ?></td>
                            <td><?= h($portfolio->availability_start) ?></td>
                            <td><?= h($portfolio->availability_end) ?></td>
                            <td><?= h($portfolio->end_reporting_date) ?></td>
                            <td><?= h($portfolio->guarantee_termination) ?></td>
                            <td><?= h($portfolio->recovery_rate) ?></td>
                            <td><?= h($portfolio->call_time_to_pay) ?></td>
                            <td><?= h($portfolio->call_time_unit) ?></td>
                            <td><?= h($portfolio->loss_rate_trigger) ?></td>
                            <td><?= h($portfolio->actual_pv) ?></td>
                            <td><?= h($portfolio->apv_at_closure) ?></td>
                            <td><?= h($portfolio->actual_gv) ?></td>
                            <td><?= h($portfolio->default_amount) ?></td>
                            <td><?= h($portfolio->country) ?></td>
                            <td><?= h($portfolio->product_id) ?></td>
                            <td><?= h($portfolio->status_portfolio) ?></td>
                            <td><?= h($portfolio->closure_date) ?></td>
                            <td><?= h($portfolio->gs_deal_status) ?></td>
                            <td><?= h($portfolio->owner) ?></td>
                            <td><?= h($portfolio->max_trn_maturity) ?></td>
                            <td><?= h($portfolio->interest_risk_sharing_rate) ?></td>
                            <td><?= h($portfolio->created) ?></td>
                            <td><?= h($portfolio->modified) ?></td>
                            <td><?= h($portfolio->pd_final_payment_date) ?></td>
                            <td><?= h($portfolio->pd_final_payment_notice) ?></td>
                            <td><?= h($portfolio->pd_decl) ?></td>
                            <td><?= h($portfolio->in_inclusion_final_date) ?></td>
                            <td><?= h($portfolio->in_decl) ?></td>
                            <td><?= h($portfolio->capped) ?></td>
                            <td><?= h($portfolio->management_fee_rate) ?></td>
                            <td><?= h($portfolio->cofinancing_rate) ?></td>
                            <td><?= h($portfolio->risk_sharing_rate) ?></td>
                            <td><?= h($portfolio->guarantee_type) ?></td>
                            <td><?= h($portfolio->effective_termination_date) ?></td>
                            <td><?= h($portfolio->inclusion_start_date) ?></td>
                            <td><?= h($portfolio->inclusion_end_date) ?></td>
                            <td><?= h($portfolio->modifications_expected) ?></td>
                            <td><?= h($portfolio->m_files_link) ?></td>
                            <td><?= h($portfolio->kyc_embargo) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Portfolio', 'action' => 'view', $portfolio->portfolio_id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Portfolio', 'action' => 'edit', $portfolio->portfolio_id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Portfolio', 'action' => 'delete', $portfolio->portfolio_id], ['confirm' => __('Are you sure you want to delete # {0}?', $portfolio->portfolio_id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
