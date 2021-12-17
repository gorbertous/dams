<?php

declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Product Model
 *
 * @method \App\Model\Entity\Product newEmptyEntity()
 * @method \App\Model\Entity\Product newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Product[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Product get($primaryKey, $options = [])
 * @method \App\Model\Entity\Product findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Product patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Product[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Product|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Product saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Product[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Product[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Product[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Product[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ProductTable extends Table
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

        $this->setTable('product');
        $this->setDisplayField('name');
        $this->setPrimaryKey('product_id');

        $this->addBehavior('Timestamp');
    }

    public function getPeriodsByProduct($product_id)
    {
        $period = $this->find('all', [
                    'conditions' => ['Product.product_id' => $product_id],
                    'fields'     => ['Product.reporting_frequency'],
                    'recursive'  => -1
                        ]
                )->first();

        switch ($period->reporting_frequency) {
            case 'Quarterly':
                $periods = ['Q1' => 'Q1', 'Q2' => 'Q2', 'Q3' => 'Q3', 'Q4' => 'Q4'];
                break;
            case 'Semi-annually':
                $periods = ['S1' => 'S1 (From 01/01 to 30/06)', 'S2' => 'S2 (From 01/07 to 31/12)'];
                break;
            case 'Semi-annually (-3 months)':
                $periods = ['S1_spe' => 'S1 (From 01/10 to 31/03)', 'S2_spe' => 'S2 (From 01/04 to 30/09)'];
                break;
            default:
                $periods = [];
                break;
        }

        return $periods;
    }

    // for the dropdowns, do not include MAP and SME GF
    public function getProducts()
    {
        return $this->find('list', [
                    'fields'     => ['Product.product_id', 'Product.name'],
                    'keyField'   => 'product_id',
                    'valueField' => 'name',
                    'order'      => ['Product.name'],
                    'conditions' => ['Product.product_id NOT IN' => [22, 23]], //do not include MAP and SME GF
                ])->toArray();
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
                ->integer('product_id')
                ->allowEmptyString('product_id', null, 'create');

        $validator
                ->scalar('name')
                ->maxLength('name', 500)
                ->requirePresence('name', 'create')
                ->notEmptyString('name');

        $validator
                ->scalar('product_type')
                ->maxLength('product_type', 50)
                ->allowEmptyString('product_type');

        $validator
                ->scalar('capped')
                ->maxLength('capped', 10)
                ->notEmptyString('capped');

        $validator
                ->scalar('fixed_rate')
                ->maxLength('fixed_rate', 10)
                ->notEmptyString('fixed_rate');

        $validator
                ->scalar('reporting_frequency')
                ->maxLength('reporting_frequency', 50)
                ->allowEmptyString('reporting_frequency');

        return $validator;
    }

}
