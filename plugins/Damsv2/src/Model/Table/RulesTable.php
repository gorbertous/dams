<?php

declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\FactoryLocator;
use Cake\Core\Configure;
use \DateTime;

/**
 * Rules Model
 *
 * @property \App\Model\Table\ProductTable&\Cake\ORM\Association\BelongsTo $Product
 * @property \App\Model\Table\MandateTable&\Cake\ORM\Association\BelongsTo $Mandate
 * @property \App\Model\Table\PortfolioTable&\Cake\ORM\Association\BelongsTo $Portfolio
 * @property \App\Model\Table\RulesTable&\Cake\ORM\Association\BelongsTo $Truepart
 * @property \App\Model\Table\RulesTable&\Cake\ORM\Association\BelongsTo $Falsepart
 * @property \App\Model\Table\TemplateTypesTable&\Cake\ORM\Association\BelongsTo $TemplateType
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $VUser
 *
 * @method \App\Model\Entity\Rule newEmptyEntity()
 * @method \App\Model\Entity\Rule newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Rule[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Rule get($primaryKey, $options = [])
 * @method \App\Model\Entity\Rule findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Rule patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Rule[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Rule|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Rule saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Rule[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Rule[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Rule[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Rule[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RulesTable extends Table
{

    private $functionBatchSave = null;
    private function defaultSave($batchTable) : bool {
        $savePart = array_map(function ($e) { return $e['entity'];}, array_filter($batchTable, function ($v, $k) { return $v['action'] != 'D';}, ARRAY_FILTER_USE_BOTH));        
        $deletePart = array_map(function ($e) { return $e['entity']->rule_id;}, array_filter($batchTable, function ($v, $k) { return $v['action'] == 'D';}, ARRAY_FILTER_USE_BOTH));
        return (count($savePart)>0?$this->saveMany($savePart, ['associated' => []]):true) && (count($deletePart)>0?$this->deleteAll(['rule_id IN' => $deletePart]):true);
    }
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('rules');
        $this->setDisplayField('rule_number');
        $this->setPrimaryKey('rule_id');

        $this->addBehavior('Timestamp');
        //$this->addBehavior('Tree');

        $this->belongsTo('Damsv2.Product', [
            'foreignKey' => 'product_id',
        ]);
        $this->belongsTo('Damsv2.Mandate', [
            'foreignKey' => 'mandate_id',
        ]);
        $this->belongsTo('Damsv2.Portfolio', [
            'foreignKey' => 'portfolio_id',
        ]);
        $this->belongsTo('Truepart', [
            'className' => 'Damsv2.Rules',
            'foreignKey' => ['truepart_id','product_id','mandate_id','portfolio_id'],
            'bindingKey' => ['rule_number','product_id <=>','mandate_id <=>','portfolio_id <=>'],
        ]);
        $this->belongsTo('Falsepart', [
            'className' => 'Damsv2.Rules',
            'foreignKey' => ['falsepart_id','product_id','mandate_id','portfolio_id'],
            'bindingKey' => ['rule_number','product_id <=>','mandate_id <=>','portfolio_id <=>'],
        ]);

        $this->belongsTo('Damsv2.TemplateType', [
            'foreignKey' => 'template_type_id',
            'joinType'   => 'INNER',
        ]);
        $this->belongsTo('Damsv2.VUser', [
            'foreignKey' => 'user_id'
        ]);
        $this->functionBatchSave = array($this, 'defaultSave');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
                ->integer('rule_id')
                ->allowEmptyString('rule_id', null, 'create');

        $validator
                ->scalar('rule_number')
                ->maxLength('rule_number', 45)
                ->allowEmptyString('rule_number');

        $validator
                ->scalar('rule_category')
                ->maxLength('rule_category', 20)
                ->requirePresence('rule_category', 'create')
                ->notEmptyString('rule_category');

        $validator
                ->scalar('rule_level')
                ->maxLength('rule_level', 12)
                ->notEmptyString('rule_level');

        $validator
                ->scalar('is_warning')
                ->maxLength('is_warning', 1)
                ->allowEmptyString('is_warning');

        $validator
                ->scalar('inclusion_and_edit')
                ->maxLength('inclusion_and_edit', 1)
                ->notEmptyString('inclusion_and_edit');

        $validator
                ->scalar('rule_name')
                ->maxLength('rule_name', 45)
                ->allowEmptyString('rule_name');

        $validator
                ->scalar('top_level')
                ->maxLength('top_level', 1)
                ->allowEmptyString('top_level');

        $validator
                ->scalar('rule_type')
                ->maxLength('rule_type', 1)
                ->allowEmptyString('rule_type');

        $validator
                ->scalar('checked_entity')
                ->maxLength('checked_entity', 100)
                ->allowEmptyString('checked_entity');

        $validator
                ->scalar('checked_field')
                ->maxLength('checked_field', 100)
                ->allowEmptyString('checked_field');

        $validator
                ->scalar('operator')
                ->maxLength('operator', 11)
                ->notEmptyString('operator');

        $validator
                ->scalar('param_1_value')
                ->maxLength('param_1_value', 1000)
                ->allowEmptyString('param_1_value');

        $validator
                ->scalar('param_2_value')
                ->maxLength('param_2_value', 1000)
                ->allowEmptyString('param_2_value');

        $validator
                ->scalar('description')
                ->allowEmptyString('description');

        $validator
                ->integer('version_number')
                ->allowEmptyString('version_number');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['product_id'], 'Product', ['allowNullableNulls' => true]), ['errorField' => 'product_id']);
        $rules->add($rules->existsIn(['mandate_id'], 'Mandate', ['allowNullableNulls' => true]), ['errorField' => 'mandate_id']);
        $rules->add($rules->existsIn(['portfolio_id'], 'Portfolio', ['allowNullableNulls' => true]), ['errorField' => 'portfolio_id']);
        //$rules->add($rules->existsIn(['rule_number'], 'Rules'), ['errorField' => 'truepart_id']);
        //$rules->add($rules->existsIn(['rule_number'], 'Rules'), ['errorField' => 'falsepart_id']);
        $rules->add($rules->existsIn(['template_type_id'], 'TemplateType'), ['errorField' => 'template_type_id']);
        $rules->add($rules->existsIn(['user_id'], 'VUser'), ['errorField' => 'user_id']);

        return $rules;
    }

    public function brulesValid($report)
    {
        $template_type_id = !empty($report->template->template_type_id) ? $report->template->template_type_id : $report['Template']['template_type_id'];
        $portfolio_id = !empty($report->portfolio_id) ? $report->portfolio_id : $report['Report']['portfolio_id'];
        $product_id = !empty($report->portfolio->product_id) ? $report->portfolio->product_id : $report['Portfolio']['product_id'];
        $mandate_name = !empty($report->portfolio->mandate) ?  $report->portfolio->mandate : $report['Portfolio']['mandate'];
       
        $counter = 0;
        $brules_consistency_portfolio = $this->find(
                        'all', [
                    'conditions' => ['Rules.portfolio_id'     => $portfolio_id,
                        'Rules.rule_level'       => 'PORTFOLIO',
                        'Rules.template_type_id' => $template_type_id,
                        'Rules.rule_category'    => 'CONSISTENCY',
                    ],
                    'fields'     => ['Rules.rule_id'],
                    'recursive'  => 0
                ])->first();
        $counter = !(empty($brules_consistency_portfolio)) ? $counter + 1 : $counter;
        $brules_consistency_product = $this->find(
                        'all', [
                    'conditions' => ['Rules.product_id'       => $product_id,
                        'Rules.rule_level'       => 'PRODUCT',
                        'Rules.template_type_id' => $template_type_id,
                        'Rules.rule_category'    => 'CONSISTENCY',
                    ],
                    'fields'     => ['Rules.rule_id'],
                    'recursive'  => 0
                ])->first();
        $counter = !(empty($brules_consistency_product)) ? $counter + 1 : $counter;
        
        $Mandate = FactoryLocator::get('Table')->get('Damsv2.Mandate');
        $mandate_ids = $Mandate->find('all', [
                    'conditions' => ['Mandate.mandate_name' => $mandate_name],
                    'fields'     => ['mandate_id'],
                ])->first();

        $brules_consistency_mandate = $this->find(
                        'all', [
                    'conditions' => ['Rules.mandate_id'       => !empty($mandate_ids) ? $mandate_ids->mandate_id : null,
                        'Rules.rule_level'       => 'MANDATE',
                        'Rules.template_type_id' => $template_type_id,
                        'Rules.rule_category'    => 'CONSISTENCY',
                    ],
                    'fields'     => ['Rules.rule_id'],
                    'recursive'  => 0
                        ]//   
                )->first();
        $counter = !(empty($brules_consistency_mandate)) ? $counter + 1 : $counter;
        $brules_consistency_transversal = $this->find(
                        'all', [
                    'conditions' => [
                        'Rules.rule_level'       => 'TRANSVERSAL',
                        'Rules.template_type_id' => $template_type_id,
                        'Rules.rule_category'    => 'CONSISTENCY',
                    ],
                    'fields'     => ['Rules.rule_id'],
                    'recursive'  => 0
                        ]//   
                )->first();
        $counter = !(empty($brules_consistency_transversal)) ? $counter + 1 : $counter;
        $brules_consistency = $counter;
        //$brules_consistency = $brules_consistency_portfolio + $brules_consistency_product + $brules_consistency_mandate + $brules_consistency_transversal;

        //  'Rules.rule_level' => 'PORTFOLIO',  'Rules.rule_category' => 'ELIGIBILITY'
        $brules_eligibility = $this->find(
                        'all', [
                    'conditions' => ['Rules.portfolio_id'     => $portfolio_id,
                        'Rules.template_type_id' => $template_type_id,
                        'Rules.rule_level'       => 'PORTFOLIO',
                        'Rules.rule_category'    => 'ELIGIBILITY'], // checking business rules type inclusion
                    'fields'     => ['Rules.rule_id'],
                    'recursive'  => 0
                        ]
                )->first();
       

        //$brules_valid = ((count($brules_consistency) >= 1) && (count($brules_eligibility) >= 1));
        $brules_valid = (($brules_consistency >= 1) && !(empty($brules_eligibility)));
       
        error_log("business rules at import : report " . json_encode($report));
        error_log("business rules at import : brules_valid " . json_encode($brules_valid));
        error_log("business rules at import : brules_eligibility " . json_encode($brules_eligibility));
        error_log("business rules at import : brules_consistency " . json_encode($brules_consistency));
        error_log("business rules at import : brules_consistency_transversal " . json_encode($brules_consistency_transversal));
        error_log("business rules at import : brules_consistency_mandate " . json_encode($brules_consistency_mandate));
        error_log("business rules at import : brules_consistency_product " . json_encode($brules_consistency_product));
        error_log("business rules at import : brules_consistency_portfolio " . json_encode($brules_consistency_portfolio));

        return $brules_valid;
    }

    public function brulesValidPdlr($report)
    {
        $counter = 0;
        $template_type_id = !empty($report->template->template_type_id) ? $report->template->template_type_id : $report['Template']['template_type_id'];
        $portfolio_id = !empty($report->portfolio_id) ? $report->portfolio_id : $report['Report']['portfolio_id'];
        $product_id = !empty($report->portfolio->product_id) ? $report->portfolio->product_id : $report['Portfolio']['product_id'];
        $mandate_name = !empty($report->portfolio->mandate) ?  $report->portfolio->mandate : $report['Portfolio']['mandate'];
        
        $brules_consistency_portfolio = $this->find(
                        'all', [
                    'conditions' => ['Rules.portfolio_id'     => $portfolio_id,
                        'Rules.rule_level'       => 'PORTFOLIO',
                        'Rules.template_type_id' => $template_type_id,
                        'Rules.rule_category'    => 'CONSISTENCY',
                    ],
                    'fields'     => ['Rules.rule_id'],
                    'recursive'  => 0
                        ]//   
                )->first();
        $counter = !(empty($brules_consistency_portfolio)) ? $counter + 1 : $counter;
        $brules_consistency_product = $this->find(
                        'all', [
                    'conditions' => ['Rules.product_id'       => $product_id,
                        'Rules.rule_level'       => 'PRODUCT',
                        'Rules.template_type_id' => $template_type_id,
                        'Rules.rule_category'    => 'CONSISTENCY',
                    ],
                    'fields'     => ['Rules.rule_id'],
                    'recursive'  => 0
                        ]//   
                )->first();
        $counter = !(empty($brules_consistency_product)) ? $counter + 1 : $counter;
        
        $Mandate = FactoryLocator::get('Table')->get('Damsv2.Mandate');
        $mandate_ids = $Mandate->find('all', [
                    'conditions' => ['Mandate.mandate_name' => $mandate_name],
                    'fields'     => ['mandate_id'],
                ])->first();

        $brules_consistency_mandate = $this->find(
                        'all', [
                    'conditions' => ['Rules.mandate_id'       => !empty($mandate_ids) ? $mandate_ids->mandate_id : null,
                        'Rules.rule_level'       => 'MANDATE',
                        'Rules.template_type_id' => $template_type_id,
                        'Rules.rule_category'    => 'CONSISTENCY',
                    ],
                    'fields'     => ['Rules.rule_id'],
                    'recursive'  => 0
                        ]//   
                )->first();
        $counter = !(empty($brules_consistency_mandate)) ? $counter + 1 : $counter;
        $brules_consistency_transversal = $this->find(
                        'all', [
                    'conditions' => [
                        'Rules.rule_level'       => 'TRANSVERSAL',
                        'Rules.template_type_id' => $template_type_id,
                        'Rules.rule_category'    => 'CONSISTENCY',
                    ],
                    'fields'     => ['Rules.rule_id'],
                    'recursive'  => 0
                        ]//   
                )->first();
        $counter = !(empty($brules_consistency_transversal)) ? $counter + 1 : $counter;
        //$brules_consistency = $brules_consistency_portfolio + $brules_consistency_product + $brules_consistency_mandate + $brules_consistency_transversal;
        $brules_consistency = $counter;
        $brules_valid = (count($brules_consistency) >= 1);

        return $brules_valid;
    }
    public function patchEntityRecursively(&$rule, array $data) {
        $rule->rule_number = $data['rule_number'];         
        $rule->rule_name = $data['rule_name'];
        $rule->template_type_id = $data['template_type'] ?? null;
        $rule->rule_category = $data['rule_category'] ?? null;
        $rule->rule_level = $data['rule_level'] ?? null;
        $rule->product_id = $data['product_id'] ?? null;
        $rule->mandate_id = $data['mandate_id'] ?? null;
        $rule->portfolio_id = $data['portfolio_id'] ?? null;
        $rule->datatype = $data['datatype'] ?? 'text';
        $rule->product_id = $rule->product_id?$rule->product_id:null;
        $rule->mandate_id = $rule->mandate_id?$rule->mandate_id:null;
        $rule->portfolio_id = $rule->portfolio_id?$rule->portfolio_id:null;
        $rule->is_warning = $data['is_warning'];
        $rule->inclusion_and_edit = $data['inclusion_and_edit'];
        $rule->checked_entity = $data['checked_entity'];
        $rule->checked_field = $data['checked_field'];
        $rule->operator = $data['operator'];
        if (isset($data['datatype']) && $data['datatype']=="date") {
            $d1 = DateTime::createFromFormat('d/m/Y', $data['param_1_value']);
            $d2 = DateTime::createFromFormat('d/m/Y', $data['param_2_value']);
            if(!$d1) {
                $d1 = DateTime::createFromFormat('Y-m-d', $data['param_1_value']);
            }
            if(!$d2) {
                $d2 = DateTime::createFromFormat('Y-m-d', $data['param_2_value']);    
            }
            $epoch = new DateTime('1960-01-01');
            $rule->param_1_value = $d1?$epoch->diff($d1)->days:$data['param_1_value'];
            $rule->param_2_value = $d2?$epoch->diff($d2)->days:$data['param_2_value'];
        } else {
            $rule->param_1_value = $data['param_1_value'];
            $rule->param_2_value = $data['param_2_value'];
        }
        $rule->truepart_id = $data['truepart_id'] ?? null;
        $rule->falsepart_id = $data['falsepart_id'] ?? null;
        $rule->description = $data['description'];
        $rule->rule_type = ($rule->truepart_id || $rule->falsepart_id)?'C':'S';
        $rule->top_level = $data['top_level'] ?? ($rule->top_level ?? 'N');
        
        if (!empty($rule->truepart_id)) {
            $ruleTrue = $data['truepart'] ?? [];
            if (empty($rule->truepart)) {
                $rule->truepart = $this->newEmptyEntity();
                $ruleTrue['checked_entity'] = $ruleTrue['checked_entity'] ?? '';
                $ruleTrue['checked_field'] = $ruleTrue['checked_field'] ?? '';
                $ruleTrue['operator'] = $ruleTrue['operator'] ?? '';
                $ruleTrue['param_1_value'] = $ruleTrue['param_1_value'] ?? '';
                $ruleTrue['param_2_value'] = $ruleTrue['param_2_value'] ?? '';
                $ruleTrue['description'] = $ruleTrue['description'] ?? '';
            }
            $rule->truepart->rule_id = !empty($rule->rule_id)?$rule->truepart->rule_id:null; 
            $ruleTrue['rule_number'] = $rule->rule_number . 'T';  
            $rule->truepart_id =  $ruleTrue['rule_number'];      
            $ruleTrue['rule_name'] = $rule->rule_name;         
            $ruleTrue['rule_category'] = $rule->rule_category;
            $ruleTrue['rule_level'] = $rule->rule_level;
            $ruleTrue['product_id'] = $rule->product_id;
            $ruleTrue['mandate_id'] = $rule->mandate_id;
            $ruleTrue['portfolio_id'] = $rule->portfolio_id;
            $ruleTrue['is_warning'] = $rule->is_warning;
            $ruleTrue['inclusion_and_edit'] = $rule->inclusion_and_edit;
            $ruleTrue['template_type'] = $rule->template_type_id;
            $rule->truepart->user_id = $rule->user_id;
            $this->patchEntityRecursively($rule->truepart, $ruleTrue);
        }
        if (!empty($rule->falsepart_id)) {
            $ruleFalse = $data['falsepart'] ?? [];
            if (empty($rule->falsepart)) {
                $rule->falsepart = $this->newEmptyEntity();
                $ruleFalse['checked_entity'] = $ruleFalse['checked_entity'] ?? '';
                $ruleFalse['checked_field'] = $ruleFalse['checked_field'] ?? '';
                $ruleFalse['operator'] = $ruleFalse['operator'] ?? '';
                $ruleFalse['param_1_value'] = $ruleFalse['param_1_value'] ?? '';
                $ruleFalse['param_2_value'] = $ruleFalse['param_2_value'] ?? '';
                $ruleFalse['description'] = $ruleFalse['description'] ?? '';      
            }
            $rule->falsepart->rule_id = !empty($rule->rule_id)?$rule->falsepart->rule_id:null; 
            $ruleFalse['rule_number'] = $rule->rule_number . 'F';         
            $rule->falsepart_id =  $ruleFalse['rule_number'];      
            $ruleFalse['rule_name'] = $rule->rule_name;         
            $ruleFalse['rule_category'] = $rule->rule_category;
            $ruleFalse['rule_level'] = $rule->rule_level;
            $ruleFalse['product_id'] = $rule->product_id;
            $ruleFalse['mandate_id'] = $rule->mandate_id;
            $ruleFalse['portfolio_id'] = $rule->portfolio_id;
            $ruleFalse['is_warning'] = $rule->is_warning;
            $ruleFalse['inclusion_and_edit'] = $rule->inclusion_and_edit;
            $ruleFalse['template_type'] = $rule->template_type_id;
            $rule->falsepart->user_id = $rule->user_id;
            $this->patchEntityRecursively($rule->falsepart, $ruleFalse);
        }
    } 
    public function expandRecursively(&$rule) {
        if (!empty($rule->truepart)) { 
            $ruleT = $this->get($rule->truepart->rule_id, ['contain'=>['Truepart','Falsepart']]);
            $this->expandRecursively($ruleT);
            $rule->truepart = $ruleT;
        }
        if (!empty($rule->falsepart)) { 
            $ruleF = $this->get($rule->falsepart->rule_id, ['contain'=>['Truepart','Falsepart']]);
            $this->expandRecursively($ruleF);
            $rule->falsepart = $ruleF;
        }
        $rule->clean();
        return true;
    }
    // private function createExcelFile($rules)
    // private function callSas($rules, $filepath, $csv_file) {

    public function saveRecursively(&$rules, $createExcelFile, $callSas) {
        $batchTable = [];
        $ret = true;
        foreach ($rules as $rule) {
            $ret &= $this->saveRecursivelyData($rule, $batchTable);
        }        
        if ($ret) {
            $filepath = $createExcelFile($batchTable);
            if (Configure::read('Development')) {
                $ret = ($this->functionBatchSave)($batchTable);
            } else {
                $ret = $callSas($batchTable, $filepath);
            }
        }
        return $ret;
    }
    public function deleteRecursively(&$rule, $createExcelFile, $callSas) {
        $batchTable = [];
        $ret = $this->deleteRecursivelyData($rule, $batchTable);
        if ($ret) {
            $filepath = $createExcelFile($batchTable);
            if (Configure::read('Development')) {
                $ret = ($this->functionBatchSave)($batchTable);
            } else {
                $ret = $callSas($batchTable, $filepath);
            }
        }
        return $ret;
    }
    protected function _save(\Cake\Datasource\EntityInterface $rule, $options = [], &$batchTable): bool {
        if (isset($options['batch'])) {
            $entity = $this->newEntity($rule->toArray());
            $entity->rule_id = $rule->rule_id;
            $entity->datatype = $rule->datatype;
            foreach($rule->getDirty() as $field) {
                $entity->setDirty($field, true);
            }
            $entity->setNew($entity->rule_id == null || $entity->rule_id == '');
            $batchTable[] = ['entity' => $entity, 'action' => $entity->isNew()?'I':'U'];
            return true;
        } else {
            return $this->save($rule, $options);
        }
    }
    protected function _delete(\Cake\Datasource\EntityInterface $rule, $options = [], &$batchTable): bool {
        if (isset($options['batch'])) {
            $entity = $this->newEntity($rule->toArray());
            $entity->rule_id = $rule->rule_id;
            $entity->datatype = $rule->datatype;
            $entity->setNew(false);
            $batchTable[] = ['entity' => $entity, 'action' => 'D'];
            return true;
        } else {
            return $this->delete($rule, $options);
        }
    }
    private function saveRecursivelyData(&$rule, &$batchTable) {
        $recursion = (!empty($rule->truepart) ?  !empty($rule->truepart_id) ? $this->saveRecursivelyData($rule->truepart, $batchTable) : $this->deleteRecursivelyData($rule->truepart, $batchTable) : true) && 
            (!empty($rule->falsepart) ? !empty($rule->falsepart_id) ? $this->saveRecursivelyData($rule->falsepart, $batchTable) : $this->deleteRecursivelyData($rule->falsepart, $batchTable)  : true);
        if ($recursion) {
            $result = $this->_save($rule, ['atomic' => false, 'batch' => true],$batchTable);
            return $result;
        }
        return false;
    }

    private function deleteRecursivelyData(&$rule, &$batchTable) {
        if (!$rule || !$rule->rule_id) return true;
        $ruleT = (!empty($rule->truepart) && $rule->truepart->rule_id)?$this->get($rule->truepart->rule_id, ['contain'=>['Truepart','Falsepart']]):null;
        $ruleF = (!empty($rule->falsepart) && $rule->falsepart->rule_id)?$this->get($rule->falsepart->rule_id, ['contain'=>['Truepart','Falsepart']]):null;
        return $this->_delete($rule, ["atomic" => false, 'batch' => true], $batchTable) &&
        (!empty($ruleT)?$this->deleteRecursivelyData($ruleT, $batchTable):true) &&
        (!empty($ruleF)?$this->deleteRecursivelyData($ruleF, $batchTable):true);
    }

    public function getNextId($data) {
        $prefix = RulesTable::getPrefix($data['rule_category'], $data['rule_level']);
        $conditions = ['rule_category' => $data['rule_category'], 
                       'rule_level' => $data['rule_level']];
        if ($data['product_id']) {
            $conditions['product_id'] = $data['product_id']; 
        }
        if ($data['mandate_id']) {
            $conditions['mandate_id'] = $data['mandate_id']; 
        }
        if ($data['portfolio_id']) {
            $conditions['portfolio_id'] = $data['portfolio_id']; 
        }
        $lastRule = $this->find()->select(['nextval' => 'cast(replace(replace(replace(rule_number,\''.$prefix.'\',\'\'),\'T\',\'\'),\'F\',\'\') as signed)'])
                                 ->where($conditions)
                                 ->order(['nextval' => 'desc'])
                                 ->first();
        $nextval = 0;
        if ($lastRule) {
            $nextval = $lastRule->toArray()['nextval'];
        }
        $nextval++;
        return array('prefix' => $prefix, 'nextval' => $nextval);
    }

    public static function getPrefix($category, $level) {
        switch ($level) {
            case "PRODUCT":
                $part1 = 'PRD_';
                break;
            case "PORTFOLIO":
                $part1 = 'PTF_';
                break;
            case "MANDATE":
                $part1 = 'MD_';
                break;
            case "TRANSVERSAL":
                $part1 = 'DQ_';
                break;
            default:
                $part1 = 'XXX_';
        }
        switch ($category) {
            case "ELIGIBILITY":
                $part2 = 'E_';
                break;
            case "CONSISTENCY":
                $part2 = 'C_';
                break;
            default:
                $part2 = 'X_';
        }
        return $part1 . $part2;
    }

}
