<?php

declare(strict_types=1);

namespace Treasury\Console;

App::uses('Shell', 'Console');
App::uses('UniformLib', 'Lib');
class LimitCalculationShell extends Shell
{

    public $uses = array('Treasury.Limit', 'Treasury.Transaction');
    public $tasks = array('ResetOwner');
    public function main()
    {
        if (!empty($this->params['display'])) print '<h3>LimitCalculationShell</h3>';
        if (!empty($this->params['mandate'])) print '<h4>FOR MANDATE ' . $this->params['mandate'] . '</h4>';
        $status = '---- BEGINNING OF SHELL ----';


        $status = '---- END OF SHELL ----';
        $this->out($status);
        if (!empty($this->params['display'])) print('<br>' . $status);
        $this->ResetOwner->execute();
        return $status;
    }

    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addOption('display', array('short' => 'd', 'help' => 'Display in browser mode', 'boolean' => TRUE));
        $parser->addOption('date', array('short' => 't', 'help' => 'Custom date instead of today'));
        $parser->addOption('mandate', array('short' => 'm', 'help' => 'Calculation only for this mandate'));
        return $parser;
    }
}
