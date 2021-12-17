<?php
declare(strict_types=1);

namespace Treasury\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CounterpartyGroups Model
 *
 * @method \Treasury\Model\Entity\CounterpartyGroup newEmptyEntity()
 * @method \Treasury\Model\Entity\CounterpartyGroup newEntity(array $data, array $options = [])
 * @method \Treasury\Model\Entity\CounterpartyGroup[] newEntities(array $data, array $options = [])
 * @method \Treasury\Model\Entity\CounterpartyGroup get($primaryKey, $options = [])
 * @method \Treasury\Model\Entity\CounterpartyGroup findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Treasury\Model\Entity\CounterpartyGroup patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Treasury\Model\Entity\CounterpartyGroup[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Treasury\Model\Entity\CounterpartyGroup|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\CounterpartyGroup saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\CounterpartyGroup[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\CounterpartyGroup[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\CounterpartyGroup[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\CounterpartyGroup[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CounterpartyGroupsTable extends Table
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

        $this->setTable('counterparty_groups');
        $this->setDisplayField('counterpartygroup_ID');
        $this->setPrimaryKey('counterpartygroup_ID');

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
            ->integer('counterpartygroup_ID')
            ->allowEmptyString('counterpartygroup_ID', null, 'create');

        $validator
            ->scalar('counterpartygroup_name')
            ->maxLength('counterpartygroup_name', 45)
            ->allowEmptyString('counterpartygroup_name');

        $validator
            ->integer('head')
            ->requirePresence('head', 'create')
            ->notEmptyString('head');

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
