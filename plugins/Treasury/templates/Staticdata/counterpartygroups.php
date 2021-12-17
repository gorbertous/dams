<fieldset>
<legend>
	Risk Groups
	<?php
	$perms = $this->Permission->getPermissions();
	if ($perms['is_admin'] || $perms['is_risk'])
	{
		print $this->Html->link(
	    '<i class="icon-plus"></i> New Risk group',
	    'counterpartygroup',
	    array('class' => 'btn pull-right', 'escape'=>false)
		);
	}
	?>
</legend>
	<table id="counterpartygroupsTable" class="table table-bordered table-striped table-hover table-condensed">
		<thead><tr>
			<th><?php echo $this->Paginator->sort('counterpartygroup_ID', '#', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('counterpartygroup_name', 'Name', array('url' => array('page' => 1))) ?></th>
			<th>Counterparties</th>
			<th><?php echo $this->Paginator->sort('Head.cpty_name', 'Head Office', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('created', 'Created', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('modified', 'Updated', array('url' => array('page' => 1))) ?></th>
			<th></th>
		</tr></thead>
		<tbody>
			<?php foreach($rows as $key=>$row): ?>
				<tr>
					<td><?php print UniformLib::uniform($row['CounterpartyGroup']['counterpartygroup_ID'], 'counterpartygroup_ID') ?></td>
					<td><?php print !empty($row['CounterpartyGroup']['counterpartygroup_name'])?UniformLib::uniform($row['CounterpartyGroup']['counterpartygroup_name'], 'counterpartygroup_name'):'untitled' ?></td>
					<td><?php 
						$counterparties = array();
						if(!empty($row['Counterparties'])) foreach($row['Counterparties'] as $counterparty) if(!empty($counterparty['cpty_name'])) $counterparties[]=UniformLib::uniform($counterparty['cpty_name'], 'cpty_name');
						print join(', ', $counterparties); 
					?></td>
					<td>
						<?php print !empty($row['Head']['Counterparty']['cpty_name'])?UniformLib::uniform($row['Head']['Counterparty']['cpty_name'], 'cpty_name'):'' ?> 
					</td>
					<td><?php print UniformLib::uniform($row['CounterpartyGroup']['created'], 'created') ?></td>
					<td><?php print UniformLib::uniform($row['CounterpartyGroup']['modified'], 'modified') ?></td>
					<td class="actions">
						<?php
						if ($perms['is_admin'] || $perms['is_risk'])
						{
							print $this->Html->link(
						    '<i class="icon-pencil"></i>',
						    'counterpartygroup/'.$row['CounterpartyGroup']['counterpartygroup_ID'],
						    array('class' => 'btn btn-mini', 'escape'=>false)
							);
						}
						?>
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