<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Report $report
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Payment Demands/Recoveries dashboard',
        'url'     => ['controller' => 'Report', 'action' => 'pdlr'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Reception of Payment Demand / Recovery',
        'url'     => ['controller' => 'Report', 'action' => 'pdlr-reception'],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-inv-crumb'
            ]
        ]
    ]
]);

?>

<h3>Reception of Payment Demand / Recovery</h3>
<hr>
<div class="row">
    <div class="col-12">
        <?= $this->Form->create($report, ['id' => 'ReportPdlrReceptionForm',
            'context' => [
                'validator' => [
                    'Report' => 'default'
                ]
            ]
            
        ]) ?>
            
        <?= $this->Form->input('Report.version_number', ['type' => 'hidden', 'value' => $default_version_number]) ?>

        <div class="form-group row form-inline">
            <label class="col-sm-2 col-form-label h6 required" for="Template.template_type_id">Report Type</label>
            <div class="col-6">
                <?= $this->Form->radio('Template.template_type_id', [2 => 'Payment Demand', 3 => 'Loss Recovery'],
                    ['required' => true, 'default'   => $default_type, 'id' => 'templateType', 'class' => 'ml-3 py-2'])
                ?>
               
            </div>
        </div>
        <div class="form-group row form-inline">
            <label class="col-sm-2 col-form-label h6 required" for="Product.product_id">Product Name</label>
            <div class="col-6">
                <?= $this->Form->select('Product.product_id', $products,
                    [
                        'empty'    => '-- Product --',
                        'class' => 'form-control mr-2 my-2 py-2 w-25',
                        'id'       => 'ProductId',
                        'default'   => $default_product,
                        'required' => true,
						'style' => 'width: 220px',
                    ]
                );
                ?>
            </div>
        </div>
        <div class="form-group row form-inline">
            <label class="col-sm-2 col-form-label h6 required" for="Report.portfolio_id">Portfolio Name</label>
            <div class="col-6">
                <?= $this->Form->select('Report.portfolio_id', $portfolios,
                    [
                        'empty'    => '-- Portfolio --',
                        'class' => 'form-control mr-2 my-2 w-25',
                        'label'    => false,
                        'value' => $default_portfolio,
                        'id'       => 'PortfolioName',
                        'required' => true,
						'style' => 'width: 220px',
                    ]
                );
                ?>
            </div>
        </div>
        <div class="form-group row form-inline">
            <label class="col-sm-2 col-form-label h6 required" for="Report.reception_date">Reception Date</label>
            <div class="col-6">
                <?= $this->Form->control('Report.reception_date', [
                    'label'    => false,
                    'class'    => 'form-control datepicker mr-2 my-2 py-2 w-25',
                    'type'     => 'text',
                    'id'       => 'ReportReceptionDate',
                    'required' => true,
                    'value'    => empty($default_reception) ? '' : $default_reception->format('Y-m-d'),
						'style' => 'width: 220px',
                ]);
                ?>
            </div>
        </div>
        <div class="form-group row form-inline">
            <label class="col-sm-2 col-form-label h6 required" for="Report.period">Report period</label>
            <div class="col-6">
                <?= $this->Form->select('Report.period_quarter', ['Q1' => 'Q1', 'Q2' => 'Q2', 'Q3' => 'Q3', 'Q4' => 'Q4'],
                    [
                        'empty'    => '-- Quarter --',
                        'class' => 'form-control mr-2 my-2',
                        'label'    => false,
                        'value' => $default_quarter,
                        'id'       => 'ReportPeriodQuarter',
                        'required' => true,
						'style' => 'width: 90px',
                    ]
                );
                ?>
           
                 <?= $this->Form->input('Report.period_year', [
                        'empty'    => '-- Year --',
                        'class' => 'form-control mr-2 my-2',
                        'value' => $default_year,
                        'id'    => 'ReportPeriodYear',
                        'type'  => 'year',
                        'max'	=> date('Y', time())+1,
                        'min'	=> date('Y', time())-3,                       
                        'required' => true,
						'style' => 'width: 97px',
                        
                    ]);
                ?>
            </div>
        </div>
        <div class="form-group row form-inline">
            <label class="col-sm-2 col-form-label h6 required" for="Report.due_date">Indicative due date</label>
            <div class="col-6">
                <?= $this->Form->control('Report.due_date', [
                    'type'    => 'text',
                    'label'    => false,
                    'class'    => 'form-control datepicker mr-2 my-2 py-2 w-25',
                    'id'    => 'ReportDueDate',
                    'value' => empty($default_due) ? '' : $default_due->format('Y-m-d'),
                    'required' => true,
						'style' => 'width: 220px',
                ])
                ?>
            </div>
        </div>
        
         <div class="form-group row form-inline">
            <label class="col-sm-2 col-form-label h6 required" for="Report.ccy">Currency</label>
            <div class="col-6">
                <?= $this->Form->select('Report.ccy', $currencies,
                    [
                        'empty'    => '-- Currency --',
                        'class' => 'form-control mr-2 my-2 w-25',
                        'label'    => '',
                        'value' => $default_currency,
                        'id'       => 'ReportCCY',
                        'required' => true,
						'style' => 'width: 220px',
                    ]
                );
                ?>
            </div>
        </div>
          <div class="form-group row form-inline">
              <label class="col-sm-2 col-form-label h6" for="gsstage">GS Stage of the Portfolio</label>
            <div class="col-6">
                <span id="gs_stage"></span>
            </div>
        </div>
        <div class="form-group row form-inline">
            <label class="col-sm-2 col-form-label h6" for="ctrccy">Contract currency</label>
            <div class="col-6">
                <span id="ctr_ccy"></span>
            </div>
        </div>
        
        <div class="form-group row form-inline">
            <label class="col-sm-2 col-form-label h6" for="Report.amount">Amount in currency<span class="text-danger">&nbsp;*</span></label>
            <div class="col-6">
                <?= $this->Form->control('Report.amount', [
                    'type'    => 'text',
                    'label'    => false,
                    'class'    => 'form-control mr-2 my-2 w-25',
                    'value' => $default_amount,
                    'id'       => 'ReportAmount',
                    'required' => true,
						'style' => 'width: 220px',
                ])
                ?>
            </div>
        </div>
         <div class="form-group row form-inline">
             <label class="col-sm-2 col-form-label h6" for="Report.amount_ctr">Amount in contract currency</label>
            <div class="col-6">
                <?= $this->Form->control('Report.amount_ctr', [
                    'type'    => 'text',
                    'label'    => false,
                    'class'    => 'form-control mr-2 my-2 w-25',
                    'value' => $default_amount_ctr,
                    'id'       => 'ReportAmountCtr',
                    'disabled' => true,
						'style' => 'width: 220px',
                ])
                ?>
            </div>
        </div>
        <div class="form-group row form-inline" id="clawback_box" style="display:none;">
            <label class="col-sm-2 col-form-label h6" for="Report.clawback">Clawback</label>
            <div class="col-6">
                <?= $this->Form->control('Report.clawback', [
                    'type'    => 'checkbox',
                    'label'    => false,
                    'class'    => 'form-control mr-2 my-2',
                    'id'       => 'clawback_checkbox',
                ])
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6 form-inline">
                <?php
				$class = 'btn btn-primary form-control mr-3  my-3';
				if (!$perm->hasWrite(array('action' => 'pdlrReception')))
				{
					$class = 'btn btn-primary form-control mr-3  my-3 disabled';
				}
				echo $this->Form->submit('Save', [
                    'class'    => $class,
                    'id'    => 'save_button',
					'disabled' => !$perm->hasWrite(array('action' => 'pdlrReception')),
                ]);
                ?>
                <?= $this->Html->link('Cancel', ['action' => 'pdlr'], ['class' => 'btn btn-danger form-control my-3']) ?>
                   
            </div>
        </div>
        
        <?= $this->Form->end() ?>

    </div>
