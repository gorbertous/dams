<?php
/**
 * @var \App\View\AppView $this
 * @var \Treasury\Model\Entity\Transaction[]|\Cake\Collection\CollectionInterface $transactions
 */
?>
<div class="transactions index content">
    <?= $this->Html->link(__('New Transaction'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Transactions') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('tr_number') ?></th>
                    <th><?= $this->Paginator->sort('tr_type') ?></th>
                    <th><?= $this->Paginator->sort('tr_state') ?></th>
                    <th><?= $this->Paginator->sort('source_group') ?></th>
                    <th><?= $this->Paginator->sort('reinv_group') ?></th>
                    <th><?= $this->Paginator->sort('original_id') ?></th>
                    <th><?= $this->Paginator->sort('parent_id') ?></th>
                    <th><?= $this->Paginator->sort('linked_trn') ?></th>
                    <th><?= $this->Paginator->sort('external_ref') ?></th>
                    <th><?= $this->Paginator->sort('amount') ?></th>
                    <th><?= $this->Paginator->sort('commencement_date') ?></th>
                    <th><?= $this->Paginator->sort('maturity_date') ?></th>
                    <th><?= $this->Paginator->sort('indicative_maturity_date') ?></th>
                    <th><?= $this->Paginator->sort('depo_term') ?></th>
                    <th><?= $this->Paginator->sort('interest_rate') ?></th>
                    <th><?= $this->Paginator->sort('total_interest') ?></th>
                    <th><?= $this->Paginator->sort('tax_amount') ?></th>
                    <th><?= $this->Paginator->sort('depo_type') ?></th>
                    <th><?= $this->Paginator->sort('depo_renew') ?></th>
                    <th><?= $this->Paginator->sort('rate_type') ?></th>
                    <th><?= $this->Paginator->sort('date_basis') ?></th>
                    <th><?= $this->Paginator->sort('mandate_ID') ?></th>
                    <th><?= $this->Paginator->sort('cmp_ID') ?></th>
                    <th><?= $this->Paginator->sort('scheme') ?></th>
                    <th><?= $this->Paginator->sort('accountA_IBAN') ?></th>
                    <th><?= $this->Paginator->sort('accountB_IBAN') ?></th>
                    <th><?= $this->Paginator->sort('instr_num') ?></th>
                    <th><?= $this->Paginator->sort('cpty_id') ?></th>
                    <th><?= $this->Paginator->sort('ps_account') ?></th>
                    <th><?= $this->Paginator->sort('booking_status') ?></th>
                    <th><?= $this->Paginator->sort('eom_booking') ?></th>
                    <th><?= $this->Paginator->sort('accrued_interst') ?></th>
                    <th><?= $this->Paginator->sort('accrued_tax') ?></th>
                    <th><?= $this->Paginator->sort('fixing_date') ?></th>
                    <th><?= $this->Paginator->sort('eom_interest') ?></th>
                    <th><?= $this->Paginator->sort('eom_tax') ?></th>
                    <th><?= $this->Paginator->sort('tax_ID') ?></th>
                    <th><?= $this->Paginator->sort('source_fund') ?></th>
                    <th><?= $this->Paginator->sort('reference_rate') ?></th>
                    <th><?= $this->Paginator->sort('spread_bp') ?></th>
                    <th><?= $this->Paginator->sort('benchmark') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td><?= $this->Number->format($transaction->tr_number) ?></td>
                    <td><?= h($transaction->tr_type) ?></td>
                    <td><?= h($transaction->tr_state) ?></td>
                    <td><?= $this->Number->format($transaction->source_group) ?></td>
                    <td><?= $this->Number->format($transaction->reinv_group) ?></td>
                    <td><?= $this->Number->format($transaction->original_id) ?></td>
                    <td><?= $transaction->has('parent_transaction') ? $this->Html->link($transaction->parent_transaction->tr_number, ['controller' => 'Transactions', 'action' => 'view', $transaction->parent_transaction->tr_number]) : '' ?></td>
                    <td><?= $this->Number->format($transaction->linked_trn) ?></td>
                    <td><?= h($transaction->external_ref) ?></td>
                    <td><?= $this->Number->format($transaction->amount) ?></td>
                    <td><?= h($transaction->commencement_date) ?></td>
                    <td><?= h($transaction->maturity_date) ?></td>
                    <td><?= h($transaction->indicative_maturity_date) ?></td>
                    <td><?= h($transaction->depo_term) ?></td>
                    <td><?= $this->Number->format($transaction->interest_rate) ?></td>
                    <td><?= $this->Number->format($transaction->total_interest) ?></td>
                    <td><?= $this->Number->format($transaction->tax_amount) ?></td>
                    <td><?= h($transaction->depo_type) ?></td>
                    <td><?= h($transaction->depo_renew) ?></td>
                    <td><?= h($transaction->rate_type) ?></td>
                    <td><?= h($transaction->date_basis) ?></td>
                    <td><?= $this->Number->format($transaction->mandate_ID) ?></td>
                    <td><?= $this->Number->format($transaction->cmp_ID) ?></td>
                    <td><?= h($transaction->scheme) ?></td>
                    <td><?= h($transaction->accountA_IBAN) ?></td>
                    <td><?= h($transaction->accountB_IBAN) ?></td>
                    <td><?= $this->Number->format($transaction->instr_num) ?></td>
                    <td><?= $this->Number->format($transaction->cpty_id) ?></td>
                    <td><?= h($transaction->ps_account) ?></td>
                    <td><?= h($transaction->booking_status) ?></td>
                    <td><?= h($transaction->eom_booking) ?></td>
                    <td><?= $this->Number->format($transaction->accrued_interst) ?></td>
                    <td><?= $this->Number->format($transaction->accrued_tax) ?></td>
                    <td><?= h($transaction->fixing_date) ?></td>
                    <td><?= $this->Number->format($transaction->eom_interest) ?></td>
                    <td><?= $this->Number->format($transaction->eom_tax) ?></td>
                    <td><?= $this->Number->format($transaction->tax_ID) ?></td>
                    <td><?= h($transaction->source_fund) ?></td>
                    <td><?= $this->Number->format($transaction->reference_rate) ?></td>
                    <td><?= $this->Number->format($transaction->spread_bp) ?></td>
                    <td><?= h($transaction->benchmark) ?></td>
                    <td><?= h($transaction->created) ?></td>
                    <td><?= h($transaction->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $transaction->tr_number]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $transaction->tr_number]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $transaction->tr_number], ['confirm' => __('Are you sure you want to delete # {0}?', $transaction->tr_number)]) ?>
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
