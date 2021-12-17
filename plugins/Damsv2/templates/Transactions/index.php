<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Transaction[]|\Cake\Collection\CollectionInterface $transactions
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
    ]
]);
$enabled = !empty($transactions) && count($transactions) > 0 ? '' : 'disabled';
?>

<button type="button" class="btn btn-secondary float-right" id="export_data" <?= $enabled ?>><i class="fas fa-file-export"></i> &nbsp; Export to XLS</button>
<h3><?= __('Search Transaction') ?></h3>

<?= $this->Form->create(null, ['id' => 'filter', 'autocomplete' => 'off']) ?>
<div class="row form-inline">
    
    <div class="col-9">
        <?= $this->Form->select('product_id', $products,
            [
                'empty' => '-- Any product --',
                'class' => 'form-control mr-2 my-2 filter',
                'value' => $session->read('Form.data.transactions.product_id'),
                'id'    => 'productid'
            ]
        );
        ?>
    
        <?= $this->Form->select('mandate', $mandates,
            [
                'empty' => '-- Any mandate --',
                'class' => 'w-25 form-control mr-2 my-2 filter',
                'value' => $session->read('Form.data.transactions.mandate'),
                'id'    => 'mandateid'
            ]
        );
        ?>
    
        <?= $this->Form->select('portfolio_id', $portfolios,
            [
                'empty' => '-- Any portfolio --',
                'class' => 'w-25 form-control my-2 filter',
                'value' => $session->read('Form.data.transactions.portfolio_id'),
                'id'    => 'portfolioid'
            ]
        );
        ?>
    </div>
</div>
<div class="row form-inline">
    <div class="col-9">
        <?= $this->Form->input('transaction_reference', ['label' => false,  'placeholder' => 'Transaction reference','class' => 'w-25 form-control mr-2 my-2',  'value' => $session->read('Form.data.transactions.transaction_reference'), 'id' => 'transactionref']) ?>
     
        <?= $this->Form->input('amount_from', ['type' => 'number','label' => false, 'placeholder' => 'Amount From',  'class' => 'form-control mr-2 my-2', 'value' => $session->read('Form.data.transactions.amount_from'), 'id' => 'amountfrom']) ?>
   
        <?= $this->Form->input('amount_to', ['type' => 'number','label' => false, 'placeholder' => 'Amount To',  'class' => 'form-control mr-2 my-2',  'value' => $session->read('Form.data.transactions.amount_to'), 'id' => 'amountto']) ?>
   
        <?= $this->Form->select('status', $statuses,
            [
                'empty' => '-- Any status --',
                'class' => 'w-25 form-control mr-2 my-2',
                'value' => $session->read('Form.data.transactions.status'),
                'id'    => 'status_id'
            ]
        );
        ?>
    </div>
</div>
<div class="row form-inline">
    <div class="col-9">
        <?= $this->Form->input('fiscal_number', ['label' => false, 'placeholder' => 'Fiscal number',  'class' => 'w-25 form-control mr-2 my-2', 'value' => $session->read('Form.data.transactions.fiscal_number'), 'id' => 'fiscalnumber']) ?>
    
        <?= $this->Form->input('maturity_from', ['type' => 'text', 'label' => false, 'placeholder' => 'Maturity From', 'class' => 'form-control mr-2 my-2 datepicker', 'value' => $session->read('Form.data.transactions.maturity_from'), 'id' => 'maturityfrom']) ?>
      
        <?= $this->Form->input('maturity_to', ['type' => 'text', 'label' => false, 'placeholder' => 'Maturity To',  'class' => 'form-control mr-2 my-2 datepicker',  'value' => $session->read('Form.data.transactions.maturity_to'), 'id' => 'maturityto']) ?>
    
        <?= $this->Form->select('currency', $currencies,
            [
                'empty' => '-- Any currency --',
                'class' => 'w-25 form-control mr-2 my-2',
                'value' => $session->read('Form.data.transactions.currency'),
                'id'    => 'currency'
            ]
        );
        ?>
    </div>
</div>
<div class="row form-inline">
    <div class="col-9">
        <?= $this->Form->select('exclusion_flag', $exclusion_flags,
            [
                'empty' => '-- Any exclusion flag --',
                'class' => 'w-25 form-control mr-2 my-2',
                'value' => $session->read('Form.data.transactions.exclusion_flag'),
                'id'    => 'exclflags'
            ]
        );
        ?>
   
        <?= $this->Form->select('exclusion_reason', $exclusion_reasons,
            [
                'empty' => '-- Any exclusion reason --',
                'class' => 'w-25 form-control mr-2 my-2',
                'value' => $session->read('Form.data.transactions.exclusion_reason'),
                'id'    => '$exclreasons'
            ]
        );
        ?>
    </div>
