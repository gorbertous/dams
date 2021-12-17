<fieldset>
<legend>
	Counterparties
	<?php
	$perms = $this->Permission->getPermissions();
	if ($perms['is_admin'] || $perms['is_validator'] || $perms['is_operator'])
	{
		print $this->Html->link(
		    '<i class="icon-plus"></i> New counterparty',
		    'counterparty',
		    array('class' => 'btn pull-right', 'escape'=>false)
		);
	}
	?>
</legend>
	<table id="counterpartiesTable" class="table table-bordered table-striped table-hover table-condensed">
		<thead><tr>
			<th><?php echo $this->Paginator->sort('cpty_name', 'Name', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('cpty_code', 'Code', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('pirat_number', 'PiRat#', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('cpty_country', 'Country', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('contact_person1', 'Contact 1', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('created', 'Created', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('modified', 'Updated', array('url' => array('page' => 1))) ?></th>
			<th></th>
		</tr></thead>
		<tbody>
			<?php foreach($rows as $key=>$row): ?>
				<tr>
					<td><?php print UniformLib::uniform($row['Counterparty']['cpty_name'], 'cpty_name') ?></td>
					<td><?php print UniformLib::uniform($row['Counterparty']['cpty_code'], 'cpty_code') ?></td>
					<td><?php print UniformLib::uniform($row['Counterparty']['pirat_number'], 'pirat_number') ?></td>
					<td><?php print UniformLib::uniform($row['Counterparty']['cpty_country'], 'cpty_country') ?></td>
					<td><?php print UniformLib::uniform($row['Counterparty']['contact_person1'], 'contact_person1') ?></td>
					<td><?php print UniformLib::uniform($row['Counterparty']['created'], 'created') ?></td>
					<td><?php print UniformLib::uniform($row['Counterparty']['modified'], 'modified') ?></td>
					<td class="actions">
						<?php
						if ($perms['is_admin'] || $perms['is_risk'] || $perms['is_validator'] || $perms['is_operator'])
						{
							print $this->Html->link(
							    '<i class="icon-pencil"></i>',
							    'counterparty/'.$row['Counterparty']['cpty_ID'],
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