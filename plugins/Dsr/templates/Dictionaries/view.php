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
        'title'   => 'Dictionary',
        'url'     => ['controller' => 'dictionaries', 'action' => 'view', $dictionary->id],
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
<h3><?= __('Dictionary') ?></h3>
<div class="row mb-5">

    <div class="col-6">
        <div class="table-responsive">            
            <table class="table table-striped">
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($dictionary->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($dictionary->name) ?></td>
                </tr>                
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= !empty($dictionary->created) ? h($dictionary->created->format('Y-m-d H:m:s')) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= !empty($dictionary->modified) ? h($dictionary->created->format('Y-m-d H:m:s')) : '' ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="col-3">
        <h4 class="heading"><?= __('Actions') ?></h4>
        <?= $this->Html->link(__('Edit Dictionary'), ['action' => 'edit', $dictionary->id], ['class' => 'btn btn-info btn-xs my-2']) ?><br>
        <?= $this->Html->link(__('New Dictionary'), ['action' => 'add'], ['class' => 'btn btn-primary btn-xs']) ?><br>
        <?= $this->Form->postLink(__('Delete Dictionary'), ['action' => 'delete', $dictionary->id], ['title' => __('Delete'), 'class' => 'btn btn-danger btn-xs my-2'], ['confirm' => __('Are you sure you want to delete # {0}?', $dictionary->id)]) ?><br>
        <?= $this->Html->link(__('List Dictionaries'), ['action' => 'index'], ['class' => 'btn btn-secondary btn-xs']) ?><br>
        <?= $this->Html->link(__('New Dic Value'), ['controller' => 'dico-values','action' => 'add'], ['class' => 'btn btn-primary btn-xs my-2']) ?>
    </div>
</div>

<?php if (!empty($dictionary->dico_values)) : ?>
    <h3><?= __('Dictionary Values') ?></h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= __('id') ?></th>
                    <th><?= __('dictionary_id') ?></th>
                    <th><?= __('code') ?></th>
                    <th><?= __('label') ?></th>
                    <th><?= __('created') ?></th>
                    <th><?= __('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dictionary->dico_values as $dicoValue): ?>
                    <tr>
                        <td><?= $dicoValue->id ?></td>
                        <td><?= $dicoValue->has('dictionary') ? $this->Html->link($dicoValue->dictionary->name, ['controller' => 'Dictionaries', 'action' => 'view', $dicoValue->dictionary->id]) : '' ?></td>
                        <td><?= h($dicoValue->code) ?></td>
                        <td><?= h($dicoValue->label) ?></td>
                        <td><?= h($dicoValue->created) ?></td>
                        <td><?= h($dicoValue->modified) ?></td>
                        <td class="actions">
                            <?= $this->Html->link('<i class="fas fa-eye"></i>', ['controller' => 'dico-values','action' => 'view', $dicoValue->id], ['escape' => false, 'title' => __('View'), 'class' => 'btn btn-info btn-xs']) ?>
                            <?= $this->Html->link('<i class="fas fa-pencil-alt"></i>', ['controller' => 'dico-values','action' => 'edit', $dicoValue->id], ['escape' => false, 'title' => __('Edit'), 'class' => 'btn btn-info btn-xs']) ?>
                            <?= $this->Form->postLink('<i class="fas fa-trash-alt"></i>' , ['controller' => 'dico-values','action' => 'delete', $dicoValue->id],['escape' => false, 'title' => __('Delete'), 'class' => 'btn btn-danger btn-xs my-2'], ['confirm' => __('Are you sure you want to delete # {0}?', $dicoValue->id)]) ?>
                            
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>