<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Invoice $invoice
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Invoices',
        'url'     => ['controller' => 'Invoice', 'action' => 'index'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'New',
        'url'     => ['controller' => 'Invoice', 'action' => 'add', $actual_report->report_id],
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
<h3><?= __('New Invoice') ?></h3>

<?= $this->Form->create(null, ['id' => 'addForm']) ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('report_id', '#') ?></th>
                <th><?= $this->Paginator->sort('Template.template_type_id', 'Type') ?></th>
                <th><?= $this->Paginator->sort('Report.report_name', 'Report name') ?></th>
                <th><?= $this->Paginator->sort('Report.due_date', 'Due date') ?></th>
                <th><?= $this->Paginator->sort('Portfolio.owner', '<i class="fa fa-user"></i> Portfolio', ['escape' => false]) ?></th>
                <th><?= $this->Paginator->sort('Report.owner', '<i class="fa fa-user"></i> Report', ['escape' => false]) ?></th>

                <th><?= $this->Paginator->sort('', 'Amount') ?></th>
                <th><?= $this->Paginator->sort('', 'CCY') ?></th>
                <th><?= $this->Paginator->sort('', 'Status') ?></th>
                <th><?= $this->Paginator->sort('', 'Stage') ?></th>
                <th><?= $this->Paginator->sort('', 'Selection') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reports as $key => $report): ?>
                <tr id="report_<?= $report->report_id ?>">
                    <?= $this->Form->hidden('period' . $report->report_id, [
                        'id'    => 'period' . $report->report_id,
                        'value' => $report->period_year . $report->period_quarter,
                    ]);
                    ?>
                    <td><?= $report->report_id ?></td>
                    <td>
                        <?php
                            switch ($report->template->template_type_id) {
                                case '2': echo 'PD';
                                    break;
                                case '3': echo 'LR';
                                    break;
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                        if (in_array($report->status_id, [3, 8])) {
                            echo $this->Html->link($report->report_name, ['controller' => 'report', 'action' => 'pdlr-reception', $report->report_id], ['title' => 'Edit the reception of this report']);
                        } else {
                            echo $report->report_name;
                        }
                        ?>
                    </td>
                    <td><?= !empty($report->due_date) ? h($report->due_date->format('Y-m-d')) : '' ?></td>
                    <td><?= !empty($report->portfolio->v_user) ? h($report->portfolio->v_user->full_name) : '' ?></td>
                    <td><?= !empty($report->v_user) ? h($report->v_user->full_name) : '' ?></td>
                    <td style="text-align: right"><span><?= $report->amount_ctr; ?></span><?= $this->Number->precision($report->amount, 2) ?></td>

                    <td><?= $report->ccy; ?></td>
                    <td><?= $report->status->status; ?></td>
                    <td><?= $report->status->stage; ?></td>
                    <td><?php
                    if (in_array($report->status_id, [10, 11])) {
                       
                         echo $this->Form->radio('selected', [$report->report_id => $report->report_id],
                        ['id'  => 'addSelected' . $report->report_id, 'label' => false, 'class' => 'recalculate', 'hiddenField' => false]);
      
                        }/* elseif($report['Report']['status_id']==11){
                          //fake disabled checkbox (since readonly doesnt work) + hidden value
                          echo $this->Form->hidden('add.selected.'.$key, [
                          'value' => $report->report_id));
                          print '<input type="checkbox" disabled checked="checked">';
                          }; */
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<div class="form-group row">
    <div class="col-6 form-inline">
         <label class="col-sm-3 col-form-label h6">Contract currency</label>
         <div class="col-sm-3">
            <span><?= $actual_report->portfolio->currency ?></span>
        </div>
        <?= $this->Form->hidden('actual_reportid', ['value' => $actual_report->report_id])?>
        <?= $this->Form->hidden('CCY', ['value' => $actual_report->portfolio->currency])?>
        <?= $this->Form->hidden('invoice_owner', ['value' => $user_id])?>
    </div>
    <div class="col-6 form-inline">
        <div class="row col-12 alert-danger" id='invoice_error'></div>
        <label class="col-sm-3 col-form-label h6">Total in contract currency</label>
         <div class="col-sm-3">
            <span id='total_currency'></span>
            <?= $this->Form->input('total_currency', [
            'label' => false, 
            'id'   => 'total_currency', 
            'style' => "display:none;",
            'type'  => 'text'
            ])
            ?>
        </div>
        
    </div>
</div>

<div class="form-group row">
    <div class="col-6 form-inline">
        <label class="col-sm-3 col-form-label h6">Effective Cap amount</label>
         <div class="col-sm-3">
            <span id="effective_cap_amount"><?= ((empty($actual_report->portfolio->effective_cap_amount)) ? 'N/A' : $this->Number->precision($actual_report->portfolio->effective_cap_amount, 2)) ?></span>
             <?= $this->Form->input('cap_amount', [
                'value' => $actual_report->portfolio->effective_cap_amount,
                'label' => false, 
                'div'   => null, 
                'style' => "display:none;",
                'type'  => 'text'
            ])
            ?>
         </div>
       
    </div>
    <div class="col-6 form-inline">
        <label class="col-sm-3 col-form-label h6">Available Cap amount</label>
         <div class="col-sm-3">
            <span id="available_cap_amount"><?= ((empty($actual_report->portfolio->available_cap_amount)) ? '0.00' : $this->Number->precision($actual_report->portfolio->available_cap_amount, 2)) ?></span>
             <?= $this->Form->input('available_cap_amount', [
            'label' => false, 
            'id'   => 'addAvailableCapAmount', 
            'style' => "display:none;",
            'type'  => 'text',
            'value' => !empty($actual_report->portfolio->available_cap_amount) ? $actual_report->portfolio->available_cap_amount : ''
            ])
            ?>
         </div>
       
    </div>
</div>
<div class="form-group row">
    <div class="col-6 form-inline">
        <label class="col-sm-3 col-form-label h6">Period</label>
         <div class="col-sm-3">
            <span id="period_report"><?= $actual_report->period_year . '' . $actual_report->period_quarter ?></span>
            <?= $this->Form->input('period_quarter', [
                'value' => $actual_report->period_quarter,
                'label' => false, 
                'div'   => null, 
                'style' => "display:none;",
                'type'  => 'text',
            ])
            ?>

            <?= $this->Form->input('period_year', [
                'label' => false, 
                'div'   => null, 
                'style' => "display:none;",
                'type'  => 'text',
                'value' => $actual_report->period_year,
            ])
            ?>
        </div>
        
    </div>
    <div class="col-6 form-inline">
        <label class="col-sm-3 col-form-label h6">Remaining Cap amount <!--<a id="refresh_aca" class="btn btn-small" href="#"><i class="icon-refresh"></i></a>--></label>
         <div class="col-sm-3">
            <span id="av_cap_amount"></span>
            <?= $this->Form->input('remaining_cap_amount', [
                'label' => false, 
                'div'   => null, 
                'id' => 'addRemainingCapAmount',
                'style' => "display:none;",
                'type'  => 'text',
                'value' => !empty($actual_report->portfolio->remaining_cap_amount) ? $actual_report->portfolio->remaining_cap_amount : ''
            ])
            ?>
        </div>
        
    </div>
</div>
<div class="form-group row">
        <div class="col-6 form-inline">
            <label class="col-sm-3 col-form-label h6">Expected payment date</label>
            <div class="col-sm-3">
                <?= $this->Form->input('payment_date', [
                        'label' => false,
                        'class'    => 'datepicker mr-2 my-2 py-2',
                        'id' => 'addPaymentDate',
                        'required'	=> true,
                        'value' => !empty($actual_report->due_date) ? $actual_report->due_date->format('Y-m-d') : ''
                ]) ?>
            </div>
        </div>
   
        <div class="col-6 form-inline">
            <label class="col-sm-3 col-form-label h6">Last inclusion received</label>
            <div class="col-sm-3">
               <span><?= !empty($last_included->reception_date) ? h($last_included->reception_date->format('Y-m-d')) : '' ?></span>
               <?= $this->Form->input('last_inclusion_date', [
                    'label' => '', 
                    'div'   => null, 
                    'style' => "display:none;",
                    'type'  => 'text',
                    'value' => !empty($last_included->reception_date) ? $last_included->reception_date->format('Y-m-d') : ''
                ])
                ?>
            </div>
    </div>
</div>
<div class="form-group row">
    <div class="col-6 offset-6 form-inline">
        <label class="col-sm-3 col-form-label h6">Due date</label>
         <div class="col-sm-3">
            <span id="due_date_label"><?= !empty($due_date) ? $due_date->format('Y-m-d') : '' ?></span>
            <?= $this->Form->input('due_date', [
                'label' => false, 
                'id'   => 'addDueDate', 
                'style' => "display:none;",
                'type'  => 'text',
                'value' => !empty($due_date) ? $due_date->format('Y-m-d') : ''
            ])
            ?>
        </div>
    </div>
</div>
<div class="form-group row">
    <div class="col-6 form-inline">
        <label class="col-sm-3 col-form-label h6">Payment form ID</label>
         <div class="col-sm-3">
            <span id="payment_id"><?= 'PD_' . $actual_report->portfolio->portfolio_name . '_' . $actual_report->period_year . '' . $actual_report->period_quarter . '_' . $due_date ?></span>
            <?=
            $this->Form->input('portfolio_id', [
                'label' => false, 
                'div'   => null, 
                'style' => "display:none;",
                'type'  => 'text',
                'value' => $actual_report->portfolio->portfolio_id
            ])
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6 form-inline">
        <?= $this->Form->submit('Save', [
            'class'    => 'btn btn-primary form-control mr-3  my-3',
            'id'       => 'save_button',
            'disabled' => empty($actual_report->portfolio->available_cap_amount) || ($actual_report->portfolio->available_cap_amount < 0) | !$perm->hasWrite(array('controller' => 'Invoice', 'action' => 'add')),
        ])
        ?>
        <?= $this->Html->link('Cancel', ['controller' => 'report', 'action' => 'inclusion'], ['class' => 'btn btn-danger form-control my-3']) ?>

    </div>
</div>

<?= $this->Form->end(); ?>

<div class="row">
    <div class="col-6">
        <button class="btn btn-secondary" id="export_to_pdf"><i class="fas fa-file-export"></i> &nbsp;Export to PDF</button>
    </div>
</div>

<div style="display:none;">
    <?php
    //for pdf
    echo $this->Form->create(null, ['type' => 'post', 'id' => 'pdfAddForm', 'url' => '/damsv2/invoice/pdf-add/' . $actual_report->report_id]);
    echo $this->Form->input('selected_rows_id', [
        'type'  => 'text',
        'id' => 'pdfSelectedRowsId',
        
    ]);
    echo $this->Form->input('invoice_error', [
        'type'  => 'text',
        'id' => 'pdfInvoiceError',
        
    ]);
    echo $this->Form->input('expectedPaymentDate', [
        'type'  => 'text',
        'id' => 'pdfExpectedPaymentDate',
        
    ]);
    echo $this->Form->input('reception_date', [
        'type'  => 'hidden',
        'label' => false,
        'value' => !empty($last_included->reception_date) ? $last_included->reception_date->format('Y-m-d') : '',
    ]);
    echo $this->Form->input('period', [
        'type'  => 'hidden',
        'label' => false,
        
        'value' => $actual_report->period_year . '' . $actual_report->period_quarter,
    ]);
    echo $this->Form->input('available_cap_amount', [
        'type'  => 'text',
        'id'   => 'addAvailableCapAmount', 
        
        'value' => ((empty($actual_report->portfolio->available_cap_amount)) ? '0.00' : $this->Number->precision($actual_report->portfolio->available_cap_amount, 2)),
    ]);
    echo $this->Form->input('effective_cap_amount', [
        'type'  => 'text',
        'label' => false,
        
        'value' => ((empty($actual_report->portfolio->effective_cap_amount)) ? 'N/A' : $this->Number->precision($actual_report->portfolio->effective_cap_amount, 2)),
    ]);
    echo $this->Form->input('currency', [
        'type'  => 'text',
        'label' => false,
        
        'value' => $actual_report->portfolio->currency,
    ]);
    echo $this->Form->input('payment_form_id', [
        'type'  => 'text',
        'id' => 'pdfPaymentFormId',
        
    ]);
    echo $this->Form->input('due_date', [
        'type'  => 'text',
        'label' => false,
        
        'value' => !empty($due_date) ? $due_date->format('Y-m-d') : ''
    ]);
    echo $this->Form->input('total_currency', [
        'type'  => 'text',
        'id' => 'pdftotal_currency',
        
    ]);
    echo $this->Form->input('remaining_cap_amount', [
        'type'  => 'text',
        'id' => 'pdfaddRemainingCapAmount',
        
    ]);
    echo $this->Form->input('report_id', [
        'type'  => 'hidden',
        'label' => false,
        
        'value' => $actual_report->report_id,
    ]);
    
    echo $this->Form->input('portfolio_id', [
        'type'  => 'text',
        'value' => $actual_report->portfolio->portfolio_id
    ]);

    echo $this->Form->end();

    


//info form
    echo $this->Form->create(null, ['type' => 'post', 'id' => 'info', 'url' => '/damsv2/invoice/av-cap-amount/' . $actual_report->report_id]);
    echo $this->Form->input('add.selected', [
        'type'  => 'text',
        'id' => 'addSelected',
        
    ]);
    echo $this->Form->input('add.actual_reportid', [
        'type'  => 'hidden',
        'id' => 'addactual_reportid',
        
        'value' => $actual_report->report_id,
    ]);
    echo $this->Form->input('add.CCY', [
        'type'  => 'text',
        'id' => 'addCCY',
        
        'value' => $actual_report->portfolio->currency,
    ]);
    echo $this->Form->input('add.invoice_owner', [
        'type'  => 'text',
        'id' => 'addinvoice_owner',
        
    ]);
    echo $this->Form->input('add.total_currency', [
        'type'  => 'text',
        'id'   => 'total_currency', 
        
    ]);
    echo $this->Form->input('add.cap_amount', [
        'type'  => 'text',
        'id' => 'addCapAmount',
        
    ]);
    echo $this->Form->input('add.available_cap_amount', [
        'type'  => 'text',
        'id'   => 'addAvailableCapAmount', 
        
        'value' => ((empty($actual_report->portfolio->available_cap_amount)) ? '0.00' : $this->Number->precision($actual_report->portfolio->available_cap_amount, 2)),
    ]);
    echo $this->Form->input('add.period_quarter', [
        'type'  => 'text',
        'id' => 'addperiod_quarter',
        
        'value' => $actual_report->period_quarter,
    ]);
    echo $this->Form->input('add.period_year', [
        'type'  => 'hidden',
        'id' => 'addperiod_year',
        
        'value' => $actual_report->period_year,
    ]);
    echo $this->Form->input('add.remaining_cap_amount', [
        'type'  => 'text',
        'id' => 'addRemainingCapAmount',
        
    ]);
    echo $this->Form->input('add.payment_date', [
        'type'  => 'text',
        'id' => 'addPaymentDate',
        
    ]);
    echo $this->Form->input('add.last_inclusion_date', [
        'type'  => 'text',
        'id' => 'addlast_inclusion_date',
        
    ]);
    echo $this->Form->input('add.due_date', [
        'type'  => 'text',
        'id' => 'addDueDate',
        'value' => !empty($due_date) ? $due_date->format('Y-m-d') : ''
    ]);
    echo $this->Form->input('add.portfolio_id', [
        'type'  => 'text',
        'value' => $actual_report->portfolio->portfolio_id,
        
    ]);
    echo $this->Form->end();
    ?>
</div>


<?= $this->Html->script('/js/jquery.blockUI.js');?>
<script>
var values = {};
values['selected_rows_id'] = {};

var portfolio_name = "<?= $actual_report->portfolio->portfolio_name; ?>";

$(document).ready(function () {
    $("#addForm").submit(function (e)
    {
        document.getElementById('save_button').disabled = true;
    });

    $("#addPaymentDate").datepicker({
        dateFormat: "yy-mm-dd"
    });

    //several radio with different group
    $("input[type=radio]").click(function (e)
    {
        $("input[type=radio]").not($(e.target)).attr('checked', false);
    });

    $('input[type=radio] ~ label').text('');//remove label for radios

    values['reception_date'] = '<?= !empty($last_included->reception_date) ? $last_included->reception_date->format('Y - m - d') : '' ?>';
            values['period'] = '<?= $actual_report->period_year . '' . $actual_report->period_quarter; ?>';
    //values['available_cap_amount'] = '<?= ((empty($actual_report->available_cap_amount)) ? '0.00' : $this->Number->precision($actual_report->available_cap_amount, 2)); ?>';
    values['available_cap_amount'] = '<?= ((empty($actual_report->portfolio->available_cap_amount)) ? '0.00' : $this->Number->precision($actual_report->portfolio->available_cap_amount, 2)) ?>';
    //values['effective_cap_amount'] = '<?= ((empty($actual_report->effective_cap_amount)) ? 'N/A' : $this->Number->precision($actual_report->effective_cap_amount, 2)); ?>';
    values['effective_cap_amount'] = '<?= ((empty($actual_report->portfolio->effective_cap_amount)) ? 'N / A' : $this->Number->precision($actual_report->portfolio->effective_cap_amount, 2)); ?>';
    values['currency'] = '<?= $actual_report->portfolio->currency; ?>';
            values['payment_form_id'] = '<?= 'PD_' . $actual_report->portfolio->portfolio_name . '_' . $actual_report->period_year . '' . $actual_report->period_quarter . '_' . $due_date; ?>';
    values['due_date'] = '<?= $due_date; ?>';

    $(".recalculate").bind("change", function (event) {
        $.blockUI();
        $("#invoice_error").text('').removeClass('error-message');
        $('#info #addSelected').val($('input.recalculate:checked').val());
        $('#info #addPaymentDate').val($('#addForm #addPaymentDate').val());

        $.ajax({
            type: "POST",
            url: "/damsv2/invoice/av-cap-amount",
            data: $("#info").serialize(),
            headers: {'X-CSRF-TOKEN': '<?= $this->request->getAttribute('csrfToken') ?>'},
          
            success: function (data, textStatus) {
                //update the due date according to sas calculation (should be the earlier of selected reports
                var ctn = $('<div/>');
                $(ctn).html(data);

                $("#av_cap_amount").text($.trim($('#acp', ctn).text()));
                $("#addRemainingCapAmount").val($.trim($('#acp', ctn).text()));
                $("#addAvailableCapAmount").val($.trim($('#acp', ctn).text()));

                $("#due_date_label").text($.trim($('#due_date', ctn).text()));
                $("#addDueDate").val($.trim($('#due_date', ctn).text()));

                $("#total_currency").text($.trim($('#total_ccy', ctn).text()));
                $("#addTotalCurrency").val($.trim($('#total_ccy', ctn).text()));

                if ($.trim($('#Error', ctn).text())) {
                    $("#invoice_error").text($.trim($('#Error', ctn).text())).addClass('error-message');
                    //disable save button
                    $('#save_button').attr('disabled', true);
                } else
                {
                    if ($.trim($('#Message', ctn).text())) {
                        $("#invoice_error").text($.trim($('#Message', ctn).text())).addClass('error-message');
                        //disable save button
                        $('#save_button').attr('disabled', true);//added 19/10/2017
                    }
                    var valid = $.trim($('#valid', ctn).text());
                    if (valid == '1')
                    {
                        $('#save_button').attr('disabled', false);
                    }
                }

                values['due_date'] = $.trim($('#due_date', ctn).text());
                values['total_currency'] = $.trim($('#total_ccy', ctn).text());
                $("#pdfAddForm #pdftotal_currency").val($('#total_ccy', ctn).text());
                values['remaining_cap_amount'] = $.trim($('#acp', ctn).text());
                $("#pdfAddForm #pdfaddRemainingCapAmount").val($('#acp', ctn).text());
                $("#addAvailableCapAmount").val($.trim($('#acp', ctn).text()));
                values['expectedPaymentDate'] = $('#addPaymentDate').val();
                values['available_cap_amount'] = $.trim($('#available_cap_amount').text());
                values['effective_cap_amount'] = $.trim($('#effective_cap_amount').text());

                var selected_report_id = $('input[name="data[add][selected]"]:checked').val();
                var period_selected_report = $('#period' + selected_report_id).val();
                //$("#addPeriodQuarter").val(  );
                //$("#addPeriodYear").val(  );
                $('#period_report').text(period_selected_report);
                $('#payment_id').text('PD_' + portfolio_name + '_' + period_selected_report + '_' + values['due_date']);
                $.unblockUI();
            },
            error: function (xhr, status, error) {
                alert(xhr.responseText);
            }

        });
        return false;
    });
    $("#refresh_aca").trigger('click');

    tot_con_cur();
});

function addCommas(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function tot_con_cur() {
    var cumul_amount = 0;
    $("#addForm tbody input:checked").parent().parent().find('.amount span').each(function (b) {
        cumul_amount = cumul_amount + parseFloat($(this).html());
        var cumul_format = addCommas(cumul_amount.toFixed(2));
        if (cumul_format != 'NaN')
            $("#total_currency").text(cumul_format);
        $("#addTotalCurrency").val(cumul_amount);
    });
}


$("#export_to_pdf").click(function (e)
{
    e.preventDefault();
    
    $('#pdfAddForm #pdfSelectedRowsId').val($('input.recalculate:checked').val());
    $('#pdfAddForm #pdfInvoiceError').val($('#invoice_error').text());
    $('#pdfAddForm #pdfExpectedPaymentDate').val($('#addPaymentDate').val());
    
    //$("#pdfAddForm #pdftotal_currency").val($('#addForm #total_currency').val());
    //payment_form_id
    $('#pdfAddForm #pdfPaymentFormId').val($('#payment_id').text());
    $('#pdfAddForm').submit();
});
</script>
