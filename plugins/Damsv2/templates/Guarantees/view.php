<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Guarantee $guarantee
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Guarantee'), ['action' => 'edit', $guarantee->guarantee_id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Guarantee'), ['action' => 'delete', $guarantee->guarantee_id], ['confirm' => __('Are you sure you want to delete # {0}?', $guarantee->guarantee_id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Guarantees'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Guarantee'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="guarantees view content">
            <h3><?= h($guarantee->guarantee_id) ?></h3>
            <table class="table table-striped">
                <tr>
                    <th><?= __('Transaction') ?></th>
                    <td><?= $guarantee->has('transaction') ? $this->Html->link($guarantee->transaction->transaction_id, ['controller' => 'Transactions', 'action' => 'view', $guarantee->transaction->transaction_id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Transaction Reference') ?></th>
                    <td><?= h($guarantee->transaction_reference) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fiscal Number') ?></th>
                    <td><?= h($guarantee->fiscal_number) ?></td>
                </tr>
                <tr>
                    <th><?= __('Subintermediary') ?></th>
                    <td><?= h($guarantee->subintermediary) ?></td>
                </tr>
                <tr>
                    <th><?= __('Guarantee Comments') ?></th>
                    <td><?= h($guarantee->guarantee_comments) ?></td>
                </tr>
                <tr>
                    <th><?= __('Error Message') ?></th>
                    <td><?= h($guarantee->error_message) ?></td>
                </tr>
                <tr>
                    <th><?= __('Subintermediary Address') ?></th>
                    <td><?= h($guarantee->subintermediary_address) ?></td>
                </tr>
                <tr>
                    <th><?= __('Subintermediary Postcode') ?></th>
                    <td><?= h($guarantee->subintermediary_postcode) ?></td>
                </tr>
                <tr>
                    <th><?= __('Subintermediary Place') ?></th>
                    <td><?= h($guarantee->subintermediary_place) ?></td>
                </tr>
                <tr>
                    <th><?= __('Subintermediary Type') ?></th>
                    <td><?= h($guarantee->subintermediary_type) ?></td>
                </tr>
                <tr>
                    <th><?= __('Guarantee Id') ?></th>
                    <td><?= $this->Number->format($guarantee->guarantee_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Portfolio Id') ?></th>
                    <td><?= $this->Number->format($guarantee->portfolio_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sme Id') ?></th>
                    <td><?= $this->Number->format($guarantee->sme_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Report Id') ?></th>
                    <td><?= $this->Number->format($guarantee->report_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fi Guarantee Amount') ?></th>
                    <td><?= $this->Number->format($guarantee->fi_guarantee_amount) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fi Guarantee Amount Eur') ?></th>
                    <td><?= $this->Number->format($guarantee->fi_guarantee_amount_eur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fi Guarantee Amount Curr') ?></th>
                    <td><?= $this->Number->format($guarantee->fi_guarantee_amount_curr) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fi Guarantee Rate') ?></th>
                    <td><?= $this->Number->format($guarantee->fi_guarantee_rate) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fi Guarantee Signature Date') ?></th>
                    <td><?= h($guarantee->fi_guarantee_signature_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fi Guarantee Maturity Date') ?></th>
                    <td><?= h($guarantee->fi_guarantee_maturity_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($guarantee->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($guarantee->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
