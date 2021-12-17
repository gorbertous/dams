<?php

declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 *  Model
 *
 * @property \App\Model\Table\ParentPdlrsTable&\Cake\ORM\Association\BelongsTo $ParentPdlrs
 * @property \App\Model\Table\SmesTable&\Cake\ORM\Association\BelongsTo $Smes
 * @property \App\Model\Table\TransactionsTable&\Cake\ORM\Association\BelongsTo $Transactions
 * @property \App\Model\Table\SubtransactionsTable&\Cake\ORM\Association\BelongsTo $Subtransactions
 * @property \App\Model\Table\PortfoliosTable&\Cake\ORM\Association\BelongsTo $Portfolios
 * @property \App\Model\Table\ReportsTable&\Cake\ORM\Association\BelongsTo $Reports
 * @property \App\Model\Table\ParentReportsTable&\Cake\ORM\Association\BelongsTo $ParentReports
 * @property \App\Model\Table\IncludedFrspsTable&\Cake\ORM\Association\BelongsTo $IncludedFrsps
 *
 * @method \App\Model\Entity\PdlrTransaction newEmptyEntity()
 * @method \App\Model\Entity\PdlrTransaction newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\PdlrTransaction[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PdlrTransaction get($primaryKey, $options = [])
 * @method \App\Model\Entity\PdlrTransaction findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\PdlrTransaction patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PdlrTransaction[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\PdlrTransaction|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PdlrTransaction saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PdlrTransaction[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\PdlrTransaction[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\PdlrTransaction[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\PdlrTransaction[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PdlrTransactionTable extends Table
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

        $this->setTable('pdlr_transactions');
        $this->setDisplayField('pdlr_id');
        $this->setPrimaryKey('pdlr_id');

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
                ->integer('pdlr_id')
                ->allowEmptyString('pdlr_id', null, 'create');

        $validator
                ->scalar('default_type')
                ->maxLength('default_type', 50)
                ->allowEmptyString('default_type');

        $validator
                ->scalar('default_reason')
                ->maxLength('default_reason', 50)
                ->allowEmptyString('default_reason');

        $validator
                ->scalar('default_flag')
                ->maxLength('default_flag', 50)
                ->allowEmptyString('default_flag');

        $validator
                ->date('default_date')
                ->allowEmptyDate('default_date');

        $validator
                ->scalar('currency')
                ->maxLength('currency', 50)
                ->allowEmptyString('currency');

        $validator
                ->numeric('fx_rate')
                ->allowEmptyString('fx_rate');

        $validator
                ->numeric('principal_loss_amount')
                ->allowEmptyString('principal_loss_amount');

        $validator
                ->numeric('principal_loss_amount_eur')
                ->allowEmptyString('principal_loss_amount_eur');

        $validator
                ->numeric('principal_loss_amount_curr')
                ->allowEmptyString('principal_loss_amount_curr');

        $validator
                ->numeric('unpaid_interest')
                ->allowEmptyString('unpaid_interest');

        $validator
                ->numeric('unpaid_interest_eur')
                ->allowEmptyString('unpaid_interest_eur');

        $validator
                ->numeric('unpaid_interest_curr')
                ->allowEmptyString('unpaid_interest_curr');

        $validator
                ->numeric('permit_add_inter_amount')
                ->allowEmptyString('permit_add_inter_amount');

        $validator
                ->numeric('permit_add_inter_amount_eur')
                ->allowEmptyString('permit_add_inter_amount_eur');

        $validator
                ->numeric('permit_add_inter_amount_curr')
                ->allowEmptyString('permit_add_inter_amount_curr');

        $validator
                ->numeric('other_costs')
                ->allowEmptyString('other_costs');

        $validator
                ->numeric('other_costs_eur')
                ->allowEmptyString('other_costs_eur');

        $validator
                ->numeric('other_costs_curr')
                ->allowEmptyString('other_costs_curr');

        $validator
                ->numeric('total_loss')
                ->allowEmptyString('total_loss');

        $validator
                ->numeric('total_loss_eur')
                ->allowEmptyString('total_loss_eur');

        $validator
                ->numeric('total_loss_curr')
                ->allowEmptyString('total_loss_curr');

        $validator
                ->date('recovery_date')
                ->allowEmptyDate('recovery_date');

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
                ->numeric('total_interest')
                ->allowEmptyString('total_interest');

        $validator
                ->numeric('total_interest_eur')
                ->allowEmptyString('total_interest_eur');

        $validator
                ->numeric('total_interest_curr')
                ->allowEmptyString('total_interest_curr');

        $validator
                ->numeric('eif_due_amount')
                ->allowEmptyString('eif_due_amount');

        $validator
                ->numeric('eif_due_amount_eur')
                ->allowEmptyString('eif_due_amount_eur');

        $validator
                ->numeric('eif_due_amount_curr')
                ->allowEmptyString('eif_due_amount_curr');

        $validator
                ->date('fi_guarantee_call_date')
                ->allowEmptyDate('fi_guarantee_call_date');

        $validator
                ->date('fi_payment_date')
                ->allowEmptyDate('fi_payment_date');

        $validator
                ->numeric('fi_paid_amount')
                ->allowEmptyString('fi_paid_amount');

        $validator
                ->numeric('fi_paid_amount_eur')
                ->allowEmptyString('fi_paid_amount_eur');

        $validator
                ->numeric('fi_paid_amount_curr')
                ->allowEmptyString('fi_paid_amount_curr');

        $validator
                ->scalar('comments')
                ->maxLength('comments', 4294967295)
                ->allowEmptyString('comments');

        $validator
                ->date('receive_date')
                ->allowEmptyDate('receive_date');

        $validator
                ->date('due_date')
                ->allowEmptyDate('due_date');

        $validator
                ->date('value_date')
                ->allowEmptyDate('value_date');

        $validator
                ->scalar('status')
                ->maxLength('status', 255)
                ->allowEmptyString('status');

        $validator
                ->scalar('waiver')
                ->allowEmptyString('waiver');

        $validator
                ->scalar('report_type')
                ->maxLength('report_type', 2)
                ->allowEmptyString('report_type');

        $validator
                ->numeric('interest_repaid_curr')
                ->allowEmptyString('interest_repaid_curr');

        $validator
                ->numeric('interest_repaid_eur')
                ->allowEmptyString('interest_repaid_eur');

        $validator
                ->numeric('interest_repaid')
                ->allowEmptyString('interest_repaid');

        $validator
                ->scalar('error_message')
                ->maxLength('error_message', 1024)
                ->allowEmptyString('error_message');

        $validator
                ->scalar('pkid')
                ->maxLength('pkid', 32)
                ->allowEmptyString('pkid');

        $validator
                ->scalar('sampled')
                ->allowEmptyString('sampled');

        $validator
                ->date('sampling_date')
                ->allowEmptyDate('sampling_date');

        $validator
                ->integer('sampled_month')
                ->allowEmptyString('sampled_month');

        $validator
                ->integer('sampled_year')
                ->allowEmptyString('sampled_year');

        $validator
                ->date('document_request_date')
                ->allowEmptyDate('document_request_date');

        $validator
                ->date('document_receive_date')
                ->allowEmptyDate('document_receive_date');

        $validator
                ->date('sampling_closing_date')
                ->allowEmptyDate('sampling_closing_date');

        $validator
                ->scalar('sampling_finding')
                ->maxLength('sampling_finding', 50)
                ->allowEmptyString('sampling_finding');

        $validator
                ->numeric('sample_impact_eur')
                ->allowEmptyString('sample_impact_eur');

        $validator
                ->scalar('sample_comment')
                ->maxLength('sample_comment', 512)
                ->allowEmptyString('sample_comment');

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
        $rules->add($rules->existsIn(['parent_pdlr_id'], 'ParentPdlrs'), ['errorField' => 'parent_pdlr_id']);
        $rules->add($rules->existsIn(['sme_id'], 'Smes'), ['errorField' => 'sme_id']);
        $rules->add($rules->existsIn(['transaction_id'], 'Transactions'), ['errorField' => 'transaction_id']);
        $rules->add($rules->existsIn(['subtransaction_id'], 'Subtransactions'), ['errorField' => 'subtransaction_id']);
        $rules->add($rules->existsIn(['portfolio_id'], 'Portfolios'), ['errorField' => 'portfolio_id']);
        $rules->add($rules->existsIn(['report_id'], 'Reports'), ['errorField' => 'report_id']);
        $rules->add($rules->existsIn(['parent_report_id'], 'ParentReports'), ['errorField' => 'parent_report_id']);
        $rules->add($rules->existsIn(['included_frsp_id'], 'IncludedFrsps'), ['errorField' => 'included_frsp_id']);

        return $rules;
    }

}
