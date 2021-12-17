<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Mandate $mandate
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $mandate->mandate_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $mandate->mandate_id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Mandate'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="mandate form content">
            <?= $this->Form->create($mandate) ?>
            <fieldset>
                <legend><?= __('Edit Mandate') ?></legend>
                <?php
                    echo $this->Form->control('mandate_iqid');
                    echo $this->Form->control('mandate_name');
                ?>
            </fieldset>
                        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-outline-secondary my-3']); ?>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
