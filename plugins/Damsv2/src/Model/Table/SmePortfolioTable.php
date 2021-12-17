<?php

declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SmePortfolio Model
 *
 * @property \App\Model\Table\SmesTable&\Cake\ORM\Association\BelongsTo $Smes
 * @property \App\Model\Table\ReportsTable&\Cake\ORM\Association\BelongsTo $Reports
 * @property \App\Model\Table\PortfoliosTable&\Cake\ORM\Association\BelongsTo $Portfolios
 *
 * @method \App\Model\Entity\SmePortfolio newEmptyEntity()
 * @method \App\Model\Entity\SmePortfolio newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\SmePortfolio[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SmePortfolio get($primaryKey, $options = [])
 * @method \App\Model\Entity\SmePortfolio findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\SmePortfolio patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SmePortfolio[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\SmePortfolio|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SmePortfolio saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SmePortfolio[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SmePortfolio[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\SmePortfolio[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SmePortfolio[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SmePortfolioTable extends Table
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

        $this->setTable('sme_portfolio');
        $this->setDisplayField('fiscal_number');
        $this->setPrimaryKey('sme_portfolio_id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Damsv2.Sme', [
            'foreignKey' => 'sme_id',
            'joinType'   => 'INNER',
        ]);
        $this->belongsTo('Damsv2.Report', [
            'foreignKey' => 'report_id'            
        ]);
        $this->belongsTo('Damsv2.Portfolio', [
            'foreignKey' => 'portfolio_id',
            'joinType'   => 'INNER',
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
                ->integer('sme_portfolio_id')
                ->allowEmptyString('sme_portfolio_id', null, 'create');

        $validator
                ->scalar('fiscal_number')
                ->maxLength('fiscal_number', 255)
                ->allowEmptyString('fiscal_number');

        $validator
                ->scalar('siret')
                ->maxLength('siret', 255)
                ->allowEmptyString('siret');

        $validator
                ->scalar('name')
                ->maxLength('name', 255)
                ->allowEmptyString('name');

        $validator
                ->scalar('surname')
                ->maxLength('surname', 255)
                ->allowEmptyString('surname');

        $validator
                ->scalar('first_name')
                ->maxLength('first_name', 255)
                ->allowEmptyString('first_name');

        $validator
                ->scalar('phone')
                ->maxLength('phone', 255)
                ->allowEmptyString('phone');

        $validator
                ->scalar('address')
                ->maxLength('address', 255)
                ->allowEmptyString('address');

        $validator
                ->email('email')
                ->allowEmptyString('email');

        $validator
                ->scalar('gender')
                ->maxLength('gender', 255)
                ->allowEmptyString('gender');

        $validator
                ->scalar('postal_code')
                ->maxLength('postal_code', 30)
                ->allowEmptyString('postal_code');

        $validator
                ->scalar('place')
                ->maxLength('place', 255)
                ->allowEmptyString('place');

        $validator
                ->scalar('region')
                ->maxLength('region', 90)
                ->allowEmptyString('region');

        $validator
                ->scalar('region_lau')
                ->maxLength('region_lau', 30)
                ->allowEmptyString('region_lau');

        $validator
                ->scalar('country')
                ->maxLength('country', 90)
                ->allowEmptyString('country');

        $validator
                ->scalar('country_main_operations')
                ->maxLength('country_main_operations', 10)
                ->allowEmptyString('country_main_operations');

        $validator
                ->scalar('nationality')
                ->maxLength('nationality', 255)
                ->allowEmptyString('nationality');

        $validator
                ->scalar('degree_m')
                ->maxLength('degree_m', 255)
                ->allowEmptyString('degree_m');

        $validator
                ->scalar('degree_f')
                ->maxLength('degree_f', 255)
                ->allowEmptyString('degree_f');

        $validator
                ->integer('study_field')
                ->allowEmptyString('study_field');

        $validator
                ->scalar('university')
                ->maxLength('university', 255)
                ->allowEmptyString('university');

        $validator
                ->integer('study_duration')
                ->allowEmptyString('study_duration');

        $validator
                ->scalar('country_study')
                ->maxLength('country_study', 255)
                ->allowEmptyString('country_study');

        $validator
                ->scalar('country_edu')
                ->maxLength('country_edu', 255)
                ->allowEmptyString('country_edu');

        $validator
                ->scalar('small_farm')
                ->maxLength('small_farm', 30)
                ->allowEmptyString('small_farm');

        $validator
                ->scalar('young_farmer')
                ->maxLength('young_farmer', 30)
                ->allowEmptyString('young_farmer');

        $validator
                ->scalar('mountain_area')
                ->maxLength('mountain_area', 30)
                ->allowEmptyString('mountain_area');

        $validator
                ->scalar('land_size')
                ->maxLength('land_size', 30)
                ->allowEmptyString('land_size');

        $validator
                ->date('establishment_date')
                ->allowEmptyDate('establishment_date');

        $validator
                ->scalar('sector')
                ->maxLength('sector', 90)
                ->allowEmptyString('sector');

        $validator
                ->scalar('sector_lpa')
                ->maxLength('sector_lpa', 10)
                ->allowEmptyString('sector_lpa');

        $validator
                ->numeric('nbr_employees')
                ->allowEmptyString('nbr_employees');

        $validator
                ->scalar('sme_rating')
                ->maxLength('sme_rating', 50)
                ->allowEmptyString('sme_rating');

        $validator
                ->scalar('startup')
                ->maxLength('startup', 1)
                ->allowEmptyString('startup');

        $validator
                ->scalar('innovative')
                ->maxLength('innovative', 50)
                ->allowEmptyString('innovative');

        $validator
                ->scalar('waiver')
                ->maxLength('waiver', 3)
                ->allowEmptyString('waiver');

        $validator
                ->scalar('waiver_reason')
                ->maxLength('waiver_reason', 1024)
                ->allowEmptyString('waiver_reason');

        $validator
                ->numeric('turnover')
                ->allowEmptyString('turnover');

        $validator
                ->numeric('assets')
                ->allowEmptyString('assets');

        $validator
                ->numeric('ebitda')
                ->allowEmptyString('ebitda');

        $validator
                ->numeric('net_debt_to_ebitda')
                ->allowEmptyString('net_debt_to_ebitda');

        $validator
                ->scalar('eligible_beneficiary')
                ->maxLength('eligible_beneficiary', 50)
                ->allowEmptyString('eligible_beneficiary');

        $validator
                ->scalar('eligible_beneficiary_type')
                ->maxLength('eligible_beneficiary_type', 10)
                ->allowEmptyString('eligible_beneficiary_type');

        $validator
                ->scalar('target_beneficiary')
                ->maxLength('target_beneficiary', 50)
                ->allowEmptyString('target_beneficiary');

        $validator
                ->scalar('borrower_type')
                ->maxLength('borrower_type', 100)
                ->allowEmptyString('borrower_type');

        $validator
                ->scalar('micro_borrowers')
                ->maxLength('micro_borrowers', 3)
                ->allowEmptyString('micro_borrowers');

        $validator
                ->scalar('eligibility_criteria')
                ->maxLength('eligibility_criteria', 100)
                ->allowEmptyString('eligibility_criteria');

        $validator
                ->scalar('level_digitalization')
                ->maxLength('level_digitalization', 255)
                ->allowEmptyString('level_digitalization');

        $validator
                ->scalar('thematic_criteria')
                ->maxLength('thematic_criteria', 100)
                ->allowEmptyString('thematic_criteria');

        $validator
                ->scalar('sme_comments')
                ->maxLength('sme_comments', 4294967295)
                ->allowEmptyString('sme_comments');

        $validator
                ->scalar('error_message')
                ->maxLength('error_message', 1024)
                ->allowEmptyString('error_message');

        $validator
                ->scalar('category')
                ->maxLength('category', 150)
                ->allowEmptyString('category');

        $validator
                ->scalar('fr_category')
                ->maxLength('fr_category', 512)
                ->allowEmptyString('fr_category');

        $validator
                ->numeric('total_loan_amount_curr')
                ->allowEmptyString('total_loan_amount_curr');

        $validator
                ->numeric('total_loan_amount_eur')
                ->allowEmptyString('total_loan_amount_eur');

        $validator
                ->scalar('legal_form')
                ->maxLength('legal_form', 250)
                ->allowEmptyString('legal_form');

        $validator
                ->scalar('employment_status')
                ->maxLength('employment_status', 20)
                ->allowEmptyString('employment_status');

        $validator
                ->scalar('fi_rating_scale')
                ->maxLength('fi_rating_scale', 50)
                ->allowEmptyString('fi_rating_scale');

        $validator
                ->scalar('share_contacts')
                ->maxLength('share_contacts', 50)
                ->allowEmptyString('share_contacts');

        $validator
                ->scalar('natural_person')
                ->maxLength('natural_person', 1)
                ->allowEmptyString('natural_person');

        $validator
                ->scalar('natural_person_calc')
                ->maxLength('natural_person_calc', 20)
                ->allowEmptyString('natural_person_calc');

        $validator
                ->scalar('website')
                ->maxLength('website', 50)
                ->allowEmptyString('website');

        $validator
                ->scalar('social_enterprise')
                ->maxLength('social_enterprise', 50)
                ->allowEmptyString('social_enterprise');

        $validator
                ->scalar('social_sector_org')
                ->maxLength('social_sector_org', 50)
                ->allowEmptyString('social_sector_org');

        $validator
                ->scalar('holding_company')
                ->maxLength('holding_company', 50)
                ->allowEmptyString('holding_company');

        $validator
                ->scalar('part_of_group')
                ->maxLength('part_of_group', 10)
                ->allowEmptyString('part_of_group');

        $validator
                ->scalar('bds_paid')
                ->maxLength('bds_paid', 10)
                ->allowEmptyString('bds_paid');

        $validator
                ->numeric('nbr_young_employed')
                ->allowEmptyString('nbr_young_employed');

        $validator
                ->numeric('nbr_young_training')
                ->allowEmptyString('nbr_young_training');

        $validator
                ->scalar('youth_participant')
                ->maxLength('youth_participant', 1)
                ->allowEmptyString('youth_participant');

        $validator
                ->numeric('personnel_cost')
                ->allowEmptyString('personnel_cost');

        $validator
                ->numeric('labor_market_status')
                ->allowEmptyString('labor_market_status');

        $validator
                ->integer('sme_id_ori')
                ->allowEmptyString('sme_id_ori');

        $validator
                ->scalar('name_ori_alphabet')
                ->maxLength('name_ori_alphabet', 255)
                ->allowEmptyString('name_ori_alphabet');

        $validator
                ->scalar('address_ori_alphabet')
                ->maxLength('address_ori_alphabet', 255)
                ->allowEmptyString('address_ori_alphabet');

        $validator
                ->scalar('place_ori_alphabet')
                ->maxLength('place_ori_alphabet', 255)
                ->allowEmptyString('place_ori_alphabet');

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
        $rules->add($rules->existsIn(['sme_id'], 'Smes'), ['errorField' => 'sme_id']);
        $rules->add($rules->existsIn(['report_id'], 'Reports'), ['errorField' => 'report_id']);
        $rules->add($rules->existsIn(['portfolio_id'], 'Portfolios'), ['errorField' => 'portfolio_id']);

        return $rules;
    }

}
