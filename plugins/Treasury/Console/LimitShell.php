<?php

declare(strict_types=1);

namespace Treasury\Console;

App::uses('Shell', 'Console');
App::uses('UniformLib', 'Lib');
App::uses('CurrencyLib', 'Lib');
App::uses('WkHtmlToPdf', 'Vendor');

class LimitShell extends Shell
{

    public $uses = array('Treasury.Limit', 'Treasury.Transaction', 'Treasury.MandateGroup');
    public $recipients = array('eifsas-support@eif.org', 'l.chavarri@eif.org', 'm.hickey@eif.org', 'd.berkhoff@eif.org', 'n.engelputzeder@eif.org', 'g.nicolay@eif.org');

    public function main()
    {
        date_default_timezone_set('Europe/Luxembourg');
        $date = date('Y-m-d');
        if (!empty($this->params['date'])) $date = $this->params['date'];

        $breaches = array();

        $mandategroups = $this->MandateGroup->find('list', array(
            'conditions' => array('mandategroup_name <>' => null, 'mandategroup_name <>' => ''),
            'fields' => array('id', 'mandategroup_name'),
        ));
        if (!empty($mandategroups)) foreach ($mandategroups as $mandategroup => $mandategroupname) {
            $limits = $this->Limit->getByCounterparties($date, $mandategroup);
            /* limits for counterparty groups test */
            if (!empty($limits['counterpartygroups'])) foreach ($limits['counterpartygroups'] as $cptygroup_ID => $cptygroup) {
                $transactions = array();
                if (!empty($cptygroup['limit']['status'])) {
                    foreach ($cptygroup['limit']['status'] as $status) {
                        foreach ($status as $type => $val) {
                            $transactions = $this->Transaction->getTransactionsByLimit($cptygroup['limit']['limit_ID'], $date, 'group');

                            $breach = array(
                                'breachtype' => $type,
                                'mandategroup_name' => $mandategroupname,
                                'mandategroup_ID' => $mandategroup,
                                'counterpartygroup' => $cptygroup['CounterpartyGroup'],
                                'details' => $val,
                                'limit' => $cptygroup['limit'],
                                'counterparty' => null,
                                'Transactions' => $transactions,
                            );
                            $breaches[] = $breach;
                        }
                    }
                }
            }

            /* limits for individuals counterparties */
            if (!empty($limits['counterparties'])) foreach ($limits['counterparties'] as $cpty_ID => $cpty) {
                $transactions = null;

                if (!empty($cpty['counterparty']['status'])) {
                    foreach ($cpty['counterparty']['status'] as $status) {
                        foreach ($status as $type => $val) {
                            $countepartygroup = '';
                            if (!empty($cpty['limits'][0]['counterpartygroup_ID'])) $countepartygroup = $cpty['limits'][0]['counterpartygroup_ID'];

                            if (!is_array($transactions)) {
                                $transactions = array();
                                foreach ($cpty['limits'] as $lim) {
                                    $transactions = $this->Transaction->getTransactionsByLimit($lim['limit_ID'], $date);
                                }
                            }

                            $breach = array(
                                'breachtype' => $type,
                                'mandategroup_name' => $mandategroupname,
                                'mandategroup_ID' => $mandategroup,
                                'counterpartygroup' => $countepartygroup,
                                'details' => $val,
                                'limit' => reset($cpty['limits']),
                                'counterparty' => $cpty['counterparty'],
                                'Transactions' => $transactions,
                            );
                            $breaches[] = $breach;
                        }
                    }
                }
            }
        }
        if (!empty($this->params['display'])) debug($breaches);
        $this->notify($date, $breaches);
    }

