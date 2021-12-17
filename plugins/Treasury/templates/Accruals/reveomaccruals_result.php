<fieldset>
	<legend>REV-EOM ACCRUALS Run Report <small><?php echo $year ?>/<?php echo $month ?></small></legend>
	<p class="well"><?php echo $transaction_id ?>.csv</p>
	<table class="table table-striped">
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
		            $row['HistoPs']['tr_number'],
		            $row['HistoPs']['account'],
		            $row['HistoPs']['foreign_currency'],
		            UniformLib::uniform($row['HistoPs']['foreign_amount'], 'foreign_amount'),
		            $row['HistoPs']['transaction_line'],
		            $row['HistoPs']['line_descr'],
		            $row['HistoPs']['trans_ref_num'],
		            $row['HistoPs']['header_description'],
		        ));
	    	} ?>
	    </tbody>
	</table>
	<!-- <div class="pagination pull-right">
		<ul><?php
			echo $this->Paginator->prev('«', array('tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li', 'disabledTag' => 'a'));
			echo $this->Paginator->numbers(array('separator' => '</li><li>', 'currentClass' => 'disabled', 'before' => '<li>', 'after' => '</li>'));
			echo $this->Paginator->next('»', array('tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li', 'disabledTag' => 'a')); ?>
		</ul>
	</div> -->
	<p><a href="/treasury/treasuryajax/download_file/1?file=/data/treasury/peoplesoft/<?php echo $transaction_id ?>.csv">Click here to download the CSV file</a></p>
	<p><a href="/treasury/treasuryaccruals/eom_pdf/<?php echo $transaction_id ?>/<?php echo $year ?>/<?php echo $month ?>">Click here to download the PDF file</a></p>
</fieldset>