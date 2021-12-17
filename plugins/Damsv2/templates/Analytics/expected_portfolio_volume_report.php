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
        'title'   => 'Forecasts',
        'url'     => ['controller' => 'Analytics', 'action' => 'forecast-reports'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Active Portfolio Monitoring Report',
        'url'     => ['controller' => 'Analytics', 'action' => 'expected-portfolio-volume-report'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
<h3>Expected Portfolio Volume Report</h3>
<hr>
<div class="row">
    <div class="col-9">
        <?php if(!empty($msg)) :  ?>
        <div class="alert alert-error alert-dismissible fade show" role="alert">
            <strong>Error!</strong> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
         </div>
        <?php endif; ?>
        <?= $this->Form->create(null, ['id' => 'FiltersForm']); ?>
            <div class="form-group row form-inline">
                <label class="col-sm-2 col-form-label required" for="Product.product_id">Product</label>
                <div class="col-6">
                  <?= $this->Form->select('Product.product_id', $products, [
                            'class' => 'form-control mr-2 my-2',
                            'id'   => 'prodid',
							'multiple' => true,
                            'empty'   => '-- Any product --',
							'style'	=> 'width:220px;',
                            //'default' => $this->Session->read('Form.filter_mapping.Product.product_id')
                        ]);
                        ?>
                </div>
            </div>
        <div class="row col-6 form-inline">
            <?= $this->Form->submit('Generate report', ['class' => 'btn btn-primary  mr-2 my-2 py-2']) ?>
			<?= $this->Html->link('Cancel', ['action' => 'forecast-reports'], ['class' => 'btn btn-danger form-control my-3']) ?>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>
<script>
    $(document).ready(function ()
	{
        <?php if (isset($download_link)) {?>
                window.open("<?= $download_link; ?>");
        <?php } ?>
    });
</script>