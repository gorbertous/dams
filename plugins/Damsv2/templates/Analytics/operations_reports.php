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
        'title'   => 'Operations',
        'url'     => ['controller' => 'Analytics', 'action' => 'operations-reports'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
<h3>Operations</h3>
<hr>
<div class="table-responsive">
    <h4>Debt Inclusion Finalization Dates</h4>

    <table class="table table-bordered table-stripped">
        <thead>
            <tr class="bg-primary text-white">
                <th>Mandate</th>
                <th>Reporting Frequency</th>
                <th>Q1 2021</th>
                <th>Q2 2021</th>
                <th>Q3 2021</th>
                <th>Q4 2021</th>
            </tr>
        </thead>
        <tbody>
            <!-- UAT -->
            <tr><td>CCS</td><td>	Quarterly</td><td align="center">	14-Jun	</td><td align="center">14-Sep	</td><td align="center">14-Dec	</td><td align="center">15-Mar</td></tr>
            <tr><td>CIP</td><td>	Quarterly</td><td align="center">	22-Jun	</td><td align="center">21-Sep</td><td align="center">	20-Dec	</td><td align="center">22-Mar</td></tr>
            <tr><td>COSME</td><td>	Quarterly</td><td align="center">	22-Jun	</td><td align="center">21-Sep</td><td align="center">	20-Dec	</td><td align="center">22-Mar</td></tr>
            <tr><td>CYPEF</td><td>	Semi-annual</td><td></td><td align="center">		14-Sep		</td><td></td><td align="center">01-Mar</td></tr>
            <tr><td>DCFTA</td><td>	Semi-annual</td><td></td><td align="center">		10-Sep	</td><td></td><td align="center">	11-Mar</td></tr>
            <tr><td>EASI</td><td>	Semi-annual</td><td align="center">	11-Jun	</td><td></td><td align="center">	10-Dec	</td><td></td></tr>
            <tr><td>EASI Funded</td><td>	Semi-annual</td><td align="center">	11-Jun	</td><td></td><td align="center">	10-Dec	</td><td></td></tr>
            <tr><td>EFSI - Private Credit</td><td>	</td><td align="center">	</td><td></td><td align="center">		</td><td></td></tr>
            <tr><td>EPMF FCP</td><td>	Semi-annual</td><td align="center">	11-Jun	</td><td></td><td align="center">	10-Dec	</td><td></td></tr>
            <tr><td>EPMF FMA</td><td>	Semi-annual</td><td align="center">	11-Jun	</td><td></td><td align="center">	10-Dec	</td><td></td></tr>
            <tr><td>ERASMUS</td><td>	Quarterly</td><td align="center">	14-Jun	</td><td align="center">14-Sep	</td><td align="center">14-Dec	</td><td align="center">15-Mar</td></tr>
            <tr><td>EREM CBSI</td><td>	Quarterly</td><td align="center">	11-Jun	</td><td align="center">10-Sep	</td><td align="center">10-Dec	</td><td align="center">11-Mar</td></tr>

            <tr><td>ESIF AGRI FLPG Italy</td><td>	Quarterly</td><td align="center">	25-May	</td><td align="center">24-Aug	</td><td align="center">23-Nov	</td><td align="center">22-Feb</td></tr>
            <tr><td>ESIF AGRI FLPG</td><td>	Quarterly</td><td align="center">	16-Jun	</td><td align="center">14-Sep	</td><td align="center">14-Dec	</td><td align="center">01-Mar</td></tr>
            <tr><td>ESIF FLPG</td><td>	Quarterly</td><td align="center">	16-Jun	</td><td align="center">14-Sep	</td><td align="center">14-Dec	</td><td align="center">01-Mar</td></tr>
            <tr><td>ESIF PRSL</td><td>	Quarterly</td><td align="center">	16-Jun	</td><td align="center">14-Sep	</td><td align="center">14-Dec	</td><td align="center">01-Mar</td></tr>

            <tr><td>Future Growth Loan Scheme</td><td>	Quarterly</td><td align="center">	25-May	</td><td align="center">24-Aug	</td><td align="center">23-Nov	</td><td align="center">22-Feb</td></tr>
            <tr><td>GAGF</td><td>	Quarterly</td><td align="center">	29-Jun	</td><td align="center">28-Sep	</td><td align="center">31-Dec	</td><td align="center">29-Mar</td></tr>
            <tr><td>INAF</td><td>	Quarterly</td><td align="center">	25-Jun	</td><td align="center">24-Sep	</td><td align="center">22-Dec	</td><td align="center">25-Mar</td></tr>
            <tr><td>InnovFin</td><td>	Quarterly</td><td align="center">	25-May	</td><td align="center">24-Aug	</td><td align="center">23-Nov	</td><td align="center">22-Feb</td></tr>
            <tr><td>JEREMIE</td><td>	Quarterly</td><td align="center">	16-Jun	</td><td align="center">14-Sep	</td><td align="center">14-Dec	</td><td align="center">01-Mar</td></tr>
            <tr><td>MAP</td><td>	Quarterly</td><td colspan="4" align="center">	Inclusions ended			</td></tr>
            <tr><td>RSI</td><td>	Quarterly</td><td align="center">	25-May	</td><td align="center">24-Aug	</td><td align="center">23-Nov	</td><td align="center">22-Feb</td></tr>
            <tr><td>SMEG98</td><td>	Quarterly</td><td colspan="4"  align="center">	Inclusions ended			</td></tr>
            <tr><td>SMEi (ES, MT, FI, BG, RO)</td><td>	Quarterly</td><td align="center">	25-May	</td><td align="center">24-Aug	</td><td align="center">23-Nov	</td><td align="center">20-Feb</td></tr>
            <tr><td>SMEi IT (AP)</td><td>	Quarterly</td><td align="center">	16-Jun	</td><td align="center">14-Sep	</td><td align="center">14-Dec	</td><td align="center">15-Mar</td></tr>
            <tr><td>WB GF Serbia</td><td>	Semi-annual</td><td></td><td align="center">		14-Sep	</td><td></td><td align="center">	15-Mar</td></tr>
            <tr><td>WB I</td><td>	Semi-annual</td><td></td><td align="center">		14-Sep	</td><td></td><td align="center">	15-Mar</td></tr>
            <tr><td>WB II</td><td>	Semi-annual</td><td></td><td align="center">		14-Sep	</td><td></td><td align="center">	15-Mar</td></tr>
            <tr><td>WB Youth Employment</td><td>	Semi-annual</td><td></td><td align="center">		14-Sep	</td><td></td><td align="center">	15-Mar</td></tr>
            <tr><td>EERE Malta</td><td>	Semi-annual</td><td></td><td align="center">		24-Aug	</td><td></td><td align="center">	22-Feb</td></tr>
            <tr><td>EFSI Skills and Education Guarantee Pilot</td><td>	Quarterly</td><td align="center">	25-Jun	</td><td align="center">24-Sep	</td><td align="center">22-Dec	</td><td align="center">25-Mar</td></tr>
            <tr><td>EGF Capped</td><td>	Quarterly</td><td align="center">	7-Jun	</td><td align="center">7-Sep	</td><td align="center">7-Dec	</td><td align="center">8-Mar</td></tr>
            <tr><td>EGF Uncapped</td><td>	Quarterly</td><td align="center">	7-Jun	</td><td align="center">7-Sep	</td><td align="center">7-Dec	</td><td align="center">8-Mar</td></tr>

        </tbody>
    </table>
    <div>
        <small>Notes:<br />(1) These deadlines are indicative and depend, among other things, on the responsiveness of the intermediaries.
            <br />(2) If a deadline falls on a weekend, the following business day applies.
            <br />(3) For any questions about the inclusions please contact the Data Input & Validation unit (<a href="mailto:EIF-OIM-DH-DP@eif.org">EIF-OIM-DH-DP@eif.org</a>).
        </small>
    </div>
</div>

<div class="h5 mt-3"><a class="text-primary" href="/damsv2/analytics/inclusion-status">Inclusion Status Report</a><sup class="text-danger ml-2">SAS</sup></div>
<div class="mb-3">
    A report which lists the latest finalized inclusions by deal.
</div>

