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
	<legend>Call Deposit</legend>

	<?php if (sizeof($transactions) > 0): ?>

        <?php echo $this->Form->create('calldeposit', array('default' => false)); ?>
        <table id="selectCall" class="table table-bordered table-striped table-hover table-condensed">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>TRN</th>
                    <th>DI</th>
                    <th>Scheme</th>
                    <th>Origin TRN</th>
                    <th>Parent TRN</th>
                    <th>Mandate</th>
                    <th>Compartment</th>
                    <th>Counterparty</th>
                    <th>Amount</th>
                    <th>Currency</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td>
					   <?php
						echo $this->Form->input('Transaction.tr_number',
							array(
								'type'		=> 'radio',
								'label'		=> false,
								'div'		=> false,
								'class'		=> 'origin_radio',
								'value'		=> $transaction['Transaction']['tr_number'],
								'id'		=> 'data[Transaction][tr_number]',
								//'style'		=> "display: none;",
							)
						);
						echo $this->Form->input('Transaction.amount',
							array(
								'type'		=> 'hidden',
								'label'		=> false,
								'div'		=> false,
								'class'		=> 'origin_radio',
								'value'		=> UniformLib::uniform($transaction['Transaction']['amount'], 'amount'),
								'id'		=> 'data[Transaction][tr_number]',
							)
						);
						?>
                    </td>
                    <td class="tr_number"><?php echo UniformLib::uniform($transaction['Transaction']['tr_number'], 'tr_number') ?></td>
                    <td class="instr_num"><?php echo UniformLib::uniform($transaction['Instruction']['instr_num'], 'instr_num') ?></td>
                    <td class="scheme"><?php echo UniformLib::uniform($transaction['Transaction']['scheme'], 'scheme') ?></td>
                    <td class="original_id"><?php echo UniformLib::uniform($transaction['Transaction']['original_id'], 'original_id') ?></td>
                    <td class="parent_id"><?php echo UniformLib::uniform($transaction['Transaction']['parent_id'], 'parent_id') ?></td>
                    <td class="mandate_name"><?php echo UniformLib::uniform($transaction['Mandate']['mandate_name'], 'mandate_name') ?></td>
                    <td class="cmp_name"><?php echo UniformLib::uniform($transaction['Compartment']['cmp_name'], 'cmp_name') ?></td>
                    <td class="cpty_name"><?php echo UniformLib::uniform($transaction['Counterparty']['cpty_name'], 'cpty_name') ?></td>
                    <td class="amount"><?php echo UniformLib::uniform($transaction['Transaction']['amount'], 'amount') ?></td>
                    <td class="ccy"><?php echo UniformLib::uniform($transaction['AccountA']['ccy'], 'ccy') ?></td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
        <br><br>
        <div class="row-fluid radio-form">
        	<div class="span12">
        		<div class="input text input-prepend">
					<label for="calldepositValueDate">Value date</label>
					<span class="add-on"><i class="icon-calendar"></i></span>
				<?php
				
					echo $this->Form->input('Transaction.tr_number', array(
						'label'	=> false,
						'div'	=> false,
						'type'	=> 'text',
						'id'	=> 'tr_number_form',
						'style'	=> 'display:none;',
					));
					echo $this->Form->input('Transaction.value_date', array(
						'label'	=> false,
						'div'	=> false,
						'class'	=> 'span4',
						'data-date-format'	=> 'dd/mm/yyyy',
					));
				?>
				</div>
				<div class="row-fluid">
        			<div class="span5">
						<?php
							echo $this->Form->input('Transaction.reqamount', array(
								'label'	=> 'Principal called<br /><small>Excluding interest and tax</small>',
								'class'	=> 'span4 text-right',
								'div'=>'input-append input input-with-ccy-add-on',
								'after'=>'<div class="add-on pos-absolute-top-right ccy"></div>'
							));
						?>
					</div>
				</div>
				<div class="row-fluid">
        			<div class="span5">
						<?php
							echo $this->Form->input('Transaction.interest_amount', array(
								'label'	=> 'Interest amount<br /><small>Total interest at value date</small>',
								'class'	=> 'span4 text-right',
								'div'=>'input-append input input-with-ccy-add-on',
								'after'=>'<div class="add-on pos-absolute-top-right ccy"></div>'
							));
						?>
					</div>
				</div>
				<div class="row-fluid">
        			<div class="span5">
						<?php
							echo $this->Form->input('Transaction.tax_amount', array(
								'label'	=> 'tax amount<br /><small>Total tax at value date</small>',
								'class'	=> 'span4 text-right',
								'div'=>'input-append input input-with-ccy-add-on',
								'after'=>'<div class="add-on pos-absolute-top-right ccy"></div>'
							));
						?>
					</div>
				</div>
				<?php
					echo $this->Form->input('Transaction.accountA_IBAN', array(
						'label'     => 'Principal account',
						'class'	=> 'span4',
						'options'   => $account_list,
						'type'		=> 'select',
						'empty' 	=> __('-- Select an account --'),
					));
				?>
				<?php
					echo $this->Form->input('Transaction.accountB_IBAN', array(
						'label'     => 'Interest account',
						'class'	=> 'span4',
						'options'   => $account_list,
						'type'		=> 'select',
						'empty' 	=> __('-- Select an account --'),
					));
				?>
				
				<div class="fixed_size_info">
				<span class="fixed_size_text">Amount to be repaid:</span><span id="repaid_amount">-</span>
				</div>
				<div class="fixed_size_info">
				<span class="fixed_size_text">Amount to be reinvested:</span><span id="reinvested_amount">-</span>
				</div>

				<?php
					echo $this->Form->end(array(
						"label" => "Call Deposit",
						"class" => "btn btn-primary",
					));
				?>
        	</div>
        </div>

		<?php
		 echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/jquery.dataTables.js');
		 echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/dataTables.bootstrap.js');
		 echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/extras/TableTools/media/js/ZeroClipboard.js');
		?>
