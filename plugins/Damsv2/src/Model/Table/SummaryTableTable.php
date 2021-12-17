<?php
declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SummaryTable Model
 *
 * @method \App\Model\Entity\SummaryTable newEmptyEntity()
 * @method \App\Model\Entity\SummaryTable newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\SummaryTable[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SummaryTable get($primaryKey, $options = [])
 * @method \App\Model\Entity\SummaryTable findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\SummaryTable patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SummaryTable[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\SummaryTable|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SummaryTable saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SummaryTable[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SummaryTable[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\SummaryTable[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SummaryTable[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class SummaryTableTable extends Table
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

        $this->setTable('summary_table');
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
            ->scalar('mandate')
            ->maxLength('mandate', 100)
            ->allowEmptyString('mandate');

        $validator
            ->numeric('total_principal_amount_eur')
            ->allowEmptyString('total_principal_amount_eur');

        $validator
            ->numeric('total_disbursed_amount_eur')
            ->allowEmptyString('total_disbursed_amount_eur');

        $validator
            ->numeric('number_of_loans')
            ->allowEmptyString('number_of_loans');

        $validator
            ->numeric('number_of_supported_SMEs')
            ->allowEmptyString('number_of_supported_SMEs');

        return $validator;
    }

    /**
     * Returns the database connection name to use by default.
     *
     * @return string
     */
    public static function defaultConnectionName(): string
    {
        return 'analytics';
    }
}
