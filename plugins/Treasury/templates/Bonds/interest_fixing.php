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
    <legend>Interest fixing

    <div id="fixing_filters">
    <?php echo $this->Form->create('filterTransactions'); ?>
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

	<?php
		echo $this->Form->input('action', array(
			'type' => 'hidden',
			'label'	=> false,
			'div'	=> false,
			'value'	=> 'automate',
		));
	?>

    <?php echo $this->Form->submit('Automatic fixing', array("class" => "btn", "div"=> false, 'style' => 'margin-top: -10px;'));?>

    <?php echo $this->Form->end(); ?>
    </div>
    <div class="separator"></div>
    <div id="filter_selection">
        <?php echo $this->Form->create('filterform'); ?>
        <?php echo $this->Form->input('action', array(
                            'type'  => 'hidden',
                            'value' => 'filter'
                        )); ?>
                    
        TRN&nbsp;
    <?php echo $this->Form->input('tr_number', array(
                            'type'  => 'text',
                            'div'   => false,
                            'style' =>  "width:6em; height:15px;",
                            'label' => false
                        )); ?>

    <?php echo $this->Form->input('mandate_id', array(
                            'label' => false,
                            'div'   => false,
                            'options'=> $mandates,
                            'style' => "width: 20em;",
                            'empty' => '-- Any mandate --'
                        )); ?>

   <?php echo $this->Form->input('cpty_id', array(
                            'label' => false,
                            'div'   => false,
                            'style' => "width: 20em;",
                            'options'=> $counterparties,
                            'empty' => '-- Any counterparty --'
                        )); ?>
    &nbsp;<?php
	$export_link_options = array('class' => 'btn', "id" => "export_xls", "style" => "margin-top: -10px;", 'escape' => false, 'download', 'disabled' => false);
	if (count($transactions) < 1)
	{
		$export_link_options = array('class' => 'btn disabled', "id" => "export_xls", "style" => "margin-top: -10px;", 'escape' => false, 'disabled' => true);
	}
	echo $this->Html->link('<i class="icon-download"></i> Export to XLS', array('controller' => 'treasurytransactions', 'action' => 'export_interest_fixing_list', "tr_number" => $tr_number_filter, "mandate_id" => $mandate_id_filter, "ctpy_id" => $cpty_id_filter), $export_link_options); ?>
        <?php echo $this->Form->end(); ?>
    </div>
    </legend>
    <?php if (sizeof($transactions) > 0): ?>

        <?php echo $this->Form->create('intfixing', array('name'=>'fixingform')); ?>
        <table id="selectCall" class="table table-bordered table-striped table-hover table-condensed">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>TRN</th>
                    <th>State</th>
                    <th>DI</th>
                    <th>Commencement date</th>
                    <th>Amount</th>
                    <th>Currency</th>
                    <th>Scheme</th>
                    <th>Origin TRN</th>
                    <th>Parent TRN</th>
                    <th>Mandate</th>
                    <th>Compartment</th>
                    <th>Counterparty</th>
                    <th>Fixing date</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td>
						<?php echo $this->Form->input('Transaction.tr_number', array(
									'type'		=> 'radio',
									'label'		=> false,
									'value'		=> $transaction['Transaction']['tr_number'],
									'class'		=> "origin_radio",
									'id'		=> 'data[Transaction][tr_number]',
								)
							);
							
						$date  = new DateTime(str_replace('/','-',$transaction['Transaction']['commencement_date']));
						echo $this->Form->input('Transaction.tr_number', array(
									'type'		=> 'hidden',
									'label'		=> false,
									'value'		=> $date->format('Y-m-d'),
									'class'		=> "com_date",
								)
							);
                        if (($transaction['Transaction']['maturity_date'] != null) && ($transaction['Transaction']['tr_state'] == 'Called'))
                        {
                            $date_mat  = new DateTime(str_replace('/','-',$transaction['Transaction']['maturity_date'])); 
							echo $this->Form->input('Transaction.tr_number', array(
									'type'		=> 'hidden',
									'label'		=> false,
									'value'		=> $date_mat->format('Y-m-d'),
									'class'		=> "mat_date",
								)
							);
                        } ?>
                    </td>
                    <td class="text-right"><?php echo UniformLib::uniform($transaction['Transaction']['tr_number'], 'tr_number') ?></td>
                    <td class="text-right"><?php echo UniformLib::uniform($transaction['Transaction']['tr_state'], 'tr_state') ?></td>
                    <td class="text-right"><?php echo UniformLib::uniform($transaction['Instruction']['instr_num'], 'instr_num') ?></td>
                    <td><?php echo UniformLib::uniform($transaction['Transaction']['commencement_date'], 'commencement_date') ?></td>
                    <td class="text-right"><?php echo UniformLib::uniform($transaction['Transaction']['amount'], 'amount') ?></td>
                    <td><?php echo UniformLib::uniform($transaction['AccountA']['ccy'], 'ccy') ?></td>
                    <td><?php echo UniformLib::uniform($transaction['Transaction']['scheme'], 'scheme') ?></td>
                    <td class="text-right"><?php echo UniformLib::uniform($transaction['Transaction']['original_id'], 'origin_id') ?></td>
                    <td class="text-right"><?php echo UniformLib::uniform($transaction['Transaction']['parent_id'], 'parent_id') ?></td>
                    <td><?php echo UniformLib::uniform($transaction['Mandate']['mandate_name'], 'mandate_name') ?></td>
                    <td><?php echo UniformLib::uniform($transaction['Compartment']['cmp_name'], 'cmp_name') ?></td>
                    <td><?php echo UniformLib::uniform($transaction['Counterparty']['cpty_name'], 'cpty_name') ?></td>
                    <td><?php echo UniformLib::uniform($transaction['Transaction']['fixing_date'], 'date') ?></td>
                    <?php
					echo $this->Form->input('rate_type_'.$transaction['Transaction']['tr_number'], array(
						'type' => 'hidden',
						'label'	=> false,
						'div'	=> false,
						'value'	=> $transaction['Transaction']['rate_type'],
						'id'	=> 'hidden_tax_rate_type_'.$transaction['Transaction']['tr_number'],
					));
					echo $this->Form->input('interest_rate_'.$transaction['Transaction']['tr_number'], array(
						'type' => 'hidden',
						'label'	=> false,
						'div'	=> false,
						'value'	=> $transaction['Transaction']['interest_rate'],
						'id'	=> 'hidden_tax_interest_rate_'.$transaction['Transaction']['tr_number'],
					));
					echo $this->Form->input('state_'.$transaction['Transaction']['tr_number'], array(
						'type' => 'hidden',
						'label'	=> false,
						'div'	=> false,
						'value'	=> $transaction['Transaction']['tr_state'],
						'id'	=> 'state_'.$transaction['Transaction']['tr_number'],
					));
					?>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
        <br><br>
        <div class=" radio-form">
            <div class="row-fluid">
                <div class="span4">
                </div>
                <div class="span6">
                    <table id="tax_calculate">
                        <tbody>
                            <tr>
                                <td class="text">Rate Type :</td>
                                <td class="text" id="tax_rate_type"></td>
                            </tr>
                            <tr>
                                <td class="text">Interest Rate :</td>
                                <td class="text" id="tax_interest_rate"></td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                                <td><button type="button" id="recalculate_taxes" class="btn btn-primary text">Recalculate</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span4">
                    <div class="input text input-prepend">
                        <label for="intfixingValueDate">Fixing date</label>
                        <span class="add-on"><i class="icon-calendar"></i></span>
                    <?php
                        echo $this->Form->input('Transaction.fixingdate', array(
                            'label' => false,
                            'div'   => false,
                            'class' => 'span3',
                            'style' =>  'width: 8em;',
                            'required' => true,
                            'placeholder' => "YYYY-MM-DD",
                        ));
                    ?>
                    </div>
                    <span class="help-block specialhelp">(usually) last day of the month</span>
                </div>
                <div class="span4">
                    <?php
                        echo $this->Form->input('Transaction.eom', array(
                            'label' => 'Accrued Interest',
                            'class' => 'span2',
                            'placeholder' => '0.00',
                            'style' =>  'width: 11em; text-align: left;'
                        ));
                    ?>
                    <span class="help-block specialhelp minus10">interest accrued on the deposit from Commencement Date until Fixing date</span>
                </div>
                
                <div class="span3">
                    <label for="tax_accrued_interest">Tax</label>
					<?php
						echo $this->Form->input('tax_accrued_interest' , array(
								'type'		=> 'text',
								'label'		=> false,
								'value'		=> $transaction['Transaction']['tr_state'],
								'id'		=> "id_tax_accrued_interest",
								'class'		=> 'span2',
								'placeholder' => "0.00", 
								'style'		=> "width: 7em; text-align: left;",
							)
						);
						?>
                </div>
            </div>
            <div id="capitalisation" class="row-fluid">
                <div class="span4">
                    <div class="input text input-prepend">
                        <label for="intfixingCapitalisationDate">Capitalisation date</label>
                        <span class="add-on"><i class="icon-calendar"></i></span>
                    <?php
                        echo $this->Form->input('capitalisation_date', array(
                            'label' => false,
                            'div'   => false,
                            'class' => 'span3',
                            'style' =>  'width: 8em;',
                            'placeholder' => "YYYY-MM-DD",
                        ));
                    ?>                        
                    </div>
                    <span class="help-block specialhelp">(usually) first day of following month</span>
                </div>
                <div class="span4">
                    <?php
                        echo $this->Form->input('interest_capitalisation', array(
                            'label' => 'Interest Capitalisation',
                            'class' => 'span2',
                            'placeholder' => '0.00',
                            'style' =>  'width: 11em; text-align: left;'
                        ));
                    ?>
                    <span class="help-block specialhelp minus10 minus10">interest accrued on the deposit from Commencement Date until Capitalisation date</span>
                </div>
                <div class="span3">
                    <label for="tax_interest_capitalisation">Tax</label>
					<?php
						echo $this->Form->input('tax_interest_capitalisation' , array(
								'type'		=> 'text',
								'label'		=> false,
								'value'		=> $transaction['Transaction']['tr_state'],
								'id'		=> "id_tax_interest_capitalisation",
								'class'		=> 'span2',
								'placeholder' => "0.00", 
								'style'		=> "width: 7em; text-align: left;",
							)
						);
						?>
                </div>
            </div>
            <div class="row-fluid">
                <div class="offset4 span4">
                    <div class="input">
                        <label for="no_int_cap">No Interest Capitalisation</label>
				<?php		echo $this->Form->input('intfixing.no_capitalisation' , array(
					'type'		=> 'checkbox',
					'label'		=> false,
					'id'		=> "no_int_cap",
					'class'		=> "pull-right",
				)
			); ?>
                    </div>
                </div>
            </div>
			<?php
			echo $this->Form->input('intfixing.eom_tax' , array(
					'type'		=> 'hidden',
					'label'		=> false,
					'value'		=> '',
				)
			);
            echo $this->Form->submit('Fix Interest', array("class" => "btn btn-primary", 'id' => "fix_interest_submit"));
        ?>
        </div>
        <?php
            echo $this->Form->end();
        ?>
    </fieldset>
