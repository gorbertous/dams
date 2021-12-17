<fieldset>
<legend>
	Banks
	<?php
	$perms = $this->Permission->getPermissions();
	print $this->Html->link(
	    '<i class="icon-plus"></i> New bank',
	    'bank',
	    array('class' => 'btn pull-right', 'escape'=>false)
	); ?>
</legend>
	<table id="banksTable" class="table table-bordered table-striped table-hover table-condensed">
		<thead><tr>
			<th><?php echo $this->Paginator->sort('bank_name', 'Name', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('short_name', 'Short name', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('BIC', 'BIC', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('country', 'Country', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('contact_person', 'Contact', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('email', 'Email', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('created', 'Created', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('modified', 'Updated', array('url' => array('page' => 1))) ?></th>
			<th></th>
		</tr></thead>
		<tbody>
			<?php foreach($rows as $key=>$row): ?>
				<tr>
					<td><?php print UniformLib::uniform($row['Bank']['bank_name'], 'bank_name') ?></td>
					<td><?php print UniformLib::uniform($row['Bank']['short_name'], 'short_name') ?></td>
					<td><?php print UniformLib::uniform($row['Bank']['BIC'], 'bic') ?></td>
					<td><?php print UniformLib::uniform($row['Bank']['country'], 'country') ?></td>
					<td><?php print UniformLib::uniform($row['Bank']['contact_person'], 'contact_person') ?></td>
					<td><?php print UniformLib::uniform($row['Bank']['email'], 'email') ?></td>
					<td><?php print UniformLib::uniform($row['Bank']['created'], 'created') ?></td>
					<td><?php print UniformLib::uniform($row['Bank']['modified'], 'modified') ?></td>
					<td class="actions">
						<?php
						if ($perms['is_admin'] || $perms['is_validator'] || $perms['is_operator'])
						{
							print $this->Html->link(
						    	'<i class="icon-pencil"></i>',
						    	'bank/'.$row['Bank']['BIC'],
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