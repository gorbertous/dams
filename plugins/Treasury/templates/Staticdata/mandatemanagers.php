<?php
$perms = $this->Permission->getPermissions();
?><fieldset>
<legend>
	Mandate Managers	
</legend>
	<table id="mandatemanagersTable" class="table table-bordered table-striped table-hover table-condensed">
		<thead><tr>
			<th style="width: 50px"><?php echo $this->Paginator->sort('mandate_ID', '#', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('mandate_name', 'Name', array('url' => array('page' => 1))) ?></th>
			<th>Managers</th>
			<th style="width: 40px"></th>
		</tr></thead>
		<tbody>
			<?php foreach($rows as $key=>$row): ?>
				<tr>
					<td><?php print UniformLib::uniform($row['Mandate']['mandate_ID'], 'mandate_ID') ?></td>
					<td><?php print UniformLib::uniform($row['Mandate']['mandate_name'], 'mandate_name') ?></td>
					<td><?php 
						$managers = array();
						if(!empty($row['Managers'])) foreach($row['Managers'] as $manager){
							$name = $manager['name'];
							if(!empty($manager['email']))$name.=' ('.$manager['email'].')';
							$managers[]=UniformLib::uniform($name, 'manager_name');
						}
						print join(', ', $managers); 
					?></td>
					
					<td class="actions">
						<?php
						if ($perms['is_admin'] || $perms['is_validator'] || $perms['is_operator'])
						{
							print $this->Html->link(
							    '<i class="icon-pencil"></i>',
							    'mandatemanager/'.$row['Mandate']['mandate_ID'],
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