<!-- di -->
<html>
<head>
  <?php print $this->Html->css('Treasury.DepositInstruction', null, array('fullBase' => true)); ?>
</head>
<body>
  <?php
  $html = "<h1>Deposit Instruction -CALL- $instr_num</h1>
          <table class='header'>
            <tr>
              <td>From</td>
              <td>European Investment Fund</td>
              <td>To</td>
              <td>".UniformLib::uniform($transactions[array_shift(array_keys($transactions))]['Counterparty']['cpty_name'], 'cpty_name')."</td>
            </tr>
            <tr>
              <td></td>
              <td>EIF-Admin-Expenses@eif.org</td>
              <td></td>
              <td>".nl2br(UniformLib::uniform($transactions[array_shift(array_keys($transactions))]['Counterparty']['cpty_address'], 'cpty_address')."</td>
            </tr>
            <tr>
              <td></td>
              <td>tel. +352 24 85 81 491</td>
              <td></td>
              <td>".UniformLib::uniform($transactions[array_shift(array_keys($transactions))]['Counterparty']['cpty_zipcode'], 'cpty_zipcode')." ".UniformLib::uniform($transactions[array_shift(array_keys($transactions))]['Counterparty']['cpty_city'], 'cpty_city')."<br>".UniformLib::uniform($transactions[array_shift(array_keys($transactions))]['Counterparty']['cpty_country'], 'cpty_country')."</td>
            </tr>
            <tr>
              <td></td>
              <td>fax. +352 24 85 81 302</td>
              <td></td>
              <td>fax. ".UniformLib::uniform($transactions[array_shift(array_keys($transactions))]['Counterparty']['fax1'], 'counterparty_fax1')."</td>
            </tr>
          </table>
          <br>
          <p>Luxembourg, ".date('j M Y', time())."</p>
          <br>
          <p>For the purpose of the ".UniformLib::uniform($transactions[array_shift(array_keys($transactions))]['Mandate']['mandate_name'], 'mandate_name')." (".UniformLib::uniform($transactions[array_shift(array_keys($transactions))]['Mandate']['BU'], 'mandate_bu')."), the European Investment Fund ('Depositor') requests the ".UniformLib::uniform($transactions[array_shift(array_keys($transactions))]['Counterparty']['cpty_name'], 'cpty_name')." to execute the <strong>call</strong> of the following amount to the below mentioned accounts $x5 in line with the below specified terms:</p>
          <h2>1. DEPOSIT CALLS</h2>"; ?>

          <?php if(sizeof($transactions) > 0): ?>
          <?php $html .= "
          <table class='deposit'>
              <tr>
                <th><br>TRN<br>(1)</th>
                <th><br>Comp.<br>(2)</th>
                <th><br>IBAN<br></th>
                <th><br>BIC<br></th>
                <th>Called<br>Amount<br></th>
                <th>Repayment<br>Date<br></th>
                <th>Interest<br>% p.a<br></th>
                <th>Interest<br>value<br></th>
              </tr>
            <tbody>"; ?>
              <?php foreach ($transactions as $key => $transaction): ?>
                <?php 
                  /*$cdate = ''; 
                  if(!empty($transaction['Transaction']['commencement_date'])) $cdate = date('d/m/Y', strtotime($transaction['Transaction']['commencement_date']));*/
                ?>
                <?php $html .= "
                <tr>
                  <td>".UniformLib::uniform($transaction['Transaction']['di_tr_number'], 'di_tr_number')."</td>
                  <td>".UniformLib::uniform($transaction['Compartment']['cmp_value'], 'cmp_value')."</td>
                  <td>".UniformLib::uniform($transaction['AccountA']['IBAN'], 'accountA_IBAN')."</td>
                  <td>".UniformLib::uniform($transaction['AccountA']['BIC'], 'accountA_BIC')."</td>
                  <td style='text-align:right'>".UniformLib::uniform($transaction['Transaction']['amount'], 'amount')." ".UniformLib::uniform($transaction['AccountA']['ccy'], 'ccy')."</td>
                  <td style='text-align:center; text-transform: uppercase;'>".UniformLib::uniform($transaction['Transaction']['commencement_date'], 'commencement_date')."</td>
                  <td style='text-align:right'>".UniformLib::uniform($transaction['Transaction']['interest_rate'], 'interest_rate')." %</td>
                  <td style='text-align:right'>".UniformLib::uniform($transaction['Transaction']['total_interest'], 'total_interest')." ".UniformLib::uniform($transaction['AccountA']['ccy'], 'ccy')."</td>
                </tr>" ?>
              <?php endforeach; ?>
            <?php $html .= "
            </tbody>
          </table>" ?>
          <?php $html .= "<br><p class='title'>At requested repayment date, please transfer the <strong>Principal part</strong> of called amount to the below mentioned account:</p>
          "
          ?>
          <?php $html .= "
          <table class='treasury'>
            <thead>
              <tr>
                <th>TRN<br>(1)</th>
                <th>IBAN<br></th>
                <th>BIC<br></th>
                <th>Amount<br></th>
              </tr>
            </thead>
            <tbody>" ?>
              <?php foreach ($transactions as $key => $transaction): ?>
                <?php $html .= "
                  <tr>
                    <td>".UniformLib::uniform($transaction['Transaction']['di_tr_number'], 'di_tr_number')."</td>
                    <td>".UniformLib::uniform($transaction['Transaction']['accountA_IBAN'], 'accountA_IBAN')."</td>
                    <td>".UniformLib::uniform($transaction['AccountA']['BIC'], 'accountA_BIC')."</td>
                    <td style='text-align:right'>"." . ".UniformLib::uniform($transaction['AccountA']['ccy'], 'ccy')."</td>
                  </tr>" ?>
              <?php endforeach; ?>
              <?php $html .= "
            </tbody>
          </table>" ?>
              <?php $html .= "<br><p class='title'>At requested repayment date, please transfer the <strong>Interest part</strong> of called amount to the below mentioned account:</p>
          "
          ?>
          <?php $html .= "
          <table class='treasury'>
            <thead>
              <tr>
                <th>TRN<br>(1)</th>
                <th>IBAN<br></th>
                <th>BIC<br></th>
                <th>Amount<br></th>
              </tr>
            </thead>
            <tbody>" ?>
              <?php foreach ($transactions as $key => $transaction): ?>
                <?php $html .= "
                  <tr>
                    <td>".UniformLib::uniform($transaction['Transaction']['di_tr_number'], 'di_tr_number')."</td>
                    <td>".UniformLib::uniform($transaction['Transaction']['accountB_IBAN'], 'accountB_IBAN')."</td>
                    <td>".UniformLib::uniform($transaction['AccountB']['BIC'], 'accountB_IBAN')."</td>
                    <td style='text-align:right'>"." . ".UniformLib::uniform($transaction['AccountB']['ccy'], 'ccy')."</td>
                  </tr>" ?>
              <?php endforeach; ?>
          <?php $html .= "
            </tbody>
          </table>" ?>
          <?php endif; ?>
          <?php $html .= "
          <br><br>
          <table class='signature'>
            <tr><td>European Investment Fund </td><td>".UniformLib::uniform($transactions[array_shift(array_keys($transactions))]['Counterparty']['cpty_name'], 'cpty_name')."</td></tr>
            <tr><td>Signature: ..............</td><td>Signature: ..............</td></tr>
          </table>
          <br><br>
          <p class='footnote'>(1) EIF's call transaction reference number (EIF's reference number of the original called deposit DI: instruction number of the original transaction) <br> (2) Code of the Operational Programmes as set in EIF systems in line with the Funding Agreement.</p>"; ?>

          <?php echo $html; ?>
  </body>
  </html>
<!-- end di -->
