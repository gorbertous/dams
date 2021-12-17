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
        'title'   => 'Delete Inclusion report',
        'url'     => ['controller' => 'Control', 'action' => 'pdlr-list'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>

<h3>Delete Inclusion report #<?= $report->report_id ?></h3>
<hr>


<?php if (!empty($report)) { ?>
<div class="table-responsive">
    <table id="Dashboard" class="table table-stripped">
        <thead>
            <tr>
                <th>#</th>
                <th>Report name</th>
                <th>Mandate</th>
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
            echo "<td>" . $report->status->stage . "</td>";
            echo "<td>" . $report->status->status . "</td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</div>
<?php
    if ($is_clawback) {
        echo "<div class='text-danger my-2'>This inclusion report may be linked to a clawback and has to be deleted manually.</div>";
    }
    echo $this->Form->create(null, ['class' => 'form-inline', 'id' => 'delete']);
    echo $this->Form->input('Report.report_id', [
        'type'  => 'hidden',
        'label' => false,
        'value' => $report->report_id,
    ]);

    echo $this->Form->submit('Delete report', ['class' => 'btn btn-primary', 'disabled' => $is_clawback]);
    echo $this->Form->end();
?>
