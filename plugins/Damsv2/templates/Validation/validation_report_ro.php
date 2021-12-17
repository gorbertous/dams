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
        'url'     => ['controller' => 'validation', 'action' => 'inclusion-validation-ro', $report->report_id],
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
        'url'     => ['controller' => 'validation', 'action' => 'validation-report-ro', $report->report_id],
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
<hr>
<div class="row">
    <div class="col-12">
        <?php if (file_exists($pdf)) : ?>
            <a class="btn btn-secondary float-right ml-3" href=<?=
            $this->Url->build(['controller' => 'ajax', 'action'     => 'downloadFile', '_ext'       => null,
                'eif_inclusion_validation_report_' . $report->report_id . '.pdf',
                'reports']);
            ?> ><i class="fas fa-file-export"></i> &nbsp;Export to PDF </a>

        <?php endif; ?>
        <?php if (!empty($apv_breakdown_path)) : ?>
            <a class="btn btn-secondary float-right ml-3" href=<?=
               $this->Url->build(['controller' => 'ajax', 'action'     => 'downloadFile', '_ext'       => null,
                    $apv_breakdown_path,
                   'reports']);
               ?> >APV breakdown </a>
        <?php endif; ?>
        <?php
		if (($report->status_id == 5) || ($report->status_id == 23)){
			echo '<a class="btn btn-secondary float-right ml-3" href="https://eif-alteryx-uat.theinformationlab.lu/gallery#!page/pec" >Portfolio Concentration</a>';
        } ?>
    </div>
</div>

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
                INFO: Portfolio is closed. Portfolio Volume at the moment of closure was <strong><?= $this->Number->format($portfolio->actual_pv) ?></strong>
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

        <div class="row">
            <div class="col-4">
                <?php echo $this->Html->link('Back', ['action' => 'inclusion_validation_ro/' . $report->report_id], ['class' => 'btn btn-secondary']) ?>

                <?php
                $link = ['controller' => 'Validation', 'action' => 'waiver_reason_ro/' . $report->report_id];
                echo $this->Html->link('Next', $link, ['class' => 'btn btn-primary']);
                ?>
            </div>
        </div>
    </div>
</div>