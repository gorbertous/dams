<?php

/**
 * @var \App\View\AppView $this
 * @var \Damsv2\Model\Entity\Outsourcing[]|\Cake\Collection\CollectionInterface $outsourcing
 */

$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Outsourcing Log',
        'url'     => ['controller' => 'Outsourcing', 'action' => 'index'],
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

<h3><?= __('Outsourcing Log') ?></h3>

<?= $this->Form->create(null, ['class' => 'form-inline']) ?>

<div class="row col-10">
    <?= $this->Form->hidden('storeinsession', ['value' => true]) ?>
    <?= $this->Form->select('period_quarter', $quarters, [
        'label'        => false,
        'empty'     => '-- Any Quarter --',
        'class'     => 'form-control mr-2 my-2',
        'value' => $session->read('Form.data.outsourcing.period_quarter')
    ]); ?>
    <?= $this->Form->select('inclusion_deadline', $deadlines, [
        'label'        => false,
        'empty'     => '-- Any Inclusion Deadline --',
        'class'     => 'form-control mr-2 my-2',
        'disabled' => empty($session->read('Form.data.outsourcing.period_quarter')),
        'value' => $session->read('Form.data.outsourcing.inclusion_deadline')
    ]); ?>
    <?= $this->Form->select('dh_resp', $dh_resp, [
        'label'        => false,
        'empty'     => '-- Any DH Responsible --',
        'class'     => 'form-control mr-2 my-2',
        'disabled' => empty($session->read('Form.data.outsourcing.period_quarter')),
        'value' => $session->read('Form.data.outsourcing.dh_resp')
    ]); ?>
</div>
<div class="row col-10">
    <?= $this->Form->select('mandate_id', $mandates, [
        'label'        => false,
        'empty'     => '-- Any Mandate --',
        'class'     => 'form-control mr-2 my-2',
        'style' => 'width:200px',
        'disabled' => empty($session->read('Form.data.outsourcing.period_quarter')),
        'value' => $session->read('Form.data.outsourcing.mandate_id')
    ]); ?>
    <?= $this->Form->select('prioritised', $prioritised, [
        'label'        => false,
        'empty'     => '-- Any Prioritized --',
        'class'     => 'form-control mr-2 my-2',
        'disabled' => empty($session->read('Form.data.outsourcing.period_quarter')),
        'value' => $session->read('Form.data.outsourcing.prioritised')
    ]); ?>
    <?= $this->Form->select('inclusion_resp', $inclusion_resp, [
        'label'        => false,
        'empty'     => '-- Any Inclusion Responsible --',
        'class'     => 'form-control mr-2 my-2',
        'disabled' => empty($session->read('Form.data.outsourcing.period_quarter')),
        'value' => $session->read('Form.data.outsourcing.inclusion_resp')
    ]); ?>
</div>
<div class="row col-10">
    <?= $this->Form->select('portfolio_id', $portfolios, [
        'label'        => false,
        'empty'     => '-- Any Portfolio --',
        'class'     => 'form-control mr-2 my-2',
        'style' => 'width:200px',
        'disabled' => empty($session->read('Form.data.outsourcing.period_quarter')),
        'value' => $session->read('Form.data.outsourcing.portfolio_id')
    ]); ?>
    <?= $this->Form->select('inclusion_status', $inclusion_status, [
        'label'        => false,
        'empty'     => '-- Any Inclusion Status --',
        'class'     => 'form-control mr-2 my-2',
        'disabled' => empty($session->read('Form.data.outsourcing.period_quarter')),
        'value' => $session->read('Form.data.outsourcing.inclusion_status')
    ]); ?>
    <?= $this->Form->submit('Search', ['class' => 'btn btn-primary my-2', 'id' => '']) ?>
    <?= $this->Html->link('Reset', ['controller' => 'invoice', 'action' => 'reset-filters'], ['class' => 'btn btn-secondary ml-2 my-2', 'id' => 'reset']) ?>
</div>
<?= $this->Form->end() ?>

