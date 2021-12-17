<?php
	echo $this->Html->css('/treasury/css/redmond/jquery-ui-1.10.3.custom.css');
	echo $this->Html->script('/treasury/js/jquery-ui-1.10.3.custom.min.js');
	$portfolio_concentration_key = 'portfolio_concentration';
	$portfolio_concentration_suffix = ' EUR';
	if(!empty($portfolioConcentrationUnit) && $portfolioConcentrationUnit=='PCT'){
		$portfolio_concentration_key.='_pct';
		$portfolio_concentration_suffix='%';
	}	
?>
<fieldset>
<legend>
Limits Monitor
		<?php
		if (empty($this->request->data['filter']))
		{
			$date = date('Y-m-d');
			reset($mandategroups);
			$first_key = key($mandategroups);
			$portfolio = $first_key;
		}
		else
		{
			$date = $this->request->data['filter']['Date'];
			$date = date('Y-m-d', strtotime(str_replace('/','-',$date)));
			$portfolio = $this->request->data['filter']['Portfolio'];
		}
		?>
<a id="xls_download" style="margin-left:20px" class="btn pull-right" href="/treasury/treasuryajax/export_limit_monitor_excel/<?php echo $portfolio;?>/<?php echo $date; ?>"><i class="icon-download"></i> Export to XLS</a>
<div class='clearfix'>
	<?php
		echo $this->Form->create('filter');
		echo $this->Form->input('Portfolio', array(
			'options'=> $mandategroups,
			'div'=>'form-group',
			'required'=>true
		));
		echo $this->Form->input('Date', array(
			'label'	=> 'Limits / Exposure as of&nbsp;&nbsp;',
			'data-date-format'	=> 'dd/mm/yyyy',
			'default'=>UniformLib::uniform($date, 'limit_date'),
			'div'=>'form-group'
		));
		echo $this->Form->end();
	?>
	<div class="pull-right values text-right">
		<div><b>Portfolio size: </b> <?php print UniformLib::uniform($portfolioSize, 'portfolio_size').' EUR';
		if ($portfolioSize == 0)
		{
			$portfolioSize = 1;//to avoid division by 0
		}		?></div>
			<div><b>Portfolio Concentration Limit: </b> <?php if(!empty($portfolioMaxConcentration)) print UniformLib::uniform($portfolioMaxConcentration, $portfolio_concentration_key).$portfolio_concentration_suffix;
			else print 'N/A'; ?></div>
	</div>
</div>
</legend>
<div id='limits_dashboard'>
<?php if(empty($limits['counterparties']) && empty($limits['counterpartygroups'])): ?>
	<p>No Limits for these criteria</p>
<?php else: ?>
	<table id="limitsTable" class="table table-bordered table-striped table-hover table-condensed">
		<thead>
			<tr>
				<th width="1%" style="text-align:center;vertical-align: middle;">#</th>
				<th width="19%" style="vertical-align: middle;">Counterparty or Group</th>
				<th width="5%" style="text-align:center;vertical-align: middle;">Transactions</th>
				<th width="7%" style="text-align:center;vertical-align: middle;">Retained LT</th>
				<th width="7%" style="text-align:center;vertical-align: middle;">Retained ST</th>
				<th width="3%" style="text-align:center;vertical-align: middle;">All</th>
				<th width="10%" style="text-align:center;vertical-align: middle;">Max Maturity (days)</th>
				<th width="8%" style="text-align:center;vertical-align: middle;">Limit (in EUR)</th>
				<th width="9%" style="text-align:center;vertical-align: middle;">Exposure (in EUR)</th>
				<th width="12%" style="text-align:center;vertical-align: middle;">Portfolio Concentration</th>
				<th width="12%" style="text-align:center;vertical-align: middle;">Limit Available (in EUR)</th>
				<th width="5%" style="text-align:center;vertical-align: middle;">Status</th>
			</tr>
		</thead>
		<tbody>
