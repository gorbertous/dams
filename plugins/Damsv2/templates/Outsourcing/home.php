<?php
echo $this->Html->script('/treasury/js/bootstrap-datepicker');
echo $this->Html->css('/treasury/css/datepicker');
function Damsv2($date)
{
    if (strpos($date, '-') !== false) {
        $date = explode('-', $date);
        $date = $date[2] . '/' . $date[1] . '/' . $date[0];
    }
    return $date;
}
?><fieldset>
    <legend>Outsourcing Log</legend>

    <?php echo $this->Form->create('filter') ?>
    <?php echo $this->Form->input('filter.quarter', array(
        'label'        => false, 'div' => false,
        'empty'     => '-- Any Quarter --',
        'required' => false,
        'options'     => $quarters,
        'default'    => $filter['quarter'],
    )); ?>
    <?php echo $this->Form->input('filter.deadline', array(
        'label'        => false, 'div' => false,
        'empty'     => '-- Any Inclusion Deadline --',
        'required' => false,
        'options'     => $deadlines,
        'disabled' => empty($filter['quarter']),
        'default'    => $filter['deadline'],
    )); ?>
    <?php echo $this->Form->input('filter.dh_resp', array(
        'label'        => false, 'div' => false,
        'empty'     => '-- Any DH Responsible --',
        'required' => false,
        'options'     => $dh_resp,
        'disabled' => empty($filter['quarter']),
        'default'    => $filter['dh_resp'],
    )); ?>
    <br />
    <?php echo $this->Form->input('filter.mandate', array(
        'label'        => false, 'div' => false,
        'empty'     => '-- Any Mandate --',
        'required' => false,
        'options'     => $mandates,
        'disabled' => empty($filter['quarter']),
        'default'    => $filter['mandate'],
    )); ?>
    <?php echo $this->Form->input('filter.prioritized', array(
        'label'        => false, 'div' => false,
        'empty'     => '-- Any Prioritized --',
        'required' => false,
        'options'     => $prioritized,
        'disabled' => empty($filter['quarter']),
        'default'    => $filter['prioritized'],
    )); ?>
    <?php echo $this->Form->input('filter.inclusion_resp', array(
        'label'        => false, 'div' => false,
        'empty'     => '-- Any Inclusion Responsible --',
        'required' => false,
        'options'     => $inclusion_resp,
        'disabled' => empty($filter['quarter']),
        'default'    => $filter['inclusion_resp'],
    )); ?>
    <br />
    <?php echo $this->Form->input('filter.portfolio_id', array(
        'label'        => false, 'div' => false,
        'empty'     => '-- Any Portfolio --',
        'required' => false,
        'options'     => $portfolios,
        'disabled' => empty($filter['quarter']),
        'default'    => $filter['portfolio_id'],
    )); ?>
    <?php echo $this->Form->input('filter.inclusion_status', array(
        'label'        => false, 'div' => false,
        'empty'     => '-- Any Inclusion Status --',
        'required' => false,
        'options'     => $inclusion_status,
        'disabled' => empty($filter['quarter']),
        'default'    => $filter['inclusion_status'],
    )); ?>
    <?php echo $this->Form->submit('Search', array('class' => 'btn btn-primary')) ?>
    <?php echo $this->Form->end() ?>

    <div>
        <?php
        if (!empty($outsourcinglog)) {
            echo $this->Form->create('outsourcing');
        ?>
            <table id="Dashboard" class="table">
                <thead>
                    <tr>
                        <th>select</th>
                        <th><?php echo $this->Paginator->sort('OutsourcingLog.log_id', '#') ?></th>
                        <th><?php echo $this->Paginator->sort('OutsourcingLog.deal_business_key', 'Key') ?></th>
                        <th><?php echo $this->Paginator->sort('OutsourcingLog.deal_name', 'Agreement name') ?></th>
                        <th><?php echo $this->Paginator->sort('OutsourcingLog.portfolio_name', 'Portfolio') ?></th>
                        <th><?php echo $this->Paginator->sort('OutsourcingLog.mandate', 'Mandate') ?></th>
                        <th><?php echo $this->Paginator->sort('OutsourcingLog.inclusion_deadline', 'Inclusion deadline') ?></th>
                        <th><?php echo $this->Paginator->sort('OutsourcingLog.prioritised', 'Prioritized') ?></th>
                        <th><?php echo $this->Paginator->sort('OutsourcingLog.inclusion_status', 'Inclusion status') ?></th>
                        <th><?php echo $this->Paginator->sort('OutsourcingLog.email_date', 'Date Last Email') ?></th>
                        <th><?php echo $this->Paginator->sort('OutsourcingLog.dh_resp', 'DH responsible') ?></th>
                        <th><?php echo $this->Paginator->sort('OutsourcingLog.inclusion_resp', 'Inclusion responsible') ?></th>
                        <th><?php echo $this->Paginator->sort('OutsourcingLog.received_date', 'Receipt Date') ?></th>
                        <th><?php echo $this->Paginator->sort('OutsourcingLog.first_email_date', 'Email OIM/FI Date') ?></th>
                        <th><?php echo $this->Paginator->sort('OutsourcingLog.inclusion_date', 'Inclusion Date') ?></th>
                        <th><?php echo $this->Paginator->sort('OutsourcingLog.c_sheet', 'CG/C Sheet') ?></th>
                        <th><?php echo $this->Paginator->sort('OutsourcingLog.follow_up', 'Follow Up') ?></th>
                        <th><?php echo $this->Paginator->sort('OutsourcingLog.comments', 'Comments') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($outsourcinglog as $log) {
                        $log_id = $log['OutsourcingLog']['log_id'];
                        echo "<tr>";
                        echo "<td>" . $this->Form->checkbox('OutsourcingLog.' . $log_id . '.sel', array('data-log_id' => $log_id, 'class' => 'selection_export'));
                        echo $this->Form->input('OutsourcingLog.' . $log_id . '.log_id', array(
                            'type' => 'hidden',
                            'label'    => false,
                            'div'    => false,
                            'value' => $log_id,
                        ));
                        echo $this->Form->input('OutsourcingLog.filter', array(
                            'type' => 'hidden',
                            'label'    => false,
                            'div'    => false,
                            'value' => serialize($filter),
                        ));
                        echo "</td>";
                        echo "<td>" . $log['OutsourcingLog']['log_id'] . "</td>";
                        echo "<td>" . $log['OutsourcingLog']['deal_business_key'] . "</td>";
                        echo "<td>" . $log['OutsourcingLog']['deal_name'] . "</td>";
                        echo "<td>" . $log['OutsourcingLog']['portfolio_name'] . "</td>";
                        echo "<td>" . $log['OutsourcingLog']['mandate'] . "</td>";
                        echo "<td>" . $log['OutsourcingLog']['inclusion_deadline'] . "</td>";
                        echo "<td>" . $this->Form->input('OutsourcingLog.' . $log_id . '.prioritised', array(
                            'label'        => false, 'div' => false,
                            'required'    => false,
                            'options'    => $prioritized,
                            'value'     => $log['OutsourcingLog']['prioritised']
                        )) . "</td>";
                        echo "<td>" . $this->Form->input('OutsourcingLog.' . $log_id . '.inclusion_status', array(
                            'label'        => false, 'div' => false,
                            'required'    => false,
                            'options'    => $inclusion_status,
                            'empty'        => '-- Any Status --',
                            'value'     => $log['OutsourcingLog']['inclusion_status'],
                            //'required'	=> true,
                        )) . "</td>";
                        echo "<td>" . $this->Form->input('OutsourcingLog.' . $log_id . '.email_date', array(
                            'type'        => 'text',
                            'label'        => false, 'div' => false,
                            'required'    => false,
                            'class'        => 'hasDatepicker',
                            'value'     => datesToslash($log['OutsourcingLog']['email_date'])
                        )) . "</td>";
                        echo "<td>" . $this->Form->input('OutsourcingLog.' . $log_id . '.dh_resp', array(
                            'label'        => false, 'div' => false,
                            'required'    => false,
                            'value'     => $log['OutsourcingLog']['dh_resp'],
                            'required'    => false,
                        )) . "</td>";
                        echo "<td>" . $this->Form->input('OutsourcingLog.' . $log_id . '.inclusion_resp', array(
                            'label'        => false, 'div' => false,
                            'required'    => false,
                            'value'     => $log['OutsourcingLog']['inclusion_resp']
                        )) . "</td>";
                        echo "<td>" . $this->Form->input('OutsourcingLog.' . $log_id . '.received_date', array(
                            'type'        => 'text',
                            'label'        => false, 'div' => false,
                            'required'    => false,
                            'class'        => 'hasDatepicker',
                            'value'     => datesToslash($log['OutsourcingLog']['received_date'])
                        )) . "</td>";
                        echo "<td>" . $this->Form->input('OutsourcingLog.' . $log_id . '.first_email_date', array(
                            'type'        => 'text',
                            'label'        => false, 'div' => false,
                            'required'    => false,
                            'class'        => 'hasDatepicker',
                            'value'     => datesToslash($log['OutsourcingLog']['first_email_date'])
                        )) . "</td>";
                        echo "<td>" . $this->Form->input('OutsourcingLog.' . $log_id . '.inclusion_date', array(
                            'type'        => 'text',
                            'label'        => false, 'div' => false,
                            'required'    => false,
                            'class'        => 'hasDatepicker',
                            'value'     => datesToslash($log['OutsourcingLog']['inclusion_date'])
                        )) . "</td>";
                        echo "<td>" . $this->Form->input('OutsourcingLog.' . $log_id . '.c_sheet', array(
                            'label'        => false, 'div' => false,
                            'required'    => false,
                            'options'        => array('Y' => 'Yes', 'N' => 'No'),
                            'value'     => $log['OutsourcingLog']['c_sheet']
                        )) . "</td>";
                        echo "<td>" . $this->Form->input('OutsourcingLog.' . $log_id . '.follow_up', array(
                            'label'        => false, 'div' => false,
                            'required'    => false,
                            'value'     => $log['OutsourcingLog']['follow_up']
                        )) . "</td>";
                        echo "<td>" . $this->Form->input('OutsourcingLog.' . $log_id . '.comments', array(
                            'label'        => false, 'div' => false,
                            'required'    => false,
                            'value'     => $log['OutsourcingLog']['comments']
                        )) . "</td>";
                        echo "</tr>";
                    }

                    ?></tbody>
            </table>
            <?php echo $this->Paginator->counter(
                'Page {:page} of {:pages}, showing {:current} records out of
     {:count} total, starting on record {:start}, ending on {:end}'
            ); ?>

            <div class="pagination">
                <ul>
                    <?php
                    echo $this->Paginator->prev('<<', array('class' => '', 'tag' => 'li'), null, array('class' => 'disabled myclass', 'tag' => 'li'));
                    echo $this->Paginator->numbers(array('tag' => 'li', 'separator' => '', 'currentClass' => 'disabled myclass'));
                    echo $this->Paginator->next('>>', array('class' => '', 'tag' => 'li'), null, array('class' => 'disabled myclass', 'tag' => 'li'));
                    ?>
                </ul>
            </div>
    </div>

    <div>
        <?php
            echo $this->Form->submit('Save', array('class' => 'btn btn-primary', 'div' => false, 'id' => 'save_button'));
            echo '<a href="#" class="btn" id="Export">Export to Excel</a>';
            echo $this->Html->link('Cancel', array('controller' => 'Damsv2App', 'action' => 'homepage'), array('class' => 'btn'));
            echo $this->Form->end(); ?>
    </div>
<?php
        }
