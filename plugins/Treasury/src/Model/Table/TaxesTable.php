<?php
declare(strict_types=1);

namespace Treasury\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Taxes Model
 *
 * @method \Treasury\Model\Entity\Tax newEmptyEntity()
 * @method \Treasury\Model\Entity\Tax newEntity(array $data, array $options = [])
 * @method \Treasury\Model\Entity\Tax[] newEntities(array $data, array $options = [])
 * @method \Treasury\Model\Entity\Tax get($primaryKey, $options = [])
 * @method \Treasury\Model\Entity\Tax findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Treasury\Model\Entity\Tax patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Treasury\Model\Entity\Tax[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Treasury\Model\Entity\Tax|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\Tax saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\Tax[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Tax[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Tax[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Tax[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TaxesTable extends Table
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

        $this->setTable('taxes');
        $this->setDisplayField('tax_ID');
        $this->setPrimaryKey('tax_ID');

        $this->addBehavior('Timestamp');
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
            ->integer('tax_ID')
            ->allowEmptyString('tax_ID', null, 'create');

        $validator
            ->integer('mandate_ID')
            ->allowEmptyString('mandate_ID');

        $validator
            ->integer('cpty_ID')
            ->allowEmptyString('cpty_ID');

        $validator
            ->decimal('tax_rate')
            ->allowEmptyString('tax_rate');

        return $validator;
    }

    /**
     * Returns the database connection name to use by default.
     *
     * @return string
     */
    public static function defaultConnectionName(): string
    {
        return 'treasury';
    }
}
