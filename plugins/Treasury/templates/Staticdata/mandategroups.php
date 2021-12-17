<fieldset>
<legend>
	Portfolios
	<?php
		$perms = $this->Permission->getPermissions();
		if ($perms['is_admin'] || $perms['is_risk'])
		{
			print $this->Html->link(
	    '<i class="icon-plus"></i> New Portfolio',
	    'mandategroup',
	    array('class' => 'btn pull-right', 'escape'=>false)
	);
	} ?>
</legend>
	<table id="mandategroupsTable" class="table table-bordered table-striped table-hover table-condensed">
		<thead><tr>
			<th><?php echo $this->Paginator->sort('mandategroup_ID', '#', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('mandategroup_name', 'Name', array('url' => array('page' => 1))) ?></th>
			<th>Mandates</th>
			<th><?php echo $this->Paginator->sort('created', 'Created', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('modified', 'Updated', array('url' => array('page' => 1))) ?></th>
			<th></th>
		</tr></thead>
		<tbody>
			<?php foreach($rows as $key=>$row): ?>
				<tr>
					<td><?php print UniformLib::uniform($row['MandateGroup']['id'], 'mandategroup_ID') ?></td>
					<td><?php print UniformLib::uniform($row['MandateGroup']['mandategroup_name'], 'mandategroup_name') ?></td>
					<td><?php 
						$mandates = array();
						if(!empty($row['Mandates'])) foreach($row['Mandates'] as $mandate) if(!empty($mandate['mandate_name'])) $mandates[]=UniformLib::uniform($mandate['mandate_name'], 'mandate_name');
						print join(', ', $mandates); 
					?></td>
					
					<td><?php print UniformLib::uniform($row['MandateGroup']['created'], 'created') ?></td>
					<td><?php print UniformLib::uniform($row['MandateGroup']['modified'], 'modified') ?></td>
					<td class="actions">
						<?php
						if ($perms['is_admin'] || $perms['is_risk'])
						{
							print $this->Html->link(
						    '<i class="icon-pencil"></i>',
						    'mandategroup/'.$row['MandateGroup']['id'],
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