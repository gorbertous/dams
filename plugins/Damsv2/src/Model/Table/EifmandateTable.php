<?php
declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Mandate Model
 *
 * @method \App\Model\Entity\Mandate newEmptyEntity()
 * @method \App\Model\Entity\Mandate newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Mandate[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Mandate get($primaryKey, $options = [])
 * @method \App\Model\Entity\Mandate findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Mandate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Mandate[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Mandate|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Mandate saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Mandate[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Mandate[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Mandate[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Mandate[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EifmandateTable extends Table
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

        $this->setTable('mandate');
        $this->setDisplayField('mandate');
        $this->setPrimaryKey('mandate_id');

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
            ->integer('mandate_id')
            ->allowEmptyString('mandate_id', null, 'create');

        $validator
            ->scalar('mandate_iqid')
            ->maxLength('mandate_iqid', 255)
            ->requirePresence('mandate_iqid', 'create')
            ->notEmptyString('mandate_iqid');

        $validator
            ->scalar('mandate')
            ->maxLength('mandate', 255)
            ->requirePresence('mandate', 'create')
            ->notEmptyString('mandate');

        return $validator;
    }

    /**
     * Returns the database connection name to use by default.
     *
     * @return string
     */
    public static function defaultConnectionName(): string
    {
        return 'eif';
    }
}
