<?php
	$title = 'New counterparty';
	$submit = 'Save';
	$id = '';
	$readonly = '';
	$readonly_group = false;
	$disabled = '';
	$from = '';
	if(!empty($_GET['from'])) $from=$_GET['from'];

	if(!empty($row)){
		$title = 'Edit counterparty '.$row['Counterparty']['cpty_name'];
		$submit = 'Update';
		$id = $row['Counterparty']['cpty_ID'];
		$readonly = 'readonly';
	}else $row=null;

$can_edit_pyrat_number = false;
if(!empty($user_groups['treasuryRisk']) || in_array('treasuryRisk', $user_groups)){
		$readonly_group = true;
		$disabled = 'disabled';
		$can_edit_pyrat_number = true;
}
if(!empty($user_groups['Admin']) || in_array('Admin', $user_groups)){
	$can_edit_pyrat_number = true;
}
?><fieldset>
<legend><?php print $title ?></legend>
<div id="form" class = "well span12 noleftmargin">
	<?php echo $this->Form->create('Counterparty', array('data-id'=>$id)) ?>
		<?php
			echo $this->Form->input('Counterparty.cpty_ID', array(
					'type'		=> 'hidden',
					'label'		=> false,
					'value'		=> $id
				)
			);
		?>
		
		<?php
			echo $this->Form->input('Counterparty.action_from', array(
					'type'		=> 'hidden',
					'value'		=> $from,
				)
			);
		?>
		<div class="span11"><?php
			echo $this->Form->input('Counterparty.cpty_name', array(
					'type'		=> 'text',
					'label'		=> 'Name',
					'class'		=> 'span12',
					'div'		=> 'span6',
					'default'	=> $row['Counterparty']['cpty_name'],
					'readonly' => $readonly_group
				)
			);
		?>
		<?php
			echo $this->Form->input('Counterparty.cpty_code', array(
					'type'		=> 'text',
					'label'		=> 'Code',
					'class'		=> 'span12',
					'div'		=> 'span2',
					'default'	=> $row['Counterparty']['cpty_code'],
					'readonly' => $readonly_group
				)
			);
		?>
		<?php
			echo $this->Form->input('Counterparty.pirat_number', array(
					'type'		=> 'text',
					'label'		=> 'Pirat Number',
					'class'		=> 'span12',
					'div'		=> 'span2',
					'default'	=> $row['Counterparty']['pirat_number'],
					'disabled'	=> !$can_edit_pyrat_number
				)
			);
		if (! $can_edit_pyrat_number)
		{
			echo $this->Form->input('Counterparty.pirat_number',
				array(
					'type'		=> 'hidden',
					'label'		=> false,
					'value'		=> $row['Counterparty']['pirat_number'],
				)
			);
		}
			$ticked = false;
			if(!empty($row['Counterparty']['eu_central_bank']) && $row['Counterparty']['eu_central_bank']=='Y') $ticked=true;
			echo $this->Form->input('Counterparty.eu_central_bank', array(
					'type'	=> 'checkbox',
					'label'		=> 'EU Central Bank',
					'class'		=> '',
					'div'		=> 'span2 checkbox checkboxvlabel',
					'required'	=> false,
					'checked'	=> $ticked,
					'value'	=> 'Y',
					'disabled' => $disabled
				)
			);
		?>
		</div>
		<div class="span11">
			<div class="half">&nbsp;</div>
			<div class="half" id="pirat_number_msg"></div>
		</div>
		<div class="span11">
		<?php
			echo $this->Form->input('Counterparty.cpty_address', array(
					'type'		=> 'textarea',
					'label'		=> 'Address',
					'class'		=> 'span12',
					'div'		=> 'span6',
					'default'	=> $row['Counterparty']['cpty_address'],
					'readonly' => $readonly_group
				)
			);
		?>
		<?php
			echo $this->Form->input('Counterparty.cpty_zipcode', array(
					'type'		=> 'text',
					'label'		=> 'Zipcode',
					'class'		=> 'span12',
					'div'		=> 'span6',
					'default'	=> $row['Counterparty']['cpty_zipcode'],
					'readonly' => $readonly_group
				)
			);
		?>
		<?php
			echo $this->Form->input('Counterparty.cpty_city', array(
					'type'		=> 'text',
					'label'		=> 'City',
					'class'		=> 'span12',
					'div'		=> 'span6',
					'default'	=> $row['Counterparty']['cpty_city'],
					'readonly' => $readonly_group
				)
			);
		?>
		<?php
			echo $this->Form->input('Counterparty.cpty_country', array(
					'type'		=> 'text',
					'label'		=> 'Country',
					'class'		=> 'span12',
					'div'		=> 'span6',
					'default'	=> $row['Counterparty']['cpty_country'],
					'readonly' => $readonly_group
				)
			);
		?></div>
		<div class="span11"></div>
		<div class="span11">
		<?php
			echo $this->Form->input('Counterparty.cpty_bic', array(
					'type'		=> 'text',
					'label'		=> 'BIC',
					'class'		=> 'span12',
					'div'		=> 'span4',
					'maxlength' => 19,
					'default'	=> $row['Counterparty']['cpty_bic'],
					'readonly' => $readonly_group
				)
			);
		?>
