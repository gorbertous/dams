<?php
declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Transactions Model
 *
 * @property \App\Model\Table\SmesTable&\Cake\ORM\Association\BelongsTo $Smes
 * @property \App\Model\Table\PortfoliosTable&\Cake\ORM\Association\BelongsTo $Portfolios
 * @property \App\Model\Table\ReportsTable&\Cake\ORM\Association\BelongsTo $Reports
 *
 * @method \App\Model\Entity\Transaction newEmptyEntity()
 * @method \App\Model\Entity\Transaction newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Transaction[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Transaction get($primaryKey, $options = [])
 * @method \App\Model\Entity\Transaction findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Transaction patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Transaction[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Transaction|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Transaction saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Transaction[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Transaction[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Transaction[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Transaction[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
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
        $this->setDisplayField('transaction_id');
        $this->setPrimaryKey('transaction_id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Damsv2.Sme', [
            'foreignKey' => 'sme_id',
        ]);
        $this->belongsTo('Damsv2.Portfolio', [
            'foreignKey' => 'portfolio_id',
        ]);
        $this->belongsTo('Damsv2.Report', [
            'foreignKey' => 'report_id',
        ]);
         $this->hasMany('Damsv2.IncludedTransactions', [
            'foreignKey' => 'transaction_id',
        ]);
          $this->hasOne('Guarantee', [
            'foreignKey' => 'transaction_id',
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
            ->integer('transaction_id')
            ->allowEmptyString('transaction_id', null, 'create')
            ->add('transaction_id', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('fiscal_number')
            ->maxLength('fiscal_number', 240)
            ->allowEmptyString('fiscal_number');

        $validator
            ->scalar('siret')
            ->maxLength('siret', 255)
            ->allowEmptyString('siret');

        $validator
            ->scalar('transaction_reference')
            ->maxLength('transaction_reference', 240)
            ->requirePresence('transaction_reference', 'create')
            ->notEmptyString('transaction_reference');

        $validator
            ->scalar('currency')
            ->maxLength('currency', 45)
            ->allowEmptyString('currency');

        $validator
            ->numeric('fx_rate')
            ->allowEmptyString('fx_rate');

        $validator
            ->scalar('purpose')
            ->maxLength('purpose', 240)
            ->allowEmptyString('purpose');

        $validator
            ->numeric('investment_amount')
            ->allowEmptyString('investment_amount');

        $validator
            ->numeric('investment_amount_eur')
            ->allowEmptyString('investment_amount_eur');

        $validator
            ->numeric('investment_amount_curr')
            ->allowEmptyString('investment_amount_curr');

        $validator
            ->numeric('working_capital')
            ->allowEmptyString('working_capital');

        $validator
            ->numeric('working_capital_eur')
            ->allowEmptyString('working_capital_eur');

        $validator
            ->numeric('working_capital_curr')
            ->allowEmptyString('working_capital_curr');

        $validator
            ->numeric('principal_amount')
            ->allowEmptyString('principal_amount');

        $validator
            ->numeric('principal_amount_eur')
            ->allowEmptyString('principal_amount_eur');

        $validator
            ->numeric('principal_amount_curr')
            ->allowEmptyString('principal_amount_curr');

        $validator
            ->numeric('purchase_price')
            ->allowEmptyString('purchase_price');

        $validator
            ->numeric('purchase_price_eur')
            ->allowEmptyString('purchase_price_eur');

        $validator
            ->numeric('purchase_price_curr')
            ->allowEmptyString('purchase_price_curr');

        $validator
            ->numeric('down_payment')
            ->allowEmptyString('down_payment');

        $validator
            ->numeric('down_payment_eur')
            ->allowEmptyString('down_payment_eur');

        $validator
            ->numeric('down_payment_curr')
            ->allowEmptyString('down_payment_curr');

        $validator
            ->numeric('baloon_amount')
            ->allowEmptyString('baloon_amount');

        $validator
            ->numeric('baloon_amount_eur')
            ->allowEmptyString('baloon_amount_eur');

        $validator
            ->numeric('baloon_amount_curr')
            ->allowEmptyString('baloon_amount_curr');

        $validator
            ->numeric('maturity')
            ->allowEmptyString('maturity');

        $validator
            ->numeric('additional_maturity')
            ->allowEmptyString('additional_maturity');

        $validator
            ->numeric('grace_period')
            ->allowEmptyString('grace_period');

        $validator
            ->date('final_maturity_date')
            ->allowEmptyDate('final_maturity_date');

        $validator
            ->date('signature_date')
            ->allowEmptyDate('signature_date');

        $validator
            ->date('first_disbursement_date')
            ->allowEmptyDate('first_disbursement_date');

        $validator
            ->date('first_instalment_date')
            ->allowEmptyDate('first_instalment_date');

        $validator
            ->scalar('repayment_frequency')
            ->maxLength('repayment_frequency', 150)
            ->allowEmptyString('repayment_frequency');

        $validator
            ->numeric('collateralisation_rate')
            ->allowEmptyString('collateralisation_rate');

        $validator
            ->scalar('standard_rate')
            ->maxLength('standard_rate', 200)
            ->allowEmptyString('standard_rate');

        $validator
            ->scalar('reference_rate')
            ->maxLength('reference_rate', 100)
            ->allowEmptyString('reference_rate');

        $validator
            ->date('interest_rate_date')
            ->allowEmptyDate('interest_rate_date');

        $validator
            ->numeric('interest_rate')
            ->allowEmptyString('interest_rate');

        $validator
            ->scalar('interest_rate_txt')
            ->maxLength('interest_rate_txt', 50)
            ->allowEmptyString('interest_rate_txt');

        $validator
            ->scalar('interest_rate_type')
            ->maxLength('interest_rate_type', 50)
            ->allowEmptyString('interest_rate_type');

        $validator
            ->numeric('rsi_guarantee_fee_rate')
            ->allowEmptyString('rsi_guarantee_fee_rate');

        $validator
            ->numeric('lgd')
            ->allowEmptyString('lgd');

        $validator
            ->numeric('total_project_cost')
            ->allowEmptyString('total_project_cost');

        $validator
            ->numeric('total_project_cost_eur')
            ->allowEmptyString('total_project_cost_eur');

        $validator
            ->numeric('total_project_cost_curr')
            ->allowEmptyString('total_project_cost_curr');

        $validator
            ->numeric('allocation_amount')
            ->allowEmptyString('allocation_amount');

        $validator
            ->numeric('allocation_amount_eur')
            ->allowEmptyString('allocation_amount_eur');

        $validator
            ->numeric('allocation_amount_curr')
            ->allowEmptyString('allocation_amount_curr');

        $validator
            ->scalar('project_description')
            ->maxLength('project_description', 100)
            ->allowEmptyString('project_description');

        $validator
            ->scalar('on_lending_bank')
            ->maxLength('on_lending_bank', 100)
            ->allowEmptyString('on_lending_bank');

        $validator
            ->scalar('olb_address')
            ->maxLength('olb_address', 255)
            ->allowEmptyString('olb_address');

        $validator
            ->scalar('olb_postal_code')
            ->maxLength('olb_postal_code', 30)
            ->allowEmptyString('olb_postal_code');

        $validator
            ->scalar('olb_place')
            ->maxLength('olb_place', 255)
            ->allowEmptyString('olb_place');

        $validator
            ->scalar('pass_through_institution')
            ->maxLength('pass_through_institution', 100)
            ->allowEmptyString('pass_through_institution');

        $validator
            ->scalar('acc_flag')
            ->maxLength('acc_flag', 100)
            ->allowEmptyString('acc_flag');

        $validator
            ->date('acc_date')
            ->allowEmptyDate('acc_date');

        $validator
            ->scalar('acc_type')
            ->maxLength('acc_type', 100)
            ->allowEmptyString('acc_type');

        $validator
            ->numeric('ori_principal_amount')
            ->allowEmptyString('ori_principal_amount');

        $validator
            ->numeric('ori_principal_amount_eur')
            ->allowEmptyString('ori_principal_amount_eur');

        $validator
            ->numeric('ori_principal_amount_curr')
            ->allowEmptyString('ori_principal_amount_curr');

        $validator
            ->scalar('partial_exclusion')
            ->maxLength('partial_exclusion', 100)
            ->allowEmptyString('partial_exclusion');

        $validator
            ->scalar('amortisation_profile')
            ->maxLength('amortisation_profile', 250)
            ->allowEmptyFile('amortisation_profile');

        $validator
            ->scalar('investment_location')
            ->maxLength('investment_location', 90)
            ->allowEmptyString('investment_location');

        $validator
            ->scalar('investment_location_lau')
            ->maxLength('investment_location_lau', 30)
            ->allowEmptyString('investment_location_lau');

        $validator
            ->scalar('territory_type')
            ->maxLength('territory_type', 90)
            ->allowEmptyString('territory_type');

        $validator
            ->numeric('gge_amount')
            ->allowEmptyString('gge_amount');

        $validator
            ->numeric('gge_amount_eur')
            ->allowEmptyString('gge_amount_eur');

        $validator
            ->numeric('gge_amount_curr')
            ->allowEmptyString('gge_amount_curr');

        $validator
            ->integer('gge_change')
            ->allowEmptyString('gge_change');

        $validator
            ->date('gge_modification_date')
            ->allowEmptyDate('gge_modification_date');

        $validator
            ->numeric('gge_additional')
            ->allowEmptyString('gge_additional');

        $validator
            ->numeric('gge_additional_eur')
            ->allowEmptyString('gge_additional_eur');

        $validator
            ->numeric('gge_additional_curr')
            ->allowEmptyString('gge_additional_curr');

        $validator
            ->scalar('gge_calc_method')
            ->maxLength('gge_calc_method', 3)
            ->allowEmptyString('gge_calc_method');

        $validator
            ->allowEmptyString('sme_history_at_trn');

        $validator
            ->allowEmptyString('sme_history_at_report');

        $validator
            ->scalar('transaction_comments')
            ->maxLength('transaction_comments', 1024)
            ->allowEmptyString('transaction_comments');

        $validator
            ->scalar('error_message')
            ->maxLength('error_message', 1024)
            ->allowEmptyString('error_message');

        $validator
            ->scalar('loan_type')
            ->maxLength('loan_type', 240)
            ->allowEmptyString('loan_type');

        $validator
            ->scalar('invest_nace_bg')
            ->maxLength('invest_nace_bg', 255)
            ->allowEmptyString('invest_nace_bg');

        $validator
            ->scalar('waiver')
            ->maxLength('waiver', 3)
            ->allowEmptyString('waiver');

        $validator
            ->scalar('waiver_reason')
            ->maxLength('waiver_reason', 500)
            ->allowEmptyString('waiver_reason');

        $validator
            ->scalar('waiver_details')
            ->maxLength('waiver_details', 200)
            ->allowEmptyString('waiver_details');

        $validator
            ->numeric('applied_guarantee_rate')
            ->allowEmptyString('applied_guarantee_rate');

        $validator
            ->numeric('applied_cap_rate')
            ->allowEmptyString('applied_cap_rate');

        $validator
            ->scalar('thematic_category')
            ->maxLength('thematic_category', 50)
            ->allowEmptyString('thematic_category');

        $validator
            ->scalar('transaction_status')
            ->maxLength('transaction_status', 200)
            ->allowEmptyString('transaction_status');

        $validator
            ->scalar('trn_exclusion_flag')
            ->maxLength('trn_exclusion_flag', 50)
            ->allowEmptyString('trn_exclusion_flag');

        $validator
            ->scalar('early_termination')
            ->maxLength('early_termination', 100)
            ->allowEmptyString('early_termination');

        $validator
            ->numeric('sme_turnover')
            ->allowEmptyString('sme_turnover');

        $validator
            ->numeric('sme_assets')
            ->allowEmptyString('sme_assets');

        $validator
            ->scalar('sme_target_beneficiary')
            ->maxLength('sme_target_beneficiary', 50)
            ->allowEmptyString('sme_target_beneficiary');

        $validator
            ->numeric('sme_nbr_employees')
            ->allowEmptyString('sme_nbr_employees');

        $validator
            ->scalar('sme_sector')
            ->maxLength('sme_sector', 90)
            ->allowEmptyString('sme_sector');

        $validator
            ->scalar('sme_rating')
            ->maxLength('sme_rating', 50)
            ->allowEmptyString('sme_rating');

        $validator
            ->scalar('sme_current_rating')
            ->maxLength('sme_current_rating', 50)
            ->allowEmptyString('sme_current_rating');

        $validator
            ->scalar('sme_borrower_type')
            ->maxLength('sme_borrower_type', 100)
            ->allowEmptyString('sme_borrower_type');

        $validator
            ->scalar('sme_eligibility_criteria')
            ->maxLength('sme_eligibility_criteria', 100)
            ->allowEmptyString('sme_eligibility_criteria');

        $validator
            ->scalar('sme_level_digitalization')
            ->maxLength('sme_level_digitalization', 255)
            ->allowEmptyString('sme_level_digitalization');

        $validator
            ->numeric('tangible_assets')
            ->allowEmptyString('tangible_assets');

        $validator
            ->numeric('tangible_assets_eur')
            ->allowEmptyString('tangible_assets_eur');

        $validator
            ->numeric('tangible_assets_curr')
            ->allowEmptyString('tangible_assets_curr');

        $validator
            ->numeric('intangible_assets')
            ->allowEmptyString('intangible_assets');

        $validator
            ->numeric('intangible_assets_eur')
            ->allowEmptyString('intangible_assets_eur');

        $validator
            ->numeric('intangible_assets_curr')
            ->allowEmptyString('intangible_assets_curr');

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
            ->scalar('collateral_type')
            ->maxLength('collateral_type', 50)
            ->allowEmptyString('collateral_type');

        $validator
            ->scalar('eu_program')
            ->maxLength('eu_program', 50)
            ->allowEmptyString('eu_program');

        $validator
            ->scalar('EFSI_trn')
            ->maxLength('EFSI_trn', 15)
            ->allowEmptyString('EFSI_trn');

        $validator
            ->scalar('retroactivity_flag')
            ->maxLength('retroactivity_flag', 4)
            ->allowEmptyString('retroactivity_flag');

        $validator
            ->scalar('sme_fi_rating_scale')
            ->maxLength('sme_fi_rating_scale', 50)
            ->allowEmptyString('sme_fi_rating_scale');

        $validator
            ->scalar('sme_current_fi_rating_scale')
            ->maxLength('sme_current_fi_rating_scale', 50)
            ->allowEmptyString('sme_current_fi_rating_scale');

        $validator
            ->scalar('publication')
            ->maxLength('publication', 1)
            ->allowEmptyString('publication');

        $validator
            ->numeric('fi_risk_sharing_rate')
            ->allowEmptyString('fi_risk_sharing_rate');

        $validator
            ->date('fi_signature_date')
            ->allowEmptyDate('fi_signature_date');

        $validator
            ->scalar('eco_innovation')
            ->maxLength('eco_innovation', 1)
            ->allowEmptyString('eco_innovation');

        $validator
            ->scalar('converted_reference')
            ->maxLength('converted_reference', 250)
            ->allowEmptyString('converted_reference');

        $validator
            ->date('conversion_date')
            ->allowEmptyDate('conversion_date');

        $validator
            ->scalar('product_type')
            ->maxLength('product_type', 250)
            ->allowEmptyString('product_type');

        $validator
            ->numeric('recovery_rate')
            ->allowEmptyString('recovery_rate');

        $validator
            ->numeric('interest_reduction')
            ->allowEmptyString('interest_reduction');

        $validator
            ->date('reperforming_start')
            ->allowEmptyDate('reperforming_start');

        $validator
            ->date('reperforming_end')
            ->allowEmptyDate('reperforming_end');

        $validator
            ->scalar('reperforming')
            ->maxLength('reperforming', 50)
            ->allowEmptyString('reperforming');

        $validator
            ->scalar('fi')
            ->maxLength('fi', 200)
            ->allowEmptyString('fi');

        $validator
            ->scalar('periodic_fee')
            ->maxLength('periodic_fee', 1)
            ->allowEmptyString('periodic_fee');

        $validator
            ->scalar('permitted_add_inter_freq')
            ->maxLength('permitted_add_inter_freq', 150)
            ->allowEmptyString('permitted_add_inter_freq');

        $validator
            ->scalar('permitted_add_interest')
            ->maxLength('permitted_add_interest', 1)
            ->allowEmptyString('permitted_add_interest');

        $validator
            ->scalar('sampled')
            ->allowEmptyString('sampled');

        $validator
            ->date('sampling_date')
            ->allowEmptyDate('sampling_date');

        $validator
            ->scalar('fi_review_sme_status')
            ->maxLength('fi_review_sme_status', 3)
            ->allowEmptyString('fi_review_sme_status');

        $validator
            ->scalar('fi_review_refinancing')
            ->maxLength('fi_review_refinancing', 3)
            ->allowEmptyString('fi_review_refinancing');

        $validator
            ->scalar('fi_review_purpose')
            ->maxLength('fi_review_purpose', 3)
            ->allowEmptyString('fi_review_purpose');

        $validator
            ->scalar('fi_review_sector')
            ->maxLength('fi_review_sector', 3)
            ->allowEmptyString('fi_review_sector');

        $validator
            ->scalar('linked_trn')
            ->maxLength('linked_trn', 240)
            ->allowEmptyString('linked_trn');

        $validator
            ->scalar('stand_alone_loan')
            ->maxLength('stand_alone_loan', 30)
            ->allowEmptyString('stand_alone_loan');

        $validator
            ->numeric('operation_type')
            ->allowEmptyString('operation_type');

        $validator
            ->scalar('priority_theme')
            ->maxLength('priority_theme', 240)
            ->allowEmptyString('priority_theme');

        $validator
            ->numeric('state_aid_benefit')
            ->allowEmptyString('state_aid_benefit');

        $validator
            ->scalar('cn_code')
            ->maxLength('cn_code', 30)
            ->allowEmptyString('cn_code');

        $validator
            ->scalar('renewal_generation')
            ->maxLength('renewal_generation', 10)
            ->allowEmptyString('renewal_generation');

        $validator
            ->scalar('thematic_focus')
            ->maxLength('thematic_focus', 255)
            ->allowEmptyString('thematic_focus');

        $validator
            ->scalar('agricultural_branch')
            ->maxLength('agricultural_branch', 50)
            ->allowEmptyString('agricultural_branch');

        $validator
            ->numeric('agg_co_lenders_financing')
            ->allowEmptyString('agg_co_lenders_financing');

        $validator
            ->numeric('agg_co_lenders_financing_eur')
            ->allowEmptyString('agg_co_lenders_financing_eur');

        $validator
            ->numeric('agg_co_lenders_financing_curr')
            ->allowEmptyString('agg_co_lenders_financing_curr');

        $validator
            ->numeric('nb_co_lenders')
            ->allowEmptyString('nb_co_lenders');

        $validator
            ->numeric('commitment_fee')
            ->allowEmptyString('commitment_fee');

        $validator
            ->numeric('ori_issue_discount')
            ->allowEmptyString('ori_issue_discount');

        $validator
            ->numeric('prepayment_penalty')
            ->allowEmptyString('prepayment_penalty');

        $validator
            ->numeric('upfront_fee')
            ->allowEmptyString('upfront_fee');

        $validator
            ->numeric('pik_interest_rate')
            ->allowEmptyString('pik_interest_rate');

        $validator
            ->scalar('pik_frequency')
            ->maxLength('pik_frequency', 50)
            ->allowEmptyString('pik_frequency');

        $validator
            ->scalar('equity_kicker')
            ->maxLength('equity_kicker', 50)
            ->allowEmptyString('equity_kicker');

        $validator
            ->numeric('residual_value')
            ->allowEmptyString('residual_value');

        $validator
            ->numeric('residual_value_eur')
            ->allowEmptyString('residual_value_eur');

        $validator
            ->numeric('residual_value_curr')
            ->allowEmptyString('residual_value_curr');

        $validator
            ->scalar('third_party_guarantor')
            ->maxLength('third_party_guarantor', 50)
            ->allowEmptyString('third_party_guarantor');

        $validator
            ->numeric('guaranteed_percentage')
            ->allowEmptyString('guaranteed_percentage');

        $validator
            ->scalar('primary_investment')
            ->maxLength('primary_investment', 1)
            ->allowEmptyString('primary_investment');

        $validator
            ->scalar('senior_debt')
            ->maxLength('senior_debt', 1)
            ->allowEmptyString('senior_debt');

        $validator
            ->scalar('non_distressed_instrument')
            ->maxLength('non_distressed_instrument', 1)
            ->allowEmptyString('non_distressed_instrument');

        $validator
            ->scalar('exclusion_flag')
            ->maxLength('exclusion_flag', 50)
            ->allowEmptyString('exclusion_flag');

        $validator
            ->scalar('exclusion_reason')
            ->maxLength('exclusion_reason', 50)
            ->allowEmptyString('exclusion_reason');

        $validator
            ->scalar('youth_employment_loan')
            ->maxLength('youth_employment_loan', 1)
            ->allowEmptyString('youth_employment_loan');

        $validator
            ->scalar('eligible_investment_skills')
            ->maxLength('eligible_investment_skills', 512)
            ->allowEmptyString('eligible_investment_skills');

        $validator
            ->scalar('field_study')
            ->maxLength('field_study', 512)
            ->allowEmptyString('field_study');

        $validator
            ->scalar('level_edu_programme')
            ->maxLength('level_edu_programme', 512)
            ->allowEmptyString('level_edu_programme');

        $validator
            ->scalar('country_study')
            ->maxLength('country_study', 512)
            ->allowEmptyString('country_study');

        $validator
            ->numeric('study_duration')
            ->allowEmptyString('study_duration');

        $validator
            ->numeric('periodic_fee_rate')
            ->allowEmptyString('periodic_fee_rate');

        $validator
            ->scalar('fee_int_rate_period')
            ->maxLength('fee_int_rate_period', 255)
            ->allowEmptyString('fee_int_rate_period');

        $validator
            ->numeric('one_off_fee')
            ->allowEmptyString('one_off_fee');

        $validator
            ->scalar('covid19_moratorium')
            ->maxLength('covid19_moratorium', 3)
            ->allowEmptyString('covid19_moratorium');

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
        $rules->add($rules->isUnique(['transaction_id']), ['errorField' => 'transaction_id']);
        $rules->add($rules->existsIn(['sme_id'], 'Smes'), ['errorField' => 'sme_id']);
        $rules->add($rules->existsIn(['portfolio_id'], 'Portfolios'), ['errorField' => 'portfolio_id']);
        $rules->add($rules->existsIn(['report_id'], 'Reports'), ['errorField' => 'report_id']);

        return $rules;
    }
}
