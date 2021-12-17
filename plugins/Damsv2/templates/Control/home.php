<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Report[]|\Cake\Collection\CollectionInterface $report
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Delete Inclusion report',
        'url'     => ['controller' => 'Control', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>

<h3>Delete Inclusion report</h3>
<hr>


<?= $this->Form->create(null, ['class' => 'form-inline', 'id' => 'filter']) ?>

<?=
$this->Form->select('Product.product_id', $products,
        [
            'empty' => '-- Product --',
            'class' => 'form-control mr-2 my-2 filter',
            //'value' => $this->request->getQuery('product_id'),
            'id'    => 'productid'
        ]
);
?>
<?= $this->Form->input('Portfolio.portfolio_empty', ['type' => 'hidden', 'default' => "-- Portfolio --"]); ?>
<?=
$this->Form->select('Portfolio.portfolio_id', $portfolios,
        [
            'empty' => '-- Portfolio --',
            'class' => 'w-25 form-control mr-2 my-2 filter',
            //'value' => $this->request->getQuery('portfolio_id'),
            'id'    => 'portfolioid'
        ]
);
?>
<?= $this->Form->submit('Search reports', ['class' => 'btn btn-primary', 'id' => 'submit_id']) ?>
<?= $this->Form->end() ?>


<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('report_id', '#') ?></th>
                <th><?= $this->Paginator->sort('report_name') ?></th>
                <th><?= $this->Paginator->sort('Portfolio.mandate', 'Mandate') ?></th>
                <th><?= $this->Paginator->sort('Template.template_type_id', 'Flow') ?></th>

                <th><?= $this->Paginator->sort('report_type') ?></th>
                <th><?= $this->Paginator->sort('Status.stage', 'Stage') ?></th>
                <th><?= $this->Paginator->sort('Status.status', 'Status') ?></th>

                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reports as $report): ?>
                <?php
                if ($report->template->template_type_id == 2) {
                    $flow = 'PD';
                } else if ($report->template->template_type_id == 3) {
                    $flow = 'LR';
                } else {
                    $flow = 'Inclusion';
                }
                ?>
                <tr>
                    <td><?= $report->report_id ?></td>
                    <td><?= $this->Html->link($report->report_name, ['controller' => 'Report', 'action' => 'inclusionHistory', $report->report_id]) ?></td>

                    <td><?= !empty($report->portfolio->mandate) ? h($report->portfolio->mandate) : '' ?></td>
                    <td><?= $flow ?></td>

                    <td><?= h($report->report_type) ?></td>

                    <td><?= h($report->status->stage) ?></td>
                    <td><?= h($report->status->status) ?></td>

                    <td>
                        <?= $this->Form->postLink('<i class="fas fa-trash-alt"></i>' , ['action' => 'delete-report', $report->report_id],['escape' => false, 'title' => __('Delete'), 'class' => 'btn btn-danger btn-xs'], ['confirm' => __('Are you sure you want to delete # {0}?', $report->report_id)]) ?>
                        
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
$(document).ready(function () {
    $("#productid").on('change',function() {
        var formData = $('#filter').serialize(); 
        $.ajax({
            url: '/damsv2/ajax/getPortfoliosByProduct',
            type: 'POST',
            //dataType: 'HTML',
            data: formData,
            headers: {'X-CSRF-TOKEN': '<?= $this->request->getAttribute('csrfToken') ?>'},
            success: function (data) {
               $('#portfolioid').html(data);
            }
        });
    });
});
</script>