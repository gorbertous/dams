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
        'title'   => 'Annual CIP sampling parameters',
        'url'     => ['controller' => 'sampling-evaluation', 'action' => 'index'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>

<h3>Annual CIP sampling parameters</h3>
<hr>
<?= $this->Form->create(null, ['id' => 'AnnualSamplingParameter'])?>
<div class="form-group row">
    <div class="col-6">
        <label class="col-sm-3 col-form-label h6">Year</label>
        
        <?= $this->Form->input('AnnualSamplingParameter.year', [
            'class'    => 'mr-2 my-2 py-2',
            'label'    => false,
            'value' => key($years),
            'disabled' => true,
           
        ]);
        ?>
      
        <?= $this->Form->hidden('year', ['value' => key($years)]) ?>
    </div>
</div>

<div class="form-group row">
    <div class="col-6">
        <label class="col-sm-3 col-form-label h6 required">Expected amount of payment demands, EUR</label>
    <?php
        $expected_amount = floatval($expected_amount);
        
        echo $this->Form->input('AnnualSamplingParameter.expected_amount', ['class' => 'mr-2 my-2 py-2', 'required' => true, 'disabled' => $disabled, 'label' => false, 'value' => $this->Number->format($expected_amount)]);
        if ($disabled) {
            echo $this->Form->hidden('expected_amount', [
                'value' => $this->Number->format($expected_amount),
                'id'    => 'AnnualSamplingParameterExpectedAmount'
            ]);
        }
    ?>
    </div>
</div>
<div class="form-group row">
    <div class="col-6">
        <label class="col-sm-3 col-form-label h6 required">Number of samples</label>
    <?php
        echo $this->Form->input('AnnualSamplingParameter.number_of_samples', ['class' => 'mr-2 my-2 py-2', 'required' => true, 'disabled' => $disabled, 'label' => false, 'value' => $number_of_samples]);
        if ($disabled) {
            echo $this->Form->hidden('number_of_samples', [
                'value' => $number_of_samples,
                'id'    => 'AnnualSamplingParameterNumberOfSamples'
            ]);
        }
    ?>
    </div>
</div>
<div class="form-group row">
    <div class="col-6">
        <label class="col-sm-3 col-form-label h6 required">Sampling interval, EUR</label>
    <?php    
        $sampling_interval = floatval($sampling_interval);
       
        echo $this->Form->input('AnnualSamplingParameter.sampling_interval', ['class' => 'mr-2 my-2 py-2', 'required' => true, 'disabled' => true, 'label' => false, 'id'    => 'AnnualSamplingParameterSamplingInterval', 'value' => $this->Number->format($sampling_interval)]);
        
        echo $this->Form->hidden('sampling_interval',  [
            'value' => $this->Number->format($sampling_interval),
            'id'    => 'id_sampling_interval',
        ]);
        echo $this->Form->hidden('sampled_month',  [
            'value' => $sampled_month,
        ]);
        echo $this->Form->hidden('last_sampled_month',  [
            'value' => $last_sampled_month,
        ]);
    ?>
    </div>
</div>

<div class="row">
    <div class="col-6 form-inline">

        <?= $this->Form->submit('Save', [
            'class'    => 'btn btn-primary form-control mr-3  my-3',
            'id'    => 'save_button',
            'disabled' => $disabled,
        ])
        ?>
        <?= $this->Html->link('Cancel', ['controller' => 'Home', 'action' => 'home'], ['class' => 'btn btn-danger form-control my-3']) ?>

    </div>
</div>
<?php echo $this->Form->end(); ?>




<script>

    $(document).ready(function () {
        $('#AnnualSamplingParameterExpectedAmount, #AnnualSamplingParameterNumberOfSamples').bind('change', function (e) {
            calculate_interval();
        });


    });

    function calculate_interval()
    {
        var number_of_sample = parseInt($('#AnnualSamplingParameterNumberOfSamples').val(), 10);
        var amount = parseInt($('#AnnualSamplingParameterExpectedAmount').val(), 10);

        var interval = amount / number_of_sample;
        if (!isNaN(interval))
        {
            //round to 2 after dot
            interval = Math.round(interval * 100) / 100;
            $('#AnnualSamplingParameterSamplingInterval').val(interval);
            $('#id_sampling_interval').val(interval);
        }
    }

</script>