    public function notify($date = null, $breaches = null)
    {
        if (empty($date)) $date = date('Y-m-d');

        //breacher gathered, send by mail, or return false
        if (empty($breaches)) {
            $status = 'No breach found at ' . $date;
            $this->out($status);
            if (!empty($this->params['display'])) print('<br>' . $status);
            return false;
        } else {
            foreach ($breaches as &$breach) {
                $type = '';
                if (!empty($breach['breachtype'])) $type = ucfirst($breach['breachtype']) . ' ';
                $subject = '[Treasury] ' . $type . 'Limit breach Alert : ' . $breach['limit']['limit_name'];
                $prefix = $sufix = $server = '';

                //subject: prepend server
                $prefix = '[TEST] ';
                $recipients = array('eifsas-support@eif.org');
                if (EIFENV == 'dev' || $this->args[0] == 'dev') {
                    $prefix = '[VMD - TEST] ';
                } elseif (EIFENV == 'uat' || $this->args[0] == 'uat') {
                    $prefix = '[VMU - TEST] ';
                } elseif (EIFENV == 'prod' || $this->args[0] == 'prod') {
                    $prefix = '';
                    $recipients = $this->recipients;
                }

                $subject = $prefix . $subject;
                //$recipients = array('v.tissot@eif.org');

                App::uses('CakeEmail', 'Network/Email');
                $Email = new CakeEmail();
                $Email->template('Treasury.limit_breach_notification')
                    ->emailFormat('html')
                    ->from(array('eifsas-support@eif.org' => 'EIFSAS Platform'))
                    ->to($recipients)
                    ->subject($subject)
                    ->viewVars(array('breach' => $breach));
                try {
                    // @$Email->send();

                    //saving notification in pdf format
                    $pdf_path = "";
                    $pdf_path = $this->sendToPdf($subject, $breach, $recipients);

                    $this->logBreach($breach, $recipients, $pdf_path);
                } catch (Exception $e) {
                    print($e->getMessage());
                }
            }

            $status = count($breaches) . ' breach(es) found and sent by email for ' . $date;
            $this->out($status);
            if (!empty($this->params['display'])) print('<br>' . $status);
            return true;
        }
    }

    public function sendToPdf($subject, $breach, $recipients)
    {
        $path = false;
        $view_pdf = new View();
        $view_pdf->view = 'Treasury.Pdf/limit_breach_notification';
        $view_pdf->layout = 'pdf/html/layoutPdf';
        $view_pdf->set('breach', $breach);
        $view_pdf->set('recipients', $recipients);
        $html = $view_pdf->render('Treasury.Pdf/limit_breach_notification');
        file_put_contents("/var/www/html/data/treasury/pdf/pdf.html", $html);
        $pdf = new WkHtmlToPdf();
        $pdf->addPage($html);
        $pdfpath = "/var/www/html/data/treasury/pdf/LimitBreach" . date("Y-M-d") . "_" . $subject . "_" . time() . microtime() . rand() . ".pdf";
        $pdfpath = str_replace(' ', '_', $pdfpath);
        if (!$pdf->saveAs($pdfpath)) {
            //could not save pdf
        } else {
            // pdf saved
            $path = $pdfpath;
        }
        return $path;
    }

