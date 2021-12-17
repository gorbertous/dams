<?php

declare(strict_types=1);

namespace Damsv2\Controller;

use App\Lib\Helpers;
use Cake\Event\EventInterface;
use KubAT\PhpSimple\HtmlDomParser;
use \DateTime;
use \DateInterval;

/**
 * Rules Controller
 *
 * @property \App\Model\Table\RulesTable $Rules
 * 
 * @method \App\Model\Entity\Rule[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RulesController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        //$this->loadComponent('Security');
        $this->loadComponent('Spreadsheet');
        $this->loadComponent('SAS');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        //$this->Security->setConfig('unlockedActions', ['inclusion']);
        //$this->Security->setConfig('blackHoleCallback', 'blackhole');
    }

    protected function checkPermissions()
    {
        if (!$this->request->is('ajax') ||
                !(substr($this->request->getParam('action'), 0, 3) == 'get')) {
            parent::checkPermissions();
        } else {
            if (!$this->perm->hasRead('index')) {
                $ident = $this->userIdentity();
                error_log('access denied2 ' . $this->getName() . ' ' . $this->request->getParam('action') . ' ' . json_encode($ident));
                $this->Flash->error(__('Access Denied.'));
                $this->setAction("deny");
            }
        }
    }

    public $paginate = [
        'limit' => 50,
        'order' => [
            'rule_id' => 'desc'
        ]
    ];

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $session = $this->request->getSession();
        $this->loadModel('Damsv2.Mandate');
        $this->loadModel('Damsv2.Portfolio');

        $conditions = ['Rules.top_level' => 'Y', 'or' => ['Rules.portfolio_id NOT IN' => [-1, 0], 'Rules.portfolio_id IS' => null]];
        $cond_portfolio = [];
        $cond_mandate = [];

        if (!$session->read('Form.data.brules')) {
            $session->write('Form.data.brules', [
                'rule_name'     => '',
                'template_type' => '',
                'portfolio_id'  => '',
                'rule_level'    => '',
                'category_id'   => '',
                'is_warning'    => '',
                'product_id'    => '',
                'mandate_id'    => '',
                'mandate_name'  => ''
            ]);
        }

        if ($this->request->is('post')) {
            //load session with request data
            $session->write('Form.data.brules', $this->request->getData());
        }

        //filter dropdowns
        $prodid = !empty($this->request->getData('product_id')) ? $this->request->getData('product_id') : $session->read('Form.data.brules.product_id');
        $manid = !empty($this->request->getData('mandate_id')) ? $this->request->getData('mandate_id') : $session->read('Form.data.brules.mandate_id');

        //product
        if ($prodid) {
            $cond_portfolio = Helpers::arrayPushAssoc($cond_portfolio, 'Product.product_id', $prodid);
            $cond_mandate = Helpers::arrayPushAssoc($cond_portfolio, 'Product.product_id', $prodid);
        }
        //mandate
        if ($manid) {//from mandate name to portfolio_id 
            // $getmandate = $this->Portfolio->find('all', ['fields' => ['mandate'], 'conditions' => ['Portfolio.mandate_id' => $manid]])->first();
            // $session->write('Form.data.brules.mandate_name', $getmandate->mandate);
            $cond_portfolio = Helpers::arrayPushAssoc($cond_portfolio, 'Portfolio.mandate_id', $manid);
        }

        if ($prodid) {
            if (($prodid) && ($manid)) {
                $mandate_possible = $this->Portfolio->find('all', [
                            'fields'     => ['portfolio_name', 'product_id'],
                            'conditions' => [
                                'Portfolio.product_id' => $prodid,
                                'Portfolio.mandate_id'    => $session->read('Form.data.brules.mandate_id')
                    ]])->first();

                if (empty($mandate_possible)) {
                    $session->write('Form.data.brules.mandate_id', null);
                } else {
                    $cond_portfolio = Helpers::arrayPushAssoc($cond_portfolio, 'Portfolio.mandate_id', $session->read('Form.data.brules.mandate_id'));
                }
            }
            $cond_portfolio = Helpers::arrayPushAssoc($cond_portfolio, 'Product.product_id', $prodid);
            $cond_mandate = Helpers::arrayPushAssoc($cond_portfolio, 'Product.product_id', $prodid);
        }

        //$template_types = $this->Rules->TemplateType->find('list')->where(['type_id IN' => [1, 2, 3]])->toArray();
        $data = $this->Rules->TemplateType->find('list')->toArray();
        $template_types = $this->filterByPermission($data, 'index');
        $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.template_type_id IN', array_column($template_types,'value'));

        $products = $this->Portfolio->Product->getProducts();

        $mandates = $this->Portfolio->find('list', [
                    'contain'    => ['Product'],
                    'fields'     => ['mandate_id', 'mandate'],
                    'keyField'   => 'mandate_id',
                    'valueField' => 'mandate',
                    'group'      => 'mandate',
                    'order'      => 'mandate',
                    'conditions' => [$cond_mandate]
                ])->toArray();

        $portfolios = $this->Portfolio->find('list', [
                    'contain'    => ['Product'],
                    'fields'     => ['Product.name', 'Portfolio.portfolio_name', 'Portfolio.portfolio_id'],
                    'keyField'   => 'portfolio_id',
                    'valueField' => 'portfolio_name',
                    'groupField' => 'product.name',
                    'order'      => ['Product.name', 'Portfolio.portfolio_name'],
                    'conditions' => [$cond_portfolio]
                ])->toArray();
        $rulelevels = ['MANDATE' => 'MANDATE', 'PORTFOLIO' => 'PORTFOLIO', 'PRODUCT' => 'PRODUCT', 'TRANSVERSAL' => 'TRANSVERSAL'];
        $categories = $this->categoriesByPermission('index');
        $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.rule_category IN', array_keys($categories));

        //product id
        if ($prodid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.product_id', $prodid);
        }

        if ($manid) {
            $portfolio_ids = $this->Portfolio->find('list', [
                        'conditions' => ['mandate_id' =>  $manid],
                        'fields'     => ['portfolio_id'],
                        'keyField'   => 'portfolio_id',
                        'valueField' => 'portfolio_id'
                    ])->toArray();
            if (!empty($portfolio_ids)) {
                $conditions = Helpers::arrayPushAssoc($conditions, 'or', ['Rules.mandate_id'      => $manid,
                            'Rules.portfolio_id IN' => $portfolio_ids]);
            } else {
                $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.mandate_id', $manid);
            }
        }

        //portfolio id
        $portid = $session->read('Form.data.brules.portfolio_id');
        if ($portid) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.portfolio_id', $portid);
        }
        $rule_name = $session->read('Form.data.brules.rule_name');
        if ($rule_name) {
            $rule_name = '%' . $rule_name . '%';
            $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.rule_name LIKE', $rule_name);
        }
        $rule_level = $session->read('Form.data.brules.rule_level');
        if ($rule_level) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.rule_level', $rule_level);
        }
        $category_id = $session->read('Form.data.brules.category_id');
        if ($category_id) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.rule_category', $category_id);
        }
        $is_warning = $session->read('Form.data.brules.is_warning');
        if ($is_warning) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.is_warning', $is_warning);
        }
        $template_type = $session->read('Form.data.brules.template_type');
        if ($template_type) {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.template_type_id', $template_type);
        }

        $query = $this->Rules->find('all', [
            'conditions' => [$conditions]
        ]);

        $rules = $this->paginate($query, ['contain' => ['Product', 'Mandate', 'Portfolio', 'TemplateType', 'VUser']]);
    
        $this->set(compact('rules', 'rulelevels', 'template_types', 'categories', 'products', 'mandates', 'portfolios', 'session'));
    }

    private function categoriesByPermission($action) {
        $categories = [];
        if ($this->perm->hasRead(['action' => $action,'filter' => 'consistency'])) {
            $categories['CONSISTENCY'] = 'CONSISTENCY';
        }
        if ($this->perm->hasRead(['action' => $action,'filter' => 'elegibility'])) {
            $categories['ELIGIBILITY'] = 'ELIGIBILITY';
        }

        return $categories;
    }

    public function export()
    {
        if ($this->request->is('ajax')) {
            $this->loadModel('Damsv2.Portfolio');
            $session = $this->request->getSession();
            $conditions = ['or' => ['Rules.portfolio_id NOT IN' => [-1, 0], 'Rules.portfolio_id IS' => null]];
            //set view to Ajax
            $this->viewBuilder()->setLayout('ajax');
            $prodid = $session->read('Form.data.brules.product_id');
            if ($prodid) {
                $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.product_id', $prodid);
            }
            $manid = $session->read('Form.data.brules.mandate_id');
            if ($manid) {
                $portfolio_ids = $this->Portfolio->find('list', [
                            'conditions' => ['mandate' => $session->read('Form.data.brules.mandate_name')],
                            'fields'     => ['portfolio_id'],
                            'keyField'   => 'portfolio_id',
                            'valueField' => 'portfolio_id'
                        ])->toArray();
                if (!empty($portfolio_ids)) {
                    $conditions = Helpers::arrayPushAssoc($conditions, 'or', ['Rules.mandate_id'      => $manid,
                                'Rules.portfolio_id IN' => $portfolio_ids]);
                } else {
                    $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.mandate_id', $manid);
                }
            }
            $portid = $session->read('Form.data.brules.portfolio_id');
            if ($portid) {
                $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.portfolio_id', $portid);
            }
            $rule_name = $session->read('Form.data.brules.rule_name');
            if ($rule_name) {
                $rule_name = '%' . $rule_name . '%';
                $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.rule_name LIKE', $rule_name);
            }
            $rule_level = $session->read('Form.data.brules.rule_level');
            if ($rule_level) {
                $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.rule_level', $rule_level);
            }
            $category_id = $session->read('Form.data.brules.category_id');
            $categories = $this->categoriesByPermission('index');
            $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.rule_category IN', array_keys($categories));
            if ($category_id) {
                $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.rule_category', $category_id);
            }
            $is_warning = $session->read('Form.data.brules.is_warning');
            if ($is_warning) {
                $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.is_warning', $is_warning);
            }
            $template_type = $session->read('Form.data.brules.template_type');
            if ($template_type) {
                $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.template_type_id', $template_type);
            }
            if (!empty($conditions)) {
                $results = $this->Rules->find('all', [
                            'fields'     => ['rule_number', 'rule_name', 'is_warning', 'inclusion_and_edit', 'top_level', 'rule_type', 'checked_entity', 'checked_field', 'operator', 'param_1_value', 'param_2_value', 'truepart_id', 'falsepart_id', 'description', 'version_number'],
                            'contain'    => ['Product', 'Mandate', 'Portfolio', 'TemplateType', 'VUser'],
                            'conditions' => [$conditions]
                        ])->toArray();
                $filepath = '/var/www/html/data/damsv2/export/export_br' . time() . '.xlsx';
                $skeleton = ['BRules'];
                $this->Spreadsheet->generateExcelFromQuery($results, $skeleton, $filepath, true);
                $this->set('filepath', basename($filepath));
            }
        }
    }

    public function createExcelFile($rules)
    {

        $this->autoRender = false;
        $filepath = '/var/www/html/data/damsv2/br/br_' . $rules[0]['entity']['rule_number'] . '_' . date("Ymd") . '_' . time();
        $skeleton = ['Worksheet']; 
        //rule_number	Rule_name	is_warning	inclusion_and_edit	top_level	rule_type	checked_entity	checked_field	operator	param_1_value	param_2_value	truepart_id	falsepart_id	Description	version_number
        $values = array_reverse(array_map(function ($a) {
            $tmp = ['rule_number'=>$a['entity']['rule_number'],'Rule_name'=>$a['entity']['rule_name'],'is_warning'=>$a['entity']['is_warning'],'inclusion_and_edit'=>$a['entity']['inclusion_and_edit'],'top_level'=>$a['entity']['top_level'],'rule_type'=>$a['entity']['rule_type'],'checked_entity'=>$a['entity']['checked_entity'],'checked_field'=>$a['entity']['checked_field'],'operator'=>$a['entity']['operator'],'param_1_value'=>"".$a['entity']['param_1_value'],'param_2_value'=>"".$a['entity']['param_2_value'],'truepart_id'=>$a['entity']['truepart_id'],'falsepart_id'=>$a['entity']['falsepart_id'],'Description'=>$a['entity']['description'],'version_number'=>$a['entity']['version_number'],'action'=>$a['action']];
            // if ($a['action'] == 'D') {
            //     $tmp['top_level'] = 'Y';
            // }
            if ($a['entity']['datatype'] == "date") {
                if ($tmp['param_1_value'] !== "" && intval($tmp['param_1_value']) > 0) {
                    $param_date = new DateTime('1960-01-01');
                    $param_date->add(new DateInterval('P'.$tmp['param_1_value'].'D'));
                    $tmp['param_1_value'] = $param_date->format('d/m/Y');
                    // $tmp['param_1_value'] = $param_date;
                }
                if ($tmp['param_2_value'] !== "" && intval($tmp['param_2_value']) > 0) {
                    $param_date = new DateTime('1960-01-01');
                    $param_date->add(new DateInterval('P'.$tmp['param_2_value'].'D'));
                    $tmp['param_2_value'] = $param_date->format('d/m/Y');
                    // $tmp['param_2_value'] = $param_date;
                }
            }
            return $tmp;
        }, $rules));
        $this->Spreadsheet->generateExcelFromQuery($values, $skeleton, $filepath. '.xlsx', false);
        $this->Spreadsheet->generateCsvFromQuery($values, $filepath. '.csv', false);
        return $filepath;
    }
    public function callSas($rules, $filepath) {
        //send to sas
        $params = [
            'filename'         => $filepath. '.xlsx',
            'template_type_id' => $rules[0]['entity']['template_type_id'],
            'rule_level'       => $rules[0]['entity']['rule_level'],
            'category'         => $rules[0]['entity']['rule_category'],
            'user_id'          => $rules[0]['entity']['user_id'],
            'product_id'       => $rules[0]['entity']['product_id'] ?? 0,
            'mandate'          => $rules[0]['entity']['mandate_id'] ?? 0,
            'portfolio_id'     => $rules[0]['entity']['portfolio_id'] ?? 0,
            'csv_file'         => $filepath. '.csv',
        ];

        $ret = $this->SAS->curlWithId('import_br_new.sas',$params);
        if (preg_match('/No errors in the BR input file/',$ret['value'])) {
            return true;
        } elseif (preg_match('/This request completed with errors/',$ret['value'])) {
            $this->Flash->error(__('Sas request error, please contact support.') . '(' . $ret['id'] . ')');
        } else {
            $dom = HtmlDomParser::str_get_html($ret['value']);
            $lines = $dom->find('td');
            foreach ($lines as $line) {
                if ($line->class == 'l data') {
                    if (!preg_match('/rows have been/',$line->innertext)) {
                        $this->Flash->error(ucfirst(preg_replace('/At line\(s\) +(,?[0-9]+)+: (\&[^ ]+)?/','',$line->innertext)));
                    }
                }
            }
        }
        return false;
    }

    /**
     * Copy method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function copy()
    {
        $req = $this->request->getData();
        $template_type = $req['template_type'] ?? '';
        $rule_level = $req['rule_level'] ?? 'PRODUCT';
        $rule_category = $req['rule_category'] ?? 'CONSISTENCY';
        $product_id = $req['product_id'] ?? '';
        $mandate_id = $req['mandate_id'] ?? '';
        $portfolio_id = $req['portfolio_id'] ?? '';
        $product_id_to = $req['product_id_to'] ?? '';
        $mandate_id_to = $req['mandate_id_to'] ?? '';
        $portfolio_id_to = $req['portfolio_id_to'] ?? '';

        $rule = $req['rule'] ?? [];
        if ($this->request->is(['patch', 'post', 'put'])) {
            $rules = [];
            foreach ($rule as $key => $value) {
                if ($value == 'Y') {
                    $temp = $this->Rules->get($key, ['contain' => ['Truepart', 'Falsepart']]);
                    $this->Rules->expandRecursively($temp);
                    $rules[] = $temp;
                }
            }
            if ($req['expandField'] == '') {
                if (($product_id != '' && ($product_id_to == '' || $product_id == $product_id_to)) ||
                        ($mandate_id != '' && ($mandate_id_to == '' || $mandate_id == $mandate_id_to)) ||
                        ($portfolio_id != '' && ($portfolio_id_to == '' || $portfolio_id == $product_id_to))) {
                    $this->Flash->error(__('Source and destination levels must be different.'));
                } else {
                    if (count($rules) <= 0) {
                        $this->Flash->error(__('Please select some rules to copy.'));
                    } else {
                        //Validate and update before copy
                        for ($i = 0; $i < count($rules); $i++) {
                            $rules[$i]->product_id = $product_id_to;
                            $rules[$i]->mandate_id = $mandate_id_to;
                            $rules[$i]->portfolio_id = $portfolio_id_to;
                            $rules[$i]->rule_id = null;
                        }
                        $this->createEditFieldsDomains($rules, 'copy');
                        $this->render('add');
                        return;
                    }
                }
            }
        }
        $conditions = ['Rules.top_level' => 'Y'];
        $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.template_type_id', $template_type);
        $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.rule_level', $rule_level);
        $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.rule_category', $rule_category);

        if ($portfolio_id && $rule_level == 'PORTFOLIO') {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.portfolio_id', $portfolio_id);
        } else {
            $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.portfolio_id is', null);
            if ($mandate_id && $rule_level == 'MANDATE') {
                $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.mandate_id', $mandate_id);
            } else {
                $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.mandate_id is', null);
                if ($product_id && $rule_level == 'PRODUCT') {
                    $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.product_id', $product_id);
                } else {
                    $conditions = Helpers::arrayPushAssoc($conditions, 'Rules.product_id is', null);
                }
            }
        }

        $query = $this->Rules->find('all', [
            'conditions' => [$conditions]
        ]);
        $rules = $this->paginate($query);
        $data = $this->Rules->TemplateType->find('list')->toArray();
        $template_types = $this->filterByPermission($data, 'add');
        $products = $this->Rules->Product->find('list', ['order' => 'Product.name']);
        $mandates = $this->Rules->Mandate->find('list', ['order' => 'Mandate.mandate_name']);
        $portfolios = $this->Rules->Portfolio->find('list', ['order' => ['Portfolio.mandate', 'Portfolio.portfolio_name'], 'groupField' => 'mandate']);
        $rule_levels = ['PRODUCT' => 'PRODUCT', 'PORTFOLIO' => 'PORTFOLIO', 'MANDATE' => 'MANDATE'];
        $rule_categories = $this->categoriesByPermission('copy');
        $this->set(compact('rules', 'rule', 'template_type', 'template_types', 'rule_levels', 'rule_level', 'rule_category', 'rule_categories', 'products', 'mandates', 'portfolios', 'product_id', 'product_id_to', 'mandate_id', 'mandate_id_to', 'portfolio_id', 'portfolio_id_to'));
    }

    private function getOperatorArray(): array
    {
        // types: 1 for text, 2 for number and 4 for date. Sum values to set when the operator is shown.
        return [['value' => '=', 'text' => __('='), 'types' => '7', 'params' => '1'],
            ['value' => '<>', 'text' => __('<>'), 'types' => '7', 'params' => '1'],
            ['value' => '<', 'text' => __('<'), 'types' => '6', 'params' => '1'],
            ['value' => '<=', 'text' => __('<='), 'types' => '6', 'params' => '1'],
            ['value' => '>', 'text' => __('>'), 'types' => '6', 'params' => '1'],
            ['value' => '>=', 'text' => __('>='), 'types' => '6', 'params' => '1'],
            ['value' => '()', 'text' => __('()'), 'types' => '6', 'params' => '2'],
            ['value' => '[]', 'text' => __('[]'), 'types' => '6', 'params' => '2'],
            ['value' => 'in', 'text' => __('in'), 'types' => '1', 'params' => '1'],
            ['value' => 'ex', 'text' => __('ex'), 'types' => '1', 'params' => '1'],
            ['value' => 'like', 'text' => __('like'), 'types' => '1', 'params' => '1'],
            ['value' => 'not like', 'text' => __('not like'), 'types' => '1', 'params' => '1'],
            ['value' => 'startsWith', 'text' => __('startsWith'), 'types' => '1', 'params' => '1'],
            ['value' => 'missing', 'text' => __('missing'), 'types' => '7', 'params' => '0']
        ];
    }

    private function createEditFieldsDomains($var, $action)
    {
        $data = $this->Rules->TemplateType->find('list')->toArray();
        $template_types = $this->filterByPermission($data, $action);
        $products = $this->Rules->Product->find('list', ['order' => 'Product.name']);
        $mandates = $this->Rules->Mandate->find('list', ['order' => 'Mandate.mandate_name']);
        $portfolios = $this->Rules->Portfolio->find('list', ['order' => ['Portfolio.mandate', 'Portfolio.portfolio_name'], 'groupField' => 'mandate']);
        $users = $this->Rules->VUser->find('list', ['limit' => 200]);
        $rule_levels = ['PRODUCT' => __('PRODUCT'), 'MANDATE' => __('MANDATE'), 'PORTFOLIO' => __('PORTFOLIO'), 'TRANSVERSAL' => __('TRANSVERSAL')];
        $rule_categories = $this->categoriesByPermission($action);

        $first_rule = $var;
        if ($action != 'edit') {
            $first_rule = $var[0];
        }
        //TODO: Update queries to retrieve RuleParameter, distinct entities and fields.
        //Use if $var is an Array $var[0], if not $var, filter by current rule template_type, product(or null), mandate(or null) and portfolio(or null)
        //Mechanism on Checked Entity must change, no more template_type hidding options.
        $filter = [];
        if ($first_rule->rule_level == 'PORTFOLIO') {
            $products = $this->getTableLocator()->get('Damsv2.Portfolio')->find()->select(['product_id'])->where(['portfolio_id IS'=>$first_rule->portfolio_id, 'product_id IS NOT NULL'])->all()->combine('product_id', function ($entity) {return $entity->product_id; })->toArray();
            $products = array_values($products);
            $products[] = null;
            $filter = ['template_type_id'=>($first_rule->template_type_id ?? 1),
                       'OR' => [['portfolio_id IS' => $first_rule->portfolio_id], 
                                ['portfolio_id is null', 'mandate_id IS' => $first_rule->mandate_id], 
                                ['mandate_id is null', 'product_id IN' => $products],
                                ['portfolio_id is null', 'mandate_id is null', 'product_id is null']
                               ]
                      ];
        } elseif ($first_rule->rule_level == 'MANDATE') {
            $products = $this->getTableLocator()->get('Damsv2.Portfolio')->find()->select(['product_id'])->where(['mandate_id IS'=>$first_rule->mandate_id, 'product_id IS NOT NULL'])->all()->combine('product_id', function ($entity) {return $entity->product_id; })->toArray();
            $products = array_values($products);
            $products[] = null;
            $filter = ['template_type_id'=>($first_rule->template_type_id ?? 1),
                       'OR' => [['mandate_id IS' => $first_rule->mandate_id], 
                                ['mandate_id is null', 'product_id IN' => $products],
                                ['portfolio_id is null', 'mandate_id is null', 'product_id is null']
                               ]
                      ];
        } elseif ($first_rule->rule_level == 'PRODUCT') {
            $filter = ['template_type_id'=>($first_rule->template_type_id ?? 1),
                       'OR' => [['product_id IS' => $first_rule->product_id], 
                                ['portfolio_id is null', 'mandate_id is null', 'product_id is null']
                               ]
                      ];
        } else {
            $filter = ['template_type_id'=>($first_rule->template_type_id ?? 1)];
        }
        $checked_entities = $this->getTableLocator()->get('Damsv2.BrParameter')->find()->select(['checked_entity', 'template_type_id'])->where($filter)->group(['checked_entity', 'template_type_id'])->order(['checked_entity', 'template_type_id'])->all()->combine(function ($entity) {
            return null;
        }, function($entity) {
            return ['value' => $entity->checked_entity, 'text' => $entity->checked_entity, 'type' => $entity->template_type_id];
        });
        $checked_fields = $this->getTableLocator()->get('Damsv2.BrParameter')->find()->select(['checked_entity', 'checked_field', 'datatype', 'dictionary_ids' => 'GROUP_CONCAT(DISTINCT dictionary_id SEPARATOR \',\')'])->where($filter)->group(['checked_entity', 'checked_field', 'datatype'])->order('checked_entity', 'checked_field', 'datatype')->all()->combine('table_name', function($entity) {
            return ['value' => $entity->checked_field, 'text' => $entity->checked_field, 'type' => $entity->datatype, 'table' => $entity->checked_entity, 'dictionary' => $entity->dictionary_ids];
        }); 
            
        $operators = $this->getOperatorArray();
        if ($action == 'edit') {
            $rule = $var;
            $this->set(compact('rule', 'template_types', 'rule_levels', 'rule_categories', 'products', 'mandates', 'portfolios', 'checked_entities', 'checked_fields', 'operators', 'users'));
        } else {
            $rules = $var;
            $this->set(compact('rules', 'template_types', 'rule_levels', 'rule_categories', 'products', 'mandates', 'portfolios', 'checked_entities', 'checked_fields', 'operators', 'users'));
        }
    }

    private function filterByPermission($data, $action)
    {
        $filtered = [];
        foreach ($data as $key => $line) {
            $item = [];
            $item['value'] = $key;
            $item['hidden'] = true;
            $item['text'] = $line;
            if (($this->perm->hasRead(['filter' => 'payment']) &&
                    in_array($line, ['Payment Demand'])) ||
                    ($this->perm->hasRead(['filter' => 'payment']) &&
                    in_array($line, ['Loss Recovery'])) ||
                    ($this->perm->hasRead(['filter' => 'inclusion']) &&
                    in_array($line, ['Inclusion']))) {
                $item['hidden'] = false;
				$filtered[] = $item;
            }			
        }
        return $filtered;
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $rules = [];
        $req = (array) $this->request->getData();
        $user_id = $this->userIdentity()->id;
        if ($this->request->is(['patch', 'post', 'put'])) {
            $id = 0;
            $skip_id = PHP_INT_MAX;
            if (str_starts_with($req['expandField'], 'delete-rule')) {
                $skip_id = intval(str_replace('delete-rule-', '', $req['expandField']));
            }
            if ($req[0]['rule_level'] == 'PORTFOLIO' && isset($req[0]['portfolio_id']) && $req[0]['portfolio_id'] != null) {
                $portfolio = $this->Rules->Portfolio->find()->where(['portfolio_id' => $req[0]['portfolio_id']])->first();
                //$mandate = $this->Rules->Mandate->find()->where(['mandate_name' => $portfolio->mandate])->first();
                $req[0]['product_id'] = $portfolio->product_id;
                $req[0]['mandate_id'] = $portfolio->mandate_id;
            } elseif ($req[0]['rule_level'] == 'MANDATE' && isset($req[0]['mandate_id']) && $req[0]['mandate_id'] != null) {
                //$mandate = $this->Rules->Mandate->find()->where(['mandate_id' => $req[0]['mandate_id']])->first();
                $portfolio = $this->Rules->Portfolio->find()->where(['mandate_id' => $req[0]['mandate_id']])->first();
                $req[0]['product_id'] = $portfolio?$portfolio->product_id:null;
                $req[0]['portfolio_id'] = null;
            } elseif ($req[0]['rule_level'] == 'PRODUCT' && isset($req[0]['product_id']) && $req[0]['product_id'] != null) {
                $req[0]['mandate_id'] = null;
                $req[0]['portfolio_id'] = null;
            } else {
                $req[0]['mandate_id'] = null;
                $req[0]['portfolio_id'] = null;
                $req[0]['product_id'] = null;
            }
            $baseId = $this->Rules->getNextId($req[0]);
            for (; isset($req[$id]); $id++) {
                if ($id == $skip_id) {
                    continue;
                }
                $rule = $this->Rules->newEmptyEntity();
                $rule->user_id = $user_id;
                $req[$id]['template_type'] = $req[0]['template_type'] ?? null;
                $req[$id]['rule_category'] = $req[0]['rule_category'] ?? null;
                $req[$id]['rule_level'] = $req[0]['rule_level'] ?? null;
                $req[$id]['product_id'] = $req[0]['product_id'] ?? null;
                $req[$id]['mandate_id'] = $req[0]['mandate_id'] ?? null;
                $req[$id]['portfolio_id'] = $req[0]['portfolio_id'] ?? null;
                $req[$id]['top_level'] = 'Y';
                $req[$id]['rule_number'] = $baseId['prefix'] . ($baseId['nextval'] + $id - ($id > $skip_id ? 1 : 0));

                $this->Rules->patchEntityRecursively($rule, $req[$id]);
                $rules[$id - ($id > $skip_id ? 1 : 0)] = $rule;
            }
            if ($req['expandField'] == '') {
                if (!$this->Rules->saveRecursively($rules, array($this,'createExcelFile'), array($this, 'callSas'))) {
                    $this->Flash->error(__('The rules could not be saved. Please, try again.'));
                    $this->createEditFieldsDomains($rules, 'add');
                    $this->render('add');
                    return; 
                } else {
                    $this->Flash->success(__('The rules have been saved.'));
					$this->logDams('Add BR: ' . json_encode($this->ruleInfo($rule)), 'dams', 'Add Business Rule');
                    return $this->redirect(['action' => 'index']);
                }
            }
            if ($req['expandField'] == 'add-rule') {
                $rules[$id] = $this->Rules->newEmptyEntity();
                $rules[$id]->rule_number = 'XXX_';
            }
        }
        if (count($rules) == 0) {
            $rules[0] = $this->Rules->newEmptyEntity();
            $rules[0]->rule_number = 'XXX_';
        }
        $this->createEditFieldsDomains($rules, 'add');
    }

    /**
     * Edit method
     *
     * @param string|null $id Rule id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if ($id == null) {
            $id = $this->request->getData()[rule_id];
        }
		$this->loadModel('Damsv2.Rules');
        $rule = $this->Rules->get($id, [
            'contain' => ['Product', 'Mandate', 'Portfolio', 'Truepart', 'Falsepart', 'TemplateType', 'VUser'],
        ]);

		if (($rule->template_type_id == 1) && (!$this->perm->hasWrite(['filter' => 'inclusion'])))
		{
			$this->set('view_only', true);
		}
		elseif (($rule->template_type_id == 2) && (!$this->perm->hasWrite(['filter' => 'payment'])))
		{
			$this->set('view_only', true);
		}
		elseif (($rule->template_type_id == 3) && (!$this->perm->hasWrite(['filter' => 'payment'])))
		{
			$this->set('view_only', true);
		}
		elseif (($rule->rule_category == "CONSISTENCY") && (!$this->perm->hasWrite(['filter' => 'consistency'])))
		{
			$this->set('view_only', true);
		}
		elseif (($rule->rule_category == "ELIGIBILITY") && (!$this->perm->hasWrite(['filter' => 'elegibility'])))
		{
			$this->set('view_only', true);
		}
		else
		{
			$this->set('view_only', false);
		}

		
        $this->Rules->expandRecursively($rule);
        //      var_dump($this->request);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->Rules->patchEntityRecursively($rule, $this->request->getData());
            if ($this->request->getData()['expandField'] == '') {
                $rules = [$rule];
                if ($this->Rules->saveRecursively($rules, array($this,'createExcelFile'), array($this, 'callSas'))) {
                    $this->Flash->success(__('The rule has been saved.'));
					
					$this->logDams('Edit BR: ' . json_encode($this->ruleInfo($rule)), 'dams', 'Edit Business Rule');
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Rules->patchEntityRecursively($rule, $this->request->getData());
                    $this->Flash->error(__('The rule could not be saved. Please, try again.'));
                    $this->createEditFieldsDomains($rule, 'edit');
                    $this->render('edit');
        		    return;
                }
            }
        }
        $this->createEditFieldsDomains($rule, 'edit');
    }

    /**
     * Delete method
     *
     * @param string|null $id Rule id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['delete']);
        $rule = $this->Rules->get($id, ['contain' => ['Truepart', 'Falsepart']]);
        if ($this->Rules->deleteRecursively($rule, array($this,'createExcelFile'), array($this, 'callSas'))) {
			$this->logDams('Delete BR: ' . json_encode($this->ruleInfo($rule)), 'dams', 'Delete Business Rule');
            $this->Flash->success(__('The rule has been deleted.'));
        } else {
            $this->Flash->error(__('The rule could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    public function getDictionaryValues($id = null)
    {
        $ids = explode(",", $id);
        $dictValues = [];
        if (array_count_values($ids)>0) {
            $dictValues = (new \Damsv2\Model\Table\DictionaryValuesTable())
                ->find()
                ->where(['dictionary_id IN' => $ids, 'translation IS' => null])
                ->order(['code'])
                ->all()
                ->toArray();
        }
            //->combine('dicoval_id','code');
        $this->set(compact('dictValues'));
        $this->viewBuilder()->setClassName('Json');
        $this->viewBuilder()
                ->setOption('serialize', 'dictValues');
    }
	
	private function ruleInfo($rule)
	{
		return array('product_id'=>$rule->product_id,
		'mandate_id'=>$rule->mandate_id,
		'portfolio_id'=>$rule->portfolio_id,
		'template_type'=>$rule->template_type_id,
		'type'=>$rule->rule_category,
		'level'=>$rule->rule_level);
	}

}
