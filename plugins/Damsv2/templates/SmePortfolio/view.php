<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\smePortfolio $smePortfolio
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Search SME',
        'url'     => ['controller' => 'smePortfolio', 'action' => 'index'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => $smePortfolio->portfolio->portfolio_name,
        'url'     => ['controller' => 'Portfolio', 'action' => 'view', $smePortfolio->portfolio->portfolio_id],
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

 <h3>SME</h3>
 <hr>
 <div class="table-responsive">
<table class="table table-striped table-bordered">
    <?php if (!empty($mapping_columns)) : ?>
        <?php foreach ($mapping_columns as $column) : ?>
            <?php if ($column['in_view']): ?>
                <tr>
                    <?php
                    $colname = ucfirst(str_replace('_', ' ', $column->table_field));
                    if ($colname == 'Sme rating')
                        $colname = 'Sme current rating';
                    ?>
                    <td><strong><?= $colname ?></strong></td>
                    <?php
                        if (in_array($colname, ['Turnover', 'Assets'])) {
                            echo "<td>" . $this->Number->precision([$column->table_field],2) . "</td>";
                        } else {
                            $col_value = !empty($smePortfolio[$column->table_field]) ? $smePortfolio[$column->table_field] instanceof \Cake\I18n\FrozenDate ? $smePortfolio[$column->table_field]->format('Y-m-d') : $smePortfolio[$column->table_field] : '' ;   
                            echo "<td>" . $col_value . "</td>";
                        }
                    ?>
                </tr>
                <?php if ($column['is_converted'] AND isset($smePortfolio[$column->table_field . '_eur']) AND isset($smePortfolio[$column->table_field . '_curr'])): ?>
                    <tr>
                        <td><strong><?= ucfirst(str_replace('_', ' ', $column->table_field)) ?> in euro</strong></td>
                        <td><?= $smePortfolio[$column->table_field . '_eur'] ?></td>
                    </tr>
                    <tr>
                        <td><strong><?= ucfirst(str_replace('_', ' ', $column->table_field)) ?> in contract currency</strong></td>
                        <td><?= $smePortfolio[$column->table_field . '_curr'] ?></td>
                    </tr>
                <?php endif ?>
            <?php endif ?>

        <?php endforeach ?>
    <?php endif ?>
    <tr><td><strong>Portfolio name</strong></td><?= "<td>" . $smePortfolio->portfolio->portfolio_name . "</td>"; ?></tr>
    <?php if (!empty($smePortfolio->report)) : ?>
        <tr>
            <td><strong><?= __('Report name'); ?></strong></td>
            <td><?= h(str_replace('_', ' ', $smePortfolio->report->report_name)); ?></td>
        </tr>
        <tr>
            <td><strong><?= __('Reception date'); ?></strong></td>
            <td><?= !empty($smePortfolio->report->reception_date) ?  h($smePortfolio->report->reception_date->format('Y-m-d')) : '' ; ?></td>
        </tr>
    <?php endif ?>
    <tr>
        <td><strong><?= __('Category'); ?></strong></td>
        <td><?= h($smePortfolio->category); ?></td>
    </tr>
    <tr>
        <td><strong><?php echo __('Exemption granted'); ?></strong></td>
        <td><?php echo h($smePortfolio->waiver); ?></td>
    </tr>
    <tr>
        <td><strong><?php echo __('Exemption reason'); ?></strong></td>
        <td><?php echo h($smePortfolio->waiver_reason); ?></td>
    </tr>
    <tr>
        <td><strong><?php echo __('Error message'); ?></strong></td>
        <td><?php echo h($smePortfolio->error_message); ?></td>
    </tr>
    <!--<tr>
            <td><strong><?= __('Total loan amount in currency'); ?></strong></td>
            <td><?= $this->Number->precision($smePortfolio->total_loan_amount_curr,2) ?></td>
    </tr>
    <tr>
            <td><strong><?= __('Total loan amount in Euro'); ?></strong></td>
            <td><?= $this->Number->precision($smePortfolio->total_loan_amount_eur,2) ?></td>
    </tr>-->

</table><!-- .table table-striped table-bordered -->

<h3>Associated Transaction(s)</h3>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Portfolio Name</th>
            <th>Transaction reference</th>
            <th>Fiscal number</th>
            <th>Status</th>
            <th>Currency</th>
            <th>Principal amount</th>
            <th>Maturity</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($transactions as $transaction): ?>
            <tr>
                <td><?= $transaction->portfolio->portfolio_name ?></td>
                <td><?= $this->Html->link($transaction->transaction_reference, ['controller' => 'transactions', 'action' => 'view', $transaction->transaction_id]); ?>
                </td>
                <td><?= $transaction->sme->fiscal_number ?></td>
                <td><?= $transaction->transaction_status ?></td>
                <td><?= $transaction->currency ?></td>
                <td><?= $this->Number->precision($transaction->principal_amount,2) ?></td>
                <td><?= $transaction->maturity ?></td>
                <td>
                     <?= $this->Html->link(
                            '<i class="fas fa-eye"></i>' . __('View'),
                            ['controller' => 'transactions','action' => 'view', $transaction->transaction_id],
                            ['escape' => false, 'title' => __('View'), 'class' => 'btn btn-info btn-xs']
                    )
                    ?>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
</div>
