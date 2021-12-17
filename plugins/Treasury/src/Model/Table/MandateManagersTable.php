<?php
declare(strict_types=1);

namespace Treasury\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MandateManagers Model
 *
 * @method \Treasury\Model\Entity\MandateManager newEmptyEntity()
 * @method \Treasury\Model\Entity\MandateManager newEntity(array $data, array $options = [])
 * @method \Treasury\Model\Entity\MandateManager[] newEntities(array $data, array $options = [])
 * @method \Treasury\Model\Entity\MandateManager get($primaryKey, $options = [])
 * @method \Treasury\Model\Entity\MandateManager findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Treasury\Model\Entity\MandateManager patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Treasury\Model\Entity\MandateManager[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Treasury\Model\Entity\MandateManager|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\MandateManager saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\MandateManager[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\MandateManager[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\MandateManager[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\MandateManager[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class MandateManagersTable extends Table
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

        $this->setTable('mandate_managers');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
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
            ->integer('mandate_ID')
            ->allowEmptyString('mandate_ID');

        $validator
            ->scalar('name')
            ->maxLength('name', 128)
            ->allowEmptyString('name');

        $validator
            ->email('email')
            ->allowEmptyString('email');

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
