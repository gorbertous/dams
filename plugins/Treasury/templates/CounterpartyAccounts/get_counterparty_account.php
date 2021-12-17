		<div class="span11">
		<label for="Accounts">Accounts</label>
			<table id="CounterpartyAccountTable" class="table table-bordered table-striped table-hover table-condensed">
				<thead>
					<tr>
						<td>Currency</td>
						<td>TARGET</td>
						<td>Correspondent Bank</td>
						<td>Correspondent BIC</td>
						<td>IBAN</td>
						<td>&nbsp;</td>
					</tr>
				</thead>
				<tbody>
<?php
				if (!empty($row))
				{
					$accounts = $row['CounterpartyAccount'];
					foreach($accounts as $account)
					{
						echo '<tr id="CounterpartyAccount_'.$account['cpty_acc_id'].'">';

						echo '<td>';
						echo $this->Form->input('CounterpartyAccount.currencies',
							array(
								'label'		=> false,
								'class'		=> 'span8',
								'options'	=> $currencies,
								'default'	=> $account['currency'],
							)
						);
						echo '</td>';

						echo '<td>';
						echo $this->Form->input('CounterpartyAccount.target',
							array(
								'label'		=> false,
								'type'		=> 'checkbox',
								'default'	=> $account['target'],
							)
						);
						echo '</td>';

						echo '<td>';
						echo $this->Form->input('CounterpartyAccount.correspondent_bank',
							array(
								'label'		=> false,
								'style'		=> 'width: 95%;',
								'default'	=> $account['correspondent_bank'],
							)
						);
						echo '</td>';

						echo '<td>';
						echo $this->Form->input('CounterpartyAccount.correspondent_BIC',
							array(
								'label'		=> false,
								'style'		=> 'width: 95%;',
								'default'	=> $account['correspondent_BIC'],
							)
						);
						echo '</td>';

						echo '<td>';
						echo $this->Form->input('CounterpartyAccount.account_IBAN',
							array(
								'label'		=> false,
								'style'		=> 'width: 95%;',
								'default'	=> $account['account_IBAN'],
							)
						);
						echo '</td>';

						echo '<td><a href="/treasury/treasurystaticdatas/counterpartyaccount/del_counterparty_account/'.$account['cpty_acc_id'].'" id="del_account_'.$account['cpty_acc_id'].'" class="btn btn-mini confirmation"><i class="icon-trash"></a></td>';//<a href="/damsv2/DSToolbox/delete/2" class="btn btn-mini confirmation"><i class="icon-trash"></i></a>
						echo '</tr>';
						echo '<script type="text/javascript">';
						echo '$("#CounterpartyAccount_'.$account['cpty_acc_id'].' input").change(update_cpty_account);';
						echo '</script>';
					}
				}
?>
				</tbody>
			</table>
			<a id="new_account">+ Add a new Account</a>
		</div>
		<script type="text/javascript">
			$("#new_account").mouseup(function()
			{
				var new_account = "";
				$.ajax({
					type: 'GET',
					url: '/treasury/Treasurycounterpartyaccount/new_counterparty_account',
					success: function(data)
					{
						if(data.length > 0)
						{
							$("#CounterpartyAccountTable tbody").append(data);
						}
					}
				});
			});

			function update_cpty_account(e)
			{
				var line_el = $(e.target);
				var line_tr = line_el.parents("tr");
				console.log(line_tr);
			}
		</script>