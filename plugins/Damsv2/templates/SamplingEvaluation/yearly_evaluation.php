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
        'title'   => 'Yearly CIP sample evaluation',
        'url'     => ['controller' => 'sampling-evaluation', 'action' => 'yearly-evaluation'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
 <h3>Yearly CIP sample evaluation</h3>
 <hr>

<div class="row">
    <div class="col-6">
     
            
        <?php if (empty($sasResult)): ?>
            <?= $this->Form->create(null,['id' => 'sampleevaluation', 'class' => 'form-inline']); ?>

            <?= $this->Form->select('sampleevaluation.year', $year_list, ['class' => 'form-control mr-3  my-3', 'label' => 'Sample evaluation year', 'id' => 'yearid']); ?>


            <?php
			$disabled = !$perm->hasWrite(array('action' => 'yearlyEvaluation'));
			$class    = 'btn btn-primary form-control mr-3  my-3';
			if($disabled)
			{
				$class    = 'btn btn-primary form-control mr-3  my-3 disabled';
			}
			echo $this->Form->submit('Evaluate', ['class' => $disabled, 'disabled' => $disabled]); ?>
			<?= $this->Html->link('Cancel', ['controller' => 'Home', 'action' => 'home'], ['class' => 'btn btn-danger form-control my-3']) ?>
            <?= $this->Form->end(); ?>
           
        <?php else: ?>
            <?= $sasResult ?>
        <?php endif ?>

            <?php
            $dld_link = "/damsv2/ajax/download-file/";
            if (isset($pdf_link)) {
                echo '<div>Please use this link to download the results in pdf format : <a href="' . $dld_link . $pdf_file .  '/sampling/pdf">file</a></div>';
            }

            if (isset($pdf_list)) {
                
                echo '<h6 class="my-3 py-2">Sample evaluation results for previous years:</h6>';
                
                foreach ($pdf_list as $pdf_file) {
                    if (strpos($pdf_file, '.html') === false) {
                        echo '<div class="row col-sm-12 my-2 py-2">';
                        echo '<a href="' . $dld_link . $pdf_file . '/sampling/pdf">' . $pdf_file . '</a>';
                        echo "</div>";
                    }
                }
            }
            ?>
      
    </div>
</div>
<script>
    $(document).ready(function ()
    {
        $("#yearid").change(function () {
            $("input").prop('disabled', false);// re-enable the submit
        });
    });

</script>