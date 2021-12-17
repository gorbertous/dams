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
<div class="row">
    
    <div class="column-responsive column-80">
        <div class="report view content">
            <h3><?= h($report->report_name) ?></h3>
            <table class="table table-striped">
                <tr>
                    <th><?= __('Report Name') ?></th>
                    <td><?= h($report->report_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Period Quarter') ?></th>
                    <td><?= h($report->period_quarter) ?></td>
                </tr>
                <tr>
                    <th><?= __('Validation Status') ?></th>
                    <td><?= h($report->validation_status) ?></td>
                </tr>
                <tr>
                    <th><?= __('Comments Validator2') ?></th>
                    <td><?= h($report->comments_validator2) ?></td>
                </tr>
                <tr>
                    <th><?= __('Operation Iqid') ?></th>
                    <td><?= h($report->operation_iqid) ?></td>
                </tr>
                <tr>
                    <th><?= __('Invoice') ?></th>
                    <td><?= $report->has('invoice') ? $this->Html->link($report->invoice->invoice_id, ['controller' => 'Invoice', 'action' => 'view', $report->invoice->invoice_id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Sheets') ?></th>
                    <td><?= h($report->sheets) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sheets Umbrella') ?></th>
                    <td><?= h($report->sheets_umbrella) ?></td>
                </tr>
                <tr>
                    <th><?= __('Ccy') ?></th>
                    <td><?= h($report->ccy) ?></td>
                </tr>
                <tr>
                    <th><?= __('Input Filename') ?></th>
                    <td><?= h($report->input_filename) ?></td>
                </tr>
                <tr>
                    <th><?= __('Input Filename Umbrella') ?></th>
                    <td><?= h($report->input_filename_umbrella) ?></td>
                </tr>
                <tr>
                    <th><?= __('Output Filename') ?></th>
                    <td><?= h($report->output_filename) ?></td>
                </tr>
                <tr>
                    <th><?= __('Report Type') ?></th>
                    <td><?= h($report->report_type) ?></td>
                </tr>
                <tr>
                    <th><?= __('Clawback') ?></th>
                    <td><?= h($report->clawback) ?></td>
                </tr>
                <tr>
                    <th><?= __('Pkid') ?></th>
                    <td><?= h($report->pkid) ?></td>
                </tr>
                <tr>
                    <th><?= __('M Files Link') ?></th>
                    <td><?= h($report->m_files_link) ?></td>
                </tr>
                <tr>
                    <th><?= __('Report Id') ?></th>
                    <td><?= $this->Number->format($report->report_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Period Year') ?></th>
                    <td><?= $this->Number->format($report->period_year) ?></td>
                </tr>
                <tr>
                    <th><?= __('Portfolio Id') ?></th>
                    <td><?= $this->Number->format($report->portfolio_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Template Id') ?></th>
                    <td><?= $this->Number->format($report->template_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Status Id') ?></th>
                    <td><?= $this->Number->format($report->status_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Validator1') ?></th>
                    <td><?= $this->Number->format($report->validator1) ?></td>
                </tr>
                <tr>
                    <th><?= __('Validator2') ?></th>
                    <td><?= $this->Number->format($report->validator2) ?></td>
                </tr>
                <tr>
                    <th><?= __('Status Id Umbrella') ?></th>
                    <td><?= $this->Number->format($report->status_id_umbrella) ?></td>
                </tr>
                <tr>
                    <th><?= __('Owner') ?></th>
                    <td><?= $this->Number->format($report->owner) ?></td>
                </tr>
                <tr>
                    <th><?= __('Version Number') ?></th>
                    <td><?= $this->Number->format($report->version_number) ?></td>
                </tr>
                <tr>
                    <th><?= __('Header') ?></th>
                    <td><?= $this->Number->format($report->header) ?></td>
                </tr>
                <tr>
                    <th><?= __('Amount') ?></th>
                    <td><?= $this->Number->format($report->amount) ?></td>
                </tr>
                <tr>
                    <th><?= __('Amount EUR') ?></th>
                    <td><?= $this->Number->format($report->amount_EUR) ?></td>
                </tr>
                <tr>
                    <th><?= __('Amount Ctr') ?></th>
                    <td><?= $this->Number->format($report->amount_ctr) ?></td>
                </tr>
                <tr>
                    <th><?= __('Visible') ?></th>
                    <td><?= $this->Number->format($report->visible) ?></td>
                </tr>
                <tr>
                    <th><?= __('Bulk') ?></th>
                    <td><?= $this->Number->format($report->bulk) ?></td>
                </tr>
                <tr>
                    <th><?= __('Management Fees') ?></th>
                    <td><?= $this->Number->format($report->management_fees) ?></td>
                </tr>
                <tr>
                    <th><?= __('Requests') ?></th>
                    <td><?= $this->Number->format($report->requests) ?></td>
                </tr>
                <tr>
                    <th><?= __('Rejections') ?></th>
                    <td><?= $this->Number->format($report->rejections) ?></td>
                </tr>
                <tr>
                    <th><?= __('Rejection Rate') ?></th>
                    <td><?= $this->Number->format($report->rejection_rate) ?></td>
                </tr>
                <tr>
                    <th><?= __('Interest Rate') ?></th>
                    <td><?= $this->Number->format($report->interest_rate) ?></td>
                </tr>
                <tr>
                    <th><?= __('Charges') ?></th>
                    <td><?= $this->Number->format($report->charges) ?></td>
                </tr>
                <tr>
                    <th><?= __('Collateral Rate') ?></th>
                    <td><?= $this->Number->format($report->collateral_rate) ?></td>
                </tr>
                <tr>
                    <th><?= __('Provisional Pv') ?></th>
                    <td><?= $this->Number->format($report->provisional_pv) ?></td>
                </tr>
                <tr>
                    <th><?= __('Report Date') ?></th>
                    <td><?= h($report->report_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Period Start Date') ?></th>
                    <td><?= h($report->period_start_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Period End Date') ?></th>
                    <td><?= h($report->period_end_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Reception Date') ?></th>
                    <td><?= h($report->reception_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Due Date') ?></th>
                    <td><?= h($report->due_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($report->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($report->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Description') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($report->description)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Comments') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($report->comments)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Agreed Pv Comments') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($report->agreed_pv_comments)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Total Disbursement Comments') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($report->total_disbursement_comments)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>
