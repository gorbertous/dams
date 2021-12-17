<?php
/**
 * @var \App\View\AppView $this
 * @var \Dsr\Model\Entity\Portfolio $portfolio
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Reports',
        'url'     => ['controller' => 'Portfolios', 'action' => 'index'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => $portfolio->name,
        'url'     => ['controller' => 'Portfolios', 'action' => 'view', $portfolio],
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
<div class="row mb-5">
    <div class="col-6">
        <div class="table-responsive">
            <h3><?= h($portfolio->name) ?></h3>
            <table class="table table-striped">
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($portfolio->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Product') ?></th>
                    <td><?= $portfolio->has('product') ? $this->Html->link($portfolio->product->name, ['controller' => 'Products', 'action' => 'view', $portfolio->product->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($portfolio->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fi Name') ?></th>
                    <td><?= h($portfolio->fi_name) ?></td>
                </tr>

                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= !empty($portfolio->created) ? h($portfolio->created->format('Y-m-d H:m:s')) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= !empty($portfolio->modified) ? h($portfolio->created->format('Y-m-d H:m:s')) : '' ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<?php if (!empty($loans)) : ?>
    <h3><?= __('Related Loans') ?></h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= __('Id') ?></th>
                    <th><?= __('Loan reference') ?></th>
                    <th><?= __('File reference') ?></th>
                    <th><?= __('Gender') ?></th>
                    <th><?= __('Employment') ?></th>
                    <th><?= __('Education') ?></th>
                    <th><?= __('Age') ?></th>
                    <th><?= __('Specific group') ?></th>
                    <th><?= __('Country') ?></th>
                    <th><?= __('Region') ?></th>
                    <th><?= __('Total employees') ?></th>
                    <th><?= __('Total male') ?></th>
                    <th><?= __('Total female') ?></th>
                    <th><?= __('Total < 25') ?></th>
                    <th><?= __('Total 25-54') ?></th>
                    <th><?= __('Total > 55') ?></th>
                    <th><?= __('Total minority') ?></th>
                    <th><?= __('Total disabled') ?></th>
                    <th><?= __('Expost total employees') ?></th>
                    <th><?= __('Created') ?></th>
                    <th><?= __('Modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($loans as $loan): ?>
                    <tr>
                        <td><?= $loan->id ?></td>
                        <td><?= h($loan->loan_reference) ?></td>
                        <td><?= h($loan->file_reference) ?></td>
                        <td><?= $loan->gender ?></td>
                        <td><?= $loan->employment ?></td>
                        <td><?= $loan->education ?></td>
                        <td><?= $loan->age ?></td>
                        <td><?= $loan->specific_group ?></td>
                        <td><?= h($loan->country) ?></td>
                        <td><?= h($loan->region) ?></td>
                        <td><?= $loan->total_employees ?></td>
                        <td><?= $loan->total_male ?></td>
                        <td><?= $loan->total_female ?></td>
                        <td><?= $loan->total_less_25 ?></td>
                        <td><?= $loan->total_25_54 ?></td>
                        <td><?= $loan->total_more_55 ?></td>
                        <td><?= $loan->total_minority ?></td>
                        <td><?= $loan->total_disabled ?></td>
                        <td><?= $loan->expost_total_employees ?></td>
                        <td><?= !empty($loan->created) ? h($loan->created->format('Y-m-d H:m:s')) : '' ?></td>
                        <td><?= !empty($loan->modified) ? h($loan->created->format('Y-m-d H:m:s')) : '' ?></td>
                        <td class="actions">
                            <?= $this->Html->link('<i class="fas fa-eye"></i>', ['controller' => 'loans', 'action' => 'view', $loan->id], ['escape' => false, 'title' => __('View'), 'class' => 'btn btn-info btn-xs mb-2']) ?>
                            <?= $this->Html->link('<i class="fas fa-pencil-alt"></i>', ['controller' => 'loans', 'action' => 'edit', $loan->id], ['escape' => false, 'title' => __('Edit'), 'class' => 'btn btn-info btn-xs']) ?>

                            <?= $this->Form->postLink('<i class="fas fa-trash-alt"></i>', ['controller' => 'loans', 'action' => 'delete', $loan->id], ['escape' => false, 'title' => __('Delete'), 'class' => 'btn btn-danger btn-xs my-2'], ['confirm' => __('Are you sure you want to delete # {0}?', $loan->id)]) ?>
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
<?php endif; ?>
