<!DOCTYPE html>
<html>
    <head>
        <title>PDF</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
        <style>
            @page {
                margin: 0px 0px 0px 0px !important;
                padding: 0px 0px 0px 0px !important;
            }
        </style>
    </head>
    <body>

        <h1>PD/LR validation</h1>
        
        <div class="form-group row form-inline">
            <label class="col-sm-2 col-form-label h6" for="type">Report type</label>
            <div class="col-6">
                <?= $this->Form->input('type', array(
                    'label'    => false,
                    'type'     => 'text',
                    'class'    => 'form-control ml-2 my-2',
                    'value'    => $report->template->template_type_id == 2 ? "PD" : "LR",
                    'disabled' => true,
                ));
                ?>
            </div>
        </div>
        <div class="form-group row form-inline">
            <label class="col-sm-2 col-form-label h6" for="repid">Report ID</label>
            <div class="col-6">
                <?= $this->Form->input('repid', array(
                    'label'    => false,
                    'type'     => 'text',
                    'class'    => 'form-control ml-2 my-2',
                    'value'    => $report->report_name,
                    'disabled' => true,
                ));
                ?>

            </div>
        </div>
        <div class="form-group row form-inline">
            <label class="col-sm-2 col-form-label h6" for="portid">Portfolio ID</label>
            <div class="col-6">
                <?= $this->Form->input('portid', array(
                     'label'    => false,
                     'type'     => 'text',
                     'class'    => 'form-control ml-2 my-2',
                     'value'    => $report->portfolio->portfolio_name,
                     'disabled' => true,
                 ));
                 ?>
            </div>
        </div>
        <div class="form-group row form-inline">
            <label class="col-sm-2 col-form-label h6" for="concur">Contract currency</label>
            <div class="col-6">
                <?= $this->Form->input('concur', array(
                        'label'    => false,
                        'type'     => 'text',
                        'class'    => 'form-control ml-2 my-2',
                        'value'    => $report->portfolio->currency,
                        'disabled' => true,
                    ));
                ?>
            </div>
        </div>
        <div class="form-group row form-inline">
            <label class="col-sm-2 col-form-label h6" for="totloss">Total Loss/Recovery in contract currency</label>
            <div class="col-6">
                <?=$this->Form->input('totloss', array(
                    'label'    => false,
                    'type'     => 'text',
                    'class'    => 'form-control ml-2 my-2',
                    'value'    => $total_value,
                    'disabled' => true,
                ));
                ?>
            </div>
        </div>

        <div class="form-group row form-inline">
            <label class="col-sm-2 col-form-label h6" for="eifduetra">EIF due amount in transaction currency</label>
            <div class="col-6">
                <?= $this->Form->input('eifduetra', array(
                    'label'    => false,
                    'type'     => 'text',
                    'class'    => 'form-control ml-2 my-2',
                    'value'    => $eif_due,
                    'disabled' => true,
                ));
                ?>
            </div>
        </div>
        <div class="form-group row form-inline">
            <label class="col-sm-2 col-form-label h6" for="eifdueeur">EIF due amount in EUR</label>
            <div class="col-6">
                <?= $this->Form->input('eifdueeur', array(
                    'label'    => false,
                    'type'     => 'text',
                    'class'    => 'form-control ml-2 my-2',
                    'value'    => $due_eur,
                    'disabled' => true,
                ));
                ?>
            </div>
        </div>
        <div class="form-group row form-inline">
            <label class="col-sm-2 col-form-label h6" for="eifduecurr">EIF due amount in contract currency</label>
            <div class="col-6">
                <?=$this->Form->input('eifduecurr', array(
                    'label'    => false,
                    'type'     => 'text',
                    'class'    => 'form-control ml-2 my-2',
                    'value'    => $due_curr,
                    'disabled' => true,
                ));
                ?>
            </div>
        </div>
        
        <div class="form-group row form-inline">
            <div class="col-6">
                <?= $result ?>
            </div>
        </div>

        <div class="form-group row form-inline">
            <label class="col-sm-2 col-form-label h6" for="period">Period</label>
            <div class="col-6">
                <?php
                    $value_period = '';
                    if (!empty($report->period_quarter)) {
                        $value_period = $report->period_quarter . '-' . $report->period_year;
                    }
                    echo $this->Form->input('period', array(
                        'label'    => false,
                        'type'     => 'text',
                        'class'    => 'form-control ml-2 my-2',
                        'value'    => $value_period,
                        'disabled' => true,
                    ));
                ?>
            </div>
        </div>

        <div class="form-group row form-inline">
            <label class="col-sm-2 col-form-label h6" for="duedate">Due date</label>
            <div class="col-6">
                <?php
                    $value_due_date = '';
                    if (!empty($report->due_date)) {
                        $value_due_date = date('d/m/Y', strtotime($report->due_date));
                    }
                    echo $this->Form->input('duedate', array(
                        'label'    => false,
                        'type'     => 'text',
                        'class'    => 'form-control ml-2 my-2',
                        'value'    => $value_due_date,
                        'disabled' => true,
                    ));
                ?>
            </div>
        </div>

    </body>
</html>