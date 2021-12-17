<fieldset>
<legend>
	Accounts
	<?php 
	$perms = $this->Permission->getPermissions();
	print $this->Html->link(
	    '<i class="icon-plus"></i> New account',
	    'account',
	    array('class' => 'btn pull-right', 'escape'=>false)
	); ?>
</legend>
	<table id="accountsTable" class="table table-bordered table-striped table-hover table-condensed">
		<thead><tr>
			<th><?php echo $this->Paginator->sort('IBAN', 'IBAN', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('BIC', 'BIC', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('ccy', 'Currency', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('PS_account', 'PS Account', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('created', 'Created', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('modified', 'Updated', array('url' => array('page' => 1))) ?></th>
			<th></th>
		</tr></thead>
		<tbody>
			<?php foreach($rows as $key=>$row): ?>
				<tr>
					<td><?php print UniformLib::uniform($row['Account']['IBAN'], 'account_IBAN') ?></td>
					<td><?php print UniformLib::uniform($row['Account']['BIC'], 'account_BIC') ?></td>
					<td><?php print UniformLib::uniform($row['Account']['ccy'], 'ccy') ?></td>
					<td class="ps_account"><?php print UniformLib::uniform($row['Account']['PS_account'], 'PS_account') ?></td>
					<td><?php print UniformLib::uniform($row['Account']['created'], 'created') ?></td>
					<td><?php print UniformLib::uniform($row['Account']['modified'], 'modified') ?></td>
					<td class="actions">
						<?php
						if ($perms['is_admin'] || $perms['is_validator'] || $perms['is_operator'])
						{
							print $this->Html->link(
							    '<i class="icon-pencil"></i>',
							    'account/'.$row['Account']['IBAN'],
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