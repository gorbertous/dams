<fieldset class="querypage">
<legend class="noprint">Query Bonds</legend>

<?php
  echo $this->Html->css('/treasury/css/redmond/jquery-ui-1.10.3.custom.css');
  echo $this->Html->css('/treasury/css/datepicker');
  //echo $this->Html->script('/treasury/js/jquery-ui-1.10.3.custom.min.js');
  echo $this->Html->script('/treasury/js/bootstrap-datepicker');
  echo $this->Html->script('/treasury/js/jquery-sortable-min');
  
?>

<style>
  #BondtransactionAllForm select { width: 100% }
  #BondtransactionAllForm .formcol input{ width: 108px; margin-right: 10px; }
  #BondtransactionAllForm .formcol input.last{ margin-right: 0; }
  #BondtransactionAllForm .submit input.btn{ width: 100% !important; }
  .datesep{ margin-right: 10px; }
  .submit .btn{ margin-top:25px; width: 200px !important;}
  #BondtransactionAllForm label{ width: 100% !important;}

  .customfieldsblock{ margin-bottom: 20px; }
  .customfieldsblock .sortablelist{ float: left; width: 100%; margin: 0 10px 0 0; }
  .customfieldsblock .sortablelist .content{ background: #eee; float: left; width: 100%; min-height: 100px; margin-bottom: 5px; padding-bottom: 3px;  }
  .customfieldsblock .sortablelist .contentlist{ padding: 5px;   }
  .customfieldsblock .sortablelist .content .sortable{ height: 100%; min-height: 100px; }
  .customfieldsblock .sortable{ padding: 0; margin: 0; width: 100%; float: left; }
  .customfieldsblock .sortable li{ float: left; min-width: 30px; min-height: 23px; width: 100%; list-style: none; margin: 0 0 2px 0; line-height: normal; font-size: 12px; background: #fff;  }

  .customfieldsgroup .infos{ font-weight: bold; font-size: 13px; }
  .customfieldsblock .sortable li .lbl{ padding: 4px 8px; display: block;  }
  .customfieldsblock.visible .sortablelist .btn{ float: left; }

  /*.customfieldsblock.available .sortable li{ width: auto; margin: 0 2px 2px 0; background: #eee; min-height: 0; }
  .customfieldsblock.available .sortable li .lbl{ padding: 3px 5px; }*/
  .customfieldsblock.available .sortable li{ width: 32.9%; margin: 0 1px 3px 1px; }

  #quickfilters .hidden{ display: none; }
  #quickfilters .btns{ position: absolute; top: 0; right: 0;}
  #quickfilters .btns > *{ margin-left: 10px; float: right; }
  #quickfilters .btnsctrl a{ margin-bottom: 5px; width: 195px; }
  #actions{ padding-bottom: 40px; position: relative; padding-top: 0 !important; }
  .customfieldsgroup .customfields { margin-top: 10px; }

  #queries tr th{ padding: 4px 10px;  }
  #queries tr td{ white-space: nowrap; padding: 4px 10px;  }
  #queries tr td.Bondtransaction_amount,
  #queries tr td.Bondtransaction_amount_eur,
  #queries tr td.Bondtransaction_commencement_date,
  #queries tr td.Bondtransaction_maturity_date,
  #queries tr td.Bondtransaction_indicative_maturity_date,
  #queries tr td.Bondtransaction_interest_rate,
  #queries tr td.Bondtransaction_reference_rate,
  #queries tr td.Bondtransaction_interest,
  #queries tr td.Bondtransaction_total_interest,
  #queries tr td.Bondtransaction_accrued_interst,
  #queries tr td.Bondtransaction_instr_num,
  #queries tr td.Bondtransaction_date_basis,
  #queries tr td.Bondtransaction_eom_interest,
  #queries tr td.Bondtransaction_eom_booking,
  #queries tr td.Bondtransaction_fixing_date,
  #queries tr td.Bondtransaction_fixing_date,
  #queries tr td.Bondtransaction_fromreinv,
  #queries tr td.Bondtransaction_inreinv,
  #queries tr td.Bondtransaction_original_id,
  #queries tr td.Bondtransaction_parent_id,
  #queries tr td.Bondtransaction_depo_term,
  #queries tr td.Bondtransaction_tax,
  #queries tr td.Bondtransaction_tr_number,
  #queries tr td.Bondtransaction_amount,
  #queries tr td.Bondtransaction_amount_eur,
  #queries tr td.Bondtransaction_amount_eur,
  #queries tr td.Bondtransaction_commencement_date,
  #queries tr td.Bondtransaction_days{ text-align: right; }
  

  body.dragging, body.dragging * {
    cursor: move !important;
  }
  .dragged {
    position: absolute;
    opacity: 0.5;
    z-index: 2000;
  }
  ol.sortable li.placeholder {
    position: relative; 
    background: #fcc;
  }
  ol.sortable li.placeholder:before {
    position: absolute;
  }
  @media print {
    *{ overflow: visible !important; }
    #queries{ float: left; display: block !important; }
  }
</style>
<script>
$(document).ready(function () {

    var issueFrom;
    var issueTo;

    issueFrom = $('#issueFrom').datepicker({}).on('changeDate', function(ev) {
      if (ev.date.valueOf() > issueTo.date.valueOf()) {
        var newDate = new Date(ev.date);
        newDate.setDate(newDate.getDate() + 1);
        issueTo.setValue(newDate);
      }
      issueFrom.hide();
      $('#issueTo')[0].focus();
    }).data('datepicker');

    issueTo = $('#issueTo').datepicker({
      onRender: function(date) {
        return date.valueOf() <= issueFrom.date.valueOf() ? 'disabled' : '';
      }
    }).on('changeDate', function(ev) {
      issueTo.hide();
    }).data('datepicker');

    var settlFrom;
    var settlTo;

    settlFrom = $('#settlFrom').datepicker({}).on('changeDate', function(ev) {
      if (ev.date.valueOf() > settlTo.date.valueOf()) {
        var newDate = new Date(ev.date);
        newDate.setDate(newDate.getDate() + 1);
        settlTo.setValue(newDate);
      }
      settlFrom.hide();
      $('#settlTo')[0].focus();
    }).data('datepicker');

    settlTo = $('#settlTo').datepicker({
      onRender: function(date) {
        return date.valueOf() <= settlFrom.date.valueOf() ? 'disabled' : '';
      }
    }).on('changeDate', function(ev) {
      settlTo.hide();
    }).data('datepicker');

    var matFrom;
    var matTo;

    matFrom = $('#matFrom').datepicker({}).on('changeDate', function(ev) {
        if (ev.date.valueOf() > matTo.date.valueOf()) {
          var newDate = new Date(ev.date);
          newDate.setDate(newDate.getDate() + 1);
          matTo.setValue(newDate);
        }
        matFrom.hide();
        $('#matTo')[0].focus();
    }).data('datepicker');

    matTo = $('#matTo').datepicker({
      onRender: function(date) {
        return date.valueOf() <= matFrom.date.valueOf() ? 'disabled' : '';
      }
    }).on('changeDate', function(ev) {
      matTo.hide();
    }).data('datepicker');

    //copy BT
      $('#quickfilters .btns').append('<div class="btn pull-right" id="copyBT">Copy to Excel</div>')        
      $('#copyBT').bind('click', function(e){
        e.preventDefault();
        try{
          var textRange = document.body.createTextRange(); 
          if($('#queriesCopy').length){
            textRange.moveToElementText(document.getElementById('queriesCopy')); 
          }else{
            textRange.moveToElementText(document.getElementById('queries')); 
          }            
          textRange.execCommand("Copy");
          alert('The table has been copied to the clipboard. You can now paste it in any Excel spreadsheet.');
        }catch(e){
          alert('Due to compatibility issues, copy to Excel function is only available from Internet Explorer');
        }
        return false;
      });
    //print BT
      $('#quickfilters .btns').append('<div class="btn pull-right" id="printBT">Print</div>')    
      $('#printBT').bind('click', function(e){
        window.print();
        e.preventDefault();
        return false;
      });

    // layout
    $('#quicklayout').bind('change', function(e){
      $('#BondtransactionLayout').val($(this).val());
      //$('#customfieldstoggle').addClass('hidden');

      $('#BondtransactionAllForm').submit();

      /*if($(this).val()!='custom'){
        $('#BondtransactionAllForm').submit();
      }else{
        $('#customfieldstoggle').removeClass('hidden');
      }*/
    });

    if($('#quicklayout').val()=='custom'){
      $('#customfieldstoggle').removeClass('hidden');
    }
    $('#customfieldstoggle').bind('click', function(e){
      if($('.customfieldsgroup').hasClass('hidden')){
        $('.customfieldsgroup').removeClass('hidden');
        $('.on', this).addClass('hidden');
        $('.off', this).removeClass('hidden');
      }else{
        $('.customfieldsgroup').addClass('hidden');
        $('.on', this).removeClass('hidden');
        $('.off', this).addClass('hidden');
      }
      
      e.preventDefault();
      return false;
    });

    //custom fields: sortable
    $('.visible ol.sortable').sortable({
      group: 'draggroup',
      onDrop: function  (item, targetContainer, _super) {
        updateVisibleCount();
        updateVisibleList();
        
        _super(item);
      },
    });
    $('.customfieldsblock.available ol.sortable').sortable({
      group: 'draggroup'
    });
    $('.customfieldsblock .btn.refresh').bind('click', function(e){
      e.preventDefault;
      $('#BondtransactionAllForm').submit();
      return false;
    });
    $('.customfieldsblock .btn.clear').bind('click', function(e){
      e.preventDefault;
      $('.customfieldsblock.visible ol.sortable li').each(function(i, item){
        $('.customfieldsblock.available ol.sortable').append(item);
      });
      return false;
    });
    $('.customfieldsblock.available ol.sortable').delegate('li', 'click', function(e){
      $('.customfieldsblock.visible ol.sortable').append($(this));
      updateVisibleCount();
      updateVisibleList();
      //e.preventDefault();
      //return false;
    });
    
    function updateVisibleCount(){
      var list = $('.visible ol.sortable');
      var count = $('li', list).length;
      $('.visible .count').text('('+count+')');
    }
    function updateVisibleList(){
      var list = [];
      $('.visible ol.sortable li').each(function(i, item){
        list.push( $(item).attr('data-val'));
      });
      
      if(list.length>0) $('#BondtransactionCustomfields').val( JSON.stringify( list ) );
      return list;
    }

    //add current displayed columns to the visible list (and remove them from the available list)
    if($('#queries thead th').length){
      $('#queries thead th').each(function(i, item){
        if($(item).attr('data-val')){
          var field = $('.available ol.sortable li.col-'+$(item).attr('class'));
          $('.customfieldsblock.visible ol.sortable').append(field);
        }
      });
    }else{
      $('.customfieldsblock.visible ol.sortable').prepend($('.available ol.sortable li.col-Bondtransaction_tr_number'));
    }

    //update current infos
    updateVisibleCount();
    updateVisibleList();
    
});
</script>
<div class="row-fluid noprint <?php if(!empty($Bondtransactions)) print ' hidden' ?>">
    <?php echo $this->Form->create('Bondtransaction', array('url'=>'/treasury/treasurybondqueries/all', 'class'=>'', 'type' => 'post'));?>
    <div class=" pull-left">
      <div class="well span3 formcol">
        <?php echo $this->Form->input('Bondtransaction.tr_number', array(
            'type' => 'text',
            'label'=> 'TRN',
			'maxlength' => null
          ));
        ?>
		<?php echo $this->Form->input('Bond.ISIN', array(
            'type' => 'text',
            'label'=> 'ISIN',
			'maxlength' => null,
			'required'	=> false,
          ));
        ?>
        <?php echo $this->Form->input('Bondtransaction.tr_type', array(
            'options' => $tr_types,
            'empty'   => __('-- Type --'),
            'label'=> 'Type'
          ));
        ?>
        <?php echo $this->Form->input('Bondtransaction.tr_state', array(
            'options' => $tr_states,
            'empty'   => __('-- State --'),
            'label'=> 'State'
          ));
        ?>

      </div>
      <div class = "well span5 formcol">
        <?php echo $this->Form->input('Bondtransaction.mandate_ID', array(
            'options' => $mandates_portfolio_list,
            'empty'   => __('-'),
            'label' => 'Mandate / Portfolio'
          ));
        ?>
        <?php echo $this->Form->input('Bondtransaction.cmp_ID', array(
            'options' => $cmp_list,
            'empty'   => __('-'),
            'label' => 'Compartment'
          ));
        ?>
        <?php echo $this->Form->input('Bondtransaction.cpty_id', array(
            'options' => $cpty_list,
            'empty'   => __('-'),
            'label' => 'Counterparty'
          ));
        ?>
        <?php echo $this->Form->input('Bondtransaction.issuer', array(
            'options' => $issuer_list,
            'empty'   => __('-'),
            'label' => 'Issuer'
          ));
        ?>
      </div>
      <div class="well span4 formcol">
        <?php echo $this->Form->input('Bondtransaction.instr_num', array(
            'type'    => 'text',
            'label'=> 'BI'
          ));
        ?>
        <div class="input date">
          <?php echo $this->Form->input('From', array(
              'name'=> 'data[Dates][issue_from]',
              'label' => 'Issue Date',
              'data-date-format'  => 'dd/mm/yyyy',
              'id'  => 'issueFrom',
              'div'=> false,
              'default'=>!empty($this->request->data['Dates']['issue_from'])?$this->request->data['Dates']['issue_from']:''
            ));
          ?><span class="datesep">to</span>
          <?php echo $this->Form->input('To', array(
              'name'=> 'data[Dates][issue_to]',
              'label' => false,
              'data-date-format'  => 'dd/mm/yyyy',
              'id' => 'issueTo',
              'div'=> false,
              'class'=>'last',
              'default'=>!empty($this->request->data['Dates']['issue_to'])?$this->request->data['Dates']['issue_to']:''
            ));
          ?>
        </div>

        <div class="input date">
          <?php echo $this->Form->input('From', array(
              'name'=> 'data[Dates][settl_from]',
              'label' => 'Settlement Date',
              'data-date-format'  => 'dd/mm/yyyy',
              'id'  => 'settlFrom',
              'div'=> false,
              'default'=>!empty($this->request->data['Dates']['settl_from'])?$this->request->data['Dates']['settl_from']:''
            ));
          ?><span class="datesep">to</span>
          <?php echo $this->Form->input('To', array(
              'name'=> 'data[Dates][settl_to]',
              'label' => false,
              'data-date-format'  => 'dd/mm/yyyy',
              'id' => 'settlTo',
              'div'=> false,
              'class'=>'last',
              'default'=>!empty($this->request->data['Dates']['settl_to'])?$this->request->data['Dates']['settl_to']:''
            ));
          ?>
        </div>

        <div class="input date">
          <?php echo $this->Form->input('From', array(
              'name'=> 'data[Dates][mat_from]',
              'label' => 'Maturity Date',
              'data-date-format'  => 'dd/mm/yyyy',
              'id' => 'matFrom',
              'div'=> false,
              'default'=>!empty($this->request->data['Dates']['mat_from'])?$this->request->data['Dates']['mat_from']:''
            ));
          ?><span class="datesep">to</span>
          <?php echo $this->Form->input('To', array(
              'name'=> 'data[Dates][mat_to]',
              'label' => false,
              'data-date-format'  => 'dd/mm/yyyy',
              'id' => 'matTo',
              'div'=> false,
              'class'=>'last',
              'default'=>!empty($this->request->data['Dates']['mat_to'])?$this->request->data['Dates']['mat_to']:''
            ));
          ?>
        </div>
      </div>
      <div class="well span4 pull-right formactions">
        <?php echo $this->Form->input('layout', array(
            'options' => array('default'=>'Standard', 'custom'=>'Custom'),
            'label' => 'Layout'
          ));
          echo $this->Form->input('customfields', array(
            'type'=>'text',
			'style'	=> 'display:none;',
            'default'=> !empty($customfields)?json_encode($customfields):''
          ));
        ?>
        <div class="span12 noleftmargin">
          <div class="span3">
            <a class="btn btn-block btn-default colr-md-3" href="?reset=1">Reset</a>
          </div>
          <?php echo $this->Form->submit('Search',array(
              'class' => 'btn btn-block btn-primary',
              'div'=> 'span9',
            ));
          ?>
        </div>
          
      </div>
    </div>
    <?php echo $this->Form->end() ?>
</div>
<div style="display:none;">
<?php
echo $this->Form->create('getcmp', array('url'=>'/treasury/treasuryajax/getcmpbymandate_orall'));
echo $this->Form->input('Transaction.mandate_ID', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));
echo $this->Form->end();

echo $this->Form->create('getcpty', array('url'=>'/treasury/treasuryajax/getcptybymandate_orall'));
echo $this->Form->input('Transaction.mandate_id', array(
	'type' => 'text',
	'label'	=> false,
	'div'	=> false,
));

echo $this->Form->end();
?>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#BondtransactionMandateID').change(function(e)
	{
		$('#getcmpAllForm #TransactionMandateID').val( $('#BondtransactionAllForm #BondtransactionMandateID').val() );
		var data = $('#getcmpAllForm').serialize();
		$.ajax({
			type: "POST",
			url: '/treasury/treasuryajax/getcmpbymandate_orall',
			dataType: 'text', 
			data: data,
			async:true,
			success:function (data, textStatus) {
				$('#BondtransactionCmpID').html(data);
			}
		});
	});
	$('#BondtransactionMandateID').change(function(e)
	{
		$('#getcptyAllForm #TransactionMandateId').val( $('#BondtransactionAllForm #BondtransactionMandateID').val() );
		var data = $('#getcptyAllForm').serialize();
		$.ajax({
			type: "POST",
			url: '/treasury/treasuryajax/getcptybymandate_orall',
			dataType: 'text', 
			data: data,
			async:true,
			success:function (data, textStatus) {
				$('#BondtransactionCptyId').html(data);
			}
		});
	});
});
</script>
<?php if(!empty($Bondtransactions)): ?>
<div class="noprint noleftmargin" id="actions">
  <div id="quickfilters">
    <div class="span3">
      <?php
        echo $this->Form->input('quicklayout', array(
          'options' => array('default'=>'Standard layout', 'custom'=>'Custom layout'),
          'label' => false,
          'default'=> !empty($currentlayout)?$currentlayout:'',
          'div'=>'span12',
        )); ?>
        <div class="btnsctrl">
          <a href="#" id="customfieldstoggle" class="btn btn-default customfields hidden"><span class="on">Select columns</span><span class="off hidden">Hide columns choice</span></a>
          <?php print $this->Html->link(
            'Change parameters',
            'all',
            array('class' => 'btn', 'escape'=>false)
          ); ?>
        </div>
    </div>

    <div class="customfieldsgroup hidden span9 row">
      <div class="infos span10">
        <p>Drag and drop column labels to remove, select or change order of the columns.</p>
      </div>
      <div class="customfields span3 visible customfieldsblock noleftmargin">
        <div class="sortablelist">
          Visible columns <span class="count">(0)</span>
          <div class="content"><div class="contentlist">
            <ol class="sortable"></ol>
          </div></div>
          <button class="btn btn-default clear btn-block">Clear list</button>
        </div>
      </div>

      <div class="customfields span8 available customfieldsblock">
        <div class="sortablelist">
          Available columns
          <div class="content"><div class="contentlist">
            <ol class="sortable">
              <?php foreach($allfields as $key=>$val) print '<li class="col-'.strtolower(str_replace('.','_',$key)).'" data-val="'.$key.'"><span class="lbl">'.$val.'</span></li>'; ?>
            </ol>
          </div></div>
          <button class="btn btn-primary refresh btn-block">Update Results</button>
        </div>
      </div>
    </div>

    <div class="btns">

    </div>
  </div>
</div>
<table id="queries" class="table table-bordered table-striped table-hover table-condensed">
  <thead><tr>
  <?php foreach($layout as $key=>$col){
      print '<th class="'.strtolower(str_replace('.','_',$col)).'" data-val="'.$col.'">'.$key.'</th>';
  } ?>
  </tr></thead>
  <tbody>
    <?php 
      foreach($Bondtransactions as $trn){
        print '<tr>';
		
        foreach($trn as $key=>$col){
          if(empty($col)) $col='&nbsp;';
		  //if ($key == "Bond.coupon_frequency"){ $col = ucfirst($col); }
          print '<td class="'.strtolower(str_replace('.','_',$key)).'">'.$col.'</td>';
        }
        print '</tr>';
      }
    ?>
  </tbody>
</table>
<?php endif ?>
</fieldset>