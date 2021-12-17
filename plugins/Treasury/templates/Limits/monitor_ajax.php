<?php if(isset($limits) && !empty($limits)): ?>
	<?php $newdate = date('Y-m-d', strtotime(str_replace('/','-',$date))); ?>
	<div id ='limits_dashboard'>
		<table id="limits_monitor" class="table table-bordered table-striped table-hover table-condensed">
			<thead>
				<th> Limit Name </th>
				<th> Transactions </th>
				<th> Mandate Group </th>
				<th> Counterparty </th>
				<th> Rating </th>
				<th>Max Maturity </th>
				<th class="text-right-force">Limit in EUR </th>
				<th class="text-right-force">Exposure in EUR </th>
				<th class="text-right-force">Limit Available in EUR </th>
				<th>Status </th>
			</thead>
			<tbody>
			<?php foreach ($limits as $key => $value): ?>
				<tr>
					<td>
						<?php echo UniformLib::uniform($value['limit_name'], 'limit_name'); ?>
					</td>
					<td>
						<?php echo '<a title="limit" class="btn '.$value['limit_name'].'" href="/treasury/treasurylimits/transactions/'.$value['limit_ID'].'/'.$newdate.'"><i class="icon-folder-open icon-green"></i></a>'; 
						?>
					</td>
					<td><?php echo UniformLib::uniform($value['mandategroup_name'], 'mandategroup_name') ?></td>
					<td><?php echo UniformLib::uniform($value['cpty_name'], 'cpty_name') ?></td>
					<td><?php echo UniformLib::uniform($value['cpty_rating'], 'cpty_rating') ?></td>
					<td><?php echo UniformLib::uniform($value['max_maturity'], 'max_maturity') ?></td>
					<td class="text-right-force"><?php echo UniformLib::uniform($value['limit_eur'], 'limit_eur') ?></td>
					<td class="text-right-force"><?php echo UniformLib::uniform($value['exposure_eur'], 'exposure_eur') ?></td>
					<td class="text-right-force"><?php echo UniformLib::uniform($value['limit_available'], 'limit_available') ?></td>
					<td><?php echo UniformLib::uniform($value['status'], 'status') ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php else: ?>
	<div class="alert"> No limits corresponding to this date. </div>
<?php endif; ?>	


