<?php
declare(strict_types=1);

namespace Dsr\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Dictionaries Model
 *
 * @property \Dsr\Model\Table\DicoValuesTable&\Cake\ORM\Association\HasMany $DicoValues
 *
 * @method \Dsr\Model\Entity\Dictionary newEmptyEntity()
 * @method \Dsr\Model\Entity\Dictionary newEntity(array $data, array $options = [])
 * @method \Dsr\Model\Entity\Dictionary[] newEntities(array $data, array $options = [])
 * @method \Dsr\Model\Entity\Dictionary get($primaryKey, $options = [])
 * @method \Dsr\Model\Entity\Dictionary findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Dsr\Model\Entity\Dictionary patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Dsr\Model\Entity\Dictionary[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Dsr\Model\Entity\Dictionary|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Dsr\Model\Entity\Dictionary saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Dsr\Model\Entity\Dictionary[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Dsr\Model\Entity\Dictionary[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Dsr\Model\Entity\Dictionary[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Dsr\Model\Entity\Dictionary[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DictionariesTable extends Table
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

        $this->setTable('dictionaries');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Dsr.DicoValues', [
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
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        return $validator;
    }

    /**
     * Returns the database connection name to use by default.
     *
     * @return string
     */
    public static function defaultConnectionName(): string
    {
        return 'dsr';
    }
}
