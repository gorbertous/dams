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
        'title'   => 'FAQ',
        'url'     => ['controller' => 'analytics', 'action' => 'faq'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
<h3>FAQ</h3>
<hr>
<div class="mb-3">
    <strong>Who is the creator of the reports? </strong><br>
    Originally Portfolio Analytics within MIBO-DS (MIBO-DS-PA) has initiated and currently Data Analytics within OIM (EIF-OIM-DH-DA) is responsible for the SAS codes that create the reports, which in turn provide the data for you in the excel output file.
</div>
<div class="mb-3">
    <strong>What is SAS?  </strong><br>
    SAS (Statistical Analytical Software) is an integrated software suite for data analytics where the package purchased by EIF has options for an intuitive point-and-click interface for less proficient users and enhanced coding in the SAS language for more advanced users. 
</div>
<div class="mb-3">
    <strong>What database am I accessing by running these reports?   </strong><br>
    By running the SAS reports (denoted with the SAS icon) you are accessing the DAMS database and eFront data warehouse live which means that you have instant access to the most up-to-date data. 
</div>
<div class="mb-3">
    <strong>What is DAMS?    </strong><br>
    DAMS (Debt Administration & Monitoring System) is an internally developed application designed to closely monitor EIF’s debt products especially with respect to the inclusion and guarantee calls processes. As of today all guarantee and loan products are managed in DAMS except the securitisation deals and the quasi-debt product diversified loan funds.
</div>
<div class="mb-3">
    <strong>What is eFront?     </strong><br>
    Developed by the eFront company, the software was initially deployed to EIF to manage EIF’s equity transactions. The eFront solution currently stores the core information about all EIF business lines (Mandate, Equity, Guarantee & Microfinance) and makes the data available through data warehouse for reporting and analytical purposes.
</div>
<div class="mb-3">
    <strong>What if I retrieve a report during an active inclusions period? Is the data reliable?      </strong><br>
    The accessed data is always validated before being stored in the database and is reliable. Data in processing stages is not available. During an active inclusion period some deals may have their inclusion finalized while others are in processing stages and thus any mandate level statistics might need to be used with caution. To see the latest finalized inclusion period per deal run the report entitled ” Inclusion Status Report” in the Operations tab.
</div>

<div class="mb-3">
    <strong>What if I want to modify the report and add a field, for example?       </strong><br>
    These reports have been specified and written in SAS and cannot be changed via the website. You can reach out to <a href="mailto:EIF-OIM-DH-DA@eif.org">EIF-OIM-DH-DA@eif.org</a> with any special requests.
</div>
<div class="mb-3">
    <strong>What if our Team is in a frequent need of a certain type of data and would like to add a new report?      </strong><br>
    If the particular needs are not met by any of the reports yet, the possibility of preparing a new customized report can be discussed by reaching out to <a href="mailto:EIF-OIM-DH-DA@eif.org">EIF-OIM-DH-DA@eif.org</a>.
</div>
<div class="mb-3">
    <strong>How can a colleague get access to these reports?       </strong><br>
    Anyone interested in getting access to the reports should send an email to <a href="mailto:eifsas-support@eif.org">eifsas-support@eif.org</a> expressing their interest. The SAS team will proceed with gathering the needed approvals and providing you with the proper access.
</div>
<div class="mb-3">
    <strong>Can these reports be run if teleworking or while offsite?        </strong><br>
    The data is now stored on Amazon Web Services (AWS) so it can be accessed remotely using your EIB/EIF laptop with the VPN connection.
</div>
<div class="mb-3">
    <strong>What if I get an “error message” while running any of the reports?        </strong><br>
    Contact SAS Support at <a href="mailto:eifsas-support@eif.org">eifsas-support@eif.org</a>.
</div>
