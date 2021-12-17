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
        'url'     => ['controller' => 'validation', 'action' => 'inclusion-validation-ro', $report->report_id],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-inv-crumb'
            ]
        ]
    ],
    [
        'title'   => 'Validation Report',
        'url'     => ['controller' => 'validation', 'action' => 'validation-report-ro', $report->report_id],
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
        'url'     => ['controller' => 'validation', 'action' => 'waiver-reason-ro', $report->report_id],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-inv-crumb'
            ]
        ]
    ]
]);

?>

<h3>Exemption reasons</h3>
<hr>


<div class="row pt-5">
    <div class="col-6"><h5>Exempted SMEs</h5></div>
    <div class="col-4">
        <a class="btn btn-secondary" href=<?=
        $this->Url->build(['controller' => 'ajax', 'action'     => 'downloadFile', '_ext'       => null,
            'sme_exemption_' . $report->report_id . '.xlsx',
            'waiver_reasons','validated']);
        ?> >
            Download
        </a>
    </div>
</div>
<?php if (empty($reasons['SME'])) : ?>
    <h6>No waived SMEs</h6>
<?php else : ?>
    <div class="row">
        <div class="col-6">
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
                        echo $sme['comment'];
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
<div class="row pt-5">
    <div class="col-6"><h5>Exempted Transactions</h5></div>
    <div class="col-4">
        <a class="btn btn-secondary" href=<?=
        $this->Url->build(['controller' => 'ajax', 'action'     => 'downloadFile', '_ext'       => null,
            'transactions_exemption_' . $report->report_id . '.xlsx',
            'waiver_reasons','validated']);
        ?> >
            Download
        </a>
    </div>
</div>

<?php if (empty($reasons['TRN'])) : ?>
    <h6>No waived Transactions</h6>            
<?php else : ?>
    <div class="row">
        <div class="col-6">
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
                        echo $trn['comment'];
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>  

<div class="row pt-5">
    <div class="col-6"><h5>Exempted Sub-Transactions</h5></div>
    <div class="col-4">
        <a class="btn btn-secondary" href=<?=
        $this->Url->build(['controller' => 'ajax', 'action'     => 'downloadFile', '_ext'       => null,
            'subtransactions_exemption_' . $report->report_id . '.xlsx',
            'waiver_reasons','validated']);
        ?> >
            Download
        </a>
    </div>
</div>

<?php if (empty($reasons['SUB'])) : ?>
    <h6>No exempted Sub-Transactions</h6>
<?php else : ?>
    <div class="row">
        <div class="col-6">
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
                    foreach ($reasons['SUB'] as $sub) {// fiscal number transaction_reference subtransaction_reference and then error_message 
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
                        echo $sub['comment'];
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
        <?= $this->Html->link('Back', ['action'=>'inclusion-validation-ro',$report->report_id], ['class' => 'btn btn-secondary mr-2']) ?>
        <?= $this->Html->link('Next', ['action'=>'comment',$report->report_id], ['class' => 'btn btn-primary']) ?>
    </div>
</div>
    

