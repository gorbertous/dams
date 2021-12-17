<?php

declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * StatusUmbrella Model
 *
 * @method \App\Model\Entity\StatusUmbrella newEmptyEntity()
 * @method \App\Model\Entity\StatusUmbrella newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\StatusUmbrella[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\StatusUmbrella get($primaryKey, $options = [])
 * @method \App\Model\Entity\StatusUmbrella findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\StatusUmbrella patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\StatusUmbrella[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\StatusUmbrella|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StatusUmbrella saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StatusUmbrella[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\StatusUmbrella[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\StatusUmbrella[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\StatusUmbrella[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class StatusUmbrellaTable extends Table
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

        $this->setTable('status_umbrella');
        $this->setDisplayField('status_id_umbrella');
        $this->setPrimaryKey('status_id_umbrella');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Damsv2.Status', [
            'foreignKey' => 'status_id',
            'joinType'   => 'INNER',
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
                ->integer('status_id_umbrella')
                ->allowEmptyString('status_id_umbrella', null, 'create');

        $validator
                ->scalar('stage')
                ->maxLength('stage', 100)
                ->requirePresence('stage', 'create')
                ->notEmptyString('stage');

        return $validator;
    }

}