<?php /** COUNTERPARTY GROUPS **/ ?>
	<?php if(!empty($limits['counterpartygroups'])) foreach($limits['counterpartygroups'] as $group): ?>
		<?php if(!empty($group['limit'])): ?>
			<tr class="group-limit group-limit-<?php print $group['limit']['counterpartygroup_ID'] ?><?php if(!empty($group['limit']['status'])) print ' error' ?>">
				<td class="id numeric"><?php print $group['limit']['limit_ID'] ?></td>
				<td class="cpty"><a href="#" class="toggle" data-group="<?php print $group['limit']['counterpartygroup_ID'] ?>"><?php print UniformLib::uniform($group['CounterpartyGroup']['counterpartygroup_name'], 'counterpartygroup_name') ?></a></td>
				<td class="trns actions">
					<a href="/treasury/treasurylimits/details/group/<?php print $group['limit']['limit_ID'] ?>/<?php print $date ?>" class="btn transactions-<?php print $group['limit']['counterpartygroup_ID'] ?>" title="Transactions for <?php print $group['CounterpartyGroup']['counterpartygroup_name'] ?>"><i class="icon-folder-open icon-green"></i></a>
				</td>
				<td class="rating rating-lt"><?php print $group['limit']['rating_lt'] ?></td>
				<td class="rating rating-st"><?php print $group['limit']['rating_st'] ?></td>
				<td class="rating rating-all">
					<?php if(!empty($group['head']['Rating'])): ?>
						<div class="infotip">
							<div class="btn btn-small">+</div>
							<div class="infotip-content">
								<h4 class="funds"><?php print  UniformLib::uniform($group['head']['Rating']['own_funds'],'own_funds_eur') ?> EUR</h4>
								<h5>Long term</h5>
								<p>
									<span class="name">Moody's</span> 
									<span class="rating"><?php print UniformLib::uniform($group['head']['Rating']['LT-MDY'],'LT-MDY') ?></span> 
									<span class="date"><?php print UniformLib::uniform($group['head']['Rating']['LT-MDY_date'],'LT-MDY_date') ?></span>  
									<?php if(!empty($group['head']['Rating']['LT-STP_outlook'])): ?><br><span class="outlook"><?php print UniformLib::uniform($group['head']['Rating']['LT-MDY_outlook'],'LT-MDY_outlook') ?></span>  
									<?php endif ?>
								</p>
								<p>
									<span class="name">Fitch</span> 
									<span class="rating"><?php print UniformLib::uniform($group['head']['Rating']['LT-FIT'],'LT-FIT') ?></span> 
									<span class="date"><?php print UniformLib::uniform($group['head']['Rating']['LT-FIT_date'],'LT-FIT_date') ?></span> 
									<?php if(!empty($group['head']['Rating']['LT-STP_outlook'])): ?><br><span class="outlook"><?php print UniformLib::uniform($group['head']['Rating']['LT-FIT_outlook'],'LT-FIT_outlook') ?></span> 
									<?php endif ?>
								</p>
								<p>
									<span class="name">S&amp;P</span> 
									<span class="rating"><?php print UniformLib::uniform($group['head']['Rating']['LT-STP'],'LT-STP') ?></span>   
									<span class="date"><?php print UniformLib::uniform($group['head']['Rating']['LT-STP_date'],'LT-STP_date') ?></span>  
									<?php if(!empty($group['head']['Rating']['LT-STP_outlook'])): ?><br><span class="outlook"><?php print UniformLib::uniform($group['head']['Rating']['LT-STP_outlook'],'LT-STP_outlook') ?></span> 
									<?php endif ?> 
								</p>
								<p>
									<span class="name">EIB</span> 
									<span class="rating"><?php print UniformLib::uniform($group['head']['Rating']['LT-EIB'],'LT-EIB') ?></span>   
									<span class="date"><?php print UniformLib::uniform($group['head']['Rating']['LT-EIB_date'],'EIB_date') ?></span>   
								</p>

								<h5>Short term</h5>
								<p>
									<span class="name">Moody's</span> 
									<span class="rating"><?php print UniformLib::uniform($group['head']['Rating']['ST-MDY'],'ST-MDY') ?></span> 
									<span class="date"><?php print UniformLib::uniform($group['head']['Rating']['ST-MDY_date'],'ST-MDY_date') ?></span>  
									<?php if(!empty($group['head']['Rating']['LT-STP_outlook'])): ?><br><span class="outlook"><?php print UniformLib::uniform($group['head']['Rating']['ST-MDY_outlook'],'ST-MDY_outlook') ?></span>
									<?php endif ?>  
								</p>
								<p>
									<span class="name">Fitch</span> 
									<span class="rating"><?php print UniformLib::uniform($group['head']['Rating']['ST-FIT'],'ST-FIT') ?></span> 
									<span class="date"><?php print UniformLib::uniform($group['head']['Rating']['ST-FIT_date'],'ST-FIT_date') ?></span> 
									<?php if(!empty($group['head']['Rating']['LT-STP_outlook'])): ?><br><span class="outlook"><?php print UniformLib::uniform($group['head']['Rating']['ST-FIT_outlook'],'ST-FIT_outlook') ?></span> 
									<?php endif ?>
								</p>
								<p>
									<span class="name">S&amp;P</span> 
									<span class="rating"><?php print UniformLib::uniform($group['head']['Rating']['ST-STP'],'ST-STP') ?></span>   
									<span class="date"><?php print UniformLib::uniform($group['head']['Rating']['ST-STP_date'],'ST-STP_date') ?></span>  
									<?php if(!empty($group['head']['Rating']['LT-STP_outlook'])): ?><br><span class="outlook"><?php print UniformLib::uniform($group['head']['Rating']['ST-STP_outlook'],'ST-STP_outlook') ?></span>  
									<?php endif ?>
								</p>
								<p>
									<span class="name">EIB</span> 
									<span class="rating"><?php print UniformLib::uniform($group['head']['Rating']['ST-EIB'],'ST-EIB') ?></span>   
									<span class="date"><?php print UniformLib::uniform($group['head']['Rating']['ST-EIB_date'],'ST-ST-EIB_date') ?></span>   
								</p>
							</div>
						</div>
					<?php endif ?>
				</td>
				<td class="maxmaturity numeric"><?php print UniformLib::uniform($group['limit']['max_maturity'], 'max_maturity') ?></td>
				<td class="limit numeric"><?php print UniformLib::uniform($group['limit']['limit_eur'], 'limit_eur') ?></td>
				<td class="exposure numeric"><?php print UniformLib::uniform($group['CounterpartyGroup']['exposure'], 'exposure') ?></td>
				<td class="concentration numeric"><?php
					$concentration = $group['CounterpartyGroup']['concentration'];
					$exposure = $group['CounterpartyGroup']['exposure'];
					$concentrationkey = 'concentration';
					$concentration_suffix='';
					$limit_unit = $group['limit']['concentration_limit_unit'];
					if ($portfolioSize == 0)
					{
						$portfolioSize = 1;//to avoid division by 0
					}
					if(($limit_unit=='PCT') || ($limit_unit=='NA')) {
						//$concentration*=100;
						$concentration = ($exposure*100)/$portfolioSize;
						$concentrationkey = 'concentration_pct';
						$concentration_suffix = '%';
					}
					print UniformLib::uniform($concentration, $concentrationkey).$concentration_suffix;
				?></td>
				<td class="limitavailable numeric"><?php print UniformLib::uniform($group['CounterpartyGroup']['limit_available'], 'limit_available') ?></td>
				<td class="status actions">
					<?php if(empty($group['limit']['status'])): ?>
						<i class="icon-ok" title="OK"></i>
					<?php else: ?>
						<?php 
							$title = '';
							foreach($group['limit']['status'] as $error){
								if($title) $title.= ' + ';
								foreach($error as $key=>$val) $title.=$key.' ('.$val.')';
							}
							if($title) $title='Limit Breach: '.$title;
						?>
						<i class="icon-remove" title="<?php print $title ?>"></i>
					<?php endif ?>
				</td>
			</tr>

