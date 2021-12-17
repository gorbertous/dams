<?php
declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BrParameter Model
 *
 * @property \App\Model\Table\TemplateTypesTable&\Cake\ORM\Association\BelongsTo $TemplateTypes
 * @property \App\Model\Table\ProductsTable&\Cake\ORM\Association\BelongsTo $Products
 * @property \App\Model\Table\MandatesTable&\Cake\ORM\Association\BelongsTo $Mandates
 * @property \App\Model\Table\PortfoliosTable&\Cake\ORM\Association\BelongsTo $Portfolios
 * @property \App\Model\Table\DictionariesTable&\Cake\ORM\Association\BelongsTo $Dictionaries
 *
 * @method \App\Model\Entity\BrParameter newEmptyEntity()
 * @method \App\Model\Entity\BrParameter newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\BrParameter[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BrParameter get($primaryKey, $options = [])
 * @method \App\Model\Entity\BrParameter findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\BrParameter patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\BrParameter[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\BrParameter|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BrParameter saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BrParameter[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\BrParameter[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\BrParameter[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\BrParameter[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BrParameterTable extends Table
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

        $this->addBehavior('Timestamp');

        $this->belongsTo('Damsv2.TemplateTypes', [
            'foreignKey' => 'template_type_id',
        ]);
        $this->belongsTo('Damsv2.Products', [
            'foreignKey' => 'product_id',
        ]);
        $this->belongsTo('Damsv2.Mandates', [
            'foreignKey' => 'mandate_id',
        ]);
        $this->belongsTo('Damsv2.Portfolios', [
            'foreignKey' => 'portfolio_id',
        ]);
        $this->belongsTo('Damsv2.Dictionaries', [
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
            ->maxLength('checked_entity', 45)
            ->allowEmptyString('checked_entity');

        $validator
            ->scalar('checked_field')
            ->maxLength('checked_field', 60)
            ->allowEmptyString('checked_field');

        $validator
            ->scalar('datatype')
            ->maxLength('datatype', 100)
            ->allowEmptyString('datatype');

        $validator
            ->integer('is_cf')
            ->allowEmptyString('is_cf');

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
        $rules->add($rules->existsIn(['template_type_id'], 'TemplateType'), ['errorField' => 'template_type_id']);
        $rules->add($rules->existsIn(['product_id'], 'Product'), ['errorField' => 'product_id']);
        $rules->add($rules->existsIn(['mandate_id'], 'Mandate'), ['errorField' => 'mandate_id']);
        $rules->add($rules->existsIn(['portfolio_id'], 'Portfolio'), ['errorField' => 'portfolio_id']);
        $rules->add($rules->existsIn(['dictionary_id'], 'Dictionary'), ['errorField' => 'dictionary_id']);

        return $rules;
    }
}
