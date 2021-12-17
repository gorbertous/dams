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
        'title'   => $report->report_name,
        'url'     => ['controller' => 'Report', 'action' => 'view', $report->report_id],
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


<h3>Inclusion history of <strong><?= $report->portfolio->portfolio_name ?></strong> for the period: <strong><?= $report->period_year ?><?= $report->period_quarter ?></strong></h3>
<hr>


<div class="row">
    <div class="col-6 table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Link</th>
                    <th>Filename</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($uploads as $file): ?>
                    <tr>
                        <td>
                            <?php
                            if (file_exists("/var/www/html/data/damsv2/upload/" . $file)) {
                                echo $this->Html->link('Download',
                                        [
                                            'controller' => 'ajax',
                                            'action'     => 'downloadFile',
                                            '_ext' => null,
                                             $file,
                                            'inclusion']
                                );
                            } else {
                                echo $this->Html->link('Download',
                                        [
                                            'controller' => 'ajax',
                                            'action'     => 'downloadFile',
                                            '_ext' => null,
                                             $file,
                                            'archive']
                                );
                            }
                            ?>
                        </td>
                        <td><?= $file ?> </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="col-6 table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Link</th>
                    <th>Filename</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($errors as $key => $file): ?>
                    <tr>
                        <td><?php
                            echo $this->Html->link('Download',
                                    [
                                        'controller' => 'ajax',
                                        'action'     => 'downloadFile',
                                        '_ext' => null,
                                        $file,
                                       'error']
                            );
                            ?></td>
                        <td><?= $file ?> </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
