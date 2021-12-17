<?php
	echo $this->Html->script('/treasury/js/bootstrap-datepicker');
	echo $this->Html->script('/treasury/js/autoNumeric.js');
	echo $this->Html->script('/treasury/js/form_ajax.js');
	echo $this->Html->script('/treasury/js/transactions.js');
	echo $this->Html->script('/treasury/js/bootstrap-datepicker');
	echo $this->Html->css('/treasury/css/datepicker');
?>

<style>
	select { width: 100% }
</style>

<div class = "row-fluid">
	<div class = "well span6">
		<?php echo $this->Form->create('transaction', array('default'=>false)); ?>
		<?php  if(isset($fromReinv)): ?>
			<div class="alert alert-info">The selected transaction is a rollover outgoing from reinvestment : <?php echo $fromReinv; ?></div>
		<?php endif; ?>

		<?php echo $this->Form->input(
			'Transaction.mandate_ID', array(
				'label'     => 'Mandate',
				'class'		=> 'span12',
				'options'   => $mandates_list,
				'default'	=> $defaultOpts['mandate_ID'],
				'empty'     => __('-- Select a mandate --'),
				/*'required'	=> 'required',*/

				)
			);
		?>
			
		<?php echo $this->Form->input(
			'Transaction.cmp_ID', array(
				'label'		=>'Compartment',
				'class'		=> 'span12',
				'options'	=> $cmps,
				'default'	=> $defaultOpts['cmp_ID'],
				'disabled'	=> $disabledOpts['cmp_ID'],
				/*'empty' 	=> __('-- Select a compartment --'),*/

				)
			);
		?>
	
		<?php echo $this->Form->input(
			'Transaction.cpty_id', array(
				'label'		=>'Counterparty',
				'class'		=> 'span12',
				'options'	=> $cptys,
				'default'	=> $defaultOpts['cpty_ID'],
				'disabled'	=> $disabledOpts['cpty_ID'],
				/*'empty' 	=> __('-- Select a counterparty --'),*/

				)
			);
		?>
		
		<div class="span12" id="TransactionAccountsIBAN"></div>
	</div>

	<div class="well span3">
		<label for="TransactionAmount">Deposit Amount</label>
		<div class="input-append">
			<?php echo $this->Form->input('Transaction.amount', 
					array('type'=>'text', 'id'=>'TransactionAmount', 'class' => 'span10')); ?>
			<div id="ccy" class="add-on">CCY</div>
		</div>

		<?php echo $this->Form->input(
				'Transaction.accountA_IBAN', array(
				'label'     => 'Principal Account',
				'class'		=> 'span12',
				'options'   => array(''),
				'default'	=> $defaultOpts['accountA_IBAN'],
				'empty' 	=> __('-- Select an account --'),
			));
		?>

		<?php echo $this->Form->input(
				'Transaction.accountB_IBAN', array(
				'label'     => 'Interest Account',
				'class'		=> 'span12',
				'options'   => array(''),
				'default'	=> $defaultOpts['accountB_IBAN'],
				'empty' 	=> __('-- Select an account --'),
			));
		?>

		<?php echo $this->Form->input('Transaction.scheme', 
				array('type'=>'hidden', 'id'=>'TransactionScheme')); ?>
	</div>

	<div class="well span3">

		<?php
			echo $this->Form->input(
				'Transaction.depo_type', array(
				'label'     => 'Deposit Type',
				'options'   => array('Term' => 'Term','Callable'=>'Callable'),
				'default'	=> $defaultOpts['depo_type'],
				/*'empty' 	=> __('-- Choose One --'),*/
			));
		?>

		<div id="depoTermDiv">
			<?php echo $this->Form->input(
					'Transaction.depo_term', array(
					'label'     => 'Depo term',
					'class'		=> 'span8',
					'options'   => $depoTerm,
					'default'	=> $defaultOpts['depo_term'],
					'empty'		=> __('-- Choose one --'),
				));
			?>
		</div>

		<div id="automaticRenewalDiv">
			<div class="input file">Automatic renewal at maturity</div>
			<table class="table table-stripped">
				<tr>
					<td>
						<?php echo $this->Form->input('Transaction.depo_renew', 
					array('type'=>'radio', 'id'=>'TransactionDepoRenewYes', 'checked'=>"checked", 'value' => 'Yes')); ?>
						<label for="TransactionDepoRenewYes">Yes</label>
					</td>
					<td>
						<?php echo $this->Form->input('Transaction.depo_renew', 
					array('type'=>'radio', 'id'=>'TransactionDepoRenewNo', 'checked'=>"checked", 'value' => 'No')); ?>
						<label for="TransactionDepoRenewNo">No</label>
					</td>
				</tr>
			</table>
		</div>

		
		<?php
		echo $this->Form->input(
				'commencement', array(
				'name'				=> 'data[Transaction][commencement_date]',
				'label'				=> 'Commencement date',
				'id'				=> 'TransactionCommencementDate',
				'class'				=> 'span6',
				'data-date-format'	=> 'dd/mm/yyyy',
				'disabled'			=> $disabledOpts['commencement_date'],
				'default'			=> $defaultOpts['commencement_date'],
		));
		?>
		

		<!-- </div>  -->

		<!--  Maturity Date is displayed using Ajax whenever "Non Standard Depo Term" is selected by the user -->
		<div id="MaturityDateDiv" class="input file" style="display:none">
		<?php
		echo $this->Form->input(
				'maturity', array(
				'name'=> 'data[Transaction][maturity_date]',
				'label'	=> 'Maturity date',
				'id'	=> 'TransactionMaturityDate',
				'class'		=> 'span6',
				'data-date-format'	=> 'dd/mm/yyyy',
				'default'			=> $defaultOpts['maturity_date'],
		));
		?>
		</div>
	</div>
	<?php echo $this->Form->end(array('label'=>__('Create New Deposit'), 'class' => 'span3 offset9 btn btn-primary')) ?>
