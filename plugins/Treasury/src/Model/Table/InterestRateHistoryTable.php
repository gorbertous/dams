<?php
declare(strict_types=1);

namespace Treasury\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * InterestRateHistory Model
 *
 * @method \Treasury\Model\Entity\InterestRateHistory newEmptyEntity()
 * @method \Treasury\Model\Entity\InterestRateHistory newEntity(array $data, array $options = [])
 * @method \Treasury\Model\Entity\InterestRateHistory[] newEntities(array $data, array $options = [])
 * @method \Treasury\Model\Entity\InterestRateHistory get($primaryKey, $options = [])
 * @method \Treasury\Model\Entity\InterestRateHistory findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Treasury\Model\Entity\InterestRateHistory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Treasury\Model\Entity\InterestRateHistory[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Treasury\Model\Entity\InterestRateHistory|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\InterestRateHistory saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\InterestRateHistory[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\InterestRateHistory[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\InterestRateHistory[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\InterestRateHistory[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class InterestRateHistoryTable extends Table
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

        $this->setTable('interest_rate_history');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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
            ->integer('trn_number')
            ->requirePresence('trn_number', 'create')
            ->notEmptyString('trn_number');

        $validator
            ->date('interest_rate_from')
            ->requirePresence('interest_rate_from', 'create')
            ->notEmptyDate('interest_rate_from');

        $validator
            ->date('interest_rate_to')
            ->allowEmptyDate('interest_rate_to');

        $validator
            ->decimal('interest_rate')
            ->allowEmptyString('interest_rate');

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
