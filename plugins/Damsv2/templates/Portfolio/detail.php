<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Portfolio $portfolio
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Portfolios',
        'url'     => ['controller' => 'Portfolio', 'action' => 'index'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => $portfolio->deal_name,
        'url'     => ['controller' => 'Portfolio', 'action' => 'detail', $portfolio->portfolio_id],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-port-crumb'
            ]
        ]
    ]
]);


?>

<h2>Key Contract Information</h2>

<?php
if($perm->hasRead(array('controller' => 'Portfolio', 'action' => 'statistics'))) echo '<a class="btn btn-info my-3" id="view_statistics">View Statistics</a>';
if($perm->hasRead(array('controller' => 'Portfolio', 'action' => 'detail', 'filter' => 'store'))) echo '<a class="btn btn-info ml-2 my-3" id="store_dico_values">Store dictionary translations</a>';
if($perm->hasRead(array('controller' => 'Template', 'action' => 'dashboard'))) echo '<a class="btn btn-info ml-2 my-3" id="get_templates">Templates</a>';
if($perm->hasRead(array('controller' => 'SmePortfolio', 'action' => 'index'))) echo '<a class="btn btn-info ml-2 my-3" id="get_smes">SMEs</a>';
if($perm->hasRead(array('controller' => 'Transactions', 'action' => 'index'))) echo '<a class="btn btn-info ml-2 my-3" id="get_transactions">Transactions</a>';
?>
<table class="table-responsive">
    <table class="table table">
        <thead class="thead-light">
            <tr>
                <th>Contract name</th>
                <th>Portfolio name</th>
                <th>Portfolio ID</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= h($portfolio->deal_name) ?></td>
                <td><?= h($portfolio->portfolio_name) ?></td>
                <td><?= $portfolio->portfolio_id ?></td>
            </tr>
        </tbody>

        <thead class="thead-light">
            <tr>
                <th>Mandate agreement</th>
                <th>Financial product</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= h($portfolio->mandate) ?></td>
                <td><?= h($portfolio->guarantee_type) ?></td>
            </tr>
        </tbody>

        <thead class="thead-light">
            <tr>
                <th>Financial Intermediary</th>
                <th>Country</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= h($portfolio->beneficiary_name) ?></td>
                <td><?= h($portfolio->country) ?></td>
            </tr>
        </tbody>

        <thead class="thead-light">
            <tr>
                <th>Portfolio Currency</th>
                <th>FX Rate Type</th>
                <th>Fixed FX Rate</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= h($portfolio->currency) ?></td>
                <td><?= h($portfolio->fx_rate_inclusion) ?></td>
                <td>
                    <?php
                    if (!empty($fixed_fx_rate)) {
                        '<ul>';
                        foreach ($fixed_fx_rate as $rate) {
                            $formatted_ob_value = !empty($rate->obs_value) ? $this->Number->precision($rate->obs_value, 2) : '';
                            echo "<li>" . $rate->currency . " : " . $formatted_ob_value . "</li>";
                        }
                        '</ul>';
                    }
                    ?>
                </td>
            </tr>
        </tbody>

        <thead class="thead-light">
            <tr>
                <th>Portfolio Status</th>
                <th>Closure Date</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>

                    <?php
                    //if (($portfolio->status_portfolio == 'CLOSED') | ($profile != 'dams_team')) {
                    if (($portfolio->status_portfolio == 'CLOSED')) {
                        echo $this->Form->create(null, ['class' => 'form-inline']);
                        echo $this->Form->select('Portfolio.status_portfolio',
                                ['CLOSED'],
                                [
                                    'class' => 'form-control mr-2',                                    
                                    'value' => $portfolio->status_portfolio,
                                    'id'    => 'periodqid'
                                ]
                        );
                        echo $this->Form->button('<i class="fas fa-save"></i>', ['class' => 'btn btn-primary btn-sm', 'id' => 'save_status', 'name' => 'save_status', 'escapeTitle' => false, 'type' => 'submit', 'disabled' => true]);

                        echo $this->Form->end();
                    } else {
                        echo $this->Form->create(null, ['class' => 'form-inline']);
                        echo $this->Form->input('Portfolio.portfolio_id', ['type' => 'hidden', 'value' => $portfolio->portfolio_id]);

                        echo $this->Form->select('Portfolio.status_portfolio',
                                ['OPEN' => 'OPEN', 'EARLY TERMINATED' => 'EARLY TERMINATED'],
                                [
                                    'class' => 'form-control mr-2',
                                    'value' => $portfolio->status_portfolio,
                                    'id'    => 'periodqid',
									'disabled' => !$perm->hasWrite(array('controller' => 'Portfolio', 'action' => 'detail', 'filter' => 'status'))
                                ]
                        );
                        echo $this->Form->button('<i class="fas fa-save"></i>', ['class' => 'btn btn-primary btn-sm', 'id' => 'save_status', 'name' => 'save_status', 'escapeTitle' => false, 'type' => 'submit', 'disabled' => !$perm->hasWrite(array('controller' => 'Portfolio', 'action' => 'detail', 'filter' => 'status'))]);

                        echo $this->Form->end();
                    }
                    ?>
                </td>
                <td><?= !empty($portfolio->closure_date) ? $portfolio->closure_date instanceof \Cake\I18n\FrozenDate ? $portfolio->closure_date->format('Y-m-d') : $portfolio->closure_date : '' ?></td>
            </tr>
        </tbody>

        <thead class="thead-light">
            <tr>
                <th>Contract Status</th>
                <th>Effective Termination Date</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= h($portfolio->gs_deal_status) ?></td>
                <td><?= !empty($portfolio->effective_termination_date) ? h($portfolio->effective_termination_date->format('Y-m-d')) : '' ?></td>
            </tr>
        </tbody>

        <thead class="thead-light">
            <tr>
                <th>Signed Amount</th>
                <th>Signature Date</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= !empty($portfolio->signed_amount) ? $this->Number->precision($portfolio->signed_amount, 2) : '' ?></td>
                <td><?= !empty($portfolio->signature_date) ? h($portfolio->signature_date->format('Y-m-d')) : '' ?></td>
            </tr>
        </tbody>

        <thead class="thead-light">
            <tr>
                <th>Availability Start date</th>
                <th>Availability End Date</th>
                <th>End Reporting Period</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= !empty($portfolio->availability_start) ? h($portfolio->availability_start->format('Y-m-d')) : '' ?></td>
                <td><?= !empty($portfolio->availability_end) ? h($portfolio->availability_end->format('Y-m-d')) : '' ?></td>
                <td><?= !empty($portfolio->end_reporting_date) ? h($portfolio->end_reporting_date->format('Y-m-d')) : '' ?></td>
            </tr>
        </tbody>

        <thead class="thead-light">
            <tr>
                <th>Inclusion Start date</th>
                <th>Inclusion End Date</th>
                <th>Number of periods for including</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= !empty($portfolio->inclusion_start_date) ? h($portfolio->inclusion_start_date->format('Y-m-d')) : '' ?></td>
                <td><?= !empty($portfolio->inclusion_end_date) ? h($portfolio->inclusion_end_date->format('Y-m-d')) : '' ?></td>
                <td><?= h($number_of_periods) ?></td>
            </tr>
        </tbody>

        <thead class="thead-light">
            <tr>
                <th>Maximum Portfolio Volume</th>
                <th>Agreed Portfolio Volume</th>
                <th>Actual Portfolio Volume</th>
                <th>Actual Portfolio Volume at Closure</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= !empty($portfolio->maxpv) ? $this->Number->precision($portfolio->maxpv, 2) : '' ?></td>
                <td><?= !empty($portfolio->agreed_pv) ? $this->Number->precision($portfolio->agreed_pv, 2) : '' ?></td>
                <td>
                    <?php
                    //            if ($profile == 'dams_team') {
                    echo $this->Form->create(null, ['id' => 'refresh_apvDetailForm', 'class' => 'form-inline']);
                    if (!empty($portfolio->actual_pv)) {
                        echo $this->Number->precision($portfolio->actual_pv, 2);
                    }
                    echo $this->Form->hidden('Portfolio.refresh_apv', ['value' => 1]);
                    echo $this->Form->hidden('Portfolio.portfolio_id', ['value' => $portfolio->portfolio_id]);
                    echo $this->Form->button('<i class="fas fa-sync"></i>', ['class' => 'btn btn-primary btn-sm ml-2', 'id' => 'refresh_apv_submit', 'name' => 'refresh_apv_submit', 'escapeTitle' => false, 'type' => 'submit',
									'disabled' => !$perm->hasWrite(array('controller' => 'Portfolio', 'action' => 'detail', 'filter' => 'volume'))]);
                    echo $this->Form->end();
                    //            }
                    ?>
                </td>
                <td><?= !empty($portfolio->apv_at_closure) ? $this->Number->precision($portfolio->apv_at_closure, 2) : '' ?></td>
            </tr>
        </tbody>

        <thead class="thead-light">
            <tr>
                <th>Guarantee Rate</th>
                <th>Guarantee Amount</th>
                <th>Actual Guaranteed volume</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <?php
                    if (!empty($portfolio_rates)) {
                        '<ul>';
                        foreach ($portfolio_rates as $gr) {
                            $start_date = !empty($gr->availability_start) ? " : " . $gr->availability_start->format('Y-m-d') . " -> " : '';
                            $end_date = !empty($gr->availability_end) ? $gr->availability_end->format('Y-m-d') : '';
                            $formatted_guarantee_rate = !empty($gr->guarantee_rate) ? $this->Number->precision($gr->guarantee_rate, 2) : '';
                            echo "<li>" . $gr->theme . $start_date . $end_date . " : " . $formatted_guarantee_rate . "</li>";
                        }
                        '</ul>';
                    }
                    ?>
                </td>
                <td><?= !empty($portfolio->guarantee_amount) ? $this->Number->precision($portfolio->guarantee_amount, 2) : '' ?></td>
                <td><?= !empty($portfolio->actual_gv) ? $this->Number->precision($portfolio->actual_gv, 2) : '' ?></td>
            </tr>
        </tbody>

        <thead class="thead-light">
            <tr>
                <th>Default Amount</th>
                <th>Guarantee Termination Date</th>
                <th>Call Time to Pay</th>
                <th>Call Time Unit</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= !empty($portfolio->default_amount) ? $this->Number->precision($portfolio->default_amount, 2) : '' ?></td>
                <td><?= !empty($portfolio->guarantee_termination) ? h($portfolio->guarantee_termination->format('Y-m-d')) : '' ?></td>
                <td><?= !empty($portfolio->call_time_to_pay) ? $this->Number->precision($portfolio->call_time_to_pay, 2) : '' ?></td>
                <td><?= h($portfolio->call_time_unit) ?></td>
            </tr>
        </tbody>

        <thead class="thead-light">
            <tr>
                <th>Capped</th>
                <th>Guarantee Cap Rate</th>
                <th>Guarantee Cap Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= h($portfolio->capped) ?></td>
                <td>
                    <?php
                    if (!empty($portfolio_rates)) {
                        '<ul>';
                        foreach ($portfolio_rates as $cr) {
                            $start_date = !empty($cr->availability_start) ? $cr->availability_start->format('Y-m-d') . " -> " : '';
                            $end_date = !empty($cr->availability_end) ? $cr->availability_end->format('Y-m-d') . " : " : '';
                            $formatted_guarantee_cap_rate = !empty($cr->cap_rate) ? $this->Number->precision($cr->cap_rate, 2) : '';
                            echo "<li>" . $start_date . $end_date . $formatted_guarantee_cap_rate . "</li>";
                        }
                        '</ul>';
                    }
                    ?>
                </td>
                <td><?= $portfolio->capped === 'YES' ? $this->Number->precision($portfolio->cap_amount, 2) : '' ?></td>
            </tr>
        </tbody>

        <thead class="thead-light">
            <tr>
                <th>Guarantee Effective Cap Amount</th>
                <th>Guarantee Available Cap Amount</th>
                <th>Recovery Rate</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= !empty($portfolio->effective_cap_amount) ? $this->Number->precision($portfolio->effective_cap_amount, 2) : '' ?></td>
                <td><?= !empty($portfolio->available_cap_amount) ? $this->Number->precision($portfolio->available_cap_amount, 2) : '' ?></td>
                <td><?= !empty($portfolio->recovery_rate) ? $this->Number->precision($portfolio->recovery_rate, 2) : '' ?></td>
            </tr>
        </tbody>
    </table>
