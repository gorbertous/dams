<?php
declare(strict_types=1);

namespace Treasury\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CalculatedLimits Model
 *
 * @method \Treasury\Model\Entity\CalculatedLimit newEmptyEntity()
 * @method \Treasury\Model\Entity\CalculatedLimit newEntity(array $data, array $options = [])
 * @method \Treasury\Model\Entity\CalculatedLimit[] newEntities(array $data, array $options = [])
 * @method \Treasury\Model\Entity\CalculatedLimit get($primaryKey, $options = [])
 * @method \Treasury\Model\Entity\CalculatedLimit findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Treasury\Model\Entity\CalculatedLimit patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Treasury\Model\Entity\CalculatedLimit[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Treasury\Model\Entity\CalculatedLimit|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\CalculatedLimit saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\CalculatedLimit[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\CalculatedLimit[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\CalculatedLimit[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\CalculatedLimit[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CalculatedLimitsTable extends Table
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

        $this->setTable('calculated_limits');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->integer('mandategroup_ID')
            ->requirePresence('mandategroup_ID', 'create')
            ->notEmptyString('mandategroup_ID');

        $validator
            ->integer('cpty_ID')
            ->requirePresence('cpty_ID', 'create')
            ->notEmptyString('cpty_ID');

        $validator
            ->scalar('pirat_number')
            ->maxLength('pirat_number', 128)
            ->allowEmptyString('pirat_number');

        $validator
            ->scalar('LT-R')
            ->maxLength('LT-R', 10)
            ->allowEmptyString('LT-R');

        $validator
            ->date('LT-R_date')
            ->allowEmptyDate('LT-R_date');

        $validator
            ->scalar('ST-R')
            ->maxLength('ST-R', 10)
            ->allowEmptyString('ST-R');

        $validator
            ->date('ST-R_date')
            ->allowEmptyDate('ST-R_date');

        $validator
            ->boolean('eligibility')
            ->notEmptyString('eligibility');

        $validator
            ->decimal('calculated_limit')
            ->notEmptyString('calculated_limit');

        $validator
            ->integer('calculated_max_maturity')
            ->notEmptyString('calculated_max_maturity');

        $validator
            ->decimal('calculated_max_concentration')
            ->allowEmptyString('calculated_max_concentration');

        $validator
            ->scalar('concentration_limit_unit')
            ->maxLength('concentration_limit_unit', 3)
            ->allowEmptyString('concentration_limit_unit');

        $validator
            ->dateTime('effective_date')
            ->allowEmptyDateTime('effective_date');

        return $validator;
    }

    /**
     * Returns the database connection name to use by default.
     *
     * @return string
     */
    public static function defaultConnectionName(): string
    {
        return 'treasury';
    }
}
