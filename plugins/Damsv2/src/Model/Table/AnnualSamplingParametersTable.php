<?php
declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AnnualSamplingParameters Model
 *
 * @method \App\Model\Entity\AnnualSamplingParameter newEmptyEntity()
 * @method \App\Model\Entity\AnnualSamplingParameter newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\AnnualSamplingParameter[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AnnualSamplingParameter get($primaryKey, $options = [])
 * @method \App\Model\Entity\AnnualSamplingParameter findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\AnnualSamplingParameter patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AnnualSamplingParameter[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\AnnualSamplingParameter|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AnnualSamplingParameter saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AnnualSamplingParameter[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AnnualSamplingParameter[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\AnnualSamplingParameter[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AnnualSamplingParameter[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AnnualSamplingParametersTable extends Table
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

        $this->setTable('annual_sampling_parameters');
        $this->setDisplayField('sample_year_id');
        $this->setPrimaryKey('sample_year_id');

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
            ->integer('sample_year_id')
            ->allowEmptyString('sample_year_id', null, 'create');

        $validator
            ->integer('sampling_year')
            ->requirePresence('sampling_year', 'create')
            ->notEmptyString('sampling_year');

        $validator
            ->integer('last_sampled_month')
            ->allowEmptyString('last_sampled_month');

        $validator
            ->numeric('expected_payments_eur')
            ->requirePresence('expected_payments_eur', 'create')
            ->notEmptyString('expected_payments_eur');

        $validator
            ->integer('number_of_samples')
            ->requirePresence('number_of_samples', 'create')
            ->notEmptyString('number_of_samples');

        $validator
            ->numeric('sampling_interval_eur')
            ->requirePresence('sampling_interval_eur', 'create')
            ->notEmptyString('sampling_interval_eur');

        $validator
            ->scalar('user')
            ->maxLength('user', 255)
            ->allowEmptyString('user');

        return $validator;
    }
}
