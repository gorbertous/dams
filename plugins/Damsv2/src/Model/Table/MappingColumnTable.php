<?php
declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MappingColumn Model
 *
 * @property \App\Model\Table\TablesTable&\Cake\ORM\Association\BelongsTo $Tables
 * @property \App\Model\Table\DbsTable&\Cake\ORM\Association\BelongsTo $Dbs
 * @property \App\Model\Table\FksTable&\Cake\ORM\Association\BelongsTo $Fks
 * @property \App\Model\Table\DictionariesTable&\Cake\ORM\Association\BelongsTo $Dictionaries
 * @property \App\Model\Table\MappingTableTable&\Cake\ORM\Association\BelongsTo $MappingTable
 *
 * @method \App\Model\Entity\MappingColumn newEmptyEntity()
 * @method \App\Model\Entity\MappingColumn newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\MappingColumn[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MappingColumn get($primaryKey, $options = [])
 * @method \App\Model\Entity\MappingColumn findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\MappingColumn patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MappingColumn[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\MappingColumn|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MappingColumn saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MappingColumn[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MappingColumn[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\MappingColumn[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MappingColumn[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MappingColumnTable extends Table
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

        $this->setTable('mapping_column');
        $this->setDisplayField('column_id');
        $this->setPrimaryKey('column_id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Damsv2.MappingTable', [
            'foreignKey' => 'table_id',
            'joinType' => 'INNER',
        ]);
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
            ->integer('column_id')
            ->allowEmptyString('column_id', null, 'create');

        $validator
            ->scalar('table_field')
            ->maxLength('table_field', 70)
            ->requirePresence('table_field', 'create')
            ->notEmptyString('table_field');

        $validator
            ->scalar('datatype')
            ->maxLength('datatype', 100)
            ->requirePresence('datatype', 'create')
            ->notEmptyString('datatype');

        $validator
            ->integer('exec_order')
            ->requirePresence('exec_order', 'create')
            ->notEmptyString('exec_order');

        $validator
            ->integer('excel_pk')
            ->allowEmptyString('excel_pk');

        $validator
            ->integer('excel_fk')
            ->allowEmptyString('excel_fk');

        $validator
            ->allowEmptyString('excel_column');

        $validator
            ->boolean('is_null')
            ->notEmptyString('is_null');

        $validator
            ->integer('db_pk')
            ->allowEmptyString('db_pk');

        $validator
            ->integer('db_fk')
            ->allowEmptyString('db_fk');

        $validator
            ->integer('db_load_pk')
            ->allowEmptyString('db_load_pk');

        $validator
            ->integer('db_load_fk')
            ->allowEmptyString('db_load_fk');

        $validator
            ->scalar('sql_formula')
            ->maxLength('sql_formula', 250)
            ->allowEmptyString('sql_formula');

        $validator
            ->scalar('macro')
            ->maxLength('macro', 250)
            ->allowEmptyString('macro');

        $validator
            ->integer('is_cf')
            ->allowEmptyString('is_cf');

        $validator
            ->boolean('is_converted')
            ->notEmptyString('is_converted');

        $validator
            ->boolean('in_view')
            ->notEmptyString('in_view');

        $validator
            ->boolean('transcode')
            ->notEmptyString('transcode');

        $validator
            ->integer('not_store')
            ->allowEmptyString('not_store');

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
        $rules->add($rules->existsIn(['table_id'], 'Tables'), ['errorField' => 'table_id']);
        $rules->add($rules->existsIn(['dictionary_id'], 'Dictionaries'), ['errorField' => 'dictionary_id']);

        return $rules;
    }
}
