<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Transaction $transaction
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Search Transaction',
        'url'     => ['controller' => 'Transactions', 'action' => 'index'],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-port-crumb'
            ]
        ]
    ],
    [
        'title'   => $transaction->portfolio->portfolio_name,
        'url'     => ['controller' => 'Portfolio', 'action' => 'view', $transaction->portfolio->portfolio_id],
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

<h3>Transaction</h3>
<hr>
<table class="table-responsive">
    <table class="table table-striped table-bordered">
        <?php foreach ($mapping_tables as $table): ?>
            <?php foreach ($table->mapping_column as $column): ?>
                <?php  if ($table->table_name == 'transactions' &&   $column->in_view): ?>
                    <tr>
                        <td><?= ucfirst(str_replace('_', ' ', $column->table_field)) ?></td>
                        <td><?php
                            $amount = !(strpos($column->table_field, 'amount') === false);
                            if (isset($transaction[$column->table_field . '_eur']) || isset($transaction[$column->table_field . '_curr']) || $amount) {
                                //is an amount => formated
                                echo $this->Number->precision($transaction[$column->table_field],2);
                            } else {
                                $col_value = !empty($transaction[$column->table_field]) ? $transaction[$column->table_field] instanceof \Cake\I18n\FrozenDate ? $transaction[$column->table_field]->format('Y-m-d') : $transaction[$column->table_field] : '' ;
                                echo $col_value;
                            }
                            ?></td>
                    </tr>
                    <?php if ($column->is_converted && isset($transaction[$column->table_field . '_eur']) && isset($transaction[$column->table_field . '_curr'])): ?>
                        <tr>
                            <td><?= ucfirst(str_replace('_', ' ', $column->table_field)) ?> in euro</td>
                            <td><?= $this->Number->precision($transaction[$column->table_field . '_eur'],2); ?></td>
                        </tr>
                        <tr>
                            <td><?= ucfirst(str_replace('_', ' ', $column->table_field)) ?> in contract currency</td>
                            <td><?= $this->Number->precision($transaction[$column->table_field . '_curr'],2); ?></td>
                        </tr>
                    <?php endif ?>
                <?php  endif ?>
            <?php endforeach ?>
        <?php endforeach ?>
                  
        <tr>
            <td><?= __('SME'); ?></td>
            <td><?= $this->Html->link($transaction->sme->name, ['controller' => 'sme-portfolio', 'action' => 'toSmeportfolio', $transaction->sme_id, $transaction->portfolio_id]); ?></td>
        </tr>
        <tr>
            <td><?= __('Portfolio'); ?></td>
            <td><?= h($transaction->portfolio->portfolio_name); ?></td>
        </tr>
        <tr>
            <td><?= __('Report'); ?></td>
            <td><?= h($transaction->report->report_name); ?></td>
        </tr>
        <tr>
            <td><?= __('Exemption granted'); ?></td>
            <td><?= h($transaction['waiver']); ?></td>
        </tr>
        <tr>
            <td><?= __('Business rule trigger'); ?></td>
            <td><?= h($transaction['waiver_details']); ?></td>
        </tr>
        <tr>
            <td><?= __('Exemption reason'); ?></td>
            <td><?= h($transaction['waiver_reason']); ?></td>
        </tr>
        <tr>
            <td><?= __('Error message'); ?></td>
            <td><?= h($transaction['error_message']); ?></td>
        </tr>
        <tr>
            <td><?= __('Transaction status'); ?></td>
            <td><?= h($transaction['transaction_status']); ?></td>
        </tr>
         <tr>
            <td><?= __('Sme Turnover') ?></th>
            <td><?= $this->Number->precision($transaction->sme_turnover,2) ?></td>
        </tr>
        <tr>
            <td><?= __('Sme Assets') ?></th>
            <td><?= $this->Number->precision($transaction->sme_assets,2) ?></td>
        </tr>
        <tr>
            <td><?= __('SME number of employees'); ?></td>
            <td><?= h($transaction['sme_nbr_employees']); ?></td>
        </tr>
        <tr>
            <td><?= __('SME sector'); ?></td>
            <td><?= h($transaction['sme_sector']); ?></td>
        </tr>
        <tr>
            <td><?= __('SME rating'); ?></td>
            <td><?= h($transaction['sme_rating']); ?></td>
        </tr>
        <tr>
            <td><?= __('SME current rating') ?></td>
            <td><?= $transaction['sme_current_rating']; ?></td>
        </tr>
        <tr>
            <td><?= __('Sampled') ?></td>
            <td><?= $transaction->sampled ?></td>
        </tr>
        <tr>
            <td><?= __('Sampling Date') ?></td>
            <td><?= !empty($transaction->sampling_date) ? $transaction->sampling_date->format('Y-m-d') : '' ?></td>
        </tr>
        
        <?php if (!empty($expired_transaction)): ?>
            <tr>
                <td>Repayment date</td>
                <td><?= $expired_transaction->repayment_date->format('Y-m-d') ?></td>
            </tr>
        <?php endif ?>
        <?php if (!empty($excluded_transaction)): ?>
            <tr>
                <td>Exclusion date </td>
                <td><?= $excluded_transaction->exclusion_date->format('Y-m-d') ?></td>
            </tr>
            <tr>
                <td>Excluded transaction amount </td>
                <td><?= $this->Number->precision($excluded_transaction['excluded_transaction_amount'],2); ?></td>
            </tr>
            <tr>
                <td>Exclusion type </td>
                <td><?= $excluded_transaction['exclusion_type'] ?></td>
            </tr>
        <?php endif ?>
        
        <?php if (!empty($guarantee)): ?>
            <?php foreach ($mapping_tables as $table): ?>
                <?php foreach ($table->mapping_column as $column): ?>
                    <?php  if ($table->table_name == 'guarantees' &&   $column->in_view): ?>
                        <tr>
                            <td><?= ucfirst(str_replace('_', ' ', $column->table_field)) ?></td>
                            <td><?= $guarantee[$column->table_field] ?></td>
                        </tr>
                        <?php if ($column->is_converted && isset($guarantee[$column->table_field . '_eur']) && isset($guarantee[$column->table_field . '_curr'])): ?>
                            <tr>
                                <td><?= ucfirst(str_replace('_', ' ', $column->table_field)) ?> in euro</td>
                                <td><?= $this->Number->precision($guarantee[$column->table_field  . '_eur'],2); ?></td>
                            </tr>
                            <tr>
                                <td><?= ucfirst(str_replace('_', ' ', $column->table_field)) ?> in contract currency</td>
                                <td><?= $this->Number->precision($guarantee[$column->table_field  . '_curr'],2); ?></td>
                            </tr>
                        <?php endif ?>
                    <?php endif ?>
                <?php endforeach ?>
            <?php endforeach ?>
        <?php endif ?>
                        
        <?php if (!empty($transaction->included_transactions)): ?>
            <tr>
                <td><u>Latest Included transaction data</u></td>
                <td>---------------------------</td>
            </tr>
            <?php foreach ($mapping_tables as $table): ?>
                <?php foreach ($table->mapping_column as $column): ?>
                    <?php  if ($table->table_name == 'included_transactions' &&   $column->in_view): ?>
                        
                        <tr>
                            <td><?= ucfirst(str_replace('_', ' ', $column->table_field)) ?></td>
                            <td><?php
                                if (isset($transaction->included_transactions[sizeof($transaction->included_transactions) - 1][$column->table_field . '_eur']) || isset($transaction->included_transactions[sizeof($transaction->included_transactions) - 1][$column->table_field . '_curr'])) {
                                    echo $this->Number->precision($transaction->included_transactions[sizeof($transaction->included_transactions) - 1][$column->table_field],2);
                                } else {
                                    $col_value = !empty($transaction->included_transactions[sizeof($transaction->included_transactions) - 1][$column->table_field]) ? $transaction->included_transactions[sizeof($transaction->included_transactions) - 1][$column->table_field] instanceof \Cake\I18n\FrozenDate ? $transaction->included_transactions[sizeof($transaction->included_transactions) - 1][$column->table_field]->format('Y-m-d') : $transaction->included_transactions[sizeof($transaction->included_transactions) - 1][$column->table_field] : '' ;
                                    echo $col_value;
                                }
                                ?></td>
                        </tr>
                        <?php if ($column->is_converted && isset($transaction->included_transactions[sizeof($transaction->included_transactions) - 1][$column->table_field . '_eur']) && isset($transaction->included_transactions[sizeof($transaction->included_transactions) - 1][$column->table_field . '_curr'])): ?>
                            <tr>
                                <td><?= ucfirst(str_replace('_', ' ', $column->table_field)) ?> in euro</td>
                                <td><?= $this->Number->precision($transaction->included_transactions[sizeof($transaction->included_transactions) - 1][$column->table_field . '_eur'],2); ?></td>
                            </tr>
                            <tr>
                                <td><?= ucfirst(str_replace('_', ' ', $column->table_field)) ?> in contract currency</td>
                                <td><?= $this->Number->precision($transaction->included_transactions[sizeof($transaction->included_transactions) - 1][$column->table_field . '_curr'],2); ?></td>
                            </tr>
                        <?php endif ?>
                    <?php endif ?>
                <?php endforeach ?>
            <?php endforeach ?>
        <?php endif ?>
  
    </table>


<h3>Payment demand / Loss recovery</h3>
<?php
if (empty($pdlr_trns->toArray())) {
    echo "No payment demand/loss recovery reported for this transaction.";
} else {
    foreach ($pdlr_trns as $pdlr_trn) {
        ?>
        
            <table class="table table-striped table-bordered">
                <tr><td>PDLR ID</td><td><?= $pdlr_trn['pdlr_id']; ?></td></tr>
                <tr><td>Default date</td><td><?= !empty($pdlr_trn->default_date) ?  $pdlr_trn->default_date->format('Y-m-d') : ''; ?></td></tr>
                <tr><td>Currency</td><td><?= $pdlr_trn['currency']; ?></td></tr>
                <tr><td>Total loss</td><td><?= $this->Number->precision($pdlr_trn['total_loss'],2); ?></td></tr>
                <tr><td>Principal loss amount</td><td><?= $this->Number->precision($pdlr_trn['principal_loss_amount'], 2); ?></td></tr>
                <tr><td>Unpaid interest</td><td><?= $this->Number->precision($pdlr_trn['unpaid_interest'], 2); ?></td></tr>
                <tr><td>Recovery amount</td><td><?= $this->Number->precision($pdlr_trn['recovery_amount'], 2); ?></td></tr>
                <tr><td>Sampled</td><td><?= $pdlr_trn['sampled']; ?></td></tr>
                <tr><td>Document request date</td><td><?= !empty($pdlr_trn->document_request_date) ?  h($pdlr_trn->document_request_date->format('Y-m-d')) : '' ; ?></td></tr>
                <tr><td>Document receive date</td><td><?= !empty($pdlr_trn->document_receive_date) ?  h($pdlr_trn->document_receive_date->format('Y-m-d')) : '' ; ?></td></tr>
                <tr><td>Sampling closing date</td><td><?= !empty($pdlr_trn->sampling_closing_date) ?  h($pdlr_trn->sampling_closing_date->format('Y-m-d')) : '' ; ?></td></tr>
                <tr><td>Sampling finding</td><td><?= $pdlr_trn['sampling_finding']; ?></td></tr>
                <tr><td>Sampling impact in euro</td><td><?= $this->Number->precision($pdlr_trn['sample_impact_eur'], 2); ?></td></tr>
                <tr><td>Sample comment</td><td><?= $pdlr_trn['sample_comment']; ?></td></tr>
            </table>
       
        <?php
    }
}
?>

 </table>

