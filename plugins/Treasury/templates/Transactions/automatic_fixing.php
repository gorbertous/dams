<?php
    echo $this->Html->css('/treasury/css/redmond/jquery-ui-1.10.3.custom.css');
    echo $this->Html->script('/treasury/js/jquery-ui-1.10.3.custom.min.js');
    echo $this->Html->script('/treasury/js/autoNumeric.js');
	echo $this->Html->css('/treasury/css/datepicker');
	echo $this->Html->script('/treasury/js/bootstrap-datepicker');
	echo $this->Html->script('/treasury/js/sort-table.js');
?>
<fieldset>
    <legend>Automatic Interest fixing</legend>
    <div id="filter_selection">
	<?php echo $this->Form->create('filterform'); ?>
	<?php echo $this->Form->input('action', array(
                            'type'  => 'hidden',
                            'value' => 'filter'
                        )); ?>
   <?php echo $this->Form->input('year', array(
                            'label' => 'Year*',
                            'options'=> $year_list,
                            'empty' => '-- Any year --',
                        )); ?>
    <?php echo $this->Form->input('month', array(
                            'label' => 'Month',
                            'options'=> $month_list,
                            'empty' => '-- Any month --',
                        )); ?>
	<?php echo $this->Form->submit('Search',array(
		'class' => 'btn btn-primary',
		'id' => 'search_trn',
		'disabled' => true,
	));
	?>
	<?php echo $this->Form->end(); ?>
    </div>
    <div id="selection_date">

		<?php echo $this->Form->create('fixing'); ?>
		    <?php echo $this->Form->input('action', array(
					'type'  => 'hidden',
					'label' => null,
					'class' => null,
					'value' => 'saving'
				)); ?>
			<?php echo $this->Form->input('year', array(
					'type' => 'hidden',
					'label' => null,
					'value' => $year_filter,
				)); ?>
			<?php echo $this->Form->input('month', array(
					'type' => 'hidden',
					'label' => null,
					'value' => $month_filter,
				)); ?>
				<?php
			if (!empty($transactions_confirmed))
			{
				?>
        <table id="selectCall" class="table table-bordered table-striped table-hover table-condensed js-sort-table">
            <thead>
                <tr>
                    <th>TRN</th>
                    <th>Counterparty</th>
                    <th>Mandate</th>
                    <th>Compartment</th>
                    <th>State</th>
                    <th>DI</th>
                    <th>Commencement date</th>
                    <th>Amount</th>
                    <th>Currency</th>
                    <th>Origin TRN</th>
                    <th>Parent TRN</th>
                    <th>Last Fixing date</th>
                </tr>
            </thead>
            <tbody>
            <?php
				foreach ($transactions_confirmed as $transaction): ?>
                <tr>
                    <td class="text-right"><?php echo UniformLib::uniform($transaction['Transaction']['tr_number'], 'tr_number') ?></td>
                    <td class="text-right"><?php echo UniformLib::uniform($transaction['Counterparty']['cpty_name'], 'cpty_name') ?></td>
                    <td class="text-right"><?php echo UniformLib::uniform($transaction['Mandate']['mandate_name'], 'mandate_name') ?></td>
                    <td class="text-right"><?php echo UniformLib::uniform($transaction['Compartment']['cmp_name'], 'cmp_name') ?></td>
                    <td class="text-right"><?php echo UniformLib::uniform($transaction['Transaction']['tr_state'], 'state') ?></td>
                    <td class="text-right"><?php echo UniformLib::uniform($transaction['Instruction']['instr_num'], 'instr_num') ?></td>
                    <td><?php echo UniformLib::uniform($transaction['Transaction']['commencement_date'], 'commencement_date') ?></td>
                    <td class="text-right"><?php echo UniformLib::uniform($transaction['Transaction']['amount'], 'amount') ?></td>
                    <td><?php echo UniformLib::uniform($transaction['AccountA']['ccy'], 'ccy') ?></td>
                    <td><?php echo UniformLib::uniform($transaction['Transaction']['original_id'], 'original_id') ?></td>
                    <td><?php echo UniformLib::uniform($transaction['Transaction']['parent_id'], 'parent_id') ?></td>
                    <td><?php echo UniformLib::uniform($transaction['Transaction']['fixing_date'], 'date') ?></td>
				</tr>
            <?php endforeach ?>
            </tbody>
        </table>
        <br><br>
		<?php echo $this->Form->submit('Automatic fixing',array(
			'class' => 'btn btn-primary',
			'div'	=> false,
		));echo $this->Html->link('Cancel', array('controller' => 'treasurytransactions', 'action' => 'automatic_fixing'), array('class' => 'btn'));

		echo $this->Form->end();
        } ?>
    </fieldset>
<div style="display:none;">
<?php


echo $this->Form->create('fixInterestajax', array('url'=>'/treasury/treasurytransactions/interest_fixing'));
echo $this->Form->input('intfixing.com_date', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('intfixing.rate_type', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('intfixing.interest_rate', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('intfixing.state', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('intfixing.tax_accrued_interest', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('intfixing.capitalisation_date', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('intfixing.interest_capitalisation', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('intfixing.tax_interest_capitalisation', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('intfixing.no_capitalisation', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('intfixing.eom_tax', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('tax_accrued_interest', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.fixingdate', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.eom', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
/*echo $this->Form->input('Transaction.tr_number', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));*/
echo $this->Form->input('Transaction.tr_num', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();
?>
</div>
<script>
	$(document).ready(function()
	{
		$('.hasDatepicker').datepicker({  format: "yyyy-mm-dd" });
		
		$('.amount').autoNumeric('init',{
			aSep: ',',aDec: '.',
			vMax: 9999999999999.99, vMin:-9999999999999.99
		});

		if ($("#filterformCptyId").val() != '')
		{
			document.getElementById('search_trn').disabled = false;
		}
		$("#filterformCptyId").change(function(e)
		{
			if ($("#filterformCptyId").val() != '')
			{
				document.getElementById('search_trn').disabled = false;
			}
		});
		
		$('#selection_dateFixingDate, #selection_dateCapitalisationDate').change(function(e)
		{
			$('#selection_dateManualFixingForm').submit();
		});
	});
</script>
<style>
.text-right{ text-align: right !important; }
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
#export_xls
{
	margin: 0 10px;
}
</style>