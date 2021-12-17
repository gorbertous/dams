<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Report $report
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Report'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="report form content">
            <?= $this->Form->create($report) ?>
            <fieldset>
                <legend><?= __('Add Report') ?></legend>
                <?php
                    echo $this->Form->control('report_name');
                    echo $this->Form->control('report_date', ['empty' => true]);
                    echo $this->Form->control('period_start_date', ['empty' => true]);
                    echo $this->Form->control('period_end_date', ['empty' => true]);
                    echo $this->Form->control('period_quarter');
                    echo $this->Form->control('period_year');
                    echo $this->Form->control('portfolio_id');
                    echo $this->Form->control('template_id');
                    echo $this->Form->control('status_id');
                    echo $this->Form->control('validation_status');
                    echo $this->Form->control('validator1');
                    echo $this->Form->control('validator2');
                    echo $this->Form->control('comments_validator2');
                    echo $this->Form->control('status_id_umbrella');
                    echo $this->Form->control('operation_iqid');
                    echo $this->Form->control('invoice_id', ['options' => $invoices, 'empty' => true]);
                    echo $this->Form->control('owner');
                    echo $this->Form->control('description');
                    echo $this->Form->control('version_number');
                    echo $this->Form->control('header');
                    echo $this->Form->control('sheets');
                    echo $this->Form->control('sheets_umbrella');
                    echo $this->Form->control('reception_date', ['empty' => true]);
                    echo $this->Form->control('due_date', ['empty' => true]);
                    echo $this->Form->control('ccy');
                    echo $this->Form->control('amount');
                    echo $this->Form->control('amount_EUR');
                    echo $this->Form->control('amount_ctr');
                    echo $this->Form->control('input_filename');
                    echo $this->Form->control('input_filename_umbrella');
                    echo $this->Form->control('output_filename');
                    echo $this->Form->control('visible');
                    echo $this->Form->control('bulk');
                    echo $this->Form->control('report_type');
                    echo $this->Form->control('clawback');
                    echo $this->Form->control('management_fees');
                    echo $this->Form->control('requests');
                    echo $this->Form->control('rejections');
                    echo $this->Form->control('rejection_rate');
                    echo $this->Form->control('interest_rate');
                    echo $this->Form->control('charges');
                    echo $this->Form->control('collateral_rate');
                    echo $this->Form->control('comments');
                    echo $this->Form->control('agreed_pv_comments');
                    echo $this->Form->control('total_disbursement_comments');
                    echo $this->Form->control('pkid');
                    echo $this->Form->control('provisional_pv');
                    echo $this->Form->control('m_files_link');
                ?>
            </fieldset>
                        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-outline-secondary my-3']); ?>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
