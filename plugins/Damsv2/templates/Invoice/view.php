<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Invoice $invoice
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Invoices',
        'url'     => ['controller' => 'Invoice', 'action' => 'index'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => $invoice->portfolio->deal_name,
        'url'     => ['controller' => 'Invoice', 'action' => 'view', $invoice->invoice_id],
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
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Invoice'), ['action' => 'edit', $invoice->invoice_id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Invoice'), ['action' => 'delete', $invoice->invoice_id], ['confirm' => __('Are you sure you want to delete # {0}?', $invoice->invoice_id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Invoices'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Invoice'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="invoices view content">
            <h3><?= h($invoice->portfolio->deal_name) ?></h3>
            <table class="table table-striped">
                <tr>
                    <th><?= __('Portfolio') ?></th>
                    <td><?= $invoice->has('portfolio') ? $this->Html->link($invoice->portfolio->deal_name, ['controller' => 'Portfolio', 'action' => 'view', $invoice->portfolio->portfolio_id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Contract Currency') ?></th>
                    <td><?= h($invoice->contract_currency) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fx Rate Label') ?></th>
                    <td><?= h($invoice->fx_rate_label) ?></td>
                </tr>
                <tr>
                    <th><?= __('Invoice Number') ?></th>
                    <td><?= h($invoice->invoice_number) ?></td>
                </tr>
                <tr>
                    <th><?= __('Status') ?></th>
                    <td><?= $invoice->has('status') ? $this->Html->link($invoice->status->status, ['controller' => 'Status', 'action' => 'view', $invoice->status->status_id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Stage') ?></th>
                    <td><?= $invoice->has('status') ? $this->Html->link($invoice->status->stage, ['controller' => 'Status', 'action' => 'view', $invoice->status->status_id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Pkid') ?></th>
                    <td><?= h($invoice->pkid) ?></td>
                </tr>
                <tr>
                    <th><?= __('Invoice Id') ?></th>
                    <td><?= $this->Number->format($invoice->invoice_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Invoice Owner') ?></th>
                    <td><?= $this->Number->format($invoice->invoice_owner) ?></td>
                </tr>
                <tr>
                    <th><?= __('Amount Curr') ?></th>
                    <td><?= $this->Number->format($invoice->amount_curr) ?></td>
                </tr>
                <tr>
                    <th><?= __('Amount Eur') ?></th>
                    <td><?= $this->Number->format($invoice->amount_eur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fx Rate') ?></th>
                    <td><?= $this->Number->format($invoice->fx_rate) ?></td>
                </tr>
                <tr>
                    <th><?= __('Invoice Date') ?></th>
                    <td><?= h($invoice->invoice_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Due Date') ?></th>
                    <td><?= h($invoice->due_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Expected Payment Date') ?></th>
                    <td><?= h($invoice->expected_payment_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Accounting Payment Date') ?></th>
                    <td><?= h($invoice->accounting_payment_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($invoice->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($invoice->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
