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
        'url'     => ['controller' => 'Report', 'action' => 'inclusion-validation', $report->report_id],
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

<h3>Inclusion validation</h3>
<hr>
<div class="row">
    <div class="col-12">
        <?php
		if (($report->status_id == 5) || ($report->status_id == 23)){
			echo '<a class="btn btn-secondary float-right ml-3" href="https://eif-alteryx-uat.theinformationlab.lu/gallery#!page/pec" >Portfolio Concentration</a>';
        } ?>
    </div>
</div>
<?= $this->Form->create(null, ['type' => 'post', 'id' => 'ReportInclusionValidationForm']) ?>
<?= $this->Form->input('Report.report_id', ['type' => 'hidden', 'value' => $report->report_id]) ?>

<div class="form-group row form-inline">
    <label class="col-sm-1 col-form-label h6" for="portfolio_name">Portfolio ID</label>
    <div class="col-4">
       <?= $this->Form->control('portfolio_name', [
            'label'    => false,
            'class' => 'form-control w-100 mr-2 my-2 py-2',
            'type'     => 'text',
            'disabled' => true,
            'value'    => $report->portfolio->portfolio_name,
        ]);
        ?>
    </div>
</div>
<div class="form-group row form-inline">
    <label class="col-sm-1 col-form-label h6" for="report_name">Report ID</label>
    <div class="col-4">
       <?= $this->Form->control('report_name', [
            'label'    => false,
            'class' => 'form-control w-100 mr-2 my-2 py-2',
            'type'     => 'text',
            'disabled' => true,
            'value'    => $report->report_name,
        ]);
        ?>
    </div>
</div>

<?= $this->Form->end() ?>

<div id="sasResults">
    <?= $result ?>
    <?php if ($save == 2): ?><p>The following active loans contained in the database where not reported in B sheet. Please reject the report and upload it including these transactions.</p><?php endif; ?>
</div>

<?php
if ($num_sme > 0) {
    echo '<div class="row"><div class="col-6 ml-2 form-inline">';
    echo $this->Form->control('Report.inclusion_notice_received', ['type' => 'checkbox', 'id' => 'ReportInclusionNoticeReceived', 'class' => 'form-check-input', 'label' => ' Inclusion notice received*', 'checked' => ($report->inclusion_notice_received == 'TRUE')]);

    echo '<span  class="ml-2" style="font-size: smaller;">*In line with the reconciled figures</span>';
    echo '</div></div>';
}
?>