<?php /** COUNTERPARTY GROUPS: related counterparties **/ ?>
			<?php if(!empty($group['counterparties'])) foreach($group['counterparties'] as $cpty): ?>
				<tr class="group-counterparty group-counterparty-<?php print $group['limit']['counterpartygroup_ID'] ?>">
					<td class="id numeric"></td>
					<td class="cpty"><?php print UniformLib::uniform($cpty['cpty_name'], 'cpty_name') ?></td>
					<td class="trns actions">

					</td>
					<td class="rating rating-lt"></td>
					<td class="rating rating-st"></td>
					<td class="rating rating-all">
						<?php if(!empty($cpty['rating'])): ?>
							<div class="infotip">
								<div class="btn btn-small">+</div>
								<div class="infotip-content">
									<h4 class="funds"><?php print  UniformLib::uniform($cpty['rating']['own_funds'],'own_funds_eur') ?> EUR</h4>
									<h5>Long term</h5>
									<p>
										<span class="name">Moody's</span> 
										<span class="rating"><?php print UniformLib::uniform($cpty['rating']['LT-MDY'],'LT-MDY') ?></span> 
										<span class="date"><?php print UniformLib::uniform($cpty['rating']['LT-MDY_date'],'LT-MDY_date') ?></span>  
										<?php if(!empty($cpty['rating']['LT-STP_outlook'])): ?><br><span class="outlook"><?php print UniformLib::uniform($cpty['rating']['LT-MDY_outlook'],'LT-MDY_outlook') ?></span>  
										<?php endif ?>
									</p>
									<p>
										<span class="name">Fitch</span> 
										<span class="rating"><?php print UniformLib::uniform($cpty['rating']['LT-FIT'],'LT-FIT') ?></span> 
										<span class="date"><?php print UniformLib::uniform($cpty['rating']['LT-FIT_date'],'LT-FIT_date') ?></span> 
										<?php if(!empty($cpty['rating']['LT-STP_outlook'])): ?><br><span class="outlook"><?php print UniformLib::uniform($cpty['rating']['LT-FIT_outlook'],'LT-FIT_outlook') ?></span> 
										<?php endif ?>
									</p>
									<p>
										<span class="name">S&amp;P</span> 
										<span class="rating"><?php print UniformLib::uniform($cpty['rating']['LT-STP'],'LT-STP') ?></span>   
										<span class="date"><?php print UniformLib::uniform($cpty['rating']['LT-STP_date'],'LT-STP_date') ?></span>  
										<?php if(!empty($cpty['rating']['LT-STP_outlook'])): ?><br><span class="outlook"><?php print UniformLib::uniform($cpty['rating']['LT-STP_outlook'],'LT-STP_outlook') ?></span> 
										<?php endif ?> 
									</p>
									<p>
										<span class="name">EIB</span> 
										<span class="rating"><?php print UniformLib::uniform($cpty['rating']['LT-EIB'],'LT-EIB') ?></span>   
										<span class="date"><?php print UniformLib::uniform($cpty['rating']['LT-EIB_date'],'EIB_date') ?></span>   
									</p>

									<h5>Short term</h5>
									<p>
										<span class="name">Moody's</span> 
										<span class="rating"><?php print UniformLib::uniform($cpty['rating']['ST-MDY'],'ST-MDY') ?></span> 
										<span class="date"><?php print UniformLib::uniform($cpty['rating']['ST-MDY_date'],'ST-MDY_date') ?></span>  
										<?php if(!empty($cpty['rating']['LT-STP_outlook'])): ?><br><span class="outlook"><?php print UniformLib::uniform($cpty['rating']['ST-MDY_outlook'],'ST-MDY_outlook') ?></span>
										<?php endif ?>  
									</p>
									<p>
										<span class="name">Fitch</span> 
										<span class="rating"><?php print UniformLib::uniform($cpty['rating']['ST-FIT'],'ST-FIT') ?></span> 
										<span class="date"><?php print UniformLib::uniform($cpty['rating']['ST-FIT_date'],'ST-FIT_date') ?></span> 
										<?php if(!empty($cpty['rating']['LT-STP_outlook'])): ?><br><span class="outlook"><?php print UniformLib::uniform($cpty['rating']['ST-FIT_outlook'],'ST-FIT_outlook') ?></span> 
										<?php endif ?>
									</p>
									<p>
										<span class="name">S&amp;P</span> 
										<span class="rating"><?php print UniformLib::uniform($cpty['rating']['ST-STP'],'ST-STP') ?></span>   
										<span class="date"><?php print UniformLib::uniform($cpty['rating']['ST-STP_date'],'ST-STP_date') ?></span>  
										<?php if(!empty($cpty['rating']['LT-STP_outlook'])): ?><br><span class="outlook"><?php print UniformLib::uniform($cpty['rating']['ST-STP_outlook'],'ST-STP_outlook') ?></span>  
										<?php endif ?>
									</p>
									<p>
										<span class="name">EIB</span> 
										<span class="rating"><?php print UniformLib::uniform($cpty['rating']['ST-EIB'],'ST-EIB') ?></span>   
										<span class="date"><?php print UniformLib::uniform($cpty['rating']['ST-EIB_date'],'ST-ST-EIB_date') ?></span>   
									</p>
								</div>
							</div>
						<?php endif ?>
					</td>
					<td class="maxmaturity numeric"></td>
					<td class="limit numeric"></td>
					<td class="exposure numeric"><?php print UniformLib::uniform($cpty['exposure'], 'exposure') ?></td>
					<td class="concentration numeric"><?php
						$concentration = $cpty['concentration'];
						$concentrationkey = 'concentration';
						$concentration_suffix='';
						$limit_unit = $group['limit']['concentration_limit_unit'];
						if(($limit_unit=='PCT') || ($limit_unit=='NA')){
							$concentration*=100;
							$concentrationkey = 'concentration_pct';
							$concentration_suffix='%';
						}
						print UniformLib::uniform($concentration, $concentrationkey).$concentration_suffix;
					?></td>
					<td class="limitavailable numeric"></td>
					<td class="status actions"></td>
				</tr>
			<?php endforeach ?>
		<?php endif ?>
	<?php endforeach ?>

