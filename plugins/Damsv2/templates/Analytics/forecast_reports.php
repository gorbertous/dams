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
        'title'   => 'Forecasts',
        'url'     => ['controller' => 'analytics', 'action' => 'forecast-reports'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
<h3>Forecasts</h3>
<hr>
<?php if ($perm->hasRead(array('controller' => 'Analytics', 'action' => 'expected-portfolio-volume-report'))) { ?>
<div class="h5" ><a class="text-primary" href="/damsv2/analytics/expected-portfolio-volume-report">Expected Portfolio Volume Report</a><sup class="text-danger ml-2">SAS</sup></div>
<div class="mb-3">
   A report which shows the mandate utilization rate and actual portfolio volume and utilization rate for all deals with finalized as well as non-finalized inclusions.
</div>
<?php } ?>
<?php if ($perm->hasRead(array('controller' => 'Analytics', 'action' => 'flpg-product-repayment-forecast'))) { ?>
<div class="h5"><a class="text-primary" href="/damsv2/analytics/flpg-product-repayment-forecast">FLPG Product Repayment Forecast</a><sup class="text-danger ml-2">SAS</sup></div>
<div class="mb-3">
    A deal level report which shows the expected repayments of outstanding amounts for Jeremie FLPG deals.
</div>
<?php } ?>
<?php if ($perm->hasRead(array('controller' => 'Analytics', 'action' => 'cash-flow-forecast'))) { ?>
<div class="h5" ><a class="text-primary" href="/damsv2/analytics/cash-flow-forecast">Guarantee Calls Forecast</a><sup class="text-danger ml-2">SAS</sup></div>
<div class="mb-3">
   A report which shows forecasted annual net guarantee calls. The user can input a cumulative amount of expected pipeline signatures to be factored into the forecast
</div>
<?php } ?>
<?php if ($perm->hasRead(array('controller' => 'Analytics', 'action' => 'product-repayment-forecast'))) { ?>
<div class="h5"><a class="text-primary" href="/damsv2/analytics/product-repayment-forecast">Loan* Product Repayment Forecast</a><sup class="text-danger ml-2">SAS</sup></div>
<div class="mb-3">
    A deal level report which shows the expected repayments of outstanding amounts.
</div>
<?php } ?>
