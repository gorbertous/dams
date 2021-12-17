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
    <legend> Reopen reinvestment </legend>
    <?php if (sizeof($reopenableReinvs) > 0): ?>
        
        
        <?php $this->BootstrapTables->displayRawsAndLinks('selectReinvestment', $reopenableReinvs, array('reinvestments'), array('reopen'), array('Reopen'), array('btn-success'), array('icon-folder-open'), 'reinv_group', true); ?>
      
</fieldset>

        <?php
            echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/jquery.dataTables.js');
            echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/dataTables.bootstrap.js');
            echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/extras/TableTools/media/js/ZeroClipboard.js');
            echo $this->Html->script('/treasury/js/ColumnFilter/media/js/jquery.dataTables.columnFilter.js');
        ?>
        <script>
            $(document).ready(function(){
                $("#selectReinvestment").dataTable({}).columnFilter({});
                $('#selectReinvestment tr').click(function () {
                        //$(this).find('td input:radio').prop('checked', true);
                        $(this).find('td input:radio:checked');
                        $('#selectReinvestment tr').removeClass("active");
                        $(this).addClass("active");
                });
            });
        </script>
    <?php else: ?>
    <div class="well">There are no reinvestments which can be reopened.</div>
    <?php endif ?>
    
