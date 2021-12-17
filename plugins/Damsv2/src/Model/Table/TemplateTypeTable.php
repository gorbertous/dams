<?php
declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TemplateType Model
 *
 * @property \App\Model\Table\RulesTable&\Cake\ORM\Association\HasMany $Rules
 * @property \App\Model\Table\RulesLogHistoryTable&\Cake\ORM\Association\HasMany $RulesLogHistory
 * @property \App\Model\Table\TemplateTable&\Cake\ORM\Association\HasMany $Template
 *
 * @method \App\Model\Entity\TemplateType newEmptyEntity()
 * @method \App\Model\Entity\TemplateType newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\TemplateType[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TemplateType get($primaryKey, $options = [])
 * @method \App\Model\Entity\TemplateType findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\TemplateType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TemplateType[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\TemplateType|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TemplateType saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TemplateType[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\TemplateType[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\TemplateType[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\TemplateType[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TemplateTypeTable extends Table
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

        $this->setTable('template_type');
        $this->setDisplayField('name');
        $this->setPrimaryKey('type_id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Damsv2.Rules', [
            'foreignKey' => 'template_type_id',
        ]);
      
        $this->hasMany('Damsv2.Template', [
            'foreignKey' => 'template_type_id',
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
            ->integer('type_id')
            ->allowEmptyString('type_id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        return $validator;
    }
}
