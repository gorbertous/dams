<?php
/**
 * @var \App\View\AppView $this
 * @var \Treasury\Model\Entity\CounterpartyAccount $counterpartyAccount
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Counterparty Account'), ['action' => 'edit', $counterpartyAccount->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Counterparty Account'), ['action' => 'delete', $counterpartyAccount->id], ['confirm' => __('Are you sure you want to delete # {0}?', $counterpartyAccount->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Counterparty Accounts'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Counterparty Account'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="counterpartyAccounts view content">
            <h3><?= h($counterpartyAccount->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Correspondent Bank') ?></th>
                    <td><?= h($counterpartyAccount->correspondent_bank) ?></td>
                </tr>
                <tr>
                    <th><?= __('Correspondent BIC') ?></th>
                    <td><?= h($counterpartyAccount->correspondent_BIC) ?></td>
                </tr>
                <tr>
                    <th><?= __('Currency') ?></th>
                    <td><?= h($counterpartyAccount->currency) ?></td>
                </tr>
                <tr>
                    <th><?= __('Account IBAN') ?></th>
                    <td><?= h($counterpartyAccount->account_IBAN) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($counterpartyAccount->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cpty Id') ?></th>
                    <td><?= $this->Number->format($counterpartyAccount->cpty_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Target') ?></th>
                    <td><?= $counterpartyAccount->target ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
