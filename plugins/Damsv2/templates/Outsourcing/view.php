<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OutsourcingLog $outsourcingLog
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Outsourcing Log'), ['action' => 'edit', $outsourcingLog->log_id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Outsourcing Log'), ['action' => 'delete', $outsourcingLog->log_id], ['confirm' => __('Are you sure you want to delete # {0}?', $outsourcingLog->log_id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Outsourcing Log'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Outsourcing Log'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="outsourcingLog view content">
            <h3><?= h($outsourcingLog->log_id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Period Quarter') ?></th>
                    <td><?= h($outsourcingLog->period_quarter) ?></td>
                </tr>
                <tr>
                    <th><?= __('Deal Business Key') ?></th>
                    <td><?= h($outsourcingLog->deal_business_key) ?></td>
                </tr>
                <tr>
                    <th><?= __('Deal Name') ?></th>
                    <td><?= h($outsourcingLog->deal_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Portfolio Name') ?></th>
                    <td><?= h($outsourcingLog->portfolio_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Mandate') ?></th>
                    <td><?= h($outsourcingLog->mandate) ?></td>
                </tr>
                <tr>
                    <th><?= __('Prioritised') ?></th>
                    <td><?= h($outsourcingLog->prioritised) ?></td>
                </tr>
                <tr>
                    <th><?= __('Inclusion Status') ?></th>
                    <td><?= h($outsourcingLog->inclusion_status) ?></td>
                </tr>
                <tr>
                    <th><?= __('Dh Resp') ?></th>
                    <td><?= h($outsourcingLog->dh_resp) ?></td>
                </tr>
                <tr>
                    <th><?= __('Inclusion Resp') ?></th>
                    <td><?= h($outsourcingLog->inclusion_resp) ?></td>
                </tr>
                <tr>
                    <th><?= __('C Sheet') ?></th>
                    <td><?= h($outsourcingLog->c_sheet) ?></td>
                </tr>
                <tr>
                    <th><?= __('Follow Up') ?></th>
                    <td><?= h($outsourcingLog->follow_up) ?></td>
                </tr>
                <tr>
                    <th><?= __('Comments') ?></th>
                    <td><?= h($outsourcingLog->comments) ?></td>
                </tr>
                <tr>
                    <th><?= __('Log Id') ?></th>
                    <td><?= $this->Number->format($outsourcingLog->log_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Period Year') ?></th>
                    <td><?= $this->Number->format($outsourcingLog->period_year) ?></td>
                </tr>
                <tr>
                    <th><?= __('Portfolio Id') ?></th>
                    <td><?= $this->Number->format($outsourcingLog->portfolio_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Mandate Id') ?></th>
                    <td><?= $this->Number->format($outsourcingLog->mandate_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Inclusion Deadline') ?></th>
                    <td><?= h($outsourcingLog->inclusion_deadline) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email Date') ?></th>
                    <td><?= h($outsourcingLog->email_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Received Date') ?></th>
                    <td><?= h($outsourcingLog->received_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('First Email Date') ?></th>
                    <td><?= h($outsourcingLog->first_email_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Inclusion Date') ?></th>
                    <td><?= h($outsourcingLog->inclusion_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($outsourcingLog->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($outsourcingLog->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
