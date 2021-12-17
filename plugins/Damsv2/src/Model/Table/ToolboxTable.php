<?php
declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Toolbox Model
 *
 * @method \App\Model\Entity\Toolbox newEmptyEntity()
 * @method \App\Model\Entity\Toolbox newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Toolbox[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Toolbox get($primaryKey, $options = [])
 * @method \App\Model\Entity\Toolbox findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Toolbox patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Toolbox[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Toolbox|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Toolbox saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Toolbox[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Toolbox[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Toolbox[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Toolbox[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class ToolboxTable extends Table
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

        $this->setTable('toolbox');
        $this->setDisplayField('name');
        $this->setPrimaryKey('toolbox_id');
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
            ->integer('toolbox_id')
            ->allowEmptyString('toolbox_id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 50)
            ->allowEmptyString('name');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->scalar('filename')
            ->maxLength('filename', 100)
            ->allowEmptyFile('filename');

        $validator
            ->dateTime('creation_date')
            ->allowEmptyDateTime('creation_date');

        $validator
            ->dateTime('modification_date')
            ->allowEmptyDateTime('modification_date');

        return $validator;
    }
}
