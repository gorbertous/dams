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
        'title'   => 'Inclusion Dashboard',
        'url'     => ['controller' => 'Report', 'action' => 'index'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
<div class="report index content">

    <h3>Inclusion Dashboard</h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('report_id', '#') ?></th>
                    <th><?= $this->Paginator->sort('report_name') ?></th>
                    <th><?= $this->Paginator->sort('owner', '<i class="fa fa-user"></i> Report', ['escape' => false]) ?></th>
                    <th><?= $this->Paginator->sort('Portfolio.owner', '<i class="fa fa-user"></i> Portfolio', ['escape' => false]) ?></th>
                    <th><?= $this->Paginator->sort('Portfolio.availability_start','Availability end') ?></th>
                    <th><?= $this->Paginator->sort('Portfolio.availability_end') ?></th>
                    <th><?= $this->Paginator->sort('reception_date','Availability end') ?></th>
                    <th><?= $this->Paginator->sort('report_type') ?></th>
                    <th><?= $this->Paginator->sort('Status.stage','Stage') ?></th>
                    <th><?= $this->Paginator->sort('Status.status','Status') ?></th>

                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($report as $report): ?>
                    <tr>
                        <td><?= $this->Number->format($report->report_id) ?></td>
                        <td><?= h($report->report_name) ?></td>
                        <td><?= $report->portfolio->owner ?></td>
                        <td><?= $report->owner ?></td>
                        <td><?= !empty($report->portfolio->inclusion_start_date) ? h($report->portfolio->inclusion_start_date->format('Y-m-d')) : '' ?></td>
                        <td><?= !empty($report->portfolio->inclusion_end_date) ? h($report->portfolio->inclusion_end_date->format('Y-m-d')) : '' ?></td>
                        <td><?= !empty($report->reception_date) ? h($report->reception_date->format('Y-m-d')) : '' ?></td>
                        <td><?= h($report->report_type) ?></td>

                        <td><?= h($report->status->stage) ?></td>
                        <td><?= h($report->status->status) ?></td>


                        <td class="actions">
                            <?= $this->Html->link(__('View'), ['action' => 'view', $report->report_id]) ?>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
