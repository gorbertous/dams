<fieldset>
<legend>
	Mandates pending
	<?php
	$perms = $this->Permission->getPermissions();
	/*if ($perms['is_admin'] || $perms['is_operator'] || $perms['is_validator'])
	{
		print $this->Html->link(
	    '<i class="icon-plus"></i> New mandate',
	    'mandate_edit',
	    array('class' => 'btn pull-right', 'escape'=>false)
		);
	}*/
	?>
</legend>
	<table id="mandatesTable" class="table table-bordered table-striped table-hover table-condensed">
		<thead><tr>
			<th><?php echo $this->Paginator->sort('mandate_name', 'Name', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('created', 'Created', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('modified', 'Updated', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('status', 'Active', array('url' => array('page' => 1))) ?></th>
			<th></th>
		</tr></thead>
		<tbody>
			<?php foreach($rows as $key=>$row): ?>
				<tr>
					<td><?php print UniformLib::uniform($row['MandatePending']['mandate_name'], 'mandate_name') ?></td>
					<td><?php print UniformLib::uniform($row['MandatePending']['created'], 'created') ?></td>
					<td><?php print UniformLib::uniform($row['MandatePending']['modified'], 'modified') ?></td>
					<td><input type="checkbox" disabled <?php if ($row['MandatePending']['status']== 'APPROVED' ){ echo "checked"; }?> ></td>
					<td class="actions">
						<?php
						print $this->Html->link(
						    '<i class="icon-pencil"></i>',
						    'mandate_approval/'.$row['MandatePending']['mandate_ID'],
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