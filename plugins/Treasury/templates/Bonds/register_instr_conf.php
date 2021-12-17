<?php
	echo $this->Html->script('/treasury/js/bootstrap-datepicker');
	echo $this->Html->css('/treasury/css/datepicker');
	echo $this->Html->script('/treasury/js/autoNumeric.js');
?>
<div id="processformcontainer" data-ajaxbasepath="<?php print Router::url(array(
	    'controller' => 'treasuryajax', 'plugin'=>'treasury')) ?>" ><?php 
  if(!empty($form_process)){
  	if($form_process['result']=='success') print '<div id="formprocess" data-trnum="'.$form_process['trnum'].'" data-result="success" class="alert alert-success">';
  	else print '<div id="formprocess" data-trnum="'.$form_process['trnum'].'" data-result="error" class="alert alert-error">';
  	print '<button class="close" data-dismiss="alert" type="button">×</button>';
  	print '<p>'.$form_process['result_text'].'</p></div>';

  }
					
?>
</div>
	<div class="span11 noleftmargin">
		<div class="span1"><strong>DI #:</strong> <?php print $instr['Instruction']['instr_num'] ?></div>
		<div class="span3"><strong>Mandate:</strong> <?php print $instr['Mandate']['mandate_name'] ?></div>
		<div class="span3"><strong>Counterparty:</strong> <?php print $instr['Counterparty']['cpty_name'] ?></div>
		<div class="span5 text-right">
			<?php echo $this->Html->link("DI: <i class='icon-file'></i>", "/data/treasury/pdf/deposit_instruction_".$instr['Instruction']['instr_num'].".pdf", array('escape' => false, 'target' => '_blank')) ?>&nbsp;    

<?php if(empty($instr['Instruction']['confirmation_file'])): ?>
	Confirmation: <a href="#" class="btn btn-default btn-mini attachbt">Attach</a>

	<?php echo $this->Form->create('Instruction', array('enctype' => 'multipart/form-data', 'class'=>'form-inline span12 attachform')) ?>
		<?php 
			echo $this->Form->input('instr_num', array(
					'div'=>false,
					'type'=>'hidden',
					'value'	=> $instr['Instruction']['instr_num'],
			)); 
		?>
		<?php 
			echo $this->Form->input('attachment', array(
					'div'=>false,
					'type'=>'file',
					'label'	=> false,
					'required'	=> 'required',
			)); 
		?>
		<?php
			echo $this->Form->submit('Submit', array(
					'div'=>false,
					'type' 	=> 'submit',
					'class' => 'btn btn-primary',
			));
		?>
	<?php echo $this->Form->end() ?>

<?php else: ?>

		<?php echo $this->Html->link("Confirmation <i class='icon-file'></i>", "/data/treasury/pdf/".$instr['Instruction']['confirmation_file'], array('escape' => false, 'target' => '_blank')) ?>
