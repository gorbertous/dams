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
        'title'   => 'Inclusion Validation',
        'url'     => ['controller' => 'Report', 'action' => 'inclusion-validation', $report->report_id],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-inv-crumb'
            ]
        ]
    ],
    [
        'title'   => $report->report_name,
        'url'     => ['controller' => 'Report', 'action' => 'inclusion-validation-report', $report->report_id],
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

<h3><?= $title ?></h3>
<div class="row">
    <div class="col-12">
        <?php if (file_exists($pdf)) : ?>
            <a class="btn btn-secondary float-right ml-3" href=<?= $this->Url->build(['controller' => 'ajax', 'action' => 'downloadFile','_ext' => null,
                                                'eif_inclusion_validation_report_' . $report->report_id . '.pdf',
                                                'reports']); ?> ><i class="fas fa-file-export"></i> &nbsp;Export to PDF </a>
                                    
        <?php endif; ?>  
        <?php if (!empty($apv_breakdown_path)) : ?>
             <a class="btn btn-secondary float-right ml-3" href=<?= $this->Url->build(['controller' => 'ajax', 'action' => 'downloadFile','_ext' => null,
                                             $apv_breakdown_path,
                                            'reports']); ?> >APV breakdown </a>
        <?php endif; ?> 
        <?php if ($report->status_id == 5) : ?>
             <a class="btn btn-secondary float-right" href=<?= $this->Url->build(['controller' => 'validation', 'action' => 'waiver-reason-ro',$report->report_id]); ?> >Waiver reason </a>
        <?php endif; ?> 
             
        <?php if ($report->status_id == 5 || $report->status_id == 23) : ?>
             <a class="btn btn-secondary float-right" href="https://eif-alteryx-uat.theinformationlab.lu/gallery#!page/pec" target="_blank">Portfolio Concentration </a>
        <?php endif; ?> 
	
        <a style="margin-left:20px" class="btn btn-secondary float-right" href="/damsv2/report/inclusion-validation/<?= $report->report_id ?>">Back to Inclusion validation</a>
    </div>
</div>
<hr>
<div class="row mt-3">
    <div class="col-12">
        <?php if (!empty($warning_agreed_portfolio_volume) && ($warning_agreed_portfolio_volume == true)): ?>
            <div class="alert alert-warning" role="alert">
               <strong>Warning!!</strong> Actual Portfolio Volume exceeds Agreed Portfolio Volume.
            </div>
        <?php endif; ?>
        <?php if ($apvExceeded == true): ?>
            <div class="alert alert-warning" role="alert">
                <strong>Warning!!</strong> Actual Portfolio Volume exceeds Maximum Portfolio Volume.
            </div>
        <?php endif; ?>
        <?php if (!empty($portfolio_apv)): ?>
            <div class="alert alert-info" role="alert">
                INFO: Portfolio is closed. Portfolio Volume at the moment of closure was <strong><?= $this->Number->format($report->portfolio->apv_at_closure) ?></strong>
            </div>
        <?php endif ?>
        <?php if (!empty($apvDecrease)): ?>
            <div class="alert alert-warning" role="alert">
                <strong>Warning!!</strong> Actual Portfolio Volume decreased and the Available Cap Amount is now negative.
            </div>
        <?php endif ?>
        <?php if (!empty($mgv)): ?>
            <div class="alert alert-warning" role="alert">
                <strong>Warning!!</strong> Actual Guaranteed Volume exceeds Maximum Guaranteed Volume.
            </div>
        <?php endif ?>
        <?php if (!empty($agreed_ga)): ?>
            <div class="alert alert-warning" role="alert">
                <strong>Warning!!</strong> Actual Guarantee Amount exceeds Agreed Guarantee Amount.
            </div>
        <?php endif ?>
        <?php if (!empty($total_principal_disbursement)): ?>
		<div class="alert alert-warning" role="alert">
			<strong>Warning!!</strong> SME transactions amount disbursed (cumulated) exceeds Total disbursed amount.
		</div>
	<?php endif ?>
	<?php if (!empty($aga_nonCOVID19)): ?>
		<div class="alert alert-warning" role="alert">
			<strong>Warning!!</strong> Actual Guarantee Amount exceeds Maximum Guarantee Amount for non-COVID19 enhanced rate transactions.
		</div>
	<?php endif ?>
        <?php if (!empty($covid_19_enhanced_rate_transactions)): ?>
		<div class="alert alert-warning" role="alert">
			<strong>Warning!!</strong> COVID19 enhanced rate transactions detected in the inclusion file. Please prioritize the inclusion of these transactions.
		</div>
	<?php endif ?>
	
        <?php
        switch ($msgWarning) {
            case -1:
                ?>
                <div class="alert alert-info" role="alert">
                    <strong>Warning!!</strong> This portfolio is CLOSED. After including this report, APV will become lower than APV at closure of portfolio.
                </div>
                <?php
                break;
            case 2:
                ?>
                <div class="alert alert-info" role="alert">
                    <strong>Warning!!</strong> This portfolio is CLOSED. After including this report, APV will become higher than APV at closure of portfolio.
                </div>
            <?php }
        ?>

        <?php if ($warning_closure): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Warning!!</strong>  The inclusion end date has been reached for this portfolio (<?= $warning_closure_date; ?>). Please consider uploading a closure report.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif ?>

        <?php echo $result ?>

       
    </div>
</div>