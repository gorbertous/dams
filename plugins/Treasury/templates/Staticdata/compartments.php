<fieldset>
<legend>
	Compartments
	<?php
	$perms = $this->Permission->getPermissions();
	if (!$perms['is_risk'])
	{
		print $this->Html->link(
			'<i class="icon-plus"></i> New compartment',
			'Compartment',
			array('class' => 'btn pull-right', 'escape'=>false)
		);
	} ?>
</legend>
	<table id="compartmentsTable" class="table table-bordered table-striped table-hover table-condensed">
		<thead><tr>
			<th><?php echo $this->Paginator->sort('cmp_ID', '#', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('cmp_name', 'Name', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('cmp_dpt_code_value', 'Department code', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('cmp_sof_value', 'Source of funding', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('created', 'Created', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('modified', 'Updated', array('url' => array('page' => 1))) ?></th>
			<th></th>
		</tr></thead>
		<tbody>
			<?php foreach($rows as $key=>$row): ?>
				<tr>
					<td class="id"><?php print UniformLib::uniform($row['Compartment']['cmp_ID'], 'cmp_ID') ?></td>
					<td><?php print UniformLib::uniform($row['Compartment']['cmp_name'], 'cmp_name') ?></td>
					<td><?php print UniformLib::uniform($row['Compartment']['cmp_dpt_code_value'], 'cmp_type') ?></td>
					<td class="cmp_value"><?php print UniformLib::uniform($row['Compartment']['cmp_sof_value'], 'cmp_value') ?></td>

					<td><?php print UniformLib::uniform($row['Compartment']['created'], 'created') ?></td>
					<td><?php print UniformLib::uniform($row['Compartment']['modified'], 'modified') ?></td>
					<td class="actions">
						<?php
						if ($perms['is_admin'] || $perms['is_validator'] || $perms['is_operator'])
						{
							print $this->Html->link(
							    '<i class="icon-pencil"></i>',
							    'compartment/'.$row['Compartment']['cmp_ID'],
							    array('class' => 'btn btn-mini', 'escape'=>false)
							);
						} ?>
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