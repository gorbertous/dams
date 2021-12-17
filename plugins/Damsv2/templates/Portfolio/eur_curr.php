<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Portfolio $portfolio
 */
$port_title = !empty($portfolio->deal_name) ? $portfolio->deal_name : 'Statistics';
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
        'title'   => 'Eur and contract currency equivalencies calculation',
        'url'     => ['controller' => 'Portfolio', 'action' => 'eur-curr'],
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


<?php if (!isset($result)): ?>

    <h3>Eur and contract currency equivalencies calculation</h3>
    <hr>

    <?= $this->Form->create(null, ['class' => 'form-inline', 'id' => 'filter']) ?>

    <?=
    $this->Form->select('Product.product_id', $products,
            [
                'empty' => '-- Product --',
                'class' => 'form-control mr-2 my-2 filter',
                //'value' => $this->request->getQuery('product_id'),
                'id'    => 'productid'
            ]
    );
    ?>
    <?= $this->Form->input('Portfolio.portfolio_empty', ['type' => 'hidden', 'default' => "-- Portfolio --"]); ?>
    <?=
    $this->Form->select('Portfolio.portfolio_id', $portfolios,
            [
                'empty'    => '-- Portfolio --',
                'class'    => 'w-25 form-control mr-2 my-2 filter',
                //'value' => $this->request->getQuery('portfolio_id'),
                'id'       => 'portfolioid',
                'required' => true,
            ]
    );
    ?>
    <?=
    $this->Form->select('table', $tables,
            [
                'empty'    => '-- Table --',
                'class'    => 'form-control mr-2 my-2 filter',
                //'value' => $this->request->getQuery('portfolio_id'),
                'id'       => 'tableid',
                'required' => true,
            ]
    );
    ?>
    <?= $this->Form->submit('Recalculate', ['class' => 'btn btn-primary', 'id' => 'submit_id']) ?>
    <?= $this->Form->end() ?>


<?php else: ?>
    <h3>Result</h3>
    <hr>
    <?= $result ?>
    <?= $this->Form->create(null, ['class' => 'form-inline', 'id' => 'Pages']) ?>
    <?= $this->Form->hidden('store'); ?>
    <?= $this->Form->input('Portfolio.portfolio_id', ['type' => 'hidden', 'value' => $portfolio_id]); ?>
    <?= $this->Form->submit('Store in database', ['class' => 'btn btn-primary']) ?>
    <?= $this->Form->end() ?>
    </fieldset>
<?php endif; ?>

<script>
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