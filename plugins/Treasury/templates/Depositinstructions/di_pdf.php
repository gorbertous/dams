<!-- di -->
<html>
<head>
  <?php print $this->Html->css('Treasury.DepositInstruction', null, array('fullBase' => true)); ?>
</head>
<body class="v2tpl"><div class="maincontainer">
  <?php
  $arr_k = array_keys($transactions);
  $first_trn_key = array_shift($arr_k);
  $html = "<h1>Deposit Instruction $instr_num</h1>
          <table class='header'>
            <tr>
              <td>From</td>
              <td>European Investment Fund</td>
              <td>To</td>
              <td>".UniformLib::uniform($transactions[$first_trn_key]['Counterparty']['cpty_name'], 'cpty_name')."</td>
            </tr>
            <tr>
              <td></td>
              <td>EIF-Admin-Expenses@eif.org</td>
              <td></td>
              <td>".nl2br(UniformLib::uniform($transactions[$first_trn_key]['Counterparty']['cpty_address'], 'cpty_address'))."</td>
            </tr>
            <tr>
              <td></td>
              <td>tel. +352 24 85 81 491</td>
              <td></td>
              <td>".UniformLib::uniform($transactions[$first_trn_key]['Counterparty']['cpty_zipcode'], 'cpty_zipcode')." ".UniformLib::uniform($transactions[$first_trn_key]['Counterparty']['cpty_city'], 'cpty_city')."<br>".UniformLib::uniform($transactions[$first_trn_key]['Counterparty']['cpty_country'], 'cpty_country')."</td>
            </tr>
            <tr>
              <td></td>
              <td>fax. +352 24 85 81 302</td>
              <td></td>
              <td>fax. ".UniformLib::uniform($transactions[$first_trn_key]['Counterparty']['fax1'], 'counterparty_fax1')."</td>
            </tr>";
          if(!empty($attn)) $html.="<tr>
              <td></td>
              <td></td>
              <td></td>
              <td>ATTN: ".$attn."</td>
            </tr>";

          $hdrdate = date('j F Y');
          if(!empty($headerdate)) $hdrdate=date('j F Y',strtotime(str_replace('/','-',$headerdate)));
          $html.="</table>
          <br>
          <p>Luxembourg, ".$hdrdate."</p>";
          
          if(!empty($preamble)) $html.='<p class="preamble">'.$preamble.'</p>';
          
          $mandate_bu = '';
          if(!empty($transactions[$first_trn_key]['Mandate']['BU'])) $mandate_bu=' ('.UniformLib::uniform($transactions[$first_trn_key]['Mandate']['BU'], 'mandate_bu').')';

          $html.="<p>For the purpose of the ".UniformLib::uniform($transactions[$first_trn_key]['Mandate']['mandate_name'], 'mandate_bu').$mandate_bu.", the European Investment Fund ('Depositor') requests the ".UniformLib::uniform($transactions[$first_trn_key]['Counterparty']['cpty_name'], 'cpty_name')." to execute the following instructions $x5 in line with the below specified terms:</p>
          <h2>1. DEPOSITS</h2>"; ?>

          <?php if(sizeof($transactions) > 0): ?>
          <?php $html .= "
          <table class='deposit' cellspacing='5'>
              <tr>
                <th><br>TRN<br>(1)</th>
                <th class='coldeposit'>Deposit<br>Type<br>(2)</th>
                <th>Auto<br>Renewal<br>(3)</th>
                <th><br>Cmp.<br>(4)</th>
                <th><br>Amount<br>(5)</th>
                <th>Comm.<br>Date<br>(6)</th>
                <th><br>Period<br>(7)</th>
                <th>Maturity<br>Date<br>(8)</th>
                <th>Interest<br>% p.a<br>(9)</th>
              </tr>
            <tbody>"; ?>
              <?php foreach ($transactions as $key => $transaction): ?>
                <?php if(strtolower($transaction['Transaction']['tr_type']) != 'repayment'): ?>
                  <?php $html .= "
                  <tr class='deposit_row'>
                    <td>".UniformLib::uniform($transaction['Transaction']['tr_number'], 'tr_number')."</td>
                    <td>".UniformLib::uniform($transaction['Transaction']['depo_type'], 'depo_type')." ".UniformLib::uniform($transaction['Transaction']['tr_type'], 'tr_type')."</td>
                    <td>&nbsp;&nbsp;".UniformLib::uniform($transaction['Transaction']['depo_renew'], 'depo_renew')."</td>
                    <td>".UniformLib::uniform($transaction['Compartment']['cmp_value'], 'cmp_value')."</td>                    
                    <td style='text-align:right'>".UniformLib::uniform($transaction['Transaction']['amount'], 'amount')." ".UniformLib::uniform($transaction['AccountA']['ccy'], 'ccy')."&nbsp; &nbsp; </td>
                    <td style='text-align:center; text-transform: uppercase;'>".UniformLib::uniform($transaction['Transaction']['commencement_date'], 'commencement_date')."</td>
                    <td style='text-align:center'>".UniformLib::uniform($transaction['Transaction']['depo_term'], 'depo_term')."</td>
                    <td style='text-align:center; text-transform: uppercase;'>".UniformLib::uniform($transaction['Transaction']['maturity_date'], 'maturity_date')."</td>
                    <td style='text-align:right'>".UniformLib::uniform($transaction['Transaction']['interest_rate'], 'interest_rate')." %</td>
                  </tr>
                  <tr>
                    <td class='deposit_accounts_row' colspan='9'>
                      <table class='deposit_accounts'>
                        <tr>
                          <td class='title'>Principal Account</td>
                          <td class='iban'>&nbsp;&nbsp;".UniformLib::uniform($transaction['AccountA']['IBAN'], 'accountA_IBAN')."</td>
                          <td class='bic'>".UniformLib::uniform($transaction['AccountA']['BIC'], 'accountA_BIC')."</td>
                        </tr>
                        <tr>
                          <td class='title'>Interest Account</td>
                          <td class='iban'>&nbsp;&nbsp;".UniformLib::uniform($transaction['AccountB']['IBAN'], 'accountB_IBAN')."</td>
                          <td class='bic'>".UniformLib::uniform($transaction['AccountB']['BIC'], 'accountB_BIC')."</td>
                        </tr>
                      </table>
                  </tr>"; ?>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php $html .= "
            </tbody>
          </table>" ?>
          <?php else: ?>
            <?php $html .= "<p class='well'><strong>There are no transactions to instruct.</strong></p>" ?>
          <?php endif; ?>
          <?php  if(!empty($deposits_footer)) $html.='<p class="deposits_footer">'.$deposits_footer.'</p>'; ?>
          <?php if(sizeof($di_repayments) > 0): ?>
          <?php $html .= "
          <div class='break_this'></div>
          <h2>2. REPAYMENTS</h2>
          <p>At Repayment Date please transfer to the Depositor account(s) below mentioned the following amount(s):</p>" ?>
          
            <?php $html .= "
          <table class='treasury repayments' cellspacing='5'>
              <thead>
                <tr>
                  <th>TRN<br>(1)</th>
                  <th>Cmp.<br>(4)</th>
                  <th>Account<br>(10)</th>
                  <th>Amount<br>(5)</th>
                  <th>Repayment Date<br>(11)</th>
                </tr>
              </thead>
              <tbody>" ?>
                <?php foreach ($di_repayments as $key => $transaction): ?>
                  <?php $html .= "
                  <tr>
                    <td>".UniformLib::uniform($transaction['Transaction']['tr_number'], 'tr_number')."</td>
                    <td>".UniformLib::uniform($transaction['Compartment']['cmp_value'], 'cmp_value')."</td>
                    <td>".UniformLib::uniform($transaction['Transaction']['accountA_IBAN'], 'accountA_IBAN')."<span class='spacer'></span>".UniformLib::uniform($transaction['AccountA']['BIC'], 'accountA_BIC')."</td>
                    <td style='text-align:right'>".UniformLib::uniform($transaction['Transaction']['amount'], 'amount')." ".UniformLib::uniform($transaction['AccountA']['ccy'], 'ccy')."</td>
                    <td style='text-align:center'>".UniformLib::uniform($transaction['Transaction']['commencement_date'], 'commencement_date')."</td>
                  </tr>" ?>
                <?php endforeach; ?>
              <?php $html .= "
              </tbody>
            </table>" ?>
          <?php endif; ?>
          <?php $html .= "
          <br><br>
          <table class='signature'>
            <tr><td>Signature: ..............</td><td>Signature: ..............</td></tr>
            <tr><td>European Investment Fund </td><td>".UniformLib::uniform($transactions[$first_trn_key]['Counterparty']['cpty_name'], 'cpty_name')."</td></tr>            
          </table>
          <br>
          <p class='footnote'>(1) EIF's transaction reference number <br> (2) Term - Fixed term deposit; Callable - Callable deposit; Deposit - New deposit; Rollover - Rollover <br> (3) Yes (P+I) - The deposit (principal+interest) at Maturity Date shall be automatically renewed for the specified Period<br>&nbsp;Yes (P) - Only principal of the deposit at Maturity Date shall be automatically renewed for the specified Period<br>(4) Code of the Operational Programme as set in EIF systems in line with the Funding Agreement<br>(6) Commencement date<br>(7) NS - non-standard period ending at specified Maturity Date; O/N - overnight;<br>&nbsp;1W - 1 week; 1M - 1 month; 2M - 2 months; 3M - 3 months; 6M - 6 months; 9M - 9 months; 1Y - 1 year</p>"; ?>

		  <?php if ($transaction['AccountA']['BIC'] == 'BGLLLULL')
			$html .= '<p style="font-size: 16px; background-color: yellow;">For new Deposit, please instruct the transfer via Multiline</p>';
		  ?>
          <?php echo $html; ?>
  </div></body>
  </html>
<!-- end di -->
