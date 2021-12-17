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
        'url'     => ['controller' => 'Report', 'action' => 'pdlr-reconciliation', $report->report_id],
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

<h3>Payment Demands/Recoveries reconciliation</h3>
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
    <label class="col-sm-2 col-form-label h6" for="concur">Total in contract currency</label>
    <div class="col-6">
        <?= $this->Form->input('', array(
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
    <div class="col-6" id="sasResults">
<!--        $result-->
    </div>
</div>
    

<div class="row">
    <div class="col-1">
        <?php
        echo $this->Form->create(null, ['url' => '/damsv2/report/validation-page', 'id' => 'Save']);
        echo $this->Form->input('Report.report_id', array(
            'type'  => 'hidden',
            'label' => false,
            'value' => $report->report_id,
        ));
        echo $this->Form->input('Report.action', array(
            'type'  => 'hidden',
            'label' => false,
            'value' => 'valid',
        ));
        echo $this->Form->submit('Save',
                array(
                    'id'    => 'save',
                    'name'  => 'save',
                    'type'  => 'submit',
                    'class' => 'btn btn-success',
                    'div'   => false//array('class' => array('span11'))
                )
        );
        echo $this->Form->end();
        ?>
    </div>
    <div class="col-1">
        <?php
        echo $this->Form->create(null, ['url' => '/damsv2/pdlr-reject', 'id' => 'reject']);
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
                    'div'   => false//array('class' => array('span11'))
                )
        );
        echo $this->Form->end();
        ?>
    </div>
 
</div>

<script>
    $(document).ready(function () {
        $("#ReportCorrectionForm").submit(function (event) {

            $("#ReportCorrectionForm [required]").each(function () {
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