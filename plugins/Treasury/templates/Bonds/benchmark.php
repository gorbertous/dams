<?php 
	echo $this->Html->script('/treasury/js/autoNumeric.js');

	if(!empty($updateSuccess['status'])){
		print '<div id="updateSuccess" class="alert alert-'.$updateSuccess['status'].'">'.$updateSuccess['message'].'</div>';
	}

	// FILTERS
	echo $this->Form->create('filters', array('id'=>'FiltersForm', 'class'=>'form-inline')); ?>
	
	
	<?php echo $this->Form->input('Transaction.mandate_ID', array(
		'label'		=> false, 'div' => false,
		'class' => 'mandate_ID',
		'type' => 'select',
		'empty' 	=> '-- Mandate --',
		'options' 	=> $instr_mandates,
		'default'	=> $this->Session->read('Form.data.Transaction.mandate_ID')
	)); ?>
	<?php echo $this->Form->input('Transaction.cpty_id', array(
		'label'		=> false, 'div' => false,
		'class' => 'cpty_ID',
		'type' => 'select',
		'empty' 	=> '-- Counterparty --',
		'options' 	=> $instr_counterparties,
		'default'	=> $this->Session->read('Form.data.Transaction.cpty_id')
	)); ?>
	<?php echo $this->Form->input('Transaction.cmp_ID', array(
		'label'		=> false, 'div' => false,
		'class' => 'cmp_id',
		'type' => 'select',
		'empty' 	=> '-- Compartment --',
		'options' 	=> $instr_cmp,
		'default'	=> $this->Session->read('Form.data.Transaction.cmp_ID')
	)); ?>
	<?php echo $this->Form->submit('Search', array('class' => 'btn btn-default')) ?>
	<?php echo $this->Form->end(); ?>

<table id="transactionsBenchmark" class="table table-bordered table-striped table-hover table-condensed">
	<thead>
		<th class="instr_num">TRN</th>
		<th class="di">DI</th>
		<th class="cmp">Compartment</th>
		<th class="cmmt_date">Cmmt Date</th>
		<th class="ccy">CCY</th>
		<th class="principal">Principal</th>
		<th class="interest">Int.Rate%</th>
		<th class="benchmark" width="165">Benchmark</th>
		<th class="refrate">Ref.Rate%</th>
		<th class="spread">Spread bp</th>
		<th class="action"></th>
	</thead>
	<tbody>
		<?php foreach ($transactions as $key => $tr): ?>
			<?php
				$spread = '';
				if(isset($tr['Transaction']['reference_rate']) && isset($tr['Transaction']['interest_rate'])){
					$spread = ($tr['Transaction']['interest_rate']-$tr['Transaction']['reference_rate'])*100;
				}
			?>
			<tr>
				<td class="instr_num"><?php print $tr['Transaction']['tr_number'] ?></td>
				<td class="di"><?php print $tr['Transaction']['instr_num'] ?></td>
				<td class="cmp"><?php print $tr['Compartment']['cmp_name'] ?></td>
				<td class="cmmt_date"><?php print $tr['Transaction']['commencement_date'] ?></td>
				<td class="ccy"><?php print $tr['AccountA']['ccy'] ?></td>
				<td class="principal"><?php print number_format($tr['Transaction']['amount'], 2) ?></td>
				<td class="interest"><?php print $tr['Transaction']['interest_rate'] ?></td>
				<td class="benchmark"><?php print $tr['AccountA']['ccy']." ".$tr['Transaction']['benchmark']; ?></td>
				<td class="reference_rate">
					<?php echo $this->Form->input('Transaction.reference_rate', 
						array('type'=>'text', 'value'=>$tr['Transaction']['reference_rate'])); ?>
				</td>
				<td class="spread">
					<?php echo $this->Form->input('Transaction.spread_bp', 
						array('type'=>'text','readonly'=>"readonly", 'value'=>$spread)); ?>
				</td>
				<td class="action">
					<?php echo $this->Form->input('Transaction.tr_number', 
						array('type'=>'hidden', 'value'=>$tr['Transaction']['tr_number'])); ?>
					<?php echo $this->Form->input('updateRefRate', 
						array('type'=>'hidden', 'value'=>'1')); ?>
					<a class="btn btn-primary register-btn formQueueLaunchOne" href="#">Update</a>
				</td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>