</div>
<!-- Ajax script used for Compartments population based on Mandate_ID -->
<?php
    $this->Js->get('#TransactionMandateID')->event('change',
        $this->Js->request(
            array(
                'controller'    =>  'treasuryajax',
                'action'        =>  'getcmpbymandate'
                ),
            array(
                'update'        =>  '#TransactionCmpID',
                'async'         =>  true,
                'method'        =>  'post',
                'dataExpression'=>  true,
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

<!-- Ajax script used for Counterparties population based on Mandate_ID -->
<?php
    $this->Js->get('#TransactionMandateID')->event('change',
        $this->Js->request(
            array(
                'controller'    =>  'treasuryajax',
                'action'        =>  'getcptybymandate'
                ),
            array(
                'update'        =>  '#TransactionCptyId',
                /*'async'       =>  false,*/
                'async'         =>  true,
                'method'        =>  'post',
                'dataExpression'=>  true,
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
<!--  Get accounts A and B from compartment and display them in a table-->
<?php
    $this->Js->get('#TransactionCmpID')->event('change',
        $this->Js->request(
            array(
                'controller'    =>  'treasuryajax',
                'action'        =>  'getaccounts'
                ),
            array(
                'update'        =>  '#TransactionAccountsIBAN',
                'async'         =>  true,
                'method'        =>  'post',
                'dataExpression'=>  true,
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
<!--  Get currency -->
<?php
    $this->Js->get('#TransactionCmpID')->event('change',
        $this->Js->request(
            array(
                'controller'    =>  'treasuryajax',
                'action'        =>  'getccy'
                ),
            array(
                'update'        =>  '#ccy',
                'async'         =>  true,
                'method'        =>  'post',
                'dataExpression'=>  true,
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

<!-- Get accounts A and B and put them in a Select options -->
<?php
    $this->Js->get('#TransactionCmpID')->event('change',
        $this->Js->request(
            array(
                'controller'    =>  'treasuryajax',
                'action'        =>  'accountslist'
                ),
            array(
                'update'        =>  '#TransactionAccountAIBAN',
                'async'         =>  true,
                'method'        => 'post',
                'dataExpression'=>  true,
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

<!-- Hide accounts table when mandate is changed  -->
<?php
    $this->Js->get('#TransactionMandateID')->event('change',
        $this->Js->request(
            array(
                'controller'    =>  'treasuryajax',
                'action'        =>  'hideaccounts'
                ),
            array(
                'update'        =>  '#TransactionAccountsIBAN',
                'async'         =>  true,
                'method'        => 'post',
                'dataExpression'=>  true,
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
    $this->Js->get('#TransactionCmpID')->event('change',
        $this->Js->request(
            array(
                'controller'    =>  'treasuryajax',
                'action'        =>  'accountslist'
            ),
            array(
                'update'        =>  '#TransactionAccountBIBAN',
                'async'         =>  true,
                'method'        => 'post',
                'dataExpression'=>  true,
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

<!-- Control Accounts Scheme A-A, A-B, B-B -->
<?php
    $this->Js->get('#TransactionAccountAIBAN')->event('change',
        $this->Js->request(
            array(
                'controller'    =>  'treasuryajax',
                'action'        =>  'accountscheme'
            ),
            array(
                'update'        =>  '#TransactionAccountBIBAN',
                'async'         =>  true,
                'method'        => 'post',
                'dataExpression'=>  true,
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


<!--  Script for amount formatting, datepicker, showing/hiding/controling maturity date based on depo_term element and commencement date -->
<script type="text/javascript">
    $(document).ready(function(){

        $('#TransactionAmount').autoNumeric('init',{aSep: ',',aDec: '.'});

        $('#TransactionDepoTerm').change(function() {
            if ($(this).val() == "NS") {
                 $('#MaturityDateDiv').show();
            }
            else {
                $("#TransactionMaturityDate").val('');
                $('#MaturityDateDiv').hide()
            }
        });

        $('#TransactionDepoType').change(function() {
            if ($(this).val() == "Callable") {
                 $('#depoTermDiv').hide();
                 $('#automaticRenewalDiv').hide();
                 $('#TransactionDepoTerm').val('');
                 $("#TransactionMaturityDate").val('');
                 $('#MaturityDateDiv').hide();
            }
            else {
                $('#automaticRenewalDiv').show();
                $('#depoTermDiv').show()
            }
        });

        $("#TransactionAccountAIBAN, #TransactionAccountBIBAN").change(function(){
            var arr = ["Scheme", "A", "B"];
            var a = $("#TransactionAccountAIBAN :selected").text().substr(9, 1);
            var b = $("#TransactionAccountBIBAN :selected").text().substr(9, 1);
            if(jQuery.inArray(a, arr ) && jQuery.inArray(b, arr )){
                var scheme = "";
                scheme.concat(a,b);
                $("#TransactionScheme").val(scheme.concat(a,b));
            }else{
                $("#TransactionScheme").val("");
            }
        });

        var checkin = $('#TransactionCommencementDate').datepicker({dateFormat: 'dd/mm/yy'}).on('changeDate', function(ev) {
            if (ev.date.valueOf() > checkout.date.valueOf()) {
                var newDate = new Date(ev.date);
                newDate.setDate(newDate.getDate() + 1);
                checkout.setValue(newDate);
            }
            checkin.hide();
            $('#TransactionMaturityDate')[0].focus();
        }).data('datepicker');
        var checkout = $('#TransactionMaturityDate').datepicker({
            dateFormat: 'dd/mm/yy',
            onRender: function(date) {
                return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
            }
        }).on('changeDate', function(ev) {
            checkout.hide();
        }).data('datepicker');

    });
</script>



