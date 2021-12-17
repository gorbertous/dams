<?php
declare(strict_types=1);

namespace Dsr\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DicoValues Model
 *
 * @property \Dsr\Model\Table\DictionariesTable&\Cake\ORM\Association\BelongsTo $Dictionaries
 *
 * @method \Dsr\Model\Entity\DicoValue newEmptyEntity()
 * @method \Dsr\Model\Entity\DicoValue newEntity(array $data, array $options = [])
 * @method \Dsr\Model\Entity\DicoValue[] newEntities(array $data, array $options = [])
 * @method \Dsr\Model\Entity\DicoValue get($primaryKey, $options = [])
 * @method \Dsr\Model\Entity\DicoValue findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Dsr\Model\Entity\DicoValue patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Dsr\Model\Entity\DicoValue[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Dsr\Model\Entity\DicoValue|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Dsr\Model\Entity\DicoValue saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Dsr\Model\Entity\DicoValue[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Dsr\Model\Entity\DicoValue[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Dsr\Model\Entity\DicoValue[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Dsr\Model\Entity\DicoValue[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DicoValuesTable extends Table
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

        $this->setTable('dico_values');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Dsr.Dictionaries', [
            'foreignKey' => 'dictionary_id',
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
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('code')
            ->maxLength('code', 100)
            ->requirePresence('code', 'create')
            ->notEmptyString('code');

        $validator
            ->scalar('label')
            ->maxLength('label', 100)
            ->requirePresence('label', 'create')
            ->notEmptyString('label');

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
        $rules->add($rules->existsIn(['dictionary_id'], 'Dictionaries'), ['errorField' => 'dictionary_id']);

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
