<?php
    echo $this->Html->css('/treasury/css/redmond/jquery-ui-1.10.3.custom.css');
    echo $this->Html->css('/treasury/css/dataTableSort');
    echo $this->Html->script('/treasury/js/jquery-ui-1.10.3.custom.min.js');
    echo $this->Html->script('/treasury/js/autoNumeric.js');
    echo $this->Html->script('/treasury/js/form_ajax.js');
    echo $this->Html->script('/treasury/js/transactions.js');
?>
<style type="text/css">
	input[type = text] {
		width: 80px;
    }
</style>
<fieldset>
    <legend>Correct Transaction</legend>
    <?php if (sizeof($transactions) > 0): ?>
        <?php //echo $this->Form->create('Transaction', array('default' => false)); ?>
       	<?php $this->BootstrapTables->displayRawsAsInputs('selectTransaction', $transactions, 'radio', 'Transaction', 'tr_number', true); ?>
        <div id="correct"></div>
        <?php
            /*echo $this->Form->end(array(
                "label" => "Correct Transaction",
                "class" => "btn btn-primary",
            ));*/
        ?>
</fieldset>

        <?php
            echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/jquery.dataTables.js');
            echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/dataTables.bootstrap.js');
            echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/extras/TableTools/media/js/ZeroClipboard.js');
            echo $this->Html->script('/treasury/js/ColumnFilter/media/js/jquery.dataTables.columnFilter.js');
        ?>
        <script>
            $(document).ready(function(){
                $("#selectTransaction").dataTable({}).columnFilter({});
                $('#selectTransaction tr').click(function () {
                        //$(this).find('td input:radio').prop('checked', true);
                        $(this).find('td input:radio:checked');
                        $('#selectTransaction tr').removeClass("active");
                        $(this).addClass("active");
                });
            });
        </script>
    <?php else: ?>
    <div class="well">There are no transactions which can be corrected. You may want to correct a transaction manually, if such then please contact the administrator.</div>
    <?php endif ?>

<?php
	$this->Js->get('#selectTransaction input:radio')->event('change',
		$this->Js->request(
			array(
				'controller'	=>	'treasurytransactions',
				'action'		=>	'newdeposits', true
				),
			array(
				'update'		=>	'#correct',
				'async' 		=> 	true,
				'method' 		=> 	'post',
				'dataExpression'=>	true,
				'data'=> $this->Js->serializeForm(
					array(
						'isForm' => true,
						'inline' => true
						)
					)
				)
			)
		);
?>