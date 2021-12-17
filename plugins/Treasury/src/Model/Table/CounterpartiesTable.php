<?php
declare(strict_types=1);

namespace Treasury\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Counterparties Model
 *
 * @method \Treasury\Model\Entity\Counterparty newEmptyEntity()
 * @method \Treasury\Model\Entity\Counterparty newEntity(array $data, array $options = [])
 * @method \Treasury\Model\Entity\Counterparty[] newEntities(array $data, array $options = [])
 * @method \Treasury\Model\Entity\Counterparty get($primaryKey, $options = [])
 * @method \Treasury\Model\Entity\Counterparty findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Treasury\Model\Entity\Counterparty patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Treasury\Model\Entity\Counterparty[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Treasury\Model\Entity\Counterparty|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\Counterparty saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\Counterparty[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Counterparty[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Counterparty[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Counterparty[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CounterpartiesTable extends Table
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

        $this->setTable('counterparties');
        $this->setDisplayField('cpty_ID');
        $this->setPrimaryKey('cpty_ID');

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
            ->integer('cpty_ID')
            ->allowEmptyString('cpty_ID', null, 'create');

        $validator
            ->scalar('cpty_name')
            ->maxLength('cpty_name', 100)
            ->allowEmptyString('cpty_name');

        $validator
            ->scalar('cpty_code')
            ->maxLength('cpty_code', 10)
            ->allowEmptyString('cpty_code');

        $validator
            ->scalar('cpty_address')
            ->maxLength('cpty_address', 100)
            ->allowEmptyString('cpty_address');

        $validator
            ->scalar('cpty_city')
            ->maxLength('cpty_city', 30)
            ->allowEmptyString('cpty_city');

        $validator
            ->scalar('cpty_country')
            ->maxLength('cpty_country', 20)
            ->allowEmptyString('cpty_country');

        $validator
            ->scalar('cpty_zipcode')
            ->maxLength('cpty_zipcode', 10)
            ->allowEmptyString('cpty_zipcode');

        $validator
            ->integer('automatic_fixing')
            ->allowEmptyString('automatic_fixing');

        $validator
            ->scalar('capitalisation_frequency')
            ->maxLength('capitalisation_frequency', 50)
            ->allowEmptyString('capitalisation_frequency');

        $validator
            ->scalar('cpty_bic')
            ->maxLength('cpty_bic', 11)
            ->allowEmptyString('cpty_bic');

        $validator
            ->scalar('pirat_number')
            ->maxLength('pirat_number', 128)
            ->allowEmptyString('pirat_number');

        $validator
            ->scalar('eu_central_bank')
            ->maxLength('eu_central_bank', 1)
            ->allowEmptyString('eu_central_bank');

        $validator
            ->scalar('contact_person1')
            ->maxLength('contact_person1', 50)
            ->allowEmptyString('contact_person1');

        $validator
            ->scalar('contact_person2')
            ->maxLength('contact_person2', 50)
            ->allowEmptyString('contact_person2');

        $validator
            ->scalar('tel1')
            ->maxLength('tel1', 50)
            ->allowEmptyString('tel1');

        $validator
            ->scalar('tel2')
            ->maxLength('tel2', 50)
            ->allowEmptyString('tel2');

        $validator
            ->scalar('fax1')
            ->maxLength('fax1', 50)
            ->allowEmptyString('fax1');

        $validator
            ->scalar('fax2')
            ->maxLength('fax2', 50)
            ->allowEmptyString('fax2');

        $validator
            ->scalar('email1')
            ->maxLength('email1', 200)
            ->allowEmptyString('email1');

        $validator
            ->scalar('email2')
            ->maxLength('email2', 200)
            ->allowEmptyString('email2');

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
