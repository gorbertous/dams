<?php
      //echo $this->Html->script('/treasury/js/bootstrap-datepicker');
      echo $this->Html->script('/treasury/js/autoNumeric.js');
      echo $this->Html->css('/treasury/css/dataTableSort');
      // echo $this->Html->css('/treasury/css/radio-fx');
      // echo $this->Html->script('/treasury/js/radio-fx');
      echo $this->Html->script('/treasury/js/radio-fx-replacement');
?>
<div id="register" class="">
      <?php echo $this->Form->create('Transaction') ?>
      <div id="registerConfSelect" class="">
            <?php if(sizeof($transactions) > 0): ?>
            <table id="selectTRNToRegister" class="table table-bordered table-striped table-hover table-condensed">
                  <thead>
                        <th> Select </th>
                        <th> DI </th>
                        <th> TRN </th>
                        <th> Mandate </th>
                        <th> Compartment </th>
                        <th> Value Date </th>
                        <th> Amount </th>
                  </thead>
                  <tbody>
                        <?php foreach ($transactions as $tr): ?>
                        <tr>
                              <td>
									<?php
									echo $this->Form->input('Transaction.tr_number',
										array(
											'type'		=> 'radio',
											//'label'		=> false,
											'div'		=> false,
											'class'		=> "origin_radio",
											'data-value' => $tr['Transaction']['tr_number'],
											'options'		=> $tr['Transaction']['tr_number'],
										)
									);
									echo $this->Form->input('Transaction.original_id',
										array(
											'type'		=> 'hidden',
											'label'		=> false,
											'div'		=> false,
											'id'		=> 'origin_trn',
											'value'		=> UniformLib::uniform($tr['Transaction']['original_id'], 'original_id'),
										)
									);
									echo $this->Form->input('amount',
										array(
											'type'		=> 'hidden',
											'label'		=> false,
											'div'		=> false,
											'class'		=> 'amount',
											'value'		=> UniformLib::uniform($tr['Transaction']['amount'], 'amount'),
										)
									);
									echo $this->Form->input('currency',
										array(
											'type'		=> 'hidden',
											'label'		=> false,
											'div'		=> false,
											'class'		=> 'currency',
											'value'		=> UniformLib::uniform($tr['AccountA']['ccy'], 'ccy'),
										)
									);
									
									?>
                              </td>
                              <td><?php echo UniformLib::uniform($tr['Transaction']['instr_num'], 'instr_num') ?></td>
                              <td><?php echo UniformLib::uniform($tr['Transaction']['tr_number'], 'tr_number') ?></td>
                              <td><?php echo UniformLib::uniform($tr['Mandate']['mandate_name'], 'mandate_name') ?></td>
                              <td><?php echo UniformLib::uniform($tr['Compartment']['cmp_name'], 'cmp_name') ?></td>
                              <td style="text-align:right;"><?php echo UniformLib::uniform($tr['Transaction']['commencement_date'], 'commencement_date') ?></td>
                              <td style="text-align:right;">
                                    <?php echo UniformLib::uniform($tr['Transaction']['amount'], 'amount')." ".UniformLib::uniform($tr['AccountA']['ccy'], 'ccy'); ?>
                              </td>
                        </tr>
                  <?php endforeach; ?>
            </tbody>
      </table>
      <br><br>
      <div class="well radio-form">
            <div id="ifselected"></div><hr>
            <div class="input text">
                  <label for="TransactionPrincipal">Principal amount:<span style="color:red;">*</span></label>
					<?php
					echo $this->Form->input('Transaction.principal',
						array(
							'type'		=> 'text',
							'label'		=> false,
							'class'		=> 'span3 disabled',
							'value'		=> UniformLib::uniform($tr['AccountA']['ccy'], 'ccy'),
							'readonly'	=> true,
							'id'		=> 'TransactionPrincipal',
							'required'	=> true,
						)
					);
					?>
                  <span class="currency"></span>
            </div>

            <div class="input text">
                  <label for="TransactionInterest">Interest amount:<span style="color:red;">*</span><br /><span style="font-size: 11px; font-style: italic;">Total interest of the called deposit</span></label>
                  <?php
					echo $this->Form->input('Transaction.total_interest',
						array(
							'type'		=> 'text',
							'label'		=> false,
							'class'		=> 'span3',
							'value'		=> "0.00",
							'id'		=> 'TransactionInterest',
							'required'	=> true,
						)
					);
					?>
				  <span class="currency"></span>
            </div>

            <div class="input text">
                  <label for="TransactionTax">Tax amount:<span style="color:red;">*</span></label>
				   <?php
					echo $this->Form->input('Transaction.tax_amount',
						array(
							'type'		=> 'text',
							'label'		=> false,
							'class'		=> 'span3',
							'value'		=> "0.00",
							'id'		=> 'TransactionTax',
							'required'	=> true,
						)
					);
					?>
                  <span class="currency"></span>
            </div>
            <p>Repayment amount: <span id="repay_amount"></span> <span class="currency"></span></p>
            <?php echo $this->Form->submit('Register Confirmation', array(
                  'class' => 'btn btn-primary',
                  'div'   => false,
                  )) ?>
            </div>
            <?php echo $this->Form->end(); ?>
      <?php else: ?>
      <div class="well alert-info">There are no instructed calls.</div>
