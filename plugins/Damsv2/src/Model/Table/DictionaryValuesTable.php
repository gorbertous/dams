<?php
declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DictionaryValues Model
 *
 * @property \App\Model\Table\DictionaryTable&\Cake\ORM\Association\BelongsTo $Dictionary
 *
 * @method \App\Model\Entity\DictionaryValue newEmptyEntity()
 * @method \App\Model\Entity\DictionaryValue newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\DictionaryValue[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DictionaryValue get($primaryKey, $options = [])
 * @method \App\Model\Entity\DictionaryValue findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\DictionaryValue patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DictionaryValue[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\DictionaryValue|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DictionaryValue saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DictionaryValue[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DictionaryValue[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\DictionaryValue[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DictionaryValue[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DictionaryValuesTable extends Table
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

        $this->setTable('dictionary_values');
        $this->setDisplayField('dicoval_id');
        $this->setPrimaryKey('dicoval_id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Damsv2.Dictionary', [
            'foreignKey' => 'dictionary_id',
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
            ->integer('dicoval_id')
            ->allowEmptyString('dicoval_id', null, 'create');

        $validator
            ->scalar('code')
            ->maxLength('code', 250)
            ->allowEmptyString('code');

        $validator
            ->scalar('translation')
            ->maxLength('translation', 250)
            ->allowEmptyString('translation');

        $validator
            ->scalar('label')
            ->maxLength('label', 400)
            ->allowEmptyString('label');

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
        $rules->add($rules->existsIn(['dictionary_id'], 'Dictionary'), ['errorField' => 'dictionary_id']);

        return $rules;
    }
}
