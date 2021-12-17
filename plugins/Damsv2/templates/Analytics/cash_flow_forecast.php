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
        'title'   => 'Guarantee Calls Forecast',
        'url'     => ['controller' => 'Report', 'action' => 'cash-flow-forecast'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
<h3>Guarantee Calls Forecast</h3>
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
                            'empty'   => '-- Any mandate --',
							'required' => true,
							'style'	=> 'width:220px;',
                            //'default' => $this->Session->read('Form.filter_mapping.Product.product_id')
                        ]);
                        ?>
                </div>
            </div>
            <div class="form-group row form-inline">
                <label class="col-sm-2 col-form-label required" for="Report.inclusion_period_end">Inclusion period end</label>
                <div class="col-6">
                  <?= $this->Form->input('Report.inclusion_period_end',  [
							'class'    => 'datepicker mr-2 my-2 py-2',
                            'id'   => 'inclusion_period_end',
							'required' => true,
							'style'	=> 'width:220px;',
                        ]);
                        ?>
                </div>
            </div>
			<p>Choose the end of the last finalised reporting period. Future inclusions will be estimated as from this date.</p>
            <div class="form-group row form-inline">
                <label class="col-sm-2 col-form-label" for="Report.pipeline_amount_1">Pipeline amount 1</label>
                <div class="col-6">
                  <?= $this->Form->input('Report.pipeline_amount_1',  [
							'class'    => 'mr-2 my-2 py-2',
                            'id'   => 'pipeline_amount_1',
							'style'	=> 'width:220px;',
                        ]);
                        ?>
                </div>
            </div>
			<p>Please insert a pipeline cap amount (EL amount) in EUR (optional).</p>
            <div class="form-group row form-inline">
                <label class="col-sm-2 col-form-label" for="Report.inclusion_start_pipe_1">Inclusion start for pipeline amount 1</label>
                <div class="col-6">
                  <?= $this->Form->input('Report.inclusion_start_pipe_1',  [
							'class'    => 'datepicker mr-2 my-2 py-2',
                            'id'   => 'inclusion_start_pipe_1',
							'style'	=> 'width:220px;',
                        ]);
                        ?>
                </div>
            </div>
			<p>Please insert the inclusion start date for pipeline amount 1 (mandatory if an entry was made in pipeline amount 1).</p>
            <div class="form-group row form-inline">
                <label class="col-sm-2 col-form-label" for="Report.inclusion_end_pipe_1">Inclusion end for pipeline amount 1</label>
                <div class="col-6">
                  <?= $this->Form->input('Report.inclusion_end_pipe_1',  [
							'class'    => 'datepicker mr-2 my-2 py-2',
                            'id'   => 'inclusion_end_pipe_1',
							'style'	=> 'width:220px;',
                        ]);
                        ?>
                </div>
            </div>
			<p>Please insert the inclusion end date for pipeline amount 1 (mandatory if an entry was made in pipeline amount 1).</p>
            <div class="form-group row form-inline">
                <label class="col-sm-2 col-form-label" for="Report.pipeline_amount_2">Pipeline amount 2</label>
                <div class="col-6">
                  <?= $this->Form->input('Report.pipeline_amount_2',  [
							'class'    => 'mr-2 my-2 py-2',
                            'id'   => 'pipeline_amount_2',
							'style'	=> 'width:220px;',
                        ]);
                        ?>
                </div>
            </div>
			<p>Please insert a pipeline cap amount (EL amount) in EUR? This entry is useful if a different inclusion period to what has been chosen for pipeline amount (optional).</p>
            <div class="form-group row form-inline">
                <label class="col-sm-2 col-form-label" for="Report.inclusion_start_pipe_2">Inclusion start for pipeline amount 2</label>
                <div class="col-6">
                  <?= $this->Form->input('Report.inclusion_start_pipe_2',  [
							'class'    => 'datepicker mr-2 my-2 py-2',
                            'id'   => 'inclusion_start_pipe_2',
							'style'	=> 'width:220px;',
                        ]);
                        ?>
                </div>
            </div>
			<p>Please insert the inclusion start date for pipeline amount 2 (mandatory if an entry was made in pipeline amount 2).</p>
            <div class="form-group row form-inline">
                <label class="col-sm-2 col-form-label" for="Report.inclusion_end_pipe_2">Inclusion end for pipeline amount 2</label>
                <div class="col-6">
                  <?= $this->Form->input('Report.inclusion_end_pipe_2',  [
							'class'    => 'datepicker mr-2 my-2 py-2',
                            'id'   => 'inclusion_end_pipe_2',
							'style'	=> 'width:220px;',
                        ]);
                        ?>
                </div>
            </div>
			<p>Please insert the inclusion end date for pipeline amount 2 (mandatory if an entry was made in pipeline amount 2).</p>
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