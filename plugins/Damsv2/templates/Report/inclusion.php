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
        'title'   => 'Inclusion Dashboard',
        'url'     => ['controller' => 'Report', 'action' => 'inclusion'],
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
<?php elseif(!empty($_GET['inclusion_background'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong>  The report file <strong>#<?php print intval($_GET['inclusion_background']) ?></strong> is being saved.
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


<h3>Inclusion Dashboard</h3>

 
<?= $this->Form->create(null, ['class' => 'form-inline', 'id' => 'filters']) ?>
   
    <?= $this->Form->hidden('visible', ['value' => 1]) ?>
    <?= $this->Form->hidden('template_type_id', ['value' => 1]) ?>

    <?= $this->Form->select('product_id', $products,
        [
            'empty' => '-- Any product --',
            'class' => 'form-control mr-2 my-2 filters',
            'value' => $session->read('Form.data.inclusion.product_id'),
            'id'    => 'productid'
        ]
    );
    ?>
    <?= $this->Form->select('mandate', $mandates,
        [
            'empty' => '-- Any mandate --',
            'class' => 'w-25 form-control mr-2 my-2 filters',
            'value' => $session->read('Form.data.inclusion.mandate'),
            'id'    => 'mandateid'
        ]
    );
    ?>
    <?= $this->Form->select('portfolio_id', $portfolios,
        [
            'empty' => '-- Any portfolio --',
            'class' => 'w-25 form-control mr-2 my-2 filters',
            'value' => $session->read('Form.data.inclusion.portfolio_id'),
            'id'    => 'portfolioid'
        ]
    );
    ?>
    <?= $this->Form->select('beneficiary_name', $beneficiary,
        [
            'empty' => '-- Any beneficiary --',
            'class' => 'w-25 form-control mr-2 my-2 filters',
            'value' => $session->read('Form.data.inclusion.beneficiary_name'),
            'id'    => 'beneficiaryid'
        ]
    );
    ?>
  
    <?= $this->Form->select('period_quarter', 
        ['Q1'=>'Q1', 'Q2'=>'Q2', 'Q3'=>'Q3', 'Q4'=>'Q4','S1' => 'S1','S2' => 'S2'],
        [
            'empty' 	=> '-- Any period --',
            'class' => 'form-control mr-2 my-2 filters',
            'value'	=> $session->read('Form.data.inclusion.period_quarter'),
            'id'    => 'periodqid'
        ]
    ); 
    ?>
  
    <?= $this->Form->control('period_year', [
            'empty' => '-- Any year --',
            'label' => '',
            'class' => 'form-control mr-2 my-2 filters',
            'value' => $session->read('Form.data.inclusion.period_year'),
            'type' => 'year',
            'max'	=> date('Y', time())+1,
            'min'	=> date('Y', time())-3,
            'id'    => 'year_id',
        ]);
    ?>

    <?= $this->Form->select('stage', $stages,
        [
            'empty' => '-- Any stage --',
            'class' => 'form-control mr-2 my-2 filters',
            'value' => $session->read('Form.data.inclusion.stage'),
            'id'    => 'stage_id'
        ]
    );
    ?>

    <?= $this->Form->select('status', $statuses,
        [
            'empty' => '-- Any status --',
            'class' => 'form-control mr-2 my-2 filters',
            'value' => $session->read('Form.data.inclusion.status'),
            'id'    => 'status_id'
        ]
    );
    ?>
    
    <?= $this->Form->select('report_type', ['closure'=>'Closure', 'regular'=>'Regular'],[
           'empty' 	=> '-- Any report type –',
           'class' => 'form-control mr-2 my-2 filters',
           'value'	=> $session->read('Form.data.inclusion.report_type'),
           'id'    => 'reptype_id'
    ]); ?>
    
    <?= $this->Form->select('rep_owner', $users_rep,
        [
            'empty' => '-- Any report owner --',
            'class' => 'form-control mr-2 my-2 filters',
            'value' => $session->read('Form.data.inclusion.rep_owner'),
            'id'    => 'repowner_id'
        ]
    );
    ?>
    
    <?= $this->Form->select('port_owner', $users_port,
        [
            'empty' => '-- Any portfolio owner --',
            'class' => 'form-control mr-2 my-2 filters',
            'value' => $session->read('Form.data.inclusion.port_owner'),
            'id'    => 'portowner_id'
        ]
    );
    ?>
    <?= $this->Form->input('report_id', ['type' => 'number', 'label' => '', 'placeholder' => 'Any report id', 'class' => 'form-control mr-2 my-2 filters', 'style' => 'width:150px', 'value' => $session->read('Form.data.inclusion.report_id'), 'id' => 'report_id']) ?>

   
    <?= $this->Html->link('Reset', ['controller' => 'invoice', 'action' => 'reset-filters'], ['class' => 'btn btn-secondary ml-2 my-2', 'id' => 'reset']) ?>
<?= $this->Form->end(); ?>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('report_id', '#') ?></th>
                <th><?= $this->Paginator->sort('report_name') ?></th>
                <th><?= $this->Paginator->sort('owner', '<i class="fa fa-user"></i> Report', ['escape' => false]) ?></th>
                <th><?= $this->Paginator->sort('Portfolio.owner', '<i class="fa fa-user"></i> Portfolio', ['escape' => false]) ?></th>
                <th><?= $this->Paginator->sort('Portfolio.availability_start','Availability start') ?></th>
                <th><?= $this->Paginator->sort('Portfolio.availability_end','Availability end') ?></th>
                <th><?= $this->Paginator->sort('reception_date','Reception date') ?></th>
                <th><?= $this->Paginator->sort('report_type') ?></th>
                <th><?= $this->Paginator->sort('Status.stage','Stage') ?></th>
                <th><?= $this->Paginator->sort('Status.status','Status') ?></th>
               
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reports as $report): 
                $report->perm = $perm; ?>
                <tr>
                    <td><?= $report->report_id ?></td>
                    <td><?= $this->Html->link($report->report_name, ['controller' => 'Report', 'action' => 'inclusionHistory', $report->report_id])  ?></td>
                   
                    <td><?= !empty($report->v_user) ? h($report->v_user->full_name) : '' ?></td>
                    <td><?= !empty($report->portfolio->v_user) ? h($report->portfolio->v_user->full_name) : '' ?></td>
                    <td><?= !empty($report->portfolio->inclusion_start_date) ? h($report->portfolio->inclusion_start_date->format('Y-m-d')) : '' ?></td>
                    <td><?= !empty($report->portfolio->inclusion_end_date) ? h($report->portfolio->inclusion_end_date->format('Y-m-d')) : '' ?></td>
                    <td><?= !empty($report->reception_date) ? h($report->reception_date->format('Y-m-d')) : '' ?></td>
                    <td><?= h($report->report_type) ?></td>

                    <td><?= h($report->status->stage) ?></td>
                    <td><?= h($report->status->status) ?></td>
                    <td>
                        <?php
                        $edit_actions = array(1, 2, 3, 4, 5, 6, 7, 8, 22);
                        $view_actions = array(2, 4, 5, 6, 7, 8, 22, 23);
                        $validation_actions = array(4, 5, 7, 9, 23);
                        $edit_right = $perm->hasWrite(array('controller' => 'Report', 'action' => 'correction'));
                        $access_inclusion_view = $perm->hasRead(array('controller' => 'Validation', 'action' => 'inclusionValidation'));
                        $view_validation = $perm->hasRead(array('controller' => 'Validation', 'action' => 'inclusionValidation'));
                        $access_validation = $perm->hasWrite(array('controller' => 'Validation', 'action' => 'inclusionValidation'));
                        
                        if ($edit_right && in_array($report->status_id, $edit_actions)) {
                            echo $report->report_action_link;
                        } elseif ($access_inclusion_view && in_array($report->status_id, $view_actions)) {
                            echo $report->report_action_link;
                        } elseif ($access_inclusion_view && in_array($report->status_id, array(1, 3))) {
                            // received/load correction 
                            echo $report->report_action_link;
                        } elseif ($view_validation && in_array($report->status_id, $validation_actions)) {
                            echo $report->report_action_link;
                        } elseif ($access_validation && in_array($report->status_id, $validation_actions)) {
                            echo $report->report_action_link;
                        }
                        ?>
                    </td>
                    <td>
                    <?php
                        //if ($this->UserAuth->isAdmin() || (!empty($getgroupalias) && !in_array('DAMSreporting', $getgroupalias))) {
						if ($perm->hasWrite(array('controller' => 'Report', 'action' => 'rejectReport'))){
                            if ($report->status_id != 1 && $report->status_id != 2) {
                                $umbrella_running = in_array($report->report_id, $sub_reports) && ($report->status->stage == 'PROCESSING');
                                if (($report->status_id < 5 OR $report->status_id == 19 OR $report->status_id == 100) AND!$umbrella_running) {
                                    echo $this->Form->create(null, ['url' => '/damsv2/report/reject-report', 'id' => 'rej_report']);
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
                                       'onclick'  => 'if(confirm("You are about to reject this report, are you sure you want to proceed?") == false)return false;'
                                   ]);
                                   echo $this->Form->end();
                                }
                            }
                        }
						if ($perm->hasWrite(array('controller' => 'Report', 'action' => 'delete'))){
							if ($report->status_id == 1 || $report->status_id == 2) {
								echo $this->Form->create(null,['url' => '/damsv2/report/delete', 'id' => 'del_report']);
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
    echo $this->Form->input('reportid', [
            'type' => 'text',
            'label'	=> false,
            'id'	=> 'ReportReportId',
    ]);
    echo $this->Form->end();
    
    echo $this->Form->create(null, ['id' => 'repeat_inclusion', 'url'=>'/damsv2/report/repeatLastInclusion']);
    echo $this->Form->input('Report.report_id', [
            'type' => 'text',
            'label'	=> false,
            'id'	=> 'ReportReportId',
    ]);
    echo $this->Form->end();
    
    echo $this->Form->create(null, ['id' => 'change_status_1', 'url'=>'/damsv2/report/update-status']);
    echo $this->Form->input('Report.report_id', [
            'type' => 'text',
            'label'	=> false,
            'id'	=> 'ReportReportId',
    ]);
    echo $this->Form->input('Report.status_id', [
            'type' => 'text',
            'label'	=> false,
            'value'	=> 2,
    ]);
    echo $this->Form->end();
    
    echo $this->Form->create(null, ['id' => 'change_status_2', 'url'=>'/damsv2/report/update-status']);
    echo $this->Form->input('Report.report_id', [
            'type' => 'text',
            'label'	=> false,
            'id'	=> 'ReportReportId',
    ]);
    echo $this->Form->input('Report.status_id', [
            'type' => 'text',
            'label'	=> false,
            'value'	=> 7,
    ]);
    echo $this->Form->end();
    
    echo $this->Form->create(null, ['id' => 'change_status_12', 'url'=>'/damsv2/report/update-status']);
    echo $this->Form->input('Report.report_id', [
            'type' => 'text',
            'label'	=> false,
            'id'	=> 'ReportReportId',
    ]);
    echo $this->Form->input('Report.status_id', [
            'type' => 'text',
            'label'	=> false,
            'value'	=> 2,
    ]);
    echo $this->Form->input('Report.rejected', [
            'type' => 'text',
            'label'	=> false,
            'value'	=> true,
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

    $(".repeat_inclusion").click(function (e)
    {
        e.preventDefault();
        var id = $(e.target).attr('data-id-repeat');
        $('#repeat_inclusion #ReportReportId').val(id);
        $('#repeat_inclusion').submit();
    });

    $(".change_status_1").click(function (e)
    {
        e.preventDefault();
        var id = $(e.target).attr('data-report-id');
        $('#change_status_1 #ReportReportId').val(id);
        $('#change_status_1').submit();
    });

    $(".change_status_2").click(function (e)
    {
        e.preventDefault();
        var id = $(e.target).attr('data-report-id');
        $('#change_status_2 #ReportReportId').val(id);
        $('#change_status_2').submit();
    });

    $(".change_status_12").click(function (e)
    {
        e.preventDefault();
        var id = $(e.target).attr('data-report-id');
        $('#change_status_12 #ReportReportId').val(id);
        $('#change_status_12').submit();
    });
  
});

if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>


