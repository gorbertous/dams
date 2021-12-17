<?php
declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SamplingEvaluation Model
 *
 * @method \App\Model\Entity\SamplingEvaluation newEmptyEntity()
 * @method \App\Model\Entity\SamplingEvaluation newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\SamplingEvaluation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SamplingEvaluation get($primaryKey, $options = [])
 * @method \App\Model\Entity\SamplingEvaluation findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\SamplingEvaluation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SamplingEvaluation[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\SamplingEvaluation|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SamplingEvaluation saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SamplingEvaluation[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SamplingEvaluation[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\SamplingEvaluation[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SamplingEvaluation[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SamplingEvaluationTable extends Table
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

        $this->setTable('sampling_evaluation');
        $this->setDisplayField('evaluation_id');
        $this->setPrimaryKey('evaluation_id');

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
            ->integer('evaluation_id')
            ->allowEmptyString('evaluation_id', null, 'create');

        $validator
            ->integer('evaluation_year')
            ->requirePresence('evaluation_year', 'create')
            ->notEmptyString('evaluation_year');

        $validator
            ->numeric('value_pds')
            ->requirePresence('value_pds', 'create')
            ->notEmptyString('value_pds');

        $validator
            ->numeric('value_pds_sampled')
            ->requirePresence('value_pds_sampled', 'create')
            ->notEmptyString('value_pds_sampled');

        $validator
            ->integer('nb_pds_sampled')
            ->requirePresence('nb_pds_sampled', 'create')
            ->notEmptyString('nb_pds_sampled');

        $validator
            ->integer('nb_hv_sampled')
            ->requirePresence('nb_hv_sampled', 'create')
            ->notEmptyString('nb_hv_sampled');

        $validator
            ->integer('nb_lv_sampled')
            ->requirePresence('nb_lv_sampled', 'create')
            ->notEmptyString('nb_lv_sampled');

        $validator
            ->numeric('value_hv')
            ->requirePresence('value_hv', 'create')
            ->notEmptyString('value_hv');

        $validator
            ->numeric('overstatements_hv')
            ->requirePresence('overstatements_hv', 'create')
            ->notEmptyString('overstatements_hv');

        $validator
            ->numeric('materiality_threshold')
            ->requirePresence('materiality_threshold', 'create')
            ->notEmptyString('materiality_threshold');

        $validator
            ->numeric('materiality_threshold_eur')
            ->requirePresence('materiality_threshold_eur', 'create')
            ->notEmptyString('materiality_threshold_eur');

        $validator
            ->numeric('res_materiality_threshold')
            ->requirePresence('res_materiality_threshold', 'create')
            ->notEmptyString('res_materiality_threshold');

        $validator
            ->numeric('average_taint_lv')
            ->requirePresence('average_taint_lv', 'create')
            ->notEmptyString('average_taint_lv');

        $validator
            ->numeric('confidence_no_overstate')
            ->requirePresence('confidence_no_overstate', 'create')
            ->notEmptyString('confidence_no_overstate');

        $validator
            ->numeric('probability_overstate')
            ->requirePresence('probability_overstate', 'create')
            ->notEmptyString('probability_overstate');

        $validator
            ->scalar('user')
            ->maxLength('user', 255)
            ->allowEmptyString('user');

        return $validator;
    }
}
