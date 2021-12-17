<?php
declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ExpiredTransactions Model
 *
 * @property \App\Model\Table\TransactionsTable&\Cake\ORM\Association\BelongsTo $Transactions
 * @property \App\Model\Table\SubtransactionsTable&\Cake\ORM\Association\BelongsTo $Subtransactions
 * @property \App\Model\Table\SmesTable&\Cake\ORM\Association\BelongsTo $Smes
 * @property \App\Model\Table\PortfoliosTable&\Cake\ORM\Association\BelongsTo $Portfolios
 * @property \App\Model\Table\ReportsTable&\Cake\ORM\Association\BelongsTo $Reports
 *
 * @method \App\Model\Entity\ExpiredTransaction newEmptyEntity()
 * @method \App\Model\Entity\ExpiredTransaction newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\ExpiredTransaction[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ExpiredTransaction get($primaryKey, $options = [])
 * @method \App\Model\Entity\ExpiredTransaction findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\ExpiredTransaction patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ExpiredTransaction[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ExpiredTransaction|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ExpiredTransaction saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ExpiredTransaction[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ExpiredTransaction[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\ExpiredTransaction[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ExpiredTransaction[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ExpiredTransactionsTable extends Table
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

        $this->setTable('expired_transactions');
        $this->setDisplayField('expired_id');
        $this->setPrimaryKey('expired_id');

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
            ->integer('expired_id')
            ->allowEmptyString('expired_id', null, 'create')
            ->add('expired_id', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->date('repayment_date')
            ->allowEmptyDate('repayment_date');

        $validator
            ->integer('nbr_employees_expired')
            ->allowEmptyString('nbr_employees_expired');

        $validator
            ->date('sale_date')
            ->allowEmptyDate('sale_date');

        $validator
            ->numeric('sale_price')
            ->allowEmptyString('sale_price');

        $validator
            ->numeric('sale_price_eur')
            ->allowEmptyString('sale_price_eur');

        $validator
            ->numeric('sale_price_curr')
            ->allowEmptyString('sale_price_curr');

        $validator
            ->date('write_off_date')
            ->allowEmptyDate('write_off_date');

        $validator
            ->numeric('write_off')
            ->allowEmptyString('write_off');

        $validator
            ->numeric('write_off_eur')
            ->allowEmptyString('write_off_eur');

        $validator
            ->numeric('write_off_curr')
            ->allowEmptyString('write_off_curr');

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
        $rules->add($rules->isUnique(['expired_id']), ['errorField' => 'expired_id']);
        $rules->add($rules->existsIn(['transaction_id'], 'Transactions'), ['errorField' => 'transaction_id']);
        $rules->add($rules->existsIn(['subtransaction_id'], 'Subtransactions'), ['errorField' => 'subtransaction_id']);
        $rules->add($rules->existsIn(['sme_id'], 'Smes'), ['errorField' => 'sme_id']);
        $rules->add($rules->existsIn(['portfolio_id'], 'Portfolios'), ['errorField' => 'portfolio_id']);
        $rules->add($rules->existsIn(['report_id'], 'Reports'), ['errorField' => 'report_id']);

        return $rules;
    }
}
