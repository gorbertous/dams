<?php
    echo $this->Html->css('/treasury/css/redmond/jquery-ui-1.10.3.custom.css');
    echo $this->Html->css('/treasury/css/dataTableSort');
    echo $this->Html->script('/treasury/js/jquery-ui-1.10.3.custom.min.js');
    echo $this->Html->script('/treasury/js/autoNumeric.js');
    echo $this->Html->script('/treasury/js/form_ajax.js');
    echo $this->Html->script('/treasury/js/transactions.js');
    //echo $this->Html->css('/treasury/css/radio-fx');
    //echo $this->Html->script('/treasury/js/radio-fx');
    echo $this->Html->script('/treasury/js/radio-fx-replacement');
?>
<fieldset>
    <legend>Automatic interest fixing

    <div id="fixing_filters">
    <?php echo $this->Form->create('filterTransactions', array('url'=>'/treasury/treasurytransactions/automatic_interest_fixing')); ?>
                        <span>
    Month :
    <?php echo $this->Form->input('Filter.month', array(
                            'label' => false,
                            'div'   => false,
                            'options'=> $months,
                            'multiple'=> false
                        )); ?>
    Year :
    <?php echo $this->Form->input('Filter.year', array(
                            'label' => false,
                            'div'   => false,
                            'options'=> $years,
                            'multiple'=> false
                        )); ?>

    <input type="hidden" name="action" value="automate" />

    <?php echo $this->Form->submit('Automatic fixing', array("class" => "btn", "div"=> false, 'style' => 'margin-top: -10px;'));?>

    <?php echo $this->Form->end(); ?>
    </div>
    <div class="separator"></div>
    </legend>


        <?php
            echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/jquery.dataTables.js');
            echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/dataTables.bootstrap.js');
            echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/extras/TableTools/media/js/ZeroClipboard.js');
        ?>

    <style>.text-right{ text-align: right !important; }
    .text
    {
        font-size: 14px;
        font-weight: normal;
        line-height: 20px;
    }
    #tax_calculate
    {
        margin-bottom: 2em;
    }
    #recalculate_taxes
    {
        margin-left: 2em;
    }
    form .input > label
    {
        width: 140px;
    }
    .help-block.specialhelp
    {
        margin-left: 140px;
    }
    #filterTransactionsInterestFixingForm
    {
        float: right;
        font-weight : normal;
        font-size: 12px;
    }
    #filterTransactionsInterestFixingForm .btn
    {
        font-size: 12px:
    }
    legend
    {
        height: 110px;
    }
    select{
        height: 25px;
        width: 150px;
        font-size: 12px;
    }
    #fixing_filters, #filter_selection
    {
        float: right;
        font-weight : normal;
        font-size: 12px;
    }
    .separator
    {
        clear: both;
    }
    </style>
    <script>
        $(document).ready(function(){
            $("#filterformTrNumber, #filterformMandateId, #filterformCptyId").change(function(e)
            {
                $("#filterformInterestFixingForm").submit();
            });
<?php
		if (count($transactions) < 1)
		{
			?>
			$("#export_xls").mousedown(function(e)
			{
				e.preventDefault();
				e.stopPropagation();
				alert("There are no transaction for fixing with this Mandate and Counterparty");
			});
			<?php
		}
			?>
        });
    </script>