</div><div class="span11">
		<span class="help-block">BIC/IBAN of the Treasury Manager's account to which funds are transferred for placing new deposits (e.g. account with Deutsche Bank for EIB)</span>
		</div>
		<div class="span11"></div>
		<div class="span11"><div class="span6 spancol">
		<?php
			echo $this->Form->input('Counterparty.contact_person1', array(
					'type'		=> 'text',
					'label'		=> 'Contact Person 1',
					'class'		=> 'span12',
					'div'		=> 'span12',
					'required'	=> false,
					'default'	=> $row['Counterparty']['contact_person1'],
					'readonly' => $readonly_group
				)
			);
		?>
		<?php
			echo $this->Form->input('Counterparty.tel1', array(
					'type'		=> 'text',
					'label'		=> 'Tel 1',
					'class'		=> 'span12',
					'div'		=> 'span12',
					'required'	=> false,
					'default'	=> $row['Counterparty']['tel1'],
					'readonly' => $readonly_group
				)
			);
		?>
		<?php
			echo $this->Form->input('Counterparty.fax1', array(
					'type'		=> 'text',
					'label'		=> 'Fax 1',
					'class'		=> 'span12',
					'div'		=> 'span12',
					'required'	=> false,
					'default'	=> $row['Counterparty']['fax1'],
					'readonly' => $readonly_group
				)
			);
		?>
		<?php
			echo $this->Form->input('Counterparty.email1', array(
					'type'		=> 'text',
					'label'		=> 'Email 1',
					'class'		=> 'span12',
					'div'		=> 'span12',
					'required'	=> false,
					'default'	=> $row['Counterparty']['email1'],
					'readonly' => $readonly_group
				)
			);
		?>
		</div>
		<div class="span6 spancol">
		<?php
			echo $this->Form->input('Counterparty.contact_person2', array(
					'type'		=> 'text',
					'label'		=> 'Contact Person 2',
					'class'		=> 'span12',
					'div'		=> 'span12',
					'required'	=> false,
					'default'	=> $row['Counterparty']['contact_person2'],
					'readonly' => $readonly_group
				)
			);
		?>
		<?php
			echo $this->Form->input('Counterparty.tel2', array(
					'type'		=> 'text',
					'label'		=> 'Tel 2',
					'class'		=> 'span12',
					'div'		=> 'span12',
					'required'	=> false,
					'default'	=> $row['Counterparty']['tel2'],
					'readonly' => $readonly_group
				)
			);
		?>
		<?php
			echo $this->Form->input('Counterparty.fax2', array(
					'type'		=> 'text',
					'label'		=> 'Fax 2',
					'class'		=> 'span12',
					'div'		=> 'span12',
					'required'	=> false,
					'default'	=> $row['Counterparty']['fax2'],
					'readonly' => $readonly_group
				)
			);
		?>
		<?php
			echo $this->Form->input('Counterparty.email2', array(
					'type'		=> 'text',
					'label'		=> 'Email 2',
					'class'		=> 'span12',
					'div'		=> 'span12',
					'required'	=> false,
					'default'	=> $row['Counterparty']['email2'],
					'readonly' => $readonly_group
				)
			);
		?></div></div>
		<div class="span11"></div>
		<div id="counterpartyaccount">
		
		<div class="span11">
		<label for="Accounts">Accounts</label>
			<table id="CounterpartyAccountTable" class="table table-bordered table-striped table-hover table-condensed">
				<thead>
					<tr>
						<td>Currency</td>
						<td>TARGET</td>
						<td>Correspondent Bank</td>
						<td>Correspondent BIC</td>
						<td>IBAN</td>
						<td>&nbsp;</td>
					</tr>
				</thead>
				<tbody>
