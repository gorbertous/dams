<?php
declare(strict_types=1);

namespace Treasury\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CounterpartyAccounts Model
 *
 * @property \Treasury\Model\Table\CptiesTable&\Cake\ORM\Association\BelongsTo $Cpties
 *
 * @method \Treasury\Model\Entity\CounterpartyAccount newEmptyEntity()
 * @method \Treasury\Model\Entity\CounterpartyAccount newEntity(array $data, array $options = [])
 * @method \Treasury\Model\Entity\CounterpartyAccount[] newEntities(array $data, array $options = [])
 * @method \Treasury\Model\Entity\CounterpartyAccount get($primaryKey, $options = [])
 * @method \Treasury\Model\Entity\CounterpartyAccount findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Treasury\Model\Entity\CounterpartyAccount patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Treasury\Model\Entity\CounterpartyAccount[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Treasury\Model\Entity\CounterpartyAccount|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\CounterpartyAccount saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\CounterpartyAccount[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\CounterpartyAccount[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\CounterpartyAccount[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\CounterpartyAccount[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class CounterpartyAccountsTable extends Table
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

        $this->setTable('counterparty_accounts');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Cpties', [
            'foreignKey' => 'cpty_id',
            'joinType' => 'INNER',
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
            ->scalar('correspondent_bank')
            ->maxLength('correspondent_bank', 200)
            ->allowEmptyString('correspondent_bank');

        $validator
            ->scalar('correspondent_BIC')
            ->maxLength('correspondent_BIC', 50)
            ->allowEmptyString('correspondent_BIC');

        $validator
            ->scalar('currency')
            ->maxLength('currency', 5)
            ->requirePresence('currency', 'create')
            ->notEmptyString('currency');

        $validator
            ->scalar('account_IBAN')
            ->maxLength('account_IBAN', 30)
            ->allowEmptyString('account_IBAN');

        $validator
            ->boolean('target')
            ->allowEmptyString('target');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['cpty_id'], 'Cpties'), ['errorField' => 'cpty_id']);

        return $rules;
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
