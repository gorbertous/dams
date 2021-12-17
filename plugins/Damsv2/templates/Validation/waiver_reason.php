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
        'title'   => 'Inclusion Dashboard',
        'url'     => ['controller' => 'Report', 'action' => 'inclusion'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Inclusion Validation',
        'url'     => ['controller' => 'report', 'action' => 'inclusion-validation', $report->report_id],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-inv-crumb'
            ]
        ]
    ],
    [
        'title'   => $report->report_name,
        'url'     => ['controller' => 'validation', 'action' => 'waiver-reason', $report->report_id],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-inv-crumb'
            ]
        ]
    ]
]);
$link = '';
?>

<h3>Exempted reasons</h3>
<hr>
<?= $this->Form->create(null, ['type' => 'post', 'id' => 'Damsv2.Report']) ?>
<?= $this->Form->input('Report.report_id', ['type' => 'hidden', 'value' => $report->report_id]) ?>

<h5>Exempted SMEs</h5>
<?php if (!empty($reasons['SME'])): ?>
    <div class="row">
        <div class="col-12">
            <span class="btn btn-secondary my-2 float-right" id="duplicate_sme">Replicate Reason</span>
            <span class="btn btn-secondary my-2 mr-2 float-right" id="clear_sme">Clear Fields</span>
        </div>
    </div>
<?php endif ?>
<?php if (empty($reasons['SME'])) : ?>
    <h6>No exempted SMEs</h6>
<?php else : ?>
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Fiscal number</th>
                        <th>Country</th>
                        <th>Error message</th>
                        <th>Exemption reason</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($reasons['SME'] as $sme) {
                        echo '<tr>';
                        echo '<td>';
                        echo $sme['fiscal_number'];
                        echo '</td>';
                        echo '<td>';
                        echo $sme['country'];
                        echo '</td>';
                        echo '<td>';
                        echo $sme['error_message'];
                        echo '</td>';
                        echo '<td>';
                        echo $this->Form->input('SME.comment_' . $sme['fiscal_number'] . '___' . $sme['country'], [
                            'type'     => 'text',
                            'default'  => $sme['comment'],
                            'required' => true,
                            'label'    => false,
                            'class'    => 'sme_comment',
                        ]);
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
<h5>Exempted Transactions</h5>
<?php if (!empty($reasons['TRN'])): ?>
    <div class="row pt-5">
        <div class="col-12">
            <span class="btn btn-secondary my-2 float-right" id="duplicate_trn">Replicate Reason</span>
            <span class="btn btn-secondary my-2 mr-2 float-right" id="clear_trn">Clear Fields</span>
        </div>
    </div>
<?php endif; ?>
<?php if (empty($reasons['TRN'])) : ?>
    <h6>No waived Transactions</h6>            
<?php else : ?>
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Fiscal number</th>
                        <th>Transaction reference</th>
                        <th>Error message</th>
                        <th>Exemption reason</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($reasons['TRN'] as $trn) {
                        echo '<tr>';
                        echo '<td>';
                        echo $trn['fiscal_number'];
                        echo '</td>';
                        echo '<td>';
                        echo $trn['transaction_reference'];
                        echo '</td>';
                        echo '<td>';
                        echo $trn['error_message'];
                        echo '</td>';
                        echo '<td>';
                        echo $this->Form->input('TRN.comment_' . $trn['fiscal_number'] . '___' . $trn['transaction_reference'], [
                            'type'     => 'text',
                            'default'  => $trn['comment'],
                            'required' => true,
                            'label'    => false,
                            'class'    => 'trn_comment',
                        ]);
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?> 

<h5>Exempted Sub-Transactions</h5>
<?php if (!empty($reasons['SUB'])): ?>
    <div class="row pt-5">
        <div class="col-12">

            <span class="btn btn-secondary my-2 float-right" id="duplicate_sub">Replicate Reason</span>
            <span class="btn btn-secondary my-2 mr-2 float-right" id="clear_sub">Clear Fields</span>
        </div>
    </div>
<?php endif; ?>
<?php if (empty($reasons['SUB'])) : ?>
    <h6>No exempted Sub-Transactions</h6>
<?php else : ?>
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Fiscal number</th>
                        <th>Transaction Reference</th>
                        <th>Sub-Transaction Reference</th>
                        <th>Error message</th>
                        <th>Exemption reason</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($reasons['SUB'] as $sub) {
                        echo '<tr>';
                        echo '<td>';
                        echo $sub['fiscal_number'];
                        echo '</td>';
                        echo '<td>';
                        echo $sub['transaction_reference'];
                        echo '</td>';
                        echo '<td>';
                        echo $sub['subtransaction_reference'];
                        echo '</td>';
                        echo '<td>';
                        echo $sub['error_message'];
                        echo '</td>';
                        echo '<td>';
                        echo $this->Form->input('SUB.comment_' . $sub['fiscal_number'] . '__' . $sub['transaction_reference'] . '__' . $sub['subtransaction_reference'], [
                            'type'     => 'text',
                            'default'  => $sub['comment'],
                            'required' => true,
                            'label'    => false,
                            'class'    => 'sub_comment',
                        ]);
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
    
<div class="row py-5">
    <div class="col-6 form-inline">
        <?= $this->Html->link('Back', ['controller' => 'report', 'action' => 'inclusion-validation', $report->report_id], ['class' => 'btn btn-secondary mr-2']) ?>
        <?= $this->Form->submit('Next', [
            'class' => 'btn btn-primary',
            'name'  => 'upload_report',
            'id'    => 'upload_report',
			'disabled' => !$perm->hasWrite(array('controller' => 'Validation', 'action' => 'waiverReason')),
        ])
        ?>
    </div>
</div>
  
<?php echo $this->Form->end() ?>



<script>

    function test_all_fields()
    {
        var allow_submit = true;
        $("input").each(function (index) {
            var e = $(this);
            var val = e.val();
            if (val.trim() == '')
            {
                allow_submit = false;
                e.attr('style', 'border-color: red;');
            } else
            {
                e.attr('style', '');
            }

        });
        document.getElementById('upload_report').disabled = !allow_submit;
    }

    $(document).ready(function () {
        test_all_fields();

        $("input").keyup(test_all_fields);

        $('#duplicate_sme').click(function (e)
        {
            var val = $('.sme_comment').first().val();
            $('.sme_comment').val(val);
            test_all_fields();
        });

        $('#clear_sme').click(function (e)
        {
            var val = '';
            $('.sme_comment').val(val);
            test_all_fields();
        });
        $('#duplicate_trn').click(function (e)
        {
            var val = $('.trn_comment').first().val();
            $('.trn_comment').val(val);
            test_all_fields();
        });

        $('#clear_trn').click(function (e)
        {
            var val = '';
            $('.trn_comment').val(val);
            test_all_fields();
        });
        $('#duplicate_sub').click(function (e)
        {
            var val = $('.sub_comment').first().val();
            $('.sub_comment').val(val);
            test_all_fields();
        });

        $('#clear_sub').click(function (e)
        {
            var val = '';
            $('.sub_comment').val(val);
            test_all_fields();
        });
    });

</script>