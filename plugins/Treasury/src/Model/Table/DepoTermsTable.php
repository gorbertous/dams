<?php
declare(strict_types=1);

namespace Treasury\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DepoTerms Model
 *
 * @method \Treasury\Model\Entity\DepoTerm newEmptyEntity()
 * @method \Treasury\Model\Entity\DepoTerm newEntity(array $data, array $options = [])
 * @method \Treasury\Model\Entity\DepoTerm[] newEntities(array $data, array $options = [])
 * @method \Treasury\Model\Entity\DepoTerm get($primaryKey, $options = [])
 * @method \Treasury\Model\Entity\DepoTerm findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Treasury\Model\Entity\DepoTerm patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Treasury\Model\Entity\DepoTerm[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Treasury\Model\Entity\DepoTerm|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\DepoTerm saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\DepoTerm[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\DepoTerm[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\DepoTerm[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\DepoTerm[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class DepoTermsTable extends Table
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

        $this->setTable('depo_terms');
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
            ->scalar('value')
            ->maxLength('value', 2)
            ->requirePresence('value', 'create')
            ->notEmptyString('value');

        $validator
            ->scalar('label')
            ->maxLength('label', 16)
            ->requirePresence('label', 'create')
            ->notEmptyString('label');

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
