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
        'title'   => 'Report',
        'url'     => ['controller' => 'reports', 'action' => 'dsr-view'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>

<h3><?= __('Report') ?></h3>

<hr>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>period year</th>
            <th>name</th>
            <th>portfolio id</th>
            <th>FI</th>
            <th>GENDER_MALE</th>
            <th>GENDER_FEMALE</th>
            <th>GENDER_NI</th>
            <th>EMPLOYMENT_EMPLOYED</th>
            <th>EMPLOYMENT_UNEMPLOYED</th>
            <th>EMPLOYMENT_STUDYING</th>
            <th>EMPLOYMENT_INACTIVE</th>
            <th>EMPLOYMENT_NI</th>
            <th>EDUCATION_NONE</th>
            <th>EDUCATION_PRIMARY</th>
            <th>EDUCATION_SECONDARY</th>
            <th>EDUCATION_POST_SEC</th>
            <th>EDUCATION_UNIVERSITY</th>
            <th>EDUCATION_NI</th>
            <th>AGE_LESS_25</th>
            <th>AGE_25_54</th>
            <th>AGE_55_MORE</th>
            <th>AGE_NI</th>
            <th>GROUP_MINORITY</th>
            <th>GROUP_DISABLED</th>
            <th>GROUP_BOTH</th>
            <th>GROUP_NI</th>
            <th>TOTAL_EMPLOYEES</th>
            <th>TOTAL_MALE</th>
            <th>TOTAL_FEMALE</th>
            <th>TOTAL_LESS_25</th>
            <th>TOTAL_24_54</th>
            <th>TOTAL_MORE_55</th>
            <th>TOTAL_MINORITY</th>
            <th>TOTAL_DISABLED</th>
            <th>TOTAL_EXPOST_EMPLOYEES</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($reports as $report): ?>
            <tr>
                <td><?= $report->period_year ?></td>
                <td><?= $report->name ?></td>
                <td><?= $report->has('portfolio') ? $this->Html->link($report->portfolio->name, ['controller' => 'Portfolios', 'action' => 'view', $report->portfolio->id]) : '' ?></td>
                <td><?= h($report->fi_name) ?></td>
                <td><?= $report->GENDER_MALE ?></td>
                <td><?= $report->GENDER_FEMALE ?></td>
                <td><?= $report->GENDER_NI ?></td>
                <td><?= $report->EMPLOYMENT_EMPLOYED ?></td>
                <td><?= $report->EMPLOYMENT_UNEMPLOYED ?></td>
                <td><?= $report->EMPLOYMENT_STUDYING ?></td>
                <td><?= $report->EMPLOYMENT_INACTIVE ?></td>
                <td><?= $report->EMPLOYMENT_NI ?></td>
                <td><?= $report->EDUCATION_NONE ?></td>
                <td><?= $report->EDUCATION_PRIMARY ?></td>
                <td><?= $report->EDUCATION_SECONDARY ?></td>
                <td><?= $report->EDUCATION_POST_SEC ?></td>
                <td><?= $report->EDUCATION_UNIVERSITY ?></td>
                <td><?= $report->EDUCATION_NI ?></td>
                <td><?= $report->AGE_LESS_25 ?></td>
                <td><?= $report->AGE_25_54 ?></td>
                <td><?= $report->AGE_55_MORE ?></td>
                <td><?= $report->AGE_NI ?></td>
                <td><?= $report->GROUP_MINORITY ?></td>
                <td><?= $report->GROUP_DISABLED ?></td>
                <td><?= $report->GROUP_BOTH ?></td>
                <td><?= $report->GROUP_NI ?></td>
                <td><?= $report->TOTAL_EMPLOYEES ?></td>
                <td><?= $report->TOTAL_MALE ?></td>
                <td><?= $report->TOTAL_FEMALE ?></td>
                <td><?= $report->TOTAL_LESS_25 ?></td>
                <td><?= $report->TOTAL_24_54 ?></td>
                <td><?= $report->TOTAL_MORE_55 ?></td>
                <td><?= $report->TOTAL_MINORITY ?></td>
                <td><?= $report->TOTAL_DISABLED ?></td>
                <td><?= $report->TOTAL_EXPOST_EMPLOYEES ?></td>
            </tr>
    <?php endforeach; ?>
    </tbody>
</table>

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