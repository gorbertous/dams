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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $errorsLog->error_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $errorsLog->error_id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Errors Log'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="errorsLog form content">
            <?= $this->Form->create($errorsLog) ?>
            <fieldset>
                <legend><?= __('Edit Errors Log') ?></legend>
                <?php
                    echo $this->Form->control('portfolio_id');
                    echo $this->Form->control('portfolio_name');
                    echo $this->Form->control('mandate');
                    echo $this->Form->control('beneficiary_name');
                    echo $this->Form->control('period');
                    echo $this->Form->control('report_id');
                    echo $this->Form->control('total_lines');
                    echo $this->Form->control('iterations');
                    echo $this->Form->control('file_formats');
                    echo $this->Form->control('total_formats');
                    echo $this->Form->control('total_dictionaries');
                    echo $this->Form->control('total_integrities');
                    echo $this->Form->control('total_business_rules');
                    echo $this->Form->control('total_warnings');
                    echo $this->Form->control('fi_responsivness');
                    echo $this->Form->control('comments');
                ?>
            </fieldset>
                        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-outline-secondary my-3']); ?>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