</div>


<div style="display:none;">
    <?php
    
    echo $this->Form->create(null, array('url' => '/damsv2/ajax/pdlr-reception', 'id' => 'portfolioPdlrReceptionForm'));
    echo $this->Form->input('Product.product_id', array(
        'type'  => 'text',
        'id'    => 'ProductProductId',
        
    ));
    echo $this->Form->input('Portfolio.portfolio_id', array(
        'type'  => 'text',
        'id'    => 'PortfolioPortfolioId',
        
    ));
    echo $this->Form->end();

    echo $this->Form->create(null, array('url' => '/damsv2/report/check-portfolio-template', 'id' => 'check_templatePdlrReceptionForm'));
    echo $this->Form->input('Product.product_id', array(
        'type'  => 'text',
        'id'    => 'ProductProductId',
        
    ));
    echo $this->Form->input('Portfolio.portfolio_id', array(
        'type'  => 'text',
        'id'    => 'PortfolioPortfolioId',
        
    ));
    echo $this->Form->input('Portfolio.type', array(
        'type'  => 'text',
        'id'    => 'PortfolioType',
        
    ));
    echo $this->Form->end();

    echo $this->Form->create(null, array('url' => '/damsv2/report/check-portfolio-recovery-rate', 'id' => 'check_recovery_ratePdlrReceptionForm'));
    echo $this->Form->input('Product.product_id', array(
        'type'  => 'text',
        'id'    => 'ProductProductId',
        
    ));
    echo $this->Form->input('Portfolio.portfolio_id', array(
        'type'  => 'text',
        'id'    => 'PortfolioPortfolioId',
        
    ));
    echo $this->Form->input('Portfolio.type', array(
        'type'  => 'text',
        'id'    => 'PortfolioType',
        
    ));
    echo $this->Form->end();

    echo $this->Form->create(null, array('url' => '/damsv2/ajax/pdlrMaxPeriod','id' => 'max_periodPdlrReceptionForm'));
    echo $this->Form->input('Product.product_id', array(
        'type'  => 'text',
        'id'    => 'ProductProductId',
        
    ));
    echo $this->Form->input('Report.period_quarter', array(
        'type'  => 'text',
        'id'    => 'ReportPeriodQuarter',
        
    ));
    echo $this->Form->input('Report.period_year', array(
        'type'  => 'text',
        'id'    => 'ReportPeriodYear',
        
    ));
    echo $this->Form->end();

    echo $this->Form->create(null, array('url' => '/damsv2/ajax/getAmountCcy', 'id' => 'amoutccyPdlrReceptionForm'));
    echo $this->Form->input('Report.product_id', array(
        'type'  => 'text',
        'id'    => 'ProductProductId',
        
    ));
    echo $this->Form->input('Report.portfolio_id', array(
        'type'  => 'text',
        'id'    => 'ReportPortfolioId',
        
    ));
    echo $this->Form->input('Report.amount', array(
        'type'  => 'text',
        'id'    => 'ReportAmount',
        
    ));
    echo $this->Form->input('Report.ccy', array(
        'type'  => 'text',
        'id'    => 'ReportCcy',
        
    ));
    echo $this->Form->end();

    echo $this->Form->create(null, array('url' => '/damsv2/ajax/portfolioHasPDLR', 'id' => 'haspdlrPdlrReceptionForm'));

    echo $this->Form->input('Portfolio.portfolio_id', array(
        'type'  => 'text',
        'id'    => 'PortfolioPortfolioId',
        
    ));
    echo $this->Form->end();

    echo $this->Form->create(null, array('url' => '/damsv2/ajax/currenciesHaveRate', 'id' => 'currRatePdlrReceptionForm'));
    echo $this->Form->input('portfolio_id', array(
        'type'  => 'text',
        'id'    => 'currRatePortfolioId',
        
    ));
    echo $this->Form->input('report_curr', array(
        'type'  => 'text',
        'id'    => 'currRateReportCurr',
        
    ));
    echo $this->Form->input('contract_curr', array(
        'type'  => 'text',
        'id'    => 'currRateContractCurr',
        
    ));
    echo $this->Form->end();
    ?>
