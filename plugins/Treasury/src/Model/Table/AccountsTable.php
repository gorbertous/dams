<?php
declare(strict_types=1);

namespace Treasury\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Accounts Model
 *
 * @method \Treasury\Model\Entity\Account newEmptyEntity()
 * @method \Treasury\Model\Entity\Account newEntity(array $data, array $options = [])
 * @method \Treasury\Model\Entity\Account[] newEntities(array $data, array $options = [])
 * @method \Treasury\Model\Entity\Account get($primaryKey, $options = [])
 * @method \Treasury\Model\Entity\Account findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Treasury\Model\Entity\Account patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Treasury\Model\Entity\Account[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Treasury\Model\Entity\Account|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\Account saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\Account[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Account[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Account[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Account[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AccountsTable extends Table
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

        $this->setTable('accounts');
        $this->setDisplayField('IBAN');
        $this->setPrimaryKey('IBAN');

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
            ->scalar('IBAN')
            ->maxLength('IBAN', 45)
            ->allowEmptyString('IBAN', null, 'create');

        $validator
            ->scalar('BIC')
            ->maxLength('BIC', 11)
            ->allowEmptyString('BIC');

        $validator
            ->scalar('ccy')
            ->maxLength('ccy', 3)
            ->allowEmptyString('ccy');

        $validator
            ->scalar('PS_account')
            ->maxLength('PS_account', 45)
            ->allowEmptyString('PS_account');

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
