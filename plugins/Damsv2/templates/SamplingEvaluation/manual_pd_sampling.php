<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SamplingEvaluation $samplingEvaluation
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Manually Sampled PDs',
        'url'     => ['controller' => 'sampling-evaluation', 'action' => 'manual-pd-sampling'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
<h3>Manually Sampled PDs</h3>
<hr>

<?= $this->Form->create(null, ['id' => 'manuallysampledpd']) ?>
<div class="row form-inline">
    <div class="col-2">
        <?= $this->Form->label('Product.product_id', 'Product', ['class' => 'h6 required']) ?>
    </div>
    <div class="col-4">
        <?= $this->Form->select('Product.product_id', $products,
                [
                    'empty'    => '-Any product-',
                    'class'    => 'w-50 form-control mr-2 my-2',
                    'label'    => false,
                    'id'       => 'productid',
                    'required' => true
                ]
        );
        ?>
    </div>
</div>
<div class="row form-inline">
    <div class="col-2">
        <?= $this->Form->label('Portfolio.portfolio_id', 'Portfolio', ['class' => 'h6']) ?>
    </div>
    <div class="col-4">
        <?= $this->Form->select('Portfolio.portfolio_id', $portfolios,
                [
                    'empty' => '-Any portfolio-',
                    'class' => 'w-50 form-control mr-2 my-2',
                    'label' => false,
                    'id'    => 'portfolioid'
                ]
        );
        ?>
    </div>
</div>
<div class="row form-inline">
    <div class="col-2">
        <?= $this->Form->label('year', 'Year', ['class' => 'h6']) ?>
    </div>
    <div class="col-4">
        <?= $this->Form->select('year', $year_list,
                [
                    'class' => 'w-50 form-control mr-2 my-2',
                    'label' => false,
                    'id'    => 'yearlist'
                ]
        );
        ?>
    </div>
</div>
<div class="row form-inline">
    <div class="col-2">
        <?= $this->Form->label('month', 'Month', ['class' => 'h6']) ?>
    </div>
    <div class="col-4">
        <?= $this->Form->select('month', $month_list,
                [
                    'class' => 'w-50 form-control mr-2 my-2',
                    'label' => false,
                    'id'    => 'monthlist'
                ]
        );
        ?>
    </div>
</div>
<div class="row form-inline">
    <div class="col-2">
        <?= $this->Form->label('type', 'Sampling type', ['class' => 'h6']) ?>   
    </div>
    <div class="col-4">
        <?= $this->Form->select('type', $types,
                [
                    'class' => 'w-50 form-control mr-2 my-2',
                    'label' => false,
                    'id'    => 'types'
                ]
        );
        ?>
    </div>
</div>
<div class="row form-inline">
    <div class="col-2">
        <?= $this->Form->label('finding', 'Sampling finding', ['class' => 'h6']) ?>  
    </div>
    <div class="col-4">
        <?= $this->Form->select('finding', $findings,
                [
                    'class' => 'w-50 form-control mr-2 my-2',
                    'label' => 'Sampling finding',
                    'id'    => 'findings'
                ]
        );
        ?>
    </div>
</div>
<div class="row">
    <div class="col-2">
        <?= $this->Form->submit('Extract PDs', ['class' => 'btn btn-primary mr-2 my-2 py-2']); ?>
    </div>
</div>
<?= $this->Form->end(); ?>


<div class="row">
    <div class="col-6">
    <?php
    if (isset($result)) {
        echo $result;
    }
    ?>
    </div>
</div>


<script>
    $(document).ready(function () {
        $("#manuallysampledpd").submit(function (e)
        {
            $(":submit").prop('disabled', true);//prevent several submission
        });

        $("#productid").on('change', function () {
            var formData = $('#manuallysampledpd').serialize();
            $.ajax({
                url: '/damsv2/ajax/getPortfoliosByProductAllPortfolio',
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