<?php

declare(strict_types=1);

namespace Treasury\Controller;

use Cake\Event\EventInterface;

/**
 * Taxes Controller
 *
 * @property \Treasury\Model\Table\TaxesTable $Taxes
 * @method \Treasury\Model\Entity\Tax[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StaticdataController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        //$this->loadComponent('Security');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        //$this->Security->setConfig('blackHoleCallback', 'blackhole');
    }
    /* 
	 * ACCOUNTS Management
	 */
    public function accounts()
    {
        $this->Paginator->settings = $this->paginate;
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('Account.modified' => 'DESC')
        );
        $this->set('rows', $this->Paginator->paginate('Account'));
    }

    public function account($id = null)
    {
        @$this->validate_param('string', $id);
        @$this->validate_param('string', $this->request->data['action_from']);
        $redirect = (!empty($this->request->data['action_from'])) ? $this->request->data['action_from'] : 'accounts';

        if (!empty($this->request->data)) {
            if ($this->Account->save($this->request->data)) {
                $this->Session->setFlash('Account ' . $this->request->data['Account']['IBAN'] . ' has been saved', 'flash/success');
                $this->log_entry("Account saved : " . json_encode($this->request->data['Account'], true), 'treasury');
                $this->redirect($redirect);
            } else {
                $this->Session->setFlash('Something went wrong during writing. Please re-check your values.', 'flash/error');
            }
        }

        //bics list for select
        $bics = $this->Bank->find('list', array('recursive' => -1, 'fields' => array('BIC')));
        $this->set(compact('bics'));

        //ccy list for select
        $ccies = array('BGN' => 'BGN', 'CZK' => 'CZK', 'DKK' => 'DKK', 'EUR' => 'EUR', 'GBP' => 'GBP', 'HRK' => 'HRK', 'HUF' => 'HUF', 'NOK' => 'NOK', 'PLN' => 'PLN', 'RON' => 'RON', 'SEK' => 'SEK', 'TRY' => 'TRY', 'USD' => 'USD', 'ZAR' => 'ZAR');
        $this->set(compact('ccies'));

        //set account
        $this->set('row', $this->Account->getAccountById($id));
    }

    /* 
	 * BANKS Management
	 */
    public function banks()
    {
        $this->Paginator->settings = $this->paginate;
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('Bank.bank_name' => 'ASC')
        );
        $this->set('rows', $this->Paginator->paginate('Bank'));
    }
    public function bank($id = null)
    {
        @$this->validate_param('string', $id);
        @$this->validate_param('string', $this->request->data['action_from']);
        $redirect = (!empty($this->request->data['action_from'])) ? $this->request->data['action_from'] : 'banks';

        if (!empty($this->request->data)) {
            if ($this->Bank->save($this->request->data)) {
                $this->log_entry("Bank saved : " . json_encode($this->request->data['Bank'], true), 'treasury');
                $this->Session->setFlash('Bank ' . $this->request->data['Bank']['bank_name'] . ' has been saved', 'flash/success');
                $this->redirect($redirect);
            } else {
                $this->Session->setFlash('Something went wrong during writing. Please re-check your values.', 'flash/error');
            }
        }

        $this->set('row', $this->Bank->getBankById($id));
    }

    /* 
	 * COMPARTMENTS Management
	 */
    public function compartments()
    {
        $this->Paginator->settings = $this->paginate;
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('Compartment.cmp_ID' => 'ASC'),
            'conditions' => array('Compartment.cmp_id <>' => '')
        );
        $this->set('rows', $this->Paginator->paginate('Compartment'));
    }
    public function compartment($id = null)
    {
        @$this->validate_param('int', $id);
        @$this->validate_param('string', $this->request->data['action_from']);
        $redirect = !empty($this->request->data['action_from']) ? $this->request->data['action_from'] : 'compartments';

        if (!empty($this->request->data)) {
            if ($this->Compartment->save($this->request->data)) {
                $this->log_entry("Compartment saved : " . json_encode($this->request->data['Compartment'], true), 'treasury');
                $this->Session->setFlash('Compartment ' . $this->request->data['Compartment']['cmp_name'] . ' has been saved', 'flash/success');
                $this->redirect($redirect);
            } else {
                $this->Session->setFlash('Something went wrong during writing. Please re-check your values.', 'flash/error');
            }
        }

        /*
		 * Accounts list for select
		 */
        $accs = $this->Account->getAccounts();
        $accounts = array();
        foreach ($accs as $acc) {
            $account = UniformLib::uniform($acc['Account']['IBAN'], 'account_IBAN') . ' - ' . UniformLib::uniform($acc['Account']['BIC'], 'account_BIC');
            $details = '';
            if (!empty($acc['Account']['ccy'])) $details .= $acc['Account']['ccy'];
            if (!empty($acc['Account']['PS_account']) && is_numeric($acc['Account']['PS_account'])) {
                if ($details) $details .= ' - ';
                $details .= 'PS' . UniformLib::uniform($acc['Account']['PS_account'], 'PS_account');
            }
            if ($details) $account .= ' (' . $details . ')';
            $accounts[$acc['Account']['IBAN']] = $account;
        }
        $this->set(compact('accounts'));

        /*
		 * Mandates list for select
		 */
        $this->set('mandates_list', $this->Mandate->getMandateList());

        /*
		 * Set Compartment
		 */
        $this->set('row', $this->Compartment->getCompartementById($id));
    }

    // COUNTERPARTIES
    public function counterparties()
    {
        $this->Paginator->settings = $this->paginate;
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('Counterparty.cpty_name' => 'ASC'),
            'conditions' => array('Counterparty.cpty_name <>' => '')
        );
        $this->set('rows', $this->Paginator->paginate('Counterparty'));
    }
    public function counterparty($id = null)
    {
        @$this->validate_param('int', $id);
        @$this->validate_param('string', $this->request->data['action_from']);
        $redirect = (!empty($this->request->data['action_from'])) ? $redirect = $this->request->data['action_from'] : 'counterparties';

        //get current user group
        $user_groups = array();
        if ($groups = $this->Session->read('UserAuth.UserGroups')) {
            foreach ($groups as $group) {
                $user_groups[$group['alias_name']] = $group['name'];
            }
        }

        $this->set('user_groups', $user_groups);

        $frequencies = array(
            'Monthly' => 'Monthly',
            'Quarterly' => 'Quarterly',
            'Semi-annually' => 'Semi-annually',
            'Annually' => 'Annually'
        );
        $this->set('frequencies', $frequencies);

        if (!empty($this->request->data)) {
            /*
			 * capitalisation frequency is mandatory if autmatic_fixing is 'on'
			 */
            if (isset($this->request->data["Counterparty"]["automatic_fixing"]) && ($this->request->data["Counterparty"]["automatic_fixing"] == 1) && ($this->request->data["Counterparty"]["capitalisation_frequency"] == "")) {

                $this->Session->setFlash('If the counterparty is in Automatic fixing the Capitalisation frequency is mandatory.', 'flash/error');
            } else {
                /*
				 * Check if the pirat number already exists
				 */
                if ($this->request->data['Counterparty']['pirat_number'] == '') {
                    // update limits to manual for this counterparty
                    $this->Limit->updateAll(array('Limit.automatic' => 0), array('Limit.cpty_ID' => $id));
                }
                $counterparty = $this->Counterparty->getCounterpartyByPiratNumber($this->request->data['Counterparty']['pirat_number'], $id);
                if ($counterparty) {
                    $this->Session->setFlash('The PiRat number "' . $this->request->data['Counterparty']['pirat_number'] . '" has already been created for the counterparty "' . $counterparty['Counterparty']['cpty_name'] . '". Please re-enter another PiRat number.', 'flash/error');
                } else {
                    if (isset($this->request->data["Counterparty"]["automatic_fixing"]) && ($this->request->data['Counterparty']['automatic_fixing'] != '1')) {
                        $this->request->data['Counterparty']['capitalisation_frequency'] = null;
                    }
                    if ($counterparty = $this->Counterparty->save($this->request->data)) {
                        $this->log_entry("Counterparty saved : " . json_encode($this->request->data['Counterparty'], true), 'treasury');

                        //remove calculated limits
                        $CalculatedLimit = ClassRegistry::init('Treasury.CalculatedLimit');
                        $CalculatedLimit->deleteAll(array('CalculatedLimit.cpty_ID' => $id));
                        $event = new CakeEvent('Model.Treasury.Counterparty.updated', $this, array('counterparty' => $counterparty));
                        $this->Rating->getEventManager()->dispatch($event);

                        if ($id == null) // if new counterparty
                        {
                            $data = array(
                                'cpty_ID' => $counterparty['Counterparty']['cpty_ID'],
                                'cpty_name' => $counterparty['Counterparty']['cpty_name'],
                            );
                            $emailto = array('i.ribassin@ext.eif.org');
                            $prefix = '[DEV]';
                            App::uses('CakeEmail', 'Network/Email');
                            $Email = new CakeEmail();
                            $Email->template('Treasury.new_counterparty')
                                ->emailFormat('html')
                                ->from(array('eifsas-support@eif.org' => 'EIFSAS Platform'))
                                ->to($emailto)
                                ->subject($prefix . 'New counterparty added in SAS Treasury')
                                ->viewVars(array('counterparty' => $data));
                            try {
                                @$Email->send();
                            } catch (Exception $e) {
                                error_log("could not send email for new counterparty: " . $e->getMessage());
                            }
                        }

                        $this->Session->setFlash('Counterparty ' . $this->request->data['Counterparty']['cpty_name'] . ' has been saved', 'flash/success');
                        $this->redirect($redirect);
                    } else {
                        $this->Session->setFlash('Something went wrong during writing. Please re-check your values.', 'flash/error');
                    }
                }
            }
        }
        //$this->set('row', $this->Counterparty->getCounterpartyById($id));
        $cpts = $this->Counterparty->find('first', array(
            'recursive' => 1,
            'conditions' => array(
                'Counterparty.cpty_ID' => $id
            ),
            //'fields'	=>	array('cpty_ID', 'cpty_name', 'cpty_code', 'cpty_address', 'cpty_city', 'cpty_country', 'cpty_zipcode', 'automatic_fixing', 'capitalisation_frequency', 'cpty_bic', 'cpty_mt202_message', 'pirat_number', 'eu_central_bank', 'contact_person1', 'contact_person2', 'tel1', 'tel2', 'fax1', 'fax2', 'email1', 'email2', 'created', 'modified')
        ));
        $this->set('row', $cpts);

        $currencies = array(
            'BGN' => 'BGN',
            'CZK' => 'CZK',
            'DKK' => 'DKK',
            'EUR' => 'EUR',
            'GBP' => 'GBP',
            'HRK' => 'HRK',
            'HUF' => 'HUF',
            'NOK' => 'NOK',
            'PLN' => 'PLN',
            'USD' => 'USD',
            'RON' => 'RON',
            'SEK' => 'SEK',
            'TRY' => 'TRY'
        );
        $this->set('currencies', $currencies);
    }

    // COUNTERPARTIE GROUPS
    public function counterpartygroups()
    {
        $this->Paginator->settings = $this->paginate;
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('CounterpartyGroup.counterpartygroup_name' => 'ASC'),
        );
        $this->set('rows', $this->Paginator->paginate('CounterpartyGroup'));
    }
    public function counterpartygroup($id = null)
    {
        @$this->validate_param('int', $id);
        @$this->validate_param('string', $this->request->data['CounterpartyGroup']['action_from']);
        $redirect = (!empty($this->request->data['action_from'])) ? $redirect = $this->request->data['CounterpartyGroup']['action_from'] : (!empty($id)) ? $redirect = 'counterpartygroup/' . $id : '';

        if (!empty($this->request->data)) {
            if ($counterpartygroup = $this->CounterpartyGroup->getCounterpartyGroupById($this->request->data['CounterpartyGroup']['counterpartygroup_ID'])) {
                //remove counterparties
                if (!empty($this->request->data['CounterpartyGroup']['counterparty_remove'])) {
                    foreach ($this->request->data['CounterpartyGroup']['counterparty_remove'] as $key => $rem) {
                        if (!empty($rem)) foreach ($counterpartygroup['Counterparties'] as $countkey => $counterparty) {
                            if ($counterparty['cpty_ID'] == $key) {
                                unset($counterpartygroup['Counterparties'][$countkey]);
                            }
                        }
                    }
                }
            } else {
                $counterpartygroup = array('CounterpartyGroup' => array(), 'Counterparties' => array());
            }

            //add counterparty
            $duplicate = false;
            if (!empty($this->request->data['CounterpartyGroup']['add_counterparty'])) {
                foreach ($counterpartygroup['Counterparties'] as $counterparty) {
                    if ($counterparty['cpty_ID'] == $this->request->data['CounterpartyGroup']['add_counterparty']) {
                        $duplicate = true;
                        break;
                    }
                }
                if (!$duplicate) {
                    $counterpartygroup['Counterparties'][] = array('cpty_ID' => $this->request->data['CounterpartyGroup']['add_counterparty']);
                }
            }

            //name
            $counterpartygroup['CounterpartyGroup']['counterpartygroup_name'] = $this->request->data['CounterpartyGroup']['counterpartygroup_name'];

            //name
            $counterpartygroup['CounterpartyGroup']['head'] = $this->request->data['CounterpartyGroup']['head'];


            //hack: force remove of previous subscriptions, as it will add all the new ones...
            if ($id) $this->CounterpartyGroup->query('DELETE FROM counterparty_group_subscriptions WHERE cpty_ID=' . intval($id));

            if ($counterpartygroup = $this->CounterpartyGroup->save($counterpartygroup)) {
                if (empty($redirect)) {
                    if (!empty($counterpartygroup['CounterpartyGroup']['counterpartygroup_ID'])) {
                        $redirect = 'counterpartygroup/' . $counterpartygroup['CounterpartyGroup']['counterpartygroup_ID'];
                    }
                }
                $this->log_entry("Risk group saved : " . json_encode($counterpartygroup, true), 'treasury');
                $this->Session->setFlash('Risk group ' . $counterpartygroup['CounterpartyGroup']['counterpartygroup_name'] . ' has been saved', 'flash/success');
                $this->redirect($redirect);
            } else {
                $this->Session->setFlash('Something went wrong during writing. Please re-check your values.', 'flash/error');
            }
        }

        /*
         * Counterparty list for select
         */
        $this->set('counterparties', $this->Counterparty->getCounterpartyList());



        $this->set('row', $this->CounterpartyGroup->getCounterpartyGroupById($id));
    }

    //CUSTOM TEXTS
    public function custom_texts($id = null)
    {

        $this->set('counterparties', $this->Counterparty->getCounterpartyList());
        $conditions = !empty($this->request->data['cpty']['cpty_id']) ? array('Counterparty.cpty_ID' => $this->request->data['cpty']['cpty_id']) : array();

        $this->Paginator->settings = $this->paginate;
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('Counterparty.dropdown_txt' => 'ASC'),
            'conditions' => $conditions
        );
        $this->set('rows', $this->Paginator->paginate('CustomText'));
    }

    public function custom_text($id = null)
    {
        @$this->validate_param('int', $id);
        @$this->validate_param('string', $this->request->data['CustomText']['action_from']);
        $redirect = !empty($this->request->data['CustomText']['action_from']) ? $redirect = $this->request->data['CustomText']['action_from'] : 'custom_texts';

        if (!empty($this->request->data)) {
            if ($this->CustomText->save($this->request->data)) {
                $this->log_entry("DI Settlement (Custom Text) saved : " . json_encode($this->request->data['CustomText'], true), 'treasury');
                $this->Session->setFlash('Custom Text #' . $this->request->data['CustomText']['custom_id'] . ' has been saved', 'flash/success');
                error_log("redirection = " . $redirect);
                $this->redirect($redirect);
            } else {
                $this->Session->setFlash('Something went wrong during writing. Please re-check your values.', 'flash/error');
            }
        }

        //counterparties list for select 
        $this->set('counterparties', $this->Counterparty->getCounterpartyList());
        $this->set('row', $this->CustomText->getCustomTextById($id));
    }

    // RATINGS
    public function ratings()
    {
        $this->Paginator->settings = $this->paginate;
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('Rating.pirat_number' => 'ASC'),
            'group' => 'Rating.id',
        );
        $this->set('rows', $this->Paginator->paginate('Rating'));
    }
    public function rating($id = null)
    {

        @$this->validate_param('int', $id);
        @$this->validate_param('string', $this->request->data['Rating']['action_from']);
        $redirect = (!empty($this->request->data['Rating']['action_from'])) ? $redirect = $this->request->data['Rating']['action_from'] : (!empty($id)) ? 'rating/' . $id : 'ratings';

        if (!empty($this->request->data)) {
            if (!empty($this->request->data['Rating']['pirat_number'])) {
                $rate = $this->Rating->getRatingsByPiratNumber($this->request->data['Rating']['pirat_number']);
            }

            if (!empty($rate) && empty($id)) {
                $this->Session->setFlash('Rating ' . $this->request->data['Rating']['pirat_number'] . ' has already been created', 'flash/error');
            } else {

                $own_fund = $this->request->data['Rating']['own_funds'];
                $own_fund = str_replace(',', '', $own_fund);
                $this->request->data['Rating']['own_funds'] = $own_fund;
                if ($rating = $this->Rating->save($this->request->data)) {
                    $event = new CakeEvent('Model.Treasury.Rating.updated', $this, array('rating' => $rating));
                    $this->Rating->getEventManager()->dispatch($event);

                    $this->log_entry("Rating saved : " . json_encode($this->request->data['Rating'], true), 'treasury');
                    $this->Session->setFlash('Rating ' . $this->request->data['Rating']['id'] . ' has been saved and all the Calculated limits have been recalculated', 'flash/success');
                    $this->redirect($redirect);
                } else {
                    $this->Session->setFlash('Something went wrong during writing. Please re-check your values.', 'flash/error');
                }
            }
        }

        //list for select
        $counterparties = $this->Counterparty->find('list', array('recursive' => -1, 'fields' => array('pirat_number'), 'group' => array('pirat_number')));
        $counterpartygroups = $this->CounterpartyGroup->find('list', array('recursive' => -1, 'fields' => array('counterpartygroup_name'), 'order' => array('counterpartygroup_name')));
        $ratings = array(
            'LT-MDY' => array(),
            'LT-FIT' => array(),
            'LT-STP' => array(),
            'LT-EIB' => array(),
            'ST-MDY' => array(),
            'ST-FIT' => array(),
            'ST-STP' => array(),
            'ST-EIB' => array(),
        );

        foreach ($this->Rating->long_term as $key => $value) {
            $ratings['LT-MDY'][$value['LT-MDY']] = $value['LT-MDY'];
            $ratings['LT-FIT'][$value['LT-FIT']] = $value['LT-FIT'];
            $ratings['LT-STP'][$value['LT-STP']] = $value['LT-STP'];
            //$ratings['LT-EIB'][$value['R']] = $value['R'];
            $ratings['LT-EIB'][$value['LT-MDY']] = $value['LT-MDY'];
        }
        foreach ($this->Rating->short_term as $key => $value) {
            $ratings['ST-MDY'][$value['ST-MDY']] = $value['ST-MDY'];
            $ratings['ST-FIT'][$value['ST-FIT']] = $value['ST-FIT'];
            $ratings['ST-STP'][$value['ST-STP']] = $value['ST-STP'];
            //$ratings['ST-EIB'][$value['R']] = $value['R'];
            $ratings['ST-EIB'][$value['ST-MDY']] = $value['ST-MDY'];
        }
        $this->set(compact('counterparties', 'counterpartygroups', 'ratings'));

        //set rating
        $this->set('row', $this->Rating->getRatingsById($id));
    }

    // MANDATES
    public function mandates()
    {
        $this->Paginator->settings = $this->paginate;
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('mandate_name' => 'ASC'),
            'conditions' => array('mandate_name <>' => ''),
        );
        $this->set('rows', $this->Paginator->paginate('Mandate'));
    }
    public function mandate($id = null)
    {
        $row = null;
        @$this->validate_param('int', $id);
        @$this->validate_param('string', $this->request->data['Mandate']['action_from']);
        $redirect = (!empty($this->request->data['Mandate']['action_from'])) ? $this->request->data['Mandate']['action_from'] : 'mandates';

        //get current user group
        $user_groups = array();
        if ($groups = $this->Session->read('UserAuth.UserGroups')) {
            foreach ($groups as $group) {
                $user_groups[$group['alias_name']] = $group['name'];
            }
        }
        $this->set('user_groups', $user_groups);

        //update/create
        if (!empty($this->request->data)) {
            if ($smandate = $this->Mandate->save($this->request->data)) {
                $event = new CakeEvent('Model.Treasury.Mandate.updated', $this, array('mandate' => $smandate));
                $this->Mandate->getEventManager()->dispatch($event);

                $this->log_entry("Mandate saved : " . json_encode($this->request->data['Mandate'], true), 'treasury');
                $this->Session->setFlash('Mandate ' . $this->request->data['Mandate']['mandate_name'] . ' has been saved', 'flash/success');
                $this->redirect($redirect);
            } else {
                $this->Session->setFlash('Something went wrong during writing. Please re-check your values.', 'flash/error');
            }
        }

        //counterparties list
        $this->set('counterparties', $this->Counterparty->getCounterpartyList());

        //set mandate
        $this->set('row', $this->Mandate->getMandateById($id));
    }

    // TAXES
    public function taxes()
    {
        $this->Paginator->settings = $this->paginate;
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('Mandate.mandate_name' => 'ASC', 'Counterparty.cpty_name' => 'ASC'),
            //'conditions' => array('Counterparty.cpty_name <>'=>'')
        );
        $this->set('rows', $this->Paginator->paginate('Tax'));
    }
    public function tax($id = null)
    {
        /*
		 * the submitted data
		 */
        @$this->validate_param('int', $id);
        if (!empty($this->request->data)) {
            $tax = $this->request->data['Tax']['tax_rate'];
            $tax = str_replace(',', '', $tax);
            $this->request->data['Tax']['tax_rate'] = $tax;
            if ($this->Tax->save($this->request->data)) {

                $this->log_entry("Tax saved : " . json_encode($this->request->data['Tax'], true), 'treasury');
                $redirect = (!empty($this->request->data['action_from'])) ? $this->request->data['action_from'] : 'taxes';
                $this->Session->setFlash('Tax #' . $this->request->data['Tax']['tax_ID'] . ' has been saved', 'flash/success');
                $this->redirect($redirect);
            } else {
                $this->Session->setFlash('Something went wrong during writing. Please re-check your values.', 'flash/error');
            }
        }

        /*
		 * Mandates list for select
		 */
        $this->set('mandates', $this->Mandate->getMandateList());

        /*
		 * Counterparties list for select
		 */
        $this->set('counterparties', $this->Counterparty->getCounterpartyList());

        /*
		 * Set Tax 
		 */
        $this->set('row', $this->Tax->getTaxById($id));
    }

    // DEPO TERMS
    public function depoterms()
    {
        $this->Paginator->settings = $this->paginate;
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('DepoTerm.label' => 'ASC'),
        );
        $this->set('rows', $this->Paginator->paginate('DepoTerm'));
    }
    public function depoterm($id = null)
    {
        /*
		 * the submitted data
		 */
        @$this->validate_param('string', $id);
        if (!empty($this->request->data)) {
            if (!empty($this->request->data['DepoTerm']['val'])) $this->request->data['DepoTerm']['value'] = $this->request->data['DepoTerm']['val'];

            if ($this->DepoTerm->save($this->request->data)) {
                $redirect = (!empty($this->request->data['action_from'])) ? $this->request->data['action_from'] : 'depoterms';
                $this->Session->setFlash('Depo Term ' . $this->request->data['DepoTerm']['label'] . ' has been saved', 'flash/success');
                $this->redirect($redirect);
            } else {
                $this->Session->setFlash('Something went wrong during writing. Please re-check your values.', 'flash/error');
            }
        }

        /*
		 * Set Depo term
		 */
        $this->set('row', $this->DepoTerm->getDepoTermById($id));
    }

    // DI Templates
    public function ditemplates()
    {
        $this->Paginator->settings = $this->paginate;
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('Mandate.mandate_name' => 'ASC', 'Counterparty.cpty_name' => 'ASC'),
        );
        $this->set('rows', $this->Paginator->paginate('DItemplate'));
    }
    public function ditemplate($id = null, $action = null)
    {

        @$this->validate_param('int', $id);
        @$this->validate_param('string', $action);
        @$this->validate_param('string', $this->request->data['DItemplate']['action_from']);
        $redirect = (!empty($this->request->data['DItemplate']['action_from'])) ? $this->request->data['DItemplate']['action_from'] : 'ditemplates';

        /*
		 * Save DI template
		 */
        if (!empty($this->request->data)) {
            foreach ($this->request->data['deposits_footer'] as $param) {
                @$this->validate_param('string', $param['key']);
                @$this->validate_param('string', $param['value']);
            }

            if (!empty($this->request->data['deposits_footer'])) {
                $footers = array();
                foreach ($this->request->data['deposits_footer'] as $key => $footer) {
                    if (!empty($footer['value'])) {
                        $key = substr(trim($footer['value']), 0, 16);

                        if (!empty($footer['key'])) {
                            $key = $footer['key'];
                        }
                        $footers[$key] = $footer['value'];
                    }
                }
                if (!empty($footers)) {
                    $this->request->data['DItemplate']['deposits_footer'] = json_encode($footers);
                    unset($this->request->data['deposits_footer']);
                }
            }

            if ($this->DItemplate->save($this->request->data)) {
                $this->log_entry("Di template saved : " . json_encode($this->request->data['DItemplate'], true), 'treasury');
                $this->Session->setFlash('DI template #' . $this->DItemplate->id . ' has been saved', 'flash/success');
                $this->redirect($redirect);
            } else {
                $this->Session->setFlash('Something went wrong during writing. Please re-check your values.', 'flash/error');
            }
        }

        /*
		 * Mandates list for select
		 */
        $this->set('mandates', $this->Mandate->getMandateList());

        /*
		 * Counterparties list
		 */
        $this->set('counterparties', $this->Counterparty->getCounterpartyList());

        /*
		 * Set DITemplate
		 */
        $this->set('row', $this->DItemplate->getDiTemplateById($id));
    }

    // Mandate groups
    function mandategroups()
    {
        $this->Paginator->settings = $this->paginate;
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('MandateGroup.mandategroup_name' => 'ASC')
        );
        $this->set('rows', $this->Paginator->paginate('MandateGroup'));
    }
    function mandategroup($id = null)
    {
        /*
    	 * Get current user group
    	 */

        @$this->validate_param('int', $id);
        $user_groups = array();
        if ($groups = $this->Session->read('UserAuth.UserGroups')) {
            foreach ($groups as $group) {
                $user_groups[$group['alias_name']] = $group['name'];
            }
        }
        $this->set('user_groups', $user_groups);
        $redirect = (!empty($this->request->data['MandateGroup']['action_from'])) ? $this->request->data['MandateGroup']['action_from'] : (!empty($id)) ? 'mandategroup/' . $id : '';

        if (!empty($this->request->data)) {
            if (empty($this->request->data['MandateGroup']['mandategroup_name'])) {
                $this->Session->setFlash('Something went wrong during writing. Please re-check your values.', 'flash/error');
                $this->redirect($redirect);
            }

            if ($mandategroup = $this->MandateGroup->getMandateGroupById($id)) {
                if (!empty($this->request->data['MandateGroup']['mandate_remove'])) {
                    foreach ($this->request->data['MandateGroup']['mandate_remove'] as $key => $rem) {
                        if (!empty($rem)) foreach ($mandategroup['Mandates'] as $mandkey => $mandate) {
                            if ($mandate['mandate_ID'] == $key) {
                                unset($mandategroup['Mandates'][$mandkey]);
                            }
                        }
                    }
                }
            } else {
                $mandategroup = array('MandateGroup' => array(), 'Mandates' => array());
            }

            /*
        	 * Add mandate
        	 */
            $duplicate = false;
            if (!empty($this->request->data['MandateGroup']['add_mandate'])) {
                foreach ($mandategroup['Mandates'] as $mandate) {
                    if ($mandate['mandate_ID'] == $this->request->data['MandateGroup']['add_mandate']) {
                        $duplicate = true;
                        break;
                    }
                }
                if (!$duplicate) {
                    $mandategroup['Mandates'][] = array('mandate_ID' => $this->request->data['MandateGroup']['add_mandate']);
                }
            }
            $mandategroup['MandateGroup']['mandategroup_name'] = $this->request->data['MandateGroup']['mandategroup_name'];

            //hack: force remove of previous subscriptions, as it will add all the new ones...
            if ($id) $this->MandateGroup->query('DELETE FROM mandate_group_subscriptions WHERE mandategroup_ID=' . intval($id));

            if ($mandategroup = $this->MandateGroup->save($mandategroup)) {
                if (empty($redirect)) {
                    if (!empty($mandategroup['MandateGroup']['id'])) {
                        $redirect = 'mandategroup/' . $mandategroup['MandateGroup']['id'];
                    }
                }

                $this->log_entry("Mandate group saved : " . json_encode($this->request->data['MandateGroup'], true), 'treasury');
                $this->Session->setFlash('Mandate group ' . $mandategroup['MandateGroup']['mandategroup_name'] . ' has been saved', 'flash/success');
                $this->redirect($redirect);
            } else {
                $this->Session->setFlash('Something went wrong during writing. Please re-check your values.', 'flash/error');
            }
        }

        /*
         * Mandates list for select
         */
        $mandates = array(0 => "----") + $this->Mandate->getMandateList(); //empty value is important, otherwise the first mandate of the list is added when updating
        $this->set('mandates', $mandates);

        /*
         * Delete mandates from group
         */
        $this->set('row', $this->MandateGroup->getMandateGroupById($id));
    }

    public function delete_mandategroup()
    {
        $id = $this->request->data['MandateGroup']['id'];
        if (!empty($id)) {
            $this->MandateGroup->query('DELETE FROM mandate_group_subscriptions WHERE mandate_id=' . $this->request->data['MandateGroup']['del_id'] . ' AND mandategroup_ID=' . $id);
            $this->redirect('mandategroup/' . $id);
        } else {
            $this->redirect('/treasury/treasurystaticdatas/mandategroups');
        }
    }
    public function delete_manager()
    {
        $mandate_id = $this->request->data['Mandate']['mandate_id'];
        $remove_id = $this->request->data['Mandate']['remove_id'];
        if (!empty($remove_id)) {
            $this->Mandate->query('DELETE FROM mandate_managers WHERE id=' . intval($remove_id) . ' AND mandate_id=' . intval($mandate_id));
            $this->log_entry("Mandate manager " . $remove_id . " removed from mandate " . $mandate_id, 'treasury');
        }
        $this->redirect('mandatemanager/' . $mandate_id);
    }
    public function delete_counterpartygroup()
    {
        $id = $this->request->data['CounterpartyGroup']['counterpartygroup_ID'];
        $del_id = $this->request->data['CounterpartyGroup']['del_counterpartygroup_ID'];
        if (!empty($id)) {
            $this->CounterpartyGroup->query('DELETE FROM counterparty_group_subscriptions WHERE cpty_id=' . $del_id . ' AND counterpartygroup_ID=' . $id);
            $this->log_entry("Counterparty with id " . $del_id . " removed from risk group : " . $id, 'treasury');
            $this->redirect('/treasury/treasurystaticdatas/counterpartygroup/' . $id);
        } else {
            $this->redirect('/treasury/treasurystaticdatas/counterpartygroups');
        }
    }

    public function delete_ditemplate()
    {
        $id = $this->request->data['DItemplate']['dit_id'];
        if (!empty($id)) {
            if ($this->DItemplate->delete($id)) {
                $this->log_entry("Di template with id " . $id . " deleted", 'treasury');
                $this->Session->setFlash('DI template #' . $id . ' has been removed', 'flash/success');
            }
            $this->log_entry("Counterparty with id " . $del_id . " removed from risk group : " . $id, 'treasury');
        }
        $this->redirect('/treasury/treasurystaticdatas/ditemplates');
    }

    /* mandate managers */
    public function mandatemanagers()
    {
        $this->Paginator->settings = $this->paginate;
        $this->Paginator->settings = array(
            'limit' => 50,
            'order' => array('Mandate.mandate_name' => 'ASC'),
            'conditions' => array('Mandate.mandate_name <>' => ''),
        );

        $this->set('rows', $this->Paginator->paginate('Mandate'));
    }
    public function mandatemanager($id = null)
    {

        @$this->validate_param('int', $id);

        $redirect = (!empty($this->request->data['action_from'])) ? $this->request->data['action_from'] : (!empty($id)) ? $redirect = 'mandatemanager/' . $id : 'mandate';

        if (!empty($this->request->data)) {
            $mandate = $this->MandateManager->getMandateManagerByMandateEmail($this->request->data['MandateManager']['mandate_ID'], $this->request->data['MandateManager']['email']);
            if (!empty($mandate)) {
                $this->Session->setFlash('This manager (' . $this->request->data['MandateManager']['email'] . ') seems to already be part of the Mandate #' . $this->request->data['MandateManager']['mandate_ID'], 'flash/error');
            } else {
                if ($this->MandateManager->save($this->request->data)) {
                    $this->log_entry("Mandate manager saved : " . json_encode($this->request->data['MandateManager'], true), 'treasury');
                    $this->Session->setFlash('Mandate Manager #' . $this->request->data['MandateManager']['name'] . ' has been saved', 'flash/success');
                    $this->redirect($redirect);
                } else {
                    $this->Session->setFlash('Something went wrong during writing. Please re-check your values.', 'flash/error');
                }
            }
        }

        if (!empty($id) && !empty($_GET['remove'])) {
            @$this->validate_param('int', $_GET['remove']);
            $this->Mandate->query('DELETE FROM mandate_managers WHERE id=' . intval($_GET['remove']) . ' AND mandate_id=' . intval($id));
            $this->log_entry("Mandate manager " . $_GET['remove'] . " removed from mandate " . $id, 'treasury');
            $this->redirect('mandatemanager/' . $id);
        }

        $this->set('row', $this->Mandate->getMandateById($id));
    }

    public function limits()
    {

        $this->Paginator->settings = $this->paginate;
        $this->Paginator->settings = array(
            'limit' => 50,
            'order' => array('Limit.modified' => 'DESC'),
            'conditions' => array('Limit.limit_date_to' => null),
        );

        $this->set('rows', $this->Paginator->paginate('Limit'));
    }
    public function limit($id = null, $action = null)
    {
        $row = null;
        $is_not_limited = false;
        @$this->validate_param('int', $id);
        @$this->validate_param('string', $action);
        $redirect = !empty($this->request->data['action_from']) ? $this->request->data['action_from'] : 'limits';

        /*
		 * Delete
		 */
        if (!empty($action) && !empty($id)) {
            if ($action == 'delete') {
                if ($this->Limit->delete($id)) {
                    $msg = "Limit " . $id . " deleted";
                    $this->log_entry($msg, 'treasury');
                    $this->Session->setFlash('Limit #' . $id . ' has been removed', 'flash/success');
                    $this->redirect($redirect);
                }
            }
        }

        if (!empty($this->request->data)) {
            $this->request->data['Limit']['no_limit'] = $this->request->data['Limit']['no_limit_hidden'];
            $limit_ID = $this->request->data['Limit']['limit_ID'];
            $mandategroup_ID = $this->request->data['Limit']['mandategroup_ID'];
            $cpty_ID = $this->request->data['Limit']['cpty_ID'];

            if (!empty($this->request->data['Limit']['limit_eur'])) {
                $this->request->data['Limit']['limit_eur'] = str_replace(',', '', $this->request->data['Limit']['limit_eur']);
            }

            if (!empty($this->request->data['Limit']['max_concentration_abs'])) {
                $this->request->data['Limit']['max_concentration_abs'] = str_replace(',', '', $this->request->data['Limit']['max_concentration_abs']);
            }

            //if its an update, create a new limit with the form values, and change de date_to of the old limit

            //automatically set the limit_date_from=now and limit_date_to=now + max_maturity (in days)			
            if (!empty($this->request->data['Limit']['max_maturity'])) {
                //$this->request->data['Limit']['limit_date_to'] = date('Y-m-d',strtotime('+'.$this->request->data['Limit']['max_maturity'].'days'));
            }

            //counterparty vs counterpartygroup
            if (!empty($this->request->data['Limit']['cpty_ID'])) {
                $this->request->data['Limit']['counterpartygroup_ID'] = 0;
                if (substr($this->request->data['Limit']['cpty_ID'], 0, 1) == 'g') {
                    $this->request->data['Limit']['counterpartygroup_ID'] = str_replace('g', '', $this->request->data['Limit']['cpty_ID']);
                    $this->request->data['Limit']['cpty_ID'] = 0;
                }
            }

            if (isset($this->request->data['Limit']['max_concentration_abs'])) {
                $this->request->data['Limit']['max_concentration_abs'] = trim($this->request->data['Limit']['max_concentration_abs'], ' ');
            }
            if (isset($this->request->data['Limit']['max_concentration'])) {
                $this->request->data['Limit']['max_concentration'] = trim($this->request->data['Limit']['max_concentration'], ' ');
            }
            if (isset($this->request->data['Limit']['max_concentration']) && ($this->request->data['Limit']['max_concentration'] != '0') && isset($this->request->data['Limit']['max_concentration_abs']) && ($this->request->data['Limit']['max_concentration_abs'] != '0')) {
                if (empty($this->request->data['Limit']['no_limit']) && empty($this->request->data['Limit']['max_concentration']) && empty($this->request->data['Limit']['max_concentration_abs'])) {
                    $this->Session->setFlash('Please choose between No Limit and a maximum concentration.', 'flash/error');
                    $this->redirect($this->referer());
                    die();
                }
            }
            //concentration abs/pcent + unit
            if (!empty($this->request->data['Limit']['no_limit'])) {
                if ($this->request->data['Limit']['no_limit'] == '1') {
                    $this->request->data['Limit']['max_concentration'] = null;
                    $this->request->data['Limit']['concentration_limit_unit'] = 'NA';
                }
            } elseif ((!empty($this->request->data['Limit']['max_concentration']) || ($this->request->data['Limit']['max_concentration'] == 0)) && ($this->request->data['Limit']['max_concentration'] != '')) {
                $this->request->data['Limit']['max_concentration'] /= 100;
                $this->request->data['Limit']['concentration_limit_unit'] = 'PCT';
            } else {
                $this->request->data['Limit']['concentration_limit_unit'] = 'ABS';
                $this->request->data['Limit']['max_concentration'] = $this->request->data['Limit']['max_concentration_abs'];
            }
            unset($this->request->data['Limit']['max_concentration_abs']);

            //dont duplicate limit if new limit is created with the same portfolio and counterparty
            $conditions[] = array(
                'mandategroup_ID' => $mandategroup_ID, 'cpty_ID' => $cpty_ID,
                'limit_date_to' => NULL, 'counterpartygroup_ID' => $this->request->data['Limit']['counterpartygroup_ID'],
                'limit_ID <>' => $limit_ID
            );

            $limits = null;

            $testIfLimitExist = false;
            if (empty($this->request->data['Limit']['limit_ID'])) {
                //dont duplicate limit if new limit is created with the same portfolio and counterparty
                $conditions = array(
                    'mandategroup_ID' => $mandategroup_ID, 'cpty_ID' => $cpty_ID,
                    'counterpartygroup_ID' => $this->request->data['Limit']['counterpartygroup_ID'],
                    'limit_ID <>' => $limit_ID, 'limit_date_to' => NULL,
                );
                $limits = $this->Limit->find('all', array(
                    'conditions' => $conditions,
                    'order' => array('limit_date_from' => 'DESC'),
                    'recursive' => -1,
                    'limit' => 1,
                ));

                if (!empty($limits)) {
                    foreach ($limits as $limit) {
                        if (strpos($limit['Limit']['limit_date_from'], '/')) {
                            $limit_date_from_exp = explode('/', $limit['Limit']['limit_date_from']);
                            $limit_date_from = $limit_date_from_exp[2] . "-" . $limit_date_from_exp[1] . "-" . $limit_date_from_exp[0];
                        } else {
                            $limit_date_from = $limit['Limit']['limit_date_from'];
                        }
                        if (strtotime($limit_date_from) < strtotime(date('Y-m-d'))) {
                            $testIfLimitExist = true;
                            break;
                        }
                    }
                }
            } else {
                //forbid changing the portfolio or mandate
                $conditions = array(
                    array('NOT' => array(
                        'mandategroup_ID' => $mandategroup_ID, 'cpty_ID' => $cpty_ID,
                        'counterpartygroup_ID' => $this->request->data['Limit']['counterpartygroup_ID']
                    )),
                    'limit_ID' => $limit_ID
                );
                $limits = $this->Limit->find('all', array(
                    'conditions' => $conditions,
                    'order' => array('limit_date_from' => 'DESC'),
                    'recursive' => -1,
                    'limit' => 1
                ));
                if (!empty($limits)) {
                    $testIfLimitExist = true;
                }
            }


            if ($testIfLimitExist == true) {
                if (empty($this->request->data['Limit']['limit_ID'])) {
                    $this->Session->setFlash('A limit has already been created for the selected portfolio and counterparty.', 'flash/warning');
                } else {
                    $this->Session->setFlash('You cannot change the selected portfolio and counterparty on an existing limit.', 'flash/warning');
                }
            } else {
                if ($saved = $this->Limit->save($this->request->data)) {
                    $this->log_entry("Limit " . $this->request->data['Limit']['limit_name'] . " saved : " . json_encode($this->request->data['Limit'], true), 'treasury');
                    $this->Session->setFlash('Limit ' . $this->request->data['Limit']['limit_name'] . ' has been saved', 'flash/success');
                    $this->redirect($redirect);
                } else {
                    $this->Session->setFlash('Something went wrong during writing. Please re-check your values.', 'flash/error');
                }
            }
        }

        /*
		 * Datas list for select
		 */
        $mandategroupList = $this->MandateGroup->getMandateGroupList();
        $this->set('mandategroups', $mandategroupList);

        /*
		 * Set Counterparties and CounterpartiesGroups for the same select element
		 */
        $counterparties = array();
        $groups = $this->CounterpartyGroup->getCounterpartyGroupList();
        if (!empty($groups)) {
            $counterparties['Groups'] = array();
            foreach ($groups as $key => $group) {
                if (!empty($group)) $counterparties['Groups']['g' . $key] = $group;
            }
        }
        $counterparties['Counterparties'] = $this->Counterparty->getCounterpartyList();
        $this->set(compact('counterparties'));
        $is_not_limited_manualMode = false;
        $is_not_limit_automaticMode = false;
        $is_not_limited = false;
        if (!empty($id)) {

            $row = $this->Limit->getLimitById($id);

            //replace id by g.groupID if counterpartygroup
            if (!empty($row['Limit']['counterpartygroup_ID'])) {
                $row['Limit']['cpty_ID'] = 'g' . $row['Limit']['counterpartygroup_ID'];
            }

            //concentration: abs/pcent
            $row['Limit']['max_concentration_abs'] = '';
            if (!empty($row['Limit']['concentration_limit_unit']) && $row['Limit']['concentration_limit_unit'] == 'ABS') {
                $row['Limit']['max_concentration_abs'] = $row['Limit']['max_concentration'];
                if ($row['Limit']['max_concentration_abs'] == '') {
                    $row['Limit']['max_concentration_abs'] = 0;
                }
                $row['Limit']['max_concentration'] = '';
            } elseif (!empty($row['Limit']['concentration_limit_unit']) && $row['Limit']['concentration_limit_unit'] == 'PCT') {
                $row['Limit']['max_concentration'] *= 100;
                if ($row['Limit']['max_concentration'] == '') {
                    $row['Limit']['max_concentration'] = 0;
                }
            }

            //retrieve the first limit created for mandate and cpty
            $conditions = array('Limit.mandategroup_ID' => $row['Limit']['mandategroup_ID']);
            if (!empty($row['Limit']['cpty_ID'])) {
                $conditions = array('Limit.mandategroup_ID' => $row['Limit']['mandategroup_ID'], 'Limit.cpty_ID' => $row['Limit']['cpty_ID']);
            }
            if (!empty($row['Limit']['counterpartygroup_ID'])) {
                $conditions = array('Limit.mandategroup_ID' => $row['Limit']['mandategroup_ID'], 'Limit.counterpartygroup_ID' => $row['Limit']['counterpartygroup_ID']);
            }

            $MandateGroup = ClassRegistry::init('Treasury.MandateGroup');
            $concentration_unit = $MandateGroup->getConcentrationUnit($row['Limit']['mandategroup_ID']);
            $is_not_limit_automaticMode = ($concentration_unit == 'NA');
            $is_not_limited_manualMode = (($row['Limit']['max_concentration_abs'] == null) && ($row['Limit']['max_concentration'] == null));
            $is_not_limited = $row['Limit']['concentration_limit_unit'] == 'NA';
        }

        $this->set('is_not_limit_automaticMode', $is_not_limit_automaticMode);
        $this->set('is_not_limited_manualMode', $is_not_limited_manualMode);
        $this->set('is_not_limited', $is_not_limited);
        $this->set(compact('row'));
    }

    public function limite_delete()
    {
        $this->autoRender = false;
        $id = $this->request->data['limit']['limit_id'];
        error_log("delete limit " . $id);
        if ($this->Limit->delete($id)) {
            $msg = "Limit " . $id . " deleted";
            $this->log_entry($msg, 'treasury');
            $this->Session->setFlash('Limit #' . $id . ' has been removed', 'flash/success');
        }
        $this->redirect('/treasury/treasurystaticdatas/limits');
    }

    public function limitGetCalculated($mandategroupId = null, $cpty = null)
    {
        @$this->validate_param('int', $mandategroupId);
        @$this->validate_param('string', $cpty); //can be a group
        $out = array('error' => 'not found');

        $mandategroupId = $this->request->data['limit']['mandategroupId'];
        $cpty = $this->request->data['limit']['cpty'];

        $cpty = strtolower($cpty);
        $mandategroupId = strtolower($mandategroupId);
        $MandateGroup = ClassRegistry::init('Treasury.MandateGroup');
        $pirat_number = null;
        if (substr($cpty, 0, 1) == 'g') {
            $group = str_replace('g', '', $cpty);
            $cpty = null;
            $cptygroup = $this->CounterpartyGroup->getCounterpartyGroupById($group);

            if (!empty($cptygroup['Head']['Counterparty'])) {
                $cpty = $cptygroup['Head']['Counterparty']['cpty_ID'];
                $pirat_number = $cptygroup['Head']['Counterparty']['pirat_number'];
            } else {
                $out = array('error' => 'This counterpartygroup has not head');
                die(json_encode($out));
            }
            //check if the counterparty has a piratnumber
            if (empty($pirat_number)) {
                $out['error'] = 'The calculation of limit cannot be automatic because the ' . $cptygroup['Head']['Counterparty']['cpty_name'] . ' has no Pirat number.';
                die(json_encode($out));
            }
        }

        if (!empty($cpty)) {
            //check if the counterparty has a piratnumber
            $cptyModel = $this->Counterparty->getCounterpartyById($cpty);

            if (empty($cptyModel['Counterparty']['pirat_number'])) {
                $out['error'] = 'The calculation of limit cannot be automatic because the ' . $cptyModel['Counterparty']['cpty_name'] . ' has no Pirat number.';
                die(json_encode($out));
            }

            $calculated = $this->CalculatedLimit->getCalculatedLimitByMandateGroupCounterparty($mandategroupId, $cpty);

            if (!empty($calculated['CalculatedLimit'])) {
                $out = array('result' => $calculated['CalculatedLimit']);
                $out['result']['portfolio_size'] = $this->MandateGroup->getSize($mandategroupId);
                $out['result']['calculated_limit'] = UniformLib::uniform($out['result']['calculated_limit'], 'calculated_limit');
                if ((empty($calculated['CalculatedLimit']['eligibility'])) || ($calculated['CalculatedLimit']['eligibility'] == '0')) {
                    $out['result']['calculated_max_concentration'] = 0;
                } else {
                    $out['result']['calculated_max_concentration'] = UniformLib::uniform($out['result']['calculated_max_concentration'], 'calculated_max_concentration');
                }
            } else {
                $mandateGroupName = $MandateGroup->query('SELECT mandategroup_name FROM mandate_groups WHERE id = ' . intval($mandategroupId));
                $out = array('error' => 'No calculated limit found for mandategroup ' . $mandateGroupName[0]['mandate_groups']['mandategroup_name'] . ' and counterparty ' . $cptyModel['Counterparty']['cpty_name']);
                die(json_encode($out));
            }
        }
        error_log(json_encode($out, true));
        die(json_encode($out));
    }
}