<?php endif ?>
			</div>
		</div>

	<?php if(empty($trns)): ?>
		<div class="alert alert-info span11">
			All transaction confirmations have been registered for Instruction #<?php print $instr['Instruction']['instr_num'] ?>
		</div>
		<a href="<?php print Router::url('/treasury/treasurydepositinstructions/displaydi') ?>" class="btn btn-default pull-left" style="clear: both">Back to Display DI</a>
	<?php else: ?>
		<table id="downloadInstruction" data-di="<?php print $instr['Instruction']['instr_num'] ?>" class="table table-bordered table-striped table-hover table-condensed">
			<thead>
				<th class="instr_num">TRN</th>
				<th class="instr_type">Type</th>
				<th class="compartment">Compartment</th>
				<th class="amount">Amount</th>
				<th class="ccy">CCY</th>
				<th class="cmmt_date">Cmmt. Date</th>
				<th class="period">Period</th>
				<th class="scheme">Scheme</th>
				<th class="extref">ExtRef</th>
				<th class="maturity">Maturity Date*</th>
				<th class="int_rate">Int. Rate% *</th>
				<th class="day_basis">Day Basis*</th>
				<th class="interest">Interest*</th>
				<th class="tax">Tax*</th>
				<th class="reference_rate">Ref. Rate%</th>
				<th class="actions">Actions</th>
			</thead>
			<tbody>
			
				<?php foreach ($trns as $trnum => $trn): ?>
					<?php 
						$shortname = $trn['cmp_name']; 
						$exp = explode('-', $shortname);
						if(count($exp)>1) $shortname=trim($exp[0]);

						$date_com  = new DateTime(str_replace('/','-',$trn['commencement_date'])); 
						$date_mat  = new DateTime(str_replace('/','-',$trn['maturity_date']));

						if(!isset($trn['external_ref']))$trn['external_ref']='';
						if(!empty($this->request->data['Transaction']['external_ref'])) $trn['external_ref']=$this->request->data['Transaction']['external_ref'];

						if(!isset($trn['maturity_date']))$trn['maturity_date']='';
						if(!empty($this->request->data['Transaction']['maturity_date'])) $trn['maturity_date']=$this->request->data['Transaction']['maturity_date'];

						if(!isset($trn['interest_rate']))$trn['interest_rate']='';
						if(!empty($this->request->data['Transaction']['interest_rate'])) $trn['interest_rate']=$this->request->data['Transaction']['interest_rate'];

						$spread = '';
						if(isset($trn['reference_rate']) && isset($trn['interest_rate'])){
							$spread = ($trn['interest_rate']-$trn['reference_rate'])*100;
						}

						if(empty($trn['date_basis'])){
							$trn['date_basis']='Act/360';
							if(!empty($trn['currency'])){
								if($trn['currency']=='GBP' || $trn['currency']=='PLN'){
									$trn['date_basis']='Act/365';
								}
							}
						}

						if(isset($this->request->data['Transaction']['date_basis'])) $trn['date_basis']=$this->request->data['Transaction']['date_basis'];

						if(!isset($trn['total_interest']))$trn['total_interest']='';
						if(!empty($this->request->data['Transaction']['total_interest'])) $trn['total_interest']=$this->request->data['Transaction']['total_interest'];

						if(!isset($trn['tax_amount']))$trn['tax_amount']='';
						if(!empty($this->request->data['Transaction']['tax_amount'])) $trn['tax_amount']=$this->request->data['Transaction']['tax_amount'];

						if(!isset($trn['reference_rate']))$trn['reference_rate']='';
						if(!empty($this->request->data['Transaction']['reference_rate'])) $trn['reference_rate']=$this->request->data['Transaction']['reference_rate'];

						$callable = false;
						$automatic_fixing = false;
						if(strtolower($trn['depo_type'])=='callable') $callable=true;
						if ($trn['automatic_fixing'] == '1')
						{
							$automatic_fixing = true;
						}
						$startdate = strtotime(str_replace('/','-', $trn['commencement_date']));
						$startdate = date('d/m/Y', $startdate+(60*60*24));

					?>
					<tr class="formQueueLine" id="rowTrn<?php echo $trnum ?>" data-trnum="<?php echo $trnum ?>" data-date-com="<?php print $date_com->format('j-n-Y') ?>" data-date-mat="<?php print $date_mat->format('d-m-Y') ?>" data-currency="<?php echo $trn['currency'] ?>" >
						
