<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Report[]|\Cake\Collection\CollectionInterface $report
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Analytics',
        'url'     => ['controller' => 'Report', 'action' => 'analytics-reports'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
<h3>Analytics</h3>
<hr>
<?php if ($perm->hasRead(array('controller' => 'Analytics', 'action' => 'active-portfolio-management'))) { ?>
<div class="h5"><a class="text-primary" href="/damsv2/analytics/active-portfolio-management">Active Portfolio Monitoring Report</a><sup class="text-danger ml-2">SAS</sup></div>
<div class="mb-3">
    A report that shows underlying inclusion details and key portfolio monitoring measures.
</div>

<?php } ?>
<?php if ($perm->hasRead(array('controller' => 'Analytics', 'action' => 'mandate-performance-country'))) { ?>
<div class="h5" ><a class="text-primary" href="/damsv2/analytics/mandate-performance-country">Country’s Mandates Performance Report</a><sup class="text-danger ml-2">SAS</sup></div>
<div class="mb-3">
    A comparative analysis report summarizing mandates’ performances in the selected country.
</div>
<?php } ?>
<?php if ($perm->hasRead(array('controller' => 'Analytics', 'action' => 'cumulative-key-portfolio'))) { ?>
<div  class="h5"><a class="text-primary" href="/damsv2/analytics/cumulative-key-portfolio">Cumulative Key Portfolio Data Report</a><sup class="text-danger ml-2">SAS</sup></div>
<div class="mb-3">
    Cumulative Key Portfolio Data Report.
</div>
<?php } ?>
<?php if ($perm->hasRead(array('controller' => 'Analytics', 'action' => 'main-agri-statistics'))) { ?>
<div  class="h5" ><a class="text-primary" href="/damsv2/analytics/main-agri-statistics">Main AGRI statistics</a><sup class="text-danger ml-2">SAS</sup></div>
<div class="mb-3">
    A report showing a number of SMEs and transactions as well as amounts financed in 3 AGRI sector categories (holistic approach).
</div>
<?php } ?>
<?php if ($perm->hasRead(array('controller' => 'Analytics', 'action' => 'mandate-performance'))) { ?>
<div  class="h5" ><a class="text-primary" href="/damsv2/analytics/mandate-performance">Mandate Performance Report</a><sup class="text-danger ml-2">SAS</sup></div>
<div class="mb-3">
    A comparative analysis report summarizing selected mandate’s performance across countries.
</div>
<?php } ?>
<?php if ($perm->hasRead(array('controller' => 'Analytics', 'action' => 'seasonality-report'))) { ?>
<div  class="h5" ><a class="text-primary" href="/damsv2/analytics/seasonality-report">Portfolio Inclusion Seasonality Report</a><sup class="text-danger ml-2">SAS</sup></div>
<div class="mb-3">
    A report which shows the ramp-up of loans into the portfolio.
</div>
<?php } ?>
<?php if ($perm->hasRead(array('controller' => 'Portfolio', 'action' => 'statistics'))) { ?>
<div  class="h5"><a class="text-primary" href="/damsv2/portfolio/statistics">Portfolio Summary Statistics</a><sup class="text-danger ml-2">SAS</sup></div>
<div class="mb-3">
    Portfolio-level key statistics report.
</div>
<?php } ?>
<?php if ($perm->hasRead(array('controller' => 'Analytics', 'action' => 'loan-collateral-report'))) { ?>
<div  class="h5"><a class="text-primary" href="/damsv2/analytics/loan-collateral-report">SME Initiative Loan and Collateral Statistics</a><sup class="text-danger ml-2">SAS</sup></div>
<div class="mb-3">
    A report which shows mandate and deal level statistics of loan details with a focus on collateral.
</div>
<?php } ?>
<?php if ($perm->hasRead(array('controller' => 'Analytics', 'action' => 'transaction_monitoring'))) { ?>
<div class="h5"><a class="text-primary" target="_blank" href="/damsv2/analytics/transaction_monitoring">Transaction monitoring report  <i class="fas fa-external-link-alt fa-xs"></i></a><sup class="text-danger ml-2">BO</sup></div>
<div class="mb-3">
    <div>A Business Objects (BO) report which combines quantitative and qualitative portfolio-level information.</div>
    <ul style="list-style-type: none;">
        <li>The user is prompted to enter the following:
            <ul style="list-style-type: none;">
                <li>Prompt 1: <em>Report date</em> (tied to eFront data) Always choose the previous business day in order to have the latest data.</li>
                <li>Prompt 2: <em>Mandate</em> Choose the mandate of interest.</li>
                <li>Prompt 3: <em>Historisation Type</em> (refers to the type of historisation in BO for DAMS data) Always choose “Current” historisation type in order to have the latest available changes.</li>
                <li>Prompt 4: <em>Portfolio Report date</em> (tied to DAMS data) Always choose the previous business day in order to have the latest available changes.</li>
                <li>Prompt 5: <em>End of reporting period</em>  Choose the end of the reporting period for which you want to see the data.</li>
                <li>Prompt 6: <em>Value date</em>  Choose the date up to which you want to include guarantee calls, recoveries, and guarantee fees.</li>
            </ul>
        </li>
    </ul>
</div>
<?php } ?>





