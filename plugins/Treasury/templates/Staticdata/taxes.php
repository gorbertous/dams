<fieldset>
<legend>
	Taxes
	<?php
	$perms = $this->Permission->getPermissions();
	print $this->Html->link(
	    '<i class="icon-plus"></i> New tax',
	    'tax',
	    array('class' => 'btn pull-right', 'escape'=>false)
	); ?>
</legend>
	<table id="taxesTable" class="table table-bordered table-striped table-hover table-condensed">
		<thead><tr>
			<th><?php echo $this->Paginator->sort('mandate_name', 'Mandate', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('cpty_name', 'Counterparty', array('url' => array('page' => 1))) ?></th>
			<th style="text-align: right"><?php echo $this->Paginator->sort('tax_rate', 'Rate %', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('created', 'Created', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('modified', 'Updated', array('url' => array('page' => 1))) ?></th>
			<th></th>
		</tr></thead>
		<tbody>
			<?php foreach($rows as $key=>$row): ?>
				<tr>
					<td><?php print UniformLib::uniform($row['Mandate']['mandate_name'], 'mandate_name') ?></td>
					<td><?php print UniformLib::uniform($row['Counterparty']['cpty_name'], 'cpty_name') ?></td>
					<td style="text-align: right"><?php print UniformLib::uniform($row['Tax']['tax_rate'], 'tax_rate') ?></td>

					<td><?php print UniformLib::uniform($row['Tax']['created'], 'created') ?></td>
					<td><?php print UniformLib::uniform($row['Tax']['modified'], 'modified') ?></td>
					<td class="actions">
						<?php
						if ($perms['is_admin'] || $perms['is_validator'] || $perms['is_operator'])
						{
							print $this->Html->link(
							    '<i class="icon-pencil"></i>',
							    'tax/'.$row['Tax']['tax_ID'],
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