<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SamplingEvaluation $samplingEvaluation
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Download SME Rating Mapping',
        'url'     => ['controller' => 'sme-rating-mapping', 'action' => 'download-rating'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
<h3>Download SME Rating Mapping</h3>
<hr>

<?= $this->Form->create(null, ['id' => 'SmeRating']) ?>
<div class="row">
    <div class="col-6 w-25 form-inline">
        <label class="col-form-label h6">Portfolio ID</label>
        <?= $this->Form->select('SmeRating.portfolio_id', $portfolios, ['label' => false, 'empty' => '- All Portfolios -', 'class' => 'form-control ml-3']); ?>
         <?= $this->Form->submit('Download', [
            'class' => 'btn btn-primary form-control ml-3  my-3',
            'id'    => 'save_button'
        ])
        ?>
    </div>
</div>

<?= $this->Form->end(); ?>

<script>
    $(document).ready(function ()
    {
        <?php
        if (isset($download_link)) {
            ?>
                    window.open("<?php echo $download_link; ?>");
            <?php
        }
        ?>
    });
</script>