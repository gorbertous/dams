<?php
declare(strict_types=1);

namespace Treasury\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Reinvestments Model
 *
 * @method \Treasury\Model\Entity\Reinvestment newEmptyEntity()
 * @method \Treasury\Model\Entity\Reinvestment newEntity(array $data, array $options = [])
 * @method \Treasury\Model\Entity\Reinvestment[] newEntities(array $data, array $options = [])
 * @method \Treasury\Model\Entity\Reinvestment get($primaryKey, $options = [])
 * @method \Treasury\Model\Entity\Reinvestment findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Treasury\Model\Entity\Reinvestment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Treasury\Model\Entity\Reinvestment[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Treasury\Model\Entity\Reinvestment|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\Reinvestment saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\Reinvestment[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Reinvestment[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Reinvestment[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Reinvestment[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReinvestmentsTable extends Table
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

        $this->setTable('reinvestments');
        $this->setDisplayField('reinv_group');
        $this->setPrimaryKey('reinv_group');

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
            ->integer('reinv_group')
            ->allowEmptyString('reinv_group', null, 'create');

        $validator
            ->scalar('reinv_status')
            ->maxLength('reinv_status', 45)
            ->requirePresence('reinv_status', 'create')
            ->notEmptyString('reinv_status');

        $validator
            ->integer('mandate_ID')
            ->allowEmptyString('mandate_ID');

        $validator
            ->integer('cmp_ID')
            ->allowEmptyString('cmp_ID');

        $validator
            ->integer('cpty_ID')
            ->allowEmptyString('cpty_ID');

        $validator
            ->date('availability_date')
            ->allowEmptyDate('availability_date');

        $validator
            ->scalar('accountA_IBAN')
            ->maxLength('accountA_IBAN', 50)
            ->allowEmptyString('accountA_IBAN');

        $validator
            ->scalar('accountB_IBAN')
            ->maxLength('accountB_IBAN', 50)
            ->allowEmptyString('accountB_IBAN');

        $validator
            ->decimal('amount_leftA')
            ->allowEmptyString('amount_leftA');

        $validator
            ->decimal('amount_leftB')
            ->allowEmptyString('amount_leftB');

        $validator
            ->scalar('reinv_type')
            ->allowEmptyString('reinv_type');

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
