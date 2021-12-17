<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MappingTable $mappingTable
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Mapping Table'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="mappingTable form content">
            <?= $this->Form->create($mappingTable) ?>
            <fieldset>
                <legend><?= __('Add Mapping Table') ?></legend>
                <?php
                    echo $this->Form->control('template_id');
                    echo $this->Form->control('name');
                    echo $this->Form->control('table_name');
                    echo $this->Form->control('sheet_name');
                    echo $this->Form->control('loading_order');
                    echo $this->Form->control('is_cf');
                ?>
            </fieldset>
                        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-outline-secondary my-3']); ?>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
