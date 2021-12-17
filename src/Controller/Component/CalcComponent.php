<?php

class CalcComponent extends Component {

    /**
     * Convert a field from a transaction
     * @param $trn_id - Transaction ID
     * @param $field - Field to convert (useless !!TO BE DELETED!!)
     * @param $amount - amount to convert
     * @param $fx_type - FX rate to apply
     * @return array - EUR ; CURR values
     */
    public function trn_eur_curr($trn_id, $field=null, $amount, $fx_type) {
        $Trn = ClassRegistry::init('Damsv2.Transaction');
        $actual_trn = $Trn->findByTransactionId($trn_id);

        $from             = $actual_trn['Transaction']['currency'];
        $to               = $actual_trn['Portfolio']['currency'];
        $portfolio_id     = $actual_trn['Portfolio']['portfolio_id'];
        $date             = $actual_trn['Report']['period_end_date'];

        if($from=='EUR' && $to=='EUR'){
              // case 1 trn in eur and portfolio in eur
              $eur  = $curr = $amount;
        }elseif($from=='EUR' && $to<>'EUR'){
              // case 2 trn in eur and portfolio in another currency
              $eur  = $amount;
              $curr = $this->convert_ccy($from, $to, $portfolio_id, $fx_type, $amount, $date);
        }else{
              $eur  = $this->convert_ccy($from, 'EUR', $portfolio_id, $fx_type, $amount, $date);
              $curr = $this->convert_ccy($from, $to, $portfolio_id, $fx_type, $amount, $date);
        }

        return array('eur'=>$eur,'curr'=>$curr);
  }

    /**
     * Convert amount from one currency to another with fx rate depending of portfolio type
     * @param $from - From currency
     * @param $to - To currency
     * @param $portfolio_id - Portfolio ID from Damsv2.portfolio table
     * @param $fx_type - FIXED or REGULAR
     * @param $amount - Amount to convert
     * @param $date - Date of the fx rate
     * @return float - amount converted
     */
  public function convert_ccy($from, $to, $portfolio_id, $fx_type, $amount, $date) {
        $converted_amount = $amount;
        $rates['EUR'] = '1';
        switch ($fx_type) {
              case 'FIXED':
                    $Rate = ClassRegistry::init('Damsv2.FixedRate');
                    $ccy_rates = $Rate->find('all', array(
                          'conditions'=>array('portfolio_id'=>$portfolio_id, 'currency'=>array($from,$to))
                    ));

                    break;
              case 'REGULAR':
              default:
                    $Rate = ClassRegistry::init('Damsv2.Rate');
                    $rate = $Rate->find('first', array(
                          'conditions'=>array(
                                'CURRENCY'        => $from,
                                'TIME_PERIOD <'   => $date
                          ),
                          'order' => array('TIME_PERIOD DESC')
                    ));
                    $ccy_rates[] = $rate;
                    $rate = $Rate->find('first', array(
                          'conditions'=>array(
                                'CURRENCY'        => $to,
                                'TIME_PERIOD <'   => $date
                          ),
                          'order' => array('TIME_PERIOD DESC')
                    ));
                    $ccy_rates[] = $rate;

                    break;
        }

        foreach ($ccy_rates as $value) {
              if(array_key_exists('Rate', $value))
                    $rates[$value['Rate']['CURRENCY']]     = $value['Rate']['OBS_VALUE'];
              if(array_key_exists('FixedRate', $value))
                    $rates[$value['FixedRate']['currency']] = $value['FixedRate']['obs_value'];
        }
        if(!empty($rates[$from]) && !empty($rates[$to])){
              $conversion_rate  = $rates[$from] / $rates[$to];
              $converted_amount = round ($amount / $conversion_rate, 2);
        }
        return $converted_amount;
  }

}
