<?php
/**
 * @var \App\View\AppView $this
 * @var \Dsr\Model\Entity\DicoValue $dicoValue
 * @var \Cake\Collection\CollectionInterface|string[] $dictionaries
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Dictionaries',
        'url'     => ['controller' => 'dictionaries', 'action' => 'index'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'New Dictionary Value',
        'url'     => ['controller' => 'dico-values', 'action' => 'edit'],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-port-crumb'
            ]
        ]
    ]
]);
?>
<div class="row">

    <div class="col-4">
        <?= $this->Form->create($dicoValue) ?>
       
            <legend><?= __('New Dico Value') ?></legend>
            <?php
            echo $this->Form->control('dictionary_id', ['options' => $dictionaries, 'class' => 'form-control mb-3']);
            echo $this->Form->control('code', ['class' => 'form-control mb-3']);
            echo $this->Form->control('label', ['class' => 'form-control mb-3']);
            ?>
        
        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-outline-secondary my-3']); ?> 
        <?= $this->Form->end() ?>
    </div>
</div>

