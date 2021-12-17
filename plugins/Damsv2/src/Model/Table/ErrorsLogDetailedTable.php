<?php
declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ErrorsLogDetailed Model
 *
 * @property \App\Model\Table\ErrorsLogTable&\Cake\ORM\Association\BelongsTo $ErrorsLog
 *
 * @method \App\Model\Entity\ErrorsLogDetailed newEmptyEntity()
 * @method \App\Model\Entity\ErrorsLogDetailed newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\ErrorsLogDetailed[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ErrorsLogDetailed get($primaryKey, $options = [])
 * @method \App\Model\Entity\ErrorsLogDetailed findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\ErrorsLogDetailed patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ErrorsLogDetailed[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ErrorsLogDetailed|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ErrorsLogDetailed saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ErrorsLogDetailed[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ErrorsLogDetailed[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\ErrorsLogDetailed[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ErrorsLogDetailed[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ErrorsLogDetailedTable extends Table
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

        $this->setTable('errors_log_detailed');
        $this->setDisplayField('error_detail_id');
        $this->setPrimaryKey('error_detail_id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Damsv2.ErrorsLog', [
            'foreignKey' => 'error_id',
            'joinType' => 'INNER',
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
            ->integer('error_detail_id')
            ->allowEmptyString('error_detail_id', null, 'create')
            ->add('error_detail_id', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('sheet')
            ->maxLength('sheet', 6)
            ->requirePresence('sheet', 'create')
            ->notEmptyString('sheet');

        $validator
            ->integer('lines')
            ->allowEmptyString('lines');

        $validator
            ->scalar('file_formats')
            ->maxLength('file_formats', 3)
            ->allowEmptyFile('file_formats');

        $validator
            ->integer('formats')
            ->allowEmptyString('formats');

        $validator
            ->integer('dictionaries')
            ->allowEmptyString('dictionaries');

        $validator
            ->integer('integrities')
            ->allowEmptyString('integrities');

        $validator
            ->integer('business_rules')
            ->allowEmptyString('business_rules');

        $validator
            ->integer('warnings')
            ->allowEmptyString('warnings');

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
        $rules->add($rules->isUnique(['error_detail_id']), ['errorField' => 'error_detail_id']);
        $rules->add($rules->existsIn(['error_id'], 'ErrorsLog'), ['errorField' => 'error_id']);

        return $rules;
    }
}
