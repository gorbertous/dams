<?php
/**
 * @var \App\View\AppView $this
 * @var \Dsr\Model\Entity\Loan[]|\Cake\Collection\CollectionInterface $loans
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Loans',
        'url'     => ['controller' => 'loans', 'action' => 'index'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>

<?= $this->Html->link(__('New Loan'), ['action' => 'add'], ['class' => 'btn btn-primary float-right my-2 py-2']) ?>
<h3><?= __('Loans') ?></h3>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('report_id') ?></th>
                <th><?= $this->Paginator->sort('portfolio_id') ?></th>
                <th><?= $this->Paginator->sort('deal_name') ?></th>
                <th><?= $this->Paginator->sort('start_year') ?></th>
                <th><?= $this->Paginator->sort('end_year') ?></th>
                <th><?= $this->Paginator->sort('loan_reference') ?></th>
                <th><?= $this->Paginator->sort('file_reference') ?></th>
                <th><?= $this->Paginator->sort('intermediary') ?></th>
                <th><?= $this->Paginator->sort('gender') ?></th>
                <th><?= $this->Paginator->sort('employment') ?></th>
                <th><?= $this->Paginator->sort('education') ?></th>
                <th><?= $this->Paginator->sort('age') ?></th>
                <th><?= $this->Paginator->sort('specific_group') ?></th>
                <th><?= $this->Paginator->sort('country') ?></th>
                <th><?= $this->Paginator->sort('region') ?></th>
                <th><?= $this->Paginator->sort('total_employees') ?></th>
                <th><?= $this->Paginator->sort('total_male') ?></th>
                <th><?= $this->Paginator->sort('total_female') ?></th>
                <th><?= $this->Paginator->sort('total_less_25') ?></th>
                <th><?= $this->Paginator->sort('total_25_54') ?></th>
                <th><?= $this->Paginator->sort('total_more_55') ?></th>
                <th><?= $this->Paginator->sort('total_minority') ?></th>
                <th><?= $this->Paginator->sort('total_disabled') ?></th>
                <th><?= $this->Paginator->sort('expost_total_employees') ?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th><?= $this->Paginator->sort('modified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($loans as $loan): ?>
                <tr>
                    <td><?= $loan->id ?></td>
                    <td><?= $loan->has('report') ? $this->Html->link($loan->report->id, ['controller' => 'Reports', 'action' => 'view', $loan->report->id]) : '' ?></td>
                    <td><?= $loan->has('portfolio') ? $this->Html->link($loan->portfolio->name, ['controller' => 'Portfolios', 'action' => 'view', $loan->portfolio->id]) : '' ?></td>
                    <td><?= h($loan->deal_name) ?></td>
                    <td><?= $loan->start_year ?></td>
                    <td><?= $loan->end_year ?></td>
                    <td><?= h($loan->loan_reference) ?></td>
                    <td><?= h($loan->file_reference) ?></td>
                    <td><?= h($loan->intermediary) ?></td>
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
                        <?= $this->Html->link('<i class="fas fa-eye"></i>', ['action' => 'view', $loan->id], ['escape' => false, 'title' => __('View'), 'class' => 'btn btn-info btn-xs mb-2']) ?>
                        <?= $this->Html->link('<i class="fas fa-pencil-alt"></i>', ['action' => 'edit', $loan->id], ['escape' => false, 'title' => __('Edit'), 'class' => 'btn btn-info btn-xs']) ?>

                        <?= $this->Form->postLink('<i class="fas fa-trash-alt"></i>', ['action' => 'delete', $loan->id], ['escape' => false, 'title' => __('Delete'), 'class' => 'btn btn-danger btn-xs my-2'], ['confirm' => __('Are you sure you want to delete # {0}?', $loan->id)]) ?>
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