<div style="display:none;">
<?php
echo $this->Form->create('accounts', array('url'=>'/treasury/treasuryajax/getaccountsbytrn'));
echo $this->Form->input('Transaction.tr_number', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();
echo $this->Form->create('calculations', array('url'=>'/treasury/treasuryajax/get_call_calculations'));
echo $this->Form->input('Transaction.tr_number', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.principal', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.interest', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.tax', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();

echo $this->Form->create('calculation_interest', array('url'=>'/treasury/treasuryajax/getInterestCallDeposit'));
echo $this->Form->input('Transaction.tr_number', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.date', array(//end date
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();

echo $this->Form->create('calculation_tax', array('url'=>'/treasury/treasuryajax/getTax'));
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
?>
</div>
<style>

.fixed_size_info
{
	margin: 8px;
}
.fixed_size_text
{
	font-weight: bold;
	font-size: 14px;
	margin: 8px;
}
</style>
<script>
	$(document).ready(function(){
		$('#TransactionValueDate').datepicker({ dateFormat: "dd/mm/yy" });
		$('#TransactionReqamount').autoNumeric('init',{
			aSep: ',',aDec: '.',
			vMax: 9999999999999.99, vMin:-9999999999999.99
		});

		/*$("#selectCall").dataTable({
			"bPaginate": true,
			"bLengthChange": true,
			"aLengthMenu": [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
			"iDisplayLength" : 5,
			"bFilter": false,
			"bInfo": false
		});*/
		$('#calldepositCalldepositForm').submit(function (e)
		{
			//$('#calldepositCalldepositForm #TransactionTrNumber').val( $('.radio-line.selected .tr_number').text() );
			/*var input_tr_number = $('<input type="hidden" name="data[Transaction][tr_number]" />');
			input_tr_number.val( $('.radio-line.selected .tr_number').text() );
			console.log(input_tr_number);
			$(this).append(input_tr_number);*/
			$('#tr_number_form').val($('.radio-line.selected .tr_number').text());
			prevent_double_submit();
		});

		function prevent_double_submit()
		{
			$('input[type = submit]')[0].disabled = true;
			/*function reenable()
			{
				$('input[type = submit]')[0].disabled = false;
			}
			setTimeout(reenable, 2000);*/
		}
		$('body').bind('refreshSubcontent', function(e){
			var ccy = $('#calldepositCalldepositForm tr.selected td.ccy').text();
			$('.input-with-ccy-add-on .add-on').text(ccy);
		});

		$('#selectCall .btn-radio-select').click(function (e)
		{
			$('#accountsCalldepositForm #TransactionTrNumber').val( $('.radio-line.selected .tr_number').text() );
			$('#tr_number_form').val($('.radio-line.selected .tr_number').text());//for validation
			get_interest_tax();
			var data = $('#accountsCalldepositForm').serialize();
			$.ajax({
				type: "POST",
				url: '/treasury/treasuryajax/getaccountsbytrn',
				dataType: 'text', 
				data: data,
				async:true,
				success:function (data, textStatus) {
					$('#TransactionAccountAIBAN').html(data);
					$('#TransactionAccountBIBAN').html(data);
				}
			});
		});
		
		$('#TransactionReqamount, #TransactionInterestAmount, #TransactionTaxAmount').change(function ()
		{
			//update amount to be repaid and amount ot be reinvested
			$("#calculationsCalldepositForm #TransactionTrNumber").val( $('.radio-line.selected .tr_number').text() );
			$("#calculationsCalldepositForm #TransactionPrincipal").val( $('#TransactionReqamount').val() );
			$("#calculationsCalldepositForm #TransactionInterest").val( $('#TransactionInterestAmount').val() );
			$("#calculationsCalldepositForm #TransactionTax").val( $('#TransactionTaxAmount').val() );
			var data = $("#calculationsCalldepositForm").serialize();
			$.ajax({
				type: "POST",
				url: '/treasury/treasuryajax/get_call_calculations',
				dataType: 'json', 
				data: data,
				async:true,
				success:function (data, textStatus) {
					if (typeof data.repaid !== 'undefined')
					{
						$('#repaid_amount').html(data.repaid);
						$('#reinvested_amount').html(data.reinvested);
					}
				}
			});
		});
		
		$('#TransactionValueDate').change(function(e)
		{
			get_interest_tax();
		});
		
		function get_interest_tax()
		{
			if ($('#TransactionValueDate').val() != '')
			{
				$("#calculation_interestCalldepositForm #TransactionTrNumber").val( $('.radio-line.selected .tr_number').text() );
				$("#calculation_interestCalldepositForm #TransactionDate").val( $('#TransactionValueDate').val() );
				var data = $("#calculation_interestCalldepositForm").serialize();
				$.ajax({
					type: "POST",
					url: '/treasury/treasuryajax/getInterestCallDeposit',
					dataType: 'text', 
					data: data,
					async:true,
					success:function (data, textStatus) {
						$('#TransactionInterestAmount').html(data);

						$('#calculation_taxCalldepositForm #TransactionTrNumber').val( $('.radio-line.selected .tr_number').text() );
						$('#calculation_taxCalldepositForm #TransactionInterest').val( data );
						var data = $('#calculation_taxCalldepositForm').serialize();
						$.ajax({
							type: "POST",
							url: '/treasury/treasuryajax/getTax',
							dataType: 'text', 
							data: data,
							async:true,
							success:function (data, textStatus) {
								if (typeof data.tax !== 'undefined' )
								{
									$('#TransactionTaxAmount').html(data.tax);
								}
							}
						});
					}
				});
			}
		}
		
	});
</script>
<?php else: ?>
	<div class="well">There is no Callable Deposit</div>
<?php endif ?>
</fieldset>
<style>
#selectCall td.tr_number,
#selectCall td.instr_num,
#selectCall td.original_id,
#selectCall td.parent_id,
#selectCall td.amount{ text-align: right !important; }
#TransactionReqamount{ width: 153px !important; }
</style>