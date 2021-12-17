<fieldset>
<legend>
	Depo Terms
	<?php print $this->Html->link(
	    '<i class="icon-plus"></i> New Depo Term',
	    'depoterm',
	    array('class' => 'btn pull-right', 'escape'=>false)
	); ?>
</legend>
	<table id="depotermsTable" class="table table-bordered table-striped table-hover table-condensed">
		<thead><tr>
			<th><?php echo $this->Paginator->sort('value', 'Code', array('url' => array('page' => 1))) ?></th>
			<th><?php echo $this->Paginator->sort('label', 'Label', array('url' => array('page' => 1))) ?></th>
			<th></th>
		</tr></thead>
		<tbody>
			<?php foreach($rows as $key=>$row): ?>
				<tr>
					<td><?php print UniformLib::uniform($row['DepoTerm']['value'], 'depoterm_value') ?></td>
					<td><?php print UniformLib::uniform($row['DepoTerm']['label'], 'depoterm_label') ?></td>
					<td class="actions">
						<?php print $this->Html->link(
						    '<i class="icon-pencil"></i>',
						    'depoterm/'.$row['DepoTerm']['value'],
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