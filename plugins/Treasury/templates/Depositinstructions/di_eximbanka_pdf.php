<?php
  // SPECIAL PREPROCESS FOR THIS CUSTOM TEMPLATE: only 3 transactions: Overnight, 3 months & 6 months
  $cust_transactions = array();

  if(!empty($transactions)){
    foreach($transactions as $key=>$transaction){
      if(!empty($transaction['Transaction']['depo_term'])){

        if(empty($transaction['Transaction']['maturity_date'])) $transaction['Transaction']['maturity_date'] = $transaction['Transaction']['indicative_maturity_date'];
        $cust_transactions[] = array(
          'amount'=> $transaction['Transaction']['amount'],
          'commencement_date'=> $transaction['Transaction']['commencement_date'],
          'maturity_date'=> $transaction['Transaction']['maturity_date']
        );

      }
    }
  }

?><!-- di -->
<html>
<head>
  <?php print $this->Html->css('Treasury.DepositInstruction',null, array('fullBase' => true)); ?>
</head>
<body class="v2tpl eximbanka">
  <?php
	  $arr_k = array_keys($transactions);
	  $first_trn_key = array_shift($arr_k);
          $html = "<center><h4>SCHEDULE 4 <br><br>FORM OF TREASURY STRATEGY INSTRUCTION</h4></center>
          <p>
            <u>From EIF to ".UniformLib::uniform($transactions[$first_trn_key]['Counterparty']['cpty_name'], 'cpty_name').":</u><br>
            ".nl2br(UniformLib::uniform($transactions[$first_trn_key]['Counterparty']['cpty_address'], 'cpty_address'))." <br>
            ".UniformLib::uniform($transactions[$first_trn_key]['Counterparty']['cpty_zipcode'], 'cpty_zipcode')." ".UniformLib::uniform($transactions[$first_trn_key]['Counterparty']['cpty_city'], 'cpty_city')."<br>".UniformLib::uniform($transactions[$first_trn_key]['Counterparty']['cpty_country'], 'cpty_country')." <br>
          </p>";

          $hdrdate = date('j M Y');
          if(!empty($headerdate)) $hdrdate=date('d/m/Y',strtotime(str_replace('/','-',$headerdate)));
          $html.="
          <br>
          <p style='text-align: right; margin-top: -40px;'>Luxembourg, ".$hdrdate."</p>";
          
          if(!empty($preamble)) $html.='<p class="preamble">'.$preamble.'</p>';
          
          $mandate_bu = '';
          if(!empty($transactions[$first_trn_key]['Mandate']['BU'])) $mandate_bu=' ('.UniformLib::uniform($transactions[$first_trn_key]['Mandate']['BU'], 'mandate_bu').')';

          $html.='<p>A.<br>For the purposes of the JEREMIE ("Joint European Resources for Micro to Medium Entreprises") Slovakia Holding Fund'.$mandate_bu.', the European Investment Fund (the Pledgee) requests '.UniformLib::uniform(strtoupper($transactions[$first_trn_key]['Counterparty']['cpty_name']), 'cpty_name').' to block the following funds '.$x5.' in line with the below specified terms:</p>'; ?>

          <?php if(sizeof($cust_transactions) > 0): ?>
          <?php $html .= "
          <table class='deposit'>
              <tr>
                <th></th>
                <th style='text-align: left'>Principal <br>amount in EUR<sup>1</sup></th>
                <th style='text-align: left'>Commencement <br>Date</th>
                <th>Maturity Date</th>
                <th style='text-align: left'>Interest rate in <br>% p.a</th>
        <th style='text-align: left'>Automatic rollover <br>for 1 (one) day <br>(overnight)<sup>2</sup></th>
              </tr>
            <tbody>"; ?>
            
              <?php 
              $i = 0;
              foreach ($cust_transactions as $cust_transaction) {
                $i++;
                $html .= "
                  <tr class='deposit_row'>
                    <td style='text-align: center'>".$i."</td>
                    <td style='text-align:right'>".(!empty($cust_transaction['amount'])?UniformLib::uniform($cust_transaction['amount'], 'amount'):'')."</td>
                    <td style='text-align:center; text-transform: uppercase;'>".UniformLib::uniform($cust_transaction['commencement_date'], 'commencement_date')."</td>
                    <td style='text-align:center; text-transform: uppercase;'>".UniformLib::uniform($cust_transaction['maturity_date'], 'maturity_date')."</td>
                    <td>corresponding <br>Money Market <br>interest rate</td>
                    <td style='text-align:center'>NO</td>
                  </tr>"; 
                  }
                ?>

            <?php $html .= "
            </tbody>
          </table> " ?>

          <?php $html.='<p>B.<br>For the purposes of the JEREMIE ("Joint European Resources for Micro to Medium Entreprises") Slovakia Holding Fund'.$mandate_bu.', the European Investment Fund (the Pledgee) requests '.UniformLib::uniform(strtoupper($transactions[$first_trn_key]['Counterparty']['cpty_name']), 'cpty_name').' to terminate the following funds from automatic rollover for 1 (one) day (overnight) in line with the below specified terms<sup>3</sup>:</p>'; ?>

          <?php $html .= "
          <table class='deposit'>
              <tr>
                <th></th>
                <th style='text-align: left'>Confirmation No. of <br> terms of blocked amounts<sup>4</sup></th>
                <th style='text-align: left'>Principal amount in EUR</th>
                <th>Maturity Date<sup>5</sup></th>
              </tr>
            <tbody>"; ?>

              <?php 
              $i = 0;
              foreach ($cust_transactions as $cust_transaction) {
                $i++;
                $html .= "
                  <tr class='deposit_row'>
                    <td style='text-align: center'>".$i."</td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>"; 
                  }
                ?>

            <?php $html .= "
            </tbody>
          </table> " ?>

          <?php else: ?>
            <?php $html .= "<p class='well'><strong>There are no transactions to instruct.</strong></p>" ?>
          <?php endif; ?>
          
          <?php $html .= "
          <br>
          <p style='text-align: center; margin: 30px 0 30px;'>European investment Fund, <br>acting as the Pledgee</p>
          <table class='signature'>
            <tr><td>Signature....................................</td><td style='width: 10%'>Signature....................................</td></tr>
          </table>
          <br>
      <br>
      <br>
      <hr style='max-width: 200px; margin-left:0;'>
          <p class='footnote'><sup>1</sup> Minimum Principal amount required for all term blocking 500,000.00 - euros<br>
      &nbsp;&nbsp;&nbsp;Note: All principal amounts shall be rounded to whole euros.<br>
      <sup>2</sup> Fill YES if you require the automatic rollover for overnight term blocking. Fill NO if you do not require the automatic rollover for <br>&nbsp;&nbsp;&nbsp; overnight term blocking.<br>
      <sup>3</sup> Fill only in  case you require termination of automatic rollover 1 (one) day (overnight)<br>
      <sup>4</sup> Fill the number of Confirmation which represents the automatic rollover for 1 (one) day (overnight) you want to terminate<br>
      <sup>5</sup> Fill the date at which you wish to terminate the automatic rollover for 1 (one) day (overnight)
      </p>"; ?>
		  <?php if ($transaction['AccountA']['BIC'] == 'BGLLLULL')
			$html .= '<p style="font-size: 16px; background-color: yellow;">For new Deposit, please instruct the transfer via Multiline</p>';
		  ?>
          <?php echo $html; ?>
  </body>
  </html>
<!-- end di -->
