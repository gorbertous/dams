<!-- di -->
<html>
<head>
  <?php print $this->Html->css('Treasury.DepositInstruction', null, array('fullBase' => true)); ?>
</head>
<body class="v2tpl">
  <?php
  $arr_k = array_keys($transactions);
  $first_trn_key = array_shift($arr_k);
  $html = "<h1>Deposit Instruction -BREAK- $instr_num</h1>
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
              <td>".nl2br(UniformLib::uniform($transactions[$first_trn_key]['Counterparty']['cpty_address'],'cpty_address'))."</td>
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
            </tr>
          </table>
          <br>";
          
          $hdrdate = date('j F Y');
          if(!empty($headerdate)) $hdrdate=date('j F Y',strtotime(str_replace('/','-',$headerdate)));

          $html.="<p>Luxembourg, ".$hdrdate."</p>
          <br>
          <p>For the purpose of the ".UniformLib::uniform($transactions[$first_trn_key]['Mandate']['mandate_name'], 'mandate_name')." (".UniformLib::uniform($transactions[$first_trn_key]['Mandate']['BU'], 'mandate_bu')."), the European Investment Fund ('Depositor') requests the ".UniformLib::uniform($transactions[$first_trn_key]['Counterparty']['cpty_name'], 'cpty_name')." to execute the <strong>withdrawal</strong> from the Principal of the following amount to the below mentioned accounts $x5 in line with the below specified terms:</p>
          <h2>1. DEPOSIT WITHDRAWAL</h2>"; ?>

          <?php if(sizeof($transactions) > 0): ?>
          <?php 
            /*$cdate = ''; 
            if(!empty($transactions[0]['Transaction']['commencement_date'])) $cdate = date('d/m/Y', strtotime($transactions[0]['Transaction']['commencement_date']));*/
          ?>
          <?php $html .= "
          <table class='deposit'>
              <tr>
                <th><br>TRN<br>(1)</th>
                <th><br>Comp.<br>(2)</th>
                <th>Withdrawal<br>Amount<br></th>
                <th>Repay.<br>Date<br></th>
              </tr>
            <tbody>"; ?>
                <?php $html .= "
                <tr>
                  <td>".UniformLib::uniform($transactions[0]['Transaction']['di_tr_number'], 'di_tr_number')."</td>
                  <td>".UniformLib::uniform($transactions[0]['Compartment']['cmp_value'], 'cmp_value')."</td>
                  <td style='text-align:right'>".UniformLib::uniform($transactions[0]['Transaction']['amount'], 'amount')." ".UniformLib::uniform($transactions[0]['AccountA']['ccy'], 'ccy')."</td>
                  <td style='text-align:center; text-transform: uppercase;'>".UniformLib::uniform($transactions[0]['Transaction']['commencement_date'], 'commencement_date')."</td>
                </tr>
                <tr>
                  <td class='deposit_accounts_row' colspan='4'>
                    <table class='deposit_accounts'>
                      <tr>
                        <td class='title'>Principal Account</td>
                        <td class='iban'>".UniformLib::uniform($transactions[0]['AccountA']['IBAN'], 'accountA_IBAN')."</td>
                        <td class='bic'>".UniformLib::uniform($transactions[0]['AccountA']['BIC'], 'accountA_BIC')."</td>
                      </tr>
                      <tr>
                        <td class='title'>Interest Account</td>
                        <td class='iban'>".UniformLib::uniform($transactions[0]['AccountB']['IBAN'], 'accountB_IBAN')."</td>
                        <td class='bic'>".UniformLib::uniform($transactions[0]['AccountB']['BIC'], 'accountB_BIC')."</td>
                      </tr>
                    </table>
                </tr>" ?>
            <?php $html .= "
            </tbody>
          </table>";
          /*$html.="<br>
          <p>Related interest to this withdrawal repayment should be paid into account: <strong>".$transactions[0]['Transaction']['accountB_IBAN']."</strong></p>
          <br>
          <p class='title'>At Maturity date of the original transaction, please transfer the <strong>Remaining Principal</strong> to the below mentioned account:</p>" ?>
          <?php if(sizeof($sisters) > 0): ?>
          <?php $html .= "
          <table class='treasury'>
            <thead>
              <tr>
                <th>TRN<br>(1)</th>
                <th>Comp.<br>(2)</th>
                <th>IBAN<br></th>
                <th>BIC<br></th>
                <th>Amount<br></th>
              </tr>
            </thead>
            <tbody>" ?>
              <?php $html .= "
                <tr>
                  <td>".$sisters[array_shift(array_keys($sisters))]['Transaction']['di_tr_number']."</td>
                  <td>".$sisters[array_shift(array_keys($sisters))]['Compartment']['cmp_value']."</td>
                  <td>".chunk_split($sisters[array_shift(array_keys($sisters))]['Transaction']['accountA_IBAN'], 4, ' ')."</td>
                  <td>".$sisters[array_shift(array_keys($sisters))]['AccountA']['BIC']."</td>
                  <td style='text-align:right'>".number_format($sisters[array_shift(array_keys($sisters))]['Transaction']['amount'], 2)." ".$sisters[array_shift(array_keys($sisters))]['AccountA']['ccy']."</td>
                </tr>" ?>
            <?php $html .= "
            </tbody>
          </table>" ?>
          <?php endif; ?>
          <?php $html .= "
          <br>
          <p class='title'>At Maturity date of the original transaction, please transfer the <strong> Remaining Interest</strong> to the below mentioned account:</p>" ?>
          <?php if(sizeof($sisters) > 0): ?>
          <?php $html .= "
          <table class='treasury'>
              <thead>
                <tr>
                  <th>TRN<br>(1)</th>
                  <th>Comp.<br>(2)</th>
                  <th>IBAN<br></th>
                  <th>BIC<br></th>
                  <th>Amount<br></th>
                </tr>
              </thead>
              <tbody>" ?>
                  <?php $html .= "
                  <tr>
                    <td>".$sisters[array_shift(array_keys($sisters))]['Transaction']['di_tr_number']."</td>
                    <td>".$sisters[array_shift(array_keys($sisters))]['Compartment']['cmp_value']."</td>
                    <td>".chunk_split($sisters[array_shift(array_keys($sisters))]['Transaction']['accountB_IBAN'], 4, ' ')."</td>
                    <td>".$sisters[array_shift(array_keys($sisters))]['AccountB']['BIC']."</td>
                    <td style='text-align:right'>. ".$sisters[array_shift(array_keys($sisters))]['AccountB']['ccy']."</td>
                  </tr>" ?>
              <?php $html .= "
              </tbody>
            </table>" ?>
          <?php endif;*/ ?>
          <?php endif; ?>
          <?php $html .= "
          <br><br>
          <table class='signature'>
            <tr><td>European Investment Fund </td><td>".UniformLib::uniform($transactions[$first_trn_key]['Counterparty']['cpty_name'], 'cpty_name')."</td></tr>
            <tr><td>Signature: ..............</td><td>Signature: ..............</td></tr>
          </table>
          <br><br>
          <p class='footnote'>(1) EIF's withdrawal transaction reference number (EIF's reference number of the original broken deposit DI: instruction number of the original transaction) <br> (2) Code of the Operational Programmes as set in EIF systems in line with the Funding Agreement.</p>"; ?>

          <?php echo $html; ?>
  </body>
  </html>
<!-- end di -->