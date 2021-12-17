<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Invoice[]|\Cake\Collection\CollectionInterface $invoices
 */

 $this->Breadcrumbs->add([
        [
            'title' => 'Home', 
            'url' => ['controller' => 'Home', 'action' => 'home'],
            'options' => ['class'=> 'breadcrumb-item']
        ],
        [
            'title' => 'Invoices', 
            'url' => ['controller' => 'Invoice', 'action' => 'index'],
            'options' => [
                'class' => 'breadcrumb-item active',
                'innerAttrs' => [
                    'class' => 'test-list-class',
                    'id' => 'the-inv-crumb'
                ]
            ]
        ]
    ]);
?>
<div class="invoices index content">
    <h3><?= __('Invoices') ?></h3>
    <?= $this->Form->create(null, ['class' => 'form-inline', 'id' => 'filters']) ?>
  

    <?= $this->Form->select('product_id', $products,
        [
            'empty' => '-- Any product --',
            'class' => 'form-control mr-2 my-2 filtersLive',
            'value' => $session->read('Form.data.invoices.product_id'),
            'id'    => 'productid'
        ]
    );
    ?>
    <?= $this->Form->select('mandate', $mandates,
        [
            'empty' => '-- Any mandate --',
            'class' => 'w-25 form-control mr-2 my-2 filtersLive',
            'value' => $session->read('Form.data.invoices.mandate'),
            'id'    => 'mandateid'
        ]
    );
    ?>
    <?= $this->Form->select('portfolio_id', $portfolios,
        [
            'empty' => '-- Any portfolio --',
            'class' => 'w-25 form-control mr-2 my-2 filtersLive',
            'value' => $session->read('Form.data.invoices.portfolio_id'),
            'id'    => 'portfolioid'
        ]
    );
    ?>
    <?= $this->Form->select('beneficiary_name', $beneficiary,
        [
            'empty' => '-- Any beneficiary --',
            'class' => 'w-25 form-control mr-2 my-2 filtersLive',
            'value' => $session->read('Form.data.invoices.beneficiary_name'),
            'id'    => 'beneficiaryid'
        ]
    );
    ?>
    
    <?= $this->Form->select('stage', $stages,
        [
            'empty' => '-- Any stage --',
            'class' => 'form-control mr-2 my-2 filtersLive',
            'value' => $session->read('Form.data.invoices.stage'),
            'id'    => 'stage_id'
        ]
    );
    ?>

    <?= $this->Form->select('status', $statuses,
        [
            'empty' => '-- Any status --',
            'class' => 'form-control mr-2 my-2 filtersLive',
            'value' =>  $session->read('Form.data.invoices.status'),
            'id'    => 'status_id'
        ]
    );
    ?>
   
    
    <?= $this->Form->select('rep_owner', $users,
        [
            'empty' => '-- Any report owner --',
            'class' => 'form-control mr-2 my-2 filtersLive',
            'value' => $session->read('Form.data.invoices.rep_owner'),
            'id'    => 'repowner_id'
        ]
    );
    ?>
    
    <?= $this->Form->select('port_owner', $users,
        [
            'empty' => '-- Any portfolio owner --',
            'class' => 'form-control mr-2 my-2 filtersLive',
            'value' => $session->read('Form.data.invoices.port_owner'),
            'id'    => 'portowner_id'
        ]
    );
    ?>
    <?= $this->Form->input('invoice_id', ['type' => 'number', 'label' => '', 'placeholder' => 'Any invoice id', 'class' => 'form-control mr-2 my-2 filters', 'style' => 'width:150px', 'value' => $session->read('Form.data.invoices.invoice_id'), 'id' => 'invoice_id']) ?>
    <?= $this->Html->link('Reset', ['action' => 'reset-filters'], ['class' => 'btn btn-secondary ml-2 my-2', 'id' => 'reset']) ?>
    
<?= $this->Form->end(); ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('invoice_id', '#') ?></th>
                    <th>Processing form</th>
                    <th>Final form</th>
                    <th><?= $this->Paginator->sort('Portfolio.deal_name') ?></th>
                    <th><?= $this->Paginator->sort('due_date') ?></th>
                    <th><?= $this->Paginator->sort('expected_payment_date') ?></th>
                    <th><?= $this->Paginator->sort('Portfolio.owner', '<i class="fa fa-user"></i> Portfolio',['escape' => false]) ?></th>
                    <th><?= $this->Paginator->sort('invoice_owner', '<i class="fa fa-user"></i> Invoice',['escape' => false]) ?></th>
                    <th><?= $this->Paginator->sort('amount_curr', 'Amount') ?></th>
                    <th><?= $this->Paginator->sort('contract_currency', 'CCY') ?></th>
                    <th><?= $this->Paginator->sort('stage') ?></th>
                    <th><?= $this->Paginator->sort('status_id') ?></th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoices as $invoice): ?>
                <tr>
                    <td><?= $invoice->invoice_id ?></td>
                    <td><?= $this->Html->link(__('View'), ['action' => 'processing_form', $invoice->invoice_id]) ?></td>
                    <td><?= $invoice->status_id == 16 ? $this->Html->link(__('View'), ['action' => 'final_form', $invoice->invoice_id]) : '' ?></td>
                    <td><?= $invoice->has('portfolio') ? $this->Html->link($invoice->portfolio->deal_name, ['controller' => 'Portfolio', 'action' => 'detail', $invoice->portfolio->portfolio_id]) : '' ?></td>
                    <td><?= !empty($invoice->due_date) ?  h($invoice->due_date->format('Y-m-d')) : '' ?></td>
                    <td><?= !empty($invoice->expected_payment_date) ?  h($invoice->expected_payment_date->format('Y-m-d')) : '' ?></td>
                    <td><?= !empty($invoice->portfolio->v_user) ? h($invoice->portfolio->v_user->full_name) : '' ?></td>
                    <td><?= !empty($invoice->v_user) ? h($invoice->v_user->full_name) : '' ?></td>
                    <td style="text-align: right"><?= $this->Number->precision($invoice->amount_curr,2) ?></td>
                    <td ><?= h($invoice->contract_currency) ?></td>
                    <td><?= $invoice->status->stage ?></td>
                    <td><?= $invoice->status->status ?></td>
                    <td class="actions">
						<?php
						if ($invoice->status_id == 10 || $invoice->status_id == 11)
						{
							//add to invoice
							if ($perm->hasRead(array('controller' => 'Invoice', 'action' => 'add')))
							{
								echo $invoice->invoice_action_link;
							}
						}
						elseif ($invoice->status_id == 15)
						{
							//add to invoice
							if ($perm->hasRead(array('controller' => 'Invoice', 'action' => 'accounting')))
							{
								echo $invoice->invoice_action_link;
							}
						}
						elseif ($perm->hasWrite(array('controller' => 'Invoice', 'action' => 'index')) )
						{
							echo $invoice->invoice_action_link;
						}
						?>
				
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

<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>