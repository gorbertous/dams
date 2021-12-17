<fieldset>
<legend>
	Ratings
	<?php
	$perms = $this->Permission->getPermissions();
	if ($perms['is_admin'] || $perms['is_risk'])
	{
		print $this->Html->link(
	    '<i class="icon-plus"></i> New Entry',
	    'rating',
	    array('class' => 'btn pull-right', 'escape'=>false)
		);
	}
	?>
</legend>
	<table id="ratingsTable" class="table table-bordered table-striped table-hover table-condensed">
		<thead><tr>
			<th><?php echo $this->Paginator->sort('id', '#', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('pirat_number', 'PiRat#', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('pirat_cpty_name', 'Counterparty name', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('created', 'Created', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('modified', 'Updated', array('url' => array('page' => 1))) ?></th>
			<th></th>
		</tr></thead>
		<tbody>
			<?php foreach($rows as $key=>$row): ?>
				<tr>
					<td><?php print UniformLib::uniform($row['Rating']['id'], 'id') ?></td>
					<td><?php print !empty($row['Rating']['pirat_number'])?UniformLib::uniform($row['Rating']['pirat_number'], 'pirat_number'):'' ?></td>
					<td><?php print UniformLib::uniform($row['Rating']['pirat_cpty_name'], 'pirat_cpty_name') ?></td>
					<td><?php print UniformLib::uniform($row['Rating']['created'], 'created') ?></td>
					<td><?php print UniformLib::uniform($row['Rating']['modified'], 'modified') ?></td>
					<td class="actions">
						<?php
						print $this->Html->link(
						'<i class="icon-pencil"></i>',
						'rating/'.$row['Rating']['id'],
						array('class' => 'btn btn-mini', 'escape'=>false)
						);
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