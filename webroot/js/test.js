$(document).ready(function () {
    $('#ReportReceptionDate, #ReportDueDate, #ReportFXRateDate').datepicker({
        dateFormat: "yy-mm-dd"
    });
    $('#ReportAmount').autoNumeric('init', {aSep: ',', aDec: '.', mDec: 2, vMin: -999999999999999999999, vMax: 999999999999999999999});
    $('#ReportAmountCtr').autoNumeric('init', {aSep: ',', aDec: '.', mDec: 2, vMin: -99999999999999999, vMax: 999999999999999999999});
    $("fieldset form").bind("change", function (event) {
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
            url: '/ajax/pdlr-reception',
            type: "POST",
            data: data,
            context: $(this)
        }).done(function (data) {
            var obj = $.parseJSON(data);

            $('#PortfolioName')
                    .find('optgroup')
                    .remove()
                    .end()
                    ;
            $('#PortfolioName')
                    .find('option')
                    .remove()
                    .end()
                    .append('<option>-- Portfolio --</option>')
                    ;

            $("#ctr_ccy, #gs_stage").text('');

            $.each(obj.portfolios, function (key, value) {
                $('#PortfolioName')
                        .append($("<option></option>")
                                .attr("value", value.Portfolio.portfolio_id)
                                .text(value.Portfolio.portfolio_name))
                        ;
            });
        });

    });
    $("#PortfolioName").bind("change", function (event) {
        $('#portfolioPdlrReceptionForm #ProductProductId').val($('#ReportPdlrReceptionForm #ProductId').val());
        $('#portfolioPdlrReceptionForm #PortfolioPortfolioId').val($('#ReportPdlrReceptionForm #PortfolioName').val());
        var data = $('#portfolioPdlrReceptionForm').serialize();
        $.ajax({
            url: '/ajax/pdlr-reception',
            type: "POST",
            data: data,
            context: $(this)
        }).done(function (data) {
            var obj = $.parseJSON(data);
            $("#ctr_ccy").text(obj.portfolio.Portfolio.currency);
            currencies_have_rate();
            $("#gs_stage").text(obj.portfolio.Portfolio.gs_deal_status);
        });

    });


    $("#PortfolioName").bind("change", function (event) {
        $('#haspdlrPdlrReceptionForm #PortfolioPortfolioId').val($('#ReportPdlrReceptionForm #PortfolioName').val());
        var data = $('#haspdlrPdlrReceptionForm').serialize();
        $.ajax({
            url: '/ajax/portfolioHasPDLR',
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
                $("#PortfolioName2, label[for='PortfolioName2']").css('display', 'inline-block');
            } else
            {
                $("#PortfolioName2").prop('checked', false).attr("checked", false).removeAttr("checked");
                $("#PortfolioName2, label[for='PortfolioName2']").css('display', 'none');
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
        if ($('#PortfolioName2:checked').length)
        {
            $('#clawback_box').show();
        } else
        {
            $('#clawback_box').hide();
        }
    }
    $('input[name="data[Template][template_type_id]"]').bind('click', toggleClawBack);

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
            url: "/damsv2/reports/check_portfolio_template"
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
            url: "/damsv2/reports/check_portfolio_recovery_rate"
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
        url: "/ajax/PDLRMaxPeriod"
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
        type: "post", url: "/ajax/getAmountCcy"
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
                if (!data.rate_report || !data.rate_contract)
                {
                    var error_msg = $('<span id="error_curr">&nbsp;&nbsp;&nbsp;The relevant FX rate is missing in the database for this portfolio.</span>');
                    $("#ReportAmount").after(error_msg);
                    //block save button
                    currency_block = true;
                    block_submitting(true);
                }
            },
            type: "post", url: "/ajax/currencies-have-rate"
        });
    }
}