<?php
				if (!empty($row))
				{
					$accounts = $row['CounterpartyAccount'];
					foreach($accounts as $account)
					{
						echo '<tr id="CounterpartyAccount_'.$account['id'].'">';
						echo $this->Form->input('CounterpartyAccount.id',
							array(
								'type'		=> 'hidden',
								'value'		=> $account['id'],
							)
						);
						echo $this->Form->input('CounterpartyAccount.cpty_id',
							array(
								'type'		=> 'hidden',
								'value'		=> $id,
							)
						);
						echo '<td>';
						echo $this->Form->input('CounterpartyAccount.currency',
							array(
								'label'		=> false,
								'class'		=> 'span8',
								'options'	=> $currencies,
								'default'	=> $account['currency'],
							)
						);
						echo '</td>';

						echo '<td>';
						echo $this->Form->input('CounterpartyAccount.target',
							array(
								'label'		=> false,
								'type'		=> 'checkbox',
								'default'	=> $account['target'],
							)
						);
						echo '</td>';

						echo '<td>';
						echo $this->Form->input('CounterpartyAccount.correspondent_bank',
							array(
								'label'		=> false,
								'style'		=> 'width: 95%;',
								'default'	=> $account['correspondent_bank'],
							)
						);
						echo '</td>';

						echo '<td>';
						echo $this->Form->input('CounterpartyAccount.correspondent_BIC',
							array(
								'label'		=> false,
								'style'		=> 'width: 95%;',
								'default'	=> $account['correspondent_BIC'],
							)
						);
						echo '</td>';

						echo '<td>';
						echo $this->Form->input('CounterpartyAccount.account_IBAN',
							array(
								'label'		=> false,
								'style'		=> 'width: 95%;',
								'default'	=> $account['account_IBAN'],
							)
						);
						echo '</td>';

						echo '<td><a id="del_account_'.$account['id'].'" data-id-account="'.$account['id'].'" class="btn btn-mini confirmation del_account_class"><i class="icon-trash"></a></td>';//<a href="/damsv2/DSToolbox/delete/2" class="btn btn-mini confirmation"><i class="icon-trash"></i></a>
						echo '</tr>';
					}
				}
?>
				</tbody>
			</table>
			<?php
			if (empty($id))
			{
				echo '<a>+ Add a new Account (please save the counterparty first)</a>';
			}
			else
			{
				echo '<a id="new_account">+ Add a new Account</a>';
			}
			?>
		</div>
		</div>

		<div class="span11"></div>
		<div class="span11">
		<?php
			$ticked = false;
			if(!empty($row['Counterparty']['automatic_fixing']) && $row['Counterparty']['automatic_fixing']=='1') $ticked=true;
			echo $this->Form->input('Counterparty.automatic_fixing', array(
					'type'	=> 'checkbox',
					'label'		=> 'Automatic fixing',
					'class'		=> '',
					'div'		=> array('class' => 'span2 checkbox', 'style' => 'margin-top: 12px;'),
					'required'	=> false,
					'checked'	=> $ticked,
					'value'		=> '1',
					'disabled' => $disabled
				)
			);
		?>
		
		<?php
			echo $this->Form->input('Counterparty.capitalisation_frequency',
				array(
					'label'		=> 'Capitalisation frequency : ',
					'class'		=> 'span3',
					'div'		=> 'span5',
					'required'	=> false,
					'options'	=> $frequencies,
					'style'		=> 'margin-top: 8px;',
					'empty'		=> "----",
					'default'	=> $row['Counterparty']['capitalisation_frequency'],
					'disabled' => $disabled
				)
			);
		?>
		</div>
		
		<div class="span11"></div>

		<?php if(!empty($id)): ?>
		<div class="span11"></div><div class="span11">
		<?php
			echo $this->Form->input('Counterparty.created',
				array(
					'type'		=> 'text',
					'label'		=> 'Created on',
					'disabled'	=> true,
					'class'		=> 'span12',
					'div'		=> 'span6',
					'default'	=> $row['Counterparty']['created']
				)
			);
		?>
		<?php
			echo $this->Form->input('Counterparty.modified',
				array(
					'type'		=> 'text',
					'label'		=> 'Last update',
					'disabled'	=> true,
					'class'		=> 'span12',
					'div'		=> 'span6',
					'required'	=> true,
					'default'	=> $row['Counterparty']['modified']
				)
			);
		?>
		</div><?php endif ?>

		<div class="span11"></div>
		<div class="span11">
			<?php
				echo $this->Form->submit($submit,
					array(
						'id' 	=> 'createButton',
						'type' 	=> 'submit',
						'class' => 'btn btn-primary pull-right',
						'div'	=> false//array('class' => array('span11'))
					)
				);
			?>
			<?php print $this->Html->link(
			    '<i class="icon-chevron-left"></i> Cancel',
			    (!empty($from))?$from:'counterparties',
			    array('class' => 'btn pull-right btn-small btn-cancel', 'style'=>'margin-right: 5px;', 'escape'=>false)
			); ?>
		</div>

	<?php echo $this->Form->end() ?>
