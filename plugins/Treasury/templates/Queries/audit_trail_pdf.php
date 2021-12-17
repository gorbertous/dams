<!-- AUDIT -->
<html>
<head>
  <link rel="stylesheet" href="http://localhost/theme/Cakestrap/css/bootstrap.css">
</head>
<body>
	<fieldset>
		<legend>Audit Log Trail<br><small><?php echo date('d/m/Y H:i:s') ?></small></legend>
		<br><br>
		<table style="font-size:10px;" class="table table-striped">
		    <thead>
		        <?php
				$headers = array();
				$headers[] = $this->Paginator->sort('TRN', 'TRN');
				$headers[] = $this->Paginator->sort('Date time', 'Date time');
				$headers[] = $this->Paginator->sort('User', 'User');
				$headers[] = $this->Paginator->sort('Message', 'Message');
				echo $this->Html->tableHeaders($headers);
		        ?>
		    </thead>
		    <tbody>
		    	<?php 
				foreach ($content as $row)
				{
					echo $this->Html->tableCells($row['log_entries']);
		    	}
				?>
		    </tbody>
		</table>
	</fieldset>
		<style>
		thead
		{
			display:table-header-group;
		}
		tfoot
		{
			display:table-row-group;
		}
		tr
		{
			page-break-inside: avoid;
		}
	</style>
</body>
</html>
<!-- END AUDIT -->