<?php echo $this->Paginator->counter(
    'Page {:page} of {:pages}, showing {:current} records out of
     {:count} total, starting on record {:start}, ending on {:end}'
); ?>
<?php if(intval($this->Paginator->counter('{:pages}'))>1): ?>
	<div class="pagination">
	    <ul>
	        <?php 
	            echo $this->Paginator->prev( '<<', array( 'class' => '', 'tag' => 'li' ), null, array( 'class' => 'disabled myclass', 'tag' => 'li' ) );
	            echo $this->Paginator->numbers( array( 'tag' => 'li', 'separator' => '', 'currentClass' => 'disabled myclass' ) );
	            echo $this->Paginator->next( '>>', array( 'class' => '', 'tag' => 'li' ), null, array( 'class' => 'disabled myclass', 'tag' => 'li' ) );
	        ?>
	    </ul>
	</div>
<?php endif ?>

<style type="text/css">

	#FiltersForm{ margin-bottom: 20px; }
	table input[type="text"]{ width: 60px; height: 20px !important; background: #fff; border: 0; box-shadow: none !important; outline: none 0 !important; margin: 0; border: #eee 1px solid; }
	table td{ vertical-align: middle !important; }

	#transactionsBenchmark tbody tr.successcheck td,
	#transactionsBenchmark tbody tr.success td{ background-color: #DFF0D8; }
	#transactionsBenchmark tbody tr.error td{ background-color: #f2dede; }

	#transactionsBenchmark td.spread input, 
	#transactionsBenchmark td.principal,
	#transactionsBenchmark td.reference_rate input{ text-align: right; }

	#transactionsBenchmark td.spreada{ position: relative; }
	#transactionsBenchmark td.spreada input{ margin-right: 40px; background: none transparent; border: 0; color: #232323 !important;}
	#transactionsBenchmark td.spreada button{ position: absolute; top: 7px; right: 8px; }

	.actions .btn.pull-right{ margin-left: 10px; }

</style>
<script>
$(document).ready(function(e){
	$('#FiltersForm select').bind('change', function(e){
		$(this).parents('form').submit();
	});
	$('#transactionsBenchmark td.spread input').each(function(i, input){
		calculateSpread(input);
	});
	$('#transactionsBenchmark td.reference_rate input').bind('keyup', function(e){
		calculateSpread($(this));
	});
	$('#transactionsBenchmark td.reference_rate input').bind('change', function(e){
		calculateSpread($(this));
	});

	$('#transactionsBenchmark td.reference_rate input').autoNumeric('init',{aSep: '',aDec: '.', mDec:3, vMin: -99999.999});

	function calculateSpread(input){
		var line = $(input).parents('tr');
		var spread = getSpread($('td.reference_rate input', line).val(), $('td.interest', line).text());

		$('td.spread input', line).val( spread );
	}

	function getSpread(reference_rate, int_rate){
		var spread = 'N/A';

		var interest = undefined;
		var refrate = undefined;
		if($.trim(reference_rate).length>0) refrate = parseFloat(reference_rate);
		if($.trim(int_rate).length>0) interest = parseFloat(int_rate);

		if(refrate!=undefined && interest!=undefined){
			var newval = (interest-refrate)*100;
			newval = parseFloat(newval).toFixed(1);
			if(newval>0) newval = '+'+newval;
			spread = newval;
		}

		return spread;
	}

	$('#transactionsBenchmark .formQueueLaunchOne').bind('click', function(e){
		var line = $(this).parents('tr');
		var datas = {};
		$('input', line).each(function(i, item){
			datas[$(item).attr('name')] = $(item).val();
		});

		$('#updateSuccess').remove();
		$.ajax({type: 'POST', context: {line: line}, data: datas})
		.done(function( data ){
			var html = $('<div/>').html(data);
			$(this.line).addClass('success');
			$('#updateSuccess').remove();
			$('#FiltersForm').before( $('#updateSuccess', html) );
		});

		e.preventDefault();
		return false;
	});
})

</script>