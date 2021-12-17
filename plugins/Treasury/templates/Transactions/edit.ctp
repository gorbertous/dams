<?php
    //echo $this->Html->css('/treasury/css/redmond/jquery-ui-1.10.3.custom.css');
    //echo $this->Html->css('/treasury/css/dataTableSort');
    //echo $this->Html->script('/treasury/js/jquery-ui-1.10.3.custom.min.js');
    echo $this->Html->script('/treasury/js/autoNumeric.js');
    //echo $this->Html->script('/treasury/js/form_ajax.js');
    //echo $this->Html->script('/treasury/js/transactions.js');
?>
<style type="text/css">
	input[type = text] {
		width: 80px;
    }
    #filter_selection
    {
        float: right;
    }
</style>
<fieldset>
    <legend> Correct / Delete Transaction </legend>
	    <div id="filter_selection">
			<?php echo $this->Form->create('filterform'); ?>
			<?php echo $this->Form->input('action', array(
                            'type'  => 'hidden',
                            'value' => 'filter',
                            'default' => $tr_number_filter
                        )); ?>
                    
			TRN&nbsp;
			<?php echo $this->Form->input('tr_number', array(
                            'type'  => 'text',
                            'div'   => false,
                            'style' =>  "width:6em; height:20px;",
                            'label' => false
                        )); ?>

			<?php echo $this->Form->input('mandate_id', array(
                            'label' => false,
                            'div'   => false,
                            'options'=> $mandates,
                            'style' => "width: 20em;",
                            'empty' => '-- Any mandate --',
							'default' => $mandate_id_filter
                        )); ?>

			<?php echo $this->Form->input('cpty_id', array(
                            'label' => false,
                            'div'   => false,
                            'style' => "width: 20em;",
                            'options'=> $counterparties,
                            'empty' => '-- Any counterparty --',
							'default' => $cpty_id_filter
                        )); ?>
			&nbsp;
			<?php // echo $this->Form->submit('Filter', array('class' => 'btn btn-primary')) 
            ?>
			<?php echo $this->Form->end(); ?>
		</div>

    <?php if (sizeof($transactions) > 0): ?>

       	<?php
		$perms = $this->Permission->getPermissions();