<?php if (isset($outsourcing_log_list)) : ?>
    <?php if (!empty($outsourcing_log_list)) {
        echo $this->Form->create(null);
    } ?>
    <div class="table-responsive">
        <table id="Dashboard" class="table table-striped">
            <thead>
                <tr>
                    <th>select</th>
                    <th><?= $this->Paginator->sort('log_id', '#') ?></th>
                    <th><?= $this->Paginator->sort('deal_business_key', 'Key') ?></th>
                    <th><?= $this->Paginator->sort('deal_name', 'Agreement name') ?></th>
                    <th><?= $this->Paginator->sort('portfolio_name', 'Portfolio') ?></th>
                    <th><?= $this->Paginator->sort('mandate_id', 'Mandate') ?></th>
                    <th><?= $this->Paginator->sort('inclusion_deadline', 'Inclusion inclusion_deadline') ?></th>
                    <th><?= $this->Paginator->sort('prioritised', 'Prioritized') ?></th>
                    <th><?= $this->Paginator->sort('inclusion_status', 'Inclusion status') ?></th>
                    <th><?= $this->Paginator->sort('email_date', 'Date Last Email') ?></th>
                    <th><?= $this->Paginator->sort('dh_resp', 'DH responsible') ?></th>
                    <th><?= $this->Paginator->sort('inclusion_resp', 'Inclusion responsible') ?></th>
                    <th><?= $this->Paginator->sort('received_date', 'Receipt Date') ?></th>
                    <th><?= $this->Paginator->sort('first_email_date', 'Email OIM/FI Date') ?></th>
                    <th><?= $this->Paginator->sort('inclusion_date', 'Inclusion Date') ?></th>
                    <th><?= $this->Paginator->sort('c_sheet', 'CG/C Sheet') ?></th>
                    <th><?= $this->Paginator->sort('follow_up', 'Follow Up') ?></th>
                    <th><?= $this->Paginator->sort('comments', 'Comments') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($outsourcing_log_list as $log) {
                    $inclusion_deadline = !empty($log->inclusion_deadline) ? h($log->inclusion_deadline->format('Y-m-d')) : '';
                    $email_date = !empty($log->email_date) ? h($log->email_date->format('Y-m-d')) : '';
                    $received_date = !empty($log->received_date) ? h($log->received_date->format('Y-m-d')) : '';
                    $first_email_date = !empty($log->first_email_date) ? h($log->first_email_date->format('Y-m-d')) : '';
                    $inclusion_date = !empty($log->inclusion_date) ? h($log->inclusion_date->format('Y-m-d')) : '';
                    $log_id = $log->log_id;
                    echo "<tr>";
                    echo "<td>" . $this->Form->checkbox('OutsourcingLog.' . $log_id . '.sel', ['data-log_id' => $log_id, 'class' => 'selection_export']);
                    echo $this->Form->hidden('OutsourcingLog.' . $log_id . '.log_id', ['value' => $log_id]);
                    echo $this->Form->hidden('storeinsession', ['value' => false]);
                    echo "</td>";
                    echo "<td>" . $log->log_id . "</td>";
                    echo "<td>" . $log->deal_business_key . "</td>";
                    echo "<td>" . $log->deal_name . "</td>";
                    echo "<td>" . $log->portfolio_name . "</td>";
                    echo "<td>" . $log->mandate_id . "</td>";
                    echo "<td>" . $inclusion_deadline . "</td>";
                    echo "<td>" . $this->Form->select('OutsourcingLog.' . $log_id . '.prioritised', $prioritised, [
                        'label'        => false,
                        'style'    => 'width:150px',
                        'class' => 'form-control',
                        'value'     => $log->prioritised
                    ]) . "</td>";
                    echo "<td>" . $this->Form->select('OutsourcingLog.' . $log_id . '.inclusion_status', $inclusion_status, [
                        'label'        => false,
                        'style'    => 'width : 200px',
                        'class' => 'form-control',
                        'empty'        => '-- Any Status --',
                        'value'     => $log->inclusion_status
                    ]) . "</td>";
                    echo "<td>" . $this->Form->input('OutsourcingLog.' . $log_id . '.email_date', [
                        'type'        => 'text',
                        'label'        => false,
                        'style'    => 'width : 100px',
                        'class'        => 'form-control datepicker',
                        'value'     => $email_date
                    ]) . "</td>";
                    echo "<td>" . $this->Form->input('OutsourcingLog.' . $log_id . '.dh_resp', [
                        'type'        => 'text',
                        'label'        => false,
                        'class' => 'form-control',
                        'value'     => $log->dh_resp,
                        'style'    => 'width : 130px',
                    ]) . "</td>";
                    echo "<td>" . $this->Form->input('OutsourcingLog.' . $log_id . '.inclusion_resp', [
                        'type'        => 'text',
                        'label'        => false,
                        'style'    => 'width : 130px',
                        'class' => 'form-control',
                        'value'     => $log->inclusion_resp
                    ]) . "</td>";
                    echo "<td>" . $this->Form->input('OutsourcingLog.' . $log_id . '.received_date', [
                        'type'        => 'text',
                        'label'        => false,
                        'style'    => 'width : 100px',
                        'class'        => 'form-control datepicker',
                        'value'     => $received_date
                    ]) . "</td>";
                    echo "<td>" . $this->Form->input('OutsourcingLog.' . $log_id . '.first_email_date', [
                        'type'        => 'text',
                        'label'        => false,
                        'style'    => 'width : 100px',
                        'class'        => 'form-control datepicker',
                        'value'     => $first_email_date
                    ]) . "</td>";
                    echo "<td>" . $this->Form->input('OutsourcingLog.' . $log_id . '.inclusion_date', [
                        'type'        => 'text',
                        'label'        => false,
                        'style'    => 'width : 100px',
                        'class'        => 'form-control datepicker',
                        'value'     => $inclusion_date
                    ]) . "</td>";
                    echo "<td>" . $this->Form->select('OutsourcingLog.' . $log_id . '.c_sheet', ['Y' => 'Yes', 'N' => 'No'], [
                        'label'        => false,
                        'style'    => 'width : 70px',
                        'class'        => 'form-control',
                        'value'     => $log->c_sheet
                    ]) . "</td>";
                    echo "<td>" . $this->Form->input('OutsourcingLog.' . $log_id . '.follow_up', [
                        'type'        => 'text',
                        'label'        => false,
                        'class'        => 'form-control',
                        'style'    => 'width : 130px',
                        'value'     => $log->follow_up
                    ]) . "</td>";
                    echo "<td>" . $this->Form->input('OutsourcingLog.' . $log_id . '.comments', [
                        'type'        => 'text',
                        'label'        => false,
                        'class'        => 'form-control',
                        'style'    => 'width : 200px',
                        'value'     => $log->comments
                    ]) . "</td>";
                    echo "</tr>";
                }
                ?>
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
    <div class="form-inline">
        <?php
        echo $this->Form->submit('Save', ['class' => 'btn btn-primary',  'id' => 'save_button']);
        echo '<button type="button" class="btn btn-secondary ml-2" id="export_button"><i class="fas fa-file-export"></i> &nbsp; Export to XLS</button>';
        echo $this->Html->link('Cancel', ['controller' => 'Home', 'action' => 'home'], ['class' => 'btn btn-danger ml-2']);
        echo $this->Form->end();
        ?>
    </div>
