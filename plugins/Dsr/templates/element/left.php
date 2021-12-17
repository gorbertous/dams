<div id="layoutSidenav_nav" class="mb-5 bg-white">
    <nav class="sb-sidenav accordion sb-sidenav-light elevation-4">
        <div class="sb-sidenav-menu">
            <div class="nav">

                <a class="nav-link" href="/dsr" id="home">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-home text-info"></i>
                    </div> Home
                </a>

                <div class="collapse show" id="dsrreports-items" aria-labelledby="dsrReports">
                    <nav class="nav">
                        <a class="nav-link collapsed" href="#" id="reports" data-toggle="collapse" data-target="#dsrreports" aria-expanded="true" aria-controls="dsrreports">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-book-open text-navy"></i>
                            </div> Reports
                            <div class="sb-sidenav-collapse-arrow">
                                <i class="fas fa-angle-down"></i>
                            </div>
                        </a>


                        <div class="collapse" id="dsrreports" aria-labelledby="dsrReports" data-parent="#dsrreports-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'reports', 'action'     => 'index']); ?> id="report_list">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> List
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="dsrreports" aria-labelledby="dsrReports" data-parent="#dsrreports-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'reports', 'action'     => 'import']); ?> id="report_import">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Import
                                </a>
                            </nav>
                        </div>

                    </nav>
                </div>
                <a class="nav-link" href="<?= $this->Url->build(['controller' => 'portfolios', 'action'     => 'index']); ?>" id="portfolio_list">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-briefcase text-info"></i>
                    </div> Portfolios
                </a>
                <a class="nav-link" href="<?= $this->Url->build(['controller' => 'products', 'action'     => 'index']); ?>" id="product_list">
                    <div class="sb-nav-link-icon">
                        <i class="fab fa-product-hunt text-info"></i>
                    </div> Products
                </a>
                <a class="nav-link" href="<?= $this->Url->build(['controller' => 'loans', 'action'     => 'index']); ?>" id="loan_list">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-wallet text-info"></i>
                    </div> Loans
                </a>
                <a class="nav-link" href="<?= $this->Url->build(['controller' => 'dictionaries', 'action'     => 'index']); ?>" id="dico_list">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-database text-info"></i>
                    </div> Dico
                </a>
                <a class="nav-link" href="<?= $this->Url->build(['controller' => 'reports', 'action'     => 'dsr-view']); ?>" id="view_list">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-chart-bar text-info"></i>
                    </div> View
                </a>

                <div class="collapse show" id="usermanual-items" aria-labelledby="userManual">
                    <nav class="nav">
                        <a class="nav-link collapsed" href="#" id="manual" data-toggle="collapse" data-target="#usermanual" aria-expanded="true" aria-controls="usermanual">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-book-open text-navy"></i>
                            </div> Documentation
                            <div class="sb-sidenav-collapse-arrow">
                                <i class="fas fa-angle-down"></i>
                            </div>
                        </a>

                        <div class="collapse" id="usermanual" aria-labelledby="userManual" data-parent="#usermanual-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build([
                                                                'controller' => 'ajax', 'action' => 'downloadFile', '_ext' => null,
                                                                'DSR_EPMF_Social_Report_Portfolio_Inclusions.docx',
                                                                'docs'
                                                            ]); ?>>
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Manual
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="usermanual" aria-labelledby="userManual" data-parent="#usermanual-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build([
                                                                'controller' => 'ajax', 'action' => 'downloadFile', '_ext' => null,
                                                                'DSR_Import_template.xls',
                                                                'docs'
                                                            ]); ?>>
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Template
                                </a>
                            </nav>
                        </div>

                    </nav>
                </div>

                <div class="d-inline my-3 ml-3">
                    <a href="https://www.sogeti.lu/" target="_blank"><img src="/img/sog_logo.png" style="height:30px" alt="" class="img-fluid"></a>

                    <a href="https://www.sas.com" target="_blank"><img src="/img/sas_logo.png" alt="" class="img-fluid"></a>
                </div>
            </div>
        </div>
    </nav>
</div>