<?php endif; ?>
</div>
</div>
<div id="registerConfResult"></div>

<?php
      echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/jquery.dataTables.js');
      echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/dataTables.bootstrap.js');
      echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/extras/TableTools/media/js/ZeroClipboard.js');
      echo $this->Html->css('/treasury/css/redmond/jquery-ui-1.10.3.custom.css');
      echo $this->Html->script('/treasury/js/jquery-ui-1.10.3.custom.min.js');
?>

      <script type="text/javascript">
        var complete_trn = false;
        var tr_number_called = '';
        var tr_number_call = '';
        
        function new_amount()
        {
            if (complete_trn)
            {
				value = parseFloat($("#TransactionPrincipal").autoNumeric('get'));
				$.getJSON('/treasury/treasuryajax/callInterest/'+tr_number_called+'/'+value, function (data)
				{
					  $("#error_amount").remove();
					  $("#error_missing_data").remove();
					  if (data.hasOwnProperty('success'))
					  {
						  $("#TransactionInterest").autoNumeric('set', data.success);
						   // $("#TransactionInterest").val(data.success);
							new_interest();
					  }
					  else
					  {
							if (data.hasOwnProperty('error'))
							{
								  var error = $('<span style="color: red;" id="error_amount">'+data.error+'</span>');
								  $("#TransactionPrincipal").parent().append(error);
							}
							else
							{
								  var error = $('<span style="color: red;" id="error_missing_data">'+data.empty+'</span>');
								  $("#TransactionInterest").parent().append(error);
							}
					  }
				});
            }
        }
        
        
		function new_interest()
		{
			if (complete_trn)
			{
				value = parseFloat($("#TransactionInterest").autoNumeric('get'));
				$.get('/treasury/treasuryajax/callTax/'+tr_number_called+'/'+value, function (data)
				{
					$("#TransactionTax").autoNumeric('set', data);
					//$("#TransactionTax").val(data);
					update_repay_amount();
				});
			}
		}
	
		function update_repay_amount()
		{
		var repay_amount = 0;
		var can_submit = true;
		if(parseFloat($("#TransactionPrincipal").autoNumeric('get'))) {
			  value = parseFloat($("#TransactionPrincipal").autoNumeric('get'));
			  repay_amount = repay_amount + value;
		}
		if(parseFloat($("#TransactionInterest").autoNumeric('get'))) {
			  value = parseFloat($("#TransactionInterest").autoNumeric('get'));
			  repay_amount = repay_amount + value;
		}
		if(parseFloat($("#TransactionTax").autoNumeric('get'))) {
			  value = parseFloat($("#TransactionTax").autoNumeric('get'));
			  repay_amount = repay_amount - value;
		}
		$("#repay_amount").autoNumeric('set', repay_amount);
		if(can_submit){
			  $("#TransactionCallconfForm input:submit").show();
		}else{
			  $("#TransactionCallconfForm input:submit").hide();
		}
		}


		function get_original_tr(tr_number_call)
		{

            $.ajax({
                  url: '/treasury/treasuryajax/callOriginalTrn/'+tr_number_call,
                  async: false,
                  success: function (data){
                                    data = JSON.parse(data);
                        tr_number_called = data.success;
                        /*
                         *     complete_trn if the interest rate is filled and the rate type is fixed
                         */
                        complete_trn = ((data.data.interest_rate != '') && (data.data.rate_type == 'Fixed'));
                }
            });

            return tr_number_called;
		}

		$(document).ready(function() {
			
			$('label[for="TransactionTrNumber0"]').remove();
			$('#TransactionPrincipal').autoNumeric('init');
			$('#TransactionCallconfForm input:radio').change(function(e){
				$("#error_amount").remove();
				$("#error_missing_data").remove();

				tr_number_called = get_original_tr( $(e.currentTarget).attr('data-value') );
				tr_number_call = $(e.currentTarget).attr('data-value');

				$('span.currency').text($(this).parent().find('.currency').val());
				$('#TransactionPrincipal').val( $(this).parent().find('.amount').val());
				$('#TransactionPrincipal').autoNumeric('set', $(this).parent().find('.amount').val());
				new_amount();
				var repay_amount = 0;
				if(parseFloat($("#TransactionPrincipal").autoNumeric('get'))) {
					  value = parseFloat($("#TransactionPrincipal").autoNumeric('get'));
					  repay_amount = repay_amount + value;
				}
				if(parseFloat($("#TransactionInterest").autoNumeric('get'))) {
					  value = parseFloat($("#TransactionInterest").autoNumeric('get'));
					  repay_amount = repay_amount + value;
				}
				if(parseFloat($("#TransactionTax").autoNumeric('get'))) {
					  value = parseFloat($("#TransactionTax").autoNumeric('get'));
					  repay_amount = repay_amount - value;
				}
				$("#repay_amount").autoNumeric('set', repay_amount);
			});
			
			$("#TransactionPrincipal").change(new_amount);
			$("#TransactionInterest").change(new_interest);
			$('.well input:text').change(function(){
			  update_repay_amount();
			});
			$('#TransactionPrincipal, #repay_amount').autoNumeric('init',{aSep: ',',aDec: '.', mDec: 2, vMin: -9999999999999.99, vMax: 9999999999999.99});
			$('#TransactionInterest').autoNumeric('init',{aSep: ',',aDec: '.', mDec: 2, vMin: -9999999999999.99, vMax: 9999999999999.99});
			$('#TransactionTax').autoNumeric('init',{aSep: ',',aDec: '.', mDec: 2, vMin: 0, vMax: 9999999999999.99});
			
			$('#TransactionCallconfForm table input').change(function (e)
			{
				var tr_number = $(e.currentTarget).attr('data-value');
				$('input[name="data[Transaction][tr_number]"]').val( tr_number );
				$('#getoriginaltrncallCallconfForm #TransactionTrNumber').val( tr_number );
				$('#getoriginaltrncallCallconfForm #TransactionOriginalId').val( $(e.currentTarget).parent('td').find('#origin_trn').val() );
				var data = $('#getoriginaltrncallCallconfForm').serialize();
				$.ajax({
					type: "POST",
					url: '/treasury/treasuryajax/getoriginaltrncall',
					dataType: 'text', 
					data: data,
					async:true,
					success:function (data, textStatus) {
						$('#ifselected').html(data);
					}
				});
			});
		});

</script>

<div style="display:none;">
<?php

echo $this->Form->create('getoriginaltrncall', array('url'=>'/treasury/treasuryajax/getoriginaltrncall'));
echo $this->Form->input('Transaction.tr_number', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('Transaction.original_id', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();
?>
</div>
