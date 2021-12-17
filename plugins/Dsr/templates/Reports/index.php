<?php
/**
 * @var \App\View\AppView $this
 * @var \Dsr\Model\Entity\Report[]|\Cake\Collection\CollectionInterface $reports
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Reports',
        'url'     => ['controller' => 'reports', 'action' => 'index'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>

<h3><?= __('Reports') ?></h3>

<?= $this->Html->link(__('New Report'), ['action' => 'add'], ['class' => 'btn btn-primary float-right my-2 py-2']) ?>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('portfolio_id') ?></th>
                <th><?= $this->Paginator->sort('period_quarter') ?></th>
                <th><?= $this->Paginator->sort('period_year') ?></th>
                <th><?= $this->Paginator->sort('report_date') ?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th><?= $this->Paginator->sort('modified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reports as $report): ?>
            <tr>
                <td><?= $this->Number->format($report->id) ?></td>
                <td><?= $report->has('portfolio') ? $this->Html->link($report->portfolio->name, ['controller' => 'Portfolios', 'action' => 'view', $report->portfolio->id]) : '' ?></td>
                <td><?= h($report->period_quarter) ?></td>
                <td><?= h($report->period_year) ?></td>
                <td><?= !empty($report->report_date) ? h($report->report_date->format('Y-m-d')): '' ?></td>
                <td><?= !empty($report->created) ? h($report->created->format('Y-m-d H:m:s')): '' ?></td>
                <td><?= !empty($report->modified) ? h($report->created->format('Y-m-d H:m:s')): '' ?></td>
                <td class="actions">
                    <?= $this->Html->link('<i class="fas fa-eye"></i>' ,['action' => 'view', $report->id],['escape' => false, 'title' => __('View'), 'class' => 'btn btn-info btn-xs'])?>
                    <?= $this->Html->link('<i class="fas fa-pencil-alt"></i>' ,['action' => 'edit', $report->id],['escape' => false, 'title' => __('Edit'), 'class' => 'btn btn-info btn-xs'])?>
                   
                    <?= $this->Form->postLink('<i class="fas fa-trash-alt"></i>' , ['action' => 'delete', $report->id],['escape' => false, 'title' => __('Delete'), 'class' => 'btn btn-danger btn-xs'], ['confirm' => __('Are you sure you want to delete # {0}?', $report->id)]) ?>
                  
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
