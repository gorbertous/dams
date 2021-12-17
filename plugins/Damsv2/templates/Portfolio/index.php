<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Portfolio[]|\Cake\Collection\CollectionInterface $portfolio
 */
 if (!$this->request->is('ajax')) {
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
    ]
]);
?>
<div class="portfolio index content">
    <h3><?= __('Portfolios') ?></h3>

    <?= $this->Form->create(null, ['type' => 'get', 'class' => 'form-inline formAjax', 'id' => 'filters']) ?>

    <?= $this->Form->control('portid', ['type' => 'number', 'label' => '', 'placeholder' => 'Portfolio ID', 'class' => 'form-control mr-2 my-2 filtersLive', 'style' => 'width:120px', 'value' => $this->request->getQuery('portid'), 'id' => 'port_id']) ?>
    <?= $this->Form->control('dealid', ['label' => '', 'placeholder' => 'Contract name', 'class' => 'form-control mr-2 my-2 filtersLive', 'value' => $this->request->getQuery('dealid'), 'id' => 'deal_id']) ?>

    <?=
    $this->Form->select('manid', $mandate_type,
            [
                'empty' => '--Mandate--',
                'class' => 'form-control mr-2 my-2 filtersLive',
                'value' => $this->request->getQuery('manid'),
                'id'    => 'man_id',
                'style' => 'width:200px',
            ]
    );
    ?>    
    <?=
    $this->Form->select('guarid', $guarantee_type,
            [
                'empty' => '--Financial Product--',
                'class' => 'form-control mr-2 my-2 filtersLive',
                'value' => $this->request->getQuery('guarid'),
                'id'    => 'guar_id'
            ]
    );
    ?>

    <?=
    $this->Form->select('contid', $gsdeal_status,
            [
                'empty' => '--Contract Status--',
                'class' => 'form-control mr-2 my-2 filtersLive',
                'value' => $this->request->getQuery('contid'),
                'id'    => 'con_id'
            ]
    );
    ?>
    <?=
    $this->Form->select('stid', $status_list,
            [
                'empty' => '--Portfolio Closed--',
                'class' => 'form-control mr-2 my-2 filtersLive',
                'value' => $this->request->getQuery('stid'),
                'id'    => 'st_id'
            ]
    );
    ?>

<?= $this->Form->reset('Reset', ['class' => 'btn btn-secondary my-2', 'id' => 'reset_filter']) ?>
<?= $this->Form->end();
 }// end if ajax
 ?>
<div id="filters_data">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('portfolio_id', '#') ?></th>
                    <th><?= $this->Paginator->sort('deal_name', 'Contract Name') ?></th>
                    <th><?= $this->Paginator->sort('mandate') ?></th>
                    <th><?= $this->Paginator->sort('guarantee_type', 'Financial product') ?></th>
                    <th><?= $this->Paginator->sort('inclusion_start_date', 'Inclusion Start') ?></th>
                    <th><?= $this->Paginator->sort('inclusion_end_date', 'Inclusion End') ?></th>
                    <th><?= $this->Paginator->sort('maxpv', 'MPV') ?></th>
                    <th><?= $this->Paginator->sort('agreed_pv', 'Agreed PV') ?></th>
                    <th><?= $this->Paginator->sort('actual_pev', 'Actual PV') ?></th>
                    <th><?= $this->Paginator->sort('status_portfolio', 'Closed') ?></th>


                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($portfolio as $portfolio): ?>
                    <tr>
                        <td><?= $portfolio->portfolio_id ?></td>
                        <td><?= h($portfolio->deal_name) ?></td>
                        <td><?= h($portfolio->mandate) ?></td>
                        <td><?= h($portfolio->guarantee_type) ?></td>
                        <td><?= !empty($portfolio->inclusion_start_date) ? h($portfolio->inclusion_start_date->format('Y-m-d')): '' ?></td>
                        <td><?= !empty($portfolio->inclusion_end_date) ? h($portfolio->inclusion_end_date->format('Y-m-d')): '' ?></td>
                        <td><?= $this->Number->format($portfolio->maxpv) ?></td>
                        <td><?= $this->Number->format($portfolio->agreed_pv) ?></td>
                        <td><?= $this->Number->format($portfolio->actual_pv) ?></td>
                        <td><?= h($portfolio->status_portfolio) != 'OPEN' ? 'Yes' : 'No' ?></td>


                        <td class="actions">
                            <?=
                            $this->Html->link(
                                    '<i class="fas fa-eye"></i>' . __('View'),
                                    ['action' => 'detail', $portfolio->portfolio_id],
                                    ['escape' => false, 'title' => __('View'), 'class' => 'btn btn-info btn-xs']
                            )
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
</div>
</div>

<script>
//reset the form function
function resetForm($form) {
    $form.find('input:text, select, textarea').val('');
    $form.find('input:radio, input:checkbox')
            .removeAttr('checked').removeAttr('selected');
}

$(document).ready(function () {
     $("#reset_filter").on("click", function (e) {
        e.preventDefault();
        resetForm($('#filters'));
        window.location.replace("/damsv2/portfolio");
    });
});
</script>

<style>
   .fas {
    display: inline;
}  
</style>

