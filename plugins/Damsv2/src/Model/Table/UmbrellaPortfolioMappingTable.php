<?php
declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UmbrellaPortfolioMapping Model
 *
 * @property \App\Model\Table\UmbrellaPortfolioTable&\Cake\ORM\Association\BelongsTo $UmbrellaPortfolio
 * @property \App\Model\Table\PortfolioTable&\Cake\ORM\Association\BelongsTo $Portfolio
 *
 * @method \App\Model\Entity\UmbrellaPortfolioMapping newEmptyEntity()
 * @method \App\Model\Entity\UmbrellaPortfolioMapping newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolioMapping[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolioMapping get($primaryKey, $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolioMapping findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolioMapping patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolioMapping[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolioMapping|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolioMapping saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolioMapping[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolioMapping[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolioMapping[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolioMapping[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class UmbrellaPortfolioMappingTable extends Table
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

        $this->setTable('umbrella_portfolio_mapping');
        $this->setDisplayField('umbrella_portfolio_mapping_id');
        $this->setPrimaryKey('umbrella_portfolio_mapping_id');

        $this->belongsTo('Damsv2.UmbrellaPortfolio', [
            'foreignKey' => 'umbrella_portfolio_id',
            'joinType' => 'INNER',
        ]);
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
            ->integer('umbrella_portfolio_mapping_id')
            ->allowEmptyString('umbrella_portfolio_mapping_id', null, 'create');

        $validator
            ->scalar('portfolio_name')
            ->maxLength('portfolio_name', 255)
            ->allowEmptyString('portfolio_name');

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
        $rules->add($rules->existsIn(['umbrella_portfolio_id'], 'UmbrellaPortfolio'), ['errorField' => 'umbrella_portfolio_id']);
        $rules->add($rules->existsIn(['portfolio_id'], 'Portfolio'), ['errorField' => 'portfolio_id']);

        return $rules;
    }
}
