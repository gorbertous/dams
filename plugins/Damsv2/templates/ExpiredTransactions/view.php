<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ExpiredTransaction $expiredTransaction
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Expired Transaction'), ['action' => 'edit', $expiredTransaction->expired_id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Expired Transaction'), ['action' => 'delete', $expiredTransaction->expired_id], ['confirm' => __('Are you sure you want to delete # {0}?', $expiredTransaction->expired_id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Expired Transactions'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Expired Transaction'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="expiredTransactions view content">
            <h3><?= h($expiredTransaction->expired_id) ?></h3>
            <table class="table table-striped">
                <tr>
                    <th><?= __('Transaction') ?></th>
                    <td><?= $expiredTransaction->has('transaction') ? $this->Html->link($expiredTransaction->transaction->transaction_id, ['controller' => 'Transactions', 'action' => 'view', $expiredTransaction->transaction->transaction_id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Subtransaction') ?></th>
                    <td><?= $expiredTransaction->has('subtransaction') ? $this->Html->link($expiredTransaction->subtransaction->subtransaction_id, ['controller' => 'Subtransactions', 'action' => 'view', $expiredTransaction->subtransaction->subtransaction_id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Expired Id') ?></th>
                    <td><?= $this->Number->format($expiredTransaction->expired_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sme Id') ?></th>
                    <td><?= $this->Number->format($expiredTransaction->sme_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Portfolio Id') ?></th>
                    <td><?= $this->Number->format($expiredTransaction->portfolio_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Report Id') ?></th>
                    <td><?= $this->Number->format($expiredTransaction->report_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Nbr Employees Expired') ?></th>
                    <td><?= $this->Number->format($expiredTransaction->nbr_employees_expired) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sale Price') ?></th>
                    <td><?= $this->Number->format($expiredTransaction->sale_price) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sale Price Eur') ?></th>
                    <td><?= $this->Number->format($expiredTransaction->sale_price_eur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sale Price Curr') ?></th>
                    <td><?= $this->Number->format($expiredTransaction->sale_price_curr) ?></td>
                </tr>
                <tr>
                    <th><?= __('Write Off') ?></th>
                    <td><?= $this->Number->format($expiredTransaction->write_off) ?></td>
                </tr>
                <tr>
                    <th><?= __('Write Off Eur') ?></th>
                    <td><?= $this->Number->format($expiredTransaction->write_off_eur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Write Off Curr') ?></th>
                    <td><?= $this->Number->format($expiredTransaction->write_off_curr) ?></td>
                </tr>
                <tr>
                    <th><?= __('Repayment Date') ?></th>
                    <td><?= h($expiredTransaction->repayment_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sale Date') ?></th>
                    <td><?= h($expiredTransaction->sale_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Write Off Date') ?></th>
                    <td><?= h($expiredTransaction->write_off_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($expiredTransaction->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($expiredTransaction->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
