<?php
declare(strict_types=1);

namespace Dsr\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Vdsrreport Model
 *
 * @property \Dsr\Model\Table\PortfoliosTable&\Cake\ORM\Association\BelongsTo $Portfolios
 *
 * @method \Dsr\Model\Entity\Vdsrreport newEmptyEntity()
 * @method \Dsr\Model\Entity\Vdsrreport newEntity(array $data, array $options = [])
 * @method \Dsr\Model\Entity\Vdsrreport[] newEntities(array $data, array $options = [])
 * @method \Dsr\Model\Entity\Vdsrreport get($primaryKey, $options = [])
 * @method \Dsr\Model\Entity\Vdsrreport findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Dsr\Model\Entity\Vdsrreport patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Dsr\Model\Entity\Vdsrreport[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Dsr\Model\Entity\Vdsrreport|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Dsr\Model\Entity\Vdsrreport saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Dsr\Model\Entity\Vdsrreport[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Dsr\Model\Entity\Vdsrreport[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Dsr\Model\Entity\Vdsrreport[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Dsr\Model\Entity\Vdsrreport[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class VdsrreportTable extends Table
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

        $this->setTable('dsr_report');
        $this->setDisplayField('name');

        $this->belongsTo('Dsr.Portfolios', [
            'foreignKey' => 'portfolio_id',
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
            ->allowEmptyString('period_year');

        $validator
            ->scalar('name')
            ->maxLength('name', 100)
            ->allowEmptyString('name');

        $validator
            ->scalar('fi_name')
            ->maxLength('fi_name', 255)
            ->allowEmptyString('fi_name');

        $validator
            ->notEmptyString('GENDER_MALE');

        $validator
            ->notEmptyString('GENDER_FEMALE');

        $validator
            ->notEmptyString('GENDER_NI');

        $validator
            ->notEmptyString('EMPLOYMENT_EMPLOYED');

        $validator
            ->notEmptyString('EMPLOYMENT_UNEMPLOYED');

        $validator
            ->notEmptyString('EMPLOYMENT_STUDYING');

        $validator
            ->notEmptyString('EMPLOYMENT_INACTIVE');

        $validator
            ->notEmptyString('EMPLOYMENT_NI');

        $validator
            ->notEmptyString('EDUCATION_NONE');

        $validator
            ->notEmptyString('EDUCATION_PRIMARY');

        $validator
            ->notEmptyString('EDUCATION_SECONDARY');

        $validator
            ->notEmptyString('EDUCATION_POST_SEC');

        $validator
            ->notEmptyString('EDUCATION_UNIVERSITY');

        $validator
            ->notEmptyString('EDUCATION_NI');

        $validator
            ->notEmptyString('AGE_LESS_25');

        $validator
            ->notEmptyString('AGE_25_54');

        $validator
            ->notEmptyString('AGE_55_MORE');

        $validator
            ->notEmptyString('AGE_NI');

        $validator
            ->notEmptyString('GROUP_MINORITY');

        $validator
            ->notEmptyString('GROUP_DISABLED');

        $validator
            ->notEmptyString('GROUP_BOTH');

        $validator
            ->notEmptyString('GROUP_NI');

        $validator
            ->numeric('TOTAL_EMPLOYEES')
            ->allowEmptyString('TOTAL_EMPLOYEES');

        $validator
            ->numeric('TOTAL_MALE')
            ->allowEmptyString('TOTAL_MALE');

        $validator
            ->numeric('TOTAL_FEMALE')
            ->allowEmptyString('TOTAL_FEMALE');

        $validator
            ->numeric('TOTAL_LESS_25')
            ->allowEmptyString('TOTAL_LESS_25');

        $validator
            ->numeric('TOTAL_24_54')
            ->allowEmptyString('TOTAL_24_54');

        $validator
            ->numeric('TOTAL_MORE_55')
            ->allowEmptyString('TOTAL_MORE_55');

        $validator
            ->numeric('TOTAL_MINORITY')
            ->allowEmptyString('TOTAL_MINORITY');

        $validator
            ->numeric('TOTAL_DISABLED')
            ->allowEmptyString('TOTAL_DISABLED');

        $validator
            ->numeric('TOTAL_EXPOST_EMPLOYEES')
            ->allowEmptyString('TOTAL_EXPOST_EMPLOYEES');

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
        $rules->add($rules->existsIn(['portfolio_id'], 'Portfolios'), ['errorField' => 'portfolio_id']);

        return $rules;
    }

    /**
     * Returns the database connection name to use by default.
     *
     * @return string
     */
    public static function defaultConnectionName(): string
    {
        return 'dsr';
    }
}
