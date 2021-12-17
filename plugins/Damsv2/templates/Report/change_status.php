<fieldset>
	<legend>Portfolio status</legend>
	<?php echo $this->Form->create('filters', array('id'=>'FiltersForm', 'class'=>'form-inline')); ?>
	<?php echo $this->Form->input('Product.product_id', array(
		'label'		=> false, 'div' => false,
		'empty' 	=> '-- Any product --',
		'options' 	=> $products,
		'default'	=> $this->Session->read('Form.data.Product.product_id')
	)); ?>
	<?php echo $this->Form->input('Portfolio.portfolio_id', array(
		'label'		=> false, 'div' => false,
		'empty' 	=> '-- Any portfolio --',
		'options' 	=> $portfolios,
		'default'	=> $this->Session->read('Form.data.Portfolio.portfolio_id')
	)); ?>
	<?php echo $this->Form->end(); ?>
	<table class="table table-striped">
		<thead>
			<th>#</th>
			<th>Porfolio</th>
			<th>Status</th>
			<th>Action</th>
		</thead>
		<tbody>
	<?php foreach ($portfolio_status as $portfolio): ?>
			<tr>
				<td><?php echo $portfolio['Portfolio']['portfolio_id'] ?></td>
				<td><?php echo $portfolio['Portfolio']['portfolio_name'] ?></td>
				<td>
					<p class="label <?php switch ($portfolio['Portfolio']['status_portfolio']) {
						case 'OPEN':
							echo 'label-success';
							break;
						case 'EARLY TERMINATED':
							echo 'label-warning';
							break;

						default:
							echo 'label-default';
							break;
					} ?>"><?php echo $portfolio['Portfolio']['status_portfolio']; ?></p>
				</td>
				<td>
				<?php if ($portfolio['Portfolio']['status_portfolio'] == 'CLOSED'){
							echo $this->Form->input('status', array('value'=>$portfolio['Portfolio']['status_portfolio'], 'label' => '', 'disabled' => true, 'style'=>'padding:0;'));
						}
						else 
						{
				?>
					<?php echo $this->Form->create('Portfolio', array('inputDefaults' => array('label' => false))) ?>
						<?php echo $this->Form->input('portfolio_id', array('type'=>'hidden', 'value'=>$portfolio['Portfolio']['portfolio_id'])) ?>
						<?php echo $this->Form->input('status_portfolio', array('type'=>'hidden', 'value'=>$portfolio['Portfolio']['status_portfolio'])) ?>
						<?php echo $this->Form->input('status', array(
							'options'=>array(
								'OPEN' 				=> 'Open',
								'EARLY TERMINATED' 	=> 'Early terminated',
							),
							'empty' => '-- Change status --',
							'onchange' => '$("#loading").modal();'
						)) ?>
					<?php echo $this->Form->end() ?>
					<?php } ?>
				</td>
			</tr>
	<?php endforeach ?>
		</tbody>
	</table>
	<?php echo $this->Paginator->counter(
    'Page {:page} of {:pages}, showing {:current} records out of
     {:count} total, starting on record {:start}, ending on {:end}'
); ?>

	<div class="pagination">
	    <ul>
	        <?php 
	            echo $this->Paginator->prev( '<<', array( 'class' => '', 'tag' => 'li' ), null, array( 'class' => 'disabled myclass', 'tag' => 'li' ) );
	            echo $this->Paginator->numbers( array( 'tag' => 'li', 'separator' => '', 'currentClass' => 'disabled myclass' ) );
	            echo $this->Paginator->next( '>>', array( 'class' => '', 'tag' => 'li' ), null, array( 'class' => 'disabled myclass', 'tag' => 'li' ) );
	        ?>
	    </ul>
	</div>

</fieldset>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$("select").change(function(event) {
			$(this).closest('form').trigger('submit');
		});

	});
</script>