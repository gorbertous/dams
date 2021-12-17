<?php
declare(strict_types=1);

namespace Treasury\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Banks Model
 *
 * @method \Treasury\Model\Entity\Bank newEmptyEntity()
 * @method \Treasury\Model\Entity\Bank newEntity(array $data, array $options = [])
 * @method \Treasury\Model\Entity\Bank[] newEntities(array $data, array $options = [])
 * @method \Treasury\Model\Entity\Bank get($primaryKey, $options = [])
 * @method \Treasury\Model\Entity\Bank findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Treasury\Model\Entity\Bank patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Treasury\Model\Entity\Bank[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Treasury\Model\Entity\Bank|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\Bank saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\Bank[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Bank[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Bank[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Bank[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BanksTable extends Table
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

        $this->setTable('banks');
        $this->setDisplayField('BIC');
        $this->setPrimaryKey('BIC');

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
            ->scalar('BIC')
            ->maxLength('BIC', 11)
            ->allowEmptyString('BIC', null, 'create');

        $validator
            ->scalar('bank_name')
            ->maxLength('bank_name', 100)
            ->allowEmptyString('bank_name');

        $validator
            ->scalar('short_name')
            ->maxLength('short_name', 10)
            ->allowEmptyString('short_name');

        $validator
            ->scalar('address')
            ->maxLength('address', 100)
            ->allowEmptyString('address');

        $validator
            ->scalar('city')
            ->maxLength('city', 30)
            ->allowEmptyString('city');

        $validator
            ->scalar('country')
            ->maxLength('country', 20)
            ->allowEmptyString('country');

        $validator
            ->scalar('zipcode')
            ->maxLength('zipcode', 10)
            ->allowEmptyString('zipcode');

        $validator
            ->scalar('contact_person')
            ->maxLength('contact_person', 50)
            ->allowEmptyString('contact_person');

        $validator
            ->email('email')
            ->allowEmptyString('email');

        $validator
            ->scalar('tel')
            ->maxLength('tel', 50)
            ->allowEmptyString('tel');

        $validator
            ->scalar('fax')
            ->maxLength('fax', 50)
            ->allowEmptyString('fax');

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
