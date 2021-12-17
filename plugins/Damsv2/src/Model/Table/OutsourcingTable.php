<?php

declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * OutsourcingLog Model
 *
 * @property \Damsv2\Model\Table\PortfoliosTable&\Cake\ORM\Association\BelongsTo $Portfolios
 * @property \Damsv2\Model\Table\MandatesTable&\Cake\ORM\Association\BelongsTo $Mandates
 *
 * @method \Damsv2\Model\Entity\OutsourcingLog newEmptyEntity()
 * @method \Damsv2\Model\Entity\OutsourcingLog newEntity(array $data, array $options = [])
 * @method \Damsv2\Model\Entity\OutsourcingLog[] newEntities(array $data, array $options = [])
 * @method \Damsv2\Model\Entity\OutsourcingLog get($primaryKey, $options = [])
 * @method \Damsv2\Model\Entity\OutsourcingLog findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Damsv2\Model\Entity\OutsourcingLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Damsv2\Model\Entity\OutsourcingLog[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Damsv2\Model\Entity\OutsourcingLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Damsv2\Model\Entity\OutsourcingLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Damsv2\Model\Entity\OutsourcingLog[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Damsv2\Model\Entity\OutsourcingLog[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Damsv2\Model\Entity\OutsourcingLog[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Damsv2\Model\Entity\OutsourcingLog[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OutsourcingTable extends Table
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

        $this->setTable('outsourcing_log');

        $this->setPrimaryKey('log_id');

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
            ->integer('log_id')
            ->allowEmptyString('log_id', null, 'create');

        $validator
            ->scalar('period_quarter')
            ->maxLength('period_quarter', 3)
            ->requirePresence('period_quarter', 'create')
            ->notEmptyString('period_quarter');

        $validator
            ->integer('period_year')
            ->requirePresence('period_year', 'create')
            ->notEmptyString('period_year');

        $validator
            ->scalar('deal_business_key')
            ->maxLength('deal_business_key', 50)
            ->requirePresence('deal_business_key', 'create')
            ->notEmptyString('deal_business_key');

        $validator
            ->scalar('deal_name')
            ->maxLength('deal_name', 255)
            ->requirePresence('deal_name', 'create')
            ->notEmptyString('deal_name');

        $validator
            ->scalar('portfolio_name')
            ->maxLength('portfolio_name', 100)
            ->requirePresence('portfolio_name', 'create')
            ->notEmptyString('portfolio_name');

        $validator
            ->scalar('mandate')
            ->maxLength('mandate', 255)
            ->requirePresence('mandate', 'create')
            ->notEmptyString('mandate');

        $validator
            ->date('inclusion_deadline')
            ->requirePresence('inclusion_deadline', 'create')
            ->notEmptyDate('inclusion_deadline');

        $validator
            ->scalar('prioritised')
            ->maxLength('prioritised', 50)
            ->allowEmptyString('prioritised');

        $validator
            ->scalar('inclusion_status')
            ->maxLength('inclusion_status', 2)
            ->requirePresence('inclusion_status', 'create')
            ->notEmptyString('inclusion_status');

        $validator
            ->date('email_date')
            ->allowEmptyDate('email_date');

        $validator
            ->scalar('dh_resp')
            ->maxLength('dh_resp', 100)
            ->allowEmptyString('dh_resp');

        $validator
            ->scalar('inclusion_resp')
            ->maxLength('inclusion_resp', 100)
            ->allowEmptyString('inclusion_resp');

        $validator
            ->date('received_date')
            ->allowEmptyDate('received_date');

        $validator
            ->date('first_email_date')
            ->allowEmptyDate('first_email_date');

        $validator
            ->date('inclusion_date')
            ->allowEmptyDate('inclusion_date');

        $validator
            ->scalar('c_sheet')
            ->maxLength('c_sheet', 3)
            ->allowEmptyString('c_sheet');

        $validator
            ->scalar('follow_up')
            ->maxLength('follow_up', 255)
            ->allowEmptyString('follow_up');

        $validator
            ->scalar('comments')
            ->maxLength('comments', 500)
            ->allowEmptyString('comments');

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