<?php /** SINGLE COUNTERPARTIES **/ ?>		
	<?php if(!empty($limits['counterparties'])) foreach($limits['counterparties'] as $cpty): ?>
		<?php if(!empty($cpty['limits'])) foreach($cpty['limits'] as $limit): ?>
			<tr class="cpty-limit <?php if(!empty($cpty['counterparty']['status'])) print ' error' ?>">
				<td class="id numeric"><?php print UniformLib::uniform($limit['limit_ID'], 'limit_ID') ?></td>
				<td class="cpty"><?php print UniformLib::uniform($cpty['counterparty']['cpty_name'], 'cpty_name') ?></td>
				<td class="trns actions">
					<a href="/treasury/treasurylimits/details/cpty/<?php print $limit['limit_ID'] ?>/<?php print $date ?>" class="btn transactions-<?php print $cpty['counterparty']['cpty_ID'] ?>" title="Transactions for <?php print $cpty['counterparty']['cpty_code'] ?>"><i class="icon-folder-open icon-green"></i></a>
				</td>
				<td class="rating rating-lt"><?php print $limit['rating_lt'] ?></td>
				<td class="rating rating-st"><?php print $limit['rating_st'] ?></td>
				<td class="rating rating-all">
					<?php if(!empty($cpty['rating'])): ?>
						<div class="infotip">
							<div class="btn btn-small">+</div>
							<div class="infotip-content">
								<h4 class="funds"><?php print  UniformLib::uniform($cpty['rating']['own_funds'],'own_funds_eur') ?> EUR</h4>
								<h5>Long term</h5>
								<p>
									<span class="name">Moody's</span> 
									<span class="rating"><?php print UniformLib::uniform($cpty['rating']['LT-MDY'],'LT-MDY') ?></span> 
									<span class="date"><?php print UniformLib::uniform($cpty['rating']['LT-MDY_date'],'LT-MDY_date') ?></span>  
									<?php if(!empty($cpty['rating']['LT-STP_outlook'])): ?><br><span class="outlook"><?php print UniformLib::uniform($cpty['rating']['LT-MDY_outlook'],'LT-MDY_outlook') ?></span>  
									<?php endif ?>
								</p>
								<p>
									<span class="name">Fitch</span> 
									<span class="rating"><?php print UniformLib::uniform($cpty['rating']['LT-FIT'],'LT-FIT') ?></span> 
									<span class="date"><?php print UniformLib::uniform($cpty['rating']['LT-FIT_date'],'LT-FIT_date') ?></span> 
									<?php if(!empty($cpty['rating']['LT-STP_outlook'])): ?><br><span class="outlook"><?php print UniformLib::uniform($cpty['rating']['LT-FIT_outlook'],'LT-FIT_outlook') ?></span> 
									<?php endif ?>
								</p>
								<p>
									<span class="name">S&amp;P</span> 
									<span class="rating"><?php print UniformLib::uniform($cpty['rating']['LT-STP'],'LT-STP') ?></span>   
									<span class="date"><?php print UniformLib::uniform($cpty['rating']['LT-STP_date'],'LT-STP_date') ?></span>  
									<?php if(!empty($cpty['rating']['LT-STP_outlook'])): ?><br><span class="outlook"><?php print UniformLib::uniform($cpty['rating']['LT-STP_outlook'],'LT-STP_outlook') ?></span> 
									<?php endif ?> 
								</p>
								<p>
									<span class="name">EIB</span> 
									<span class="rating"><?php print UniformLib::uniform($cpty['rating']['LT-EIB'],'LT-EIB') ?></span>   
									<span class="date"><?php print UniformLib::uniform($cpty['rating']['LT-EIB_date'],'EIB_date') ?></span>   
								</p>

								<h5>Short term</h5>
								<p>
									<span class="name">Moody's</span> 
									<span class="rating"><?php print UniformLib::uniform($cpty['rating']['ST-MDY'],'ST-MDY') ?></span> 
									<span class="date"><?php print UniformLib::uniform($cpty['rating']['ST-MDY_date'],'ST-MDY_date') ?></span>  
									<?php if(!empty($cpty['rating']['LT-STP_outlook'])): ?><br><span class="outlook"><?php print UniformLib::uniform($cpty['rating']['ST-MDY_outlook'],'ST-MDY_outlook') ?></span>
									<?php endif ?>  
								</p>
								<p>
									<span class="name">Fitch</span> 
									<span class="rating"><?php print UniformLib::uniform($cpty['rating']['ST-FIT'],'ST-FIT') ?></span> 
									<span class="date"><?php print UniformLib::uniform($cpty['rating']['ST-FIT_date'],'ST-FIT_date') ?></span> 
									<?php if(!empty($cpty['rating']['LT-STP_outlook'])): ?><br><span class="outlook"><?php print UniformLib::uniform($cpty['rating']['ST-FIT_outlook'],'ST-FIT_outlook') ?></span> 
									<?php endif ?>
								</p>
								<p>
									<span class="name">S&amp;P</span> 
									<span class="rating"><?php print UniformLib::uniform($cpty['rating']['ST-STP'],'ST-STP') ?></span>   
									<span class="date"><?php print UniformLib::uniform($cpty['rating']['ST-STP_date'],'ST-STP_date') ?></span>  
									<?php if(!empty($cpty['rating']['LT-STP_outlook'])): ?><br><span class="outlook"><?php print UniformLib::uniform($cpty['rating']['ST-STP_outlook'],'ST-STP_outlook') ?></span>  
									<?php endif ?>
								</p>
								<p>
									<span class="name">EIB</span> 
									<span class="rating"><?php print UniformLib::uniform($cpty['rating']['ST-EIB'],'ST-EIB') ?></span>   
									<span class="date"><?php print UniformLib::uniform($cpty['rating']['ST-EIB_date'],'ST-ST-EIB_date') ?></span>   
								</p>
							</div>
						</div>
					<?php endif ?>
				</td>
				<td class="maxmaturity numeric"><?php print UniformLib::uniform($limit['max_maturity'], 'max_maturity') ?></td>
				<td class="limit numeric"><?php print UniformLib::uniform($limit['limit_eur'], 'limit_eur') ?></td>
				<td class="exposure numeric"><?php print UniformLib::uniform($cpty['counterparty']['exposure'], 'exposure') ?></td>
				<td class="concentration numeric">
				<?php
					$concentration = $cpty['counterparty']['concentration'];
					$exposure = $cpty['counterparty']['exposure'];
					$concentrationkey = 'concentration';
					$concentration_suffix = '';
					$limit_unit = $cpty['limits'][0]['concentration_limit_unit'];

					if(($limit_unit=='PCT') || ($limit_unit=='NA')){
						$concentration = ($exposure*100)/$portfolioSize;
						$concentrationkey = 'concentration_pct';
						$concentration_suffix='%';
					}
					
					print UniformLib::uniform($concentration, $concentrationkey).$concentration_suffix;
				?></td>
				<td class="limitavailable numeric"><?php
				 print UniformLib::uniform($cpty['counterparty']['limit_available'], 'limit_available') ?></td>
				<td class="status actions">
					<?php if(empty($cpty['counterparty']['status'])): ?>
						<i class="icon-ok" title="OK"></i>
					<?php else: ?>
						<?php 
							$title = '';
							foreach($cpty['counterparty']['status'] as $error){
								if($title) $title.= ' + ';
								foreach($error as $key=>$val) $title.=$key.' ('.$val.')';
							}
							if($title) $title='Limit Breach: '.$title;
						?>
						<i class="icon-remove" title="<?php print $title ?>"></i>
					<?php endif ?>
				</td>
			</tr>
		<?php endforeach ?>
	<?php endforeach ?>
		</tbody>
	</table>
