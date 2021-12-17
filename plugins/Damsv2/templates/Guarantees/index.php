<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Guarantee[]|\Cake\Collection\CollectionInterface $guarantees
 */
?>
<div class="guarantees index content">
    <?= $this->Html->link(__('New Guarantee'), ['action' => 'add'], ['class' => 'btn btn-primary float-right']) ?>
    <h3><?= __('Guarantees') ?></h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('guarantee_id') ?></th>
                    <th><?= $this->Paginator->sort('transaction_id') ?></th>
                    <th><?= $this->Paginator->sort('portfolio_id') ?></th>
                    <th><?= $this->Paginator->sort('sme_id') ?></th>
                    <th><?= $this->Paginator->sort('transaction_reference') ?></th>
                    <th><?= $this->Paginator->sort('fiscal_number') ?></th>
                    <th><?= $this->Paginator->sort('report_id') ?></th>
                    <th><?= $this->Paginator->sort('fi_guarantee_amount') ?></th>
                    <th><?= $this->Paginator->sort('fi_guarantee_amount_eur') ?></th>
                    <th><?= $this->Paginator->sort('fi_guarantee_amount_curr') ?></th>
                    <th><?= $this->Paginator->sort('fi_guarantee_rate') ?></th>
                    <th><?= $this->Paginator->sort('fi_guarantee_signature_date') ?></th>
                    <th><?= $this->Paginator->sort('fi_guarantee_maturity_date') ?></th>
                    <th><?= $this->Paginator->sort('subintermediary') ?></th>
                    <th><?= $this->Paginator->sort('guarantee_comments') ?></th>
                    <th><?= $this->Paginator->sort('error_message') ?></th>
                    <th><?= $this->Paginator->sort('subintermediary_address') ?></th>
                    <th><?= $this->Paginator->sort('subintermediary_postcode') ?></th>
                    <th><?= $this->Paginator->sort('subintermediary_place') ?></th>
                    <th><?= $this->Paginator->sort('subintermediary_type') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($guarantees as $guarantee): ?>
                <tr>
                    <td><?= $this->Number->format($guarantee->guarantee_id) ?></td>
                    <td><?= $guarantee->has('transaction') ? $this->Html->link($guarantee->transaction->transaction_id, ['controller' => 'Transactions', 'action' => 'view', $guarantee->transaction->transaction_id]) : '' ?></td>
                    <td><?= $this->Number->format($guarantee->portfolio_id) ?></td>
                    <td><?= $this->Number->format($guarantee->sme_id) ?></td>
                    <td><?= h($guarantee->transaction_reference) ?></td>
                    <td><?= h($guarantee->fiscal_number) ?></td>
                    <td><?= $this->Number->format($guarantee->report_id) ?></td>
                    <td><?= $this->Number->format($guarantee->fi_guarantee_amount) ?></td>
                    <td><?= $this->Number->format($guarantee->fi_guarantee_amount_eur) ?></td>
                    <td><?= $this->Number->format($guarantee->fi_guarantee_amount_curr) ?></td>
                    <td><?= $this->Number->format($guarantee->fi_guarantee_rate) ?></td>
                    <td><?= h($guarantee->fi_guarantee_signature_date) ?></td>
                    <td><?= h($guarantee->fi_guarantee_maturity_date) ?></td>
                    <td><?= h($guarantee->subintermediary) ?></td>
                    <td><?= h($guarantee->guarantee_comments) ?></td>
                    <td><?= h($guarantee->error_message) ?></td>
                    <td><?= h($guarantee->subintermediary_address) ?></td>
                    <td><?= h($guarantee->subintermediary_postcode) ?></td>
                    <td><?= h($guarantee->subintermediary_place) ?></td>
                    <td><?= h($guarantee->subintermediary_type) ?></td>
                    <td><?= h($guarantee->created) ?></td>
                    <td><?= h($guarantee->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $guarantee->guarantee_id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $guarantee->guarantee_id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $guarantee->guarantee_id], ['confirm' => __('Are you sure you want to delete # {0}?', $guarantee->guarantee_id)]) ?>
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
