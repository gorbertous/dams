<?php
declare(strict_types=1);

namespace Treasury\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BondsTransactions Model
 *
 * @property \Treasury\Model\Table\MandatesTable&\Cake\ORM\Association\BelongsTo $Mandates
 * @property \Treasury\Model\Table\CmpsTable&\Cake\ORM\Association\BelongsTo $Cmps
 * @property \Treasury\Model\Table\CptiesTable&\Cake\ORM\Association\BelongsTo $Cpties
 * @property \Treasury\Model\Table\BondsTransactionsTable&\Cake\ORM\Association\BelongsTo $ParentBondsTransactions
 * @property \Treasury\Model\Table\BondsTable&\Cake\ORM\Association\BelongsTo $Bonds
 * @property \Treasury\Model\Table\BondsTransactionsTable&\Cake\ORM\Association\HasMany $ChildBondsTransactions
 *
 * @method \Treasury\Model\Entity\BondsTransaction newEmptyEntity()
 * @method \Treasury\Model\Entity\BondsTransaction newEntity(array $data, array $options = [])
 * @method \Treasury\Model\Entity\BondsTransaction[] newEntities(array $data, array $options = [])
 * @method \Treasury\Model\Entity\BondsTransaction get($primaryKey, $options = [])
 * @method \Treasury\Model\Entity\BondsTransaction findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Treasury\Model\Entity\BondsTransaction patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Treasury\Model\Entity\BondsTransaction[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Treasury\Model\Entity\BondsTransaction|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\BondsTransaction saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\BondsTransaction[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\BondsTransaction[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\BondsTransaction[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\BondsTransaction[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BondsTransactionsTable extends Table
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

        $this->setTable('bonds_transactions');
        $this->setDisplayField('tr_number');
        $this->setPrimaryKey('tr_number');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Mandates', [
            'foreignKey' => 'mandate_id',
        ]);
        $this->belongsTo('Cmps', [
            'foreignKey' => 'cmp_id',
        ]);
        $this->belongsTo('Cpties', [
            'foreignKey' => 'cpty_id',
        ]);
        $this->belongsTo('ParentBondsTransactions', [
            'className' => 'BondsTransactions',
            'foreignKey' => 'parent_id',
        ]);
        $this->belongsTo('Bonds', [
            'foreignKey' => 'bond_id',
        ]);
        $this->hasMany('ChildBondsTransactions', [
            'className' => 'BondsTransactions',
            'foreignKey' => 'parent_id',
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
            ->integer('instr_num')
            ->allowEmptyString('instr_num');

        $validator
            ->scalar('tr_type')
            ->maxLength('tr_type', 255)
            ->requirePresence('tr_type', 'create')
            ->notEmptyString('tr_type');

        $validator
            ->scalar('tr_state')
            ->maxLength('tr_state', 255)
            ->allowEmptyString('tr_state');

        $validator
            ->scalar('currency')
            ->maxLength('currency', 255)
            ->allowEmptyString('currency');

        $validator
            ->decimal('nominal_amount')
            ->allowEmptyString('nominal_amount');

        $validator
            ->decimal('coupon_payment_amount')
            ->allowEmptyString('coupon_payment_amount');

        $validator
            ->decimal('purchase_price')
            ->allowEmptyString('purchase_price');

        $validator
            ->decimal('purchase_amount')
            ->allowEmptyString('purchase_amount');

        $validator
            ->decimal('accrued_coupon_at_purchase')
            ->allowEmptyString('accrued_coupon_at_purchase');

        $validator
            ->decimal('total_purchase_amount')
            ->allowEmptyString('total_purchase_amount');

        $validator
            ->date('trade_date')
            ->allowEmptyDate('trade_date');

        $validator
            ->date('settlement_date')
            ->allowEmptyDate('settlement_date');

        $validator
            ->decimal('yield_to_maturity')
            ->allowEmptyString('yield_to_maturity');

        $validator
            ->decimal('accrued_coupon_eom')
            ->allowEmptyString('accrued_coupon_eom');

        $validator
            ->decimal('accrued_tax_eom')
            ->allowEmptyString('accrued_tax_eom');

        $validator
            ->decimal('total_coupon')
            ->allowEmptyString('total_coupon');

        $validator
            ->decimal('total_tax')
            ->allowEmptyString('total_tax');

        $validator
            ->decimal('reference_rate')
            ->allowEmptyString('reference_rate');

        $validator
            ->decimal('spread_bp')
            ->allowEmptyString('spread_bp');

        $validator
            ->scalar('benchmark')
            ->maxLength('benchmark', 16777215)
            ->allowEmptyString('benchmark');

        $validator
            ->scalar('comment')
            ->allowEmptyString('comment');

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
        $rules->add($rules->existsIn(['mandate_id'], 'Mandates'), ['errorField' => 'mandate_id']);
        $rules->add($rules->existsIn(['cmp_id'], 'Cmps'), ['errorField' => 'cmp_id']);
        $rules->add($rules->existsIn(['cpty_id'], 'Cpties'), ['errorField' => 'cpty_id']);
        $rules->add($rules->existsIn(['parent_id'], 'ParentBondsTransactions'), ['errorField' => 'parent_id']);
        $rules->add($rules->existsIn(['bond_id'], 'Bonds'), ['errorField' => 'bond_id']);

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
