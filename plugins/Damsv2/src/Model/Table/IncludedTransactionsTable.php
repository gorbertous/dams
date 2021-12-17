<?php
declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * IncludedTransactions Model
 *
 * @property \App\Model\Table\TransactionsTable&\Cake\ORM\Association\BelongsTo $Transactions
 * @property \App\Model\Table\SmesTable&\Cake\ORM\Association\BelongsTo $Smes
 * @property \App\Model\Table\PortfoliosTable&\Cake\ORM\Association\BelongsTo $Portfolios
 * @property \App\Model\Table\ReportsTable&\Cake\ORM\Association\BelongsTo $Reports
 *
 * @method \App\Model\Entity\IncludedTransaction newEmptyEntity()
 * @method \App\Model\Entity\IncludedTransaction newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\IncludedTransaction[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\IncludedTransaction get($primaryKey, $options = [])
 * @method \App\Model\Entity\IncludedTransaction findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\IncludedTransaction patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\IncludedTransaction[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\IncludedTransaction|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\IncludedTransaction saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\IncludedTransaction[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\IncludedTransaction[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\IncludedTransaction[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\IncludedTransaction[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class IncludedTransactionsTable extends Table
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

        $this->setTable('included_transactions');
        $this->setDisplayField('included_id');
        $this->setPrimaryKey('included_id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Damsv2.Transactions', [
            'foreignKey' => 'transaction_id',
        ]);
        $this->belongsTo('Damsv2.Sme', [
            'foreignKey' => 'sme_id',
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
            ->integer('included_id')
            ->allowEmptyString('included_id', null, 'create')
            ->add('included_id', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('currency')
            ->maxLength('currency', 255)
            ->allowEmptyString('currency');

        $validator
            ->numeric('fx_rate')
            ->allowEmptyString('fx_rate');

        $validator
            ->numeric('cumulative_disbursed')
            ->allowEmptyString('cumulative_disbursed');

        $validator
            ->numeric('cumulative_disbursed_eur')
            ->allowEmptyString('cumulative_disbursed_eur');

        $validator
            ->numeric('cumulative_disbursed_curr')
            ->allowEmptyString('cumulative_disbursed_curr');

        $validator
            ->numeric('cumulative_repaid')
            ->allowEmptyString('cumulative_repaid');

        $validator
            ->numeric('cumulative_repaid_eur')
            ->allowEmptyString('cumulative_repaid_eur');

        $validator
            ->numeric('cumulative_repaid_curr')
            ->allowEmptyString('cumulative_repaid_curr');

        $validator
            ->numeric('outstanding_principal')
            ->allowEmptyString('outstanding_principal');

        $validator
            ->numeric('outstanding_principal_eur')
            ->allowEmptyString('outstanding_principal_eur');

        $validator
            ->numeric('outstanding_principal_curr')
            ->allowEmptyString('outstanding_principal_curr');

        $validator
            ->scalar('disbursement_ended')
            ->maxLength('disbursement_ended', 255)
            ->allowEmptyString('disbursement_ended');

        $validator
            ->numeric('daily_avg_outstanding')
            ->allowEmptyString('daily_avg_outstanding');

        $validator
            ->numeric('daily_avg_outstanding_eur')
            ->allowEmptyString('daily_avg_outstanding_eur');

        $validator
            ->numeric('daily_avg_outstanding_curr')
            ->allowEmptyString('daily_avg_outstanding_curr');

        $validator
            ->numeric('daily_sum_outstanding')
            ->allowEmptyString('daily_sum_outstanding');

        $validator
            ->numeric('daily_sum_outstanding_eur')
            ->allowEmptyString('daily_sum_outstanding_eur');

        $validator
            ->numeric('daily_sum_outstanding_curr')
            ->allowEmptyString('daily_sum_outstanding_curr');

        $validator
            ->scalar('delinquent_transaction')
            ->maxLength('delinquent_transaction', 100)
            ->allowEmptyString('delinquent_transaction');

        $validator
            ->integer('delinquency_days')
            ->allowEmptyString('delinquency_days');

        $validator
            ->scalar('defaulted_transaction')
            ->maxLength('defaulted_transaction', 100)
            ->allowEmptyString('defaulted_transaction');

        $validator
            ->scalar('comments')
            ->maxLength('comments', 255)
            ->allowEmptyString('comments');

        $validator
            ->date('default_event_date')
            ->allowEmptyDate('default_event_date');

        $validator
            ->scalar('upside_realised')
            ->maxLength('upside_realised', 1)
            ->allowEmptyString('upside_realised');

        $validator
            ->numeric('upside_amount_curr')
            ->allowEmptyString('upside_amount_curr');

        $validator
            ->numeric('upside_amount_eur')
            ->allowEmptyString('upside_amount_eur');

        $validator
            ->numeric('upside_amount')
            ->allowEmptyString('upside_amount');

        $validator
            ->numeric('permit_add_inter_amount_curr')
            ->allowEmptyString('permit_add_inter_amount_curr');

        $validator
            ->numeric('permit_add_inter_amount_eur')
            ->allowEmptyString('permit_add_inter_amount_eur');

        $validator
            ->numeric('permit_add_inter_amount')
            ->allowEmptyString('permit_add_inter_amount');

        $validator
            ->numeric('amount_to_disburse')
            ->allowEmptyString('amount_to_disburse');

        $validator
            ->numeric('amount_to_disburse_curr')
            ->allowEmptyString('amount_to_disburse_curr');

        $validator
            ->numeric('amount_to_disburse_eur')
            ->allowEmptyString('amount_to_disburse_eur');

        $validator
            ->numeric('contractual_os_principal')
            ->allowEmptyString('contractual_os_principal');

        $validator
            ->numeric('contractual_os_principal_eur')
            ->allowEmptyString('contractual_os_principal_eur');

        $validator
            ->numeric('contractual_os_principal_curr')
            ->allowEmptyString('contractual_os_principal_curr');

        $validator
            ->scalar('sme_rating')
            ->maxLength('sme_rating', 50)
            ->allowEmptyString('sme_rating');

        $validator
            ->scalar('fi_rating_scale')
            ->maxLength('fi_rating_scale', 50)
            ->allowEmptyString('fi_rating_scale');

        $validator
            ->numeric('actual_os_principal_perf')
            ->allowEmptyString('actual_os_principal_perf');

        $validator
            ->numeric('actual_os_principal_perf_eur')
            ->allowEmptyString('actual_os_principal_perf_eur');

        $validator
            ->numeric('actual_os_principal_perf_curr')
            ->allowEmptyString('actual_os_principal_perf_curr');

        $validator
            ->numeric('cumulative_intr_repaid_curr')
            ->allowEmptyString('cumulative_intr_repaid_curr');

        $validator
            ->numeric('cumulative_intr_repaid_eur')
            ->allowEmptyString('cumulative_intr_repaid_eur');

        $validator
            ->numeric('cumulative_intr_repaid')
            ->allowEmptyString('cumulative_intr_repaid');

        $validator
            ->numeric('fair_value')
            ->allowEmptyString('fair_value');

        $validator
            ->numeric('fair_value_eur')
            ->allowEmptyString('fair_value_eur');

        $validator
            ->numeric('fair_value_curr')
            ->allowEmptyString('fair_value_curr');

        $validator
            ->date('sme_rating_date')
            ->allowEmptyDate('sme_rating_date');

        $validator
            ->numeric('provisioned_amount')
            ->allowEmptyString('provisioned_amount');

        $validator
            ->numeric('provisioned_amount_eur')
            ->allowEmptyString('provisioned_amount_eur');

        $validator
            ->numeric('provisioned_amount_curr')
            ->allowEmptyString('provisioned_amount_curr');

        $validator
            ->numeric('recovery_amount')
            ->allowEmptyString('recovery_amount');

        $validator
            ->numeric('recovery_amount_eur')
            ->allowEmptyString('recovery_amount_eur');

        $validator
            ->numeric('recovery_amount_curr')
            ->allowEmptyString('recovery_amount_curr');

        $validator
            ->numeric('equity_kicker_valuation')
            ->allowEmptyString('equity_kicker_valuation');

        $validator
            ->numeric('equity_kicker_valuation_eur')
            ->allowEmptyString('equity_kicker_valuation_eur');

        $validator
            ->numeric('equity_kicker_valuation_curr')
            ->allowEmptyString('equity_kicker_valuation_curr');

        $validator
            ->numeric('collateral_amount')
            ->allowEmptyString('collateral_amount');

        $validator
            ->numeric('collateral_amount_eur')
            ->allowEmptyString('collateral_amount_eur');

        $validator
            ->numeric('collateral_amount_curr')
            ->allowEmptyString('collateral_amount_curr');

        $validator
            ->numeric('current_income')
            ->allowEmptyString('current_income');

        $validator
            ->scalar('bds_received')
            ->maxLength('bds_received', 1)
            ->allowEmptyString('bds_received');

        $validator
            ->scalar('fr_status')
            ->maxLength('fr_status', 50)
            ->allowEmptyString('fr_status');

        $validator
            ->scalar('bds_type')
            ->maxLength('bds_type', 50)
            ->allowEmptyString('bds_type');

        $validator
            ->scalar('bds_cost')
            ->maxLength('bds_cost', 50)
            ->allowEmptyString('bds_cost');

        $validator
            ->scalar('covid19_moratorium')
            ->maxLength('covid19_moratorium', 3)
            ->allowEmptyString('covid19_moratorium');

        $validator
            ->numeric('maximum_exposure')
            ->allowEmptyString('maximum_exposure');

        $validator
            ->numeric('maximum_exposure_eur')
            ->allowEmptyString('maximum_exposure_eur');

        $validator
            ->numeric('maximum_exposure_curr')
            ->allowEmptyString('maximum_exposure_curr');

        $validator
            ->scalar('covered_dilution')
            ->maxLength('covered_dilution', 5)
            ->allowEmptyString('covered_dilution');

        $validator
            ->date('covered_dilution_date')
            ->allowEmptyDate('covered_dilution_date');

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
        $rules->add($rules->isUnique(['included_id']), ['errorField' => 'included_id']);
        $rules->add($rules->existsIn(['transaction_id'], 'Transactions'), ['errorField' => 'transaction_id']);
        $rules->add($rules->existsIn(['sme_id'], 'Smes'), ['errorField' => 'sme_id']);
        $rules->add($rules->existsIn(['portfolio_id'], 'Portfolios'), ['errorField' => 'portfolio_id']);
        $rules->add($rules->existsIn(['report_id'], 'Reports'), ['errorField' => 'report_id']);

        return $rules;
    }
}
