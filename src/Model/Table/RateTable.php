<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Rate Model
 *
 * @method \App\Model\Entity\Rate newEmptyEntity()
 * @method \App\Model\Entity\Rate newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Rate[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Rate get($primaryKey, $options = [])
 * @method \App\Model\Entity\Rate findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Rate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Rate[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Rate|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Rate saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Rate[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Rate[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Rate[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Rate[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class RateTable extends Table
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

        $this->setTable('rate');
        $this->setDisplayField('ID');
        $this->setPrimaryKey('ID');
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
            ->integer('ID')
            ->allowEmptyString('ID', null, 'create');

        $validator
            ->date('TIME_PERIOD')
            ->requirePresence('TIME_PERIOD', 'create')
            ->notEmptyDate('TIME_PERIOD');

        $validator
            ->scalar('CURRENCY')
            ->maxLength('CURRENCY', 3)
            ->requirePresence('CURRENCY', 'create')
            ->notEmptyString('CURRENCY');

        $validator
            ->numeric('OBS_VALUE')
            ->requirePresence('OBS_VALUE', 'create')
            ->notEmptyString('OBS_VALUE');

        return $validator;
    }

    /**
     * Returns the database connection name to use by default.
     *
     * @return string
     */
    public static function defaultConnectionName(): string
    {
        return 'ecb';
    }
}
