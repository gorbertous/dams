<?php

declare(strict_types=1);

namespace Treasury\Console;

App::uses('Shell', 'Console');
App::uses('UniformLib', 'Lib');
App::uses('CurrencyLib', 'Lib');
class CallFixShell extends Shell
{

    public $uses = array('Treasury.Transaction');
    public $tasks = array('ResetOwner');
    public function main()
    {

        /* First, repayments */
        $calls = $this->Transaction->find('all', array(
            'conditions' => array(
                'tr_type'           => array('Call'),
                'tr_state'          => array('Confirmation Received', 'Confirmed'),
            )
        ));

        foreach ($calls as $trn_call) {
            $parent_id = $trn_call['Transaction']['parent_id'];
            $called_trn = $this->Transaction->read(null, $parent_id);

            $called_trn['Transaction']['total_interest'] = $trn_call['Transaction']['total_interest'];
            $trn_call['Transaction']['total_interest'] = null;

            $called_trn['Transaction']['tax_amount'] = $trn_call['Transaction']['tax_amount'];
            $trn_call['Transaction']['tax_amount'] = null;

            echo "\n called " . $called_trn['Transaction']['tr_number'] . "  A=" . $called_trn['Transaction']['amount'] . " I=" . $called_trn['Transaction']['total_interest'] . "  T=" . $called_trn['Transaction']['tax_amount'];
            echo "\n call " . $trn_call['Transaction']['tr_number'] . "  A=" . $trn_call['Transaction']['amount'] . " I=" . $trn_call['Transaction']['total_interest'] . "  T=" . $trn_call['Transaction']['tax_amount'];
            echo "****************\n***************\n";
            //$this->Transaction->save($called_trn);
            //$this->Transaction->save($trn_call);
        }

        $this->ResetOwner->execute();
        return true;
    }

    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addOption('display', array('short' => 'd', 'help' => 'Display in browser mode', 'boolean' => TRUE));
        return $parser;
    }
}