?>

<div style="display:none;">
    <?php
    echo $this->Form->create('filterajax', array('url' => '/damsv2/damsv2ajax/getPortfoliosByProduct'));
    echo $this->Form->input('Product.product_id', array(
        'type' => 'text',
        'label'    => false,
        'div'    => false,
    ));
    echo $this->Form->input('Portfolio.portfolio_empty', array(
        'type' => 'hidden',
        'label'    => false,
        'div'    => false,
        'value' => '-- Portfolio --',
    ));
    echo $this->Form->end();

    echo $this->Form->create('extract', array('url' => '/damsv2/outsourcing/export'));
    echo $this->Form->input('OutsourcingLog.log_id', array(
        'type' => 'text',
        'label'    => false,
        'div'    => false,
    ));
    echo $this->Form->end();

    ?>
</div>
</fieldset>
<style>
    #Dashboard select {
        width: 100px;
    }

    .hasDatepicker {
        width: 73px;
    }
</style>
<script>
    $(document).ready(function() {
        $('#ProductProductId').change(function(e) {
            $('#filterajaxHomeForm #ProductProductId').val($('#filterHomeForm #ProductProductId').val());
            var post = $('#filterajaxHomeForm').serialize();
            $.ajax({
                url: '/damsv2/damsv2ajax/getPortfoliosByProduct',
                type: "POST",
                data: post,
            }).done(function(data) {
                $('#filterHomeForm #PortfolioPortfolioId').html(data);
            });
        });
        $('.hasDatepicker').datepicker({
            format: 'dd/mm/yyyy',
        });

        $('.selection_export').change(function(e) {
            var select = $(e.target);
            var tr = select.parents('tr');
            select = tr.find('input.selection_export');
            var required = select.is(':CHECKED');
            tr.find('#OutsourcingLog1InclusionStatus').attr('required', required);
            tr.find('#OutsourcingLog1DhResp').attr('required', required);
        });

        $('#save_button').attr('disabled', true);
        $('.selection_export').change(function(e) {
            var selects = $('.selection_export:CHECKED');
            $('#save_button').attr('disabled', (selects.length < 1));
        });

        $('#Export').mousedown(function(e) {
            e.preventDefault();
            var log_id_list = Array();
            var selects = $('.selection_export:CHECKED');
            selects.each(function(i, j) {
                var el = $(j);
                log_id_list.push(el.attr('data-log_id'));
            });
            $('#extractHomeForm #OutsourcingLogLogId').val(log_id_list.join(','));
            var post = $('#extractHomeForm').serialize();
            $.ajax({
                url: '/damsv2/outsourcing/export',
                type: "POST",
                data: post,
                dataType: 'json',
            }).done(function(data) {
                if (data.success) {
                    window.open('/damsv2/damsv2ajax/download_file/1?file=/data/damsv2/export/' + data.file);
                }
            });
        });
    });
</script>