<?php
									echo $this->Form->input(
										'Transaction.commencement_date', array(
												'type' 			=> 'hidden',
												'label'     	=> false,
												'value'  		=> $trn['commencement_date'],
									));
									echo $this->Form->input(
										'Transaction.amount', array(
												'type' 			=> 'hidden',
												'label'     	=> false,
												'value'  		=> $trn['amount'],
									));
									echo $this->Form->input(
										'Transaction.depo_type', array(
												'type' 			=> 'hidden',
												'label'     	=> false,
												'value'  		=> $trn['depo_type'],
									));
									echo $this->Form->input(
										'Transaction.depo_term', array(
												'type' 			=> 'hidden',
												'label'     	=> false,
												'value'  		=> $trn['depo_term'],
									));
									echo $this->Form->input(
										'Transaction.scheme', array(
												'type' 			=> 'hidden',
												'label'     	=> false,
												'value'  		=> $trn['scheme'],
									));
									echo $this->Form->input(
										'Transaction.mandate_ID', array(
												'type' 			=> 'hidden',
												'label'     	=> false,
												'value'  		=> $trn['mandate_ID'],
									));
									echo $this->Form->input(
										'Transaction.cpty_id', array(
												'type' 			=> 'hidden',
												'label'     	=> false,
												'value'  		=> $trn['cpty_id'],
									));
								?>
						<td><?php echo UniformLib::uniform($trnum, 'trnum') ?></td>
						<td><?php echo UniformLib::uniform($trn['tr_type'], 'tr_type'); ?></td>
						<td><?php echo UniformLib::uniform($shortname, 'shortname') ?></td>
						<td class="amount"><?php echo UniformLib::uniform($trn['amount'], 'amount'); ?></td>
						<td><?php echo UniformLib::uniform($trn['currency'], 'ccy'); ?></td>
						<td><?php echo UniformLib::uniform($trn['commencement_date'], 'commencement_date'); ?></td>
						<td><?php echo UniformLib::uniform($trn['depo_term'], 'depo_term'); ?></td>
						<td>
							<?php echo UniformLib::uniform($trn['scheme'], 'scheme'); ?> 
							<span class="infotip accounts">
								<span class="icon icon-info-sign"></span>
								<div class="content">Account A: <?php print UniformLib::uniform($trn['AccountA'], 'accountA_iban') ?><br>Account B: <?php print UniformLib::uniform($trn['AccountB'], 'accountB_iban') ?></div>
							</span>
						</td>
						<td class="control-group external_ref optional">
							<?php
							echo $this->Form->input(
								'Transaction.external_ref', array(
										'type' 			=> 'text',
										'label'     	=> false,
										'class'  		=> 'TransactionExternalRef',
										'value'  		=> $trn['external_ref'],
							));
							?>
						</td>
						<td class="control-group maturity_date">
							<div class="span12">
							<?php if(empty($callable))
							{
								echo $this->Form->input(
									'Transaction.maturity_date', array(
											'type' 			=> 'text',
											'label'     	=> false,
											'class'  		=> 'TransactionMaturityDate datepckr',
											'value'  		=> $trn['maturity_date'],
											'data-date-start-date'	=> $startdate,
											'data-date-format'	=> "dd/mm/yyyy",
								));
							}
							?>
							
							</div>
						</td>
						<td class="control-group interest_rate">
								<?php	echo $this->Form->input(
									'interest_rate', array(
										'label'     => false,
										'id'	=> false,
										'div'	=> false,
										'name'	=> 'data[Transaction][interest_rate]',
										'class' => 'interest_rate',
										'default'=> $trn['interest_rate'],
										'required' => $automatic_fixing
								));
								?>
						</td>
						<td class="control-group date_basis">
							<?php
								echo $this->Form->input(
									'date_basis', array(
										'label'     => false,
										'id'	=> false,
										'div'	=> false,
										'name'	=> 'data[Transaction][date_basis]',
										'class' => 'TransactionDateBasis',
										'options'   => array(
											"Act/360"	=>	"Act/360",
											"Act/365"	=>	"Act/365",
											"30/360"	=>	"30E/360"
										) ,
										'empty'		=> __('-- Choose one --'),
										'default'=> $trn['date_basis'],
										'required' => $automatic_fixing
								));
							?>
						</td>
						<td class="control-group total_interest">
							<?php
							if(empty($callable))
							{
								echo $this->Form->input(
									'Transaction.total_interest', array(
										'type'		=> "text",
										'label'     => false,
										'class'		=> 'TransactionTotalInterest',
										'value'		=> $trn['total_interest'],
								));
							}	
							?>
						</td>
						<td class="control-group tax_amount">
							<div class="input-prepend">
							<?php
							if(empty($callable))
							{
								echo $this->Form->input(
									'Transaction.tax_amount', array(
										'type'		=> "text",
										'label'     => false,
										'class'		=> 'TransactionTaxAmount',
										'value'		=> $trn['tax_amount'],
								));
								echo '<button class="refreshTaxAmount" class="btn" type="button"> <i class="icon-refresh"></i></button>';
							}
							?>
							</div>
						</td>
						<td class="control-group reference_rate optional" style="position: relative;">
							<?
							echo $this->Form->input(
								'Transaction.reference_rate', array(
									'type'		=> "text",
									'label'     => false,
									'value'		=> $trn['reference_rate'],
									'class'		=> 'TransactionRefRate',
							));
							?>
							<span class="infotip benchmark">
								<span class="icon icon-info-sign"></span>
								<div class="content"><?php print $trn['benchmark'] ?></div>
							</span>
						</td>
						<td class="control-group action">
							<?php
							echo $this->Form->input(
								'Transaction.tr_number', array(
									'type'		=> "hidden",
									'label'     => false,
									'class'		=> 'TransactionNum',
									'value'		=> $trnum,
							));
							echo $this->Form->input(
								'Transaction.tr_state', array(
									'type'		=> "hidden",
									'label'     => false,
									'value'		=> "Confirmation Received",
							));
							?>
							<a href="#" class="btn btn-primary register-btn formQueueLaunchOne">Register</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="span11" id="week_end_date_error">
			
		</div>
		<div class="span11">

			<input type="submit" class="btn btn-success pull-right formQueueLaunchAll" value="Register All Confirmations"/>
			<a href="<?php print Router::url('/treasury/treasurydepositinstructions/displaydi') ?>" class="btn btn-default pull-right" style="margin-right: 10px;">Back to Display DI</a>
		</div>
		
	<?php endif ?>

