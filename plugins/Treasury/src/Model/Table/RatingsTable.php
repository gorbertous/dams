<?php
declare(strict_types=1);

namespace Treasury\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Ratings Model
 *
 * @method \Treasury\Model\Entity\Rating newEmptyEntity()
 * @method \Treasury\Model\Entity\Rating newEntity(array $data, array $options = [])
 * @method \Treasury\Model\Entity\Rating[] newEntities(array $data, array $options = [])
 * @method \Treasury\Model\Entity\Rating get($primaryKey, $options = [])
 * @method \Treasury\Model\Entity\Rating findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Treasury\Model\Entity\Rating patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Treasury\Model\Entity\Rating[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Treasury\Model\Entity\Rating|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\Rating saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\Rating[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Rating[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Rating[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Rating[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RatingsTable extends Table
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

        $this->setTable('ratings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

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
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->integer('automatic')
            ->notEmptyString('automatic');

        $validator
            ->scalar('pirat_number')
            ->maxLength('pirat_number', 5)
            ->requirePresence('pirat_number', 'create')
            ->notEmptyString('pirat_number');

        $validator
            ->scalar('pirat_cpty_name')
            ->maxLength('pirat_cpty_name', 100)
            ->allowEmptyString('pirat_cpty_name');

        $validator
            ->scalar('pirat_address')
            ->maxLength('pirat_address', 200)
            ->allowEmptyString('pirat_address');

        $validator
            ->scalar('pirat_country')
            ->maxLength('pirat_country', 200)
            ->allowEmptyString('pirat_country');

        $validator
            ->scalar('mother_company')
            ->maxLength('mother_company', 128)
            ->allowEmptyString('mother_company');

        $validator
            ->decimal('own_funds')
            ->allowEmptyString('own_funds');

        $validator
            ->date('bs_date')
            ->allowEmptyDate('bs_date');

        $validator
            ->scalar('LT-MDY')
            ->maxLength('LT-MDY', 10)
            ->allowEmptyString('LT-MDY');

        $validator
            ->date('LT-MDY_date')
            ->allowEmptyDate('LT-MDY_date');

        $validator
            ->scalar('LT-MDY_outlook')
            ->maxLength('LT-MDY_outlook', 10)
            ->allowEmptyString('LT-MDY_outlook');

        $validator
            ->scalar('LT-FIT')
            ->maxLength('LT-FIT', 10)
            ->allowEmptyString('LT-FIT');

        $validator
            ->date('LT-FIT_date')
            ->allowEmptyDate('LT-FIT_date');

        $validator
            ->scalar('LT-FIT_outlook')
            ->maxLength('LT-FIT_outlook', 10)
            ->allowEmptyString('LT-FIT_outlook');

        $validator
            ->scalar('LT-STP')
            ->maxLength('LT-STP', 10)
            ->allowEmptyString('LT-STP');

        $validator
            ->date('LT-STP_date')
            ->allowEmptyDate('LT-STP_date');

        $validator
            ->scalar('LT-STP_outlook')
            ->maxLength('LT-STP_outlook', 10)
            ->allowEmptyString('LT-STP_outlook');

        $validator
            ->scalar('LT-EIB')
            ->maxLength('LT-EIB', 10)
            ->allowEmptyString('LT-EIB');

        $validator
            ->date('LT-EIB_date')
            ->allowEmptyDate('LT-EIB_date');

        $validator
            ->scalar('ST-MDY')
            ->maxLength('ST-MDY', 10)
            ->allowEmptyString('ST-MDY');

        $validator
            ->date('ST-MDY_date')
            ->allowEmptyDate('ST-MDY_date');

        $validator
            ->scalar('ST-MDY_outlook')
            ->maxLength('ST-MDY_outlook', 10)
            ->allowEmptyString('ST-MDY_outlook');

        $validator
            ->scalar('ST-FIT')
            ->maxLength('ST-FIT', 10)
            ->allowEmptyString('ST-FIT');

        $validator
            ->date('ST-FIT_date')
            ->allowEmptyDate('ST-FIT_date');

        $validator
            ->scalar('ST-FIT_outlook')
            ->maxLength('ST-FIT_outlook', 10)
            ->allowEmptyString('ST-FIT_outlook');

        $validator
            ->scalar('ST-STP')
            ->maxLength('ST-STP', 10)
            ->allowEmptyString('ST-STP');

        $validator
            ->date('ST-STP_date')
            ->allowEmptyDate('ST-STP_date');

        $validator
            ->scalar('ST-STP_outlook')
            ->maxLength('ST-STP_outlook', 10)
            ->allowEmptyString('ST-STP_outlook');

        $validator
            ->scalar('ST-EIB')
            ->maxLength('ST-EIB', 10)
            ->allowEmptyString('ST-EIB');

        $validator
            ->date('ST-EIB_date')
            ->allowEmptyDate('ST-EIB_date');

        return $validator;
    }

    /**
     * Returns the database connection name to use by default.
     *
     * @return string
     */
    public static function defaultConnectionName(): string
    {
        return 'treasury';
    }
}