<?php endif; ?>

<div style="display:none;">
    <?php
    echo $this->Form->create(null, ['url' => '/damsv2/outsourcing/export', 'id' => 'extractHomeForm']);
    echo $this->Form->input('OutsourcingLog.log_id', [
        'type' => 'text',
        'id'    => 'OutsourcingLogLogId',
    ]);
    echo $this->Form->end();
    ?>
</div>

<script>
    function exportData() {
        //ajax to generate the list
        var log_id_list = Array();
        var selects = $('.selection_export:CHECKED');
        selects.each(function(i, j) {
            var el = $(j);
            log_id_list.push(el.attr('data-log_id'));
        });
        $('#extractHomeForm #OutsourcingLogLogId').val(log_id_list.join(','));
        var data = $('#extractHomeForm').serialize();
        $.ajax({
            url: '/damsv2/outsourcing/export',
            type: 'post',
            data: data,
            headers: {
                'X-CSRF-TOKEN': '<?= $this->request->getAttribute('csrfToken') ?>'
            },
        }).done(function(returndata) {
            window.open('/damsv2/ajax/download-file/' + returndata + '/export');
        });
        return false;
    }

    $(document).ready(function() {
        $('.selection_export').change(function(e) {
            var select = $(e.target);
            var tr = select.parents('tr');
            select = tr.find('input.selection_export');
            var required = select.is(':CHECKED');
            tr.find('#OutsourcingLog1InclusionStatus').attr('required', required);
            tr.find('#OutsourcingLog1DhResp').attr('required', required);
        });

        $('#save_button').attr('disabled', true);
        $('#export_button').attr('disabled', true);
        $('.selection_export').change(function(e) {
            var selects = $('.selection_export:CHECKED');
            $('#save_button').attr('disabled', (selects.length < 1));
            $('#export_button').attr('disabled', (selects.length < 1));
        });

        $("#export_button").on("click", function(e) {
            e.preventDefault();
            exportData();
        });
    });
</script>