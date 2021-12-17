<!DOCTYPE html>
<html>
    <head>
        <title>PDF</title> 
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

        <style>
            @page {
                margin: 0px 0px 0px 0px !important;
                padding: 0px 0px 0px 0px !important;
            }
        </style>
    </head>
    <body>
        <table class="table table">
            <thead>
                <tr class="row form-inline py-2 my-2">
                    <th class="h3">#</th>
                    <th class="h3">Type</th>
                    <th class="h3">Report name</th>
                    <th class="h3">Due date</th>
                    <th class="h3"><i class="fa fa-user"></i>Portfolio</th>
                    <th class="h3"><i class="fa fa-user"></i>Report</th>
                    <th class="h3">Amount</th>
                    <th class="h3">CCY</th>
                    <th class="h3">Status</th>
                    <th class="h3">Stage</th>
                    <th class="h3">Selection</th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($reports as $report): ?>
                    <tr class="row form-inline py-2 my-2">
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
                        <td><?= $report->report_name ?></td>
                        <td><?= !empty($report->due_date) ? h($report->due_date->format('Y-m-d')) : '' ?></td>
                        <td><?= !empty($report->portfolio->v_user) ? h($report->portfolio->v_user->full_name) : '' ?></td>
                        <td><?= !empty($report->v_user) ? h($report->v_user->full_name) : '' ?></td>
                        <td><span><?= $report->amount_ctr; ?></span><?= $this->Number->precision($report->amount, 2) ?></td>

                        <td><?= $report->ccy; ?></td>
                        <td><?= $report->status->status; ?></td>
                        <td><?= $report->status->stage; ?></td>
                        <td><?php
                            if ($report->report_id == $values['selected_rows_id']) {
                                echo '<div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" disabled checked="checked"></div>';
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <table class="table table">
            <tr class="row form-inline py-2 my-2">
                <td>
                    <div>Contract currency : <?php echo $values['currency'] ?></div>
                </td>
                <td>
                    <div id='invoice_error' <?php
                    if (strlen($values['invoice_error']) > 0) {
                        echo 'style="padding: 8px 35px 8px 14px;
                        text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
                        border: 1px solid #fbeed5;
                        -webkit-border-radius: 4px;
                        -moz-border-radius: 4px;
                        border-radius: 4px;
                        margin-bottom: 5px;
                        color: #b94a48;
                        font-size: 12px;
                        background-color: #f2dede;
                        border-color: #eed3d7;"';
                    }
                    ?>><?php echo $values['invoice_error'] ?>
                    </div>
                    <div>
                        Total in contract currency : <span id='total_currency'><?php echo $values['total_currency'] ?></span>
                    </div>
                </td>
            </tr>

            <tr class="row form-inline py-2 my-2">
                <td>
                    <div>
                        Effective Cap amount : 
                        <span><?php echo $values['effective_cap_amount']; ?></span>
                    </div>
                </td>
                <td>
                    <div class="d-inline">
                        Available Cap amount : 
                        <span><?php echo $values['available_cap_amount']; ?></span>
                    </div>
                </td>
            </tr>
            <tr class="row form-inline py-2 my-2">
                <td>
                    <div>
                       Period : 
                        <span><?php echo $values['period']; ?></span>
                    </div>
                </td>
                <td>
                    <div>
                        Remaining Cap amount : 
                        <span id="av_cap_amount"><?php echo $values['remaining_cap_amount'] ?></span>
                    </div>
                </td>
            </tr>
            <tr class="row form-inline py-2 my-2">
                <td>
                    <div>
                        Expected payment date : 
                        <span><?php echo $values['expectedPaymentDate']; ?></span>
                    </div>
                </td>
                <td>
                    <div>
                        Last inclusion received : 
                        <span><?php echo $values['reception_date'] ?></span>
                    </div>
                </td>
            </tr>
            <tr class="row form-inline py-2 my-2">
                <td>
                    <div>
                        Payment form ID : 
                        <span><?php echo $values['payment_form_id']; ?></span>
                    </div>
                </td>
                <td>
                    <div>
                        Due date : 
                        <span id="due_date_label"><?php echo $values['due_date']; ?></span>
                    </div>
                </td>
            </tr>
        </table>

    </body>
</html>