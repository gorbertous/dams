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
        'title'   => 'Analytics',
        'url'     => ['controller' => 'Analytics', 'action' => 'analytics-reports'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Mandate Performance Report',
        'url'     => ['controller' => 'Analytics', 'action' => 'mandate-performance'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
<h3>Mandate Performance Report</h3>
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
                <label class="col-sm-2 col-form-label required" for="Portfolio.Mandate">Mandate</label>
                <div class="col-6">
                  <?= $this->Form->select('Portfolio.Mandate', $mandates, [
                            'class' => 'form-control mr-2 my-2',
                            'id'   => 'prodid',
                            'empty'   => '-- Any mandate --',
							'style'	=> 'width:220px;',
                            //'default' => $this->Session->read('Form.filter_mapping.Product.product_id')
                        ]);
                        ?>
                </div>
            </div>
        <div class="row col-6 form-inline">
            <?= $this->Form->submit('Generate report', ['class' => 'btn btn-primary  mr-2 my-2 py-2']) ?>
			<?= $this->Html->link('Cancel', ['action' => 'analytics-reports'], ['class' => 'btn btn-danger form-control my-3']) ?>
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