if ($perms['is_breach_tester'])
{// breach tester can edit but not delete
	$this->BootstrapTables->displayRawsAndLinks('selectTransaction', $transactions, array('transactions'), array('trcorrection_router'), array('Edit'), array('btn-success'), array('icon-edit'), 'tr_number', false);
}
else
{
		$headers = array('TRN Type', 'Commencement date', 'Maturity date', 'Term or Call', 'Renewal', 'Depo term', 'Source group', 'Parent id', 'Days', 'Reinv availability date', 'Mandate', 'Compartment', 'Amount');
		?>
		<table id="selectTransaction" class="table table-bordered table-stripped table-hover">
		<thead>
			<tr>
				<th> Select </th>
				<th class="type">Instrument Type</th>
				<th class="tr_number"><?php echo $this->Paginator->sort('tr_number', 'TRN'); ?></th>
				<th class="commencement_date">Commencement date</th>
				<th class="maturity_date">Maturity date</th>
				<th class="term_or_call">Term or Call</th>
				<th class="renewal">Renewal</th>
				<th class="depo_term">Depo term</th>
				<th class="days">Days</th>
				<th class="mandate_name">Mandate  </th>
				<th class="cmp_name">Compartment  </th>
				<th class="amount">Amount</th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ($transactions as $key => $value)
		{
			echo '<tr>';
			echo '<td>';
			echo '<div class="btn-group" data-toggle="buttons-checkbox">';
			echo '<a title="Edit" class="btn btn-success" href="/treasury/treasurytransactions/trcorrection_router/'.$value[0]['tr_number'].'">Edit</a>';
			
			$url_form_delete = '/treasury/treasurytransactions/deletetr';
			if ($value[0]['Type'] == 'Bond')
			{
				$url_form_delete = '/treasury/treasurytransactions/deleteBondtr';
			}
			
			echo $this->Form->create('del_user', array('url'=>$url_form_delete, 'style' => 'display: contents;'));
			echo $this->Form->input('Transaction.tr_number', array(
				'type' => 'hidden',
				'label'	=> false,
				'div'	=> false,
				'value'	=> $value[0]['tr_number'],
			));
			echo $this->Form->submit('Delete',
				array(
					'label' 	=> 'Delete',
					'type' 	=> 'submit',
					'class' => 'btn btn-danger',
					'div'	=> false//array('class' => array('span11'))
				)
			);
			echo $this->Form->end();

			echo '</div>';
			echo '</td>';

			echo '<td class="'.strtolower(Inflector::slug('Type')).'">'.UniformLib::uniform( $value[0]['Type'], 'Type').'</td>';
			echo '<td class="'.strtolower(Inflector::slug('tr_number')).'">'.UniformLib::uniform( $value[0]['tr_number'], 'tr_number').'</td>';
			echo '<td class="'.strtolower(Inflector::slug('commencement_date')).'">'.UniformLib::uniform( $value[0]['commencement_date'], 'commencement_date').'</td>';
			echo '<td class="'.strtolower(Inflector::slug('maturity_date')).'">'.UniformLib::uniform( $value[0]['maturity_date'], 'maturity_date').'</td>';
			echo '<td class="'.strtolower(Inflector::slug('Term_or_Call')).'">'.UniformLib::uniform( $value[0]['Term_or_Call'], 'Term_or_Call').'</td>';
			echo '<td class="'.strtolower(Inflector::slug('Renewal')).'">'.UniformLib::uniform( $value[0]['Renewal'], 'Renewal').'</td>';
			echo '<td class="'.strtolower(Inflector::slug('depo_term')).'">'.UniformLib::uniform( $value[0]['depo_term'], 'depo_term').'</td>';
			echo '<td class="'.strtolower(Inflector::slug('days')).'">'.UniformLib::uniform( $value[0]['days'], 'days').'</td>';
			echo '<td class="'.strtolower(Inflector::slug('mandate_name')).'">'.UniformLib::uniform( $value[0]['mandate_name'], 'mandate_name').'</td>';
			echo '<td class="'.strtolower(Inflector::slug('cmp_name')).'">'.UniformLib::uniform( $value[0]['cmp_name'], 'cmp_name').'</td>';
			echo '<td class="'.strtolower(Inflector::slug('Amount')).'">'.UniformLib::uniform( $value[0]['Amount'], 'Amount').'</td>';

			echo '</tr>';
		}
		echo '</tbody>';
		echo '</table>';
	}
	?>

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

        <?php
            //echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/jquery.dataTables.js');
            //echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/media/js/dataTables.bootstrap.js');
            //echo $this->Html->script('/theme/Cakestrap/js/libs/datatables/extras/TableTools/media/js/ZeroClipboard.js');
            //echo $this->Html->script('/treasury/js/ColumnFilter/media/js/jquery.dataTables.columnFilter.js');
        ?>
        <script>
            $(document).ready(function(){
               /* $("#selectTransaction").dataTable({});
                $('#selectTransaction tr').click(function () {
                        //$(this).find('td input:radio').prop('checked', true);
                        $(this).find('td input:radio:checked');
                        $('#selectTransaction tr').removeClass("active");
                        $(this).addClass("active");
                });*/
            });
        </script>
    <?php else: ?>
    <div class="well" style="clear: both;">There are no transactions to display. If you would like to correct the transactions manually, please contact the administrator.</div>
    <?php endif ?>
    <script>
        $(document).ready(function(){
            $("#filterformTrNumber, #filterformMandateId, #filterformCptyId").change(function(e){
                $("#filterformEditForm").submit();
            });
			
        });
    </script>
