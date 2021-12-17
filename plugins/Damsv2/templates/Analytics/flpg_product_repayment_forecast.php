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
        'title'   => 'FLPG Product Repayment Forecast',
        'url'     => ['controller' => 'Report', 'action' => 'flpg-product-repayment-forecast'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
<h3>FLPG Product Repayment Forecast</h3>
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
                <label class="col-sm-2 col-form-label required" for="Portfolio.mandate">Mandate</label>
                <div class="col-6">
                  <?= $this->Form->select('Portfolio.mandate', $mandates, [
                            'class' => 'form-control mr-2 my-2',
                            'id'   => 'mandate_id',
							'multiple' => true,
                            'empty'   => '-- Any mandate --',
                            'required'  => true,
							'style'	=> 'width:220px;',
                        ]);
                        ?>
                </div>
            </div>
            <div class="form-group row form-inline">
                <label class="col-sm-2 col-form-label required" for="Report.Date_start">Inclusion period end</label>
                <div class="col-6">
                  <?= $this->Form->input('Report.Date_start',  [
							'class'    => 'datepicker mr-2 my-2 py-2',
                            'id'   => 'date_start',
                            'required'  => true,
							'style'	=> 'width:220px;',
                        ]);
                        ?>
                </div>
            </div>
            <div class="form-group row form-inline">
                <label class="col-sm-2 col-form-label required" for="Report.Date_end">Balance sheet date</label>
                <div class="col-6">
                  <?= $this->Form->input('Report.Date_end',  [
							'class'    => 'datepicker mr-2 my-2 py-2',
                            'id'   => 'date_end',
                            'required'  => true,
							'style'	=> 'width:220px;',
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
<div style="display:none;">
<?php
echo $this->Form->create(null, ['url' => '/damsv2/ajax/getDealsByMandateUnique', 'id' => 'getdeals']);
echo $this->Form->input('Portfolio.mandate', [
	'type'  => 'text',
	'label' => false,
	'div'   => false,
	'id'	=> 'getDealsByMandate',
]);
echo $this->Form->end();
?>
</div>
<script>
    $(document).ready(function ()
	{
		$('#mandate_id').change(function(e)
		{
			$('#getdeals #getDealsByMandate').val($('#mandate_id').val());
			var data = $("#getdeals").serialize();
			$.ajax({
				async: true,
				data: data,
				type: "POST",
				url: "/damsv2/ajax/getDealsByMandateUnique",
				headers: {'X-CSRF-TOKEN': '<?= $this->request->getAttribute('csrfToken') ?>'},
				success: function (data) {
					$('#deal_id').replaceWith(data);
				}
			});
		});

        <?php if (isset($download_link)) {?>
                window.open("<?= $download_link; ?>");
        <?php } ?>
    });
</script>