</div>
</fieldset>
<div style="display:none;">
<?php
echo $this->Form->create('updateAccount', array('url'=>'/treasury/Treasurycounterpartyaccount/update_counterparty_account'));
echo $this->Form->input('CounterpartyAccount.id', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('CounterpartyAccount.cpty_id', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('CounterpartyAccount.currency', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('CounterpartyAccount.target', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('CounterpartyAccount.correspondent_bank', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('CounterpartyAccount.correspondent_BIC', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('CounterpartyAccount.account_IBAN', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();

echo $this->Form->create('saveAccount', array('url'=>'/treasury/Treasurycounterpartyaccount/create_counterparty_account'));
echo $this->Form->input('CounterpartyAccount.id', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('CounterpartyAccount.cpty_id', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('CounterpartyAccount.currency', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('CounterpartyAccount.target', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('CounterpartyAccount.correspondent_bank', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('CounterpartyAccount.correspondent_BIC', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->input('CounterpartyAccount.account_IBAN', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();

echo $this->Form->create('del_cpty_acc', array('url'=>'/treasury/Treasurycounterpartyaccount/del_counterparty_account'));
echo $this->Form->input('CounterpartyAccount.id', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();
?>
</div>
<style>
.half
{
	width: 49%;
	float: left;
}
.error
{
	color: red;
}
</style>
<script type="text/javascript">
	var counterparty_id = '<?php echo $id; ?>';
	var CounterpartyPiratNumber_orig = '<?php echo $row['Counterparty']['pirat_number']; ?>';
	$(document).ready(function()
	{
		<?php if (!$disabled){ ?>
		allow_capitalisation_frequency();
		$("#CounterpartyAutomaticFixing").change(allow_capitalisation_frequency);
		<?php } ?>
		//$("#counterpartyaccount").load("/treasury/Treasurycounterpartyaccount/get_counterparty_account/"+counterparty_id);

		//update of lines 	
		$("#CounterpartyAccountTable input, #CounterpartyAccountTable select").change(update_cpty_account);

		$('#CounterpartyPiratNumber').change(update_pirat_number);
		
		$("#CounterpartyCounterpartyForm").submit(function (e)
		{
			//data[CounterpartyAccount]
			$("#CounterpartyAccountTable .unsaved input").prop('disabled', true);
			$("#CounterpartyAccountTable .unsaved select").prop('disabled', true);
			$("#CounterpartyAccountTable .newline input").prop('disabled', true);
			$("#CounterpartyAccountTable .newline select").prop('disabled', true);
		});
	});
	
	function update_pirat_number()
	{
		//check if set to empty
		var CounterpartyPiratNumber = $('#CounterpartyPiratNumber').val();
		if ((CounterpartyPiratNumber_orig != '') && (CounterpartyPiratNumber == ''))
		{
			var msg = '<span class="error" id="no_pirat_warning">WARNING: After the Pirat Number is removed, all automatic limits will be switched to manual for this counterparty!</span>';
			$("#pirat_number_msg").html(msg);
		}
		else
		{
			$("#no_pirat_warning").remove();
		}
		
		//check if exists
		if (CounterpartyPiratNumber != '')
		{
			var data = {pirat_number: CounterpartyPiratNumber};
			$.ajax({
				type: 'POST',
				url: '/treasury/Treasuryajax/pirat_number_exists',
				data: data,
				dataType: "json",
				success: function(data)
				{
					if (data.exists == false)
					{
						var msg = '<span class="error" id="no_pirat_warning">WARNING: The entered Pirat Number does not exist in Treasury application. Please make sure that the counterparty ratings are imported from Pirat, otherwise automatic limits for this counterparty will not work!</span>';
						$("#pirat_number_msg").html(msg);
					}
					else
					{
						$("#no_pirat_warning").remove();
					}
				}
			});
		}
	}

	function allow_capitalisation_frequency()
	{
		if ($('#CounterpartyAutomaticFixing:checked').length)
		{
			$("#CounterpartyCapitalisationFrequency option").prop('disabled', false);
		}
		else
		{
			$('#CounterpartyCapitalisationFrequency').val(null);
			$('#CounterpartyCapitalisationFrequency option[value!=""]').prop('disabled', true);
		}
	}
	
	
	$("#new_account").mouseup(function()
	{
		var new_account = "";
		$.ajax({
			type: 'GET',
			url: '/treasury/Treasurycounterpartyaccount/new_counterparty_account',
			success: function(data)
			{
				if(data.length > 0)
				{
					var data_dom = $(data);
					data_dom.find(".icon-pencil").click(create_cpty_account);
					$("#CounterpartyAccountTable tbody").append(data_dom);
				}
			}
		});
	});

	function update_cpty_account(e)
	{
		var line_el = $(e.target);
		var line_tr = line_el.parents("tr");
		$('#updateAccountCounterpartyForm #CounterpartyAccountId').val( line_tr.find('#CounterpartyAccountId').val() );
		$('#updateAccountCounterpartyForm #CounterpartyAccountCptyId').val( line_tr.find('#CounterpartyAccountCptyId').val() );
		$('#updateAccountCounterpartyForm #CounterpartyAccountCurrency').val( line_tr.find('#CounterpartyAccountCurrency').val() );
		$('#updateAccountCounterpartyForm #CounterpartyAccountTarget').val( line_tr.find('#CounterpartyAccountTarget').is(":checked") );
		$('#updateAccountCounterpartyForm #CounterpartyAccountCorrespondentBank').val( line_tr.find('#CounterpartyAccountCorrespondentBank').val() );
		$('#updateAccountCounterpartyForm #CounterpartyAccountCorrespondentBIC').val( line_tr.find('#CounterpartyAccountCorrespondentBIC').val() );
		$('#updateAccountCounterpartyForm #CounterpartyAccountAccountIBAN').val( line_tr.find('#CounterpartyAccountAccountIBAN').val() );

		var POST = $('#updateAccountCounterpartyForm').serialize();
		$.ajax({
			type: 'POST',
			data: POST,
			url: '/treasury/Treasurycounterpartyaccount/update_counterparty_account',
			success: function(data)
			{
				$(".alert").remove();
				if(data.length > 0)
				{
					if (data == "The IBAN is not valid")
					{
						show_error(data);
					}
					if (data == "A single account can exist for each currency.")
					{
						show_error(data);
					}
					// update events
				}
			}
		});
	}
	
	function create_cpty_account(e)
	{
		var line_tr = $(e.target).parents('tr');
		$('#saveAccountCounterpartyForm #CounterpartyAccountId').val( line_tr.find('#CounterpartyAccountId').val() );
		$('#saveAccountCounterpartyForm #CounterpartyAccountCptyId').val( line_tr.find('#CounterpartyAccountCptyId').val() );
		$('#saveAccountCounterpartyForm #CounterpartyAccountCurrency').val( line_tr.find('#CounterpartyAccountCurrency').val() );
		$('#saveAccountCounterpartyForm #CounterpartyAccountTarget').val( line_tr.find('#CounterpartyAccountTarget').is(":checked") );
		$('#saveAccountCounterpartyForm #CounterpartyAccountCorrespondentBank').val( line_tr.find('#CounterpartyAccountCorrespondentBank').val() );
		$('#saveAccountCounterpartyForm #CounterpartyAccountCorrespondentBIC').val( line_tr.find('#CounterpartyAccountCorrespondentBIC').val() );
		$('#saveAccountCounterpartyForm #CounterpartyAccountAccountIBAN').val( line_tr.find('#CounterpartyAccountAccountIBAN').val() );
		var POST = $('#saveAccountCounterpartyForm').serialize();
		POST = POST+'&data%5BCounterpartyAccount%5D%5Bcpty_id%5D='+counterparty_id;
		$.ajax({
			type: 'POST',
			data: POST,
			url: '/treasury/Treasurycounterpartyaccount/create_counterparty_account',
			success: function(data)
			{
				if(data.length > 0)
				{
					if (data == "A single account can exist for each currency.")
					{
						show_error("A single account can exist for each currency.");
					}
					else if (data == "The IBAN is not valid")
					{
						show_error("The IBAN is not valid");
					}
					else
					{
						line_tr.remove();
						$("#CounterpartyAccountTable tbody").append(data);
					}
				}
			}
		});
	}

	$(".del_account_class").mouseup(function(e)
	{
		var el = $(e.target).parent('a');
		var id_acc = el.attr('data-id-account');
		$('#del_cpty_accCounterpartyForm #CounterpartyAccountId').val( id_acc );
		var data = $('#del_cpty_accCounterpartyForm').serialize();
		$.ajax({
			type: 'POST',
			data: data,
			url: '/treasury/Treasurycounterpartyaccount/del_counterparty_account',
			success: function(data)
			{
				$("#CounterpartyAccount_"+id_acc).remove();
			}
		});
	});

	function show_error(error)
	{
		//console.log(error);
		$('#mainContentHolder').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">×</button>'+error+'</div>');
	}
</script>