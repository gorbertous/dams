<?php
/**
 * @var \App\View\AppView $this
 * @var \Dsr\Model\Entity\DicoValue $dicoValue
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
        'title'   => 'Dictionary Value',
        'url'     => ['controller' => 'dico-values', 'action' => 'view', $dicoValue->id],
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
<h3><?= __('Dictionary Value') ?></h3>
<div class="row mb-5">
    <div class="col-6">
        <div class="table-responsive">       
            <table class="table table-striped">
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($dicoValue->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Dictionary') ?></th>
                    <td><?= $dicoValue->has('dictionary') ? $this->Html->link($dicoValue->dictionary->name, ['controller' => 'Dictionaries', 'action' => 'view', $dicoValue->dictionary->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Code') ?></th>
                    <td><?= h($dicoValue->code) ?></td>
                </tr>
                <tr>
                    <th><?= __('Label') ?></th>
                    <td><?= h($dicoValue->label) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= !empty($dicoValue->created) ? h($dicoValue->created->format('Y-m-d H:m:s')) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= !empty($dicoValue->modified) ? h($dicoValue->created->format('Y-m-d H:m:s')) : '' ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="col-3">
        <h4 class="heading"><?= __('Actions') ?></h4>
        <?= $this->Html->link(__('Edit Dico Value'), ['action' => 'edit', $dicoValue->id], ['class' => 'btn btn-info btn-xs my-2']) ?><br>
        <?= $this->Html->link(__('New Dico Value'), ['action' => 'add'], ['class' => 'btn btn-primary btn-xs']) ?><br>
        <?= $this->Form->postLink(__('Delete Dico Value'), ['action' => 'delete', $dicoValue->id], ['title' => __('Delete'), 'class' => 'btn btn-danger btn-xs my-2'], ['confirm' => __('Are you sure you want to delete # {0}?', $dicoValue->id)]) ?><br>
        <?= $this->Html->link(__('List Dictionaries'), ['controller' => 'dictionaries', 'action' => 'index'], ['class' => 'btn btn-secondary btn-xs']) ?>
    </div>
</div>
