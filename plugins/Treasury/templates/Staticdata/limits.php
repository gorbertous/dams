<fieldset>
<legend>
	Limits
	<?php
	$perms = $this->Permission->getPermissions();
	if($perms['is_risk'] || $perms['is_admin'])
	{
		print $this->Html->link(
			'<i class="icon-plus"></i> New Limit',
			'limit',
			array('class' => 'btn pull-right', 'escape'=>false)
		);
	}
	?>
</legend>
	<table id="mandatesTable" class="table table-bordered table-striped table-hover table-condensed">
		<thead><tr>
			<th style="vertical-align: middle;"><?php echo $this->Paginator->sort('limit_name', 'Name', array('url' => array('page' => 1))) ?></th>
			<th style="vertical-align: middle;"><?php echo $this->Paginator->sort('MandateGroup.mandategroup_name', 'Portfolio', array('url' => array('page' => 1))) ?></th>
			<th style="vertical-align: middle;"><?php echo $this->Paginator->sort('Counterparty.cpty_name', 'Counterparty or risk group', array('url' => array('page' => 1))) ?></th>
			<th style="text-align:center;vertical-align: middle;"><?php echo $this->Paginator->sort('rating_lt', 'Retained LT', array('url' => array('page' => 1))) ?></th>
			<th style="text-align:center;vertical-align: middle;"><?php echo $this->Paginator->sort('rating_st', 'Retained ST', array('url' => array('page' => 1))) ?></th>
			<th style="text-align:center;vertical-align: middle;"><?php echo $this->Paginator->sort('max_maturity', 'MaxMaturity', array('url' => array('page' => 1))) ?></th>
			<th style="text-align:center;vertical-align: middle;"><?php echo $this->Paginator->sort('limit_eur', 'Limit in EUR', array('url' => array('page' => 1))) ?></th>
			<th style="text-align:center;vertical-align: middle;"><?php echo $this->Paginator->sort('created', 'Created', array('url' => array('page' => 1))) ?></th>
			<th style="text-align:center;vertical-align: middle;"><?php echo $this->Paginator->sort('modified', 'Updated', array('url' => array('page' => 1))) ?></th>
			<th style="text-align:center;vertical-align: middle;"><?php echo $this->Paginator->sort('automatic', 'Auto', array('url' => array('page' => 1))) ?></th>
			<th></th>
		</tr></thead>
		<tbody>
			<?php foreach($rows as $key=>$row): ?>
				<tr>
					<td><?php print UniformLib::uniform($row['Limit']['limit_name'], 'limit_name') ?></td>
					<td><?php print UniformLib::uniform($row['MandateGroup']['mandategroup_name'], 'mandategroup_name') ?></td>
					<td>
						<?php if(!empty($row['Counterparty']['cpty_name'])) print UniformLib::uniform($row['Counterparty']['cpty_name'], 'cpty_name');
							elseif(!empty($row['CounterpartyGroup']['counterpartygroup_name'])) print UniformLib::uniform($row['CounterpartyGroup']['counterpartygroup_name'].' (group)', 'counterpartygroup_name'); ?>
					</td>
					<td>
						<?php 
							$rating = array();
							if(!empty($row['Limit']['cpty_rating'])) $rating[]=UniformLib::uniform($row['Limit']['cpty_rating'], 'cpty_rating');
							//debug($rating);

							if(!empty($row['Limit']['rating_lt'])) $rating[]=UniformLib::uniform($row['Limit']['rating_lt'], 'rating_lt');

							if(!empty($rating)) print implode(',', $rating);
						?>
					</td>
					<td>
						<?php 
							$rating = array();
							if(!empty($row['Limit']['cpty_rating'])) $rating[]=UniformLib::uniform($row['Limit']['cpty_rating'], 'cpty_rating');
							if(!empty($row['Limit']['rating_st'])) $rating[]=UniformLib::uniform($row['Limit']['rating_st'], 'rating_st');
							if(!empty($rating)) print implode(',', $rating);
						?>
					</td>
					<td><?php print UniformLib::uniform($row['Limit']['max_maturity'], 'max_maturity') ?></td>
					<td style="text-align: right;"><?php print UniformLib::uniform($row['Limit']['limit_eur'], 'limit_eur') ?></td>
					
					<td><?php print UniformLib::uniform($row['Limit']['created'], 'created') ?></td>
					<td><?php print UniformLib::uniform($row['Limit']['modified'], 'modified') ?></td>
					<td class="automatic">
						<?php if(!empty($row['Limit']['automatic'])) print '<i class="icon-ok"></i>'; ?>
					</td>
					<td class="actions">
						<?php
						$perms = $this->Permission->getPermissions();
						if ($perms['is_admin'] || $perms['is_risk'])
						{
							print $this->Html->link(
								'<i class="icon-pencil"></i>',
								'limit/'.$row['Limit']['limit_ID'],
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