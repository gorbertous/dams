<?php
    echo $this->Html->css('/treasury/css/redmond/jquery-ui-1.10.3.custom.css');
    echo $this->Html->css('/treasury/css/dataTableSort');
    echo $this->Html->script('/treasury/js/jquery-ui-1.10.3.custom.min.js');
    echo $this->Html->script('/treasury/js/autoNumeric.js');
    echo $this->Html->script('/treasury/js/form_ajax.js');
    echo $this->Html->script('/treasury/js/transactions.js');
    // echo $this->Html->css('/treasury/css/radio-fx');
    // echo $this->Html->script('/treasury/js/radio-fx');
    echo $this->Html->script('/treasury/js/radio-fx-replacement');
?>
<style> .text-right{ text-align: right !important; }</style>
<fieldset>
	<legend>Break Deposit</legend>

	<?php if (sizeof($transactions) > 0): ?>

        <?php echo $this->Form->create('breakdeposit'); ?>
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
                    <th>Maturity date</th>
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
									'id'		=> "data[Transaction][tr_number]",
									'class'		=> "origin_radio",
									
								)
							);
							echo $this->Form->input('Transaction.tr_number', array(
									'type'		=> 'hidden',
									'label'		=> false,
									'value'		=> UniformLib::uniform($transaction['Transaction']['amount'], 'amount'),
									'class'		=> "amount",
									
								)
							);
?></td>
                    <td class="text-right"><?php echo UniformLib::uniform($transaction['Transaction']['tr_number'], 'tr_number') ?></td>
                    <td class="text-right"><?php echo UniformLib::uniform($transaction['Instruction']['instr_num'], 'instr_num') ?></td>
                    <td><?php echo UniformLib::uniform($transaction['Transaction']['scheme'], 'scheme') ?></td>
                    <td class="text-right"><?php echo UniformLib::uniform($transaction['Transaction']['original_id'], 'original_id') ?></td>
                    <td class="text-right"><?php echo UniformLib::uniform($transaction['Transaction']['parent_id'], 'parent_id') ?></td>
                    <td><?php echo UniformLib::uniform($transaction['Mandate']['mandate_name'], 'mandate_name') ?></td>
                    <td><?php echo UniformLib::uniform($transaction['Compartment']['cmp_name'], 'cmp_name') ?></td>
                    <td><?php echo UniformLib::uniform($transaction['Counterparty']['cpty_name'], 'cpty_name') ?></td>
                    <td class="text-right"><?php echo UniformLib::uniform($transaction['Transaction']['amount'], 'amount') ?></td>
                    <td><?php echo UniformLib::uniform($transaction['AccountA']['ccy'], 'ccy') ?></td>
                    <td>
                    	<?php $date = new DateTime(str_replace('/','-',$transaction['Transaction']['maturity_date'])); ?>
			<?php			echo $this->Form->input('Transaction.tr_number', array(
									'type'		=> 'hidden',
									'label'		=> false,
									'value'		=> $date->format('d'),
									'class'		=> "mat_day",
									
								)
							);
							echo $this->Form->input('Transaction.tr_number', array(
									'type'		=> 'hidden',
									'label'		=> false,
									'value'		=> $date->format('m'),
									'class'		=> "mat_mon",
									
								)
							);
							echo $this->Form->input('Transaction.tr_number', array(
									'type'		=> 'hidden',
									'label'		=> false,
									'value'		=> $date->format('Y'),
									'class'		=> "mat_year",
									
								)
							);
?>
                    	<?php echo UniformLib::uniform($transaction['Transaction']['maturity_date'], 'maturity_date') ?>
                    </td>
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
					echo $this->Form->input('Transaction.value_date', array(
						'label'	=> false,
						'div'	=> false,
						'class'	=> 'span4',
						'data-date-format' => 'dd/mm/YYYY',
					));
				?>
				</div>
				<div class="input text">
					<?php
						echo $this->Form->input('Transaction.reqamount', array(
							'label'	=> 'Withdrawal amount',
							'class'	=> 'span2 text-right',
						));
					?>
				</div>

				<?php
					echo $this->Form->input('Transaction.accountA_IBAN', array(
						'label'     => 'Principal account',
						'class'	=> 'span4',
						'options'   => array(''),
						'empty' 	=> __('-- Select an account --'),
					));
				?>
				<?php
					echo $this->Form->input('Transaction.accountB_IBAN', array(
						'label'     => 'Interest account',
						'class'	=> 'span4',
						'options'   => array(''),
						'empty' 	=> __('-- Select an account --'),
					));
				?>
				<?php
					echo $this->Form->end(array(
						"label" => "Break Deposit",
						"class" => "btn btn-primary",
					));
				?>
				<?php
					$this->Js->get('#selectCall input:radio')->event('click',
						$this->Js->request(
							array(
								'controller'	=>	'treasuryajax',
								'action'		=>	'getaccountsbytrn'
								),
							array(
								'update'		=>	'#TransactionAccountAIBAN',
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
				<?php
					$this->Js->get('#selectCall input:radio')->event('click',
						$this->Js->request(
							array(
								'controller'	=>	'treasuryajax',
								'action'		=>	'getaccountsbytrn'
								),
							array(
								'update'		=>	'#TransactionAccountBIBAN',
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
        	</div>
        </div>

		<?php
		 echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/jquery.dataTables.js');
		 echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/dataTables.bootstrap.js');
		 echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/extras/TableTools/media/js/ZeroClipboard.js');
		?>
		<script>
			$(document).ready(function(){
				/*$("#selectCall").dataTable({
					"bPaginate": true,
				 	"bLengthChange": true,
				    "aLengthMenu": [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
				    "iDisplayLength" : 5,
				    "bFilter": false,
				    "bInfo": false
				});*/

				$('#TransactionValueDate').datepicker({dateFormat: "dd/mm/yy"});
				$('#TransactionReqamount').autoNumeric('init',{
                    aSep: ',',aDec: '.',
                    vMax: 9999999999999.99, vMin:-9999999999999.99
                });

				$("#breakdepositBreakdepositForm input[type=radio]").click(function(){
					var mat_year = parseInt($(this).parent().parent().find(".mat_year").val());
					var mat_month = parseInt($(this).parent().parent().find(".mat_mon").val()) - 1;
					var mat_day = parseInt($(this).parent().parent().find(".mat_day").val());

					var matDate = new Date(mat_year, mat_month, mat_day);

					$('#TransactionValueDate').datepicker("option", "maxDate", matDate);
					//$("#TransactionValueDate").datepicker("option", "dateFormat", "yy-mm-dd");

					$('#breakdepositBreakdepositForm').submit(function (e)
					{
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
				});
			});
		</script>
		<?php else: ?>
    		<div class="well">There are no confirmed deposits.</div>
    	<?php endif ?>
</fieldset>
