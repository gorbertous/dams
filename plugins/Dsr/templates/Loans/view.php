<?php
/**
 * @var \App\View\AppView $this
 * @var \Dsr\Model\Entity\Loan $loan
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Loans',
        'url'     => ['controller' => 'Loans', 'action' => 'index'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => $loan->portfolio->name,
        'url'     => ['controller' => 'Loans', 'action' => 'view', $loan->id],
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
<h3><?= __('Loan') ?></h3>
<div class="row mb-5">
    <div class="col-6">
        <div class="table-responsive">
            
            <table class="table table-striped">
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($loan->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Report') ?></th>
                    <td><?= $loan->has('report') ? $this->Html->link($loan->report->id, ['controller' => 'Reports', 'action' => 'view', $loan->report->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Portfolio') ?></th>
                    <td><?= $loan->has('portfolio') ? $this->Html->link($loan->portfolio->name, ['controller' => 'Portfolios', 'action' => 'view', $loan->portfolio->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Deal Name') ?></th>
                    <td><?= h($loan->deal_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Loan Reference') ?></th>
                    <td><?= h($loan->loan_reference) ?></td>
                </tr>
                <tr>
                    <th><?= __('File Reference') ?></th>
                    <td><?= h($loan->file_reference) ?></td>
                </tr>
                <tr>
                    <th><?= __('Intermediary') ?></th>
                    <td><?= h($loan->intermediary) ?></td>
                </tr>
                <tr>
                    <th><?= __('Country') ?></th>
                    <td><?= h($loan->country) ?></td>
                </tr>
                <tr>
                    <th><?= __('Region') ?></th>
                    <td><?= h($loan->region) ?></td>
                </tr>
                
                <tr>
                    <th><?= __('Start Year') ?></th>
                    <td><?= $loan->start_year ?></td>
                </tr>
                <tr>
                    <th><?= __('End Year') ?></th>
                    <td><?= $loan->end_year ?></td>
                </tr>
                <tr>
                    <th><?= __('Gender') ?></th>
                    <td><?= $this->Number->format($loan->gender) ?></td>
                </tr>
                <tr>
                    <th><?= __('Employment') ?></th>
                    <td><?= $this->Number->format($loan->employment) ?></td>
                </tr>
                <tr>
                    <th><?= __('Education') ?></th>
                    <td><?= $this->Number->format($loan->education) ?></td>
                </tr>
                <tr>
                    <th><?= __('Age') ?></th>
                    <td><?= $this->Number->format($loan->age) ?></td>
                </tr>
                <tr>
                    <th><?= __('Specific Group') ?></th>
                    <td><?= $this->Number->format($loan->specific_group) ?></td>
                </tr>
                <tr>
                    <th><?= __('Total Employees') ?></th>
                    <td><?= $this->Number->format($loan->total_employees) ?></td>
                </tr>
                <tr>
                    <th><?= __('Total Male') ?></th>
                    <td><?= $this->Number->format($loan->total_male) ?></td>
                </tr>
                <tr>
                    <th><?= __('Total Female') ?></th>
                    <td><?= $this->Number->format($loan->total_female) ?></td>
                </tr>
                <tr>
                    <th><?= __('Total Less 25') ?></th>
                    <td><?= $this->Number->format($loan->total_less_25) ?></td>
                </tr>
                <tr>
                    <th><?= __('Total 25 54') ?></th>
                    <td><?= $this->Number->format($loan->total_25_54) ?></td>
                </tr>
                <tr>
                    <th><?= __('Total More 55') ?></th>
                    <td><?= $this->Number->format($loan->total_more_55) ?></td>
                </tr>
                <tr>
                    <th><?= __('Total Minority') ?></th>
                    <td><?= $this->Number->format($loan->total_minority) ?></td>
                </tr>
                <tr>
                    <th><?= __('Total Disabled') ?></th>
                    <td><?= $this->Number->format($loan->total_disabled) ?></td>
                </tr>
                <tr>
                    <th><?= __('Expost Total Employees') ?></th>
                    <td><?= $this->Number->format($loan->expost_total_employees) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= !empty($loan->created) ? h($loan->created->format('Y-m-d H:m:s')) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= !empty($loan->modified) ? h($loan->created->format('Y-m-d H:m:s')) : '' ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="col-3">
        <h4 class="heading"><?= __('Actions') ?></h4>
        <?= $this->Html->link(__('Edit Loan'), ['action' => 'edit', $loan->id], ['class' => 'btn btn-info btn-xs my-2']) ?><br>
        <?= $this->Html->link(__('New Loan'), ['action' => 'add'], ['class' => 'btn btn-primary btn-xs']) ?><br>
        <?= $this->Form->postLink(__('Delete Loan'), ['action' => 'delete', $loan->id], ['title' => __('Delete'), 'class' => 'btn btn-danger btn-xs my-2'], ['confirm' => __('Are you sure you want to delete # {0}?', $loan->id)]) ?><br>
        <?= $this->Html->link(__('List Loans'), ['action' => 'index'], ['class' => 'btn btn-secondary btn-xs']) ?>
    </div>
</div>
