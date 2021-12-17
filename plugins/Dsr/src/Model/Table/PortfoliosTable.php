<?php
declare(strict_types=1);

namespace Dsr\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Portfolios Model
 *
 * @property \Dsr\Model\Table\ProductsTable&\Cake\ORM\Association\BelongsTo $Products
 * @property \Dsr\Model\Table\LoansTable&\Cake\ORM\Association\HasMany $Loans
 * @property \Dsr\Model\Table\ReportsTable&\Cake\ORM\Association\HasMany $Reports
 *
 * @method \Dsr\Model\Entity\Portfolio newEmptyEntity()
 * @method \Dsr\Model\Entity\Portfolio newEntity(array $data, array $options = [])
 * @method \Dsr\Model\Entity\Portfolio[] newEntities(array $data, array $options = [])
 * @method \Dsr\Model\Entity\Portfolio get($primaryKey, $options = [])
 * @method \Dsr\Model\Entity\Portfolio findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Dsr\Model\Entity\Portfolio patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Dsr\Model\Entity\Portfolio[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Dsr\Model\Entity\Portfolio|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Dsr\Model\Entity\Portfolio saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Dsr\Model\Entity\Portfolio[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Dsr\Model\Entity\Portfolio[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Dsr\Model\Entity\Portfolio[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Dsr\Model\Entity\Portfolio[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PortfoliosTable extends Table
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

        $this->setTable('portfolios');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Dsr.Products', [
            'foreignKey' => 'product_id',
        ]);
        $this->hasMany('Dsr.Loans', [
            'foreignKey' => 'portfolio_id',
        ]);
        $this->hasMany('Dsr.Reports', [
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
            ->scalar('name')
            ->maxLength('name', 100)
            ->allowEmptyString('name');

        $validator
            ->scalar('fi_name')
            ->maxLength('fi_name', 255)
            ->allowEmptyString('fi_name');

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
        $rules->add($rules->existsIn(['product_id'], 'Products'), ['errorField' => 'product_id']);

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
