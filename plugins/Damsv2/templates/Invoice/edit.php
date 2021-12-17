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
        'url'     => ['controller' => 'Invoice', 'action' => 'edit', $invoice->invoice_id],
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
            <?=
            $this->Form->postLink(
                    __('Delete'),
                    ['action' => 'delete', $invoice->invoice_id],
                    ['confirm' => __('Are you sure you want to delete # {0}?', $invoice->invoice_id), 'class' => 'side-nav-item']
            )
            ?>
<?= $this->Html->link(__('List Invoices'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="invoices form content">
                <?= $this->Form->create($invoice) ?>
            <fieldset>
                <legend><?= __('Edit Invoice') ?></legend>
                <?php
                echo $this->Form->control('portfolio_id', ['options' => $portfolio, 'empty' => true]);
                echo $this->Form->control('invoice_owner');
                echo $this->Form->control('invoice_date', ['empty' => true]);
                echo $this->Form->control('due_date', ['empty' => true]);
                echo $this->Form->control('expected_payment_date', ['empty' => true]);
                echo $this->Form->control('accounting_payment_date', ['empty' => true]);
                echo $this->Form->control('contract_currency');
                echo $this->Form->control('amount_curr');
                echo $this->Form->control('amount_eur');
                echo $this->Form->control('fx_rate');
                echo $this->Form->control('fx_rate_label');
                echo $this->Form->control('invoice_number');
                echo $this->Form->control('status_id', ['options' => $status, 'empty' => true]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-outline-secondary my-3']); ?>

<?= $this->Form->end() ?>
        </div>
    </div>
</div>
