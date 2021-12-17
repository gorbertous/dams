<?php
declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ExcludedTransactions Model
 *
 * @property \App\Model\Table\SmesTable&\Cake\ORM\Association\BelongsTo $Smes
 * @property \App\Model\Table\TransactionsTable&\Cake\ORM\Association\BelongsTo $Transactions
 * @property \App\Model\Table\SubtransactionsTable&\Cake\ORM\Association\BelongsTo $Subtransactions
 * @property \App\Model\Table\PortfoliosTable&\Cake\ORM\Association\BelongsTo $Portfolios
 * @property \App\Model\Table\ReportsTable&\Cake\ORM\Association\BelongsTo $Reports
 *
 * @method \App\Model\Entity\ExcludedTransaction newEmptyEntity()
 * @method \App\Model\Entity\ExcludedTransaction newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\ExcludedTransaction[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ExcludedTransaction get($primaryKey, $options = [])
 * @method \App\Model\Entity\ExcludedTransaction findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\ExcludedTransaction patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ExcludedTransaction[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ExcludedTransaction|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ExcludedTransaction saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ExcludedTransaction[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ExcludedTransaction[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\ExcludedTransaction[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ExcludedTransaction[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ExcludedTransactionsTable extends Table
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

        $this->setTable('excluded_transactions');
        $this->setDisplayField('excluded_id');
        $this->setPrimaryKey('excluded_id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Damsv2.Sme', [
            'foreignKey' => 'sme_id',
        ]);
        $this->belongsTo('Damsv2.Transactions', [
            'foreignKey' => 'transaction_id',
        ]);
        $this->belongsTo('Damsv2.Portfolio', [
            'foreignKey' => 'portfolio_id',
        ]);
        $this->belongsTo('Damsv2.Report', [
            'foreignKey' => 'report_id',
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
            ->integer('excluded_id')
            ->allowEmptyString('excluded_id', null, 'create');

        $validator
            ->date('exclusion_date')
            ->allowEmptyDate('exclusion_date');

        $validator
            ->numeric('excluded_transaction_amount')
            ->allowEmptyString('excluded_transaction_amount');

        $validator
            ->numeric('excluded_transaction_amount_eur')
            ->allowEmptyString('excluded_transaction_amount_eur');

        $validator
            ->numeric('excluded_transaction_amount_curr')
            ->allowEmptyString('excluded_transaction_amount_curr');

        $validator
            ->scalar('exclusion_type')
            ->maxLength('exclusion_type', 50)
            ->allowEmptyString('exclusion_type');

        $validator
            ->scalar('coverage_implication')
            ->maxLength('coverage_implication', 5)
            ->allowEmptyString('coverage_implication');

        $validator
            ->scalar('acceleration_flag')
            ->maxLength('acceleration_flag', 100)
            ->allowEmptyString('acceleration_flag');

        $validator
            ->scalar('comments')
            ->maxLength('comments', 1000)
            ->allowEmptyString('comments');

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
        $rules->add($rules->existsIn(['sme_id'], 'Smes'), ['errorField' => 'sme_id']);
        $rules->add($rules->existsIn(['transaction_id'], 'Transactions'), ['errorField' => 'transaction_id']);
        $rules->add($rules->existsIn(['subtransaction_id'], 'Subtransactions'), ['errorField' => 'subtransaction_id']);
        $rules->add($rules->existsIn(['portfolio_id'], 'Portfolios'), ['errorField' => 'portfolio_id']);
        $rules->add($rules->existsIn(['report_id'], 'Reports'), ['errorField' => 'report_id']);

        return $rules;
    }
}
