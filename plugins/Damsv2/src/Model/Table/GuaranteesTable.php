<?php
declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Guarantees Model
 *
 * @property \App\Model\Table\TransactionsTable&\Cake\ORM\Association\BelongsTo $Transactions
 * @property \App\Model\Table\PortfolioTable&\Cake\ORM\Association\BelongsTo $Portfolio
 * @property \App\Model\Table\SmeTable&\Cake\ORM\Association\BelongsTo $Sme
 * @property \App\Model\Table\ReportTable&\Cake\ORM\Association\BelongsTo $Report
 *
 * @method \App\Model\Entity\Guarantee newEmptyEntity()
 * @method \App\Model\Entity\Guarantee newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Guarantee[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Guarantee get($primaryKey, $options = [])
 * @method \App\Model\Entity\Guarantee findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Guarantee patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Guarantee[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Guarantee|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Guarantee saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Guarantee[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Guarantee[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Guarantee[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Guarantee[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GuaranteesTable extends Table
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

        $this->setTable('guarantees');
        $this->setDisplayField('guarantee_id');
        $this->setPrimaryKey('guarantee_id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Damsv2.Transactions', [
            'foreignKey' => 'transaction_id',
        ]);
        $this->belongsTo('Damsv2.Portfolio', [
            'foreignKey' => 'portfolio_id',
        ]);
        $this->belongsTo('Damsv2.Sme', [
            'foreignKey' => 'sme_id',
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
            ->nonNegativeInteger('guarantee_id')
            ->allowEmptyString('guarantee_id', null, 'create');

        $validator
            ->scalar('transaction_reference')
            ->maxLength('transaction_reference', 240)
            ->allowEmptyString('transaction_reference');

        $validator
            ->scalar('fiscal_number')
            ->maxLength('fiscal_number', 240)
            ->allowEmptyString('fiscal_number');

        $validator
            ->numeric('fi_guarantee_amount')
            ->allowEmptyString('fi_guarantee_amount');

        $validator
            ->numeric('fi_guarantee_amount_eur')
            ->allowEmptyString('fi_guarantee_amount_eur');

        $validator
            ->numeric('fi_guarantee_amount_curr')
            ->allowEmptyString('fi_guarantee_amount_curr');

        $validator
            ->numeric('fi_guarantee_rate')
            ->allowEmptyString('fi_guarantee_rate');

        $validator
            ->date('fi_guarantee_signature_date')
            ->allowEmptyDate('fi_guarantee_signature_date');

        $validator
            ->date('fi_guarantee_maturity_date')
            ->allowEmptyDate('fi_guarantee_maturity_date');

        $validator
            ->scalar('subintermediary')
            ->maxLength('subintermediary', 250)
            ->allowEmptyString('subintermediary');

        $validator
            ->scalar('guarantee_comments')
            ->maxLength('guarantee_comments', 4000)
            ->allowEmptyString('guarantee_comments');

        $validator
            ->scalar('error_message')
            ->maxLength('error_message', 1024)
            ->allowEmptyString('error_message');

        $validator
            ->scalar('subintermediary_address')
            ->maxLength('subintermediary_address', 250)
            ->allowEmptyString('subintermediary_address');

        $validator
            ->scalar('subintermediary_postcode')
            ->maxLength('subintermediary_postcode', 250)
            ->allowEmptyString('subintermediary_postcode');

        $validator
            ->scalar('subintermediary_place')
            ->maxLength('subintermediary_place', 250)
            ->allowEmptyString('subintermediary_place');

        $validator
            ->scalar('subintermediary_type')
            ->maxLength('subintermediary_type', 250)
            ->allowEmptyString('subintermediary_type');

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
        $rules->add($rules->existsIn(['transaction_id'], 'Transactions'), ['errorField' => 'transaction_id']);
        $rules->add($rules->existsIn(['portfolio_id'], 'Portfolio'), ['errorField' => 'portfolio_id']);
        $rules->add($rules->existsIn(['sme_id'], 'Sme'), ['errorField' => 'sme_id']);
        $rules->add($rules->existsIn(['report_id'], 'Report'), ['errorField' => 'report_id']);

        return $rules;
    }
}
