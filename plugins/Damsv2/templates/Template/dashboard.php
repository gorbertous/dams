<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Report[]|\Cake\Collection\CollectionInterface $report
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Dashboard',
        'url'     => ['controller' => 'Template', 'action' => 'dashboard'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
<h3>Templates</h3>
<hr>

<?= $this->Form->create(null, [ 'id' => 'TemplatesDashboardForm'])?>

<?= $this->Form->hidden('Portfolio.portfolio_empty', [
    'value' => '-- Portfolio --'
])
?>

<div class="form-group row form-inline">
    <label class="col-sm-2 col-form-label required" for="ProductName">Product</label>
    <div class="col-6">
       <?= $this->Form->select('Product.product_id', $products,
        [
            'empty' => '-- Product --',
            'class' => 'form-control mr-2 my-2 w-50',
            //'value' => $this->request->getQuery('product_id'),
            'id'    => 'productid',
			'style'	=> 'width:220px;',
        ]
        );
        ?>
    </div>
</div>
<div class="form-group row form-inline">
    <label class="col-sm-2 col-form-label required" for="PortfolioName">Portfolio</label>
    <div class="col-6">
        <?= $this->Form->select('Portfolio.portfolio_id', $portfolios,
        [
            'empty' => '-- Portfolio --',
            'class' => 'w-25 form-control mr-2 my-2 w-50',
            'required' => true,
            'id'    => 'portfolioid',
			'style'	=> 'width:220px;',
        ]
        );
        ?>
    </div>
</div>
<div class="row col-6 form-inline">
    <?= $this->Form->submit('Display templates', ['class' => 'btn btn-primary  mr-2 my-2 py-2']) ?>
    <?= $this->Html->link('Clear', ['url' => '/'], ['class' => 'btn btn-secondary ml-2 my-2 py-2']) ?>
</div>
<?= $this->Form->end(); ?>


<div style="display:none;">
    <?php
    echo $this->Form->create(null, ['url' => '/damsv2/ajax/getPortfoliosByProductAllPortfolio', 'id' => 'getportfoliosDashboardForm']);
    echo $this->Form->input('Product.product_id', [
        'type'  => 'text',
        'label' => false,
        'id'   => 'productid',
    ]);
    echo $this->Form->input('Portfolio.portfolio_empty', [
        'type'  => 'hidden',
        'label' => false,
        'div'   => false,
        'value' => '-- Portfolio --',
    ]);
    echo $this->Form->end();
    ?>
</div>
<?php
    if ($post == true && count($templates) > 0) {
       
        echo '<div class="row col-6 py-2" id="files" name="files">';
        foreach ($templates as $type => $url) {
            echo '<div class="row col-12">';
            echo '<label class="col-sm-6 col-form-label h6">' . $type . '</label>';
            if ($url =="") {
                echo '<span class="error">('.__('Template not found').')</span>';
            } else {
                echo $this->Html->link("Download", $url, ['escape' => true, 'target' => '_blank']);
            }
            echo '</div>';
        }
        echo '</div>';
    }
?>

<p class="py-5">Note: Fields marked in red are mandatory.</p>

<script>
    $(document).ready(function (e) {

        $("#productid").bind("change", function (event) {
            $('#files').hide();
        });
        $("#PortfolioPortfolioId").bind("change", function (event) {
            $('#files').hide();
        });

        $("#productid").bind("change", function (event) {
            $('#getportfoliosDashboardForm #productid').val($('#TemplatesDashboardForm  #productid').val());
            var data = $("#getportfoliosDashboardForm").serialize();
            $.ajax({
                type: "POST",
                url: "/damsv2/ajax/getPortfoliosByProductAllPortfolio",
                async: true,
                data: data,
                headers: {'X-CSRF-TOKEN': '<?= $this->request->getAttribute('csrfToken') ?>'},
                success: function (data) {
                    $('#TemplatesDashboardForm #portfolioid').html(data);
                }
            });
        });
    });
</script>