<fieldset>
<legend>
	<span class="pull-left">DI Settlement</span>
	<?php
	echo $this->Form->create('cpty', array('class'=>'pull-left'));
	$perms = $this->Permission->getPermissions();
	?>
	<?php print $this->Form->input('cpty_id', array(
		'label'		=> false,
		//'class'		=> 'span12',
		'div'		=> false,
		'options'	=> array('-- All Counterparties --')+$counterparties,
		//'default'	=> $row['CustomText']['cpty_id'],
		//'after'		=> '<span class="help-block addnewform text-right"><a href="'.Router::url('counterparty?from='.$this->here).'">+ Add a new Counterparty</a></span>'
	)); ?>
	<?php echo $this->Form->end() ?>
	<?php print $this->Html->link(
	    '<i class="icon-plus"></i> New settlement',
	    'custom_text',
	    array('class' => 'btn pull-right', 'escape'=>false)
	); ?>
</legend>
	<table id="customTextTable" class="table table-bordered table-striped table-hover table-condensed">
		<thead><tr>
			<th><?php echo $this->Paginator->sort('cpty_name', 'Counterparty', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('dropdown_txt', 'Dropdown', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('custom_txt', 'DI Settlement', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('created', 'Created', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('modified', 'Updated', array('url' => array('page' => 1))) ?></th>
			<th></th>
		</tr></thead>
		<tbody>
			<?php foreach($rows as $key=>$row): ?>
				<?php $ctxt = $this->Text->truncate($row['CustomText']['custom_txt'], 90, array(
			        'ellipsis' => '...',
			        'exact' => false
			    )); ?>
				<tr>
					<td><?php print UniformLib::uniform($row['Counterparty']['cpty_name'], 'cpty_name') ?></td>
					<td><?php print UniformLib::uniform($row['CustomText']['dropdown_txt'], 'dropdown_txt') ?></td>
					<td><?php print UniformLib::uniform($ctxt, 'custom_txt') ?></td>

					<td><?php print UniformLib::uniform($row['CustomText']['created'], 'created') ?></td>
					<td><?php print UniformLib::uniform($row['CustomText']['modified'], 'modified') ?></td>
					<td class="actions">
						<?php
						if ($perms['is_admin'] || $perms['is_validator'] || $perms['is_operator'])
						{
							print $this->Html->link(
							    '<i class="icon-pencil"></i>',
							    'custom_text/'.$row['CustomText']['custom_id'],
							    array('class' => 'btn btn-mini', 'escape'=>false)
							); 
						}?>
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
</fieldset>
<style>
#cptyCustomTextsForm{ margin: 0 0 0 15px; }
legend{ float: left; height: auto; width: 100%; }
</style>
<script>
$(document).ready(function(e){
	$('#cptyCptyId').bind('change', function(e){
		$(this).parents('form').submit();
	});
})
</script>