<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MappingColumn $mappingColumn
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $mappingColumn->column_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $mappingColumn->column_id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Mapping Column'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="mappingColumn form content">
            <?= $this->Form->create($mappingColumn) ?>
            <fieldset>
                <legend><?= __('Edit Mapping Column') ?></legend>
                <?php
                    echo $this->Form->control('table_id');
                    echo $this->Form->control('table_field');
                    echo $this->Form->control('datatype');
                    echo $this->Form->control('exec_order');
                    echo $this->Form->control('excel_pk');
                    echo $this->Form->control('excel_fk');
                    echo $this->Form->control('excel_column');
                    echo $this->Form->control('is_null');
                    echo $this->Form->control('db_pk');
                    echo $this->Form->control('db_fk');
                    echo $this->Form->control('db_load_pk');
                    echo $this->Form->control('db_load_fk');
                    echo $this->Form->control('db_id');
                    echo $this->Form->control('fk_id');
                    echo $this->Form->control('sql_formula');
                    echo $this->Form->control('macro');
                    echo $this->Form->control('is_cf');
                    echo $this->Form->control('is_converted');
                    echo $this->Form->control('in_view');
                    echo $this->Form->control('transcode');
                    echo $this->Form->control('not_store');
                    echo $this->Form->control('dictionary_id');
                ?>
            </fieldset>
                        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-outline-secondary my-3']); ?>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
