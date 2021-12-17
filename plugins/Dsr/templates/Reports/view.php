<?php
/**
 * @var \App\View\AppView $this
 * @var \Dsr\Model\Entity\Report $report
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Reports',
        'url'     => ['controller' => 'Reports', 'action' => 'index'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => $report->portfolio->name,
        'url'     => ['controller' => 'Reports', 'action' => 'view', $report->id],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-port-crumb'
            ]
        ]
    ]
]);
?>

<h3><?= __('Report') ?></h3>
<div class="row mb-5">
    <div class="col-6">
        <div class="table-responsive">

            <table class="table table-striped">
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($report->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Portfolio') ?></th>
                    <td><?= $report->has('portfolio') ? $this->Html->link($report->portfolio->name, ['controller' => 'Portfolios', 'action' => 'view', $report->portfolio->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Period Quarter') ?></th>
                    <td><?= h($report->period_quarter) ?></td>
                </tr>

                <tr>
                    <th><?= __('Period Year') ?></th>
                    <td><?= $report->period_year ?></td>
                </tr>
                <tr>
                    <th><?= __('Report Date') ?></th>
                    <td><?= !empty($report->report_date) ? h($report->report_date->format('Y-m-d')) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= !empty($report->created) ? h($report->created->format('Y-m-d H:m:s')) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= !empty($report->modified) ? h($report->created->format('Y-m-d H:m:s')) : '' ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="col-3">
        <h4 class="heading"><?= __('Actions') ?></h4>
        <?= $this->Html->link(__('Edit Report'), ['action' => 'edit', $report->id], ['class' => 'btn btn-info btn-xs my-2']) ?><br>
        <?= $this->Html->link(__('New Report'), ['action' => 'add'], ['class' => 'btn btn-primary btn-xs']) ?><br>
        <?= $this->Form->postLink(__('Delete Report'), ['action' => 'delete', $report->id], ['title' => __('Delete'), 'class' => 'btn btn-danger btn-xs my-2'], ['confirm' => __('Are you sure you want to delete # {0}?', $report->id)]) ?><br>
        <?= $this->Html->link(__('List Reports'), ['action' => 'index'], ['class' => 'btn btn-secondary btn-xs']) ?>
    </div>
</div>
<?php if (!empty($report->loans)) : ?>
    <h3><?= __('Related Loans') ?></h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= __('Id') ?></th>
                    <th><?= __('Loan reference') ?></th>
                    <th><?= __('File reference') ?></th>
                    <th><?= __('Gender') ?></th>
                    <th><?= __('Employment') ?></th>
                    <th><?= __('Education') ?></th>
                    <th><?= __('Age') ?></th>
                    <th><?= __('Specific group') ?></th>
                    <th><?= __('Country') ?></th>
                    <th><?= __('Region') ?></th>
                    <th><?= __('Total employees') ?></th>
                    <th><?= __('Total male') ?></th>
                    <th><?= __('Total female') ?></th>
                    <th><?= __('Total < 25') ?></th>
                    <th><?= __('Total 25-54') ?></th>
                    <th><?= __('Total > 55') ?></th>
                    <th><?= __('Total minority') ?></th>
                    <th><?= __('Total disabled') ?></th>
                    <th><?= __('Expost total employees') ?></th>
                    <th><?= __('Created') ?></th>
                    <th><?= __('Modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($report->loans as $loan): ?>
                    <tr>
                        <td><?= $loan->id ?></td>
                        <td><?= h($loan->loan_reference) ?></td>
                        <td><?= h($loan->file_reference) ?></td>
                        <td><?= $loan->gender ?></td>
                        <td><?= $loan->employment ?></td>
                        <td><?= $loan->education ?></td>
                        <td><?= $loan->age ?></td>
                        <td><?= $loan->specific_group ?></td>
                        <td><?= h($loan->country) ?></td>
                        <td><?= h($loan->region) ?></td>
                        <td><?= $loan->total_employees ?></td>
                        <td><?= $loan->total_male ?></td>
                        <td><?= $loan->total_female ?></td>
                        <td><?= $loan->total_less_25 ?></td>
                        <td><?= $loan->total_25_54 ?></td>
                        <td><?= $loan->total_more_55 ?></td>
                        <td><?= $loan->total_minority ?></td>
                        <td><?= $loan->total_disabled ?></td>
                        <td><?= $loan->expost_total_employees ?></td>
                        <td><?= !empty($loan->created) ? h($loan->created->format('Y-m-d H:m:s')) : '' ?></td>
                        <td><?= !empty($loan->modified) ? h($loan->created->format('Y-m-d H:m:s')) : '' ?></td>
                        <td class="actions">
                            <?= $this->Html->link('<i class="fas fa-eye"></i>', ['controller' => 'loans', 'action' => 'view', $loan->id], ['escape' => false, 'title' => __('View'), 'class' => 'btn btn-info btn-xs mb-2']) ?>
                            <?= $this->Html->link('<i class="fas fa-pencil-alt"></i>', ['controller' => 'loans', 'action' => 'edit', $loan->id], ['escape' => false, 'title' => __('Edit'), 'class' => 'btn btn-info btn-xs']) ?>

                            <?= $this->Form->postLink('<i class="fas fa-trash-alt"></i>', ['controller' => 'loans', 'action' => 'delete', $loan->id], ['escape' => false, 'title' => __('Delete'), 'class' => 'btn btn-danger btn-xs my-2'], ['confirm' => __('Are you sure you want to delete # {0}?', $loan->id)]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
