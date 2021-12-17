<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Dstoolbox[]|\Cake\Collection\CollectionInterface $dstoolbox
 */

$this->Breadcrumbs->add([
    [
        'title' => 'Home',
        'url' => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title' => 'Toolbox',
        'url' => ['controller' => 'dstoolbox', 'action' => 'index'],
        'options' => [
            'class' => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id' => 'the-inv-crumb'
            ]
        ]
    ]
]);
?>

<?php if ($perm->hasWrite(array('controller' => 'Dstoolbox', 'action' => 'add'))) { 
    echo $this->Html->link(__('+Upload project'), ['action' => 'add'], ['class' => 'btn btn-primary float-right']);
} ?>
<h3><?= __('Toolbox') ?></h3>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('name') ?></th>
                <th>Description</th>
                <th>File</th>
                <th>Link</th>
                <th colspan="2">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dstoolbox as $dstoolbox) : ?>
                <tr>

                    <td><?= h($dstoolbox->name) ?></td>
                    <td><?= h($dstoolbox->description) ?></td>
                    <td>
                        <a class="nav-link" href=<?= $this->Url->build([
                                                        'controller' => 'ajax', 'action' => 'downloadFile', '_ext' => null,
                                                         $dstoolbox->filename,
                                                        'dstoolbox',$dstoolbox->dstoolbox_id
                                                    ]); ?>>
                            <div class="sb-nav-link-icon">
                                
                            </div> <?= h($dstoolbox->filename) ?>
                        </a>
                    </td>
                    <td><a class="nav-link" _target="_blank" href="<?= h($dstoolbox->BO_url) ?>"><?= h($dstoolbox->BO_url) ?></a></td>

                    <td style="width:20px">
                        <?php
                        if ($perm->hasWrite(array('controller' => 'Dstoolbox', 'action' => 'edit'))) {
                            echo $this->Html->link(
                                '<i class="fas fa-pencil-alt"></i>',
                                ['action' => 'edit', $dstoolbox->dstoolbox_id],
                                ['escape' => false, 'title' => __('Edit'), 'class' => 'btn btn-info btn-xs']
                            );
                        }
                        ?>
                    </td>
                    <td style="width:20px">
                        <?php
                        if ($perm->hasDelete(array('controller' => 'Dstoolbox', 'action' => 'delete'))) {
                            echo $this->Form->postLink('<i class="fas fa-trash-alt"></i>', ['action' => 'delete', $dstoolbox->dstoolbox_id], ['escape' => false, 'title' => __('Delete'), 'class' => 'btn btn-danger btn-xs'], ['confirm' => __('Are you sure you want to delete # {0}?', $dstoolbox->dstoolbox_id)]);
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="paginator">
    <ul class="pagination">
        <?= $this->Paginator->first('<< ' . __('first')) ?>
        <?= $this->Paginator->prev('< ' . __('previous')) ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next(__('next') . ' >') ?>
        <?= $this->Paginator->last(__('last') . ' >>') ?>
    </ul>
    <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
</div>