</div>

<script>
    $(document).ready(function () {
    $('#ReportReceptionDate, #ReportDueDate, #ReportFXRateDate').datepicker({
        dateFormat: "yy-mm-dd"
    });
    $('#ReportAmount').autoNumeric('init', {aSep: ',', aDec: '.', mDec: 2, vMin: -999999999999999999999, vMax: 999999999999999999999});
    $('#ReportAmountCtr').autoNumeric('init', {aSep: ',', aDec: '.', mDec: 2, vMin: -99999999999999999, vMax: 999999999999999999999});
    $(" form").bind("change", function (event) {
        generate_amount_ccy();
    });

    $("#ReportCCY").bind('change', function (e)
    {
        currencies_have_rate();
    });

    $("#ReportPdlrReceptionForm #ProductId").bind("change", function (event) {
        $('#portfolioPdlrReceptionForm #ProductProductId').val($('#ReportPdlrReceptionForm #ProductId').val());
        $('#portfolioPdlrReceptionForm #PortfolioPortfolioId').val($('#ReportPdlrReceptionForm #PortfolioName').val());
        var data = $('#portfolioPdlrReceptionForm').serialize();
        $.ajax({
            url: '/damsv2/ajax/pdlr-reception',
            type: 'POST',
            data: data,
            dataType: 'json',
            context: $(this)
        }).done(function (data) {
            if(data) {
                try {
                    //let obj = JSON.parse(data);
                    $('#PortfolioName')
                    .find('optgroup')
                    .remove()
                    .end();
                    $('#PortfolioName')
                    .find('option')
                    .remove()
                    .end()
                    .append('<option>-- Portfolio --</option>');

                    $("#ctr_ccy, #gs_stage").text('');

                    $.each(data.results.portfolios, function (key, value) {
                        $('#PortfolioName')
                                .append($("<option></option>")
                                        .attr("value", value.portfolio_id)
                                        .text(value.portfolio_name));
                    });
                } catch(e) {
                    alert(e); // error in the above string (in this case, yes)!
                    console.log(data.results.portfolios);
                }
            }
            
            //var obj = $.parseJSON(data);
            
            
        });

    });
    $("#PortfolioName").bind("change", function (event) {
        $('#portfolioPdlrReceptionForm #ProductProductId').val($('#ReportPdlrReceptionForm #ProductId').val());
        $('#portfolioPdlrReceptionForm #PortfolioPortfolioId').val($('#ReportPdlrReceptionForm #PortfolioName').val());
        var data = $('#portfolioPdlrReceptionForm').serialize();
        $.ajax({
            url: '/damsv2/ajax/pdlr-reception',
            type: "POST",
            data: data,
            dataType: 'json',
            context: $(this)
        }).done(function (data) {
            console.log(data.results.portfolio);
            //var obj = $.parseJSON(data);
            $("#ctr_ccy").text(data.results.portfolio.currency);
            currencies_have_rate();
            $("#gs_stage").text(data.results.portfolio.gs_deal_status);
        });

    });


    $("#PortfolioName").bind("change", function (event) {
        $('#haspdlrPdlrReceptionForm #PortfolioPortfolioId').val($('#ReportPdlrReceptionForm #PortfolioName').val());
        var data = $('#haspdlrPdlrReceptionForm').serialize();
        $.ajax({
            url: '/damsv2/ajax/portfolioHasPDLR',
            type: "POST",
            data: data,
            context: $(this)
        }).done(function (data) {
            var obj = $.parseJSON(data);
            if (obj.hasLR)
            {
                $("#PortfolioName3, label[for='PortfolioName3']").css('display', 'inline-block');
            } else
            {
                $("#PortfolioName3").prop('checked', false).attr("checked", false).removeAttr("checked");
                $("#PortfolioName3, label[for='PortfolioName3']").css('display', 'none');
            }
            if (obj.hasPD)
            {
                $("#templateType-2, label[for='templateType-2']").css('display', 'inline-block');
            } else
            {
                $("#templateType-2").prop('checked', false).attr("checked", false).removeAttr("checked");
                $("#templateType-2, label[for='templateType-2']").css('display', 'none');
            }

            checkPortfolioTemplate(event);
        });

    });

    $("#ReportAmount").keyup(function (event) {
        generate_amount_ccy();
    });

    if (!$("#ReportDueDate").val()) {
        generate_due_date();
    }

    $("#ReportPeriodQuarter, #ReportPeriodYear, #ProductId, #ReportReceptionDate").bind("change", function (event) {
        generate_due_date();
    });


    $("#ReportAmount").bind("change", function (event) {
        //generate_due_date();
    });

    $("fieldset form").submit(function (event) {

        $("fieldset form [required]").each(function () {
            error = true;
            if ($(this).attr('id')) {
                if ($(this).val()) {
                    $(this).css('border-color', 'rgb(204, 204, 204)');
                    error = false;
                } else {
                    $(this).css('border', '1px solid red');
                    error = true;
                    return false;
                }
            }
        });

        if (error) {
            $(".alert").show().fadeOut(4000);
            return false;
        }

    });
    function toggleClawBack(e)
    {
        if ($('#templateType-2:checked').length)
        {
            $('#clawback_box').show();
        } else
        {
            $('#clawback_box').hide();
        }
    }
    $('input[name="Template[template_type_id]"]').bind('click', toggleClawBack);

    $('input[name="data[Template][template_type_id]"]').bind('click', checkPortfolioTemplate);
    function checkPortfolioTemplate(e) {
        $('#portfolioalert').remove();

        $('#check_templatePdlrReceptionForm #PortfolioType').val($('input[name="data[Template][template_type_id]"]:checked:visible').val());
        $('#check_templatePdlrReceptionForm #PortfolioPortfolioId').val($('#ReportPdlrReceptionForm #PortfolioName').val());
        $('#check_templatePdlrReceptionForm #ProductProductId').val($('#ReportPdlrReceptionForm #ProductId').val());
        var data = $('#check_templatePdlrReceptionForm').serialize();
        $.ajax({
            async: true,
            data: data,
            dataType: "html",
            success: function (data, textStatus) {
                $('#portfolioalert').remove();
                if (data) {
                    $('#ReportPdlrReceptionForm').before('<div id="portfolioalert"/>');
                    $('#portfolioalert').html(data);
                }
            },
            type: "post",
            url: "/damsv2/report/check_portfolio_template"
        });
    }

    $('input[name="data[Template][template_type_id]"], #PortfolioName').bind('click', checkPortfolioRecoveryRate);
    function checkPortfolioRecoveryRate(e) {
        $('#portfolioalertRecoveryRate').remove();
        $('#check_recovery_ratePdlrReceptionForm #ProductProductId').val($('#ReportPdlrReceptionForm #ProductId').val());
        $('#check_recovery_ratePdlrReceptionForm #PortfolioPortfolioId').val($('#ReportPdlrReceptionForm #PortfolioName').val());
        $('#check_recovery_ratePdlrReceptionForm #PortfolioType').val($('input[name="data[Template][template_type_id]"]:checked').val());
        var data = $('#check_recovery_ratePdlrReceptionForm').serialize();
        $.ajax({
            async: true,
            data: data,
            dataType: "html",
            success: function (data, textStatus) {
                $('#portfolioalertRecoveryRate').remove();
                if (data) {
                    $('#ReportPdlrReceptionForm').before('<div id="portfolioalertRecoveryRate"/>');
                    $('#portfolioalertRecoveryRate').html(data);
                    recovery_rate_block = true;
                } else
                {
                    recovery_rate_block = false;
                }
                block_submitting(true);
            },
            type: "post",
            url: "/damsv2/report/check_portfolio_recovery_rate"
        });
    }

    $('#ProductId, #ReportPeriodQuarter, #ReportPeriodYear').change(check_period);
});