    public function logBreach($breach, $recipients, $pdf_path)
    {
        if (!empty($breach['counterpartygroup'])) {
            $concentration = $breach['counterpartygroup']['concentration'];
            $name = $breach['counterpartygroup']['counterpartygroup_name'];
            $exposure = $breach['counterpartygroup']['exposure'];
        } else {
            $concentration = $breach['counterparty']['concentration'];
            $name = $breach['counterparty']['cpty_name'];
            $exposure = $breach['counterparty']['exposure'];
        }

        if (!empty($breach['limit']['concentration_limit_unit']) && ($breach['limit']['concentration_limit_unit'] == 'PCT' ||
            $breach['limit']['concentration_limit_unit'] == 'ABS') && empty($breach['limit']['automatic']) && $concentration <= 1) {
            $concentration *= 100;
            $concentration = number_format($concentration, 2);
            $concentration .= '%';
        } else {
            if ($concentration <= 1) {
                $concentration *= 100;
                $concentration = number_format($concentration, 2);
                $concentration .= '%';
            } else $concentration = number_format($concentration, 2);
        }
        $log = array();
        $log['mandategroup_id'] = $breach['mandategroup_ID'];
        $log['counterpartygroup_id'] = $breach['limit']['counterpartygroup_ID'];
        $log['cpty_id'] = $breach['limit']['cpty_ID'];
        $log['limit_name'] = UniformLib::uniform($breach['limit']['limit_name'], 'limit_name');
        $log['portfolio_name'] = UniformLib::uniform($breach['mandategroup_name'], 'mandategroup_name');
        if (!empty($breach['counterpartygroup'])) {
            $log['counterparty_name'] = '';
            $log['riskgroup_name'] = UniformLib::uniform($name, 'counterpartygroup_name');
        } else {
            $log['counterparty_name'] = UniformLib::uniform($name, 'cpty_name');
            $log['riskgroup_name'] = '';
        }
        $log['rating_lt'] = UniformLib::uniform($breach['limit']['rating_lt'], 'rating_lt');
        $log['rating_st'] = UniformLib::uniform($breach['limit']['rating_st'], 'rating_st');
        $log['max_maturity'] = UniformLib::uniform($breach['limit']['max_maturity'], 'max_maturity');
        $log['limit_in_eur'] = UniformLib::uniform($breach['limit']['limit_eur'], 'limit_eur');
        $log['exposure_in_eur'] = UniformLib::uniform($exposure, 'exposure_eur');
        $log['portfolio_concentration'] = UniformLib::uniform($concentration, 'concentration');
        $log['limit_available_in_eur'] = UniformLib::uniform($breach['limit']['limit_available'], 'limit_available');
        $log['breach_type'] = UniformLib::uniform(ucfirst($breach['breachtype']), 'breach_type');

        if ($breach['breachtype'] == 'exposure') {
            $log['breach_details'] = ' (cparty exposure / limit): ';
        } elseif ($breach['breachtype'] == 'concentration') {
            $log['breach_details'] = ' (cparty concentration / max concentration): ';

            $val1 = preg_replace('/(.*) \/.*/', '$1', $breach['details']);
            $val2 = preg_replace('/.*\/(.*)/', '$1', $breach['details']);

            $val1 = preg_replace('/(.*) \/.*/', '$1', $breach['details']);
            $val2 = preg_replace('/.* \/(.*)/', '$1', $breach['details']);

            //check if the value contains , to compare if percentage
            if (str_replace(',', '', $val1) < 100) $val1 .= '%';
            if (str_replace(',', '', $val2) < 100) $val2 .= '%';
            $breach['details'] .= $val1 . ' / ' . $val2;
        }
        $log['breach_details'] = UniformLib::uniform(ucfirst($breach['details']), 'breach_details');
        $transactions_ids = array();
        if (!empty($breach['Transactions'])) {
            if (!empty($breach['Transactions']['EUR'])) {
                $transactions = $breach['Transactions']['EUR'];
                if (!empty($transactions)) foreach ($transactions as $transaction) {
                    $transactions_ids[] = UniformLib::uniform($transaction['Transaction']['tr_number'], 'tr_number');
                }
            }
        }
        if (!empty($breach['Transactions']['CURR'])) {
            $transactions = $breach['Transactions']['CURR'];
            if (!empty($transactions)) foreach ($transactions as $transaction) {
                $transactions_ids[] = UniformLib::uniform($transaction['Transaction']['tr_number'], 'tr_number');
            }
        }
        $log['transaction_ids'] = implode(',', $transactions_ids);
        $date = date("Y-m-d H:i:s");
        $log['date'] = $date;
        $log['recipients'] = implode(',', $recipients);
        $log['email_reference'] = $pdf_path;
        array_walk($log, array($this, 'prepare_query'));
        $insert = implode(',', $log);
        $req = "INSERT INTO limit_breaches (`mandategroup_id`,`counterpartygroup_id`,`cpty_id`,`limit`,`portfolio`,`counterparty`,`risk_group`,`rating_lt`,`rating_st`,`max_maturity`,`limit_in_eur`,`exposure_in_eur`,`portfolio_concentration`,`limit_available_in_eur`,`breach_type`,`breach_details`,`transaction_ids`,`date`,`recipients`,`email_reference`) VALUES (" . $insert . ")";
        try {
            $ins = $this->Limit->query($req);
            $nn = (isset($log['riskgroup_name'])) ? $log['riskgroup_name'] : $log['counterparty_name'];
            $inserted_breach_id = $this->get_sql_insert_id();
            foreach ($transactions_ids as $id_transaction) {
                $req_assoc = "INSERT INTO limit_breaches_transactions (`id_transaction`, `id_breach`, `date`, `mandategroup_id`) VALUES ('" . $id_transaction . "', '" . $inserted_breach_id . "', '" . $date . "', " . $breach['mandategroup_ID'] . ")";
                $ins = $this->Limit->query($req_assoc);
                //$req = $this->Limit->query("select * from limit_breaches where id=".$inserted_breach_id);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    public function get_sql_insert_id()
    {
        $db1 = $this->Limit->useDbConfig;
        $db2 = ConnectionManager::getDataSource($db1);
        $db = &$db2;
        return $db->lastInsertId();
    }

    public function prepare_query(&$item, $key)
    {
        $ids = array('mandategroup_id', 'counterpartygroup_id', 'cpty_id');
        if (!in_array($key, $ids)) {
            $numericals = array('limit_in_eur', 'exposure_in_eur', 'limit_available_in_eur');
            if (in_array($key, $numericals)) {
                $item = str_replace(',', '', $item);
            }
            $item = '"' . $item . '"';
        }
    }


    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addOption('display', array('short' => 'd', 'help' => 'Display in browser mode', 'boolean' => TRUE));
        $parser->addOption('date', array('short' => 't', 'help' => 'Custom date instead of today'));
        return $parser;
    }
}
