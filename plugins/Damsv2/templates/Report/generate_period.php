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
        'title'   => 'Generate Period',
        'url'     => ['controller' => 'Report', 'action' => 'generate-period'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
<h3>Generate Period</h3>
<hr>
 <?= $this->Form->create(null, ['id' => 'generateform']) ?>
<div id="message_container"></div>
    <div class="form-group row">
        <div class="col-6 form-inline">
        <?= $this->Form->label('Product.product_id', 'Product', ['class' => 'col-sm-3 col-form-label h6 required']) ?>
        <?= $this->Form->select('Product.product_id', $products,
                [
                    'empty' => '-- Product --',
                    'class' => 'form-control ml-2 my-2',
                    'required'  => true,
                    'id'    => 'productid',
                    'style' => 'width:220px;'
                ]
        );
        ?>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-6 form-inline">
        <?= $this->Form->label('Portfolio.portfolio_id', 'Portfolio', ['class' => 'col-sm-3 col-form-label h6']) ?>
        <?= $this->Form->select('Portfolio.portfolio_id', [],
                [
                    'empty'	=> '-- Select a portfolio --',
                    'class' => 'form-control ml-2 my-2',
                    'id'    => 'portfolioid',
                    'style' => 'width:220px;'
                ]
        );
        ?>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-6 form-inline">
        <?= $this->Form->label('Report.period', 'Report period', ['class' => 'col-sm-3 col-form-label h6']) ?>
        <?= $this->Form->select('Report.period', [],
                [
                    'class' => 'form-control ml-2 my-2',
                    'id'    => 'reportperiod',
                    'style' => 'width:220px;'
                ]
        );
        ?>

        <?= $this->Form->control('Report.year', [
            'label'   => '',
            'class'   => 'form-control ml-2 my-2',
            'type'    => 'year',
            'max'     => date('Y', time()) + 1,
            'min'     => date('Y', time()) - 4,
            'id'      => 'reportyear',
            'default' => date('Y', time()),
            'style' => 'width:220px;',
			'required' => true,
        ]);
        ?>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-6 form-inline">
        <?= $this->Form->label('Report.report_type', 'Report type', ['class' => 'col-sm-3 col-form-label h6']) ?>
        <?= $this->Form->select('Report.report_type', ['regular' => 'Regular', 'closure' => 'Closure'], ['class' => 'form-control ml-2 my-2', 'style' => 'width:220px;' ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-6 form-inline">
        <?= $this->Form->submit('Generate period', [
            'class' => 'btn btn-primary form-control mr-3  my-3',
            'id'    => 'save_button'
        ])
        ?>
        <?= $this->Html->link('Cancel', ['controller' => 'home', 'action' => 'home'], ['class' => 'btn btn-secondary form-control mb-2 my-3']) ?>
        </div>     
    </div>      
  
  <?= $this->Form->end(); ?>
<div style="display:none;">
    <?php
    echo $this->Form->create(null, ['url' => '/damsv2/ajax/getInclusionMaxPeriod', 'id' => 'datamaxperiod']);
    echo $this->Form->input('Product.product_id', [
        'type'  => 'text',
        'label' => false,
        'id'   => 'productid',
    ]);
    echo $this->Form->input('Report.year', [
        'type'  => 'text',
        'label' => false,
        'id'   => 'reportyear',
    ]);
    echo $this->Form->input('Report.period', [
        'type'  => 'text',
        'label' => false,
        'div'   => false,
    ]);
    echo $this->Form->end();


    echo $this->Form->create(null, ['url' => '/damsv2/ajax/getPortfoliosAndUmbrellaByProduct', 'id' => 'dataportfolios']);
    echo $this->Form->input('Product.product_id', [
        'type'  => 'text',
        'label' => false,
        'id'   => 'productid',
    ]);
    echo $this->Form->input('Portfolio.portfolio_empty', [
        'type'  => 'hidden',
        'value' => '--All Portfolios--'
    ]);
    echo $this->Form->end();

    echo $this->Form->create(null, ['url' => '/damsv2/ajax/getPeriodsByProduct', 'id' => 'dataperiods']);
    echo $this->Form->input('Product.product_id', [
        'type'  => 'text',
        'label' => false,
        'id'   => 'productid',
    ]);
    echo $this->Form->input('Portfolio.portfolio_empty', [
        'type'  => 'hidden',
        'value' => '--All Portfolios--'
    ]);
    echo $this->Form->end();
	
    echo $this->Form->create(null, ['url' => '/damsv2/ajax/getperiodsdoublereport', 'id' => 'getperiodsdoublereportGeneratePeriodForm']);
	echo $this->Form->input('Report.product_id', array(
		'type' => 'text',
		'label'	=> false,
		'div'	=> false,
	));
	echo $this->Form->input('Report.portfolio_id', array(
		'type' => 'text',
		'label'	=> false,
		'div'	=> false,
	));
	echo $this->Form->input('Report.period_quarter', array(
		'type' => 'text',
		'label'	=> false,
		'div'	=> false,
	));
	echo $this->Form->input('Report.period_year', array(
		'type' => 'text',
		'label'	=> false,
		'div'	=> false,
	));
	echo $this->Form->end();
    ?>
</div>

<script>
$(document).ready(function () {

    $('#generateform #reportperiod').find('option').remove();
    $('#generateform #portfolioid').find('option').remove();
//    $("#portfolioid").change(function (event) {
//        if ($("#portfolioid").val().indexOf('u_') !== -1)
//        {
//            $("#status_report_umbrella").show();
//        } else
//        {
//            $("#status_report_umbrella").hide();
//        }
//
//    });

    $('#productid, #reportperiod, #reportyear').change(check_period);
	$('#productid, #portfolioid, #reportperiod, #reportyear, select[name="Report[report_type]"]').change(check_period_double);

    $('#generateform  #productid').change(function ()
    {
        $('#dataportfolios #productid').val($('#generateform  #productid').val());
        $.ajax({
            async: true,
            data: $("#dataportfolios").serialize(),
            dataType: "text",
            type: "post",
            url: "/damsv2/ajax/getPortfoliosAndUmbrellaByProduct",
            headers: {'X-CSRF-TOKEN': '<?= $this->request->getAttribute('csrfToken') ?>'},
            success: function (data) {
                $('#generateform #portfolioid').html(data);
            }
        });
    });

    $('#generateform  #productid').change(function ()
    {
        $('#dataperiods #productid').val($('#generateform  #productid').val());
        $.ajax({
            async: true,
            data: $("#dataperiods").serialize(),
            dataType: "text",
            type: "post",
            url: "/damsv2/ajax/getPeriodsByProduct",
            headers: {'X-CSRF-TOKEN': '<?= $this->request->getAttribute('csrfToken') ?>'},
            success: function (data) {
                $('#reportperiod').html(data);
            }
        });
    });
   
});

function check_period()
{
    $('#datamaxperiod #productid').val($('#generateform #productid').val());
    $('#datamaxperiod #reportyear').val($('#generateform #reportyear').val());
    $('#datamaxperiod #reportperiod').val($('#generateform #reportperiod').val());
    $.ajax({
        async: true,
        data: $("#datamaxperiod").serialize(),
        dataType: "text",
        type: "post",
        url: "/damsv2/ajax/getInclusionMaxPeriod",
        headers: {'X-CSRF-TOKEN': '<?= $this->request->getAttribute('csrfToken') ?>'},
        success: function (data) {
            if (data === "true")
            {
                // cannot save
                block_submitting(true);
                show_message_future(true);
            } else
            {
                // can save
                block_submitting(false);
                show_message_future(false);
            }
        }
    });
}

function show_message_future(show)
{
    if (show)
    {
        if ($('#future_message').length < 1)
        {
            $("#generateform").find(".btn-primary").before("<p id='future_message' style='color: red; font-size: medium'>Report cannot be generated for the future period.</p>");
        }
    } else
    {
        $('#future_message').remove();
    }
}

function show_warning_double_report(show)
{
	if (show)
	{
		if ($('#warning_double_report').length < 1)
		{
			$("#message_container").append("<div id='warning_double_report' class='alert alert-danger alert-dismissible' style='margin-top:10px;'><b>Warning</b>: There is already a report included for the same reporting period. Please double-check if generating a new report under the same period is needed.</div>");
		}
	}
	else
	{
		$('#warning_double_report').remove();
	}
}
function block_submitting(block)
{
    document.getElementById("save_button").disabled = block;
}
function check_period_double(e)
{
	var report_type_regular = ($('#generateform select[name="Report[report_type]"]').val() == 'regular');
	var portfolio_id = $('#generateform #portfolioid').val();
	if (report_type_regular && (portfolio_id != ''))
	{
		$('#getperiodsdoublereportGeneratePeriodForm input[name="Report[product_id]"]').val( $('#generateform #productid').val() );
		$('#getperiodsdoublereportGeneratePeriodForm input[name="Report[portfolio_id]"]').val( portfolio_id );
		$('#getperiodsdoublereportGeneratePeriodForm input[name="Report[period_quarter]"]').val( $('#generateform #reportperiod').val() );
		$('#getperiodsdoublereportGeneratePeriodForm input[name="Report[period_year]"]').val( $('#generateform #reportyear').val() );
		$('#getperiodsdoublereportGeneratePeriodForm input[name="Report[report_type]"]').val( $('#generateform select[name="Report[report_type]"]').val() );
		$.ajax({
			async:true,
			data:$("#getperiodsdoublereportGeneratePeriodForm").serialize(),
			dataType:"text",
			success:function (data, textStatus) {
				if (data == "true")
				{
					show_warning_double_report(true);
				}
				else
				{
					show_warning_double_report(false);
				}
			},
			type:"post",
			url:"/damsv2/ajax/getperiodsdoublereport"
		});
	}
	else
	{
		show_warning_double_report(false);
	}
}
</script>

