<?php
declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Status Model
 *
 * @method \App\Model\Entity\Status newEmptyEntity()
 * @method \App\Model\Entity\Status newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Status[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Status get($primaryKey, $options = [])
 * @method \App\Model\Entity\Status findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Status patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Status[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Status|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Status saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Status[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Status[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Status[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Status[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class StatusTable extends Table
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

        $this->setTable('status');
        $this->setDisplayField('status_id');
        $this->setPrimaryKey('status_id');

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
            ->integer('status_id')
            ->allowEmptyString('status_id', null, 'create');

        $validator
            ->scalar('stage')
            ->maxLength('stage', 100)
            ->requirePresence('stage', 'create')
            ->notEmptyString('stage');

        $validator
            ->scalar('status')
            ->maxLength('status', 100)
            ->allowEmptyString('status');

        $validator
            ->scalar('action')
            ->maxLength('action', 100)
            ->allowEmptyString('action');

        return $validator;
    }
}
