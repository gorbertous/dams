<?php

declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\FactoryLocator;
use \DateTime;

/**
 * Rules Model
 *
 * @property \App\Model\Table\ProductTable&\Cake\ORM\Association\BelongsTo $Product
 * @property \App\Model\Table\MandateTable&\Cake\ORM\Association\BelongsTo $Mandate
 * @property \App\Model\Table\PortfolioTable&\Cake\ORM\Association\BelongsTo $Portfolio
 * @property \App\Model\Table\TemplateTypesTable&\Cake\ORM\Association\BelongsTo $TemplateType
 * @property \App\Model\Table\DictionaryTable&\Cake\ORM\Association\BelongsTo $Dictionary
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
class RuleParametersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('br_parameter');
        $this->setDisplayField(['checked_entity', 'checked_field']);
        $this->setPrimaryKey(['template_type_id', 'product_id', 'mandate_id', 'portfolio_id']);

        $this->addBehavior('Timestamp');

        $this->belongsTo('Damsv2.Product', [
            'foreignKey' => 'product_id',
        ]);
        $this->belongsTo('Damsv2.Mandate', [
            'foreignKey' => 'mandate_id',
        ]);
        $this->belongsTo('Damsv2.Portfolio', [
            'foreignKey' => 'portfolio_id',
        ]);
        $this->belongsTo('Damsv2.TemplateType', [
            'foreignKey' => 'template_type_id',
            'joinType'   => 'INNER',
        ]);
        $this->belongsTo('Damsv2.Dictionary', [
            'foreignKey' => 'dictionary_id',
        ]);
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
                ->scalar('checked_entity')
                ->maxLength('checked_entity', 100)
                ->notEmptyString('checked_entity');

        $validator
                ->scalar('checked_field')
                ->maxLength('checked_field', 100)
                ->notEmptyString('checked_field');


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
        $rules->add($rules->existsIn(['template_type_id'], 'TemplateType'), ['errorField' => 'template_type_id']);
        $rules->add($rules->existsIn(['dictionary_id'], 'Dictionary',  ['allowNullableNulls' => true]), ['errorField' => 'dictionary_id']);

        return $rules;
    }

}
