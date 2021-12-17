<?php
declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FixedRate Model
 *
 * @property \App\Model\Table\PortfoliosTable&\Cake\ORM\Association\BelongsTo $Portfolios
 *
 * @method \App\Model\Entity\FixedRate newEmptyEntity()
 * @method \App\Model\Entity\FixedRate newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\FixedRate[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FixedRate get($primaryKey, $options = [])
 * @method \App\Model\Entity\FixedRate findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\FixedRate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FixedRate[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\FixedRate|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FixedRate saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FixedRate[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FixedRate[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\FixedRate[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FixedRate[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FixedRateTable extends Table
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

        $this->setTable('fixed_rate');
        $this->setDisplayField('rate_id');
        $this->setPrimaryKey('rate_id');

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
            ->allowEmptyString('rate_id', null, 'create');

        $validator
            ->scalar('currency')
            ->maxLength('currency', 3)
            ->requirePresence('currency', 'create')
            ->notEmptyString('currency');

        $validator
            ->numeric('obs_value')
            ->requirePresence('obs_value', 'create')
            ->notEmptyString('obs_value');

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
}
