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
        'title'   => 'Inclusion Dashboard',
        'url'     => ['controller' => 'Report', 'action' => 'inclusion'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => $report->report_name,
        'url'     => ['controller' => 'Report', 'action' => 'inclusion-import', $report->report_id],
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

<h3>Import of inclusion file</h3>
<hr>
<div class="row">
    <div class="col-12">
        <?php if ($report->portfolio->owner != $user_id): ?>           
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Warning!!</strong>  You are not the owner of this portfolio
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif ?>
        <?php if ($warning_closure): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Warning!!</strong>  The inclusion end date has been reached for this portfolio (<?= $warning_closure_date; ?>). Please consider uploading a closure report.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif ?>
        <?php if ($KYC_embargo_ongoing): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Warning!!</strong>  The KYC procedure is still ongoing for this portfolio. Inclusions are not allowed.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif ?>

        <?php if ($modifications_expected == 'Y'): ?>

            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Warning!!</strong>  Please ensure F sheet modifications identified in Monitoring Visit Follow up letter have been processed before uploading inclusion report.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif ?>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <?= $this->Form->create(null, ['id' => 'ReportInclusionImportForm', 'enctype' => 'multipart/form-data']) ?>
        <?= $this->Form->input('Template.id', ['type' => 'hidden', 'value' => $report->template_id]) ?>
        <?= $this->Form->input('Report.id', ['type' => 'hidden', 'value' => $report->report_id]) ?>
        <?= $this->Form->input('Report.version_number', ['type' => 'hidden', 'value' => $report->version_number]) ?>

        <div class="row">
            <div class="col-6">
                <?= $this->Form->control('Portfolio.portfolio_name', [
                    'label'    => 'Portfolio Name',
                    'class'    => 'form-control mr-2 my-2',
                    'type'     => 'text',
                    'disabled' => true,
                    'value'    => $report->portfolio->portfolio_name,
                ]);
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <?= $this->Form->control('Report.reception_date', [
                    'label'    => 'Reception Date',
                    'class'    => 'form-control datepicker mr-2 my-2 py-2',
                    'type'     => 'text',
                    'id'       => 'ReportReceptionDate',
                    'value'    => empty($report->reception_date) ? '' : $report->reception_date->format('Y-m-d'),
                ]);
                ?>
            </div>
        </div>
       
        <div class="row">
            <div class="col-3">
                <?= $this->Form->control('Report.period_quarter', [
                    'type'     => 'text',
                    'class'    => 'form-control mr-2 my-2',
                    'options'  => ['Q1' => 'Q1', 'Q2' => 'Q2', 'Q3' => 'Q3', 'Q4' => 'Q4'],
                    'value'  => $report->period_quarter,
                    'disabled' => true
                ])
                ?>
            </div>
            <div class="col-3">
                <?= $this->Form->control('Report.period_year', [
                    'type'     => 'text',
                    'class'    => 'form-control mr-2 my-2',
                    'value'  => $report->period_year,
                    'disabled' => true
                ])
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <?= $this->Form->control('Report.provisional_pv', [
                    'type'    => 'text',
                    'label'    => 'Provisional Portfolio Volume',
                    'class'    => 'form-control mr-2 my-2',
                    'id'      => 'ReportProvisionalPv',
                    'value' => $provisional_portfolio_volume,
                ])
                ?>
            </div>
        </div>

         <div class="row">
            <div class="col-6">
                <?= $this->Form->label('Report.owner', 'Report Owner'); ?>
                <?= $this->Form->select('Report.owner', $users,
                    [
                        'class' => 'form-control mr-2 my-2',
                        'label'    => '',
                        'value' => $user_id,
                        'label' => 'Report Owner'
                    ]
                );
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <?= $this->Form->control('Report.report_name', [
                    'class'    => 'form-control mr-2 my-2',
                    'type'     => 'text',
                    'disabled' => true,
                    'label'    => 'Report ID',
                    'value'    => $report->report_name
                ])
                ?>
            </div>
        </div>
       
       <?php if ($modifications_expected == 'Y') : ?>
            <div class="row">
                <div class="col-6 form-inline">
                    <?= $this->Form->control('Portfolio.modifications_expected', ['type' => 'checkbox', 'class' => 'form-check-input my-2', 'checked'  => true, 'label' => false, 'id' => 'modexpected']); ?>
                    <label class="col-form-label h6 required">Modification performed</label>
                </div>
            </div>
        <?php endif; ?>
        
	<div class="row">
            <div class="col-6">
                <?= $this->Form->control('Report.header', ['type' => 'checkbox', 'class' => 'mr-2 my-3 py-3', 'label' => 'File includes header', 'checked' => true]); ?>
            </div>
	</div>
      
        <div class="row">
            <div class="col-6">
            <?php
                $sheet_disabled = [];
                $sheet_selected = [];
                
                if ($report->portfolio->product->product_type == "guarantee"):
                   
                    $sheet_options = [
                        'A1' => 'A1 (New SMEs)',
                        'A2' => 'A2 (New Transactions)',
                        'A3' => 'A3 (New Guarantees)',
                        'B'  => 'B   (Included Transactions)',
                        'D'  => 'D   (Expired Transactions)',
                        'E'  => 'E   (Excluded Transactions)'
                    ];
                   
                    if (($report->portfolio->product->name == 'EASI')  || ($report->portfolio->product->name == 'EaSI Funded')) {
                        $sheet_options['S1'] = 'S1 (Impact data)';
                        $sheet_options['S2'] = 'S2 (Ex-post impact data )';
                    }
                    if ($report->portfolio->product->name == 'EPMF FMA' || $report->portfolio->product->name == 'EASI') {
                        $sheet_options['A4'] = 'A4 (Number of Micro Credit requests/rejections)';
                        ksort($sheet_options);
                    }
                    if ($report->template_id == 327) {
                        $sheet_options['A21'] = 'A21 (subtransactions)';
                        $sheet_options['B1'] = 'B1 (Included Subtransactions)';
                        ksort($sheet_options);
                    }
                    if (in_array($report->template_id, [340, 343])) {
                        $sheet_options['S1'] = 'S1 (Impact data)';
                        $sheet_options['S2'] = 'S2 (Ex-post impact data )';
                        ksort($sheet_options);
                    }
                    
                    if (in_array($report->portfolio->product->product_id, [19, 20, 26])) {//FOSTER AGRI, FLPG
                        unset($sheet_options['A3']);
                    }

                    if ($report->template_id == 275) {// ESIF EAFRD FLPG Alter'na
                        unset($sheet_options['A3']);
                    }
				
                    if ($report->template_id == 85) {// Innovfin Direct V2
                        unset($sheet_options['A3']);
                    }
                    if ($report->template_id == 310) {// Innovfin on lending
                        unset($sheet_options['A3']);
                    }
                    if ($report->template_id == 287) {// Inclusion InnovFin Direct Guarantee v2 digitalisation sub-debt
                        unset($sheet_options['A3']);
                    }
                    if ($report->template_id == 149) {// Inclusion Banco Cooperativo umbrella
                        unset($sheet_options['A3']);
                    }
                    if ($report->template_id == 322) {// Inclusion WB EDIF Youth
                        unset($sheet_options['A3']);
                    }
                    if (in_array($report->template_id, array(291, 299))) {// COSME Direct and Counter Guarantee
                        unset($sheet_options['A3']);
                    }
                    if (in_array($report->template_id, array(327))) {// Jeremie Bulgaria
                        unset($sheet_options['A3']);
                    }
                    if (in_array($report->template_id, array(340))) {// Direct EaSI MF Sub-Fund
                        unset($sheet_options['A3']);
                    }
                    if (in_array($report->template_id, array(343))) {// Inclusion Direct EaSI SE Sub-Fund
                        unset($sheet_options['A3']);
                    }
                    if (in_array($report->template_id, array(369))) {// Inclusion ESIF EERE Malta
                        unset($sheet_options['A3']);
                    }
                    if (in_array($report->template_id, array(356))) {// Inclusion EGF Direct
                        unset($sheet_options['A3']);
                    }

                    if (in_array($report->portfolio->product->product_id, [21])) {//jeremie FRSP
                        unset($sheet_options['A1']);
                        unset($sheet_options['A2']);
                        unset($sheet_options['R']);
                    }
                    if (in_array($report->portfolio->product->product_id, [27])) {//jeremie FRSP
                        unset($sheet_options['E']);
                        unset($sheet_options['R']);
                    }
                    
                ?>
                <?php if (($report->portfolio->status_portfolio == "CLOSED")): ?>
                        <?php $sheet_selected = ['B'] ?>
                        <?php if ($report->report_type != 'closure'): ?>
                            <?php $sheet_disabled = ['A1', 'A2', 'A3'] ?>
                        <?php endif; ?>
                    <?php elseif ($report->portfolio->status_portfolio == "OPEN"): ?>
                        <?php $sheet_disabled = [] ?>
                        <?php $sheet_selected = ['A1', 'A2', 'A4', 'B'] ?>
                    <?php elseif ($report->portfolio->status_portfolio == "EARLY TERMINATED"): ?>
                        <?php $sheet_disabled = ['A1', 'A2', 'A3', 'A4', 'B', 'D', 'E'] ?>
                        <?php $sheet_selected = [] ?>
                    <?php endif; ?>
                <?php else: ?>
                    <?php
                    $sheet_options = [
                        'A1' => 'A1 (New SMEs)',
                        'A2' => 'A2 (New Transactions)',
                        'B'  => 'B   (Included Transactions)',
                        'C'  => 'C   (Defaulted Transactions)',
                        'D'  => 'D   (Expired Transactions)',
                        'E'  => 'E   (Excluded Transactions)',
                        'R'  => 'R   (Recoveries)'
                    ];
                    
                    if (($report->portfolio->product->name == 'EASI') || ($report->portfolio->product->name == 'EaSI Funded')) {
                        $sheet_options['S1'] = 'S1';
                        $sheet_options['S2'] = 'S2';
                    }

                    if ($report->portfolio->product->product_id == 30) {//EASI FUNDED
                        $sheet_options['A4'] = 'A4 (Number of Micro Credit requests/rejections)';
                        ksort($sheet_options);
                    }


                    if ($report->portfolio->product->name == 'EREM CBSI') {
                        unset($sheet_options['C']);
                        unset($sheet_options['D']);
                        unset($sheet_options['R']);
                    }

                    if ($report->portfolio->product->name == 'EPMF FCP') {
                        unset($sheet_options['R']);
                    }
                    
                    if ($report->portfolio->product->product_id == 30){
                        unset($sheet_options['R']);
                    } 
                    
                    if ($report->portfolio->product->name == 'JEREMIE Bulgaria') {
                        $sheet_options['A21'] = 'A21 (subtransactions)';
                        $sheet_options['B1'] = 'B1 (Included Subtransactions)';
                        ksort($sheet_options);
                    }

                    if ($report->portfolio->product->product_id == 9) {
                        $sheet_options['I1'] = 'I1 (Start of re-performing transaction)';
                        $sheet_options['I2'] = 'I2 (End of re-performing transaction)';
                        ksort($sheet_options);
                    }

                    if (in_array($report->template_id, [340, 343])) {
                        $sheet_options['S1'] = 'S1 (Impact data)';
                        $sheet_options['S2'] = 'S2 (Ex-post impact data )';
                        ksort($sheet_options);
                    }

                    if (in_array($report->portfolio->product->product_id, [19, 20, 26])) {//FOSTER PRSL, AGRI, FLPG (18 removed https://eifsas.atlassian.net/browse/DAMS-1087 )
                        unset($sheet_options['R']);
                    }
                    ?>
                    <?php if ($report->portfolio->status_portfolio == "CLOSED"): ?>
                        <?php $sheet_selected = ['B'] ?>
                        <?php if ($report->report_type != 'closure'): ?>
                            <?php $sheet_disabled = ['A1', 'A2', 'A3'] ?>
                        <?php endif; ?>
                        <?php $sheet_selected = ['B'] ?>
                    <?php elseif ($report->portfolio->status_portfolio == "OPEN"): ?>
                        <?php $sheet_disabled = ['A3'] ?>
                        <?php $sheet_selected = ['A1', 'A2', 'B'] ?>
                    <?php elseif ($report->portfolio->status_portfolio == "EARLY TERMINATED"): ?>
                        <?php $sheet_disabled = ['A1' => 'A1', 'A2' => 'A2', 'A3' => 'A3', 'B' => 'B', 'D' => 'D', 'E' => 'E'] ?>
                        <?php $sheet_selected = [] ?>
                    <?php elseif ($report->report_type == "regular" && $report->portfolio->status_portfolio == 'Closed'): ?>
                        <?php $sheet_disabled = ['A1' => 'A1', 'A2' => 'A2', 'A3' => 'A3'] ?>
                        <?php $sheet_selected = [] ?>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php
                if ($report->portfolio->product->name == 'EREM CBSI' && ($report->portfolio->status_portfolio == "OPEN")) {
                    $sheet_selected = ['A1', 'A2', 'B'];
                }

                if (in_array($report->portfolio->product->product_id, [19, 20, 26])) {//FOSTER AGRI, FLPG
                    unset($sheet_options['A3']);
                }
                if ($report->template_id == 275) {// ESIF EAFRD FLPG Alter'na
                    unset($sheet_options['A3']);
                }
                
                if ($report->template_id == 85) {// Innovfin Direct V2
                    unset($sheet_options['A3']);
                }
                if ($report->template_id == 310) {// Innovfin on lending
                    unset($sheet_options['A3']);
                }
                if ($report->template_id == 287) {// Inclusion InnovFin Direct Guarantee v2 digitalisation sub-debt
                    unset($sheet_options['A3']);
                }
                if ($report->template_id == 149) {// Inclusion Banco Cooperativo umbrella
                    unset($sheet_options['A3']);
                }
                if ($report->template_id == 322) {// Inclusion WB EDIF Youth
                    unset($sheet_options['A3']);
                }
                if (in_array($report->template_id, array(291, 299))) {// COSME Direct and Counter Guarantee
                    unset($sheet_options['A3']);
                }
                if (in_array($report->template_id, array(327))) {// Jeremie Bulgaria
                    unset($sheet_options['A3']);
                }
                if (in_array($report->template_id, array(340))) {//  Direct EaSI MF Sub-Fund
                    unset($sheet_options['A3']);
                }
                if (in_array($report->template_id, array(343))) {//  Inclusion Direct EaSI SE Sub-Fund
                    unset($sheet_options['A3']);
                }
                if (in_array($report->template_id, array(369))) {// Inclusion ESIF EERE Malta
                    unset($sheet_options['A3']);
                }
                if (in_array($report->template_id, array(356))) {// Inclusion EGF Direct
                        unset($sheet_options['A3']);
                }

                if (in_array($report->portfolio->product->product_id, [21])) {//jeremie FRSP
                    unset($sheet_options['A1']);
                    unset($sheet_options['A2']);
                    unset($sheet_options['R']);
                }

                if (in_array($report->portfolio->product->product_id, [27])) {//jeremie FRSP
                    unset($sheet_options['E']);
                    unset($sheet_options['R']);
                }
                echo $this->Form->label('Report.sheets', 'Included sheets', ['class' => 'h6 my-2 py-2']);
                echo $this->Form->select('Report.sheets', $sheet_options,[
                    'multiple' => 'checkbox', 
                    'class' => 'form-group mr-2 my-2 py-2',
                    'value'  => $sheet_selected,
                    'idPrefix'    => 'ReportSheets'
                    //'disabled' => $sheet_disabled//csrf : will block the entire sheet list
                ]);

                if ($report->portfolio->product->name == 'EREM CBSI' && ($report->portfolio->status_portfolio == "OPEN")) {
                    echo '<script>$("#report-sheets-b").attr("readonly", true);</script>';
                }
                ?>
             
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <?= $this->Form->label('file', 'Report File', ['class' => 'h6 my-2 py-2 required']); ?>
                <?= $this->Form->control('file', ['type' => 'file', 'class' => 'form-control-file mr-2 my-2 py-2', 'required' => true, 'label' => false]); ?>
                <div class="invalid-feedback">Example invalid form file feedback</div>
            </div>
        </div>
        <div class="row">
                <div class="col-6">
                <?= $this->Form->label('Report.description', 'Description', ['class' => 'h6 my-2 py-2']); ?>
                <?= $this->Form->control('Report.description', ['type' => 'textarea','rows' => '5', 'cols' => '5','class' => 'form-control', 'label' => false]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6 form-inline">
              
                <?= $this->Form->submit('Upload report', [
                    'class'    => 'btn btn-primary form-control mr-3  my-3',
                    'disabled' => ($KYC_embargo_ongoing),
                    'name'     => 'upload_report',
                    'id'       => 'upload_report',
                ])
                ?>
                <?= $this->Html->link('Cancel', ['action' => 'inclusion'], ['class' => 'btn btn-danger form-control my-3']) ?>
                   
            </div>
        </div>
        
        <?= $this->Form->end() ?>

    </div>
</div>

<script>
    $(document).ready(function () {

        <?php if ($modifications_expected == 'Y' && (!$KYC_embargo_ongoing)) { ?>
                  
            if ($('#modexpected').is(':checked')) {
                document.getElementById("upload_report").disabled = false;
            }

            const checkbox = document.getElementById('modexpected');

            checkbox.addEventListener('change', (event) => {
                if (event.currentTarget.checked) {
                    document.getElementById("upload_report").disabled = false;
                } else {
                  document.getElementById("upload_report").disabled = true;
                }
            })

        <?php } ?>

        $('#ReportReceptionDate').datepicker({
            dateFormat: "yy-mm-dd",
            defaultDate: 0,
        });

        //init data values for checkboxes
        $(".checkbox input").each(function (i, j)
        {
            var el = $(j);
            el.attr('prev_val', el.prop("checked"));
        });

        $(".checkbox input").change(function (e)
        {
            var el = $(e.target);
            //var prev_val = el.attr('prev_val');
            var name = el.val();
            var val = el.prop('checked');
            // checks
            //el.attr('prev_val', el.prop( "checked" ));

            // selections
            if (val == true)
            {
                if (name == 'A1')
                {
                    $("#report-sheets-a2").prop('checked', true);
                }
                if (name == 'A2')
                {
                    $("#report-sheets-a1").prop('checked', true);
                }
                if (name == 'A3')
                {
                    $("#report-sheets-a1").prop('checked', true);
                    $("#report-sheets-a2").prop('checked', true);
                }
                if (name == 'A21')
                {
                    $("#report-sheets-b1").prop('checked', true);
                    $("#report-sheets-a2").prop('checked', true);
                }
            }

            if (val == false)
            {
                if (name == 'A1')
                {
                    $("#report-sheets-a2").prop('checked', false);
                    $("#report-sheets-a3").prop('checked', false);
                }
                if (name == 'A2')
                {
                    $("#report-sheets-a1").prop('checked', false);
                    $("#report-sheets-a3").prop('checked', false);
                }
            }
        });

        $('#ReportReceptionDate').datepicker("setDate", new Date());

        $('#ReportProvisionalPv').autoNumeric('init', {aSep: ',', aDec: '.', mDec: 2, vMin: -99999999999999999999.99, vMax: 99999999999999999999.99});

        $('#report-sheets-b').change(function (event) {
            $("#report-sheets-b").prop("checked", true);
        });

        if (('#report-sheets-a4').length)
        {
            $('#report-sheets-a4').prop('checked', true);
            $('#report-sheets-a4').change(function (event)
            {
                $('#report-sheets-a4').prop('checked', true);
            });
        }

        if ($('#report-sheets-b'))
            $("#ReportHeader").prop("checked", true);

       
        });

        <?php
        foreach ($sheet_disabled as $sheet) {

            echo '$("#report-sheets-' . $sheet . '").prop("checked", false);';
            echo "\n";
            echo '$("#report-sheets-' . $sheet . '").prop("readonly", true);';
            echo "\n";
            echo '$("#report-sheets-' . $sheet . '").click(function(e){ return false; });';
            echo "\n";
            echo '$("#report-sheets-' . $sheet . '").addClass("disabled_checkbox");';
            echo "\n";
        }
        ?>
   
</script>
<style>
    .disabled_checkbox:before
    {
        position: absolute;
        content: '';
        background: rgba(0,0,0, 0.5); /*partially transparent image*/
        width: 12px;
        height: 12px;
        pointer-events: none;
    }
</style>
