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
        'title'   => $report->report_name,
        'url'     => ['controller' => 'Report', 'action' => 'pdlr-validation', $report->report_id],
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

<h3>PD/LR validation</h3>
<hr>

<?= $this->Form->create(null, ['type' => 'post', 'id' => 'Damsv2.Report']) ?>
<?= $this->Form->input('Report.report_id', ['type' => 'hidden', 'value' => $report->report_id]) ?>

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
<?php echo $this->Form->end() ?>


    

<div class="row">
    <div class="col-1">
        <?php
        echo $this->Form->create(null, ['url' => '/damsv2/report/validation-page', 'id' => 'Save']);
        echo $this->Form->input('Report.report_id', array(
            'type'  => 'hidden',
            'value' => $report->report_id,
        ));
        echo $this->Form->input('Report.action', array(
            'type'  => 'hidden',
            'value' => 'valid',
        ));
        echo $this->Form->submit('Save',
                array(
                    'id'    => 'save',
                    'name'  => 'save',
                    'type'  => 'submit',
                    'class' => 'btn btn-success',
                    'div'   => false,
					'disabled' => !$perm->hasWrite(array('action' => 'validationPage')),
                )
        );
        echo $this->Form->end();
        ?>
    </div>
    <div class="col-1">
        <?php
        echo $this->Form->create(null, ['url' => '/damsv2/report/pdlr-reject', 'id' => 'reject']);
        echo $this->Form->input('Report.report_id', array(
            'type'  => 'hidden',
            'label' => false,
            'div'   => false,
            'value' => $report->report_id,
        ));
        echo $this->Form->submit('Reject',
                array(
                    'type'  => 'submit',
                    'class' => 'btn btn-danger',
                    'div'   => false,
					'disabled' => !$perm->hasWrite(array('action' => 'pdlrReject')),
                )
        );
        echo $this->Form->end();
        ?>
    </div>
    <div class="col-2">
         <?= $this->Html->link(__('Export to PDF'), ['action' => 'pdlr-validation-pdf', $report->report_id ],['class' => 'btn btn-info']) ?>
        
    </div>
</div>


<script>
    $(document).ready(function () {
        $("fieldset form").submit(function (event) {
            document.getElementById("save").disabled = true;
            $("fieldset form [required]").each(function () {
                error = true;
                if ($(this).attr('id')) {
                    if ($(this).val()) {
                        $(this).css('border-color', 'rgb(204, 204, 204)');
                        error = false;
                    } else {
                        $(this).css('border', '1px solid red');
                    }
                }
            });

            if (error) {
                $(".alert").show().fadeOut(4000);
                return false;
            }
        });

    });
</script>
