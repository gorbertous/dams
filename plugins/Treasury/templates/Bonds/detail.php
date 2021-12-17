<fieldset>
	<legend> Bonds details for RM </legend>
	<div id="filter_selection">
		<?php echo $this->Form->create('filterform'); ?>
		
		<?php echo $this->Form->input('mandate_id', array(
						'label' => false,
						'div'   => false,
						'options'=> $mandates,
						
						'empty' => '- Mandate -',
						'default' => $mandate_id_filter
					)); ?>

		<?php echo $this->Form->input('issuer', array(
						'label' => false,
						'div'   => false,
						
						'options'=> $issuer_list,
						'empty' => '- Issuer -',
						'default' => $issuer_filter
					)); ?>
		&nbsp;
		<?php  echo $this->Form->submit('Search', array('class' => 'btn btn-primary', 'id' => 'search_button', 'div' => false)) 
		?>
		<?php echo $this->Form->end(); ?>
	</div>
	
	
	<table class="table table-bordered table-striped table-hover table-condensed">
		<thead>
			<tr>
				<th><?php echo $this->Paginator->sort('ISIN'); ?></th>
				<th>Issuer</th>
				<th>CCY</th>
				<th>Issue Date</th>
				<th>Country</th>
				<th>Issue size</th>
				<th>Covered</th>
				<th>Secured</th>
				<th>Seniority</th>
				<th>Guarantor</th>
				<th>Structured</th>
				<th>Issuer Type</th>
				<th>Issue Rating S&amp;P</th>
				<th>Issue Rating Moody's</th>
				<th>Issue Rating Fitch</th>
				<th>Retained Rating</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
		<?php
		if (!empty($bonds))
		{
			foreach($bonds as $bond)
			{
				echo $this->Form->create('form_update_bond_'.$bond["Bond"]["bond_id"]);
				echo $this->Form->input('bond_id', array(
								'label' => false,
								'div'   => false,
								'type'	=> 'hidden',
								'class'	=>'bond_id',
								'value' => $bond["Bond"]["bond_id"],
							));

				echo $this->Form->input('ISIN', array(
								'label' => false,
								'div'   => false,
								'type'	=> 'hidden',
								'class'	=>'ISIN',
								'value' => $bond["Bond"]["ISIN"],
							));
				echo "<tr class='".$bond["Bond"]["bond_id"]."'>";
				echo "<td>".$bond["Bond"]["ISIN"]."</td>";
				echo "<td>".$bond["Bond"]["issuer"]."</td>";
				echo "<td>".$bond["Bond"]["currency"]."</td>";
				echo "<td>".$bond["Bond"]["issue_date"]."</td>";
		
				echo "<td>".$this->Form->input('country', array(
								'label' => false,
								'div'   => false,
								'options'	=> $country_values,
								'empty'		=> "",	
								'default' => $bond["Bond"]["country"],
							))."</td>";

				echo "<td>".$this->Form->input('issue_size', array(
								'label' => false,
								'div'   => false,
								'empty'		=> "",	
								'default' => $bond["Bond"]["issue_size"]
							))."</td>";

				echo "<td>".$this->Form->input('covered', array(
								'label' => false,
								'div'   => false,
								'options'=> $covered_values,
								'empty'		=> "",	
								'default' => $bond["Bond"]["covered"]
							))."</td>";

				echo "<td>".$this->Form->input('secured', array(
								'label' => false,
								'div'   => false,
								'options'=> $secured_values,
								'empty'		=> "",	
								'default' => $bond["Bond"]["secured"]
							))."</td>";

				echo "<td>".$this->Form->input('seniority', array(
								'label' => false,
								'div'   => false,
								'options'=> $seniority_values,
								'empty'		=> "",	
								'default' => $bond["Bond"]["seniority"]
							))."</td>";

				echo "<td>".$this->Form->input('guarantor', array(
								'label' => false,
								'div'   => false,
								'empty'		=> "",	
								'default' => $bond["Bond"]["guarantor"]
							))."</td>";

				echo "<td>".$this->Form->input('structured', array(
								'label' => false,
								'div'   => false,
								'options'=> $structured_values,
								'empty'		=> "",	
								'default' =>$bond["Bond"]["structured"]
							))."</td>";

				echo "<td>".$this->Form->input('issuer_type', array(
								'label' => false,
								'div'   => false,
								'options'=> $issuer_type_values,
								'empty'		=> "",	
								'default' => $bond["Bond"]["issuer_type"]
							))."</td>";

				echo "<td>".$this->Form->input('issue_rating_STP', array(
								'label' => false,
								'div'   => false,
								'options'=> $issue_rating_STP_values,
								"class"	=>	"rating",
								'default' => empty($bond["Bond"]["issue_rating_STP"]) ? 'NR' : urlencode($bond["Bond"]["issue_rating_STP"]),
							))."</td>";

				echo "<td>".$this->Form->input('issue_rating_MDY', array(
								'label' => false,
								'div'   => false,
								'options'=> $issue_rating_MDY_values,
								"class"	=>	"rating",
								'default' => empty($bond["Bond"]["issue_rating_MDY"]) ? 'NR' : urlencode($bond["Bond"]["issue_rating_MDY"]),
							))."</td>";

				echo "<td>".$this->Form->input('issue_rating_FIT', array(
								'label' => false,
								'div'   => false,
								'options'=> $issue_rating_FIT_values,
								"class"	=>	"rating",
								'default' => empty($bond["Bond"]["issue_rating_FIT"]) ? 'NR' : urlencode($bond["Bond"]["issue_rating_FIT"]),
							))."</td>";

				if (empty($bond["Bond"]["retained_rating"]))
				{
					$bond["Bond"]["retained_rating"] = 'NR';
				}
				echo "<td><span class='retained_rating' id='form_display_bond_".$bond["Bond"]["bond_id"]."RetainedRating'>".$bond["Bond"]["retained_rating"]."</span>";
				
				echo $this->Form->input('retained_rating', array(
								'label' => false,
								'div'   => false,
								'type'  => 'hidden',
								'class'	=>	'retained_rating',
								'default' => $bond["Bond"]["retained_rating"]
							))."</td>";

				echo "<td>".$this->Form->submit('Update', array('class' => 'btn btn-primary'))."</td>";

				echo "</tr>";

				echo $this->Form->end();
			}
		}
		?>
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
<style>
	input[type="text"]
	{
		width : 6em;
		height: 1.5em;
	}
	
	td select
	{
		width: 5em;
	}
	#search_button, #filterformIssuer, #filterformMandateId
	{
		float: left;
		margin-left: 5px;
	}
</style>
<script type="text/javascript">
	var bond_id=null;
	$(window).load(function()
	{
		$(".rating").change(function(e)
		{
			var bond_id_target = $(e.currentTarget);
			var parent = bond_id_target.parents('tr');
			bond_id = parent.attr('class');
			$.ajax(
			{
				async:true,
				dataType: "text",
				success:function (data, textStatus)
				{
					$("#form_update_bond_"+bond_id+"RetainedRating").val(data);
					$("#form_display_bond_"+bond_id+"RetainedRating").text(data);
				},
				type:"post",
				url:"\/treasury\/treasurybonds\/get_retained_rating\/"+$("#form_update_bond_"+bond_id+"IssueRatingSTP").val()+"\/"+$("#form_update_bond_"+bond_id+"IssueRatingMDY").val()+"\/"+$("#form_update_bond_"+bond_id+"IssueRatingFIT").val()+"\/1"
			});
		});
	});
</script>