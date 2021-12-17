<div id="layoutSidenav_nav" class="mb-5 bg-white">
    <nav class="sb-sidenav accordion sb-sidenav-light elevation-4">
        <div class="sb-sidenav-menu">
            <div class="nav">

                <a class="nav-link" href="/treasury" id="home">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-home text-info"></i>
                    </div> Home
                </a>

                <div class="collapse show" id="operations-items" aria-labelledby="operations">
                    <nav class="nav">
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#operations" aria-expanded="true" aria-controls="operations">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-book-open text-navy"></i>
                            </div> New Operation
                            <div class="sb-sidenav-collapse-arrow">
                                <i class="fas fa-angle-down"></i>
                            </div>
                        </a>

                        <div class="collapse" id="operations" aria-labelledby="operations" data-parent="#operations-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="new_deposit">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> New Deposits
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="operations" aria-labelledby="operations" data-parent="#operations-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="new_bond">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> New Bonds
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="operations" aria-labelledby="operations" data-parent="#operations-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="new_rollover">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> New Rollovers / Repayments
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="operations" aria-labelledby="operations" data-parent="#operations-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link collapsed" href="#" id="49" data-toggle="collapse" data-target="#ch1-sub-5-5" aria-expanded="false" aria-controls="ch1-sub-5-5">
                                    <div class="sb-nav-link-icon">
                                        <i class="far fa-circle"></i>
                                    </div> New Reinvestments
                                    <div class="sb-sidenav-collapse-arrow">
                                        <i class="fas fa-angle-down"></i>
                                    </div>
                                </a>
                                <div class="collapse" id="ch1-sub-5-5" aria-labelledby="operations" data-parent="#operations">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="open_reinvestment">
                                            <div class="sb-nav-link-icon">
                                                <i class="far fa-dot-circle"></i>
                                            </div> Open Reinvestment
                                        </a>
                                    </nav>
                                </div>
                                <div class="collapse" id="ch1-sub-5-5" aria-labelledby="operations" data-parent="#operations">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="new_repayment">
                                            <div class="sb-nav-link-icon">
                                                <i class="far fa-dot-circle"></i>
                                            </div> New Repayment
                                        </a>
                                    </nav>
                                </div>
                                <div class="collapse" id="ch1-sub-5-5" aria-labelledby="operations" data-parent="#operations">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="new_rollover">
                                            <div class="sb-nav-link-icon">
                                                <i class="far fa-dot-circle"></i>
                                            </div> New Rollover
                                        </a>
                                    </nav>
                                </div>
                                <div class="collapse" id="ch1-sub-5-5" aria-labelledby="operations" data-parent="#operations">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="close_reinvestment">
                                            <div class="sb-nav-link-icon">
                                                <i class="far fa-dot-circle"></i>
                                            </div> Close Reinvestment
                                        </a>
                                    </nav>
                                </div>
                            </nav>
                        </div>

                    </nav>
                </div>

                <div class="collapse show" id="instruction-items" aria-labelledby="instruction">
                    <nav class="nav">
                        <a class="nav-link collapsed" href="#" id="manual" data-toggle="collapse" data-target="#instruction" aria-expanded="true" aria-controls="instruction">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-book-open text-navy"></i>
                            </div> Instruction
                            <div class="sb-sidenav-collapse-arrow">
                                <i class="fas fa-angle-down"></i>
                            </div>
                        </a>

                        <div class="collapse" id="instruction" aria-labelledby="instruction" data-parent="#instruction-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="create_instruction">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Create Instruction
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="instruction" aria-labelledby="instruction" data-parent="#instruction-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="validate_instruction">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Validate Instruction
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="instruction" aria-labelledby="instruction" data-parent="#instruction-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="display_instruction">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Display Instruction
                                </a>
                            </nav>
                        </div>

                    </nav>
                </div>

                <div class="collapse show" id="confirmation-items" aria-labelledby="confirmation">
                    <nav class="nav">
                        <a class="nav-link collapsed" href="#" id="manual" data-toggle="collapse" data-target="#confirmation" aria-expanded="true" aria-controls="confirmation">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-book-open text-navy"></i>
                            </div> Confirmation
                            <div class="sb-sidenav-collapse-arrow">
                                <i class="fas fa-angle-down"></i>
                            </div>
                        </a>

                        <div class="collapse" id="confirmation" aria-labelledby="confirmation" data-parent="#confirmation-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="create_confirmation">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Register Confirmation
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="confirmation" aria-labelledby="confirmation" data-parent="#confirmation-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="validate_confirmation">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Validate Confirmation
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="confirmation" aria-labelledby="confirmation" data-parent="#confirmation-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="display_confirmation">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Display confirmation
                                </a>
                            </nav>
                        </div>

                    </nav>
                </div>

                <div class="collapse show" id="callable-items" aria-labelledby="callable">
                    <nav class="nav">
                        <a class="nav-link collapsed" href="#" id="manual" data-toggle="collapse" data-target="#callable" aria-expanded="true" aria-controls="callable">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-book-open text-navy"></i>
                            </div> Callable Deposit
                            <div class="sb-sidenav-collapse-arrow">
                                <i class="fas fa-angle-down"></i>
                            </div>
                        </a>

                        <div class="collapse" id="callable" aria-labelledby="callable" data-parent="#callable-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="create_callable">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Call Deposit
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="callable" aria-labelledby="callable" data-parent="#callable-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="validate_callable">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Instruct Call Deposit
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="callable" aria-labelledby="callable" data-parent="#callable-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="display_callable">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Call Confirmation
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="callable" aria-labelledby="callable" data-parent="#callable-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="display_callable">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Validate Call Confirmation
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="callable" aria-labelledby="callable" data-parent="#callable-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="display_callable">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Automatic Interest Fixing
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="callable" aria-labelledby="callable" data-parent="#callable-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="display_callable">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Manual Interest Fixing
                                </a>
                            </nav>
                        </div>

                    </nav>
                </div>

                <div class="collapse show" id="break-items" aria-labelledby="break">
                    <nav class="nav">
                        <a class="nav-link collapsed" href="#" id="manual" data-toggle="collapse" data-target="#break" aria-expanded="true" aria-controls="break">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-book-open text-navy"></i>
                            </div> Break Deposit
                            <div class="sb-sidenav-collapse-arrow">
                                <i class="fas fa-angle-down"></i>
                            </div>
                        </a>

                        <div class="collapse" id="break" aria-labelledby="break" data-parent="#break-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="create_break">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Break Deposit
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="break" aria-labelledby="break" data-parent="#break-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="validate_break">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Instruct Break Deposit
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="break" aria-labelledby="break" data-parent="#break-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="display_break">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Break Confirmation
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="break" aria-labelledby="break" data-parent="#break-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="display_break">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Validate Break Confirmation
                                </a>
                            </nav>
                        </div>

                    </nav>
                </div>

                <div class="collapse show" id="query-items" aria-labelledby="query">
                    <nav class="nav">
                        <a class="nav-link collapsed" href="#" id="manual" data-toggle="collapse" data-target="#query" aria-expanded="true" aria-controls="query">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-book-open text-navy"></i>
                            </div> Query
                            <div class="sb-sidenav-collapse-arrow">
                                <i class="fas fa-angle-down"></i>
                            </div>
                        </a>

                        <div class="collapse" id="query" aria-labelledby="query" data-parent="#query-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="create_query">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Deposits
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="query" aria-labelledby="query" data-parent="#query-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="validate_query">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Bonds
                                </a>
                            </nav>
                        </div>

                    </nav>
                </div>

                <div class="collapse show" id="reports-items" aria-labelledby="reports">
                    <nav class="nav">
                        <a class="nav-link collapsed" href="#" id="manual" data-toggle="collapse" data-target="#reports" aria-expanded="true" aria-controls="reports">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-book-open text-navy"></i>
                            </div> Reports
                            <div class="sb-sidenav-collapse-arrow">
                                <i class="fas fa-angle-down"></i>
                            </div>
                        </a>

                        <div class="collapse" id="reports" aria-labelledby="reports" data-parent="#reports-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="create_reports">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Accruals Calculation
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="reports" aria-labelledby="reports" data-parent="#reports-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="validate_reports">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Reinvestment Report
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="reports" aria-labelledby="reports" data-parent="#reports-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="validate_reports">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Calendar of Maturing Transactions
                                </a>
                            </nav>
                        </div>

                    </nav>
                </div>

                <div class="collapse show" id="booking-items" aria-labelledby="booking">
                    <nav class="nav">
                        <a class="nav-link collapsed" href="#" id="manual" data-toggle="collapse" data-target="#booking" aria-expanded="true" aria-controls="booking">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-book-open text-navy"></i>
                            </div> Booking
                            <div class="sb-sidenav-collapse-arrow">
                                <i class="fas fa-angle-down"></i>
                            </div>
                        </a>

                        <div class="collapse" id="booking" aria-labelledby="booking" data-parent="#booking-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="create_booking">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> EOM Accruals
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="booking" aria-labelledby="booking" data-parent="#booking-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="validate_booking">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> IN-OUT-BOOKING
                                </a>
                            </nav>
                        </div>

                    </nav>
                </div>


                <div class="collapse show" id="editdelete-items" aria-labelledby="editdelete">
                    <nav class="nav">
                        <a class="nav-link collapsed" href="#" id="reports" data-toggle="collapse" data-target="#editdelete" aria-expanded="true" aria-controls="editdelete">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-book-open text-navy"></i>
                            </div> Edit / Delete
                            <div class="sb-sidenav-collapse-arrow">
                                <i class="fas fa-angle-down"></i>
                            </div>
                        </a>

                        <div class="collapse" id="editdelete" aria-labelledby="editdelete" data-parent="#editdelete-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="new_deposit">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Transactions
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="editdelete" aria-labelledby="editdelete" data-parent="#editdelete-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link collapsed" href="#" id="49" data-toggle="collapse" data-target="#ch1-sub-5-5" aria-expanded="false" aria-controls="ch1-sub-5-5">
                                    <div class="sb-nav-link-icon">
                                        <i class="far fa-circle"></i>
                                    </div> Reinvestments
                                    <div class="sb-sidenav-collapse-arrow">
                                        <i class="fas fa-angle-down"></i>
                                    </div>
                                </a>
                                <div class="collapse" id="ch1-sub-5-5" aria-labelledby="editdelete" data-parent="#editdelete">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="open_reinvestment">
                                            <div class="sb-nav-link-icon">
                                                <i class="far fa-dot-circle"></i>
                                            </div> Reopen
                                        </a>
                                    </nav>
                                </div>
                                <div class="collapse" id="ch1-sub-5-5" aria-labelledby="editdelete" data-parent="#editdelete">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="new_repayment">
                                            <div class="sb-nav-link-icon">
                                                <i class="far fa-dot-circle"></i>
                                            </div> Delete
                                        </a>
                                    </nav>
                                </div>

                            </nav>
                        </div>

                        <div class="collapse" id="editdelete" aria-labelledby="editdelete" data-parent="#editdelete-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="new_deposit">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Benchmark
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="editdelete" aria-labelledby="editdelete" data-parent="#editdelete-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="new_deposit">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Interest Rate Change
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="editdelete" aria-labelledby="editdelete" data-parent="#editdelete-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="new_deposit">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Bond details for RM
                                </a>
                            </nav>
                        </div>

                    </nav>
                </div>

                <a class="nav-link" href="/treasury" id="home">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-wallet text-info"></i>
                    </div> Maturity Alert Batch
                </a>

                <a class="nav-link" href="/treasury" id="home">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-database text-info"></i>
                    </div> Limits Monitor
                </a>

                <div class="collapse show" id="static-items" aria-labelledby="static">
                    <nav class="nav">
                        <a class="nav-link collapsed" href="#" id="manual" data-toggle="collapse" data-target="#static" aria-expanded="true" aria-controls="static">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-book-open text-navy"></i>
                            </div> Static Data
                            <div class="sb-sidenav-collapse-arrow">
                                <i class="fas fa-angle-down"></i>
                            </div>
                        </a>

                        <div class="collapse" id="static" aria-labelledby="static" data-parent="#static-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="create_static">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Accounts
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="static" aria-labelledby="static" data-parent="#static-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="validate_static">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Banks
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="static" aria-labelledby="static" data-parent="#static-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="validate_static">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Compartments
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="static" aria-labelledby="static" data-parent="#static-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="validate_static">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Counterparties
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="static" aria-labelledby="static" data-parent="#static-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="validate_static">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Limits
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="static" aria-labelledby="static" data-parent="#static-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="validate_static">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Ratings
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="static" aria-labelledby="static" data-parent="#static-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="validate_static">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Mandates
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="static" aria-labelledby="static" data-parent="#static-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="validate_static">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Portfolios
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="static" aria-labelledby="static" data-parent="#static-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="validate_static">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Mandate Managers
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="static" aria-labelledby="static" data-parent="#static-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="validate_static">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Risk Groups
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="static" aria-labelledby="static" data-parent="#static-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="validate_static">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Taxes
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="static" aria-labelledby="static" data-parent="#static-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="validate_static">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> DI Settlement
                                </a>
                            </nav>
                        </div>

                    </nav>
                </div>

                <div class="collapse show" id="settings-items" aria-labelledby="settings">
                    <nav class="nav">
                        <a class="nav-link collapsed" href="#" id="manual" data-toggle="collapse" data-target="#settings" aria-expanded="true" aria-controls="settings">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-book-open text-navy"></i>
                            </div> Settings
                            <div class="sb-sidenav-collapse-arrow">
                                <i class="fas fa-angle-down"></i>
                            </div>
                        </a>

                        <div class="collapse" id="settings" aria-labelledby="settings" data-parent="#settings-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="create_settings">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> DI Templates
                                </a>
                            </nav>
                        </div>

                        <div class="collapse" id="settings" aria-labelledby="settings" data-parent="#settings-items">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=<?= $this->Url->build(['controller' => 'home', 'action'     => 'home']); ?> id="validate_settings">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-circle"></i>
                                    </div> Depo Terms
                                </a>
                            </nav>
                        </div>

                    </nav>
                </div>

                <a class="nav-link" href="/treasury" id="home">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-file-download text-info"></i>
                    </div> User Manual
                </a>

                <div class="d-inline my-3 ml-3">
                    <a href="https://www.sogeti.lu/" target="_blank"><img src="/img/sog_logo.png" style="height:30px" alt="" class="img-fluid"></a>

                    <a href="https://www.sas.com" target="_blank"><img src="/img/sas_logo.png" alt="" class="img-fluid"></a>
                </div>

            </div>
        </div>
    </nav>
</div>