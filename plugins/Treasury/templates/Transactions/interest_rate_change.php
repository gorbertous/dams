<?php
	echo $this->Html->css('/treasury/css/redmond/jquery-ui-1.10.3.custom.css');
	echo $this->Html->css('/treasury/css/datepicker');
	echo $this->Html->script('/treasury/js/bootstrap-datepicker');
	echo $this->Html->script('/treasury/js/autoNumeric.js');
?>

<fieldset>
    <legend>Interest Rate Change</legend>
	<div id="filter">
	<p id="warning_text">WARNING: Please ensure that the capitalisation of the callable deposits has been executed (if needed) before registering the change of the rate.</p>
	<?php
		echo $this->Form->create('updateInterestRate');
		echo $this->Form->input('cpty_ID', array(
			'label'		=> 'Counterparty',
			'div'		=> array('class' => 'filter_input'),
			'options'	=> $counterparties,
			'empty' 	=> __('-- Select a counterparty --'),
			'selected'	=> empty($cpty_ID) ? '' : $cpty_ID,
		));
		echo $this->Form->input('value_date', array(
			'div'		=> array('class' => 'filter_input'),
			'label'		=>'Value date',
			'placeholder' => "YYYY-MM-DD",
			'empty'		=> empty($value_date) ? '' : $value_date,
		));
		echo $this->Form->input('new_rate', array(
			'div'		=> array('class' => 'filter_input'),
			'label'		=>'New Interest Rate',
		));
	?>
	</div>
	<table id="list_trn" class="table table-bordered table-striped table-hover table-condensed">
		<thead>
			<tr>
				<th>Select all <input type="checkbox" name="selectall" id="selectall" /></th>
				<th>TRN</th>
				<th>Counterparty</th>
				<th>Mandate</th>
				<th>Amount</th>
				<th>Newest Interest Rate</th>
				<th>Rate Value Date</th>
			</tr>
		</thead>
		<tbody id="transactions">
		<?php
		foreach($callables as $k => $trn)
		{
			$tr_number = $trn['Transaction']['tr_number'];
			$ischecked = '';
			if (!empty($selected_trn) && in_array($tr_number, $selected_trn))
			{
				$ischecked = "CHECKED";
			}
			echo '<tr class="cpty_'.$trn['Transaction']['cpty_id'].'">';
			echo '<td>';
			echo $this->Form->input('Transaction.'.$tr_number, array(
				'type' => 'checkbox',
				'label'	=> false,
				'div'	=> false,
				'value'	=> $trn['Transaction']['maturity_date'],
				'checked'	=> ($ischecked == "CHECKED"),
			));
			echo $this->Form->input('value_date_'.$tr_number, array(
				'type' => 'hidden',
				'label'	=> false,
				'div'	=> false,
				'value'	=> $trn['Transaction']['maturity_date'],
				'id'	=> 'value_date_'.$tr_number,
			));
			echo '</td>';
			echo '<td>'.$tr_number.'</td>';
			echo '<td>'.$trn['Counterparty']['cpty_name'].'</td>';
			echo '<td>'.$trn['Mandate']['mandate_name'].'</td>';
			echo '<td style="text-align: right;">'.$trn['Transaction']['amount'].'</td>';
			echo '<td style="text-align: right;" class="has_interest_rate">'.$trn['Interest']['interest_rate'].'</td>';
			echo '<td>'.$trn['Interest']['interest_rate_from'].'</td>';
			echo '</tr>';
		}
		?>
		</tbody>
	</table>
	<br />
	<div class="span11">
		<?php
			echo $this->Form->submit('Change rate',
				array(
					'id' 	=> 'createButton',
					'type' 	=> 'submit',
					'class' => 'btn btn-primary pull-right',
					'div'	=> false,//array('class' => array('input submit'))
  					'style'	=>	'align-right',
  					'disabled' => true
				)
			);
		?>
	</div>
	<?php echo $this->Form->end(); ?>
</fieldset>
<style>
.filter_input{
	float: left;
	margin: 5px;
}
.filter_input input{
	height: 20px;
}
.filter_input label{
	margin: 0 10px;
}
#warning_text
{
	font-size: 15px;
	font-weight: 600;
}
</style>
<script type="text/javascript">

function validate_form()
{
	var rate = $("#updateInterestRateNewRate").val().length > 0;
	var transactions = $("#transactions input:checked").length > 0;

	document.getElementById('createButton').disabled = !(rate && transactions);	
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
var current_valid_date = "";
//UAT


$(document).ready(function () {

	$('#updateInterestRateValueDate').datepicker({ dateFormat: "yy-mm-dd", format: "yyyy-mm-dd" });
	$('#updateInterestRateNewRate').autoNumeric('init',{aSep: false,aDec: '.', vMin:-99999999.999, vMax: 99999999.999});

	$('#updateInterestRateNewRate').autoNumeric('init',{aSep: false,aDec: '.', vMin:-99999999.999, vMax: 99999999.999});

 	$("input[type=checkbox], #updateInterestRateNewRate").change(function(e)
  	{
  		validate_form();
  	});
	
	$('#updateInterestRateValueDate').on("keypress", function(e)
	{
		var date = $(e.target).val();
		var regex = /^[0-9-]+$/ig;
		if ( ! regex.exec(date))//if anything other than '-' and number
		{
			$('#updateInterestRateValueDate').val("");
		}
		else
		{
			current_valid_date = $('#updateInterestRateValueDate').val();
		}
	});
	$('#updateInterestRateValueDate').on("keyup", function(e)
	{
		var date = $(e.target).val();
		var regex = /^[0-9-]+$/ig;
		if ( ! regex.exec(date))//if anything other than '-' and number
		{
			$('#updateInterestRateValueDate').val(current_valid_date);
		}
	});
	$('#updateInterestRateValueDate').on("change", function(e)
	{
		var date = fix_date_string($(e.target).val());
		if ( ! date_string_valid(date))
		{
			$('#updateInterestRateValueDate').val("");
		}
	});

	$("#updateInterestRateCptyID, #updateInterestRateValueDate").change(function(e){
		var date = $("#updateInterestRateValueDate").val();
		if (date_string_valid(date))
		{
			if ( $("#updateInterestRateCptyID").val() != "" )
			{
				$("#updateInterestRateInterestRateChangeForm").submit();
			}
		}
	});

	/*$("#createButton").mousedown(function(e)
	{
		if ($("#transactions input:checked").length > 0)
		{
			$("#updateInterestRateInterestRateChangeForm").append('<input type="hidden" name="add_rate" value="true">');
		}
	});*/
	$("#createButton").click(function(e){//no double validation
		if ($("#transactions input:checked").length > 0)
		{
			$("#updateInterestRateInterestRateChangeForm").submit();
			$('#createButton')[0].disabled = true;
			$('#createButton').attr('disabled', true);
		}
		else
		{
			return false;
		}
	});

	$("#selectall").click(function(e){
		if ($("#selectall:checked").length > 0)
		{
			$("input[type=checkbox]").prop('checked', "checked");
		}
		else
		{
			$("input[type=checkbox]").prop('checked', false);
		}
		validate_form();
	});
});
</script>