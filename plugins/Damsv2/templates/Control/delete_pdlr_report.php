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
        'title'   => 'Delete pdlr report',
        'url'     => ['controller' => 'Control', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>

<h3>Delete PD/LR report #<?= $report->report_id ?></h3>
<hr>

<?php
echo $this->Form->create(null, ['id' => 'delete']);

if (!empty($report)) {
?>
    <div class="table-responsive">
        <table id="Dashboard" class="table table-stripped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Report name</th>
                    <th>Mandate</th>
                    <th>Flow</th>
                    <th>Stage</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                echo "<tr>";
                echo "<td>" . $report->report_id . "</td>";
                echo "<td>" . $report->report_name . "</td>";
                echo "<td>" . $report->portfolio->mandate . "</td>";
                $flow = 'Inclusion';
                if ($report->template->template_type_id == 2) {
                    $flow = 'PD';
                }
                if ($report->template->template_type_id == 3) {
                    $flow = 'LR';
                }
                echo "<td>" . $flow . "</td>";
                echo "<td>" . $report->status->stage . "</td>";
                echo "<td>" . $report->status->status . "</td>";
                echo "</tr>";
                ?>
            </tbody>
        </table>
    </div>
<?php
}
?>
<div class="row col-12 form-inline my-2">
    <?php
    echo $this->Form->control('Report.capped', [
        'type' => 'checkbox',
        'id' => 'ReportCapped',
        'class'    => 'form-control my-2',
        'label' => false,
    ]);
    echo $this->Form->label('Report.capped', 'The report contains lines that were capped entries before, or the report has been split into an invoiced/paid and capped reports', ['class' => 'h6 my-2 py-2 ml-2']);
    ?>
</div>
<div id="warning_capped" class="row col-12 my-2 py-2 text-danger h6"></div>
<div class="row col-12 form-inline my-2">
    <?php
    echo $this->Form->label('Report.status_target', 'Set back to status:', ['class' => 'h6 my-2 py-2']);
    echo $this->Form->select('Report.status_target', $pdlr_target_status, [
        'class'    => 'form-control ml-2 my-2',
        'label'   => false,
        'id'      => 'ReportStatusTarget',
        'options' => $pdlr_target_status,
    ]);
    echo $this->Form->input('Report.report_id', [
        'type'  => 'hidden',
        'label' => false,
        'div'   => false,
        'value' => $report->report_id,
    ]);
    ?>
</div>
<div class="row col-12 my-2 py-2">
    <?php
    if ($is_clawback) {
        echo "<div class='text-danger h6'>This PD/LR is part of clawback and it should be setback manually.</div>";
    }
    ?>
</div>
<div class="row col-12 my-2">
    <?php
    echo $this->Form->submit('Change status', ['class' => 'btn btn-primary', 'id' => 'submit_button', 'disabled' => $is_clawback]);
    echo $this->Form->end();
    ?>
</div>

<script>
    $(document).ready(function() {
        $('#ReportCapped').change(function(e) {
            if ($('#ReportCapped').is(':checked')) {               
                document.getElementById("ReportStatusTarget").disabled = true;
                document.getElementById("submit_button").disabled = true;
                $('#warning_capped').append('If the report has related capped lines it must be deleted manually');
            } else {
                document.getElementById("submit_button").disabled = false;
                document.getElementById("ReportStatusTarget").disabled = false;
                $('#warning_capped').empty();
            }
        });

        $("form").submit(function(e) {
            document.getElementById("submit_button").disabled = true;
        });
    });
</script>