<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Portfolio $portfolio
 */
$port_title = !empty($portfolio->deal_name) ? $portfolio->deal_name : 'Statistics';
$port_id = !empty($portfolio->portfolio_id) ? $portfolio->portfolio_id : 0;
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
        'title'   => 'Details',
        'url'     => ['controller' => 'Portfolio', 'action' => 'detail', $port_id],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-port-crumb'
            ]
        ]
    ],
    [
        'title'   => $port_title,
        'url'     => ['controller' => 'Portfolio', 'action' => 'statistics'],
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
<h3>Portfolio Summary Statistics</h3>
<hr>

<?php if (!isset($result)): ?>

    <?= $this->Form->create(null, ['class' => 'form-inline', 'id' => 'filter']) ?>

    <?= $this->Form->select('Product.product_id', $products,
            [
                'empty' => '-- Product --',
                'class' => 'form-control mr-2 my-2 filter',
                //'value' => $this->request->getQuery('product_id'),
                'id'    => 'productid'
            ]
    );
    ?>
    <?= $this->Form->input('Portfolio.portfolio_empty', ['type' => 'hidden', 'default' => "-- Portfolio --"]); ?>
    <?= $this->Form->select('Portfolio.portfolio_id', $portfolios,
            [
                'empty'    => '-- Portfolio --',
                'class'    => 'w-25 form-control mr-2 my-2 filter',
                //'value' => $this->request->getQuery('portfolio_id'),
                'id'       => 'portfolioid',
                'required' => true,
            ]
    );
    ?>
    <?= $this->Form->submit('View statistics', ['class' => 'btn btn-primary', 'id' => 'submit_id']) ?>
    <?= $this->Form->end() ?>

    <script>
        $("#filter").submit(function ()
        {
            document.getElementById("submit_id").disabled = true;
        });
        
        <!-- Ajax script to fill portfolio and quarters from product -->
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
        });
    </script>


<?php else: ?>
    <div class="mr-2">Viewed on <?= $date; ?></div>
    <div class="row-cols-12" id="printableArea">
            
            <?php echo $result; ?>
     </div>
    <?= $this->Html->link('Back', ['action' => 'detail', $portfolio->portfolio_id], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Html->link(__('Export to PDF'), ['action' => 'pdf', $portfolio->portfolio_id ],['class' => 'btn btn-secondary']) ?>
  
    <button class="btn btn-secondary" onclick="printDiv('printableArea')"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
    <?php
    if (!empty($apv_breakdown_path)) {
        echo '<a style="margin-left:20px" class="btn btn-secondary" target="_blank" href="/damsv2/ajax/download-file/' . $apv_breakdown_path . '/reports">APV breakdown</a>';
    }
    ?>
    
    <script>
        
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
       }
    
    </script>
   
<?php endif ?>

