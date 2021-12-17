<?php
    echo $this->Html->css('/treasury/css/redmond/jquery-ui-1.10.3.custom.css');
    echo $this->Html->script('/treasury/js/jquery-ui-1.10.3.custom.min.js');
    echo $this->Html->script('/treasury/js/autoNumeric.js');
	echo $this->Html->css('/treasury/css/datepicker');
	echo $this->Html->script('/treasury/js/bootstrap-datepicker');
	echo $this->Html->script('/treasury/js/sort-table.js');
	echo $this->Html->script('/damsv2/js/jquery.blockUI.js');
	
?>
<fieldset>
    <legend>Manual Interest fixing</legend>
    <div id="filter_selection">
	<?php echo $this->Form->create('filterform'); ?>
	<?php echo $this->Form->input('action', array(
                            'type'  => 'hidden',
                            'value' => 'filter'
                        )); ?>
   <?php echo $this->Form->input('cpty_id', array(
                            'label' => 'Counterparty*',
                            'options'=> $counterparties,
                            'empty' => '-- Any counterparty --',
							'default' => $cpty_id_filter
                        )); ?>
    <?php echo $this->Form->input('mandate_id', array(
                            'label' => 'mandate',
                            'options'=> $mandates,
                            'empty' => '-- Any mandate --',
							'default' => $mandate_id_filter
                        )); ?>
	<div class='submit'>
	<?php echo $this->Form->submit('Search',array(
		'class' => 'btn btn-primary',
		'id' => 'search_trn',
		'disabled' => true,
		'div'		=> false,
	));
	?>&nbsp;&nbsp;&nbsp;<?php
	echo $this->Html->link('Export all callables to XLS', array('controller' => 'treasurytransactions', 'action' => 'export_interest_fixing_list'), array('class' => 'btn', "id" => "export_all_xls", 'escape' => false));
	?>
	</div>
	<?php echo $this->Form->end(); ?>
    </div>
    <div id="selection_date">
	<?php if (!empty($cpty_id_filter) || !empty($mandate_id_filter))
	{		?>
		<?php echo $this->Form->create('selection_date'); ?>
		
		<?php echo $this->Form->input('cpty_id', array(
								'type'  => 'hidden',
								'label' => null,
								'class' => null,
								'value' => $cpty_id_filter
							)); ?>
		<?php echo $this->Form->input('mandate_id', array(
								'type'  => 'hidden',
								'label' => null,
								'class' => null,
								'value' => $mandate_id_filter
							)); ?>
		<?php echo $this->Form->input('fixing_date', array(
								'label' => 'Fixing Date*',
								'class' => "hasDatepicker",
							)); ?>
	   <?php echo $this->Form->input('capitalisation_date', array(
								'label' => 'Capitalisation Date',
								'class' => "hasDatepicker",
							)); ?>

        <?php
            echo $this->Form->end();
        ?>
		<?php echo $this->Form->create('fixing'); ?>
		    <?php echo $this->Form->input('action', array(
					'type'  => 'hidden',
					'label' => null,
					'class' => null,
					'value' => 'saving'
				)); ?>
			<?php echo $this->Form->input('fixing_date', array(
					'type' => 'hidden',
					'label' => null,
					'value' => $fixing_date,
				)); ?>
			<?php echo $this->Form->input('capitalisation_date', array(
					'type' => 'hidden',
					'label' => null,
					'value' => $capitalisation_date,
				));
	}
				?>
        <table id="selectCall" class="table table-bordered table-striped table-hover table-condensed js-sort-table">
            <thead>
                <tr>
                    <th>
					<a href="#" id="group_select">Select</a>
					</th>
                    <th>TRN</th>
                    <th>State</th>
                    <th>DI</th>
                    <th>Commencement date</th>
                    <th>Amount</th>
                    <th>Currency</th>
                    <th>Compartment</th>
                    <th>Last Fixing date</th>
                    <th>Accrued Interest</th>
                    <th>Interest Capitalisation</th>
                </tr>
            </thead>
            <tbody class="freeRow">
            <?php
			function fix_date($date)
			{
				if (strpos($date, '/') !== false)
				{
					$date = explode('/', $date);
					$date = $date[2].'-'.$date[1].'-'.$date[0];
				}
				return $date;
			}
			foreach ($transactions as $transaction): ?>
                <tr >
                    <td>
						<?php
						$error = array();
						if (empty($transaction['Transaction']['scheme']))
						{
							$error[] = 'The scheme is empty';
						}
						if (empty($transaction['Transaction']['interest_rate']))
						{
							$error[] = 'The interest is empty';
						}
						if ((!empty($fixing_date))&&(strtotime(fix_date($transaction['Transaction']['commencement_date'])) > strtotime($fixing_date) ))
						{
							$error[] = 'The commencement date has to be before the fixing date';
						}
						echo $this->Form->input("tr_".$transaction['Transaction']['tr_number'].".selected", array(
							'type' => 'checkbox',
							'label'	=> false,
							'div'	=> false,
							'CHECKED'	=> (empty($error))? 'CHECKED' : false,
							'disabled' => !empty($error),
						));
						echo $this->Form->input("tr_".$transaction['Transaction']['tr_number'].".tr_number", array(
							'type' => 'hidden',
							'label'	=> false,
							'div'	=> false,
							'value'	=> $transaction['Transaction']['tr_number'],
						));
						?>
                    </td>
                    <td class="text-right"><?php
					echo UniformLib::uniform($transaction['Transaction']['tr_number'], 'tr_number');

					if (!empty($error))
					{
						echo '<i class="icon-warning-sign"></i><div class="error_trn">Issues with the TRN '.$transaction['Transaction']['tr_number'].':<br />'.implode('<br />', $error).'</div>';
					}
					?></td>
                    <td class="text-right"><?php echo UniformLib::uniform($transaction['Transaction']['tr_state'], 'tr_state') ?></td>
                    <td class="text-right"><?php echo UniformLib::uniform($transaction['Instruction']['instr_num'], 'instr_num') ?></td>
                    <td><?php echo UniformLib::uniform($transaction['Transaction']['commencement_date'], 'commencement_date') ?></td>
                    <td class="text-right"><?php echo UniformLib::uniform($transaction['Transaction']['amount'], 'amount') ?></td>
                    <td><?php echo UniformLib::uniform($transaction['AccountA']['ccy'], 'ccy') ?></td>
                    <td><?php echo UniformLib::uniform($transaction['Compartment']['cmp_name'], 'cmp_name') ?></td>
                    <td><?php echo UniformLib::uniform($transaction['Transaction']['fixing_date'], 'date') ?></td>
                    <td><?php echo $this->Form->input('tr_'.$transaction['Transaction']['tr_number'].'.fixing_interest', array(
							'type' => 'text',
							'label'	=> false,
							'div'	=> false,
							'class'	=> 'amount',
							'value'	=> $fixing_interests[ $transaction['Transaction']['tr_number'] ],
						)); ?></td>
                    <td><?php echo $this->Form->input('tr_'.$transaction['Transaction']['tr_number'].'.capitalisation_interest', array(
							'type' => 'text',
							'label'	=> false,
							'div'	=> false,
							'class'	=> 'amount',
							'value'	=> $capitalisation_interests[ $transaction['Transaction']['tr_number'] ],
						)); ?></td>
				</tr>
            <?php endforeach ?>
            </tbody>
        </table>
        <br><br>
		<?php echo $this->Form->submit('save',array(
			'class' => 'btn btn-primary',
			'div'	=> false,
		));
		$export_link_options = array('class' => 'btn', "id" => "export_xls", 'escape' => false, 'download', 'disabled' => false);
		if (count($transactions) < 1)
		{
			$export_link_options = array('class' => 'btn disabled', "id" => "export_xls", 'escape' => false, 'disabled' => true);
		}
		echo $this->Html->link('Export to XLS', array('controller' => 'treasurytransactions', 'action' => 'export_interest_fixing_list', "mandate_id" => $mandate_id_filter, "ctpy_id" => $cpty_id_filter), $export_link_options);
		echo $this->Html->link('Cancel', array('controller' => 'treasurytransactions', 'action' => 'manual_fixing'), array('class' => 'btn'));

		echo $this->Form->end();
        ?>
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
	var all_selected = true;
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
		
		/*$('#selection_dateFixingDate, #selection_dateCapitalisationDate').change(function(e)
		{
			$.blockUI();
			$('#selection_dateManualFixingForm').submit();
		});*/
		
		$('#group_select').mouseup(function(e)
		{
			e.preventDefault();
			if (all_selected)
			{
				all_selected = false;
				//unselect all checkboxes/ all trn
			}
			else
			{
				all_selected = true;
			}
			$('input[type=checkbox]').prop("checked", all_selected);
		});

		$('#selection_dateFixingDate, #selection_dateCapitalisationDate').on("change", function(e)
		{
			e.preventDefault();
			$("#error_fixing").remove();
			var date = $(e.target).val();
			if ( ! date_string_valid(date))
			{
				$(e.target).val("");
			}
			else
			{
				//date is valid, check if inside interval
				date = $('#selection_dateFixingDate').val();
				date = new Date(date);
				date.setHours(1);
				/*if ((min_date != null) && (date.getTime() < min_date.getTime()))
				{
					show_msg_fixing_date("Fixing date cannot be lower than the commencement date", $(e.target));
					$(e.target).val("");
				}
				else
				{
					if ((max_date != null) && (date.getTime() > max_date.getTime()))
					{
						show_msg_fixing_date("Fixing date cannot be after the maturity date of the called deposit", $(e.target));
						$(e.target).val("");
					}
				}*/
				var capi_date = $("#selection_dateCapitalisationDate").val();
				if ((date_string_valid(capi_date)))
				{
					capi_date = new Date(capi_date);
					capi_date.setHours(1);
					if (capi_date < date)
					{
						show_msg_capitalisation_date("Capitalisation date cannot be before the Fixing date", $(e.target));
						$(e.target).val("");
					}
					else
					{
						submit_dates();
					}
				}
				else
				{
					submit_dates();
				}
			}
		});
		
	});
	function submit_dates()
	{
		$.blockUI();
		$('#selection_dateManualFixingForm').submit();
	}
	function date_string_valid(date_string)
	{
		if ((date_string.indexOf("/") != -1) || (date_string.indexOf("\\") != -1))
		{
			return false;
		}
		if (date_string == "")
		{
			return false;
		}
		var patt = new RegExp(/\d{4}-\d{1,2}-\d{1,2}$/);
		if (! patt.test(date_string))
		{
			return false;
		}
		return true;
	}
	/*function show_msg_fixing_date(msg, el)
	{
		$("#error_fixing").remove();
		el.parent().find(".error-message").remove();
		el.after('<div class="error-message" id="error_fixing">'+msg+'</div>');
	}*/

	function show_msg_capitalisation_date(msg, el)
	{
		$("#error_capi").remove();
		var el = $('#selection_dateCapitalisationDate');
		el.after('<div class="error-message" id="error_capi">'+msg+'</div>');
	}
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
.error_trn
{
	display: none;
	font-color: #f19865;
	background-color: white;
	border: 1px solid;
	position: fixed;
	width: 600px;
	height: 200px;
	left: 50%;
	top: 50%;
	margin-left: -300px;
	padding: 5px;
	text-align: left;
	font-size: 16px;
}
.icon-warning-sign:hover ~ .error_trn
{
	display: inline;
}
.icon-warning-sign
{
	margin: 3px;
}
</style>