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
    [
        'title'   => 'Active Portfolio Monitoring Report',
        'url'     => ['controller' => 'Report', 'action' => 'active-portfolio-management'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
<h3>Active Portfolio Monitoring Report</h3>
<hr>


<?php
    if (!empty($sas_res))
    {
    ?>
    <div class="alert alert-error alert-dismissible fade show" role="alert">
        <strong>Error!</strong> <?= $sas_res; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    <?php
    }
    ?>
<?= $this->Form->create(null, ['id' => 'col_report']) ?>

<div class="form-group row">
    <div class="col-6 form-inline">
        <label class="col-sm-3 col-form-label h6 required" for="Report.Mandate">Mandate </label>        
        <?= $this->Form->select('Report.Mandate', $mandates,
        [
            'empty' => '-- Select Mandate --',
            'class' => 'w-50 form-control my-2 py-2',
            'required'  => true,
            'id'    => 'productid'
                ]
        );
        ?>
      
    </div>
</div>

<div class="row">
    <div class="col-6 form-inline">

        <?= $this->Form->submit('Generate report', [
            'class'    => 'btn btn-primary form-control mr-3  my-3',
            'id'    => 'submit_id'
            ])
        ?>
        <?= $this->Html->link('Cancel', ['action' => 'analytics-reports'], ['class' => 'btn btn-danger form-control my-3']) ?>

    </div>
</div>
<?php echo $this->Form->end(); ?>
<script>
$(document).ready(function(){
    $("form").submit(function (e)
    {
            document.getElementById("submit_id").disabled = true;
    });

    <?php
    if (isset($download_link))
    {
    ?>
    window.open("<?php echo $download_link; ?>");
    <?php
    }

    ?>
});
</script>