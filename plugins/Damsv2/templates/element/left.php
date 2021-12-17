<div id="layoutSidenav_nav" class="mb-5 bg-white">
    <nav class="sb-sidenav accordion sb-sidenav-light elevation-4">
        <div class="sb-sidenav-menu">
            <div class="nav">

                <a class="nav-link" href="/damsv2" id="home">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-home text-info"></i>
                    </div> Home
                </a>
                <?php if ($perm->hasRead(array('controller' => 'Report', 'action' => 'inclusion'))) { ?>
                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'Report', 'action' => 'inclusion']); ?> id="inclusion_dashboard">
                        <div class="sb-nav-link-icon">
                            <i class="fas fa-tachometer-alt text-success"></i>
                        </div> Inclusion Dashboard
                    </a>
                <?php } ?>

                <?php if ($perm->hasRead(array('controller' => 'Report', 'action' => 'pdlr'))) { ?>
                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'Report', 'action' => 'pdlr']); ?> id="pd_recoveries">
                        <div class="sb-nav-link-icon">
                            <i class="fas fa-stroopwafel text-warning"></i>
                        </div> PD/Recoveries
                    </a>
                <?php } ?>

                <?php if ($perm->hasRead(array('controller' => 'Invoice', 'action' => 'index'))) { ?>
                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'invoice', 'action' => 'index']); ?> id="invoices">
                        <div class="sb-nav-link-icon">
                            <i class="fas fa-file-invoice text-info"></i>
                        </div> Invoices
                    </a>
                <?php } ?>

                <?php if ($perm->hasRead(array('controller' => 'Rules', 'action' => 'index'))) { ?>
                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'rules', 'action' => 'index']); ?> id="rules">
                        <div class="sb-nav-link-icon">
                            <i class="fas fa-stroopwafel text-warning"></i>
                        </div> Rules configuration
                    </a>
                <?php } ?>

                <?php if ($perm->hasRead(array('controller' => 'Report', 'action' => 'generatePeriod'))) { ?>
                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'Report', 'action' => 'generate-period']); ?> id="generate_period">
                        <div class="sb-nav-link-icon">
                            <i class="fas fa-stroopwafel text-warning"></i>
                        </div> Generate Period
                    </a>
                <?php } ?>

                <!--Portfolio with sub elements-->
                <?php if (
                    $perm->hasRead(array('controller' => 'Portfolio', 'action' => 'index'))
                    || $perm->hasRead(array('controller' => 'Validation', 'action' => 'inclusionNoticeFollowup'))
                    || $perm->hasRead(array('controller' => 'Monitoringvisit', 'action' => 'index'))
                    || $perm->hasRead(array('controller' => 'Portfolio', 'action' => 'eurCurr'))
                ) { ?>
                    <div class="collapse show" id="portfolio-items" aria-labelledby="portfolioItems">
                        <nav class="nav">
                            <a class="nav-link collapsed" href="#" id="port" data-toggle="collapse" data-target="#portfolio" aria-expanded="true" aria-controls="portfolio">
                                <div class="sb-nav-link-icon">
                                    <i class="fas fa-book-open text-navy"></i>
                                </div> Portfolio
                                <div class="sb-sidenav-collapse-arrow">
                                    <i class="fas fa-angle-down"></i>
                                </div>
                            </a>
                            <?php if ($perm->hasRead(array('controller' => 'Portfolio', 'action' => 'index'))) { ?>
                                <div class="collapse" id="portfolio" aria-labelledby="portfolioItems" data-parent="#portfolio-items">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href=<?= $this->Url->build(['controller' => 'portfolio', 'action' => 'index']); ?> id="port_list">
                                            <div class="sb-nav-link-icon">
                                                <i class="fas fa-circle"></i>
                                            </div> Portfolios
                                        </a>
                                    </nav>
                                </div>
                            <?php } ?>

                            <?php if ($perm->hasRead(array('controller' => 'Validation', 'action' => 'inclusionNoticeFollowup'))) { ?>
                                <div class="collapse" id="portfolio" aria-labelledby="portfolioItems" data-parent="#portfolio-items">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href=<?= $this->Url->build(['controller' => 'Validation', 'action' => 'inclusionNoticeFollowup']); ?> id="inc_follow_up">
                                            <div class="sb-nav-link-icon">
                                                <i class="fas fa-circle"></i>
                                            </div> Inclusion notice follow up
                                        </a>
                                    </nav>
                                </div>
                            <?php } ?>
                            <?php if ($perm->hasRead(array('controller' => 'Monitoringvisit', 'action' => 'index'))) { ?>
                                <div class="collapse" id="portfolio" aria-labelledby="portfolioItems" data-parent="#portfolio-items">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href=<?= $this->Url->build(['controller' => 'Monitoringvisit', 'action' => 'index']); ?> id="port_mv_follow_up">
                                            <div class="sb-nav-link-icon">
                                                <i class="fas fa-circle"></i>
                                            </div> MV Follow Up
                                        </a>
                                    </nav>
                                </div>
                            <?php } ?>
                            <?php if ($perm->hasRead(array('controller' => 'Portfolio', 'action' => 'eur-curr'))) { ?>
                                <div class="collapse" id="portfolio" aria-labelledby="portfolioItems" data-parent="#portfolio-items">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href=<?= $this->Url->build(['controller' => 'Portfolio', 'action' => 'eur-curr']); ?> id="port_recal_currency">
                                            <div class="sb-nav-link-icon">
                                                <i class="fas fa-circle"></i>
                                            </div> Recalculate currency equivalences
                                        </a>
                                    </nav>
                                </div>
                            <?php } ?>
                        </nav>
                    </div>
                <?php } ?>

                <!--View with sub elements-->
                <?php if ($perm->hasRead(array('controller' => 'SmePortfolio', 'action' => 'index'))||$perm->hasRead(array('controller' => 'Transactions', 'action' => 'index'))) { ?>
                    <div class="collapse show" id="view-items" aria-labelledby="viewItems">
                        <nav class="nav">
                            <a class="nav-link collapsed" href="#" id="view" data-toggle="collapse" data-target="#view-subitems" aria-expanded="true" aria-controls="view-subitems">
                                <div class="sb-nav-link-icon">
                                    <i class="fas fa-book-open text-navy"></i>
                                </div> View
                                <div class="sb-sidenav-collapse-arrow">
                                    <i class="fas fa-angle-down"></i>
                                </div>
                            </a>

                            <?php if ($perm->hasRead(array('controller' => 'SmePortfolio', 'action' => 'index'))) { ?>
                            <div class="collapse" id="view-subitems" aria-labelledby="viewItems" data-parent="#view-items">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'SmePortfolio', 'action' => 'index']); ?> id="view_sme">
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-circle"></i>
                                        </div> SME
                                    </a>
                                </nav>
                            </div>
                            <?php } ?>
                            <?php if ($perm->hasRead(array('controller' => 'Transactions', 'action' => 'index'))) { ?>
                            <div class="collapse" id="view-subitems" aria-labelledby="viewItems" data-parent="#view-items">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'transactions', 'action' => 'index']); ?> id="view_trn">
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-circle"></i>
                                        </div> TRN
                                    </a>
                                </nav>
                            </div>
                            <?php } ?>
                        </nav>
                    </div>
                <?php } ?>

                <?php if ($perm->hasRead(array('controller' => 'Import', 'action' => 'index'))) { ?>
                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'Import', 'action' => 'index']); ?> id="edit">
                        <div class="sb-nav-link-icon">
                            <i class="fas fa-stroopwafel text-warning"></i>
                        </div> Edit
                    </a>
                <?php } ?>

                <!--Sampling with sub elements-->
                <?php if (
                    $perm->hasRead(array('controller' => 'Sampling-Evaluation', 'action' => 'non_cip_sampling'))
                    || $perm->hasRead(array('controller' => 'Sampling-Evaluation', 'action' => 'drawing'))
                    || $perm->hasRead(array('controller' => 'Sampling-Evaluation', 'action' => 'index'))
                    || $perm->hasRead(array('controller' => 'Sampling-Evaluation', 'action' => 'list-samples'))
                    || $perm->hasRead(array('controller' => 'Sampling-Evaluation', 'action' => 'sample-upload'))
                    || $perm->hasRead(array('controller' => 'Sampling-Evaluation', 'action' => 'yearly-evaluation'))
                    || $perm->hasRead(array('controller' => 'Sampling-Evaluation', 'action' => 'transactions-update'))
                    || $perm->hasRead(array('controller' => 'Sampling-Evaluation', 'action' => 'manual-pd-sampling'))
                    || $perm->hasRead(array('controller' => 'Sampling-Evaluation', 'action' => 'manual-sampling'))
                ) { ?>
                    <div class="collapse show" id="sampling-items" aria-labelledby="samplingItems">
                        <nav class="nav">
                            <a class="nav-link collapsed" href="#" id="samp" data-toggle="collapse" data-target="#sampling" aria-expanded="true" aria-controls="sampling">
                                <div class="sb-nav-link-icon">
                                    <i class="fas fa-book-open text-navy"></i>
                                </div> Sampling
                                <div class="sb-sidenav-collapse-arrow">
                                    <i class="fas fa-angle-down"></i>
                                </div>
                            </a>


                            <?php if ($perm->hasRead(array('controller' => 'Sampling-Evaluation', 'action' => 'index'))) { ?>
                            <div class="collapse" id="sampling" aria-labelledby="samplingItems" data-parent="#sampling-items">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'Sampling-Evaluation', 'action' => 'index']); ?> id="samp_annual">
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-circle"></i>
                                        </div> Annual CIP Parameters
                                    </a>
                                </nav>
                            </div>
                            <?php } ?>
                            <?php if ($perm->hasRead(array('controller' => 'Sampling-Evaluation', 'action' => 'drawing'))) { ?>
                            <div class="collapse" id="sampling" aria-labelledby="samplingItems" data-parent="#sampling-items">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'Sampling-Evaluation', 'action' => 'drawing']); ?> id="samp_cip">
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-circle"></i>
                                        </div> CIP Sample Drawing
                                    </a>
                                </nav>
                            </div>
                            <?php } ?>
                            <?php if ($perm->hasRead(array('controller' => 'Sampling-Evaluation', 'action' => 'non-cip-sampling'))) { ?>
                            <div class="collapse" id="sampling" aria-labelledby="samplingItems" data-parent="#sampling-items">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'Sampling-Evaluation', 'action' => 'non-cip-sampling']); ?> id="non_cip">
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-circle"></i>
                                        </div> Non-CIP Sample Drawing
                                    </a>
                                </nav>
                            </div>
                            <?php } ?>
                            <?php if ($perm->hasRead(array('controller' => 'Sampling-Evaluation', 'action' => 'manual-sampling'))) { ?>
                            <div class="collapse" id="sampling" aria-labelledby="samplingItems" data-parent="#sampling-items">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'Sampling-Evaluation', 'action' => 'manual-sampling']); ?> id="samp_manual_pds">
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-circle"></i>
                                        </div> Manual PDs Sampling
                                    </a>
                                </nav>
                            </div>
                            <?php } ?>
                            <?php if ($perm->hasRead(array('controller' => 'Sampling-Evaluation', 'action' => 'list-samples'))) { ?>
                            <div class="collapse" id="sampling" aria-labelledby="samplingItems" data-parent="#sampling-items">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'Sampling-Evaluation', 'action' => 'list-samples']); ?> id="samp_randomly">
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-circle"></i>
                                        </div> Randomly Sampled CIP PDs
                                    </a>
                                </nav>
                            </div>
                            <?php } ?>
                            <?php if ($perm->hasRead(array('controller' => 'Sampling-Evaluation', 'action' => 'manual-pd-sampling'))) { ?>
                            <div class="collapse" id="sampling" aria-labelledby="samplingItems" data-parent="#sampling-items">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'Sampling-Evaluation', 'action' => 'manual-pd-sampling']); ?> id="samp_manually_sampled">
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-circle"></i>
                                        </div> Manually Sampled PDs
                                    </a>
                                </nav>
                            </div>
                            <?php } ?>
                            <?php if ($perm->hasRead(array('controller' => 'Sampling-Evaluation', 'action' => 'sample-upload'))) { ?>
                            <div class="collapse" id="sampling" aria-labelledby="samplingItems" data-parent="#sampling-items">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'Sampling-Evaluation', 'action' => 'sample-upload']); ?> id="samp_info_update">
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-circle"></i>
                                        </div> Sampling information update
                                    </a>
                                </nav>
                            </div>
                            <?php } ?>
                            <?php if ($perm->hasRead(array('controller' => 'Sampling-Evaluation', 'action' => 'yearly-evaluation'))) { ?>
                            <div class="collapse" id="sampling" aria-labelledby="samplingItems" data-parent="#sampling-items">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'Sampling-Evaluation', 'action' => 'yearly-evaluation']); ?> id="samp_yearly_cip">
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-circle"></i>
                                        </div> Yearly CIP sample evaluation
                                    </a>
                                </nav>
                            </div>
                            <?php } ?>
                            <?php if ($perm->hasRead(array('controller' => 'Sampling-Evaluation', 'action' => 'transactions-update'))) { ?>
                            <div class="collapse" id="sampling" aria-labelledby="samplingItems" data-parent="#sampling-items">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'Sampling-Evaluation', 'action' => 'transactions-update']); ?> id="samp_tranactions">
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-circle"></i>
                                        </div> Transactions sampling
                                    </a>
                                </nav>
                            </div>
                            <?php } ?>
                        </nav>
                    </div>
                <?php } // end permission analytics 
                ?>



                <!--Reports and analytics with sub elements-->
                <?php if (
                    $perm->hasRead(array('controller' => 'Analytics', 'action' => 'analyticsReports'))
                    || $perm->hasRead(array('controller' => 'Analytics', 'action' => 'dataExtractsReports'))
                    || $perm->hasRead(array('controller' => 'Analytics', 'action' => 'forecastReports'))
                    || $perm->hasRead(array('controller' => 'Analytics', 'action' => 'operationsReports'))
                    || $perm->hasRead(array('controller' => 'Analytics', 'action' => 'faq'))
                ) { ?>
                    <div class="collapse show" id="report-items" aria-labelledby="reportItems">
                        <nav class="nav">
                            <a class="nav-link collapsed" href="#" id="rep" data-toggle="collapse" data-target="#report" aria-expanded="true" aria-controls="report">
                                <div class="sb-nav-link-icon">
                                    <i class="fas fa-book-open text-navy"></i>
                                </div> Reports and Analytics
                                <div class="sb-sidenav-collapse-arrow">
                                    <i class="fas fa-angle-down"></i>
                                </div>
                            </a>

                            <?php if ($perm->hasRead(array('controller' => 'Analytics', 'action' => 'analytics_reports'))) { ?>
                            <div class="collapse" id="report" aria-labelledby="reportItems" data-parent="#report-items">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'analytics', 'action' => 'analytics_reports']); ?> id="rep_analytics">
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-circle"></i>
                                        </div> Analytics
                                    </a>
                                </nav>
                            </div>
                            <?php } ?>
                            <?php if ($perm->hasRead(array('controller' => 'Analytics', 'action' => 'data_extracts_reports'))) { ?>
                            <div class="collapse" id="report" aria-labelledby="reportItems" data-parent="#report-items">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'analytics', 'action' => 'data_extracts_reports']); ?> id="rep_data_extracts">
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-circle"></i>
                                        </div> Data Extracts
                                    </a>
                                </nav>
                            </div>
                            <?php } ?>
                            <?php if ($perm->hasRead(array('controller' => 'Analytics', 'action' => 'forecast_reports'))) { ?>
                            <div class="collapse" id="report" aria-labelledby="reportItems" data-parent="#report-items">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'analytics', 'action' => 'forecast_reports']); ?> id="rep_forecasts">
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-circle"></i>
                                        </div> Forecasts
                                    </a>
                                </nav>
                            </div>
                            <?php } ?>
                            <?php if ($perm->hasRead(array('controller' => 'Analytics', 'action' => 'operations_reports'))) { ?>
                            <div class="collapse" id="report" aria-labelledby="reportItems" data-parent="#report-items">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'analytics', 'action' => 'operations_reports']); ?> id="rep_operations">
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-circle"></i>
                                        </div> Operations
                                    </a>
                                </nav>
                            </div>
                            <?php } ?>
                            <?php if ($perm->hasRead(array('controller' => 'Analytics', 'action' => 'faq'))) { ?>
                            <div class="collapse" id="report" aria-labelledby="reportItems" data-parent="#report-items">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'analytics', 'action' => 'faq']); ?> id="rep_faq">
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-circle"></i>
                                        </div> FAQ
                                    </a>
                                </nav>
                            </div>
                            <?php } ?>

                        </nav>
                    </div>
                <?php } // end permission analytics
                if ($perm->hasRead(array('controller' => 'Dstoolbox', 'action' => 'index'))) {
                ?>
                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'dstoolbox', 'action' => 'index']); ?> id="toolbox">
                        <div class="sb-nav-link-icon">
                            <i class="fas fa-toolbox text-dark"></i>
                        </div> Toolbox
                    </a>
                <?php } ?>

                <?php if ($perm->hasRead(array('controller' => 'SmeRatingMapping', 'action' => 'downloadRating'))||$perm->hasRead(array('controller' => 'SmeRatingMapping', 'action' => 'uploadRating'))) { ?>
                    <!--SME Rating Mapping with sub elements-->
                    <div class="collapse show" id="sme-items" aria-labelledby="smeItems">
                        <nav class="nav">
                            <a class="nav-link collapsed" href="#" id="smes" data-toggle="collapse" data-target="#sme" aria-expanded="true" aria-controls="sme">
                                <div class="sb-nav-link-icon">
                                    <i class="fas fa-book-open text-navy"></i>
                                </div> SME Rating Mapping
                                <div class="sb-sidenav-collapse-arrow">
                                    <i class="fas fa-angle-down"></i>
                                </div>
                            </a>
                            <?php if ($perm->hasRead(array('controller' => 'SmeRatingMapping', 'action' => 'uploadRating'))) { ?>
                            <div class="collapse" id="sme" aria-labelledby="smeItems" data-parent="#sme-items">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'SmeRatingMapping', 'action' => 'uploadRating']); ?> id="sme_upload_rating">
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-circle"></i>
                                        </div> Upload
                                    </a>
                                </nav>
                            </div>
                            <?php } ?>
                            <?php if ($perm->hasRead(array('controller' => 'SmeRatingMapping', 'action' => 'downloadRating'))) { ?>
                            <div class="collapse" id="sme" aria-labelledby="smeItems" data-parent="#sme-items">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'SmeRatingMapping', 'action' => 'downloadRating']); ?> id="sme_download_rating">
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-circle"></i>
                                        </div> Download
                                    </a>
                                </nav>
                            </div>
                            <?php } ?>
                        </nav>
                    </div>
                <?php } ?>

                <?php if ($perm->hasRead(array('controller' => 'Template', 'action' => 'mappingView'))
                    || $perm->hasRead(array('controller' => 'Ajax', 'action' => 'downloadFile/callForwards'))
                    || $perm->hasRead(array('controller' => 'Ajax', 'action' => 'downloadFile/messagesDocumentation'))
                    || $perm->hasRead(array('controller' => 'Ajax', 'action' => 'downloadFile/templateMapping'))
                    || $perm->hasRead(array('controller' => 'Ajax', 'action' => 'downloadFile/userManual'))
                    || $perm->hasRead(array('controller' => 'Template', 'action' => 'dashboard'))
                    || $perm->hasRead(array('controller' => 'Dictionary', 'action' => 'index'))) { ?>
                    <!--User Manual with sub elements-->
                    <div class="collapse show" id="usermanual-items" aria-labelledby="userManual">
                        <nav class="nav">
                            <a class="nav-link collapsed" href="#" id="manual" data-toggle="collapse" data-target="#usermanual" aria-expanded="true" aria-controls="usermanual">
                                <div class="sb-nav-link-icon">
                                    <i class="fas fa-book-open text-navy"></i>
                                </div> User Manual
                                <div class="sb-sidenav-collapse-arrow">
                                    <i class="fas fa-angle-down"></i>
                                </div>
                            </a>

                            <?php //if ($perm->hasRead(array('controller' => 'Ajax', 'action' => 'downloadFile', 'filter'=>'userManual'))) { ?>
                            <div class="collapse" id="usermanual" aria-labelledby="userManual" data-parent="#usermanual-items">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'Outsourcing', 'action' => 'index']); ?> id="outsourcing">
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-circle"></i>
                                        </div> Outsourcing Log
                                    </a>
                                </nav>
                            </div>
                            <?php //} ?>

                            <?php if ($perm->hasRead(array('controller' => 'Ajax', 'action' => 'downloadFile', 'filter'=>'userManual'))) { ?>
                            <div class="collapse" id="usermanual" aria-labelledby="userManual" data-parent="#usermanual-items">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href=<?= $this->Url->build([
                                                                    'controller' => 'ajax', 'action' => 'downloadFile', '_ext' => null,
                                                                    'DAMS_User_Manual.pdf',
                                                                    'docs'
                                                                ]); ?>>
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-circle"></i>
                                        </div> User Manual
                                    </a>
                                </nav>
                            </div>
                            <?php } ?>
                            <?php if ($perm->hasRead(array('controller' => 'Dictionary', 'action' => 'index'))) { ?>
                            <div class="collapse" id="usermanual" aria-labelledby="userManual" data-parent="#usermanual-items">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'dictionary', 'action' => 'index']); ?> id="manual_dictionaries">
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-circle"></i>
                                        </div> Dictionaries
                                    </a>
                                </nav>
                            </div>
                            <?php } ?>
                            <?php if ($perm->hasRead(array('controller' => 'Ajax', 'action' => 'downloadFile', 'filter'=>'templateMapping'))) { ?>
                            <div class="collapse" id="usermanual" aria-labelledby="userManual" data-parent="#usermanual-items">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href=<?= $this->Url->build([
                                                                    'controller' => 'ajax', 'action' => 'downloadFile', '_ext' => null,
                                                                    'DAMS_Template_mapping.xlsx',
                                                                    'docs'
                                                                ]); ?>>
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-circle"></i>
                                        </div> Template Mapping
                                    </a>
                                </nav>
                            </div>
                            <?php } ?>
                            <?php if ($perm->hasRead(array('controller' => 'Ajax', 'action' => 'downloadFile', 'filter'=>'callForwards'))) { ?>
                            <div class="collapse" id="usermanual" aria-labelledby="userManual" data-parent="#usermanual-items">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href=<?= $this->Url->build([
                                                                    'controller' => 'ajax', 'action' => 'downloadFile', '_ext' => null,
                                                                    'Call_FORWARD.pdf',
                                                                    'docs'
                                                                ]); ?>>
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-circle"></i>
                                        </div> Call-Forwards
                                    </a>
                                </nav>
                            </div>
                            <?php } ?>
                            <?php if ($perm->hasRead(array('controller' => 'Ajax', 'action' => 'downloadFile', 'filter'=>'messagesDocumentation'))) { ?>
                            <div class="collapse" id="usermanual" aria-labelledby="userManual" data-parent="#usermanual-items">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href=<?= $this->Url->build([
                                                                    'controller' => 'ajax', 'action' => 'downloadFile', '_ext' => null,
                                                                    'DAMS_Messages_documentation.pdf',
                                                                    'docs'
                                                                ]); ?>>
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-circle"></i>
                                        </div> Messages Documentation
                                    </a>
                                </nav>
                            </div>
                            <?php } ?>
                            <?php if ($perm->hasRead(array('controller' => 'Template', 'action' => 'dashboard'))) { ?>
                            <div class="collapse" id="usermanual" aria-labelledby="userManual" data-parent="#usermanual-items">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'template', 'action' => 'dashboard']); ?> id="manual_templates">
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-circle"></i>
                                        </div> Templates
                                    </a>
                                </nav>
                            </div>
                            <?php } ?>
                            <?php if ($perm->hasRead(array('controller' => 'Template', 'action' => 'mapping-view'))) { ?>
                            <div class="collapse" id="usermanual" aria-labelledby="userManual" data-parent="#usermanual-items">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'template', 'action' => 'mapping-view']); ?> id="manual_template_summary">
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-circle"></i>
                                        </div> Template Summary
                                    </a>
                                </nav>
                            </div>
                            <?php } ?>
                        </nav>
                    </div>
                <?php } ?>

                <?php if ($perm->hasRead(array('controller' => 'Control', 'action' => 'home'))) { ?>
                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'control', 'action' => 'home']); ?> id="delete_inclusion_report">
                        <div class="sb-nav-link-icon">
                            <i class="far fa-trash-alt text-danger"></i>
                        </div> Delete inclusion report
                    </a>
                <?php } ?>
                <?php if ($perm->hasRead(array('controller' => 'Control', 'action' => 'pdlr-list'))) { ?>
                    <a class="nav-link" href=<?= $this->Url->build(['controller' => 'control', 'action' => 'pdlr-list']); ?> id="delete_pdlr_report">
                        <div class="sb-nav-link-icon">
                            <i class="far fa-trash-alt text-danger"></i>
                        </div> Delete PD/LR Report
                    </a>
                <?php } ?>

                <div class="d-inline my-3 ml-3">
                    <a href="https://www.sogeti.lu/" target="_blank"><img src="/img/sog_logo.png" style="height:30px" alt="" class="img-fluid"></a>

                    <a href="https://www.sas.com" target="_blank"><img src="/img/sas_logo.png" alt="" class="img-fluid"></a>
                </div>
            </div>
        </div>
    </nav>
</div>