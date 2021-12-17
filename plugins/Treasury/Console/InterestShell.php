<?php

declare(strict_types=1);

namespace Treasury\Console;

App::uses('Shell', 'Console');
App::uses('UniformLib', 'Lib');
App::uses('CurrencyLib', 'Lib');



// /var/www/html/php/app/Console/cake Treasury.Interest


class InterestShell extends Shell
{

    public $uses = array('Treasury.Transaction', 'Treasury.Interest');
    public $tasks = array('ResetOwner');
    public function main()
    {

        $trns = $this->Transaction->find('all', array(
            'conditions' => array(
                'Transaction.depo_type'           => array('Callable'),
                'Transaction.maturity_date < '  => '2017-01-01',
                'Transaction.fixing_date < '  => '2017-01-01',
            )
        ));

        foreach ($trns as $key => $tr) {
            $interest_rate = $tr['Transaction']['interest_rate'];
            if ($tr['Transaction']['commencement_date'] != null) {
                echo "\ncomm date : " . $tr['Transaction']['commencement_date'];
                $commencement_date = date("Y-m-d", strtotime($tr['Transaction']['commencement_date']));
                echo "\n interest from : " . $commencement_date;
                $data = array('Interest' => array(
                    'trn_number' =>  $tr['Transaction']['tr_number'],
                    'interest_rate_from'    =>  $commencement_date,
                    'interest_rate' =>  $interest_rate
                ));
                $int = $this->Interest->create();
                $this->Interest->save($data);
            }
        }
    }

    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addOption('display', array('short' => 'd', 'help' => 'Display in browser mode', 'boolean' => TRUE));
        return $parser;
    }
}
