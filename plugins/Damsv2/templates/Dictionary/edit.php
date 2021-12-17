<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Dictionary $dictionary
 */
$this->Breadcrumbs->add([
        [
            'title' => 'Home', 
            'url' => ['controller' => 'Home', 'action' => 'home'],
            'options' => ['class'=> 'breadcrumb-item']
        ],
        [
            'title' => 'List', 
            'url' => ['controller' => 'Dictionary', 'action' => 'index'],
            'options' => ['class'=> 'breadcrumb-item']
        ],
        [
            'title' => 'Edit - '.$dictionary->name, 
            'url' => ['controller' => 'Dictionary', 'action' => 'edit', $dictionary->dictionary_id],
            'options' => [
                'class' => 'breadcrumb-item active',
                'innerAttrs' => [
                    'class' => 'test-list-class',
                    'id' => 'the-dict-crumb'
                ]
            ]
        ]
    ]);
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $dictionary->dictionary_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $dictionary->dictionary_id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Dictionary'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="dictionary form content">
            <?= $this->Form->create($dictionary) ?>
            <fieldset>
                <legend><?= __('Edit Dictionary') ?></legend>
                <?php
                    echo $this->Form->control('name');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit'),['class'=>'btn btn-outline-secondary my-3']); ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
