<fieldset>
<legend>
	DI Templates
	<?php print $this->Html->link(
	    '<i class="icon-plus"></i> New DI template',
	    'ditemplate',
	    array('class' => 'btn pull-right', 'escape'=>false)
	); ?>
</legend>
	<table id="ditemplatesTable" class="table table-bordered table-striped table-hover table-condensed">
		<thead><tr>
			<th><?php echo $this->Paginator->sort('Mandate.mandate_name', 'Mandate', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('Counterparty.cpty_name', 'Counterparty', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('template', 'Template', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('attn', 'Attn', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('preamble', 'Preamble', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('deposits_footer', 'Footer', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('created', 'Created', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('modified', 'Updated', array('url' => array('page' => 1))) ?></th>
			<th></th>
		</tr></thead>
		<tbody>
			<?php foreach($rows as $key=>$row): ?>
				<tr>
					<td><?php print UniformLib::uniform($row['Mandate']['mandate_name'], 'mandate_name') ?></td>
					<td><?php print UniformLib::uniform($row['Counterparty']['cpty_name'], 'cpty_name') ?></td>
					<td><?php print UniformLib::uniform($row['DItemplate']['template'], 'deposits_template') ?></td>
					<td><?php print UniformLib::uniform($row['DItemplate']['attn'], 'deposits_attn'); ?></td>
					<td><?php print UniformLib::uniform($row['DItemplate']['preamble'], 'deposits_preamble'); ?></td>
					<td><?php 
						if(substr($row['DItemplate']['deposits_footer'],0,1)=='{'){
							$json = json_decode($row['DItemplate']['deposits_footer'], true);
							print UniformLib::uniform(reset($json), 'deposits_footer');
						}else{
							print UniformLib::uniform($row['DItemplate']['deposits_footer'], 'deposits_footer');
						}
					?></td>
					<td><?php print UniformLib::uniform($row['DItemplate']['created'], 'created') ?></td>
					<td><?php print UniformLib::uniform($row['DItemplate']['modified'],'modified') ?></td>

					<td class="actions">
						<?php print $this->Html->link(
						    '<i class="icon-pencil"></i>',
						    'ditemplate/'.$row['DItemplate']['dit_id'],
						    array('class' => 'btn btn-mini', 'escape'=>false)
						); ?>
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