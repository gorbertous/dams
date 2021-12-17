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
        'title'   => 'Randomly sampled CIP PDs by month',
        'url'     => ['controller' => 'sampling-evaluation', 'action' => 'list-samples'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);

?>
<h3>Randomly sampled CIP PDs by month</h3>
<hr>
<?php if (empty($year_list) || count($year_list) == 1): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Warning!!</strong>  Missing files
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php else : ?>
<div class="row">
     <div class="col-6 form-inline">
        <?= $this->Form->create(null, ['id' => 'sampleDrawing']) ?>
        
            <?= $this->Form->select('year_list', $year_list,
                    [
                        'class' => 'form-control mr-2 my-2',
                        'label' => false,
                        'id' => 'yearlist'
                    ]
            );
            ?>
            <?= $this->Form->select('month_list', $month_list,
                    [
                        'class' => 'form-control mr-2 my-2',
                        'label' => false,
                        'id' => 'monthlist'
                    ]
            );
            ?>
        
        <?php echo $this->Form->end(); ?>

       
    </div>
</div>
<div class="row py-3">
    <div class="col-6">
        <h6>Sampled payment demands</h6>
        <?php foreach ($dir_list as $filename) {
            //echo "<p><a href='".$file_path.$filename."' class='filedownload'>".$filename."</a></p>";
            echo "<p class='filedownload'>" . $this->Html->link($filename,
                    [
                        'controller' => 'ajax',
                        'action'     => 'downloadFile',
                        '_ext' => null,
                        $filename,
                        'sampling','error']
            ) . "</p>";
        }
        ?>
    </div>
</div>
<?php endif ?>
<script>

    $(document).ready(function () {
        $('#yearlist, #monthlist').bind('change', function (e) {
            filter_links();
        });
    });

    function filter_links()
    {
        $(".filedownload").each(function (i, item) {
            var year = $('#yearlist').val();
            var month = $('#monthlist').val();
            var filename = $(item).text();
            var pattern = '';
            if (month == '0')
            {
                pattern = "^[1-9]*";
            } else
            {
                pattern = "^" + month;
            }
            pattern += '_';
            if (year == '0')
            {
                pattern += "[0-9]*";
            } else
            {
                pattern += year;
            }
            pattern += '_CIP_Sample';
            var regxp = new RegExp(pattern, 'i');
            if (regxp.test(filename))
            {
                $(item).show();
            } else
            {
                $(item).hide();
            }
        });//text()
    }

</script>