<div id="processformcontainer" data-ajaxbasepath="<?php print Router::url(array(
	    'controller' => 'treasuryajax')) ?>"><?php 
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
				<?php echo $this->Html->link("DI <i class='icon-file'></i>", "/data/treasury/pdf/deposit_instruction_".$instr['Instruction']['instr_num'].".pdf", array('escape' => false, 'target' => '_blank')) ?>
			&nbsp;  

<?php if(!empty($instr['Instruction']['confirmation_file'])): ?>

				<?php echo $this->Html->link("Confirmation <i class='icon-file'></i>", "/data/treasury/pdf/".$instr['Instruction']['confirmation_file'], array('escape' => false, 'target' => '_blank')) ?>
<?php endif ?>
			</div>
		</div>

	<?php if(empty($trns)): ?>
		<div class="alert alert-info span11">
			There is no waiting confirmation for Instruction #<?php print $instr['Instruction']['instr_num'] ?>
		</div> 
		<?php if(empty($_GET['from'])): ?>
			<a href="<?php print Router::url('/treasury/treasurydepositinstructions/displaydi') ?>" class="btn btn-default pull-left" style="clear: both">Back to Display DI</a>
		<?php else: ?>
			<a href="<?php print Router::url('/treasury/treasurytransactions/validateconf') ?>" class="btn btn-default pull-right" style="margin-right: 10px;">Back</a>
		<?php endif ?>
	<?php else: ?>
		<table id="downloadInstruction" class="table table-bordered table-striped table-hover table-condensed">
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
				<th class="maturity">Maturity Date</th>
				<th class="int_rate">Int. Rate%</th>
				<th class="day_basis">Day Basis</th>
				<th class="interest">Interest</th>
				<th class="tax">Tax</th>
				<th class="reference_rate">Ref. Rate%</th>
				<th class="actions" style="min-width: 145px;">Actions</th>
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

						if(!isset($trn['date_basis']))$trn['date_basis']='';
						if(!empty($this->request->data['Transaction']['date_basis'])) $trn['date_basis']=$this->request->data['Transaction']['date_basis'];

						if(!isset($trn['total_interest']))$trn['total_interest']='';
						if(!empty($this->request->data['Transaction']['total_interest'])) $trn['total_interest']=$this->request->data['Transaction']['total_interest'];

						if(!isset($trn['tax_amount']))$trn['tax_amount']='';
						if(!empty($this->request->data['Transaction']['tax_amount'])) $trn['tax_amount']=$this->request->data['Transaction']['tax_amount'];

						if(!isset($trn['reference_rate']))$trn['reference_rate']='';
						if(!empty($this->request->data['Transaction']['reference_rate'])) $trn['reference_rate']=$this->request->data['Transaction']['reference_rate'];
					?>
					<tr class="formQueueLine" id="rowTrn<?php echo $trnum ?>" data-trnum="<?php echo $trnum ?>" data-date-com="<?php print $date_com->format('j-n-Y') ?>" data-date-mat="<?php print $date_mat->format('d-m-Y') ?>" data-currency="<?php echo $trn['currency'] ?>" >
						<td><?php echo $trnum ?></td>
						<td><?php echo $trn['tr_type']; ?></td>
						<td><?php echo $shortname ?></td>
						<td><?php echo $trn['amount']; ?></td>
						<td><?php echo $trn['currency']; ?></td>
						<td><?php echo $trn['commencement_date']; ?></td>
						<td><?php echo $trn['depo_term']; ?></td>
						<td><?php echo $trn['scheme']; ?></td>
						<td class="control-group"><?php print $trn['external_ref'] ?></td>
						<td class="control-group"><?php print $trn['maturity_date'] ?></td>
						<td class="control-group"><?php print $trn['interest_rate'] ?></td>
						<td class="control-group"><?php print $trn['date_basis'] ?></td>
						<td class="control-group"><?php print $trn['total_interest'] ?></td>
						<td class="control-group"><?php print $trn['tax_amount'] ?></td>
						<td class="control-group"><?php print $trn['reference_rate'] ?></td>
						<td class="control-group">
							<?php
							echo $this->Form->input(
								'tr_number', array(
									'type'		=> "hidden",
									'label'     => false,
									'value'		=> $trnum,
									'class'		=> 'TransactionNum',
							));
							?>
							<div class="btn-group" data-toggle="buttons-checkbox">
								<a href="#" class="btn btn-danger register-btn formQueueLaunchOne" data-action="reject">Reject</a>
								<a href="#" class="btn btn-success register-btn formQueueLaunchOne" data-action="validate">Validate</a>
							</div>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="span11">
			<input type="submit" class="btn btn-success pull-right formQueueLaunchAll" style="margin-left: 10px; " data-action="validate" value="Validate All Confirmations"/>
			<input type="submit" class="btn btn-danger pull-right formQueueLaunchAll" data-action="reject" value="Reject All Confirmations"/> 
		<?php if(empty($_GET['from'])): ?>
			<a href="<?php print Router::url('/treasury/treasurydepositinstructions/displaydi') ?>" class="btn btn-default pull-right" style="margin-right: 10px;">Back to Display DI</a>
		<?php else: ?>
			<a href="<?php print Router::url('/treasury/treasurytransactions/validateconf') ?>" class="btn btn-default pull-right" style="margin-right: 10px;">Back</a>
		<?php endif ?>
			
		</div>
		
	<?php endif ?>

