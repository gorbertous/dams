<?php
	// echo $this->Html->css('/treasury/css/radio-fx');
    // echo $this->Html->script('/treasury/js/radio-fx');
	echo $this->Html->css('/treasury/css/dataTableSort');
	echo $this->Html->script('/treasury/js/radio-fx-replacement');
?>
<div class="tabbable">
	<ul class="nav nav-tabs">
		<li class ="active">
			<a href="#tab1" data-toggle="tab">Opened</a>
 		</li>
 		<li>
 			<a href="#tab2" data-toggle="tab">Closed</a>
 		</li>
 	</ul>

 	<div class="tab-content">
  		<div class= "tab-pane active" id="tab1">
   			<div id="openedReinvs">
				<?php
					if(isset($openedreinvs) && is_array($openedreinvs)){
						echo
							'<table id="selectOpenedReinvs" class="table table-bordered table-striped">
	    					<thead>
		 						<tr>
									<th> Select </th>
									<th> Reinv </th>
									<th> Availibility Date </th>
			    					<th> Mandate </th>
			 						<th> Compartment </th>
			 						<th> Counterparty </th>
									<th> Funds left A </th>
			    					<th> Funds left B </th>
	    						</tr>
	 						</thead>';

						echo '<tbody>';
						foreach ($openedreinvs as $key => $value) { 
							echo '<tr>';
							echo '<td>';
							echo $this->Form->input(//just for CSRF
									'reinvreportform.Open', array(
										'type'		=> 'hidden',
										'label'     => false,
										'div'		=> false,
								));
							echo '<input class="radio-fx origin_radio" type="radio" name="data[reinvreportform][Open]" value="'.$key.'" id="data[reinvreportform][Open]">';
							echo '</td>';
							echo '<td>'.$key.'</td>';
							echo '<td>'.UniformLib::uniform($value['availability_date'], 'availability_date').'</td>';
							echo '<td>'.UniformLib::uniform($value['mandate_name'], 'mandate_name').'</td>';
							echo '<td>'.UniformLib::uniform($value['cmp_name'], 'cmp_name').'</td>';
							echo '<td>'.UniformLib::uniform($value['cpty_name'], 'cpty_name').'</td>';
							echo '<td style="text-align:right;">'.UniformLib::uniform($value['amount_leftA'], 'amount_leftA').' '.UniformLib::uniform($value['ccy'], 'ccy').'</td>';
							echo '<td style="text-align:right;">'.UniformLib::uniform($value['amount_leftB'], 'amount_leftB').' '.UniformLib::uniform($value['ccy'], 'ccy').'</td>';
							echo '</tr>';
						}
						echo '</tbody>';
						echo '</table>';
					}
					else
					{
						
						echo '<div class="well alert-info">'.$openedreinvs.'</div>';
					}
				?>
				<div id="OpenedReinvestsResult" class="radio-form" style="overflow:auto;">
				</div>
   			</div>
    	</div>

    	<div class= "tab-pane" id="tab2">
	    	<div id="closedReinvs">	
				<?php
					if(isset($closedreinvs) && is_array($closedreinvs)){
						echo
							'<table id="selectClosedReinvs" class="table table-bordered table-condensed">
							<thead>
			 					<tr>
				 					<th> Select </th>
									<th> Reinv </th>
									<th> Availibility Date </th>
		 							<th> Mandate </th>
		 							<th> Compartment </th>
		 							<th> Counterparty </th>			
									<th> Funds </th>
			 					</tr>
		 					</thead>';
						
						echo '<tbody>';
						foreach ($closedreinvs as $key => $value) {
							echo '<tr>';
							echo '<td>';
							echo $this->Form->input(
									'reinvreportform.Closed', array(
										'type'		=> 'radio',
										'label'     => false,
										'options'		=> $key,
										'class'		=> 'radio-fx origin_radio',
										'id'		=> 'data[reinvreportform][Closed]',
								));
							echo '</td>';
							echo '<td>'.$key.'</td>';
							echo '<td>'.UniformLib::uniform($value['availability_date'], 'availability_date').'</td>';
							echo '<td>'.UniformLib::uniform($value['mandate_name'], 'mandate_name').'</td>';
							echo '<td>'.UniformLib::uniform($value['cmp_name'], 'cmp_name').'</td>';
							echo '<td>'.UniformLib::uniform($value['cpty_name'], 'cpty_name').'</td>';
							echo '<td style="text-align:right;">'.UniformLib::uniform($value['groupReinvFunds'], 'groupReinvFunds').'</td>';
							echo '</tr>';
						}
						echo '</tbody>';
						echo '</table>';
					}

					else 
						echo '<div class="well alert-info">'.$closedreinvs.'</div>';
				?>
	    		<div id="ClosedReinvestsResult" class="radio-form" style="overflow:auto;">
	    		</div>
    		</div>
    	</div>
	</div>
</div>

<?php 
	echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/jquery.dataTables.js');
	echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/dataTables.bootstrap.js');
	echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/extras/TableTools/media/js/ZeroClipboard.js');
	//echo $this->Html->script('/theme/Cakestrap/js/libs/jquery-ui.min.js');
?>
<div style="display:none;">
<?php
echo $this->Form->create('open', array('url'=>'/treasury/treasuryreinvestments/reinvreportresult/Open'));
echo $this->Form->input('reinvreportform.Open', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();
echo $this->Form->create('close', array('url'=>'/treasury/treasuryreinvestments/reinvreportresult/Closed'));
echo $this->Form->input('reinvreportform.Closed', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();
?>
</div>
<script type="text/javascript">

$(document).ready(function () {


	$("#selectOpenedReinvs input").bind('change', function (event) {
		$('#openReinvreportformForm #reinvreportformOpen').val(  );
		$.ajax({async:true, data:$("#openReinvreportformForm").serialize(), 
			dataType:"html", 
			success:function (data, textStatus) {
				$("#OpenedReinvestsResult").html(data);
			}, 
			type:"post", 
			url:"\/treasury\/treasuryreinvestments\/reinvreportresult\/Open"});
			return false;
		});


	$("#selectClosedReinvs input").bind('change', function (event) {
		$.ajax({async:true, data:$("#closeReinvreportformForm").serialize(), 
			dataType:"html", 
			success:function (data, textStatus) {
				$("#ClosedReinvestsResult").html(data);}, 
				type:"post", 
				url:"\/treasury\/treasuryreinvestments\/reinvreportresult\/Closed"});
				return false;
	});

	$("#selectClosedReinvs").dataTable({
		    "aLengthMenu": [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
		    "iDisplayLength" : 5,
		    "bInfo": true,
	});

	$("#selectOpenedReinvs").dataTable({
		    "aLengthMenu": [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
		    "iDisplayLength" : 5,
		    "bInfo": false,
	});

});

/*$('#selectOpenedReinvs tr').click(function () {
	
    	$(this).find('td input:radio:checked');
    	$('#selectOpenedReinvs tr').removeClass("active");
    	$(this).addClass("active");
});

$('#selectClosedReinvs tr').click(function () {
    
    	$(this).find('td input:radio:checked');
    	$('#selectClosedReinvs tr').removeClass("active");
    	$(this).addClass("active");
});*/
</script>
