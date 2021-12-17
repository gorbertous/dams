<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Report $report
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Inclusion notice follow up',
        'url'     => ['controller' => 'validation', 'action' => 'inclusion-notice-followup'],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-inv-crumb'
            ]
        ]
    ]
]);

$enabled = !empty($reports) && count($reports) > 0 ? '' : 'disabled';
?>
<button type="button" class="btn btn-primary float-right" id="extract_list" <?= $enabled ?>><i class="fas fa-file-export"></i> &nbsp; Export to XLS</button>

<h3>Inclusion notice follow up</h3>

<hr>
<?= $this->Form->create(null, ['class' => 'form-inline', 'id' => 'filters']) ?>

<?= $this->Form->hidden('visible', ['value' => 1]) ?>
<?= $this->Form->hidden('template_type_id', ['value' => 1]) ?>

<?= $this->Form->select('product_id', $products,
        [
            'empty' => '-- Any product --',
            'class' => 'form-control mr-2 my-2 filters',
            'value' => $session->read('Form.data.inclusionnf.product_id'),
            'id'    => 'productid'
        ]
);
?>
<?= $this->Form->select('portfolio_id', $portfolios,
        [
            'empty' => '-- Any portfolio --',
            'class' => 'w-25 form-control mr-2 my-2 filters',
            'value' => $session->read('Form.data.inclusionnf.portfolio_id'),
            'id'    => 'portfolioid'
        ]
);
?>
<?= $this->Form->select('period_quarter',
        ['Q1' => 'Q1', 'Q2' => 'Q2', 'Q3' => 'Q3', 'Q4' => 'Q4', 'S1' => 'S1', 'S2' => 'S2'],
        [
            'empty' => '-- Any period --',
            'class' => 'form-control mr-2 my-2 filters',
            'value' => $session->read('Form.data.inclusionnf.period_quarter'),
            'id'    => 'periodqid'
        ]
);
?>

<?= $this->Form->control('period_year', [
    'empty' => '-- Any year --',
    'label' => false,
    'class' => 'form-control mr-2 my-2 filters',
    'value' => $session->read('Form.data.inclusionnf.period_year'),
    'type'  => 'year',
    'max'   => date('Y', time()) + 1,
    'min'   => date('Y', time()) - 3,
    'id'    => 'year_id',
]);
?>
<?= $this->Form->select('inclusion_notice_received',
        ['TRUE' => 'TRUE', 'FALSE' => 'FALSE'],
        [
            'empty' => ['' => '-- Inclusion notice received --'],
            'label' => '',
            'class' => 'form-control mr-2 my-2 filters',
            'value' => $session->read('Form.data.inclusionnf.inclusion_notice_received'),
            'id'    => 'ReportInclusionNoticeReceived',
]);
?>
<?= $this->Form->select('owner', $users,
        [
            'empty' => '-- Any portfolio owner --',
            'class' => 'form-control mr-2 my-2 filters',
            'value' => $session->read('Form.data.inclusionnf.port_owner'),
            'id'    => 'portowner_id'
        ]
);
?>
<?= $this->Form->input('report_id', ['type' => 'number', 'label' => '', 'placeholder' => 'Any report id', 'class' => 'form-control mr-2 my-2 filters', 'style' => 'width:150px', 'value' => $session->read('Form.data.inclusionnf.report_id'), 'id' => 'report_id']) ?>


<?= $this->Html->link('Reset', ['controller' => 'invoice', 'action' => 'reset-filters'], ['class' => 'btn btn-secondary ml-2 my-2', 'id' => 'reset']) ?>
<?= $this->Form->end(); ?>


