<?php
declare(strict_types=1);

namespace Dsr\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Loans Model
 *
 * @property \Dsr\Model\Table\ReportsTable&\Cake\ORM\Association\BelongsTo $Reports
 * @property \Dsr\Model\Table\PortfoliosTable&\Cake\ORM\Association\BelongsTo $Portfolios
 *
 * @method \Dsr\Model\Entity\Loan newEmptyEntity()
 * @method \Dsr\Model\Entity\Loan newEntity(array $data, array $options = [])
 * @method \Dsr\Model\Entity\Loan[] newEntities(array $data, array $options = [])
 * @method \Dsr\Model\Entity\Loan get($primaryKey, $options = [])
 * @method \Dsr\Model\Entity\Loan findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Dsr\Model\Entity\Loan patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Dsr\Model\Entity\Loan[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Dsr\Model\Entity\Loan|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Dsr\Model\Entity\Loan saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Dsr\Model\Entity\Loan[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Dsr\Model\Entity\Loan[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Dsr\Model\Entity\Loan[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Dsr\Model\Entity\Loan[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class LoansTable extends Table
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

        $this->setTable('loans');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Dsr.Reports', [
            'foreignKey' => 'report_id',
        ]);
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
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('deal_name')
            ->maxLength('deal_name', 255)
            ->allowEmptyString('deal_name');

        $validator
            ->integer('start_year')
            ->allowEmptyString('start_year');

        $validator
            ->integer('end_year')
            ->allowEmptyString('end_year');

        $validator
            ->scalar('loan_reference')
            ->maxLength('loan_reference', 100)
            ->allowEmptyString('loan_reference');

        $validator
            ->scalar('file_reference')
            ->maxLength('file_reference', 100)
            ->allowEmptyFile('file_reference');

        $validator
            ->scalar('intermediary')
            ->maxLength('intermediary', 200)
            ->allowEmptyString('intermediary');

        $validator
            ->allowEmptyString('gender');

        $validator
            ->allowEmptyString('employment');

        $validator
            ->allowEmptyString('education');

        $validator
            ->allowEmptyString('age');

        $validator
            ->allowEmptyString('specific_group');

        $validator
            ->scalar('country')
            ->maxLength('country', 5)
            ->allowEmptyString('country');

        $validator
            ->scalar('region')
            ->maxLength('region', 5)
            ->allowEmptyString('region');

        $validator
            ->numeric('total_employees')
            ->allowEmptyString('total_employees');

        $validator
            ->numeric('total_male')
            ->allowEmptyString('total_male');

        $validator
            ->numeric('total_female')
            ->allowEmptyString('total_female');

        $validator
            ->numeric('total_less_25')
            ->allowEmptyString('total_less_25');

        $validator
            ->numeric('total_25_54')
            ->allowEmptyString('total_25_54');

        $validator
            ->numeric('total_more_55')
            ->allowEmptyString('total_more_55');

        $validator
            ->numeric('total_minority')
            ->allowEmptyString('total_minority');

        $validator
            ->numeric('total_disabled')
            ->allowEmptyString('total_disabled');

        $validator
            ->numeric('expost_total_employees')
            ->allowEmptyString('expost_total_employees');

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
        $rules->add($rules->existsIn(['report_id'], 'Reports'), ['errorField' => 'report_id']);
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
