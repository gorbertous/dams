<?php
declare(strict_types=1);

namespace Treasury\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Transactions Model
 *
 * @property \Treasury\Model\Table\OriginalsTable&\Cake\ORM\Association\BelongsTo $Originals
 * @property \Treasury\Model\Table\TransactionsTable&\Cake\ORM\Association\BelongsTo $ParentTransactions
 * @property \Treasury\Model\Table\CptiesTable&\Cake\ORM\Association\BelongsTo $Cpties
 * @property \Treasury\Model\Table\HistoPsTable&\Cake\ORM\Association\HasMany $HistoPs
 * @property \Treasury\Model\Table\TransactionsTable&\Cake\ORM\Association\HasMany $ChildTransactions
 * @property \Treasury\Model\Table\BondsTable&\Cake\ORM\Association\BelongsToMany $Bonds
 * @property \Treasury\Model\Table\LimitBreachesTable&\Cake\ORM\Association\BelongsToMany $LimitBreaches
 *
 * @method \Treasury\Model\Entity\Transaction newEmptyEntity()
 * @method \Treasury\Model\Entity\Transaction newEntity(array $data, array $options = [])
 * @method \Treasury\Model\Entity\Transaction[] newEntities(array $data, array $options = [])
 * @method \Treasury\Model\Entity\Transaction get($primaryKey, $options = [])
 * @method \Treasury\Model\Entity\Transaction findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Treasury\Model\Entity\Transaction patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Treasury\Model\Entity\Transaction[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Treasury\Model\Entity\Transaction|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\Transaction saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\Transaction[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Transaction[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Transaction[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Transaction[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TransactionsTable extends Table
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

        $this->setTable('transactions');
        $this->setDisplayField('tr_number');
        $this->setPrimaryKey('tr_number');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Originals', [
            'foreignKey' => 'original_id',
        ]);
        $this->belongsTo('ParentTransactions', [
            'className' => 'Transactions',
            'foreignKey' => 'parent_id',
        ]);
        $this->belongsTo('Cpties', [
            'foreignKey' => 'cpty_id',
        ]);
        $this->hasMany('HistoPs', [
            'foreignKey' => 'transaction_id',
        ]);
        $this->hasMany('ChildTransactions', [
            'className' => 'Transactions',
            'foreignKey' => 'parent_id',
        ]);
        $this->belongsToMany('Bonds', [
            'foreignKey' => 'transaction_id',
            'targetForeignKey' => 'bond_id',
            'joinTable' => 'bonds_transactions',
        ]);
        $this->belongsToMany('LimitBreaches', [
            'foreignKey' => 'transaction_id',
            'targetForeignKey' => 'limit_breach_id',
            'joinTable' => 'limit_breaches_transactions',
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
            ->integer('tr_number')
            ->allowEmptyString('tr_number', null, 'create');

        $validator
            ->scalar('tr_type')
            ->maxLength('tr_type', 20)
            ->allowEmptyString('tr_type');

        $validator
            ->scalar('tr_state')
            ->maxLength('tr_state', 25)
            ->allowEmptyString('tr_state');

        $validator
            ->integer('source_group')
            ->allowEmptyString('source_group');

        $validator
            ->integer('reinv_group')
            ->allowEmptyString('reinv_group');

        $validator
            ->integer('linked_trn')
            ->allowEmptyString('linked_trn');

        $validator
            ->scalar('external_ref')
            ->maxLength('external_ref', 50)
            ->allowEmptyString('external_ref');

        $validator
            ->decimal('amount')
            ->allowEmptyString('amount');

        $validator
            ->date('commencement_date')
            ->allowEmptyDate('commencement_date');

        $validator
            ->date('maturity_date')
            ->allowEmptyDate('maturity_date');

        $validator
            ->date('indicative_maturity_date')
            ->allowEmptyDate('indicative_maturity_date');

        $validator
            ->scalar('depo_term')
            ->maxLength('depo_term', 2)
            ->allowEmptyString('depo_term');

        $validator
            ->decimal('interest_rate')
            ->allowEmptyString('interest_rate');

        $validator
            ->decimal('total_interest')
            ->allowEmptyString('total_interest');

        $validator
            ->decimal('tax_amount')
            ->allowEmptyString('tax_amount');

        $validator
            ->scalar('depo_type')
            ->maxLength('depo_type', 10)
            ->allowEmptyString('depo_type');

        $validator
            ->scalar('depo_renew')
            ->maxLength('depo_renew', 3)
            ->allowEmptyString('depo_renew');

        $validator
            ->scalar('rate_type')
            ->maxLength('rate_type', 10)
            ->allowEmptyString('rate_type');

        $validator
            ->scalar('date_basis')
            ->maxLength('date_basis', 10)
            ->allowEmptyString('date_basis');

        $validator
            ->integer('mandate_ID')
            ->allowEmptyString('mandate_ID');

        $validator
            ->integer('cmp_ID')
            ->allowEmptyString('cmp_ID');

        $validator
            ->scalar('scheme')
            ->maxLength('scheme', 2)
            ->allowEmptyString('scheme');

        $validator
            ->scalar('accountA_IBAN')
            ->maxLength('accountA_IBAN', 50)
            ->allowEmptyString('accountA_IBAN');

        $validator
            ->scalar('accountB_IBAN')
            ->maxLength('accountB_IBAN', 50)
            ->allowEmptyString('accountB_IBAN');

        $validator
            ->integer('instr_num')
            ->allowEmptyString('instr_num');

        $validator
            ->scalar('ps_account')
            ->maxLength('ps_account', 45)
            ->allowEmptyString('ps_account');

        $validator
            ->scalar('booking_status')
            ->maxLength('booking_status', 10)
            ->allowEmptyString('booking_status');

        $validator
            ->scalar('eom_booking')
            ->maxLength('eom_booking', 45)
            ->notEmptyString('eom_booking');

        $validator
            ->decimal('accrued_interst')
            ->allowEmptyString('accrued_interst');

        $validator
            ->decimal('accrued_tax')
            ->allowEmptyString('accrued_tax');

        $validator
            ->date('fixing_date')
            ->allowEmptyDate('fixing_date');

        $validator
            ->decimal('eom_interest')
            ->allowEmptyString('eom_interest');

        $validator
            ->decimal('eom_tax')
            ->allowEmptyString('eom_tax');

        $validator
            ->integer('tax_ID')
            ->allowEmptyString('tax_ID');

        $validator
            ->scalar('source_fund')
            ->maxLength('source_fund', 1)
            ->allowEmptyString('source_fund');

        $validator
            ->scalar('comment')
            ->allowEmptyString('comment');

        $validator
            ->decimal('reference_rate')
            ->allowEmptyString('reference_rate');

        $validator
            ->decimal('spread_bp')
            ->allowEmptyString('spread_bp');

        $validator
            ->scalar('benchmark')
            ->maxLength('benchmark', 128)
            ->allowEmptyString('benchmark');

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
        $rules->add($rules->existsIn(['original_id'], 'Originals'), ['errorField' => 'original_id']);
        $rules->add($rules->existsIn(['parent_id'], 'ParentTransactions'), ['errorField' => 'parent_id']);
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
