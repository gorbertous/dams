<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Template $template
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $template->template_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $template->template_id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Template'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="template form content">
            <?= $this->Form->create($template) ?>
            <fieldset>
                <legend><?= __('Edit Template') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('template_type_id');
                    echo $this->Form->control('callback_id');
                    echo $this->Form->control('portfolio._ids', ['options' => $portfolio]);
                ?>
            </fieldset>
                        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-outline-secondary my-3']); ?>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
