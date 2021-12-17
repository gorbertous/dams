<?php

declare(strict_types=1);

namespace Treasury\Console;

App::uses('Shell', 'Console');
App::uses('UniformLib', 'Lib');
App::uses('CurrencyLib', 'Lib');


//  /var/www/html/php/app/Console/cake Treasury.Maturitybond



class MaturitybondShell extends Shell
{

    public $uses = array('Treasury.Bond', 'Treasury.Bondtransaction', 'Treasury.Transactionbondid');
    //public $tasks = array('ResetOwner');
    public function main()
    {

        $bonds = $this->Bond->find('all', array(
            'conditions' => array(
                'maturity_date <='  => date("Y-m-d"), // matures today or skipped during the week end
                'state'          => 'Confirmed',
            )
        ));

        if (count($bonds) > 0) {
            foreach ($bonds as $key => $tr) {
                $btr = $this->Bondtransaction->find("all", array('conditions' => array('BondTransaction.bond_id' => $tr['Bond']['bond_id'], 'BondTransaction.tr_state' => 'Confirmed', 'BondTransaction.tr_type' => 'Bond')));
                foreach ($btr as $key => $trn) {
                    $trn['Bondtransaction']['tr_state'] = 'Matured';
                    $this->Bondtransaction->save($trn);
                    echo print_r($trn, true);
                    $this->log("Bond TRN " . $trn['Bondtransaction']['tr_number'] . " has matured", 'treasury');
                }
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
