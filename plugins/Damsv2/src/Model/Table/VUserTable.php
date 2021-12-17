<?php

declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * VUser Model
 *
 * @method \App\Model\Entity\VUser newEmptyEntity()
 * @method \App\Model\Entity\VUser newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\VUser[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VUser get($primaryKey, $options = [])
 * @method \App\Model\Entity\VUser findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\VUser patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VUser[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\VUser|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VUser saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VUser[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\VUser[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\VUser[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\VUser[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class VUserTable extends Table
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

        $this->setTable('v_users');
        $this->setDisplayField('fullName');
        $this->setPrimaryKey('id');

        $this->hasMany('Damsv2.Rules', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('Damsv2.Report', [
            'foreignKey' => 'owner',
        ]);
        $this->hasMany('Damsv2.Portfolio', [
            'foreignKey' => 'owner',
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
                ->numeric('id')
                ->allowEmptyString('id');

        $validator
                ->scalar('first_name')
                ->maxLength('first_name', 19)
                ->allowEmptyString('first_name');

        $validator
                ->scalar('last_name')
                ->maxLength('last_name', 22)
                ->allowEmptyString('last_name');

        return $validator;
    }

}
