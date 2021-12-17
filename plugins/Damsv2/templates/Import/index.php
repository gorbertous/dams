
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
        'title'   => 'Edit',
        'url'     => ['controller' => 'Import', 'action' => 'index'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
<h3>Edit</h3>
<hr>
<div class="alert alert-danger alert-dismissible" style="margin-top:10px;">
	<b>Warning</b>: Do not click several times on the "Save" button or refresh the page while processing modifications!
</div>
<?php if (!isset($table)): ?>
    <?= $this->Form->create(null, ['id' => 'import', 'enctype' => 'multipart/form-data']) ?>

    <div class="form-group row form-inline">
        <label class="col-sm-2 col-form-label required">Product</label>
        <div class="col-6">
            <?= $this->Form->select('Product.product_id', $products, [
                'label'    => false, 
                'class'    => 'form-control ml-2 my-2',
                'id' => 'ProductId',
                'empty'    => '-- Product --',
                'required' => true,
            'style' => 'width:220px;',
            ]);
            echo $this->Form->input('Portfolio.portfolio_empty', [
                'label' => false, 
                'type'  => 'hidden',
                'value' => '-- Portfolio --',
            ]);
            ?>
        </div>
    </div>

    <div class="form-group row form-inline">
        <label class="col-sm-2 col-form-label required">Portfolio</label>
        <div class="col-6">
            <?= $this->Form->select('Import.portfolio_id', [],[
                'empty'    => '-- Portfolio --', 
                'required' => true,
                'class'    => 'w-50 form-control ml-2 my-2',
                'id' => 'ImportPortfolioId',
                'onchange' => '$("#periodId").hide()',
            'style' => 'width:220px;',
            ])
            ?>
        </div>
    </div>
    <div class="form-group row form-inline">
        <label class="col-sm-2 col-form-label required">Entity</label>
        <div class="col-6">
            <?= $this->Form->select('Import.sheet',[], [
                'empty'    => '-- Entity --',
                'required' => true,
                'class'    => 'form-control ml-2 my-2',
                'id' => 'ImportSheet',
                'onchange' => 'displayReportDiv(this);',
            'style' => 'width:220px;',
            ])
            ?>
        </div>

    </div>
    <div class="form-group row form-inline" style="display:none" id="periodId" name="periodId">
        <label class="col-sm-2 col-form-label required">Report period</label>
        <div class="col-6">
            <?= $this->Form->select('Import.quarter',  ['Q1' => 'Q1', 'Q2' => 'Q2', 'Q3' => 'Q3', 'Q4' => 'Q4', 'S1' => 'S1', 'S2' => 'S2'],[
                'class'    => 'form-control ml-2 my-2',
				'id'	=> 'ImportQuarter',
                'required' => true,
            'style' => 'width:104px;',
                ])
            ?>
            <?= $this->Form->select('Import.year', [],[
                'required' => true,
                'class'    => 'form-control ml-2 my-2',
                'empty'    => '- select a year -',
                'id'       => 'ImportYear',
            'style' => 'width:104px;',
            ])
            ?>
        </div>

    </div>
    <div class="form-group row form-inline">
        <label class="col-sm-2 col-form-label required">Type</label>
        <div class="col-6">
            <?= $this->Form->select('Import.type', $types,[
                'empty'    => '-- Type --', 
                'class'    => 'form-control ml-2 my-2',
                'required' => true,
		'id'	   => 'ImportType',
            'style' => 'width:220px;',
            ])
            ?>
        </div>
    </div>

    <div class="form-group row form-inline">
        <label class="col-sm-2 col-form-label">Owner</label>
        <div class="col-6">
            <?= $this->Form->select('Report.owner',$owners, [
                'default'  => $owner,
                'class'    => 'form-control ml-2 my-2',
                'disabled' => true,
            'style' => 'width:220px;',
            ])
            ?>
        </div>
    </div>
    <div class="form-group row form-inline">
        <label class="col-sm-2 col-form-label required" for="ReportName">File</label>
        <div class="col-6">
             <?= $this->Form->control('Import.file', ['type' => 'file', 'label' => false, 'class' => 'form-control-file mb-3', 'required' => true]); ?>
             <?= $this->Form->hidden('Import.correction', ['value' => 0]) ?>
        </div>
    </div>
   
    <div class="row col-6 form-inline">
        <?php
		$disabled = !$perm->hasWrite(array('action' => 'index'));
		$class    = 'btn btn-primary form-control mr-3  my-3';
		if($disabled)
		{
			$class    = 'btn btn-primary form-control mr-3  my-3 disabled';
		}
		echo $this->Form->submit('Upload', [
            'class'    => $class,
            'id'       => 'submit_id',
			'disabled' => $disabled,
        ]);
        ?>
        <?= $this->Html->link('Cancel', ['action' => 'index'], ['class' => 'btn btn-danger my-3']) ?>
    </div>
      
    <?= $this->Form->end() ?>

    <div style="display:none;">
        <?php
        echo $this->Form->create(null, ['id' => 'ProductAjax','url' => '/damsv2/ajax/getPortfoliosAndUmbrellaByProduct2']);
            echo $this->Form->input('product_id', [
                'type'  => 'text',
                'label' => false,
                'id'   => 'prodid',
            ]);
            echo $this->Form->input('Portfolio.portfolio_empty', [
                'type'  => 'hidden',
                'label' => false,
                'div'   => false,
                'value' => '-- Portfolio --',
            ]);
        echo $this->Form->end();

        echo $this->Form->create(null, ['id' => 'getLastYearEditForm','url' => '/damsv2/ajax/getLastYear']);
        echo $this->Form->end();

        echo $this->Form->create(null, ['id' => 'PortfolioAjax', 'url' => '/damsv2/ajax/getTemplatesByPortfolio']);
            echo $this->Form->input('portfolio_id', [
                'type'  => 'text',
                'label' => false,
                'id'   => 'portid',
            ]);
        echo $this->Form->end();

        echo $this->Form->create(null, ['id' => 'PortfolioAjaxType', 'url' => '/damsv2/ajax/getTypeByPortfolio']);
            echo $this->Form->input('portfolio_id', [
                'type'  => 'text',
                'label' => false,
                'id'   => 'portid',
            ]);
        echo $this->Form->end();

        echo $this->Form->create(null, ['id' => 'PortfolioAjaxPeriod', 'url' => '/damsv2/ajax/getPeriodByPortfolio']);
            echo $this->Form->input('portfolio_id', [
                'type'  => 'text',
                'label' => false,
                'id'   => 'portid',
            ]);
        echo $this->Form->end();
        ?>
    </div>
  

    <script>
        function displayReportDiv() {
            if (($('#ImportSheet').val() == "B") || ($('#ImportSheet').val() == "B1"))
            {
                $('#periodId').show();
            } else
                $('#periodId').hide();
        }
        
        $(document).ready(function () {
            $('#ImportSheet').change(function (event) {
                if ($(this).val() == 'B' || $(this).val() == 'A3' || $(this).val() == 'GGE' || $(this).val() == 'H' || $(this).val() == 'CR' || $(this).val() == 'IR' || $(this).val() == 'C' || $(this).val() == 'R' || $(this).val() == 'I1' || $(this).val() == 'I2' || $(this).val() == 'D' || $(this).val() == 'E' || $(this).val() == 'EP' || $(this).val() == 'BDS' || $(this).val() == 'TBE' || $(this).val() == 'B1') {
                    $("#ImportType option[value='BK']").remove();
                    $('#ImportType').val('DATA');
                } else {
                    $("#ImportType option[value='BK']").show();
                    if ($("#ImportType option[value='BK']").length == 0)
                        $('#ImportType').append('<option value="BK">Business keys</option>');
                }
            });
            $("fieldset form").submit(function (event) {
                $("fieldset form [required]").each(function () {
                    error = true;
                    if ($(this).attr('id')) {
                        if ($(this).val()) {
                            $(this).css('border-color', 'rgb(204, 204, 204)');
                            error = false;
                        } else {
                            $(this).css('border', '1px solid red');
                            error = true;
                        }
                    }
                });

                if (error) {
                    $(".alert").show().fadeOut(4000);
                    event.preventDefault();
                }

                $("#loading").modal();


            });

            $('#ImportPortfolioId').bind('change', function () {
                var post = $('#getLastYearEditForm').serialize();
                $.ajax({
                    url: '/damsv2/ajax/getLastYear',
                    type: "POST",
                    data: post,
                }).done(function (data) {
                    $('#ImportYear').html(data);
                });
            });

            $("form").submit(function ()
            {
                document.getElementById('submit_id').disabled = true;
            });


            $('#ProductId').change(function () {
                $('#ProductAjax #prodid').val($('#ProductId').val());
                var post = $('#ProductAjax').serialize();
                $.ajax({
                    url: '/damsv2/ajax/getPortfoliosAndUmbrellaByProduct2',
                    type: "POST",
                    data: post,
                    headers: {'X-CSRF-TOKEN': '<?= $this->request->getAttribute('csrfToken') ?>'},
                }).done(function (data) {
                    $('#ImportPortfolioId').html(data);
                    $("#ImportPortfolioId").val($("#ImportPortfolioId option:first").val());
                    $("#ImportPortfolioId").trigger("change");
                });
            });


            $('#ImportPortfolioId').change(function () {
                $('#PortfolioAjax #portid').val($('#ImportPortfolioId').val());
                var post = $('#PortfolioAjax').serialize();
                $.ajax({
                    url: '/damsv2/ajax/getTemplatesByPortfolio',
                    type: "POST",
                    data: post,
                    headers: {'X-CSRF-TOKEN': '<?= $this->request->getAttribute('csrfToken') ?>'},
                }).done(function (data) {
                    $("#ImportSheet").html(data);
                });
            });


            $('#ImportPortfolioId').change(function () {
                $('#PortfolioAjaxType #portid').val($('#ImportPortfolioId').val());
                var post = $('#PortfolioAjaxType').serialize();
                $.ajax({
                    url: '/damsv2/ajax/getTypeByPortfolio',
                    type: "POST",
                    data: post,
                    headers: {'X-CSRF-TOKEN': '<?= $this->request->getAttribute('csrfToken') ?>'},
                }).done(function (data) {
                    $("#ImportType").html(data);
                });
            });


            $('#ImportPortfolioId').change(function () {
                $('#PortfolioAjaxPeriod #portid').val($('#ImportPortfolioId').val());
                var post = $('#PortfolioAjaxPeriod').serialize();
                $.ajax({
                    url: '/damsv2/ajax/getPeriodByPortfolio',
                    type: "POST",
                    data: post,
                    headers: {'X-CSRF-TOKEN': '<?= $this->request->getAttribute('csrfToken') ?>'},
                }).done(function (data) {
                    $("#ImportQuarter").html(data);
                });
            });

        });
    </script>
<?php elseif (!isset($action)): ?>
    <script>

        $(document).ready(function () {

            $("#downloadAll").mouseup(function ()
            {
                // ==UserScript==
                // @name        window.close demo
                // @include     http://vmd-sas-01/damsv2/*, http://vmu-sas-01/damsv2/*, http://vmp-sas-01/damsv2/*
                // @grant       GM_addStyle
                // ==/UserScript==
                $("#sasres a").each(function (i, item)
                {
                    item = $(item);
                    if (!item.hasClass('btn'))
                    {

                        var href = item.attr('href');
                        var url = '/damsv2/ajax/download-file/1?file=' + href;
                        try {
                            window.open(url, 'download_' + i);
                        } catch (e)
                        {
                            //console.dir(e);
                        }
                        /*$.ajax({
                         url: '/damsv2/ajax/download_file/1?file='+href,
                         });*/
                    }
                });
            });
        });
    </script>
  
        <h3>Result</h3>

        <?php if ($msgWarning == -1) : ?>
            <div class="alert alert-warning" role="alert">
                <strong>Warning!!</strong> This portfolio is CLOSED. After this modification APV will become lower than APV at closure of portfolio.
            </div>
        <?php endif ?>

        <?php if ($msgWarning == 2) : ?>
            <div class="alert alert-warning" role="alert">
                <strong>Warning!!</strong> This portfolio is CLOSED. After this modification APV will become higher than APV at closure of portfolio.
            </div>
        <?php endif ?>
        <?php if (!empty($multi_sme)) : ?>
            <div class="alert alert-warning" role="alert">
                <strong>Warning!!</strong> In case SME has multiple transactions in the portfolio, the change will apply to all of these transactions.
            </div>
        <?php endif ?>

        <?= $table ?>

        <?php
		if ($save != 0)
		{
					?>
		
        <?= $this->Form->create(null, ['id' => 'import','url' => '/damsv2/import/store']) ?>
        <?= $this->Form->hidden('Import.filename', ['value' => $filename])?>


        <?= $this->Form->hidden('Import.type', [ 'value' => $type])?>
        <?= $this->Form->hidden('Import.portfolio_id', [ 'value' => $portfolio_id])?>
        <?= $this->Form->hidden('Import.report_id', [ 'value' => $report_id])?>
        <?= $this->Form->hidden('Import.sheet', [ 'value' => $sheet])?>
		<?php
		echo $this->Form->submit('Save in database', [
			'div'   => false,
			'id'    => 'saveInDatabase',
			'class' => 'btn btn-primary',
		]);
		?>
		<?= $this->Form->end()?>
		<?php
		}
		else
		{
		?>
        <?= $this->Form->create(null, ['id' => 'import']) ?>
        <?= $this->Form->hidden('Import.filename', ['value' => $filename])?>


        <?= $this->Form->hidden('Import.type', [ 'value' => $type])?>
        <?= $this->Form->hidden('Import.portfolio_id', [ 'value' => $portfolio_id])?>
        <?= $this->Form->hidden('Import.report_id', [ 'value' => $report_id])?>
        <?= $this->Form->hidden('Import.sheet', [ 'value' => $sheet])?>
		<?= $this->Form->end()?>
		<?php } ?>

        <?php
        if (($save == 0) && (empty($bkedit)) && ($downloadAllButton == 0) && (empty($expired_to_performing_val)) && (empty($bds_support_paid_val))) {
			echo $this->Form->create(null, ['type' => 'POST', 'class' => 'form-inline', 'id' => 'filters', 'action' => '/damsv2/import/edit-action']);
			echo $this->Form->hidden('Import.type', [ 'value' => $type]);
			echo $this->Form->hidden('Import.portfolio_id', [ 'value' => $portfolio_id]);
			echo $this->Form->hidden('Import.report_id', [ 'value' => $report_id]);
			echo $this->Form->hidden('Import.sheet', [ 'value' => $sheet]);
			echo $this->Form->submit('Load correction', [
                'div'   => false,
                'id'    => 'edit_action',
                'class' => 'btn btn-primary',
            ]);
			echo $this->Form->end();
        }

        if (empty($downloadAllButton) && (empty($expired_to_performing_val)) && (empty($bds_support_paid_val))) {
            echo $this->Html->link('Cancel', '/damsv2/import', ['class' => 'btn btn-danger']);
        }

        if (!empty($expired_to_performing_val)) {
            echo $this->Html->link('Back to edit', '/damsv2/import', ['class' => 'btn btn-danger']);
        }
        echo $this->Form->end();

        if (isset($downloadAllButton) && $downloadAllButton == 1) {
            echo "<button id='downloadAll' class='btn btn-success'>Download all</button>";
        }
        ?>
    
<?php else: ?>

<?php endif ?>


