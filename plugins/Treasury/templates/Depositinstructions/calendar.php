<fieldset>
<legend>Calendar of Maturing Transactions</legend>
<div id="form" class = "well span11">
	<?php echo $this->Form->create('Instruction', array("url" => $this->here)) ?>
		<div class="span12 noleftmargin">
			<?php
				echo $this->Form->input('mandate_ID',
					array(
						'label'     => false,
						'class'		=> 'span12',
						'div'		=> 'span4',
						'type'		=> 'select',
						'options'   => $mandates_list,
						'empty'     => __('-- All mandates --'),
						'default'	=> null
					)
				);
			?>
			<?php
				echo $this->Form->input('month',
					array(
						'label'     => false,
						'class'		=> 'span12',
						'div'		=> 'span2',
						'type'		=> 'select',
						'options'   => $month_list,
						'empty'     => __('-- Month --'),
						'required'	=> 'required',
						'default'	=> $month
					)
				);
			?>
			<?php
				echo $this->Form->input('year',
					array(
						'label'     => false,
						'class'		=> 'span12',
						'div'		=> 'span2',
						'type'		=> 'select',
						'options'   => $year_list,
						'empty'     => __('-- Year --'),
						'required'	=> 'required',
						'default'	=> $year
					)
				);
			?>
			<div class="span4">
				<?php
					echo $this->Form->submit('Refresh',
						array(
							'id' 	=> 'refreshButton',
							'type' 	=> 'submit',
							'class' => 'btn btn-primary pull-right',
							'div'	=> false,//array('class' => array('input submit'))
						)
					);
				?>
			</div>
		</div>

	<?php echo $this->Form->end() ?>
</div>
</fieldset>
<div id="calendarContent">
<?php if(empty($transactions)): ?>
	No transaction found for this period / mandate
<?php else: ?>
	<table id="calendarTransactions" class="table table-bordered table-striped table-hover table-condensed">
	<thead><tr>
		<th>TRN</th>
		<th>Status</th>
		<th>Mandate</th>
		<th>Compartment</th>
		<th>Maturity Date</th>
		<th>Currency</th>
		<th>Principal Amount</th>
		<th>Interest Amount</th>
		<th>Tax</th>
	</tr></thead>
	<tbody>
<?php 
	foreach($transactions as $key=>$transaction): 
?>

			<tr>
				<td><?php print UniformLib::uniform($transaction['Transaction']['tr_number'], 'tr_number') ?></td>
				<td><?php print UniformLib::uniform($transaction['Transaction']['tr_state'], 'tr_state') ?></td>
				<td><?php print UniformLib::uniform($transaction['Mandate']['mandate_name'], 'mandate_name') ?></td>
				<td><?php print UniformLib::uniform($transaction['Compartment']['cmp_name'], 'cmp_name') ?></td>
				<td><?php 
						if(!empty($transaction['Transaction']['maturity_date'])) print UniformLib::uniform($transaction['Transaction']['maturity_date'], 'maturity_date');
						elseif(!empty($transaction['Transaction']['indicative_maturity_date'])) print UniformLib::uniform($transaction['Transaction']['indicative_maturity_date'], 'indicative_maturity_date');
				 ?></td>
				<td><?php print UniformLib::uniform($transaction['AccountA']['ccy'], 'ccy') ?></td>
				<td style="text-align: right;"><?php print UniformLib::uniform($transaction['Transaction']['amount'], 'amount') ?></td>
				<td style="text-align: right;"><?php print UniformLib::uniform($transaction['Transaction']['total_interest'], 'total_interest') ?></td>
				<td style="text-align: right;"><?php print UniformLib::uniform($transaction['Transaction']['tax_amount'], 'tax_amount') ?></td>
			</tr>
<?php endforeach ?>
	</tbody></table>
<?php endif ?>
</div>
<style>
		#calendarTransactions tr.separ{ background: transparent; }
		#calendarTransactions tr.separ td{ 
			padding-bottom: 0; font-size: 16px; background: #f4f4f4; text-indent: -9999px; 
		}
		#form{ padding-bottom: 10px; }		
		#form .btn{ margin-left: 5px; }
</style>
<script>
		$(document).ready(function(e){
			if($('#InstructionCalendarForm').length){
				var buttonZone = $('#refreshButton').parent();
				$('#refreshButton').remove();

				$('#InstructionCalendarForm select').bind('change', function(e){
					e.preventDefault();
					$('#calendarContent').html('Loading...');
					$(this).parents('form').submit();
				});

				//copy bt
				$(buttonZone).append(' <div class="btn btn-primary pull-right noprint" id="copyBT">Copy to Excel</div> ');				
				$('#copyBT').bind('click', function(e){
					e.preventDefault();
					copyTableToClipboad($('#calendarTransactions'));					
					return false;
				});

				//print bt
				$(buttonZone).append(' <div class="btn btn-primary pull-right noprint" id="printBT">Print</div> ');				
				$('#printBT').bind('click', function(e){
					e.preventDefault();
					window.print();		
					return false;
				});
			}
		});

		function copyTableToClipboad(table){
			/*var out = '';
			$('tr', table).each(function(i, tr){
				$('td', tr).each(function(j, td){
					out+=$(td).text();
					out+="\t";
				});
				out+="\n";
			});
			console.log(out);*/

			try{
			   	var textRange = document.body.createTextRange(); 
				textRange.moveToElementText(document.getElementById('calendarTransactions')); 
				textRange.execCommand("Copy");
				alert('The table has been copied to the clipboard. You can now paste it in any Excel spreadsheet.');
			}catch(e){
				alert('Due to compatibility issues, copy to Excel function is only available from Internet Explorer');
			}			
		}
</script>