<style>
 table input[type="text"]{ width: 50px; height: 20px !important; background: #fff; border: 0; box-shadow: none !important; outline: none 0 !important; margin: 0; border: #eee 1px solid; }
 table input.datepckr{ width: 70px;}
 table select{ width: 140px; margin: 0; }
 table .input-prepend{ margin: 0; }
 table td{ vertical-align: center !important; }
 table th{ vertical-align: middle !important; }

 #downloadInstruction td input, #downloadInstruction td select{ border-color: #49afcd !important; }
 #downloadInstruction td.optional input, #downloadInstruction td.optional select { border-color: #d9edf7 !important; }
 
 .attachform{ margin-top: 10px; }
 table input.TransactionExternalRef{ width: 30px; }
 table input.TransactionExternalRef:focus{ width: 150px; }
 .infotip{ position: relative; margin-left:5px;  }
 .infotip .content{  min-width: 130px; display: none; position: absolute; background: #fff; border: 1px solid #eee; padding: 10px; z-index: 100; }
 .infotip:hover .content{ display: block; }

 .infotip.benchmark{ position: absolute; top: 8px; right: 0; }
 .infotip.benchmark .content{ right: 0; }
  table input.TransactionRefRate{ margin-right: 20px; }
  td.amount{ text-align: right; }
  #week_end_date_error { color: red; }
  .TransactionTotalInterest { width: 8em !important; }
  .TransactionTaxAmount { width: 8em !important; }
</style>
<script>
$(document).ready(function(e){

	var errors = [];
	var success = [];
	var reporturl = '';

	var ajaxpath = '';
	if($('#processformcontainer').attr('data-ajaxbasepath')) ajaxpath=$('#processformcontainer').attr('data-ajaxbasepath');

	//attachment show/hide
	$('.attachbt').bind('click', function(e){
		$('.attachform').toggle();
		e.preventDefault();
		return false;
	})
	$('.attachform').hide();
	
	

	//datepicker
	$('input.datepckr').each(function(i, item){
		item.dtpckr = $(item).datepicker({ dateFormat: 'dd/mm/yy' }).on('changeDate', function(ev) {
			item.dtpckr.hide(); 
		}).data('datepicker');
	});
	
	
	function isWeekEnd(date)
	{
		var date_array = date.split("/");
		var date_YMD = date_array[2] + '-' + date_array[1] + '-' + date_array[0];
		var d = new Date();
		d.setTime(Date.parse(date_YMD));
		var n = d.getDay();
		return ((n == 6) || (n == 0));
	}

	if ($("input.datepckr").length > 0)
	{
		//editable date
		$(".datepckr").change(function(e){
			var el = $(e.target);
			if (el.val() == '')
			{
				return 0;
			}
			if ( isWeekEnd(el.val()) )
			{
				var tr_num = "";
				tr_num = el.parents("tr").first().attr('data-trnum');
				$("#week_end_date_error").html("The maturity date of TRN " + tr_num + " falls on a weekend.");
			}
			else
			{
				$("#week_end_date_error").empty();
			}
		});
	}
	$(".datepckr").change();
	

	//number format
	$('.TransactionInterestRate, .TransactionRefRate').autoNumeric('init',{aSep: ',',aDec: '.', vMin:-9999999999999.999});
	$('.TransactionTaxAmount, .TransactionTotalInterest').autoNumeric('init',{aSep: ',',aDec: '.', vMin:-9999999999999.99});

	//rates calculation
	$('.refreshTaxAmount').bind('click', function(e){
		var line = $(this).parents('.formQueueLine');
		var datas = formQueueGetAllFields(line);
		
		$.ajax({context: {line: line}, data: datas, url: ajaxpath+'/calcTotalInterest?'})
		.done(function( data ){
			data = $.trim(data.split('(').join('').split(')').join('').split('?').join(''));
			
			if(data && data!='.' && data!='-' && data.length<20){
				if(data=='0') data='0.00';
				$('input[name="data[Transaction][total_interest]"]', this.line).val(data);

				datas = formQueueGetAllFields(this.line);
				
				$.ajax({context: {line: this.line}, data: datas, url: ajaxpath+'/computeTaxFromInterestAndMandateCpty?'})
				.done(function( data ){
					if(!data) data='0.00';
					$('input[name="data[Transaction][tax_amount]"]', this.line).val(data);
				});
			}
			
		});

		e.preventDefault();
		return false;
	});
	
	// form queueing
	var formQueue = [];
	$('.formQueueLaunchOne').bind('click', function(e){
		var line = $(this).parents('.formQueueLine');
		formQueue = [];
		formQueue.push(formQueueGetAllFields(line));
		formQueueLaunch();

		e.preventDefault();
		return false;
	});
	$('.formQueueLaunchAll').bind('click', function(e){
		formQueue = [];
		$('.formQueueLine').each(function(i, line){
			if($('.formQueueLaunchOne', line).length){
				formQueue.push(formQueueGetAllFields(line));
			}
		})
		formQueueLaunch();

		e.preventDefault();
		return false;
	});
	function formQueueGetAllFields(parent){
		var fields = {};
		$('input[name], select[name], textarea[name]', parent).each(function(i, field){
			fields[$(field).attr('name')] = $(field).val();
		});
		return fields;
	}
	function formQueueLaunch(){
		if(formQueue.length){
			errors = [];
			success = [];
			$('#processformcontainer .alert-error').remove();
			$('#downloadInstruction tr.error').removeClass('error');
			$('#downloadInstruction tr.successcheck').removeClass('successcheck');
			$('#downloadInstruction .error-message').remove();
			$('#downloadInstruction td').removeClass('error');
			
			formQueueNext();
		}
	}
	function formQueueNext(){
		if(formQueue.length){
			//build the temporary form
			$('#queueform').html('');
			var currentStepDatas = formQueue.shift();

			$.ajax({
			  type: "POST",
			  data: currentStepDatas,
			}).done(function( data ){
				formQueueLoaded(data);
			});
		}else{
			if(!errors.length){
				//if no errors, and then only success, redirect to a global report page
				if(success.length>0 && reporturl){
					window.location = reporturl+'/'+success.join(',')+'/'+$('#downloadInstruction').attr('data-di');
				}
			}
		}
	}
	function formQueueLoaded(data){
		var html = $('<div/>').html(data);
		var process = $('#processformcontainer', html);
		var linenum = $('#formprocess', process).attr('data-trnum');
		var row = $('#rowTrn'+$('#formprocess', process).attr('data-trnum'));

		//$('#processformcontainer').append( $(process).html() );
		
		//new request: display only message if its an error
		if($('#formprocess',process).attr('data-result')=='error'){
			var errormsg = 'Please fill in all mandatory* fields';
			$('ul.errorlist li', process).each(function(i, li){
				var field = $(li).attr('data-field');
				var error = $(li).text();

				//add error class to the criminal field
				$('td.'+field, row).addClass('error');
				errors.push(field);
			});
			$('ul.errorlist', process).remove();

			if(!$('#alertmandatory').length) $('#processformcontainer').append('<div id="alertmandatory" class="alert alert-error"><button type="button" data-dismiss="alert" class="close">×</button><span><strong>'+errormsg+'</strong></span></div>');
			$(row).addClass('error');
		}else{
			var link = $('<div/>');
			var trnum = $('.trnum', process).text();

			if(!reporturl){
				if($('.trreport', process).length && trnum){
					if($('.trreport', process).attr('href')){
						var tmp = $('.trreport', process).attr('href').split('/'+trnum);
						if(tmp[0]) reporturl = tmp[0];
					}
				}
			}
			success.push(trnum);

			$(link).append( trnum+' ' );
			$(link).append( $('.trreport', process) );

			$('td.action',row).html('');
			$('td.action',row).append(link);
			
			$(row).addClass('success');
			$('input, select',row).attr('disabled','disabled');
		}

		//disable the proceeded line
		/*if($('#formprocess',process).attr('data-trnum')){
			var row = $('#rowTrn'+$('#formprocess', process).attr('data-trnum'));

			if($('#formprocess',process).attr('data-result')=='success'){
				$('td',row).css('background-color', '#DFF0D8');
				$('input, select',row).attr('disabled','disabled');
				$('.formQueueLaunchOne', row).remove();
			}else{
				$(row).addClass('error');
				$('td',row).css('background-color', '#F2DEDE');
			}
		}*/

		//next line (if queue)!
		formQueueNext();
	}
})
</script>