var recovery_rate_block = false;
var period_block = false;
function check_period(e)
{
    $('#max_periodPdlrReceptionForm #ProductProductId').val($('#ReportPdlrReceptionForm ProductId').val());
    $('#max_periodPdlrReceptionForm #ReportPeriodQuarter').val($('#ReportPdlrReceptionForm ReportPeriodQuarter').val());
    $('#max_periodPdlrReceptionForm #ReportPeriodYear').val($('#ReportPdlrReceptionForm ReportPeriodYear').val());
    var data = $('#max_periodPdlrReceptionForm').serialize();
    $.ajax({
        async: true,
        data: data,
        dataType: "text",
        success: function (data, textStatus) {
            if (data == 'true')
            {
                // cannot save
                period_block = true;
                block_submitting(true);
                show_message_future(true);
            } else
            {
                // can save
                period_block = false;
                block_submitting(false);
                show_message_future(false);
            }
        },
        type: "post",
        url: "/damsv2/ajax/pdlrMaxPeriod"
    });
}


function show_message_future(show)
{
    if (show)
    {
        if ($('#future_message').length < 1)
        {
            $("#ReportPdlrReceptionForm").find(".btn-primary").before("<p id='future_message' style='color: red; font-size: medium'>Report cannot be generated for the future period.</p>");
        }
    } else
    {
        $('#future_message').remove();
    }
}

