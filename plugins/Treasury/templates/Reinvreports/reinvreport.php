<?php
	echo $this->Html->css('/treasury/css/dataTableSort');
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
   				<?php //echo $this->Form->create('reinvestreportformo') ?>
				<!--<input type="hidden" name="data[reinvestreportformo][opened]" id="openreinvestformOpened">-->

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
									<th> Amount left A </th>
			    					<th> Amount left B </th>
	    						</tr>
	 						</thead>';

						echo '<tbody>';
						foreach ($openedreinvs as $key => $value) {
							echo '<tr>';
							echo '<td>';
							echo $this->Form->input(
									'reinvestreportformo.opened', array(
										'type'		=> 'radio',
										'label'     => false,
										'value'		=> $key,
										'id'		=> 'data[reinvestreportformo][opened]',
								));
							echo '</td>';
							echo '<td>'.$key.'</td>';
							echo '<td>'.UniformLib::uniform($value['availability_date'], 'availability_date').'</td>';
							echo '<td>'.UniformLib::uniform($value['mandate_name'], 'mandate_name').'</td>';
							echo '<td>'.UniformLib::uniform($value['cmp_name'], 'cmp_name').'</td>';
							echo '<td>'.UniformLib::uniform($value['cpty_name'], 'cpty_name').'</td>';
							echo '<td style="text-align:right;">'.UniformLib::uniform($value['amount_leftA'], 'amount_leftA').'</td>';
							echo '<td style="text-align:right;">'.UniformLib::uniform($value['amount_leftB'], 'amount_leftB').'</td>';
							echo '</tr>';
						}
						echo '</tbody>';
						echo '</table>';
					}

					else
						echo '<div class="well alert-info">'.$openedreinvs.'</div>';
				?>
				<div id="OpenedReinvestsResult" style="overflow:auto;">
				</div>
   			</div>
    	</div>

    	<div class= "tab-pane" id="tab2">
	    	<div id="closedReinvs">
	    		<?php //echo $this->Form->create('reinvestreportformc') ?>
<?php echo $this->Form->input(
									'reinvestreportformc.closed', array(
										'type'		=> 'hidden',
										'label'     => false,
										'id'		=> 'openreinvestformClosed',
								)); ?>
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
									<th> Amount </th>
			 					</tr>
		 					</thead>';

						echo '<tbody>';
						foreach ($closedreinvs as $key => $value) {
							echo '<tr>';
							echo '<td>';
							echo $this->Form->input(
									'reinvestreportformc.closed', array(
										'type'		=> 'radio',
										'label'     => false,
										'value'		=> $key,
										'id'		=> 'data[reinvestreportformc][closed]',
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
	    		<div id="ClosedReinvestsResult" style="overflow:auto;">
	    		</div>
    		</div>
    	</div>
	</div>
</div>

<script type="text/javascript">
/*
	$(document).ready(function () {

		$('#selectClosedReinvs tr').change( function() {
        	$(this).toggleClass('row_selected');
    	});


	});
*/

</script>


<?php /*
	$this->Js->get('#selectOpenedReinvs input')->event('change',
		$this->Js->request(
			array(
				'controller'=>'treasuryreinvreports',
				'action'=>'openedreport'
			),
			array(
				'update'=>'#OpenedReinvestsResult',
				'async' => true,
				'method' => 'post',
				'dataExpression'=>true,
				'data'=> $this->Js->serializeForm(
					array(
						'isForm' => true,
						'inline' => true,
						)
					)
				)
			)
		); */
 ?>



<?php /*
	$this->Js->get('#selectClosedReinvs input')->event('propertychange change',
		$this->Js->request(
			array(
				'controller'=>'treasuryreinvreports',
				'action'=>'closedreport'
			),
			array(
				'update'=>'#ClosedReinvestsResult',
				'async' => false,
				'method' => 'post',
				'dataExpression'=>true,
				'data'=> $this->Js->serializeForm(
					array(
						'isForm' => true,
						'inline' => true,
						)
					)
				)
			)
		);*/
 ?>

<?php
	echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/jquery.dataTables.js');
	echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/dataTables.bootstrap.js');
	echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/extras/TableTools/media/js/ZeroClipboard.js');
	//echo $this->Html->script('/theme/Cakestrap/js/libs/jquery-ui.min.js');
?>



<script type="text/javascript">

$(document).ready(function () {


	$("#selectOpenedReinvs input").bind('change', function (event) {
		$.ajax({async:true, data:$("#selectOpenedReinvs input").serialize(),
			dataType:"html",
			success:function (data, textStatus) {
				$("#OpenedReinvestsResult").html(data);
			},
			type:"post",
			url:"\/treasury\/treasuryreinvreports\/openedreport"});
			return false;
		});


	$("#selectClosedReinvs input").bind('change', function (event) {
		$.ajax({async:true, data:$("#selectClosedReinvs input").serialize(),
			dataType:"html",
			success:function (data, textStatus) {
				$("#ClosedReinvestsResult").html(data);},
				type:"post",
				url:"\/treasury\/treasuryreinvreports\/closedreport"});
				return false;
	});

	$("#selectClosedReinvs").dataTable({
			//"sDom": "<'row'<'span6'l><'span-'f>r>t<'row'<'span6'i><'span6'p>>",
			"sScrollY": "150px",
			"bPaginate": false,
		 	"bLengthChange": false,
		    "aLengthMenu": [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
		    "iDisplayLength" : 5,
		    "bInfo": false,
	});

});

$('#selectOpenedReinvs tr').click(function () {
    	//$(this).find('td input:radio').prop('checked', true);
    	$(this).find('td input:radio:checked');
    	$('#selectOpenedReinvs tr').removeClass("active");
    	$(this).addClass("active");
});

$('#selectClosedReinvs tr').click(function () {
    	//$(this).find('td input:radio').prop('checked', true);
    	$(this).find('td input:radio:checked');
    	$('#selectClosedReinvs tr').removeClass("active");
    	$(this).addClass("active");
});


</script>
