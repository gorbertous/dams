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
        'title'   => 'Data Extracts',
        'url'     => ['controller' => 'Analytics', 'action' => 'data-extracts-reports'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
<h3>Data Extracts</h3>
<hr>
<?php if ($perm->hasRead(array('controller' => 'Analytics', 'action' => 'generic-data-extract'))) { ?>
<div class="h5"><a class="text-primary" href="/damsv2/analytics/generic-data-extract">In-depth Data Extract</a><sup class="text-danger ml-2">SAS</sup></div>
<div class="mb-3">
    A comprehensive transaction-level report which includes loan characteristics, SME characteristics, as well as further details about any loan defaults and recoveries.
</div>
<?php } ?>
<?php if ($perm->hasRead(array('controller' => 'Analytics', 'action' => 'key-fields-report'))) { ?>
<div class="h5"><a class="text-primary" href="/damsv2/analytics/key-fields-report">Key Fields Data Extract</a><sup class="text-danger ml-2">SAS</sup></div>
<div class="mb-3">
    Transaction-level report which includes loan characteristics and SME characteristics.
</div>
<?php } ?>
