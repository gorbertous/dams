<?php
declare(strict_types=1);

namespace Treasury\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Limits Model
 *
 * @method \Treasury\Model\Entity\Limit newEmptyEntity()
 * @method \Treasury\Model\Entity\Limit newEntity(array $data, array $options = [])
 * @method \Treasury\Model\Entity\Limit[] newEntities(array $data, array $options = [])
 * @method \Treasury\Model\Entity\Limit get($primaryKey, $options = [])
 * @method \Treasury\Model\Entity\Limit findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Treasury\Model\Entity\Limit patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Treasury\Model\Entity\Limit[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Treasury\Model\Entity\Limit|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\Limit saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\Limit[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Limit[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Limit[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Limit[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class LimitsTable extends Table
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

        $this->setTable('limits');
        $this->setDisplayField('limit_ID');
        $this->setPrimaryKey('limit_ID');

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
            ->integer('limit_ID')
            ->allowEmptyString('limit_ID', null, 'create');

        $validator
            ->scalar('limit_name')
            ->maxLength('limit_name', 45)
            ->allowEmptyString('limit_name');

        $validator
            ->date('limit_date_from')
            ->allowEmptyDate('limit_date_from');

        $validator
            ->date('limit_date_to')
            ->allowEmptyDate('limit_date_to');

        $validator
            ->integer('mandategroup_ID')
            ->allowEmptyString('mandategroup_ID');

        $validator
            ->integer('counterpartygroup_ID')
            ->allowEmptyString('counterpartygroup_ID');

        $validator
            ->integer('cpty_ID')
            ->allowEmptyString('cpty_ID');

        $validator
            ->integer('automatic')
            ->allowEmptyString('automatic');

        $validator
            ->scalar('rating_lt')
            ->maxLength('rating_lt', 64)
            ->allowEmptyString('rating_lt');

        $validator
            ->scalar('rating_st')
            ->maxLength('rating_st', 64)
            ->allowEmptyString('rating_st');

        $validator
            ->scalar('cpty_rating')
            ->maxLength('cpty_rating', 50)
            ->allowEmptyString('cpty_rating');

        $validator
            ->integer('max_maturity')
            ->allowEmptyString('max_maturity');

        $validator
            ->decimal('limit_eur')
            ->allowEmptyString('limit_eur');

        $validator
            ->decimal('max_concentration')
            ->allowEmptyString('max_concentration');

        $validator
            ->scalar('concentration_limit_unit')
            ->maxLength('concentration_limit_unit', 3)
            ->allowEmptyString('concentration_limit_unit');

        $validator
            ->integer('is_current')
            ->allowEmptyString('is_current');

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
