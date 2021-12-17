<?php
declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PortfolioRates Model
 *
 * @property \App\Model\Table\PortfolioTable&\Cake\ORM\Association\BelongsTo $Portfolio
 *
 * @method \App\Model\Entity\PortfolioRates newEmptyEntity()
 * @method \App\Model\Entity\PortfolioRates newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\PortfolioRates[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PortfolioRates get($primaryKey, $options = [])
 * @method \App\Model\Entity\PortfolioRates findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\PortfolioRates patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PortfolioRates[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\PortfolioRates|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PortfolioRates saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PortfolioRates[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\PortfolioRates[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\PortfolioRates[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\PortfolioRates[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PortfolioRatesTable extends Table
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

        $this->setTable('portfolio_rates');
        $this->setDisplayField('portfolio_rates_id');
        $this->setPrimaryKey('portfolio_rates_id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Damsv2.Portfolio', [
            'foreignKey' => 'portfolio_id',
            'joinType' => 'INNER',
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
            ->integer('portfolio_rates_id')
            ->allowEmptyString('portfolio_rates_id', null, 'create');

        $validator
            ->scalar('theme')
            ->maxLength('theme', 255)
            ->allowEmptyString('theme');

        $validator
            ->date('effective_date')
            ->allowEmptyDate('effective_date');

        $validator
            ->date('availability_start')
            ->allowEmptyDate('availability_start');

        $validator
            ->date('availability_end')
            ->allowEmptyDate('availability_end');

        $validator
            ->date('rate_application_date')
            ->allowEmptyDate('rate_application_date');

        $validator
            ->numeric('guarantee_rate')
            ->allowEmptyString('guarantee_rate');

        $validator
            ->numeric('cap_rate')
            ->allowEmptyString('cap_rate');

        $validator
            ->numeric('commitment')
            ->allowEmptyString('commitment');

        $validator
            ->numeric('cap_amount')
            ->allowEmptyString('cap_amount');

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

        return $rules;
    }
}
