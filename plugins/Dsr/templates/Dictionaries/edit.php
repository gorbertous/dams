<?php
/**
 * @var \App\View\AppView $this
 * @var \Dsr\Model\Entity\Dictionary $dictionary
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
        'title'   => 'Edit Dictionary',
        'url'     => ['controller' => 'dictionaries', 'action' => 'edit', $dictionary->id],
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
        <?= $this->Form->create($dictionary) ?>
        <legend><?= __('Edit Dictionary') ?></legend>
        <?php echo $this->Form->control('name', ['class' => 'form-control mb-3']);?>

        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-outline-secondary my-3']); ?> 
        <?= $this->Form->end() ?>
    </div>

</div>
