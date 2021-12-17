<?php
declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Daily Model
 *
 * @method \App\Model\Entity\Daily newEmptyEntity()
 * @method \App\Model\Entity\Daily newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Daily[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Daily get($primaryKey, $options = [])
 * @method \App\Model\Entity\Daily findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Daily patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Daily[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Daily|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Daily saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Daily[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Daily[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Daily[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Daily[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class DailyTable extends Table
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

        $this->setTable('daily');
        $this->setDisplayField('CURRENCY');
        $this->setPrimaryKey('CURRENCY');
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
            ->integer('ORDER')
            ->requirePresence('ORDER', 'create')
            ->notEmptyString('ORDER');

        $validator
            ->date('DATE')
            ->requirePresence('DATE', 'create')
            ->notEmptyDate('DATE');

        $validator
            ->scalar('CURRENCY')
            ->maxLength('CURRENCY', 3)
            ->allowEmptyString('CURRENCY', null, 'create');

        $validator
            ->numeric('OBS_VALUE')
            ->requirePresence('OBS_VALUE', 'create')
            ->notEmptyString('OBS_VALUE');

        $validator
            ->requirePresence('TREND', 'create')
            ->notEmptyString('TREND');

        $validator
            ->numeric('DIFF')
            ->requirePresence('DIFF', 'create')
            ->notEmptyString('DIFF');

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
