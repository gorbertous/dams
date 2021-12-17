<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Report $report
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Inclusion Dashboard',
        'url'     => ['controller' => 'Report', 'action' => 'inclusion'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Umbrella portfolio result',
        'url'     => ['controller' => 'Report', 'action' => 'split-upload', $report_id],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-inv-crumb'
            ]
        ]
    ]
]);
?>
<h3>Umbrella portfolio result</h3>
<hr>

<div class="row mt-3">
    <div class="col-12">
        <p>Umbrella portfolio : <b><?php echo $umbrella_portfolio_name; ?></b> for the period <b><?php echo $period; ?></b></p>
        <?php if ($brule_missing): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Warning!!</strong> <?= $brules_message; ?>.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php echo $this->Form->create(null, ['id' => 'umbrella_portfolio_result']); ?>

<table id="Dashboard" class="table table-striped">
    <thead>
        <tr>
            <?php
            if (!$double_generation) {
                echo "<th>Select</th>";
            }
            ?>
            <th>Portfolio name</th>
            <th>Report id</th>
            <th>File name</th>
            <th>Inclusion file</th>
            <th>Automatic upload status</th>
            <th>Current report status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (isset($sasResult)) {
            echo $sasResult;
        } else {
            foreach ($sub_portfolios as $portfolio) {
                $is_running = $portfolio['status_id'] == 19 ? true : false;
                $disabled = false;
                if ($is_running || (isset($portfolio->disabled) && $portfolio->disabled)) {
                    $disabled = true;
                }
                ?>
                <tr>
                    <?php
                    if (!$double_generation) {
                        echo '<td>';
                        echo $this->Form->input('Select_' . $portfolio['report_id'], [
                            'label'    => false, 
                            'type'     => 'checkbox',
                            'disabled' => $disabled,
                        ]);
                        echo '</td>';
                    }
                    ?>

                    <td><?= isset($portfolio->portfolio->portfolio_name) ? $portfolio->portfolio->portfolio_name : $portfolio['portfolio_name'] ?></td>
                    <td><?php 
                        if (isset($portfolio)) {
                            echo $portfolio['report_id'];
                        } else {
                            echo 'N/A';
                        }
                    ?>
                    </td>
                    <td><?php if (isset($portfolio)) echo $portfolio['input_filename_umbrella']; ?></td>
                    <?php
                    if (isset($portfolio) && ($portfolio['input_filename_umbrella'] != "")) {
                        echo '<td><a href=' . $this->Url->build(
                                [
                                    'controller' => 'ajax',
                                    'action'     => 'downloadFile',
                                    '_ext'       => null,
                                    $portfolio['input_filename_umbrella'],
                                    'inclusion'
                        ]);
                        echo '>Download</a></td>';
                    } else {
                        echo '<td>No data found</td>';
                    }
                    echo '<td>' . $portfolio['stage'] . '</td>';
                    echo '<td>' . $portfolio['status']['status'] . '</td>';
                    ?>
                </tr>

                <?php
            }
            ?>
        </tbody>
    </table>
    <?php
    if (!$double_generation) {
        //button not shown if disabled
        echo $this->Form->submit('Automatic inclusion', [
            'div'   => false,
            'class' => 'btn btn-primary',
            'id'    => 'save_button',
                //'disabled' => $double_generation
        ]);
    }
    ?>

    <?php
    echo $this->Form->end();
}
?>

<script>
    $(document).ready(function ()
    {
        //prevent double submit
        $('form').submit(function () {
            document.getElementById("save_button").disabled = true;
        });
    });
</script>