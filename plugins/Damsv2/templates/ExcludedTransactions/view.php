<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ExcludedTransaction $excludedTransaction
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Excluded Transaction'), ['action' => 'edit', $excludedTransaction->excluded_id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Excluded Transaction'), ['action' => 'delete', $excludedTransaction->excluded_id], ['confirm' => __('Are you sure you want to delete # {0}?', $excludedTransaction->excluded_id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Excluded Transactions'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Excluded Transaction'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="excludedTransactions view content">
            <h3><?= h($excludedTransaction->excluded_id) ?></h3>
            <table class="table table-striped">
                <tr>
                    <th><?= __('Transaction') ?></th>
                    <td><?= $excludedTransaction->has('transaction') ? $this->Html->link($excludedTransaction->transaction->transaction_id, ['controller' => 'Transactions', 'action' => 'view', $excludedTransaction->transaction->transaction_id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Subtransaction') ?></th>
                    <td><?= $excludedTransaction->has('subtransaction') ? $this->Html->link($excludedTransaction->subtransaction->subtransaction_id, ['controller' => 'Subtransactions', 'action' => 'view', $excludedTransaction->subtransaction->subtransaction_id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Exclusion Type') ?></th>
                    <td><?= h($excludedTransaction->exclusion_type) ?></td>
                </tr>
                <tr>
                    <th><?= __('Coverage Implication') ?></th>
                    <td><?= h($excludedTransaction->coverage_implication) ?></td>
                </tr>
                <tr>
                    <th><?= __('Acceleration Flag') ?></th>
                    <td><?= h($excludedTransaction->acceleration_flag) ?></td>
                </tr>
                <tr>
                    <th><?= __('Comments') ?></th>
                    <td><?= h($excludedTransaction->comments) ?></td>
                </tr>
                <tr>
                    <th><?= __('Excluded Id') ?></th>
                    <td><?= $this->Number->format($excludedTransaction->excluded_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sme Id') ?></th>
                    <td><?= $this->Number->format($excludedTransaction->sme_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Excluded Transaction Amount') ?></th>
                    <td><?= $this->Number->format($excludedTransaction->excluded_transaction_amount) ?></td>
                </tr>
                <tr>
                    <th><?= __('Excluded Transaction Amount Eur') ?></th>
                    <td><?= $this->Number->format($excludedTransaction->excluded_transaction_amount_eur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Excluded Transaction Amount Curr') ?></th>
                    <td><?= $this->Number->format($excludedTransaction->excluded_transaction_amount_curr) ?></td>
                </tr>
                <tr>
                    <th><?= __('Portfolio Id') ?></th>
                    <td><?= $this->Number->format($excludedTransaction->portfolio_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Report Id') ?></th>
                    <td><?= $this->Number->format($excludedTransaction->report_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Exclusion Date') ?></th>
                    <td><?= h($excludedTransaction->exclusion_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($excludedTransaction->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($excludedTransaction->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
