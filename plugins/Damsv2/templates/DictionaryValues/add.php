<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DictionaryValue $dictionaryValue
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Dictionary Values'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="dictionaryValues form content">
            <?= $this->Form->create($dictionaryValue) ?>
            <fieldset>
                <legend><?= __('Add Dictionary Value') ?></legend>
                <?php
                    echo $this->Form->control('dictionary_id', ['options' => $dictionary, 'empty' => true]);
                    echo $this->Form->control('code');
                    echo $this->Form->control('translation');
                    echo $this->Form->control('label');
                ?>
            </fieldset>
                        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-outline-secondary my-3']); ?>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