</div>

<div class="row">
    <div class="col-4 form-inline">
        <?= $this->Form->submit('Search', ['class' => 'btn btn-primary my-2', 'id' => 'search']) ?>
        <?= $this->Html->link('Reset', ['controller' => 'invoice', 'action' => 'reset-filters'], ['class' => 'btn btn-secondary ml-2 my-2', 'id' => 'reset']) ?>
    </div>
    
</div>
<?= $this->Form->end(); ?>

<?php if (isset($transactions)): ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?php echo $this->Paginator->sort('Portfolio.portfolio_name', 'Portfolio Name'); ?></th>
                    <th><?php echo $this->Paginator->sort('transaction_reference'); ?></th>
                    <th><?php echo $this->Paginator->sort('fiscal_number'); ?></th>
                    <th><?php echo $this->Paginator->sort('status'); ?></th>
                    <th><?php echo $this->Paginator->sort('currency'); ?></th>
                    <th><?php echo $this->Paginator->sort('principal_amount'); ?></th>
                    <th><?php echo $this->Paginator->sort('maturity'); ?></th>
                    <th class="actions"><?php echo __('Actions'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions as $transaction): ?>
            <tr>
                <td><?= $this->Html->link($transaction->portfolio->portfolio_name, ['controller' => 'Portfolio', 'action' => 'detail', $transaction->portfolio->portfolio_id])  ?></td>
                
                <td><?= h($transaction->transaction_reference) ?></td>
                <td><?= $this->Html->link($transaction->fiscal_number, ['controller' => 'SmePortfolio', 'action' => 'to-smeportfolio',$transaction->sme->sme_id, $transaction->portfolio->portfolio_id])  ?></td>
                
                <td><?= h($transaction->transaction_status) ?></td>
                <td><?= h($transaction->currency) ?></td>
               
                <td style="text-align:right;"><?= $this->Number->format($transaction->principal_amount) ?></td>
                <td><?= $transaction->maturity ?></td>

                 <td class="actions">
                    <?=
                    $this->Html->link(
                            '<i class="fas fa-eye"></i>' . __('View'),
                            ['action' => 'view', $transaction->transaction_id],
                            ['escape' => false, 'title' => __('View'), 'class' => 'btn btn-info btn-xs']
                    )
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="paginator">
    <ul class="pagination">
        <?= $this->Paginator->first('<< ' . __('first')) ?>
        <?= $this->Paginator->prev('< ' . __('previous')) ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next(__('next') . ' >') ?>
        <?= $this->Paginator->last(__('last') . ' >>') ?>
    </ul>
    <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
</div>

<?php endif; ?>


<script>
function exportData()
{
    //ajax to generate the list
    var data = $("#filter").serialize();
    $.ajax({
            type: 'get',
            data: data,
            url: '/damsv2/transactions/export',
            headers: {'X-CSRF-TOKEN': '<?= $this->request->getAttribute('csrfToken') ?>'},
            success: function(returndata)
            {
                //success message + download file
                window.open("/damsv2/ajax/download-file/"+returndata+"/export");
            }
    });
    return false;
}
$(document).ready(function () {
    $("#productid").on('change',function() {
        var formData = $('#filter').serialize(); 
        $.ajax({
            url: '/damsv2/ajax/getPortfoliosByProduct',
            type: 'POST',
            //dataType: 'HTML',
            data: formData,
            headers: {'X-CSRF-TOKEN': '<?= $this->request->getAttribute('csrfToken') ?>'},
            success: function (data) {
               $('#portfolioid').html(data);
            }
        });
    });
    
    $("#productid").on('change',function() {
        var formData = $('#filter').serialize(); 
        $.ajax({
            url: '/damsv2/ajax/getMandatesByProduct',
            type: 'POST',
            //dataType: 'HTML',
            data: formData,
            headers: {'X-CSRF-TOKEN': '<?= $this->request->getAttribute('csrfToken') ?>'},
            success: function (data) {
               $('#mandateid').html(data);
            }
        });
    });
    
    $("#export_data").on("click", function (e) {
        e.preventDefault();
        exportData();
    });
    
    $( ".datepicker" ).datepicker({'dateFormat':'yy-mm-dd',
        changeMonth : true,
        changeYear : true
    });
});

if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}

</script>