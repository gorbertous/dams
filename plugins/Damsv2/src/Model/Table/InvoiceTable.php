<?php

declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Invoice Model
 *
 * @property \App\Model\Table\PortfoliosTable&\Cake\ORM\Association\BelongsTo $Portfolio
 * @property \App\Model\Table\StatusesTable&\Cake\ORM\Association\BelongsTo $Statuses
 *
 * @method \App\Model\Entity\Invoice newEmptyEntity()
 * @method \App\Model\Entity\Invoice newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Invoice[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Invoice get($primaryKey, $options = [])
 * @method \App\Model\Entity\Invoice findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Invoice patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Invoice[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Invoice|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Invoice saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Invoice[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Invoice[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Invoice[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Invoice[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class InvoiceTable extends Table
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

        $this->setTable('invoices');
        $this->setDisplayField('invoice_id');
        $this->setPrimaryKey('invoice_id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Damsv2.Portfolio', [
            'foreignKey' => 'portfolio_id',
        ]);
        $this->belongsTo('Damsv2.Status', [
            'foreignKey' => 'status_id',
        ]);

        $this->belongsTo('Damsv2.VUser', [
            'foreignKey' => 'invoice_owner',
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
                ->integer('invoice_id')
                ->allowEmptyString('invoice_id', null, 'create');

        $validator
                ->integer('invoice_owner')
                ->allowEmptyString('invoice_owner');

        $validator
                ->date('invoice_date')
                ->allowEmptyDate('invoice_date');

        $validator
                ->date('due_date')
                ->allowEmptyDate('due_date');

        $validator
                ->date('expected_payment_date')
                ->allowEmptyDate('expected_payment_date');

        $validator
                ->date('accounting_payment_date')
                ->allowEmptyDate('accounting_payment_date');

        $validator
                ->scalar('contract_currency')
                ->maxLength('contract_currency', 3)
                ->allowEmptyString('contract_currency');

        $validator
                ->decimal('amount_curr')
                ->allowEmptyString('amount_curr');

        $validator
                ->decimal('amount_eur')
                ->allowEmptyString('amount_eur');

        $validator
                ->decimal('fx_rate')
                ->allowEmptyString('fx_rate');

        $validator
                ->scalar('fx_rate_label')
                ->maxLength('fx_rate_label', 100)
                ->allowEmptyString('fx_rate_label');

        $validator
                ->scalar('invoice_number')
                ->maxLength('invoice_number', 100)
                ->allowEmptyString('invoice_number');

        $validator
                ->scalar('stage')
                ->maxLength('stage', 100)
                ->allowEmptyString('stage');

        $validator
                ->scalar('pkid')
                ->maxLength('pkid', 32)
                ->allowEmptyString('pkid');

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
        $rules->add($rules->existsIn(['portfolio_id'], 'Portfolio'), ['errorField' => 'portfolio_id']);
        $rules->add($rules->existsIn(['status_id'], 'Status'), ['errorField' => 'status_id']);

        return $rules;
    }

}
