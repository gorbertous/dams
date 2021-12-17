<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ErrorsLogDetailed $errorsLogDetailed
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $errorsLogDetailed->error_detail_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $errorsLogDetailed->error_detail_id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Errors Log Detailed'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="errorsLogDetailed form content">
            <?= $this->Form->create($errorsLogDetailed) ?>
            <fieldset>
                <legend><?= __('Edit Errors Log Detailed') ?></legend>
                <?php
                    echo $this->Form->control('error_id', ['options' => $errorsLog]);
                    echo $this->Form->control('sheet');
                    echo $this->Form->control('lines');
                    echo $this->Form->control('file_formats');
                    echo $this->Form->control('formats');
                    echo $this->Form->control('dictionaries');
                    echo $this->Form->control('integrities');
                    echo $this->Form->control('business_rules');
                    echo $this->Form->control('warnings');
                ?>
            </fieldset>
                        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-outline-secondary my-3']); ?>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