<div class="row my-5">
    <div class="col-12 form-inline">
        <?= $this->Form->postButton('Validation Report', ['action' => 'inclusion-validation-report/' . $report->report_id], ['class' => 'btn btn-secondary']) ?>
      

        <?php if (!$viewonly): ?>

                <?php
                //enable the button of the validation report button was clicked before 
                $disabled = true;
                $title = 'Click first on "Validation Report" button';
                $link = [];
                $onclick = 'return false';
                
                if (file_exists('/var/www/html/data/damsv2/reports/eif_inclusion_validation_report_' . $report->report_id)) {
                    $disabled = false;                  
                    if ($apvExceeded || $mgv || $aga_nonCOVID19) {// APV > MPV (agreed PV not blocking), MGV blocking
                        error_log('inclusion validation blocked ' . $report->report_id . ', apvExceeded = ' . serialize($apvExceeded));
                        error_log('inclusion validation blocked ' . $report->report_id . ', mgv = ' . serialize($mgv));
                        error_log('inclusion validation blocked ' . $report->report_id . ', aga_nonCOVID19 = ' . serialize($aga_nonCOVID19));
                        $disabled = true;
                    }                    
                    $title = $onclick = '';
                    $agreed_products = [3, 5, 12];
                    //if APV>MPV and CAPPED portfolios and not PRSL => Exceeded MPV form => FI responsivness
                    //if APV>agreed_pv and CAPPED portfolios or (RSI, IF & SMEi) => Exceeded MPV form => FI responsivness
                    //else => FI responsivness
                    if ($apvExceeded == true && $report->portfolio->capped == 'YES' && $report->portfolio->product_id != 4) {
                        $link = ['action' => 'exceeded_mpv/' . $report->report_id];
                    } elseif (!empty($warning_agreed_portfolio_volume) && (($warning_agreed_portfolio_volume == true) && (($report->portfolio->capped == 'YES') || (in_array($report->portfolio->product_id, $agreed_products))))) {
                        $link = ['action' => 'exceeded_mpv/' . $report->report_id];
                    } elseif (!empty($agreed_ga) && (in_array($report->portfolio->product_id, $agreed_ga_portfolios_list))) {
                        $link = ['action' => 'exceeded_mpv/' . $report->report_id];
                    } elseif (!empty($total_principal_disbursement) && (($total_principal_disbursement == true))) {
                        $link = ['action' => 'exceeded_total_principal_disbursement/' . $report->report_id];
                    } else {
                        $link = ['controller' => 'validation', 'action' => 'waiver_reason/' . $report->report_id];
                    }
                }

                $options = ['class' => 'btn btn-success ml-2', 'data-url' => $link, 'id' => 'proceed_button'];
                
                if ($disabled) {
                    $options['disabled'] = 'true';
                    $options['class'] = 'btn btn-success ml-2 disabled';
                    $options['style'] = 'pointer-events: none;';
                }
				if (!$perm->hasWrite($link))
				{
                    $options['class'] = 'btn btn-success ml-2 disabled';
					$options['disabled'] = 'true';
				}
              
                if ($save == 1) {
                    echo $this->Html->link(__('Verify and proceed'), $link, $options);
                }
                ?>

                <?= $this->Form->create(null, ['type' => 'post', 'url' => '/damsv2/report/reject-report']) ?>

                <?= $this->Form->input('Report.report_id', ['type' => 'hidden', 'value' => $report->report_id]) ?>
                <?php
				$class_reject = 'btn btn-danger ml-2';
				if (!$perm->hasWrite(array('action' => 'rejectReport')))
				{
					$class_reject = 'btn btn-danger ml-2 disabled';
				}
				?>
				<?= $this->Form->submit('Reject',
                        [
                            'id'    => 'save',
                            'name'  => 'save',
                            'type'  => 'submit',
                            'class' => $class_reject,
							'disabled' => !$perm->hasWrite(array('action' => 'rejectReport')),
                        ]
                );
                ?>
                <?= $this->Form->end(); ?>

        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-12 form-inline">
     
        <?php if ($modifications_expected == 'Y'): ?>
                <?= $this->Form->checkbox('Portfolio.modifications_expected', ['checked ' => true, 'id ' => 'modexpected', 'class' => 'form-check-input'])?>
                <label class="form-check-label" for="modexpected">F sheet modifications identified in monitoring visit follow up letter have been processed. <a class="ml-2" href="<?= $m_files_link; ?>">M-files link</a></label>


        <?php elseif (!empty($m_files_link)): ?>
                <?= $this->Form->checkbox('Portfolio.modifications_expected_old', [
                    'class' => 'form-check-input',
                    'disabled' => true,
                    'checked'  => true,
                ]);
                ?>
                <label class="form-check-label" for="mod_expected">F sheet modifications identified in monitoring visit follow up letter have been processed. <a class="ml-2" href="<?= $m_files_link; ?>">M-files link</a></label>

        <?php endif ?>
    </div>
</div>

<div style="display:none;">
    <?php
    echo $this->Form->create(null, ['id' => 'updateInclusionNotice', 'url' => '/damsv2/report/set-inclusion-notice-received']);
    echo $this->Form->input('Report.report_id', ['type' => 'hidden', 'value' => $report->report_id]);
    echo $this->Form->input('Report.inclusion_notice_received', [
            'type' => 'text',
            'label'	=> false,
            'id' => 'InclusionNoticeReceived'
    ]);
    echo $this->Form->end();
    ?>
</div>

<script>

    $(document).ready(function () {
        
        $('#ReportInclusionNoticeReceived').change(function(e){
            //  inclusion_notice_receivedInclusionValidationForm
            var checked = 'FALSE';
            if ($('#ReportInclusionNoticeReceived:checked').length > 0)
            {
                checked = 'TRUE';
            }
            $("#updateInclusionNotice #InclusionNoticeReceived").val( checked );
            var post = $('#updateInclusionNotice').serialize();
            $.ajax({
              url: '/damsv2/report/set-inclusion-notice-received',
              type: 'POST',
              data: post,
              headers: {'X-CSRF-TOKEN': '<?= $this->request->getAttribute('csrfToken') ?>'},
            }).done(function( data ){
            });
	    });

        <?php if (($modifications_expected == 'Y') && (!$disabled)): ?>
   
            const modexpected_checkbox = document.getElementById('modexpected');
        
            modexpected_checkbox.addEventListener('change', (event) => {
                if (event.currentTarget.checked) {
                    // can save
                    document.getElementById("proceed_button").removeAttribute('disabled');
                    document.getElementById("proceed_button").style = '';
                    $('#proceed_button').removeClass('disabled');
                    
                } else {
                    $('#proceed_button').addClass('disabled');
                    document.getElementById("proceed_button").disabled = true;
                    document.getElementById("proceed_button").disabled = 'disabled';
                    document.getElementById("proceed_button").style = 'pointer-events: none;';
                }
            })

        <?php endif; ?>

    });
</script>
