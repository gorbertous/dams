<?php
declare(strict_types=1);

namespace Treasury\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Bonds Model
 *
 * @property \Treasury\Model\Table\TransactionsTable&\Cake\ORM\Association\BelongsToMany $Transactions
 *
 * @method \Treasury\Model\Entity\Bond newEmptyEntity()
 * @method \Treasury\Model\Entity\Bond newEntity(array $data, array $options = [])
 * @method \Treasury\Model\Entity\Bond[] newEntities(array $data, array $options = [])
 * @method \Treasury\Model\Entity\Bond get($primaryKey, $options = [])
 * @method \Treasury\Model\Entity\Bond findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Treasury\Model\Entity\Bond patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Treasury\Model\Entity\Bond[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Treasury\Model\Entity\Bond|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\Bond saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\Bond[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Bond[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Bond[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Bond[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BondsTable extends Table
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

        $this->setTable('bonds');
        $this->setDisplayField('bond_id');
        $this->setPrimaryKey('bond_id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Transactions', [
            'foreignKey' => 'bond_id',
            'targetForeignKey' => 'transaction_id',
            'joinTable' => 'bonds_transactions',
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
            ->integer('bond_id')
            ->allowEmptyString('bond_id', null, 'create');

        $validator
            ->scalar('ISIN')
            ->maxLength('ISIN', 255)
            ->requirePresence('ISIN', 'create')
            ->notEmptyString('ISIN');

        $validator
            ->scalar('state')
            ->maxLength('state', 255)
            ->allowEmptyString('state');

        $validator
            ->scalar('currency')
            ->maxLength('currency', 255)
            ->allowEmptyString('currency');

        $validator
            ->scalar('issuer')
            ->allowEmptyString('issuer');

        $validator
            ->date('issue_date')
            ->allowEmptyDate('issue_date');

        $validator
            ->date('first_coupon_accrual_date')
            ->allowEmptyDate('first_coupon_accrual_date');

        $validator
            ->date('first_coupon_payment_date')
            ->allowEmptyDate('first_coupon_payment_date');

        $validator
            ->date('maturity_date')
            ->allowEmptyDate('maturity_date');

        $validator
            ->decimal('coupon_rate')
            ->allowEmptyString('coupon_rate');

        $validator
            ->scalar('coupon_frequency')
            ->maxLength('coupon_frequency', 255)
            ->allowEmptyString('coupon_frequency');

        $validator
            ->scalar('date_basis')
            ->maxLength('date_basis', 255)
            ->allowEmptyString('date_basis');

        $validator
            ->scalar('date_convention')
            ->maxLength('date_convention', 255)
            ->allowEmptyString('date_convention');

        $validator
            ->decimal('tax_rate')
            ->allowEmptyString('tax_rate');

        $validator
            ->scalar('country')
            ->maxLength('country', 255)
            ->allowEmptyString('country');

        $validator
            ->decimal('issue_size')
            ->allowEmptyString('issue_size');

        $validator
            ->boolean('covered')
            ->allowEmptyString('covered');

        $validator
            ->boolean('secured')
            ->allowEmptyString('secured');

        $validator
            ->scalar('seniority')
            ->maxLength('seniority', 255)
            ->allowEmptyString('seniority');

        $validator
            ->scalar('guarantor')
            ->allowEmptyString('guarantor');

        $validator
            ->boolean('structured')
            ->allowEmptyString('structured');

        $validator
            ->scalar('issuer_type')
            ->maxLength('issuer_type', 255)
            ->allowEmptyString('issuer_type');

        $validator
            ->scalar('issue_rating_STP')
            ->maxLength('issue_rating_STP', 255)
            ->allowEmptyString('issue_rating_STP');

        $validator
            ->scalar('issue_rating_MDY')
            ->maxLength('issue_rating_MDY', 255)
            ->allowEmptyString('issue_rating_MDY');

        $validator
            ->scalar('issue_rating_FIT')
            ->maxLength('issue_rating_FIT', 255)
            ->allowEmptyString('issue_rating_FIT');

        $validator
            ->scalar('retained_rating')
            ->maxLength('retained_rating', 255)
            ->allowEmptyString('retained_rating');

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
