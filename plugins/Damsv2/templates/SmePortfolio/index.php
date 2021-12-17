<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SmePortfolio[]|\Cake\Collection\CollectionInterface $smePortfolio
 */

$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Search SME',
        'url'     => ['controller' => 'SmePortfolio', 'action' => 'index'],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-port-crumb'
            ]
        ]
    ]
]);
$enabled = !empty($smePortfolios) && count($smePortfolios) > 0 ? '' : 'disabled';
?>

<button type="button" class="btn btn-secondary float-right" id="export_data" <?= $enabled ?>><i class="fas fa-file-export"></i> &nbsp; Export to XLS</button>
<h3><?= __('Search SME') ?> </h3>
<hr>

<div class="row">
    <?= $this->Form->create(null, ['class' => 'form-inline', 'id' => 'filter', 'autocomplete' => 'off']) ?>
    <div class="col-3">
        <?= $this->Form->select('product_id', $products,
            [
                'empty' => '-- Any product --',
                'class' => 'w-75 form-control mr-2 my-2 filter',
                'value' => $session->read('Form.data.smeportfolio.product_id'),
                'id'    => 'productid'
            ]
        );
        ?>
    </div>

    <div class="col-3">
        <?= $this->Form->select('portfolio_id', $portfolios,
            [
                'empty' => '-- Any portfolio --',
                'class' => 'w-75 form-control mr-2 my-2 filter',
                'value' => $session->read('Form.data.smeportfolio.portfolio_id'),
                'id'    => 'portfolioid'
            ]
        );
        ?>
    </div>
    <div class="col-3 text-secondary">
        Note: If portfolio is not selected, SMEs with transactions in multiple portfolios will be displayed multiple times in the results.
    </div>
</div>
<div class="row">
    <div class="col-3">
        <?= $this->Form->control('fiscal_number', ['label' => false, 'placeholder' => 'Fiscal number', 'class' => 'form-control my-2 filter', 'value' => $session->read('Form.data.smeportfolio.fiscal_number'), 'id' => 'fiscal_number']) ?>
    </div>
    <div class="col-3">    
        <?= $this->Form->control('name', ['label' => false, 'placeholder' => 'Name',  'class' => 'form-control my-2 filter', 'value' => $session->read('Form.data.smeportfolio.name'), 'id' => 'smename']) ?>
    </div>
    <div class="col-3">    
        <?= $this->Form->control('sector', ['label' => false, 'placeholder' => 'Sector', 'class' => 'form-control my-2 filter', 'value' => $session->read('Form.data.smeportfolio.sector'), 'id' => 'sector']) ?>
     </div>
    <div class="col-3 form-inline">
        <?= $this->Form->submit('Search', ['class' => 'btn btn-primary mr-2 my-2 py-2', 'id' => 'search']) ?>
        <?= $this->Html->link('Reset', ['controller' => 'invoice', 'action' => 'reset-filters'], ['class' => 'btn btn-secondary ml-2 my-2 py-2', 'id' => 'reset']) ?>
    </div>
    <?= $this->Form->end(); ?>
</div>

<?php if (isset($smePortfolios)): ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?php echo $this->Paginator->sort('Portfolio.portfolio_name', 'Portfolio name'); ?></th>
                    <th><?php echo $this->Paginator->sort('fiscal_number'); ?></th>
                    <th><?php echo $this->Paginator->sort('name'); ?></th>
                    <th><?php echo $this->Paginator->sort('sector'); ?></th>
                    <th><?php echo $this->Paginator->sort('region'); ?></th>
                    <th><?php echo $this->Paginator->sort('nbr_employees', 'Number of employees'); ?></th>
                    <th class="actions"><?php echo __('Actions'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($smePortfolios as $smePortfolio): ?>
            <tr>
                <td><?= $this->Html->link($smePortfolio->portfolio->portfolio_name, ['controller' => 'Portfolio', 'action' => 'detail', $smePortfolio->portfolio->portfolio_id])  ?></td>
                <td><?= $this->Html->link(h($smePortfolio->fiscal_number), [ 'action' => 'view', $smePortfolio->sme_portfolio_id]) ?></td>
                <td><?= h($smePortfolio->name) ?></td>
                <td><?= h($smePortfolio->sector) ?></td>
                <td><?= h($smePortfolio->region) ?></td>
                <td><?= $smePortfolio->nbr_employees ?></td>


                <td class="actions">
                    <?=
                    $this->Html->link(
                            '<i class="fas fa-eye"></i>' . __('View'),
                            ['action' => 'view', $smePortfolio->sme_portfolio_id],
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
            url: '/damsv2/sme-portfolio/export',
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
    
    $("#export_data").on("click", function (e) {
        e.preventDefault();
        exportData();
    });
});

if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>


