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
        'title'   => 'Payment Demands/Recoveries dashboard',
        'url'     => ['controller' => 'Report', 'action' => 'pdlr'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>

<?php if(!empty($_GET['inclusion_error'])): ?>
     <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong>  An error occurred during the insertion of the report file <strong>#<?php print intval($_GET['inclusion_error']) ?></strong>, please contact the SAS support.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php elseif(!empty($_GET['inclusion_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong>  The report file <strong>#<?php print intval($_GET['inclusion_success']) ?></strong> has been inserted.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php elseif(!empty($_GET['inclusion_additional_check'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong>  Additional integrity breaches in the report <strong>#<?php print intval($_GET['inclusion_additional_check']) ?></strong>. Please check the error_file!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    
<?php endif ?>

<?php
if ($perm->hasRead(array('controller' => 'Report', 'action' => 'pdlrReception')))
{
	echo $this->Html->link(__('New PD / Recovery'), ['action' => 'pdlr-reception'], ['class' => 'btn btn-primary float-right mr-5 my-2']);
}
?>
<h3>Payment Demands/Recoveries dashboard</h3>

<?= $this->Form->create(null, ['class' => 'form-inline', 'id' => 'filters']) ?>

    <?= $this->Form->hidden('template_type_id', ['value' => [2,3]]) ?>

    <?= $this->Form->select('product_id', $products,
        [
            'empty' => '-- Any product --',
            'class' => 'form-control mr-2 my-2 filters',
            'value' => $session->read('Form.data.pdlr.product_id'),
            'id'    => 'productid'
        ]
    );
    ?>
    <?= $this->Form->select('mandate', $mandates,
        [
            'empty' => '-- Any mandate --',
            'class' => 'w-25 form-control mr-2 my-2 filters',
            'value' => $session->read('Form.data.pdlr.mandate'),
            'id'    => 'mandateid'
        ]
    );
    ?>
    <?= $this->Form->select('portfolio_id', $portfolios,
        [
            'empty' => '-- Any portfolio --',
            'class' => 'w-25 form-control mr-2 my-2 filters',
            'value' => $session->read('Form.data.pdlr.portfolio_id'),
            'id'    => 'portfolioid'
        ]
    );
    ?>
    <?= $this->Form->select('beneficiary_name', $beneficiary,
        [
            'empty' => '-- Any beneficiary --',
            'class' => 'w-25 form-control mr-2 my-2 filters',
            'value' => $session->read('Form.data.pdlr.beneficiary_name'),
            'id'    => 'beneficiaryid'
        ]
    );
    ?>

    <?= $this->Form->select('period_quarter', 
        ['Q1'=>'Q1', 'Q2'=>'Q2', 'Q3'=>'Q3', 'Q4'=>'Q4','S1' => 'S1','S2' => 'S2'],
        [
            'empty' 	=> '-- Any period --',
            'class' => 'form-control mr-2 my-2 filters',
            'value'	=> $session->read('Form.data.pdlr.period_quarter'),
            'id'    => 'periodqid'
        ]
    ); 
    ?>

    <?= $this->Form->input('period_year', [
            'empty' => '-- Any year --',
            'class' => 'form-control mr-2 my-2 filters',
            'value' => $session->read('Form.data.pdlr.period_year'),
            'type'  => 'year',
            'max'   => date('Y', time())+1,
            'min'   => date('Y', time())-3,
            'id'    => 'year_id',
            //'default' => date('Y', time())
        ]);
    ?>

    <?= $this->Form->select('stage', $stages,
        [
            'empty' => '-- Any stage --',
            'class' => 'form-control mr-2 my-2 filters',
            'value' => $session->read('Form.data.pdlr.stage'),
            'id'    => 'stage_id'
        ]
    );
    ?>

    <?= $this->Form->select('status', $statuses,
        [
            'empty' => '-- Any status --',
            'class' => 'form-control mr-2 my-2 filters',
            'value' => $session->read('Form.data.pdlr.status'),
            'id'    => 'status_id'
        ]
    );
    ?>

    <?= $this->Form->select('rep_owner', $users_rep,
        [
            'empty' => '-- Any report owner --',
            'class' => 'form-control mr-2 my-2 filters',
            'value' => $session->read('Form.data.pdlr.rep_owner'),
            'id'    => 'repowner_id'
        ]
    );
    ?>

    <?= $this->Form->select('port_owner', $users_port,
        [
            'empty' => '-- Any portfolio owner --',
            'class' => 'form-control mr-2 my-2 filters',
            'value' => $session->read('Form.data.pdlr.port_owner'),
            'id'    => 'portowner_id'
        ]
    );
    ?>
    <?= $this->Form->input('report_id', ['type' => 'number', 'label' => '', 'placeholder' => 'Any report id', 'class' => 'form-control mr-2 my-2 filters', 'style' => 'width:150px', 'value' => $session->read('Form.data.pdlr.report_id'), 'id' => 'report_id']) ?>

    <?= $this->Html->link('Reset', ['controller' => 'invoice', 'action' => 'reset-filters'], ['class' => 'btn btn-secondary ml-2 my-2', 'id' => 'reset']) ?>
<?= $this->Form->end(); ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('report_id', '#') ?></th>
                <th><?= $this->Paginator->sort('Template.template_type_id','Type') ?></th>
                <th><?= $this->Paginator->sort('report_name') ?></th>
                <th><?= $this->Paginator->sort('due_date','Due date') ?></th>
                <th><?= $this->Paginator->sort('Portfolio.owner', '<i class="fa fa-user"></i> Portfolio', ['escape' => false]) ?></th>
                <th><?= $this->Paginator->sort('owner', '<i class="fa fa-user"></i> Report', ['escape' => false]) ?></th>
                <th><?= $this->Paginator->sort('amount') ?></th>
                <th><?= $this->Paginator->sort('ccy') ?></th>

                <th><?= $this->Paginator->sort('Status.stage','Stage') ?></th>
                <th><?= $this->Paginator->sort('Status.status','Status') ?></th>

                <th class="actions"><?= __('Action') ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($report as $report): 
                $report->perm = $perm; ?>
                <tr>
                    <td><?= $report->report_id ?></td>
                    <td><?php switch ($report->template->template_type_id){
                            case '2':
                                    echo 'PD';
                                    break;
                            case '3':
                                    echo 'LR';
                                    break;
                            } ?>
                    </td>
                    <td><?= h($report->report_name) ?></td>
                    <td><?= !empty($report->due_date) ? h($report->due_date->format('Y-m-d')) : '' ?></td>
                    <td><?= !empty($report->portfolio->v_user) ? h($report->portfolio->v_user->full_name) : '' ?></td>
                    <td><?= !empty($report->v_user) ? h($report->v_user->full_name) : '' ?></td>
                    <td><?= $this->Number->format($report->amount) ?></td>
                     <td><?= h($report->ccy) ?></td>
                    <td><?= h($report->status->stage) ?></td>
                    <td><?= h($report->status->status) ?></td>
                    <td><?= $report->report_action_link ?></td>
                    <td>
                    <?php
                        //if ($this->UserAuth->isAdmin() || (!empty($getgroupalias) && !in_array('DAMSreporting', $getgroupalias))) {
                            if ($report->status_id != 8) {
                                if (($report->status_id < 10 OR $report->status_id == 19 ) && $perm->hasWrite(array('action' => 'rejectPdlr'))) {
                                    echo $this->Form->create(null, ['url' => '/damsv2/report/reject-pdlr', 'id' => 'reject_pdlr']);
                                    echo $this->Form->input('Report.report_id', [
                                        'type'  => 'hidden',
                                        'label' => false,
                                        'div'   => false,
                                        'value' => $report->report_id,
                                    ]);
                                    echo $this->Form->submit('Reject', [
                                       'class'    => 'btn btn-info',
                                       'title'    => 'Reject this report',
                                       'id'       => 'rejreport',
                                       'onclick'  => 'alert("You are about to reject this report, are you sure you want to do this?")'
                                   ]);
                                   echo $this->Form->end();
                                }
                            }
                        //}
                        if (($report->status_id == 8)&& $perm->hasDelete(array('action' => 'deletePdlr'))) {
                            echo $this->Form->create(null,['url' => '/damsv2/report/delete-pdlr', 'id' => 'del_pdlr']);
                            echo $this->Form->input('Report.report_id', [
                                'type'  => 'hidden',
                                'label' => false,
                                'div'   => false,
                                'value' => $report->report_id,
                            ]);
                            echo $this->Form->submit('Delete', [
                                       'class'    => 'btn btn-danger',
                                       'title'    => 'Delete this report',
                                       'id'       => 'delreport',
                                       'onclick'  => 'if(confirm("You are about to reject this report, are you sure you want to proceed?") == false)return false;'
                                   ]);
                            echo $this->Form->end();
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

<div style="display: none;">
<?php
    echo $this->Form->create(null, ['id' => 'delete_cache', 'url'=>'/damsv2/report/delete-cache']);
    echo $this->Form->input('Report.report_id', [
            'type' => 'text',
            'label'	=> false,
            'id'	=> 'ReportReportId',
    ]);
    echo $this->Form->end();
   
?>
</div>

<script>
var submitting = false;
$(document).ready(function () {

    $('form').submit(function ()//to prevent double click on reject/delete and sometimes the filter takes a while
    {
        if (submitting)
        {
            return false;
        } else
        {
            submitting = true;
        }
    });
    
    $('#ReportReportId').autoNumeric('init', {aSep: false, aDec: '.', mDec: '0', vMax: 999999, wEmpty: 'empty'});
    
    $(".delete_cache").click(function (e)
    {
        e.preventDefault();
        var id = $(e.target).attr('data-id-cache');
       
        $('#ReportReportId').val(id);
        $('#delete_cache').submit();
    });

});

if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>

