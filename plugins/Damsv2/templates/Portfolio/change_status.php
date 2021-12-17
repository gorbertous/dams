<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Portfolio[]|\Cake\Collection\CollectionInterface $portfolio
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
     [
        'title'   => 'Portfolios',
        'url'     => ['controller' => 'Portfolio', 'action' => 'index'],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-port-crumb'
            ]
        ]
    ],
    [
        'title'   => 'Change Status',
        'url'     => ['controller' => 'Portfolio', 'action' => 'change-status'],
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

<h3><?= __('Portfolio status') ?></h3>

<?= $this->Form->create(null, ['type' => 'get', 'class' => 'form-inline', 'id' => 'filters']) ?>

<?= $this->Form->select('product_id', $products,
        [
            'empty' => '-- Any product --',
            'class' => 'form-control mr-2 my-2',
            'value' => $this->request->getQuery('product_id'),
            'id'    => 'productid'
        ]
    );
?>

<?= $this->Form->select('portfolio_id', $portfolios,
    [
        'empty' => '-- Any portfolio --',
        'class' => 'form-control mr-2 my-2',
        'value' => $this->request->getQuery('portfolio_id'),
        'id'    => 'portfolioid'
    ]
);
?>

<?= $this->Form->reset('Reset', ['class' => 'btn btn-secondary py-2 my-2', 'id' => 'reset']) ?>
<?= $this->Form->end() ?>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('portfolio_id', '#') ?></th>
                <th><?= $this->Paginator->sort('portfolio_name', 'Portfolio') ?></th>
                <th><?= $this->Paginator->sort('status_portfolio', 'Status') ?></th>
                <th>Action</th>

            </tr>
        </thead>
        <tbody>
            <?php foreach ($portfolio as $portfolio): ?>
                <tr>
                    <td><?= $portfolio->portfolio_id ?></td>
                    <td><?= h($portfolio->portfolio_name) ?></td>
                    <td>
                        <span class="badge <?php
                        switch ($portfolio->status_portfolio) {
                            case 'OPEN':
                                echo 'badge-success';
                                break;
                            case 'EARLY TERMINATED':
                                echo 'badge-warning';
                                break;

                            default:
                                echo 'badge-light';
                                break;
                        }
                        ?>">
                        <?= $portfolio->status_portfolio ?></span>
                    </td>
                    <td>
                        <?php if ($portfolio->status_portfolio == 'CLOSED'): ?>
                            <?= $this->Form->input('status', ['value' => $portfolio->status_portfolio, 'label' => '', 'disabled' => true, 'style' => 'padding:0;']);?>
                        <?php else: ?>
                            <?= $this->Form->create($portfolio, ['type' => 'post', 'id' => 'statusform'.$portfolio->portfolio_id]) ?>
                            <?= $this->Form->input('Portfolio.portfolio_id', ['type' => 'hidden', 'value' => $portfolio->portfolio_id]) ?>
                            <?= $this->Form->input('Portfolio.status_portfolio', ['type' => 'hidden', 'value' => $portfolio->status_portfolio]) ?>
                          
                            <?= $this->Form->select('Portfolio.status', [
                                    'OPEN'             => 'Open',
                                    'EARLY TERMINATED' => 'Early terminated',
                                ],
                                [
                                    'empty' => '-- Change status --',
                                    'class' => 'form-control mr-2 my-2',
                                    'id'    => 'statusid'
                                    //'onchange' => '$("#loading").modal();'
                                ]
                            );
                            ?>
                            <?= $this->Form->end() ?>
                        <?php endif ?>
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

<script>
  //reset the form function
function resetForm($form) {
    $form.find('input:text, select, textarea').val('');
    $form.find('input:radio, input:checkbox')
            .removeAttr('checked').removeAttr('selected');
}

$(document).ready(function () {
     $("#reset").on("click", function (e) {
        e.preventDefault();
        resetForm($('#filters'));
        window.location.replace("/damsv2/portfolio/change-status");
    });
});
</script>


