<?php
declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MappingTable Model
 *
 * @property \App\Model\Table\TemplatesTable&\Cake\ORM\Association\BelongsTo $Templates
 *
 * @method \App\Model\Entity\MappingTable newEmptyEntity()
 * @method \App\Model\Entity\MappingTable newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\MappingTable[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MappingTable get($primaryKey, $options = [])
 * @method \App\Model\Entity\MappingTable findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\MappingTable patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MappingTable[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\MappingTable|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MappingTable saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MappingTable[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MappingTable[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\MappingTable[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MappingTable[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MappingTableTable extends Table
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

        $this->setTable('mapping_table');
        $this->setDisplayField('name');
        $this->setPrimaryKey('table_id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Damsv2.Template', [
            'foreignKey' => 'template_id',
            'joinType' => 'INNER',
        ]);
        
         $this->hasMany('Damsv2.MappingColumn', [
            'foreignKey' => 'table_id',
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
            ->integer('table_id')
            ->allowEmptyString('table_id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 300)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('table_name')
            ->maxLength('table_name', 45)
            ->requirePresence('table_name', 'create')
            ->notEmptyString('table_name');

        $validator
            ->scalar('sheet_name')
            ->maxLength('sheet_name', 45)
            ->allowEmptyString('sheet_name');

        $validator
            ->integer('loading_order')
            ->allowEmptyString('loading_order');

        $validator
            ->integer('is_cf')
            ->notEmptyString('is_cf');

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
        $rules->add($rules->existsIn(['template_id'], 'Templates'), ['errorField' => 'template_id']);

        return $rules;
    }
}
