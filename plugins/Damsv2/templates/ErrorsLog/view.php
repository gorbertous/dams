<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ErrorsLog $errorsLog
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Errors Log'), ['action' => 'edit', $errorsLog->error_id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Errors Log'), ['action' => 'delete', $errorsLog->error_id], ['confirm' => __('Are you sure you want to delete # {0}?', $errorsLog->error_id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Errors Log'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Errors Log'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="errorsLog view content">
            <h3><?= h($errorsLog->error_id) ?></h3>
            <table class="table table-striped">
                <tr>
                    <th><?= __('Portfolio Name') ?></th>
                    <td><?= h($errorsLog->portfolio_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Mandate') ?></th>
                    <td><?= h($errorsLog->mandate) ?></td>
                </tr>
                <tr>
                    <th><?= __('Beneficiary Name') ?></th>
                    <td><?= h($errorsLog->beneficiary_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Period') ?></th>
                    <td><?= h($errorsLog->period) ?></td>
                </tr>
                <tr>
                    <th><?= __('File Formats') ?></th>
                    <td><?= h($errorsLog->file_formats) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fi Responsivness') ?></th>
                    <td><?= h($errorsLog->fi_responsivness) ?></td>
                </tr>
                <tr>
                    <th><?= __('Error Id') ?></th>
                    <td><?= $this->Number->format($errorsLog->error_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Portfolio Id') ?></th>
                    <td><?= $this->Number->format($errorsLog->portfolio_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Report Id') ?></th>
                    <td><?= $this->Number->format($errorsLog->report_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Total Lines') ?></th>
                    <td><?= $this->Number->format($errorsLog->total_lines) ?></td>
                </tr>
                <tr>
                    <th><?= __('Iterations') ?></th>
                    <td><?= $this->Number->format($errorsLog->iterations) ?></td>
                </tr>
                <tr>
                    <th><?= __('Total Formats') ?></th>
                    <td><?= $this->Number->format($errorsLog->total_formats) ?></td>
                </tr>
                <tr>
                    <th><?= __('Total Dictionaries') ?></th>
                    <td><?= $this->Number->format($errorsLog->total_dictionaries) ?></td>
                </tr>
                <tr>
                    <th><?= __('Total Integrities') ?></th>
                    <td><?= $this->Number->format($errorsLog->total_integrities) ?></td>
                </tr>
                <tr>
                    <th><?= __('Total Business Rules') ?></th>
                    <td><?= $this->Number->format($errorsLog->total_business_rules) ?></td>
                </tr>
                <tr>
                    <th><?= __('Total Warnings') ?></th>
                    <td><?= $this->Number->format($errorsLog->total_warnings) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($errorsLog->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($errorsLog->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Comments') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($errorsLog->comments)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>
