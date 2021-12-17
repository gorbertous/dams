<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Toolbox $toolbox
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Toolbox'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="toolbox form content">
            <?= $this->Form->create($toolbox) ?>
            <fieldset>
                <legend><?= __('Add Toolbox') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('description');
                    echo $this->Form->control('filename');
                    echo $this->Form->control('creation_date', ['empty' => true]);
                    echo $this->Form->control('modification_date', ['empty' => true]);
                ?>
            </fieldset>
                        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-outline-secondary my-3']); ?>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