<?= $this->Form->create(null, ['class' => 'form-inline', 'id' => 'list']); ?>
<table id="Dashboard" class="table">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('report_id', '#') ?></th>
            <th><?= $this->Paginator->sort('report_name') ?></th>
            <th><i class="icon-user"></i><?= $this->Paginator->sort('Portfolio.owner', 'Portfolio') ?></th>
            <th><?= $this->Paginator->sort('inclusion_notice_received', "Inclusion notice received") ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($reports as $report): ?>
        <?php
        //force the report owner value as  portfolio owner, if it was Admin
        $report_owner = $report->owner;
        if ((empty($report_owner) || $report_owner < 2) && !empty($report->Portfolio->owner)){
            $report_owner = $report->Portfolio->owner;
        }
        ?>
            <tr>
                <td><?= $report->report_id; ?></td>
                <td><?= $report->report_name; ?></td>
                <td><?= !empty($users[$report->portfolio->owner]) ? $users[$report->portfolio->owner] : 'N/A' ?></td>
                <td>
                    <?= $this->Form->checkbox('Report.inclusion_notice_received_' . $report->report_id, array(
                        'value'         => 'TRUE',
                        'checked'       => ($report->inclusion_notice_received == 'TRUE'),
                        'data-reportid' => $report->report_id,
                    ));
                    ?>
                </td>
            </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?= $this->Form->end(); ?>

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

<style type="text/css">
   
    #ReportReportId
    {
        height: 20px;
        width: 206px;
    }
  
</style>
<div style="display: none;">
    <?php
    echo $this->Form->create(null, array('id' => 'export', 'url' => '/damsv2/validation/inclusion-notice-followup-csv'));
    echo $this->Form->input('Portfolio.portfolio_id', array(
        'type'  => 'text',
        'label' => false,
        'div'   => false,
        'id'    => 'PortfolioPortfolioId',
    ));
    echo $this->Form->input('Report.period_quarter', array(
        'type'  => 'text',
        'label' => false,
        'div'   => false,
        'id'    => 'ReportPeriodQuarter',
    ));
    echo $this->Form->input('Report.period_year', array(
        'type'  => 'text',
        'label' => false,
        'div'   => false,
        'id'    => 'ReportPeriodYearYear',
    ));
    echo $this->Form->input('Portfolio.owner', array(
        'type'  => 'text',
        'label' => false,
        'div'   => false,
        'id'    => 'PortfolioOwner',
    ));
    echo $this->Form->input('Report.inclusion_notice_received', array(
        'type'  => 'text',
        'label' => false,
        'div'   => false,
        'id'    => 'ReportInclusionNoticeReceived',
    ));
    echo $this->Form->end();
    
    echo $this->Form->create(null, array('id' => 'ajax_save', 'url' => '/damsv2/validation/inclusion-notice-followup-save'));
    echo $this->Form->input('Report.report_id', array(
        'type'  => 'text',
        'label' => false,
        'div'   => false,
        'id'    => 'ReportReportId',
    ));

    echo $this->Form->input('Report.inclusion_notice_received', array(
        'type'  => 'text',
        'label' => false,
        'div'   => false,
        'id'    => 'ReportInclusionNoticeReceived',
    ));
    echo $this->Form->end();
    ?>
</div>
<script>
    $(document).ready(function () {
        $("#filters input, #filters select").change(function (event) {
            $("#filters").submit();
        });

        $('#extract_list').click(function (e)
        {
            e.preventDefault();
            $('#export #PortfolioPortfolioId').val($('#filters #portfolioid').val());
            $('#export #ReportPeriodQuarter').val($('#filters #periodqid').val());
            $('#export #ReportPeriodYearYear').val($('#filters #year_id').val());
            $('#export #PortfolioOwner').val($('#filters #portowner_id').val());
            $('#export #ReportInclusionNoticeReceived').val($('#filters #ReportInclusionNoticeReceived').val());
            var POST = $('#export').serialize();
            $.ajax({
                url: '/damsv2/validation/inclusion-notice-followup-csv',
                type: "POST",
                data: POST,
                headers: {'X-CSRF-TOKEN': '<?= $this->request->getAttribute('csrfToken') ?>'},
            }).done(function (data) {
                window.open("/damsv2/ajax/download-file/" + data);
            });
        });

        $('input[type="checkbox"]').mouseup(function (e)
        {
            var report_id = $(e.currentTarget).data('reportid');
            var val = $(e.currentTarget).is(':checked');
            if (!val)//somehow it is negated
            {
                val = 'TRUE';
            } else
            {
                val = 'FALSE';
            }

            $('#ajax_save #ReportReportId').val(report_id);
            $('#ajax_save #ReportInclusionNoticeReceived').val(val);
            var POST = $('#ajax_save').serialize();
            $.ajax({
                url: '/damsv2/validation/inclusion-notice-followup-save',
                type: "POST",
                data: POST,
            }).done(function (data) {

            });
        });

    });
</script>