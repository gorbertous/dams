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
            <?= $this->Html->link(__('List Outsourcing Log'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="outsourcingLog form content">
            <?= $this->Form->create($outsourcingLog) ?>
            <fieldset>
                <legend><?= __('Add Outsourcing Log') ?></legend>
                <?php
                    echo $this->Form->control('period_quarter');
                    echo $this->Form->control('period_year');
                    echo $this->Form->control('deal_business_key');
                    echo $this->Form->control('deal_name');
                    echo $this->Form->control('portfolio_id');
                    echo $this->Form->control('portfolio_name');
                    echo $this->Form->control('mandate_id');
                    echo $this->Form->control('mandate');
                    echo $this->Form->control('inclusion_deadline');
                    echo $this->Form->control('prioritised');
                    echo $this->Form->control('inclusion_status');
                    echo $this->Form->control('email_date', ['empty' => true]);
                    echo $this->Form->control('dh_resp');
                    echo $this->Form->control('inclusion_resp');
                    echo $this->Form->control('received_date', ['empty' => true]);
                    echo $this->Form->control('first_email_date', ['empty' => true]);
                    echo $this->Form->control('inclusion_date', ['empty' => true]);
                    echo $this->Form->control('c_sheet');
                    echo $this->Form->control('follow_up');
                    echo $this->Form->control('comments');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