<?php endif ?>

<?php
if (!empty($breaches))
{
		echo "<p>Limit Breach Notifications :</p><ul class='list-group'>";
		foreach($breaches as $breach)
		{			
			$link = str_replace('/var/www/html', '', $breach['lb']['email_reference']);
			$label = $breach['lb']['date'].' '.$breach['lb']['portfolio'].' - ';
			if ($breach['lb']['counterparty'] == "")
			{
				$label .= $breach['lb']['risk_group'].' - ';
			}
			else
			{
				$label .= $breach['lb']['counterparty'].' - ';
			}
			switch($breach['lb']['breach_type'])
			{
				case 'CONC':
					$label .= 'Concentration Limit Breach';
				break;
				case 'CPTY':
					$label .= 'Exposure Limit Breach';
				break;
				case 'MATU':
					$label .= 'Maturity Limit Breach';
				break;
			}
			echo '<li class="list-group-item"><span class="icon-envelope"></span> <a href="/treasury/treasuryajax/download_file/1?file='.$link.'" target="_blank">'.$label.'</a></li>';
		}
		echo "</ul>";
		
}
else
{
	echo '<p>No Limit Breaches were recorded</p>';
}
?>



</div>
</fieldset>
<style>
	#filterLimitsForm > .form-group{ float: left; margin: 0 10px 0 0; }
	#filterLimitsForm input.hasDatepicker{ height: 20px; width: 100px; }

	#limitsTable tr.group-counterparty{ display: none; }
	#limitsTable tr.group-counterparty.show{ display: table-row; }

	legend .values{ font-size: 13px; line-height: 20px; font-weight: normal; }
	#limitsTable tr.group-counterparty td{ background: #dadada; color: #777; border-color: #dadada; }
	#limitsTable tr.group-counterparty td.cpty{ padding-left: 20px; }
	#limitsTable td.numeric{ text-align: right; }

	.infotip{ position: relative; }
	.infotip-content{ 
		position: absolute; top: 0; left: 0; z-index: 100;
		width: 200px; min-height: 200px; 
		padding: 15px;
		background: #fff; border: 1px solid #ccc;
		display: none;
	}
	.infotip:hover .infotip-content{ display: block; }
	.infotip h4{ margin: 0; line-height: normal; font-size: 13px; color: #777; }
	.infotip h5{ margin: 10px 0 5px 0; line-height: normal; font-size: 14px; }
	.infotip .name{ text-decoration: underline; }
	.infotip .rating{ font-weight: bold; }
	.infotip .date{ font-size: 10px; color: #777; }
	.infotip .outlook{ font-style: italic; font-size: 11px; line-height: normal; }
	.infotip p{ margin-left: 10px; }

</style>
<script>
	$(document).ready(function(){
		$('#filterDate').datepicker({ dateFormat: "dd/mm/yy" });
		$('#filterLimitsForm input, #filterLimitsForm select').bind('change', function(e){
			$('#filterLimitsForm').submit();
		});

		$('#limitsTable tr.group-limit a.toggle').bind('click', function(e){
			$('#limitsTable tr.group-counterparty').removeClass('show');
			if(!$(this).hasClass('active')){
				$(this).addClass('active');
				$('#limitsTable tr.group-counterparty-'+$(this).attr('data-group')).addClass('show');
			}else{
				$(this).removeClass('active');
			}
			e.preventDefault();
		});
	});
</script>