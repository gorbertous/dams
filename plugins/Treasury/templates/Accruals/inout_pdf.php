<!-- di -->
<html>
<head>
  <link rel="stylesheet" href="http://localhost/theme/Cakestrap/css/bootstrap.css">
</head>
<body>
	<fieldset>
		<legend>IN-OUT Booking Report - <?php echo $transaction_id ?> <br><small>From <?php echo $start_date ?> To <?php echo $end_date ?></small></legend>
		<br><br>
		<table style="font-size:10px;" class="table table-striped">
		    <thead>
		        <?php
		        echo $this->Html->tableHeaders(array(
		            $this->Paginator->sort('tr_number', 'TRN'),
		            $this->Paginator->sort('account', 'PS Account'),
		            $this->Paginator->sort('foreign_currency', 'CCY'),
		            $this->Paginator->sort('foreign_amount', 'Amount'),
		            $this->Paginator->sort('transaction_line', 'Line'),
		            $this->Paginator->sort('line_descr', 'Line Description'),
		            $this->Paginator->sort('trans_ref_num', 'Journal'),
		            $this->Paginator->sort('header_description', 'Journal Description'),
		        ));
		        ?>
		    </thead>
		    <tbody>
		    	<?php foreach ($rows as &$row){
			        if(!empty($row['HistoPs'])) echo $this->Html->tableCells(array(
			            UniformLib::uniform($row['HistoPs']['tr_number'], 'tr_number'),
			            $row['HistoPs']['account'],
			            UniformLib::uniform($row['HistoPs']['foreign_currency'], 'foreign_currency'),
			            UniformLib::uniform($row['HistoPs']['foreign_amount'], 'foreign_amount'),
			            $row['HistoPs']['transaction_line'],
			            UniformLib::uniform($row['HistoPs']['line_descr'], 'line_descr'),
			            UniformLib::uniform($row['HistoPs']['trans_ref_num'], 'trans_ref_num'),
			            UniformLib::uniform($row['HistoPs']['header_description'], 'header_description'),
			        ));
		    	} ?>
		    </tbody>
		</table>
	</fieldset>
	<p class="well">Generated on <?php echo date("d/m/Y H:i") ?></p>
</body>
</html>
<!-- end di -->