function block_submitting(block)
{
    document.getElementById("save_button").disabled = (period_block || currency_block || recovery_rate_block);
}


function pad(number) {
    if (number < 10) {
        return '0' + number;
    }
    return number;
}

Date.prototype.toISOString = function () {
    return this.getUTCFullYear() +
            '-' + pad(this.getUTCMonth() + 1) +
            '-' + pad(this.getUTCDate())
};


function is_leap_year(year)
{
    return ((year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0);
}

function generate_due_date()
{
    if (($("#ProductId option:selected").val() == ''))
    {
        $("#ReportDueDate").val('');
        return false;
    }

    $("#ReportDueDate").val('');
    var new_due_date = '';

    switch ($("#ProductId").val())
    {
        case "1":// FLPG
            switch ($("#ReportPeriodQuarter").val()) {
                case 'Q1':
                    new_due_date = $("#ReportPeriodYear").val() + '-06-29';
                    break;
                case 'Q2':
                    new_due_date = $("#ReportPeriodYear").val() + '-09-29';
                    break;
                case 'Q3':
                    new_due_date = $("#ReportPeriodYear").val() + '-12-30';
                    break;
                case 'Q4':
                    var year = parseInt($("#ReportPeriodYear").val()) + 1;
                    new_due_date = year + '-04-01';
                    if (is_leap_year(year))
                    {
                        new_due_date = year + '-03-31';
                    }
            }
            break;
        case "2"://WB GF
        case "6":// COSME
        case "10":// ERASMUS
        case "13":// EASI
        case "15":// CCS
        case "16":// WB2
            var reception_date = $("#ReportReceptionDate").val();
            if (reception_date == "")
            {
                return 0;
            }
            var splitted = reception_date.split('-');
            var month = splitted[1] - 1;// 0 -> january, 2 -> february etc...
            var day = splitted[2];
            var year = splitted[0];

            day = parseInt(day) + 60;
            var date_due_date = new Date(year, month, day);
            new_due_date = date_due_date.getFullYear() + '-' + (date_due_date.getMonth() + 1) + '-' + date_due_date.getDate();
            break;
        case "8":// progress FMA
            switch ($("#ReportPeriodQuarter").val()) {
                case 'Q1':
                    new_due_date = $("#ReportPeriodYear").val() + '-05-31';
                    break;
                case 'Q2':
                    new_due_date = $("#ReportPeriodYear").val() + '-08-31';
                    break;
                case 'Q3':
                    new_due_date = $("#ReportPeriodYear").val() + '-11-30';
                    break;
                case 'Q4':
                    var year = parseInt($("#ReportPeriodYear").val()) + 1;
                    new_due_date = year + '-02-28';
                    if (is_leap_year(year))
                    {
                        new_due_date = year + '-02-29';
                    }
            }

            if (new_due_date) {
                var splitted = new_due_date.split('-');
                var year = parseInt(splitted[0]);
                var month = splitted[1] + 1;
                if ($("#ReportPeriodQuarter").val() == 'Q4')
                    year = year + 1;
                $("#ReportDueDate").val(year + "-" + month + "-" + splitted[2]);
            }
            break;
        case "3"://RSI
        case "5"://InnoFin
        case "12"://SMEi
            switch ($("#ReportPeriodQuarter").val()) {
                case 'Q1':
                    new_due_date = $("#ReportPeriodYear").val() + '-06-29';
                    break;
                case 'Q2':
                    new_due_date = $("#ReportPeriodYear").val() + '-09-29';
                    break;
                case 'Q3':
                    new_due_date = $("#ReportPeriodYear").val() + '-12-30';
                    break;
                case 'Q4':
                    var year = parseInt($("#ReportPeriodYear").val()) + 1;
                    new_due_date = year + '-03-21';
                    if (is_leap_year(year))
                    {
                        new_due_date = year + '-03-20';
                    }
            }
            break;
        case "14"://GAGF
        case "24"://DCFTA
        case "11"://CIP
            var reception_date = $("#ReportReceptionDate").val();
            if (reception_date == "")
            {
                return 0;
            }
            var splitted = reception_date.split('-');
            var month = splitted[1];
            var day = splitted[2];
            var year = splitted[0];

            month = parseInt(month) + 2 - 1;// january => 0, february => 1, etc...

            var date_due_date = new Date(year, month, 15, 12);// day 15 to avoid overlapping a month, 12 to avoid overlapping a day with timezones
            date_due_date.setUTCHours(18);
            var month_added = date_due_date.getMonth();
            var date_due_date_tmp = new Date(year, month, day, 12);
            var month_added_tmp = date_due_date_tmp.getMonth();
            if (month_added != month_added_tmp)//if we already overlap a month (31 dec 2016 -> 3 march 2017 instead of 28 february 2017)
            {
                date_due_date_tmp = new Date(year, month + 1, 0, 12);//last day of the month
            }

            new_due_date = date_due_date_tmp.toISOString();// local time issues
            new_due_date = new_due_date.split('T')[0];
            break;
        default:
        case 'Q1':
            new_due_date = $("#ReportPeriodYear").val() + '-03-31';
            break;
        case 'Q2':
            new_due_date = $("#ReportPeriodYear").val() + '-06-30';
            break;
        case 'Q3':
            new_due_date = $("#ReportPeriodYear").val() + '-09-30';
            break;
        case 'Q4':
            new_due_date = $("#ReportPeriodYear").val() + '-12-31';
            break;
    }
    if (new_due_date) {
        var splitted = new_due_date.split('-');
        var add_60d = new Date(splitted[0], splitted[1] - 1, splitted[2]);
        //add_60d.setDate(add_60d.getDate()+60);
        var add_y = add_60d.getFullYear();
        var add_m = add_60d.getMonth() + 1;
        var add_d = add_60d.getDate();
        if (add_m < 10)
            add_m = '0' + add_m;
        if (add_d < 10)
            add_d = '0' + add_d;// leading 0 mandatory for IE
        $("#ReportDueDate").val(add_y + "-" + add_m + "-" + add_d);
    }

}

function generate_amount_ccy() {
    $('#amoutccyPdlrReceptionForm #ReportProductId').val($('#ReportPdlrReceptionForm #ProductId').val());
    $('#amoutccyPdlrReceptionForm #ReportPortfolioId').val($('#ReportPdlrReceptionForm #PortfolioName').val());
    $('#amoutccyPdlrReceptionForm #ReportAmount').val($('#ReportPdlrReceptionForm #ReportAmount').val());
    $('#amoutccyPdlrReceptionForm #ReportCcy').val($('#ReportPdlrReceptionForm #ReportCCY').val());
    var data = $("#amoutccyPdlrReceptionForm").serialize();
    $.ajax({
        async: true,
        data: data,
        dataType: "html",
        success: function (data, textStatus) {
            $("#ReportAmountCtr").autoNumeric('set', data);
        },
        type: "post", url: "/damsv2/ajax/getAmountCcy"
    });
}

var currency_block = false;

function currencies_have_rate()
{
    var report_curr = $("#ReportCCY").val();
    var contract_curr = $.trim($("#ctr_ccy").text());
    var portfolio_id = $("#PortfolioName").val();
    //unblock save button
    currency_block = false;
    block_submitting(false);
    $("#error_curr").remove();
    if ((report_curr != '') && (contract_curr != '') && (report_curr != contract_curr))
    {
        //var data = {portfolio_id: portfolio_id, report_curr: report_curr, contract_curr: contract_curr};
        $('#currRatePdlrReceptionForm #currRatePortfolioId').val(portfolio_id);
        $('#currRatePdlrReceptionForm #currRateReportCurr').val(report_curr);
        $('#currRatePdlrReceptionForm #currRateContractCurr').val(contract_curr);
        var data = $('#currRatePdlrReceptionForm').serialize();
        $.ajax({
            async: true,
            data: data,
            dataType: "json",
            success: function (data, textStatus)
            {
                console.log(data.result.rate_report);
                if (!data.result.rate_report || !data.result.rate_contract)
                {
                    var error_msg = $('<span id="error_curr">&nbsp;&nbsp;&nbsp;The relevant FX rate is missing in the database for this portfolio.</span>');
                    $("#ReportAmount").after(error_msg);
                    //block save button
                    currency_block = true;
                    block_submitting(true);
                }
            },
            type: "post", url: "/damsv2/ajax/currencies-have-rate"
        });
    }
}

</script>
