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
						<?php echo $this->Form->input('Transaction.tr_number', array(
									'type'		=> 'radio',
									'label'		=> false,
									'value'		=> $transaction['Transaction']['tr_number'],
									'class'		=> "origin_radio",
									'id'		=> 'data[Transaction][tr_number]',
								)
							);
							?>
						<?php echo $this->Form->input('Transaction.amount', array(
									'type'		=> 'hidden',
									'label'		=> false,
									'value'		=> UniformLib::uniform($transaction['Transaction']['amount'], 'amount'),
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
								'label'	=> 'Requested amount',
								'class'	=> 'span4 text-right',
								'div'=>'input-append input input-with-ccy-add-on',
								'after'=>'<div class="add-on pos-absolute-top-right ccy"></div>'
							));
						?>

					</div>
					<div class="span7">
						<div class="input">
							<label for="#calldepositFull">Call full amount and terminate deposit</label>
							<?php echo $this->Form->input('Transaction.full', array(
									'type'		=> 'checkbox',
									'label'		=> false,
									'value'		=> "full",
									'id'		=> "calldepositFull"
								)
							);
							?>
						</div>
					</div>
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
						"label" => "Call Deposit",
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