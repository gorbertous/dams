<?php

declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UmbrellaPortfolio Model
 *
 * @property \App\Model\Table\ProductTable&\Cake\ORM\Association\BelongsTo $Product
 * @property \App\Model\Table\DeletedTable&\Cake\ORM\Association\BelongsToMany $Deleted
 *
 * @method \App\Model\Entity\UmbrellaPortfolio newEmptyEntity()
 * @method \App\Model\Entity\UmbrellaPortfolio newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolio[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolio get($primaryKey, $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolio findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolio patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolio[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolio|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolio saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolio[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolio[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolio[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolio[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class UmbrellaPortfolioTable extends Table
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

        $this->setTable('umbrella_portfolio');
        $this->setDisplayField('umbrella_portfolio_id');
        $this->setPrimaryKey('iqid');

        $this->belongsTo('Damsv2.Product', [
            'foreignKey' => 'product_id',
            'joinType'   => 'INNER',
        ]);
       
     
    }

    public function getByProduct($product_id)
    {
        return $this->find('all', ['condition' => ['product_id' => $product_id], 'order' => 'umbrella_portfolio_name']);
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
                ->integer('umbrella_portfolio_id')
                ->allowEmptyString('umbrella_portfolio_id', null, 'create');

        $validator
                ->scalar('umbrella_portfolio_name')
                ->maxLength('umbrella_portfolio_name', 100)
                ->allowEmptyString('umbrella_portfolio_name');

        $validator
                ->scalar('iqid')
                ->maxLength('iqid', 32)
                ->allowEmptyString('iqid');

        $validator
                ->scalar('splitting_field')
                ->maxLength('splitting_field', 20)
                ->allowEmptyString('splitting_field');

        $validator
                ->scalar('splitting_table')
                ->maxLength('splitting_table', 20)
                ->allowEmptyString('splitting_table');

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
        $rules->add($rules->existsIn(['product_id'], 'Product'), ['errorField' => 'product_id']);

        return $rules;
    }

}