<style>
 table input[type="text"]{ width: 50px; height: 20px !important; background: #fff; border: 0; box-shadow: none !important; outline: none 0 !important; margin: 0; border: #eee 1px solid; }
 table input.date{ width: 65px;}
 table select{ width: 65px; margin: 0; }
 table .input-prepend{ margin: 0; }
 table td{ vertical-align: middle !important; }
</style>
<script>
$(document).ready(function(e){

	// form queueing
	var formQueue = [];
	$('.formQueueLaunchOne').bind('click', function(e){
		var line = $(this).parents('.formQueueLine');
		formQueue = [];
		var datas = formQueueGetAllFields(line);
		if($(this).attr('data-action')) datas.action=$(this).attr('data-action');
		formQueue.push(datas);
		formQueueLaunch();

		e.preventDefault();
		return false;
	});
	$('.formQueueLaunchAll').bind('click', function(e){
		formQueue = [];
		var bt = this;
		$('.formQueueLine').each(function(i, line){
			if($('.formQueueLaunchOne', line).length){
				var datas = formQueueGetAllFields(line);
				if($(bt).attr('data-action')) datas.action=$(bt).attr('data-action');
				
				formQueue.push(datas);
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
			$('#processformcontainer').html('<div class="alert alert-info loading">Loading...</div>');
			$('#downloadInstruction tr td.error').css('background-color','');

			formQueueNext();
		}
	}
	function formQueueNext(){
		if(formQueue.length){
			
			//build the temporary form
			$('#queueform').html('');
			var currentStepDatas = formQueue.shift();

			var url = '';
			var urlbasepath = '/treasury/treasuryajax';
			if($('#processformcontainer').attr('data-ajaxbasepath')) urlbasepath=$('#processformcontainer').attr('data-ajaxbasepath');

			if(currentStepDatas.tr_number){
				if(currentStepDatas.action=='validate'){
					currentStepDatas.url = urlbasepath+'/confvalidate/'+currentStepDatas.tr_number+'/1';
				}else if(currentStepDatas.action=='reject'){
					currentStepDatas.url = urlbasepath+'/confreject/'+currentStepDatas.tr_number+'/1';
				}
			}

			if(currentStepDatas.url){
				$.ajax({
				  url: currentStepDatas.url,
				  type: "POST",
				  data: currentStepDatas,
				}).done(function( data ){
					formQueueLoaded(data);
				});
			}
		}
	}
	function formQueueLoaded(data){
		$('#processformcontainer .alert.loading').remove();

		if(datas = $.parseJSON(data)){
			if(datas.text){
				var css='success';
				if(datas.action=='confreject') css='danger';

				var msg = '<div id="formprocess" class="alert alert-'+css+'"><button class="close" data-dismiss="alert" type="button">×</button>';
  				msg+='<p>'+datas.text+'</p></div>';
  				$('#processformcontainer').append( msg );
			}

			if(datas.id){
				if($('#rowTrn'+datas.id).length){
					if(datas.action=='confreject') $('#rowTrn'+datas.id).css('background-color', '#F2DEDE');
					else if(datas.action=='confvalidate') $('#rowTrn'+datas.id).css('background-color', '#DFF0D8');
					$('#rowTrn'+datas.id+' .formQueueLaunchOne').remove();
				}
			}
		}

		//next line (if queue)!
		formQueueNext();
	}
})
</script>
