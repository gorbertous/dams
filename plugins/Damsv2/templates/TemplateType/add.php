<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TemplateType $templateType
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Template Type'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="templateType form content">
            <?= $this->Form->create($templateType) ?>
            <fieldset>
                <legend><?= __('Add Template Type') ?></legend>
                <?php
                    echo $this->Form->control('name');
                ?>
            </fieldset>
                        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-outline-secondary my-3']); ?>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