<div style="display:none;">
<?php
echo $this->Form->create('interest', array('url'=>'/treasury/treasuryajax/getInterest'));
echo $this->Form->input('Transaction.tr_number', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.date', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();

echo $this->Form->create('tax', array('url'=>'/treasury/treasuryajax/getTax'));
echo $this->Form->input('Transaction.tr_number', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.interest', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();

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
echo $this->Form->input('Transaction.tr_number', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();
?>
</div>
        <?php
            echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/jquery.dataTables.js');
            echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/dataTables.bootstrap.js');
            echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/extras/TableTools/media/js/ZeroClipboard.js');
        ?>
        <script>
            var max_date = null;
            var min_date = null;

            $(document).ready(function(){
                $('#selectCall input[type=radio]').click(function () {
                    max_date = null;
                    min_date = null;
                    var com_date = fix_date_string($(this).parent().find(".com_date").val());
                    var comDate = new Date(com_date);
                    comDate.setHours(1);
                    if (date_valid(comDate))
                    {
                        min_date = comDate;
                    }

                    if ($(this).parent().find(".mat_date").length > 0)//if maturity date is not null
                    {
                        var mat_date = fix_date_string($(this).parent().find(".mat_date").val());
                        var matDate = new Date(mat_date);
                        matDate.setHours(1);
                        if (date_valid(matDate))
                        {
                            max_date = matDate;
                        }
                    }
                    var commencement_date = comDate.setHours( comDate.getHours() + 24 );
                    $('#TransactionFixingdate').datepicker("option", "minDate", comDate);
                    $('#intfixingCapitalisationDate').datepicker("option", "minDate", comDate);
                    
            
                    $('#TransactionFixingdate').datepicker("option", "maxDate", max_date);
                    $('#intfixingCapitalisationDate').datepicker("option", "maxDate", max_date);
                
                    $("#TransactionFixingdate").datepicker("option", "dateFormat", "yy-mm-dd");
                });

                if ($("#intfixingCapitalisationDate").length > 0)// it does not appear
                {
                    document.getElementById('intfixingCapitalisationDate').required = false;
                }
                $("#no_int_cap").click(function(){

                    $("#capitalisation").toggle();
                    make_capitalisation_date_mandatory();
                    $("#intfixingCapitalisationDate, #intfixingInterestCapitalisation, #id_tax_interest_capitalisation").val("");
                });

                $('#TransactionEom, #intfixingInterestCapitalisation').autoNumeric('init',{
                    aSep: ',',aDec: '.', vMin: -9999999999999.99, vMax: 9999999999999.99
                });

                $('#TransactionFixingdate, #intfixingCapitalisationDate').datepicker(
                    { dateFormat: "yy-mm-dd" }
                );
                $('#TransactionFixingdate').on("change", function(e)
                {
                    $("#error_fixing").remove();
                    var date = fix_date_string($(e.target).val());
                    if ( ! date_string_valid(date))
                    {
                        $(e.target).val("");
                        $("#TransactionEom").val("");
                        $("#id_tax_accrued_interest").val("");
                    }
                    else
                    {
                        //date is valid, check if inside interval
                        date = new Date(date);
                        date.setHours(1);
                        if ((min_date != null) && (date.getTime() < min_date.getTime()))
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
                        }
                        var capi_date = fix_date_string($("#intfixingCapitalisationDate").val());
                        if ((date_string_valid(capi_date)))
                        {
                            capi_date = new Date(capi_date);
                            capi_date.setHours(1);
                            if (capi_date < date)
                            {
                                show_msg_fixing_date("Capitalisation date cannot be before the Fixing date", $(e.target));
                                $(e.target).val("");
                            }
                        }
                    }
                });

                $('#intfixingCapitalisationDate').on("change", function(e)
                {
                    if (!$('#capitalisation').is(':hidden'))
                    {
                        $("#error_capi").remove();
                        var date = fix_date_string($(e.target).val());
                        if ( ! date_string_valid(date))
                        {
                            $(e.target).val("");
                            $("#intfixingInterestCapitalisation").val("");
                            $("#id_tax_interest_capitalisation").val("");
                        }
                        else
                        {
                            //date is valid, check if inside interval
                            date = new Date(date);
                            date.setHours(1);
                            if ((min_date != null) && (date.getTime() < min_date.getTime()))
                            {
                                show_msg_capitalisation_date("Capitalisation date cannot be lower than the commencement date", $(e.target));
                                $(e.target).val("");
                            }
                            else
                            {
                                if ((max_date != null) && (date.getTime() >= max_date.getTime()))
                                {
                                    show_msg_capitalisation_date("Capitalisation date cannot be after the maturity date of the called deposit", $(e.target));
                                    $(e.target).val("");
                                }
                            }
                            
                            var fixing_date = fix_date_string($('#TransactionFixingdate').val());
                            if (date_string_valid(fixing_date))
                            {
                                fixing_date = new Date(fixing_date);
                                fixing_date.setHours(1);
                                if (fixing_date > date)
                                {
                                    show_msg_capitalisation_date("Capitalisation date cannot be before the Fixing date", $(e.target));
                                    $(e.target).val("");
                                }
                            }
                        }
                    }
                });

                // Updated to separate rows fixing/capitalisation
                $("#TransactionFixingdate").bind("change", calculate_fixing);
                $("#intfixingCapitalisationDate").bind("change", calculate_capitalisation);
                $("#TransactionEom").bind("change", update_fixing_tax);
                $("#intfixingInterestCapitalisation").bind("change", update_capitalisation_tax);

                $("#recalculate_taxes").click(recalculate_fixing_and_capitalisation);

                $('#intfixingInterestFixingForm').submit(function (e)
                {
					e.preventDefault();
                    document.getElementById('fix_interest_submit').disabled = true;
					ajax_submit();
                });
				
				function ajax_submit()
				{
					$('#fixInterestajaxInterestFixingForm #intfixingComDate').val( $('#intfixingInterestFixingForm #intfixingComDate').val() );
					$('#fixInterestajaxInterestFixingForm #intfixingRateType').val( $('#tax_rate_type').text() );
					$('#fixInterestajaxInterestFixingForm #intfixingInterestRate').val( $("#intfixingInterestFixingForm #intfixingInterestRate").val() );
					$('#fixInterestajaxInterestFixingForm #intfixingState').val( $("#intfixingInterestFixingForm #intfixingState").val() );
					$('#fixInterestajaxInterestFixingForm #intfixingTaxAccruedInterest').val( $("#intfixingInterestFixingForm #intfixingTaxAccruedInterest").val() );
					$('#fixInterestajaxInterestFixingForm #intfixingCapitalisationDate').val( $("#intfixingInterestFixingForm #intfixingCapitalisationDate").val() );
					$('#fixInterestajaxInterestFixingForm #intfixingInterestCapitalisation').val( $("#intfixingInterestFixingForm #intfixingInterestCapitalisation").val() );
					$('#fixInterestajaxInterestFixingForm #intfixingTaxInterestCapitalisation').val( $("#intfixingInterestFixingForm #intfixingTaxInterestCapitalisation").val() );
					$('#fixInterestajaxInterestFixingForm #intfixingNoCapitalisation').val( $("#intfixingInterestFixingForm #no_int_cap").is(':checked') );
					$('#fixInterestajaxInterestFixingForm #intfixingEomTax').val( $("#id_tax_interest_capitalisation").val() );
					$('#fixInterestajaxInterestFixingForm #TransactionFixingdate').val( $("#intfixingInterestFixingForm #TransactionFixingdate").val() );
					$('#fixInterestajaxInterestFixingForm #TransactionEom').val( $("#intfixingInterestFixingForm #TransactionEom").val() );
					$('#fixInterestajaxInterestFixingForm #fixInterestajaxTaxAccruedInterest').val( $("#intfixingInterestFixingForm #TransactionEom").val() );
					$('#fixInterestajaxInterestFixingForm #TransactionTrNumber').val( $('.selected').find('#intfixingTrNumber').val() );
					var data = $('#fixInterestajaxInterestFixingForm').serialize();
					$.ajax(
					{
						async:true,
						data:data,
						dataType: "json",
						success:function (data, textStatus)
						{
							document.location('/treasury/treasurytransactions/interest_fixing');
						},
						type:"post",
						url:"/treasury/treasurytransactions/interest_fixing"
					});
				}

                document.onkeypress = function(e) {
                    if(e.keyCode && e.keyCode == 116) return false;//prevent refreshing the page and repost automatic fixing
                }

                $('#id_tax_accrued_interest, #id_tax_interest_capitalisation').autoNumeric('init',{aSep: ',',aDec: '.', vMin:0, mDec:2, vMax: 9999999999999.99});
            });
            
            function recalculate_fixing_and_capitalisation()
            {
                calculate_fixing();
                calculate_capitalisation();
            }
            
            function calculate_fixing()
            {
                update_interest_fixing();
            }

            function calculate_capitalisation()
            {
                update_capitalisation_fixing();
            }

            function update_interest_fixing()
            {
                /*if ($("#tax_rate_type").text() == 'Fixed')
                {
                    if ($("#TransactionFixingdate").val() != "")
                    {*/
						$('#interestInterestFixingForm #TransactionTrNumber').val( tr_number );
						$('#interestInterestFixingForm #TransactionDate').val( $("#TransactionFixingdate").val() );
						var data = $('#interestInterestFixingForm').serialize();
                        $.ajax(
                        {
                            async:true,
                            data:data,
                            dataType: "json",
                            success:function (data, textStatus)
                            {
                                $("#TransactionEom").autoNumeric('set', data.interest);
                                update_fixing_tax();
                            },
                            type:"post",
                            url:"/treasury/treasuryajax/getInterest"
                        });
                    /*}
                    else
                    {
                        $("#TransactionEom").val('');
                        $("#id_tax_accrued_interest").val('');
                    }
                }
                else if ($("#tax_rate_type").text() == 'Floating')
                {
                    update_fixing_tax();
                }*/
            }


            function update_capitalisation_fixing()
            {
                /*if ($("#tax_rate_type").text() == 'Fixed')
                {
                    if ($("#intfixingCapitalisationDate").val() != "")
                    {*/
						$('#interestInterestFixingForm #TransactionTrNumber').val( tr_number );
						$('#interestInterestFixingForm #TransactionDate').val( $("#intfixingCapitalisationDate").val() );
						var data = $('#interestInterestFixingForm').serialize();
                        $.ajax(
                        {
                            async:true,
                            data:data,
                            dataType: "json",
                            success:function (data, textStatus)
                            {
                                $("#intfixingInterestCapitalisation").autoNumeric('set', data.interest);
                                update_capitalisation_tax();
                            },
                            type:"post",
                            url:"/treasury/treasuryajax/getInterest"
                        });
                    /*}
                    else
                    {
                        $("#intfixingInterestCapitalisation").val('');
                        $("#id_tax_interest_capitalisation").val('');
                    }
                }
                else if ($("#tax_rate_type").text() == 'Floating')
                {
                    update_fixing_tax();
                }*/
            }
            
            function update_fixing_tax()
            {
                if ($("#TransactionEom").val() != "")
                {
					$('#taxInterestFixingForm #TransactionTrNumber').val( tr_number );
					$('#taxInterestFixingForm #TransactionInterest').val( $("#TransactionEom").val() );
					var data = $('#taxInterestFixingForm').serialize();
                    $.ajax(
                    {
                        async:true,
                        data:data,
                        dataType: "json",
                        success:function (data, textStatus)
                        {
                            $("#id_tax_accrued_interest").autoNumeric('set', data.tax);
                        },
                        type:"post",
                        url:"/treasury/treasuryajax/getTax"
                    });
                }
                else
                {
                    $("#id_tax_accrued_interest").val('');
                }
            }


            function update_capitalisation_tax()
            {
                if ($("#intfixingInterestCapitalisation").val() != "")
                {
					$('#taxInterestFixingForm #TransactionTrNumber').val( tr_number );
					$('#taxInterestFixingForm #TransactionInterest').val( $("#intfixingInterestCapitalisation").val() );
					var data = $('#taxInterestFixingForm').serialize();
                    $.ajax(
                    {
                        async:true,
                        data:data,
                        dataType: "json",
                        success:function (data, textStatus)
                        {
                            $("#id_tax_interest_capitalisation").autoNumeric('set', data.tax);
                        },
                        type:"post",
                        url:"/treasury/treasuryajax/getTax"
                    });
                }
                else
                {
                    $("#id_tax_interest_capitalisation").val('');
                }
            }

            function make_capitalisation_date_mandatory()
            {
                if ($("#intfixingCapitalisationDate").length > 0)
                {
                    document.getElementById('intfixingCapitalisationDate').required = false;
                    if ($("#capitalisation:visible").length > 0)
                    {
                        if(document.getElementById('no_int_cap').checked)
                        {
                            document.getElementById('intfixingCapitalisationDate').required = false;
                        }
                        else
                        {
                            document.getElementById('intfixingCapitalisationDate').required = true;
                        }
                    }
                }
            }

            String.prototype.count=function(c) { 
                var result = 0, i = 0;
                for(i;i<this.length;i++)if(this[i]==c)result++;
                return result;
            };

            function date_string_valid(date_string)
            {
                if (date_string.count('-') != 2)
                {
                    return false;
                }
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

                date_string = fix_date_string(date_string);
                var date = new Date(date_string);
                if (Object.prototype.toString.call(date) === '[object Date]')
                {
                    if ( isNaN( date.getTime() ) )
                    {
                        return false;
                    }
                    else
                    {
                        if (is_possible_date(date, date_string))
                        {
                            return true;
                        }
                    }
                }
                else
                {
                    return false;
                }
            }

            function is_possible_date(date, date_string)
            {
                var date_explode = date_string.split("-");
                var formattedMonth = date_explode[1]-1;// - 1 for js format
                var formattedYear = date_explode[0];
                var formattedDay = date_explode[2];

                if (date.getFullYear() != formattedYear || date.getMonth() != formattedMonth || date.getDate() != formattedDay)
                {
                    return false;
                }
                else
                {
                    return true;
                }
            }

            function date_valid(date)
            {
                if (Object.prototype.toString.call(date) === '[object Date]')
                {
                    if ( isNaN( date.getTime() ) )
                    {
                        return false;
                    }
                    else
                    {
                        return true;
                    }
                }
                else
                {
                    return false;
                }
            }


            function fix_date_string(date_string)// for IE
            {
                var date_explode = date_string.split("-");
                if (date_explode.length == 3)
                {
                    if (date_explode[1].length < 2)
                    {
                        date_explode[1] = "0"+date_explode[1];
                    }
                    if (date_explode[2].length < 2)
                    {
                        date_explode[2] = "0"+date_explode[2];
                    }
                    date_string = date_explode[0]+'-'+date_explode[1]+'-'+date_explode[2];
                }
                return date_string;
            }

            function show_msg_fixing_date(msg, el)
            {
                $("#error_fixing").remove();
                el.parent().find(".error-message").remove();
                el.after('<div class="error-message" id="error_fixing">'+msg+'</div>');
            }

            function show_msg_capitalisation_date(msg, el)
            {
                $("#error_capi").remove();
                el.parent().find(".error-message").remove();
                el.after('<div class="error-message" id="error_capi">'+msg+'</div>');
            }

        </script>
    <?php else: ?>
    <div class="well">There are no callable deposits / rollovers.</div>
    <?php endif ?>
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