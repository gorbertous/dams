<?php
declare(strict_types=1);

namespace Treasury\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Compartments Model
 *
 * @method \Treasury\Model\Entity\Compartment newEmptyEntity()
 * @method \Treasury\Model\Entity\Compartment newEntity(array $data, array $options = [])
 * @method \Treasury\Model\Entity\Compartment[] newEntities(array $data, array $options = [])
 * @method \Treasury\Model\Entity\Compartment get($primaryKey, $options = [])
 * @method \Treasury\Model\Entity\Compartment findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Treasury\Model\Entity\Compartment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Treasury\Model\Entity\Compartment[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Treasury\Model\Entity\Compartment|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\Compartment saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\Compartment[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Compartment[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Compartment[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Compartment[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CompartmentsTable extends Table
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

        $this->setTable('compartments');
        $this->setDisplayField('cmp_ID');
        $this->setPrimaryKey('cmp_ID');

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
            ->integer('cmp_ID')
            ->allowEmptyString('cmp_ID', null, 'create');

        $validator
            ->scalar('cmp_name')
            ->maxLength('cmp_name', 100)
            ->allowEmptyString('cmp_name');

        $validator
            ->scalar('cmp_type')
            ->maxLength('cmp_type', 20)
            ->allowEmptyString('cmp_type');

        $validator
            ->scalar('cmp_value')
            ->maxLength('cmp_value', 10)
            ->allowEmptyString('cmp_value');

        $validator
            ->scalar('cmp_dpt_code_value')
            ->maxLength('cmp_dpt_code_value', 250)
            ->allowEmptyString('cmp_dpt_code_value');

        $validator
            ->scalar('cmp_sof_value')
            ->maxLength('cmp_sof_value', 250)
            ->allowEmptyString('cmp_sof_value');

        $validator
            ->integer('mandate_ID')
            ->allowEmptyString('mandate_ID');

        $validator
            ->scalar('accountA_IBAN')
            ->maxLength('accountA_IBAN', 50)
            ->allowEmptyString('accountA_IBAN');

        $validator
            ->scalar('accountB_IBAN')
            ->maxLength('accountB_IBAN', 50)
            ->allowEmptyString('accountB_IBAN');

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