</table>

<div class="form_store_dico_values">
    <?php
    echo $this->Form->create(null, ['id' => 'storeDetailForm']);
    echo $this->Form->hidden('Portfolio.store', ['value' => 1]);
    echo $this->Form->hidden('Portfolio.portfolio_id', ['value' => $portfolio->portfolio_id,]);
    echo $this->Form->end();

    echo $this->Form->create(null, ['id' => 'statisticsDetailForm', 'url' => '/damsv2/portfolio/statistics']);
    echo $this->Form->hidden('Portfolio.statistics', ['value' => 1]);
    echo $this->Form->hidden('Portfolio.portfolio_id', ['value' => $portfolio->portfolio_id]);
    echo $this->Form->end();
    
    echo $this->Form->create(null, ['id' => 'templatesForm', 'url' => '/damsv2/template/dashboard']);
    echo $this->Form->hidden('Product.product_id', ['value' => $portfolio->product_id]);
    echo $this->Form->hidden('Portfolio.portfolio_id', ['value' => $portfolio->portfolio_id]);
    echo $this->Form->end();
    
    echo $this->Form->create(null, ['id' => 'smesForm', 'url' => '/damsv2/sme-portfolio']);
    echo $this->Form->hidden('product_id', ['value' => $portfolio->product_id]);
    echo $this->Form->hidden('portfolio_id', ['value' => $portfolio->portfolio_id]);
    echo $this->Form->end();
    
    echo $this->Form->create(null, ['id' => 'transactionsForm', 'url' => '/damsv2/transactions']);
    echo $this->Form->hidden('product_id', ['value' => $portfolio->product_id]);
    echo $this->Form->hidden('portfolio_id', ['value' => $portfolio->portfolio_id]);
    echo $this->Form->end();
    ?>
</div>


<script>
    $(document).ready(function () {
        $('#store_dico_values').click(function () {
            $('#storeDetailForm').submit();
        });
        $('#view_statistics').click(function () {
            $('#statisticsDetailForm').submit();
        });
        $('#refresh_apv_submit').click(function () {
            $('#refresh_apvDetailForm').submit();
        });
        $('#get_templates').click(function () {
            $('#templatesForm').submit();
        });
        $('#get_smes').click(function () {
            $('#smesForm').submit();
        });
        $('#get_transactions').click(function () {
            $('#transactionsForm').submit();
        });
    });
</script>
