
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
        'title'   => 'Template summary',
        'url'     => ['controller' => 'Template', 'action' => 'mapping-view'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);

?>
<h3>Template summary</h3>
<hr>
<div class="row col-9 py-3">
    This report allows you to extract the templates configuration by applying several filters (all filters are optional).<br />
    Non-existing combination will result in an empty report (e.g. field not existing in the selected template).<br /><br />
</div>
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
                <label class="col-sm-2 col-form-label" for="Product.product_id">Product</label>
                <div class="col-6">
                  <?= $this->Form->select('Product.product_id', $products, [
                            'class' => 'form-control mr-2 my-2 w-50',
                            'id'   => 'prodid',
							'empty'   => '-- Any product --',
							'style'	=> 'width:293px;',
                            //'default' => $this->Session->read('Form.filter_mapping.Product.product_id')
                        ]);
                        ?>
                </div>
            </div>
            
            <div class="form-group row form-inline">
                <label class="col-sm-2 col-form-label" for="Portfolio.portfolio_id">Portfolio</label>
                <div class="col-6">
                    <?= $this->Form->select('Portfolio.portfolio_id', $portfolios,
                    [
                        'empty' => '-- Portfolio --',
                        'class' => 'w-50 form-control mr-2 my-2 w-50',
                        'label'   => false,
                        'id'    => 'portfolioid',
						'style'	=> 'width:293px;',
                    ]);
                    ?>
                </div>
            </div>
        
            <div class="form-group row form-inline">
                <label class="col-sm-2 col-form-label" for="Template.template_id">Template name</label>
                <div class="col-6">
                   <?= $this->Form->select('Template.template_id', $templates,[
                        'label'   => false,
                        'class' => 'w-50 form-control mr-2 my-2 w-50',
                        'empty'   => '-- Any template --',
                        'id'      => 'templid',
						'style'	=> 'width:293px;',
                        //'default' => $this->Session->read('Form.filter_mapping.Template.template_id')
                    ]);
                    ?>
                </div>
            </div>
        
            <div class="form-group row form-inline">
                <label class="col-sm-2 col-form-label" for="Template.template_type_id">Template type</label>
                <div class="col-6">
                    <?= $this->Form->select('Template.template_type_id',$template_types, [
                        'label'   => false,
                        'class' => 'w-50 form-control mr-2 my-2 w-50',
                        'empty'   => '-- Any template type --',
                        'id'      => 'templtypeid',
						'style'	=> 'width:293px;',
                        //'default' => $this->Session->read('Form.filter_mapping.Template.template_type_id')
                    ]);
                    ?>
                </div>
            </div>
        
            <div class="form-group row form-inline">
                <label class="col-sm-2 col-form-label" for="Mapping_table.table_name">Table</label>
                <div class="col-6">
                      <?= $this->Form->select('Mapping_table.table_name',$tables, [
                            'label'   => false,
                            'class' => 'form-control mr-2 my-2 w-50',
                            'empty'   => '-- Any table --',
                            'id'      => 'tableid',
							'style'	=> 'width:293px;',
                            //'default' => $this->Session->read('Form.filter_mapping.Mapping_table.table_name')
                        ]);
                        ?>
                </div>
            </div>
            
            <div class="form-group row form-inline">
                <label class="col-sm-2 col-form-label" for="MappingColumn.table_field">Field</label>
                <div class="col-6">
                        <?= $this->Form->select('MappingColumn.table_field', $columns,[
                            'label'   => false,
                            'class' => 'form-control mr-2 my-2 w-50',
                            'empty'   => '-- Any column --',
                            'id'      => 'colid',
							'style'	=> 'width:293px;',
                            //'default' => $this->Session->read('Form.filter_mapping.MappingColumn.table_field')
                        ]);
                        ?>
                </div>
            </div>
        
        <div class="row col-6 form-inline">
            <?= $this->Form->submit('Submit', ['class' => 'btn btn-primary  mr-2 my-2']) ?>           
        </div>

        <?= $this->Form->end() ?>
   
    </div>
</div>
<div style="display:none;">
    <?php
        echo $this->Form->create(null, ['url' => '/damsv2/ajax/getPortfoliosByProduct', 'id' => 'getportfolios']);
        echo $this->Form->input('Product.product_id', [
            'type'  => 'text',
            'id'    => 'prodid',
            'label' => false,
            'div'   => false,
        ]);
        echo $this->Form->input('Portfolio.portfolio_empty', [
            'type'  => 'hidden',
            'label' => false,
            'div'   => false,
            'value' => '-- Portfolio --',
        ]);
        echo $this->Form->end();

        echo $this->Form->create(null, ['url' => '/damsv2/ajax/getFieldsMapping', 'id' => 'getFields']);
        echo $this->Form->input('Mapping_table.table_name', [
            'type'  => 'text',
            'id'    => 'tableid',
            'label' => false,
            'div'   => false,
        ]);
        echo $this->Form->input('MappingColumn.table_field', [
            'type'  => 'text',
            'id'    => 'colid',
            'label' => false,
            'div'   => false,
        ]);
        echo $this->Form->end();
    ?>
</div>
<script>
    $(document).ready(function () {
        $("#prodid").bind("change", function () {
            $('#getportfolios #prodid').val($('#FiltersForm  #prodid').val());
            var data = $("#getportfolios").serialize();
            $.ajax({
                async: true,
                data: data,
                type: "POST",
                url: "/damsv2/ajax/getPortfoliosByProduct",
                headers: {'X-CSRF-TOKEN': '<?= $this->request->getAttribute('csrfToken') ?>'},
                success: function (data) {
                    $('#FiltersForm #portfolioid').html(data);
                }
            });
        });
        
        $("#tableid").bind("change", function () {
            $('#getFields #tableid').val($('#FiltersForm  #tableid').val());
            $('#getFields #colid').val($('#FiltersForm  #colid').val());
            var data = $("#getFields").serialize();
            $.ajax({
                async: true,
                data: data,
                type: "POST",
                url: "/damsv2/ajax/getFieldsMapping",
                headers: {'X-CSRF-TOKEN': '<?= $this->request->getAttribute('csrfToken') ?>'},
                success: function (data) {
                    $('#FiltersForm #colid').html(data);
                }
            });
        });

        <?php if (isset($download_link)) {?>
                window.open("<?= $download_link; ?>");
        <?php } ?>

    });
</script>