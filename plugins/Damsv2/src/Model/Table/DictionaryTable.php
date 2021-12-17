<?php
declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Dictionary Model
 *
 * @method \App\Model\Entity\Dictionary newEmptyEntity()
 * @method \App\Model\Entity\Dictionary newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Dictionary[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Dictionary get($primaryKey, $options = [])
 * @method \App\Model\Entity\Dictionary findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Dictionary patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Dictionary[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Dictionary|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Dictionary saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Dictionary[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Dictionary[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Dictionary[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Dictionary[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DictionaryTable extends Table
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

        $this->setTable('dictionary');
        $this->setDisplayField('name');
        $this->setPrimaryKey('dictionary_id');

        $this->addBehavior('Timestamp');
        
        $this->hasMany('Damsv2.DictionaryValues', [
            'foreignKey' => 'dictionary_id'           
        ]);
        
        $this->hasMany('Damsv2.MappingColumn', [
            'foreignKey' => 'dictionary_id'
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
            ->integer('dictionary_id')
            ->allowEmptyString('dictionary_id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 250)
            ->allowEmptyString('name');

        return $validator;
    }
}
