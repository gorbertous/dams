<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Report $report
 */
$menu_identifier = $report->template->template_type_id == 1 ? 'inclusion' : 'pdlr';
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => $menu_identifier .' dashboard',
        'url'     => ['controller' => 'Report', 'action' => $menu_identifier],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => $report->report_name,
        'url'     => ['controller' => 'Report', 'action' => 'correction', $report->report_id],
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

<h3>Load correction file</h3>
<hr>
<div class="row">
    <div class="col-12">

        <?php if (!isset($report)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong>  No correction file needed for this Report ID
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php else: ?>
            <div class="alert alert-danger alert-dismissible fade hide" role="alert">
                <strong>Error!</strong>  Some required fields are missing.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?=
            $this->Form->create(null, ['id' => 'ReportCorrectionForm', 'enctype' => 'multipart/form-data',
                'context' => [
                    'validator' => [
                        'Report' => 'default'
                    ]
                ]
            ])
            ?>
            <?= $this->Form->input('Template.type_id', ['type' => 'hidden', 'value' => $report->template->template_type_id]) ?>
            <?= $this->Form->input('Report.id', ['type' => 'hidden', 'value' => $report->report_id]) ?>    

            <div class="row">
                <div class="col-6">
                    <?=
                    $this->Form->control('portfolio_name', [
                        'label'    => 'Portfolio Name',
                        'class'    => 'form-control mr-2 my-2',
                        'type'     => 'text',
                        'disabled' => true,
                        'value'    => $report->portfolio->portfolio_name,
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <?=
                    $this->Form->control('report_name', [
                        'class'    => 'form-control mr-2 my-2',
                        'type'     => 'text',
                        'disabled' => true,
                        'label'    => 'Report ID',
                        'value'    => $report->report_name
                    ])
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <?= $this->Form->label('file', 'Correction File', ['class' => 'h6 my-2 py-2 required']); ?>
                    <?= $this->Form->control('file', ['type' => 'file', 'class'    => 'form-control-file mr-2 my-2 py-2', 'required' => true, 'label' => false]); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-6 form-inline">

                    <?=
                    $this->Form->submit('Run corrections', [
                        'class' => 'btn btn-primary form-control mr-3  my-3',
                        'name'  => 'run_corrections',
                        'id'    => 'run_corrections',
                    ])
                    ?>
                    <?php
                    $action = $report->template->template_type_id != 1 ? 'pdlr' : 'inclusion';
                    echo $this->Html->link('Cancel', ['action' => $action], ['class' => 'btn btn-danger form-control my-3']);
                    ?>

                </div>
            </div>

            <?= $this->Form->end(); ?>

        </div>
    </div>    
<?php endif ?>

<script>
    $(document).ready(function () {
        $("#ReportCorrectionForm").submit(function (event) {
            document.getElementById("run_corrections").disabled = true;
            $("#ReportCorrectionForm [required]").each(function () {
                
                error = true;
                if ($(this).attr('id')) {
                    if ($(this).val()) {
                        $(this).css('border-color', 'rgb(204, 204, 204)');
                        error = false;
                    } else {
                        $(this).css('border', '1px solid red');
                    }
                }
            });

            if (error) {
                $(".alert").show().fadeOut(4000);
                event.preventDefault();
            }

            var options = {
                type: 'post',
                success: showResponse
            };

            $("#loading").modal();

        });
    });
</script>