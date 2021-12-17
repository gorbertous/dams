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
            'title' => 'List', 
            'url' => ['controller' => 'Portfolio', 'action' => 'index'],
            'options' => ['class'=> 'breadcrumb-item']
        ],
        [
            'title' => 'New', 
            'url' => ['controller' => 'Portfolio', 'action' => 'add'],
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
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Portfolio'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="portfolio form content">
            <?= $this->Form->create($portfolio) ?>
            <fieldset>
                <legend><?= __('Add Portfolio') ?></legend>
                <?php
                    echo $this->Form->control('deal_name');
                    echo $this->Form->control('deal_business_key');
                    echo $this->Form->control('iqid');
                    echo $this->Form->control('mandate');
                    echo $this->Form->control('portfolio_name');
                    echo $this->Form->control('beneficiary_iqid');
                    echo $this->Form->control('beneficiary_name');
                    echo $this->Form->control('maxpv');
                    echo $this->Form->control('agreed_pv');
                    echo $this->Form->control('agreed_ga');
                    echo $this->Form->control('agreed_pv_rate');
                    echo $this->Form->control('actual_pev');
                    echo $this->Form->control('minpv');
                    echo $this->Form->control('reference_volume');
                    echo $this->Form->control('currency');
                    echo $this->Form->control('fx_rate_inclusion');
                    echo $this->Form->control('fx_rate_pdlr');
                    echo $this->Form->control('guarantee_amount');
                    echo $this->Form->control('signed_amount');
                    echo $this->Form->control('cap_amount');
                    echo $this->Form->control('effective_cap_amount');
                    echo $this->Form->control('available_cap_amount');
                    echo $this->Form->control('signature_date', ['empty' => true]);
                    echo $this->Form->control('availability_start', ['empty' => true]);
                    echo $this->Form->control('availability_end', ['empty' => true]);
                    echo $this->Form->control('end_reporting_date', ['empty' => true]);
                    echo $this->Form->control('guarantee_termination', ['empty' => true]);
                    echo $this->Form->control('recovery_rate');
                    echo $this->Form->control('call_time_to_pay');
                    echo $this->Form->control('call_time_unit');
                    echo $this->Form->control('loss_rate_trigger');
                    echo $this->Form->control('actual_pv');
                    echo $this->Form->control('apv_at_closure');
                    echo $this->Form->control('actual_gv');
                    echo $this->Form->control('default_amount');
                    echo $this->Form->control('country');
                    echo $this->Form->control('product_id');
                    echo $this->Form->control('status_portfolio');
                    echo $this->Form->control('closure_date', ['empty' => true]);
                    echo $this->Form->control('gs_deal_status');
                    echo $this->Form->control('owner');
                    echo $this->Form->control('max_trn_maturity');
                    echo $this->Form->control('interest_risk_sharing_rate');
                    echo $this->Form->control('pd_final_payment_date', ['empty' => true]);
                    echo $this->Form->control('pd_final_payment_notice');
                    echo $this->Form->control('pd_decl');
                    echo $this->Form->control('in_inclusion_final_date', ['empty' => true]);
                    echo $this->Form->control('in_decl');
                    echo $this->Form->control('capped');
                    echo $this->Form->control('management_fee_rate');
                    echo $this->Form->control('cofinancing_rate');
                    echo $this->Form->control('risk_sharing_rate');
                    echo $this->Form->control('guarantee_type');
                    echo $this->Form->control('effective_termination_date', ['empty' => true]);
                    echo $this->Form->control('inclusion_start_date', ['empty' => true]);
                    echo $this->Form->control('inclusion_end_date', ['empty' => true]);
                    echo $this->Form->control('modifications_expected');
                    echo $this->Form->control('m_files_link');
                    echo $this->Form->control('kyc_embargo');
                    echo $this->Form->control('sme._ids', ['options' => $sme]);
                    echo $this->Form->control('template._ids', ['options' => $template]);
                ?>
            </fieldset>
                        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-outline-secondary my-3']); ?>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
